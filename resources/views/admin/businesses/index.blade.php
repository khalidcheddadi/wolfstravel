@extends('layouts.admin')

@section('content')

<div class="page-header anim-fade-up">
    <div class="page-header-left">
        <div class="breadcrumb-nav">
            <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">Home</a>
            <span class="breadcrumb-separator">/</span>
            <span class="breadcrumb-current">Business Management</span>
        </div>
        <h1 class="page-heading">Business Management</h1>
    </div>
    <div class="page-header-right">
        <div class="search-wrapper">
            <i class="fa-solid fa-search search-icon-input"></i>
            <input type="text" class="search-field" id="searchBusiness" placeholder="Search for a business...">
        </div>
        <a href="#" class="user-menu-trigger">
            <div class="user-menu-details">
                <div class="user-menu-displayname">{{ auth()->user()->name }}</div>
                <div class="user-menu-rolename">System Admin</div>
            </div>
            <div class="user-menu-avatar-circle">
                {{ Str::upper(Str::substr(auth()->user()->name, 0, 1)) }}
            </div>
        </a>
    </div>
</div>

<div class="stats-mini-row anim-fade-up anim-delay-1">
    <div class="stats-mini-card">
        <div class="stats-mini-icon stats-mini-icon-blue">
            <i class="fa-solid fa-building"></i>
        </div>
        <div class="stats-mini-content">
            <div class="stats-mini-label">Total Businesses</div>
            <div class="stats-mini-value">{{ $businesses->total() }}</div>
        </div>
    </div>

    <div class="stats-mini-card">
        <div class="stats-mini-icon stats-mini-icon-green">
            <i class="fa-solid fa-certificate"></i>
        </div>
        <div class="stats-mini-content">
            <div class="stats-mini-label">Verified Businesses</div>
            <div class="stats-mini-value">{{ $verifiedCount ?? 0 }}</div>
        </div>
    </div>

    <div class="stats-mini-card">
        <div class="stats-mini-icon stats-mini-icon-amber">
            <i class="fa-solid fa-clock"></i>
        </div>
        <div class="stats-mini-content">
            <div class="stats-mini-label">Unverified</div>
            <div class="stats-mini-value">{{ $unverifiedCount ?? 0 }}</div>
        </div>
    </div>
</div>

<div class="filters-bar-wrapper anim-fade-up anim-delay-2">
    <div class="filters-group">
        <button class="filter-pill filter-pill-active" data-filter="all">
            <i class="fa-solid fa-list"></i>
            <span>All</span>
        </button>
        <button class="filter-pill" data-filter="verified">
            <i class="fa-solid fa-certificate"></i>
            <span>Verified</span>
        </button>
        <button class="filter-pill" data-filter="unverified">
            <i class="fa-solid fa-clock"></i>
            <span>Unverified</span>
        </button>
    </div>

    <div class="sort-group">
        <label class="sort-label" for="sortSelect">Sort by:</label>
        <select id="sortSelect" class="sort-select">
            <option value="newest">Newest</option>
            <option value="oldest">Oldest</option>
            <option value="name_asc">Name (A-Z)</option>
            <option value="name_desc">Name (Z-A)</option>
        </select>
    </div>
</div>

@if($businesses->isEmpty())
    <div class="empty-panel anim-scale-in anim-delay-2">
        <div class="empty-panel-inner">
            <div class="empty-icon-wrapper">
                <i class="fa-solid fa-building-circle-exclamation"></i>
            </div>
            <h3 class="empty-title">No businesses registered</h3>
            <p class="empty-description">No business has been registered in the system yet.</p>
            <a href="{{ route('admin.dashboard') }}" class="empty-action-btn">
                <i class="fa-solid fa-arrow-right"></i>
                Back to Dashboard
            </a>
        </div>
    </div>
