<?php
session_start();
include("header.php");
if (isset($_POST['add_article'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST['category']; 
    $author_id = $_SESSION['user_id'];
    
    $sql = "INSERT INTO articles (title, content, author_id, category) VALUES ('$title', '$content', '$author_id', '$category')";
    if (!$conn->query($sql)) {
        die("Error adding article: " . $conn->error);
    }
    header("Location: article.php");
    exit();
}

if (isset($_GET['delete'])) {
    $article_id = $_GET['delete'];
    if (!$conn->query("DELETE FROM articles WHERE id = '$article_id'")) {
        die("Error deleting article: " . $conn->error);
    }
    header("Location: article.php");
    exit();
}

if (isset($_POST['edit_article'])) {
    $article_id = $_POST['article_id'];
    $new_title = $_POST['title'];
    $new_content = $_POST['content'];
    $category = $_POST['category']; 
    
    $sql = "UPDATE articles SET title='$new_title', content='$new_content', category='$category', updated_at=NOW() WHERE id='$article_id'";
    if (!$conn->query($sql)) {
        die("Error editing article: " . $conn->error);
    }
    header("Location: article.php");
    exit();
}

$articles = $conn->query("SELECT a.*, c.name AS category_name FROM articles a LEFT JOIN categories c ON a.category = c.id");

if (!$articles) {
    die("Error fetching articles: " . $conn->error);
}

$categories = $conn->query("SELECT * FROM categories");

if (!$categories) {
    die("Error fetching categories: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление статьями</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Управление статьями</h1>

        <form method="POST" action="article.php">
            <label for="title">Название статьи:</label>
            <input type="text" name="title" required>
            
            <label for="content">Содержание статьи:</label>
            <textarea name="content" required></textarea>
            
            <label for="category">Категория:</label>
            <select name="category" required>
                <?php while ($category = $categories->fetch_assoc()): ?>
                    <option value="<?= $category['id']; ?>"><?= $category['name']; ?></option>
                <?php endwhile; ?>
            </select>

            <button type="submit" name="add_article">Добавить статью</button>
        </form>

        <h2>Список статей</h2>
        <table>
            <thead>
                <tr>
                    <th>Название</th>
                    <th>Категория</th>
                    <th>Дата создания</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($article = $articles->fetch_assoc()): ?>
                    <tr>
                        <td><?= $article['title']; ?></td>
                        <td><?= $article['category_name']; ?></td>
                        <td><?= $article['created_at']; ?></td>
                        <td>
                            <a href="article.php?edit=<?= $article['id']; ?>">Редактировать</a>
                            <a href="article.php?delete=<?= $article['id']; ?>">Удалить</a>
                        </td>
                    </tr>

                    <?php if (isset($_GET['edit']) && $_GET['edit'] == $article['id']): ?>
                        <tr>
                            <td colspan="4">
                                <form method="POST" action="article.php">
                                    <input type="hidden" name="article_id" value="<?= $article['id']; ?>">
                                    
                                    <label for="title">Название статьи:</label>
                                    <input type="text" name="title" value="<?= $article['title']; ?>" required>
                                    
                                    <label for="content">Содержание статьи:</label>
                                    <textarea name="content" required><?= $article['content']; ?></textarea>

                                    <label for="category">Категория:</label>
                                    <select name="category" required>
                                        <?php 
                                        $categories->data_seek(0); 
                                        while ($category = $categories->fetch_assoc()): ?>
                                            <option value="<?= $category['id']; ?>" <?= ($category['id'] == $article['category']) ? 'selected' : ''; ?>><?= $category['name']; ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                    
                                    <button type="submit" name="edit_article">Сохранить изменения</button>
                                </form>
                            </td>
                        </tr>
                    <?php endif; ?>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
