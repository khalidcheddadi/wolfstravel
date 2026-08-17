@extends('layouts.admin')

@section('content')

{{-- ========== PAGE HEADER ========== --}}
<div class="page-header anim-fade-up">
    <div class="page-header-left">
        <div class="breadcrumb-nav">
            <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">الرئيسية</a>
            <span class="breadcrumb-separator">/</span>
            <span class="breadcrumb-current">إدارة المستخدمين</span>
        </div>
        <h1 class="page-heading">إدارة المستخدمين</h1>
    </div>
    <div class="page-header-right">
        <div class="search-wrapper">
            <i class="fa-solid fa-search search-icon-input"></i>
            <input type="text" class="search-field" id="searchUser" placeholder="بحث عن مستخدم...">
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

{{-- ========== STATS MINI CARDS ========== --}}
<div class="stats-mini-row anim-fade-up anim-delay-1">
    <div class="stats-mini-card">
        <div class="stats-mini-icon stats-mini-icon-blue">
            <i class="fa-solid fa-users"></i>
        </div>
        <div class="stats-mini-content">
            <div class="stats-mini-label">إجمالي المستخدمين</div>
            <div class="stats-mini-value">{{ $users->total() }}</div>
        </div>
    </div>

    <div class="stats-mini-card">
        <div class="stats-mini-icon stats-mini-icon-green">
            <i class="fa-solid fa-user-check"></i>
        </div>
        <div class="stats-mini-content">
            <div class="stats-mini-label">المستخدمون النشطون</div>
            <div class="stats-mini-value">{{ $activeUsers ?? 0 }}</div>
        </div>
    </div>

    <div class="stats-mini-card">
        <div class="stats-mini-icon stats-mini-icon-purple">
            <i class="fa-solid fa-shield-halved"></i>
        </div>
        <div class="stats-mini-content">
            <div class="stats-mini-label">المدراء</div>
            <div class="stats-mini-value">{{ $adminUsers ?? 0 }}</div>
        </div>
    </div>
</div>

{{-- ========== FILTERS BAR ========== --}}
<div class="filters-bar-wrapper anim-fade-up anim-delay-2">
    <div class="filters-group">
        <button class="filter-pill filter-pill-active" data-filter="all">
            <i class="fa-solid fa-list"></i>
            <span>الكل</span>
        </button>
        <button class="filter-pill" data-filter="admin">
            <i class="fa-solid fa-shield-halved"></i>
            <span>مدير</span>
        </button>
        <button class="filter-pill" data-filter="business_owner">
            <i class="fa-solid fa-building"></i>
            <span>صاحب منشأة</span>
        </button>
        <button class="filter-pill" data-filter="user">
            <i class="fa-solid fa-user"></i>
            <span>مستخدم</span>
        </button>
        <button class="filter-pill" data-filter="active">
            <i class="fa-solid fa-circle-check"></i>
            <span>نشط</span>
        </button>
        <button class="filter-pill" data-filter="suspended">
            <i class="fa-solid fa-circle-pause"></i>
            <span>معلق</span>
        </button>
    </div>

    <div class="sort-group">
        <label class="sort-label" for="sortSelect">ترتيب حسب:</label>
        <select id="sortSelect" class="sort-select">
            <option value="newest">الأحدث</option>
            <option value="oldest">الأقدم</option>
            <option value="name_asc">الاسم (أ-ي)</option>
            <option value="name_desc">الاسم (ي-أ)</option>
        </select>
    </div>
</div>

