@extends('layouts.admin')

@section('content')

<div class="page-header anim-fade-up">
    <div class="page-header-left">
        <div class="breadcrumb-nav">
            <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">الرئيسية</a>
            <span class="breadcrumb-separator">/</span>
            <span class="breadcrumb-current">إدارة التقييمات</span>
        </div>
        <h1 class="page-heading">إدارة التقييمات</h1>
    </div>
    <div class="page-header-right">
        <div class="search-wrapper">
            <i class="fa-solid fa-search search-icon-input"></i>
            <input type="text" class="search-field" id="searchReview" placeholder="بحث في التقييمات...">
        </div>
        <a href="#" class="user-menu-trigger">
            <div class="user-menu-details">
                <div class="user-menu-displayname">{{ auth()->user()->name }}</div>
                <div class="user-menu-rolename">مدير النظام</div>
            </div>
            <div class="user-menu-avatar-circle">
                {{ Str::upper(Str::substr(auth()->user()->name, 0, 1)) }}
            </div>
        </a>
    </div>
</div>

<div class="stats-mini-row anim-fade-up anim-delay-1">
    <div class="stats-mini-card">
        <div class="stats-mini-icon stats-mini-icon-amber">
            <i class="fa-solid fa-clock"></i>
        </div>
        <div class="stats-mini-content">
            <div class="stats-mini-label">بانتظار المراجعة</div>
            <div class="stats-mini-value">{{ $reviews->total() }}</div>
        </div>
    </div>

    @if(isset($approvedCount))
    <div class="stats-mini-card">
        <div class="stats-mini-icon stats-mini-icon-green">
            <i class="fa-solid fa-check-circle"></i>
        </div>
        <div class="stats-mini-content">
            <div class="stats-mini-label">التقييمات المعتمدة</div>
            <div class="stats-mini-value">{{ $approvedCount }}</div>
        </div>
    </div>
    @endif

    @if(isset($averageRating))
    <div class="stats-mini-card">
        <div class="stats-mini-icon stats-mini-icon-blue">
            <i class="fa-solid fa-star"></i>
        </div>
        <div class="stats-mini-content">
            <div class="stats-mini-label">متوسط التقييمات</div>
            <div class="stats-mini-value">{{ number_format($averageRating, 1) }}</div>
        </div>
    </div>
    @endif
</div>

<div class="filters-bar-wrapper anim-fade-up anim-delay-2">
    <div class="filters-group">
        <button class="filter-pill filter-pill-active" data-filter="all">
            <i class="fa-solid fa-list"></i>
            <span>الكل</span>
        </button>
        <button class="filter-pill" data-filter="5">
            <i class="fa-solid fa-star"></i>
            <span>5 نجوم</span>
        </button>
        <button class="filter-pill" data-filter="4">
            <i class="fa-solid fa-star"></i>
            <span>4 نجوم</span>
        </button>
        <button class="filter-pill" data-filter="3">
            <i class="fa-solid fa-star"></i>
            <span>3 نجوم</span>
        </button>
        <button class="filter-pill" data-filter="low">
            <i class="fa-solid fa-star-half-stroke"></i>
            <span>أقل من 3</span>
        </button>
    </div>

    <div class="sort-group">
        <label class="sort-label" for="sortSelect">ترتيب حسب:</label>
        <select id="sortSelect" class="sort-select">
            <option value="newest">الأحدث</option>
            <option value="oldest">الأقدم</option>
            <option value="rating_high">الأعلى تقييماً</option>
            <option value="rating_low">الأقل تقييماً</option>
        </select>
    </div>
</div>

@if($reviews->isEmpty())
    <div class="empty-panel anim-scale-in anim-delay-2">
        <div class="empty-panel-inner">
            <div class="empty-icon-wrapper">
                <i class="fa-solid fa-star-half-stroke"></i>
            </div>
            <h3 class="empty-title">لا توجد تقييمات بانتظار المراجعة</h3>
            <p class="empty-description">جميع التقييمات تمت مراجعتها. عمل رائع!</p>
            <a href="{{ route('admin.dashboard') }}" class="empty-action-btn">
                <i class="fa-solid fa-arrow-right"></i>
                العودة للوحة التحكم
            </a>
        </div>
    </div>
