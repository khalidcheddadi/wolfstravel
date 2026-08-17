@extends('layouts.customer')

@section('content')
    <header class="top-bar anim-fade-up">
        <div class="page-title">التقيمات والآراء</div>
        <div class="top-actions">
            <a href="{{ route('customer.dashboard') }}" class="btn-ghost">
                <i class="fa-solid fa-arrow-left"></i> العودة للوحة التحكم
            </a>
        </div>
    </header>

    <div class="panel anim-fade-up anim-delay-1" style="margin-bottom: 2rem;">
        <div class="panel-header">
            <h3 class="panel-title">
                <i class="fa-solid fa-star" style="color:#f59e0b;margin-right:8px;"></i>
                أضف تقييمك للموقع
            </h3>
        </div>

        <form action="{{ route('customer.reviews.store') }}" method="POST" class="space-y-4" style="padding:1rem 0;">
            @csrf

            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1rem;">
                <div>
                    <label for="rating" style="display:block;margin-bottom:0.5rem;font-weight:600;color:var(--text-main);">التقييم</label>
                    <select id="rating" name="rating" required style="width:100%;padding:0.8rem 1rem;border:1px solid #e2e8f0;border-radius:12px;">
                        <option value="">اختر التقييم</option>
                        @for ($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}">{{ $i }} نجمة</option>
                        @endfor
                    </select>
                    @error('rating')
                        <small style="color:#dc2626;display:block;margin-top:0.35rem;">{{ $message }}</small>
                    @enderror
                </div>

                <div style="grid-column:1 / -1;">
                    <label for="title" style="display:block;margin-bottom:0.5rem;font-weight:600;color:var(--text-main);">العنوان (اختياري)</label>
                    <input id="title" type="text" name="title" maxlength="255" value="{{ old('title') }}" placeholder="مثال: تجربة ممتازة" style="width:100%;padding:0.8rem 1rem;border:1px solid #e2e8f0;border-radius:12px;">
                    @error('title')
                        <small style="color:#dc2626;display:block;margin-top:0.35rem;">{{ $message }}</small>
                    @enderror
                </div>

                <div style="grid-column:1 / -1;">
                    <label for="comment" style="display:block;margin-bottom:0.5rem;font-weight:600;color:var(--text-main);">التعليق</label>
                    <textarea id="comment" name="comment" rows="5" required placeholder="اكتب رأيك عن الموقع والخدمة..." style="width:100%;padding:0.8rem 1rem;border:1px solid #e2e8f0;border-radius:12px;resize:vertical;">{{ old('comment') }}</textarea>
                    @error('comment')
                        <small style="color:#dc2626;display:block;margin-top:0.35rem;">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            <div style="display:flex;justify-content:flex-end;margin-top:1rem;">
                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-paper-plane"></i> إضافة التقييم
                </button>
            </div>
        </form>
    </div>

    <div class="panel anim-fade-up anim-delay-2">
        <div class="panel-header">
            <h3 class="panel-title">
                <i class="fa-solid fa-clock-rotate-left" style="color:var(--primary);margin-right:8px;"></i>
                تقييماتي الأخيرة
            </h3>
        </div>

        @if($myReviews->isEmpty())
            <div class="empty-state">
                <div class="empty-icon"><i class="fa-solid fa-star-half-stroke"></i></div>
                <h4>لا توجد تقييمات بعد</h4>
                <p>ابدأ بإضافة تقييمك الأول عن تجربتك في الموقع.</p>
            </div>
        @else
            @foreach($myReviews as $review)
                <div class="list-row">
                    <div class="list-row-left" style="flex:1;">
                        <div class="list-info" style="flex:1;">
                            <h4 style="max-width:100%;">{{ $review->title ?: 'تقييم بدون عنوان' }}</h4>
                            <p style="margin-top:0.2rem;">
                                <span class="rating-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="fa-solid fa-star"></i>
                                        @else
                                            <i class="fa-regular fa-star"></i>
                                        @endif
                                    @endfor
                                </span>
                                <span style="font-weight:700;color:var(--text-main);margin-left:4px;">{{ $review->rating }}/5</span>
                            </p>
                            <p style="font-size:0.8rem;color:var(--text-muted);margin-top:0.3rem;line-height:1.6;">{{ $review->comment }}</p>
                            <p style="font-size:0.72rem;color:var(--text-muted);margin-top:0.5rem;">{{ $review->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            @endforeach

            @if($myReviews->hasPages())
                <div style="padding-top: 1rem;">{{ $myReviews->links() }}</div>
            @endif
        @endif
    </div>
@endsection
