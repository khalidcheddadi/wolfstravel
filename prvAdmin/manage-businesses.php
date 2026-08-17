<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once 'db.php';

if (isset($_POST['update_phone']) && isset($_POST['business_id']) && isset($_POST['phone'])) {
    $businessId = (int)$_POST['business_id'];
    $phone = trim($_POST['phone']);
    
    try {
        $stmt = $pdo->prepare("UPDATE businesses SET phone = ? WHERE id = ?");
        $stmt->execute([$phone, $businessId]);
        $success = "تم تحديث رقم الهاتف بنجاح.";
    } catch (Exception $e) {
        $error = "حدث خطأ أثناء تحديث الهاتف: " . $e->getMessage();
    }
}

if (isset($_POST['delete_businesses']) && isset($_POST['business_ids'])) {
    $businessIds = array_map('intval', $_POST['business_ids']);
    $placeholders = implode(',', array_fill(0, count($businessIds), '?'));
    
    try {
        $pdo->beginTransaction();
        
        $stmt = $pdo->prepare("DELETE FROM listings WHERE business_id IN ($placeholders)");
        $stmt->execute($businessIds);
        
        $stmt = $pdo->prepare("DELETE FROM businesses WHERE id IN ($placeholders)");
        $stmt->execute($businessIds);
        
        $pdo->commit();
        $success = "تم حذف " . count($businessIds) . " منشأة وجميع الأنشطة المرتبطة بها بنجاح.";
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "حدث خطأ أثناء الحذف: " . $e->getMessage();
    }
}

