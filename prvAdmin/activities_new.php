<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json; charset=utf-8');

    if ($_POST['action'] === 'import_json') {
        handleImportJSON();
        exit;
    } elseif ($_POST['action'] === 'delete') {
        handleDelete();
        exit;
    } elseif ($_POST['action'] === 'toggle') {
        handleToggle();
        exit;
    }
}

function handleImportJSON() {
    global $pdo;
    
    try {
        $jsonFile = __DIR__ . '/fetch-activities.json';
        
        if (!file_exists($jsonFile)) {
            echo json_encode([
                'success' => false,
                'message' => 'JSON file not found: ' . $jsonFile,
                'error' => 'FILE_NOT_FOUND'
            ]);
            return;
        }

        $jsonContent = file_get_contents($jsonFile);
        if ($jsonContent === false) {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to read JSON file',
                'error' => 'READ_FAILED'
            ]);
            return;
        }

        $jsonData = json_decode($jsonContent, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode([
                'success' => false,
                'message' => 'JSON parse error: ' . json_last_error_msg(),
                'error' => 'INVALID_JSON'
            ]);
            return;
        }

        $activities = $jsonData['activities'] ?? [];
        if (empty($activities)) {
            echo json_encode([
                'success' => false,
                'message' => 'No activities found in JSON file',
                'error' => 'EMPTY_ACTIVITIES'
            ]);
            return;
        }

        $stats = processActivities($pdo, $activities);

        echo json_encode([
            'success' => true,
            'summary' => $stats['summary'],
            'errors' => $stats['errors'],
            'debug' => $stats['debug']
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage(),
            'error' => 'EXCEPTION',
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ], JSON_UNESCAPED_UNICODE);
    }
}

