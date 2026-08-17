<section class="discover-section" data-aos="fade-up" data-aos-duration="800" data-aos-delay="0">

    <div class="dark-background-overlay"></div>

    <div class="discover-container">

        <div class="discover-top">

            <div class="discover-visuals" data-aos="fade-right" data-aos-duration="800" data-aos-delay="200">
                <div class="circle-container">
                    <svg viewBox="0 0 100 100" class="spinning-svg">
                        <defs>
                            <path id="circlePath" d="M 50, 50 m -35, 0 a 35,35 0 1,1 70,0 a 35,35 0 1,1 -70,0" />
                        </defs>
                        <text fill="#ffffff" font-size="9" font-weight="700" letter-spacing="2">
                            <textPath href="#circlePath">
                                {{ __('messages.discover_spinning_text') }}
                            </textPath>
                        </text>
                    </svg>
                </div>

                <div class="img-box img-1">
                    <img src="https://images.unsplash.com/photo-1554118811-1e0d58224f24?q=80&w=500&auto=format&fit=crop"
                         alt="{{ __('messages.discover_image_cafe') }}" loading="lazy">
                </div>

                <div class="img-box img-2">
                    <img src="https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?q=80&w=500&auto=format&fit=crop"
                         alt="{{ __('messages.discover_image_party') }}" loading="lazy">
                </div>

                <svg class="red-squiggle" viewBox="0 0 100 50" fill="none">
                    <path d="M 10 40 Q 25 10, 40 35 T 70 15 T 90 30"
                          stroke="#ef4444" stroke-width="4" stroke-linecap="round"/>
                </svg>
            </div>

            <div class="discover-text-content" data-aos="fade-left" data-aos-duration="800" data-aos-delay="400">
                <h2 class="discover-title">{{ __('messages.discover_title') }}</h2>

                <svg class="red-wave-line" viewBox="0 0 100 15" fill="none">
                    <path d="M2 10 Q 25 2, 50 10 T 98 10"
                          stroke="#ef4444" stroke-width="3.5" stroke-linecap="round"/>
                </svg>

                <p class="discover-text">
                    {!! __('messages.discover_text') !!}
                </p>

                <a href="{{ route('search') }}" class="listing-map-btn">
                    {{ __('messages.discover_button') }} <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>

        </div>

        <div class="categories-grid" data-aos="fade-up" data-aos-duration="800" data-aos-delay="300">
            @php
                $categoriesData = [
                    [
                        'name' => __('messages.category_gastronomy'),
                        'icon' => 'fa-utensils',
                        'slug' => 'gastronomy',
                        'img' => 'category-bg-Gastronomy.webp',
                        'id'   => $categories->where('slug', 'gastronomy')->first()?->id,
                        'count' => $categories->where('slug', 'gastronomy')->first()?->listings_count ?? 0
                    ],
                    [
                        'name' => __('messages.category_culture_art'),
                        'icon' => 'fa-binoculars',
                        'slug' => 'culture-art',
                        'img' => 'category-bg-Culture.webp',
                        'id'   => $categories->where('slug', 'culture-art')->first()?->id,
                        'count' => $categories->where('slug', 'culture-art')->first()?->listings_count ?? 0
                    ],
                    [
                        'name' => __('messages.category_outdoor_activities'),
                        'icon' => 'fa-map',
                        'slug' => 'outdoor-activities',
                        'img' => 'category-bg-Activities.webp',
                        'id'   => $categories->where('slug', 'outdoor-activities')->first()?->id,
                        'count' => $categories->where('slug', 'outdoor-activities')->first()?->listings_count ?? 0
                    ],
                    [
                        'name' => __('messages.category_rural_tourism'),
                        'icon' => 'fa-radio',
                        'slug' => 'rural-tourism',
                        'img' => 'category-bg-Tourism.webp',
                        'id'   => $categories->where('slug', 'rural-tourism')->first()?->id,
                        'count' => $categories->where('slug', 'rural-tourism')->first()?->listings_count ?? 0
                    ],
                    [
                        'name' => __('messages.category_wellness_health'),
                        'icon' => 'fa-stethoscope',
                        'slug' => 'wellness-health',
                        'img' => 'category-bg-Wellness.webp',
                        'id'   => $categories->where('slug', 'wellness-health')->first()?->id,
                        'count' => $categories->where('slug', 'wellness-health')->first()?->listings_count ?? 0
                    ],
                ];
            @endphp

            @foreach($categoriesData as $index => $cat)
                <a href="{{ $cat['id'] ? route('search', ['category' => $cat['id']]) : '#' }}" class="category-card"
                   data-aos="fade-up"
                   data-aos-delay="{{ 200 + ($index * 100) }}"
                   data-aos-duration="600"
                   data-aos-anchor-placement="top-bottom">
                    <img src="{{ asset('images/category/'.$cat['img']) }}"
                         class="category-bg"
                         alt="{{ $cat['name'] }}"
                         loading="lazy">
                    <div class="category-overlay"></div>
                    <div class="category-content">
                        <div class="category-icon">
                            <i class="fa-solid {{ $cat['icon'] }}"></i>
                        </div>
                        <h3 class="category-name">{{ $cat['name'] }}</h3>
                        <span class="category-count">{{ $cat['count'] }} {{ $cat['count'] == 1 ? __('messages.activity_singular') : __('messages.activity_plural') }}</span>
                    </div>
                </a>
            @endforeach
        </div>

    </div>
