@extends('layouts.app')

@section('title', 'رفع أو إضافة مقال جديد — ' . __('site.brand'))

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
                            <line x1="12" y1="5" x2="12" y2="19"></line>
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                        </svg>
                        <span>مقال جديد</span>
                    </div>
                    <h1 class="admin-title">رفع أو إضافة مقال إلى المدونة</h1>
                    <p class="admin-subtitle">يمكنك رفع ملف Markdown جاهز (.md) مباشرة، أو كتابة بيانات ومتن المقال عبر النموذج أدناه.</p>
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

            <div class="admin-grid-two" data-reveal>
                {{-- البطاقة الأولى: رفع ملف Markdown جاهز --}}
                <div class="admin-card">
                    <div class="admin-card__head">
                        <div class="admin-card__icon">
                            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" y1="3" x2="12" y2="15"></line>
                            </svg>
                        </div>
                        <div>
                            <h2 class="admin-card__title">رفع ملف Markdown مباشر (.md)</h2>
                            <p class="admin-card__subtitle">ارفع ملف المقال وسيتم التعرف تلقائياً على الترويسة واللغة والرابط.</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.posts.store') }}" enctype="multipart/form-data" class="admin-form">
                        @csrf

                        <div class="form-group">
                            <label class="form-label" for="markdown_file">اختر ملف Markdown (.md أو .markdown)</label>
                            <div class="admin-file-drop">
                                <input type="file"
                                       id="markdown_file"
                                       name="markdown_file"
                                       accept=".md,.markdown,text/markdown,text/plain"
                                       class="admin-file-input"
                                       required>
                                <div class="admin-file-prompt">
                                    <svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                        <polyline points="14 2 14 8 20 8"></polyline>
                                        <line x1="12" y1="18" x2="12" y2="12"></line>
                                        <polyline points="9 15 12 12 15 15"></polyline>
                                    </svg>
                                    <span class="admin-file-text">اضغط لاختيار ملف .md من جهازك</span>
                                    <span class="admin-file-hint">صيغة الملف: slug.ar.md أو slug.en.md</span>
                                </div>
                            </div>
                        </div>

                        <div class="admin-form-row">
                            <div class="form-group">
                                <label for="upload_slug" class="form-label">الرابط الدائم (Slug) — اختياري</label>
                                <input type="text"
                                       id="upload_slug"
                                       name="upload_slug"
                                       class="form-control"
                                       dir="ltr"
                                       placeholder="يُستخرج تلقائياً من اسم الملف">
                                <span class="form-hint">اتركه فارغاً للاستخراج من اسم الملف</span>
                            </div>

                            <div class="form-group">
                                <label for="upload_locale" class="form-label">اللغة — اختياري</label>
                                <select id="upload_locale" name="upload_locale" class="form-control">
                                    <option value="">تلقائي (حسب اسم الملف .ar.md أو .en.md)</option>
                                    <option value="ar">العربية (ar)</option>
                                    <option value="en">English (en)</option>
                                </select>
                            </div>
                        </div>

                        <div class="admin-card__foot">
                            <button type="submit" class="btn btn--primary">
                                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="17 8 12 3 7 8"></polyline>
                                    <line x1="12" y1="3" x2="12" y2="15"></line>
                                </svg>
                                <span>رفع ونشر الملف</span>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- البطاقة الثانية: إرشادات سريعة لملفات Markdown --}}
                <div class="admin-card admin-card--muted">
                    <div class="admin-card__head">
                        <div class="admin-card__icon">
                            <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <circle cx="12" cy="12" r="10"></circle>
                                <line x1="12" y1="16" x2="12" y2="12"></line>
                                <line x1="12" y1="8" x2="12.01" y2="8"></line>
                            </svg>
                        </div>
                        <div>
                            <h2 class="admin-card__title">هيكل ملف المقال المدعوم</h2>
                            <p class="admin-card__subtitle">تنسيق الترويسة الأمامية (Front Matter) المدعومة في المدونة:</p>
                        </div>
                    </div>

                    <pre class="admin-code-block" dir="ltr"><code>---
title: "عنوان المقال الكامل هنا"
date: {{ date('Y-m-d') }}
tags: [laravel, php, ذكاء_اصطناعي]
summary: "موجز قصير يظهر في بطاقة المقال والبحث."
---

