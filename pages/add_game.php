<?php
require_once '../db.php';

$errors = [];
$success = false;

$genres = $pdo->query("SELECT * FROM genres")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $genre_id = $_POST['genre_id'] ?? '';

    // Валидация текстовых полей
    if (empty($title)) $errors[] = 'Название игры обязательно.';
    if (empty($description)) $errors[] = 'Описание игры обязательно.';
    if (empty($genre_id)) $errors[] = 'Выберите жанр.';

    // Проверяем, загружен ли файл
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'Загрузите картинку для игры.';
    } else {
        // Читаем содержимое файла в бинарный код
        $imageData = file_get_contents($_FILES['image']['tmp_name']);
    }

    // Если ошибок нет — сохраняем
    if (empty($errors)) {
        // Теперь сохраняем в колонку image_data
        $stmt = $pdo->prepare("INSERT INTO games (title, description, image_data, genre_id) VALUES (?, ?, ?, ?)");
        // Для бинарных данных используем PDO::PARAM_LOB
        $stmt->bindParam(1, $title);
        $stmt->bindParam(2, $description);
        $stmt->bindParam(3, $imageData, PDO::PARAM_LOB);
        $stmt->bindParam(4, $genre_id);
        
        if ($stmt->execute()) {
            $success = true;
        } else {
            $errors[] = 'Ошибка при сохранении в базу данных.';
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
    <title>Добавить игру - GameCenter</title>
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
            <h1>Добавить игру</h1>

            <?php if ($success): ?>
                <div style="background: #e6f4ea; color: #1e8e3e; padding: 12px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
                    Игра успешно добавлена! <br>
                    <a href="catalog.php" style="color: #1e8e3e; font-weight: bold; text-decoration: underline;">Перейти в каталог</a>
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

            <!-- Обязательно enctype="multipart/form-data" -->
            <form method="POST" class="auth-form" enctype="multipart/form-data">
                <div class="form-group">
                    <label>Название игры</label>
                    <input type="text" name="title" value="<?= htmlspecialchars($_POST['title'] ?? '') ?>" placeholder="Например, Cyberpunk 2077">
                </div>
                <div class="form-group">
                    <label>Описание</label>
                    <textarea name="description" rows="4" style="padding: 11px 14px; border: 1px solid #e0e0e0; border-radius: 8px; font-family: inherit; font-size: 15px;" placeholder="Краткое описание..."><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label>Обложка игры (картинка)</label>
                    <!-- Поле для загрузки файла -->
                    <input type="file" name="image" accept="image/*" required style="padding: 10px; border: 1px solid #e0e0e0; border-radius: 8px; background: #fff;">
                </div>
                <div class="form-group">
                    <label>Жанр</label>
                    <select name="genre_id" style="padding: 11px 14px; border: 1px solid #e0e0e0; border-radius: 8px; font-family: inherit; font-size: 15px;">
                        <option value="">-- Выберите жанр --</option>
                        <?php foreach ($genres as $genre): ?>
                            <option value="<?= $genre['id'] ?>" <?= (($_POST['genre_id'] ?? '') == $genre['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($genre['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <button type="submit" class="btn-submit" style="margin-top: 15px;">Сохранить игру</button>
            </form>
        </div>
    </main>
</body>
</html>