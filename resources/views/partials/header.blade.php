<nav class="navbar" role="navigation" aria-label="Main navigation">
    <div class="nav-container">
        <div class="logo-area">
            <a href="{{ route('home') }}" class="logo-link" aria-label="Homepage">
                <img src="{{ asset('images/wolfstravel.png') }}" alt="wolfstravel Logo" />
            </a>
        </div>
        <ul class="nav-links" id="mainNav" role="menubar">
            <button class="menu-close-btn" id="menuCloseBtn" aria-label="Close menu">✕</button>
            <li role="none">
                <a href="{{ route('home') }}" role="menuitem" class="{{ request()->routeIs('home') || request()->routeIs('*.home') ? 'active' : '' }}">
                    {{ __('messages.home') }}
                </a>
            </li>
            <li role="none">
                <a href="{{ route('search') }}" role="menuitem" class="{{ request()->routeIs('search') || request()->routeIs('*.search') ? 'active' : '' }}">
                    {{ __('messages.all_activities') }}
                </a>
            </li>
            <li role="none">
                <a href="{{ route('privacy') }}" role="menuitem">{{ __('messages.privacy') }}</a>
            </li>
            <li role="none">
                <a href="{{ route('contact') }}" role="menuitem" class="{{ request()->routeIs('contact') ? 'active' : '' }}">
                    {{ __('messages.contact_us') }}
                </a>
            </li>
            <li class="mobile-actions" id="mobileActions" role="none"></li>
        </ul>
        <div class="nav-actions">
            <div class="lang-wrapper" id="langWrapper">
                <button type="button" class="lang-btn" id="langBtn" aria-haspopup="true" aria-expanded="false">
                    <i class="fa-solid fa-globe globe-icon" aria-hidden="true"></i>
                    <span class="lang-code-text">{{ strtoupper(app()->getLocale()) }}</span>
                    <i class="fa-solid fa-chevron-down chevron-icon" id="langChevron" aria-hidden="true"></i>
                </button>
                <div class="lang-dropdown" id="langDropdown" role="menu">
                    @foreach(['en' => 'English', 'es' => 'Español', 'fr' => 'Français', 'ar' => 'العربية', 'de' => 'Deutsch'] as $code => $name)
                        <a href="{{ route('language.switch', $code) }}"
                        class="lang-item {{ app()->getLocale() == $code ? 'is-active' : '' }}"
                        role="menuitem">
                        <span class="lang-code">{{ strtoupper($code) }}</span>
                        <span>{{ $name }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
            @auth
                <a href="{{ route('dashboard') }}" class="account-btn desktop-only" title="{{ __('messages.dashboard') }}" aria-label="Dashboard">
                    <i class="fa-regular fa-user" aria-hidden="true"></i>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="desktop-only" style="display:inline;">
                    @csrf
                    <button type="submit" class="logout-btn">{{ __('messages.logout') }}</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="account-btn desktop-only" title="{{ __('messages.login') }}" aria-label="Login">
                    <i class="fa-regular fa-user" aria-hidden="true"></i>
                </a>
                <a href="{{ route('register') }}" class="add-listing-btn desktop-only">
                    <span class="plus-icon"><i class="fa-solid fa-plus" aria-hidden="true"></i></span>
                    {{ __('messages.register') }}
                </a>
            @endauth
            <button class="hamburger-btn" id="hamburgerBtn" aria-label="Toggle menu" aria-expanded="false">
                <span class="bar"></span>
                <span class="bar"></span>
                <span class="bar"></span>
            </button>
        </div>
    </div>
</nav>

<style>
:root {
    --nav-bg: rgba(10, 14, 30, 0.55);
    --nav-border: rgba(255, 255, 255, 0.12);
    --nav-blur: 18px;
    --text-white: #ffffff;
    --text-muted: rgba(255, 255, 255, 0.75);
    --text-dark: #0f172a;
    --primary-blue: #2b73d2;
    --primary-dark: #0b2e59;
    --primary-hover: #3a86e8;
    --lang-btn-bg: rgba(255, 255, 255, 0.08);
    --lang-btn-border: rgba(255, 255, 255, 0.35);
    --lang-btn-hover-bg: #ffffff;
    --lang-btn-hover-color: var(--primary-blue);
    --menu-bg: #ffffff;
    --menu-shadow: 0 24px 48px -12px rgba(0, 0, 0, 0.25),
        0 8px 16px -6px rgba(0, 0, 0, 0.06);
    --menu-radius: 18px;
    --menu-item-radius: 10px;
    --mobile-overlay: rgba(0, 0, 0, 0.55);
    --mobile-menu-bg: rgba(10, 14, 30, 0.97);
    --mobile-menu-blur: 28px;
    --transition-smooth: cubic-bezier(0.16, 1, 0.3, 1);
    --transition-bounce: cubic-bezier(0.68, -0.55, 0.265, 1.55);
    --max-width: 1320px;
    --header-padding-x: 35px;
    --header-padding-y: 4px;
}

.navbar {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    padding: var(--header-padding-y) var(--header-padding-x);
    z-index: 1000;
    border-bottom: 1px solid var(--nav-border);
    backdrop-filter: blur(var(--nav-blur));
    -webkit-backdrop-filter: blur(var(--nav-blur));
    transition: background 0.3s ease, backdrop-filter 0.3s ease, box-shadow 0.3s ease;
}

.navbar.scrolled {
    background: #001c3d84 !important;
    backdrop-filter: blur(var(--nav-blur));
    -webkit-backdrop-filter: none !important;
    border-bottom-color: rgba(255, 255, 255, 0.15);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
}

.nav-container {
    max-width: var(--max-width);
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    position: relative;
}

.logo-area {
    flex-shrink: 0;
    display: flex;
    align-items: center;
}

.logo-area .logo-link {
    display: block;
    line-height: 0;
    text-decoration: none;
}

.logo-area .logo-link img {
    height: auto;
    max-height: 64px;
    width: auto;
    max-width: 190px;
    display: block;
    object-fit: contain;
    transition: opacity 0.25s ease;
}

.logo-area .logo-link:hover img {
    opacity: 0.85;
}

.nav-links {
    display: flex;
    list-style: none;
    gap: clamp(18px, 2.8vw, 38px);
    align-items: center;
    margin: 0;
    padding: 0;
    order: 1;
}

.nav-links a {
    position: relative;
    color: var(--text-white);
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    padding: 6px 0;
    letter-spacing: 0.01em;
    transition: color 0.25s ease, opacity 0.25s ease;
    white-space: nowrap;
}

.nav-links a::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 0;
    height: 2.5px;
    background: var(--text-white);
    border-radius: 4px;
    transition: width 0.35s var(--transition-smooth);
}

