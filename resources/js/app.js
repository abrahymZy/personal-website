import hljs from 'highlight.js/lib/common';

/* =========================================================
   ABRAHYM — سلوك الواجهة
   المظهر (ثلاث حالات) + القائمة الجانبية + Scroll-Spy + الظهور
   ========================================================= */

const root = document.documentElement;
const THEME_KEY = 'theme';
const THEME_ORDER = ['system', 'light', 'dark'];
const darkQuery = window.matchMedia('(prefers-color-scheme: dark)');

/* ---------- 1. المظهر ---------- */

const themeToggle = document.querySelector('[data-theme-toggle]');

function readThemePref() {
    try {
        const stored = localStorage.getItem(THEME_KEY);

        return THEME_ORDER.includes(stored) ? stored : 'system';
    } catch (error) {
        return 'system';
    }
}

function writeThemePref(pref) {
    try {
        localStorage.setItem(THEME_KEY, pref);
    } catch (error) {
        /* التخزين المحلي غير متاح — نتجاهل بهدوء */
    }
}

function applyTheme(pref) {
    const resolved = pref === 'system' ? (darkQuery.matches ? 'dark' : 'light') : pref;

    root.setAttribute('data-theme', resolved);
    root.setAttribute('data-theme-pref', pref);

    if (themeToggle) {
        const label = themeToggle.dataset['label' + pref.charAt(0).toUpperCase() + pref.slice(1)];

        themeToggle.setAttribute('aria-label', `${themeToggle.dataset.labelPrefix}: ${label}`);
        themeToggle.setAttribute('title', `${themeToggle.dataset.labelPrefix}: ${label}`);
    }
}

applyTheme(readThemePref());

themeToggle?.addEventListener('click', () => {
    const next = THEME_ORDER[(THEME_ORDER.indexOf(readThemePref()) + 1) % THEME_ORDER.length];

    writeThemePref(next);
    applyTheme(next);
});

darkQuery.addEventListener('change', () => {
    if (readThemePref() === 'system') {
        applyTheme('system');
    }
});

/* ---------- 2. القائمة الجانبية ---------- */

const menuToggles = [...document.querySelectorAll('[data-menu-toggle]')];
const menuToggle = document.querySelector('.tool--menu');
const nav = document.getElementById('primary-nav');
const navOverlay = document.querySelector('[data-menu-overlay]');
const desktopQuery = window.matchMedia('(min-width: 1200px)');

function syncMenuToggles(expanded) {
    menuToggles.forEach((toggle) => {
        if (toggle.hasAttribute('aria-expanded')) {
            toggle.setAttribute('aria-expanded', String(expanded));
        }

        const label = expanded ? toggle.dataset.labelClose : toggle.dataset.labelOpen;

        if (label) {
            toggle.setAttribute('aria-label', label);
        }
    });
}

function openMenu() {
    nav?.classList.add('is-open');
    navOverlay?.classList.add('is-open');
    document.body.classList.add('is-locked', 'is-menu-open');
    syncMenuToggles(true);
}

function closeMenu() {
    nav?.classList.remove('is-open');
    navOverlay?.classList.remove('is-open');
    document.body.classList.remove('is-locked', 'is-menu-open');
    syncMenuToggles(false);
}

menuToggles.forEach((toggle) => {
    toggle.addEventListener('click', () => {
        if (nav?.classList.contains('is-open')) {
            closeMenu();
        } else {
            openMenu();
        }
    });
});

navOverlay?.addEventListener('click', closeMenu);

nav?.addEventListener('click', (event) => {
    if (event.target.closest('a')) {
        closeMenu();
    }
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && nav?.classList.contains('is-open')) {
        closeMenu();
        menuToggle?.focus();
    }
});

desktopQuery.addEventListener('change', (event) => {
    if (event.matches) {
        closeMenu();
    }
});

/* ---------- 3. حالة الشريط العلوي عند التمرير ---------- */

const header = document.querySelector('[data-header]');

function syncHeaderState() {
    header?.classList.toggle('is-scrolled', window.scrollY > 80);
}

syncHeaderState();
window.addEventListener('scroll', syncHeaderState, { passive: true });

/* ---------- 4. تمييز القسم النشط (Scroll-Spy) ---------- */

const navLinks = [...document.querySelectorAll('[data-nav-link]')];
const navSections = navLinks
    // في الصفحات الداخلية تشير الروابط إلى أقسام الصفحة الرئيسية، فلا نراقبها هنا.
    .filter((link) => (link.getAttribute('href') || '').startsWith('#'))
    .map((link) => document.querySelector(link.getAttribute('href')))
    .filter((section) => section !== null);

if (navSections.length > 0) {
    const spy = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                navLinks.forEach((link) => link.removeAttribute('aria-current'));

                const active = navLinks.find(
                    (link) => link.getAttribute('href') === `#${entry.target.id}`
                );

                active?.setAttribute('aria-current', 'true');
            });
        },
        { rootMargin: '-45% 0px -50% 0px' }
    );

    navSections.forEach((section) => spy.observe(section));
}

/* ---------- 5. ظهور العناصر عند الدخول ---------- */

const revealTargets = [...document.querySelectorAll('[data-reveal]')];

if ('IntersectionObserver' in window) {
    const revealObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                entry.target.classList.add('is-visible');
                revealObserver.unobserve(entry.target);
            });
        },
        { threshold: 0.15 }
    );

    revealTargets.forEach((target) => revealObserver.observe(target));
} else {
    revealTargets.forEach((target) => target.classList.add('is-visible'));
}

/* ---------- 6. بطاقات المهارات القابلة للتوسّع ---------- */

document.querySelectorAll('[data-skill-card]').forEach((card) => {
    const toggle = card.querySelector('[data-skill-toggle]');
    const panel = card.querySelector('[data-skill-panel]');

    if (!toggle || !panel) {
        return;
    }

    toggle.addEventListener('click', () => {
        const isOpen = card.classList.toggle('is-open');

        toggle.setAttribute('aria-expanded', String(isOpen));
        panel.hidden = !isOpen;
    });
});

/* ---------- 7. تظليل شيفرة المقالات + زر النسخ ---------- */

function createCopyButton(code, label, doneLabel) {
    const button = document.createElement('button');

    button.type = 'button';
    button.className = 'code-copy';
    button.textContent = label;
    button.setAttribute('aria-label', label);

    button.addEventListener('click', async () => {
        try {
            await navigator.clipboard.writeText(code.textContent);
        } catch (error) {
            return;
        }

        button.textContent = doneLabel;
        button.classList.add('is-done');

        window.setTimeout(() => {
            button.textContent = label;
            button.classList.remove('is-done');
        }, 2000);
    });

    return button;
}

document.querySelectorAll('.markdown').forEach((container) => {
    const copyLabel = container.dataset.copyLabel || 'Copy';
    const doneLabel = container.dataset.copyDone || 'Copied';

    container.querySelectorAll('pre > code').forEach((code) => {
        const pre = code.parentElement;

        if (!pre) {
            return;
        }

        hljs.highlightElement(code);

        // غلاف يحمل زر النسخ حتى لا يتحرّك مع تمرير الشيفرة أفقياً.
        const wrapper = document.createElement('div');

        wrapper.className = 'code-block';

        pre.replaceWith(wrapper);
        wrapper.append(pre, createCopyButton(code, copyLabel, doneLabel));
    });
});
