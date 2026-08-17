<?php
// ===== اتصال قاعدة البيانات =====
$host = 'localhost';
$dbname = 'u363024655_wolfstravel';
$username = 'u363024655_youssefWolfs';
$password = 'Khalid0pm9ol8ikn7ujb@';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("فشل الاتصال بقاعدة البيانات: " . $e->getMessage());
}

// ===== دوال مساعدة =====
function generateSlug($string) {
    $string = strtolower(trim($string));
    $string = preg_replace('/[^a-z0-9\-]/', '-', $string);
    $string = preg_replace('/-+/', '-', $string);
    return trim($string, '-');
}

function getTableData($pdo, $table, $orderBy = 'id DESC') {
    $stmt = $pdo->query("SELECT * FROM $table ORDER BY $orderBy");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ===== معالجة الطلبات =====
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $table = $_POST['table'] ?? '';
    $name = trim($_POST['name'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $icon = trim($_POST['icon'] ?? '');
    $parent_id = isset($_POST['parent_id']) ? (int)$_POST['parent_id'] : null;

    // إذا لم يتم إدخال slug نولده من الاسم
    if (empty($slug)) {
        $slug = generateSlug($name);
    }

    // التحقق من صحة البيانات
    if (empty($name) || empty($table)) {
        $message = 'الاسم والحقل مطلوبان.';
        $messageType = 'error';
    } else {
        try {
            // تحديد الحقول المطلوبة حسب الجدول
            $fields = ['name', 'slug'];
            $placeholders = [':name', ':slug'];
            $values = [':name' => $name, ':slug' => $slug];

            if ($table === 'categories' && $parent_id !== null) {
                $fields[] = 'parent_id';
                $placeholders[] = ':parent_id';
                $values[':parent_id'] = $parent_id;
            }

            if (in_array($table, ['listing_types', 'listing_features']) && !empty($icon)) {
                $fields[] = 'icon';
                $placeholders[] = ':icon';
                $values[':icon'] = $icon;
            }

            // إضافة created_at و updated_at
            $fields[] = 'created_at';
            $fields[] = 'updated_at';
            $placeholders[] = 'NOW()';
            $placeholders[] = 'NOW()';

            $sql = "INSERT INTO $table (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($values);

            $message = "تمت إضافة '$name' بنجاح.";
            $messageType = 'success';
        } catch (PDOException $e) {
            // التحقق من خطأ التكرار (مفتاح فريد)
            if (strpos($e->getMessage(), 'Duplicate entry') !== false) {
                $message = "هذا الاسم أو slug موجود مسبقاً. يرجى استخدام اسم مختلف.";
            } else {
                $message = "حدث خطأ: " . $e->getMessage();
            }
            $messageType = 'error';
        }
    }
}

// ===== معالجة الحذف =====
if (isset($_GET['delete']) && isset($_GET['table']) && isset($_GET['id'])) {
    $table = $_GET['table'];
    $id = (int)$_GET['id'];
    try {
        $stmt = $pdo->prepare("DELETE FROM $table WHERE id = ?");
        $stmt->execute([$id]);
        $message = "تم الحذف بنجاح.";
        $messageType = 'success';
    } catch (PDOException $e) {
        $message = "خطأ في الحذف: " . $e->getMessage();
        $messageType = 'error';
    }
}

// ===== جلب البيانات لكل جدول =====
$tables = [
    'categories' => ['label' => 'التصنيفات', 'fields' => ['name', 'slug', 'parent_id']],
    'listing_types' => ['label' => 'أنواع القوائم', 'fields' => ['name', 'slug', 'icon']],
    'business_types' => ['label' => 'أنواع الأعمال', 'fields' => ['name', 'slug']],
    'listing_features' => ['label' => 'المميزات', 'fields' => ['name', 'slug', 'icon']],
    'listing_tags' => ['label' => 'الوسوم', 'fields' => ['name', 'slug']],
];

$data = [];
foreach (array_keys($tables) as $t) {
    $data[$t] = getTableData($pdo, $t);
}

// جلب التصنيفات الأبوية لإظهارها في قائمة منسدلة عند إضافة تصنيف
$categories = getTableData($pdo, 'categories', 'name ASC');
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الفئات والأنواع - Wolf's Travel</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, sans-serif;
        }
        body {
            background: #f4f7fc;
            margin: 0;
            padding: 20px;
            direction: rtl;
        }
        .container {
            max-width: 1200px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        h1 {
            text-align: center;
            color: #2c3e50;
            border-bottom: 3px solid #3498db;
            padding-bottom: 15px;
            margin-top: 0;
        }
        .tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin: 20px 0 10px;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
        }
        .tab-btn {
            background: #ecf0f1;
            border: none;
            padding: 10px 20px;
            border-radius: 30px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: 0.3s;
        }
        .tab-btn.active {
            background: #3498db;
            color: white;
        }
        .tab-btn:hover {
            background: #bdc3c7;
        }
        .tab-content {
            display: none;
            padding: 20px 0;
            animation: fade 0.3s;
        }
        .tab-content.active {
            display: block;
        }
        @keyframes fade {
            from { opacity: 0.3; }
            to { opacity: 1; }
        }

        .form-group {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            align-items: center;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
        }
        .form-group label {
            font-weight: bold;
            min-width: 100px;
        }
        .form-group input, .form-group select {
            padding: 10px 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            flex: 1;
            min-width: 180px;
        }
        .form-group button {
            background: #2ecc71;
            color: white;
            border: none;
            padding: 10px 30px;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }
        .form-group button:hover {
            background: #27ae60;
        }

        .message {
            padding: 12px 20px;
            border-radius: 6px;
            margin: 10px 0 20px;
            font-weight: bold;
        }
        .message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th {
            background: #34495e;
            color: white;
        }
        tr:nth-child(even) {
            background: #f2f2f2;
        }
        .delete-btn {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 6px 14px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            font-size: 14px;
            transition: 0.2s;
        }
        .delete-btn:hover {
            background: #c0392b;
        }
        .empty-row {
            text-align: center;
            color: #888;
        }
        .badge {
            background: #3498db;
            color: white;
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 13px;
        }
        @media (max-width: 768px) {
            .form-group { flex-direction: column; align-items: stretch; }
            .tabs { justify-content: center; }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>📋 إدارة الفئات والأنواع</h1>

    <?php if ($message): ?>
        <div class="message <?= $messageType ?>"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <!-- التبويبات -->
    <div class="tabs">
        <?php foreach ($tables as $key => $info): ?>
            <button class="tab-btn <?= $key === 'categories' ? 'active' : '' ?>" data-tab="<?= $key ?>">
                <?= htmlspecialchars($info['label']) ?>
            </button>
        <?php endforeach; ?>
    </div>

    <!-- محتوى التبويبات -->
    <?php foreach ($tables as $key => $info): ?>
        <div class="tab-content <?= $key === 'categories' ? 'active' : '' ?>" id="tab-<?= $key ?>">
            <h3>إضافة جديد - <?= htmlspecialchars($info['label']) ?></h3>
            <form method="POST" action="">
                <input type="hidden" name="table" value="<?= $key ?>">
                <input type="hidden" name="action" value="add">
                <div class="form-group">
                    <label>الاسم:</label>
                    <input type="text" name="name" required placeholder="أدخل الاسم ...">
                    
                    <?php if ($key === 'categories'): ?>
                        <label>التصنيف الأب:</label>
                        <select name="parent_id">
                            <option value="">لا يوجد (جذر)</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>

                    <?php if (in_array($key, ['listing_types', 'listing_features'])): ?>
                        <label>الأيقونة (اختياري):</label>
                        <input type="text" name="icon" placeholder="مثل: fa-hotel">
                    <?php endif; ?>

                    <label>Slug (اختياري):</label>
                    <input type="text" name="slug" placeholder="يُترك لتوليد تلقائي">
                    
                    <button type="submit">➕ إضافة</button>
                </div>
            </form>

            <h4>القائمة الحالية</h4>
            <?php if (empty($data[$key])): ?>
                <p class="empty-row">لا توجد بيانات في هذا الجدول.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الاسم</th>
                            <th>Slug</th>
                            <?php if ($key === 'categories'): ?><th>الأب</th><?php endif; ?>
                            <?php if (in_array($key, ['listing_types', 'listing_features'])): ?><th>الأيقونة</th><?php endif; ?>
                            <th>إجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data[$key] as $row): ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td><span class="badge"><?= htmlspecialchars($row['slug']) ?></span></td>
                                <?php if ($key === 'categories'): ?>
                                    <td>
                                        <?php 
                                        if ($row['parent_id']) {
                                            $parent = array_filter($categories, fn($c) => $c['id'] == $row['parent_id']);
                                            echo $parent ? htmlspecialchars(reset($parent)['name']) : '—';
                                        } else {
                                            echo '—';
                                        }
                                        ?>
                                    </td>
                                <?php endif; ?>
                                <?php if (in_array($key, ['listing_types', 'listing_features'])): ?>
                                    <td><?= htmlspecialchars($row['icon'] ?? '') ?></td>
                                <?php endif; ?>
                                <td>
                                    <a href="?delete=1&table=<?= $key ?>&id=<?= $row['id'] ?>" 
                                       onclick="return confirm('هل أنت متأكد من حذف هذا العنصر؟')" 
                                       class="delete-btn">🗑 حذف</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

<script>
    // تبديل التبويبات
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const tabId = this.dataset.tab;
            document.querySelectorAll('.tab-content').forEach(tc => tc.classList.remove('active'));
            document.getElementById('tab-' + tabId).classList.add('active');
        });
    });

    // توليد slug تلقائي من الاسم أثناء الكتابة (اختياري)
    document.querySelectorAll('input[name="name"]').forEach(input => {
        input.addEventListener('input', function() {
            const slugInput = this.closest('.form-group').querySelector('input[name="slug"]');
            if (slugInput && !slugInput.value) {
                const val = this.value.trim().toLowerCase()
                    .replace(/[^a-z0-9\-]/g, '-')
                    .replace(/-+/g, '-')
                    .replace(/^-|-$/g, '');
                slugInput.value = val;
            }
        });
    });
</script>
</body>
</html>