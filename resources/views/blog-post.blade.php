@extends('layouts.app')

@section('title', $post['title'].' — ABRAHYM')
@section('description', $post['summary'] !== '' ? $post['summary'] : __('site.blog.meta_description'))

@php
    $current = app()->getLocale();
    $other = collect($locales)->first(fn (string $locale): bool => $locale !== $current);
@endphp

@section('content')
    <article class="section article">
        <div class="container article__container">
            <header class="article__head" data-reveal>
                <a class="article__back" href="{{ route('blog.index') }}">
                    <span class="article__back-arrow" aria-hidden="true">&#8592;</span>
                    <span>{{ __('site.blog.back') }}</span>
                </a>

                <h1 class="article__title">{{ $post['title'] }}</h1>

                <p class="article__meta">
                    <time dir="ltr" datetime="{{ $post['date']->toDateString() }}">
                        {{ $post['date']->locale($current)->isoFormat('D MMMM YYYY') }}
                    </time>
                    <span aria-hidden="true">·</span>
                    <span>{{ __('site.blog.reading_time', ['minutes' => $post['reading_time']]) }}</span>
                </p>

                @if (! empty($post['tags']))
                    <ul class="article__tags">
                        @foreach ($post['tags'] as $tag)
                            <li class="chip chip--tech">{{ $tag }}</li>
                        @endforeach
                    </ul>
                @endif
            </header>

            <div class="markdown"
                 data-copy-label="{{ __('site.blog.code_copy') }}"
                 data-copy-done="{{ __('site.blog.code_copied') }}">
                {!! $post['html'] !!}
            </div>

            <footer class="article__foot">
                <a class="btn btn--ghost" href="{{ route('blog.index') }}">{{ __('site.blog.back') }}</a>

                @if ($other)
                    <a class="btn btn--primary"
                       href="{{ route('lang.switch', $other) }}"
                       hreflang="{{ $other }}"
                       lang="{{ $other }}">
                        <span>{{ __('site.blog.lang_switch') }}</span>
                        <span class="btn__glyph" aria-hidden="true">&#8644;</span>
                    </a>
                @endif
            </footer>
        </div>
    </article>
@endsection
