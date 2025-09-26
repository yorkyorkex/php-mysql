<?php
$_GET['action'] = 'enrolments';
$_GET['page'] = 1;
$_GET['limit'] = 5;
$_GET['user_name'] = 'j';
$_SERVER['REQUEST_METHOD'] = 'GET';
include 'api/index.php';
?>