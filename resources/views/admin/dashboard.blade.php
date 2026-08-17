@extends('layouts.admin')

@section('content')

<div class="welcome-banner anim-scale-in anim-delay-1">
    <div class="welcome-banner-content">
        <div class="welcome-text">
            <div class="label">{{ __('messages.control_center') }}</div>
            <h2>{{ __('messages.welcome_back') }}, {{ auth()->user()->name }}</h2>
            <p>
                {{ __('messages.admin_welcome_text') }}
            </p>
            <div class="status-pill">
                <span class="status-dot"></span>
                {{ __('messages.system_active') }}
            </div>
        </div>
        <div class="welcome-actions">
            <a href="{{ route('admin.listings.index') }}" class="btn btn-primary-white">
                <i class="fa-solid fa-clipboard-check"></i>
                {{ __('messages.review_pending_activities') }}
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-white">
                <i class="fa-solid fa-users-gear"></i>
                {{ __('messages.manage_users') }}
            </a>
        </div>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card anim-fade-up anim-delay-2">
        <div class="stat-card-top">
            <span class="stat-card-label">{{ __('messages.total_users') }}</span>
            <div class="stat-card-icon blue">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>
        <div class="stat-card-value" id="counterUsers">{{ $stats['total_users'] ?? 0 }}</div>
        <div class="stat-card-footer">
            @if(isset($stats['active_users']))
                <span class="stat-trend up"><i class="fa-solid fa-arrow-up"></i> {{ $stats['active_users'] }} {{ __('messages.active') }}</span>
            @endif
        </div>
    </div>

    <div class="stat-card anim-fade-up anim-delay-2">
        <div class="stat-card-top">
            <span class="stat-card-label">{{ __('messages.registered_businesses') }}</span>
            <div class="stat-card-icon indigo">
                <i class="fa-solid fa-building"></i>
            </div>
        </div>
        <div class="stat-card-value" id="counterBusinesses">{{ $stats['total_businesses'] ?? 0 }}</div>
        <div class="stat-card-footer">{{ __('messages.all_businesses') }}</div>
    </div>

    <div class="stat-card anim-fade-up anim-delay-2">
        <div class="stat-card-top">
            <span class="stat-card-label">{{ __('messages.published_activities') }}</span>
            <div class="stat-card-icon green">
                <i class="fa-solid fa-check-circle"></i>
            </div>
        </div>
        <div class="stat-card-value" id="counterPublished">{{ $stats['published_listings'] ?? 0 }}</div>
        <div class="stat-card-footer">
            @if(isset($stats['total_listings']) && $stats['total_listings'] > 0)
                <span class="stat-trend up">{{ round(($stats['published_listings'] / $stats['total_listings']) * 100) }}% {{ __('messages.of_total') }}</span>
            @endif
        </div>
    </div>

    <div class="stat-card anim-fade-up anim-delay-2">
        <div class="stat-card-top">
            <span class="stat-card-label">{{ __('messages.pending_reviews') }}</span>
            <div class="stat-card-icon amber">
                <i class="fa-solid fa-clock"></i>
            </div>
        </div>
        <div class="stat-card-value" id="counterPending" style="{{ ($stats['pending_listings'] ?? 0) > 0 ? 'color:#d97706;' : '' }}">
            {{ $stats['pending_listings'] ?? 0 }}
        </div>
        <div class="stat-card-footer">
            @if(($stats['pending_listings'] ?? 0) > 0)
                <span class="stat-trend warn"><i class="fa-solid fa-exclamation-triangle"></i> {{ __('messages.need_review') }}</span>
            @else
                <span style="color:#10b981;">{{ __('messages.no_pending') }} ✓</span>
            @endif
        </div>
    </div>
</div>

<div class="stats-grid-3">
    <div class="stat-card anim-fade-up anim-delay-3">
        <div class="stat-card-top">
            <span class="stat-card-label">{{ __('messages.total_activities') }}</span>
            <div class="stat-card-icon rose">
                <i class="fa-solid fa-layer-group"></i>
            </div>
        </div>
        <div class="stat-card-value" id="counterTotalListings">{{ $stats['total_listings'] ?? 0 }}</div>
        <div class="stat-card-footer">{{ __('messages.all_platform_activities') }}</div>
    </div>

    <div class="stat-card anim-fade-up anim-delay-3">
        <div class="stat-card-top">
            <span class="stat-card-label">{{ __('messages.total_reviews') }}</span>
            <div class="stat-card-icon cyan">
                <i class="fa-solid fa-star"></i>
            </div>
        </div>
        <div class="stat-card-value" id="counterReviews">{{ $stats['total_reviews'] ?? 0 }}</div>
        <div class="stat-card-footer">
            @if(isset($stats['pending_reviews']) && $stats['pending_reviews'] > 0)
                <span class="stat-trend warn">{{ $stats['pending_reviews'] }} {{ __('messages.pending_review') }}</span>
            @endif
        </div>
    </div>

    <div class="stat-card anim-fade-up anim-delay-3">
        <div class="stat-card-top">
            <span class="stat-card-label">{{ __('messages.active_users') }}</span>
            <div class="stat-card-icon green">
                <i class="fa-solid fa-user-check"></i>
            </div>
        </div>
        <div class="stat-card-value" id="counterActiveUsers">{{ $stats['active_users'] ?? 0 }}</div>
        <div class="stat-card-footer">{{ __('messages.last_30_days') }}</div>
    </div>
</div>

