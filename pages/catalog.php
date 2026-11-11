<?php
// Подключаем базу данных (файл db.php лежит на уровень выше, в корне проекта)
require_once '../db.php';

// Делаем запрос к БД: берем все игры и присоединяем к ним названия жанров
$stmt = $pdo->query("SELECT games.*, genres.name AS genre_name FROM games LEFT JOIN genres ON games.genre_id = genres.id");
$games = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/style.css">
    <title>Каталог игр GameCenter</title>
</head>
<body>
    <header>
        <a href="../index.html" class="logo">Game<span>Center</span></a>
        <nav>
            <ul>
                <li><a href="../index.php">Главная</a></li>
                <!-- Теперь ссылка на каталог ведет на .php -->
                <li><a href="catalog.php">Каталог</a></li>
                <li><a href="login.html">Войти</a></li>
                <li><a href="register.html">Регистрация</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <!-- Блок добавления игры (пока просто ссылка, форму сделаем на следующем этапе) -->
        <div style="margin-bottom: 20px; text-align: right;">
            <a href="add_game.php" class="btn-welcome" style="padding: 8px 16px; font-size: 14px;">+ Добавить игру</a>
        </div>

        <section class="search-block">
            <input type="text" id="search" class="search-input" placeholder="Поиск игры..." autocomplete="off">
        </section>

        <section>
            <h2>Все игры</h2>
            <div class="games-grid" id="games-grid">
                
                <?php foreach ($games as $game): ?>
                    <div class="game-card">
                        <div class="game-cover">
                            <!-- Выводим картинку из БД -->
                            <img src="../<?= htmlspecialchars($game['image_url']) ?>" alt="<?= htmlspecialchars($game['title']) ?>">
                        </div>
                        <div class="game-body">
                            <!-- Выводим название и описание -->
                            <h3><?= htmlspecialchars($game['title']) ?></h3>
                            <p><?= htmlspecialchars($game['description']) ?></p>
                            <!-- Жанр (если он есть) -->
                            <?php if ($game['genre_name']): ?>
                                <span style="font-size: 12px; color: #6c8cff; margin-top: auto;">Жанр: <?= htmlspecialchars($game['genre_name']) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </section>
    </main>
    <footer>
        <div class="footer-inner">
            <div class="footer-col">
                <a href="../index.html" class="logo">Game<span>Center</span></a>
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
    <script src="../scripts/search.js"></script>
</body>
</html>