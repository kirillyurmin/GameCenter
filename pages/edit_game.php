<?php
require_once '../db.php';

// Получаем ID игры из GET-запроса
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Ищем игру в базе
$stmt = $pdo->prepare("SELECT * FROM games WHERE id = ?");
$stmt->execute([$id]);
$game = $stmt->fetch();

// Если игры нет — ошибка 404
if (!$game) {
    die("<h1 style='text-align:center; margin-top:50px;'>Игра не найдена (ошибка 404)</h1><p style='text-align:center;'><a href='catalog.php'>Вернуться в каталог</a></p>");
}

$errors = [];
$success = false;
$genres = $pdo->query("SELECT * FROM genres")->fetchAll();

// Если форму отправили (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $genre_id = $_POST['genre_id'] ?? '';

    if (empty($title)) $errors[] = 'Название обязательно.';
    if (empty($description)) $errors[] = 'Описание обязательно.';
    if (empty($genre_id)) $errors[] = 'Выберите жанр.';

    // Обработка картинки: если загружена новая — берем её, если нет — оставляем старую
    $imageData = $game['image_data']; // По умолчанию старая картинка
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageData = file_get_contents($_FILES['image']['tmp_name']);
    }

    if (empty($errors)) {
        // Обновляем запись в БД (UPDATE)
        $sql = "UPDATE games SET title = ?, description = ?, genre_id = ?, image_data = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(1, $title);
        $stmt->bindParam(2, $description);
        $stmt->bindParam(3, $genre_id);
        $stmt->bindParam(4, $imageData, PDO::PARAM_LOB);
        $stmt->bindParam(5, $id);
        
        if ($stmt->execute()) {
            $success = true;
            // Обновляем данные для отображения в форме
            $game['title'] = $title;
            $game['description'] = $description;
            $game['genre_id'] = $genre_id;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $game['image_data'] = $imageData;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/style.css">
    <title>Редактировать игру - GameCenter</title>
</head>
<body>
    <header>
        <a href="../index.php" class="logo">Game<span>Center</span></a>
        <nav>
            <ul>
                <li><a href="../index.php">Главная</a></li>
                <li><a href="catalog.php">Каталог</a></li>
            </ul>
        </nav>
    </header>

    <main class="auth-main">
        <div class="auth-card" style="max-width: 600px;">
            <h1>Редактировать игру</h1>

            <?php if ($success): ?>
                <div style="background: #e6f4ea; color: #1e8e3e; padding: 12px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
                    Изменения сохранены! <br>
                    <a href="catalog.php" style="color: #1e8e3e; font-weight: bold; text-decoration: underline;">Вернуться в каталог</a>
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div style="background: #fce8e6; color: #d93025; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                    <ul style="margin-left: 20px;">
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="POST" class="auth-form" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Название игры</label>
                    <input type="text" name="title" value="<?= htmlspecialchars($game['title']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Описание</label>
                    <textarea name="description" rows="4" style="padding: 11px 14px; border: 1px solid #e0e0e0; border-radius: 8px; font-family: inherit; font-size: 15px;" required><?= htmlspecialchars($game['description']) ?></textarea>
                </div>
                <div class="form-group">
                    <label>Текущая обложка:</label><br>
                    <!-- Выводим картинку через скрипт -->
                    <img src="get_image.php?id=<?= $game['id'] ?>" alt="Обложка" style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px; margin-bottom: 10px;">
                    <br>
                    <label>Загрузить новую обложку (если не выбрано — останется старая):</label>
                    <input type="file" name="image" accept="image/*" style="padding: 10px; border: 1px solid #e0e0e0; border-radius: 8px; background: #fff;">
                </div>
                <div class="form-group">
                    <label>Жанр</label>
                    <select name="genre_id" style="padding: 11px 14px; border: 1px solid #e0e0e0; border-radius: 8px; font-family: inherit; font-size: 15px;">
                        <?php foreach ($genres as $genre): ?>
                            <option value="<?= $genre['id'] ?>" <?= ($game['genre_id'] == $genre['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($genre['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <button type="submit" class="btn-submit" style="margin-top: 15px;">Сохранить изменения</button>
                <a href="catalog.php" style="text-align: center; display: block; margin-top: 15px; color: #666; text-decoration: none;">Отмена</a>
            </form>
        </div>
    </main>
</body>
</html>