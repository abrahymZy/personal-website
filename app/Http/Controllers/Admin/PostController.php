<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\MarkdownPosts;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    public function __construct(private readonly MarkdownPosts $posts) {}

    public function index(Request $request): View
    {
        $all = $this->posts->allPosts();

        if ($request->filled('locale')) {
            $all = $all->where('locale', $request->query('locale'));
        }

        if ($request->filled('q')) {
            $query = mb_strtolower(trim((string) $request->query('q')));
            $all = $all->filter(function (array $post) use ($query): bool {
                return str_contains(mb_strtolower($post['title']), $query)
                    || str_contains(mb_strtolower($post['slug']), $query)
                    || str_contains(mb_strtolower($post['summary']), $query);
            });
        }

        return view('admin.posts.index', [
            'posts' => $all->values(),
            'currentLocale' => $request->query('locale', ''),
            'searchQuery' => $request->query('q', ''),
            'locales' => MarkdownPosts::SUPPORTED,
        ]);
    }

    public function create(): View
    {
        return view('admin.posts.create', [
            'locales' => MarkdownPosts::SUPPORTED,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        // 1. خيار رفع ملف Markdown مباشر
        if ($request->hasFile('markdown_file')) {
            $request->validate([
                'markdown_file' => ['required', 'file', 'extensions:md,markdown,txt', 'max:5120'],
                'upload_slug' => ['nullable', 'string', 'regex:/^[a-z0-9\-_]+$/i', 'max:120'],
                'upload_locale' => ['nullable', 'in:'.implode(',', MarkdownPosts::SUPPORTED)],
            ], [
                'markdown_file.required' => 'يرجى تحديد ملف Markdown لرفعه.',
                'markdown_file.extensions' => 'يجب أن يكون الملف بصيغة .md أو .markdown.',
                'upload_slug.regex' => 'المفتاح (Slug) يقبل فقط الحروف الإنجليزية والأرقام والشرطات.',
            ]);

            $result = $this->posts->saveUploadedFile(
                $request->file('markdown_file'),
                $request->input('upload_slug'),
                $request->input('upload_locale')
            );

            return redirect()
                ->route('admin.posts.index')
                ->with('status', "تم رفع المقال \"{$result['title']}\" بنجاح!");
        }

        // 2. خيار كتابة المقال يدوياً عبر النموذج
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'regex:/^[a-z0-9\-_]+$/i', 'max:120'],
            'locale' => ['required', 'in:'.implode(',', MarkdownPosts::SUPPORTED)],
            'date' => ['required', 'date'],
            'summary' => ['nullable', 'string', 'max:1000'],
            'tags' => ['nullable', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ], [
            'title.required' => 'عنوان المقال مطلوب.',
            'slug.required' => 'الرابط الدائم (Slug) مطلوب.',
            'slug.regex' => 'الرابط الدائم يقبل فقط الحروف الإنجليزية والأرقام والشرطات.',
            'locale.required' => 'لغة المقال مطلوبة.',
            'date.required' => 'تاريخ النشر مطلوب.',
            'body.required' => 'محتوى المقال مطلوب.',
        ]);

        $tags = array_values(array_filter(array_map('trim', explode(',', (string) ($validated['tags'] ?? '')))));

        $this->posts->savePost(
            key: $validated['slug'],
            locale: $validated['locale'],
            meta: [
                'title' => $validated['title'],
                'date' => $validated['date'],
                'summary' => $validated['summary'] ?? '',
                'tags' => $tags,
            ],
            body: $validated['body']
        );

        return redirect()
            ->route('admin.posts.index')
            ->with('status', "تم نشر المقال \"{$validated['title']}\" بنجاح!");
    }

    public function edit(string $slug, string $locale): View
    {
        $post = $this->posts->findRaw($slug, $locale);

        abort_if($post === null, 404, 'المقال غير موجود');

        return view('admin.posts.edit', [
            'post' => $post,
            'slug' => $slug,
            'locale' => $locale,
            'locales' => MarkdownPosts::SUPPORTED,
        ]);
    }

    public function update(Request $request, string $slug, string $locale): RedirectResponse
    {
        $existing = $this->posts->findRaw($slug, $locale);
        abort_if($existing === null, 404, 'المقال غير موجود');

        // خيار استبدال المحتوى بملف مرفوع
        if ($request->hasFile('markdown_file')) {
            $request->validate([
                'markdown_file' => ['required', 'file', 'extensions:md,markdown,txt', 'max:5120'],
            ]);

            $this->posts->saveUploadedFile(
                $request->file('markdown_file'),
                $slug,
                $locale
            );

            return redirect()
                ->route('admin.posts.index')
                ->with('status', 'تم تحديث المقال عبر الملف المرفوع بنجاح!');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'new_slug' => ['required', 'string', 'regex:/^[a-z0-9\-_]+$/i', 'max:120'],
            'new_locale' => ['required', 'in:'.implode(',', MarkdownPosts::SUPPORTED)],
            'date' => ['required', 'date'],
            'summary' => ['nullable', 'string', 'max:1000'],
            'tags' => ['nullable', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ]);

        $newSlug = $validated['new_slug'];
        $newLocale = $validated['new_locale'];
        $tags = array_values(array_filter(array_map('trim', explode(',', (string) ($validated['tags'] ?? '')))));

        // إذا تغيّر المفتاح أو اللغة، احذف الملف القديم
        if ($newSlug !== $slug || $newLocale !== $locale) {
            $this->posts->deletePost($slug, $locale);
        }

        $this->posts->savePost(
            key: $newSlug,
            locale: $newLocale,
            meta: [
                'title' => $validated['title'],
                'date' => $validated['date'],
                'summary' => $validated['summary'] ?? '',
                'tags' => $tags,
            ],
            body: $validated['body']
        );

        return redirect()
            ->route('admin.posts.index')
            ->with('status', "تم تحديث المقال \"{$validated['title']}\" بنجاح!");
    }

    public function destroy(string $slug, string $locale): RedirectResponse
    {
        $deleted = $this->posts->deletePost($slug, $locale);

        if (! $deleted) {
            return redirect()
                ->route('admin.posts.index')
                ->with('error', 'تعذّر العثور على المقال لحذفه.');
        }

        return redirect()
            ->route('admin.posts.index')
            ->with('status', 'تم حذف المقال بنجاح.');
    }
}
