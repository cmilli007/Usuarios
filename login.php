<?php
session_start(); 

include 'conexao.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    if (isset($_POST['nm_cliente']) && isset($_POST['senha'])) {
        $nm_cliente = $_POST['nm_cliente'];
        $senha = $_POST['senha'];

        $stmt = $pdo->prepare("SELECT * FROM tb_vendedores WHERE login = :nm_cliente");
        $stmt->execute(['nm_cliente' => $nm_cliente]);        
        $user = $stmt->fetch();

        if ($user) {
            
            if (password_verify($senha, $user['senha'])) { 
                $_SESSION['cd_cliente'] = $user['cd_cliente']; 
                header("Location: perfil.php"); 
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
