<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->title }} | Blog</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon-180x180.png') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
            color: #1a1a2e;
            line-height: 1.7;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .container {
            max-width: 1320px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .site-header {
            background: #ffffff;
            box-shadow: 0 2px 15px rgba(0,0,0,0.04);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .logo {
            font-size: 26px;
            font-weight: 800;
            color: #001c3d;
            letter-spacing: -0.5px;
        }

        .nav-links {
            display: flex;
            gap: 30px;
            list-style: none;
            align-items: center;
        }

        .nav-links a {
            font-weight: 500;
            font-size: 15px;
            color: #444;
            transition: color 0.2s;
        }

        .nav-links a:hover {
            color: #001c3d;
        }

        .btn-outline {
            padding: 8px 22px;
            border: 1px solid #001c3d;
            border-radius: 30px;
            color: #001c3d;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-outline:hover {
            background: #001c3d;
            color: #fff;
        }

        .post-header {
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            margin: 30px 0 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.05);
        }

        .post-featured-image {
            width: 100%;
            max-height: 500px;
            object-fit: cover;
        }

        .post-header-content {
            padding: 30px 40px;
        }

        .post-category {
            display: inline-block;
            background: #eef3f8;
            padding: 4px 18px;
            border-radius: 50px;
            font-size: 13px;
            font-weight: 600;
            color: #001c3d;
            margin-bottom: 15px;
        }

        .post-title {
            font-size: 38px;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 15px;
            color: #001c3d;
        }

        .post-meta {
            display: flex;
            gap: 25px;
            color: #6b7280;
            font-size: 15px;
            flex-wrap: wrap;
        }

        .post-meta i {
            margin-left: 6px;
        }

        .two-column {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 40px;
            margin: 40px 0;
        }

        .main-content {
            background: #fff;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.05);
        }

        .post-body {
            font-size: 17px;
            color: #2d2d3f;
        }

        .post-body p {
            margin-bottom: 25px;
        }

        .post-body img {
            max-width: 100%;
            border-radius: 16px;
            margin: 30px 0;
            box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        }

        .gallery-section {
            margin-top: 50px;
            border-top: 1px solid #e5e7eb;
            padding-top: 40px;
        }

        .gallery-section h3 {
            font-size: 24px;
            font-weight: 700;
            color: #001c3d;
            margin-bottom: 25px;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .gallery-item {
            background: #f9fafb;
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            transition: 0.3s;
            border: 1px solid #f0f0f0;
        }

        .gallery-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.06);
        }

        .gallery-item i {
            font-size: 32px;
            color: #001c3d;
            margin-bottom: 10px;
            display: block;
        }

        .gallery-item h4 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .gallery-item .date {
            font-size: 13px;
            color: #6b7280;
        }

        .post-navigation {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 1px solid #e5e7eb;
        }

        .post-navigation a {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #001c3d;
            font-weight: 600;
            transition: 0.2s;
        }

        .post-navigation a:hover {
            color: #3b82f6;
        }

        .comments-section {
            margin-top: 50px;
            border-top: 1px solid #e5e7eb;
            padding-top: 40px;
        }

        .comments-section h3 {
            font-size: 24px;
            font-weight: 700;
            color: #001c3d;
            margin-bottom: 25px;
        }

        .comment-form input,
        .comment-form textarea {
            width: 100%;
            padding: 14px 20px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            font-size: 15px;
            background: #f9fafb;
            margin-bottom: 16px;
            transition: 0.2s;
        }

        .comment-form input:focus,
        .comment-form textarea:focus {
            outline: none;
            border-color: #001c3d;
            background: #fff;
        }

        .comment-form textarea {
            height: 140px;
            resize: vertical;
        }

        .comment-form .btn-submit {
            background: #001c3d;
            color: #fff;
            border: none;
            padding: 14px 40px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        .comment-form .btn-submit:hover {
            background: #003b6f;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,28,61,0.25);
        }

        .comment-form .required {
            color: #ef4444;
            margin-right: 4px;
        }

        .sidebar {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        .sidebar-widget {
            background: #fff;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.05);
        }

        .sidebar-widget h4 {
            font-size: 18px;
            font-weight: 700;
            color: #001c3d;
            margin-bottom: 18px;
            border-bottom: 2px solid #eef3f8;
            padding-bottom: 10px;
        }

        .search-form {
            display: flex;
            align-items: center;
            background: #f5f7fa;
            border-radius: 50px;
            padding: 5px 5px 5px 20px;
            border: 1px solid transparent;
            transition: 0.3s;
        }

        .search-form:focus-within {
            border-color: #001c3d;
            background: #fff;
        }

        .search-form input {
            flex: 1;
            border: none;
            background: transparent;
            padding: 12px 0;
            font-size: 15px;
            outline: none;
        }

        .search-form button {
            background: #001c3d;
            border: none;
            color: #fff;
            width: 46px;
            height: 46px;
            border-radius: 50%;
            cursor: pointer;
            transition: 0.3s;
        }

        .search-form button:hover {
            background: #003b6f;
        }

        .recent-post-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .recent-post-item:last-child {
            border-bottom: 0;
        }

        .recent-post-item i {
            color: #001c3d;
            font-size: 18px;
            width: 20px;
        }

        .recent-post-item .info {
            flex: 1;
        }

        .recent-post-item .title {
            font-weight: 600;
            font-size: 15px;
            color: #1a1a2e;
        }

        .recent-post-item .date {
            font-size: 13px;
            color: #6b7280;
        }

        .tags-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .tags-list a {
            background: #eef3f8;
            padding: 6px 18px;
            border-radius: 50px;
            font-size: 14px;
            color: #1a1a2e;
            transition: 0.2s;
            font-weight: 500;
        }

        .tags-list a:hover {
            background: #001c3d;
            color: #fff;
        }

        .ad-banner {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            border-radius: 16px;
            padding: 25px 20px;
            text-align: center;
            color: #1a1a2e;
            font-weight: 700;
            box-shadow: 0 8px 25px rgba(245, 158, 11, 0.3);
        }

        .ad-banner .title {
            font-size: 22px;
            line-height: 1.3;
            margin-bottom: 5px;
        }

        .ad-banner .sub {
            font-size: 14px;
            font-weight: 400;
            opacity: 0.8;
        }

        .related-posts {
            margin-top: 60px;
            padding-top: 40px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
        }

        .related-posts h3 {
            font-size: 24px;
            font-weight: 700;
            color: #001c3d;
            margin-bottom: 25px;
        }

        .related-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
        }

        .related-card {
            background: #fff;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.04);
            transition: 0.3s;
            border: 1px solid #f0f0f0;
        }

        .related-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.08);
        }

        .related-card .title {
            font-weight: 700;
            font-size: 18px;
            color: #001c3d;
            margin-bottom: 8px;
            display: block;
        }

        .related-card .meta {
            font-size: 14px;
            color: #6b7280;
        }

        .related-card .meta i {
            margin-left: 4px;
        }

        .site-footer {
            background: #001c3d;
            color: rgba(255,255,255,0.8);
            text-align: center;
            padding: 40px 20px;
            margin-top: 60px;
            font-size: 14px;
        }

        @media (max-width: 992px) {
            .two-column {
                grid-template-columns: 1fr;
            }
            .gallery-grid,
            .related-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .post-header-content {
                padding: 20px;
            }
            .post-title {
                font-size: 28px;
            }
            .main-content {
                padding: 20px;
            }
            .gallery-grid,
            .related-grid {
                grid-template-columns: 1fr;
            }
            .post-navigation {
                flex-direction: column;
                gap: 15px;
                align-items: center;
            }
            .header-inner {
                flex-direction: column;
                gap: 10px;
            }
            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
                gap: 15px;
            }
        }
    </style>
