<?php
require_once '../db.php';
require_once '../auth.php';

require_admin();

// Получаем ВСЕ игры с именами владельцев
$stmt = $pdo->query("
    SELECT games.*, users.login AS owner_name 
    FROM games 
    LEFT JOIN users ON games.user_id = users.id 
    ORDER BY games.id DESC
");
$games = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/style.css">
    <title>Админ-панель - GameCenter</title>
</head>
<body>
    <header>
        <a href="../index.php" class="logo">Game<span>Center</span></a>
        <nav>
            <ul>
                <li><a href="../index.php">Главная</a></li>
                <li><a href="catalog.php">Каталог</a></li>
                <li><a href="profile.php">Профиль</a></li>
                <li><a href="admin.php" style="color: #e35b5b; font-weight: bold;">Админ-панель</a></li>
            </ul>
        </nav>
    </header>

    <main class="admin-container">
        <h1 class="admin-title">Управление играми пользователей</h1>

        <div class="admin-table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 10%;">ID</th>
                        <th style="width: 40%;">Название игры</th>
                        <th style="width: 30%;">Владелец</th>
                        <th style="width: 20%;">Действие</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($games as $game): ?>
                        <tr>
                            <td data-label="ID"><?= $game['id'] ?></td>
                            <td data-label="Название">
                                <a href="game.php?id=<?= $game['id'] ?>" class="admin-game-link">
                                    <?= htmlspecialchars($game['title']) ?>
                                </a>
                            </td>
                            <td data-label="Владелец">
                                <?= htmlspecialchars($game['owner_name'] ?? 'Неизвестно') ?>
                            </td>
                            <td data-label="Действие">
                                <a href="edit_game.php?id=<?= $game['id'] ?>" class="btn-admin-edit">Редактировать</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>