@extends('layouts.public')

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <style>
        .listings-slider {
            padding: 20px 0 40px;
            position: relative;
            overflow: hidden;
        }

        .swiper-wrapper {
            display: flex !important;
            flex-direction: row !important;
            gap: 0;
        }

        .swiper-slide {
            height: auto;
            width: auto;
            flex-shrink: 0;
            display: flex;
        }

        .swiper-slide .listing-card {
            width: 100%;
            max-width: 350px;
            margin: 0 auto;
        }

        .swiper-button-next,
        .swiper-button-prev {
            color: #2b73d2;
            background: #fff;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .swiper-button-next::after,
        .swiper-button-prev::after {
            font-size: 18px;
            font-weight: 700;
        }

        .swiper-button-next:hover,
        .swiper-button-prev:hover {
            background: #2b73d2;
            color: #fff;
            box-shadow: 0 6px 20px rgba(43, 115, 210, 0.3);
        }

        .swiper-button-disabled {
            opacity: 0.3 !important;
            cursor: default !important;
        }

        .swiper-pagination-bullet {
            background: #cbd5e1;
            opacity: 1;
            width: 10px;
            height: 10px;
            transition: all 0.3s ease;
        }

        .swiper-pagination-bullet-active {
            background: #2b73d2;
            width: 30px;
            border-radius: 5px;
        }

        .slider-section {
            padding: 30px 0 20px;
        }

        .slider-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding: 0 10px;
        }

        .slider-header h2 {
            font-size: 28px;
            font-weight: 700;
            color: #1a1a1a;
        }

        .slider-header .view-all-link {
            color: #2b73d2;
            font-weight: 600;
            text-decoration: none;
            font-size: 15px;
            transition: color 0.3s ease;
        }

        .slider-header .view-all-link:hover {
            color: #1e60be;
            text-decoration: underline;
        }

        .trending-header-section {
            padding: 30px 0 10px;
            background: #ffffff;
        }

        .trending-header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
            text-align: center;
        }

        .trending-badge {
            font-size: 14px;
            font-weight: 600;
            color: #888;
            letter-spacing: 2px;
            text-transform: uppercase;
            display: block;
            margin-bottom: 8px;
        }

        .trending-main-title {
            font-size: 32px;
            font-weight: 700;
            color: #111;
            margin: 0 0 15px 0;
            line-height: 1.2;
        }

        .trending-sub-text {
            font-size: 16px;
            color: #333;
            line-height: 1.6;
            max-width: 700px;
            margin: 0 auto;
        }

        @media (max-width: 768px) {
            .swiper-button-next,
            .swiper-button-prev {
                display: none !important;
            }

            .slider-header h2 {
                font-size: 22px;
            }

            .swiper-slide .listing-card {
                max-width: 280px;
            }

            .trending-main-title {
                font-size: 26px;
            }
        }

        @media (max-width: 480px) {
            .swiper-slide .listing-card {
                max-width: 100%;
            }

            .trending-main-title {
                font-size: 22px;
            }
        }
    </style>
@endsection

@section('content')



<section class="trending-header-section" data-aos="fade-up" data-aos-duration="700" data-aos-delay="0">
    <div class="trending-header-content">
        <span class="trending-badge">{{ __('messages.trending_badge') }}</span>
        <h2 class="trending-main-title">{{ __('messages.trending_title') }}</h2>
        <p class="trending-sub-text">
            {{ __('messages.trending_description') }}
        </p>
    </div>
</section>

@include('public.sections.explore')



<section class="slider-section" data-aos="fade-up" data-aos-duration="700" data-aos-delay="200">
    <div class="container mx-auto px-4">
        <div class="slider-header">
            <h2>
                @if($listings->total() > 0)
                    {{ trans_choice('messages.activities_count', $listings->total(), ['count' => $listings->total()]) }}
                @else
                    {{ __('messages.no_results') }}
                @endif
            </h2>
            <a href="{{ route('search') }}" class="view-all-link">{{ __('messages.view_all') }} <i class="fas fa-arrow-right"></i></a>
        </div>

        @if($listings->isEmpty())
            <div class="bg-white p-8 text-center rounded-xl border border-gray-100 shadow-sm">
                <p class="text-gray-600">{{ __('messages.no_results_message') }}</p>
            </div>
        @else
            <div class="listings-slider swiper" id="mainSlider">
                <div class="swiper-wrapper">
                    @foreach($listings as $listing)
                        <div class="swiper-slide">
                            @include('public.partials.listing-card', ['listing' => $listing])
                        </div>
                    @endforeach
                </div>


            </div>
        @endif
    </div>
</section>

<!--@if($featuredListings->count() > 0)-->
<!--<section class="slider-section bg-gray-50 py-12" data-aos="fade-up" data-aos-duration="700" data-aos-delay="400">-->
<!--    <div class="container mx-auto px-4">-->
<!--        <div class="slider-header">-->
<!--            <h2>{{ __('messages.featured_activities') }}</h2>-->
<!--            <a href="{{ route('search') }}" class="view-all-link">{{ __('messages.explore_more') }} <i class="fas fa-arrow-right"></i></a>-->
<!--        </div>-->

<!--        <div class="listings-slider swiper" id="featuredSlider">-->
<!--            <div class="swiper-wrapper">-->
<!--                @foreach($featuredListings as $listing)-->
<!--                    <div class="swiper-slide">-->
<!--                        @include('public.partials.listing-card', ['listing' => $listing])-->
<!--                    </div>-->
<!--                @endforeach-->
<!--            </div>-->


<!--        </div>-->
<!--    </div>-->
<!--</section>-->
<!--@endif-->

{{-- ============================================================ --}}

@include('public.sections.discover')
@include('public.sections.reviews')
@include('public.sections.blog', ['posts' => $posts])

@include('public.sections.footer')

@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mainSliderElement = document.getElementById('mainSlider');
            if (mainSliderElement) {
                new Swiper(mainSliderElement, {
                    slidesPerView: 1,
                    spaceBetween: 20,
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    breakpoints: {
                        480: {
                            slidesPerView: 1.2,
                            spaceBetween: 15,
                        },
                        640: {
                            slidesPerView: 2,
                            spaceBetween: 20,
                        },
                        768: {
                            slidesPerView: 2.2,
                            spaceBetween: 25,
                        },
                        1024: {
                            slidesPerView: 3,
                            spaceBetween: 30,
                        },
                        1280: {
                            slidesPerView: 4,
                            spaceBetween: 30,
                        },
                    },
                    on: {
                        init: function() {
                            this.update();
                        }
                    }
                });
            }

            const featuredSliderElement = document.getElementById('featuredSlider');
            if (featuredSliderElement) {
                new Swiper(featuredSliderElement, {
                    slidesPerView: 1,
                    spaceBetween: 20,
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    breakpoints: {
                        480: {
                            slidesPerView: 1.2,
                            spaceBetween: 15,
                        },
                        640: {
                            slidesPerView: 2,
                            spaceBetween: 20,
                        },
                        768: {
                            slidesPerView: 2.2,
                            spaceBetween: 25,
                        },
                        1024: {
                            slidesPerView: 3,
                            spaceBetween: 30,
                        },
                        1280: {
                            slidesPerView: 4,
                            spaceBetween: 30,
                        },
                    },
                });
            }
        });
    </script>
@endsection
