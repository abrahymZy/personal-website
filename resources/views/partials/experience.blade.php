<section id="experience" class="section experience">
    <div class="container">
        <div class="section__head" data-reveal>
            <p class="section__eyebrow">
                <span class="section__index" dir="ltr">{{ __('site.experience.index') }}</span>
                <span>{{ __('site.experience.eyebrow') }}</span>
            </p>

            <h2 class="section__title">
                <span class="section__title-line">{{ __('site.experience.title_1') }}</span>
                <span class="section__title-line section__title-line--accent">{{ __('site.experience.title_2') }}</span>
            </h2>
        </div>

        <article class="exp-card" data-reveal>
            <header class="exp-card__head">
                <div>
                    <h3 class="exp-card__role">{{ __('site.experience.role') }}</h3>
                    <p class="exp-card__org">{{ __('site.experience.org') }}</p>
                </div>

                <p class="exp-card__period">
                    <span class="exp-card__badge">{{ __('site.experience.current') }}</span>
                    <span>{{ __('site.experience.period') }}</span>
                </p>
            </header>

            <p class="exp-card__label">{{ __('site.experience.duties_label') }}</p>

            <ul class="exp-card__duties">
                @foreach (__('site.experience.duties') as $duty)
                    <li class="exp-card__duty">{{ $duty }}</li>
                @endforeach
            </ul>
        </article>
    </div>
</section>
