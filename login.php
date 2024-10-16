<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <form action="processar_login.php" method="post">
        <label for="email">Email:</label>
        <input type="email" name="email" required><br>
        
        <label for="senha">Senha:</label>
        <input type="password" name="senha" required><br>
        
        <input type="submit" value="Entrar">
    </form>
</body>
</html>
