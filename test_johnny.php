<?php
$_GET['action'] = 'enrolments';
$_GET['page'] = 1;
$_GET['limit'] = 1;
$_SERVER['REQUEST_METHOD'] = 'GET';
include 'api/index.php';
?>