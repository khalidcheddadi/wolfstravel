@extends('layouts.admin')

@section('content')

<div class="page-header anim-fade-up">
    <div class="page-header-left">
        <div class="breadcrumb-nav">
            <a href="{{ route('admin.dashboard') }}" class="breadcrumb-link">Inicio</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('admin.listings.index') }}" class="breadcrumb-link">Revisar actividades</a>
            <span class="breadcrumb-separator">/</span>
            <span class="breadcrumb-current">{{ $listing->title }}</span>
        </div>
        <h1 class="page-heading">Revisar actividad</h1>
    </div>
    <div class="page-header-right">
        <a href="{{ route('admin.listings.index') }}" class="back-btn">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Volver a la lista</span>
        </a>
        <a href="#" class="user-menu-trigger">
            <div class="user-menu-details">
                <div class="user-menu-displayname">{{ auth()->user()->name }}</div>
                <div class="user-menu-rolename">Administrador del sistema</div>
            </div>
            <div class="user-menu-avatar-circle">
                {{ Str::upper(Str::substr(auth()->user()->name, 0, 1)) }}
            </div>
        </a>
    </div>
</div>

<div class="review-grid">

    <div class="review-main-column">

        <div class="info-card anim-fade-up anim-delay-1">
            <div class="info-card-header">
                <div class="info-card-icon info-card-icon-primary">
                    <i class="fa-solid fa-clipboard-list"></i>
                </div>
                <h2 class="info-card-title">Información de la actividad</h2>
            </div>
            <div class="info-card-body">
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Título</span>
                        <span class="info-value">{{ $listing->title }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Tipo</span>
                        <span class="info-value">{{ $listing->type?->name ?? 'No especificado' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Ciudad</span>
                        <span class="info-value">{{ $listing->city?->name ?? 'No especificada' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">País</span>
                        <span class="info-value">{{ $listing->country?->name ?? 'No especificado' }}</span>
                    </div>
                    <div class="info-item info-item-full">
                        <span class="info-label">Dirección completa</span>
                        <span class="info-value">{{ $listing->address ?? 'No especificada' }}</span>
                    </div>
                    <div class="info-item info-item-full">
                        <span class="info-label">Descripción breve</span>
                        <p class="info-text">{{ $listing->short_description ?? 'Sin descripción breve' }}</p>
                    </div>
                    <div class="info-item info-item-full">
                        <span class="info-label">Descripción detallada</span>
                        <div class="info-description-box">
                            {!! nl2br(e($listing->description)) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($listing->getMedia('images')->count())
        <div class="info-card anim-fade-up anim-delay-2">
            <div class="info-card-header">
                <div class="info-card-icon info-card-icon-image">
                    <i class="fa-solid fa-images"></i>
                </div>
                <h2 class="info-card-title">Imágenes</h2>
                <span class="info-card-badge">{{ $listing->getMedia('images')->count() }} imágenes</span>
            </div>
            <div class="info-card-body">
                <div class="images-grid">
                    @foreach($listing->getMedia('images') as $image)
                        @php
                            $signedUrl = URL::temporarySignedRoute(
                                'media.download',
                                now()->addMinutes(30),
                                [
                                    'media' => $image->id,
                                    'conversion' => 'medium'
                                ]
                            );
                        @endphp
                        <div class="image-card">
                            <div class="image-card-inner">
                                <img src="{{ $signedUrl }}"
                                     class="image-card-img"
                                     alt="{{ $listing->title }} - imagen"
                                     loading="lazy">
                                <div class="image-card-overlay">
                                    <a href="{{ $signedUrl }}"
                                       target="_blank"
                                       class="image-zoom-btn"
                                       title="Ver imagen a tamaño completo">
                                        <i class="fa-solid fa-magnifying-glass-plus"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <div class="two-col-grid anim-fade-up anim-delay-3">
            @if($listing->categories->count())
            <div class="info-card">
                <div class="info-card-header">
                    <div class="info-card-icon info-card-icon-category">
                        <i class="fa-solid fa-folder-tree"></i>
                    </div>
                    <h2 class="info-card-title">Categorías</h2>
                </div>
                <div class="info-card-body">
                    <div class="tags-container">
                        @foreach($listing->categories as $category)
                            <span class="tag tag-category">{{ $category->name }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            @if($listing->features->count())
            <div class="info-card">
                <div class="info-card-header">
                    <div class="info-card-icon info-card-icon-feature">
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <h2 class="info-card-title">Características</h2>
                </div>
                <div class="info-card-body">
                    <div class="tags-container">
                        @foreach($listing->features as $feature)
                            <span class="tag tag-feature">{{ $feature->name }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="info-card anim-fade-up anim-delay-3">
            <div class="info-card-header">
                <div class="info-card-icon info-card-icon-business">
                    <i class="fa-solid fa-building"></i>
                </div>
                <h2 class="info-card-title">Propietario del negocio</h2>
            </div>
            <div class="info-card-body">
                <div class="business-details-grid">
                    <div class="business-detail-item">
                        <div class="business-detail-icon">
                            <i class="fa-solid fa-shop"></i>
                        </div>
                        <div class="business-detail-content">
                            <span class="business-detail-label">Negocio</span>
                            <span class="business-detail-value">{{ $listing->business?->business_name ?? 'No especificado' }}</span>
                        </div>
                    </div>
                    <div class="business-detail-item">
                        <div class="business-detail-icon">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <div class="business-detail-content">
                            <span class="business-detail-label">Correo electrónico</span>
                            <span class="business-detail-value">{{ $listing->business?->email ?? 'No especificado' }}</span>
                        </div>
                    </div>
                    <div class="business-detail-item">
                        <div class="business-detail-icon">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <div class="business-detail-content">
                            <span class="business-detail-label">Teléfono</span>
                            <span class="business-detail-value">{{ $listing->business?->phone ?? 'No disponible' }}</span>
                        </div>
                    </div>
                    <div class="business-detail-item">
                        <div class="business-detail-icon">
                            <i class="fa-solid fa-user-tie"></i>
                        </div>
                        <div class="business-detail-content">
                            <span class="business-detail-label">Propietario</span>
                            <span class="business-detail-value">
                                {{ $listing->business?->owner?->first_name ?? '' }}
                                {{ $listing->business?->owner?->last_name ?? 'No especificado' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="review-side-column">
        <div class="actions-card anim-scale-in anim-delay-2">
            <div class="actions-card-header">
                <div class="actions-card-icon">
                    <i class="fa-solid fa-gears"></i>
                </div>
                <h2 class="actions-card-title">Acciones de revisión</h2>
            </div>

            <div class="actions-card-body">
                <div class="current-status-section">
                    <span class="current-status-label">Estado actual</span>
                    <span class="status-display status-display-{{ $listing->status }}">
                        @if($listing->status == 'submitted')
                            <i class="fa-solid fa-clock"></i> En revisión
                        @elseif($listing->status == 'under_review')
                            <i class="fa-solid fa-magnifying-glass"></i> Bajo revisión
                        @elseif($listing->status == 'published')
                            <i class="fa-solid fa-check-circle"></i> Publicado
                        @elseif($listing->status == 'draft')
                            <i class="fa-solid fa-file"></i> Borrador
                        @else
                            {{ ucfirst($listing->status) }}
                        @endif
                    </span>
                </div>

                <form action="{{ route('admin.listings.approve', $listing) }}" method="POST" class="action-form">
                    @csrf
                    <button type="submit" class="approve-btn">
                        <i class="fa-solid fa-check-circle"></i>
                        <span>Aprobar y publicar</span>
                    </button>
                    <p class="action-hint">Se publica directamente sin pago electrónico</p>
                </form>

                <div class="action-divider">
                    <span>o</span>
                </div>

                <form action="{{ route('admin.listings.reject', $listing) }}" method="POST" class="action-form" id="rejectForm">
                    @csrf
                    <div class="form-group">
                        <label for="rejectionReason" class="form-label">
                            <i class="fa-solid fa-pen-to-square"></i>
                            Motivo del rechazo
                        </label>
                        <textarea
                            name="reason"
                            id="rejectionReason"
                            class="form-textarea"
                            rows="4"
                            placeholder="Escribe el motivo del rechazo para enviarlo al propietario del negocio..."
                        ></textarea>
                        <div class="char-counter">
                            <span id="charCount">0</span> / 500
                        </div>
                    </div>
                    <button type="submit" class="reject-btn" id="rejectBtn">
                        <i class="fa-solid fa-xmark-circle"></i>
                        <span>Rechazar actividad</span>
                    </button>
                </form>

                {{-- 🔥 ========== BOTÓN PARA AÑADIR EVALUACIÓN ========== --}}
                <div class="action-divider">
                    <span>📊</span>
                </div>

                <a href="{{ route('admin.listings.rate.form', $listing) }}"
                   class="w-full bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-center block font-semibold transition duration-150 ease-in-out">
                    <i class="fa-solid fa-star"></i>
                    ✍️ Añadir valoración a esta actividad
                </a>
                <p class="action-hint">Solo los administradores pueden añadir valoraciones</p>

                <div class="extra-info-section">
                    <div class="extra-info-item">
                        <i class="fa-solid fa-calendar-plus"></i>
                        <div>
                            <span class="extra-info-label">Fecha de creación</span>
                            <span class="extra-info-value">{{ $listing->created_at->format('Y-m-d H:i') }}</span>
                        </div>
                    </div>
                    <div class="extra-info-item">
                        <i class="fa-solid fa-star"></i>
                        <div>
                            <span class="extra-info-label">Calificación</span>
                            <span class="extra-info-value">
                                @if($listing->average_rating)
                                    {{ number_format($listing->average_rating, 1) }} / 5.0
                                @else
                                    Sin calificaciones
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="extra-info-item">
                        <i class="fa-solid fa-eye"></i>
                        <div>
                            <span class="extra-info-label">Vistas</span>
                            <span class="extra-info-value">{{ $listing->views ?? 0 }} vistas</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.75rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .page-header-left {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
    }

    .breadcrumb-nav {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .breadcrumb-link {
        color: #778da9;
        text-decoration: none;
        transition: color 0.15s ease;
    }

    .breadcrumb-link:hover {
        color: #3b71a8;
    }

    .breadcrumb-separator {
        color: #cbd5e1;
        font-weight: 400;
    }

    .breadcrumb-current {
        color: #415a77;
    }

    .page-heading {
        font-size: 1.4rem;
        font-weight: 700;
        color: #0d1b2a;
        letter-spacing: -0.3px;
        margin: 0;
    }

    .page-header-right {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        padding: 0.55rem 1.2rem;
        border-radius: 30px;
        border: 1.5px solid #e2e8f0;
        background: #ffffff;
        color: #415a77;
        font-weight: 600;
        font-size: 0.82rem;
        text-decoration: none;
        transition: all 0.15s ease;
        white-space: nowrap;
    }

    .back-btn:hover {
        border-color: #3b71a8;
        color: #1e3a5f;
        background: #f4f8fc;
    }

    .user-menu-trigger {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.3rem 1rem 0.3rem 0.3rem;
        border-radius: 30px;
        border: 1.5px solid #e2e8f0;
        background: #ffffff;
        cursor: pointer;
        transition: all 0.15s ease;
        text-decoration: none;
    }

    .user-menu-trigger:hover {
        border-color: #3b71a8;
        background: #f4f8fc;
    }

    .user-menu-details {
        text-align: left;
    }

    .user-menu-displayname {
        font-size: 0.82rem;
        font-weight: 600;
        color: #0d1b2a;
        line-height: 1.2;
    }

    .user-menu-rolename {
        font-size: 0.68rem;
        color: #778da9;
        font-weight: 500;
    }

    .user-menu-avatar-circle {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #1e3a5f;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.8rem;
        flex-shrink: 0;
    }

    .review-grid {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 1.5rem;
        align-items: start;
    }

    .review-main-column {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .review-side-column {
        position: sticky;
        top: 1.5rem;
    }

    .info-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
    }

    .info-card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #edf2f7;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .info-card-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .info-card-icon-primary {
        background: #e8f1f8;
        color: #3b71a8;
    }

    .info-card-icon-image {
        background: #fce7f3;
        color: #db2777;
    }

    .info-card-icon-category {
        background: #eff6ff;
        color: #2563eb;
    }

    .info-card-icon-feature {
        background: #fef3c7;
        color: #d97706;
    }

    .info-card-icon-business {
        background: #f0fdf4;
        color: #059669;
    }

    .info-card-title {
        font-size: 1rem;
        font-weight: 700;
        color: #0d1b2a;
        margin: 0;
        flex: 1;
    }

    .info-card-badge {
        font-size: 0.72rem;
        font-weight: 600;
        color: #778da9;
        background: #f1f5f9;
        padding: 0.2rem 0.7rem;
        border-radius: 20px;
    }

    .info-card-body {
        padding: 1.5rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 0.3rem;
    }

    .info-item-full {
        grid-column: 1 / -1;
    }

    .info-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-value {
        font-size: 0.9rem;
        font-weight: 600;
        color: #0d1b2a;
    }

    .info-text {
        font-size: 0.88rem;
        color: #415a77;
        line-height: 1.7;
        margin: 0;
    }

    .info-description-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.25rem;
        font-size: 0.88rem;
        color: #334155;
        line-height: 1.8;
    }

    .images-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.85rem;
    }

    .image-card {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        aspect-ratio: 4 / 3;
    }

    .image-card-inner {
        width: 100%;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .image-card-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.3s ease;
    }

    .image-card:hover .image-card-img {
        transform: scale(1.05);
    }

    .image-card-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0);
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.25s ease;
    }

    .image-card:hover .image-card-overlay {
        background: rgba(0, 0, 0, 0.35);
    }

    .image-zoom-btn {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.95);
        color: #0d1b2a;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        font-size: 1rem;
        opacity: 0;
        transform: scale(0.8);
        transition: all 0.2s ease;
    }

    .image-card:hover .image-zoom-btn {
        opacity: 1;
        transform: scale(1);
    }

    .image-zoom-btn:hover {
        background: #ffffff;
        color: #1e3a5f;
    }

    .two-col-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.25rem;
    }

    .tags-container {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .tag {
        padding: 0.35rem 0.85rem;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 600;
        white-space: nowrap;
    }

    .tag-category {
        background: #eff6ff;
        color: #2563eb;
    }

    .tag-feature {
        background: #f1f5f9;
        color: #475569;
    }

    .business-details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .business-detail-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.75rem;
        background: #f8fafc;
        border-radius: 12px;
        border: 1px solid #edf2f7;
    }

    .business-detail-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        background: #e8f1f8;
        color: #3b71a8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
        flex-shrink: 0;
    }

    .business-detail-content {
        display: flex;
        flex-direction: column;
        gap: 0.15rem;
        min-width: 0;
    }

    .business-detail-label {
        font-size: 0.7rem;
        color: #94a3b8;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .business-detail-value {
        font-size: 0.85rem;
        font-weight: 600;
        color: #0d1b2a;
        word-break: break-word;
    }

    .actions-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
    }

    .actions-card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #edf2f7;
        display: flex;
        align-items: center;
        gap: 0.65rem;
    }

    .actions-card-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: #fef3c7;
        color: #d97706;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
    }

    .actions-card-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: #0d1b2a;
        margin: 0;
    }

    .actions-card-body {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .current-status-section {
        display: flex;
        flex-direction: column;
        gap: 0.4rem;
        padding: 1rem;
        background: #f8fafc;
        border-radius: 12px;
        border: 1px solid #edf2f7;
    }

    .current-status-label {
        font-size: 0.72rem;
        color: #94a3b8;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-display {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 700;
        width: fit-content;
    }

    .status-display i {
        font-size: 0.7rem;
    }

    .status-display-submitted {
        background: #fffbeb;
        color: #b45309;
    }

    .status-display-under_review {
        background: #eff6ff;
        color: #2563eb;
    }

    .status-display-published {
        background: #ecfdf5;
        color: #059669;
    }

    .status-display-draft {
        background: #f1f5f9;
        color: #475569;
    }

    .action-form {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .approve-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        padding: 0.85rem 1.5rem;
        background: #059669;
        color: #ffffff;
        border: none;
        border-radius: 12px;
        font-size: 0.9rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.15s ease;
        font-family: 'Tajawal', 'Inter', sans-serif;
    }

    .approve-btn:hover {
        background: #047857;
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(5, 150, 105, 0.25);
    }

    .approve-btn:active {
        transform: translateY(0);
    }

    .action-hint {
        font-size: 0.7rem;
        color: #94a3b8;
        text-align: center;
        margin: 0;
    }

    .action-divider {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #cbd5e1;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .action-divider::before,
    .action-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e2e8f0;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .form-label {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.8rem;
        font-weight: 600;
        color: #415a77;
    }

    .form-label i {
        color: #94a3b8;
        font-size: 0.75rem;
    }

    .form-textarea {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        font-size: 0.85rem;
        color: #0d1b2a;
        outline: none;
        resize: vertical;
        min-height: 100px;
        transition: all 0.15s ease;
        font-family: 'Tajawal', 'Inter', sans-serif;
        line-height: 1.6;
    }

    .form-textarea::placeholder {
        color: #94a3b8;
    }

    .form-textarea:focus {
        border-color: #3b71a8;
        box-shadow: 0 0 0 3px rgba(59, 113, 168, 0.08);
    }

    .char-counter {
        font-size: 0.7rem;
        color: #94a3b8;
        text-align: right;
    }

    .reject-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        width: 100%;
        padding: 0.85rem 1.5rem;
        background: #dc2626;
        color: #ffffff;
        border: none;
        border-radius: 12px;
        font-size: 0.9rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.15s ease;
        font-family: 'Tajawal', 'Inter', sans-serif;
    }

    .reject-btn:hover {
        background: #b91c1c;
        transform: translateY(-1px);
        box-shadow: 0 8px 20px rgba(220, 38, 38, 0.25);
    }

    .reject-btn:active {
        transform: translateY(0);
    }

    .extra-info-section {
        padding-top: 1rem;
        border-top: 1px solid #edf2f7;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .extra-info-item {
        display: flex;
        align-items: flex-start;
        gap: 0.65rem;
    }

    .extra-info-item i {
        font-size: 0.85rem;
        color: #94a3b8;
        margin-top: 0.1rem;
    }

    .extra-info-item > div {
        display: flex;
        flex-direction: column;
        gap: 0.1rem;
    }

    .extra-info-label {
        font-size: 0.7rem;
        color: #94a3b8;
        font-weight: 500;
    }

    .extra-info-value {
        font-size: 0.8rem;
        font-weight: 600;
        color: #415a77;
    }

    @media (max-width: 1200px) {
        .review-grid {
            grid-template-columns: 1fr 340px;
        }
    }

    @media (max-width: 1024px) {
        .review-grid {
            grid-template-columns: 1fr;
        }

        .review-side-column {
            position: static;
        }

        .images-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
        }

        .page-header-right {
            width: 100%;
            justify-content: space-between;
        }

        .user-menu-details {
            display: none;
        }

        .user-menu-trigger {
            padding: 0.3rem;
        }

        .info-grid {
            grid-template-columns: 1fr;
        }

        .two-col-grid {
            grid-template-columns: 1fr;
        }

        .business-details-grid {
            grid-template-columns: 1fr;
        }

        .images-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 480px) {
        .images-grid {
            grid-template-columns: 1fr 1fr;
            gap: 0.5rem;
        }
    }
