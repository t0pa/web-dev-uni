<?php
$host = getenv('DB_HOST') ?: 'your-db-host';
$port = getenv('DB_PORT') ?: 25060;
$user = getenv('DB_USER') ?: 'your-db-user';
$password = getenv('DB_PASSWORD') ?: 'your-db-password';
$dbname = getenv('DB_NAME') ?: 'your-db-name';

$dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $password);
    echo "Connection successful!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
