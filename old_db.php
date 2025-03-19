<?php



$host = "localhost";
$port = "8888";
$user = "eva";
$password = "JahodaMalina1";
$dbname = "cvicna_users";


try {
    $dsn = "mysql:host=$host;dbname=$dbname;port=$port";
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

