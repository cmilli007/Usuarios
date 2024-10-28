<?php
session_start(); 

include 'conexao.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    if (isset($_POST['email']) && isset($_POST['senha'])) {
        $email = $_POST['email'];
        $senha = $_POST['senha'];

        // Altere a consulta para usar o email
        $stmt = $pdo->prepare("SELECT * FROM tb_usuarios WHERE email = :email");
        $stmt->execute(['email' => $email]);        
        $user = $stmt->fetch();

        if ($user) {
            if (password_verify($senha, $user['senha'])) { 
                $_SESSION['cd_cliente'] = $user['cd_cliente']; 
                header("Location: perfil.php"); 
                exit();
            } else {
                // Se a senha estiver incorreta, redireciona para a página de erro
                $mensagemErro = "Senha incorreta.";
                header("Location: erro_login.php?mensagem=" . urlencode($mensagemErro));
                exit();
            }
        } else {
            // Se não encontrar o usuário, redireciona para a página de erro
            $mensagemErro = "Usuário não encontrado.";
            header("Location: usuarios.html?mensagem=" . urlencode($mensagemErro));
            exit();
        }
        
    } else {
        // Se os campos não estiverem preenchidos, redireciona para a página de erro
        $mensagemErro = "Por favor, preencha todos os campos.";
        header("Location: erro_login.php?mensagem=" . urlencode($mensagemErro));
        exit();
    }
}
?>
