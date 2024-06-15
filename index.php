<?php
$host = 'banco';
$dbname = 'meu_banco';
$user = 'usuario';
$pass = '12345';

// Coletar dados do formulário
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Verificar se os campos estão preenchidos
if (empty($name) || empty($email) || empty($password)) {
    header("Location: /index.html?status=error&message=empty_fields");
    exit();
}

// Criptografar a senha
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

try {
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['name' => $name, 'email' => $email, 'password' => $hashed_password]);

    // Redirecionar após inserção bem-sucedida
    header("Location: /index.html?status=success");
    exit(); // Sempre é uma boa prática sair após um redirecionamento
} catch (PDOException $e) {
    // Em caso de erro de banco de dados, redirecionar para página de erro
    header("Location: /index.html?status=error&message=db_error");
    exit();
}
?>
