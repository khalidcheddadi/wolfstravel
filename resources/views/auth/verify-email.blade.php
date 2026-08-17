<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirmar correo electrónico · Wolfstravel</title>

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon-180x180.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #001c3d;
            --primary-hover: #002d62;
            --accent: #ff4a5a;
            --text-dark: #222222;
            --text-gray: #444444;
            --text-muted: #888888;
            --border: #e5e5e5;
            --bg-light: #fbfbfb;
            --white: #ffffff;
            --error: #e74c3c;
            --success: #10b981;
            --info: #001c3d;
            --info-bg: #eef3f8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background: var(--white);
            -webkit-font-smoothing: antialiased;
            direction: ltr;
        }

        /* ========== LAYOUT ========== */
        .auth-layout {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        /* ========== IMAGE SIDE ========== */
        .auth-image {
            flex: 1.1;
            background: linear-gradient(rgba(0, 11, 24, 0.48), rgba(0, 11, 24, 0.58)),
                        url('https://images.unsplash.com/photo-1486312338219-ce68d2c6f44d?auto=format&fit=crop&w=1200&q=80') center/cover;
            position: relative;
            display: flex;
            align-items: flex-end;
            min-height: 100vh;
            padding: 4rem 3rem;
        }

        .auth-image-content {
            color: #fff;
            max-width: 500px;
        }

        .auth-image-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 74, 90, 0.2);
            backdrop-filter: blur(10px);
            padding: 0.5rem 1.2rem;
            border-radius: 40px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #fecaca;
            margin-bottom: 2rem;
            letter-spacing: 0.5px;
            border: 1px solid rgba(255, 74, 90, 0.3);
        }

        .auth-image-badge .dot {
            width: 8px;
            height: 8px;
            background: var(--accent);
            border-radius: 50%;
            animation: pulse-dot 2s infinite;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }

        .auth-image-title {
            font-size: 2.4rem;
            font-weight: 900;
            margin-bottom: 1rem;
            line-height: 1.25;
            letter-spacing: -0.5px;
        }

        .auth-image-desc {
            font-size: 1rem;
            color: rgba(255,255,255,0.8);
            line-height: 1.8;
            margin-bottom: 2.5rem;
            font-weight: 400;
            max-width: 420px;
        }

        .auth-image-illustration {
            display: flex;
            gap: 1rem;
        }

        .illustration-step {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255,255,255,0.08);
            padding: 0.75rem 1rem;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 500;
            color: rgba(255,255,255,0.9);
        }

        .illustration-step i {
            font-size: 1.1rem;
            color: #fca5a5;
        }

        /* ========== FORM SIDE ========== */
        .auth-form {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 2rem;
            background: var(--white);
            min-height: 100vh;
        }

        .auth-form-inner {
            width: 100%;
            max-width: 440px;
        }

        .auth-form-header {
            margin-bottom: 2.5rem;
            text-align: center;
        }

        .auth-form-brand {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 2rem;
            text-decoration: none;
        }

        .auth-form-brand span {
            font-size: 1.8rem;
            font-weight: 900;
            color: var(--primary);
            letter-spacing: -0.5px;
        }

        .auth-form-brand i {
            font-size: 1.6rem;
            color: var(--accent);
        }

        .auth-form-title {
            font-size: 1.7rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
            letter-spacing: -0.3px;
        }

        .auth-form-subtitle {
            color: var(--text-muted);
            font-size: 0.9rem;
            font-weight: 500;
            line-height: 1.7;
        }

        /* ========== EMAIL ICON ========== */
        .email-icon-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: var(--info-bg);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }

        .email-icon-circle i {
            font-size: 2rem;
            color: var(--primary);
        }

        /* ========== INFO BOX ========== */
        .info-box {
            background: #fef2f2;
            border-left: 3px solid var(--accent);
            padding: 1rem 1.25rem;
            border-radius: 8px 0 0 8px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 0.65rem;
        }

        .info-box i {
            color: var(--accent);
            font-size: 1.1rem;
            margin-top: 0.1rem;
            flex-shrink: 0;
        }

        .info-box p {
            font-size: 0.85rem;
            color: #7f1d1d;
            line-height: 1.7;
            margin: 0;
        }

        /* ========== ALERTS ========== */
        .alert {
            padding: 0.85rem 1rem;
            border-radius: 8px;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .alert-success {
            background: #ecfdf5;
            color: #065f46;
            border-left: 3px solid var(--success);
        }

        /* ========== BUTTONS ========== */
        .btn-row {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            margin-top: 0.5rem;
        }

        .btn-primary {
            width: 100%;
            height: 52px;
            background: var(--primary);
            color: #fff;
            border: none;
            border-radius: 40px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            font-family: 'Tajawal', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            letter-spacing: 0.2px;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
        }

        .btn-primary:active {
            transform: scale(0.98);
        }

        .btn-primary .spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2.5px solid rgba(255,255,255,0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }

        .btn-primary.loading .spinner {
            display: block;
        }

        .btn-primary.loading .btn-text {
            display: none;
        }

        .btn-primary.loading {
            pointer-events: none;
            opacity: 0.85;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .btn-ghost {
            width: 100%;
            height: 48px;
            background: transparent;
            color: var(--text-muted);
            border: 1px solid var(--border);
            border-radius: 40px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'Tajawal', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-ghost:hover {
            color: var(--accent);
            border-color: #fca5a5;
            background: #fef2f2;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 1024px) {
            .auth-image {
                flex: 0.8;
                padding: 3rem 2rem;
            }
            .auth-image-title {
                font-size: 2rem;
            }
            .auth-image-illustration {
                flex-direction: column;
            }
        }

        @media (max-width: 768px) {
            .auth-layout {
                flex-direction: column;
            }
            .auth-image {
                min-height: 280px;
                padding: 2.5rem 2rem;
                align-items: center;
                text-align: center;
            }
            .auth-image-content {
                text-align: center;
                display: flex;
                flex-direction: column;
                align-items: center;
            }
            .auth-image-desc {
                margin-left: auto;
                margin-right: auto;
                max-width: 100%;
            }
            .auth-form {
                min-height: auto;
                padding: 2.5rem 1.5rem;
            }
            .auth-form-title {
                font-size: 1.4rem;
            }
        }

        @media (max-width: 480px) {
            .auth-image {
                min-height: 220px;
                padding: 2rem 1.25rem;
            }
            .auth-image-title {
                font-size: 1.4rem;
            }
            .auth-form {
                padding: 2rem 1.25rem;
            }
        }
    </style>
</head>
<body>

    <div class="auth-layout">

        {{-- IMAGE SIDE --}}
        <div class="auth-image">
            <div class="auth-image-content">
                <div class="auth-image-badge">
                    <span class="dot"></span>
                    Verificación de correo
                </div>
                <h1 class="auth-image-title">Último paso</h1>
                <p class="auth-image-desc">
                    Solo necesitamos asegurarnos de que tu dirección de correo electrónico sea correcta. Revisa tu bandeja de entrada.
                </p>
                <div class="auth-image-illustration">
                    <div class="illustration-step">
                        <i class="fa-solid fa-envelope-circle-check"></i>
                        <span>Revisa tu correo</span>
                    </div>
                    <div class="illustration-step">
                        <i class="fa-solid fa-link"></i>
                        <span>Haz clic en el enlace</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- FORM SIDE --}}
        <div class="auth-form">
            <div class="auth-form-inner">

                <div class="auth-form-header">
                    <a href="{{ route('home') }}" class="auth-form-brand">
                        <i class="fa-solid fa-suitcase-rolling"></i>
                        <span>Wolfstravel</span>
                    </a>
                    <h2 class="auth-form-title">Confirmar correo electrónico</h2>
                    <p class="auth-form-subtitle">
                        ¡Gracias por registrarte! Por favor confirma tu correo electrónico para continuar.
                    </p>
                </div>

                {{-- Email Icon --}}
                <div class="email-icon-circle">
                    <i class="fa-solid fa-envelope-open-text"></i>
                </div>

                {{-- Info Box --}}
                <div class="info-box">
                    <i class="fa-solid fa-circle-info"></i>
                    <p>
                        Gracias por registrarte. Antes de comenzar, ¿podrías confirmar tu dirección de correo electrónico haciendo clic en el enlace que te acabamos de enviar? Si no recibiste el correo, con gusto te enviaremos otro.
                    </p>
                </div>

                {{-- Success Alert --}}
                @if(session('status') == 'verification-link-sent')
                    <div class="alert alert-success">
                        <i class="fa-solid fa-circle-check"></i>
                        Se ha enviado un nuevo enlace de confirmación a la dirección de correo electrónico que proporcionaste durante el registro.
                    </div>
                @endif

                {{-- Error Alert --}}
                @if(session('error'))
                    <div class="alert alert-error">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Buttons --}}
                <div class="btn-row">
                    <form method="POST" action="{{ route('verification.send') }}" id="resendForm">
                        @csrf
                        <button type="submit" class="btn-primary" id="resendBtn">
                            <span class="spinner"></span>
                            <span class="btn-text">
                                <i class="fa-solid fa-paper-plane"></i>
                                Reenviar enlace de confirmación
                            </span>
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn-ghost">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                            Cerrar sesión
                        </button>
                    </form>
                </div>

            </div>
        </div>

    </div>

    <script>
        const form = document.getElementById('resendForm');
        const resendBtn = document.getElementById('resendBtn');
        form?.addEventListener('submit', function() {
            if (form.checkValidity()) {
                resendBtn.classList.add('loading');
            }
        });
    </script>

</body>
</html>
