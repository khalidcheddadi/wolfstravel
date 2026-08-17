<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $ids = isset($_POST['ids']) ? $_POST['ids'] : [];
    $method = isset($_POST['delete_method']) ? $_POST['delete_method'] : 'soft';

    if (empty($ids) || !is_array($ids)) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => false, 'message' => 'لم يتم تحديد أي نشاط']);
        exit;
    }

    $ids = array_map('intval', $ids);
    $ids = array_filter($ids, function($v) { return $v > 0; });
    if (empty($ids)) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => false, 'message' => 'معرفات غير صالحة']);
        exit;
    }

    try {
        $placeholders = rtrim(str_repeat('?,', count($ids)), ',');
        if ($method === 'hard') {
            $stmt = $pdo->prepare("DELETE FROM media WHERE model_id IN ($placeholders) AND model_type = 'App\\\\Models\\\\Listing\\\\Listing'");
            $stmt->execute(array_values($ids));
            
            $stmt = $pdo->prepare("DELETE FROM listings WHERE id IN ($placeholders)");
            $stmt->execute(array_values($ids));
        } else {
            $stmt = $pdo->prepare("UPDATE listings SET status = 'deleted' WHERE id IN ($placeholders)");
            $stmt->execute(array_values($ids));
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => true,
            'deleted_ids' => $ids,
            'method' => $method,
            'message' => $method === 'hard' ? 'تم الحذف النهائي بنجاح' : 'تم وضع الأنشطة في سلة المحذوفات'
        ]);
        exit;
    } catch (Exception $e) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => false, 'message' => 'خطأ في الخادم: ' . $e->getMessage()]);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'toggle') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    if ($id <= 0) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => false, 'message' => 'معرف غير صالح']);
        exit;
    }

    try {
        $stmt = $pdo->prepare('SELECT status FROM listings WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if (!$row) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'message' => 'النشاط غير موجود']);
            exit;
        }

        $current = $row['status'];
        if ($current === 'deleted') {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => false, 'message' => 'لا يمكن تغيير حالة نشاط محذوف']);
            exit;
        }

        $newStatus = ($current === 'published') ? 'suspended' : 'published';
        $u = $pdo->prepare('UPDATE listings SET status = ? WHERE id = ?');
        $u->execute([$newStatus, $id]);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => true, 'id' => $id, 'status' => $newStatus]);
        exit;
    } catch (Exception $e) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => false, 'message' => 'خطأ في الخادم: ' . $e->getMessage()]);
        exit;
    }
}

