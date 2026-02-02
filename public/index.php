<?php

require_once __DIR__ . '/../app/Core/Database.php';

$statement = $pdo->query("SHOW TABLES");
$tables = $statement->fetchall(PDO::FETCH_ASSOC);

echo "<pre>";
print_r($tables);
echo "</pre>";