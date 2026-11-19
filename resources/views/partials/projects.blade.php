<section id="projects" class="section projects">
    <div class="container">
        <div class="section__head" data-reveal>
            <p class="section__eyebrow">
                <span class="section__index" dir="ltr">{{ __('site.projects.index') }}</span>
                <span>{{ __('site.projects.eyebrow') }}</span>
            </p>

            <h2 class="section__title">
                <span class="section__title-line">{{ __('site.projects.title_1') }}</span>
                <span class="section__title-line section__title-line--accent">{{ __('site.projects.title_2') }}</span>
            </h2>

            <p class="section__lead">{{ __('site.projects.lead') }}</p>
        </div>

        <ol class="projects__grid">
            @foreach (__('site.projects.items') as $index => $project)
                @php $isLead = $index === 0; @endphp

                <li class="project-card{{ $isLead ? ' project-card--lead' : '' }}" data-reveal>
                    <div class="project-card__body">
                        <p class="project-card__meta">
                            <span class="project-card__index" dir="ltr">{{ $project['index'] }}</span>
                            <span class="project-card__category">{{ $project['category'] }}</span>
                        </p>

                        <h3 class="project-card__title" dir="ltr">{{ $project['title'] }}</h3>
                        <p class="project-card__text">{{ $project['text'] }}</p>

                        <ul class="project-card__stack">
                            @foreach ($project['stack'] as $technology)
                                <li class="chip chip--tech" dir="ltr">{{ $technology }}</li>
                            @endforeach
                        </ul>

                        <p class="project-card__status">{{ $project['status'] }}</p>
                    </div>

                    @if ($isLead)
                        <div class="project-card__visual">
                            <svg class="project-card__art"
                                 viewBox="0 0 320 240"
                                 role="img"
                                 aria-label="{{ $project['category'] }}">
                                <defs>
                                    <linearGradient id="project-art" x1="0" y1="0" x2="1" y2="1">
                                        <stop offset="0%" stop-color="var(--accent)" stop-opacity=".22" />
                                        <stop offset="100%" stop-color="var(--accent-2)" stop-opacity=".06" />
                                    </linearGradient>
                                </defs>

                                <rect x="0.5" y="0.5" width="319" height="239" rx="16" fill="url(#project-art)" />
                                <rect x="0.5" y="0.5" width="319" height="239" rx="16" fill="none" stroke="var(--border)" />

                                <g stroke="var(--border)">
                                    <path d="M64 60h96M64 120h96M64 180h96M160 60l96 60-96 60M160 120h96" />
                                </g>

                                <g fill="var(--accent)">
                                    <circle cx="64" cy="60" r="5" />
                                    <circle cx="64" cy="120" r="5" />
                                    <circle cx="64" cy="180" r="5" />
                                </g>

                                <g fill="var(--accent-2)">
                                    <circle cx="160" cy="60" r="5" />
                                    <circle cx="160" cy="120" r="5" />
                                    <circle cx="160" cy="180" r="5" />
                                    <circle cx="256" cy="120" r="5" />
                                </g>
                            </svg>
                        </div>
                    @endif
                </li>
            @endforeach
        </ol>

        <div class="projects__more" data-reveal>
            <h3 class="projects__more-title">{{ __('site.projects.more_title') }}</h3>

            <ul class="projects__more-list">
                @foreach (__('site.projects.more') as $item)
                    <li class="more-project">
                        <span class="more-project__title" dir="ltr">{{ $item['title'] }}</span>
                        <span class="more-project__kind">{{ $item['kind'] }}</span>
                    </li>
                @endforeach
            </ul>
        </div>

        <p class="projects__cta" data-reveal>
            <a class="btn btn--ghost" href="{{ route('projects') }}">
                <span>{{ __('site.projects_page.all_projects') }}</span>
                <span aria-hidden="true">↗</span>
            </a>
        </p>
    </div>
</section>
