@extends('layouts.public')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">{{ __('messages.favorites_title') }}</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($favorites->isEmpty())
        <div class="bg-gray-100 p-8 text-center rounded">
            <p class="text-gray-600 text-lg">{{ __('messages.favorites_empty') }}</p>
            <a href="{{ route('home') }}" class="text-blue-600 hover:underline mt-2 inline-block">
                {{ __('messages.favorites_explore_link') }}
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($favorites as $listing)
                @include('public.partials.listing-card', ['listing' => $listing])
            @endforeach
        </div>
        <div class="mt-6">
            {{ $favorites->links() }}
        </div>
    @endif
</div>
@endsection
