@extends('layouts.customer')

@section('content')

<header class="top-bar anim-fade-up">
    <div class="page-title">{{ __('messages.summary') }}</div>
    <div class="top-actions">
        <div class="search-box">
            <i class="fa-solid fa-magnifying-glass" style="color:var(--text-muted);font-size:0.85rem;"></i>
            <input type="text" placeholder="{{ __('messages.search_activities_placeholder') }}">
        </div>
        <a href="{{ route('profile.edit') }}" class="icon-btn" title="{{ __('messages.settings') }}">
            <i class="fa-solid fa-gear"></i>
        </a>
        <a href="{{ route('profile.edit') }}" class="user-chip" title="{{ __('messages.profile') }}">
            <img src="{{ $user->profile_photo_url ?? asset('images/default-avatar.png') }}"
                 alt="{{ __('messages.avatar') }}"
                 onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 40 40%22><rect fill=%22%23e2e8f0%22 width=%2240%22 height=%2240%22 rx=%2220%22/><text x=%2220%22 y=%2226%22 text-anchor=%22middle%22 font-size=%2216%22 fill=%22%2394a3b8%22>{{ Str::substr($user->name, 0, 1) }}</text></svg>'">
            <div class="user-chip-info">
                <h5>{{ $user->name }}</h5>
                <span>{{ __('messages.user_role') }}</span>
            </div>
        </a>
    </div>
</header>

<div class="welcome-card anim-scale-in anim-delay-1">
    <div class="welcome-text">
        <div class="greeting">{{ __('messages.customer_dashboard') }}</div>
        <h1>{{ __('messages.hello') }}, {{ $user->name }}!</h1>
        <p>
            @if($user->last_login_at)
                {{ __('messages.last_access') }}: <strong>{{ $user->last_login_at->diffForHumans() }}</strong> &middot;
            @endif
            {{ __('messages.customer_welcome_text') }}
        </p>
    </div>
    <div class="welcome-actions">
        <a href="{{ route('home') }}" class="btn-primary">
            <i class="fa-solid fa-compass"></i> {{ __('messages.explore_activities') }}
        </a>
        <a href="{{ route('profile.edit') }}" class="btn-ghost">
            <i class="fa-solid fa-pen-to-square"></i> {{ __('messages.edit_profile') }}
        </a>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card anim-fade-up anim-delay-2">
        <div class="stat-header">
            <span class="stat-label">{{ __('messages.favorites_label') }}</span>
            <div class="stat-icon amber"><i class="fa-solid fa-heart"></i></div>
        </div>
        <div class="stat-value" id="counterFavorites">{{ $stats['favorites_count'] }}</div>
        <div class="stat-sub">{{ __('messages.saved_activities') }}</div>
    </div>

    <div class="stat-card anim-fade-up anim-delay-2">
        <div class="stat-header">
            <span class="stat-label">{{ __('messages.reviews_label') }}</span>
            <div class="stat-icon green"><i class="fa-solid fa-star"></i></div>
        </div>
        <div class="stat-value" id="counterReviews">{{ $stats['reviews_count'] }}</div>
        <div class="stat-sub">
            @if($stats['reviews_count'] > 0)
                <span class="stat-trend up"><i class="fa-solid fa-check-circle"></i> {{ $stats['reviews_count'] }} {{ __('messages.reviews_count') }}</span>
            @else
                {{ __('messages.no_reviews_yet') }}
            @endif
        </div>
    </div>

    @if(isset($stats['bookings_count']))
    <div class="stat-card anim-fade-up anim-delay-2">
        <div class="stat-header">
            <span class="stat-label">{{ __('messages.bookings_label') }}</span>
            <div class="stat-icon blue"><i class="fa-solid fa-ticket"></i></div>
        </div>
        <div class="stat-value" id="counterBookings">{{ $stats['bookings_count'] ?? 0 }}</div>
        <div class="stat-sub">{{ __('messages.active_bookings') }}</div>
    </div>
    @endif

    <div class="stat-card anim-fade-up anim-delay-2">
        <div class="stat-header">
            <span class="stat-label">{{ __('messages.last_activity_label') }}</span>
            <div class="stat-icon purple"><i class="fa-solid fa-clock-rotate-left"></i></div>
        </div>
        <div class="stat-value" style="font-size:1.1rem;line-height:1.4;">
            {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : __('messages.today') }}
        </div>
        <div class="stat-sub">{{ __('messages.last_login_label') }}</div>
    </div>
</div>