.nav-links a:hover::after,
.nav-links a.active::after {
    width: 100%;
}

.nav-links a:hover {
    color: rgba(255, 255, 255, 0.9);
}

.nav-links a.active {
    color: var(--text-white);
    font-weight: 600;
}

.nav-actions {
    display: flex;
    align-items: center;
    gap: 14px;
    flex-shrink: 0;
    order: 2;
}

.lang-wrapper {
    position: relative;
    z-index: 100;
}

.lang-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px 8px 14px;
    background: var(--lang-btn-bg);
    color: var(--text-white);
    border: 1px solid var(--lang-btn-border);
    border-radius: 50px;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s var(--transition-smooth);
    outline: none;
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    line-height: 1;
}

.lang-btn:hover {
    background: var(--lang-btn-hover-bg);
    color: var(--lang-btn-hover-color);
    border-color: var(--lang-btn-hover-bg);
    box-shadow: 0 6px 14px rgba(0, 0, 0, 0.08);
}

.lang-btn .globe-icon {
    font-size: 0.95rem;
}

.lang-btn .chevron-icon {
    font-size: 0.65rem;
    transition: transform 0.35s var(--transition-smooth);
}

.lang-btn.is-open .chevron-icon {
    transform: rotate(180deg);
}

.lang-dropdown {
    position: absolute;
    top: calc(100% + 14px);
    right: 0;
    min-width: 200px;
    background: var(--menu-bg);
    border: 1px solid rgba(226, 232, 240, 0.7);
    border-radius: var(--menu-radius);
    box-shadow: var(--menu-shadow);
    padding: 8px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-10px) scale(0.97);
    transform-origin: top right;
    transition: opacity 0.3s var(--transition-smooth),
                transform 0.35s var(--transition-smooth),
                visibility 0s 0.35s;
    list-style: none;
    z-index: 200;
}

