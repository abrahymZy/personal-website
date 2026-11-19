@extends('layouts.app')

@section('title', 'تسجيل دخول المسؤول — ' . __('site.brand'))

@section('content')
    <section class="section admin-auth">
        <div class="container">
            <div class="admin-auth__card" data-reveal>
                <div class="admin-auth__head">
                    <div class="admin-badge">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                        </svg>
                        <span>لوحة الإدارة</span>
                    </div>

                    <h1 class="admin-auth__title">تسجيل دخول المسؤول</h1>
                    <p class="admin-auth__desc">سجّل الدخول لإدارة المدونة ونشر ورفع المقالات الجديدة</p>
                </div>

                @if ($errors->any())
                    <div class="admin-alert admin-alert--danger" role="alert">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <div>
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login.submit') }}" class="admin-form">
                    @csrf

                    <div class="form-group">
                        <label for="email" class="form-label">البريد الإلكتروني</label>
                        <input type="email"
                               id="email"
                               name="email"
                               class="form-control"
                               value="{{ old('email') }}"
                               placeholder="admin@example.com"
                               required
                               autofocus
                               dir="ltr">
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">كلمة المرور</label>
                        <input type="password"
                               id="password"
                               name="password"
                               class="form-control"
                               placeholder="••••••••"
                               required
                               dir="ltr">
                    </div>

                    <div class="form-group form-group--inline">
                        <label class="form-checkbox">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <span>تذكرني على هذا الجهاز</span>
                        </label>
                    </div>

                    <div class="admin-auth__actions">
                        <button type="submit" class="btn btn--primary admin-btn-block">
                            <span>تسجيل الدخول</span>
                            <span class="btn__glyph" aria-hidden="true">&#8592;</span>
                        </button>
                    </div>
                </form>

                <div class="admin-auth__foot">
                    <a href="{{ route('blog.index') }}" class="admin-back-link">
                        <span>العودة إلى المدونة</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
