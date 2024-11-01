<?php 
// Database Connection Settings
$host = 'localhost';
$dbname = 'user_auth_system';
$user = 'root';
$pass = 'root';
$port = '10076'; // I am using Locapwp thats why needed port number to connect

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $pass);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed:" . $e->getMessage());
}
?>