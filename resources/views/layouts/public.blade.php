<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('meta_description', 'Discover unforgettable travel experiences, city tours, private experiences and curated activities in Europe and beyond with Wolfstravel.')">
    <meta name="robots" content="index,follow,max-image-preview:large,max-snippet:-1">
    <meta name="author" content="{{ config('app.name') }}">
    <meta name="theme-color" content="#2563eb">
    <meta name="application-name" content="{{ config('app.name') }}">
    <meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="format-detection" content="telephone=yes">

    <title>@yield('title', __('messages.home_title', ['app' => config('app.name')]))</title>
    <link rel="canonical" href="{{ url()->current() }}">

    @php
        $supportedLocales = ['en', 'es', 'fr', 'ar', 'de'];
        $currentPath = request()->path();
        $pathSegments = explode('/', $currentPath);
        $hasLocalePrefix = in_array($pathSegments[0] ?? '', $supportedLocales);
        $pathWithoutLocale = $hasLocalePrefix
            ? implode('/', array_slice($pathSegments, 1))
            : $currentPath;
    @endphp

    @if($hasLocalePrefix)
        @foreach($supportedLocales as $altLocale)
            <link rel="alternate" hreflang="{{ $altLocale }}" href="{{ url($altLocale . '/' . $pathWithoutLocale) }}">
        @endforeach
        <link rel="alternate" hreflang="x-default" href="{{ url(config('app.fallback_locale', 'es') . '/' . $pathWithoutLocale) }}">
    @endif

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('favicon-48x48.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon-192x192.png') }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('favicon-512x512.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon-180x180.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <meta property="og:title" content="@yield('og_title', __('messages.og_title'))">
    <meta property="og:description" content="@yield('og_description', 'Book unforgettable private tours, unique experiences and city escapes with Wolfstravel.')">
    <meta property="og:image" content="{{ url('/images/logo.png') }}">
    <meta property="og:image:secure_url" content="{{ url('/images/logo.png') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="{{ config('app.name') }} logo">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:locale" content="{{ app()->getLocale() }}">
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:title" content="@yield('og_title', config('app.name'))">
    <meta property="twitter:description" content="@yield('og_description', 'Book unforgettable travel experiences with Wolfstravel.')">
    <meta property="twitter:image" content="{{ url('/images/logo.png') }}">

    <link rel="stylesheet" href="{{ asset('css/layouts/public.css') }}">

    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css" />


    <style>
        :root {
            --fx-accent-glow: #2563eb;
            --fx-accent-light: #60a5fa;
            --fx-bg-curtain: rgba(9, 11, 16, 0.88);
        }

        .mxt-screen-curtain {
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100vh;
            background: var(--fx-bg-curtain);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            z-index: 999999;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            transition: opacity 0.2s ease, visibility 0.2s ease;
        }

        .q8-core-spinner {
            position: relative;
            width: 86px;
            height: 86px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .k3-halo-effect {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            background: conic-gradient(from 0deg, transparent 0%, var(--fx-accent-glow) 70%, #ffffff 100%);
            mask-image: radial-gradient(farthest-side, transparent calc(100% - 4px), #000 100%);
            -webkit-mask-image: radial-gradient(farthest-side, transparent calc(100% - 4px), #000 100%);
            animation: orbitSpin 1.1s linear infinite;
            filter: drop-shadow(0 0 12px rgba(37, 99, 235, 0.65));
        }

        .v9-center-node {
            width: 44px;
            height: 44px;
            background: radial-gradient(circle, var(--fx-accent-light) 0%, transparent 70%);
            border-radius: 50%;
            opacity: 0.8;
            animation: corePulse 1.8s ease-in-out infinite alternate;
        }

        .tx-status-wrap {
            margin-top: 28px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .tx-status-wrap .lbl-title {
            color: #ffffff;
            font-family: 'Poppins', sans-serif;
            font-size: 0.85rem;
            letter-spacing: 3px;
            text-transform: uppercase;
            font-weight: 600;
            opacity: 0.9;
        }

        .vx-progress-bar {
            width: 140px;
            height: 2px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 10px;
            overflow: hidden;
            position: relative;
        }

        .vx-progress-bar::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 45%;
            background: linear-gradient(90deg, transparent, var(--fx-accent-light), transparent);
            animation: shimmerSlide 1.6s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes orbitSpin {
            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes corePulse {
            0% {
                transform: scale(0.65);
                opacity: 0.3;
            }
            100% {
                transform: scale(1.15);
                opacity: 0.9;
            }
        }

        @keyframes shimmerSlide {
            0% {
                left: -50%;
            }
            100% {
                left: 100%;
            }
        }

        .d2-fade-out {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }
    </style>

    @yield('styles')
</head>
<body>

    <div id="mxt-screen-curtain" class="mxt-screen-curtain">
        <div class="q8-core-spinner">
            <div class="k3-halo-effect"></div>
            <div class="v9-center-node"></div>
        </div>
        <div class="tx-status-wrap">
            <span class="lbl-title">wolfstravel</span>
            <div class="vx-progress-bar"></div>
        </div>
    </div>

    <script>
        (function() {
            const curtain = document.getElementById('mxt-screen-curtain');

            try {
                const navEntries = performance.getEntriesByType('navigation');
                if (navEntries.length > 0 && navEntries[0].type === 'back_forward') {
                    curtain.style.display = 'none';
                    return;
                }
            } catch (e) {
            }

            setTimeout(function() {
                curtain.classList.add('d2-fade-out');
                setTimeout(function() {
                    curtain.remove();
                }, 200);
            }, 3000);
        })();
    </script>

    <header class="hero-section">
        <video class="hero-video" autoplay loop muted playsinline>
            <source src="{{ asset('videos/turi.mp4') }}" type="video/mp4">
            {{ __('messages.browser_video_not_supported') }}
        </video>
        <div class="hero-overlay"></div>

        @include('partials.header')

        <div class="hero-content">
            <span class="sub-title" data-aos="fade-down" data-aos-duration="600" data-aos-delay="0">
                {{ __('messages.discover_nearby') }}
            </span>

            <h1 class="main-title" data-aos="fade-up" data-aos-duration="600" data-aos-delay="0">
                {{ __('messages.find_amazing_place') }}
            </h1>

            <div class="search-container" data-aos="zoom-in-up" data-aos-duration="600" data-aos-delay="0">
                <form action="{{ route('search') }}" method="GET" class="search-form" id="searchForm">
                    <div class="search-field">
                        <input type="text" name="q" placeholder="{{ __('messages.search_placeholder') }}" value="{{ request('q') }}">
                    </div>
                    <div class="search-field select-field custom-select-wrapper" data-field="city">
                        <i class="fa-solid fa-location-dot field-icon"></i>
                        <div class="custom-select-display">
                            <span class="custom-select-text">{{ __('messages.all_cities') }}</span>
                            <i class="fa-solid fa-chevron-down arrow-icon custom-arrow"></i>
                        </div>
                        <input type="hidden" name="city" value="{{ request('city') }}">
                        <ul class="custom-select-options">
                            <li data-value="">{{ __('messages.all_cities') }}</li>
                            @foreach($cities as $city)
                                <li data-value="{{ $city->id }}">{{ $city->translate('name') }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="search-field select-field custom-select-wrapper" data-field="category">
                        <i class="fa-solid fa-layer-group field-icon"></i>
                        <div class="custom-select-display">
                            <span class="custom-select-text">{{ __('messages.all_categories') }}</span>
                            <i class="fa-solid fa-chevron-down arrow-icon custom-arrow"></i>
                        </div>
                        <input type="hidden" name="category" value="{{ request('category') }}">
                        <ul class="custom-select-options">
                            <li data-value="">{{ __('messages.all_categories') }}</li>
                            @foreach($categories as $category)
                                <li data-value="{{ $category->id }}">{{ $category->translate('name') }}</li>
                                @if($category->children->count())
                                    @foreach($category->children as $child)
                                        <li data-value="{{ $child->id }}">-- {{ $child->translate('name') }}</li>
                                    @endforeach
                                @endif
                            @endforeach
                        </ul>
                    </div>
                    <button type="submit" class="search-submit-btn">
                        {{ __('messages.search') }} <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </form>
            </div>

            <p class="counter-text" data-aos="fade-up" data-aos-duration="600" data-aos-delay="0">
                {{ __('messages.total_listings', ['count' => 300]) }}
            </p>
        </div>
    </header>

    <main>
        @yield('content')
    </main>


    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 800,
                easing: 'ease-out-cubic',
                once: true,
                offset: 100,
                delay: 0,
                disable: false,
            });
        });

        window.addEventListener('load', function() {
            document.body.classList.add('page-loaded');
            AOS.refresh();
        });

        function toggleLanguageMenu() {
            const dropdown = document.getElementById('languageDropdown');
            const arrow = document.getElementById('langArrow');
            dropdown.classList.toggle('show');
            arrow.style.transform = dropdown.classList.contains('show') ? 'rotate(180deg)' : 'rotate(0deg)';
        }
        document.addEventListener('click', function(event) {
            const switcher = document.getElementById('languageSwitcher');
            const dropdown = document.getElementById('languageDropdown');
            if (!switcher.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.remove('show');
                document.getElementById('langArrow').style.transform = 'rotate(0deg)';
            }
        });
        document.addEventListener('DOMContentLoaded', function() {
            const currentLocale = '{{ app()->getLocale() }}';
            const options = document.querySelectorAll('.lang-option');
            options.forEach(option => {
                option.classList.remove('active');
                if (option.dataset.lang === currentLocale) {
                    option.classList.add('active');
                    const flagSvg = option.querySelector('.flag-icon').outerHTML;
                    const langCode = option.dataset.lang.toUpperCase();
                    document.getElementById('currentFlag').outerHTML = flagSvg;
                    document.getElementById('currentLang').textContent = langCode;
                }
            });
        });
        function initCustomSelects() {
            const wrappers = document.querySelectorAll('.custom-select-wrapper');
            wrappers.forEach(wrapper => {
                const display = wrapper.querySelector('.custom-select-display');
                const optionsList = wrapper.querySelector('.custom-select-options');
                const hiddenInput = wrapper.querySelector('input[type="hidden"]');
                const textSpan = display.querySelector('.custom-select-text');
                const arrow = display.querySelector('.custom-arrow');
                display.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isOpen = optionsList.classList.contains('open');
                    closeAllSelects();
                    if (!isOpen) {
                        optionsList.classList.add('open');
                        arrow.style.transform = 'rotate(180deg)';
                    } else {
                        optionsList.classList.remove('open');
                        arrow.style.transform = 'rotate(0deg)';
                    }
                });
                const items = optionsList.querySelectorAll('li');
                items.forEach(item => {
                    item.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const value = this.dataset.value;
                        const text = this.textContent.trim();
                        hiddenInput.value = value;
                        textSpan.textContent = text;
                        optionsList.classList.remove('open');
                        arrow.style.transform = 'rotate(0deg)';
                        const allItems = optionsList.querySelectorAll('li');
                        allItems.forEach(li => li.classList.remove('selected'));
                        this.classList.add('selected');
                    });
                });
            });
            document.addEventListener('click', function() {
                closeAllSelects();
            });
        }
        function closeAllSelects() {
            document.querySelectorAll('.custom-select-options.open').forEach(list => {
                list.classList.remove('open');
                const wrapper = list.closest('.custom-select-wrapper');
                if (wrapper) {
                    const arrow = wrapper.querySelector('.custom-arrow');
                    if (arrow) arrow.style.transform = 'rotate(0deg)';
                }
            });
        }
    </script>

    @yield('scripts')
