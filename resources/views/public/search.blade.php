<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.search_page_title') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon-180x180.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <link rel="stylesheet" href="{{ asset('css/public/search.css') }}">

    <style>
        /* ===== التصميم الأساسي الموجود ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        html {
            overflow-x: hidden;
            scroll-behavior: smooth;
            width: 100%;
        }

        body {
            background-color: #f9fafb;
            overflow-x: hidden;
            width: 100%;
            max-width: 100vw;
            margin: 0;
            padding: 0;
            position: relative;
        }

        .hero-mini {
            position: relative;
            width: 100%;
            height: 280px;
            overflow: hidden;
        }
        .hero-mini-video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 0;
            border-bottom-left-radius: 50% 30%;
            border-bottom-right-radius: 50% 30%;
        }
        .hero-mini-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 11, 24, 0.5);
            z-index: 1;
            border-bottom-left-radius: 50% 30%;
            border-bottom-right-radius: 50% 30%;
        }

        .search-page {
            font-family: 'Poppins', sans-serif;
            color: #1a1a2e;
            max-width: 1320px;
            margin: 0 auto;
            padding: 2rem 1.5rem;
            position: relative;
            z-index: 2;
        }

        .search-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: #1a1a2e;
        }

        .search-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
            padding: 1.8rem 2rem;
            margin-bottom: 2rem;
            border: 1px solid #f0f2f5;
        }

        .search-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.2rem;
            margin-bottom: 1.2rem;
        }

        .search-row-2col {
            grid-template-columns: 1fr 1fr;
        }

        .form-group label {
            display: block;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
            margin-bottom: 0.3rem;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.6rem 1rem;
            border: 1.5px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.9rem;
            background: #ffffff;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
            font-family: inherit;
            color: #1f2937;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #2b73d2;
            box-shadow: 0 0 0 3px rgba(43, 115, 210, 0.1);
        }

        .form-group select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%236b7280' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 12px;
            padding-right: 2.5rem;
        }

        .radius-input-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .radius-input-group input {
            flex: 1;
        }

        .radius-input-group span {
            font-size: 0.85rem;
            color: #6b7280;
            white-space: nowrap;
        }

        .special-keys {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.2rem;
        }

        .special-key-btn {
            padding: 0.3rem 1rem;
            background: #f3f4f6;
            border: 1px solid #e5e7eb;
            border-radius: 30px;
            font-size: 0.8rem;
            color: #4b5563;
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
            font-family: inherit;
        }

        .special-key-btn:hover {
            background: #e5e7eb;
            color: #1f2937;
        }

        .search-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 0.8rem;
            margin-top: 0.5rem;
        }

        .btn-primary {
            background: #2b73d2;
            color: #ffffff;
            border: none;
            padding: 0.6rem 2.2rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: background 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 12px rgba(43, 115, 210, 0.25);
            font-family: inherit;
        }

        .btn-primary:hover {
            background: #1e60be;
            box-shadow: 0 6px 18px rgba(43, 115, 210, 0.35);
        }

        .btn-secondary {
            background: #f3f4f6;
            color: #4b5563;
            border: 1px solid #e5e7eb;
            padding: 0.6rem 1.8rem;
            border-radius: 12px;
            font-weight: 500;
            font-size: 0.95rem;
            cursor: pointer;
            transition: background 0.2s;
            text-decoration: none;
            font-family: inherit;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
        }

        .content-layout {
            display: grid;
            grid-template-columns: 240px 1fr;
            gap: 2rem;
            margin-top: 0.5rem;
        }

        .sidebar-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
            padding: 1.5rem 1.2rem;
            border: 1px solid #f0f2f5;
            position: sticky;
            top: 2rem;
        }

        .sidebar-title {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6b7280;
            margin-bottom: 1.2rem;
        }

        .category-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0.7rem;
            border-radius: 10px;
            color: #4b5563;
            text-decoration: none;
            transition: background 0.15s;
            font-size: 0.9rem;
        }

        .category-item:hover {
            background: #f9fafb;
        }

        .category-item.active {
            background: #eff6ff;
            color: #2b73d2;
            font-weight: 600;
        }

        .category-item .count {
            font-size: 0.75rem;
            color: #9ca3af;
        }

        .category-item.active .count {
            color: #2b73d2;
        }

        .sub-category-item {
            padding-right: 1.5rem;
            font-size: 0.8rem;
        }

        .results-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .results-title {
            font-size: 1.6rem;
            font-weight: 700;
            color: #1a1a2e;
        }

        .results-meta {
            font-size: 0.85rem;
            color: #6b7280;
        }

        .map-container {
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
            margin-bottom: 2rem;
            height: 350px;
            background: #f9fafb;
        }

        .empty-state {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
            padding: 3rem 2rem;
            text-align: center;
            border: 1px solid #f0f2f5;
        }

        .empty-state .icon {
            font-size: 4rem;
            margin-bottom: 1rem;
        }

        .empty-state h3 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 0.5rem;
        }

        .empty-state p {
            color: #6b7280;
            font-size: 1rem;
        }

        .empty-state .sub {
            color: #9ca3af;
            font-size: 0.9rem;
            margin-top: 0.3rem;
        }

        .listings-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .pagination-wrapper {
            margin-top: 2rem;
        }

        .listing-card {
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid #f0f2f5;
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            flex-direction: column;
        }

        .card-image-wrapper {
            position: relative;
            overflow: hidden;
            aspect-ratio: 4 / 3;
            background: #e5e7eb;
        }

        .card-image-link {
            display: block;
            width: 100%;
            height: 100%;
        }

        .card-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .listing-card:hover .card-image {
            transform: scale(1.05);
        }

        .card-image-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f3f4f6;
            color: #9ca3af;
            font-size: 1.2rem;
        }

        .card-badge {
            position: absolute;
            padding: 0.35rem 0.9rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            color: #fff;
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            gap: 0.3rem;
            z-index: 2;
        }

        .badge-left {
            top: 12px;
            right: 12px;
            background: #001c3d75;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .badge-right {
            top: 12px;
            left: 12px;
            background: rgb(16 185 129 / 61%);
        }

        .card-body {
            padding: 1.2rem 1.2rem 1rem;
            display: flex;
            flex-direction: column;
            flex: 1;
        }

        .card-title-link {
            text-decoration: none;
            color: inherit;
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 0.6rem;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            transition: color 0.2s;
        }

        .card-title:hover {
            color: #2b73d2;
        }

        .card-info {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
            margin-bottom: 0.8rem;
            font-size: 0.85rem;
            color: #4b5563;
        }

        .card-info-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card-info-item i {
            width: 1.1rem;
            text-align: center;
            color: #2b73d2;
            font-size: 0.9rem;
        }

        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: auto;
            padding-top: 0.8rem;
            border-top: 1px solid #f0f2f5;
        }

        .card-actions {
            display: flex;
            gap: 0.6rem;
        }

        .card-action-btn {
            background: transparent;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1rem;
            color: #6b7280;
            transition: all 0.2s;
            padding: 0;
        }

        .card-action-btn:hover {
            background: #f3f4f6;
            color: #1f2937;
        }

        .favorite-btn.active {
            color: #ef4444;
            border-color: #fecaca;
            background: #fef2f2;
        }

        .favorite-form-inline {
            display: inline-block;
            margin: 0;
        }

        .card-rating {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            background: #fffbeb;
            color: #b45309;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .card-rating i {
            color: #f59e0b;
        }

        @keyframes favColorPulse {
            0%   { background-color: rgba(239, 68, 68, 0.15); border-color: #fecaca; transform: scale(1); }
            40%  { background-color: rgba(239, 68, 68, 0.3); border-color: #ef4444; transform: scale(1.2); }
            100% { background-color: transparent; border-color: #e5e7eb; transform: scale(1); }
        }
        .favorite-btn.fav-animate {
            animation: favColorPulse 0.5s ease forwards;
        }

        .language-switcher {
            position: fixed;
            bottom: 35px;
            left: 45px;
            z-index: 9999;
            background: #ffffff;
            padding: 10px 18px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .language-switcher:hover {
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.18);
            transform: translateY(-2px);
        }
        .language-switcher .flag-icon {
            width: 24px;
            height: 16px;
            border-radius: 2px;
            flex-shrink: 0;
        }
        .language-switcher span {
            font-size: 15px;
            font-weight: 700;
            color: #222222;
        }
        .language-switcher i {
            font-size: 11px;
            color: #555555;
            transition: transform 0.3s ease;
        }
        .language-dropdown {
            position: fixed;
            bottom: 90px;
            left: 45px;
            z-index: 9998;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
            padding: 8px 0;
            min-width: 180px;
            display: none;
        }
        .language-dropdown.show {
            display: block;
            animation: slideUp 0.3s ease;
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .language-dropdown .lang-option {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            cursor: pointer;
            transition: background-color 0.2s ease;
            text-decoration: none;
            color: #333333;
        }
        .language-dropdown .lang-option:hover {
            background-color: #f5f7fa;
        }
        .language-dropdown .lang-option.active {
            background-color: #e8f0fe;
            font-weight: 600;
        }
        .language-dropdown .lang-option .flag-icon {
            width: 24px;
            height: 16px;
            border-radius: 2px;
            flex-shrink: 0;
        }
        .language-dropdown .lang-option .lang-name {
            font-size: 14px;
            flex: 1;
        }
        .language-dropdown .lang-option .check-icon {
            color: #2b73d2;
            font-size: 14px;
            display: none;
        }
        .language-dropdown .lang-option.active .check-icon {
            display: inline-block;
        }

        .footer {
            background: #0b1a2e;
            color: #cbd5e1;
            padding: 50px 20px 30px;
            margin-top: 60px;
        }
        .footer-container {
            max-width: 1320px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
        }
        .footer h4 {
            color: #fff;
            margin-bottom: 15px;
            font-size: 18px;
        }
        .footer ul {
            list-style: none;
        }
        .footer ul li {
            margin-bottom: 8px;
        }
        .footer ul li a {
            color: #cbd5e1;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.2s;
        }
        .footer ul li a:hover {
            color: #fff;
        }
        .footer-bottom {
            text-align: center;
            border-top: 1px solid #1e2f44;
            padding-top: 25px;
            margin-top: 30px;
            font-size: 14px;
            color: #8a9bb5;
        }

        /* ===== التصميم الجديد لقائمة المميزات ===== */
        .feature-dropdown-container {
            position: relative;
            width: 100%;
        }

        .feature-dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            width: 100%;
            padding: 0.6rem 1rem;
            background: #ffffff;
            border: 1.5px solid #e5e7eb;
            border-radius: 12px;
            font-size: 0.9rem;
            color: #1f2937;
            cursor: pointer;
            transition: border-color 0.2s, box-shadow 0.2s;
            font-family: inherit;
            justify-content: space-between;
        }

        .feature-dropdown-toggle:hover {
            border-color: #d1d5db;
        }

        .feature-dropdown-toggle:focus {
            border-color: #2b73d2;
            box-shadow: 0 0 0 3px rgba(43, 115, 210, 0.1);
        }

        .feature-dropdown-toggle i {
            transition: transform 0.3s ease;
        }

        .feature-dropdown-panel {
            display: none;
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            width: 100%;
            min-width: 320px;
            max-height: 340px;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
            z-index: 120000;
            padding: 0.8rem 0;
            overflow: hidden;
            flex-direction: column;
        }

        .feature-dropdown-panel.show {
            display: flex;
        }

        .feature-search {
            padding: 0 0.8rem 0.6rem;
            border-bottom: 1px solid #f3f4f6;
        }

        .feature-search-input {
            width: 100%;
            padding: 0.5rem 0.8rem;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            font-size: 0.85rem;
            outline: none;
            transition: border-color 0.2s;
            background: #f9fafb;
        }

        .feature-search-input:focus {
            border-color: #2b73d2;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(43, 115, 210, 0.08);
        }

        .feature-list {
            flex: 1;
            overflow-y: auto;
            padding: 0.4rem 0.8rem;
            max-height: 220px;
        }

        .feature-list::-webkit-scrollbar {
            width: 4px;
        }

        .feature-list::-webkit-scrollbar-track {
            background: #f3f4f6;
            border-radius: 4px;
        }

        .feature-list::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 4px;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.4rem 0.2rem;
            font-size: 0.85rem;
            color: #1f2937;
            cursor: pointer;
            border-radius: 8px;
            transition: background 0.15s;
        }

        .feature-item:hover {
            background: #f9fafb;
        }

        .feature-item input[type="checkbox"] {
            width: 1rem;
            height: 1rem;
            accent-color: #2b73d2;
            flex-shrink: 0;
            cursor: pointer;
        }

        .feature-item.hidden {
            display: none;
        }

        @media (max-width: 640px) {
            .feature-dropdown-panel {
                min-width: 100%;
                left: 0;
            }
        }

        /* ===== التجاوب ===== */
        @media (max-width: 1024px) {
            .language-switcher { bottom: 25px; left: 25px; }
            .language-dropdown { bottom: 80px; left: 25px; }
        }
        @media (max-width: 992px) {
            .content-layout {
                grid-template-columns: 1fr;
            }
            .sidebar-card {
                position: static;
            }
            .search-row {
                grid-template-columns: 1fr 1fr;
            }
            .hero-mini {
                height: 200px;
            }
        }
        @media (max-width: 768px) {
            .language-switcher {
                position: fixed;
                bottom: 20px;
                left: 20px;
                margin: 0;
                display: inline-flex;
                width: fit-content;
            }
            .language-dropdown {
                bottom: 75px;
                left: 20px;
                min-width: 160px;
            }
        }
        @media (max-width: 640px) {
            .search-row {
                grid-template-columns: 1fr;
            }
            .search-row-2col {
                grid-template-columns: 1fr;
            }
            .search-card {
                padding: 1.2rem;
            }
            .search-actions {
                flex-direction: column;
            }
            .btn-primary,
            .btn-secondary {
                width: 100%;
                text-align: center;
            }
            .results-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
        }

        /* ===== ترقيم الصفحات (Pagination) ===== */
        .pagination-wrapper {
            margin-top: 2rem;
            display: flex;
            justify-content: center;
            width: 100%;
        }

        .pagination-nav {
            display: inline-flex;
            justify-content: center;
            background: #ffffff;
            border: 1px solid #f0f2f5;
            border-radius: 16px;
            padding: 0.5rem;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
        }

        .pagination-list {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .page-item {
            display: inline-flex;
        }

        .page-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 40px;
            height: 40px;
            padding: 0.5rem;
            border-radius: 10px;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            color: #4b5563;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
            font-family: inherit;
        }

        .page-link i {
            font-size: 0.85rem;
        }

        .page-link:hover {
            background: #f3f4f6;
            border-color: #d1d5db;
            color: #1f2937;
            transform: translateY(-1px);
        }

        .page-item.active .page-link {
            background: #2b73d2;
            border-color: #2b73d2;
            color: #ffffff;
            box-shadow: 0 6px 18px rgba(43, 115, 210, 0.35);
            transform: translateY(-1px);
        }

        .page-item.disabled .page-link {
            opacity: 0.4;
            cursor: not-allowed;
            background: #f9fafb;
            pointer-events: none;
            transform: none;
        }

        .page-item.disabled .page-link:hover {
            background: #f9fafb;
            border-color: #e5e7eb;
            color: #4b5563;
            transform: none;
        }

        .page-dots {
            min-width: 40px;
            text-align: center;
            background: transparent;
            border: none;
            color: #9ca3af;
            font-weight: 700;
            letter-spacing: 1px;
            pointer-events: none;
        }

        @media (max-width: 640px) {
            .pagination-nav {
                padding: 0.35rem;
                border-radius: 12px;
            }

            .pagination-list {
                gap: 0.2rem;
            }

            .page-link {
                min-width: 34px;
                height: 34px;
                padding: 0.35rem;
                font-size: 0.8rem;
                border-radius: 8px;
            }

            .page-dots {
                min-width: 30px;
            }
        }
    </style>