<div class="content-grid">
    <div class="panel anim-fade-up anim-delay-4">
        <div class="panel-header">
            <h3 class="panel-title">
                <i class="fa-solid fa-clock" style="color: var(--primary-400);"></i>
                {{ __('messages.recent_activities') }}
            </h3>
            <a href="{{ route('admin.listings.index') }}" class="panel-link">
                {{ __('messages.view_all') }} <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
        <div class="panel-body">
            @if(isset($recentListings) && $recentListings->count() > 0)
                @foreach($recentListings as $listing)
                    <div class="list-item">
                        <div class="list-item-left">
                            <div class="list-avatar">
                                <i class="fa-solid fa-briefcase"></i>
                            </div>
                            <div class="list-info">
                                <h4 title="{{ $listing->title }}">{{ $listing->title }}</h4>
                                <p>{{ $listing->business?->business_name ?? __('messages.no_business') }}</p>
                            </div>
                        </div>
                        <span class="status-badge
                            @if($listing->status == 'published') success
                            @elseif($listing->status == 'submitted') warning
                            @elseif($listing->status == 'draft') neutral
                            @else danger @endif">
                            @if($listing->status == 'published') {{ __('messages.published') }}
                            @elseif($listing->status == 'submitted') {{ __('messages.under_review') }}
                            @elseif($listing->status == 'draft') {{ __('messages.draft') }}
                            @else {{ $listing->status }}
                            @endif
                        </span>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <i class="fa-solid fa-inbox"></i>
                    <h4>{{ __('messages.no_activities') }}</h4>
                    <p>{{ __('messages.recent_activities_empty') }}</p>
                </div>
            @endif
        </div>
    </div>

    <div class="panel anim-fade-up anim-delay-4">
        <div class="panel-header">
            <h3 class="panel-title">
                <i class="fa-solid fa-user-plus" style="color: var(--primary-400);"></i>
                {{ __('messages.recent_users') }}
            </h3>
            <a href="{{ route('admin.users.index') }}" class="panel-link">
                {{ __('messages.view_all') }} <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
        <div class="panel-body">
            @if(isset($recentUsers) && $recentUsers->count() > 0)
                @foreach($recentUsers as $user)
                    <div class="list-item">
                        <div class="list-item-left">
                            <div class="list-avatar">
                                {{ Str::upper(Str::substr($user->first_name ?? $user->name, 0, 1)) }}
                            </div>
                            <div class="list-info">
                                <h4 title="{{ $user->first_name ?? '' }} {{ $user->last_name ?? '' }}">
                                    {{ $user->first_name ?? '' }} {{ $user->last_name ?? $user->name ?? __('messages.user') }}
                                </h4>
                                <p>{{ $user->email }}</p>
                            </div>
                        </div>
                        <span class="status-badge
                            @if(($user->role ?? '') == 'admin') info
                            @elseif(($user->role ?? '') == 'business_owner') neutral
                            @else neutral @endif">
                            @if(($user->role ?? '') == 'admin')
                                <i class="fa-solid fa-shield"></i> {{ __('messages.admin') }}
                            @elseif(($user->role ?? '') == 'business_owner')
                                <i class="fa-solid fa-building"></i> {{ __('messages.business_owner') }}
                            @else
                                <i class="fa-solid fa-user"></i> {{ __('messages.user') }}
                            @endif
                        </span>
                    </div>
                @endforeach
            @else
                <div class="empty-state">
                    <i class="fa-solid fa-users-slash"></i>
                    <h4>{{ __('messages.no_new_users') }}</h4>
                    <p>{{ __('messages.recent_users_empty') }}</p>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="quick-actions-grid anim-fade-up anim-delay-5">
    <a href="{{ route('admin.listings.index') }}" class="quick-action-card">
        <div class="quick-action-icon">
            <i class="fa-solid fa-clipboard-check"></i>
        </div>
        <span class="quick-action-label">{{ __('messages.review_activities') }}</span>
    </a>
    <a href="{{ route('admin.reviews.index') }}" class="quick-action-card">
        <div class="quick-action-icon">
            <i class="fa-solid fa-star"></i>
        </div>
        <span class="quick-action-label">{{ __('messages.manage_reviews') }}</span>
    </a>
    <a href="{{ route('admin.users.index') }}" class="quick-action-card">
        <div class="quick-action-icon">
            <i class="fa-solid fa-users-gear"></i>
        </div>
        <span class="quick-action-label">{{ __('messages.manage_users') }}</span>
    </a>
    <a href="{{ route('home') }}" class="quick-action-card">
        <div class="quick-action-icon">
            <i class="fa-solid fa-globe"></i>
        </div>
        <span class="quick-action-label">{{ __('messages.view_site') }}</span>
    </a>
</div>

<script>
    function animateCounter(elementId, targetValue) {
        const element = document.getElementById(elementId);
        if (!element || targetValue === null || targetValue === undefined) return;

        const target = parseInt(targetValue) || 0;
        if (target === 0) {
            element.textContent = '0';
            return;
        }

        let current = 0;
        const duration = 1000;
        const steps = 35;
        const increment = target / steps;
        const interval = Math.floor(duration / steps);

        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                current = target;
                clearInterval(timer);
            }
            element.textContent = Math.floor(current).toLocaleString('es-ES');
        }, interval);
    }

    document.addEventListener('DOMContentLoaded', () => {
        animateCounter('counterUsers', {{ $stats['total_users'] ?? 0 }});
        animateCounter('counterBusinesses', {{ $stats['total_businesses'] ?? 0 }});
        animateCounter('counterPublished', {{ $stats['published_listings'] ?? 0 }});
        animateCounter('counterPending', {{ $stats['pending_listings'] ?? 0 }});
        animateCounter('counterTotalListings', {{ $stats['total_listings'] ?? 0 }});
        animateCounter('counterReviews', {{ $stats['total_reviews'] ?? 0 }});
        animateCounter('counterActiveUsers', {{ $stats['active_users'] ?? 0 }});
    });
</script>

@endsection
