<?php
// 測試註冊數據端點
$_GET['action'] = 'enrolments';
$_GET['page'] = '1';
$_GET['limit'] = '5';
$_SERVER['REQUEST_METHOD'] = 'GET';

include 'api/index.php';
?>