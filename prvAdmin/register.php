<?php
session_start();
require_once 'db.php';

$errors = [];
$old = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirmation = $_POST['password_confirmation'] ?? '';
    $role = $_POST['role'] ?? 'customer';

    if (empty($name)) {
        $errors['name'] = 'El nombre completo es obligatorio.';
    }
    if (empty($email)) {
        $errors['email'] = 'El correo electrónico es obligatorio.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Correo electrónico no válido.';
    }
    if (empty($password)) {
        $errors['password'] = 'La contraseña es obligatoria.';
    } elseif (strlen($password) < 6) {
        $errors['password'] = 'La contraseña debe tener al menos 6 caracteres.';
    }
    if ($password !== $password_confirmation) {
        $errors['password_confirmation'] = 'Las contraseñas no coinciden.';
    }
    if (!in_array($role, ['customer', 'business_owner'])) {
        $errors['role'] = 'Tipo de cuenta no válido.';
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors['email'] = 'Este correo ya está registrado.';
        }
    }

    if (empty($errors)) {
        $pdo->beginTransaction();
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("
                INSERT INTO users 
                (name, email, password, email_verified_at, created_at, updated_at)
                VALUES (?, ?, ?, NOW(), NOW(), NOW())
            ");
            $stmt->execute([$name, $email, $hashedPassword]);
            $userId = $pdo->lastInsertId();

            $roleId = ($role === 'business_owner') ? 2 : 3;
            $stmt = $pdo->prepare("
                INSERT INTO model_has_roles (role_id, model_type, model_id)
                VALUES (?, 'App\\\\Models\\\\User', ?)
            ");
            $stmt->execute([$roleId, $userId]);

            if ($role === 'business_owner') {
                $businessSlug = 'negocio-' . $userId . '-' . bin2hex(random_bytes(4));
                $uuid = bin2hex(random_bytes(16)); 

                $stmt = $pdo->prepare("
                    INSERT INTO businesses 
                    (uuid, owner_id, business_name, slug, verified, status, created_at, updated_at)
                    VALUES (?, ?, ?, ?, 1, 'active', NOW(), NOW())
                ");
                $stmt->execute([$uuid, $userId, 'Mi Negocio', $businessSlug]);
            }

            $pdo->commit();

            $_SESSION['user_id'] = $userId;
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;

            header('Location: dashboard.php');
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            $errors['general'] = 'Error al crear la cuenta: ' . $e->getMessage();
        }
    }

    $old = ['name' => $name, 'email' => $email, 'role' => $role];
}
?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crear cuenta · Trav</title>

    <link rel="icon" type="image/png" href="favicon.png">
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
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

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
            background: linear-gradient(rgba(0, 11, 24, 0.55), rgba(0, 11, 24, 0.65)),
                        url('https://images.unsplash.com/photo-1488646953014-85cb44e25828?auto=format&fit=crop&w=1200&q=80') center/cover;
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

        .auth-image-stats {
            display: flex;
            gap: 2.5rem;
        }

        .auth-stat h3 {
            font-size: 1.8rem;
            font-weight: 900;
            color: #fff;
            margin-bottom: 0.2rem;
        }

        .auth-stat p {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.7);
            font-weight: 500;
        }

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
            background: var(--bg-light);
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

        .role-options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.65rem;
        }

        .role-option {
            position: relative;
            cursor: pointer;
        }

        .role-option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .role-option-box {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.8rem 0.9rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--bg-light);
            transition: all 0.2s;
        }

        .role-option input:checked + .role-option-box {
            border-color: var(--primary);
            background: #f0f4f8;
        }

        .role-option-box:hover {
            background: #f0f0f0;
        }

        .role-option input:checked + .role-option-box:hover {
            background: #f0f4f8;
        }

        .role-option-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #e8e8e8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            color: #888;
            flex-shrink: 0;
            transition: all 0.2s;
        }

        .role-option input:checked + .role-option-box .role-option-icon {
            background: var(--primary);
            color: #fff;
        }

        .role-option-text {
            font-size: 0.83rem;
            font-weight: 700;
            color: var(--text-dark);
            line-height: 1.3;
        }

        .role-option-sub {
            font-size: 0.68rem;
            color: var(--text-muted);
            font-weight: 500;
            display: block;
        }

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
            gap: 0.5rem;
            margin-top: 2rem;
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

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

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
                min-height: 350px;
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
            .auth-image-stats {
                justify-content: center;
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
                min-height: 280px;
                padding: 2rem 1.25rem;
            }
            .auth-image-title {
                font-size: 1.6rem;
            }
            .auth-image-stats {
                gap: 1.5rem;
            }
            .auth-stat h3 {
                font-size: 1.4rem;
            }
            .auth-form {
                padding: 2rem 1.25rem;
            }
            .role-options {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <div class="auth-layout">

        <div class="auth-image">
            <div class="auth-image-content">
                <div class="auth-image-badge">
                    <span class="dot"></span>
                    Lo mejor de Europa 2025
                </div>
                <h1 class="auth-image-title">Descubre el mundo<br>con Trav</h1>
                <p class="auth-image-desc">
                    Destinos increíbles, actividades inolvidables y experiencias únicas te esperan. Únete a más de 50.000 viajeros que confían en nosotros.
                </p>
                <div class="auth-image-stats">
                    <div class="auth-stat">
                        <h3>50k+</h3>
                        <p>Viajeros felices</p>
                    </div>
                    <div class="auth-stat">
                        <h3>5k+</h3>
                        <p>Actividades</p>
                    </div>
                    <div class="auth-stat">
                        <h3>200+</h3>
                        <p>Destinos globales</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="auth-form">
            <div class="auth-form-inner">

                <div class="auth-form-header">
                    <a href="index.php" class="auth-form-brand">
                        <i class="fa-solid fa-suitcase-rolling"></i>
                        <span>Trav</span>
                    </a>
                    <h2 class="auth-form-title">Crear cuenta nueva</h2>
                    <p class="auth-form-subtitle">
                        ¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a>
                    </p>
                </div>

                <?php if (isset($errors['general'])): ?>
                    <div class="field-error" style="margin-bottom: 1rem;">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        <?= htmlspecialchars($errors['general']) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="register.php" id="registerForm" novalidate>

                    <div class="field">
                        <label class="field-label" for="name">Nombre completo</label>
                        <div class="input-box">
                            <i class="fa-solid fa-user field-icon"></i>
                            <input
                                id="name"
                                type="text"
                                name="name"
                                class="<?= isset($errors['name']) ? 'has-error' : '' ?>"
                                value="<?= htmlspecialchars($old['name'] ?? '') ?>"
                                required
                                autofocus
                                autocomplete="name"
                                placeholder="Juan Pérez"
                            >
                        </div>
                        <?php if (isset($errors['name'])): ?>
                            <div class="field-error">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                <?= htmlspecialchars($errors['name']) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="field">
                        <label class="field-label" for="email">Correo electrónico</label>
                        <div class="input-box">
                            <i class="fa-solid fa-envelope field-icon"></i>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                class="<?= isset($errors['email']) ? 'has-error' : '' ?>"
                                value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                                required
                                autocomplete="username"
                                placeholder="ejemplo@correo.com"
                            >
                        </div>
                        <?php if (isset($errors['email'])): ?>
                            <div class="field-error">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                <?= htmlspecialchars($errors['email']) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="field">
                        <label class="field-label" for="password">Contraseña</label>
                        <div class="input-box">
                            <i class="fa-solid fa-lock field-icon"></i>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                class="<?= isset($errors['password']) ? 'has-error' : '' ?>"
                                required
                                autocomplete="new-password"
                                placeholder="••••••••"
                            >
                            <button type="button" class="toggle-pass" onclick="togglePassword('password', this)" tabindex="-1">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                        <?php if (isset($errors['password'])): ?>
                            <div class="field-error">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                <?= htmlspecialchars($errors['password']) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="field">
                        <label class="field-label" for="password_confirmation">Confirmar contraseña</label>
                        <div class="input-box">
                            <i class="fa-solid fa-lock field-icon"></i>
                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                class="<?= isset($errors['password_confirmation']) ? 'has-error' : '' ?>"
                                required
                                autocomplete="new-password"
                                placeholder="••••••••"
                            >
                            <button type="button" class="toggle-pass" onclick="togglePassword('password_confirmation', this)" tabindex="-1">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                        <?php if (isset($errors['password_confirmation'])): ?>
                            <div class="field-error">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                <?= htmlspecialchars($errors['password_confirmation']) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="field">
                        <label class="field-label">Tipo de cuenta</label>
                        <div class="role-options">
                            <label class="role-option">
                                <input type="radio" name="role" value="customer" <?= (($old['role'] ?? 'customer') === 'customer') ? 'checked' : '' ?>>
                                <div class="role-option-box">
                                    <div class="role-option-icon">
                                        <i class="fa-solid fa-user"></i>
                                    </div>
                                    <div>
                                        <span class="role-option-text">Cliente</span>
                                        <span class="role-option-sub">Explora y reserva actividades</span>
                                    </div>
                                </div>
                            </label>
                            <label class="role-option">
                                <input type="radio" name="role" value="business_owner" <?= (($old['role'] ?? '') === 'business_owner') ? 'checked' : '' ?>>
                                <div class="role-option-box">
                                    <div class="role-option-icon">
                                        <i class="fa-solid fa-briefcase"></i>
                                    </div>
                                    <div>
                                        <span class="role-option-text">Propietario de negocio</span>
                                        <span class="role-option-sub">Publica y gestiona tus actividades</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                        <?php if (isset($errors['role'])): ?>
                            <div class="field-error">
                                <i class="fa-solid fa-circle-exclamation"></i>
                                <?= htmlspecialchars($errors['role']) ?>
                            </div>
                        <?php endif; ?>
                    </div>


                    <button type="submit" class="submit-btn" id="submitBtn">
                        <span class="spinner"></span>
                        <span class="btn-text">Crear cuenta</span>
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

        const form = document.getElementById('registerForm');
        const submitBtn = document.getElementById('submitBtn');
        form?.addEventListener('submit', function() {
            if (form.checkValidity()) {
                submitBtn.classList.add('loading');
            }
        });
    </script>

</body>
</html>