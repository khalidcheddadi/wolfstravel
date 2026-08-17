{{-- resources/views/profile/edit.blade.php --}}
@extends('layouts.business-owner')

@section('content')

<header class="top-bar anim-fade-up">
    <div class="page-title">{{ __('messages.profile') }}</div>
    <div class="top-actions">
        <a href="{{ route('business-owner.dashboard') }}" class="btn-ghost" style="padding:0.55rem 1.5rem; font-size:0.85rem; border-color:var(--border); color:var(--text-secondary);">
            <i class="fa-solid fa-arrow-right"></i> {{ __('messages.back_to_dashboard') }}
        </a>
    </div>
</header>

@if(session('status') === 'profile-updated')
    <div class="alert-box alert-success anim-fade-up">
        <span class="alert-icon"><i class="fa-solid fa-circle-check"></i></span>
        <div class="alert-content">
            <span>{{ __('messages.profile_updated_success') }}</span>
            <button onclick="this.parentElement.parentElement.remove()" style="background:none;border:none;cursor:pointer;color:inherit;font-size:1.1rem;">&times;</button>
        </div>
    </div>
@endif

@if(session('status') === 'password-updated')
    <div class="alert-box alert-success anim-fade-up">
        <span class="alert-icon"><i class="fa-solid fa-shield-halved"></i></span>
        <div class="alert-content">
            <span>{{ __('messages.password_updated_success') }}</span>
            <button onclick="this.parentElement.parentElement.remove()" style="background:none;border:none;cursor:pointer;color:inherit;font-size:1.1rem;">&times;</button>
        </div>
    </div>
@endif

@if(session('status') === 'logged-out-all-devices')
    <div class="alert-box alert-success anim-fade-up">
        <span class="alert-icon"><i class="fa-solid fa-globe"></i></span>
        <div class="alert-content">
            <span>{{ __('messages.logged_out_all_devices_success') }}</span>
            <button onclick="this.parentElement.parentElement.remove()" style="background:none;border:none;cursor:pointer;color:inherit;font-size:1.1rem;">&times;</button>
        </div>
    </div>
@endif

@if($errors->any())
    <div class="alert-box alert-error anim-fade-up">
        <span class="alert-icon"><i class="fa-solid fa-circle-exclamation"></i></span>
        <div class="alert-content">
            <span>{{ __('messages.please_review_errors') }}</span>
            <button onclick="this.parentElement.parentElement.remove()" style="background:none;border:none;cursor:pointer;color:inherit;font-size:1.1rem;">&times;</button>
        </div>
    </div>
@endif

