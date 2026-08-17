@extends('layouts.admin')

@section('content')
{{-- تضمين مكتبة AOS --}}
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    AOS.init({ duration: 700, once: true });
  });
</script>

<style>
    .blog-section { width: 100%; padding: 100px 20px; background-color: #fbfbfb; display: flex; justify-content: center; }
    .blog-container { max-width: 1320px; width: 100%; }
    .blog-header { text-align: center; margin-bottom: 50px; }
    .blog-sub-title { font-size: 13px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: #001c3d; display: inline-block; margin-bottom: 12px; }
    .blog-main-title { font-size: 42px; font-weight: 700; color: #111111; margin-bottom: 15px; }
    .blog-description { font-size: 15px; color: #666666; max-width: 600px; margin: 0 auto; }
    .blog-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 25px; }
    .blog-card { position: relative; height: 480px; border-radius: 20px; overflow: hidden; background-size: cover; background-position: center; display: flex; flex-direction: column; justify-content: flex-end; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); transition: transform 0.3s ease, box-shadow 0.3s ease; }
    .blog-card:hover { transform: translateY(-8px); box-shadow: 0 20px 40px rgba(0,0,0,0.15); }
    .blog-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(180deg, rgba(0,0,0,0) 40%, rgba(0,0,0,0.85) 100%); z-index: 1; }
    .blog-card-content { position: relative; z-index: 2; color: #ffffff; }
    .blog-category { display: inline-block; padding: 6px 18px; border: 1px solid rgba(255,255,255,0.6); border-radius: 30px; font-size: 13px; font-weight: 500; margin-bottom: 15px; backdrop-filter: blur(4px); background: rgba(255,255,255,0.1); }
    .blog-card-title { font-size: 20px; font-weight: 700; line-height: 1.4; margin-bottom: 15px; color: #ffffff; }
    .blog-author { font-size: 14px; color: rgba(255,255,255,0.85); display: flex; align-items: center; gap: 8px; }
    .blog-author i { font-size: 13px; }
    @media (max-width: 1200px) { .blog-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 768px) { .blog-grid { grid-template-columns: 1fr; } .blog-main-title { font-size: 32px; } .blog-card { height: 420px; } }
</style>

<section class="blog-section" style="direction: rtl; text-align: right;">
    <div class="blog-container">

        <div class="blog-header">
            <span class="blog-sub-title" data-aos="fade-down" data-aos-duration="600">نصائح وأفكار</span>
            <h2 class="blog-main-title" data-aos="fade-up" data-aos-delay="150" data-aos-duration="700">المدونة</h2>
            <p class="blog-description" data-aos="fade-up" data-aos-delay="300" data-aos-duration="700">آخر المقالات والأخبار</p>
        </div>

        <div class="blog-grid">
            @forelse($posts as $post)
                <a href="{{ route('posts.show', $post->slug) }}" style="text-decoration: none; color: inherit; display: block;">
                    <div class="blog-card"
                         style="background-image: url('{{ $post->getFeaturedImageUrl() ?: 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=600&q=80' }}');"
                         data-aos="fade-up" data-aos-delay="{{ 100 + $loop->index * 150 }}" data-aos-duration="700">
                        <div class="blog-overlay"></div>
                        <div class="blog-card-content">
                            <span class="blog-category">{{ $post->category->name ?? 'غير مصنف' }}</span>
                            <h3 class="blog-card-title">{{ $post->title }}</h3>
                            <div class="blog-author">
                                <i class="fa-regular fa-user"></i>
                                {{ $post->user->name ?? 'Admin' }}
                                @if($post->published_at)
                                    <span style="margin-right: 10px; font-size: 13px; opacity: 0.8;">
                                        <i class="fa-regular fa-calendar"></i> {{ $post->published_at->format('d M, Y') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <p style="grid-column:1/-1; text-align:center; padding:40px;">لا توجد منشورات حالياً.</p>
            @endforelse
        </div>

    </div>
</section>
@endsection
