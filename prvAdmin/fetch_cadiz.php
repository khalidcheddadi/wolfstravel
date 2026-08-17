<?php
/**
 * جلب بيانات أماكن الإقامة من turispain.es وتخزينها في ملف JSON
 * المسار: /alojamientos/cadiz
 */

// 1. تحديد عنوان API الصحيح
// المسار المستخدم من قبل الموقع لجلب بيانات الخريطة
$api_url = 'https://turispain.es/wp-json/citadela-directory/map-data/points/citadela-item';

// إضافة بارامترات إضافية (قد تكون مطلوبة)
$query_params = http_build_query([
    'dataType'      => 'markers',
    'category'      => '',      // يمكن تركها فارغة لجلب الكل
    'location'      => '',      // يمكن تركها فارغة
    'only_featured' => 0
]);

$full_url = $api_url . '?' . $query_params;

// 2. جلب البيانات من API باستخدام cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $full_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // في بيئة التطوير فقط
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; YourBot/1.0)');

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// 3. التحقق من نجاح الطلب
if ($http_code !== 200) {
    die("❌ فشل جلب البيانات. رمز الحالة: $http_code");
}

// 4. تحويل البيانات من JSON إلى مصفوفة PHP
$data = json_decode($response, true);

if ($data === null) {
    die("❌ البيانات المسترجعة ليست بصيغة JSON صالحة.");
}

// 5. حفظ البيانات في ملف JSON
$output_file = 'alojamientos_cadiz.json';
$json_output = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

if (file_put_contents($output_file, $json_output)) {
    echo "✅ تم حفظ البيانات بنجاح في ملف: $output_file\n";
    echo "📊 عدد العناصر المسترجعة: " . (isset($data['total']) ? $data['total'] : count($data)) . "\n";
} else {
    echo "❌ فشل في حفظ الملف. تأكد من صلاحيات الكتابة في المجلد الحالي.\n";
}

// 6. (اختياري) عرض عينة من البيانات
if (!empty($data['points']) && is_array($data['points'])) {
    echo "\n📌 عينة من أول عنصرين:\n";
    print_r(array_slice($data['points'], 0, 2));
}
?>