.lang-dropdown.is-open {
    opacity: 1;
    visibility: visible;
    transform: translateY(0) scale(1);
    transition: opacity 0.3s var(--transition-smooth),
                transform 0.35s var(--transition-smooth),
                visibility 0s 0s;
}

.lang-dropdown::before {
    content: '';
    position: absolute;
    top: -7px;
    right: 28px;
    width: 14px;
    height: 14px;
    background: var(--menu-bg);
    transform: rotate(45deg);
    border-left: 1px solid rgba(226, 232, 240, 0.6);
    border-top: 1px solid rgba(226, 232, 240, 0.6);
    border-radius: 3px;
}

html[dir="rtl"] .lang-dropdown {
    right: auto;
    left: 0;
    transform-origin: top left;
}

html[dir="rtl"] .lang-dropdown::before {
    right: auto;
    left: 28px;
}

.lang-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 14px;
    color: var(--text-dark);
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    border-radius: var(--menu-item-radius);
    transition: all 0.2s ease;
    cursor: pointer;
}

.lang-item:hover {
    background: #f1f5f9;
    transform: translateX(4px);
}

html[dir="rtl"] .lang-item:hover {
    transform: translateX(-4px);
}

.lang-item.is-active {
    background: #eff6ff;
    color: #1d4ed8;
    font-weight: 700;
}

.lang-item .lang-code {
    font-size: 0.7rem;
    font-weight: 700;
    padding: 3px 9px;
    background: #f1f5f9;
    color: #64748b;
    border-radius: 6px;
    letter-spacing: 0.04em;
    transition: all 0.2s ease;
}

.lang-item:hover .lang-code {
    background: #e2e8f0;
    color: #334155;
}

.lang-item.is-active .lang-code {
    background: #dbeafe;
    color: #1d4ed8;
    box-shadow: 0 2px 6px rgba(29, 78, 216, 0.12);
}

.account-btn {
    width: 42px;
    height: 42px;
    border: 1.5px solid rgba(255, 255, 255, 0.6);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-white);
    text-decoration: none;
    font-size: 1rem;
    transition: all 0.3s ease;
    flex-shrink: 0;
    background: transparent;
    cursor: pointer;
}

.account-btn:hover {
    background: var(--primary-blue);
    border-color: var(--primary-blue);
    color: #fff;
    transform: scale(1.04);
}

.logout-btn {
    background: transparent;
    border: none;
    color: var(--text-white);
    font-size: 0.85rem;
    font-weight: 500;
    cursor: pointer;
    padding: 8px 14px;
    border-radius: 50px;
    transition: all 0.3s ease;
    font-family: inherit;
}

.logout-btn:hover {
    background: rgba(255, 255, 255, 0.12);
    color: #fff;
}

.add-listing-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 4px 20px 4px 4px;
    background: var(--primary-blue);
    color: #fff;
    border-radius: 50px;
    text-decoration: none;
    font-size: 0.85rem;
    font-weight: 600;
    box-shadow: 0 4px 14px rgba(43, 115, 210, 0.3);
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    font-family: inherit;
}

