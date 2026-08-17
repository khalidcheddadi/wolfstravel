@extends('layouts.business-owner')

@section('content')
{{-- Loading Overlay --}}
<div id="pageLoadingOverlay" style="
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: var(--bg-main, #f8f9fa);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    transition: opacity 0.4s ease, visibility 0.4s ease;
">
    <div style="text-align: center;">
        <div class="loading-spinner" style="
            width: 50px;
            height: 50px;
            border: 3px solid var(--border, #e5e7eb);
            border-top-color: var(--primary, #8B5E3C);
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin: 0 auto 1rem;
        "></div>
        <p style="
            color: var(--text-secondary, #6b7280);
            font-size: 0.9rem;
            font-weight: 500;
            margin: 0;
        ">جاري التحميل...</p>
    </div>
</div>

<style>
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>

<div class="anim-fade-up" id="mainContent" style="opacity: 0; transition: opacity 0.5s ease;">
    {{-- Page Header --}}
    <div class="top-bar" style="margin-top: 1rem;">
        <div>
            <h2 class="page-title" style="font-size: 1.5rem; margin-bottom: 0.3rem;">تعديل المنشأة</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem; font-weight: 500;">تعديل معلومات المنشأة</p>
        </div>
        <div class="top-actions">
            <a href="{{ route('business-owner.dashboard') }}" class="btn-ghost" style="color: var(--text-secondary); border-color: var(--border); background: var(--bg-main);">
                <i class="fa-solid fa-arrow-right"></i> العودة للوحة القيادة
            </a>
        </div>
    </div>

    @if(session('error'))
        <div class="alert-error" style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem; background: #fffbfb; border: 1px solid #fca5a5; color: #dc2626; padding: 1rem; border-radius: 12px; font-weight: 500;">
            <i class="fa-solid fa-circle-exclamation"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- Form Card --}}
    <div class="panel anim-fade-up anim-delay-1" style="max-width: 900px;">
        <form action="{{ route('business-owner.business.update') }}" method="POST" id="businessForm">
            @csrf
            @method('PUT')

            {{-- Business Name --}}
            <div style="margin-bottom: 1.8rem;">
                <label for="business_name" style="display: block; font-weight: 650; color: var(--text-main); margin-bottom: 0.6rem; font-size: 0.9rem;">
                    اسم المنشأة <span style="color: #ef4444;">*</span>
                </label>
                <div class="input-wrapper {{ $errors->has('business_name') ? 'input-error' : '' }}">
                    <i class="fa-solid fa-building input-icon"></i>
                    <input type="text" name="business_name" id="business_name"
                           class="modern-input"
                           value="{{ old('business_name', $business->business_name) }}"
                           placeholder="مثال: فندق الرياض، مطعم الأندلس، وكالة السفر العالمية"
                           required>
                </div>
                @error('business_name')
                    <span class="error-message">
                        <i class="fa-solid fa-circle-exclamation"></i>{{ $message }}
                    </span>
                @enderror
            </div>

            {{-- Business Type --}}
            <div style="margin-bottom: 1.8rem;">
                <label style="display: block; font-weight: 650; color: var(--text-main); margin-bottom: 0.6rem; font-size: 0.9rem;">
                    نوع المنشأة <span style="color: #ef4444;">*</span>
                </label>
                <div class="custom-select-wrapper {{ $errors->has('business_type_id') ? 'select-error' : '' }}">
                    <div class="custom-select" id="businessTypeSelect">
                        <div class="custom-select-trigger">
                            <i class="fa-solid fa-tags select-icon"></i>
                            <span class="custom-select-placeholder">اختر نوع المنشأة</span>
                            <i class="fa-solid fa-chevron-down select-arrow"></i>
                        </div>
                        <div class="custom-options">
                            <div class="search-box-wrapper">
                                <i class="fa-solid fa-search search-icon"></i>
                                <input type="text" class="search-input" placeholder="بحث...">
                            </div>
                            @foreach($businessTypes as $type)
                                <div class="custom-option {{ old('business_type_id', $business->business_type_id) == $type->id ? 'selected' : '' }}"
                                     data-value="{{ $type->id }}">
                                    <div class="option-content">
                                        <span class="option-text">{{ $type->name }}</span>
                                        @if(old('business_type_id', $business->business_type_id) == $type->id)
                                            <i class="fa-solid fa-check option-check"></i>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <input type="hidden" name="business_type_id" id="business_type_id" value="{{ old('business_type_id', $business->business_type_id) }}">
                </div>
                @error('business_type_id')
                    <span class="error-message">
                        <i class="fa-solid fa-circle-exclamation"></i>{{ $message }}
                    </span>
                @enderror
            </div>

            {{-- Country & City Grid --}}
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.2rem; margin-bottom: 1.8rem;">
                <div>
                    <label style="display: block; font-weight: 650; color: var(--text-main); margin-bottom: 0.6rem; font-size: 0.9rem;">الدولة</label>
                    <div class="custom-select-wrapper {{ $errors->has('country_id') ? 'select-error' : '' }}">
                        <div class="custom-select" id="countrySelect">
                            <div class="custom-select-trigger">
                                <i class="fa-solid fa-globe select-icon"></i>
                                <span class="custom-select-placeholder">اختر الدولة</span>
                                <i class="fa-solid fa-chevron-down select-arrow"></i>
                            </div>
                            <div class="custom-options">
                                <div class="search-box-wrapper">
                                    <i class="fa-solid fa-search search-icon"></i>
                                    <input type="text" class="search-input" placeholder="بحث...">
                                </div>
                                @foreach($countries as $country)
                                    <div class="custom-option {{ old('country_id', $business->country_id) == $country->id ? 'selected' : '' }}"
                                         data-value="{{ $country->id }}">
                                        <div class="option-content">
                                            <span class="option-text">{{ $country->name }}</span>
                                            @if(old('country_id', $business->country_id) == $country->id)
                                                <i class="fa-solid fa-check option-check"></i>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <input type="hidden" name="country_id" id="country_id" value="{{ old('country_id', $business->country_id) }}">
                    </div>
                    @error('country_id')
                        <span class="error-message">
                            <i class="fa-solid fa-circle-exclamation"></i>{{ $message }}
                        </span>
                    @enderror
                </div>

                <div>
                    <label style="display: block; font-weight: 650; color: var(--text-main); margin-bottom: 0.6rem; font-size: 0.9rem;">المدينة</label>
                    <div class="custom-select-wrapper {{ $errors->has('city_id') ? 'select-error' : '' }}">
                        <div class="custom-select" id="citySelect">
                            <div class="custom-select-trigger">
                                <i class="fa-solid fa-city select-icon"></i>
                                <span class="custom-select-placeholder">اختر المدينة</span>
                                <i class="fa-solid fa-chevron-down select-arrow"></i>
                            </div>
                            <div class="custom-options" id="cityOptions">
                                <div class="search-box-wrapper">
                                    <i class="fa-solid fa-search search-icon"></i>
                                    <input type="text" class="search-input" placeholder="بحث...">
                                </div>
                                <div class="city-loading" style="display: none; padding: 2rem; text-align: center;">
                                    <div style="width: 30px; height: 30px; border: 3px solid var(--border); border-top-color: var(--primary); border-radius: 50%; animation: spin 0.8s linear infinite; margin: 0 auto;"></div>
                                </div>
                                <div class="no-cities-message" style="display: none; padding: 1.5rem; text-align: center; color: #94a3b8; font-size: 0.85rem; font-weight: 500;">
                                    يرجى اختيار الدولة أولاً
                                </div>
                                @foreach($cities as $city)
                                    <div class="custom-option {{ old('city_id', $business->city_id) == $city->id ? 'selected' : '' }}"
                                         data-value="{{ $city->id }}">
                                        <div class="option-content">
                                            <span class="option-text">{{ $city->name }}</span>
                                            @if(old('city_id', $business->city_id) == $city->id)
                                                <i class="fa-solid fa-check option-check"></i>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <input type="hidden" name="city_id" id="city_id" value="{{ old('city_id', $business->city_id) }}">
                    </div>
                    @error('city_id')
                        <span class="error-message">
                            <i class="fa-solid fa-circle-exclamation"></i>{{ $message }}
                        </span>
                    @enderror
                </div>
            </div>

            {{-- Address --}}
            <div style="margin-bottom: 1.8rem;">
                <label for="address" style="display: block; font-weight: 650; color: var(--text-main); margin-bottom: 0.6rem; font-size: 0.9rem;">العنوان</label>
                <div class="input-wrapper {{ $errors->has('address') ? 'input-error' : '' }}">
                    <i class="fa-solid fa-location-dot input-icon"></i>
                    <input type="text" name="address" id="address"
                           class="modern-input"
                           value="{{ old('address', $business->address) }}"
                           placeholder="العنوان الكامل للمنشأة">
                </div>
                @error('address')
                    <span class="error-message">
                        <i class="fa-solid fa-circle-exclamation"></i>{{ $message }}
                    </span>
                @enderror
            </div>

            {{-- Phone & Website Grid --}}
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.2rem; margin-bottom: 1.8rem;">
                <div>
                    <label for="phone" style="display: block; font-weight: 650; color: var(--text-main); margin-bottom: 0.6rem; font-size: 0.9rem;">رقم الهاتف</label>
                    <div class="input-wrapper {{ $errors->has('phone') ? 'input-error' : '' }}">
                        <i class="fa-solid fa-phone input-icon"></i>
                        <input type="text" name="phone" id="phone"
                               class="modern-input"
                               value="{{ old('phone', $business->phone) }}"
                               placeholder="+34 912 345 678">
                    </div>
                    @error('phone')
                        <span class="error-message">
                            <i class="fa-solid fa-circle-exclamation"></i>{{ $message }}
                        </span>
                    @enderror
                </div>

                <div>
                    <label for="website" style="display: block; font-weight: 650; color: var(--text-main); margin-bottom: 0.6rem; font-size: 0.9rem;">الموقع الإلكتروني</label>
                    <div class="input-wrapper {{ $errors->has('website') ? 'input-error' : '' }}">
                        <i class="fa-solid fa-link input-icon"></i>
                        <input type="url" name="website" id="website"
                               class="modern-input"
                               value="{{ old('website', $business->website) }}"
                               placeholder="https://example.com">
                    </div>
                    @error('website')
                        <span class="error-message">
                            <i class="fa-solid fa-circle-exclamation"></i>{{ $message }}
                        </span>
                    @enderror
                </div>
            </div>

            {{-- Action Buttons --}}
            <div style="display: flex; gap: 0.9rem; margin-top: 2.5rem; padding-top: 1.8rem; border-top: 1px solid var(--border-light);">
                <button type="submit" class="btn-primary" style="padding: 0.8rem 2.2rem; font-size: 0.9rem;">
                    <i class="fa-solid fa-pen-to-square"></i> تحديث المنشأة
                </button>
                <a href="{{ route('business-owner.dashboard') }}"
                   class="btn-cancel">
                    <i class="fa-solid fa-xmark"></i> إلغاء
                </a>
            </div>
        </form>
    </div>
</div>

<style>
    /* ========== CUSTOM SELECT STYLES ========== */
    .custom-select-wrapper {
        position: relative;
        width: 100%;
    }

    .custom-select {
        position: relative;
        width: 100%;
    }

    .custom-select-trigger {
        position: relative;
        display: flex;
        align-items: center;
        width: 100%;
        padding: 0.85rem 2.8rem 0.85rem 2.8rem;
        border: 2px solid var(--border);
        border-radius: 12px;
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--text-main);
        background: var(--bg-main);
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        user-select: none;
        -webkit-user-select: none;
    }

    .custom-select-trigger:hover {
        border-color: #cbd5e1;
        background: #fafbfc;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
    }

    .custom-select.open .custom-select-trigger {
        border-color: var(--primary);
        background: #fff;
        box-shadow: 0 0 0 4px rgba(148, 93, 54, 0.08), 0 8px 24px rgba(0, 0, 0, 0.06);
        border-radius: 12px 12px 0 0;
    }

    .custom-select-placeholder {
        color: #b0bcc9;
        font-weight: 400;
        font-size: 0.85rem;
        flex: 1;
    }

    .custom-select-trigger .select-icon {
        position: absolute;
        right: 1.1rem;
        color: #b0bcc9;
        font-size: 1rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .custom-select.open .select-icon {
        color: var(--primary);
        transform: scale(1.1) rotate(-5deg);
    }

    .custom-select-trigger .select-arrow {
        position: absolute;
        left: 1.1rem;
        color: #b0bcc9;
        font-size: 0.8rem;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .custom-select.open .select-arrow {
        color: var(--primary);
        transform: rotate(180deg) scale(1.1);
    }

    .custom-options {
        position: absolute;
        top: calc(100% - 2px);
        left: 0;
        right: 0;
        background: white;
        border: 2px solid var(--primary);
        border-top: none;
        border-radius: 0 0 12px 12px;
        max-height: 280px;
        overflow-y: auto;
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1), 0 8px 16px rgba(0, 0, 0, 0.06);
    }

    .custom-select.open .custom-options {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    /* ========== SEARCH BOX STYLES ========== */
    .search-box-wrapper {
        position: sticky;
        top: 0;
        background: white;
        padding: 0.75rem;
        border-bottom: 2px solid rgba(0, 0, 0, 0.06);
        z-index: 2;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .search-icon {
        color: #b0bcc9;
        font-size: 0.9rem;
        margin-right: 0.25rem;
    }

    .search-input {
        width: 100%;
        border: none;
        outline: none;
        font-size: 0.85rem;
        font-weight: 500;
        color: var(--text-main);
        background: transparent;
        font-family: inherit;
        padding: 0.25rem 0;
    }

    .search-input::placeholder {
        color: #b0bcc9;
        font-weight: 400;
    }

    .search-input:focus {
        outline: none;
    }

    .custom-option.hidden-by-search {
        display: none !important;
    }

    .no-results {
        padding: 1.5rem;
        text-align: center;
        color: #94a3b8;
        font-size: 0.85rem;
        font-weight: 500;
        display: none;
    }

    .no-results.show {
        display: block;
    }

    /* ========== OPTION STYLES ========== */
    .custom-option {
        padding: 0;
        cursor: pointer;
        transition: all 0.2s ease;
        border-bottom: 1px solid rgba(0, 0, 0, 0.04);
        opacity: 1;
    }

    .custom-option:last-child {
        border-bottom: none;
        border-radius: 0 0 10px 10px;
    }

    .option-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.9rem 1.5rem;
        transition: all 0.2s ease;
        position: relative;
    }

    .option-content::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: var(--primary);
        transform: scaleY(0);
        transition: transform 0.2s ease;
    }

    .custom-option:hover .option-content {
        background: linear-gradient(135deg, #fef7f1, #fff0e8);
        padding-left: 2rem;
        padding-right: 2rem;
    }

    .custom-option:hover .option-content::before {
        transform: scaleY(1);
    }

    .custom-option.selected .option-content {
        background: linear-gradient(135deg, #f5e6d8, #fce8da);
        font-weight: 650;
        color: var(--primary);
    }

    .custom-option.selected .option-content::before {
        transform: scaleY(1);
    }

    .option-text {
        flex: 1;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .option-check {
        color: var(--primary);
        font-size: 0.9rem;
        margin-left: 0.8rem;
        animation: checkBounce 0.4s ease;
    }

    @keyframes checkBounce {
        0% { transform: scale(0); }
        50% { transform: scale(1.3); }
        100% { transform: scale(1); }
    }

    /* Scrollbar Styles */
    .custom-options::-webkit-scrollbar {
        width: 6px;
    }

    .custom-options::-webkit-scrollbar-track {
        background: #f8fafc;
        border-radius: 0 0 10px 10px;
    }

    .custom-options::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #d4c5b5, #c4b5a5);
        border-radius: 10px;
    }

    .custom-options::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(180deg, #c4b5a5, #b4a595);
    }

    /* Firefox scrollbar */
    .custom-options {
        scrollbar-color: #d4c5b5 #f8fafc;
        scrollbar-width: thin;
    }

    /* Error State */
    .select-error .custom-select-trigger {
        border-color: #fca5a5;
        background: #fffbfb;
        animation: shake 0.4s ease;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        20% { transform: translateX(-6px); }
        40% { transform: translateX(6px); }
        60% { transform: translateX(-4px); }
        80% { transform: translateX(4px); }
    }

    /* Ripple Effect */
    .ripple {
        position: absolute;
        border-radius: 50%;
        background: rgba(148, 93, 54, 0.15);
        transform: scale(0);
        animation: rippleEffect 0.6s ease-out;
        pointer-events: none;
    }

    @keyframes rippleEffect {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }

    /* Existing input styles */
    .input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .modern-input {
        width: 100%;
        padding: 0.85rem 3rem 0.85rem 2.8rem;
        border: 2px solid var(--border);
        border-radius: 12px;
        font-size: 0.9rem;
        font-weight: 500;
        color: var(--text-main);
        background: var(--bg-main);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        outline: none;
        font-family: inherit;
        letter-spacing: -0.1px;
    }

    .modern-input:hover {
        border-color: #cbd5e1;
        background: #fafbfc;
    }

    .modern-input:focus {
        border-color: var(--primary);
        background: #fff;
        box-shadow: 0 0 0 4px rgba(148, 93, 54, 0.06), 0 2px 8px rgba(0,0,0,0.02);
    }

    .modern-input::placeholder {
        color: #b0bcc9;
        font-weight: 400;
        font-size: 0.85rem;
    }

    .input-icon {
        position: absolute;
        right: 1.1rem;
        color: #b0bcc9;
        font-size: 1rem;
        transition: all 0.3s ease;
        pointer-events: none;
        z-index: 2;
    }

    .input-wrapper:focus-within .input-icon,
    .modern-input:focus ~ .input-icon {
        color: var(--primary);
        transform: scale(1.1);
    }

    .input-error .modern-input {
        border-color: #fca5a5;
        background: #fffbfb;
        animation: shake 0.4s ease;
    }

    .input-error .modern-input:focus {
        border-color: #ef4444;
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.06);
    }

    .input-error .input-icon {
        color: #ef4444;
    }

    .error-message {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        color: #dc2626;
        font-size: 0.78rem;
        font-weight: 600;
        margin-top: 0.5rem;
        animation: slideDown 0.3s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-8px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .error-message i {
        font-size: 0.85rem;
    }

    .btn-cancel {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.8rem 2rem;
        border-radius: 30px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 2px solid var(--border);
        color: var(--text-secondary);
        background: var(--bg-main);
    }

    .btn-cancel:hover {
        border-color: var(--text-muted);
        color: var(--text-main);
        background: var(--bg-tertiary);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    }

    @media (max-width: 768px) {
        .custom-select-trigger {
            padding: 0.75rem 2.5rem 0.75rem 2.5rem;
            font-size: 0.85rem;
        }

        .option-content {
            padding: 0.75rem 1.2rem;
        }

        .option-text {
            font-size: 0.85rem;
        }
    }

    @media (max-width: 480px) {
        .custom-select-trigger {
            padding: 0.7rem 2.3rem 0.7rem 2.3rem;
            font-size: 0.8rem;
        }

        .option-content {
            padding: 0.6rem 1rem;
        }

        .option-text {
            font-size: 0.8rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Hide loading overlay and show content
        const loadingOverlay = document.getElementById('pageLoadingOverlay');
        const mainContent = document.getElementById('mainContent');

        // Small delay to ensure everything is rendered
        setTimeout(() => {
            // Fade out loading overlay
            loadingOverlay.style.opacity = '0';
            loadingOverlay.style.visibility = 'hidden';

            // Fade in main content
            setTimeout(() => {
                mainContent.style.opacity = '1';

                // Initialize custom selects after content is visible
                initializeCustomSelects();
            }, 200);
        }, 300);

        function initializeCustomSelects() {
            // Initialize all custom selects
            const customSelects = document.querySelectorAll('.custom-select');

            customSelects.forEach(select => {
                const trigger = select.querySelector('.custom-select-trigger');
                const options = select.querySelectorAll('.custom-option');
                const hiddenInput = select.parentElement.querySelector('input[type="hidden"]');
                const searchInput = select.querySelector('.search-input');
                const optionsContainer = select.querySelector('.custom-options');

                // Create "no results" element
                const noResults = document.createElement('div');
                noResults.className = 'no-results';
                noResults.textContent = 'لا توجد نتائج';
                optionsContainer.appendChild(noResults);

                // Set initial selected option text
                const selectedOption = select.querySelector('.custom-option.selected');
                if (selectedOption) {
                    const placeholder = trigger.querySelector('.custom-select-placeholder');
                    placeholder.textContent = selectedOption.querySelector('.option-text').textContent;
                    placeholder.style.color = 'var(--text-main)';
                    placeholder.style.fontWeight = '500';
                    hiddenInput.value = selectedOption.dataset.value;
                }

                // Search functionality
                if (searchInput) {
                    searchInput.addEventListener('input', function(e) {
                        const searchTerm = this.value.toLowerCase().trim();
                        let hasResults = false;

                        options.forEach(option => {
                            const text = option.querySelector('.option-text').textContent.toLowerCase();
                            if (text.includes(searchTerm)) {
                                option.classList.remove('hidden-by-search');
                                hasResults = true;
                            } else {
                                option.classList.add('hidden-by-search');
                            }
                        });

                        // Show/hide no results message
                        if (!hasResults && searchTerm !== '') {
                            noResults.classList.add('show');
                        } else {
                            noResults.classList.remove('show');
                        }
                    });

                    // Prevent search input click from closing dropdown
                    searchInput.addEventListener('click', function(e) {
                        e.stopPropagation();
                    });

                    // Clear search when dropdown closes
                    const observer = new MutationObserver(function(mutations) {
                        mutations.forEach(function(mutation) {
                            if (mutation.target === select && !select.classList.contains('open')) {
                                searchInput.value = '';
                                options.forEach(option => {
                                    option.classList.remove('hidden-by-search');
                                });
                                noResults.classList.remove('show');
                            }
                        });
                    });

                    observer.observe(select, {
                        attributes: true,
                        attributeFilter: ['class']
                    });
                }

                // Toggle dropdown
                trigger.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isOpen = select.classList.contains('open');

                    // Close all other selects
                    customSelects.forEach(s => {
                        if (s !== select) {
                            s.classList.remove('open');
                        }
                    });

                    if (!isOpen) {
                        select.classList.add('open');
                        // Add ripple effect
                        addRipple(e, trigger);

                        // Focus search input if exists
                        setTimeout(() => {
                            if (searchInput) {
                                searchInput.focus();
                            }
                        }, 100);
                    } else {
                        select.classList.remove('open');
                    }
                });

                // Option selection
                options.forEach(option => {
                    option.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const value = this.dataset.value;
                        const text = this.querySelector('.option-text').textContent;
                        const placeholder = trigger.querySelector('.custom-select-placeholder');

                        // Remove selected class from all options in this select
                        options.forEach(opt => {
                            opt.classList.remove('selected');
                            const check = opt.querySelector('.option-check');
                            if (check) check.remove();
                        });

                        // Add selected class to clicked option
                        this.classList.add('selected');

                        // Add check icon
                        const content = this.querySelector('.option-content');
                        const checkIcon = document.createElement('i');
                        checkIcon.className = 'fa-solid fa-check option-check';
                        content.appendChild(checkIcon);

                        // Update trigger text
                        placeholder.textContent = text;
                        placeholder.style.color = 'var(--text-main)';
                        placeholder.style.fontWeight = '500';

                        // Update hidden input
                        hiddenInput.value = value;

                        // Trigger change event
                        hiddenInput.dispatchEvent(new Event('change', { bubbles: true }));

                        // If this is the country select, fetch cities
                        if (select.id === 'countrySelect') {
                            fetchCitiesForCountry(value);
                        }

                        // Close dropdown
                        select.classList.remove('open');
                    });
                });
            });

            // Close dropdowns when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.custom-select')) {
                    customSelects.forEach(select => {
                        select.classList.remove('open');
                    });
                }
            });

            // Close dropdowns on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    customSelects.forEach(select => {
                        select.classList.remove('open');
                    });
                }
            });

            // Ripple effect function
            function addRipple(e, element) {
                const ripple = document.createElement('span');
                ripple.className = 'ripple';

                const rect = element.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;

                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';

                element.style.position = 'relative';
                element.style.overflow = 'hidden';
                element.appendChild(ripple);

                ripple.addEventListener('animationend', () => {
                    ripple.remove();
                });
            }

            // Function to fetch cities for selected country
            function fetchCitiesForCountry(countryId) {
                const citySelect = document.getElementById('citySelect');
                const cityOptionsContainer = document.getElementById('cityOptions');
                const cityHiddenInput = document.getElementById('city_id');
                const cityTrigger = citySelect.querySelector('.custom-select-trigger');
                const cityPlaceholder = cityTrigger.querySelector('.custom-select-placeholder');

                // Remove existing city options (keep search box, loading, and no-cities message)
                const existingOptions = cityOptionsContainer.querySelectorAll('.custom-option');
                existingOptions.forEach(opt => opt.remove());

                // Reset city selection
                cityHiddenInput.value = '';
                cityPlaceholder.textContent = 'اختر المدينة';
                cityPlaceholder.style.color = '#b0bcc9';
                cityPlaceholder.style.fontWeight = '400';

                // Remove any no-results element if exists
                const existingNoResults = cityOptionsContainer.querySelector('.no-results');
                if (existingNoResults) existingNoResults.remove();

                // Show loading
                const loadingEl = cityOptionsContainer.querySelector('.city-loading');
                const noCitiesMsg = cityOptionsContainer.querySelector('.no-cities-message');
                const searchBox = cityOptionsContainer.querySelector('.search-box-wrapper');

                if (loadingEl) loadingEl.style.display = 'block';
                if (noCitiesMsg) noCitiesMsg.style.display = 'none';
                if (searchBox) searchBox.style.display = 'none';

                // Fetch cities from server
                fetch(`/api/countries/${countryId}/cities`)
                    .then(response => response.json())
                    .then(data => {
                        // Hide loading
                        if (loadingEl) loadingEl.style.display = 'none';
                        if (searchBox) searchBox.style.display = 'flex';

                        if (data.cities && data.cities.length > 0) {
                            if (noCitiesMsg) noCitiesMsg.style.display = 'none';

                            // Add new city options
                            data.cities.forEach(city => {
                                const optionDiv = document.createElement('div');
                                optionDiv.className = 'custom-option';
                                optionDiv.setAttribute('data-value', city.id);
                                optionDiv.innerHTML = `
                                    <div class="option-content">
                                        <span class="option-text">${city.name}</span>
                                    </div>
                                `;

                                // Add click event to new option
                                optionDiv.addEventListener('click', function(e) {
                                    e.stopPropagation();

                                    // Remove selected class from all city options
                                    const allCityOptions = cityOptionsContainer.querySelectorAll('.custom-option');
                                    allCityOptions.forEach(opt => {
                                        opt.classList.remove('selected');
                                        const check = opt.querySelector('.option-check');
                                        if (check) check.remove();
                                    });

                                    // Add selected class
                                    this.classList.add('selected');

                                    // Add check icon
                                    const content = this.querySelector('.option-content');
                                    const checkIcon = document.createElement('i');
                                    checkIcon.className = 'fa-solid fa-check option-check';
                                    content.appendChild(checkIcon);

                                    // Update trigger text
                                    cityPlaceholder.textContent = city.name;
                                    cityPlaceholder.style.color = 'var(--text-main)';
                                    cityPlaceholder.style.fontWeight = '500';

                                    // Update hidden input
                                    cityHiddenInput.value = city.id;

                                    // Close dropdown
                                    citySelect.classList.remove('open');

                                    // Trigger change event
                                    cityHiddenInput.dispatchEvent(new Event('change', { bubbles: true }));
                                });

                                // Add keyboard navigation
                                optionDiv.setAttribute('tabindex', '0');
                                optionDiv.addEventListener('keydown', function(e) {
                                    if (e.key === 'Enter' || e.key === ' ') {
                                        e.preventDefault();
                                        this.click();
                                    }
                                });

                                cityOptionsContainer.appendChild(optionDiv);
                            });

                            // Add no-results element for search
                            const noResults = document.createElement('div');
                            noResults.className = 'no-results';
                            noResults.textContent = 'لا توجد نتائج';
                            cityOptionsContainer.appendChild(noResults);

                            // Reinitialize search for city select
                            const citySearchInput = cityOptionsContainer.querySelector('.search-input');
                            if (citySearchInput) {
                                citySearchInput.value = '';
                                // Remove old event listener by cloning
                                const newSearchInput = citySearchInput.cloneNode(true);
                                citySearchInput.parentNode.replaceChild(newSearchInput, citySearchInput);

                                newSearchInput.addEventListener('input', function(e) {
                                    const searchTerm = this.value.toLowerCase().trim();
                                    let hasResults = false;

                                    const cityOptions = cityOptionsContainer.querySelectorAll('.custom-option');
                                    cityOptions.forEach(option => {
                                        const text = option.querySelector('.option-text').textContent.toLowerCase();
                                        if (text.includes(searchTerm)) {
                                            option.classList.remove('hidden-by-search');
                                            hasResults = true;
                                        } else {
                                            option.classList.add('hidden-by-search');
                                        }
                                    });

                                    const noResultsEl = cityOptionsContainer.querySelector('.no-results');
                                    if (!hasResults && searchTerm !== '') {
                                        if (noResultsEl) noResultsEl.classList.add('show');
                                    } else {
                                        if (noResultsEl) noResultsEl.classList.remove('show');
                                    }
                                });

                                newSearchInput.addEventListener('click', function(e) {
                                    e.stopPropagation();
                                });
                            }
                        } else {
                            if (noCitiesMsg) noCitiesMsg.style.display = 'block';
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching cities:', error);
                        if (loadingEl) loadingEl.style.display = 'none';
                        if (noCitiesMsg) {
                            noCitiesMsg.textContent = 'حدث خطأ في تحميل المدن';
                            noCitiesMsg.style.display = 'block';
                        }
                    });
            }

            // Keyboard navigation for custom selects
            customSelects.forEach(select => {
                const trigger = select.querySelector('.custom-select-trigger');
                const options = select.querySelectorAll('.custom-option');
                const searchInput = select.querySelector('.search-input');
                let currentFocus = -1;

                trigger.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ' || e.key === 'ArrowDown') {
                        e.preventDefault();
                        if (!select.classList.contains('open')) {
                            select.classList.add('open');
                            currentFocus = -1;

                            // Focus search input
                            setTimeout(() => {
                                if (searchInput) {
                                    searchInput.focus();
                                }
                            }, 100);
                        }

                        if (e.key === 'ArrowDown') {
                            currentFocus++;
                            const visibleOptions = Array.from(options).filter(opt => !opt.classList.contains('hidden-by-search'));
                            if (currentFocus >= visibleOptions.length) currentFocus = 0;
                            if (visibleOptions[currentFocus]) {
                                visibleOptions[currentFocus].focus();
                            }
                        }
                    }

                    if (e.key === 'ArrowUp' && select.classList.contains('open')) {
                        e.preventDefault();
                        currentFocus--;
                        const visibleOptions = Array.from(options).filter(opt => !opt.classList.contains('hidden-by-search'));
                        if (currentFocus < 0) currentFocus = visibleOptions.length - 1;
                        if (visibleOptions[currentFocus]) {
                            visibleOptions[currentFocus].focus();
                        }
                    }
                });

                options.forEach((option, index) => {
                    option.setAttribute('tabindex', '0');

                    option.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter' || e.key === ' ') {
                            e.preventDefault();
                            option.click();
                        }

                        if (e.key === 'ArrowDown') {
                            e.preventDefault();
                            const visibleOptions = Array.from(options).filter(opt => !opt.classList.contains('hidden-by-search'));
                            const currentIndex = visibleOptions.indexOf(this);
                            const nextIndex = currentIndex + 1;
                            if (nextIndex < visibleOptions.length) {
                                visibleOptions[nextIndex].focus();
                            }
                        }

                        if (e.key === 'ArrowUp') {
                            e.preventDefault();
                            const visibleOptions = Array.from(options).filter(opt => !opt.classList.contains('hidden-by-search'));
                            const currentIndex = visibleOptions.indexOf(this);
                            const prevIndex = currentIndex - 1;
                            if (prevIndex >= 0) {
                                visibleOptions[prevIndex].focus();
                            } else {
                                if (searchInput) {
                                    searchInput.focus();
                                } else {
                                    trigger.focus();
                                }
                            }
                        }
                    });
                });

                // Handle search input keyboard navigation
                if (searchInput) {
                    searchInput.addEventListener('keydown', function(e) {
                        if (e.key === 'ArrowDown') {
                            e.preventDefault();
                            const visibleOptions = Array.from(options).filter(opt => !opt.classList.contains('hidden-by-search'));
                            if (visibleOptions.length > 0) {
                                currentFocus = 0;
                                visibleOptions[0].focus();
                            }
                        }

                        if (e.key === 'Escape') {
                            e.preventDefault();
                            select.classList.remove('open');
                            trigger.focus();
                        }
                    });
                }
            });

            // Initial load: if country is already selected, load its cities
            const initialCountryId = document.getElementById('country_id').value;
            if (initialCountryId) {
                fetchCitiesForCountry(initialCountryId);
            }
        }
    });
</script>
@endsection
