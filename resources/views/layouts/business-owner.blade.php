<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.business_dashboard') }} · Trav</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon-180x180.png') }}">
    <script>
        (function protectMap() {
            if (typeof Map !== 'undefined' && Map.toString().includes('[native code]')) {
                Object.defineProperty(window, 'Map', {
                    value: Map,
                    writable: false,
                    configurable: false,
                    enumerable: true
                });
                console.log('✅ Native Map has been locked successfully.');
            } else {
                console.warn('⚠️ Map was already modified before we could lock it.');
            }
        })();
    </script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/@@alpinejs/click-away@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="{{ asset('css/layouts/business.css') }}">

</head>
<body>

{{-- Mobile overlay --}}
<div class="mobile-sidebar-overlay" id="mobileOverlay" onclick="closeSidebar()"></div>

{{-- SIDEBAR (scroll independiente) --}}
<aside class="sidebar custom-scrollbar" id="sidebar">
    <a href="{{ route('business-owner.dashboard') }}" class="sidebar-logo">
        <div class="logo-icon">
            <i class="fa-solid fa-mountain-sun"></i>
        </div>
        Trav
    </a>

    <nav class="sidebar-nav">
        <a href="{{ route('business-owner.dashboard') }}" class="active">
            <i class="fa-solid fa-layer-group"></i> {{ __('messages.dashboard') }}
        </a>
        <a href="{{ route('business-owner.listings.index') }}">
            <i class="fa-solid fa-list-check"></i> {{ __('messages.my_listings') }}
        </a>
        <a href="{{ route('business-owner.listings.create') }}">
            <i class="fa-solid fa-circle-plus"></i> {{ __('messages.add_listing') }}
        </a>
        <a href="{{ route('business-owner.profile') }}">
            <i class="fa-solid fa-user"></i> {{ __('messages.profile') }}
        </a>
        <a href="{{ route('home') }}">
            <i class="fa-solid fa-globe"></i> {{ __('messages.view_site') }}
        </a>
    </nav>

    <div class="sidebar-footer">
        <a href="{{ route('logout') }}"
           class="logout"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fa-solid fa-arrow-right-from-bracket"></i> {{ __('messages.logout') }}
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</aside>

{{-- MAIN CONTENT (scroll independiente) --}}
<main class="main custom-scrollbar">
    {{-- Hamburger button (mobile) --}}
    <button class="hamburger" onclick="toggleSidebar()" aria-label="{{ __('messages.open_menu') }}">
        <i class="fa-solid fa-bars"></i>
    </button>

    {{-- Top Bar --}}
    <header class="top-bar anim-fade-up">
        <div class="page-title">{{ __('messages.summary') }}</div>
        <div class="top-actions">
            <div class="search-box">
                <i class="fa-solid fa-magnifying-glass" style="color:var(--text-muted);font-size:0.85rem;"></i>
                <input type="text" placeholder="{{ __('messages.search_activities_placeholder') }}">
            </div>

            {{-- Language Switcher --}}
            <!-- <div class="language-switcher-wrapper" style="z-index: 1000;">
                @include('partials.language-switcher')
            </div> -->

            <a href="{{ route('business-owner.profile') }}" class="icon-btn" title="{{ __('messages.settings') }}">
                <i class="fa-solid fa-gear"></i>
            </a>
            <a href="{{ route('business-owner.profile') }}" class="user-chip" title="{{ __('messages.profile') }}">
                @php
                    $user = auth()->user();
                @endphp
                <img src="{{ $user->profile_photo_url ?? asset('images/default-avatar.png') }}"
                     alt="{{ __('messages.avatar') }}"
                     onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 40 40%22><rect fill=%22%23e2e8f0%22 width=%2240%22 height=%2240%22 rx=%2220%22/><text x=%2220%22 y=%2226%22 text-anchor=%22middle%22 font-size=%2216%22 fill=%22%2394a3b8%22>{{ Str::substr($user->name, 0, 1) }}</text></svg>'">
                <div class="user-chip-info">
                    <h5>{{ $user->name }}</h5>
                    <span>{{ __('messages.business_owner_role') }}</span>
                </div>
            </a>
        </div>
    </header>

    {{-- Session Messages --}}
    @if(session('success'))
        <div class="alert-box alert-success anim-fade-up">
            <span class="alert-icon"><i class="fa-solid fa-circle-check"></i></span>
            <div class="alert-content">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.parentElement.remove()" style="background:none;border:none;cursor:pointer;color:inherit;font-size:1.1rem;">&times;</button>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="alert-box alert-error anim-fade-up">
            <span class="alert-icon"><i class="fa-solid fa-circle-exclamation"></i></span>
            <div class="alert-content">
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.parentElement.remove()" style="background:none;border:none;cursor:pointer;color:inherit;font-size:1.1rem;">&times;</button>
            </div>
        </div>
    @endif

    @yield('content')
</main>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/@@alpinejs/click-away@3.x.x/dist/cdn.min.js"></script>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('mobileOverlay');
        sidebar.classList.toggle('open');
        overlay.classList.toggle('open');
    }
    function closeSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('mobileOverlay');
        sidebar.classList.remove('open');
        overlay.classList.remove('open');
    }
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeSidebar();
    });
</script>

</body>
</html>
