<?php
include 'conexao.php'; // Inclua o arquivo de conexão com o banco
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Verifica se o e-mail existe no banco de dados
    $stmt = $pdo->prepare("SELECT cd_cliente FROM tb_usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Gera um token único e um tempo de expiração
        $token = bin2hex(random_bytes(50)); // Token de 50 caracteres
        $expira_em = date("Y-m-d H:i:s", strtotime('+1 hour')); // Expira em 1 hora

        // Salva o token no banco de dados junto com o email
        $stmt = $pdo->prepare("INSERT INTO tb_redefinicao_senha (email, token, expira_em) VALUES (?, ?, ?)");
        $stmt->execute([$email, $token, $expira_em]);

        // Link de redefinição de senha
        $link = "http://seusite.com/redefinir_senha.php?token=" . $token;

        // Enviar o e-mail (usando mail(), PHPMailer ou outro método de envio de e-mail)
        $assunto = "Redefinição de senha";
        $mensagem = "Clique no link para redefinir sua senha: " . $link;
        $headers = "From: suporte@seusite.com";

        if (mail($email, $assunto, $mensagem, $headers)) {
            echo "Um e-mail foi enviado para redefinir sua senha.";
        } else {
            echo "Erro ao enviar o e-mail.";
        }
    } else {
        echo "E-mail não encontrado.";
    }
}
?>
