<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: register.php');
    exit;
}

$userName = $_SESSION['user_name'] ?? 'Usuario';
$userEmail = $_SESSION['user_email'] ?? '';
?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel · Trav</title>

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
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Tajawal', sans-serif;
            background: #f4f5f7;
            color: var(--text-dark);
            -webkit-font-smoothing: antialiased;
            direction: ltr;
            min-height: 100vh;
        }

        .dashboard-nav {
            background: var(--white);
            border-bottom: 1px solid var(--border);
            padding: 0 2rem;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
            box-shadow: 0 1px 4px rgba(0,0,0,0.02);
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .nav-brand i {
            font-size: 1.5rem;
            color: var(--accent);
        }

        .nav-brand span {
            font-size: 1.7rem;
            font-weight: 900;
            color: var(--primary);
            letter-spacing: -0.5px;
        }

        .nav-user {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .nav-user-details {
            text-align: right;
        }

        .nav-user-details .name {
            font-weight: 700;
            font-size: 0.9rem;
            color: var(--text-dark);
            line-height: 1.2;
        }

        .nav-user-details .email {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .btn-logout {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: transparent;
            border: 2px solid var(--border);
            color: var(--text-gray);
            padding: 0.5rem 1.2rem;
            border-radius: 40px;
            font-size: 0.82rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            font-family: 'Tajawal', sans-serif;
        }

        .btn-logout:hover {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }

        .btn-logout i {
            font-size: 0.85rem;
        }

        .dashboard-main {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2.5rem 2rem;
        }

        .welcome-section {
            background: var(--white);
            border-radius: 16px;
            padding: 2.5rem 2.2rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
            border: 1px solid var(--border);
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1.5rem;
        }

        .welcome-text h1 {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 0.3rem;
            letter-spacing: -0.3px;
        }

        .welcome-text p {
            color: var(--text-muted);
            font-weight: 500;
            font-size: 0.95rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--white);
            border-radius: 14px;
            padding: 1.8rem 1.5rem;
            border: 1px solid var(--border);
            box-shadow: 0 4px 10px rgba(0,0,0,0.02);
            transition: transform 0.15s, box-shadow 0.15s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.04);
        }

        .stat-card .icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: rgba(0,28,61,0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 1.2rem;
            margin-bottom: 1rem;
        }

        .stat-card h3 {
            font-size: 1.6rem;
            font-weight: 900;
            color: var(--text-dark);
            margin-bottom: 0.2rem;
        }

        .stat-card p {
            font-size: 0.8rem;
            color: var(--text-muted);
            font-weight: 600;
        }

        .quick-section {
            background: var(--white);
            border-radius: 16px;
            padding: 2rem 2.2rem;
            border: 1px solid var(--border);
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        }

        .quick-section h2 {
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 1.2rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .quick-section h2 i {
            color: var(--accent);
        }

        .quick-links {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .quick-link {
            background: var(--bg-light);
            padding: 0.8rem 1.5rem;
            border-radius: 40px;
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--primary);
            text-decoration: none;
            border: 1px solid transparent;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .quick-link:hover {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
        }

        @media (max-width: 768px) {
            .dashboard-nav {
                padding: 0 1.2rem;
            }
            .nav-brand span {
                font-size: 1.4rem;
            }
            .nav-user-details .email {
                display: none;
            }
            .welcome-section {
                flex-direction: column;
                align-items: flex-start;
            }
        }

        @media (max-width: 480px) {
            .dashboard-main {
                padding: 1.5rem 1rem;
            }
            .welcome-text h1 {
                font-size: 1.4rem;
            }
            .btn-logout span {
                display: none;
            }
            .btn-logout i {
                margin: 0;
            }
        }
    </style>
</head>
<body>

    <nav class="dashboard-nav">
        <a href="dashboard.php" class="nav-brand">
            <i class="fa-solid fa-suitcase-rolling"></i>
            <span>Trav</span>
        </a>

        <div class="nav-user">
            <div class="nav-user-details">
                <div class="name"><?= htmlspecialchars($userName) ?></div>
                <div class="email"><?= htmlspecialchars($userEmail) ?></div>
            </div>
            <a href="dashboard.php?logout=1" class="btn-logout">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Salir</span>
            </a>
        </div>
    </nav>

    <main class="dashboard-main">

        <div class="welcome-section">
            <div class="welcome-text">
                <h1>¡Hola, <?= htmlspecialchars(explode(' ', $userName)[0]) ?>!</h1>
                <p>Nos alegra verte de nuevo. Explora todo lo que Trav tiene para ti.</p>
            </div>
            <a href="#" class="btn-logout" style="background:var(--primary);color:#fff;border-color:var(--primary);">
                <i class="fa-solid fa-compass"></i> Explorar destinos
            </a>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="icon"><i class="fa-solid fa-map-pin"></i></div>
                <h3>50k+</h3>
                <p>Viajeros felices</p>
            </div>
            <div class="stat-card">
                <div class="icon"><i class="fa-solid fa-ticket"></i></div>
                <h3>5k+</h3>
                <p>Actividades</p>
            </div>
            <div class="stat-card">
                <div class="icon"><i class="fa-solid fa-globe"></i></div>
                <h3>200+</h3>
                <p>Destinos globales</p>
            </div>
            <div class="stat-card">
                <div class="icon"><i class="fa-solid fa-star"></i></div>
                <h3>4.9</h3>
                <p>Calificación media</p>
            </div>
        </div>

        <div class="quick-section">
            <h2><i class="fa-solid fa-bolt"></i> Accesos rápidos</h2>
            <div class="quick-links">
                <a href="#" class="quick-link"><i class="fa-solid fa-search"></i> Buscar actividades</a>
                <a href="#" class="quick-link"><i class="fa-solid fa-heart"></i> Mis favoritos</a>
                <a href="#" class="quick-link"><i class="fa-solid fa-calendar-check"></i> Mis reservas</a>
                <a href="#" class="quick-link"><i class="fa-solid fa-user-gear"></i> Configuración</a>
                <a href="fetch-activities.php" class="quick-link"><i class="fa-solid fa-cloud-arrow-down"></i> جلب الأنشطة الجديدة</a>
            </div>
        </div>

    </main>

</body>
</html>