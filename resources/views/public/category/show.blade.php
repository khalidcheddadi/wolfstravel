<!DOCTYPE html>
<html lang="ar" dir="rtl" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $metaTitle ?? $category->name }}</title>
    <meta name="description" content="{{ $metaDescription ?? '' }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">

    <!-- Icons & Animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Tajawal', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            900: '#1e3a8a',
                        },
                        dark: '#0f172a',
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans bg-gray-50 text-gray-800 antialiased selection:bg-primary-500 selection:text-white">

    <!-- Container -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16">

        {{-- Header Section --}}
        <div class="mb-10 lg:mb-16 text-center lg:text-start" data-aos="fade-up">
            <h1 class="text-3xl lg:text-5xl font-extrabold text-dark tracking-tight mb-4 flex items-center justify-center lg:justify-start gap-3">
                <i class="fa-solid fa-location-dot text-primary-600 text-2xl lg:text-4xl"></i>
                {{ $category->name }}
            </h1>

            @if(!empty($category->description))
                <p class="text-gray-500 text-lg max-w-2xl leading-relaxed mb-4 mx-auto lg:mx-0">
                    {{ $category->description }}
                </p>
            @endif

            <div class="inline-flex items-center gap-2 bg-white px-4 py-2 rounded-full shadow-sm border border-gray-100 text-sm font-semibold text-gray-600">
                <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                {{ $listings->total() }} نشاط متاح
            </div>
        </div>

        {{-- Main Layout: Grid --}}
        <div class="flex flex-col lg:flex-row gap-8 xl:gap-12">

            {{-- Sidebar --}}
            <aside class="w-full lg:w-1/4" data-aos="fade-left" data-aos-delay="100">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 sticky top-8">
                    <h3 class="text-xl font-bold text-dark mb-6 flex items-center gap-2">
                        <i class="fa-solid fa-layer-group text-primary-600"></i>
                        التصنيفات
                    </h3>
                    <ul class="space-y-2">
                        @foreach($mainCategories as $cat)
                            <li>
                                <a href="{{ route(app()->getLocale() . '.category.show', $cat->slug) }}"
                                   class="group flex items-center justify-between p-3 rounded-2xl transition-all duration-300
                                   {{ $cat->id == ($category->id ?? null) ? 'bg-primary-50 text-primary-600 font-bold' : 'text-gray-600 hover:bg-gray-50 hover:text-dark font-medium' }}">
                                    <span class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $cat->id == ($category->id ?? null) ? 'bg-primary-100' : 'bg-gray-100 group-hover:bg-white group-hover:shadow-sm transition-all' }}">
                                            <i class="fa-solid fa-hashtag text-xs"></i>
                                        </div>
                                        {{ $cat->name }}
                                    </span>
                                    <span class="text-xs py-1 px-2.5 rounded-full bg-white border border-gray-100 shadow-sm {{ $cat->id == ($category->id ?? null) ? 'text-primary-600' : 'text-gray-400' }}">
                                        {{ $cat->listings_count ?? 0 }}
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </aside>

            {{-- Main Content --}}
            <div class="w-full lg:w-3/4">
                @if($listings->count() > 0)

                    {{-- Listings Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 gap-6 lg:gap-8">
                        @foreach($listings as $index => $listing)
                            {{-- Card --}}
                            <div class="group bg-white rounded-3xl border border-gray-100 overflow-hidden hover:shadow-[0_20px_40px_-15px_rgba(0,0,0,0.05)] hover:-translate-y-1 transition-all duration-400"
                                 data-aos="fade-up"
                                 data-aos-delay="{{ $index * 50 }}">

                                {{-- Card Image --}}
                                <div class="relative w-full aspect-[4/3] overflow-hidden bg-gray-100">
                                    <a href="{{ route(app()->getLocale() . '.listing.show', $listing->slug) }}" class="block w-full h-full">
                                        @php
                                            $imageUrl = $listing->getSignedImageUrl('medium', 60);
                                        @endphp

                                        @if($imageUrl)
                                            <img src="{{ $imageUrl }}"
                                                 alt="{{ $listing->city?->name ?? 'Listing' }}"
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 ease-in-out"
                                                 loading="lazy">
                                        @else
                                            <div class="w-full h-full flex flex-col items-center justify-center text-gray-400 bg-gray-50">
                                                <i class="fa-regular fa-image text-4xl mb-2"></i>
                                                <span class="text-sm">{{ __('messages.listing_no_image') }}</span>
                                            </div>
                                        @endif
                                    </a>

                                    {{-- Type Badge (Top Right) --}}
                                    <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm text-dark text-xs font-bold px-3 py-1.5 rounded-full shadow-sm flex items-center gap-1.5">
                                        <i class="fas fa-tag text-primary-600"></i>
                                        <span>{{ $category->name }}</span>
                                    </div>

                                    @php
                                        $availabilityState = $listing->publicAvailabilityState();
                                    @endphp
                                    @if($availabilityState)
                                        <div class="absolute bottom-4 left-4 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg backdrop-blur-md flex items-center gap-1.5 {{ $availabilityState === 'open' ? 'bg-emerald-500/90' : 'bg-red-500/90' }}">
                                            <div class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></div>
                                            {{ $availabilityState === 'open' ? __('messages.search_open_now') : __('messages.search_closed_now') }}
                                        </div>
                                    @endif
                                </div>

                                {{-- Card Content --}}
                                <div class="p-5 lg:p-6">
                                    <a href="{{ route(app()->getLocale() . '.listing.show', $listing->slug) }}">
                                        <h3 class="text-xl font-bold text-dark mb-3 line-clamp-1 group-hover:text-primary-600 transition-colors">
                                            {{ $listing->city?->name ?? 'Madrid' }}
                                        </h3>
                                    </a>

                                    <div class="flex flex-col gap-2.5 mb-5">
                                        <div class="flex items-center gap-2.5 text-sm text-gray-500">
                                            <div class="w-6 h-6 rounded-full bg-gray-50 flex items-center justify-center text-gray-400">
                                                <i class="fas fa-map-marker-alt text-xs"></i>
                                            </div>
                                            <span class="truncate">{{ $listing->city?->name ?? 'Madrid' }}</span>
                                        </div>
                                        <div class="flex items-center gap-2.5 text-sm text-gray-500">
                                            <div class="w-6 h-6 rounded-full bg-gray-50 flex items-center justify-center text-gray-400">
                                                <i class="fas fa-phone text-xs"></i>
                                            </div>
                                            <span dir="ltr" class="font-medium tracking-wide">{{ $listing->phone ?? '+34607265518' }}</span>
                                        </div>
                                    </div>

                                    {{-- Card Footer --}}
                                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                        <div class="flex items-center gap-3">
                                            <button class="w-9 h-9 rounded-full flex items-center justify-center text-gray-400 hover:bg-gray-50 hover:text-dark transition-colors"
                                                    aria-label="{{ __('messages.search_gallery_label') }}"
                                                    title="{{ __('messages.search_gallery_label') }}">
                                                <i class="far fa-image"></i>
                                            </button>
                                        </div>

                                        <div class="flex items-center gap-1.5 bg-amber-50 px-2.5 py-1 rounded-lg">
                                            <i class="fas fa-star text-amber-400 text-xs"></i>
                                            <span class="font-bold text-amber-700 text-sm mt-0.5">
                                                {{ number_format($listing->average_rating ?? 0, 1) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-12 flex justify-center custom-pagination">
                        {{ $listings->appends(request()->query())->links() }}
                    </div>

                @else
                    {{-- Empty State --}}
                    <div class="bg-white rounded-3xl border border-gray-100 p-12 text-center shadow-sm flex flex-col items-center" data-aos="zoom-in">
                        <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center text-gray-300 text-4xl mb-6">
                            <i class="fa-solid fa-box-open"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-dark mb-2">لا توجد أنشطة حالياً</h3>
                        <p class="text-gray-500 mb-8 max-w-md">لم نتمكن من العثور على أي أنشطة في هذه الفئة في الوقت الحالي. تحقق مرة أخرى لاحقاً!</p>
                        <a href="{{ route(app()->getLocale() . '.home') }}"
                           class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 px-8 rounded-full transition-all hover:shadow-lg hover:shadow-primary-500/30">
                            العودة للرئيسية
                            <i class="fa-solid fa-arrow-left text-sm"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Scroll to Top Button --}}
    <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
       id="scrollTopBtn"
       class="fixed bottom-6 left-6 bg-dark hover:bg-primary-600 text-white w-12 h-12 rounded-full flex items-center justify-center shadow-lg transition-all duration-300 opacity-0 translate-y-10 z-50">
        <i class="fa-solid fa-arrow-up"></i>
    </button>

    {{-- Scripts --}}
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            offset: 50,
            once: true,
            easing: 'ease-out-cubic'
        });

        const scrollTopBtn = document.getElementById('scrollTopBtn');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                scrollTopBtn.classList.remove('opacity-0', 'translate-y-10');
                scrollTopBtn.classList.add('opacity-100', 'translate-y-0');
            } else {
                scrollTopBtn.classList.add('opacity-0', 'translate-y-10');
                scrollTopBtn.classList.remove('opacity-100', 'translate-y-0');
            }
        });
    </script>
</body>
</html>

