<?php

$host = 'localhost'; 
$db   = 'bd_Sustentech'; 
$user = 'root';
$pass = 'root'; 
$charset = 'utf8mb4'; 

$dsn = "mysql:host=$host;dbname=$db;charset=$charset"; // Data Source Name
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Exibe erros
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Modo de fetch
    PDO::ATTR_EMULATE_PREPARES   => false, // Utiliza prepared statements
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options); // Cria a conexão
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode()); // Lida com erros de conexão
}
?>