<div style="display: flex; flex-direction: column; gap: 1.5rem;">

    <div class="panel anim-fade-up anim-delay-1" style="padding: 2rem;">
        <div class="panel-header">
            <h3 class="panel-title">
                <i class="fa-solid fa-user-pen" style="color:var(--primary); margin-left: 8px;"></i>
                {{ __('messages.account_info') }}
            </h3>
            <span style="font-size: 0.8rem; color: var(--text-muted);">{{ __('messages.update_your_personal_info') }}</span>
        </div>
        <div style="max-width: 560px;">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>

    <div class="panel anim-fade-up anim-delay-2" style="padding: 2rem;">
        <div class="panel-header">
            <h3 class="panel-title">
                <i class="fa-solid fa-lock" style="color:var(--primary); margin-left: 8px;"></i>
                {{ __('messages.password') }}
            </h3>
            <span style="font-size: 0.8rem; color: var(--text-muted);">{{ __('messages.ensure_strong_password') }}</span>
        </div>
        <div style="max-width: 560px;">
            @include('profile.partials.update-password-form')
        </div>
    </div>

    <div class="panel anim-fade-up anim-delay-3" style="padding: 2rem;">
        <div class="panel-header">
            <h3 class="panel-title">
                <i class="fa-solid fa-globe" style="color:var(--primary); margin-left: 8px;"></i>
                {{ __('messages.active_sessions') }}
            </h3>
            <span style="font-size: 0.8rem; color: var(--text-muted);">{{ __('messages.manage_connected_devices') }}</span>
        </div>
        <div style="max-width: 560px;">
            <p style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 1.5rem; line-height: 1.7;">
                {{ __('messages.logout_all_devices_description') }}
            </p>

            <form method="POST" action="{{ route('profile.logout-all-devices') }}">
                @csrf
                <div style="margin-bottom: 1.2rem;">
                    <label for="logout_password" style="display: block; font-size: 0.85rem; font-weight: 650; color: var(--text-main); margin-bottom: 0.5rem;">
                        {{ __('messages.current_password_confirmation') }}
                    </label>
                    <input id="logout_password" name="password" type="password" required autocomplete="current-password"
                           style="width: 100%; padding: 0.7rem 1rem; border-radius: 8px; border: 1.5px solid var(--border); background: var(--bg-main); font-size: 0.9rem; color: var(--text-main); outline: none; transition: var(--transition);"
                           onfocus="this.style.borderColor='var(--primary)'"
                           onblur="this.style.borderColor='var(--border)'">
                    @if($errors->logout_all_devices->has('password'))
                        <p style="color: #dc2626; font-size: 0.8rem; margin-top: 0.4rem;">{{ $errors->logout_all_devices->first('password') }}</p>
                    @endif
                </div>

                <button type="submit" style="
                    background: #dc2626;
                    color: #fff;
                    padding: 0.7rem 1.8rem;
                    border-radius: 8px;
                    border: none;
                    font-weight: 650;
                    font-size: 0.88rem;
                    cursor: pointer;
                    transition: var(--transition);
                    display: inline-flex;
                    align-items: center;
                    gap: 0.5rem;
                " onmouseover="this.style.background='#b91c1c'"
                   onmouseout="this.style.background='#dc2626'">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    {{ __('messages.logout_all_other_devices') }}
                </button>
            </form>
        </div>
    </div>

    <div class="panel anim-fade-up anim-delay-4" style="padding: 2rem; border: 1.5px solid #fecaca !important;">
        <div class="panel-header">
            <h3 class="panel-title" style="color: #991b1b;">
                <i class="fa-solid fa-triangle-exclamation" style="color: #dc2626; margin-left: 8px;"></i>
                {{ __('messages.danger_zone') }}
            </h3>
            <span style="font-size: 0.8rem; color: #991b1b;">{{ __('messages.irreversible_action') }}</span>
        </div>
        <div style="max-width: 560px;">
            <p style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 1.5rem; line-height: 1.7;">
                {{ __('messages.delete_account_warning') }}
            </p>

            @include('profile.partials.delete-user-form')
        </div>
    </div>

</div>

<style>
    .panel {
        border: none !important;
        box-shadow: none !important;
    }
    .panel:last-of-type {
        border: 1.5px solid #fecaca !important;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"],
    input[type="number"],
    textarea,
    select {
        width: 100%;
        padding: 0.7rem 1rem;
        border-radius: 8px;
        border: 1.5px solid var(--border);
        background: var(--bg-main);
        font-size: 0.9rem;
        color: var(--text-main);
        outline: none;
        transition: var(--transition);
    }
    input:focus,
    textarea:focus,
    select:focus {
        border-color: var(--primary);
    }

    label {
        display: block;
        font-size: 0.85rem;
        font-weight: 650;
        color: var(--text-main);
        margin-bottom: 0.5rem;
        margin-top: 1rem;
    }

    button[type="submit"] {
        margin-top: 1.5rem;
    }

    .btn-save {
        background: var(--primary);
        color: #fff;
        padding: 0.7rem 1.8rem;
        border-radius: 8px;
        border: none;
        font-weight: 650;
        font-size: 0.88rem;
        cursor: pointer;
        transition: var(--transition);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .btn-save:hover {
        background: var(--primary-hover);
    }

    .input-error {
        color: #dc2626;
        font-size: 0.8rem;
        margin-top: 0.4rem;
    }

    @media (max-width: 768px) {
        .panel {
            padding: 1.5rem 1rem !important;
        }
        .top-bar {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.8rem;
        }
    }
</style>

@endsection