$listings = [];
try {
    $query = "
        SELECT 
            l.id, 
            l.title, 
            l.status, 
            l.created_at,
            m.file_name,
            m.disk
        FROM listings l
        LEFT JOIN media m ON l.id = m.model_id 
            AND m.model_type = 'App\\\\Models\\\\Listing\\\\Listing' 
            AND m.collection_name = 'images'
        ORDER BY l.created_at DESC, m.id
    ";
    $stmt = $pdo->query($query);
    $allRows = $stmt->fetchAll();
    
    $listingsMap = [];
    foreach ($allRows as $row) {
        $listingId = $row['id'];
        if (!isset($listingsMap[$listingId])) {
            $listingsMap[$listingId] = [
                'id' => $row['id'],
                'title' => $row['title'],
                'status' => $row['status'],
                'created_at' => $row['created_at'],
                'images' => []
            ];
        }
        if ($row['file_name']) {
            $listingsMap[$listingId]['images'][] = [
                'file_name' => $row['file_name'],
                'disk' => $row['disk']
            ];
        }
    }
    $listings = array_values($listingsMap);
} catch (Exception $e) {
    echo "حدث خطأ عند جلب الأنشطة: " . htmlspecialchars($e->getMessage());
    exit;
}
?>
<!doctype html>
<html lang="ar">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>قائمة الأنشطة - إدارة النشاط</title>
    <style>
        :root {
            --primary: #6c5ce7;
            --primary-dark: #5a4bd1;
            --danger: #e74c3c;
            --danger-dark: #c0392b;
            --success: #27ae60;
            --warning: #f39c12;
            --bg: #0f0f1a;
            --surface: #1a1a2e;
            --surface-hover: #24243e;
            --text: #e0e0e0;
            --text-muted: #a0a0b0;
            --border: #2c2c44;
            --shadow: 0 10px 30px rgba(0,0,0,0.4);
            --glow-primary: 0 0 15px rgba(108,92,231,0.5);
            --glow-danger: 0 0 15px rgba(231,76,60,0.5);
            --radius: 16px;
            --transition: 0.25s cubic-bezier(0.2, 0.9, 0.4, 1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Tahoma', 'Segoe UI', Arial, sans-serif;
            direction: rtl;
            background: var(--bg);
            background-image: radial-gradient(circle at 20% 10%, #1e1e3a 0%, var(--bg) 90%);
            color: var(--text);
            padding: 30px 20px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 8px;
            background: linear-gradient(135deg, #a29bfe, #6c5ce7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 0 20px rgba(108,92,231,0.5);
            letter-spacing: -0.5px;
        }

        .small {
            font-size: 14px;
            color: var(--text-muted);
            margin-bottom: 25px;
            text-align: center;
            max-width: 100%;
        }

        .actions-bar {
            background: var(--surface);
            backdrop-filter: blur(10px);
            border-radius: var(--radius);
            padding: 20px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            width: 100%;
            max-width: 100%;
        }

        .delete-options {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .actions-bar label {
            color: var(--text);
            font-weight: 600;
            font-size: 13px;
            min-width: fit-content;
        }

        .actions-bar select {
            background: #1f1f35;
            color: #fff;
            border: 1px solid var(--border);
            padding: 10px 15px;
            border-radius: 10px;
            font-size: 14px;
            cursor: pointer;
            outline: none;
            transition: var(--transition);
            min-width: 280px;
        }

        .actions-bar select:hover,
        .actions-bar select:focus {
            border-color: var(--primary);
            box-shadow: var(--glow-primary);
        }

        .btn {
            padding: 10px 22px;
            border: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            position: relative;
            overflow: hidden;
            letter-spacing: 0.3px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }

        .btn::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.4s;
            transform: scale(0.5);
        }

        .btn:hover::after {
            opacity: 1;
            transform: scale(1);
        }

        .btn:active {
            transform: scale(0.96);
        }

        .btn-toggle {
            background: linear-gradient(135deg, #00b894, #00cec9);
            color: #fff;
            min-width: 90px;
        }

        .btn-toggle.suspended {
            background: linear-gradient(135deg, #e17055, #d63031);
        }

        .btn-toggle:hover {
            box-shadow: 0 0 20px rgba(0,184,148,0.6);
            transform: translateY(-2px);
        }

        .btn-toggle.suspended:hover {
            box-shadow: 0 0 20px rgba(214,48,49,0.6);
        }

        .btn-danger {
            background: linear-gradient(135deg, #d63031, #e17055);
            color: #fff;
            min-width: 130px;
        }

        .btn-danger:hover {
            box-shadow: 0 0 20px rgba(214,48,49,0.7);
            transform: translateY(-2px);
        }

        .btn-danger:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: var(--surface);
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            margin-top: 10px;
        }

        thead tr {
            background: linear-gradient(135deg, #2d2d4a, #1f1f35);
        }

        th {
            padding: 16px 15px;
            text-align: right;
            font-weight: 600;
            font-size: 14px;
            color: #c4c4e0;
            border-bottom: 2px solid var(--primary);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            position: sticky;
            top: 0;
            white-space: nowrap;
        }

        td {
            padding: 14px 15px;
            border-bottom: 1px solid var(--border);
            transition: background var(--transition);
        }

        tbody tr {
            transition: all var(--transition);
        }

        tbody tr:hover {
            background: var(--surface-hover);
            transform: scale(1.01);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            position: relative;
            z-index: 2;
        }

        .deleted-row {
            opacity: 0.6;
            background: rgba(231,76,60,0.05);
        }

        .deleted-row:hover {
            opacity: 0.8;
        }

        .status {
            font-weight: 700;
            font-size: 13px;
            padding: 6px 12px;
            border-radius: 20px;
            display: inline-block;
            min-width: 85px;
            text-align: center;
        }

        .status-published {
            background: rgba(39,174,96,0.15);
            color: #2ecc71;
            border: 1px solid rgba(46,204,113,0.3);
            box-shadow: 0 0 10px rgba(46,204,113,0.2);
        }

        .status-suspended {
            background: rgba(243,156,18,0.15);
            color: #f1c40f;
            border: 1px solid rgba(241,196,15,0.3);
            box-shadow: 0 0 10px rgba(241,196,15,0.2);
        }

        .status-deleted {
            background: rgba(231,76,60,0.15);
            color: #e74c3c;
            border: 1px solid rgba(231,76,60,0.3);
            text-decoration: line-through;
            box-shadow: 0 0 10px rgba(231,76,60,0.2);
        }

        .row-checkbox,
        #selectAll {
            accent-color: var(--primary);
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .image-cell {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .image-thumbnail {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            object-fit: cover;
            border: 2px solid var(--primary);
            cursor: pointer;
            transition: all var(--transition);
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
        }

        .image-thumbnail:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 15px rgba(108,92,231,0.5);
        }

        .no-images {
            color: var(--text-muted);
            font-size: 12px;
            font-style: italic;
        }

        .image-count {
            background: var(--primary);
            color: white;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
            box-shadow: 0 2px 8px rgba(108,92,231,0.3);
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.9);
            animation: fadeIn 0.3s;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-content {
            position: relative;
            background-color: var(--surface);
            margin: auto;
            padding: 20px;
            border-radius: var(--radius);
            max-width: 90%;
            max-height: 90vh;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            box-shadow: 0 0 50px rgba(108,92,231,0.5);
        }

        .modal-image {
            max-width: 100%;
            max-height: 70vh;
            border-radius: 12px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.5);
        }

        .modal-close {
            color: var(--text);
            position: absolute;
            right: 20px;
            top: 15px;
            font-size: 32px;
            font-weight: bold;
            cursor: pointer;
            transition: all var(--transition);
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(108,92,231,0.2);
        }

        .modal-close:hover {
            color: var(--primary);
            background: rgba(108,92,231,0.4);
            transform: scale(1.1);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-muted);
        }

        .empty-state p {
            font-size: 18px;
            margin-bottom: 10px;
        }

        @media (max-width: 768px) {
            body {
                padding: 15px;
            }

            h1 {
                font-size: 2rem;
            }

            .small {
                font-size: 13px;
            }

            .actions-bar {
                flex-direction: column;
                align-items: stretch;
                padding: 15px;
            }

            .delete-options {
                flex-direction: column;
                width: 100%;
            }

            .actions-bar select {
                width: 100%;
                min-width: auto;
            }

            .btn-danger {
                width: 100%;
                min-width: auto;
            }

            th, td {
                padding: 10px 8px;
                font-size: 12px;
            }

            .btn {
                padding: 8px 12px;
                font-size: 12px;
            }

            .image-thumbnail {
                width: 50px;
                height: 50px;
            }

            .image-count {
                width: 24px;
                height: 24px;
                font-size: 11px;
            }

            table {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <h1>📋 قائمة الأنشطة</h1>
    <p class="small">
        👉 اضغط "تعليق" لإيقاف النشر أو "نشر" لتفعيله | 🗑️ اختر طريقة الحذف: حذف مؤقت أو حذف نهائي | 📸 انقر على الصور لتكبيرها
    </p>

    <div class="actions-bar">
        <div class="delete-options">
            <label for="deleteMethod">طريقة الحذف:</label>
            <select id="deleteMethod">
                <option value="soft">🔄 حذف مؤقت (نقل للسلة فقط)</option>
                <option value="hard">⚠️ حذف نهائي (حذف كامل من النظام)</option>
            </select>
        </div>
        <button type="button" id="deleteSelectedBtn" class="btn btn-danger">🗑️ حذف المحدد</button>
    </div>

    <?php if (empty($listings)): ?>
        <div class="empty-state">
            <p>📭 لا توجد أنشطة حالياً</p>
        </div>
    <?php else: ?>
    <table>
        <thead>
            <tr>
                <th><input type="checkbox" id="selectAll" title="تحديد جميع الأنشطة"></th>
                <th>الـID</th>
                <th>العنوان</th>
                <th>الصور</th>
                <th>تاريخ الإنشاء</th>
                <th>الحالة</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($listings as $a): ?>
                <tr data-id="<?= htmlspecialchars($a['id']) ?>" class="<?= $a['status'] === 'deleted' ? 'deleted-row' : '' ?>">
                    <td><input type="checkbox" class="row-checkbox" value="<?= htmlspecialchars($a['id']) ?>"></td>
                    <td><strong><?= htmlspecialchars($a['id']) ?></strong></td>
                    <td><?= htmlspecialchars(mb_substr($a['title'], 0, 50)) ?></td>
                    <td>
                        <div class="image-cell">
                            <?php if (!empty($a['images'])): ?>
                                <?php foreach ($a['images'] as $image): ?>
                                    <img src="/storage/app/private/<?= htmlspecialchars($image['file_name']) ?>" 
                                         alt="صورة النشاط" 
                                         class="image-thumbnail"
                                         title="انقر لتكبير الصورة"
                                         onclick="openImageModal(this.src)">
                                <?php endforeach; ?>
                                <span class="image-count"><?= count($a['images']) ?></span>
                            <?php else: ?>
                                <span class="no-images">✖️ لا توجد</span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td><?= htmlspecialchars(date('Y-m-d H:i', strtotime($a['created_at']))) ?></td>
                    <td>
                        <span class="status <?= 
                            $a['status'] === 'published' ? 'status-published' : 
                            ($a['status'] === 'suspended' ? 'status-suspended' : 'status-deleted') ?>">
                            <?php 
                                if ($a['status'] === 'published') echo '✓ منشور';
                                elseif ($a['status'] === 'suspended') echo '⏸ معلق';
                                else echo '🗑 محذوف';
                            ?>
                        </span>
                    </td>
                    <td>
                        <?php if ($a['status'] !== 'deleted'): ?>
                            <?php if ($a['status'] === 'published'): ?>
                                <button class="btn btn-toggle suspended" data-action="toggle">⏸ تعليق</button>
                            <?php else: ?>
                                <button class="btn btn-toggle" data-action="toggle">✓ نشر</button>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="small">محذوف نهائياً</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>

    <div id="imageModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeImageModal()">&times;</span>
            <img id="modalImage" class="modal-image" src="" alt="صورة مكبرة">
        </div>
    </div>

    <script>
    function openImageModal(imageSrc) {
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        modalImage.src = imageSrc;
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    }

    function closeImageModal() {
        const modal = document.getElementById('imageModal');
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    window.onclick = function(event) {
        const modal = document.getElementById('imageModal');
        if (event.target === modal) {
            closeImageModal();
        }
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeImageModal();
        }
    });

    async function postForm(formData) {
        const resp = await fetch('', { method: 'POST', body: formData });
        return resp.json();
    }

    document.querySelectorAll('button[data-action="toggle"]').forEach(btn => {
        btn.addEventListener('click', async function(){
            const tr = this.closest('tr');
            const id = tr.getAttribute('data-id');
            this.disabled = true;
            const fd = new FormData();
            fd.append('action', 'toggle');
            fd.append('id', id);
            try{
                const json = await postForm(fd);
                if (json.success) {
                    const statusTd = tr.querySelector('.status');
                    if (json.status === 'published') {
                        statusTd.innerHTML = '✓ منشور';
                        statusTd.className = 'status status-published';
                        this.classList.remove('suspended');
                        this.innerHTML = '⏸ تعليق';
                    } else {
                        statusTd.innerHTML = '⏸ معلق';
                        statusTd.className = 'status status-suspended';
                        this.classList.add('suspended');
                        this.innerHTML = '✓ نشر';
                    }
                } else {
                    alert(json.message || 'حدث خطأ');
                }
            } catch (e) {
                alert('خطأ في الاتصال بالخادم');
            } finally {
                this.disabled = false;
            }
        });
    });

    const selectAllCheckbox = document.getElementById('selectAll');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');

    selectAllCheckbox && selectAllCheckbox.addEventListener('change', function() {
        const isChecked = this.checked;
        rowCheckboxes.forEach(cb => cb.checked = isChecked);
    });

    rowCheckboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            if (!this.checked) {
                selectAllCheckbox.checked = false;
            } else if (document.querySelectorAll('.row-checkbox:checked').length === rowCheckboxes.length) {
                selectAllCheckbox.checked = true;
            }
        });
    });

    document.getElementById('deleteSelectedBtn').addEventListener('click', async function() {
        const selectedCheckboxes = document.querySelectorAll('.row-checkbox:checked');
        if (selectedCheckboxes.length === 0) {
            alert('⚠️ الرجاء تحديد نشاط واحد على الأقل للحذف.');
            return;
        }

        const deleteMethod = document.getElementById('deleteMethod').value;
        const methodLabel = deleteMethod === 'soft' ? 'مؤقت (نقل للسلة)' : 'نهائي (حذف من النظام)';
        const count = selectedCheckboxes.length;
        const confirmMsg = `⚠️ تنبيه: هل أنت متأكد من الحذف ${methodLabel} لـ ${count} نشاط؟\n\nهذا الإجراء غير قابل للتراجع عنه في حالة الحذف النهائي!`;
        
        if (!confirm(confirmMsg)) return;

        const ids = Array.from(selectedCheckboxes).map(cb => cb.value);
        const fd = new FormData();
        fd.append('action', 'delete');
        fd.append('delete_method', deleteMethod);
        ids.forEach(id => fd.append('ids[]', id));

        try {
            const json = await postForm(fd);
            if (json.success) {
                if (deleteMethod === 'hard') {
                    selectedCheckboxes.forEach(cb => {
                        const tr = cb.closest('tr');
                        tr.style.opacity = '0';
                        setTimeout(() => tr.remove(), 300);
                    });
                } else {
                    selectedCheckboxes.forEach(cb => {
                        const tr = cb.closest('tr');
                        tr.classList.add('deleted-row');
                        const statusTd = tr.querySelector('.status');
                        statusTd.innerHTML = '🗑 محذوف';
                        statusTd.className = 'status status-deleted';
                        const actionTd = tr.querySelector('td:last-child');
                        actionTd.innerHTML = '<span class="small">محذوف نهائياً</span>';
                        cb.checked = false;
                    });
                }
                selectAllCheckbox.checked = false;
                alert('✅ ' + (json.message || 'تم الحذف بنجاح'));
            } else {
                alert('❌ ' + (json.message || 'فشل الحذف'));
            }
        } catch (e) {
            alert('❌ خطأ في الاتصال بالخادم');
        }
    });
    </script>
</body>
</html>