<?php
session_start();
include("header.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE username='$username'");
    
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];  
        $_SESSION['username'] = $username;  
        header("Location: profile.php");  
        exit();
    } else {
        $error = "Неправильное имя пользователя или пароль!";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Авторизация</title>
    <link rel="stylesheet" href="log.css">
</head>
<body>
    <div class="centered-container">
        <div class="auth-box">
            <h2>Авторизация</h2>
            <?php if (isset($error)): ?>
                <p class="error"><?= $error ?></p>
            <?php endif; ?>
            <form action="login.php" method="POST">
                <label for="username">Имя пользователя:</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Пароль:</label>
                <input type="password" id="password" name="password" required>

                <input type="submit" value="Войти">
            </form>
            <p>Нет аккаунта? <a href="reg.php">Зарегистрируйтесь</a></p>
        </div>
    </div>
</body>
</html>
