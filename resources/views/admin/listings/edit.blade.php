{{-- resources/views/admin/listings/edit.blade.php --}}
@extends('layouts.admin')

@section('page_title', 'تعديل النشاط')
@section('breadcrumb', 'الأنشطة / تعديل النشاط')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap');

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: sans-serif;
        background: #f8fafc;
    }

    .admin-edit-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 24px;
        direction: rtl;
    }

    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 32px;
        flex-wrap: wrap;
        gap: 20px;
    }

    .header-text h2 {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 4px;
        letter-spacing: -0.5px;
    }

    .header-text p {
        color: #64748b;
        font-size: 15px;
        font-weight: 500;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 10px 20px;
        border-radius: 50px;
        color: #334155;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
        white-space: nowrap;
    }

    .back-btn:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
        color: #0f172a;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.04);
    }

    .back-btn i {
        font-size: 16px;
    }

    .form-card {
        background: #ffffff;
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03), 0 1px 3px rgba(0, 0, 0, 0.02);
        border: 1px solid #f1f5f9;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }

    .full-width {
        grid-column: span 2;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
        .full-width {
            grid-column: span 1;
        }
        .form-card {
            padding: 24px;
        }
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-label {
        font-size: 14px;
        font-weight: 600;
        color: #334155;
        margin-right: 4px;
    }

    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 16px;
        font-family:  sans-serif;
        font-size: 14px;
        color: #0f172a;
        transition: all 0.2s ease;
        outline: none;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
    }

    .form-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath d='M6 8L1 3h10z' fill='%2394a3b8'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: left 16px center;
        padding-left: 40px;
        cursor: pointer;
    }

    .form-textarea {
        resize: vertical;
        min-height: 100px;
        line-height: 1.6;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1), 0 1px 2px rgba(0,0,0,0.02);
    }

    .checkbox-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 12px;
        margin-top: 4px;
    }

    .checkbox-card {
        position: relative;
    }

    .checkbox-card input[type="checkbox"] {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
        z-index: 2;
    }

    .checkbox-card label {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 16px;
        font-size: 14px;
        font-weight: 500;
        color: #475569;
        cursor: pointer;
        transition: all 0.2s ease;
        user-select: none;
    }

    .checkbox-card label i {
        font-size: 16px;
        color: #94a3b8;
        width: 20px;
        text-align: center;
        transition: all 0.2s ease;
    }

    .checkbox-card input[type="checkbox"]:checked + label {
        background: #eff6ff;
        border-color: #3b82f6;
        color: #1e40af;
        font-weight: 600;
        box-shadow: 0 0 0 1px rgba(59, 130, 246, 0.2);
    }

    .checkbox-card input[type="checkbox"]:checked + label i {
        color: #2563eb;
    }

    .moderation-box {
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-radius: 16px;
        padding: 24px;
        margin-top: 8px;
    }

    .moderation-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
    }

    .moderation-header label {
        font-size: 15px;
        font-weight: 700;
        color: #92400e;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0;
    }

    .moderation-header input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: #d97706;
        cursor: pointer;
    }

    .images-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 16px;
        margin-top: 8px;
    }

    .image-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 12px;
        transition: all 0.3s ease;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }

    .image-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .image-card img {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: 10px;
        margin-bottom: 10px;
        background: #f1f5f9;
    }

    .image-card label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #64748b;
        font-weight: 500;
        cursor: pointer;
    }

    .image-card input[type="checkbox"] {
        accent-color: #ef4444;
        width: 16px;
        height: 16px;
    }

    .file-upload-wrapper {
        position: relative;
        margin-top: 8px;
    }

    .file-upload-wrapper input[type="file"] {
        width: 100%;
        font-family:  sans-serif;
        font-size: 14px;
        color: #475569;
    }

    .file-upload-wrapper input[type="file"]::file-selector-button {
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 50px;
        padding: 8px 20px;
        margin-left: 16px;
        font-family:  sans-serif;
        font-weight: 600;
        color: #1e293b;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .file-upload-wrapper input[type="file"]::file-selector-button:hover {
        background: #e2e8f0;
        border-color: #cbd5e1;
    }

    .form-actions {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-top: 40px;
        padding-top: 32px;
        border-top: 1px solid #f1f5f9;
    }

    .btn-save {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #001c3d;
        color: #ffffff;
        border: none;
        padding: 14px 32px;
        border-radius: 50px;
        font-family:  sans-serif;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 28, 61, 0.25);
        text-decoration: none;
    }

    .btn-save:hover {
        background: #002d62;
        box-shadow: 0 6px 20px rgba(0, 28, 61, 0.4);
        transform: translateY(-2px);
    }

    .btn-cancel {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        padding: 14px 32px;
        border-radius: 50px;
        font-family:  sans-serif;
        font-size: 16px;
        font-weight: 700;
        color: #475569;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn-cancel:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #0f172a;
    }
</style>

