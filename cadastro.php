<?php
// Ativar relatórios de erros do MySQLi
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Dados para conexão com o banco
$servidor = "localhost";
$user = "root";
$password = "root";
$banco = "bd_Sustentech";

// Criando a conexão
$conn = new mysqli($servidor, $user, $password, $banco);

// Verificando a conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Verifica se o formulário foi enviado com método POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Capturando dados do formulário
    $nm_cliente = ($_POST["nm_cliente"]);
    $email = ($_POST["email"]);
    $nm_endereco = ($_POST["nm_endereco"]);
    $nr_endereco = ($_POST["nr_endereco"]);
    $sg_estado = ($_POST["sg_estado"]);
    $nr_cep = ($_POST["nr_cep"]);
    $nr_telefone = ($_POST["nr_telefone"]);
    $login = ($_POST["login"]);
    $senha = $_POST["senha"];
    $ConfirmaSenha = $_POST["ConfirmaSenha"];

    // Verifica se as senhas coincidem
    if ($senha === $ConfirmaSenha) {

        // Criptografando a senha
        $hashsenha = password_hash($senha, PASSWORD_BCRYPT);

        // Consulta SQL para verificar se o usuário já existe
        $sql = "SELECT * FROM tb_vendedores WHERE nm_cliente = '$nm_cliente'";
        $retorno = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($retorno);

        if ($row) {
            // Se o usuário já existe, exibe mensagem de erro
            echo "<p style='color:red; text-align:center; font-size:25px;'>Usuário Já Existe!</p>";
            echo "<h2 style='color:green; text-align:center; font-size:25px;'><a href='usuarios.html'>VOLTAR</a></h2>";
        } else {
            // Se o usuário não existe, faz a inserção no banco de dados
            $insert = "INSERT INTO tb_vendedores 
                        (nm_cliente, login, nm_endereco, nr_endereco, sg_estado, email, nr_cep, nr_telefone, senha) 
                        VALUES ('$nm_cliente', '$login', '$nm_endereco', '$nr_endereco', '$sg_estado', '$email', '$nr_cep', '$nr_telefone', '$hashsenha')";

            $query = mysqli_query($conn, $insert);

            if ($query) {
                echo "<p style='color:green; text-align:center; font-size:25px;'>Cadastro Realizado!</p>";
                echo "<h2 style='color:green; text-align:center; font-size:25px;'><a href='usuarios.html'>VOLTAR</a></h2>";
            } else {
                // Exibe qualquer erro de inserção
                echo "Erro ao inserir: " . mysqli_error($conn);
            }
        }
    } else {
        // Se as senhas não coincidem, exibe mensagem de erro
        echo "<p style='color:red; text-align:center; font-size:25px;'>As senhas não coincidem!</p>";
        echo "<h2 style='color:green; text-align:center; font-size:25px;'><a href='usuarios.html'>VOLTAR</a></h2>";
    }
}

// Fecha a conexão
$conn->close();
?>