@else
    <div class="reviews-list anim-scale-in anim-delay-2">
        @foreach($reviews as $review)
            <div class="review-card" data-rating="{{ $review->rating }}">
                <div class="review-card-inner">
                    <div class="review-main">
                        <div class="review-header">
                            <div class="review-rating-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        <i class="fa-solid fa-star star-filled"></i>
                                    @else
                                        <i class="fa-regular fa-star star-empty"></i>
                                    @endif
                                @endfor
                                <span class="review-rating-number">{{ $review->rating }}/5</span>
                            </div>

                            @if($review->title)
                                <h3 class="review-title">{{ $review->title }}</h3>
                            @else
                                <h3 class="review-title review-title-untitled">تقييم بدون عنوان</h3>
                            @endif
                        </div>

                        <div class="review-body-text">
                            <p>{{ $review->body }}</p>
                        </div>

                        <div class="review-meta">
                            <div class="review-meta-item">
                                <div class="review-meta-avatar" style="background: #e8f1f8; color: #3b71a8;">
                                    {{ Str::upper(Str::substr($review->user?->first_name ?? '?', 0, 1)) }}
                                </div>
                                <div class="review-meta-content">
                                    <span class="review-meta-label">المستخدم</span>
                                    <span class="review-meta-value">{{ $review->user?->first_name ?? '' }} {{ $review->user?->last_name ?? 'مستخدم محذوف' }}</span>
                                </div>
                            </div>

                            <div class="review-meta-item">
                                <div class="review-meta-icon">
                                    <i class="fa-solid fa-briefcase"></i>
                                </div>
                                <div class="review-meta-content">
                                    <span class="review-meta-label">النشاط</span>
                                    <a href="{{ route('listing.show', $review->listing?->slug ?? '#') }}" class="review-meta-link">
                                        {{ $review->listing?->title ?? 'نشاط محذوف' }}
                                    </a>
                                </div>
                            </div>

                            <div class="review-meta-item">
                                <div class="review-meta-icon">
                                    <i class="fa-solid fa-calendar"></i>
                                </div>
                                <div class="review-meta-content">
                                    <span class="review-meta-label">التاريخ</span>
                                    <span class="review-meta-value">{{ $review->created_at?->format('Y-m-d H:i') ?? 'غير محدد' }}</span>
                                    <span class="review-meta-relative">{{ $review->created_at?->diffForHumans() ?? 'تاريخ غير معروف' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="review-actions">
                        <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" class="review-action-form">
                            @csrf
                            <button type="submit" class="review-action-btn review-approve-btn">
                                <i class="fa-solid fa-check-circle"></i>
                                <span>موافقة</span>
                            </button>
                        </form>

                        <form action="{{ route('admin.reviews.reject', $review) }}" method="POST" class="review-action-form">
                            @csrf
                            <button type="submit" class="review-action-btn review-reject-btn">
                                <i class="fa-solid fa-xmark-circle"></i>
                                <span>رفض</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if($reviews->hasPages())
        <div class="pagination-wrapper-custom anim-fade-up anim-delay-3">
            <div class="pagination-info">
                عرض {{ $reviews->firstItem() }} - {{ $reviews->lastItem() }} من إجمالي {{ $reviews->total() }} تقييم
            </div>
            <div class="pagination-links">
                {{ $reviews->links() }}
            </div>
        </div>
    @endif
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
        padding: 0.55rem 1rem 0.55rem 2.5rem;
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
        right: 1rem;
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
        padding: 0.3rem;
        padding-left: 1rem;
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
        text-align: right;
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

    .reviews-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .review-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.15s ease;
    }

    .review-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
    }

    .review-card-inner {
        display: flex;
        justify-content: space-between;
        align-items: stretch;
    }

    .review-main {
        flex: 1;
        padding: 1.5rem;
        min-width: 0;
    }

    .review-header {
        margin-bottom: 1rem;
    }

    .review-rating-stars {
        display: flex;
        align-items: center;
        gap: 0.2rem;
        margin-bottom: 0.5rem;
    }

    .review-rating-stars i {
        font-size: 1.1rem;
    }

    .star-filled {
        color: #f59e0b;
    }

    .star-empty {
        color: #d1d5db;
    }

    .review-rating-number {
        font-size: 0.85rem;
        font-weight: 700;
        color: #f59e0b;
        margin-right: 0.5rem;
    }

    .review-title {
        font-size: 1rem;
        font-weight: 700;
        color: #0d1b2a;
        margin: 0;
    }

    .review-title-untitled {
        color: #94a3b8;
        font-style: italic;
    }

    .review-body-text {
        margin-bottom: 1rem;
    }

    .review-body-text p {
        font-size: 0.9rem;
        color: #415a77;
        line-height: 1.8;
        margin: 0;
    }

    .review-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .review-meta-item {
        display: flex;
        align-items: center;
        gap: 0.55rem;
    }

    .review-meta-avatar {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        font-weight: 700;
        flex-shrink: 0;
    }

    .review-meta-icon {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        flex-shrink: 0;
    }

    .review-meta-content {
        display: flex;
        flex-direction: column;
    }

    .review-meta-label {
        font-size: 0.65rem;
        color: #94a3b8;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .review-meta-value {
        font-size: 0.8rem;
        font-weight: 600;
        color: #415a77;
    }

    .review-meta-link {
        font-size: 0.8rem;
        font-weight: 600;
        color: #3b71a8;
        text-decoration: none;
        transition: color 0.15s ease;
    }

    .review-meta-link:hover {
        color: #1e3a5f;
        text-decoration: underline;
    }

    .review-meta-relative {
        font-size: 0.68rem;
        color: #94a3b8;
    }

    .review-actions {
        display: flex;
        flex-direction: column;
        border-right: 1px solid #edf2f7;
        min-width: 140px;
    }

    .review-action-form {
        flex: 1;
        display: flex;
    }

    .review-action-form:first-child {
        border-bottom: 1px solid #edf2f7;
    }

    .review-action-btn {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        padding: 1.25rem 1rem;
        border: none;
        cursor: pointer;
        font-family: 'Tajawal', 'Inter', sans-serif;
        font-size: 0.8rem;
        font-weight: 700;
        transition: all 0.2s ease;
        background: transparent;
    }

    .review-action-btn i {
        font-size: 1.3rem;
        transition: transform 0.2s ease;
    }

    .review-approve-btn {
        color: #059669;
    }

    .review-approve-btn:hover {
        background: #ecfdf5;
        color: #047857;
    }

    .review-approve-btn:hover i {
        transform: scale(1.15);
    }

    .review-reject-btn {
        color: #dc2626;
    }

    .review-reject-btn:hover {
        background: #fef2f2;
        color: #b91c1c;
    }

    .review-reject-btn:hover i {
        transform: scale(1.15);
    }

    .pagination-wrapper-custom {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 1.25rem 1.5rem;
        margin-top: 1.25rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 1rem;
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
        width: 36px;
        height: 36px;
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        background: #ffffff;
        color: #415a77;
        font-size: 0.82rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.15s ease;
        cursor: pointer;
    }

    .pagination-links .pagination li a:hover {
        border-color: #3b71a8;
        background: #e8f1f8;
        color: #1e3a5f;
    }

    .pagination-links .pagination li.active span {
        background: #1e3a5f;
        border-color: #1e3a5f;
        color: #ffffff;
        font-weight: 700;
    }

    .pagination-links .pagination li.disabled span {
        color: #cbd5e1;
        cursor: not-allowed;
        opacity: 0.5;
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
            padding-left: 0.3rem;
        }

        .review-card-inner {
            flex-direction: column;
        }

        .review-actions {
            flex-direction: row;
            border-right: none;
            border-top: 1px solid #edf2f7;
            min-width: auto;
        }

        .review-action-form:first-child {
            border-bottom: none;
            border-left: 1px solid #edf2f7;
        }

        .review-action-btn {
            flex-direction: row;
            padding: 0.85rem 1.5rem;
        }

        .review-meta {
            flex-direction: column;
            gap: 0.65rem;
        }
    }

    @media (max-width: 480px) {
        .pagination-links .pagination li a,
        .pagination-links .pagination li span {
            width: 30px;
            height: 30px;
            font-size: 0.7rem;
        }

        .pagination-wrapper-custom {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>

<script>
    (function() {
        'use strict';

        const filterPills = document.querySelectorAll('.filter-pill');
        const reviewCards = document.querySelectorAll('.review-card');

        filterPills.forEach(function(pill) {
            pill.addEventListener('click', function() {
                filterPills.forEach(function(p) {
                    p.classList.remove('filter-pill-active');
                });

                this.classList.add('filter-pill-active');

                const filterValue = this.getAttribute('data-filter');

                if (reviewCards.length > 0) {
                    reviewCards.forEach(function(card) {
                        if (filterValue === 'all') {
                            card.style.display = '';
                        } else if (filterValue === 'low') {
                            const rating = parseInt(card.getAttribute('data-rating'));
                            if (rating < 3) {
                                card.style.display = '';
                            } else {
                                card.style.display = 'none';
                            }
                        } else {
                            const rating = parseInt(card.getAttribute('data-rating'));
                            if (rating === parseInt(filterValue)) {
                                card.style.display = '';
                            } else {
                                card.style.display = 'none';
                            }
                        }
                    });
                }
            });
        });

        const searchField = document.getElementById('searchReview');

        if (searchField && reviewCards.length > 0) {
            searchField.addEventListener('input', function() {
                const query = this.value.trim().toLowerCase();

                reviewCards.forEach(function(card) {
                    const title = (card.querySelector('.review-title')?.textContent || '').toLowerCase();
                    const body = (card.querySelector('.review-body-text p')?.textContent || '').toLowerCase();
                    const userName = (card.querySelector('.review-meta-value')?.textContent || '').toLowerCase();
                    const listingName = (card.querySelector('.review-meta-link')?.textContent || '').toLowerCase();

                    if (
                        title.includes(query) ||
                        body.includes(query) ||
                        userName.includes(query) ||
                        listingName.includes(query)
                    ) {
                        card.style.display = '';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        }

        const sortSelect = document.getElementById('sortSelect');
        const reviewsList = document.querySelector('.reviews-list');

        if (sortSelect && reviewsList && reviewCards.length > 0) {
            sortSelect.addEventListener('change', function() {
                const sortValue = this.value;
                const cards = Array.from(reviewCards);

                cards.sort(function(a, b) {
                    let aVal, bVal;

                    switch(sortValue) {
                        case 'newest':
                            return 0;

                        case 'oldest':
                            return 1;

                        case 'rating_high':
                            aVal = parseInt(a.getAttribute('data-rating')) || 0;
                            bVal = parseInt(b.getAttribute('data-rating')) || 0;
                            return bVal - aVal;

                        case 'rating_low':
                            aVal = parseInt(a.getAttribute('data-rating')) || 0;
                            bVal = parseInt(b.getAttribute('data-rating')) || 0;
                            return aVal - bVal;

                        default:
                            return 0;
                    }
                });

                cards.forEach(function(card) {
                    reviewsList.appendChild(card);
                });
            });
        }

        const approveBtns = document.querySelectorAll('.review-approve-btn');
        const rejectBtns = document.querySelectorAll('.review-reject-btn');

        approveBtns.forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                const card = this.closest('.review-card');
                const rating = card?.getAttribute('data-rating') || '';
                const title = card?.querySelector('.review-title')?.textContent?.trim() || 'هذا التقييم';

                const message = 'هل أنت متأكد من الموافقة على "' + title + '"؟\n\nالتقييم: ' + rating + ' / 5';

                if (!confirm(message)) {
                    e.preventDefault();
                }
            });
        });

        rejectBtns.forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                const card = this.closest('.review-card');
                const rating = card?.getAttribute('data-rating') || '';
                const title = card?.querySelector('.review-title')?.textContent?.trim() || 'هذا التقييم';

                const message = 'هل أنت متأكد من رفض "' + title + '"؟\n\nالتقييم: ' + rating + ' / 5\n\nسيتم حذف التقييم نهائياً.';

                if (!confirm(message)) {
                    e.preventDefault();
                }
            });
        });

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

@endsection