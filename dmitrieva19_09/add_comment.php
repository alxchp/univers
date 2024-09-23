<?php
session_start();
include("header.php");
if (!empty($_POST['article_id']) && !empty($_POST['content']) && !empty($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $user_query = "SELECT user_id FROM users WHERE username = '$username'";
    $user_result = mysqli_query($conn, $user_query);
    if (!$user_result) {
        die("Ошибка выполнения запроса: " . mysqli_error($conn));
    }

    if ($user = mysqli_fetch_assoc($user_result)) {
        $author_id = $user['user_id']; 
    } else {
        echo "Ошибка: Пользователь не найден.";
        exit();
    }
    $article_id = $_POST['article_id'];
    $content = mysqli_real_escape_string($conn, $_POST['content']);  
    $query = "INSERT INTO comments (article_id, author_id, content) VALUES ('$article_id', '$author_id', '$content')";
    if (mysqli_query($conn, $query)) {
        header("Location: news.php");
        exit();
    } else {
        echo "Ошибка при добавлении комментария: " . mysqli_error($conn);
    }
} else {
    echo "Ошибка: Все поля формы обязательны для заполнения.";
}
mysqli_close($conn);
?>