function processActivities($pdo, $activities) {
    $stats = [
        'summary' => [
            'imported' => 0,
            'skipped' => 0,
            'total_errors' => 0,
            'total_processed' => 0
        ],
        'errors' => [],
        'debug' => []
    ];

    foreach ($activities as $index => $activity) {
        $title = $activity['title'] ?? 'Untitled';
        $activityDebug = [
            'index' => $index + 1,
            'title' => $title,
            'steps' => [],
            'status' => 'pending'
        ];

        try {
            if (empty($title)) {
                $activityDebug['status'] = 'skipped';
                $activityDebug['reason'] = 'Title missing';
                $stats['debug'][] = $activityDebug;
                $stats['summary']['total_processed']++;
                continue;
            }

            $stmt = $pdo->prepare('SELECT id FROM listings WHERE title = ? LIMIT 1');
            $stmt->execute([$title]);
            if ($stmt->fetch()) {
                $stats['summary']['skipped']++;
                $activityDebug['status'] = 'skipped';
                $activityDebug['reason'] = 'Activity already exists';
                $stats['debug'][] = $activityDebug;
                $stats['summary']['total_processed']++;
                continue;
            }

            $cityName = $activity['location']['city'] ?? '';
            if (empty($cityName)) {
                throw new Exception("City name missing");
            }

            $stmt = $pdo->prepare('SELECT id FROM cities WHERE name = ? LIMIT 1');
            $stmt->execute([$cityName]);
            $city = $stmt->fetch();
            
            if (!$city) {
                throw new Exception("City '{$cityName}' not found");
            }
            $activityDebug['steps'][] = [
                'step' => 'Search for city',
                'status' => 'Success',
                'city_id' => $city['id']
            ];

            $typeId = $activity['type']['id'] ?? 1;
            $stmt = $pdo->prepare('SELECT id FROM listing_types WHERE id = ? LIMIT 1');
            $stmt->execute([$typeId]);
            $type = $stmt->fetch();
            
            if (!$type) {
                $typeId = 1;
            }
            $activityDebug['steps'][] = [
                'step' => 'Search for listing type',
                'status' => 'Success',
                'type_id' => $typeId
            ];

            $stmt = $pdo->prepare('
                INSERT INTO listings 
                (business_id, listing_type_id, city_id, title, description, address, latitude, longitude, status, created_at, updated_at)
                VALUES (1, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
            ');
            
            $stmt->execute([
                $typeId,
                $city['id'],
                $title,
                $activity['description'] ?? '',
                $activity['location']['address'] ?? '',
                $activity['location']['latitude'] ?? null,
                $activity['location']['longitude'] ?? null,
                'active'
            ]);

            $listingId = $pdo->lastInsertId();
            if (!$listingId) {
                throw new Exception("Failed to get listing ID");
            }
            
            $activityDebug['steps'][] = [
                'step' => 'Insert activity',
                'status' => 'Success',
                'listing_id' => $listingId
            ];

            $attachedCount = 0;
            if (!empty($activity['categories'])) {
                foreach ($activity['categories'] as $category) {
                    $catId = $category['id'] ?? null;
                    if ($catId) {
                        try {
                            $stmt = $pdo->prepare('INSERT IGNORE INTO listing_category (listing_id, category_id) VALUES (?, ?)');
                            $stmt->execute([$listingId, $catId]);
                            $attachedCount++;
                        } catch (Exception $e) {
                        }
                    }
                }
            }
            
            $activityDebug['steps'][] = [
                'step' => 'Attach categories',
                'status' => 'Success',
                'count' => $attachedCount,
                'message' => $attachedCount > 0 ? "Attached $attachedCount categories" : 'No categories'
            ];

            $imagesCount = 0;
            if (!empty($activity['images'])) {
                $privateDir = dirname(__DIR__) . '/storage/app/private';
                if (!is_dir($privateDir)) {
                    @mkdir($privateDir, 0755, true);
                }

                foreach ($activity['images'] as $image) {
                    $imageUrl = $image['url'] ?? '';
                    if (empty($imageUrl)) continue;

                    try {
                        $context = stream_context_create([
                            'http' => ['timeout' => 15]
                        ]);
                        
                        $imageContent = @file_get_contents($imageUrl, false, $context);
                        if ($imageContent === false) {
                            continue;
                        }

                        $fileName = 'activity-' . bin2hex(random_bytes(6)) . '.jpg';
                        $filePath = $privateDir . '/' . $fileName;
                        
                        if (file_put_contents($filePath, $imageContent)) {
                            $stmt = $pdo->prepare('
                                INSERT INTO media 
                                (model_type, model_id, collection_name, name, file_name, mime_type, disk, size, created_at, updated_at)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
                            ');
                            
                            $stmt->execute([
                                'App\\Models\\Listing\\Listing',
                                $listingId,
                                'images',
                                $image['name'] ?? 'activity-image',
                                $fileName,
                                'image/jpeg',
                                'private',
                                filesize($filePath)
                            ]);
                            $imagesCount++;
                        }
                    } catch (Exception $e) {
                    }
                }
            }
            
            $activityDebug['steps'][] = [
                'step' => 'Upload images',
                'status' => 'Success',
                'count' => $imagesCount,
                'message' => $imagesCount > 0 ? "Uploaded $imagesCount images" : 'No images'
            ];

            $stats['summary']['imported']++;
            $activityDebug['status'] = 'Success';

        } catch (Exception $e) {
            $stats['errors'][] = [
                'index' => $index + 1,
                'title' => $title,
                'error' => $e->getMessage(),
                'steps' => $activityDebug['steps'],
                'timestamp' => date('Y-m-d H:i:s')
            ];
            $stats['summary']['total_errors']++;
            $activityDebug['status'] = 'Failed';
            $activityDebug['error'] = $e->getMessage();
        }

        $stats['debug'][] = $activityDebug;
        $stats['summary']['total_processed']++;
    }

    return $stats;
}

function handleDelete() {
    global $pdo;

    $ids = isset($_POST['ids']) ? (array)$_POST['ids'] : [];
    $method = isset($_POST['delete_method']) ? $_POST['delete_method'] : 'soft';

    if (empty($ids)) {
        echo json_encode([
            'success' => false,
            'message' => 'No activities selected'
        ]);
        return;
    }

    $ids = array_map('intval', array_filter($ids));
    if (empty($ids)) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid IDs'
        ]);
        return;
    }

    try {
        $placeholders = rtrim(str_repeat('?,', count($ids)), ',');

        if ($method === 'hard') {
            $stmt = $pdo->prepare("DELETE FROM media WHERE model_id IN ($placeholders) AND model_type = 'App\\\\Models\\\\Listing\\\\Listing'");
            $stmt->execute($ids);
            
            $stmt = $pdo->prepare("DELETE FROM listings WHERE id IN ($placeholders)");
            $stmt->execute($ids);
        } else {
            $stmt = $pdo->prepare("UPDATE listings SET status = 'deleted' WHERE id IN ($placeholders)");
            $stmt->execute($ids);
        }

        echo json_encode([
            'success' => true,
            'deleted_ids' => $ids,
            'method' => $method,
            'message' => $method === 'hard' ? 'Permanently deleted' : 'Moved to trash'
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
}

function handleToggle() {
    global $pdo;

    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    
    if ($id <= 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid ID'
        ]);
        return;
    }

    try {
        $stmt = $pdo->prepare('SELECT status FROM listings WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        
        if (!$row) {
            echo json_encode([
                'success' => false,
                'message' => 'Activity not found'
            ]);
            return;
        }

        $newStatus = ($row['status'] === 'published') ? 'suspended' : 'published';
        $stmt = $pdo->prepare('UPDATE listings SET status = ? WHERE id = ?');
        $stmt->execute([$newStatus, $id]);

        echo json_encode([
            'success' => true,
            'id' => $id,
            'status' => $newStatus
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
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
    echo "Error: " . htmlspecialchars($e->getMessage());
    exit;
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Management</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Tajawal', 'Arial', sans-serif; background: #f5f5f5; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        h1 { color: #333; margin-bottom: 20px; }
        
        .controls { display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
        .btn { padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
        .btn-primary { background: #2b73d2; color: white; }
        .btn-success { background: #27ae60; color: white; }
        .btn-danger { background: #e74c3c; color: white; }
        .btn:hover { opacity: 0.9; }
        .btn:disabled { opacity: 0.5; cursor: not-allowed; }

        .result-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .summary-item {
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            color: white;
        }

        .summary-item.success { background: #27ae60; }
        .summary-item.warning { background: #f39c12; }
        .summary-item.error { background: #e74c3c; }
        .summary-item.info { background: #0366d6; }

        .summary-item strong { display: block; font-size: 24px; margin-bottom: 5px; }

        .error-card {
            background: #fff5f5;
            border-left: 4px solid #e74c3c;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 4px;
            cursor: pointer;
        }

        .error-card:hover { background: #ffe6e6; }

        .error-title { font-weight: bold; color: #c0392b; margin-bottom: 5px; }
        .error-message { color: #e74c3c; font-size: 14px; }

        table {
            width: 100%;
            background: white;
            border-collapse: collapse;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-top: 20px;
        }

        th {
            background: #333;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: bold;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }

        tr:hover { background: #f9f9f9; }

        .status {
            padding: 4px 8px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
        }

        .status.published { background: #d4edda; color: #155724; }
        .status.suspended { background: #fff3cd; color: #856404; }
        .status.deleted { background: #f8d7da; color: #721c24; }

        .loading { display: inline-block; width: 20px; height: 20px; border: 3px solid #f3f3f3; border-top: 3px solid #2b73d2; border-radius: 50%; animation: spin 1s linear infinite; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body>

<div class="container">
    <h1>Activity Management</h1>

    <div class="controls">
        <button class="btn btn-success" id="importJsonBtn">
            Import from JSON
        </button>
        <button class="btn btn-danger" id="deleteSelectedBtn">
            Delete Selected
        </button>
        <select id="deleteMethod" style="padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            <option value="soft">Soft Delete (Trash)</option>
            <option value="hard">Hard Delete</option>
        </select>
    </div>

    <div id="resultContainer"></div>

    <?php if (!empty($listings)): ?>
        <table>
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listings as $listing): ?>
                    <tr data-id="<?= $listing['id'] ?>">
                        <td><input type="checkbox" class="row-checkbox" value="<?= $listing['id'] ?>"></td>
                        <td><?= htmlspecialchars($listing['title']) ?></td>
                        <td>
                            <span class="status <?= strtolower($listing['status']) ?>">
                                <?php
                                    if ($listing['status'] === 'published') echo 'Published';
                                    elseif ($listing['status'] === 'suspended') echo 'Suspended';
                                    else echo 'Deleted';
                                ?>
                            </span>
                        </td>
                        <td><?= date('Y-m-d H:i', strtotime($listing['created_at'])) ?></td>
                        <td>
                            <button class="btn btn-primary toggle-btn" data-id="<?= $listing['id'] ?>">Toggle</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="result-container">
            <p style="text-align: center; color: #999;">No activities found</p>
        </div>
    <?php endif; ?>
</div>

<script>
document.getElementById('importJsonBtn').addEventListener('click', async function() {
    if (!confirm('Do you want to import activities from JSON?')) return;
    
    this.disabled = true;
    this.innerHTML = 'Importing...';
    
    try {
        const formData = new FormData();
        formData.append('action', 'import_json');
        
        const response = await fetch(window.location.href, {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        displayResults(data);
        
    } catch (error) {
        console.error(error);
        showError('Connection error: ' + error.message);
    } finally {
        this.disabled = false;
        this.innerHTML = 'Import from JSON';
    }
});

document.getElementById('deleteSelectedBtn').addEventListener('click', async function() {
    const selected = document.querySelectorAll('.row-checkbox:checked');
    if (selected.length === 0) {
        alert('Select at least one activity');
        return;
    }
    
    if (!confirm(`Are you sure you want to delete ${selected.length} activity(s)?`)) return;
    
    const ids = Array.from(selected).map(cb => cb.value);
    const method = document.getElementById('deleteMethod').value;
    
    const formData = new FormData();
    formData.append('action', 'delete');
    formData.append('delete_method', method);
    ids.forEach(id => formData.append('ids[]', id));
    
    try {
        const response = await fetch(window.location.href, {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    } catch (error) {
        alert('Error: ' + error.message);
    }
});

document.querySelectorAll('.toggle-btn').forEach(btn => {
    btn.addEventListener('click', async function() {
        const id = this.dataset.id;
        const formData = new FormData();
        formData.append('action', 'toggle');
        formData.append('id', id);
        
        try {
            const response = await fetch(window.location.href, {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        } catch (error) {
            alert('Error: ' + error.message);
        }
    });
});

function displayResults(data) {
    const container = document.getElementById('resultContainer');
    let html = '';
    
    if (data.success) {
        html += `
            <div class="result-container">
                <div class="summary-grid">
                    <div class="summary-item success">
                        <strong>${data.summary.imported}</strong>
                        <span>Imported</span>
                    </div>
                    <div class="summary-item warning">
                        <strong>${data.summary.skipped}</strong>
                        <span>Skipped</span>
                    </div>
                    <div class="summary-item error">
                        <strong>${data.summary.total_errors}</strong>
                        <span>Errors</span>
                    </div>
                </div>
        `;
        
        if (data.errors.length > 0) {
            html += '<h3 style="color: #e74c3c; margin-top: 20px;">Errors:</h3>';
            data.errors.forEach(error => {
                html += `
                    <div class="error-card" onclick="this.classList.toggle('expanded')">
                        <div class="error-title">#${error.index}: ${error.title}</div>
                        <div class="error-message">${error.error}</div>
                        <div style="display: none;" class="error-details">
                            <strong>Steps:</strong>
                            <ul style="margin: 10px 0;">
                `;
                error.steps.forEach(step => {
                    html += `<li>${step.step}: ${step.status}</li>`;
                });
                html += `</ul></div></div>`;
            });
        }
        
        html += '</div>';
        container.innerHTML = html;
        
        setTimeout(() => {
            if (data.summary.imported > 0) {
                location.reload();
            }
        }, 2000);
    } else {
        showError(data.message);
    }
}

function showError(message) {
    document.getElementById('resultContainer').innerHTML = `
        <div class="result-container" style="background: #ffe6e6; border-left: 4px solid #e74c3c;">
            <h3 style="color: #c0392b;">Error</h3>
            <p>${message}</p>
        </div>
    `;
}

document.getElementById('selectAll').addEventListener('change', function() {
    document.querySelectorAll('.row-checkbox').forEach(cb => {
        cb.checked = this.checked;
    });
});
</script>

</body>
</html>