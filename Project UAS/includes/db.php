<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$db   = 'skill_connect'; // Nama database Anda
$user = 'root';          // User database
$pass = '';              // Password database (kosong untuk XAMPP)
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "Koneksi database berhasil!"; // Hapus baris ini setelah berhasil
} catch (\PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
?>