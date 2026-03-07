<?php
session_start();

$env = parse_ini_file('.env');

$host = $env['DB_HOST'];
$user = $env['DB_USER'];
$pass = $env['DB_PASS'];
$dbname = $env['DB_NAME'];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>