<?php
require_once '../db.php';
require_once '../auth.php';

// ЖЕСТКАЯ ПРОВЕРКА: Пускаем только админов! 
// Если зайдет обычный юзер, его выкинет с ошибкой 403.
require_admin();

$errors = [];
$success = false;

// Обработка смены статуса (административное действие)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_status'])) {
    $game_id = (int)$_POST['game_id'];
    $new_status = $_POST['status'];
    
    // Разрешаем только определенные статусы
    if (in_array($new_status, ['new', 'in_progress', 'done'])) {
        $stmt = $pdo->prepare("UPDATE games SET status = ? WHERE id = ?");
        $stmt->execute([$new_status, $game_id]);
        $success = true;
    } else {
        $errors[] = 'Недопустимый статус.';
    }
}

// Получаем ВСЕ игры из базы вместе с логинами их владельцев (LEFT JOIN)
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

    <main style="max-width: 1000px; margin: 40px auto; padding: 0 20px;">
        <h1>Управление играми пользователей</h1>
        
        <?php if ($success): ?>
            <div style="background: #e6f4ea; color: #1e8e3e; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                Статус игры успешно обновлен!
            </div>
        <?php endif; ?>

        <?php if ($errors): ?>
            <div style="background: #fce8e6; color: #d93025; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                <?php foreach ($errors as $e) echo htmlspecialchars($e) . "<br>"; ?>
            </div>
        <?php endif; ?>

        <div style="background: #fff; border-radius: 12px; border: 1px solid #ececec; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background: #f9f9f9; border-bottom: 2px solid #eee;">
                        <th style="padding: 15px;">ID</th>
                        <th style="padding: 15px;">Название игры</th>
                        <th style="padding: 15px;">Владелец</th>
                        <th style="padding: 15px;">Статус</th>
                        <th style="padding: 15px;">Действие</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($games as $game): ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 15px;"><?= $game['id'] ?></td>
                            <td style="padding: 15px;">
                                <a href="game.php?id=<?= $game['id'] ?>" style="color: #6c8cff; text-decoration: none;">
                                    <?= htmlspecialchars($game['title']) ?>
                                </a>
                            </td>
                            <td style="padding: 15px;">
                                <?= htmlspecialchars($game['owner_name'] ?? 'Неизвестно') ?>
                            </td>
                            <td style="padding: 15px;">
                                <?php 
                                    $statusNames = [
                                        'new' => 'Новая',
                                        'in_progress' => 'В работе',
                                        'done' => 'Готово'
                                    ];
                                    echo $statusNames[$game['status']] ?? 'Новая'; 
                                ?>
                            </td>
                            <td style="padding: 15px;">
                                <form method="POST" style="display: flex; gap: 10px; align-items: center;">
                                    <input type="hidden" name="game_id" value="<?= $game['id'] ?>">
                                    <select name="status" style="padding: 8px; border-radius: 6px; border: 1px solid #ddd;">
                                        <option value="new" <?= $game['status'] === 'new' ? 'selected' : '' ?>>Новая</option>
                                        <option value="in_progress" <?= $game['status'] === 'in_progress' ? 'selected' : '' ?>>В работе</option>
                                        <option value="done" <?= $game['status'] === 'done' ? 'selected' : '' ?>>Готово</option>
                                    </select>
                                    <button type="submit" name="change_status" class="btn-submit" style="padding: 8px 16px; margin-top: 0;">Изменить</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>