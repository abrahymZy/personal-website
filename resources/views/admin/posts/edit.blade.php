@extends('layouts.app')

@section('title', 'تعديل مقال: ' . $post['title'] . ' — ' . __('site.brand'))

@section('content')
    <section class="section admin-section">
        <div class="container">
            <div class="admin-head-nav" data-reveal>
                <a href="{{ route('admin.posts.index') }}" class="admin-back-link">
                    <span class="btn__glyph" aria-hidden="true">&#8594;</span>
                    <span>العودة إلى قائمة المقالات</span>
                </a>
            </div>

            <div class="admin-header" data-reveal>
                <div>
                    <div class="admin-badge">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                        </svg>
                        <span>تعديل المقال</span>
                    </div>
                    <h1 class="admin-title">تعديل: {{ $post['title'] }}</h1>
                    <p class="admin-subtitle">الملف: <code>{{ $slug }}.{{ $locale }}.md</code></p>
                </div>

                <div class="admin-header__actions">
                    <a href="{{ route('blog.show', $slug) }}" class="btn btn--ghost" target="_blank" rel="noopener">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        <span>معاينة المقال</span>
                    </a>
                </div>
            </div>

            @if ($errors->any())
                <div class="admin-alert admin-alert--danger" role="alert" data-reveal>
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="12" y1="8" x2="12" y2="12"></line>
                        <line x1="12" y1="16" x2="12.01" y2="16"></line>
                    </svg>
                    <div>
                        <strong style="display:block; margin-bottom: 0.25rem;">يرجى تصحيح الأخطاء التالية:</strong>
                        <ul style="margin: 0; padding-inline-start: 1.25rem;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="admin-card" data-reveal>
                <form method="POST"
                      action="{{ route('admin.posts.update', ['slug' => $slug, 'locale' => $locale]) }}"
                      enctype="multipart/form-data"
                      class="admin-form">
                    @csrf
                    @method('PUT')

                    <div class="admin-form-row">
                        <div class="form-group" style="flex: 2;">
                            <label for="title" class="form-label">عنوان المقال <span class="required">*</span></label>
                            <input type="text"
                                   id="title"
                                   name="title"
                                   class="form-control"
                                   value="{{ old('title', $post['title']) }}"
                                   required>
                        </div>

                        <div class="form-group" style="flex: 1.5;">
                            <label for="new_slug" class="form-label">الرابط الدائم (Slug) <span class="required">*</span></label>
                            <input type="text"
                                   id="new_slug"
                                   name="new_slug"
                                   class="form-control"
                                   value="{{ old('new_slug', $slug) }}"
                                   dir="ltr"
                                   required>
                            <span class="form-hint">تعديل الرابط سيقوم بنقل الملف إلى الاسم الجديد</span>
                        </div>

                        <div class="form-group" style="flex: 1;">
                            <label for="new_locale" class="form-label">اللغة <span class="required">*</span></label>
                            <select id="new_locale" name="new_locale" class="form-control" required>
                                <option value="ar" {{ old('new_locale', $locale) === 'ar' ? 'selected' : '' }}>العربية (AR)</option>
                                <option value="en" {{ old('new_locale', $locale) === 'en' ? 'selected' : '' }}>English (EN)</option>
                            </select>
                        </div>

                        <div class="form-group" style="flex: 1;">
                            <label for="date" class="form-label">تاريخ النشر <span class="required">*</span></label>
                            <input type="date"
                                   id="date"
                                   name="date"
                                   class="form-control"
                                   value="{{ old('date', $post['date']->toDateString()) }}"
                                   required
                                   dir="ltr">
                        </div>
                    </div>

                    <div class="admin-form-row">
                        <div class="form-group" style="flex: 2;">
                            <label for="summary" class="form-label">الملخص (Summary)</label>
                            <input type="text"
                                   id="summary"
                                   name="summary"
                                   class="form-control"
                                   value="{{ old('summary', $post['summary']) }}">
                        </div>

                        <div class="form-group" style="flex: 1.5;">
                            <label for="tags" class="form-label">الوسوم (Tags)</label>
                            <input type="text"
                                   id="tags"
                                   name="tags"
                                   class="form-control"
                                   value="{{ old('tags', implode(', ', $post['tags'] ?? [])) }}">
                            <span class="form-hint">افصل بين الوسوم بفواصل (,)</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-label-row">
                            <label for="body" class="form-label">محتوى المقال (Markdown Body) <span class="required">*</span></label>
                            <span class="form-hint">يدعم كامل تنسيقات Markdown</span>
                        </div>
                        <textarea id="body"
                                  name="body"
                                  rows="18"
                                  class="form-control form-control--textarea"
                                  required>{{ old('body', $post['body']) }}</textarea>
                    </div>

                    {{-- خيار بديل: استبدال المحتوى برفع ملف جديد --}}
                    <div class="form-group admin-upload-sub">
                        <label class="form-label" for="markdown_file">أو: استبدال المحتوى برفع ملف Markdown جديد (.md)</label>
                        <input type="file"
                               id="markdown_file"
                               name="markdown_file"
                               accept=".md,.markdown,text/markdown,text/plain"
                               class="admin-file-input-inline">
                        <span class="form-hint">اختياري. إذا حددت ملفاً هنا سيتم استبدال المقال بالملف المرفوع.</span>
                    </div>

                    <div class="admin-form-actions">
                        <button type="submit" class="btn btn--primary">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <polyline points="20 6 9 17 4 12"></polyline>
                            </svg>
                            <span>حفظ التعديلات</span>
                        </button>

                        <a href="{{ route('admin.posts.index') }}" class="btn btn--ghost">
                            <span>إلغاء والعودة</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
