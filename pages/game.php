<?php
require_once '../db.php';
require_once '../auth.php'; // <--- ДОБАВЛЕНА ЭТА СТРОКА

// Получаем ID из адресной строки (GET-запрос)
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// Делаем запрос к БД, чтобы найти игру по ID
$stmt = $pdo->prepare("SELECT games.*, genres.name AS genre_name FROM games LEFT JOIN genres ON games.genre_id = genres.id WHERE games.id = ?");
$stmt->execute([$id]);
$game = $stmt->fetch();

// Если игры с таким ID нет — показываем ошибку 404
if (!$game) {
    die("<h1 style='text-align:center; margin-top:50px;'>Игра не найдена (ошибка 404)</h1><p style='text-align:center;'><a href='catalog.php'>Вернуться в каталог</a></p>");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/style.css">
    <title><?= htmlspecialchars($game['title']) ?> - GameCenter</title>
</head>

<body>
    <header>
        <a href="../index.php" class="logo">Game<span>Center</span></a>
        <nav>
            <ul>
                <li><a href="../index.php">Главная</a></li>
                <li><a href="catalog.php">Каталог</a></li>
                <!-- ИСПРАВЛЕНЫ ССЫЛКИ -->
                <li><a href="profile.php">Профиль</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <div
            style="max-width: 800px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 12px; border: 1px solid #ececec;">
            <?php if (!empty($game['image_data'])): ?>
                <img src="get_image.php?id=<?= $game['id'] ?>" alt="<?= htmlspecialchars($game['title'] ?? '') ?>"
                    style="width: 100%; max-height: 400px; object-fit: cover; border-radius: 8px; margin-bottom: 20px;">
            <?php else: ?>
                <div
                    style="width: 100%; height: 300px; background: #eee; display: flex; align-items: center; justify-content: center; border-radius: 8px; margin-bottom: 20px; color: #888;">
                    Нет обложки
                </div>
            <?php endif; ?>

            <h1 style="margin-bottom: 10px;"><?= htmlspecialchars($game['title']) ?></h1>

            <?php if ($game['genre_name']): ?>
                <p style="color: #6c8cff; margin-bottom: 20px; font-weight: bold;">Жанр:
                    <?= htmlspecialchars($game['genre_name']) ?></p>
            <?php endif; ?>

            <p style="font-size: 16px; line-height: 1.8; color: #444; margin-bottom: 30px;">
                <?= nl2br(htmlspecialchars($game['description'])) ?>
            </p>

            <div style="display: flex; gap: 10px;">
                <a href="catalog.php" class="btn-welcome" style="text-decoration: none;">← Назад в каталог</a>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <!-- Показываем кнопки, только если вошел -->
                    <a href="edit_game.php?id=<?= $game['id'] ?>" class="btn-welcome"
                        style="text-decoration: none; background: #333;">Редактировать</a>
                    <a href="delete_game.php?id=<?= $game['id'] ?>" class="btn-welcome"
                        style="text-decoration: none; background: #e35b5b;"
                        onclick="return confirm('Удалить игру?');">Удалить</a>
                <?php endif; ?>
            </div>
        </div>
    </main>
</body>

</html>