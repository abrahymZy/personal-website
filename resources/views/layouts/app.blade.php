<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', __('site.meta.title'))</title>
    <meta name="description" content="@yield('description', __('site.meta.description'))">

    {{-- تحديد المظهر قبل رسم الصفحة لمنع وميض الثيم الخاطئ (FOUC) --}}
    <script>
        (function () {
            var pref = 'system';
            try {
                pref = localStorage.getItem('theme') || 'system';
            } catch (e) {}
            var dark = pref === 'dark'
                || (pref === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
            var root = document.documentElement;
            root.setAttribute('data-theme', dark ? 'dark' : 'light');
            root.setAttribute('data-theme-pref', pref);
            root.classList.add('js');
        })();
    </script>

    @fonts

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <a class="skip-link" href="#main">{{ __('site.a11y.skip_to_content') }}</a>

    @include('partials.nav')

    <main id="main">
        @yield('content')
    </main>

    @include('partials.footer')
</body>
</html>
