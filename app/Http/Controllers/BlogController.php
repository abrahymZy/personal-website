<?php

namespace App\Http\Controllers;

use App\Services\MarkdownPosts;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function __construct(private readonly MarkdownPosts $posts) {}

    public function index(): View
    {
        return view('blog', [
            'posts' => $this->posts->index(app()->getLocale()),
        ]);
    }

    public function show(string $slug): View
    {
        $post = $this->posts->find($slug, app()->getLocale());

        abort_if($post === null, 404);

        return view('blog-post', [
            'post' => $post,
            'locales' => $this->posts->locales($slug),
        ]);
    }
}