.add-listing-btn:hover {
    background: var(--primary-hover);
    box-shadow: 0 6px 20px rgba(43, 115, 210, 0.4);
    transform: translateY(-1px);
}

.add-listing-btn .plus-icon {
    width: 34px;
    height: 34px;
    background: var(--primary-dark);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85rem;
    color: #fff;
    flex-shrink: 0;
}

.hamburger-btn {
    display: none;
    background: transparent;
    border: none;
    cursor: pointer;
    padding: 8px;
    flex-direction: column;
    gap: 5px;
    align-items: center;
    justify-content: center;
    z-index: 1001;
    flex-shrink: 0;
}

.hamburger-btn .bar {
    display: block;
    width: 26px;
    height: 2.5px;
    background: var(--text-white);
    border-radius: 4px;
    transition: all 0.35s var(--transition-bounce);
    transform-origin: center;
}

.hamburger-btn.is-active .bar:nth-child(1) {
    transform: translateY(7.5px) rotate(45deg);
}

.hamburger-btn.is-active .bar:nth-child(2) {
    opacity: 0;
    transform: scaleX(0);
}

.hamburger-btn.is-active .bar:nth-child(3) {
    transform: translateY(-7.5px) rotate(-45deg);
}

.menu-close-btn {
    display: none;
    position: absolute;
    top: 20px;
    right: 25px;
    background: transparent;
    border: none;
    color: #ffffff;
    font-size: 2.2rem;
    line-height: 1;
    cursor: pointer;
    z-index: 10002;
    padding: 8px 12px;
    transition: transform 0.3s ease;
}

.menu-close-btn:hover {
    transform: rotate(90deg);
}

html[dir="rtl"] .menu-close-btn {
    right: auto;
    left: 25px;
}

