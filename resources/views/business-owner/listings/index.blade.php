{{-- resources/views/business-owner/listings/index.blade.php --}}
@extends('layouts.business-owner')

@section('content')

<header class="top-bar anim-fade-up">
    <div class="page-title">{{ __('messages.listings_title') }}</div>
    <div class="top-actions">
        <a href="{{ route('business-owner.listings.create') }}" class="btn-primary" style="padding:0.65rem 1.8rem; font-size:0.85rem;">
            <i class="fa-solid fa-circle-plus"></i> {{ __('messages.add_new_listing') }}
        </a>
    </div>
</header>

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


@if(!$listings->isEmpty() || request()->hasAny(['search', 'status']))
<div class="panel anim-fade-up anim-delay-1" style="margin-bottom: 1.5rem; padding: 1.2rem 1.8rem;">
    <form method="GET" action="{{ route('business-owner.listings.index') }}" style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
        <div class="search-box" style="flex: 1; min-width: 220px; margin:0;">
            <i class="fa-solid fa-magnifying-glass" style="color:var(--text-muted); font-size:0.85rem;"></i>
            <input type="text" name="search" placeholder="{{ __('messages.search_listing_placeholder') }}" value="{{ request('search') }}"
                   style="background:transparent; border:none; outline:none; width:100%; font-weight:500; color:var(--text-main); font-size:0.88rem;">
        </div>

        <select name="status" style="
            padding: 0.55rem 2.2rem 0.55rem 1rem;
            border-radius: 30px;
            background: var(--bg-main);
            font-weight: 500;
            font-size: 0.88rem;
            color: var(--text-main);
            cursor: pointer;
            appearance: none;
            -webkit-appearance: none;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"12\" height=\"12\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"%2394a3b8\" stroke-width=\"2\"><path d=\"M6 9l6 6 6-6\"/></svg>');
            background-repeat: no-repeat;
            background-position: left 12px center;
            outline: none;
            transition: var(--transition);
        " onchange="this.form.submit()">
            <option value="">{{ __('messages.all_statuses') }}</option>
            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>{{ __('messages.status_published') }}</option>
            <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>{{ __('messages.status_submitted') }}</option>
            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>{{ __('messages.status_draft') }}</option>
            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>{{ __('messages.status_rejected') }}</option>
        </select>

        <button type="submit" class="btn-ghost" style="padding:0.55rem 1.5rem; font-size:0.85rem; color:var(--text-secondary); margin:0;">
            <i class="fa-solid fa-filter"></i> {{ __('messages.filter') }}
        </button>

        @if(request()->hasAny(['search', 'status']))
            <a href="{{ route('business-owner.listings.index') }}" class="btn-ghost" style="padding:0.55rem 1.2rem; font-size:0.85rem; color:var(--text-muted); margin:0;">
                <i class="fa-solid fa-xmark"></i> {{ __('messages.clear_filters') }}
            </a>
        @endif
    </form>
</div>
@endif

@if($listings->isEmpty())
    <div class="panel anim-scale-in anim-delay-1" style="text-align:center; padding:5rem 2rem;">
        <div style="max-width: 400px; margin: 0 auto;">
            <div style="font-size: 3.5rem; color: var(--border); margin-bottom: 1.5rem;">
                <i class="fa-solid fa-umbrella-beach"></i>
            </div>
            <h4 style="font-size: 1.2rem; font-weight: 700; color: var(--text-main); margin-bottom: 0.8rem;">{{ __('messages.no_listings') }}</h4>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 2rem; line-height: 1.6;">
                {{ __('messages.no_listings_subtitle') }}
            </p>
            <a href="{{ route('business-owner.listings.create') }}" class="btn-primary" style="display:inline-flex; padding:0.8rem 2.2rem;">
                <i class="fa-solid fa-circle-plus"></i> {{ __('messages.add_listing_button') }}
            </a>
        </div>
    </div>
