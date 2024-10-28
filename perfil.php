<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['cd_cliente'])) {
    header("Location: login.html"); // Redireciona para o login se não estiver logado
    exit();
}

$cd_cliente = $_SESSION['cd_cliente'];

// Consulta para obter os dados do usuário
$sql = "SELECT * FROM tb_usuarios WHERE cd_cliente = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cd_cliente); // "i" indica que é um inteiro
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    // Exibir informações do usuário
    echo "<h1>Bem-vindo, " . htmlspecialchars($user['nm_cliente']) . "</h1>";
    // Outras informações do perfil podem ser exibidas aqui
} else {
    echo "Usuário não encontrado.";
}
?>
