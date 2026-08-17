{{-- الصفحة الكاملة مع مفاتيح الترجمة المطبقة --}}
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

    .help-banner-section {
        width: 100%;
        padding: 60px 20px;
        display: flex;
        justify-content: center;
    }

    .help-banner-container {
        background: linear-gradient(rgba(0, 11, 24, 0.7), rgba(0, 11, 24, 0.7)),
                    url('https://images.unsplash.com/photo-1477959858617-67f85cf4f1df?auto=format&fit=crop&w=1920&q=80') center/cover;
        max-width: 1100px;
        width: 100%;
        border-radius: 25px;
        padding: 50px 60px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .help-content {
        max-width: 600px;
        color: #ffffff;
    }

    .help-content h2 {
        font-size: 32px;
        font-weight: 700;
        margin-bottom: 15px;
    }

    .help-content p {
        font-size: 15px;
        line-height: 1.6;
        opacity: 0.9;
    }

    .contact-btn {
        background-color: #001c3d;
        color: #ffffff;
        text-decoration: none;
        padding: 15px 35px;
        border-radius: 40px;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: background 0.3s;
        white-space: nowrap;
    }

    .contact-btn:hover {
        background-color: #002d62;
    }

    .reviews-section {
        width: 100%;
        padding: 80px 20px;
        display: flex;
        justify-content: center;
        background-color: #fbfbfb;
    }

    .reviews-container {
        max-width: 1100px;
        width: 100%;
        display: flex;
        align-items: center;
        gap: 80px;
    }

    .review-image-wrapper {
        position: relative;
        flex: 1;
    }

    .main-client-img {
        width: 100%;
        max-width: 450px;
        height: 550px;
        object-fit: cover;
        border-radius: 30px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
        display: block;
    }

    .decor-lines {
        position: absolute;
        top: -20px;
        left: -20px;
        width: 40px;
        height: 50px;
        z-index: -1;
    }

    .floating-badge {
        position: absolute;
        bottom: 30px;
        left: -40px;
        background: #ffffff;
        padding: 25px 35px;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        text-align: center;
        border: 1px solid #eeeeee;
    }

    .badge-stars {
        color: #ffb800;
        font-size: 14px;
        margin-bottom: 5px;
        letter-spacing: 2px;
    }

    .badge-number {
        font-size: 32px;
        font-weight: 700;
        color: #111111;
        margin: 5px 0 0 0;
    }

    .badge-text {
        font-size: 13px;
        color: #555555;
        font-weight: 500;
    }

    .review-text-wrapper {
        flex: 1;
    }

    .review-title {
        font-size: 36px;
        font-weight: 700;
        color: #222222;
        line-height: 1.3;
        margin-bottom: 10px;
        max-width: 450px;
    }

    .decor-swoosh {
        width: 150px;
        height: 20px;
        margin-bottom: 30px;
    }

    .reviewer-info {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
        padding-bottom: 25px;
        border-bottom: 1px solid #e5e5e5;
        max-width: 400px;
    }

    .reviewer-avatar {
        width: 55px;
        height: 55px;
        border-radius: 50%;
        object-fit: cover;
        background-color: #001c3d;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 22px;
        text-transform: uppercase;
        flex-shrink: 0;
    }

    .reviewer-details h4 {
        font-size: 18px;
        font-weight: 600;
        color: #222222;
        margin-bottom: 2px;
    }

    .reviewer-details span {
        font-size: 13px;
        color: #888888;
    }

    .client-rating {
        color: #ffb800;
        font-size: 14px;
        margin-bottom: 15px;
    }

    .review-paragraph {
        font-size: 16px;
        color: #444444;
        line-height: 1.8;
        margin-bottom: 30px;
        max-width: 500px;
    }

    .review-controls {
        display: flex;
        gap: 10px;
    }

    .nav-arrow {
        width: 40px;
        height: 40px;
        background: #ffffff;
        border: 1px solid #dddddd;
        border-radius: 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #222222;
        font-size: 14px;
        transition: all 0.3s;
    }

    .nav-arrow:hover {
        background: #f1f1f1;
        border-color: #bbbbbb;
    }

    .bottom-floating-elements {
        position: fixed;
        bottom: 30px;
        left: 30px;
        right: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        pointer-events: none;
        z-index: 99;
    }

    .language-switcher-bottom {
        background: #ffffff;
        padding: 8px 15px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        cursor: pointer;
        pointer-events: auto;
        border: 1px solid #eee;
    }

    .language-switcher-bottom img { width: 20px; border-radius: 2px; }
    .language-switcher-bottom span { font-size: 14px; font-weight: 700; color: #333;}
    .language-switcher-bottom i { font-size: 10px; color: #666; }

    .scroll-top-btn {
        background-color: #001c3d;
        color: #ffffff;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        pointer-events: auto;
        transition: background 0.3s;
    }

    .scroll-top-btn:hover { background-color: #002d62; }

    @media (max-width: 991px) {
        .help-banner-container {
            flex-direction: column;
            text-align: center;
            padding: 40px 30px;
            gap: 30px;
        }

        .help-content { margin: 0 auto; }

        .reviews-container {
            flex-direction: column;
            gap: 60px;
        }

        .review-image-wrapper {
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .main-client-img {
            max-width: 100%;
            height: 400px;
        }
        .floating-badge {
            left: 10px;
            bottom: -20px;
            padding: 15px 25px;
        }

        .decor-lines {
            left: 0;
        }

        .review-text-wrapper {
            width: 100%;
        }

        .review-title {
            max-width: 100%;
        }

        .review-paragraph {
            max-width: 100%;
        }
    }

    @media (max-width: 576px) {
        .help-content h2 { font-size: 26px; }
        .review-title { font-size: 28px; }
        .bottom-floating-elements {
            left: 15px;
            right: 15px;
            bottom: 15px;
        }
    }
</style>

<section class="help-banner-section" style="direction: ltr;" data-aos="fade-up" data-aos-duration="800" data-aos-delay="0">
    <div class="help-banner-container" data-aos="fade-up" data-aos-duration="800">
        <div class="help-content" data-aos="fade-right" data-aos-delay="200" data-aos-duration="700">
            <h2>{{ __('messages.help_title') }}</h2>
            <p>{{ __('messages.help_description') }}</p>
        </div>
        <div class="help-action" data-aos="fade-left" data-aos-delay="400" data-aos-duration="700">
            <a href="{{ route('contact') }}" class="contact-btn {{ request()->routeIs('contact') ? 'active' : '' }}">
                {{ __('messages.help_button') }} <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

@php
    $siteReviews = \App\Models\SiteReview::approved()
        ->with('user')
        ->latest()
        ->take(6)
        ->get();

    $initialReview = $siteReviews->first();

    $reviewsData = $siteReviews->map(function ($review) {
        return [
            // استخدم المفتاح الجديد للعنوان الافتراضي
            'title' => $review->title ?: __('messages.review_default_title'),
            'text' => $review->comment,
            // استخدم المفتاح الجديد للاسم الافتراضي
            'reviewer_name' => $review->user?->name ?? __('messages.review_default_client_name'),
            // استخدم المفتاح الجديد للنص الثابت
            'reviewer_location' => __('messages.review_verified_user'),
            // استخدم المفتاح الجديد للنص الثابت
            'badge_text' => __('messages.review_from_client_reviews'),
            'badge_number' => (string) $review->rating,
            // استخدم المفتاح الجديد للاسم الافتراضي في الحرف الأول
            'avatar' => mb_substr($review->user?->name ?? __('messages.review_default_client_name'), 0, 1, 'UTF-8'),
            'main_image' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=600&q=80',
        ];
    })->values();
@endphp

@if($siteReviews->isNotEmpty())
    <section class="reviews-section" style="direction: ltr;" data-aos="fade-up" data-aos-duration="800" data-aos-delay="200">
        <div class="reviews-container">
            <div class="review-image-wrapper" data-aos="zoom-in-right" data-aos-duration="800" data-aos-delay="100">
                <svg class="decor-lines" viewBox="0 0 50 50" xmlns="http://www.w3.org/2000/svg">
                    <line x1="5" y1="35" x2="20" y2="5" stroke="#ff4a5a" stroke-width="4" stroke-linecap="round"/>
                    <line x1="20" y1="45" x2="35" y2="15" stroke="#ff4a5a" stroke-width="4" stroke-linecap="round"/>
                    <line x1="35" y1="55" x2="50" y2="25" stroke="#ff4a5a" stroke-width="4" stroke-linecap="round"/>
                </svg>

                <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=600&q=80" alt="Customer review" class="main-client-img" id="mainClientImage">

                <div class="floating-badge">
                    <div class="badge-stars">
                        @for($i=1; $i<=5; $i++)
                            <i class="fa-solid fa-star"></i>
                        @endfor
                    </div>
                    <h3 class="badge-number" id="badgeNumber">{{ $initialReview->rating }}</h3>
                    {{-- استخدم المفتاح الجديد للنص الثابت --}}
                    <span class="badge-text" id="badgeText">{{ __('messages.review_from_client_reviews') }}</span>
                </div>
            </div>

            <div class="review-text-wrapper">
                {{-- استخدم المفتاح الجديد للعنوان الافتراضي --}}
                <h2 class="review-title" data-aos="fade-up" data-aos-delay="200" data-aos-duration="700" id="reviewTitle">{{ $initialReview->title ?: __('messages.review_default_title') }}</h2>

                <svg class="decor-swoosh" viewBox="0 0 200 20" xmlns="http://www.w3.org/2000/svg" data-aos="fade-up" data-aos-delay="300" data-aos-duration="600">
                    <path d="M0,15 Q100,-10 200,15" fill="none" stroke="#ff4a5a" stroke-width="4" stroke-linecap="round"/>
                </svg>

                <div class="reviewer-info" data-aos="fade-up" data-aos-delay="400" data-aos-duration="700">
                    <div class="reviewer-avatar" id="reviewerAvatar">
                        {{ mb_substr($initialReview->user?->name ?? __('messages.review_default_client_name'), 0, 1, 'UTF-8') }}
                    </div>
                    <div class="reviewer-details">
                        <h4 id="reviewerName">{{ $initialReview->user?->name ?? __('messages.review_default_client_name') }}</h4>
                        {{-- استخدم المفتاح الجديد للنص الثابت --}}
                        <span id="reviewerLocation">{{ __('messages.review_verified_user') }}</span>
                    </div>
                </div>

                <div class="client-rating" data-aos="fade-up" data-aos-delay="500" data-aos-duration="600">
                    @for($i=1; $i<=5; $i++)
                        <i class="fa-solid fa-star{{ $i > $initialReview->rating ? ' fa-regular' : '' }}"></i>
                    @endfor
                </div>

                <p class="review-paragraph" data-aos="fade-up" data-aos-delay="600" data-aos-duration="700" id="reviewText">
                    {{ $initialReview->comment }}
                </p>

                <div class="review-controls" data-aos="fade" data-aos-delay="800" data-aos-duration="500">
                    <button type="button" class="nav-arrow" id="prevReview" aria-label="Review previous"><i class="fa-solid fa-chevron-left"></i></button>
                    <button type="button" class="nav-arrow" id="nextReview" aria-label="Review next"><i class="fa-solid fa-chevron-right"></i></button>
                </div>
            </div>
        </div>
    </section>

    <div class="bottom-floating-elements">
        <a href="#" class="scroll-top-btn" data-aos="fade-up" data-aos-delay="1000" data-aos-duration="500"><i class="fa-solid fa-arrow-up"></i></a>
    </div>

    <script>
        const reviewsData = @json($reviewsData);

        const titleEl = document.getElementById('reviewTitle');
        const textEl = document.getElementById('reviewText');
        const nameEl = document.getElementById('reviewerName');
        const locationEl = document.getElementById('reviewerLocation');
        const avatarEl = document.getElementById('reviewerAvatar');
        const badgeNumberEl = document.getElementById('badgeNumber');
        const badgeTextEl = document.getElementById('badgeText');
        const mainImageEl = document.getElementById('mainClientImage');

        let currentReview = 0;
        const totalReviews = reviewsData.length;

        function updateReview(index) {
            const data = reviewsData[index];
            if (!data) return;

            titleEl.textContent = data.title;
            textEl.textContent = data.text;
            nameEl.textContent = data.reviewer_name;
            locationEl.textContent = data.reviewer_location;
            avatarEl.textContent = data.avatar;
            badgeNumberEl.textContent = data.badge_number;
            badgeTextEl.textContent = data.badge_text;
            mainImageEl.src = data.main_image;
        }

        document.getElementById('nextReview').addEventListener('click', function() {
            currentReview = currentReview >= totalReviews - 1 ? 0 : currentReview + 1;
            updateReview(currentReview);
        });

        document.getElementById('prevReview').addEventListener('click', function() {
            currentReview = currentReview <= 0 ? totalReviews - 1 : currentReview - 1;
            updateReview(currentReview);
        });
    </script>
@endif