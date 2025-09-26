<?php
// 測試新的 MySQL API
$_GET['action'] = 'statistics';
$_SERVER['REQUEST_METHOD'] = 'GET';

include 'api/index.php';
?>