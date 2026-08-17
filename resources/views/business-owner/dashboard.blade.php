@extends('layouts.business-owner')

@section('content')


@if(!$business)
    <div class="alert-box alert-warning anim-scale-in anim-delay-1">
        <span class="alert-icon"><i class="fa-solid fa-triangle-exclamation"></i></span>
        <div class="alert-content">
            <div>
                <strong>{{ __('messages.no_business_title') }}</strong>
                <span style="display:block;font-size:0.8rem;margin-top:2px;">{{ __('messages.no_business_desc') }}</span>
            </div>
            <a href="{{ route('business-owner.business.create') }}" class="btn-primary" style="font-size:0.8rem;padding:0.5rem 1.5rem;">
                <i class="fa-solid fa-circle-plus"></i> {{ __('messages.create_business_now') }}
            </a>
        </div>
    </div>
@else
    <div class="welcome-card anim-scale-in anim-delay-1">
        <div class="welcome-text">
            <div class="greeting">{{ __('messages.dashboard') }}</div>
            <h1>{{ __('messages.hello') }}, {{ $user->name }}!</h1>
            <p>
                @if($user->last_login_at)
                    {{ __('messages.last_access') }}: <strong>{{ $user->last_login_at->diffForHumans() }}</strong> &middot;
                @endif
                {{ __('messages.your_business') }}
                <strong style="color:#fff;">{{ $business->business_name }}</strong>
                @if($business->verified)
                    <span style="display:inline-flex;align-items:center;gap:3px;background:rgba(5,150,105,0.2);color:#6ee7b7;padding:2px 10px;border-radius:12px;font-size:0.7rem;margin-left:6px;">
                        <i class="fa-solid fa-certificate"></i> {{ __('messages.verified') }}
                    </span>
                @else
                    <span style="display:inline-flex;align-items:center;gap:3px;background:rgba(217,119,6,0.2);color:#fcd34d;padding:2px 10px;border-radius:12px;font-size:0.7rem;margin-left:6px;">
                        <i class="fa-solid fa-clock"></i> {{ __('messages.not_verified') }}
                    </span>
                @endif
                — {{ __('messages.ready_for_visitors') }}
            </p>
        </div>
        <div class="welcome-actions">
            <a href="{{ route('business-owner.listings.create') }}" class="btn-primary">
                <i class="fa-solid fa-circle-plus"></i> {{ __('messages.add_new_activity') }}
            </a>
            <a href="{{ route('business-owner.business.edit') }}" class="btn-ghost">
                <i class="fa-solid fa-pen-to-square"></i> {{ __('messages.edit_business') }}
            </a>
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card anim-fade-up anim-delay-2">
            <div class="stat-header">
                <span class="stat-label">{{ __('messages.total_activities_label') }}</span>
                <div class="stat-icon amber"><i class="fa-solid fa-briefcase"></i></div>
            </div>
            <div class="stat-value" id="counterTotal">{{ $stats['total_listings'] }}</div>
            <div class="stat-sub">{{ __('messages.all_added_activities') }}</div>
        </div>

        <div class="stat-card anim-fade-up anim-delay-2">
            <div class="stat-header">
                <span class="stat-label">{{ __('messages.published_label') }}</span>
                <div class="stat-icon green"><i class="fa-solid fa-check-circle"></i></div>
            </div>
            <div class="stat-value" id="counterPublished">{{ $stats['published_listings'] }}</div>
            <div class="stat-sub">
                @if($stats['total_listings'] > 0)
                    <span class="stat-trend up"><i class="fa-solid fa-arrow-up"></i> {{ round(($stats['published_listings'] / $stats['total_listings']) * 100) }}%</span> {{ __('messages.of_total_label') }}
                @else
                    {{ __('messages.none_label') }}
                @endif
            </div>
        </div>

        <div class="stat-card anim-fade-up anim-delay-2">
            <div class="stat-header">
                <span class="stat-label">{{ __('messages.pending_review_label') }}</span>
                <div class="stat-icon blue"><i class="fa-solid fa-clock-rotate-left"></i></div>
            </div>
            <div class="stat-value" id="counterPending">{{ $stats['pending_listings'] }}</div>
            <div class="stat-sub">{{ __('messages.waiting_for_review') }}</div>
        </div>

        <div class="stat-card anim-fade-up anim-delay-2">
            <div class="stat-header">
                <span class="stat-label">{{ __('messages.average_rating_label') }}</span>
                <div class="stat-icon purple"><i class="fa-solid fa-star"></i></div>
            </div>
            <div class="stat-value" id="counterRating">{{ number_format($stats['average_rating'], 1) }}</div>
            <div class="stat-sub">
                <span class="stat-trend {{ $stats['average_rating'] >= 3 ? 'up' : 'down' }}">
                    @if($stats['average_rating'] >= 3)
                        <i class="fa-solid fa-arrow-up"></i>
                    @else
                        <i class="fa-solid fa-arrow-down"></i>
                    @endif
                    / 5.0
                </span>
            </div>
        </div>

        <div class="stat-card anim-fade-up anim-delay-2">
            <div class="stat-header">
                <span class="stat-label">{{ __('messages.total_views_label') }}</span>
                <div class="stat-icon rose"><i class="fa-solid fa-eye"></i></div>
            </div>
            <div class="stat-value" id="counterViews">{{ $stats['total_views'] }}</div>
            <div class="stat-sub">{{ __('messages.total_views_sub') }}</div>
        </div>
    </div>

    <div class="panels-grid">
        <div class="panel anim-fade-up anim-delay-3">
            <div class="panel-header">
                <h3 class="panel-title">
                    <i class="fa-solid fa-clock" style="color:var(--primary);margin-right:8px;"></i>
                    {{ __('messages.recent_activities_title') }}
                </h3>
                <a href="{{ route('business-owner.listings.index') }}" class="panel-link">
                    {{ __('messages.view_all') }} <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
            <div>
                @if($recentListings->isEmpty())
                    <div class="empty-state">
                        <div class="empty-icon"><i class="fa-solid fa-inbox"></i></div>
                        <h4>{{ __('messages.no_activities_yet') }}</h4>
                        <p>{{ __('messages.no_activities_desc') }}</p>
                        <a href="{{ route('business-owner.listings.create') }}" class="btn-primary" style="font-size:0.8rem;padding:0.5rem 1.5rem;">
                            <i class="fa-solid fa-circle-plus"></i> {{ __('messages.add_activity') }}
                        </a>
                    </div>
                @else
                    @foreach($recentListings as $listing)
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
                                    <h4 title="{{ $listing->title }}">{{ $listing->title }}</h4>
                                    <p>{{ $listing->city?->name ?? __('messages.not_specified') }} · {{ number_format($listing->average_rating, 1) }} <i class="fa-solid fa-star" style="color:#f59e0b;font-size:0.65rem;"></i></p>
                                </div>
                            </div>
                            <span class="list-badge
                                @if($listing->status == 'published') badge-success
                                @elseif($listing->status == 'submitted') badge-warning
                                @elseif($listing->status == 'draft') badge-neutral
                                @else badge-danger @endif">
                                @if($listing->status == 'published') {{ __('messages.published') }}
                                @elseif($listing->status == 'submitted') {{ __('messages.under_review') }}
                                @elseif($listing->status == 'draft') {{ __('messages.draft') }}
                                @else {{ $listing->status }}
                                @endif
                            </span>
                            <div class="list-actions">
                                <a href="{{ route('business-owner.listings.edit', $listing) }}" class="list-action-btn" title="{{ __('messages.edit') }}">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                @if($listing->status == 'draft')
                                    <form action="{{ route('business-owner.listings.submit', $listing) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="list-action-btn" title="{{ __('messages.request_review') }}" style="cursor:pointer;">
                                            <i class="fa-solid fa-paper-plane"></i>
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('business-owner.listings.destroy', $listing) }}" method="POST" style="display:inline;"
                                      onsubmit="return confirm('{{ __('messages.confirm_delete') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="list-action-btn danger" title="{{ __('messages.delete') }}" style="cursor:pointer;">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <div class="panel anim-fade-up anim-delay-3">
            <div class="panel-header">
                <h3 class="panel-title">
                    <i class="fa-solid fa-building" style="color:var(--primary);margin-right:8px;"></i>
                    {{ __('messages.business_summary') }}
                </h3>
                <a href="{{ route('business-owner.business.edit') }}" class="panel-link">
                    {{ __('messages.edit') }} <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
            <div>
                <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.5rem;padding:1rem;background:var(--bg-tertiary);border-radius:var(--radius);">
                    @if($business->logo)
                        <img src="{{ asset('storage/' . $business->logo) }}" alt="{{ __('messages.logo') }}"
                             style="width:56px;height:56px;object-fit:cover;border-radius:50%;border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,0.06);">
                    @else
                        <div style="width:56px;height:56px;border-radius:50%;background:var(--bg-main);border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,0.06);display:flex;align-items:center;justify-content:center;font-size:1.4rem;color:var(--text-muted);">
                            <i class="fa-solid fa-building"></i>
                        </div>
                    @endif
                    <div>
                        <h4 style="font-weight:750;font-size:1.05rem;color:var(--text-main);">{{ $business->business_name }}</h4>
                        <p style="font-size:0.8rem;color:var(--text-muted);">{{ $business->type?->name ?? __('messages.type_not_specified') }}</p>
                    </div>
                </div>

                <div style="display:flex;flex-direction:column;gap:0.7rem;">
                    <div class="list-row" style="padding:0.6rem 0;">
                        <span style="font-size:0.82rem;color:var(--text-muted);display:flex;align-items:center;gap:0.5rem;">
                            <i class="fa-solid fa-envelope" style="width:18px;text-align:center;"></i> {{ __('messages.email_label') }}
                        </span>
                        <span style="font-weight:600;font-size:0.85rem;color:var(--text-main);">{{ $business->email ?? $user->email }}</span>
                    </div>
                    <div class="list-row" style="padding:0.6rem 0;">
                        <span style="font-size:0.82rem;color:var(--text-muted);display:flex;align-items:center;gap:0.5rem;">
                            <i class="fa-solid fa-phone" style="width:18px;text-align:center;"></i> {{ __('messages.phone_label') }}
                        </span>
                        <span style="font-weight:600;font-size:0.85rem;color:var(--text-main);">{{ $business->phone ?? __('messages.not_specified') }}</span>
                    </div>
                    <div class="list-row" style="padding:0.6rem 0;">
                        <span style="font-size:0.82rem;color:var(--text-muted);display:flex;align-items:center;gap:0.5rem;">
                            <i class="fa-solid fa-city" style="width:18px;text-align:center;"></i> {{ __('messages.city_label') }}
                        </span>
                        <span style="font-weight:600;font-size:0.85rem;color:var(--text-main);">{{ $business->city?->name ?? __('messages.not_specified') }}</span>
                    </div>
                    <div class="list-row" style="padding:0.6rem 0;border-bottom:none;">
                        <span style="font-size:0.82rem;color:var(--text-muted);display:flex;align-items:center;gap:0.5rem;">
                            <i class="fa-solid fa-shield-halved" style="width:18px;text-align:center;"></i> {{ __('messages.status_label') }}
                        </span>
                        <span class="list-badge {{ $business->verified ? 'badge-success' : 'badge-warning' }}">
                            @if($business->verified)
                                <i class="fa-solid fa-certificate"></i> {{ __('messages.verified') }}
                            @else
                                <i class="fa-solid fa-clock"></i> {{ __('messages.not_verified') }}
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel anim-fade-up anim-delay-4" style="margin-bottom:2rem;">
        <div class="panel-header" style="margin-bottom:1rem;">
            <h3 class="panel-title">
                <i class="fa-solid fa-bolt" style="color:var(--primary);margin-right:8px;"></i>
                {{ __('messages.quick_access_title') }}
            </h3>
        </div>
        <div class="quick-actions-grid">
            <a href="{{ route('business-owner.listings.create') }}" class="quick-action-card">
                <div class="qa-icon"><i class="fa-solid fa-circle-plus"></i></div>
                <span>{{ __('messages.add_activity') }}</span>
            </a>
            <a href="{{ route('business-owner.listings.index') }}" class="quick-action-card">
                <div class="qa-icon"><i class="fa-solid fa-list-check"></i></div>
                <span>{{ __('messages.all_activities') }}</span>
            </a>
            <a href="{{ route('business-owner.business.edit') }}" class="quick-action-card">
                <div class="qa-icon"><i class="fa-solid fa-building-columns"></i></div>
                <span>{{ __('messages.edit_business_short') }}</span>
            </a>
            <a href="{{ route('profile.edit') }}" class="quick-action-card">
                <div class="qa-icon"><i class="fa-solid fa-user-pen"></i></div>
                <span>{{ __('messages.profile') }}</span>
            </a>
        </div>
    </div>
@endif

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
        animateCounter('counterTotal', {{ $stats['total_listings'] ?? 0 }});
        animateCounter('counterPublished', {{ $stats['published_listings'] ?? 0 }});
        animateCounter('counterPending', {{ $stats['pending_listings'] ?? 0 }});
        animateCounter('counterRating', {{ $stats['average_rating'] ?? 0 }}, true);
        animateCounter('counterViews', {{ $stats['total_views'] ?? 0 }});
    });
</script>

@endsection