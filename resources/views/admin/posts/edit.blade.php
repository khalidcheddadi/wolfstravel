@extends('layouts.admin')

@section('page_title', 'تعديل المقال')
@section('breadcrumb', 'المقالات / تعديل المقال')

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
        max-width: 1000px;
        margin: 0 auto;
        padding: 40px 24px;
        direction: rtl;
    }

    .page-header {
        margin-bottom: 32px;
    }

    .page-header h2 {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 4px;
        letter-spacing: -0.5px;
    }

    .page-header p {
        color: #64748b;
        font-size: 15px;
        font-weight: 500;
    }

    .form-card {
        background: #ffffff;
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03), 0 1px 3px rgba(0, 0, 0, 0.02);
        border: 1px solid #f1f5f9;
    }

    .form-grid {
        display: flex;
        flex-direction: column;
        gap: 24px;
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
        font-family: sans-serif;
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

    .checkbox-inline {
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

    .checkbox-inline input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: #2563eb;
        cursor: pointer;
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

    .file-upload-wrapper {
        position: relative;
        margin-top: 8px;
    }

    .file-upload-wrapper input[type="file"] {
        width: 100%;
        font-family: sans-serif;
        font-size: 14px;
        color: #475569;
    }

    .file-upload-wrapper input[type="file"]::file-selector-button {
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 50px;
        padding: 8px 20px;
        margin-left: 16px;
        font-family: sans-serif;
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
        font-family: sans-serif;
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
        font-family: sans-serif;
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
        <h2>تعديل المقال</h2>
        <p>عدّل العنوان، المحتوى، التصنيف، الحالة، والصور كما تريد.</p>
    </div>

    <div class="form-card">
        <form method="POST" action="{{ route('admin.posts.update', $post) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">العنوان</label>
                    <input type="text" name="title" value="{{ old('title', $post->title) }}" class="form-input" required>
                </div>

                <div class="form-group">
                    <label class="form-label">التصنيف</label>
                    <select name="category_id" class="form-select">
                        <option value="">بدون فئة</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">ملخص قصير</label>
                    <textarea name="excerpt" rows="3" class="form-textarea" style="min-height: 80px;">{{ old('excerpt', $post->excerpt) }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">المحتوى</label>
                    <textarea name="content" rows="12" class="form-textarea" required>{{ old('content', $post->content) }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">الحالة</label>
                    <label class="checkbox-inline">
                        <input type="checkbox" name="is_published" value="1" {{ old('is_published', $post->is_published) ? 'checked' : '' }}>
                        منشور
                    </label>
                </div>

                <div class="form-group">
                    <div class="moderation-box">
                        <div class="moderation-header">
                            <label for="is_hidden_checkbox">
                                <input type="checkbox" name="is_hidden" value="1" id="is_hidden_checkbox" {{ old('is_hidden', $post->is_hidden) ? 'checked' : '' }}>
                                إخفاء المقال من الموقع
                            </label>
                        </div>
                        <div class="form-group" style="margin-bottom: 16px;">
                            <label class="form-label">سبب الإخفاء (اختياري)</label>
                            <textarea name="hidden_reason" rows="2" class="form-textarea" placeholder="أدخل سبب الإخفاء إن وجد">{{ old('hidden_reason', $post->hidden_reason) }}</textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label">تعليق الإشراف / الملاحظة الداخلية</label>
                            <textarea name="moderation_comment" rows="3" class="form-textarea" placeholder="اكتب تعليق المراجع أو سبب الإخفاء الذي لا يظهر للمستخدمين">{{ old('moderation_comment', $post->moderation_comment) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">تاريخ النشر</label>
                    <input type="datetime-local" name="published_at" value="{{ old('published_at', $post->published_at?->format('Y-m-d\TH:i')) }}" class="form-input">
                </div>

                <div class="form-group">
                    <label class="form-label">صورة مميزة</label>
                    <div class="file-upload-wrapper">
                        <input type="file" name="featured_image" accept="image/*">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">إضافة صور جديدة</label>
                    <div class="file-upload-wrapper">
                        <input type="file" name="images[]" multiple accept="image/*">
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-save">
                    <i class="fa-solid fa-floppy-disk"></i>
                    حفظ التعديلات
                </button>
                <a href="{{ route('admin.posts.index') }}" class="btn-cancel">
                    <i class="fa-solid fa-xmark"></i>
                    إلغاء
                </a>
            </div>
        </form>
    </div>
</div>
@endsection