</section>

<style>
.discover-section {
    position: relative;
    width: 100%;
    background-color: #ffffff;
    padding: 60px 0 50px;
    z-index: 1;
}

.dark-background-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: calc(100% - 130px);
    z-index: -1;
    background: #0a0f19d6 url('{{ asset('images/category/turiBgDr.webp') }}') center/cover no-repeat;
    background-blend-mode: overlay;
}

.discover-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

.discover-top {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 40px;
    margin-bottom: 50px;
}

.discover-visuals {
    position: relative;
    width: 420px;
    height: 280px;
    flex-shrink: 0;
}

.circle-container {
    position: absolute;
    top: -10px;
    left: -10px;
    width: 110px;
    height: 110px;
    z-index: 10;
}

.spinning-svg {
    width: 100%;
    height: 100%;
    animation: rotateText 12s linear infinite;
    filter: drop-shadow(0px 3px 5px rgba(0,0,0,0.4));
}

@keyframes rotateText {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.img-box {
    position: absolute;
    border: 3px solid #ffffff;
    border-radius: 0px;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.35);
}

.img-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.img-1 {
    width: 200px;
    height: 240px;
    top: 30px;
    left: 45px;
    z-index: 2;
}

.img-2 {
    width: 180px;
    height: 180px;
    top: 0;
    right: 25px;
    z-index: 1;
}

.red-squiggle {
    position: absolute;
    bottom: -10px;
    right: 40px;
    width: 75px;
    z-index: 3;
}

.discover-text-content {
    flex: 1;
    max-width: 520px;
}

.discover-title {
    font-size: 30px;
    font-weight: 700;
    line-height: 1.3;
    color: #ffffff;
    margin-bottom: 8px;
    direction: ltr;
}

.red-wave-line {
    width: 75px;
    height: 10px;
    margin-bottom: 20px;
}

.discover-text {
    font-size: 14.5px;
    color: #ffffffcf;
    line-height: 1.65;
    margin-bottom: 25px;
    direction: ltr;
}

.discover-text strong {
    color: #ffffff;
    font-weight: 600;
}

.listing-map-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background-color: #001c3d;
    color: #ffffff;
    padding: 10px 24px;
    border-radius: 25px;
    font-size: 13.5px;
    font-weight: 600;
    text-decoration: none;
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
}

.listing-map-btn:hover {
    background-color: #002d62;
    transform: translateY(-2px);
    color: #ffffff;
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 18px;
    position: relative;
    z-index: 5;
}

.category-card {
    position: relative;
    height: 240px;
    overflow: hidden;
    text-decoration: none;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
    align-items: center;
    padding: 16px 10px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    transition: transform 0.3s ease;
    background-color: #0a0f19;
    border-radius: 12px;
}

.category-card:hover {
    transform: translateY(-5px);
}

.category-bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    z-index: 0;
    transition: transform 0.5s ease;
}

.category-card:hover .category-bg {
    transform: scale(1.08);
}

.category-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(to top, rgba(0, 11, 28, 0.95) 0%, rgba(0, 11, 28, 0.15) 70%);
    z-index: 1;
    border-radius: 12px;
}

.category-content {
    position: relative;
    z-index: 2;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
}

.category-icon {
    width: 38px;
    height: 38px;
    background-color: #001c3d;
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    font-size: 14px;
    transition: all 0.3s ease;
}

.category-card:hover .category-icon {
    background-color: #ef4444;
    border-color: #ef4444;
    transform: scale(1.1);
}

.category-name {
    font-size: 15px;
    font-weight: 700;
    color: #ffffff;
    margin: 0;
}

.category-count {
    font-size: 11.5px;
    font-weight: 500;
    color: rgba(255, 255, 255, 0.9);
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(5px);
    -webkit-backdrop-filter: blur(5px);
    padding: 3px 12px;
    border-radius: 20px;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

@media (max-width: 1024px) {
    .discover-top {
        flex-direction: column;
        text-align: center;
    }
    .discover-text-content {
        display: flex;
        flex-direction: column;
        align-items: center;
        max-width: 100%;
    }
    .discover-text {
        text-align: center;
    }
    .categories-grid {
        grid-template-columns: repeat(3, 1fr);
    }
    .dark-background-overlay {
        height: calc(100% - 240px);
    }
}

@media (max-width: 768px) {
    .categories-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    .discover-visuals {
        width: 100%;
        max-width: 340px;
        height: 240px;
    }
    .img-1 {
        left: 10px;
        width: 58%;
        height: 200px;
    }
    .img-2 {
        right: 0;
        width: 48%;
        height: 160px;
    }
    .discover-title {
        font-size: 24px;
    }
    .dark-background-overlay {
        height: calc(100% - 360px);
    }
}

@media (max-width: 576px) {
    .categories-grid {
        grid-template-columns: 1fr 1fr;
    }
    .category-card {
        height: 180px;
    }
    .discover-title {
        font-size: 20px;
    }
    .dark-background-overlay {
        height: calc(100% - 480px);
    }
}

@media (max-width: 400px) {
    .categories-grid {
        grid-template-columns: 1fr;
    }
    .dark-background-overlay {
        height: calc(100% - 700px);
    }
}
</style>