<div class="admin-edit-container">
    <div class="page-header">
        <div class="header-text">
            <h2>تعديل النشاط</h2>
            <p>تعديل النشاط من لوحة المسؤول بنفس المنطق المستخدم في صفحة صاحب النشاط.</p>
        </div>
        <a href="{{ route('admin.listings.index') }}" class="back-btn">
            <i class="fa-solid fa-arrow-right"></i>
            العودة إلى القائمة
        </a>
    </div>

    <div class="form-card">
        <form method="POST" action="{{ route('admin.listings.update', $listing) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- زر الحفظ والإلغاء في الأعلى -->
            <div class="form-actions">
                <button type="submit" class="btn-save">
                    <i class="fa-solid fa-floppy-disk"></i>
                    حفظ التغييرات
                </button>
                <a href="{{ route('admin.listings.index') }}" class="btn-cancel">
                    <i class="fa-solid fa-xmark"></i>
                    إلغاء
                </a>
            </div>

            <!-- اختيار الصور مباشرة تحت زر الحفظ -->
            <div class="form-group full-width">
                <label class="form-label">صور النشاط الحالية</label>
                @if($listing->getMedia('images')->count() > 0)
                    <div class="images-grid">
                        @foreach($listing->getMedia('images') as $image)
                            <div class="image-card">
                                <img src="{{ $listing->getMediaSignedUrl($image, 'thumb') }}" alt="{{ $listing->title }}">
                                <label>
                                    <input type="checkbox" name="remove_images[]" value="{{ $image->id }}">
                                    حذف الصورة
                                </label>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p style="color: #94a3b8; font-size: 14px; padding: 12px 0;">لا توجد صور مرفقة حالياً.</p>
                @endif
            </div>

            <div class="form-group full-width">
                <label class="form-label">إضافة صور جديدة</label>
                <div class="file-upload-wrapper">
                    <input type="file" name="images[]" multiple accept="image/*">
                </div>
            </div>

            <!-- باقي الحقول كما هي -->
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">نوع النشاط</label>
                    <select name="listing_type_id" class="form-select" required>
                        @foreach($listingTypes as $type)
                            <option value="{{ $type->id }}" {{ old('listing_type_id', $listing->listing_type_id) == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">العنوان</label>
                    <input type="text" name="title" value="{{ old('title', $listing->title) }}" class="form-input" required>
                </div>

                <div class="form-group full-width">
                    <label class="form-label">الوصف المختصر</label>
                    <textarea name="short_description" rows="2" class="form-textarea" style="min-height: 80px;">{{ old('short_description', $listing->short_description) }}</textarea>
                </div>

                <div class="form-group full-width">
                    <label class="form-label">الوصف الكامل</label>
                    <textarea name="description" rows="5" class="form-textarea" required>{{ old('description', $listing->description) }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">الدولة</label>
                    <select name="country_id" class="form-select" required>
                        @foreach($countries as $country)
                            <option value="{{ $country->id }}" {{ old('country_id', $listing->country_id) == $country->id ? 'selected' : '' }}>
                                {{ $country->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">المدينة</label>
                    <select name="city_id" class="form-select" required>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}" {{ old('city_id', $listing->city_id) == $city->id ? 'selected' : '' }}>
                                {{ $city->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group full-width">
                    <label class="form-label">العنوان التفصيلي</label>
                    <input type="text" name="address" value="{{ old('address', $listing->address) }}" class="form-input">
                </div>

                <div class="form-group">
                    <label class="form-label">Latitude</label>
                    <input type="number" step="0.0000001" name="latitude" value="{{ old('latitude', $listing->latitude) }}" class="form-input">
                </div>

                <div class="form-group">
                    <label class="form-label">Longitude</label>
                    <input type="number" step="0.0000001" name="longitude" value="{{ old('longitude', $listing->longitude) }}" class="form-input">
                </div>

                <div class="form-group full-width">
                    <label class="form-label">التصنيفات</label>
                    <div class="checkbox-grid">
                        @foreach($categories as $category)
                            <div class="checkbox-card">
                                <input type="checkbox" name="category_ids[]" value="{{ $category->id }}" id="cat_{{ $category->id }}" {{ in_array($category->id, $listing->categories->pluck('id')->toArray(), true) ? 'checked' : '' }}>
                                <label for="cat_{{ $category->id }}">
                                    <i class="fa-solid fa-tag"></i>
                                    <span>{{ $category->name }}</span>
                                </label>
                            </div>
                            @if($category->children)
                                @foreach($category->children as $child)
                                    <div class="checkbox-card" style="margin-right: 16px;">
                                        <input type="checkbox" name="category_ids[]" value="{{ $child->id }}" id="cat_{{ $child->id }}" {{ in_array($child->id, $listing->categories->pluck('id')->toArray(), true) ? 'checked' : '' }}>
                                        <label for="cat_{{ $child->id }}">
                                            <i class="fa-solid fa-circle-dot" style="font-size: 12px;"></i>
                                            <span>{{ $child->name }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="form-group full-width">
                    <label class="form-label">الميزات</label>
                    <div class="checkbox-grid">
                        @foreach($features as $feature)
                            <div class="checkbox-card">
                                <input type="checkbox" name="features[]" value="{{ $feature->id }}" id="feat_{{ $feature->id }}" {{ in_array($feature->id, $listing->features->pluck('id')->toArray(), true) ? 'checked' : '' }}>
                                <label for="feat_{{ $feature->id }}">
                                    <i class="fa-solid fa-check"></i>
                                    <span>{{ $feature->name }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="form-group full-width">
                    <div class="moderation-box">
                        <div class="moderation-header">
                            <label for="is_hidden_checkbox">
                                <input type="checkbox" name="is_hidden" value="1" id="is_hidden_checkbox" {{ old('is_hidden', $listing->is_hidden) ? 'checked' : '' }}>
                                إخفاء النشاط من الموقع بشكل كامل
                            </label>
                        </div>
                        <div class="form-group" style="margin-bottom: 16px;">
                            <label class="form-label">سبب الإخفاء (اختياري)</label>
                            <textarea name="hidden_reason" rows="2" class="form-textarea" placeholder="أدخل سبب الإخفاء إن وجد">{{ old('hidden_reason', $listing->hidden_reason) }}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">تعليق الإشراف / الملاحظة الداخلية</label>
                            <textarea name="moderation_comment" rows="3" class="form-textarea" placeholder="اكتب تعليق المراجع أو سبب الإخفاء الذي لا يظهر للمستخدمين">{{ old('moderation_comment', $listing->moderation_comment) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection