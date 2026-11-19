@php
    $updated = $repo['pushed_at']
        ? \Illuminate\Support\Carbon::parse($repo['pushed_at'])->locale(app()->getLocale())->isoFormat('MMMM YYYY')
        : null;
@endphp

<li class="repo-card" data-reveal>
    <div class="repo-card__head">
        <h3 class="repo-card__title" dir="ltr" lang="en">{{ $repo['title'] }}</h3>

        @if ($repo['language'])
            <span class="repo-card__lang" dir="ltr" lang="en">{{ $repo['language'] }}</span>
        @endif
    </div>

    @if ($repo['description'])
        <p class="repo-card__text">{{ $repo['description'] }}</p>
    @endif

    @if (! empty($repo['topics']))
        <ul class="repo-card__topics">
            @foreach ($repo['topics'] as $topic)
                <li class="chip chip--tech" dir="ltr" lang="en">{{ $topic }}</li>
            @endforeach
        </ul>
    @endif

    @if ($updated)
        <p class="repo-card__meta">
            {{ __('site.projects_page.updated') }}
            <time dir="ltr" datetime="{{ $repo['pushed_at'] }}">{{ $updated }}</time>
        </p>
    @endif

    <div class="repo-card__links">
        <a class="repo-card__link"
           href="{{ $repo['url'] }}"
           target="_blank"
           rel="noopener noreferrer">
            <span>{{ __('site.projects_page.visit') }}</span>
            <span aria-hidden="true">↗</span>
        </a>

        @if ($repo['demo'])
            <a class="repo-card__link repo-card__link--demo"
               href="{{ $repo['demo'] }}"
               target="_blank"
               rel="noopener noreferrer">
                <span>{{ __('site.projects_page.demo') }}</span>
                <span aria-hidden="true">↗</span>
            </a>
        @endif
    </div>
</li>
