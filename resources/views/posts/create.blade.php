@extends('layouts.admin')

@section('content')

<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    AOS.init({ duration: 700, once: true });
  });
</script>

<style>
    .create-post-wrapper {
        width: 100%;
        padding: 60px 20px 100px;
        background-color: #fbfbfb;
        display: flex;
        justify-content: center;
        min-height: 100vh;
    }
    .create-post-container {
        max-width: 800px;
        width: 100%;
    }

    .create-post-card {
        background: #ffffff;
        border-radius: 24px;
        padding: 50px 60px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.06);
        margin-top: 30px;
    }

    .page-title {
        font-size: 36px;
        font-weight: 700;
        color: #111111;
        margin-bottom: 10px;
        text-align: right;
    }
    .page-subtitle {
        font-size: 14px;
        color: #666666;
        margin-bottom: 30px;
        text-align: right;
    }

    .form-label {
        font-weight: 600;
        font-size: 15px;
        color: #001c3d;
        margin-bottom: 8px;
        display: block;
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        font-size: 16px;
        transition: border-color 0.3s, box-shadow 0.3s;
        background: #fafafa;
    }
    .form-control:focus {
        outline: none;
        border-color: #001c3d;
        box-shadow: 0 0 0 3px rgba(0,28,61,0.1);
        background: #fff;
    }
    textarea.form-control {
        resize: vertical;
    }
    select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23333' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: left 16px center;
        background-size: 18px;
        padding-left: 40px;
    }

    .file-input-wrapper {
        position: relative;
        display: inline-block;
        width: 100%;
    }
    .file-input-label {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #f0f4f8;
        padding: 14px 20px;
        border-radius: 12px;
        cursor: pointer;
        border: 2px dashed #c0c9d2;
        color: #001c3d;
        font-weight: 500;
        transition: all 0.3s;
    }
    .file-input-label:hover {
        border-color: #001c3d;
        background: #eef3ff;
    }
    .file-input-label i {
        font-size: 20px;
    }
    input[type="file"] {
        display: none;
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 16px;
        color: #333;
    }
    .checkbox-group input[type="checkbox"] {
        width: 20px;
        height: 20px;
        accent-color: #001c3d;
        margin: 0;
    }

    .btn-submit {
        background: #001c3d;
        color: #fff;
        padding: 14px 36px;
        border: none;
        border-radius: 40px;
        font-weight: 700;
        font-size: 16px;
        cursor: pointer;
        transition: background 0.3s, transform 0.2s;
        box-shadow: 0 8px 20px rgba(0,28,61,0.2);
        display: inline-block;
        margin-top: 10px;
    }
    .btn-submit:hover {
        background: #002b5c;
        transform: translateY(-2px);
    }

    .alert-danger {
        background: #fff5f5;
        border-left: 4px solid #dc3545;
        color: #721c24;
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 30px;
        font-size: 14px;
    }
    .alert-danger ul {
        margin: 0;
        padding-right: 20px;
    }

    @media (max-width: 768px) {
        .create-post-card {
            padding: 30px 25px;
        }
        .page-title {
            font-size: 28px;
        }
    }
</style>

<section class="create-post-wrapper" style="direction: rtl; text-align: right;">
    <div class="create-post-container">

        <h1 class="page-title" data-aos="fade-up" data-aos-duration="600">إضافة منشور جديد</h1>
        <p class="page-subtitle" data-aos="fade-up" data-aos-delay="100">قم بملء البيانات أدناه لنشر مقال مميز</p>

        @if ($errors->any())
            <div class="alert-danger" data-aos="fade-up" data-aos-delay="150">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="create-post-card" data-aos="fade-up" data-aos-delay="200" data-aos-duration="700">
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div style="margin-bottom: 24px;">
                    <label for="title" class="form-label">العنوان</label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
                </div>

                <div style="margin-bottom: 24px;">
                    <label for="category_id" class="form-label">التصنيف</label>
                    <select name="category_id" id="category_id" class="form-control">
                        <option value="">-- اختر تصنيفاً --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div style="margin-bottom: 24px;">
                    <label for="excerpt" class="form-label">ملخص قصير</label>
                    <textarea name="excerpt" id="excerpt" class="form-control" rows="3">{{ old('excerpt') }}</textarea>
                </div>

                <div style="margin-bottom: 24px;">
                    <label for="content" class="form-label">المحتوى</label>
                    <textarea name="content" id="content" class="form-control" rows="10" required>{{ old('content') }}</textarea>
                </div>

                <div style="margin-bottom: 24px;">
                    <label class="form-label">صور المنشور</label>
                    <div class="file-input-wrapper">
                        <label for="images" class="file-input-label">
                            <i class="fa-regular fa-images"></i> اختر صوراً متعددة للمنشور
                        </label>
                        <input type="file" name="images[]" id="images" multiple accept="image/*">
                    </div>
                </div>

                <div style="margin-bottom: 24px;">
                    <label class="form-label">صورة مميزة (احتياطية)</label>
                    <div class="file-input-wrapper">
                        <label for="featured_image" class="file-input-label">
                            <i class="fa-regular fa-image"></i> اختر صورة من جهازك
                        </label>
                        <input type="file" name="featured_image" id="featured_image">
                    </div>
                </div>

                <div style="margin-bottom: 24px;">
                    <div class="checkbox-group">
                        <input type="checkbox" name="is_published" id="is_published" value="1" {{ old('is_published') ? 'checked' : '' }}>
                        <label for="is_published">نشر فوراً</label>
                    </div>
                </div>

                <div style="margin-bottom: 30px;">
                    <label for="published_at" class="form-label">تاريخ النشر (اختياري)</label>
                    <input type="datetime-local" name="published_at" id="published_at" class="form-control" value="{{ old('published_at') }}">
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fa-regular fa-paper-plane" style="margin-left: 8px;"></i> حفظ المنشور
                </button>
            </form>
        </div>

    </div>
</section>
@endsection
