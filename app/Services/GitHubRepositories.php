<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * يجلب المستودعات العامة من واجهة GitHub مع تخزين مؤقت لتفادي حدود الطلبات.
 */
class GitHubRepositories
{
    private const API = 'https://api.github.com';

    /**
     * المستودعات جاهزة للعرض، مرتّبة حسب آخر تحديث.
     *
     * @return list<array<string, mixed>>
     */
    public function all(): array
    {
        $owner = (string) config('projects.owner');

        if ($owner === '') {
            return [];
        }

        $key = 'github.repos.'.$owner;
        $cached = Cache::get($key);

        if (is_array($cached)) {
            return $cached;
        }

        $repos = $this->fetch($owner);

        // فشل الجلب يُخزَّن لمدة قصيرة فقط حتى تُعاد المحاولة قريباً.
        Cache::put($key, $repos, $repos === []
            ? now()->addMinutes(5)
            : now()->addMinutes((int) config('projects.cache_minutes')));

        return $repos;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function fetch(string $owner): array
    {
        try {
            $response = Http::withHeaders([
                'User-Agent' => config('app.name').'-site',
                'Accept' => 'application/vnd.github+json',
            ])->timeout(15)->get(self::API."/users/{$owner}/repos", [
                'per_page' => 100,
                'sort' => 'pushed',
                'direction' => 'desc',
            ]);
        } catch (Throwable $exception) {
            Log::warning('تعذّر الاتصال بواجهة GitHub.', ['error' => $exception->getMessage()]);

            return [];
        }

        if (! $response->successful()) {
            Log::warning('استجابة GitHub غير ناجحة.', ['status' => $response->status()]);

            return [];
        }

        return collect($response->json() ?? [])
            ->reject(fn (array $repo): bool => (bool) ($repo['fork'] ?? false))
            ->map(fn (array $repo): array => [
                'name' => (string) $repo['name'],
                'url' => (string) $repo['html_url'],
                'demo' => filled($repo['homepage'] ?? null) ? (string) $repo['homepage'] : null,
                'language' => filled($repo['language'] ?? null) ? (string) $repo['language'] : null,
                'topics' => array_slice($repo['topics'] ?? [], 0, 4),
                'pushed_at' => $repo['pushed_at'] ?? null,
            ])
            ->values()
            ->all();
    }
}