<div class="panels-grid">
    <div class="panel anim-fade-up anim-delay-3">
        <div class="panel-header">
            <h3 class="panel-title">
                <i class="fa-solid fa-heart" style="color:#e11d48;margin-right:8px;"></i>
                {{ __('messages.latest_favorites') }}
            </h3>
            <a href="{{ route('favorites.index') }}" class="panel-link">
                {{ __('messages.view_all') }} <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
        <div>
            @if($favorites->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon"><i class="fa-solid fa-heart-crack"></i></div>
                    <h4>{{ __('messages.no_favorites_title') }}</h4>
                    <p>{{ __('messages.no_favorites_desc') }}</p>
                    <a href="{{ route('home') }}" class="btn-primary" style="font-size:0.8rem;padding:0.5rem 1.5rem;">
                        <i class="fa-solid fa-compass"></i> {{ __('messages.explore_activities') }}
                    </a>
                </div>
            @else
                @foreach($favorites as $listing)
                    <div class="list-row">
                        <div class="list-row-left">
                            <div class="list-thumb">
                                @php
                                    $imageUrl = $listing->getSignedImageUrl('thumb', 5);
                                @endphp
                                @if($imageUrl)
                                    <img src="{{ $imageUrl }}" alt="{{ $listing->title }}">
                                @else
                                    <i class="fa-solid fa-image"></i>
                                @endif
                            </div>
                            <div class="list-info">
                                <h4 title="{{ $listing->title }}">
                                    <a href="{{ route('listing.show', $listing->slug) }}" style="text-decoration:none;color:inherit;">
                                        {{ $listing->title }}
                                    </a>
                                </h4>
                                <p>{{ $listing->city?->name ?? __('messages.not_specified') }}
                                    @if($listing->average_rating)
                                        · {{ number_format($listing->average_rating, 1) }} <i class="fa-solid fa-star" style="color:#f59e0b;font-size:0.65rem;"></i>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="list-actions">
                            <a href="{{ route('listing.show', $listing->slug) }}" class="list-action-btn" title="{{ __('messages.view_activity') }}">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="{{ route('favorites.index') }}" class="list-action-btn" title="{{ __('messages.manage_favorites') }}" style="cursor:pointer;">
                                <i class="fa-solid fa-heart"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <div class="panel anim-fade-up anim-delay-3">
        <div class="panel-header">
            <h3 class="panel-title">
                <i class="fa-solid fa-star" style="color:#f59e0b;margin-right:8px;"></i>
                {{ __('messages.my_reviews') }}
            </h3>
            <span style="font-size:0.82rem;color:var(--text-muted);font-weight:600;">
                {{ $stats['reviews_count'] }} {{ __('messages.reviews_count') }}
            </span>
        </div>
        <div>
            @if($reviews->isEmpty())
                <div class="empty-state">
                    <div class="empty-icon"><i class="fa-solid fa-star-half-stroke"></i></div>
                    <h4>{{ __('messages.no_reviews_title') }}</h4>
                    <p>{{ __('messages.no_reviews_desc') }}</p>
                    <a href="{{ route('home') }}" class="btn-primary" style="font-size:0.8rem;padding:0.5rem 1.5rem;">
                        <i class="fa-solid fa-compass"></i> {{ __('messages.explore_activities') }}
                    </a>
                </div>
            @else
                @foreach($reviews as $review)
                    <div class="list-row">
                        <div class="list-row-left">
                            <div class="list-info" style="flex:1;">
                                <h4 style="max-width:100%;">
                                    <a href="{{ route('listing.show', $review->listing->slug) }}" style="text-decoration:none;color:inherit;">
                                        {{ $review->listing->title }}
                                    </a>
                                </h4>
                                <p style="margin-top:0.2rem;">
                                    <span class="rating-stars">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <i class="fa-solid fa-star"></i>
                                            @else
                                                <i class="fa-regular fa-star"></i>
                                            @endif
                                        @endfor
                                    </span>
                                    <span style="font-weight:700;color:var(--text-main);margin-left:4px;">{{ $review->rating }}/5</span>
                                </p>
                                @if($review->body)
                                    <p style="font-size:0.78rem;color:var(--text-muted);margin-top:0.2rem;line-height:1.5;">
                                        {{ Str::limit($review->body, 80) }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

<div class="panel anim-fade-up anim-delay-4" style="margin-bottom:2rem;">
    <div class="panel-header" style="margin-bottom:1rem;">
        <h3 class="panel-title">
            <i class="fa-solid fa-bolt" style="color:var(--primary);margin-right:8px;"></i>
            {{ __('messages.quick_access') }}
        </h3>
    </div>
    <div class="quick-actions-grid">
        <a href="{{ route('favorites.index') }}" class="quick-action-card">
            <div class="qa-icon"><i class="fa-solid fa-heart"></i></div>
            <span>{{ __('messages.manage_favorites') }}</span>
        </a>
        <a href="{{ route('home') }}" class="quick-action-card">
            <div class="qa-icon"><i class="fa-solid fa-compass"></i></div>
            <span>{{ __('messages.explore_activities') }}</span>
        </a>
        <a href="{{ route('profile.edit') }}" class="quick-action-card">
            <div class="qa-icon"><i class="fa-solid fa-user-pen"></i></div>
            <span>{{ __('messages.edit_profile') }}</span>
        </a>
        <a href="{{ route('customer.reviews.index') }}" class="quick-action-card">
            <div class="qa-icon"><i class="fa-solid fa-star"></i></div>
            <span>إضافة تقييم</span>
        </a>
        <a href="{{ route('home') }}" class="quick-action-card">
            <div class="qa-icon"><i class="fa-solid fa-magnifying-glass"></i></div>
            <span>{{ __('messages.search_activities') }}</span>
        </a>
    </div>
</div>

<script>
    function animateCounter(elementId, targetValue, isDecimal = false) {
        const element = document.getElementById(elementId);
        if (!element || targetValue === null || targetValue === undefined) return;

        const target = parseFloat(targetValue) || 0;
        if (target === 0) {
            element.textContent = isDecimal ? '0.0' : '0';
            return;
        }

        let current = 0;
        const duration = 1200;
        const steps = 40;
        const increment = target / steps;
        const interval = Math.floor(duration / steps);

        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            element.textContent = isDecimal
                ? current.toFixed(1)
                : Math.floor(current).toLocaleString('es-ES');
        }, interval);
    }

    document.addEventListener('DOMContentLoaded', () => {
        animateCounter('counterFavorites', {{ $stats['favorites_count'] ?? 0 }});
        animateCounter('counterReviews', {{ $stats['reviews_count'] ?? 0 }});
        @if(isset($stats['bookings_count']))
        animateCounter('counterBookings', {{ $stats['bookings_count'] ?? 0 }});
        @endif
    });
</script>

@endsection
