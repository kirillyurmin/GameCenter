<?php
// Подключаем базу данных
require_once '../db.php';
require_once '../auth.php';

// Делаем запрос к БД
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
        <a href="../index.php" class="logo">Game<span>Center</span></a>
        <nav>
            <ul>
                <li><a href="../index.php">Главная</a></li>
                <li><a href="./catalog.php">Каталог</a></li>
                <li><a href="./profile.php">Профиль</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section class="search-block">
            <input type="text" id="search" class="search-input" placeholder="Поиск игры..." autocomplete="off">
        </section>

        <section>
            <h2>Все игры</h2>
            <div class="games-grid" id="games-grid">
                
                <?php foreach ($games as $game): ?>
                    <a href="game.php?id=<?= $game['id'] ?>" class="game-card" data-search="<?php echo htmlspecialchars($game['title']) . ' ' . htmlspecialchars($game['genre_name'] ?? ''); ?>" style="text-decoration: none; color: inherit; display: flex; flex-direction: column;">
                        <div class="game-cover">
                            <img src="get_image.php?id=<?= $game['id'] ?>" alt="<?= htmlspecialchars($game['title']) ?>">
                        </div>
                        <div class="game-body">
                            <h3><?= htmlspecialchars($game['title']) ?></h3>
                            <p><?= htmlspecialchars($game['description']) ?></p>
                            <?php if ($game['genre_name']): ?>
                                <span style="font-size: 12px; color: #6c8cff; margin-top: auto;">Жанр: <?= htmlspecialchars($game['genre_name']) ?></span>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endforeach; ?>

            </div>
        </section>

        <!-- Кнопка добавления игры внизу каталога -->
        <?php if (isset($_SESSION['user_id'])): ?>
            <div style="text-align: center; margin-top: 40px; margin-bottom: 20px;">
                <a href="./add_game.php" class="btn-welcome" style="padding: 12px 28px; font-size: 16px; display: inline-block;">+ Добавить игру</a>
            </div>
        <?php endif; ?>

    </main>
    
    <footer>
        <div class="footer-inner">
            <div class="footer-col">
                <a href="../index.php" class="logo">Game<span>Center</span></a>
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