@else
    <div class="panel anim-fade-up anim-delay-2" style="padding: 0; overflow: hidden;">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; min-width: 900px;">
                <thead>
                    <tr style="background: var(--bg-tertiary);">
                        <th style="padding: 1rem 1.5rem; text-align: right; font-size: 0.78rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">{{ __('messages.listing') }}</th>
                        <th style="padding: 1rem 1rem; text-align: right; font-size: 0.78rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">{{ __('messages.location') }}</th>
                        <th style="padding: 1rem 1rem; text-align: right; font-size: 0.78rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">{{ __('messages.status') }}</th>
                        <th style="padding: 1rem 1rem; text-align: center; font-size: 0.78rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">{{ __('messages.rating') }}</th>
                        <th style="padding: 1rem 1rem; text-align: center; font-size: 0.78rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">{{ __('messages.date_added') }}</th>
                        <th style="padding: 1rem 1.5rem; text-align: center; font-size: 0.78rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">{{ __('messages.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($listings as $listing)
                    <tr style="transition: var(--transition);"
                        onmouseover="this.style.backgroundColor='var(--bg-secondary)'"
                        onmouseout="this.style.backgroundColor='transparent'">
                        <td style="padding: 1rem 1.5rem;">
                            <a href="{{ route('business-owner.listings.edit', $listing) }}" style="display: flex; align-items: center; gap: 1rem; text-decoration: none; color: inherit;">
                                <div style="width: 44px; height: 44px; border-radius: 8px; overflow: hidden; background: var(--bg-tertiary); flex-shrink: 0;">
                                    @php
                                        $imageUrl = $listing->getSignedImageUrl('thumb', 5);
                                    @endphp
                                    @if($imageUrl)
                                        <img src="{{ $imageUrl }}" alt="{{ $listing->title }}" style="width:100%; height:100%; object-fit:cover;">
                                    @else
                                        <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color: var(--text-muted); font-size:0.9rem;">
                                            <i class="fa-solid fa-mountain-sun"></i>
                                        </div>
                                    @endif
                                </div>
                                <div style="min-width: 0;">
                                    <div style="font-weight: 650; color: var(--text-main); font-size: 0.92rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px;">{{ $listing->title }}</div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 2px;">{{ Str::limit($listing->short_description, 50) }}</div>
                                </div>
                            </a>
                        </td>
                        <td style="padding: 1rem 1rem; font-size: 0.85rem; color: var(--text-secondary); white-space: nowrap;">
                            <i class="fa-solid fa-location-dot" style="color: var(--primary); font-size: 0.7rem; margin-left: 4px;"></i>
                            {{ $listing->city?->name ?? '—' }}، {{ $listing->country?->name ?? '' }}
                        </td>
                        <td style="padding: 1rem 1rem;">
                            <span class="list-badge" style="
                                @if($listing->status == 'published') background:#ecfdf5; color:#059669;
                                @elseif($listing->status == 'submitted') background:#fffbeb; color:#d97706;
                                @elseif($listing->status == 'draft') background:#f1f5f9; color:#475569;
                                @else background:#fef2f2; color:#dc2626; @endif
                                font-size:0.72rem; padding:0.3rem 0.9rem; display:inline-flex; align-items:center; gap:5px;">
                                @if($listing->status == 'published') <i class="fa-solid fa-check-circle"></i> {{ __('messages.badge_published') }}
                                @elseif($listing->status == 'submitted') <i class="fa-solid fa-clock"></i> {{ __('messages.badge_submitted') }}
                                @elseif($listing->status == 'draft') <i class="fa-solid fa-pen-to-square"></i> {{ __('messages.badge_draft') }}
                                @else <i class="fa-solid fa-circle-xmark"></i> {{ __('messages.badge_rejected') }}
                                @endif
                            </span>
                        </td>
                        <td style="padding: 1rem 1rem; text-align: center; font-weight: 650; font-size: 0.9rem; color: var(--text-main);">
                            <span style="display: inline-flex; align-items: center; gap: 3px;">
                                {{ number_format($listing->average_rating, 1) }}
                                <i class="fa-solid fa-star" style="color: #f59e0b; font-size: 0.7rem;"></i>
                            </span>
                        </td>
                        <td style="padding: 1rem 1rem; text-align: center; font-size: 0.8rem; color: var(--text-muted); white-space: nowrap;">
                            {{ $listing->created_at->format('Y-m-d') }}
                        </td>
                        <td style="padding: 1rem 1.5rem; text-align: center;">
                            <div style="display: flex; gap: 0.4rem; justify-content: center;">
                                <a href="{{ route('business-owner.listings.edit', $listing) }}"
                                   class="list-action-btn" title="{{ __('messages.edit') }}" style="width:34px; height:34px;">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                @if($listing->status == 'draft')
                                    <form action="{{ route('business-owner.listings.submit', $listing) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="list-action-btn" title="{{ __('messages.submit_for_review') }}" style="cursor:pointer; width:34px; height:34px;">
                                            <i class="fa-solid fa-paper-plane"></i>
                                        </button>
                                    </form>
                                @endif
                                <form action="{{ route('business-owner.listings.destroy', $listing) }}"
                                      method="POST"
                                      onsubmit="return confirm('{{ __('messages.delete_confirmation') }}')"
                                      style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="list-action-btn danger" title="{{ __('messages.delete') }}" style="width:34px; height:34px; cursor:pointer;">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if($listings->hasPages())
        <div class="anim-fade-up anim-delay-3" style="display:flex; justify-content:center; margin-top:2rem;">
            <nav style="display:flex; gap:0.5rem; align-items:center; flex-wrap:wrap; background:var(--bg-main); border-radius:30px; padding:0.5rem 1.5rem;">
                @if($listings->onFirstPage())
                    <span style="color:var(--text-muted); padding:0.4rem 0.8rem; font-size:0.85rem; opacity:0.5;">
                        <i class="fa-solid fa-chevron-right"></i>
                    </span>
                @else
                    <a href="{{ $listings->previousPageUrl() }}" style="color:var(--text-secondary); padding:0.4rem 0.8rem; font-size:0.85rem; text-decoration:none; border-radius:20px; transition:var(--transition);">
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                @endif

                @foreach($listings->links()->elements[0] ?? [] as $page => $url)
                    @if(is_string($page))
                        <span style="color:var(--text-muted); padding:0.4rem 0.3rem;">...</span>
                    @else
                        @if($page == $listings->currentPage())
                            <span style="background:var(--primary); color:#fff; padding:0.4rem 0.9rem; border-radius:20px; font-weight:650; font-size:0.85rem;">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" style="color:var(--text-secondary); padding:0.4rem 0.9rem; border-radius:20px; text-decoration:none; font-weight:500; font-size:0.85rem; transition:var(--transition);"
                               onmouseover="this.style.background='var(--bg-tertiary)'"
                               onmouseout="this.style.background='transparent'">
                                {{ $page }}
                            </a>
                        @endif
                    @endif
                @endforeach

                @if($listings->hasMorePages())
                    <a href="{{ $listings->nextPageUrl() }}" style="color:var(--text-secondary); padding:0.4rem 0.8rem; font-size:0.85rem; text-decoration:none; border-radius:20px; transition:var(--transition);">
                        <i class="fa-solid fa-chevron-left"></i>
                    </a>
                @else
                    <span style="color:var(--text-muted); padding:0.4rem 0.8rem; font-size:0.85rem; opacity:0.5;">
                        <i class="fa-solid fa-chevron-left"></i>
                    </span>
                @endif
            </nav>
        </div>
    @endif
@endif

<style>
    * {
        box-shadow: none !important;
    }

    .panel {
        border: none !important;
    }

    .search-box {
        border: none !important;
    }

    select {
        border: none !important;
    }

    .btn-ghost {
        border: none !important;
    }

    .list-action-btn {
        border: none !important;
    }

    .listing-thumb,
    .listing-thumb img,
    .thumb-placeholder,
    td > div[style*="border-radius: 8px"] {
        border: none !important;
    }

    thead tr, tbody tr {
        border-bottom: none !important;
    }

    nav[style*="border-radius"] {
        border: none !important;
    }

    tr:hover {
        background-color: var(--bg-secondary);
    }

    tr .list-action-btn:hover {
        background: var(--primary-light);
    }
    tr .list-action-btn.danger:hover {
        background: #fef2f2;
    }
</style>

@endsection