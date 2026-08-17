@extends('layouts.admin')

@section('content')

<div class="stats-mini-row anim-fade-up anim-delay-1">
    <div class="stats-mini-card">
        <div class="stats-mini-icon stats-mini-icon-amber">
            <i class="fa-solid fa-clock"></i>
        </div>
        <div class="stats-mini-content">
            <div class="stats-mini-label">{{ __('messages.pending_reviews_short') }}</div>
            <div class="stats-mini-value">{{ $listings->total() }}</div>
        </div>
    </div>

    @if(isset($publishedCount))
    <div class="stats-mini-card">
        <div class="stats-mini-icon stats-mini-icon-green">
            <i class="fa-solid fa-check-circle"></i>
        </div>
        <div class="stats-mini-content">
            <div class="stats-mini-label">{{ __('messages.published_today') }}</div>
            <div class="stats-mini-value">{{ $publishedCount }}</div>
        </div>
    </div>
    @endif

    @if(isset($totalListingsCount))
    <div class="stats-mini-card">
        <div class="stats-mini-icon stats-mini-icon-blue">
            <i class="fa-solid fa-layer-group"></i>
        </div>
        <div class="stats-mini-content">
            <div class="stats-mini-label">{{ __('messages.total_activities_short') }}</div>
            <div class="stats-mini-value">{{ $totalListingsCount }}</div>
        </div>
    </div>
    @endif
</div>

<div class="filters-bar-wrapper anim-fade-up anim-delay-2">
    <div class="filters-group">
        <button class="filter-pill filter-pill-active" data-filter="all">
            <i class="fa-solid fa-list"></i>
            <span>{{ __('messages.all_filter') }}</span>
        </button>
        <button class="filter-pill" data-filter="submitted">
            <i class="fa-solid fa-clock"></i>
            <span>{{ __('messages.under_review_filter') }}</span>
        </button>
        <button class="filter-pill" data-filter="published">
            <i class="fa-solid fa-check-circle"></i>
            <span>{{ __('messages.published_filter') }}</span>
        </button>
        <button class="filter-pill" data-filter="draft">
            <i class="fa-solid fa-file"></i>
            <span>{{ __('messages.draft_filter') }}</span>
        </button>
    </div>

    <div class="sort-group">
        <label class="sort-label" for="sortSelect">{{ __('messages.sort_by') }}</label>
        <select id="sortSelect" class="sort-select">
            <option value="newest">{{ __('messages.newest') }}</option>
            <option value="oldest">{{ __('messages.oldest') }}</option>
            <option value="title_asc">{{ __('messages.name_az') }}</option>
            <option value="title_desc">{{ __('messages.name_za') }}</option>
        </select>
    </div>
</div>

@if($listings->isEmpty())
    <div class="empty-panel anim-scale-in anim-delay-2">
        <div class="empty-panel-inner">
            <div class="empty-icon-wrapper">
                <i class="fa-solid fa-clipboard-check"></i>
            </div>
            <h3 class="empty-title">{{ __('messages.no_pending_review_title') }}</h3>
            <p class="empty-description">{{ __('messages.no_pending_review_desc') }}</p>
            <a href="{{ route('admin.dashboard') }}" class="empty-action-btn">
                <i class="fa-solid fa-arrow-left"></i>
                {{ __('messages.back_to_dashboard') }}
            </a>
        </div>
    </div>
