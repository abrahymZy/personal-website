<section id="contact" class="section contact">
    <div class="container">
        <div class="contact__inner" data-reveal>
            <p class="section__eyebrow">
                <span class="section__index" dir="ltr">{{ __('site.contact.index') }}</span>
                <span>{{ __('site.contact.eyebrow') }}</span>
            </p>

            <h2 class="section__title">
                <span class="section__title-line">{{ __('site.contact.title_1') }}</span>
                <span class="section__title-line section__title-line--accent">{{ __('site.contact.title_2') }}</span>
            </h2>

            <p class="contact__lead">{{ __('site.contact.lead') }}</p>

            <div class="contact__actions">
                <a class="btn btn--primary"
                   href="{{ __('site.contact.github_url') }}"
                   target="_blank"
                   rel="noopener noreferrer">
                    <span class="btn__glyph" aria-hidden="true">↗</span>
                    {{ __('site.contact.github_label') }}
                </a>
            </div>
        </div>
    </div>
</section>
