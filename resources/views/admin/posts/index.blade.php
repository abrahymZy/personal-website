@extends('layouts.app')

@section('title', 'لوحة تحكم المدونة — ' . __('site.brand'))

@section('content')
    <section class="section admin-section">
        <div class="container">
            {{-- ترويسة لوحة التحكم --}}
            <div class="admin-header" data-reveal>
                <div>
                    <div class="admin-badge">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 20h9"></path>
                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                        </svg>
                        <span>لوحة التحكم</span>
                    </div>
                    <h1 class="admin-title">إدارة مقالات المدونة</h1>
                    <p class="admin-subtitle">إجمالي المقالات: {{ $posts->count() }} تدوينة مسجلة</p>
                </div>

                <div class="admin-header__actions">
                    <a href="{{ route('admin.posts.create') }}" class="btn btn--primary">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>رفع / إضافة مقال جديد</span>
                    </a>

                    <a href="{{ route('blog.index') }}" class="btn btn--ghost" target="_blank" rel="noopener">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                            <polyline points="15 3 21 3 21 9"></polyline>
                            <line x1="10" y1="14" x2="21" y2="3"></line>
                        </svg>
                        <span>معاينة المدونة</span>
                    </a>

                    <form method="POST" action="{{ route('admin.logout') }}" class="admin-inline-form">
                        @csrf
                        <button type="submit" class="btn btn--ghost admin-btn-danger" title="تسجيل الخروج">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                            <span>خروج</span>
                        </button>
                    </form>
                </div>
            </div>

            {{-- رسائل التنبيه والنجاح --}}
            @if (session('status'))
                <div class="admin-alert admin-alert--success" role="alert">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="admin-alert admin-alert--danger" role="alert">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- شريط التصفية والبحث --}}
            <div class="admin-filter-bar" data-reveal>
                <div class="admin-filter-tabs">
                    <a href="{{ route('admin.posts.index') }}"
                       class="admin-tab {{ empty($currentLocale) ? 'admin-tab--active' : '' }}">
                        الكل
                    </a>
                    @foreach ($locales as $loc)
                        <a href="{{ route('admin.posts.index', ['locale' => $loc]) }}"
                           class="admin-tab {{ $currentLocale === $loc ? 'admin-tab--active' : '' }}">
                            {{ $loc === 'ar' ? 'العربية (AR)' : 'English (EN)' }}
                        </a>
                    @endforeach
                </div>

                <form method="GET" action="{{ route('admin.posts.index') }}" class="admin-search-form">
                    @if (! empty($currentLocale))
                        <input type="hidden" name="locale" value="{{ $currentLocale }}">
                    @endif
                    <div class="admin-search-wrap">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" class="admin-search-icon" aria-hidden="true">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                        <input type="search"
                               name="q"
                               value="{{ $searchQuery }}"
                               placeholder="ابحث في المقالات..."
                               class="admin-search-input">
                    </div>
                </form>
            </div>

            {{-- جدول / قائمة المقالات --}}
            @if ($posts->isEmpty())
                <div class="admin-empty" data-reveal>
                    <div class="admin-empty__icon">
                        <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                    </div>
                    <h2>لا توجد مقالات تطابق البحث</h2>
                    <p>ابدأ برفع أول مقال أو كتابته الآن بسهولة.</p>
                    <a href="{{ route('admin.posts.create') }}" class="btn btn--primary" style="margin-block-start: 1rem;">
                        <span>رفع / إضافة مقال جديد</span>
                    </a>
                </div>
            @else
                <div class="admin-table-card" data-reveal>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>المقال</th>
                                <th>اللغة</th>
                                <th>الرابط (Slug)</th>
                                <th>التاريخ</th>
                                <th>الوسوم</th>
                                <th style="text-align: end;">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($posts as $post)
                                <tr>
                                    <td class="admin-table__primary">
                                        <div class="admin-post-title">{{ $post['title'] }}</div>
                                        @if (! empty($post['summary']))
                                            <div class="admin-post-summary">{{ Str::limit($post['summary'], 80) }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="admin-locale-badge admin-locale-badge--{{ $post['locale'] }}">
                                            {{ strtoupper($post['locale']) }}
                                        </span>
                                    </td>
                                    <td dir="ltr" class="admin-slug-cell">
                                        <code>{{ $post['slug'] }}</code>
                                    </td>
                                    <td>
                                        <time datetime="{{ $post['date']->toDateString() }}" class="admin-date-text">
                                            {{ $post['date']->format('Y-m-d') }}
                                        </time>
                                    </td>
                                    <td>
                                        @if (! empty($post['tags']))
                                            <div class="admin-tags-list">
                                                @foreach (array_slice($post['tags'], 0, 3) as $t)
                                                    <span class="chip chip--tech">{{ $t }}</span>
                                                @endforeach
                                                @if (count($post['tags']) > 3)
                                                    <span class="admin-more-tags">+{{ count($post['tags']) - 3 }}</span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="admin-muted">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="admin-row-actions">
                                            {{-- رابط المعاينة --}}
                                            <a href="{{ route('blog.show', $post['slug']) }}"
                                               class="admin-icon-btn"
                                               title="معاينة المقال"
                                               target="_blank"
                                               rel="noopener">
                                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                                    <circle cx="12" cy="12" r="3"></circle>
                                                </svg>
                                            </a>

                                            {{-- زر التعديل --}}
                                            <a href="{{ route('admin.posts.edit', ['slug' => $post['slug'], 'locale' => $post['locale']]) }}"
                                               class="admin-icon-btn"
                                               title="تعديل المقال">
                                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                                </svg>
                                            </a>

                                            {{-- زر الحذف --}}
                                            <form method="POST"
                                                  action="{{ route('admin.posts.destroy', ['slug' => $post['slug'], 'locale' => $post['locale']]) }}"
                                                  onsubmit="return confirm('هل أنت متأكد من حذف مقال &quot;{{ addslashes($post['title']) }}&quot; ({{ strtoupper($post['locale']) }}) نهائياً؟');"
                                                  class="admin-inline-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="admin-icon-btn admin-icon-btn--danger"
                                                        title="حذف المقال">
                                                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </section>
@endsection
