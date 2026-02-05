<?php

require_once __DIR__ . '../Services/AuthService.php';

$auth = new AuthService();
$auth->logout();

header('Location: ../public/index.php');
exit;
