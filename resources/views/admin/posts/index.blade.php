@extends('layouts.admin')

@section('page_title', 'إدارة المقالات')
@section('breadcrumb', 'المنشورات / كل المقالات')

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

    .admin-index-container {
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

    .btn-add {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #001c3d;
        color: #ffffff;
        border: none;
        padding: 12px 24px;
        border-radius: 50px;
        font-family:  sans-serif;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 28, 61, 0.25);
        text-decoration: none;
    }

    .btn-add:hover {
        background: #002d62;
        box-shadow: 0 6px 20px rgba(0, 28, 61, 0.4);
        transform: translateY(-2px);
    }

    .card-table {
        background: #ffffff;
        border-radius: 24px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03), 0 1px 3px rgba(0, 0, 0, 0.02);
        border: 1px solid #f1f5f9;
        overflow: hidden;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
    }

    thead {
        background: #f8fafc;
    }

    th {
        padding: 16px 20px;
        text-align: right;
        font-weight: 600;
        color: #475569;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #e2e8f0;
    }

    tbody tr {
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.2s ease;
    }

    tbody tr:hover {
        background: #f8fafc;
    }

    td {
        padding: 16px 20px;
        color: #334155;
        vertical-align: middle;
    }

    .post-title {
        font-weight: 600;
        color: #0f172a;
        font-size: 14px;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 50px;
        font-size: 12px;
        font-weight: 700;
    }

    .badge-published {
        background: #ecfdf5;
        color: #065f46;
    }

    .badge-draft {
        background: #fffbeb;
        color: #92400e;
    }

    .badge-hidden {
        background: #fef2f2;
        color: #991b1b;
    }

    .actions {
        display: flex;
        gap: 8px;
    }

    .btn-edit {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #0f172a;
        color: #ffffff;
        padding: 8px 16px;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s ease;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .btn-edit:hover {
        background: #1e293b;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }

    .pagination {
        margin-top: 32px;
        display: flex;
        justify-content: center;
    }

    .pagination nav {
        display: flex;
        gap: 6px;
    }

    .pagination .page-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 40px;
        height: 40px;
        padding: 0 12px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        color: #334155;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .pagination .page-link:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
    }

    .pagination .active .page-link {
        background: #001c3d;
        border-color: #001c3d;
        color: #ffffff;
        font-weight: 700;
    }

    .pagination .disabled .page-link {
        color: #94a3b8;
        pointer-events: none;
        background: #f8fafc;
    }
</style>

<div class="admin-index-container">
    <div class="page-header">
        <div class="header-text">
            <h2>كل المقالات</h2>
            <p>عرض وتعديل المقالات في النظام.</p>
        </div>
        <a href="{{ route('posts.create') }}" class="btn-add">
            <i class="fa-solid fa-plus"></i>
            إضافة مقال
        </a>
    </div>

    <div class="card-table">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>العنوان</th>
                        <th>الفئة</th>
                        <th>الحالة</th>
                        <th>تاريخ النشر</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($posts as $post)
                        <tr>
                            <td><span class="post-title">{{ $post->title }}</span></td>
                            <td>{{ $post->category->name ?? 'بدون فئة' }}</td>
                            <td>
                                @if($post->is_hidden)
                                    <span class="badge badge-hidden">معلق</span>
                                @elseif($post->is_published)
                                    <span class="badge badge-published">منشور</span>
                                @else
                                    <span class="badge badge-draft">مسودة</span>
                                @endif
                            </td>
                            <td>{{ $post->published_at?->format('Y-m-d') ?? '—' }}</td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('admin.posts.edit', $post) }}" class="btn-edit">
                                        <i class="fa-solid fa-pen"></i>
                                        تعديل
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if($posts->hasPages())
        <div class="pagination">
            {{ $posts->links() }}
        </div>
    @endif
</div>
@endsection