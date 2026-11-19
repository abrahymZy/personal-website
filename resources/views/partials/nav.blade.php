@php
    $navItems = [
        ['id' => 'home', 'label' => __('site.nav.home')],
        ['id' => 'about', 'label' => __('site.nav.about')],
        ['id' => 'expertise', 'label' => __('site.nav.expertise')],
        ['id' => 'projects', 'label' => __('site.nav.projects')],
        ['id' => 'research', 'label' => __('site.nav.research')],
        ['id' => 'experience', 'label' => __('site.nav.experience')],
        ['id' => 'education', 'label' => __('site.nav.education')],
        ['id' => 'contact', 'label' => __('site.nav.contact')],
    ];

    $onHome = request()->routeIs('home');
    $homeAnchor = fn (string $id): string => $onHome ? '#'.$id : route('home').'#'.$id;

    $otherLocale = app()->getLocale() === 'ar' ? 'en' : 'ar';
@endphp

<header class="header" data-header>
    <div class="container header__inner">
        <a class="brand" href="{{ $homeAnchor('home') }}" dir="ltr" lang="en">{{ __('site.brand') }}</a>

        <nav id="primary-nav" class="nav" aria-label="{{ __('site.a11y.primary_nav') }}">
            <div class="nav__top">
                <span class="nav__brand" dir="ltr" lang="en">{{ __('site.brand') }}</span>

                <button type="button"
                        class="tool"
                        data-menu-toggle
                        data-label-close="{{ __('site.a11y.close_menu') }}"
                        aria-label="{{ __('site.a11y.close_menu') }}">
                    <span class="tool__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                            <path d="M6 6l12 12M18 6L6 18"></path>
                        </svg>
                    </span>
                </button>
            </div>

            <ul class="nav__list">
                @foreach ($navItems as $index => $item)
                    <li>
                        <a class="nav__link"
                           href="{{ $homeAnchor($item['id']) }}"
                           data-nav-link
                           style="--i: {{ $index }}">
                            <span class="nav__index" dir="ltr">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    </li>
                @endforeach

                <li>
                    <a class="nav__link nav__link--page"
                       href="{{ route('projects') }}"
                       @if (request()->routeIs('projects')) aria-current="page" @endif>{{ __('site.projects_page.all_projects') }}</a>
                </li>

                <li>
                    <a class="nav__link nav__link--page"
                       href="{{ route('blog.index') }}"
                       @if (request()->routeIs('blog.*')) aria-current="page" @endif>{{ __('site.blog.nav_label') }}</a>
                </li>

                @auth
                    <li>
                        <a class="nav__link nav__link--page nav__link--admin"
                           href="{{ route('admin.posts.index') }}"
                           @if (request()->routeIs('admin.*')) aria-current="page" @endif>
                            <span>{{ __('site.admin.nav_label') }}</span>
                        </a>
                    </li>
                @endauth
            </ul>

            <div class="nav__foot">
                <a class="nav__action"
                   href="{{ __('site.contact.github_url') }}"
                   target="_blank"
                   rel="noopener noreferrer">
                    <span class="nav__action-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 .5C5.73.5.9 5.33.9 11.6c0 4.9 3.17 9.06 7.57 10.53.55.1.75-.24.75-.53v-2.05c-3.08.67-3.73-1.3-3.73-1.3-.5-1.28-1.23-1.62-1.23-1.62-1-.69.08-.67.08-.67 1.11.08 1.7 1.14 1.7 1.14.99 1.7 2.6 1.21 3.23.93.1-.72.39-1.21.7-1.49-2.46-.28-5.05-1.23-5.05-5.47 0-1.21.43-2.2 1.14-2.97-.12-.28-.5-1.4.1-2.92 0 0 .93-.3 3.05 1.13a10.5 10.5 0 0 1 5.55 0c2.12-1.43 3.05-1.13 3.05-1.13.6 1.52.22 2.64.11 2.92.71.77 1.14 1.76 1.14 2.97 0 4.25-2.6 5.19-5.07 5.46.4.35.76 1.03.76 2.08v3.08c0 .3.2.64.76.53a11.1 11.1 0 0 0 7.56-10.53C23.1 5.33 18.27.5 12 .5z"></path>
                        </svg>
                    </span>
                    <span>{{ __('site.a11y.github') }}</span>
                </a>

                <a class="nav__action"
                   href="{{ route('lang.switch', $otherLocale) }}"
                   hreflang="{{ $otherLocale }}"
                   lang="{{ $otherLocale }}">
                    <span class="nav__action-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="9"></circle>
                            <path d="M3 12h18M12 3c2.6 2.8 2.6 15.2 0 18M12 3c-2.6 2.8-2.6 15.2 0 18"></path>
                        </svg>
                    </span>
                    <span>{{ __('site.a11y.switch_language') }}</span>
                </a>
            </div>
        </nav>

        <div class="header__tools">
            <a class="tool tool--github"
               href="{{ __('site.contact.github_url') }}"
               target="_blank"
               rel="noopener noreferrer"
               aria-label="{{ __('site.a11y.github') }}"
               title="{{ __('site.a11y.github') }}">
                <span class="tool__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 .5C5.73.5.9 5.33.9 11.6c0 4.9 3.17 9.06 7.57 10.53.55.1.75-.24.75-.53v-2.05c-3.08.67-3.73-1.3-3.73-1.3-.5-1.28-1.23-1.62-1.23-1.62-1-.69.08-.67.08-.67 1.11.08 1.7 1.14 1.7 1.14.99 1.7 2.6 1.21 3.23.93.1-.72.39-1.21.7-1.49-2.46-.28-5.05-1.23-5.05-5.47 0-1.21.43-2.2 1.14-2.97-.12-.28-.5-1.4.1-2.92 0 0 .93-.3 3.05 1.13a10.5 10.5 0 0 1 5.55 0c2.12-1.43 3.05-1.13 3.05-1.13.6 1.52.22 2.64.11 2.92.71.77 1.14 1.76 1.14 2.97 0 4.25-2.6 5.19-5.07 5.46.4.35.76 1.03.76 2.08v3.08c0 .3.2.64.76.53a11.1 11.1 0 0 0 7.56-10.53C23.1 5.33 18.27.5 12 .5z"></path>
                    </svg>
                </span>
            </a>

            <button type="button"
                    class="tool"
                    data-theme-toggle
                    data-label-prefix="{{ __('site.theme.label') }}"
                    data-label-system="{{ __('site.theme.system') }}"
                    data-label-light="{{ __('site.theme.light') }}"
                    data-label-dark="{{ __('site.theme.dark') }}"
                    aria-label="{{ __('site.theme.label') }}"
                    title="{{ __('site.theme.label') }}">
                <span class="tool__icon" data-theme-icon="system" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="12" rx="2"></rect>
                        <path d="M8 20h8M12 16v4"></path>
                    </svg>
                </span>
                <span class="tool__icon" data-theme-icon="light" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="4"></circle>
                        <path d="M12 2v2M12 20v2M2 12h2M20 12h2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M19.1 4.9l-1.4 1.4M6.3 17.7l-1.4 1.4"></path>
                    </svg>
                </span>
                <span class="tool__icon" data-theme-icon="dark" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 14.5A8.5 8.5 0 0 1 9.5 4a8.5 8.5 0 1 0 10.5 10.5z"></path>
                    </svg>
                </span>
            </button>

            <a class="tool tool--lang"
               href="{{ route('lang.switch', $otherLocale) }}"
               hreflang="{{ $otherLocale }}"
               lang="{{ $otherLocale }}"
               aria-label="{{ __('site.a11y.switch_language') }}">
                {{ $otherLocale === 'en' ? 'EN' : 'ع' }}
            </a>

            <a class="tool tool--admin"
               href="{{ auth()->check() ? route('admin.posts.index') : route('admin.login') }}"
               aria-label="{{ auth()->check() ? __('site.admin.nav_label') : __('site.blog.admin_login') }}"
               title="{{ auth()->check() ? __('site.admin.nav_label') : __('site.blog.admin_login') }}">
                <span class="tool__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                </span>
            </a>

            <button type="button"
                    class="tool tool--menu"
                    data-menu-toggle
                    data-label-open="{{ __('site.a11y.open_menu') }}"
                    data-label-close="{{ __('site.a11y.close_menu') }}"
                    aria-expanded="false"
                    aria-controls="primary-nav"
                    aria-label="{{ __('site.a11y.open_menu') }}">
                <span class="tool__icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
                        <path d="M4 7h16M4 12h16M4 17h16"></path>
                    </svg>
                </span>
            </button>
        </div>
    </div>
</header>

<div class="nav-overlay" data-menu-overlay></div>