@media (max-width: 991px) {
    :root {
        --header-padding-x: 20px;
    }

    html, body {
        overflow-x: hidden !important;
        max-width: 100vw;
    }

    .navbar {
        width: 100%;
        max-width: 100vw;
        overflow: visible;
    }

    .nav-container {
        width: 100%;
        max-width: 100%;
        min-width: 0;
    }

    .desktop-only {
        display: none !important;
    }

    .hamburger-btn {
        display: flex;
    }

    .nav-links {
        position: fixed;
        inset: 0;
        width: 100%;
        height: 100vh;
        background: #0a0e1e;
        background: rgba(10, 14, 30, 0.98);
        backdrop-filter: blur(24px) saturate(180%);
        -webkit-backdrop-filter: blur(24px) saturate(180%);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: stretch;
        gap: 6px;
        padding: 80px 30px 40px;
        margin: 0;
        list-style: none;
        visibility: hidden;
        opacity: 0;
        transform: scale(0.96);
        transition: visibility 0s 0.4s, opacity 0.4s ease, transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        pointer-events: none;
        z-index: 9999;
        border: none;
        overflow-y: auto;
        overflow-x: hidden;
        box-shadow: 0 0 60px rgba(0,0,0,0.6);
    }

    html[dir="rtl"] .nav-links {
        right: 0;
        left: 0;
        transform: scale(0.96);
        border: none;
    }

    .nav-links.is-open {
        visibility: visible;
        opacity: 1;
        transform: scale(1);
        transition: visibility 0s 0s, opacity 0.4s ease, transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        pointer-events: auto;
    }

    html[dir="rtl"] .nav-links.is-open {
        transform: scale(1);
    }

    .nav-links a {
        display: block;
        font-size: 1.2rem;
        font-weight: 500;
        color: #ffffff;
        padding: 12px 0;
        text-align: center;
        text-decoration: none;
        letter-spacing: 0.04em;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        transition: background 0.2s, color 0.2s;
        width: 100%;
        transform: none !important;
        opacity: 1 !important;
        white-space: normal;
    }

    .nav-links a:hover {
        background: rgba(255, 255, 255, 0.06);
        color: #ffffff;
    }

    .nav-links a.active {
        font-weight: 700;
        color: #ffffff;
        background: rgba(255, 255, 255, 0.04);
        border-radius: 8px;
    }

    .nav-links a::after {
        display: none;
    }

    .nav-links li a {
        transition-delay: 0s !important;
    }

    .menu-close-btn {
        display: block;
    }

    .mobile-actions {
        display: flex !important;
        flex-direction: column;
        gap: 12px;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.12);
        opacity: 1 !important;
        transform: none !important;
        transition: none !important;
    }

    .mobile-actions .account-btn,
    .mobile-actions .add-listing-btn,
    .mobile-actions .logout-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 14px;
        width: 100%;
        padding: 14px 16px;
        border-radius: 14px;
        font-size: 1rem;
        font-weight: 600;
        border: none;
        background: rgba(255, 255, 255, 0.07);
        color: #ffffff;
        text-decoration: none;
        cursor: pointer;
        font-family: inherit;
        transition: background 0.2s;
    }

    .mobile-actions .account-btn {
        background: rgba(255, 255, 255, 0.08);
    }
    .mobile-actions .account-btn:hover {
        background: rgba(255, 255, 255, 0.15);
    }

    .mobile-actions .add-listing-btn {
        background: var(--primary-blue);
        color: #fff;
    }
    .mobile-actions .add-listing-btn:hover {
        background: var(--primary-hover);
    }
    .mobile-actions .add-listing-btn .plus-icon {
        width: 32px;
        height: 32px;
        font-size: 0.8rem;
        background: var(--primary-dark);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .mobile-actions .logout-btn {
        background: rgba(255, 70, 70, 0.12);
        color: #ff6b6b;
    }
    .mobile-actions .logout-btn:hover {
        background: rgba(255, 70, 70, 0.25);
    }

    .lang-dropdown {
        position: fixed !important;
        top: 122px !important;
        left: 50% !important;
        transform: translate(-50%, -50%) scale(0.92) !important;
        width: 260px;
        max-width: 88vw;
        border-radius: var(--menu-radius);
        transform-origin: center center !important;
        box-shadow: 0 32px 64px rgba(0, 0, 0, 0.4);
    }

    .lang-dropdown.is-open {
        transform: translate(-50%, -50%) scale(1) !important;
    }

    .lang-dropdown::before {
        display: none !important;
    }

    html[dir="ltr"] .lang-dropdown,
    html[dir="rtl"] .lang-dropdown {
        right: auto !important;
        left: 50% !important;
    }
}

@media (max-width: 480px) {
    :root {
        --header-padding-x: 14px;
    }

    .logo-area .logo-link img {
        max-height: 46px;
        max-width: 120px;
    }

    .lang-btn {
        padding: 6px 12px 6px 10px;
        font-size: 0.75rem;
    }

    .lang-btn .globe-icon {
        font-size: 0.8rem;
    }

    .nav-links {
        padding: 70px 20px 30px;
    }

    .nav-links a {
        font-size: 1rem;
        padding: 10px 0;
    }

    .mobile-actions .account-btn,
    .mobile-actions .add-listing-btn,
    .mobile-actions .logout-btn {
        font-size: 0.9rem;
        padding: 12px 14px;
    }
}

.mobile-actions {
    display: none;
}

a:focus-visible,
button:focus-visible {
    outline: 2px solid var(--primary-blue);
    outline-offset: 3px;
    border-radius: 4px;
}

