<?php
require_once '../db.php';
require_once '../auth.php';

// Если пользователь не вошел — показываем заглушку с предложением войти
if (!isset($_SESSION['user_id'])) {
    // Получаем данные пользователя из БД, если он вошел (но тут мы уже знаем, что не вошел)
    // Поэтому просто выводим HTML с кнопками
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="../styles/style.css">
        <title>Профиль - GameCenter</title>
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
        <main class="auth-main">
            <div class="auth-card" style="text-align: center;">
                <h1>Вы не авторизованы</h1>
                <p style="margin-bottom: 20px; color: #666;">Чтобы просматривать профиль, войдите или зарегистрируйтесь.</p>
                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <a href="login.php" class="btn-submit" style="text-decoration: none; text-align: center;">Войти</a>
                    <a href="register.php" class="btn-welcome" style="text-decoration: none; text-align: center; background: #eee; color: #333;">Зарегистрироваться</a>
                </div>
            </div>
        </main>
    </body>
    </html>
    <?php
    exit; // Останавливаем выполнение скрипта
}

// Если дошли сюда — значит пользователь авторизован
$user_id = $_SESSION['user_id'];

// Получаем актуальные данные пользователя из БД
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Считаем, сколько игр добавил пользователь
$stmt_games = $pdo->prepare("SELECT COUNT(*) FROM games WHERE user_id = ?");
$stmt_games->execute([$user_id]);
$games_count = $stmt_games->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../styles/style.css">
    <title>Мой профиль - GameCenter</title>
</head>
<body>
    <header>
        <a href="../index.php" class="logo">Game<span>Center</span></a>
        <nav>
            <ul>
                <li><a href="../index.php">Главная</a></li>
                <li><a href="catalog.php">Каталог</a></li>
                <li><a href="profile.php" class="active">Профиль</a></li>
            </ul>
        </nav>
    </header>
    <main class="auth-main">
        <div class="auth-card" style="max-width: 500px;">
            <h1>Мой профиль</h1>
            
            <div style="margin-bottom: 20px; padding: 15px; background: #f9f9f9; border-radius: 8px;">
                <p><strong>ФИО:</strong> <?= htmlspecialchars($user['full_name']) ?></p>
                <p><strong>Логин:</strong> <?= htmlspecialchars($user['login']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                <p><strong>Роль:</strong> <?= $user['role'] === 'admin' ? 'Администратор' : 'Пользователь' ?></p>
                <p><strong>Добавлено игр:</strong> <?= $games_count ?></p>
            </div>

            <div style="display: flex; flex-direction: column; gap: 10px;">
                <?php if ($user['role'] === 'admin'): ?>
                    <a href="admin.php" class="btn-submit" style="text-decoration: none; text-align: center; background: #333;">Перейти в админ-панель</a>
                <?php endif; ?>
                
                <a href="my_games.php" class="btn-submit" style="text-decoration: none; text-align: center;">Мои добавленные игры</a>
                <a href="logout.php" class="btn-submit" style="text-decoration: none; text-align: center; background: #e35b5b;">Выйти из аккаунта</a>
            </div>
        </div>
    </main>
</body>
</html>