</head>
<body>

    <header class="site-header" style="background: linear-gradient(90deg, #001c3d, #003b6f);">
                @include('partials.header')
    </header>

    <main class="container">

        <article class="post-header">
            @php
                $postImages = $post->getMedia('images');
            @endphp
            @if($postImages->count())
                @php
                    $firstImage = $postImages->first();
                    $imageUrl = $post->getMediaSignedUrl($firstImage, 'large');
                @endphp
                <img src="{{ $imageUrl }}" alt="{{ $post->title }}" class="post-featured-image">
            @elseif($post->featured_image)
                <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="post-featured-image">
            @endif
            <div class="post-header-content">
                <span class="post-category">{{ $post->category->name ?? 'Sin categoría' }}</span>
                <h1 class="post-title">{{ $post->title }}</h1>
                <div class="post-meta">
                    <span><i class="fa-regular fa-user"></i> {{ $post->user->name ?? 'Admin' }}</span>
                    @if($post->published_at)
                        <span><i class="fa-regular fa-calendar"></i> {{ $post->published_at->format('d M, Y') }}</span>
                    @endif
                    <span><i class="fa-regular fa-clock"></i> {{ $post->created_at->diffForHumans() }}</span>
                </div>
            </div>
        </article>

        <div class="two-column">

            <div class="main-content">

                <div class="post-body">
                    {!! $post->content !!}
                </div>

                @if($postImages->count())
                <div class="gallery-section">
                    <h3><i class="fa-regular fa-images" style="margin-right:10px;"></i> Galería de imágenes</h3>
                    <div class="gallery-grid">
                        @foreach($postImages as $image)
                        <div class="gallery-item">
                            <img src="{{ $post->getMediaSignedUrl($image, 'thumb') }}" alt="{{ $post->title }}" style="width:100%;height:180px;object-fit:cover;border-radius:12px;margin:0 0 10px;">
                            <h4>{{ $post->title }}</h4>
                            <span class="date"><i class="fa-regular fa-calendar"></i> {{ $post->published_at?->format('d M, Y') ?? $post->created_at->format('d M, Y') }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="gallery-section">
                    <h3><i class="fa-regular fa-images" style="margin-right:10px;"></i> Galería de imágenes</h3>
                    <div class="gallery-grid">
                        <div class="gallery-item">
                            <i class="fa-regular fa-building"></i>
                            <h4>Best shopping mall at the main branch</h4>
                            <span class="date"><i class="fa-regular fa-calendar"></i> agosto 18, 2022</span>
                        </div>
                        <div class="gallery-item">
                            <i class="fa-regular fa-music"></i>
                            <h4>Music blares out from every cafeteria</h4>
                            <span class="date"><i class="fa-regular fa-calendar"></i> agosto 18, 2022</span>
                        </div>
                        <div class="gallery-item">
                            <i class="fa-regular fa-image"></i>
                            <h4>Image Gallery</h4>
                            <span class="date"><i class="fa-regular fa-calendar"></i> agosto 18, 2022</span>
                        </div>
                    </div>
                </div>
                @endif

                <div class="post-navigation">
                    @if(isset($previousPost) && $previousPost)
                        <a href="{{ route('posts.show', $previousPost->slug) }}">
                            <i class="fa-solid fa-arrow-left"></i> Artículo anterior
                        </a>
                    @else
                        <span></span>
                    @endif
                    @if(isset($nextPost) && $nextPost)
                        <a href="{{ route('posts.show', $nextPost->slug) }}">
                            Artículo siguiente <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    @else
                        <span></span>
                    @endif
                </div>

                <div class="comments-section">
                    <h3><i class="fa-regular fa-comment" style="margin-right:10px;"></i> Deja un comentario</h3>
                    <form class="comment-form" action="#" method="POST" onsubmit="alert('El sistema de comentarios está en desarrollo, se añadirá pronto'); return false;">
                        @csrf
                        <input type="text" name="name" placeholder="Nombre *" required>
                        <input type="email" name="email" placeholder="Correo electrónico *" required>
                        <textarea name="comment" placeholder="Comentario *" required></textarea>
                        <button type="submit" class="btn-submit">Publicar comentario</button>
                    </form>
                    <p style="margin-top:15px;font-size:14px;color:#6b7280;">
                        <i class="fa-regular fa-envelope"></i> Tu correo electrónico no será publicado.
                    </p>
                </div>

            </div> 

            <aside class="sidebar">

                <div class="sidebar-widget">
                    <h4><i class="fa-solid fa-magnifying-glass" style="margin-right:10px;"></i> Buscar</h4>
                    <form class="search-form" action="{{ route('posts.index') }}" method="GET">
                        <input type="text" name="search" placeholder="Buscar ..." value="{{ request('search') }}">
                        <button type="submit"><i class="fa-solid fa-arrow-right"></i></button>
                    </form>
                </div>

                <div class="sidebar-widget">
                    <h4><i class="fa-regular fa-newspaper" style="margin-right:10px;"></i> Últimas publicaciones</h4>
                    @php
                        $recentPosts = \App\Models\Post::visible()->latest('published_at')->limit(3)->get();
                    @endphp
                    @forelse($recentPosts as $recent)
                    <div class="recent-post-item">
                        <i class="fa-regular fa-file-lines"></i>
                        <div class="info">
                            <div class="title">{{ $recent->title }}</div>
                            <div class="date"><i class="fa-regular fa-calendar"></i> {{ $recent->published_at ? $recent->published_at->format('d M, Y') : '' }}</div>
                        </div>
                    </div>
                    @empty
                    <div class="recent-post-item">No hay publicaciones recientes.</div>
                    @endforelse
                </div>

                <div class="sidebar-widget">
                    <h4><i class="fa-solid fa-tags" style="margin-right:10px;"></i> Etiquetas populares</h4>
                    <div class="tags-list">
                        @php
                            $tags = ['Cafetería', 'Directorio', 'Hotel', 'Cocina', 'Listado', 'Museo', 'Restaurante', 'Viaje'];
                        @endphp
                        @foreach($tags as $tag)
                            <a href="{{ route('posts.index', ['tag' => $tag]) }}">{{ $tag }}</a>
                        @endforeach
                    </div>
                </div>

                <div class="sidebar-widget" style="padding:0; background:transparent; box-shadow:none;">
                    <div class="ad-banner">
                        <div class="title">🍔 SÚPER Delicioso <br> MENÚ DE COMIDA</div>
                        <div class="sub">SOLO ESTE FIN DE SEMANA</div>
                    </div>
                </div>

            </aside> 

        </div> 

        <section class="related-posts">
            <h3><i class="fa-regular fa-copy" style="margin-right:10px;"></i> Artículos relacionados</h3>
            @php
                $relatedPosts = \App\Models\Post::where('category_id', $post->category_id)
                                ->where('id', '!=', $post->id)
                                ->visible()
                                ->limit(3)
                                ->get();
            @endphp
            @if($relatedPosts->count())
            <div class="related-grid">
                @foreach($relatedPosts as $related)
                <div class="related-card">
                    <a href="{{ route('posts.show', $related->slug) }}" class="title">{{ $related->title }}</a>
                    <div class="meta">
                        <i class="fa-regular fa-location-dot"></i> por {{ $related->user->name ?? 'Admin' }}
                        <span style="margin:0 8px;">·</span>
                        <i class="fa-regular fa-calendar"></i> {{ $related->published_at ? $related->published_at->format('d M, Y') : '' }}
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <p style="color:#6b7280;">No hay artículos relacionados actualmente.</p>
            @endif
        </section>

    </main>

    <footer class="site-footer">
        <p>© {{ date('Y') }} Todos los derechos reservados. Diseñado con creatividad 🤍</p>
    </footer>

</body>
</html>
