<section id="research" class="section research">
    <div class="container">
        <div class="section__head" data-reveal>
            <p class="section__eyebrow">
                <span class="section__index" dir="ltr">{{ __('site.research.index') }}</span>
                <span>{{ __('site.research.eyebrow') }}</span>
            </p>

            <h2 class="section__title">
                <span class="section__title-line">{{ __('site.research.title_1') }}</span>
                <span class="section__title-line section__title-line--accent">{{ __('site.research.title_2') }}</span>
            </h2>
        </div>

        <div class="research__grid">
            <div class="research__primary" data-reveal>
                <p class="research__label">{{ __('site.research.primary_label') }}</p>
                <h3 class="research__title" dir="ltr">{{ __('site.research.primary_title') }}</h3>

                <p class="research__label">{{ __('site.research.areas_label') }}</p>

                <ul class="research__areas">
                    @foreach (__('site.research.areas') as $area)
                        <li class="chip chip--tech" dir="ltr">{{ $area }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="research__aside">
                <article class="paper" data-reveal>
                    <p class="research__label">{{ __('site.research.paper_label') }}</p>
                    <h3 class="paper__title">{{ __('site.research.paper_title') }}</h3>
                    <p class="paper__text">{{ __('site.research.paper_text') }}</p>
                    <p class="paper__status">{{ __('site.research.paper_status') }}</p>
                </article>

                <div class="goals" data-reveal>
                    <p class="research__label">{{ __('site.research.goals_label') }}</p>

                    <ol class="goals__list">
                        @foreach (__('site.research.goals') as $index => $goal)
                            <li class="goals__item">
                                <span class="goals__index" dir="ltr">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                <span>{{ $goal }}</span>
                            </li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </div>
    </div>
</section>
