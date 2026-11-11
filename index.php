<?php
require_once 'db.php';

// Запрос 1: 4 случайные игры, которые мы рекомендуем
$stmtRec = $pdo->query("SELECT games.*, genres.name AS genre_name FROM games LEFT JOIN genres ON games.genre_id = genres.id WHERE games.is_recommended = 1 ORDER BY RAND() LIMIT 4");
$recommended = $stmtRec->fetchAll();

// Запрос 2: 4 случайные популярные игры
$stmtPop = $pdo->query("SELECT games.*, genres.name AS genre_name FROM games LEFT JOIN genres ON games.genre_id = genres.id WHERE games.is_recommended = 0 ORDER BY RAND() LIMIT 4");
$popular = $stmtPop->fetchAll();
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
                    <a href="./pages/game.php?id=<?= $game['id'] ?>" class="game-card" style="text-decoration: none; color: inherit; display: flex; flex-direction: column;">
                        <div class="game-cover">
                            <!-- Выводим картинку через get_image.php -->
                            <img src="./pages/get_image.php?id=<?= $game['id'] ?>" alt="<?= htmlspecialchars($game['title'] ?? '') ?>">
                        </div>
                        <div class="game-body">
                            <h3><?= htmlspecialchars($game['title'] ?? '') ?></h3>
                            <p><?= htmlspecialchars($game['description'] ?? '') ?></p>
                        </div>
                    </a>
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
                    <a href="./pages/game.php?id=<?= $game['id'] ?>" class="game-card" style="text-decoration: none; color: inherit; display: flex; flex-direction: column;">
                        <div class="game-cover">
                            <!-- Выводим картинку через get_image.php -->
                            <img src="./pages/get_image.php?id=<?= $game['id'] ?>" alt="<?= htmlspecialchars($game['title'] ?? '') ?>">
                        </div>
                        <div class="game-body">
                            <h3><?= htmlspecialchars($game['title'] ?? '') ?></h3>
                            <p><?= htmlspecialchars($game['description'] ?? '') ?></p>
                        </div>
                    </a>
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