.nav-links::-webkit-scrollbar {
    width: 4px;
}
.nav-links::-webkit-scrollbar-track {
    background: transparent;
}
.nav-links::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 8px;
}
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var langBtn = document.getElementById('langBtn');
        var langDropdown = document.getElementById('langDropdown');
        var langChevron = document.getElementById('langChevron');

        function toggleLang(forceClose) {
            var isOpen = langDropdown.classList.contains('is-open');
            var shouldOpen = forceClose !== undefined ? !forceClose : !isOpen;
            langDropdown.classList.toggle('is-open', shouldOpen);
            langBtn.classList.toggle('is-open', shouldOpen);
            langBtn.setAttribute('aria-expanded', shouldOpen);
            if (langChevron) {
                langChevron.style.transform = shouldOpen ? 'rotate(180deg)' : 'rotate(0deg)';
            }
        }

        if (langBtn && langDropdown) {
            langBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleLang();
            });
            document.addEventListener('click', function(e) {
                var wrapper = document.getElementById('langWrapper');
                if (wrapper && !wrapper.contains(e.target)) {
                    if (langDropdown.classList.contains('is-open')) {
                        toggleLang(true);
                    }
                }
            });
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && langDropdown.classList.contains('is-open')) {
                    toggleLang(true);
                    langBtn.focus();
                }
            });
        }

        var hamburger = document.getElementById('hamburgerBtn');
        var mainNav = document.getElementById('mainNav');
        var closeBtn = document.getElementById('menuCloseBtn');
        var mobileActionsContainer = document.getElementById('mobileActions');

        function buildMobileActions() {
            var actions = document.querySelector('.nav-actions');
            if (!actions) return;
            mobileActionsContainer.innerHTML = '';
            var cloneForMobile = function(el) {
                if (!el) return null;
                if (el.tagName === 'FORM') {
                    var formClone = el.cloneNode(true);
                    formClone.classList.remove('desktop-only');
                    var btn = formClone.querySelector('button');
                    if (btn) btn.classList.remove('desktop-only');
                    return formClone;
                } else {
                    var clone = el.cloneNode(true);
                    clone.classList.remove('desktop-only');
                    return clone;
                }
            };
            var accBtn = actions.querySelector('.account-btn');
            if (accBtn) {
                var cloned = cloneForMobile(accBtn);
                if (cloned) mobileActionsContainer.appendChild(cloned);
            }
            var addBtn = actions.querySelector('.add-listing-btn');
            if (addBtn) {
                var cloned = cloneForMobile(addBtn);
                if (cloned) mobileActionsContainer.appendChild(cloned);
            }
            var logoutForm = actions.querySelector('form');
            if (logoutForm) {
                var cloned = cloneForMobile(logoutForm);
                if (cloned) mobileActionsContainer.appendChild(cloned);
            }
        }

        buildMobileActions();

        function toggleMenu(forceClose) {
            var isOpen = mainNav.classList.contains('is-open');
            var shouldOpen = (forceClose !== undefined) ? !forceClose : !isOpen;
            mainNav.classList.toggle('is-open', shouldOpen);
            hamburger.classList.toggle('is-active', shouldOpen);
            hamburger.setAttribute('aria-expanded', shouldOpen);
            document.body.style.overflow = shouldOpen ? 'hidden' : '';
        }

        if (hamburger && mainNav) {
            hamburger.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleMenu();
            });

            if (closeBtn) {
                closeBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    toggleMenu(true);
                });
            }

            mainNav.querySelectorAll('a').forEach(function(link) {
                link.addEventListener('click', function() {
                    if (mainNav.classList.contains('is-open')) {
                        toggleMenu(true);
                    }
                });
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && mainNav.classList.contains('is-open')) {
                    toggleMenu(true);
                    hamburger.focus();
                }
            });

            var resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    if (window.innerWidth > 991 && mainNav.classList.contains('is-open')) {
                        toggleMenu(true);
                    }
                }, 150);
            });
        }

        var navbar = document.querySelector('.navbar');
        var scrollThreshold = 50;

        function handleScroll() {
            if (window.scrollY > scrollThreshold) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        }

        var ticking = false;
        window.addEventListener('scroll', function() {
            if (!ticking) {
                window.requestAnimationFrame(function() {
                    handleScroll();
                    ticking = false;
                });
                ticking = true;
            }
        });

        handleScroll();
    });
</script>