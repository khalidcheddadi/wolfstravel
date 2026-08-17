<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('messages.admin_dashboard') }} · Trav</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon-180x180.png') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="{{ asset('css/layouts/admin.css') }}">

</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<aside class="sidebar custom-scrollbar" id="sidebar">
    <div class="sidebar-header">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
            <div class="sidebar-brand-icon">
                <i class="fa-solid fa-mountain"></i>
            </div>
            <div>
                <div class="sidebar-brand-text">Trav</div>
                <div class="sidebar-brand-subtitle">{{ __('messages.admin_dashboard') }}</div>
            </div>
        </a>
    </div>

    <nav class="sidebar-nav">
        <div class="sidebar-section-label">{{ __('messages.main') }}</div>
        <a href="{{ route('admin.dashboard') }}"
           class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-pie"></i>
            {{ __('messages.dashboard') }}
        </a>

        <div class="sidebar-section-label">{{ __('messages.content_management') }}</div>
        <a href="{{ route('admin.listings.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.listings.*') ? 'active' : '' }}">
            <i class="fa-solid fa-list-check"></i>
            {{ __('messages.review_activities') }}
            @if(isset($pendingCount) && $pendingCount > 0)
                <span class="sidebar-badge">{{ $pendingCount }}</span>
            @endif
        </a>
        <a href="{{ route('admin.reviews.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
            <i class="fa-solid fa-star"></i>
            {{ __('messages.reviews') }}
        </a>
        <a href="{{ route('admin.posts.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.posts.index') ? 'active' : '' }}">
            <i class="fa-solid fa-newspaper"></i>
            {{ __('messages.posts') }}
        </a>
        <a href="{{ route('posts.create') }}"
           class="sidebar-link {{ request()->routeIs('posts.create') ? 'active' : '' }}">
            <i class="fa-solid fa-plus-circle"></i>
            {{ __('messages.add_post') }}
        </a>
        <a href="{{ route('admin.posts.import.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.posts.import.*') ? 'active' : '' }}">
            <i class="fa-solid fa-download"></i>
            Importar artículos
        </a>

        <div class="sidebar-section-label">{{ __('messages.platform_management') }}</div>
        <a href="{{ route('admin.users.index') }}"
           class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="fa-solid fa-users"></i>
            {{ __('messages.users') }}
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-user-avatar">
                {{ Str::upper(Str::substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="sidebar-user-info">
                <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
                <div class="sidebar-user-role">{{ __('messages.admin_role') }}</div>
            </div>
        </div>
        <a href="{{ route('home') }}" class="sidebar-footer-link">
            <i class="fa-solid fa-globe"></i> {{ __('messages.view_site') }}
        </a>
        <a href="{{ route('logout') }}"
           class="sidebar-footer-link logout"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fa-solid fa-right-from-bracket"></i> {{ __('messages.logout') }}
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
    </div>
</aside>

<main class="main custom-scrollbar">
    <div class="topbar">
        <div class="topbar-left">
            <button class="hamburger" onclick="toggleSidebar()" aria-label="{{ __('messages.open_menu') }}">
                <i class="fa-solid fa-bars"></i>
            </button>
            <div class="page-title-group">
                <h1>@yield('page_title', __('messages.admin_dashboard'))</h1>
                <div class="breadcrumb">
                    @yield('breadcrumb')
                </div>
            </div>
        </div>
        <div class="topbar-right">
            <a href="{{ route('language.switch', ['locale' => app()->getLocale() == 'ar' ? 'en' : 'ar']) }}"
               class="icon-btn"
               title="{{ app()->getLocale() == 'ar' ? 'English' : 'العربية' }}"
               aria-label="تبديل اللغة">
                <i class="fa-solid fa-language"></i>
            </a>

            {{-- زر الرجوع للصفحة السابقة --}}
            <button class="icon-btn" onclick="history.back();" title="Volver a la página anterior" aria-label="Volver atrás">
                <i class="fa-solid fa-arrow-left"></i>
            </button>

            <button class="icon-btn" aria-label="{{ __('messages.notifications') }}">
                <i class="fa-regular fa-bell"></i>
                <span class="notification-dot"></span>
            </button>
            <a href="{{ route('profile.edit') }}" class="user-menu-btn">
                <div class="user-avatar">
                    {{ Str::upper(Str::substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="user-menu-info">
                    <div class="user-menu-name">{{ auth()->user()->name }}</div>
                    <div class="user-menu-role">{{ __('messages.admin') }}</div>
                </div>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success anim-fade-up">
            <i class="fa-solid fa-circle-check"></i>
            <span>{{ session('success') }}</span>
            <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-error anim-fade-up">
            <i class="fa-solid fa-circle-exclamation"></i>
            <span>{{ session('error') }}</span>
            <button class="alert-close" onclick="this.parentElement.remove()">&times;</button>
        </div>
    @endif

    @yield('content')
</main>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        sidebar.classList.toggle('open');
        overlay.classList.toggle('open');
    }
    function closeSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        sidebar.classList.remove('open');
        overlay.classList.remove('open');
    }
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeSidebar();
    });
</script>

</body>
</html>
