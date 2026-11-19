<section id="home" class="hero">
    <div class="container hero__inner">
        <div class="hero__content">
            <p class="hero__eyebrow" data-reveal>{{ __('site.hero.eyebrow') }}</p>

            <h1 class="hero__title" data-reveal>
                <span class="hero__title-line">{{ __('site.hero.title_1') }}</span>
                <span class="hero__title-line hero__title-line--accent">{{ __('site.hero.title_2') }}</span>
            </h1>

            <p class="hero__role" data-reveal>{{ __('site.hero.role') }}</p>

            <p class="hero__lead" data-reveal>{{ __('site.hero.lead') }}</p>

            <div class="hero__actions" data-reveal>
                <a class="btn btn--primary" href="#projects">
                    <span>{{ __('site.hero.cta_primary') }}</span>
                    <span class="btn__glyph" dir="ltr" aria-hidden="true">&#8599;</span>
                </a>
                <a class="btn btn--ghost" href="{{ asset('cv/abrahym-cv.pdf') }}" download>
                    <span>{{ __('site.hero.cta_secondary') }}</span>
                    <span class="btn__glyph" dir="ltr" aria-hidden="true">&#8595;</span>
                </a>
            </div>

            <ul class="hero__chips" data-reveal>
                @foreach (__('site.hero.chips') as $chip)
                    <li class="chip">{{ $chip }}</li>
                @endforeach
            </ul>
        </div>

        <div class="hero__visual" data-reveal>
            <svg class="orb"
                 viewBox="0 0 520 520"
                 role="img"
                 aria-label="{{ __('site.hero.visual_alt') }}">
                <defs>
                    <radialGradient id="orb-glow">
                        <stop offset="0%" stop-color="var(--accent)" stop-opacity=".22" />
                        <stop offset="100%" stop-color="var(--accent)" stop-opacity="0" />
                    </radialGradient>
                    <linearGradient id="orb-core" x1="0" y1="0" x2="1" y2="1">
                        <stop offset="0%" stop-color="var(--accent)" stop-opacity=".95" />
                        <stop offset="100%" stop-color="var(--accent)" stop-opacity=".5" />
                    </linearGradient>
                </defs>

                <circle cx="260" cy="260" r="205" fill="url(#orb-glow)" />

                <g class="orb__ring orb__ring--outer" aria-hidden="true">
                    <circle cx="260" cy="260" r="238" fill="none"
                            stroke="var(--border)" stroke-width="1.5" stroke-dasharray="2 11" />
                </g>

                <g class="orb__ring orb__ring--mid" aria-hidden="true">
                    <circle cx="260" cy="260" r="186" fill="none"
                            stroke="var(--accent)" stroke-opacity=".32" stroke-width="1.5" />
                    <circle cx="260" cy="74" r="6" fill="var(--accent)" />
                    <circle cx="421" cy="353" r="4" fill="var(--accent)" fill-opacity=".6" />
                    <circle cx="99" cy="353" r="4" fill="var(--accent)" fill-opacity=".6" />
                </g>

                <g class="orb__ring orb__ring--inner" aria-hidden="true">
                    <circle cx="260" cy="260" r="126" fill="none"
                            stroke="var(--accent)" stroke-opacity=".5" stroke-width="1.5"
                            stroke-dasharray="180 612" stroke-linecap="round" />
                </g>

                <g class="orb__core" aria-hidden="true">
                    <rect x="198" y="198" width="124" height="124" rx="30"
                          transform="rotate(45 260 260)"
                          fill="url(#orb-core)" />
                    <rect x="182" y="182" width="156" height="156" rx="38"
                          transform="rotate(45 260 260)"
                          fill="none" stroke="var(--accent)" stroke-opacity=".45" stroke-width="1.5" />
                    <circle cx="260" cy="260" r="26" fill="var(--bg)" />
                    <circle cx="260" cy="260" r="26" fill="none"
                            stroke="var(--accent)" stroke-width="1.5" />
                </g>
            </svg>
        </div>
    </div>

    <div class="hero__scroll" aria-hidden="true">
        <span>{{ __('site.hero.scroll') }}</span>
        <span class="hero__scroll-arrow" dir="ltr">&#8595;</span>
    </div>
</section>
