<?php
$servidor = "localhost";
$user = "root";
$password = "root";
$bd = "bd_Sustentech";

$conn = new mysqli($servidor, $user, $password, $bd);

if ($conn->connect_error) {
    die("<p style='color:red; text-align:center; font-size:25px;'>Erro de conexão!</p>");
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   
    $nm_empresa = ($_POST["nm_empresa"]);
    $cnpj_empresa = ($_POST["cnpj_empresa"]);
    $nm_endereco = ($_POST["nm_endereco"]);
    $nr_endereco = ($_POST["nr_endereco"]);
    $nr_cep = ($_POST["nr_cep"]);
    $nr_telefone = ($_POST["nr_telefone"]);
    $nm_responsavel = ($_POST["nm_responsavel"]);
    $cargo_responsavel = ($_POST["cargo_responsavel"]);
    $email_responsavel = ($_POST["email_responsavel"]);
    $senha = ($_POST["senha"]);
    $ds_servico = ($_POST["ds_servico"]);
    $ConfirmaSenha =($_POST["ConfirmaSenha"]);

    // Verifica se as senhas coincidem
    if ($senha === $ConfirmaSenha) {
        // Consulta SQL para verificar se a empresa já existe
        $sql = "SELECT * FROM tb_empresa WHERE nm_empresa = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $nm_empresa);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Se a empresa já existe, exibe mensagem de erro
            echo "<p style='color:red; text-align:center; font-size:25px;'>Usuário Já Existe!</p>";
            echo "<h2 style='color:green; text-align:center; font-size:25px;'><a href='usuarios_empresas.html'>VOLTAR</a></h2>";
        } else {
            // Se a empresa não existe, faz a inserção no banco de dados
            $hashsenha = password_hash($senha, PASSWORD_BCRYPT);
            $insert = "INSERT INTO tb_empresa (nm_empresa, cnpj_empresa, nr_endereco, nm_endereco, nr_cep, nr_telefone, nm_responsavel, cargo_responsavel, email_responsavel, senha, ds_servico) 
                        VALUES ('$nm_empresa', '$cnpj_empresa', '$nr_endereco', '$nm_endereco', '$nr_cep', '$nr_telefone', '$nm_responsavel', '$cargo_responsavel', '$email_responsavel' ,'$hashsenha', '$ds_servico')";


$query = mysqli_query($conn, $insert);

if ($query) {
    echo "<p style='color:green; text-align:center; font-size:25px;'>Cadastro Realizado!</p>";
    echo "<h2 style='color:green; text-align:center; font-size:25px;'><a href='usuarios_empresas.html'>VOLTAR</a></h2>";
} else {
  
    echo "Erro ao inserir: " . mysqli_error($conn);
}
}
} else {
// Se as senhas não coincidem, exibe mensagem de erro
echo "<p style='color:red; text-align:center; font-size:25px;'>As senhas não coincidem!</p>";
echo "<h2 style='color:green; text-align:center; font-size:25px;'><a href='usuarios_empresas.html'>VOLTAR</a></h2>";
}
}

$conn->close();
?>