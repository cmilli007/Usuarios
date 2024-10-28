<?php
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$servidor = "localhost";
$user = "root";
$password = "root";
$banco = "bd_sustentech";

// Criando conexão com o banco de dados
$conn = new mysqli($servidor, $user, $password, $banco);

// Verificando conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capturando dados do formulário
    $nm_cliente = trim($_POST["nm_cliente"]);
    $email = trim($_POST["email"]);
    $nm_endereco = trim($_POST["nm_endereco"]);
    $nr_endereco = trim($_POST["nr_endereco"]);
    $sg_estado = trim($_POST["sg_estado"]);
    $nr_cep = trim($_POST["nr_cep"]);
    $nr_telefone = trim($_POST["nr_telefone"]);
    $login = trim($_POST["login"]);
    $senha = $_POST["senha"];
    $ConfirmaSenha = $_POST["ConfirmaSenha"];

    // Verificando se as senhas coincidem
    if ($senha === $ConfirmaSenha) {
        // Criptografando a senha
        $hashsenha = password_hash($senha, PASSWORD_BCRYPT);

        // Consulta SQL para verificar se o usuário já existe
        $sql = "SELECT * FROM tb_usuarios WHERE nm_cliente = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $nm_cliente);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Se o usuário já existe, redireciona para a página de erro
            $mensagemErro = "Usuário já existe!";
            header("Location: erro.php?mensagem=" . urlencode($mensagemErro));
            exit();
        } else {
            // Se o usuário não existe, faz a inserção no banco de dados
            $insert = "INSERT INTO tb_usuarios (nm_cliente, login, nm_endereco, nr_endereco, sg_estado, email, nr_cep, nr_telefone, senha) 
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert);
            $stmt->bind_param("sssssssss", $nm_cliente, $login, $nm_endereco, $nr_endereco, $sg_estado, $email, $nr_cep, $nr_telefone, $hashsenha);

            if ($stmt->execute()) {
                // Redireciona para a página de perfil após cadastro bem-sucedido
                header("Location: perfil.php"); // Atualize para o caminho correto do perfil
                exit();
            } else {
                // Se ocorrer um erro de inserção, redireciona para a página de erro
                $mensagemErro = "Erro ao inserir: " . mysqli_error($conn);
                header("Location: erro.php?mensagem=" . urlencode($mensagemErro));
                exit();
            }
        }
    } else {
        // Se as senhas não coincidem, redireciona para a página de erro
        $mensagemErro = "As senhas não coincidem!";
        header("Location: erro.php?mensagem=" . urlencode($mensagemErro));
        exit();
    }
}

// Fechando a conexão
$conn->close();
?>
