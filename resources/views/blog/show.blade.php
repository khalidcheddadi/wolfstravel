
@section('content')
<div class="container" style="padding: 80px 20px; max-width: 900px; margin: 0 auto;">

    @php
        $postImages = $post->getMedia('images');
    @endphp

    @if($postImages->count())
        @php
            $firstImage = $postImages->first();
        @endphp
        <div style="width:100%; height:400px; background-image: url('{{ $post->getMediaSignedUrl($firstImage, 'large') }}'); background-size: cover; background-position: center; border-radius: 20px; margin-bottom: 30px;"></div>
    @elseif($post->featured_image)
        <div style="width:100%; height:400px; background-image: url('{{ asset('storage/' . $post->featured_image) }}'); background-size: cover; background-position: center; border-radius: 20px; margin-bottom: 30px;"></div>
    @endif

    <span style="display: inline-block; background: #001c3d; color: #fff; padding: 6px 18px; border-radius: 30px; font-size: 14px; margin-bottom: 15px;">
        {{ $post->category->name ?? 'غير مصنف' }}
    </span>

    <h1 style="font-size: 42px; font-weight: 700; color: #111; line-height: 1.3; margin-bottom: 20px;">
        {{ $post->title }}
    </h1>

    <div style="display: flex; align-items: center; gap: 20px; font-size: 16px; color: #666; margin-bottom: 30px;">
        <span><i class="fa-regular fa-user"></i> {{ $post->user->name ?? 'Admin' }}</span>
        <span><i class="fa-regular fa-calendar"></i> {{ $post->published_at ? $post->published_at->format('d M, Y') : 'غير منشور' }}</span>
    </div>

    <div style="font-size: 18px; line-height: 1.8; color: #333;">
        {!! nl2br(e($post->content)) !!} {{-- أو استخدم $post->content مباشرة إذا كان مدعوماً من HTML --}}
    </div>

    <div style="margin-top: 50px;">
        <a href="{{ route('posts.index') }}" style="display: inline-block; padding: 12px 30px; background: #001c3d; color: #fff; border-radius: 30px; text-decoration: none; font-weight: 600;">← العودة إلى المدونة</a>
    </div>

</div>
@endsection
