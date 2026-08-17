<div class="listing-card">
    <div class="card-image-wrapper">
        <a href="{{ route('listing.show', $listing->slug) }}" class="card-image-link">
            @php
                $imageUrl = $listing->getSignedImageUrl('medium', 60);
            @endphp
            @if($imageUrl)
                <img src="{{ $imageUrl }}"
                     alt="{{ $listing->title }}"
                     class="card-image"
                     loading="lazy">
            @else
                <div class="card-image-placeholder">
                    <span>{{ __('messages.listing_no_image') }}</span>
                </div>
            @endif
        </a>

        <div class="card-badge badge-left">
            <i class="fas {{ $listing->type?->icon ?? 'fa-tag' }}"></i>
            <span>{{ $listing->type?->name ?? __('messages.listing_default_activity') }}</span>
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
            <h3 class="card-title">{{ $listing->title }}</h3>
        </a>

        <div class="card-info">
            <div class="card-info-item">
                <i class="fas fa-map-marker-alt"></i>
                <span>{{ $listing->city?->name }}</span>
            </div>
            <div class="card-info-item">
                <i class="fas fa-phone"></i>
                <span dir="ltr">{{ $listing->business?->phone ?? '+212 00 00 00 00' }}</span>
            </div>
        </div>

        <div class="card-footer">
            <div class="card-actions">
                <button class="card-action-btn"
                        aria-label="{{ __('messages.search_gallery_label') }}"
                        title="{{ __('messages.search_gallery_label') }}">
                    <i class="far fa-image"></i>
                </button>

                @auth
                    @php
                        $isFavorited = auth()->user()->favorites()->where('listing_id', $listing->id)->exists();
                    @endphp
                    <form action="{{ route('favorite.toggle', $listing) }}" method="POST" class="favorite-form-inline">
                        @csrf
                        <button type="submit"
                                class="card-action-btn favorite-btn {{ $isFavorited ? 'active' : '' }}"
                                title="{{ $isFavorited ? __('messages.listing_remove_favorite') : __('messages.listing_add_favorite') }}">
                            <i class="{{ $isFavorited ? 'fas' : 'far' }} fa-heart"></i>
                        </button>
                    </form>
                @endauth
            </div>

            <div class="card-rating">
                <i class="fas fa-star"></i>
                <span>{{ number_format($listing->average_rating, 1) }}</span>
            </div>
        </div>
    </div>
</div>
