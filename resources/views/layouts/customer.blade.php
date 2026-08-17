<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.customer_dashboard') }} · Trav</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon-180x180.png') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="{{ asset('css/layouts/customer.css') }}">

</head>
<body>

<div class="mobile-sidebar-overlay" id="mobileOverlay" onclick="closeSidebar()"></div>

<aside class="sidebar custom-scrollbar" id="sidebar">
    <a href="{{ route('customer.dashboard') }}" class="sidebar-logo">
        <div class="logo-icon">
            <i class="fa-solid fa-mountain-sun"></i>
        </div>
        Trav
    </a>

    <nav class="sidebar-nav">
        <a href="{{ route('customer.dashboard') }}" class="{{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-layer-group"></i> {{ __('messages.dashboard') }}
        </a>
        <a href="{{ route('favorites.index') }}" class="{{ request()->routeIs('favorites.index') ? 'active' : '' }}">
            <i class="fa-solid fa-heart"></i> {{ __('messages.favorites') }}
        </a>
        <a href="{{ route('customer.reviews.index') }}" class="{{ request()->routeIs('customer.reviews.index') ? 'active' : '' }}">
            <i class="fa-solid fa-star"></i> التقييمات
        </a>
        <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
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

<main class="main custom-scrollbar">
    <button class="hamburger" onclick="toggleSidebar()" aria-label="{{ __('messages.open_menu') }}">
        <i class="fa-solid fa-bars"></i>
    </button>

    {{-- Language Switcher (top right) --}}
    <div style="display: flex; justify-content: flex-end; margin-bottom: 1rem;">
        @include('partials.language-switcher')
    </div>

    @if(session('success'))
        <div class="alert-box alert-success anim-fade-up">
            <span class="alert-icon"><i class="fa-solid fa-circle-check"></i></span>
            <div class="alert-content">
                <span>{{ session('success') }}</span>
                <button onclick="this.parentElement.parentElement.remove()">&times;</button>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="alert-box alert-error anim-fade-up">
            <span class="alert-icon"><i class="fa-solid fa-circle-exclamation"></i></span>
            <div class="alert-content">
                <span>{{ session('error') }}</span>
                <button onclick="this.parentElement.parentElement.remove()">&times;</button>
            </div>
        </div>
    @endif

    @yield('content')
</main>

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
