<?php
// Подключение к базе данных
include_once("header.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $login = $_POST['login'];
    $password = $_POST['password'];

    // Проверка на существующий логин
    $checkLogin = mysqli_query($conn, "SELECT * FROM users WHERE login='$login'");
    if (mysqli_num_rows($checkLogin) > 0) {
        $error_message = "Этот логин уже используется!";
    } else {
        // Хеширование пароля
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, login, password) VALUES ('$username', '$login', '$hashed_password')";
        if (mysqli_query($conn, $sql)) {
            $success_message = "Регистрация успешна!";
        } else {
            $error_message = "Ошибка: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="log.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
</head>
<body>
    <div class="centered-container">
        <div class="auth-box">
            <h2>Регистрация</h2>
            <!-- Вывод сообщений об ошибке или успехе -->
            <?php if (isset($error_message)): ?>
                <p class="error-message"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <?php if (isset($success_message)): ?>
                <p class="success-message"><?php echo $success_message; ?></p>
            <?php endif; ?>

            <form action="reg.php" method="POST">
                <div class="input-group">
                    <label for="username">Имя пользователя:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div class="input-group">
                    <label for="login">Логин:</label>
                    <input type="text" id="login" name="login" required>
                </div>
                <div class="input-group">
                    <label for="password">Пароль:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <input type="submit" value="Зарегистрироваться">
            </form>
            <p>Уже есть аккаунт? <a href="login.php">Войти</a></p>
        </div>
    </div>
</body>
</html>
