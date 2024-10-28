<?php
include 'conexao.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Verifica se o token existe e não expirou
    $stmt = $pdo->prepare("SELECT * FROM tb_redefinicao_senha WHERE token = ? AND expira_em > NOW()");
    $stmt->execute([$token]);
    $resetData = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($resetData) {
        // Se o token for válido, mostre o formulário para definir a nova senha
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nova_senha = $_POST['nova_senha'];
            $confirma_senha = $_POST['confirma_senha'];

            if ($nova_senha === $confirma_senha) {
                // Criptografa a nova senha
                $hash_senha = password_hash($nova_senha, PASSWORD_BCRYPT);

                // Atualiza a senha no banco de dados
                $stmt = $pdo->prepare("UPDATE tb_usuarios SET senha = ? WHERE email = ?");
                if ($stmt->execute([$hash_senha, $resetData['email']])) {
                    // Exclui o token usado
                    $stmt = $pdo->prepare("DELETE FROM tb_redefinicao_senha WHERE token = ?");
                    $stmt->execute([$token]);

                    echo "Senha redefinida com sucesso!";
                    header("Location: login.php");
                    exit();
                } else {
                    echo "Erro ao redefinir a senha.";
                }
            } else {
                echo "As senhas não coincidem.";
            }
        }
    } else {
        echo "Token inválido ou expirado.";
    }
} else {
    echo "Token não fornecido.";
}
?>

<!-- Formulário para redefinir a senha -->
<form action="" method="POST">
    <label for="nova_senha">Nova senha:</label>
    <input type="password" id="nova_senha" name="nova_senha" required>
    <label for="confirma_senha">Confirme a nova senha:</label>
    <input type="password" id="confirma_senha" name="confirma_senha" required>
    <button type="submit">Redefinir senha</button>
</form>
