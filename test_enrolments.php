<?php
// 設置測試環境
$_GET['action'] = 'enrolments';
$_GET['page'] = '1';
$_GET['limit'] = '5';
$_SERVER['REQUEST_METHOD'] = 'GET';

// 包含 API 文件
include 'api/index.php';
?>