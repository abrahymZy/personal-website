<section id="expertise" class="section expertise">
    <div class="container">
        <div class="section__head" data-reveal>
            <p class="section__eyebrow">
                <span class="section__index" dir="ltr">{{ __('site.expertise.index') }}</span>
                <span>{{ __('site.expertise.eyebrow') }}</span>
            </p>

            <h2 class="section__title">
                <span class="section__title-line">{{ __('site.expertise.title_1') }}</span>
                <span class="section__title-line section__title-line--accent">{{ __('site.expertise.title_2') }}</span>
            </h2>

            <p class="section__lead">{{ __('site.expertise.lead') }}</p>
        </div>

        <div class="expertise__grid">
            @foreach (__('site.expertise.domains') as $index => $domain)
                @php
                    $isFeatured = ! empty($domain['featured']);
                    $panelId = 'skill-panel-'.($index + 1);
                @endphp

                <article class="skill-card{{ $isFeatured ? ' skill-card--featured' : '' }}"
                         data-skill-card
                         data-reveal>
                    <h3 class="skill-card__heading">
                        <button type="button"
                                class="skill-card__head"
                                data-skill-toggle
                                aria-expanded="false"
                                aria-controls="{{ $panelId }}">
                            <span class="skill-card__index" dir="ltr">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="skill-card__name">{{ $domain['title'] }}</span>

                            @if ($isFeatured)
                                <span class="skill-card__tag">{{ __('site.expertise.featured_tag') }}</span>
                            @endif

                            <span class="skill-card__chevron" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M6 9l6 6 6-6"></path>
                                </svg>
                            </span>
                        </button>
                    </h3>

                    <p class="skill-card__text">{{ $domain['text'] }}</p>

                    <div class="skill-card__panel" id="{{ $panelId }}" data-skill-panel hidden>
                        <p class="skill-card__label">{{ __('site.expertise.areas_label') }}</p>

                        <ul class="skill-card__chips">
                            @foreach ($domain['interest'] as $technology)
                                <li class="chip chip--tech" dir="ltr">{{ $technology }}</li>
                            @endforeach
                        </ul>

                        @if (! empty($domain['projects']))
                            <p class="skill-card__label">{{ __('site.expertise.projects_label') }}</p>

                            <ul class="skill-card__projects">
                                @foreach ($domain['projects'] as $project)
                                    <li class="skill-card__project">
                                        <span class="skill-card__project-title" dir="ltr">{{ $project['title'] }}</span>
                                        <span class="skill-card__project-note">{{ $project['note'] }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>

        <div class="levels" data-reveal>
            <span class="levels__label">{{ __('site.expertise.levels_label') }}</span>

            <ul class="levels__list">
                @foreach (__('site.expertise.levels') as $level)
                    <li class="level-tag">{{ $level }}</li>
                @endforeach
            </ul>

            <p class="levels__note">{{ __('site.expertise.levels_note') }}</p>
        </div>

        <p class="section__foot" data-reveal>{{ __('site.expertise.foot') }}</p>
    </div>
</section>
