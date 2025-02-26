<?php
$host = 'localhost';
$dbname = 'SizzleShare';
$username = 'root';
$password = 'Infiniti@111';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo 'Database Connected Successfully! Noice <br>';
} catch (PDOException $e) {
    die("Database connection failed, So Sad: " . $e->getMessage());
}
?>