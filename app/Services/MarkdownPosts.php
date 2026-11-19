<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\Attributes\AttributesExtension;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\DescriptionList\DescriptionListExtension;
use League\CommonMark\Extension\Footnote\FootnoteExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\Extension\Highlight\HighlightExtension;
use League\CommonMark\MarkdownConverter;

/**
 * يقرأ مقالات المدونة من ملفات Markdown داخل resources/content/posts
 * ويفكّ ترويسة الواجهة (front matter) ويحوّل المتن إلى HTML.
 *
 * نظام التسمية: {المفتاح}.{اللغة}.md — مثال: markdown-guide.ar.md
 * نفس المفتاح في اللغتين يعني أن المقالين نسختان لنفس التدوينة.
 */
class MarkdownPosts
{
    /**
     * حقول الترويسة المدعومة: title, date, tags, summary.
     * القيم نصّية بسطر واحد، والمصفوفات تُكتب بين معقوفتين: [php, laravel]
     */
    public const SUPPORTED = ['ar', 'en'];

    private const WORDS_PER_MINUTE = 200;

    private ?MarkdownConverter $converter = null;

    /**
     * مقالات لغة واحدة، الأحدث أولاً، مع بياناتها الوصفية فقط.
     *
     * @return Collection<int, array<string, mixed>>
     */
    public function index(string $locale): Collection
    {
        $files = $this->files();

        // نُخزّن نسخاً مجرّدة (تاريخ نصّي) لأن كائنات Carbon لا تُفكّ من التخزين المؤقت بأمان.
        $posts = Cache::remember(
            'blog.index.'.$locale.'.'.$this->fingerprint($files),
            now()->addHour(),
            function () use ($files, $locale): array {
                $posts = [];

                foreach ($files as $path) {
                    $post = $this->read($path);

                    if ($post === null || $post['locale'] !== $locale) {
                        continue;
                    }

                    unset($post['body']);

                    $posts[] = $post;
                }

                return collect($posts)->sortByDesc('date')->values()->all();
            }
        );

        return collect($posts)->map(fn (array $post): array => $this->hydrate($post))->values();
    }

    /**
     * مقال واحد بلغة محددة، مع متن محوَّل إلى HTML.
     *
     * @return array<string, mixed>|null
     */
    public function find(string $key, string $locale): ?array
    {
        $path = $this->path($key, $locale);

        if ($path === null) {
            return null;
        }

        $post = Cache::remember(
            'blog.post.'.$key.'.'.$locale.'.'.(string) filemtime($path),
            now()->addHour(),
            fn (): ?array => $this->read($path)
        );

        if ($post === null) {
            return null;
        }

        $post = $this->hydrate($post);
        $post['html'] = (string) $this->converter()->convert($post['body']);

        return $post;
    }

    /**
     * يحوّل التاريخ النصّي إلى كائن Carbon بعد قراءته من التخزين المؤقت.
     *
     * @param  array<string, mixed>  $post
     * @return array<string, mixed>
     */
    private function hydrate(array $post): array
    {
        $post['date'] = Carbon::parse($post['date'])->startOfDay();

        return $post;
    }

    /**
     * اللغات المتوفّرة لمفتاح مقال معيّن.
     *
     * @return list<string>
     */
    public function locales(string $key): array
    {
        return array_values(array_filter(
            self::SUPPORTED,
            fn (string $locale): bool => $this->path($key, $locale) !== null
        ));
    }

    /**
     * قائمة بجميع المقالات بكافة اللغات للمشرف.
     *
     * @return Collection<int, array<string, mixed>>
     */
    public function allPosts(): Collection
    {
        $files = $this->files();
        $posts = [];

        foreach ($files as $path) {
            $post = $this->read($path);

            if ($post === null) {
                continue;
            }

            $posts[] = $this->hydrate($post);
        }

        return collect($posts)->sortByDesc('date')->values();
    }

    /**
     * مقال واحد ببياناته الوصفية ومتنه الخام بدون تحويله لـ HTML (مناسب للتعديل).
     *
     * @return array<string, mixed>|null
     */
    public function findRaw(string $key, string $locale): ?array
    {
        $path = $this->path($key, $locale);

        if ($path === null) {
            return null;
        }

        $post = $this->read($path);

        return $post !== null ? $this->hydrate($post) : null;
    }

