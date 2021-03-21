<?php
$host = 'localhost';
$dbname = 'sms';
$user = 'root';
$password = '';

try {
    $dsn = "mysql:host=$host;dbname=$dbname";
    $conn = new PDO($dsn, $user, $password);
} catch (PDOException $e){
    die("Conexion fallida: " . $e->getMessage());
}