<?php
session_start();
include("header.php"); 

if (isset($_POST['add_category'])) {
    $category_name = $_POST['category_name'];
    $conn->query("INSERT INTO categories (name) VALUES ('$category_name')");
    header("Location: category.php");
}

if (isset($_GET['delete'])) {
    $category_id = $_GET['delete'];
    $conn->query("DELETE FROM categories WHERE id = '$category_id'");
    header("Location: category.php");
}

if (isset($_POST['edit_category'])) {
    $category_id = $_POST['category_id'];
    $new_name = $_POST['new_name'];
    $conn->query("UPDATE categories SET name='$new_name' WHERE id='$category_id'");
    header("Location: category.php");
}

$categories = $conn->query("SELECT * FROM categories");

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление категориями</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Управление категориями</h1>

        <form method="POST" action="category.php">
            <label for="category_name">Название категории:</label>
            <input type="text" name="category_name" required>
            <button type="submit" name="add_category">Создать категорию</button>
        </form>

        <h2>Список категорий</h2>
        <ul>
            <?php while ($category = $categories->fetch_assoc()): ?>
                <li>
                    <?php echo $category['name']; ?>
                    <a href="category.php?edit=<?php echo $category['id']; ?>">Редактировать</a>
                    <a href="category.php?delete=<?php echo $category['id']; ?>">Удалить</a>
                </li>
            <?php endwhile; ?>
        </ul>

        <?php if (isset($_GET['edit'])): ?>
            <?php
            $edit_category_id = $_GET['edit'];
            $category_to_edit = $conn->query("SELECT * FROM categories WHERE id='$edit_category_id'")->fetch_assoc();
            ?>
            <h2>Редактировать категорию</h2>
            <form method="POST" action="category.php">
                <input type="hidden" name="category_id" value="<?php echo $category_to_edit['id']; ?>">
                <label for="new_name">Новое название:</label>
                <input type="text" name="new_name" value="<?php echo $category_to_edit['name']; ?>" required>
                <button type="submit" name="edit_category">Сохранить изменения</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
