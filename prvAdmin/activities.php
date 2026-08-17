<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/db.php';

if (file_exists(dirname(__DIR__) . '/vendor/autoload.php')) {
    require_once dirname(__DIR__) . '/vendor/autoload.php';
}

if (file_exists(dirname(__DIR__) . '/bootstrap/app.php')) {
    $app = require dirname(__DIR__) . '/bootstrap/app.php';
    $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
}

function generateUuid(): string {
    $data = random_bytes(16);
    $data[6] = chr((ord($data[6]) & 0x0f) | 0x40);
    $data[8] = chr((ord($data[8]) & 0x3f) | 0x80);
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

function slugifyTitle(string $text): string {
    $text = trim($text);
    if ($text === '') {
        return 'activity-' . bin2hex(random_bytes(4));
    }
    $slug = preg_replace('/[^\pL\pN]+/u', '-', $text);
    $slug = trim((string) $slug, '-');
    $slug = preg_replace('/-+/', '-', $slug);
    $slug = strtolower($slug);
    return $slug !== '' ? $slug : 'activity-' . bin2hex(random_bytes(4));
}

function findCityRow(PDO $pdo, array $activity): ?array {
    $location = $activity['location'] ?? [];
    $candidates = [];
    if (!empty($location['city_id'])) {
        $candidates[] = ['type' => 'id', 'value' => (int) $location['city_id']];
    }
    foreach ([$location['city'] ?? '', $location['city_name'] ?? '', $location['slug'] ?? ''] as $candidate) {
        $candidate = trim((string) $candidate);
        if ($candidate !== '') {
            $candidates[] = ['type' => 'name', 'value' => $candidate];
            $candidates[] = ['type' => 'slug', 'value' => strtolower(str_replace(' ', '-', $candidate))];
        }
    }
    foreach ($candidates as $candidate) {
        if ($candidate['type'] === 'id') {
            $stmt = $pdo->prepare('SELECT id, name, slug, country_id FROM cities WHERE id = ? LIMIT 1');
            $stmt->execute([$candidate['value']]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) return $row;
        } else {
            $stmt = $pdo->prepare('SELECT id, name, slug, country_id FROM cities WHERE name = ? OR slug = ? LIMIT 1');
            $stmt->execute([$candidate['value'], strtolower(str_replace(' ', '-', $candidate['value']))]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) return $row;
        }
    }
    return null;
}

function createUniqueListingSlug(PDO $pdo, string $title): string {
    $base = slugifyTitle($title);
    $slug = $base;
    $suffix = 1;
    while (true) {
        $stmt = $pdo->prepare('SELECT id FROM listings WHERE slug = ? LIMIT 1');
        $stmt->execute([$slug]);
        if (!$stmt->fetch()) return $slug;
        $slug = $base . '-' . $suffix;
        $suffix++;
    }
}

function createUniqueBusinessSlug(PDO $pdo, string $name): string {
    $base = slugifyTitle($name);
    $slug = $base;
    $suffix = 1;
    while (true) {
        $stmt = $pdo->prepare('SELECT id FROM businesses WHERE slug = ? LIMIT 1');
        $stmt->execute([$slug]);
        if (!$stmt->fetch()) return $slug;
        $slug = $base . '-' . $suffix;
        $suffix++;
    }
}

function getOrCreateBusiness(PDO $pdo, array $businessData): ?int {
    if (empty($businessData) || empty($businessData['name'])) return null;
    $businessName = $businessData['name'];
    $businessEmail = $businessData['email'] ?? '';
    $businessPhone = $businessData['phone'] ?? '';
    if (!empty($businessEmail)) {
        $stmt = $pdo->prepare('SELECT id FROM businesses WHERE email = ? LIMIT 1');
        $stmt->execute([$businessEmail]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($existing) return (int) $existing['id'];
    }
    try {
        $uuid = generateUuid();
        $slug = createUniqueBusinessSlug($pdo, $businessName);
        $password = !empty($businessData['password']) ? password_hash($businessData['password'], PASSWORD_BCRYPT) : '';
        $stmt = $pdo->prepare('
            INSERT INTO businesses
            (uuid, owner_id, business_name, slug, email, phone, status, verified, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ');
        $stmt->execute([
            $uuid,
            1,
            $businessName,
            $slug,
            $businessEmail,
            $businessPhone,
            'active',
            0
        ]);
        return (int) $pdo->lastInsertId();
    } catch (Exception $e) {
        return null;
    }
}

function findOrCreateUser(PDO $pdo, string $email, string $name): int {
    $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($existing) return (int) $existing['id'];
    $password = password_hash(bin2hex(random_bytes(8)), PASSWORD_BCRYPT);
    $stmt = $pdo->prepare('
        INSERT INTO users (name, email, password, email_verified_at, created_at, updated_at)
        VALUES (?, ?, ?, NOW(), NOW(), NOW())
    ');
    $stmt->execute([$name, $email, $password]);
    return (int) $pdo->lastInsertId();
}

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
                'message' => 'ملف JSON غير موجود: ' . $jsonFile,
                'error' => 'FILE_NOT_FOUND'
            ]);
            return;
        }
        $jsonContent = file_get_contents($jsonFile);
        if ($jsonContent === false) {
            echo json_encode([
                'success' => false,
                'message' => 'فشل في قراءة ملف JSON',
                'error' => 'READ_FAILED'
            ]);
            return;
        }
        $jsonData = json_decode($jsonContent, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode([
                'success' => false,
                'message' => 'خطأ في صيغة JSON: ' . json_last_error_msg(),
                'error' => 'INVALID_JSON'
            ]);
            return;
        }
        $activities = $jsonData['activities'] ?? [];
        if (empty($activities)) {
            echo json_encode([
                'success' => false,
                'message' => 'لا توجد أنشطة في ملف JSON',
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
        $title = $activity['title'] ?? 'بدون عنوان';
        $activityDebug = [
            'index' => $index + 1,
            'title' => $title,
            'steps' => [],
            'status' => 'pending'
        ];

        try {
            if (empty($title)) {
                $activityDebug['status'] = 'skipped';
                $activityDebug['reason'] = 'العنوان مفقود';
                $stats['debug'][] = $activityDebug;
                $stats['summary']['total_processed']++;
                continue;
            }

            $stmt = $pdo->prepare('SELECT id FROM listings WHERE title = ? LIMIT 1');
            $stmt->execute([$title]);
            if ($stmt->fetch()) {
                $stats['summary']['skipped']++;
                $activityDebug['status'] = 'skipped';
                $activityDebug['reason'] = 'النشاط موجود بالفعل';
                $stats['debug'][] = $activityDebug;
                $stats['summary']['total_processed']++;
                continue;
            }

            $cityName = $activity['location']['city'] ?? '';
            if (empty($cityName)) throw new Exception("اسم المدينة مفقود");
            $city = findCityRow($pdo, $activity);
            if (!$city) throw new Exception("المدينة '{$cityName}' غير موجودة في قاعدة البيانات");
            $activityDebug['steps'][] = [
                'step' => 'البحث عن المدينة',
                'status' => 'نجح',
                'city_id' => $city['id'],
                'country_id' => $city['country_id'] ?? null,
                'city_name' => $cityName
            ];

            $typeId = $activity['type']['id'] ?? 1;
            $stmt = $pdo->prepare('SELECT id FROM listing_types WHERE id = ? LIMIT 1');
            $stmt->execute([$typeId]);
            $type = $stmt->fetch();
            if (!$type) $typeId = 1;
            $activityDebug['steps'][] = [
                'step' => 'البحث عن نوع الإدراج',
                'status' => 'نجح',
                'type_id' => $typeId
            ];

            $businessData = $activity['business'] ?? [];
            $businessId = null;
            $businessDebug = ['business_name' => $businessData['name'] ?? 'N/A'];
            if (!empty($businessData)) {
                $businessId = getOrCreateBusiness($pdo, $businessData);
                if ($businessId) {
                    $businessDebug['business_id'] = $businessId;
                    $businessDebug['status'] = 'تم الإنشاء/الحصول';
                } else {
                    $businessDebug['status'] = 'فشل - سيتم استخدام المنشأة الافتراضية';
                }
            }
            if (!$businessId) {
                $businessId = 1;
                $businessDebug['status'] = 'تم استخدام المنشأة الافتراضية';
            }
            $activityDebug['steps'][] = [
                'step' => 'إنشاء / البحث عن المنشأة',
                'status' => 'نجح',
                'business' => $businessDebug
            ];

            $uuid = generateUuid();
            $slug = createUniqueListingSlug($pdo, $title);
            $shortDescription = $activity['short_description'] ?? '';
            $description = $activity['description'] ?? '';
            $address = $activity['location']['address'] ?? '';
            $latitude = $activity['location']['latitude'] ?? null;
            $longitude = $activity['location']['longitude'] ?? null;

            $stmt = $pdo->prepare('
                INSERT INTO listings
                (uuid, business_id, listing_type_id, city_id, country_id, slug, title, short_description, description, address, latitude, longitude, status, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
            ');
            $stmt->execute([
                $uuid,
                $businessId,
                $typeId,
                $city['id'],
                $city['country_id'] ?? null,
                $slug,
                $title,
                $shortDescription,
                $description,
                $address,
                $latitude,
                $longitude,
                'published'
            ]);

            $listingId = $pdo->lastInsertId();
            if (!$listingId) throw new Exception("فشل الحصول على معرف النشاط");
            $activityDebug['steps'][] = [
                'step' => 'إدراج النشاط',
                'status' => 'نجح',
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
                        } catch (Exception $e) {}
                    }
                }
            }
            $activityDebug['steps'][] = [
                'step' => 'إرفاق الفئات',
                'status' => 'نجح',
                'count' => $attachedCount,
                'message' => $attachedCount > 0 ? "تم إرفاق $attachedCount فئة" : 'لا توجد فئات'
            ];

            // ==================== معالجة المميزات (features) ====================
            $featuresCount = 0;
            if (!empty($activity['features']) && is_array($activity['features'])) {
                foreach ($activity['features'] as $featureData) {
                    try {
                        $featureSlug = $featureData['slug'] ?? null;
                        if (empty($featureSlug)) continue;

                        // البحث عن الميزة في جدول listing_features
                        $stmt = $pdo->prepare('SELECT id FROM listing_features WHERE slug = ? LIMIT 1');
                        $stmt->execute([$featureSlug]);
                        $featureRow = $stmt->fetch(PDO::FETCH_ASSOC);
                        if (!$featureRow) {
                            // يمكن تسجيل تحذير ولكن لا نوقف العملية
                            continue;
                        }
                        $featureId = $featureRow['id'];
                        $featureValue = $featureData['value'] ?? null;

                        // إدراج القيمة في listing_feature_values
                        $stmt = $pdo->prepare('
                            INSERT INTO listing_feature_values (listing_id, feature_id, value, created_at, updated_at)
                            VALUES (?, ?, ?, NOW(), NOW())
                            ON DUPLICATE KEY UPDATE value = ?, updated_at = NOW()
                        ');
                        $stmt->execute([$listingId, $featureId, $featureValue, $featureValue]);
                        $featuresCount++;
                    } catch (Exception $e) {
                        // لا نوقف العملية بسبب خطأ في ميزة واحدة
                    }
                }
            }
            $activityDebug['steps'][] = [
                'step' => 'إضافة المميزات',
                'status' => 'نجح',
                'count' => $featuresCount,
                'message' => $featuresCount > 0 ? "تم إضافة $featuresCount ميزة" : 'لا توجد ميزات'
            ];
            // ================================================================

            $imagesCount = 0;
            if (!empty($activity['images'])) {
                $listingModel = null;
                if (class_exists('App\\Models\\Listing\\Listing')) {
                    $listingModel = \App\Models\Listing\Listing::find($listingId);
                }
                foreach ($activity['images'] as $image) {
                    $imageUrl = $image['url'] ?? '';
                    if (empty($imageUrl)) continue;
                    try {
                        if ($listingModel) {
                            $listingModel->addMediaFromUrl($imageUrl)
                                ->usingName($image['name'] ?? 'activity-image')
                                ->toMediaCollection('images');
                            $imagesCount++;
                        }
                    } catch (Exception $e) {}
                }
            }
            $activityDebug['steps'][] = [
                'step' => 'تحميل الصور',
                'status' => 'نجح',
                'count' => $imagesCount,
                'message' => $imagesCount > 0 ? "تم تحميل $imagesCount صورة" : 'لا توجد صور'
            ];

            $reviewRatings = [];
            if (!empty($activity['reviews_list']) && is_array($activity['reviews_list'])) {
                $reviewsInserted = 0;
                foreach ($activity['reviews_list'] as $reviewData) {
                    try {
                        $userEmail = $reviewData['user_email'] ?? '';
                        $userName = $reviewData['user_name'] ?? 'Guest';
                        if (empty($userEmail)) continue;
                        $userId = findOrCreateUser($pdo, $userEmail, $userName);
                        $rating = (float)($reviewData['rating'] ?? 0);
                        $titleReview = $reviewData['title'] ?? '';
                        $bodyReview = $reviewData['body'] ?? '';
                        $createdAt = $reviewData['created_at'] ?? date('Y-m-d H:i:s');

                        $stmt = $pdo->prepare('
                            INSERT INTO reviews
                            (listing_id, user_id, rating, title, body, status, created_at, updated_at)
                            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
                        ');
                        $stmt->execute([
                            $listingId,
                            $userId,
                            $rating,
                            $titleReview,
                            $bodyReview,
                            'published',
                            $createdAt
                        ]);
                        $reviewRatings[] = $rating;
                        $reviewsInserted++;
                    } catch (Exception $e) {}
                }
                $activityDebug['steps'][] = [
                    'step' => 'إدراج التعليقات',
                    'status' => 'نجح',
                    'count' => $reviewsInserted,
                    'message' => "تم إدراج $reviewsInserted تعليق"
                ];

                if (!empty($reviewRatings)) {
                    $avgRating = round(array_sum($reviewRatings) / count($reviewRatings), 1);
                    $totalReviews = count($reviewRatings);
                    $stmt = $pdo->prepare('UPDATE listings SET average_rating = ?, total_reviews = ? WHERE id = ?');
                    $stmt->execute([$avgRating, $totalReviews, $listingId]);
                    $activityDebug['steps'][] = [
                        'step' => 'تحديث التقييم',
                        'status' => 'نجح',
                        'average_rating' => $avgRating,
                        'total_reviews' => $totalReviews
                    ];
                }
            } else {
                $activityDebug['steps'][] = [
                    'step' => 'إدراج التعليقات',
                    'status' => 'لا توجد تعليقات',
                    'message' => 'لا توجد تعليقات في ملف JSON'
                ];
            }

            $stats['summary']['imported']++;
            $activityDebug['status'] = '✅ نجح';

        } catch (Exception $e) {
            $stats['errors'][] = [
                'index' => $index + 1,
                'title' => $title,
                'error' => $e->getMessage(),
                'steps' => $activityDebug['steps'],
                'timestamp' => date('Y-m-d H:i:s')
            ];
            $stats['summary']['total_errors']++;
            $activityDebug['status'] = '❌ فشل';
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
        echo json_encode(['success' => false, 'message' => 'لم يتم تحديد أي نشاط']);
        return;
    }
    $ids = array_map('intval', array_filter($ids));
    if (empty($ids)) {
        echo json_encode(['success' => false, 'message' => 'معرفات غير صالحة']);
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
            'message' => $method === 'hard' ? 'تم الحذف النهائي' : 'تم النقل للسلة'
        ]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

function handleToggle() {
    global $pdo;
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    if ($id <= 0) {
        echo json_encode(['success' => false, 'message' => 'معرف غير صالح']);
        return;
    }
    try {
        $stmt = $pdo->prepare('SELECT status FROM listings WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if (!$row) {
            echo json_encode(['success' => false, 'message' => 'النشاط غير موجود']);
            return;
        }
        $newStatus = ($row['status'] === 'published') ? 'suspended' : 'published';
        $stmt = $pdo->prepare('UPDATE listings SET status = ? WHERE id = ?');
        $stmt->execute([$newStatus, $id]);
        echo json_encode(['success' => true, 'id' => $id, 'status' => $newStatus]);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
}

function resolveStoredMediaImage(string $disk, string $fileName): string {
    if (empty($fileName)) return '';
    $candidate = dirname(__DIR__) . '/storage/app/' . $disk . '/' . $fileName;
    if (!is_file($candidate)) return '';
    try {
        $content = file_get_contents($candidate);
        if ($content === false) return '';
        return 'data:image/jpeg;base64,' . base64_encode($content);
    } catch (Throwable $e) {
        return '';
    }
}

$listings = [];
try {
    $query = "
        SELECT
            l.id,
            l.title,
            l.description,
            l.status,
            l.created_at,
            c.name AS city_name,
            m.file_name,
            m.disk
        FROM listings l
        LEFT JOIN cities c ON c.id = l.city_id
        LEFT JOIN media m ON l.id = m.model_id
            AND m.model_type = 'App\\Models\\Listing\\Listing'
            AND m.collection_name = 'images'
        ORDER BY l.created_at DESC, m.id
    ";
    $stmt = $pdo->query($query);
    $allRows = $stmt->fetchAll();
    $listingsMap = [];
    foreach ($allRows as $row) {
        $listingId = (int) $row['id'];
        if (!isset($listingsMap[$listingId])) {
            $listingsMap[$listingId] = [
                'id' => $listingId,
                'title' => $row['title'],
                'description' => $row['description'] ?? '',
                'city_name' => $row['city_name'] ?? '',
                'status' => $row['status'],
                'created_at' => $row['created_at'],
                'image' => '',
                'images' => []
            ];
        }
        if ($row['file_name']) {
            $listingsMap[$listingId]['images'][] = [
                'file_name' => $row['file_name'],
                'disk' => $row['disk'] ?? 'private'
            ];
            if (empty($listingsMap[$listingId]['image'])) {
                $listingsMap[$listingId]['image'] = resolveStoredMediaImage(
                    $row['disk'] ?? 'private',
                    $row['file_name']
                );
            }
        }
    }
    $listings = array_values($listingsMap);
} catch (Exception $e) {
    echo "خطأ: " . htmlspecialchars($e->getMessage());
    exit;
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الأنشطة</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
            text-align: right;
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
    <h1>📊 إدارة الأنشطة</h1>

    <div class="controls">
        <button class="btn btn-success" id="importJsonBtn">
            <i class="fas fa-download"></i> استيراد من JSON
        </button>
        <button class="btn btn-danger" id="deleteSelectedBtn">
            <i class="fas fa-trash"></i> حذف المحدد
        </button>
        <select id="deleteMethod" style="padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            <option value="soft">حذف مؤقت (السلة)</option>
            <option value="hard">حذف نهائي</option>
        </select>
    </div>

    <div id="resultContainer"></div>

    <?php if (!empty($listings)): ?>
        <table>
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll"></th>
                    <th>العنوان</th>
                    <th>الحالة</th>
                    <th>التاريخ</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listings as $listing): ?>
                    <tr data-id="<?= $listing['id'] ?>">
                        <td><input type="checkbox" class="row-checkbox" value="<?= $listing['id'] ?>"></td>
                        <td style="width: 220px;">
                            <?php if (!empty($listing['image'])): ?>
                                <img src="<?= htmlspecialchars($listing['image']) ?>" alt="<?= htmlspecialchars($listing['title']) ?>" style="width: 100%; max-width: 180px; height: 110px; object-fit: cover; border-radius: 8px; display: block; margin-bottom: 8px;">
                            <?php else: ?>
                                <div style="width: 100%; max-width: 180px; height: 110px; border-radius: 8px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; color: #999; margin-bottom: 8px;">لا توجد صورة</div>
                            <?php endif; ?>
                            <div><strong>#{<?= $listing['id'] ?>}</strong></div>
                            <div><?= htmlspecialchars($listing['title']) ?></div>
                            <?php if (!empty($listing['city_name'])): ?>
                                <div style="font-size: 12px; color: #666; margin-top: 4px;">المدينة: <?= htmlspecialchars($listing['city_name']) ?></div>
                            <?php endif; ?>
                            <?php if (!empty($listing['description'])): ?>
                                <div style="font-size: 12px; color: #666; margin-top: 6px; line-height: 1.5; max-height: 60px; overflow: hidden;">
                                    <?= htmlspecialchars(substr(strip_tags($listing['description']), 0, 120)) ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="status <?= strtolower($listing['status']) ?>">
                                <?php
                                    if ($listing['status'] === 'published') echo '✓ منشور';
                                    elseif ($listing['status'] === 'suspended') echo '⏸ معلق';
                                    else echo '🗑 محذوف';
                                ?>
                            </span>
                        </td>
                        <td><?= date('Y-m-d H:i', strtotime($listing['created_at'])) ?></td>
                        <td>
                            <button class="btn btn-primary toggle-btn" data-id="<?= $listing['id'] ?>">تغيير</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="result-container">
            <p style="text-align: center; color: #999;">لا توجد أنشطة في النظام</p>
        </div>
    <?php endif; ?>
</div>

<script>
document.getElementById('importJsonBtn').addEventListener('click', async function() {
    if (!confirm('هل تريد استيراد الأنشطة من JSON؟')) return;
    this.disabled = true;
    this.innerHTML = '<span class="loading"></span> جاري الاستيراد...';
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
        showError('خطأ في الاتصال: ' + error.message);
    } finally {
        this.disabled = false;
        this.innerHTML = '<i class="fas fa-download"></i> استيراد من JSON';
    }
});

document.getElementById('deleteSelectedBtn').addEventListener('click', async function() {
    const selected = document.querySelectorAll('.row-checkbox:checked');
    if (selected.length === 0) {
        alert('اختر نشاط واحد على الأقل');
        return;
    }
    if (!confirm(`هل أنت متأكد من حذف ${selected.length} نشاط؟`)) return;
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
            alert('خطأ: ' + data.message);
        }
    } catch (error) {
        alert('خطأ: ' + error.message);
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
                alert('خطأ: ' + data.message);
            }
        } catch (error) {
            alert('خطأ: ' + error.message);
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
                        <span>تم استيراده</span>
                    </div>
                    <div class="summary-item warning">
                        <strong>${data.summary.skipped}</strong>
                        <span>تم تخطيه</span>
                    </div>
                    <div class="summary-item error">
                        <strong>${data.summary.total_errors}</strong>
                        <span>أخطاء</span>
                    </div>
                </div>
        `;
        if (data.errors.length > 0) {
            html += '<h3 style="color: #e74c3c; margin-top: 20px;">🔴 الأخطاء:</h3>';
            data.errors.forEach(error => {
                html += `
                    <div class="error-card" onclick="this.classList.toggle('expanded')">
                        <div class="error-title">#${error.index}: ${error.title}</div>
                        <div class="error-message">${error.error}</div>
                        <div style="display: none;" class="error-details">
                            <strong>الخطوات:</strong>
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
            <h3 style="color: #c0392b;">❌ خطأ</h3>
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
