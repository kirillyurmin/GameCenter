<?php
require_once '../db.php';
require_once '../auth.php';

require_login();

$user_id = $_SESSION['user_id'];

// Получаем игры только текущего пользователя
$stmt = $pdo->prepare("
    SELECT games.*, genres.name AS genre_name 
    FROM games 
    LEFT JOIN genres ON games.genre_id = genres.id 
    WHERE games.user_id = ? 
    ORDER BY games.id DESC
");
$stmt->execute([$user_id]);
$myGames = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/style.css">
    <title>Мои игры - GameCenter</title>
</head>
<body>
    <header>
        <a href="../index.php" class="logo">Game<span>Center</span></a>
        <nav>
            <ul>
                <li><a href="../index.php">Главная</a></li>
                <li><a href="catalog.php">Каталог</a></li>
                <li><a href="profile.php">Профиль</a></li>
            </ul>
        </nav>
    </header>

    <main style="max-width: 1100px; margin: 40px auto; padding: 0 20px;">
        
        <!-- Блок с заголовком и кнопкой -->
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 30px;">
            <h1 style="font-size: 32px; color: #111; margin: 0;">Мои добавленные игры</h1>
            <a href="add_game.php" class="btn-welcome" style="margin-top: 0;">+ Добавить игру</a>
        </div>

        <?php if (empty($myGames)): ?>
            <div style="text-align: center; padding: 50px; background: #fff; border-radius: 12px; border: 1px solid #eee;">
                <p style="color: #666; margin-bottom: 20px; font-size: 16px;">Вы ещё не добавили ни одной игры.</p>
                <a href="add_game.php" class="btn-welcome">Добавить первую игру</a>
            </div>
        <?php else: ?>
            <div class="games-grid">
                <?php foreach ($myGames as $game): ?>
                    <!-- Карточка теперь div, а не a -->
                    <div class="game-card">
                        <a href="game.php?id=<?= $game['id'] ?>" style="text-decoration: none; color: inherit; display: block;">
                            <div class="game-cover">
                                <?php if (!empty($game['image_data'])): ?>
                                    <img src="get_image.php?id=<?= $game['id'] ?>" alt="<?= htmlspecialchars($game['title'] ?? '') ?>">
                                <?php else: ?>
                                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #eee; color: #888;">Нет обложки</div>
                                <?php endif; ?>
                            </div>
                        </a>
                        
                        <div class="game-body">
                            <h3>
                                <a href="game.php?id=<?= $game['id'] ?>" style="text-decoration: none; color: inherit;">
                                    <?= htmlspecialchars($game['title'] ?? '') ?>
                                </a>
                            </h3>
                            <p style="font-size: 12px; color: #6c8cff; margin-bottom: 10px;">Жанр: <?= htmlspecialchars($game['genre_name'] ?? 'Без жанра') ?></p>
                            
                            <!-- Кнопки действий -->
                            <div style="display: flex; gap: 5px; margin-top: auto; padding-top: 10px; border-top: 1px solid #eee;">
                                <a href="edit_game.php?id=<?= $game['id'] ?>" class="btn-welcome" style="padding: 6px 10px; font-size: 12px; flex: 1; text-align: center; background: #333;">Изменить</a>
                                <a href="delete_game.php?id=<?= $game['id'] ?>&from=my_games" class="btn-welcome" style="padding: 6px 10px; font-size: 12px; flex: 1; text-align: center; background: #e35b5b;" onclick="return confirm('Удалить игру?');">Удалить</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>