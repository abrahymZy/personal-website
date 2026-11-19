@php
    $date = $post['date']->locale(app()->getLocale());
@endphp

<li class="post-card" data-reveal>
    <div class="post-card__head">
        <time class="post-card__date" dir="ltr" datetime="{{ $post['date']->toDateString() }}">
            {{ $date->isoFormat('D MMMM YYYY') }}
        </time>

        <span class="post-card__time">
            {{ __('site.blog.reading_time', ['minutes' => $post['reading_time']]) }}
        </span>
    </div>

    <h2 class="post-card__title">
        <a class="post-card__link" href="{{ route('blog.show', $post['slug']) }}">{{ $post['title'] }}</a>
    </h2>

    @if ($post['summary'])
        <p class="post-card__text">{{ $post['summary'] }}</p>
    @endif

    @if (! empty($post['tags']))
        <ul class="post-card__tags">
            @foreach ($post['tags'] as $tag)
                <li class="chip chip--tech">{{ $tag }}</li>
            @endforeach
        </ul>
    @endif

    <p class="post-card__more">
        <span>{{ __('site.blog.read_more') }}</span>
        <span class="post-card__arrow" aria-hidden="true">&#8594;</span>
    </p>
</li>