    /**
     * حفظ مقال (إنشاء أو تعديل) مع ترويسة الواجهة ومتن Markdown.
     *
     * @param  array<string, mixed>  $meta
     */
    public function savePost(string $key, string $locale, array $meta, string $body): string
    {
        $key = preg_replace('/[^a-z0-9\-_]/i', '', $key) ?? '';

        if ($key === '' || ! in_array($locale, self::SUPPORTED, true)) {
            throw new \InvalidArgumentException('مفتاح المقال أو لغته غير صالحة.');
        }

        $dir = resource_path('content/posts');

        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $title = str_replace('"', '\"', (string) ($meta['title'] ?? ''));
        $date = (string) ($meta['date'] ?? now()->toDateString());
        $summary = str_replace('"', '\"', (string) ($meta['summary'] ?? ''));

        $tagsArray = (array) ($meta['tags'] ?? []);
        $cleanTags = array_values(array_filter(array_map('trim', $tagsArray)));
        $tagsStr = '['.implode(', ', $cleanTags).']';

        $frontMatter = "---\n"
            ."title: \"{$title}\"\n"
            ."date: {$date}\n"
            ."tags: {$tagsStr}\n"
            ."summary: \"{$summary}\"\n"
            ."---\n\n";

        $fullContent = $frontMatter.ltrim($body, "\r\n");
        $filePath = $dir.DIRECTORY_SEPARATOR."{$key}.{$locale}.md";

        file_put_contents($filePath, $fullContent);
        $this->clearCache();

        return $filePath;
    }

    /**
     * حفظ مقال مرفوع كملف Markdown مباشر.
     *
     * @return array{key: string, locale: string, title: string}
     */
    public function saveUploadedFile(UploadedFile $file, ?string $customKey = null, ?string $customLocale = null): array
    {
        $clientName = $file->getClientOriginalName();
        $baseName = pathinfo($clientName, PATHINFO_FILENAME);

        $parsedName = $this->splitName($baseName);
        $detectedKey = $parsedName !== null ? $parsedName[0] : $baseName;
        $detectedLocale = $parsedName !== null ? $parsedName[1] : 'ar';

        $key = $customKey !== null && trim($customKey) !== ''
            ? preg_replace('/[^a-z0-9\-_]/i', '', $customKey)
            : preg_replace('/[^a-z0-9\-_]/i', '', $detectedKey);

        $locale = $customLocale !== null && in_array($customLocale, self::SUPPORTED, true)
            ? $customLocale
            : $detectedLocale;

        if ($key === '' || $key === null) {
            $key = 'post-'.time();
        }

        if (! in_array($locale, self::SUPPORTED, true)) {
            $locale = 'ar';
        }

        $rawContent = (string) file_get_contents($file->getRealPath());
        [$meta, $body] = $this->splitFrontMatter($rawContent);

        if (empty($meta['title'])) {
            $meta['title'] = str_replace(['-', '_'], ' ', $key);
        }

        if (empty($meta['date'])) {
            $meta['date'] = now()->toDateString();
        }

        $this->savePost($key, $locale, $meta, $body);

        return [
            'key' => $key,
            'locale' => $locale,
            'title' => (string) $meta['title'],
        ];
    }

    /**
     * حذف ملف المقال.
     */
    public function deletePost(string $key, string $locale): bool
    {
        $path = $this->path($key, $locale);

        if ($path !== null && is_file($path)) {
            $result = unlink($path);
            $this->clearCache();

            return $result;
        }

        return false;
    }

    /**
     * تفريغ الذاكرة المؤقتة الخاصة بالمقالات لتظهر التعديلات فوراً.
     */
    public function clearCache(): void
    {
        clearstatcache();
        Cache::flush();
    }

    /**
     * مسار ملف المقال إن وُجد.
     */
    private function path(string $key, string $locale): ?string
    {
        if (! in_array($locale, self::SUPPORTED, true)) {
            return null;
        }

        // المفتاح يأتي من الرابط، فنمنع أي خروج عن المجلد.
        $key = preg_replace('/[^a-z0-9\-_]/i', '', $key) ?? '';

        if ($key === '') {
            return null;
        }

        $path = resource_path('content/posts/'.$key.'.'.$locale.'.md');

        return is_file($path) ? $path : null;
    }