</head>
<body>

<header class="hero-mini">
    <video class="hero-mini-video" autoplay loop muted playsinline>
        <source src="{{ asset('videos/turi.mp4') }}" type="video/mp4">
        {{ __('messages.browser_video_not_supported') }}
    </video>
    <div class="hero-mini-overlay"></div>

    @include('partials.header')
</header>

<div class="search-page">

    <h1 class="search-title">{{ __('messages.search_advanced_title') }}</h1>

    <div class="search-card">
        <form action="{{ route('search') }}" method="GET">

            <div class="search-row">
                <div class="form-group">
                    <label>{{ __('messages.search_what_label') }}</label>
                    <input type="text" name="q" value="{{ $query ?? '' }}" placeholder="{{ __('messages.search_what_placeholder') }}">
                </div>
                <div class="form-group">
                    <label>{{ __('messages.search_category_label') }}</label>
                    <select name="category">
                        <option value="">{{ __('messages.search_all_categories') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ ($categoryId ?? '') == $category->id ? 'selected' : '' }}>
                                {{ $category->translate('name') }}
                            </option>
                            @if($category->children->count())
                                @foreach($category->children as $child)
                                    <option value="{{ $child->id }}" {{ ($categoryId ?? '') == $child->id ? 'selected' : '' }}>
                                        -- {{ $child->translate('name') }}
                                    </option>
                                @endforeach
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>{{ __('messages.search_location_label') }}</label>
                    <select name="city">
                        <option value="">{{ __('messages.search_all_cities') }}</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}" {{ ($cityId ?? '') == $city->id ? 'selected' : '' }}>
                                {{ $city->translate('name') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>{{ __('messages.search_radius_label') }}</label>
                    <div class="radius-input-group">
                        <input type="number" name="distance" value="{{ $distance ?? 10 }}" min="1" max="500">
                        <span>{{ __('messages.search_radius_unit') }}</span>
                    </div>
                </div>
            </div>

            <div class="search-row">
                <div class="form-group">
                    <label>{{ __('messages.search_price_from_label') }}</label>
                    <input type="number" name="min_price" value="{{ $minPrice ?? '' }}" placeholder="0">
                </div>
                <div class="form-group">
                    <label>{{ __('messages.search_price_to_label') }}</label>
                    <input type="number" name="max_price" value="{{ $maxPrice ?? '' }}" placeholder="1000">
                </div>
                <div class="form-group">
                    <label>{{ __('messages.search_min_rating_label') }}</label>
                    <select name="min_rating">
                        <option value="0" {{ ($minRating ?? 0) == 0 ? 'selected' : '' }}>{{ __('messages.search_rating_all') }}</option>
                        <option value="3" {{ ($minRating ?? 0) == 3 ? 'selected' : '' }}>★ 3+</option>
                        <option value="4" {{ ($minRating ?? 0) == 4 ? 'selected' : '' }}>★ 4+</option>
                        <option value="4.5" {{ ($minRating ?? 0) == 4.5 ? 'selected' : '' }}>★ 4.5+</option>
                        <option value="5" {{ ($minRating ?? 0) == 5 ? 'selected' : '' }}>★ 5</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>{{ __('messages.search_sort_label') }}</label>
                    <select name="sort">
                        <option value="relevance" {{ ($sort ?? 'relevance') == 'relevance' ? 'selected' : '' }}>{{ __('messages.search_sort_relevance') }}</option>
                        <option value="rating" {{ ($sort ?? '') == 'rating' ? 'selected' : '' }}>{{ __('messages.search_sort_rating') }}</option>
                        <option value="newest" {{ ($sort ?? '') == 'newest' ? 'selected' : '' }}>{{ __('messages.search_sort_newest') }}</option>
                        <option value="price_low" {{ ($sort ?? '') == 'price_low' ? 'selected' : '' }}>{{ __('messages.search_sort_price_low') }}</option>
                        <option value="price_high" {{ ($sort ?? '') == 'price_high' ? 'selected' : '' }}>{{ __('messages.search_sort_price_high') }}</option>
                    </select>
                </div>
            </div>

            <div class="search-row search-row-2col">
                <!-- ===== الجزء المعدل: المميزات ===== -->
                <div class="form-group">
                    <label>{{ __('messages.search_features_label') }}</label>
                    <div class="feature-dropdown-container">
                        <button type="button" class="feature-dropdown-toggle" id="featureToggle">
                            <span id="featureCount">0</span>
                            <span>{{ app()->getLocale() == 'ar' ? 'مميزات' : 'Features' }}</span>
                            <i class="fas fa-chevron-down" id="featureArrow"></i>
                        </button>
                        <div class="feature-dropdown-panel" id="featurePanel">
                            <div class="feature-search">
                                <input type="text" id="featureSearch" placeholder="{{ app()->getLocale() == 'ar' ? 'بحث في المميزات' : 'Search features' }}" class="feature-search-input">
                            </div>
                            <div class="feature-list" id="featureList">
                                @foreach($allFeatures as $feature)
                                    <label class="feature-item">
                                        <input type="checkbox" name="features[]" value="{{ $feature->id }}"
                                               {{ in_array($feature->id, $features ?? []) ? 'checked' : '' }}
                                               class="feature-checkbox">
                                        {{ $feature->translate('name') }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ===== نهاية التعديل ===== -->
                <div class="form-group">
                    <label>{{ __('messages.search_near_label') }}</label>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.8rem;">
                        <input type="text" name="near" value="{{ $near ?? '' }}" placeholder="{{ __('messages.search_near_placeholder') }}">
                        <input type="number" name="distance" value="{{ $distance ?? 10 }}" placeholder="{{ __('messages.search_distance_placeholder') }}">
                    </div>
                </div>
            </div>

            <div style="margin: 0.5rem 0 0.8rem;">
                <label style="display: block; font-size: 0.7rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #6b7280; margin-bottom: 0.3rem;">{{ __('messages.search_special_label') }}</label>
                <div class="special-keys">
                    @php
                        $specialKeys = ['Icebox Cake', 'Fryer Desserts', 'Beef Quesadillas', 'Mexican Desserts', 'Chicken Francese', 'Pickled Peppers'];
                    @endphp
                    @foreach($specialKeys as $key)
                        <button type="button" class="special-key-btn" onclick="document.querySelector('input[name=q]').value='{{ $key }}'; this.closest('form').submit();">
                            {{ $key }}
                        </button>
                    @endforeach
                </div>
            </div>

            <div class="search-actions">
                <button type="submit" class="btn-primary">{{ __('messages.search_button') }}</button>
                <a href="{{ route('search') }}" class="btn-secondary">{{ __('messages.search_clear_button') }}</a>
            </div>

        </form>
    </div>

    <div class="content-layout">

        <aside>
            <div class="sidebar-card">
                <div class="sidebar-title">{{ __('messages.search_main_categories') }}</div>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    @foreach($categories as $category)
                        <li style="margin-bottom: 0.2rem;">
                            <a href="{{ route('search', array_merge(request()->query(), ['category' => $category->id])) }}"
                               class="category-item {{ ($categoryId ?? '') == $category->id ? 'active' : '' }}">
                                <span>{{ $category->translate('name') }}</span>
                                <span class="count">(0)</span>
                            </a>
                            @if($category->children->count())
                                <ul style="list-style: none; padding: 0; margin: 0.2rem 0 0.4rem 0.8rem;">
                                    @foreach($category->children as $child)
                                        <li>
                                            <a href="{{ route('search', array_merge(request()->query(), ['category' => $child->id])) }}"
                                               class="category-item sub-category-item {{ ($categoryId ?? '') == $child->id ? 'active' : '' }}">
                                                <span>— {{ $child->translate('name') }}</span>
                                                <span class="count">(0)</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </aside>

        <main>

            @if(isset($results) && $results->count() > 0)
                <div id="map-search" class="map-container"></div>
            @endif

            <div class="results-header">
                <h2 class="results-title">
                    @if(isset($results) && $results->count() > 0)
                        {{ $results->total() }} {{ __('messages.search_results_count', ['count' => $results->total()]) }}
                    @else
                        {{ __('messages.search_no_results') }}
                    @endif
                </h2>
                @if(isset($results) && $results->count() > 0)
                    <span class="results-meta">{{ $results->firstItem() ?? 0 }} - {{ $results->lastItem() ?? 0 }} {{ __('messages.search_of') }} {{ $results->total() }}</span>
                @endif
            </div>

            @if(isset($results) && $results->count() > 0)
                <div class="listings-grid">
                    @foreach($results as $listing)
                        <div class="listing-card">
                            <div class="card-image-wrapper">
                                <a href="{{ route('listing.show', $listing->slug) }}" class="card-image-link">
                                    @php
                                        $imageUrl = $listing->getSignedImageUrl('medium', 60);
                                    @endphp
                                    @if($imageUrl)
                                        <img src="{{ $imageUrl }}"
                                             alt="{{ $listing->translate('title') }}"
                                             class="card-image"
                                             loading="lazy">
                                    @else
                                        <div class="card-image-placeholder">
                                            <span>{{ __('messages.search_no_image') }}</span>
                                        </div>
                                    @endif
                                </a>

                                <div class="card-badge badge-left">
                                    <i class="fas {{ $listing->type?->icon ?? 'fa-tag' }}"></i>
                                    <span>{{ $listing->type?->translate('name') ?? __('messages.search_activity_default') }}</span>
                                </div>

                                @php
                                    $availabilityState = $listing->publicAvailabilityState();
                                @endphp
                                @if($availabilityState)
                                    <div class="card-badge badge-right {{ $availabilityState === 'open' ? 'bg-green-500' : 'bg-red-500' }}">
                                        {{ $availabilityState === 'open' ? __('messages.search_open_now') : __('messages.search_closed_now') }}
                                    </div>
                                @endif
                            </div>

                            <div class="card-body">
                                <a href="{{ route('listing.show', $listing->slug) }}" class="card-title-link">
                                    <h3 class="card-title">{{ $listing->translate('title') }}</h3>
                                </a>

                                <div class="card-info">
                                    <div class="card-info-item">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>{{ $listing->translate('short_description') ?? ($listing->city?->translate('name') . '، ' . $listing->country?->translate('name')) }}</span>
                                    </div>

                                    @php
                                        $categorySlug = $listing->category?->slug ?? '';
                                        $hidePhone = in_array($categorySlug, ['mountains', 'mountain', 'historical', 'history', 'nature', 'landmarks', 'natural']);
                                    @endphp

                                    @unless($hidePhone)
                                        <div class="card-info-item">
                                            <i class="fas fa-phone"></i>
                                            <span dir="ltr">{{ $listing->business?->phone ?? '+212 00 00 00 00' }}</span>
                                        </div>
                                    @endunless
                                </div>

                                <div class="card-footer">
                                    <div class="card-actions">
                                        <button class="card-action-btn" aria-label="{{ __('messages.search_gallery_label') }}" title="{{ __('messages.search_gallery_label') }}">
                                            <i class="far fa-image"></i>
                                        </button>

                                        @auth
                                            @php
                                                $isFavorited = auth()->user()->favorites()->where('listing_id', $listing->id)->exists();
                                            @endphp
                                            <button type="button"
                                                    class="card-action-btn favorite-btn {{ $isFavorited ? 'active' : '' }}"
                                                    data-url="{{ route('favorite.toggle', $listing) }}"
                                                    title="{{ $isFavorited ? __('messages.search_remove_favorite') : __('messages.search_add_favorite') }}">
                                                <i class="{{ $isFavorited ? 'fas' : 'far' }} fa-heart"></i>
                                            </button>
                                        @endauth
                                    </div>

                                    <div class="card-rating">
                                        <i class="fas fa-star"></i>
                                        <span>{{ number_format($listing->average_rating, 1) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="pagination-wrapper">
                    @php
                        $results->appends(request()->query());
                        $currentPage = $results->currentPage();
                        $lastPage = $results->lastPage();
                        $delta = 1;
                        $start = max(1, $currentPage - $delta);
                        $end = min($lastPage, $currentPage + $delta);
                        $pages = [];
                        if ($start > 1) {
                            $pages[] = 1;
                            if ($start > 2) $pages[] = '...';
                        }
                        for ($i = $start; $i <= $end; $i++) {
                            $pages[] = $i;
                        }
                        if ($end < $lastPage) {
                            if ($end < $lastPage - 1) $pages[] = '...';
                            $pages[] = $lastPage;
                        }
                    @endphp

                    <nav class="pagination-nav" aria-label="Page navigation" dir="ltr">
                        <ul class="pagination-list">
                            @if ($results->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link"><i class="fas fa-chevron-left"></i></span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $results->previousPageUrl() }}" rel="prev">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                            @endif

                            @foreach ($pages as $page)
                                @if (is_string($page))
                                    <li class="page-item disabled">
                                        <span class="page-link page-dots">{{ $page }}</span>
                                    </li>
                                @elseif ($page == $currentPage)
                                    <li class="page-item active" aria-current="page">
                                        <span class="page-link">{{ $page }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $results->url($page) }}">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach

                            @if ($results->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $results->nextPageUrl() }}" rel="next">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link"><i class="fas fa-chevron-right"></i></span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
            @else
                <div class="empty-state">
                    <div class="icon">🔍</div>
                    <h3>{{ __('messages.search_no_results_title') }}</h3>
                    <p>{{ __('messages.search_no_results_message') }}</p>
                    <p class="sub">{{ __('messages.search_try_changing') }}</p>
                </div>
            @endif

        </main>
    </div>

</div>

@include('public.sections.footer')

<script>
    function toggleLanguageMenu() {
        const dropdown = document.getElementById('languageDropdown');
        const arrow = document.getElementById('langArrow');
        dropdown.classList.toggle('show');
        if (dropdown.classList.contains('show')) {
            arrow.style.transform = 'rotate(180deg)';
        } else {
            arrow.style.transform = 'rotate(0deg)';
        }
    }

    document.addEventListener('click', function(event) {
        const switcher = document.getElementById('languageSwitcher');
        const dropdown = document.getElementById('languageDropdown');
        if (!switcher.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.classList.remove('show');
            document.getElementById('langArrow').style.transform = 'rotate(0deg)';
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const currentLocale = '{{ app()->getLocale() }}';
        const options = document.querySelectorAll('.lang-option');
        options.forEach(option => {
            option.classList.remove('active');
            if (option.dataset.lang === currentLocale) {
                option.classList.add('active');
                const flagSvg = option.querySelector('.flag-icon').outerHTML;
                const langCode = option.dataset.lang.toUpperCase();
                document.getElementById('currentFlag').outerHTML = flagSvg;
                document.getElementById('currentLang').textContent = langCode;
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.favorite-btn').forEach(btn => {
            btn.addEventListener('click', async function(e) {
                e.preventDefault();
                const url = this.dataset.url;
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                this.style.opacity = '0.7';
                this.disabled = true;

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    });

                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    const data = await response.json();

                    if (data.hasOwnProperty('favorited')) {
                        const icon = this.querySelector('i');
                        this.classList.add('fav-animate');

                        if (data.favorited) {
                            this.classList.add('active');
                            icon.className = 'fas fa-heart';
                            this.setAttribute('title', '{{ __('messages.search_remove_favorite') }}');
                        } else {
                            this.classList.remove('active');
                            icon.className = 'far fa-heart';
                            this.setAttribute('title', '{{ __('messages.search_add_favorite') }}');
                        }

                        this.addEventListener('animationend', function() {
                            this.classList.remove('fav-animate');
                        }, { once: true });
                    } else {
                        location.reload();
                    }
                } catch (error) {
                    console.error('No se pudo cambiar el favorito:', error);
                    this.style.opacity = '1';
                    this.disabled = false;
                    alert('{{ __('messages.search_favorite_error') }}');
                } finally {
                    this.style.opacity = '1';
                    this.disabled = false;
                }
            });
        });
    });

    // ===== Feature Dropdown Logic =====
    (function() {
        const toggle = document.getElementById('featureToggle');
        const panel = document.getElementById('featurePanel');
        const arrow = document.getElementById('featureArrow');
        const searchInput = document.getElementById('featureSearch');
        const featureItems = document.querySelectorAll('.feature-item');
        const checkboxes = document.querySelectorAll('.feature-checkbox');
        const countSpan = document.getElementById('featureCount');

        function updateCount() {
            const checked = document.querySelectorAll('.feature-checkbox:checked').length;
            countSpan.textContent = checked;
        }

        toggle.addEventListener('click', function(e) {
            e.stopPropagation();
            const isOpen = panel.classList.toggle('show');
            arrow.style.transform = isOpen ? 'rotate(180deg)' : 'rotate(0deg)';
        });

        document.addEventListener('click', function(e) {
            const container = document.querySelector('.feature-dropdown-container');
            if (container && !container.contains(e.target)) {
                panel.classList.remove('show');
                arrow.style.transform = 'rotate(0deg)';
            }
        });

        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            featureItems.forEach(item => {
                const text = item.textContent.toLowerCase().trim();
                if (text.includes(query)) {
                    item.classList.remove('hidden');
                } else {
                    item.classList.add('hidden');
                }
            });
        });

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateCount);
        });

        updateCount();

        panel.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    })();
</script>

@if(isset($results) && $results->count() > 0)
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let centerLat = 40.4168;
            let centerLng = -3.7038;

            @foreach($results as $listing)
                @if($listing->latitude && $listing->longitude)
                    centerLat = {{ $listing->latitude }};
                    centerLng = {{ $listing->longitude }};
                @endif
            @endforeach

            const map = L.map('map-search').setView([centerLat, centerLng], 6);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(map);

            @foreach($results as $listing)
                @if($listing->latitude && $listing->longitude)
                    L.marker([{{ $listing->latitude }}, {{ $listing->longitude }}])
                        .addTo(map)
                        .bindPopup(`
                            <a href="{{ route('listing.show', $listing->slug) }}" style="font-weight: 600;">
                                {{ $listing->translate('title') }}
                            </a>
                            <br>
                            <span style="font-size: 0.85rem; color: #6b7280;">{{ $listing->city?->translate('name') }}</span>
                        `);
                @endif
            @endforeach
        });
    </script>
@endif

</body>
</html>