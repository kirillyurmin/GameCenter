<?php
require_once 'db.php';

// Запрос 1: Игры, которые мы рекомендуем (берем 4 штуки)
$stmtRec = $pdo->query("SELECT games.*, genres.name AS genre_name FROM games LEFT JOIN genres ON games.genre_id = genres.id WHERE games.is_recommended = 1 LIMIT 4");
$recommended = $stmtRec->fetchAll();

// Запрос 2: Популярные игры (берем все остальные)
$stmtPop = $pdo->query("SELECT games.*, genres.name AS genre_name FROM games LEFT JOIN genres ON games.genre_id = genres.id WHERE games.is_recommended = 0");
$popularAll = $stmtPop->fetchAll();

// Отрезаем от популярных ровно 4 игры (даже если их больше)
$popular = array_slice($popularAll, 0, 4);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/style.css">
    <title>GameCenter - Главная</title>
</head>
<body>
    <header>
        <a href="index.php" class="logo">Game<span>Center</span></a>
        <nav>
            <ul>
                <li><a href="index.php">Главная</a></li>
                <li><a href="./pages/catalog.php">Каталог</a></li>
                <li><a href="./pages/login.html">Войти</a></li>
                <li><a href="./pages/register.html">Регистрация</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section class="welcome">
            <h1>Добро пожаловать в GameCenter</h1>
            <p>
                GameCenter — это платформа для геймеров, где собраны лучшие игры в одном месте.
                Мы помогаем находить интересные проекты, следить за новинками и делиться
                впечатлениями с другими игроками. Всё просто, удобно и без лишнего.
            </p>
            <a href="./pages/catalog.php" class="btn-welcome">Каталог игр</a>
        </section>

        <!-- Блок "Мы рекомендуем" -->
        <?php if (count($recommended) > 0): ?>
        <section>
            <h2>Мы рекомендуем</h2>
            <div class="games-grid">
                <?php foreach ($recommended as $game): ?>
                    <div class="game-card">
                        <div class="game-cover">
                            <img src="<?= htmlspecialchars($game['image_url']) ?>" alt="<?= htmlspecialchars($game['title']) ?>">
                        </div>
                        <div class="game-body">
                            <h3><?= htmlspecialchars($game['title']) ?></h3>
                            <p><?= htmlspecialchars($game['description']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- Блок "Популярные игры" -->
        <?php if (count($popular) > 0): ?>
        <section>
            <h2>Популярные игры</h2>
            <div class="games-grid">
                <?php foreach ($popular as $game): ?>
                    <div class="game-card">
                        <div class="game-cover">
                            <img src="<?= htmlspecialchars($game['image_url']) ?>" alt="<?= htmlspecialchars($game['title']) ?>">
                        </div>
                        <div class="game-body">
                            <h3><?= htmlspecialchars($game['title']) ?></h3>
                            <p><?= htmlspecialchars($game['description']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

    </main>

    <footer>
        <div class="footer-inner">
            <div class="footer-col">
                <a href="index.php" class="logo">Game<span>Center</span></a>
                <p class="footer-about">
                    Платформа для геймеров: лучшие игры, новинки и сообщество.
                </p>
            </div>
            <div class="footer-col">
                <h4>Мы в соц.сетях</h4>
                <ul>
                    <li><a href="#">VK</a></li>
                    <li><a href="#">MAX</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Контакты</h4>
                <ul>
                    <li><a href="#">info@gamecenter.com</a></li>
                    <li><a href="#">Поддержка</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2026 GameCenter. Все права защищены.</p>
        </div>
    </footer>
</body>
</html>