$stmt = $pdo->prepare("
    SELECT 
        b.*,
        COUNT(l.id) AS listings_count,
        GROUP_CONCAT(l.title SEPARATOR '|||') AS listing_titles,
        GROUP_CONCAT(l.id SEPARATOR '|||') AS listing_ids,
        u.name AS owner_name,
        u.email AS owner_email,
        bt.name AS business_type_name,
        c.name AS city_name,
        co.name AS country_name
    FROM businesses b
    LEFT JOIN listings l ON l.business_id = b.id
    LEFT JOIN users u ON u.id = b.owner_id
    LEFT JOIN business_types bt ON bt.id = b.business_type_id
    LEFT JOIN cities c ON c.id = b.city_id
    LEFT JOIN countries co ON co.id = b.country_id
    WHERE b.deleted_at IS NULL
    GROUP BY b.id
    HAVING COUNT(l.id) > 0
    ORDER BY b.created_at DESC
");
$stmt->execute();
$businesses = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalBusinesses = count($businesses);
$totalListings = 0;
$activeBusinesses = 0;
$verifiedBusinesses = 0;

foreach ($businesses as $b) {
    $totalListings += $b['listings_count'];
    if ($b['status'] === 'active') $activeBusinesses++;
    if ($b['verified']) $verifiedBusinesses++;
}
?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestión de Empresas con Actividades · Trav</title>

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
            --accent-hover: #e63a4a;
            --success: #22c55e;
            --warning: #f59e0b;
            --text-dark: #222222;
            --text-gray: #444444;
            --text-muted: #888888;
            --border: #e5e5e5;
            --bg-light: #fbfbfb;
            --white: #ffffff;
            --shadow: 0 4px 20px rgba(0,0,0,0.06);
            --radius: 16px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Tajawal', sans-serif;
            background: #f4f6f9;
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

        .btn-back {
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

        .btn-back:hover {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }

        .btn-back i {
            font-size: 0.85rem;
        }

        .dashboard-main {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }

        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .page-header-left h1 {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .page-header-left h1 i {
            color: var(--accent);
        }

        .page-header-left p {
            color: var(--text-muted);
            font-weight: 500;
            margin-top: 0.2rem;
        }

        .page-header-actions {
            display: flex;
            gap: 0.8rem;
            flex-wrap: wrap;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--primary);
            color: #fff;
            padding: 0.7rem 1.5rem;
            border-radius: 40px;
            border: none;
            font-size: 0.85rem;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            font-family: 'Tajawal', sans-serif;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
        }

        .btn-primary i {
            font-size: 0.9rem;
        }

        .btn-success {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--success);
            color: #fff;
            padding: 0.7rem 1.5rem;
            border-radius: 40px;
            border: none;
            font-size: 0.85rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'Tajawal', sans-serif;
        }

        .btn-success:hover {
            background: #16a34a;
            transform: translateY(-1px);
        }

        .btn-success i {
            font-size: 0.9rem;
        }

        .btn-danger {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--accent);
            color: #fff;
            padding: 0.7rem 1.5rem;
            border-radius: 40px;
            border: none;
            font-size: 0.85rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'Tajawal', sans-serif;
        }

        .btn-danger:hover {
            background: var(--accent-hover);
            transform: translateY(-1px);
        }

        .btn-danger:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        .btn-danger i {
            font-size: 0.9rem;
        }

        .btn-outline {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: transparent;
            color: var(--text-gray);
            padding: 0.7rem 1.5rem;
            border-radius: 40px;
            border: 2px solid var(--border);
            font-size: 0.85rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'Tajawal', sans-serif;
        }

        .btn-outline:hover {
            background: var(--bg-light);
            border-color: var(--text-muted);
        }

        .btn-sm {
            padding: 0.3rem 0.8rem;
            font-size: 0.7rem;
            border-radius: 6px;
            border: none;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'Tajawal', sans-serif;
        }

        .btn-sm-success {
            background: var(--success);
            color: #fff;
        }

        .btn-sm-success:hover {
            background: #16a34a;
        }

        .btn-sm-danger {
            background: var(--accent);
            color: #fff;
        }

        .btn-sm-danger:hover {
            background: var(--accent-hover);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1.2rem;
            margin-bottom: 2.5rem;
        }

        .stat-card {
            background: var(--white);
            border-radius: var(--radius);
            padding: 1.5rem 1.2rem;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            transition: transform 0.15s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .stat-card .stat-number {
            font-size: 1.8rem;
            font-weight: 900;
            color: var(--primary);
        }

        .stat-card .stat-label {
            font-size: 0.78rem;
            color: var(--text-muted);
            font-weight: 600;
            margin-top: 0.2rem;
        }

        .stat-card .stat-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: rgba(0,28,61,0.08);
            color: var(--primary);
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        .alert {
            padding: 1rem 1.5rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .alert i {
            font-size: 1.2rem;
        }

        .toast-message {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: #001c3d;
            color: #fff;
            padding: 1rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            z-index: 9999;
            transform: translateY(100px);
            opacity: 0;
            transition: all 0.3s ease;
        }

        .toast-message.show {
            transform: translateY(0);
            opacity: 1;
        }

        .table-container {
            background: var(--white);
            border-radius: var(--radius);
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .table-toolbar {
            padding: 1rem 1.2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.8rem;
            border-bottom: 1px solid var(--border);
            background: #fafbfc;
        }

        .table-toolbar-left {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            flex-wrap: wrap;
        }

        .table-toolbar-left .selected-info {
            font-size: 0.85rem;
            color: var(--text-muted);
            font-weight: 600;
        }

        .table-toolbar-left .selected-info strong {
            color: var(--text-dark);
        }

        .checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.82rem;
            color: var(--text-gray);
            user-select: none;
        }

        .checkbox-wrapper input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--primary);
        }

        .table-responsive {
            overflow-x: auto;
            padding: 0 0.5rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
        }

        thead {
            background: #f8fafc;
            border-bottom: 2px solid var(--border);
        }

        thead th {
            padding: 1rem 1.2rem;
            text-align: left;
            font-weight: 700;
            color: var(--text-gray);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        thead th:first-child {
            width: 40px;
        }

        thead th input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--primary);
        }

        tbody tr {
            border-bottom: 1px solid var(--border);
            transition: background 0.15s;
        }

        tbody tr:hover {
            background: #fafbfc;
        }

        tbody tr.selected {
            background: #eff6ff;
        }

        tbody tr:last-child {
            border-bottom: none;
        }

        tbody td {
            padding: 0.9rem 1.2rem;
            vertical-align: middle;
        }

        tbody td:first-child {
            width: 40px;
        }

        tbody td input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--primary);
        }

        .business-name {
            font-weight: 700;
            color: var(--text-dark);
        }

        .business-email {
            color: var(--text-muted);
            font-size: 0.8rem;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.25rem 0.7rem;
            border-radius: 40px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .badge-active {
            background: #dcfce7;
            color: #166534;
        }

        .badge-inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-verified {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-unverified {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-has-activity {
            background: #dcfce7;
            color: #166534;
        }

        .listings-preview {
            display: flex;
            flex-direction: column;
            gap: 0.2rem;
            max-width: 200px;
        }

        .listing-item {
            font-size: 0.78rem;
            color: var(--text-gray);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .listing-item i {
            color: var(--text-muted);
            margin-right: 0.3rem;
            font-size: 0.65rem;
        }

        .no-listings {
            color: var(--text-muted);
            font-size: 0.78rem;
            font-style: italic;
        }

        .btn-delete {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: var(--accent);
            color: #fff;
            border: none;
            padding: 0.4rem 0.9rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'Tajawal', sans-serif;
        }

        .btn-delete:hover {
            background: var(--accent-hover);
            transform: scale(0.97);
        }

        .btn-delete i {
            font-size: 0.7rem;
        }

        .btn-copy {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 0.4rem 0.9rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'Tajawal', sans-serif;
        }

        .btn-copy:hover {
            background: var(--primary-hover);
            transform: scale(0.97);
        }

        .btn-copy i {
            font-size: 0.7rem;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: var(--text-muted);
        }

        .empty-state i {
            font-size: 3rem;
            color: var(--border);
            margin-bottom: 1rem;
            display: block;
        }

        .empty-state h3 {
            font-size: 1.2rem;
            color: var(--text-gray);
            margin-bottom: 0.5rem;
        }

        .phone-edit {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .phone-edit input {
            padding: 0.3rem 0.6rem;
            border: 2px solid var(--border);
            border-radius: 6px;
            font-size: 0.8rem;
            font-family: 'Tajawal', sans-serif;
            min-width: 120px;
            max-width: 160px;
            transition: border-color 0.2s;
        }

        .phone-edit input:focus {
            border-color: var(--primary);
            outline: none;
        }

        .phone-edit .btn-sm {
            white-space: nowrap;
        }

        .phone-display {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .phone-display .phone-text {
            font-weight: 500;
            color: var(--text-dark);
        }

        .phone-display .no-phone {
            color: var(--text-muted);
            font-style: italic;
            font-size: 0.8rem;
        }

        .phone-edit-form {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .phone-edit-form .btn-sm {
            white-space: nowrap;
        }

        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(4px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-box {
            background: var(--white);
            border-radius: var(--radius);
            max-width: 480px;
            width: 100%;
            padding: 2.5rem 2rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            animation: modalIn 0.25s ease-out;
        }

        @keyframes modalIn {
            from {
                opacity: 0;
                transform: scale(0.95) translateY(20px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .modal-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #fee2e2;
            color: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin: 0 auto 1.2rem;
        }

        .modal-title {
            text-align: center;
            font-size: 1.2rem;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 0.5rem;
        }

        .modal-desc {
            text-align: center;
            color: var(--text-muted);
            font-size: 0.9rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .modal-desc strong {
            color: var(--text-dark);
        }

        .modal-actions {
            display: flex;
            gap: 0.8rem;
            justify-content: center;
        }

        .modal-actions .btn-cancel {
            padding: 0.6rem 1.8rem;
            border: 2px solid var(--border);
            border-radius: 40px;
            background: transparent;
            font-weight: 700;
            font-size: 0.85rem;
            color: var(--text-gray);
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'Tajawal', sans-serif;
        }

        .modal-actions .btn-cancel:hover {
            background: #f1f1f1;
        }

        .modal-actions .btn-confirm-delete {
            padding: 0.6rem 1.8rem;
            border: none;
            border-radius: 40px;
            background: var(--accent);
            color: #fff;
            font-weight: 700;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'Tajawal', sans-serif;
        }

        .modal-actions .btn-confirm-delete:hover {
            background: var(--accent-hover);
        }

        .modal-actions .btn-confirm-delete:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .modal-warning-box {
            background: #fee2e2;
            border: 1px solid #fca5a5;
            border-radius: 8px;
            padding: 0.8rem 1rem;
            margin-bottom: 1rem;
            text-align: center;
            color: #991b1b;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .modal-warning-box i {
            margin-right: 0.5rem;
        }

        .badge-with-activity {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.25rem 0.7rem;
            border-radius: 40px;
            font-size: 0.7rem;
            font-weight: 700;
            background: #dcfce7;
            color: #166534;
        }

        @media (max-width: 1024px) {
            table {
                font-size: 0.8rem;
            }
            thead th, tbody td {
                padding: 0.7rem 0.8rem;
            }
        }

        @media (max-width: 768px) {
            .dashboard-nav {
                padding: 0 1rem;
            }
            .nav-brand span {
                font-size: 1.3rem;
            }
            .nav-user-details .email {
                display: none;
            }
            .dashboard-main {
                padding: 1rem;
            }
            .page-header-left h1 {
                font-size: 1.3rem;
            }
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            .stat-card .stat-number {
                font-size: 1.4rem;
            }
            .table-toolbar {
                flex-direction: column;
                align-items: stretch;
                gap: 0.5rem;
            }
            .table-toolbar-left {
                flex-wrap: wrap;
            }
            .phone-edit {
                flex-direction: column;
                align-items: stretch;
            }
            .phone-edit input {
                max-width: 100%;
            }
            .phone-edit-form {
                flex-direction: column;
                width: 100%;
            }
            .phone-edit-form input {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            .dashboard-nav {
                padding: 0 0.8rem;
                height: 60px;
            }
            .nav-brand span {
                font-size: 1.1rem;
            }
            .btn-back span {
                display: none;
            }
            .stats-grid {
                grid-template-columns: 1fr 1fr;
                gap: 0.8rem;
            }
            .stat-card {
                padding: 1rem;
            }
            .stat-card .stat-number {
                font-size: 1.2rem;
            }
            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }
            .modal-box {
                padding: 1.8rem 1.2rem;
            }
            .page-header-actions .btn-danger span {
                display: none;
            }
            .page-header-actions .btn-outline span {
                display: none;
            }
            .page-header-actions .btn-success span {
                display: none;
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
                <div class="name"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></div>
                <div class="email"><?= htmlspecialchars($_SESSION['user_email'] ?? '') ?></div>
            </div>
            <a href="dashboard.php" class="btn-back">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Volver</span>
            </a>
        </div>
    </nav>

    <main class="dashboard-main">

        <div class="page-header">
            <div class="page-header-left">
                <h1>
                    <i class="fa-solid fa-building"></i>
                    Empresas con Actividades
                </h1>
                <p>المنشآت التي تحتوي على أنشطة مرتبطة بها</p>
            </div>
            <div class="page-header-actions">
                <button class="btn-success" onclick="copyAllBusinessData()">
                    <i class="fa-regular fa-copy"></i>
                    <span>نسخ جميع البيانات</span>
                </button>
                <a href="dashboard.php" class="btn-primary">
                    <i class="fa-solid fa-plus"></i>
                    Nueva Empresa
                </a>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon"><i class="fa-solid fa-store"></i></div>
                <div class="stat-number"><?= $totalBusinesses ?></div>
                <div class="stat-label">Empresas con Actividades</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fa-solid fa-ticket"></i></div>
                <div class="stat-number"><?= $totalListings ?></div>
                <div class="stat-label">Total Actividades</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fa-solid fa-check-circle"></i></div>
                <div class="stat-number"><?= $verifiedBusinesses ?></div>
                <div class="stat-label">Empresas Verificadas</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fa-solid fa-circle-check"></i></div>
                <div class="stat-number"><?= $activeBusinesses ?></div>
                <div class="stat-label">Empresas Activas</div>
            </div>
        </div>

        <?php if (isset($success)): ?>
            <div class="alert alert-success">
                <i class="fa-solid fa-check-circle"></i>
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <div class="table-container">
            <?php if (empty($businesses)): ?>
                <div class="empty-state">
                    <i class="fa-solid fa-store-slash"></i>
                    <h3>لا توجد منشآت مع أنشطة</h3>
                    <p>جميع المنشآت لا تحتوي على أنشطة مرتبطة بها.</p>
                </div>
            <?php else: ?>
                <form method="POST" id="bulkDeleteForm">
                    <div class="table-toolbar">
                        <div class="table-toolbar-left">
                            <label class="checkbox-wrapper">
                                <input type="checkbox" id="selectAll" onchange="toggleAllCheckboxes()">
                                <span>Seleccionar todos</span>
                            </label>
                            <span class="selected-info" id="selectedInfo">
                                <strong id="selectedCount">0</strong> seleccionados
                            </span>
                        </div>
                        <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
                            <button type="button" class="btn-outline" onclick="clearSelection()">
                                <i class="fa-solid fa-times"></i>
                                <span>Limpiar</span>
                            </button>
                            <button type="button" class="btn-danger" id="deleteSelectedBtn" onclick="openBulkDeleteModal()" disabled>
                                <i class="fa-solid fa-trash-can"></i>
                                <span>Eliminar seleccionados</span>
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" id="selectAllHead" onchange="toggleAllCheckboxes()">
                                    </th>
                                    <th>Empresa</th>
                                    <th>Propietario</th>
                                    <th>Teléfono</th>
                                    <th>Ubicación</th>
                                    <th>Estado</th>
                                    <th>Actividades</th>
                                    <th style="text-align:center;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($businesses as $business): ?>
                                    <tr id="row-<?= $business['id'] ?>">
                                        <td>
                                            <input type="checkbox" name="business_ids[]" value="<?= $business['id'] ?>" class="row-checkbox" onchange="updateSelection()">
                                        </td>
                                        <td>
                                            <div class="business-name"><?= htmlspecialchars($business['business_name'] ?? 'Sin nombre') ?></div>
                                            <div class="business-email">
                                                <?= htmlspecialchars($business['email'] ?? 'Sin email') ?>
                                            </div>
                                            <?php if ($business['business_type_name']): ?>
                                                <span style="font-size:0.7rem;color:var(--text-muted);">
                                                    <i class="fa-solid fa-tag"></i> <?= htmlspecialchars($business['business_type_name']) ?>
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div style="font-weight:600;"><?= htmlspecialchars($business['owner_name'] ?? 'Desconocido') ?></div>
                                            <div style="font-size:0.75rem;color:var(--text-muted);">
                                                <?= htmlspecialchars($business['owner_email'] ?? '') ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if (isset($_GET['edit_phone']) && $_GET['edit_phone'] == $business['id']): ?>
                                                <form method="POST" class="phone-edit-form">
                                                    <input type="hidden" name="business_id" value="<?= $business['id'] ?>">
                                                    <input type="text" name="phone" value="<?= htmlspecialchars($business['phone'] ?? '') ?>" placeholder="رقم الهاتف">
                                                    <button type="submit" name="update_phone" class="btn-sm btn-sm-success">
                                                        <i class="fa-solid fa-check"></i>
                                                    </button>
                                                    <a href="?<?= http_build_query(array_diff_key($_GET, ['edit_phone' => ''])) ?>" class="btn-sm btn-sm-danger">
                                                        <i class="fa-solid fa-xmark"></i>
                                                    </a>
                                                </form>
                                            <?php else: ?>
                                                <div class="phone-display">
                                                    <?php if (!empty($business['phone'])): ?>
                                                        <span class="phone-text"><?= htmlspecialchars($business['phone']) ?></span>
                                                    <?php else: ?>
                                                        <span class="no-phone">لا يوجد رقم</span>
                                                    <?php endif; ?>
                                                    <a href="?<?= http_build_query(array_merge($_GET, ['edit_phone' => $business['id']])) ?>" class="btn-sm" style="background:var(--bg-light);border:1px solid var(--border);color:var(--text-gray);padding:0.2rem 0.5rem;border-radius:4px;font-size:0.65rem;text-decoration:none;">
                                                        <i class="fa-solid fa-pen"></i>
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($business['city_name']): ?>
                                                <span><?= htmlspecialchars($business['city_name']) ?></span>
                                            <?php endif; ?>
                                            <?php if ($business['country_name']): ?>
                                                <span style="color:var(--text-muted);font-size:0.75rem;">
                                                    (<?= htmlspecialchars($business['country_name']) ?>)
                                                </span>
                                            <?php endif; ?>
                                            <?php if (!$business['city_name'] && !$business['country_name']): ?>
                                                <span style="color:var(--text-muted);font-size:0.75rem;">Sin ubicación</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div style="display:flex;flex-wrap:wrap;gap:0.3rem;">
                                                <span class="badge <?= $business['status'] === 'active' ? 'badge-active' : 'badge-inactive' ?>">
                                                    <i class="fa-solid <?= $business['status'] === 'active' ? 'fa-circle-check' : 'fa-circle-xmark' ?>"></i>
                                                    <?= $business['status'] === 'active' ? 'Activa' : 'Inactiva' ?>
                                                </span>
                                                <span class="badge <?= $business['verified'] ? 'badge-verified' : 'badge-unverified' ?>">
                                                    <i class="fa-solid <?= $business['verified'] ? 'fa-badge-check' : 'fa-clock' ?>"></i>
                                                    <?= $business['verified'] ? 'Verificada' : 'Pendiente' ?>
                                                </span>
                                                <span class="badge-with-activity">
                                                    <i class="fa-solid fa-circle-check"></i>
                                                    مع أنشطة
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if ($business['listings_count'] > 0): ?>
                                                <div class="listings-preview">
                                                    <?php 
                                                    $titles = explode('|||', $business['listing_titles'] ?? '');
                                                    $ids = explode('|||', $business['listing_ids'] ?? '');
                                                    $show = array_slice($titles, 0, 3);
                                                    $remaining = count($titles) - 3;
                                                    ?>
                                                    <?php foreach ($show as $index => $title): ?>
                                                        <div class="listing-item">
                                                            <i class="fa-solid fa-ticket"></i>
                                                            <?= htmlspecialchars($title ?: 'Sin título') ?>
                                                        </div>
                                                    <?php endforeach; ?>
                                                    <?php if ($remaining > 0): ?>
                                                        <div class="listing-item" style="color:var(--text-muted);font-weight:600;">
                                                            +<?= $remaining ?> más
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php else: ?>
                                                <span class="no-listings">
                                                    <i class="fa-solid fa-circle-minus"></i> Sin actividades
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td style="text-align:center;">
                                            <div style="display:flex;flex-direction:column;gap:0.3rem;align-items:center;">
                                                <button type="button" class="btn-copy" onclick="copyBusinessData(<?= $business['id'] ?>, '<?= htmlspecialchars(addslashes($business['business_name'])) ?>', '<?= htmlspecialchars(addslashes($business['phone'] ?? '')) ?>', '<?= htmlspecialchars(addslashes($business['listing_titles'] ?? '')) ?>')">
                                                    <i class="fa-regular fa-copy"></i>
                                                    نسخ
                                                </button>
                                                <button type="button" class="btn-delete" onclick="openSingleDeleteModal(<?= $business['id'] ?>, '<?= htmlspecialchars(addslashes($business['business_name'])) ?>', <?= $business['listings_count'] ?>)">
                                                    <i class="fa-solid fa-trash-can"></i>
                                                    Eliminar
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <input type="hidden" name="delete_businesses" value="1" id="bulkDeleteInput">
                </form>
            <?php endif; ?>
        </div>

    </main>

    <div class="toast-message" id="toastMessage">
        <i class="fa-solid fa-check-circle"></i>
        <span id="toastText">تم النسخ بنجاح</span>
    </div>

    <div class="modal-overlay" id="deleteModal">
        <div class="modal-box">
            <div class="modal-icon">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <h3 class="modal-title" id="modalTitle">تأكيد الحذف</h3>
            <div class="modal-warning-box">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span id="modalCount">سيتم حذف 1 منشأة</span>
            </div>
            <p class="modal-desc" id="modalDesc">
                هذه المنشآت <strong>تحتوي على أنشطة مرتبطة</strong>.
                <br>
                Esta acción <strong>no se puede deshacer</strong> y eliminará permanentemente todos los datos asociados.
            </p>
            <div class="modal-actions">
                <button type="button" class="btn-cancel" onclick="closeDeleteModal()">
                    Cancelar
                </button>
                <button type="button" class="btn-confirm-delete" id="confirmDeleteBtn" onclick="confirmDelete()">
                    <i class="fa-solid fa-trash-can"></i> Eliminar definitivamente
                </button>
            </div>
        </div>
    </div>

    <script>
        let deleteMode = 'single';
        let singleDeleteId = null;
        let bulkDeleteIds = [];

        function toggleAllCheckboxes() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.row-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = selectAll.checked;
                updateRowHighlight(cb);
            });
            updateSelection();
        }

        function updateRowHighlight(checkbox) {
            const row = checkbox.closest('tr');
            if (row) {
                if (checkbox.checked) {
                    row.classList.add('selected');
                } else {
                    row.classList.remove('selected');
                }
            }
        }

        function updateSelection() {
            const checkboxes = document.querySelectorAll('.row-checkbox');
            const checked = document.querySelectorAll('.row-checkbox:checked');
            const selectedCount = checked.length;
            
            document.getElementById('selectedCount').textContent = selectedCount;
            document.getElementById('deleteSelectedBtn').disabled = selectedCount === 0;
            
            const selectAll = document.getElementById('selectAll');
            const selectAllHead = document.getElementById('selectAllHead');
            const total = checkboxes.length;
            
            if (total > 0 && selectedCount === total) {
                selectAll.checked = true;
                selectAllHead.checked = true;
            } else {
                selectAll.checked = false;
                selectAllHead.checked = false;
            }
        }

        function clearSelection() {
            const checkboxes = document.querySelectorAll('.row-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = false;
                updateRowHighlight(cb);
            });
            updateSelection();
        }

        document.querySelectorAll('.row-checkbox').forEach(cb => {
            cb.addEventListener('change', function() {
                updateRowHighlight(this);
                updateSelection();
            });
        });

        document.getElementById('selectAllHead')?.addEventListener('change', function() {
            document.getElementById('selectAll').checked = this.checked;
            toggleAllCheckboxes();
        });

        function copyBusinessData(id, name, phone, listings) {
            let text = `منشأة: ${name}\n`;
            text += `رقم الهاتف: ${phone || 'غير متوفر'}\n`;
            
            if (listings) {
                const titles = listings.split('|||');
                text += `الأنشطة (${titles.length}):\n`;
                titles.forEach((title, index) => {
                    text += `  ${index + 1}. ${title}\n`;
                });
            } else {
                text += `لا توجد أنشطة\n`;
            }
            
            navigator.clipboard.writeText(text).then(() => {
                showToast(`تم نسخ بيانات: ${name}`);
            }).catch(() => {
                const textarea = document.createElement('textarea');
                textarea.value = text;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
                showToast(`تم نسخ بيانات: ${name}`);
            });
        }

        function copyAllBusinessData() {
            const rows = document.querySelectorAll('tbody tr');
            let text = '=== قائمة المنشآت والأنشطة ===\n\n';
            
            rows.forEach(row => {
                const nameEl = row.querySelector('.business-name');
                const phoneEl = row.querySelector('.phone-text');
                const listingsEl = row.querySelector('.listings-preview');
                
                if (nameEl) {
                    const name = nameEl.textContent.trim();
                    const phone = phoneEl ? phoneEl.textContent.trim() : 'غير متوفر';
                    
                    text += `منشأة: ${name}\n`;
                    text += `رقم الهاتف: ${phone}\n`;
                    
                    if (listingsEl) {
                        const items = listingsEl.querySelectorAll('.listing-item');
                        if (items.length > 0) {
                            text += `الأنشطة:\n`;
                            items.forEach(item => {
                                const title = item.textContent.trim().replace(/^\+.*$/, '').trim();
                                if (title) {
                                    text += `  - ${title}\n`;
                                }
                            });
                            const more = listingsEl.querySelector('.listing-item[style*="font-weight:600;"]');
                            if (more) {
                                text += `  ${more.textContent.trim()}\n`;
                            }
                        } else {
                            text += `  (لا توجد أنشطة)\n`;
                        }
                    } else {
                        text += `  (لا توجد أنشطة)\n`;
                    }
                    text += '\n';
                }
            });
            
            navigator.clipboard.writeText(text).then(() => {
                showToast('تم نسخ جميع البيانات بنجاح');
            }).catch(() => {
                const textarea = document.createElement('textarea');
                textarea.value = text;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
                showToast('تم نسخ جميع البيانات بنجاح');
            });
        }

        function showToast(message) {
            const toast = document.getElementById('toastMessage');
            const text = document.getElementById('toastText');
            text.textContent = message;
            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }

        function openSingleDeleteModal(id, name, listingsCount) {
            deleteMode = 'single';
            singleDeleteId = id;
            bulkDeleteIds = [];
            
            document.getElementById('modalTitle').textContent = 'تأكيد حذف المنشأة';
            document.getElementById('modalCount').textContent = `سيتم حذف 1 منشأة (${listingsCount} نشاط)`;
            document.getElementById('modalDesc').innerHTML = `
                هذه المنشأة <strong>تحتوي على ${listingsCount} نشاط مرتبط</strong>.
                <br>
                Esta acción <strong>no se puede deshacer</strong> y eliminará permanentemente todos los datos asociados.
            `;
            
            document.getElementById('deleteModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function openBulkDeleteModal() {
            const checkboxes = document.querySelectorAll('.row-checkbox:checked');
            if (checkboxes.length === 0) return;
            
            deleteMode = 'bulk';
            bulkDeleteIds = [];
            let totalListings = 0;
            
            checkboxes.forEach(cb => {
                const id = parseInt(cb.value);
                bulkDeleteIds.push(id);
                const row = cb.closest('tr');
                const listingsText = row.querySelector('.listings-preview');
                if (listingsText) {
                    const items = listingsText.querySelectorAll('.listing-item');
                    totalListings += items.length;
                }
            });
            
            document.getElementById('modalTitle').textContent = 'تأكيد الحذف الجماعي';
            document.getElementById('modalCount').textContent = `سيتم حذف ${bulkDeleteIds.length} منشأة (${totalListings} نشاط)`;
            document.getElementById('modalDesc').innerHTML = `
                هذه المنشآت <strong>تحتوي على أنشطة مرتبطة</strong>.
                <br>
                Esta acción <strong>no se puede deshacer</strong> y eliminará permanentemente todos los datos asociados.
            `;
            
            document.getElementById('deleteModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function confirmDelete() {
            const confirmBtn = document.getElementById('confirmDeleteBtn');
            confirmBtn.disabled = true;
            confirmBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Eliminando...';
            
            if (deleteMode === 'single') {
                const form = document.createElement('form');
                form.method = 'POST';
                form.style.display = 'none';
                
                const input1 = document.createElement('input');
                input1.type = 'hidden';
                input1.name = 'delete_businesses';
                input1.value = '1';
                form.appendChild(input1);
                
                const input2 = document.createElement('input');
                input2.type = 'hidden';
                input2.name = 'business_ids[]';
                input2.value = singleDeleteId;
                form.appendChild(input2);
                
                document.body.appendChild(form);
                form.submit();
            } else if (deleteMode === 'bulk') {
                const form = document.getElementById('bulkDeleteForm');
                const existingInput = document.querySelector('input[name="business_ids[]"]');
                if (existingInput) {
                    existingInput.remove();
                }
                
                bulkDeleteIds.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'business_ids[]';
                    input.value = id;
                    form.appendChild(input);
                });
                
                form.submit();
            }
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('active');
            document.body.style.overflow = '';
            const confirmBtn = document.getElementById('confirmDeleteBtn');
            confirmBtn.disabled = false;
            confirmBtn.innerHTML = '<i class="fa-solid fa-trash-can"></i> Eliminar definitivamente';
        }

        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && document.getElementById('deleteModal').classList.contains('active')) {
                closeDeleteModal();
            }
        });
    </script>

</body>
</html>