</body>
</html>
<style>
@keyframes slideInLeft {
    from { opacity: 0; transform: translateX(-40px); }
    to   { opacity: 1; transform: translateX(0); }
}
@keyframes slideInRight {
    from { opacity: 0; transform: translateX(40px); }
    to   { opacity: 1; transform: translateX(0); }
}
@keyframes slideInUp {
    from { opacity: 0; transform: translateY(25px); }
    to   { opacity: 1; transform: translateY(0); }
}
@keyframes popIn {
    0%   { opacity: 0; transform: scale(0.85); }
    70%  { transform: scale(1.03); }
    100% { opacity: 1; transform: scale(1); }
}

.search-field,
.search-submit-btn {
    opacity: 0;
    animation-fill-mode: both;
}

.page-loaded .search-field:nth-child(1) {
    animation: slideInLeft 0.5s cubic-bezier(0.23, 1, 0.32, 1) 0s both;
}
.page-loaded .search-field:nth-child(2) {
    animation: slideInUp 0.5s cubic-bezier(0.23, 1, 0.32, 1) 0s both;
}
.page-loaded .search-field:nth-child(3) {
    animation: slideInRight 0.5s cubic-bezier(0.23, 1, 0.32, 1) 0s both;
}
.page-loaded .search-submit-btn {
    animation: popIn 0.5s cubic-bezier(0.23, 1, 0.32, 1) 0s both;
}

.search-field,
.search-submit-btn {
    will-change: transform, opacity;
}
</style>
