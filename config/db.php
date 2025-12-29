<?php
// 資料庫連線設定
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); // XAMPP 預設沒有密碼
define('DB_NAME', 'notebook_app');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            // ATTR_ERRMODE 設定錯誤模式為異常
            // ATTR_DEFAULT_FETCH_MODE 設定預設取回模式為關聯陣列
            // ATTR_EMULATE_PREPARES 關閉模擬預處理以提升安全性
            // FETCH_ASSOC 只回傳關聯陣列
            // PDD:: 靜態屬性存取
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    die("資料庫連線失敗: " . $e->getMessage());
}