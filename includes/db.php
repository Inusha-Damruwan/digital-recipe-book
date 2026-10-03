<?php
declare(strict_types=1);

$host = 'localhost';
$dbname = 'digital_recipe_book';
$dbuser = 'root';
$dbpass = ''; // Default XAMPP local setup. Add your MySQL password if you have one.

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
        $dbuser,
        $dbpass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => false, 'message' => 'Database connection failed. Check includes/db.php and import database.sql.']);
    exit;
}