@else
    <div class="table-panel anim-scale-in anim-delay-2">
        @if($listings->hasPages())
            <div class="pagination-wrapper pagination-wrapper-top">
                <div class="pagination-info">
                    {{ __('messages.showing') }} {{ $listings->firstItem() }} - {{ $listings->lastItem() }} {{ __('messages.of_in_pagination') }} {{ $listings->total() }} {{ __('messages.activities_lowercase') }}
                </div>
                <div class="pagination-links">
                    {{ $listings->links() }}
                </div>
            </div>
        @endif

        {{-- شريط التمرير الأفقي العلوي --}}
        <div class="table-scroll-top" id="tableScrollTop">
            <div class="table-scroll-top-inner" id="tableScrollTopInner"></div>
        </div>

        <div class="table-container" id="tableContainer">
            <table class="data-table">
                <thead>
                    <tr class="data-table-header-row">
                        <th class="data-table-th">
                            <div class="th-content">{{ __('messages.activity') }}</div>
                        </th>
                        <th class="data-table-th">
                            <div class="th-content">{{ __('messages.business_owner_label') }}</div>
                        </th>
                        <th class="data-table-th">
                            <div class="th-content">
                                <i class="fa-solid fa-location-dot th-icon"></i>
                                {{ __('messages.city_label') }}
                            </div>
                        </th>
                        <th class="data-table-th">
                            <div class="th-content">{{ __('messages.status_label') }}</div>
                        </th>
                        <th class="data-table-th">
                            <div class="th-content">
                                <i class="fa-solid fa-calendar th-icon"></i>
                                {{ __('messages.date_label') }}
                            </div>
                        </th>
                        <th class="data-table-th th-actions">
                            <div class="th-content">{{ __('messages.actions_label') }}</div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($listings as $listing)
                        <tr class="data-table-row" data-status="{{ $listing->status }}">
                            <td class="data-table-td">
                                <div class="listing-cell">
                                    <a href="{{ route('admin.listings.edit', $listing) }}" class="listing-thumb-link">
                                        <div class="listing-thumb-wrapper">
                                            @php
                                                $imageUrl = $listing->getSignedImageUrl('thumb');
                                            @endphp
                                            @if($imageUrl)
                                                <img src="{{ $imageUrl }}"
                                                     class="listing-thumb-image"
                                                     alt="{{ $listing->title }}"
                                                     loading="lazy">
                                            @else
                                                <div class="listing-thumb-placeholder">
                                                    <i class="fa-solid fa-image"></i>
                                                </div>
                                            @endif
                                        </div>
                                    </a>
                                    <div class="listing-info-cell">
                                        <a href="{{ route('admin.listings.edit', $listing) }}" class="listing-title-link">
                                            <div class="listing-title" title="{{ $listing->title }}">
                                                {{ $listing->title }}
                                            </div>
                                        </a>
                                        @if($listing->category)
                                            <div class="listing-category">
                                                {{ $listing->category->name ?? '' }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="data-table-td">
                                <div class="business-cell">
                                    <div class="business-avatar">
                                        {{ Str::upper(Str::substr($listing->business?->business_name ?? '?', 0, 1)) }}
                                    </div>
                                    <span class="business-name" title="{{ $listing->business?->business_name }}">
                                        {{ $listing->business?->business_name ?? __('messages.not_specified') }}
                                    </span>
                                </div>
                            </td>

                            <td class="data-table-td">
                                <span class="city-name">
                                    {{ $listing->city?->name ?? '—' }}
                                </span>
                            </td>

                            <td class="data-table-td">
                                <span class="status-badge status-{{ $listing->status }}">
                                    @if($listing->is_hidden)
                                        <i class="fa-solid fa-eye-slash"></i> معلق
                                    @elseif($listing->status == 'submitted')
                                        <i class="fa-solid fa-clock"></i> {{ __('messages.under_review') }}
                                    @elseif($listing->status == 'under_review')
                                        <i class="fa-solid fa-magnifying-glass"></i> {{ __('messages.under_review') }}
                                    @elseif($listing->status == 'published')
                                        <i class="fa-solid fa-check-circle"></i> {{ __('messages.published') }}
                                    @elseif($listing->status == 'draft')
                                        <i class="fa-solid fa-file"></i> {{ __('messages.draft') }}
                                    @else
                                        {{ ucfirst($listing->status) }}
                                    @endif
                                </span>
                            </td>

                            <td class="data-table-td">
                                <div class="date-cell">
                                    <div class="date-main">
                                        {{ $listing->created_at->format('Y-m-d') }}
                                    </div>
                                    <div class="date-relative">
                                        {{ $listing->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </td>

                            <td class="data-table-td td-actions">
                                <div class="actions-group">
                                    <a href="{{ route('admin.listings.review', $listing) }}"
                                       class="action-btn action-btn-review"
                                       title="{{ __('messages.review_activity') }}">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.listings.edit', $listing) }}"
                                       class="action-btn action-btn-review"
                                       title="تعديل النشاط">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <a href="{{ route('admin.listings.rate.form', $listing) }}"
                                        class="action-btn action-btn-review"
                                        title="تقييم النشاط">
                                        <i class="fa-solid fa-star"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($listings->hasPages())
            <div class="pagination-wrapper">
                <div class="pagination-info">
                    {{ __('messages.showing') }} {{ $listings->firstItem() }} - {{ $listings->lastItem() }} {{ __('messages.of_in_pagination') }} {{ $listings->total() }} {{ __('messages.activities_lowercase') }}
                </div>
                <div class="pagination-links">
                    {{ $listings->links() }}
                </div>
            </div>
        @endif
    </div>
@endif

<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.75rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .page-header-left {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
    }

    .breadcrumb-nav {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .breadcrumb-link {
        color: #778da9;
        text-decoration: none;
        transition: color 0.15s ease;
    }

    .breadcrumb-link:hover {
        color: #3b71a8;
    }

    .breadcrumb-separator {
        color: #cbd5e1;
        font-weight: 400;
    }

    .breadcrumb-current {
        color: #415a77;
    }

    .page-heading {
        font-size: 1.4rem;
        font-weight: 700;
        color: #0d1b2a;
        letter-spacing: -0.3px;
        margin: 0;
    }

    .page-header-right {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .search-wrapper {
        position: relative;
    }

    .search-field {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 30px;
        padding: 0.55rem 2.5rem 0.55rem 1rem;
        font-size: 0.85rem;
        font-weight: 500;
        color: #0d1b2a;
        outline: none;
        width: 260px;
        transition: all 0.15s ease;
        font-family: 'Tajawal', 'Inter', sans-serif;
    }

    .search-field::placeholder {
        color: #94a3b8;
    }

    .search-field:focus {
        border-color: #3b71a8;
        box-shadow: 0 0 0 3px rgba(59, 113, 168, 0.08);
        width: 300px;
    }

    .search-icon-input {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 0.85rem;
        pointer-events: none;
    }

    .user-menu-trigger {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.3rem 1rem 0.3rem 0.3rem;
        border-radius: 30px;
        border: 1.5px solid #e2e8f0;
        background: #ffffff;
        cursor: pointer;
        transition: all 0.15s ease;
        text-decoration: none;
    }

    .user-menu-trigger:hover {
        border-color: #3b71a8;
        background: #f4f8fc;
    }

    .user-menu-details {
        text-align: left;
    }

    .user-menu-displayname {
        font-size: 0.82rem;
        font-weight: 600;
        color: #0d1b2a;
        line-height: 1.2;
    }

    .user-menu-rolename {
        font-size: 0.68rem;
        color: #778da9;
        font-weight: 500;
    }

    .user-menu-avatar-circle {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #1e3a5f;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.8rem;
        flex-shrink: 0;
    }

    .stats-mini-row {
        display: flex;
        gap: 1rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .stats-mini-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 1rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.85rem;
        flex: 1;
        min-width: 200px;
        transition: all 0.15s ease;
    }

    .stats-mini-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.04), 0 2px 4px rgba(0, 0, 0, 0.04);
        transform: translateY(-1px);
    }

    .stats-mini-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .stats-mini-icon-amber {
        background: #fffbeb;
        color: #f59e0b;
    }

    .stats-mini-icon-green {
        background: #ecfdf5;
        color: #10b981;
    }

    .stats-mini-icon-blue {
        background: #eff6ff;
        color: #3b82f6;
    }

    .stats-mini-content {
        display: flex;
        flex-direction: column;
    }

    .stats-mini-label {
        font-size: 0.78rem;
        color: #778da9;
        font-weight: 600;
    }

    .stats-mini-value {
        font-size: 1.5rem;
        font-weight: 800;
        color: #0d1b2a;
        line-height: 1;
    }

    .filters-bar-wrapper {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .filters-group {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .filter-pill {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        color: #415a77;
        padding: 0.5rem 1.1rem;
        border-radius: 30px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.15s ease;
        white-space: nowrap;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-family: 'Tajawal', 'Inter', sans-serif;
    }

    .filter-pill:hover {
        border-color: #3b71a8;
        color: #1e3a5f;
    }

    .filter-pill-active {
        background: #e8f1f8;
        border-color: #3b71a8;
        color: #1e3a5f;
    }

    .sort-group {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .sort-label {
        font-size: 0.8rem;
        color: #778da9;
        font-weight: 500;
        white-space: nowrap;
    }

    .sort-select {
        background: #ffffff;
        border: 1.5px solid #e2e8f0;
        border-radius: 30px;
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
        font-weight: 600;
        color: #415a77;
        outline: none;
        cursor: pointer;
        font-family: 'Tajawal', 'Inter', sans-serif;
        transition: all 0.15s ease;
    }

    .sort-select:focus {
        border-color: #3b71a8;
    }

    .table-panel {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
    }

    .table-container {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    /* شريط التمرير الأفقي العلوي */
    .table-scroll-top {
        height: 10px;
        overflow-x: auto;
        overflow-y: hidden;
        background: #f1f5f9;
        border-bottom: 1px solid #e2e8f0;
        display: none;
    }

    .table-scroll-top.active {
        display: block;
    }

    .table-scroll-top-inner {
        height: 1px;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.88rem;
    }

    .data-table-header-row {
        background: #f4f6f9;
        border-bottom: 2px solid #e2e8f0;
    }

    .data-table-th {
        padding: 1rem 1.25rem;
        text-align: left;
        font-weight: 700;
        color: #415a77;
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    .th-actions {
        text-align: center;
    }

    .th-content {
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .th-icon {
        color: #94a3b8;
    }

    .data-table-row {
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.15s ease;
    }

    .data-table-row:hover {
        background: #f0f3f7;
    }

    .data-table-td {
        padding: 1rem 1.25rem;
        vertical-align: middle;
    }

    .td-actions {
        text-align: center;
    }

    .listing-cell {
        display: flex;
        align-items: center;
        gap: 0.85rem;
    }

    .listing-thumb-wrapper {
        flex-shrink: 0;
    }

    .listing-thumb-link {
        display: block;
        line-height: 0;
        border-radius: 10px;
    }

    .listing-thumb-link:hover .listing-thumb-image,
    .listing-thumb-link:hover .listing-thumb-placeholder {
        border-color: #3b71a8;
        box-shadow: 0 0 0 2px rgba(59, 113, 168, 0.15);
    }

    .listing-thumb-image {
        width: 44px;
        height: 44px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid #edf2f7;
        display: block;
    }

    .listing-thumb-placeholder {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: #e8f1f8;
        border: 1px solid #edf2f7;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #3b71a8;
        font-size: 0.9rem;
    }

    .listing-info-cell {
        min-width: 0;
    }

    .listing-title-link {
        display: block;
        max-width: 200px;
        text-decoration: none;
        color: inherit;
    }

    .listing-title-link .listing-title {
        max-width: 100%;
    }

    .listing-title-link:hover .listing-title {
        color: #3b71a8;
    }

    .listing-title {
        font-weight: 600;
        color: #0d1b2a;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 200px;
    }

    .listing-category {
        font-size: 0.72rem;
        color: #778da9;
        margin-top: 0.15rem;
    }

    .business-cell {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .business-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #e8f1f8;
        color: #3b71a8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        font-weight: 700;
        flex-shrink: 0;
    }

    .business-name {
        font-weight: 500;
        color: #415a77;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 140px;
    }

    .city-name {
        color: #415a77;
        font-weight: 500;
        white-space: nowrap;
    }

    .status-badge {
        padding: 0.25rem 0.7rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .status-badge i {
        font-size: 0.65rem;
    }

    .status-submitted {
        background: #fffbeb;
        color: #b45309;
    }

    .status-under_review {
        background: #eff6ff;
        color: #2563eb;
    }

    .status-published {
        background: #ecfdf5;
        color: #059669;
    }

    .status-draft {
        background: #f1f5f9;
        color: #475569;
    }

    .date-cell {
        display: flex;
        flex-direction: column;
    }

    .date-main {
        color: #415a77;
        font-weight: 500;
        white-space: nowrap;
    }

    .date-relative {
        font-size: 0.7rem;
        color: #94a3b8;
    }

    .actions-group {
        display: flex;
        gap: 0.4rem;
        justify-content: center;
    }

    .action-btn {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.15s ease;
        text-decoration: none;
    }

    .action-btn-review {
        color: #3b71a8;
    }

    .action-btn-review:hover {
        border-color: #3b71a8;
        background: #e8f1f8;
    }

    .action-btn-preview {
        color: #3b82f6;
    }

    .action-btn-preview:hover {
        border-color: #3b82f6;
        background: #eff6ff;
    }

    .pagination-wrapper {
        padding: 1.25rem 1.5rem;
        border-top: 1px solid #edf2f7;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .pagination-wrapper-top {
        border-top: none;
        border-bottom: 1px solid #edf2f7;
        background: #fbfdff;
    }

    .pagination-info {
        font-size: 0.8rem;
        color: #94a3b8;
        font-weight: 500;
    }

    .pagination-links {
        display: flex;
        gap: 0.35rem;
    }

    .pagination-links nav {
        display: flex;
    }

    .pagination-links .pagination {
        display: flex;
        gap: 0.35rem;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .pagination-links .pagination li {
        display: inline-block;
    }

    .pagination-links .pagination li a,
    .pagination-links .pagination li span {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 12px;
        border: 1.5px solid #e2e8f0;
        background: #ffffff;
        color: #415a77;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        cursor: pointer;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
    }

    .pagination-links .pagination li a:hover {
        border-color: #3b71a8;
        background: #f4f8fc;
        color: #1e3a5f;
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(59, 113, 168, 0.15);
    }

    .pagination-links .pagination li.active span {
        background: linear-gradient(145deg, #1e3a5f, #2d5a8a);
        border-color: #1e3a5f;
        color: #ffffff;
        font-weight: 700;
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(30, 58, 95, 0.35);
    }

    .pagination-links .pagination li.disabled span {
        color: #cbd5e1;
        cursor: not-allowed;
        opacity: 0.45;
        box-shadow: none;
        transform: none;
    }

    .empty-panel {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 3rem 2rem;
        text-align: center;
    }

    .empty-panel-inner {
        max-width: 400px;
        margin: 0 auto;
    }

    .empty-icon-wrapper {
        font-size: 3.5rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
    }

    .empty-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #415a77;
        margin: 0 0 0.4rem 0;
    }

    .empty-description {
        font-size: 0.85rem;
        color: #94a3b8;
        margin: 0 0 1.25rem 0;
    }

    .empty-action-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.65rem 1.5rem;
        border-radius: 30px;
        background: #1e3a5f;
        color: #ffffff;
        font-weight: 600;
        font-size: 0.85rem;
        text-decoration: none;
        transition: all 0.15s ease;
    }

    .empty-action-btn:hover {
        background: #264b73;
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    @media (max-width: 1024px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .page-header-right {
            width: 100%;
            justify-content: space-between;
        }

        .search-field {
            width: 200px;
        }

        .search-field:focus {
            width: 240px;
        }
    }

    @media (max-width: 768px) {
        .stats-mini-row {
            flex-direction: column;
        }

        .stats-mini-card {
            min-width: 100%;
        }

        .filters-bar-wrapper {
            flex-direction: column;
            align-items: flex-start;
        }

        .filters-group {
            width: 100%;
            overflow-x: auto;
            flex-wrap: nowrap;
            padding-bottom: 0.5rem;
        }

        .filter-pill {
            flex-shrink: 0;
        }

        .search-field {
            width: 100%;
        }

        .search-field:focus {
            width: 100%;
        }

        .user-menu-details {
            display: none;
        }

        .user-menu-trigger {
            padding: 0.3rem;
        }

        .data-table-th,
        .data-table-td {
            padding: 0.75rem 0.8rem;
            font-size: 0.78rem;
        }

        .listing-thumb-image,
        .listing-thumb-placeholder {
            width: 36px;
            height: 36px;
        }

        .listing-title,
        .listing-title-link {
            max-width: 120px;
        }

        .pagination-wrapper {
            flex-direction: column;
            align-items: flex-start;
        }
    }

    @media (max-width: 480px) {
        .pagination-links .pagination li a,
        .pagination-links .pagination li span {
            width: 32px;
            height: 32px;
            font-size: 0.75rem;
            border-radius: 10px;
        }
    }
    
        svg.w-5.h-5 {
    font-size: 12px;
    width: 49px;
}
</style>

<script>
    (function() {
        'use strict';

        const filterPills = document.querySelectorAll('.filter-pill');
        const tableRows = document.querySelectorAll('.data-table-row');

        filterPills.forEach(function(pill) {
            pill.addEventListener('click', function() {
                filterPills.forEach(function(p) {
                    p.classList.remove('filter-pill-active');
                });

                this.classList.add('filter-pill-active');

                const filterValue = this.getAttribute('data-filter');

                if (tableRows.length > 0) {
                    tableRows.forEach(function(row) {
                        if (filterValue === 'all') {
                            row.style.display = '';
                        } else {
                            const rowStatus = row.getAttribute('data-status');
                            if (rowStatus === filterValue) {
                                row.style.display = '';
                            } else {
                                row.style.display = 'none';
                            }
                        }
                    });
                }
            });
        });

        const sortSelect = document.getElementById('sortSelect');

        if (sortSelect && tableRows.length > 0) {
            sortSelect.addEventListener('change', function() {
                const sortValue = this.value;
                const tbody = document.querySelector('.data-table tbody');
                const rows = Array.from(tbody.querySelectorAll('.data-table-row'));

                rows.sort(function(a, b) {
                    let aVal, bVal;

                    switch(sortValue) {
                        case 'newest':
                            return 0;

                        case 'oldest':
                            return 1;

                        case 'title_asc':
                            aVal = (a.querySelector('.listing-title')?.textContent || '').trim();
                            bVal = (b.querySelector('.listing-title')?.textContent || '').trim();
                            return aVal.localeCompare(bVal, 'es');

                        case 'title_desc':
                            aVal = (a.querySelector('.listing-title')?.textContent || '').trim();
                            bVal = (b.querySelector('.listing-title')?.textContent || '').trim();
                            return bVal.localeCompare(aVal, 'es');

                        default:
                            return 0;
                    }
                });

                rows.forEach(function(row) {
                    tbody.appendChild(row);
                });
            });
        }

        const searchField = document.querySelector('.search-field');

        if (searchField && tableRows.length > 0) {
            searchField.addEventListener('input', function() {
                const query = this.value.trim().toLowerCase();

                tableRows.forEach(function(row) {
                    const title = (row.querySelector('.listing-title')?.textContent || '').toLowerCase();
                    const business = (row.querySelector('.business-name')?.textContent || '').toLowerCase();
                    const city = (row.querySelector('.city-name')?.textContent || '').toLowerCase();

                    if (title.includes(query) || business.includes(query) || city.includes(query)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }

        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
                e.preventDefault();
                if (searchField) {
                    searchField.focus();
                }
            }
        });

    })();
</script>

<script>
    (function() {
        const tableContainer = document.getElementById('tableContainer');
        const tableScrollTop = document.getElementById('tableScrollTop');
        const tableScrollTopInner = document.getElementById('tableScrollTopInner');

        if (tableContainer && tableScrollTop && tableScrollTopInner) {
            function syncScrollTop() {
                const scrollWidth = tableContainer.scrollWidth;
                const clientWidth = tableContainer.clientWidth;

                if (scrollWidth > clientWidth) {
                    tableScrollTop.classList.add('active');
                    tableScrollTopInner.style.width = scrollWidth + 'px';
                } else {
                    tableScrollTop.classList.remove('active');
                }
            }

            tableContainer.addEventListener('scroll', function() {
                if (tableScrollTop.scrollLeft !== tableContainer.scrollLeft) {
                    tableScrollTop.scrollLeft = tableContainer.scrollLeft;
                }
            });

            tableScrollTop.addEventListener('scroll', function() {
                if (tableContainer.scrollLeft !== tableScrollTop.scrollLeft) {
                    tableContainer.scrollLeft = tableScrollTop.scrollLeft;
                }
            });

            window.addEventListener('resize', syncScrollTop);
            window.addEventListener('load', syncScrollTop);
            syncScrollTop();

            // مراقبة تغييرات الصفوف لضبط عرض الشريط بعد الفلترة/البحث/الترتيب
            const tbody = document.querySelector('.data-table tbody');
            if (tbody) {
                const observer = new MutationObserver(function() {
                    syncScrollTop();
                });
                observer.observe(tbody, {
                    childList: true,
                    subtree: true,
                    attributes: true,
                    attributeFilter: ['style']
                });
            }
        }
    })();
</script>

@endsection