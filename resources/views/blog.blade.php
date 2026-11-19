@extends('layouts.app')

@section('title', __('site.blog.meta_title'))
@section('description', __('site.blog.meta_description'))

@section('content')
    <section class="section page-head">
        <div class="container">
            <div class="section__head" data-reveal>
                <p class="section__eyebrow">
                    <span class="section__index" dir="ltr">{{ __('site.blog.index') }}</span>
                    <span>{{ __('site.blog.eyebrow') }}</span>
                </p>

                <h1 class="section__title">
                    <span class="section__title-line">{{ __('site.blog.title_1') }}</span>
                    <span class="section__title-line section__title-line--accent">{{ __('site.blog.title_2') }}</span>
                </h1>

                <p class="section__lead">{{ __('site.blog.lead') }}</p>

                @if ($posts->isNotEmpty())
                    <p class="page-head__count">
                        {{ trans_choice('site.blog.count', $posts->count(), ['count' => $posts->count()]) }}
                    </p>
                @endif

                {{-- زر رفع المقالات وطريقة دخول الأدمن لإدارة المدونة --}}
                <div class="blog-head-actions">
                    @auth
                        <a href="{{ route('admin.posts.create') }}" class="btn btn--primary">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" y1="3" x2="12" y2="15"></line>
                            </svg>
                            <span>{{ __('site.blog.upload_post') }}</span>
                        </a>

                        <a href="{{ route('admin.posts.index') }}" class="btn btn--ghost">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M12 20h9"></path>
                                <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                            </svg>
                            <span>{{ __('site.blog.manage_posts') }}</span>
                        </a>

                        <form method="POST" action="{{ route('admin.logout') }}" style="display: inline-flex;">
                            @csrf
                            <button type="submit" class="btn btn--ghost" title="{{ __('site.admin.logout') }}">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                    <polyline points="16 17 21 12 16 7"></polyline>
                                    <line x1="21" y1="12" x2="9" y2="12"></line>
                                </svg>
                                <span>{{ __('site.admin.logout') }}</span>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('admin.posts.create') }}" class="btn btn--primary">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" y1="3" x2="12" y2="15"></line>
                            </svg>
                            <span>{{ __('site.blog.upload_post') }}</span>
                        </a>

                        <a href="{{ route('admin.login') }}" class="btn btn--ghost" title="{{ __('site.blog.admin_login') }}">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                            <span>{{ __('site.blog.admin_login') }}</span>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    <section class="section page-group">
        <div class="container">
            @if ($posts->isEmpty())
                <p class="repo-empty" data-reveal>{{ __('site.blog.empty') }}</p>
            @else
                <ol class="post-grid">
                    @foreach ($posts as $post)
                        @include('partials.post-card', ['post' => $post])
                    @endforeach
                </ol>
            @endif
        </div>
    </section>
@endsection
