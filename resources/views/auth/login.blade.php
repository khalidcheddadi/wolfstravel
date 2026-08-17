<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesión · Wolfstravel</title>

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

        .auth-layout {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }

        .auth-image {
            flex: 1.1;
            background: linear-gradient(rgba(0, 11, 24, 0.5), rgba(0, 11, 24, 0.6)),
                        url('https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&w=1200&q=80') center/cover;
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
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            padding: 0.5rem 1.2rem;
            border-radius: 40px;
            font-size: 0.8rem;
            font-weight: 600;
            color: #fff;
            margin-bottom: 2rem;
            letter-spacing: 0.5px;
        }

        .auth-image-badge .dot {
            width: 8px;
            height: 8px;
            background: #22c55e;
            border-radius: 50%;
        }

        .auth-image-title {
            font-size: 2.6rem;
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

        .auth-image-quote {
            background: rgba(255,255,255,0.1);
            border-left: 3px solid var(--accent);
            padding: 1.2rem 1.5rem;
            border-radius: 8px 0 0 8px;
            max-width: 400px;
        }

        .auth-image-quote p {
            font-size: 0.95rem;
            color: rgba(255,255,255,0.9);
            font-style: italic;
            line-height: 1.7;
            margin-bottom: 1rem;
        }

        .auth-image-quote .quote-author {
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .auth-image-quote .quote-author img {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255,255,255,0.3);
        }

        .auth-image-quote .quote-author span {
            font-size: 0.85rem;
            font-weight: 600;
            color: #fff;
        }

        .auth-image-quote .quote-author small {
            display: block;
            font-size: 0.7rem;
            color: rgba(255,255,255,0.6);
            font-weight: 400;
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
        }

        .auth-form-subtitle a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 700;
            transition: color 0.15s;
        }

        .auth-form-subtitle a:hover {
            color: var(--primary-hover);
            text-decoration: underline;
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
            border-left: 3px solid #10b981;
        }

        .alert-error {
            background: #fef2f2;
            color: #991b1b;
            border-left: 3px solid #ef4444;
        }

        /* ========== FIELDS ========== */
        .field {
            margin-bottom: 1.15rem;
        }

        .field-label {
            display: block;
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.4rem;
        }

        .input-box {
            position: relative;
        }

        .input-box input {
            width: 100%;
            height: 50px;
            padding: 0 1rem 0 2.8rem;
            border: 2px solid var(--border);
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text-dark);
            background: white;
            outline: none;
            transition: border-color 0.2s, background 0.2s;
            font-family: 'Tajawal', sans-serif;
        }

        .input-box input:focus {
            border-color: var(--primary);
            background: var(--white);
        }

        .input-box input::placeholder {
            color: #bbb;
            font-weight: 400;
        }

        .input-box .field-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #bbb;
            font-size: 0.9rem;
            pointer-events: none;
            transition: color 0.2s;
        }

        .input-box input:focus ~ .field-icon {
            color: var(--primary);
        }

        .toggle-pass {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #bbb;
            font-size: 0.9rem;
            padding: 0.2rem;
            transition: color 0.2s;
        }

        .toggle-pass:hover {
            color: #888;
        }

        /* ========== ERROR ========== */
        .field-error {
            font-size: 0.73rem;
            color: var(--error);
            margin-top: 0.35rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }

        .input-box input.has-error {
            border-color: var(--error);
            background: #fff8f8;
        }

        /* ========== OPTIONS ROW ========== */
        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 0.25rem 0 0.5rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.82rem;
            color: var(--text-gray);
            cursor: pointer;
            font-weight: 500;
        }

        .remember-me input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--primary);
            cursor: pointer;
        }

        .forgot-link {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--primary);
            text-decoration: none;
            transition: color 0.15s;
        }

        .forgot-link:hover {
            color: var(--primary-hover);
            text-decoration: underline;
        }

        /* ========== SUBMIT ========== */
        .submit-btn {
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
            gap: 0.6rem;
            margin-top: 1.8rem;
            letter-spacing: 0.2px;
        }

        .submit-btn:hover {
            background: var(--primary-hover);
        }

        .submit-btn:active {
            transform: scale(0.98);
        }

        .submit-btn .spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2.5px solid rgba(255,255,255,0.3);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }

        .submit-btn.loading .spinner {
            display: block;
        }

        .submit-btn.loading .btn-text {
            display: none;
        }

        .submit-btn.loading {
            pointer-events: none;
            opacity: 0.85;
        }

        .submit-btn i {
            transition: transform 0.2s;
        }

        .submit-btn:hover i {
            transform: translateX(4px);
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
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
        }

        @media (max-width: 768px) {
            .auth-layout {
                flex-direction: column;
            }
            .auth-image {
                min-height: 320px;
                padding: 2.5rem 2rem;
                align-items: center;
                text-align: center;
            }
            .auth-image-content {
                text-align: center;
            }
            .auth-image-desc {
                margin-left: auto;
                margin-right: auto;
                max-width: 100%;
            }
            .auth-image-quote {
                margin: 0 auto;
                text-align: left;
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
                min-height: 250px;
                padding: 2rem 1.25rem;
            }
            .auth-image-title {
                font-size: 1.5rem;
            }
            .auth-form {
                padding: 2rem 1.25rem;
            }
            .form-options {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
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
                    Lo mejor de Europa 2025
                </div>
                <h1 class="auth-image-title">Bienvenido de nuevo</h1>
                <p class="auth-image-desc">
                    Inicia sesión para descubrir miles de destinos y actividades turísticas en todo el mundo.
                </p>
                <div class="auth-image-quote">
                    <p>"La mejor plataforma para reservar actividades turísticas. Fácil de usar y muy fiable."</p>
                    <div class="quote-author">
                        <img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=100&q=80" alt="Usuario">
                        <div>
                            <span>María García</span>
                            <small>Viajera frecuente</small>
                        </div>
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
                    <h2 class="auth-form-title">Iniciar sesión</h2>
                    <p class="auth-form-subtitle">
                        ¿No tienes cuenta? <a href="{{ route('register') }}">Crear una cuenta</a>
                    </p>
                </div>

                {{-- Session Status --}}
                @if(session('status'))
                    <div class="alert alert-success">
                        <i class="fa-solid fa-circle-check"></i>
                        {{ session('status') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-error">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" id="loginForm" novalidate>
                    @csrf

                    {{-- Correo electrónico --}}
                    <div class="field">
                        <label class="field-label" for="email">Correo electrónico</label>
                        <div class="input-box">
                            <i class="fa-solid fa-envelope field-icon"></i>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                class="{{ $errors->has('email') ? 'has-error' : '' }}"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="ejemplo@correo.com"
                            >
                        </div>
                        @error('email')
                            <div class="field-error">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Contraseña --}}
                    <div class="field">
                        <label class="field-label" for="password">Contraseña</label>
                        <div class="input-box">
                            <i class="fa-solid fa-lock field-icon"></i>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="{{ $errors->has('password') ? 'has-error' : '' }}"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••"
                            >
                            <button type="button" class="toggle-pass" onclick="togglePassword('password', this)" tabindex="-1">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="field-error">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Remember & Forgot --}}
                    <div class="form-options">
                        <label class="remember-me">
                            <input type="checkbox" name="remember">
                            Recordarme
                        </label>
                        @if(Route::has('password.request'))
                            <a class="forgot-link" href="{{ route('password.request') }}">
                                ¿Olvidaste tu contraseña?
                            </a>
                        @endif
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="submit-btn" id="submitBtn">
                        <span class="spinner"></span>
                        <span class="btn-text">Iniciar sesión</span>
                        <i class="fa-solid fa-arrow-right"></i>
                    </button>
                </form>

            </div>
        </div>

    </div>

    <script>
        window.togglePassword = function(id, btn) {
            const input = document.getElementById(id);
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        };

        const form = document.getElementById('loginForm');
        const submitBtn = document.getElementById('submitBtn');
        form?.addEventListener('submit', function() {
            if (form.checkValidity()) {
                submitBtn.classList.add('loading');
            }
        });
    </script>

</body>
</html>
