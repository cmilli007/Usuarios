<?php
session_start();
include 'conexao.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    if (isset($_POST['login']) && isset($_POST['senha'])) {
        $login = $_POST['login'];
        $senha = $_POST['senha'];

        $stmt = $pdo->prepare("SELECT * FROM tb_usuarios WHERE login = :login");
        $stmt->execute(['login' => $login]);        
        $user = $stmt->fetch();

        if ($user) {
            if (password_verify($senha, $user['senha'])) { 
                $_SESSION['cd_cliente'] = $user['cd_cliente']; // Armazena o ID do usuário logado
                header("Location: perfil.php"); // Redireciona para a página de perfil
                exit();
            } else {
                // Se a senha estiver incorreta
                echo "<p>Senha incorreta.</p>";
            }
        } else {
            // Se não encontrar o usuário
            echo "<p>Usuário não encontrado.</p>";
        }
    } else {
        // Se os campos não estiverem preenchidos
        echo "<p>Por favor, preencha todos os campos.</p>";
    }
}
?>