</style>

<script>
    (function() {
        'use strict';

        const textarea = document.getElementById('rejectionReason');
        const charCount = document.getElementById('charCount');
        const maxChars = 500;

        if (textarea && charCount) {
            textarea.addEventListener('input', function() {
                const currentLength = this.value.length;
                charCount.textContent = currentLength;

                if (currentLength > maxChars) {
                    charCount.style.color = '#dc2626';
                    this.value = this.value.substring(0, maxChars);
                    charCount.textContent = maxChars;
                } else if (currentLength > maxChars * 0.8) {
                    charCount.style.color = '#f59e0b';
                } else {
                    charCount.style.color = '#94a3b8';
                }
            });
        }

        const rejectForm = document.getElementById('rejectForm');
        const rejectBtn = document.getElementById('rejectBtn');

        if (rejectForm && rejectBtn) {
            rejectForm.addEventListener('submit', function(e) {
                const reason = textarea ? textarea.value.trim() : '';

                if (!reason) {
                    e.preventDefault();

                    if (textarea) {
                        textarea.style.borderColor = '#dc2626';
                        textarea.focus();

                        setTimeout(function() {
                            textarea.style.borderColor = '#e2e8f0';
                        }, 2000);
                    }

                    alert('Por favor, escribe el motivo del rechazo antes de enviar el formulario.');
                    return;
                }

                const confirmed = confirm(
                    '¿Estás seguro de rechazar esta actividad?\n\n' +
                    'El motivo del rechazo se enviará al propietario del negocio.\n\n' +
                    'Motivo del rechazo: ' + reason
                );

                if (!confirmed) {
                    e.preventDefault();
                }
            });
        }

    })();
</script>

@endsection
