<section id="about" class="section about">
    <div class="container">
        <div class="section__head" data-reveal>
            <p class="section__eyebrow">
                <span class="section__index" dir="ltr">{{ __('site.about.index') }}</span>
                <span>{{ __('site.about.eyebrow') }}</span>
            </p>

            <h2 class="section__title">
                <span class="section__title-line">{{ __('site.about.title_1') }}</span>
                <span class="section__title-line section__title-line--accent">{{ __('site.about.title_2') }}</span>
            </h2>
        </div>

        <div class="about__grid">
            <div class="about__body" data-reveal>
                @foreach (__('site.about.body') as $paragraph)
                    <p>{{ $paragraph }}</p>
                @endforeach

                <p class="about__goal">{{ __('site.about.goal') }}</p>
            </div>

            <!-- <div class="about__figure" data-reveal>
                <svg class="blueprint"
                     viewBox="0 0 360 360"
                     role="img"
                     aria-label="{{ __('site.about.visual_alt') }}">
                    <defs>
                        <pattern id="bp-grid" width="30" height="30" patternUnits="userSpaceOnUse">
                            <path d="M30 0H0v30" fill="none" stroke="var(--border)" stroke-width="1" />
                        </pattern>
                        <linearGradient id="bp-sheen" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0%" stop-color="var(--accent)" stop-opacity=".16" />
                            <stop offset="100%" stop-color="var(--accent-2)" stop-opacity=".05" />
                        </linearGradient>
                    </defs>

                    <rect x="0.5" y="0.5" width="359" height="359" rx="18" fill="var(--surface)" />
                    <rect x="0.5" y="0.5" width="359" height="359" rx="18" fill="url(#bp-grid)" opacity=".5" />
                    <rect x="0.5" y="0.5" width="359" height="359" rx="18" fill="none" stroke="var(--border)" />

                    <circle cx="180" cy="180" r="118" fill="url(#bp-sheen)" stroke="var(--border)" />
                    <circle cx="180" cy="180" r="92" fill="none"
                            stroke="var(--accent)" stroke-opacity=".45"
                            stroke-dasharray="6 10" stroke-width="1.5" />

                    <g stroke="var(--border)" stroke-dasharray="4 7">
                        <path d="M180 30v300M30 180h300" />
                    </g>

                    <polygon points="180,62 283,120 283,240 180,298 77,240 77,120"
                             fill="none" stroke="var(--accent)" stroke-opacity=".55" stroke-width="2" />

                    <g fill="var(--accent)">
                        <circle cx="180" cy="62" r="4.5" />
                        <circle cx="283" cy="120" r="4.5" />
                        <circle cx="283" cy="240" r="4.5" />
                        <circle cx="180" cy="298" r="4.5" />
                        <circle cx="77" cy="240" r="4.5" />
                        <circle cx="77" cy="120" r="4.5" />
                    </g>

                    <circle cx="180" cy="180" r="20" fill="none" stroke="var(--accent-2)" stroke-opacity=".5" />
                    <circle cx="180" cy="180" r="9" fill="var(--accent-2)" />

                    <g stroke="var(--accent-3)" stroke-width="3" stroke-linecap="round">
                        <path d="M300 96v30" />
                        <path d="M300 54v22" />
                    </g>

                    <g stroke="var(--muted)" stroke-opacity=".45">
                        <path d="M46 330h268" />
                        <path d="M46 324v12M314 324v12" />
                    </g>

                    <text x="196" y="176" fill="var(--muted)" font-size="9" font-family="monospace">r 92</text>
                    <text x="52" y="322" fill="var(--muted)" font-size="9" font-family="monospace">248</text>
                </svg>
            </div> -->
        </div>

        <ul class="about__cards">
            @foreach (__('site.about.cards') as $card)
                <li class="about-card" data-reveal>
                    <h3 class="about-card__title">{{ $card['title'] }}</h3>
                    <p class="about-card__text">{{ $card['text'] }}</p>

                    @if (! empty($card['score']))
                        <div class="about-card__rating">
                            <div class="about-card__rating-head">
                                <span class="about-card__score" dir="ltr">{{ $card['score'] }}</span>
                                @if (! empty($card['note']))
                                    <span class="about-card__note">{{ $card['note'] }}</span>
                                @endif
                            </div>
                            <div class="about-card__bar" role="progressbar"
                                 aria-valuenow="{{ $card['percentage'] }}"
                                 aria-valuemin="0"
                                 aria-valuemax="100"
                                 aria-label="{{ $card['title'] }}">
                                <div class="about-card__fill" style="--pct: {{ $card['percentage'] }}%;"></div>
                            </div>
                        </div>
                    @endif
                </li>
            @endforeach
        </ul>

        <div class="journey">
            <h3 class="journey__heading">{{ __('site.about.journey_title') }}</h3>

            <ol class="journey__track">
                @foreach (__('site.about.journey') as $index => $step)
                    <li class="journey__step" data-reveal>
                        <span class="journey__dot" dir="ltr">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <h4 class="journey__title">{{ $step['title'] }}</h4>
                        <p class="journey__text">{{ $step['text'] }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </div>
</section>
