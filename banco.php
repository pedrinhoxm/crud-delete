<?php

$host = 'localhost';
$dbname = 'crud_php';
$username = 'root';
$password = ''; // Geralmente a senha é vazia no XAMPP

try {
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch(PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}
?>