# عنوان رئيسي
هنا يبدأ متن المقال بـ **Markdown** الكامل...
</code></pre>

                    <p class="admin-note">
                        💡 عند تسمية الملف بنمط <code>my-post.ar.md</code> سيتم التعرف على الرابط <code>my-post</code> واللغة العربية تلقائياً.
                    </p>
                </div>
            </div>

            {{-- البطاقة الثالثة: كتابة المقال مباشرة عبر النموذج --}}
            <div class="admin-card" style="margin-block-start: 2rem;" data-reveal>
                <div class="admin-card__head">
                    <div class="admin-card__icon">
                        <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 20h9"></path>
                            <path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="admin-card__title">أو: كتابة المقال مباشرة عبر النموذج</h2>
                        <p class="admin-card__subtitle">املأ الحقول التالية وسيتم إنشاء ملف المقال وحفظه في النظام فوراً.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.posts.store') }}" class="admin-form">
                    @csrf

                    <div class="admin-form-row">
                        <div class="form-group" style="flex: 2;">
                            <label for="title" class="form-label">عنوان المقال <span class="required">*</span></label>
                            <input type="text"
                                   id="title"
                                   name="title"
                                   class="form-control"
                                   value="{{ old('title') }}"
                                   placeholder="مثال: دليلك الشامل لتعلم Laravel 12"
                                   required>
                        </div>

                        <div class="form-group" style="flex: 1.5;">
                            <label for="slug" class="form-label">الرابط الدائم (Slug) <span class="required">*</span></label>
                            <input type="text"
                                   id="slug"
                                   name="slug"
                                   class="form-control"
                                   value="{{ old('slug') }}"
                                   dir="ltr"
                                   placeholder="laravel-12-guide"
                                   required>
                            <span class="form-hint">أحرف إنجليزية وأرقام وشرطات فقط</span>
                        </div>

                        <div class="form-group" style="flex: 1;">
                            <label for="locale" class="form-label">اللغة <span class="required">*</span></label>
                            <select id="locale" name="locale" class="form-control" required>
                                <option value="ar" {{ old('locale', 'ar') === 'ar' ? 'selected' : '' }}>العربية (AR)</option>
                                <option value="en" {{ old('locale') === 'en' ? 'selected' : '' }}>English (EN)</option>
                            </select>
                        </div>

                        <div class="form-group" style="flex: 1;">
                            <label for="date" class="form-label">تاريخ النشر <span class="required">*</span></label>
                            <input type="date"
                                   id="date"
                                   name="date"
                                   class="form-control"
                                   value="{{ old('date', date('Y-m-d')) }}"
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
                                   value="{{ old('summary') }}"
                                   placeholder="موجز سريع من سطر أو سطرين يظهر في بطاقة المقال والبحث">
                        </div>

                        <div class="form-group" style="flex: 1.5;">
                            <label for="tags" class="form-label">الوسوم (Tags)</label>
                            <input type="text"
                                   id="tags"
                                   name="tags"
                                   class="form-control"
                                   value="{{ old('tags') }}"
                                   placeholder="laravel, php, tutorials">
                            <span class="form-hint">افصل بين الوسوم بفواصل (,)</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-label-row">
                            <label for="body" class="form-label">محتوى المقال (Markdown Body) <span class="required">*</span></label>
                            <span class="form-hint">يدعم كامل خصائص Markdown: العناوين، الجداول، الشيفرة البرمجية، والاقتباسات</span>
                        </div>
                        <textarea id="body"
                                  name="body"
                                  rows="16"
                                  class="form-control form-control--textarea"
                                  placeholder="اكتب متن المقال هنا بصيغة Markdown..."
                                  required>{{ old('body') }}</textarea>
                    </div>

                    <div class="admin-form-actions">
                        <button type="submit" class="btn btn--primary">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                <polyline points="7 3 7 8 15 8"></polyline>
                            </svg>
                            <span>نشر المقال الآن</span>
                        </button>

                        <a href="{{ route('admin.posts.index') }}" class="btn btn--ghost">
                            <span>إلغاء</span>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </section>

    {{-- مساعدة في إنشاء Slug تلقائي من العنوان إن كان بالإنجليزية --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const titleInput = document.getElementById('title');
            const slugInput = document.getElementById('slug');

            if (titleInput && slugInput) {
                titleInput.addEventListener('blur', function () {
                    if (!slugInput.value.trim() && titleInput.value.trim()) {
                        const englishOnly = titleInput.value.toLowerCase()
                            .replace(/[^a-z0-9\s-]/g, '')
                            .trim()
                            .replace(/\s+/g, '-');
                        if (englishOnly) {
                            slugInput.value = englishOnly;
                        }
                    }
                });
            }

            const fileInput = document.getElementById('markdown_file');
            const fileText = document.querySelector('.admin-file-text');
            if (fileInput && fileText) {
                fileInput.addEventListener('change', function () {
                    if (fileInput.files.length > 0) {
                        fileText.textContent = 'الملف المحدد: ' + fileInput.files[0].name;
                    }
                });
            }
        });
    </script>
@endsection
