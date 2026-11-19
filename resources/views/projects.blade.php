@extends('layouts.app')

@section('title', __('site.projects_page.meta_title'))
@section('description', __('site.projects_page.meta_description'))

@section('content')
    <section class="section page-head">
        <div class="container">
            <div class="section__head" data-reveal>
                <p class="section__eyebrow">
                    <span class="section__index" dir="ltr">{{ __('site.projects_page.index') }}</span>
                    <span>{{ __('site.projects_page.eyebrow') }}</span>
                </p>

                <h1 class="section__title">
                    <span class="section__title-line">{{ __('site.projects_page.title_1') }}</span>
                    <span class="section__title-line section__title-line--accent">{{ __('site.projects_page.title_2') }}</span>
                </h1>

                <p class="section__lead">{{ __('site.projects_page.lead') }}</p>

                <div class="page-head__actions">
                    <a class="btn btn--primary"
                       href="{{ __('site.contact.github_url') }}"
                       target="_blank"
                       rel="noopener noreferrer">{{ __('site.projects_page.profile_cta') }}</a>

                    <a class="btn btn--ghost" href="{{ route('home') }}">{{ __('site.projects_page.back_home') }}</a>
                </div>

                @if ($total > 0)
                    <p class="page-head__count">{{ __('site.projects_page.count', ['count' => $total]) }}</p>
                @endif
            </div>

            @if ($total === 0)
                <p class="repo-empty" data-reveal>{{ __('site.projects_page.empty') }}</p>
            @endif
        </div>
    </section>

    @if ($featured->isNotEmpty())
        <section class="section page-group" aria-labelledby="repo-group-featured">
            <div class="container">
                <h2 class="repo-group__title" id="repo-group-featured">{{ __('site.projects_page.featured_title') }}</h2>
                <p class="repo-group__lead">{{ __('site.projects_page.featured_lead') }}</p>

                <ol class="repo-grid">
                    @foreach ($featured as $repo)
                        @include('partials.repo-card', ['repo' => $repo])
                    @endforeach
                </ol>
            </div>
        </section>
    @endif

    @if ($lab->isNotEmpty())
        <section class="section page-group" aria-labelledby="repo-group-lab">
            <div class="container">
                <h2 class="repo-group__title" id="repo-group-lab">{{ __('site.projects_page.lab_title') }}</h2>
                <p class="repo-group__lead">{{ __('site.projects_page.lab_lead') }}</p>

                <ol class="repo-grid">
                    @foreach ($lab as $repo)
                        @include('partials.repo-card', ['repo' => $repo])
                    @endforeach
                </ol>
            </div>
        </section>
    @endif
@endsection