    /**
     * قراءة ملف واحد وفكّ ترويسته.
     *
     * @return array<string, mixed>|null
     */
    private function read(string $path): ?array
    {
        $parts = $this->splitName(basename($path, '.md'));

        if ($parts === null) {
            return null;
        }

        [$key, $locale] = $parts;
        $raw = (string) file_get_contents($path);
        [$meta, $body] = $this->splitFrontMatter($raw);

        // تقدير زمن القراءة من المتن الخام بعد إزالة رموز Markdown.
        $plain = trim(preg_replace('/\s+/u', ' ', preg_replace('/[`*_>#\[\]()!~|]/u', ' ', $body) ?? '') ?? '');
        $words = $plain === '' ? 0 : count(preg_split('/\s+/u', $plain) ?: []);

        return [
            'key' => $key,
            'locale' => $locale,
            'slug' => $key,
            'title' => (string) ($meta['title'] ?? $key),
            'summary' => (string) ($meta['summary'] ?? ''),
            'tags' => (array) ($meta['tags'] ?? []),
            'date' => Carbon::parse($meta['date'] ?? filemtime($path))->startOfDay()->toDateString(),
            'reading_time' => max(1, (int) ceil($words / self::WORDS_PER_MINUTE)),
            'body' => $body,
        ];
    }

    /**
     * يفصل اسم الملف إلى مفتاح ولغة.
     *
     * @return array{0: string, 1: string}|null
     */
    private function splitName(string $name): ?array
    {
        $dot = strrpos($name, '.');

        if ($dot === false) {
            return null;
        }

        $key = substr($name, 0, $dot);
        $locale = substr($name, $dot + 1);

        if ($key === '' || ! in_array($locale, self::SUPPORTED, true)) {
            return null;
        }

        return [$key, $locale];
    }

    /**
     * يفصل ترويسة الواجهة عن المتن.
     *
     * @return array{0: array<string, mixed>, 1: string}
     */
    private function splitFrontMatter(string $raw): array
    {
        $raw = ltrim($raw, "\xEF\xBB\xBF \t\r\n");

        if (! str_starts_with($raw, '---')) {
            return [[], $raw];
        }

        $end = strpos($raw, "\n---", 3);

        if ($end === false) {
            return [[], $raw];
        }

        $block = substr($raw, 3, $end - 3);
        $body = ltrim(substr($raw, $end + 4), "\r\n");
        $meta = [];

        foreach (preg_split('/\r\n|\r|\n/', $block) ?: [] as $line) {
            $line = trim($line);

            if ($line === '' || str_starts_with($line, '#') || ! str_contains($line, ':')) {
                continue;
            }

            [$name, $value] = explode(':', $line, 2);

            $meta[trim($name)] = $this->castValue(trim($value));
        }

        return [$meta, $body];
    }

    /**
     * يحوّل قيمة نصّية إلى مصفوفة أو نص نظيف.
     */
    private function castValue(string $value): mixed
    {
        if (str_starts_with($value, '[') && str_ends_with($value, ']')) {
            return collect(explode(',', substr($value, 1, -1)))
                ->map(fn (string $item): string => trim($item, " \t\"'"))
                ->filter()
                ->values()
                ->all();
        }

        return trim($value, "\"'");
    }

    /**
     * محوّل Markdown مُهيّأ لدعم أوسع مجموعة من خواص Markdown.
     */
    private function converter(): MarkdownConverter
    {
        if ($this->converter instanceof MarkdownConverter) {
            return $this->converter;
        }

        $environment = new Environment([
            'html_input' => 'allow',
            'allow_unsafe_links' => false,
            'max_nesting_level' => 30,
        ]);

        $environment->addExtension(new CommonMarkCoreExtension);
        $environment->addExtension(new GithubFlavoredMarkdownExtension);
        $environment->addExtension(new FootnoteExtension);
        $environment->addExtension(new DescriptionListExtension);
        $environment->addExtension(new HighlightExtension);
        $environment->addExtension(new AttributesExtension);

        return $this->converter = new MarkdownConverter($environment);
    }

    /**
     * @return list<string>
     */
    private function files(): array
    {
        $pattern = resource_path('content/posts/*.md');
        $files = glob($pattern) ?: [];

        sort($files);

        return $files;
    }

    /**
     * بصمة تتغيّر بتغيّر الملفات، فتُبطَل الذاكرة المؤقتة تلقائياً.
     *
     * @param  list<string>  $files
     */
    private function fingerprint(array $files): string
    {
        if ($files === []) {
            return 'empty';
        }

        return substr(md5(implode('|', array_map(
            fn (string $path): string => basename($path).':'.filemtime($path),
            $files
        ))), 0, 12);
    }
}
