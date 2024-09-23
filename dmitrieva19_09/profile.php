<?php
session_start();
include("header.php"); 
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); 
    exit(); 
}

$user_id = $_SESSION['user_id'];
if (isset($_GET['delete'])) {
    $article_id = $_GET['delete'];
    $conn->query("DELETE FROM articles WHERE id = '$article_id'");
    header("Location: profile.php");
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create'])) {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $content = $_POST['content'];
    $stmt = $conn->prepare("INSERT INTO articles (title, content, category, author_id, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
    $stmt->bind_param("sssi", $title, $content, $category, $user_id);
    $stmt->execute();
    header("Location: profile.php");
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit'])) {
    $article_id = $_POST['article_id'];
    $new_title = $_POST['title'];
    $new_category = $_POST['category'];
    $new_content = $_POST['content'];
    $conn->query("UPDATE articles SET title='$new_title', content='$new_content', category='$new_category', updated_at=NOW() WHERE id='$article_id'");
    header("Location: profile.php");
}
$articles = $conn->query("SELECT * FROM articles WHERE author_id = '$user_id'");
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="left-header">
                <span>Личный кабинет</span>
            </div>
            <div class="center-header">
                <h1>Мои статьи</h1>
            </div>
        </div>
        <section class="article-list container">
            <h2>Ваши статьи</h2>

            <?php while ($article = $articles->fetch_assoc()): ?>
                <div class="article">
                    <h3><?php echo $article['title']; ?></h3>
                    <p>Категория: <?php echo $article['category']; ?></p>
                    <p>Опубликовано: <?php echo $article['created_at']; ?></p>
                    <p><?php echo $article['content']; ?></p>
                    <div class="actions">
                        <button class="edit-btn" onclick="showEditForm(<?php echo $article['id']; ?>)">Редактировать</button>
                        <a href="profile.php?delete=<?php echo $article['id']; ?>"><button class="delete-btn">Удалить</button></a>
                    </div>
                </div>
                <div class="edit-form" id="edit-form-<?php echo $article['id']; ?>" style="display: none;">
                    <form action="profile.php" method="POST">
                        <input type="hidden" name="article_id" value="<?php echo $article['id']; ?>">
                        <label for="title">Название:</label>
                        <input type="text" name="title" value="<?php echo $article['title']; ?>" required><br>

                        <label for="category">Категория:</label>
                        <select name="category">
                            <option value="Технологии" <?php if($article['category'] == 'Технологии') echo 'selected'; ?>>Технологии</option>
                            <option value="Искусство" <?php if($article['category'] == 'Искусство') echo 'selected'; ?>>Искусство</option>
                            <option value="Спорт" <?php if($article['category'] == 'Спорт') echo 'selected'; ?>>Спорт</option>
                        </select><br>

                        <label for="content">Содержание:</label>
                        <textarea name="content" required><?php echo $article['content']; ?></textarea><br>

                        <button type="submit" name="edit">Сохранить изменения</button>
                    </form>
                </div>
            <?php endwhile; ?>

        </section>
        <button id="openModalBtn">Создать статью</button>
        <div id="modal" class="modal">
            <div class="modal-content">
                <span id="closeModalBtn" class="close">&times;</span>
                <h2>Создать статью</h2>
                <form action="profile.php" method="POST">
                    <label for="title">Название:</label>
                    <input type="text" id="title" name="title" required>
                    
                    <label for="category">Категория:</label>
                    <select id="category" name="category" required>
                        <option value="Технологии">Технологии</option>
                        <option value="Искусство">Искусство</option>
                        <option value="Спорт">Спорт</option>
                    </select>
                    
                    <label for="content">Содержание:</label>
                    <textarea id="content" name="content" required></textarea>
                    
                    <button type="submit" name="create">Сохранить</button>
                </form>
            </div>
        </div>

    </div>
    <footer class="footer">
        <div class="container">
            <p>© Universal 2024 - Все права защищены</p>
        </div>
    </footer>

    <script>
        function showEditForm(id) {
            document.getElementById('edit-form-' + id).style.display = 'block';
        }

        const modal = document.getElementById('modal');
        const openModalBtn = document.getElementById('openModalBtn');
        const closeModalBtn = document.getElementById('closeModalBtn');

        openModalBtn.addEventListener('click', () => {
            modal.style.display = 'flex';
        });

        closeModalBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        window.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    </script>

</body>
</html>
