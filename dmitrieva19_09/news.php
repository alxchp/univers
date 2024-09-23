<?php
session_start();

include("header.php");
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Гость';
$user_query = "SELECT user_id FROM users WHERE username = '$username'";
$user_result = mysqli_query($conn, $user_query);
$user = mysqli_fetch_assoc($user_result);
$current_user_id = $user['user_id'];

if (isset($_GET['delete'])) {
    $comment_id = $_GET['delete'];
    $delete_query = "DELETE FROM comments WHERE id = '$comment_id' AND author_id = '$current_user_id'";
    mysqli_query($conn, $delete_query);
    header("Location: news.php");  
    exit();
}
$editing_comment_id = isset($_GET['edit']) ? $_GET['edit'] : null;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['id']) && isset($_POST['content'])) {
    $comment_id = $_POST['id'];
    $updated_content = mysqli_real_escape_string($conn, $_POST['content']);
    $update_query = "UPDATE comments SET content = '$updated_content' WHERE id = '$comment_id' AND author_id = '$current_user_id'";
    mysqli_query($conn, $update_query);
    header("Location: news.php"); 
    exit();
}
$query = "SELECT id, title, content, created_at, updated_at, author_id, category, count_comment FROM articles ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Ошибка выполнения запроса: " . mysqli_error($conn));
}
if (mysqli_num_rows($result) > 0):
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Новости</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .news-item {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }
        .news-item h2 {
            margin-top: 0;
        }
        .meta-info {
            font-size: 12px;
            color: #666;
        }

        .comments-section {
            margin-top: 30px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .comments-section h3 {
            margin-bottom: 20px;
            color: #333;
        }
        .comment {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 15px;
        }
        .comment:last-child {
            border-bottom: none;
        }
        .comment-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #ccc;
            margin-right: 15px;
        }
        .comment-body {
            flex: 1;
        }
        .comment-author {
            font-weight: bold;
            color: #007bff;
        }
        .comment-time {
            font-size: 12px;
            color: #999;
            margin-left: 10px;
        }
        .comment-content {
            margin-top: 5px;
            line-height: 1.5;
        }

        /* Форма для добавления комментария */
        .comment-form {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
        .comment-form input, .comment-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }
        .comment-form button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .comment-form button:hover {
            background-color: #0056b3;
        }

    </style>
</head>
<body>

<h1>Список новостей</h1>

<?php
    while ($item = mysqli_fetch_assoc($result)):
        $article_id = $item['id']; 
?>
    <div class="news-item">
        <h2><?php echo $item['title']; ?></h2>
        <p class="meta-info">
            Автор: <?php echo $item['author_id']; ?> | 
            Категория: <?php echo $item['category']; ?> | 
            Комментарии: <?php echo $item['count_comment']; ?> | 
            Создано: <?php echo $item['created_at']; ?> | 
            Обновлено: <?php echo $item['updated_at']; ?>
        </p>
        <p><?php echo $item['content']; ?></p>
        <div class="comments-section">
            <h3>Комментарии:</h3>

            <?php
            $comments_query = "SELECT id, author_id, content, created_at FROM comments WHERE article_id = $article_id ORDER BY created_at DESC";
            $comments_result = mysqli_query($conn, $comments_query);
            if (!$comments_result) {
                echo "Ошибка выполнения запроса комментариев: " . mysqli_error($conn);
            }
            if ($comments_result && mysqli_num_rows($comments_result) > 0):
                echo "<p>Найдено комментариев: " . mysqli_num_rows($comments_result) . "</p>";

                while ($comment = mysqli_fetch_assoc($comments_result)):
            ?>
                <div class="comment">
                    <div class="comment-avatar"></div> 
                    <div class="comment-body">
                        <span class="comment-author"><?php echo $username; ?></span>
                        <span class="comment-time"><?php echo $comment['created_at']; ?></span>
                        <p class="comment-content"><?php echo $comment['content']; ?></p>

                        <?php if ($comment['author_id'] == $current_user_id): ?>
                            <a href="?edit=<?php echo $comment['id']; ?>">Редактировать</a> |
                            <a href="?delete=<?php echo $comment['id']; ?>" onclick="return confirm('Вы уверены, что хотите удалить этот комментарий?');">Удалить</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php
                endwhile;
            else:
                echo "<p>Комментариев пока нет.</p>";
            endif;
            ?>
            <?php if ($editing_comment_id): 
                $edit_comment_query = "SELECT content FROM comments WHERE id = '$editing_comment_id' AND author_id = '$current_user_id'";
                $edit_comment_result = mysqli_query($conn, $edit_comment_query);
                $edit_comment = mysqli_fetch_assoc($edit_comment_result);
            ?>
                <div class="comment-form">
                    <form method="POST">
                        <input type="hidden" name="article_id" value="<?php echo $article_id; ?>">
                        <input type="hidden" name="id" value="<?php echo $editing_comment_id; ?>">
                        <textarea name="content" required><?php echo $edit_comment['content']; ?></textarea>
                        <button type="submit">Обновить комментарий</button>
                    </form>
                </div>
            <?php else: ?>
                <div class="comment-form">
                    <form action="add_comment.php" method="post">
                        <input type="hidden" name="article_id" value="<?php echo $article_id; ?>">
                        <p><input type="text" name="author_id" value="<?php echo $username; ?>" readonly></p>
                        <p><textarea name="content" placeholder="Ваш комментарий" required></textarea></p>
                        <p><button type="submit">Отправить комментарий</button></p>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php
    endwhile;
else:
    echo "<p>Нет новостей для отображения.</p>";
endif;

mysqli_close($conn);
?>

</body>
</html>