@else
    <div class="table-panel anim-scale-in anim-delay-2">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr class="data-table-header-row">
                        <th class="data-table-th">
                            <div class="th-content">
                                <i class="fa-solid fa-shop th-icon"></i>
                                Business
                            </div>
                        </th>
                        <th class="data-table-th">
                            <div class="th-content">
                                <i class="fa-solid fa-user-tie th-icon"></i>
                                Owner
                            </div>
                        </th>
                        <th class="data-table-th">
                            <div class="th-content">
                                <i class="fa-solid fa-envelope th-icon"></i>
                                Email
                            </div>
                        </th>
                        <th class="data-table-th">
                            <div class="th-content">
                                <i class="fa-solid fa-location-dot th-icon"></i>
                                City
                            </div>
                        </th>
                        <th class="data-table-th">
                            <div class="th-content">
                                <i class="fa-solid fa-shield-halved th-icon"></i>
                                Status
                            </div>
                        </th>
                        <th class="data-table-th th-actions">
                            <div class="th-content">Actions</div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($businesses as $business)
                        <tr class="data-table-row" data-verified="{{ $business->verified ? 'verified' : 'unverified' }}">
                            <td class="data-table-td">
                                <div class="business-cell-main">
                                    <div class="business-avatar-circle">
                                        {{ Str::upper(Str::substr($business->business_name, 0, 1)) }}
                                    </div>
                                    <div class="business-info-cell">
                                        <div class="business-title" title="{{ $business->business_name }}">
                                            {{ $business->business_name }}
                                        </div>
                                        @if($business->type)
                                            <div class="business-type">
                                                {{ $business->type->name ?? '' }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>

                            <td class="data-table-td">
                                <div class="owner-cell">
                                    <div class="owner-avatar">
                                        {{ Str::upper(Str::substr($business->owner?->first_name ?? '?', 0, 1)) }}
                                    </div>
                                    <span class="owner-name">
                                        {{ $business->owner?->first_name ?? '' }} {{ $business->owner?->last_name ?? 'Unknown' }}
                                    </span>
                                </div>
                            </td>

                            <td class="data-table-td">
                                <span class="email-text">
                                    {{ $business->email ?? $business->owner?->email ?? '—' }}
                                </span>
                            </td>

                            <td class="data-table-td">
                                <span class="city-text">
                                    {{ $business->city?->name ?? '—' }}
                                </span>
                            </td>

                            <td class="data-table-td">
                                <span class="verification-badge verification-badge-{{ $business->verified ? 'verified' : 'unverified' }}">
                                    @if($business->verified)
                                        <i class="fa-solid fa-certificate"></i>
                                        <span>Verified</span>
                                    @else
                                        <i class="fa-solid fa-clock"></i>
                                        <span>Unverified</span>
                                    @endif
                                </span>
                            </td>

                            <td class="data-table-td td-actions">
                                <form action="{{ route('admin.businesses.toggle-verify', $business) }}" method="POST" class="verify-form">
                                    @csrf
                                    <button type="submit" class="verify-btn verify-btn-{{ $business->verified ? 'unverify' : 'verify' }}" title="{{ $business->verified ? 'Unverify' : 'Verify Business' }}">
                                        @if($business->verified)
                                            <i class="fa-solid fa-circle-xmark"></i>
                                            <span>Unverify</span>
                                        @else
                                            <i class="fa-solid fa-certificate"></i>
                                            <span>Verify</span>
                                        @endif
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($businesses->hasPages())
            <div class="pagination-wrapper">
                <div class="pagination-info">
                    Showing {{ $businesses->firstItem() }} - {{ $businesses->lastItem() }} of total {{ $businesses->total() }} businesses
                </div>
                <div class="pagination-links">
                    {{ $businesses->links() }}
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

    .stats-mini-icon-blue {
        background: #eff6ff;
        color: #3b82f6;
    }

    .stats-mini-icon-green {
        background: #ecfdf5;
        color: #10b981;
    }

    .stats-mini-icon-amber {
        background: #fffbeb;
        color: #f59e0b;
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
        text-align: right;
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

    .business-cell-main {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .business-avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e8f1f8;
        color: #3b71a8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.9rem;
        flex-shrink: 0;
    }

    .business-info-cell {
        min-width: 0;
    }

    .business-title {
        font-weight: 600;
        color: #0d1b2a;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 180px;
    }

    .business-type {
        font-size: 0.72rem;
        color: #778da9;
        margin-top: 0.15rem;
    }

    .owner-cell {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .owner-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #fef3c7;
        color: #d97706;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.7rem;
        font-weight: 700;
        flex-shrink: 0;
    }

    .owner-name {
        font-weight: 500;
        color: #415a77;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 140px;
    }

    .email-text {
        color: #415a77;
        font-weight: 500;
        font-size: 0.85rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 180px;
        display: block;
    }

    .city-text {
        color: #415a77;
        font-weight: 500;
        white-space: nowrap;
    }

    .verification-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.3rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .verification-badge i {
        font-size: 0.7rem;
    }

    .verification-badge-verified {
        background: #ecfdf5;
        color: #059669;
    }

    .verification-badge-unverified {
        background: #fffbeb;
        color: #b45309;
    }

    .verify-form {
        display: inline-block;
    }

    .verify-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.45rem 1rem;
        border-radius: 30px;
        font-size: 0.78rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.15s ease;
        border: 1.5px solid;
        font-family: 'Tajawal', 'Inter', sans-serif;
        white-space: nowrap;
    }

    .verify-btn-verify {
        background: #ecfdf5;
        color: #059669;
        border-color: #6ee7b7;
    }

    .verify-btn-verify:hover {
        background: #059669;
        color: #ffffff;
        border-color: #059669;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);
    }

    .verify-btn-unverify {
        background: #fef2f2;
        color: #dc2626;
        border-color: #fca5a5;
    }

    .verify-btn-unverify:hover {
        background: #dc2626;
        color: #ffffff;
        border-color: #dc2626;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.25);
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

        .data-table-th,
        .data-table-td {
            padding: 0.75rem 0.6rem;
            font-size: 0.75rem;
        }

        .business-avatar-circle {
            width: 32px;
            height: 32px;
            font-size: 0.75rem;
        }

        .business-title {
            max-width: 100px;
        }

        .email-text {
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
            width: 30px;
            height: 30px;
            font-size: 0.7rem;
        }

        .verify-btn span {
            display: none;
        }

        .verify-btn {
            padding: 0.45rem 0.6rem;
        }
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
                            const rowVerified = row.getAttribute('data-verified');
                            if (rowVerified === filterValue) {
                                row.style.display = '';
                            } else {
                                row.style.display = 'none';
                            }
                        }
                    });
                }
            });
        });

        const searchField = document.getElementById('searchBusiness');

        if (searchField && tableRows.length > 0) {
            searchField.addEventListener('input', function() {
                const query = this.value.trim().toLowerCase();

                tableRows.forEach(function(row) {
                    const businessName = (row.querySelector('.business-title')?.textContent || '').toLowerCase();
                    const ownerName = (row.querySelector('.owner-name')?.textContent || '').toLowerCase();
                    const email = (row.querySelector('.email-text')?.textContent || '').toLowerCase();
                    const city = (row.querySelector('.city-text')?.textContent || '').toLowerCase();

                    if (
                        businessName.includes(query) ||
                        ownerName.includes(query) ||
                        email.includes(query) ||
                        city.includes(query)
                    ) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }

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

                        case 'name_asc':
                            aVal = (a.querySelector('.business-title')?.textContent || '').trim();
                            bVal = (b.querySelector('.business-title')?.textContent || '').trim();
                            return aVal.localeCompare(bVal, 'ar');

                        case 'name_desc':
                            aVal = (a.querySelector('.business-title')?.textContent || '').trim();
                            bVal = (b.querySelector('.business-title')?.textContent || '').trim();
                            return bVal.localeCompare(aVal, 'ar');

                        default:
                            return 0;
                    }
                });

                rows.forEach(function(row) {
                    tbody.appendChild(row);
                });
            });
        }

        const verifyForms = document.querySelectorAll('.verify-form');

        verifyForms.forEach(function(form) {
            form.addEventListener('submit', function(e) {
                const btn = form.querySelector('.verify-btn');
                const isCurrentlyVerified = btn.classList.contains('verify-btn-unverify');
                const businessName = form.closest('.data-table-row')?.querySelector('.business-title')?.textContent?.trim() || 'this business';

                let message;
                if (isCurrentlyVerified) {
                    message = 'Are you sure you want to unverify "' + businessName + '"?\n\nThis will remove the verification badge from the business.';
                } else {
                    message = 'Are you sure you want to verify "' + businessName + '"?\n\nThis will officially approve the business.';
                }

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