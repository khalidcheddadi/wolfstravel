<?php
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

try {
    $pdo->exec("ALTER TABLE users ADD COLUMN IF NOT EXISTS activation_token VARCHAR(100) NULL");
} catch (PDOException $e) {
    // تجاهل
}
?>