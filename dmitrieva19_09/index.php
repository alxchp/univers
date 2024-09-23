<?php
require_once("header.php");

$sql_posts = 'SELECT a.id, a.title, a.content, COUNT(c.id) AS comment_count
              FROM articles a
              LEFT JOIN comments c ON a.id = c.article_id
              GROUP BY a.id
              ORDER BY comment_count DESC';
$result_posts = mysqli_query($conn, $sql_posts);

$sql_top_categories = 'SELECT name FROM categories LIMIT 5';
$top_categories = mysqli_query($conn, $sql_top_categories);

$sql_top_posts = 'SELECT title FROM articles ORDER BY count_comment DESC LIMIT 5';
$top_posts = mysqli_query($conn, $sql_top_posts);

if (!$result_posts) {
    die('Ошибка запроса постов: ' . mysqli_error($conn));
}
if (!$top_categories) {
    die('Ошибка запроса категорий: ' . mysqli_error($conn));
}
if (!$top_posts) {
    die('Ошибка запроса топ постов: ' . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Топ категории и посты</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            display: flex;
            gap: 20px;
        }

        .articles {
            flex: 3;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .article {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #ddd;
        }

        .article h3 {
            margin: 0 0 10px;
            font-size: 1.8em;
            color: #333;
        }

        .article p {
            color: #666;
            line-height: 1.6;
        }

        .article:last-child {
            border-bottom: none;
        }

        /* Правая колонка с меню */
        .sidebar {
            flex: 1;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .sidebar h3 {
            margin-bottom: 20px;
            font-size: 1.5em;
            color: #333;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
            margin: 0 0 30px;
        }

        .sidebar ul li {
            margin-bottom: 10px;
        }

        .sidebar ul li a {
            text-decoration: none;
            color: #007BFF;
            font-size: 1.1em;
        }

        .sidebar ul li a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .articles, .sidebar {
                flex: none;
                width: 100%;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <section class="articles">
        <h1>Посты по количеству комментариев</h1>
        <?php if (mysqli_num_rows($result_posts) > 0): ?>
            <?php while ($article = mysqli_fetch_assoc($result_posts)): ?>
                <div class="article">
                    <h3><?php echo $article['title']; ?></h3>
                    <p><?php echo $article['content']; ?></p>
                    <p>Комментарии: <?php echo $article['comment_count']; ?></p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Нет доступных постов.</p>
        <?php endif; ?>
    </section>

    <aside class="sidebar">
        <h3>Топ категории</h3>
        <ul>
            <?php while ($category = mysqli_fetch_assoc($top_categories)): ?>
                <li><a href="#"><?php echo $category['name']; ?></a></li>
            <?php endwhile; ?>
        </ul>

        <h3>Топ посты</h3>
        <ul>
            <?php while ($post = mysqli_fetch_assoc($top_posts)): ?>
                <li><a href="#"><?php echo $post['title']; ?></a></li>
            <?php endwhile; ?>
        </ul>
    </aside>
</div>

</body>
</html>

<?php
mysqli_close($conn);
