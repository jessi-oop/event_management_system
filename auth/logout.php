<?php

require_once __DIR__ . '/../app/Services/AuthService/logout.php';

$logout = new Logout();
$logout->logout();

header('Location: ../public/index.php');
exit;
