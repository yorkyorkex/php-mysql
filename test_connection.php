<?php
// 測試 MySQL 連接的腳本
$host = 'localhost';
$db_name = 'course_management';
$username = 'root';

// 嘗試不同的密碼
$passwords = ['', 'root', 'password', '123456'];

foreach ($passwords as $password) {
    try {
        $conn = new PDO(
            "mysql:host=" . $host . ";dbname=" . $db_name,
            $username,
            $password
        );
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        echo "✅ 成功連接！密碼是: '" . ($password === '' ? '空密碼' : $password) . "'\n";
        
        // 測試查詢
        $stmt = $conn->query("SELECT COUNT(*) as count FROM users");
        $result = $stmt->fetch();
        echo "用戶數量: " . $result['count'] . "\n";
        
        exit(0);
        
    } catch(PDOException $e) {
        echo "❌ 密碼 '" . ($password === '' ? '空密碼' : $password) . "' 失敗: " . $e->getMessage() . "\n";
    }
}

echo "❌ 所有密碼都失敗了。請手動輸入正確的密碼。\n";
?>