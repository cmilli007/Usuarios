<?php
session_start(); // Inicia a sessão

include 'conexao.php'; // Inclui o arquivo de conexão

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verifica se os campos estão definidos
    if (isset($_POST['nm_cliente']) && isset($_POST['senha'])) {
        $nm_cliente = $_POST['nm_cliente'];
        $senha = $_POST['senha'];

        // Prepara a consulta para verificar o usuário
        $stmt = $pdo->prepare("SELECT * FROM tb_vendedores WHERE login = :nm_cliente");
        $stmt->execute(['nm_cliente' => $nm_cliente]);        
        $user = $stmt->fetch();

        if ($user) {
            // Verifica se a senha está correta
            if (password_verify($senha, $user['senha'])) { // Verifica a senha com hash
                // Se o usuário for encontrado e a senha estiver correta, armazena os dados na sessão
                $_SESSION['cd_cliente'] = $user['cd_cliente']; // Ajuste conforme necessário
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
