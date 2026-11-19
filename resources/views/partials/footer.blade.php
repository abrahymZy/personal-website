<footer class="footer">
    <div class="container footer__inner">
        <div class="footer__brand">
            <span class="footer__logo" dir="ltr" lang="en">{{ __('site.brand') }}</span>
            <p class="footer__tagline">{{ __('site.footer.tagline') }}</p>
        </div>

        <nav class="footer__nav" aria-label="{{ __('site.a11y.primary_nav') }}">
            <ul class="footer__list">
                @foreach (__('site.nav') as $id => $label)
                    <li>
                        <a class="footer__link"
                           href="{{ request()->routeIs('home') ? '#'.$id : route('home').'#'.$id }}">{{ $label }}</a>
                    </li>
                @endforeach

                <li>
                    <a class="footer__link" href="{{ route('projects') }}">{{ __('site.projects_page.all_projects') }}</a>
                </li>

                <li>
                    <a class="footer__link" href="{{ route('blog.index') }}">{{ __('site.blog.nav_label') }}</a>
                </li>

                <li>
                    <a class="footer__link" href="{{ auth()->check() ? route('admin.posts.index') : route('admin.login') }}">
                        {{ auth()->check() ? __('site.admin.nav_label') : __('site.blog.admin_login') }}
                    </a>
                </li>
            </ul>
        </nav>

        <p class="footer__rights">
            <span dir="ltr">© {{ date('Y') }} {{ __('site.brand') }}</span>
            — {{ __('site.footer.rights') }}
        </p>
    </div>
</footer>
