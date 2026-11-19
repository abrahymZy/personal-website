<?php

namespace App\Http\Controllers;

use App\Services\GitHubRepositories;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class ProjectsController extends Controller
{
    public function __invoke(GitHubRepositories $repositories): View
    {
        $meta = config('projects.repos');
        $descriptions = __('site.projects_page.descriptions');

        $repos = collect($repositories->all())
            ->map(function (array $repo) use ($meta, $descriptions): array {
                $tier = $meta[$repo['name']]['tier'] ?? 'lab';

                return [
                    'name' => $repo['name'],
                    'title' => $meta[$repo['name']]['title'] ?? $repo['name'],
                    'tier' => $tier,
                    'description' => $descriptions[$repo['name']] ?? null,
                    'url' => $repo['url'],
                    'demo' => $repo['demo'],
                    'language' => $repo['language'],
                    'topics' => $repo['topics'],
                    'pushed_at' => $repo['pushed_at'],
                ];
            });

        return view('projects', [
            'featured' => $this->group($repos, 'featured'),
            'lab' => $this->group($repos, 'lab'),
            'total' => $repos->count(),
        ]);
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $repos
     * @return Collection<int, array<string, mixed>>
     */
    private function group(Collection $repos, string $tier): Collection
    {
        return $repos->where('tier', $tier)->values();
    }
}
