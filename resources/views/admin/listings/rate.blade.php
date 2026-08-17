@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">تقييم النشاط: {{ $listing->title }}</h1>

    <form action="{{ route('admin.listings.rate.store', $listing) }}" method="POST">
        @csrf

        <div class="mb-4">
            <label class="block font-medium">التقييم</label>
            <select name="rating" class="w-full border rounded p-2">
                <option value="5">★★★★★</option>
                <option value="4">★★★★</option>
                <option value="3">★★★</option>
                <option value="2">★★</option>
                <option value="1">★</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block font-medium">العنوان</label>
            <input type="text" name="title" class="w-full border rounded p-2">
        </div>

        <div class="mb-4">
            <label class="block font-medium">التعليق</label>
            <textarea name="body" rows="4" class="w-full border rounded p-2" required></textarea>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            إضافة التقييم
        </button>
    </form>
</div>
@endsection
