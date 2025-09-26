<?php
// 設置測試環境
$_GET['action'] = 'statistics';
$_SERVER['REQUEST_METHOD'] = 'GET';

// 包含 API 文件
include 'api/index.php';
?>