{{-- ========== USERS TABLE ========== --}}
@if($users->isEmpty())
    <div class="empty-panel anim-scale-in anim-delay-2">
        <div class="empty-panel-inner">
            <div class="empty-icon-wrapper">
                <i class="fa-solid fa-users-slash"></i>
            </div>
            <h3 class="empty-title">لا يوجد مستخدمين</h3>
            <p class="empty-description">لم يتم تسجيل أي مستخدم في النظام بعد.</p>
            <a href="{{ route('admin.dashboard') }}" class="empty-action-btn">
                <i class="fa-solid fa-arrow-right"></i>
                العودة للوحة التحكم
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
                                <i class="fa-solid fa-user th-icon"></i>
                                المستخدم
                            </div>
                        </th>
                        <th class="data-table-th">
                            <div class="th-content">
                                <i class="fa-solid fa-envelope th-icon"></i>
                                البريد الإلكتروني
                            </div>
                        </th>
                        <th class="data-table-th">
                            <div class="th-content">
                                <i class="fa-solid fa-user-tag th-icon"></i>
                                الدور
                            </div>
                        </th>
                        <th class="data-table-th">
                            <div class="th-content">
                                <i class="fa-solid fa-circle th-icon"></i>
                                الحالة
                            </div>
                        </th>
                        <th class="data-table-th">
                            <div class="th-content">
                                <i class="fa-solid fa-calendar th-icon"></i>
                                تاريخ التسجيل
                            </div>
                        </th>
                        <th class="data-table-th th-actions">
                            <div class="th-content">الإجراءات</div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr class="data-table-row"
                            data-role="{{ $user->roles->first()?->name ?? 'user' }}"
                            data-status="{{ $user->status ?? 'active' }}">

                            {{-- المستخدم --}}
                            <td class="data-table-td">
                                <div class="user-cell-main">
                                    <div class="user-avatar-circle">
                                        @if($user->profile_photo_url ?? false)
                                            <img src="{{ $user->profile_photo_url }}"
                                                 class="user-avatar-image"
                                                 alt="{{ $user->name }}">
                                        @else
                                            <span class="user-avatar-initials">
                                                {{ Str::upper(Str::substr($user->name, 0, 1)) }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="user-info-cell">
                                        <div class="user-display-name" title="{{ $user->name }}">
                                            {{ $user->name }}
                                        </div>
                                        <div class="user-joined-info">
                                            منذ {{ $user->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- البريد الإلكتروني --}}
                            <td class="data-table-td">
                                <div class="email-cell">
                                    <span class="email-text-display">{{ $user->email }}</span>
                                    @if($user->email_verified_at)
                                        <span class="email-verified-badge" title="بريد موثق">
                                            <i class="fa-solid fa-check-circle"></i>
                                        </span>
                                    @endif
                                </div>
                            </td>

                            {{-- الدور --}}
                            <td class="data-table-td">
                                <div class="roles-cell">
                                    @if($user->roles->isNotEmpty())
                                        @foreach($user->roles as $role)
                                            <span class="role-badge role-badge-{{ $role->name }}">
                                                @if($role->name == 'admin')
                                                    <i class="fa-solid fa-shield-halved"></i>
                                                    <span>مدير</span>
                                                @elseif($role->name == 'business_owner')
                                                    <i class="fa-solid fa-building"></i>
                                                    <span>صاحب منشأة</span>
                                                @elseif($role->name == 'moderator')
                                                    <i class="fa-solid fa-gavel"></i>
                                                    <span>مشرف</span>
                                                @else
                                                    <i class="fa-solid fa-user"></i>
                                                    <span>{{ $role->name }}</span>
                                                @endif
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="role-badge role-badge-user">
                                            <i class="fa-solid fa-user"></i>
                                            <span>مستخدم</span>
                                        </span>
                                    @endif
                                </div>
                            </td>

                            {{-- الحالة --}}
                            <td class="data-table-td">
                                <span class="status-indicator status-indicator-{{ ($user->status ?? 'active') === 'active' ? 'active' : 'suspended' }}">
                                    <span class="status-dot-circle"></span>
                                    @if(($user->status ?? 'active') === 'active')
                                        نشط
                                    @else
                                        معلق
                                    @endif
                                </span>
                            </td>

                            {{-- تاريخ التسجيل --}}
                            <td class="data-table-td">
                                <div class="date-cell-display">
                                    <div class="date-main-text">
                                        {{ $user->created_at->format('Y-m-d') }}
                                    </div>
                                    <div class="date-sub-text">
                                        {{ $user->created_at->format('H:i') }}
                                    </div>
                                </div>
                            </td>

                            {{-- الإجراءات --}}
                            <td class="data-table-td td-actions">
                                <div class="actions-group-inline">
                                    <form action="{{ route('admin.users.toggle', $user) }}" method="POST" class="action-form-inline">
                                        @csrf
                                        <button type="submit"
                                                class="action-btn-toggle action-btn-toggle-{{ ($user->status ?? 'active') === 'active' ? 'suspend' : 'activate' }}"
                                                title="{{ ($user->status ?? 'active') === 'active' ? 'تعليق المستخدم' : 'تفعيل المستخدم' }}"
                                                {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                            @if(($user->status ?? 'active') === 'active')
                                                <i class="fa-solid fa-pause"></i>
                                                <span>تعليق</span>
                                            @else
                                                <i class="fa-solid fa-play"></i>
                                                <span>تفعيل</span>
                                            @endif
                                        </button>
                                    </form>

                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="action-form-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="action-btn-delete"
                                                title="حذف المستخدم"
                                                {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                            <i class="fa-solid fa-trash-can"></i>
                                            <span>حذف</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
            <div class="pagination-wrapper">
                <div class="pagination-info">
                    عرض {{ $users->firstItem() }} - {{ $users->lastItem() }} من إجمالي {{ $users->total() }} مستخدم
                </div>
                <div class="pagination-links">
                    {{ $users->links() }}
                </div>
            </div>
        @endif
    </div>
@endif

{{-- ========== STYLES ========== --}}
<style>
    /* ========== PAGE HEADER ========== */
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

    /* ========== STATS MINI CARDS ========== */
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

    .stats-mini-icon-purple {
        background: #f5f3ff;
        color: #7c3aed;
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

    /* ========== FILTERS BAR ========== */
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

    /* ========== TABLE PANEL ========== */
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

    /* ========== USER CELL ========== */
    .user-cell-main {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .user-avatar-circle {
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
        overflow: hidden;
    }

    .user-avatar-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .user-avatar-initials {
        font-weight: 700;
        font-size: 0.9rem;
    }

    .user-info-cell {
        min-width: 0;
    }

    .user-display-name {
        font-weight: 600;
        color: #0d1b2a;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 160px;
    }

    .user-joined-info {
        font-size: 0.7rem;
        color: #94a3b8;
        margin-top: 0.1rem;
    }

    /* ========== EMAIL CELL ========== */
    .email-cell {
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }

    .email-text-display {
        color: #415a77;
        font-weight: 500;
        font-size: 0.85rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 180px;
        display: block;
    }

    .email-verified-badge {
        color: #10b981;
        font-size: 0.8rem;
        flex-shrink: 0;
    }

    /* ========== ROLES CELL ========== */
    .roles-cell {
        display: flex;
        flex-wrap: wrap;
        gap: 0.35rem;
    }

    .role-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.25rem 0.7rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        white-space: nowrap;
    }

    .role-badge i {
        font-size: 0.65rem;
    }

    .role-badge-admin {
        background: #fef2f2;
        color: #dc2626;
    }

    .role-badge-business_owner {
        background: #eff6ff;
        color: #2563eb;
    }

    .role-badge-moderator {
        background: #f5f3ff;
        color: #7c3aed;
    }

    .role-badge-user {
        background: #f1f5f9;
        color: #475569;
    }

    /* ========== STATUS INDICATOR ========== */
    .status-indicator {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .status-dot-circle {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .status-indicator-active {
        color: #059669;
    }

    .status-indicator-active .status-dot-circle {
        background: #10b981;
    }

    .status-indicator-suspended {
        color: #dc2626;
    }

    .status-indicator-suspended .status-dot-circle {
        background: #ef4444;
    }

    /* ========== DATE CELL ========== */
    .date-cell-display {
        display: flex;
        flex-direction: column;
    }

    .date-main-text {
        color: #415a77;
        font-weight: 500;
        white-space: nowrap;
    }

    .date-sub-text {
        font-size: 0.7rem;
        color: #94a3b8;
    }

    /* ========== ACTIONS ========== */
    .actions-group-inline {
        display: flex;
        gap: 0.4rem;
        justify-content: center;
    }

    .action-form-inline {
        display: inline-block;
    }

    .action-btn-toggle,
    .action-btn-delete {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.4rem 0.85rem;
        border-radius: 30px;
        font-size: 0.75rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.15s ease;
        border: 1.5px solid;
        font-family: 'Tajawal', 'Inter', sans-serif;
        white-space: nowrap;
    }

    .action-btn-toggle-suspend {
        background: #fffbeb;
        color: #b45309;
        border-color: #fcd34d;
    }

    .action-btn-toggle-suspend:hover {
        background: #f59e0b;
        color: #ffffff;
        border-color: #f59e0b;
    }

    .action-btn-toggle-activate {
        background: #ecfdf5;
        color: #059669;
        border-color: #6ee7b7;
    }

    .action-btn-toggle-activate:hover {
        background: #10b981;
        color: #ffffff;
        border-color: #10b981;
    }

    .action-btn-delete {
        background: #fef2f2;
        color: #dc2626;
        border-color: #fca5a5;
    }

    .action-btn-delete:hover {
        background: #ef4444;
        color: #ffffff;
        border-color: #ef4444;
    }

    .action-btn-toggle:disabled,
    .action-btn-delete:disabled {
        opacity: 0.4;
        cursor: not-allowed;
        pointer-events: none;
    }

    /* ========== PAGINATION ========== */
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

    /* ========== EMPTY STATE ========== */
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

    /* ========== RESPONSIVE ========== */
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
            padding: 0.7rem 0.5rem;
            font-size: 0.73rem;
        }

        .user-avatar-circle {
            width: 32px;
            height: 32px;
            font-size: 0.75rem;
        }

        .user-display-name {
            max-width: 90px;
        }

        .email-text-display {
            max-width: 120px;
        }

        .pagination-wrapper {
            flex-direction: column;
            align-items: flex-start;
        }

        .action-btn-toggle span,
        .action-btn-delete span {
            display: none;
        }

        .action-btn-toggle,
        .action-btn-delete {
            padding: 0.4rem 0.55rem;
        }
    }

    @media (max-width: 480px) {
        .pagination-links .pagination li a,
        .pagination-links .pagination li span {
            width: 30px;
            height: 30px;
            font-size: 0.7rem;
        }
    }
</style>

{{-- ========== SCRIPTS ========== --}}
<script>
    (function() {
        'use strict';

        // Filter Pills Functionality
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
                        } else if (filterValue === 'active' || filterValue === 'suspended') {
                            const rowStatus = row.getAttribute('data-status');
                            if (rowStatus === filterValue) {
                                row.style.display = '';
                            } else {
                                row.style.display = 'none';
                            }
                        } else {
                            const rowRole = row.getAttribute('data-role');
                            if (rowRole === filterValue) {
                                row.style.display = '';
                            } else {
                                row.style.display = 'none';
                            }
                        }
                    });
                }
            });
        });

        // Search Functionality
        const searchField = document.getElementById('searchUser');

        if (searchField && tableRows.length > 0) {
            searchField.addEventListener('input', function() {
                const query = this.value.trim().toLowerCase();

                tableRows.forEach(function(row) {
                    const name = (row.querySelector('.user-display-name')?.textContent || '').toLowerCase();
                    const email = (row.querySelector('.email-text-display')?.textContent || '').toLowerCase();

                    if (name.includes(query) || email.includes(query)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }

        // Sort Select Functionality
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
                            aVal = (a.querySelector('.user-display-name')?.textContent || '').trim();
                            bVal = (b.querySelector('.user-display-name')?.textContent || '').trim();
                            return aVal.localeCompare(bVal, 'ar');

                        case 'name_desc':
                            aVal = (a.querySelector('.user-display-name')?.textContent || '').trim();
                            bVal = (b.querySelector('.user-display-name')?.textContent || '').trim();
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

        // Confirm before delete
        const deleteBtns = document.querySelectorAll('.action-btn-delete');

        deleteBtns.forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                const row = this.closest('.data-table-row');
                const userName = row?.querySelector('.user-display-name')?.textContent?.trim() || 'هذا المستخدم';

                const message = 'هل أنت متأكد من حذف "' + userName + '"؟\n\nلا يمكن التراجع عن هذا الإجراء.';

                if (!confirm(message)) {
                    e.preventDefault();
                }
            });
        });

        // Confirm before toggle status
        const toggleBtns = document.querySelectorAll('.action-btn-toggle-suspend, .action-btn-toggle-activate');

        toggleBtns.forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                const row = this.closest('.data-table-row');
                const userName = row?.querySelector('.user-display-name')?.textContent?.trim() || 'هذا المستخدم';
                const isSuspending = this.classList.contains('action-btn-toggle-suspend');

                let message;
                if (isSuspending) {
                    message = 'هل أنت متأكد من تعليق "' + userName + '"؟\n\nلن يتمكن المستخدم من الوصول إلى حسابه.';
                } else {
                    message = 'هل أنت متأكد من تفعيل "' + userName + '"؟\n\nسيتمكن المستخدم من الوصول إلى حسابه مرة أخرى.';
                }

                if (!confirm(message)) {
                    e.preventDefault();
                }
            });
        });

        // Keyboard shortcut: Ctrl+K for search focus
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
