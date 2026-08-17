<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $listing->translate('title') }} - {{ __('messages.listing_details_title') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon-180x180.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <link rel="stylesheet" href="{{ asset('css/listings/show.css') }}">

    <style>
        .whatsapp-float {
            position: fixed;
            bottom: 30px;
            {{ app()->getLocale() == 'ar' ? 'left: 92%;' : 'right: 30px;' }}
            width: 60px;
            height: 60px;
            background-color: #25D366;
            color: #FFF;
            border-radius: 50%;
            text-align: center;
            font-size: 30px;
            box-shadow: 2px 2px 10px rgba(0,0,0,0.3);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            animation: pulse-whatsapp 2s infinite;
        }
        .whatsapp-float:hover {
            transform: scale(1.1);
            box-shadow: 2px 2px 15px rgba(37, 211, 102, 0.6);
            color: #FFF;
            text-decoration: none;
        }
        @keyframes pulse-whatsapp {
            0% {
                box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.5);
            }
            70% {
                box-shadow: 0 0 0 15px rgba(37, 211, 102, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(37, 211, 102, 0);
            }
        }
        @media (max-width: 768px) {
            .whatsapp-float {
                width: 50px;
                height: 50px;
                font-size: 25px;
                bottom: 20px;
                {{ app()->getLocale() == 'ar' ? 'left: 82%;' : 'right: 20px;' }}
            }
        }

        .contact-info-card {
            background: #fff;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            flex-wrap: wrap;
            gap: 1.5rem;
            align-items: center;
        }
        .contact-phone {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            font-size: 1.1rem;
            font-weight: 600;
            color: #001c3d;
        }
        .contact-phone i {
            color: #25D366;
            font-size: 1.3rem;
        }
        .contact-actions {
            display: flex;
            gap: 0.8rem;
            flex-wrap: wrap;
        }
        .btn-call, .btn-whatsapp {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.6rem 1.2rem;
            border-radius: 30px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s ease;
            font-size: 0.95rem;
        }
        .btn-call {
            background: #f0f4f8;
            color: #001c3d;
            border: 1px solid #ddd;
        }
        .btn-call:hover {
            background: #e0e7ef;
        }
        .btn-whatsapp {
            background: #25D366;
            color: white;
        }
        .btn-whatsapp:hover {
            background: #1da851;
            color: white;
        }
    </style>
</head>
<body>

    <header class="hero-mini" style="direction: {{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }};">
        <video class="hero-mini-video" autoplay loop muted playsinline>
            <source src="{{ asset('videos/turi.mp4') }}" type="video/mp4">
            {{ __('messages.browser_video_not_supported') }}
        </video>
        <div class="hero-mini-overlay"></div>
        @include('partials.header')
    </header>

    <div class="search-page">

        <h1 class="search-title">{{ __('messages.listing_details_title') }}</h1>

        <div class="main-layout">

            <aside class="filter-sidebar">
                <div class="filter-card">
                    <div class="filter-header">
                        <div class="filter-header-title">
                            <i class="fas fa-sliders-h"></i>
                            <span>{{ __('messages.filter_title') }}</span>
                        </div>
                        <a href="{{ route('search') }}" class="filter-reset">
                            <i class="fas fa-redo"></i> {{ __('messages.filter_reset') }}
                        </a>
                    </div>

                    <form action="{{ route('search') }}" method="GET">
                        <div class="filter-body">

                            <div class="filter-section">
                                <div class="filter-section-title">
                                    <i class="fas fa-search"></i>
                                    {{ __('messages.filter_basic_search') }}
                                </div>
                                <input type="text" name="q" value="{{ request('q') }}"
                                       class="filter-input"
                                       placeholder="{{ __('messages.filter_search_placeholder') }}">
                            </div>

                            <div class="filter-section">
                                <div class="filter-section-title">
                                    <i class="fas fa-folder"></i>
                                    {{ __('messages.filter_category') }}
                                </div>
                                <select name="category" class="filter-select">
                                    <option value="">{{ __('messages.filter_all_categories') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->translate('name') }}
                                        </option>
                                        @if($category->children->count())
                                            @foreach($category->children as $child)
                                                <option value="{{ $child->id }}" {{ request('category') == $child->id ? 'selected' : '' }}>
                                                    &nbsp;&nbsp;└ {{ $child->translate('name') }}
                                                </option>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="filter-section">
                                <div class="filter-section-title">
                                    <i class="fas fa-map-marker-alt"></i>
                                    {{ __('messages.filter_location') }}
                                </div>
                                <select name="city" class="filter-select" style="margin-bottom: 0.8rem;">
                                    <option value="">{{ __('messages.filter_all_cities') }}</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->id }}" {{ request('city') == $city->id ? 'selected' : '' }}>
                                            {{ $city->translate('name') }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="distance-group">
                                    <input type="number" name="distance" value="{{ request('distance', 10) }}"
                                           class="filter-input" min="1" max="500" placeholder="10">
                                    <span>{{ __('messages.filter_km') }}</span>
                                </div>
                            </div>

                            <div class="filter-section">
                                <div class="filter-section-title">
                                    <i class="fas fa-tag"></i>
                                    {{ __('messages.filter_price_range') }}
                                </div>
                                <div class="price-range-group">
                                    <input type="number" name="min_price" value="{{ request('min_price') }}"
                                           class="filter-input" placeholder="{{ __('messages.filter_from') }}">
                                    <span>-</span>
                                    <input type="number" name="max_price" value="{{ request('max_price') }}"
                                           class="filter-input" placeholder="{{ __('messages.filter_to') }}">
                                </div>
                            </div>

                            <div class="filter-section">
                                <div class="filter-section-title">
                                    <i class="fas fa-star"></i>
                                    {{ __('messages.filter_features') }}
                                </div>
                                <div class="features-grid">
                                    @foreach($allFeatures as $feature)
                                        <label class="feature-checkbox">
                                            <input type="checkbox" name="features[]" value="{{ $feature->id }}"
                                                   {{ in_array($feature->id, request('features', [])) ? 'checked' : '' }}>
                                            <span>{{ $feature->translate('name') }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="filter-section">
                                <div class="filter-section-title">
                                    <i class="fas fa-star"></i>
                                    {{ __('messages.filter_rating') }}
                                </div>
                                <div class="rating-options">
                                    @php $currentRating = request('min_rating', 0); @endphp
                                    <label class="rating-option">
                                        <input type="radio" name="min_rating" value="0" {{ $currentRating == 0 ? 'checked' : '' }}>
                                        <span>{{ __('messages.filter_rating_all') }}</span>
                                    </label>
                                    <label class="rating-option">
                                        <input type="radio" name="min_rating" value="3" {{ $currentRating == 3 ? 'checked' : '' }}>
                                        <span class="rating-stars">★★★</span>
                                        <span>{{ __('messages.filter_rating_or_more') }}</span>
                                    </label>
                                    <label class="rating-option">
                                        <input type="radio" name="min_rating" value="4" {{ $currentRating == 4 ? 'checked' : '' }}>
                                        <span class="rating-stars">★★★★</span>
                                        <span>{{ __('messages.filter_rating_or_more') }}</span>
                                    </label>
                                    <label class="rating-option">
                                        <input type="radio" name="min_rating" value="4.5" {{ $currentRating == 4.5 ? 'checked' : '' }}>
                                        <span class="rating-stars">★★★★½</span>
                                        <span>{{ __('messages.filter_rating_or_more') }}</span>
                                    </label>
                                    <label class="rating-option">
                                        <input type="radio" name="min_rating" value="5" {{ $currentRating == 5 ? 'checked' : '' }}>
                                        <span class="rating-stars">★★★★★</span>
                                        <span>{{ __('messages.filter_rating_excellent') }}</span>
                                    </label>
                                </div>
                            </div>

                            <div class="filter-section">
                                <div class="filter-section-title">
                                    <i class="fas fa-sort-amount-down"></i>
                                    {{ __('messages.filter_sort_by') }}
                                </div>
                                <div class="sort-options">
                                    @php $currentSort = request('sort', 'relevance'); @endphp
                                    <label class="sort-option">
                                        <input type="radio" name="sort" value="relevance" {{ $currentSort == 'relevance' ? 'checked' : '' }}>
                                        <span>{{ __('messages.filter_sort_relevance') }}</span>
                                    </label>
                                    <label class="sort-option">
                                        <input type="radio" name="sort" value="rating" {{ $currentSort == 'rating' ? 'checked' : '' }}>
                                        <span>{{ __('messages.filter_sort_rating') }}</span>
                                    </label>
                                    <label class="sort-option">
                                        <input type="radio" name="sort" value="newest" {{ $currentSort == 'newest' ? 'checked' : '' }}>
                                        <span>{{ __('messages.filter_sort_newest') }}</span>
                                    </label>
                                    <label class="sort-option">
                                        <input type="radio" name="sort" value="price_low" {{ $currentSort == 'price_low' ? 'checked' : '' }}>
                                        <span>{{ __('messages.filter_sort_price_low') }}</span>
                                    </label>
                                    <label class="sort-option">
                                        <input type="radio" name="sort" value="price_high" {{ $currentSort == 'price_high' ? 'checked' : '' }}>
                                        <span>{{ __('messages.filter_sort_price_high') }}</span>
                                    </label>
                                </div>
                            </div>

                        </div>

                        <div class="filter-actions">
                            <button type="submit" class="btn-filter-submit">
                                <i class="fas fa-search"></i>
                                {{ __('messages.filter_apply') }}
                            </button>
                        </div>
                    </form>
                </div>
            </aside>

            <main class="content-wrapper">

                <div class="listing-header-card">
                    <div class="listing-type-badge">
                        <i class="fas fa-tag"></i>
                        {{ $listing->type->translate('name') }}
                    </div>

                    <h1 class="listing-title-main">{{ $listing->translate('title') }}</h1>

                    <div class="listing-meta">
                        <div class="listing-meta-item">
                            <i class="fas fa-folder"></i>
                            @foreach($listing->categories as $cat)
                                <span style="background:#f0f4f8; color:var(--primary); padding:0.2rem 0.6rem; border-radius:6px; font-size:0.8rem; font-weight:600;">
                                    {{ $cat->translate('name') }}
                                </span>
                            @endforeach
                        </div>
                        <div class="listing-meta-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span style="color: #001c3d;">{{ $listing->translate('address') ?? __('messages.listing_address_not_specified') }}</span>
                        </div>
                        <div class="listing-rating-badge">
                            <i class="fas fa-star"></i>
                            <span>{{ number_format($listing->average_rating, 1) }}</span>
                        </div>
                    </div>

                    @php
                        // جلب رقم الهاتف (حاول استخدام listing->phone أولاً، ثم business->phone، ثم user->phone)
                        $contactPhone = $listing->phone
                                        ?? $listing->business?->phone
                                        ?? $listing->user?->phone
                                        ?? null;
                        if ($contactPhone) {
                            $cleanPhone = preg_replace('/[^\d+]/', '', $contactPhone);
                            // إنشاء رابط الاتصال المباشر
                            $telLink = 'tel:' . $cleanPhone;
                            // إنشاء رابط واتساب (دون أي رموز غير رقمية عدا +)
                            $whatsappLink = 'https://wa.me/' . ltrim($cleanPhone, '+');
                        }
                    @endphp

                    @if($contactPhone)
                    <div class="contact-info-card">
                        <div class="contact-phone">
                            <i class="fas fa-phone-alt"></i>
                            <span dir="ltr">{{ $contactPhone }}</span>
                        </div>
                        <div class="contact-actions">
                            <a href="{{ $telLink }}" class="btn-call">
                                <i class="fas fa-phone"></i> {{ __('messages.call_now') }}
                            </a>
                            <a href="{{ $whatsappLink }}" class="btn-whatsapp" target="_blank" rel="noopener noreferrer">
                                <i class="fab fa-whatsapp"></i> {{ __('messages.whatsapp_chat') }}
                            </a>
                        </div>
                    </div>
                    @endif

                    @auth
                    <div class="action-buttons">
                        @php
                            $isFav = auth()->user()->favorites()->where('listing_id', $listing->id)->exists();
                        @endphp
                        <button type="button"
                                class="btn-action btn-fav {{ $isFav ? 'remove' : '' }}"
                                data-url="{{ route('favorite.toggle', $listing) }}">
                            <i class="{{ $isFav ? 'fas' : 'far' }} fa-heart"></i>
                            {{ $isFav ? __('messages.listing_remove_favorite') : __('messages.listing_add_favorite') }}
                        </button>
                        @if($contactPhone)
                        <a href="{{ $telLink }}" class="btn-action btn-contact">
                            <i class="fas fa-phone"></i>
                            {{ __('messages.listing_contact_owner') }}
                        </a>
                        @else
                        <button class="btn-action btn-contact" disabled title="{{ __('messages.no_phone') }}">
                            <i class="fas fa-phone"></i>
                            {{ __('messages.listing_contact_owner') }}
                        </button>
                        @endif
                        <button class="btn-action btn-share">
                            <i class="fas fa-share-alt"></i>
                            {{ __('messages.listing_share') }}
                        </button>
                    </div>
                    @endauth
                </div>

                @php
                    $mediaItems = $listing->getMedia('images');
                @endphp

                @if($mediaItems->isNotEmpty())
                <div class="gallery-container" id="listingGallery">
                    <div class="gallery-main" id="galleryMainContainer">
                        @php $firstMedia = $mediaItems->first(); @endphp
                        <img id="galleryMainImage"
                             src="{{ $listing->getMediaSignedUrl($firstMedia, 'large') }}"
                             alt="{{ $listing->translate('title') }}">
                        @if($mediaItems->count() > 1)
                        <button class="gallery-nav gallery-prev" aria-label="{{ __('messages.listing_gallery_prev') }}">&lsaquo;</button>
                        <button class="gallery-nav gallery-next" aria-label="{{ __('messages.listing_gallery_next') }}">&rsaquo;</button>
                        @endif
                        <button class="gallery-fullscreen-btn" id="galleryFullscreenBtn" title="{{ __('messages.listing_gallery_fullscreen') }}">
                            <i class="fas fa-expand"></i>
                        </button>
                    </div>

                    @if($mediaItems->count() > 1)
                    <div class="gallery-thumbnails" id="galleryThumbnails">
                        @foreach($mediaItems as $media)
                        <div class="gallery-thumbnail {{ $loop->first ? 'active' : '' }}"
                             data-full="{{ $listing->getMediaSignedUrl($media, 'large') }}">
                            <img src="{{ $listing->getMediaSignedUrl($media, 'thumb') }}" alt="{{ __('messages.listing_gallery_image', ['number' => $loop->iteration]) }}">
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
                @else
                <div class="cover-image-container">
                    <div class="card-image-placeholder" style="aspect-ratio:16/7; font-size:1.5rem; width:100%; height:100%; display:flex; align-items:center; justify-content:center; background:#f0f4f8; color:#888;">
                        {{ __('messages.listing_no_image') }}
                    </div>
                </div>
                @endif

                <div class="card-detail">
                    <h2>
                        <i class="fas fa-info-circle"></i>
                        {{ __('messages.listing_about') }}
                    </h2>
                    <p>{{ $listing->translate('description') }}</p>
                </div>

                @if($listing->features->count() > 0)
                <div class="card-detail">
                    <h3>
                        <i class="fas fa-check-circle"></i>
                        {{ __('messages.listing_features') }}
                    </h3>
                    <div class="features-container">
                        @foreach($listing->features as $feature)
                            <span class="feature-badge">
                                <i class="fas fa-check"></i>
                                {{ $feature->translate('name') }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($listing->latitude && $listing->longitude)
                <div class="card-detail">
                    <h3>
                        <i class="fas fa-map-marked-alt"></i>
                        {{ __('messages.listing_map') }}
                    </h3>
                    <div id="map-show" class="map-container"></div>
                </div>
                @endif

                <div class="card-detail">
                    <h3>
                        <i class="fas fa-comments"></i>
                        {{ __('messages.listing_reviews', ['count' => $listing->total_reviews]) }}
                    </h3>
                    @forelse($listing->reviews as $review)
                        <div class="review-item">
                            <div class="review-header">
                                <div class="review-user">
                                    <div class="review-user-avatar">
                                        {{ mb_substr($review->user?->first_name ?? __('messages.listing_user_default'), 0, 1) }}
                                    </div>
                                    <span>{{ $review->user?->first_name ?? __('messages.listing_user_default') }}</span>
                                </div>
                                <div class="review-rating">
                                    <i class="fas fa-star"></i>
                                    {{ $review->rating }}
                                </div>
                            </div>
                            <p class="review-body">{{ $review->body }}</p>
                        </div>
                    @empty
                        <p style="color: #888;">{{ __('messages.listing_no_reviews') }}</p>
                    @endforelse
                </div>

                @if($relatedListings->count() > 0)
                <div class="card-detail">
                    <h3>
                        <i class="fas fa-th-large"></i>
                        {{ __('messages.listing_similar_activities') }}
                    </h3>
                    <div class="related-grid">
                        @foreach($relatedListings as $related)
                            <div class="listing-card">
                                <div class="card-image-wrapper">
                                    <a href="{{ route('listing.show', $related->slug) }}" class="card-image-link">
                                        @php $img = $related->getSignedImageUrl('medium', 60); @endphp
                                        @if($img)
                                            <img src="{{ $img }}" alt="{{ $related->translate('title') }}" class="card-image" loading="lazy">
                                        @else
                                            <div class="card-image-placeholder">{{ __('messages.listing_no_image') }}</div>
                                        @endif
                                    </a>
                                    <div class="card-badge badge-left">
                                        <i class="fas fa-tag"></i> {{ $related->type?->translate('name') ?? __('messages.listing_default_activity') }}
                                    </div>
                                </div>
                                <div class="card-body">
                                    <a href="{{ route('listing.show', $related->slug) }}" class="card-title-link">
                                        <h4 class="card-title">{{ $related->translate('title') }}</h4>
                                    </a>
                                    <div class="card-info">
                                        <div class="card-info-item">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span>{{ $related->city?->translate('name') ?? '' }}</span>
                                        </div>
                                    </div>
                                    <div class="card-rating">
                                        <i class="fas fa-star"></i>
                                        <span>{{ number_format($related->average_rating, 1) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </main>
        </div>
    </div>



    <div class="bottom-floating-elements">
        <a href="#" class="scroll-top-btn" id="scrollTopBtn"><i class="fa-solid fa-arrow-up"></i></a>
    </div>

    @if($contactPhone)
    <a href="{{ $whatsappLink }}" class="whatsapp-float" target="_blank" rel="noopener noreferrer" aria-label="{{ __('messages.whatsapp_chat') }}">
        <i class="fab fa-whatsapp"></i>
    </a>
    @endif

    <script>
        function toggleLanguageMenu() {
            const dropdown = document.getElementById('languageDropdown');
            const arrow = document.getElementById('langArrow');
            dropdown.classList.toggle('show');
            arrow.style.transform = dropdown.classList.contains('show') ? 'rotate(180deg)' : 'rotate(0deg)';
        }
        document.addEventListener('click', function(e) {
            const switcher = document.getElementById('languageSwitcher');
            const dropdown = document.getElementById('languageDropdown');
            if (!switcher.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.remove('show');
                document.getElementById('langArrow').style.transform = 'rotate(0deg)';
            }
        });
        document.addEventListener('DOMContentLoaded', function() {
            const currentLocale = '{{ app()->getLocale() }}';
            document.querySelectorAll('.lang-option').forEach(opt => {
                opt.classList.remove('active');
                if (opt.dataset.lang === currentLocale) {
                    opt.classList.add('active');
                    document.getElementById('currentFlag').outerHTML = opt.querySelector('.flag-icon').outerHTML;
                    document.getElementById('currentLang').textContent = currentLocale.toUpperCase();
                }
            });

            const scrollTopBtn = document.getElementById('scrollTopBtn');
            if (scrollTopBtn) {
                scrollTopBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });

                window.addEventListener('scroll', function() {
                    if (window.pageYOffset > 300) {
                        scrollTopBtn.style.opacity = '1';
                        scrollTopBtn.style.visibility = 'visible';
                    } else {
                        scrollTopBtn.style.opacity = '0';
                        scrollTopBtn.style.visibility = 'hidden';
                    }
                });

                scrollTopBtn.style.opacity = '0';
                scrollTopBtn.style.visibility = 'hidden';
                scrollTopBtn.style.transition = 'opacity 0.3s, visibility 0.3s';
            }
        });
    </script>

    @if($listing->latitude && $listing->longitude)
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const map = L.map('map-show').setView([{{ $listing->latitude }}, {{ $listing->longitude }}], 15);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap'
                }).addTo(map);
                L.marker([{{ $listing->latitude }}, {{ $listing->longitude }}])
                    .addTo(map)
                    .bindPopup("<strong>{{ $listing->translate('title') }}</strong>")
                    .openPopup();
                setTimeout(() => map.invalidateSize(), 300);
            });
        </script>
    @endif

    @auth
    <script>
        document.querySelector('.btn-fav[data-url]').addEventListener('click', async function() {
            const btn = this;
            const url = btn.dataset.url;
            btn.disabled = true;
            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
                const data = await res.json();
                if (data.hasOwnProperty('favorited')) {
                    const icon = btn.querySelector('i');
                    if (data.favorited) {
                        btn.innerHTML = '<i class="fas fa-heart"></i> {{ __('messages.listing_remove_favorite') }}';
                        btn.classList.add('remove');
                    } else {
                        btn.innerHTML = '<i class="far fa-heart"></i> {{ __('messages.listing_add_favorite') }}';
                        btn.classList.remove('remove');
                    }
                } else {
                    location.reload();
                }
            } catch (e) {
                alert('{{ __('messages.listing_error_favorite') }}');
            } finally {
                btn.disabled = false;
            }
        });
    </script>
    @endauth

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mainImage = document.getElementById('galleryMainImage');
            const thumbnails = document.querySelectorAll('.gallery-thumbnail');
            const prevBtn = document.querySelector('.gallery-prev');
            const nextBtn = document.querySelector('.gallery-next');
            const fullscreenBtn = document.getElementById('galleryFullscreenBtn');
            const galleryMainContainer = document.getElementById('galleryMainContainer');
            let currentIndex = 0;
            const totalImages = thumbnails.length;

            function updateGallery(index) {
                if (!mainImage || totalImages === 0) return;
                mainImage.src = thumbnails[index].dataset.full;
                thumbnails.forEach((thumb, i) => {
                    thumb.classList.toggle('active', i === index);
                });
                currentIndex = index;
            }

            thumbnails.forEach((thumb, idx) => {
                thumb.addEventListener('click', () => updateGallery(idx));
            });

            if (prevBtn) {
                prevBtn.addEventListener('click', () => {
                    const newIndex = (currentIndex - 1 + totalImages) % totalImages;
                    updateGallery(newIndex);
                });
            }
            if (nextBtn) {
                nextBtn.addEventListener('click', () => {
                    const newIndex = (currentIndex + 1) % totalImages;
                    updateGallery(newIndex);
                });
            }

            document.addEventListener('keydown', function(e) {
                if (e.key === 'ArrowLeft' && prevBtn) prevBtn.click();
                else if (e.key === 'ArrowRight' && nextBtn) nextBtn.click();
            });

            if (fullscreenBtn && galleryMainContainer) {
                fullscreenBtn.addEventListener('click', () => {
                    if (!document.fullscreenElement) {
                        if (galleryMainContainer.requestFullscreen) {
                            galleryMainContainer.requestFullscreen();
                        } else if (galleryMainContainer.webkitRequestFullscreen) {
                            galleryMainContainer.webkitRequestFullscreen();
                        } else if (galleryMainContainer.msRequestFullscreen) {
                            galleryMainContainer.msRequestFullscreen();
                        }
                    } else {
                        if (document.exitFullscreen) {
                            document.exitFullscreen();
                        } else if (document.webkitExitFullscreen) {
                            document.webkitExitFullscreen();
                        } else if (document.msExitFullscreen) {
                            document.msExitFullscreen();
                        }
                    }
                });

                document.addEventListener('fullscreenchange', updateFullscreenIcon);
                document.addEventListener('webkitfullscreenchange', updateFullscreenIcon);
                document.addEventListener('msfullscreenchange', updateFullscreenIcon);

                function updateFullscreenIcon() {
                    const icon = fullscreenBtn.querySelector('i');
                    if (document.fullscreenElement) {
                        icon.classList.remove('fa-expand');
                        icon.classList.add('fa-compress');
                    } else {
                        icon.classList.remove('fa-compress');
                        icon.classList.add('fa-expand');
                    }
                }
            }
        });
    </script>

</body>
</html>
