<?php
require_once '../db.php';
require_once '../auth.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $login = trim($_POST['login'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    // СЕРВЕРНАЯ ВАЛИДАЦИЯ
    if (empty($fullname)) $errors[] = 'Введите ФИО.';
    if (empty($login)) $errors[] = 'Введите логин.';
    if (empty($email)) {
        $errors[] = 'Введите email.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Некорректный формат email.';
    }
    if (strlen($password) < 6) $errors[] = 'Пароль должен содержать минимум 6 символов.';
    if ($password !== $confirm) $errors[] = 'Пароли не совпадают.';

    // Проверка на уникальность логина и email
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE login = ? OR email = ?");
        $stmt->execute([$login, $email]);
        if ($stmt->fetch()) {
            $errors[] = 'Пользователь с таким логином или email уже существует.';
        }
    }

    // Сохранение в БД
    if (empty($errors)) {
        // ХЕШИРУЕМ ПАРОЛЬ (безопасность по ТЗ)
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (full_name, login, email, password, role) VALUES (?, ?, ?, ?, 'user')");
        $stmt->execute([$fullname, $login, $email, $hashed_password]);
        $success = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/style.css">
    <title>Регистрация - GameCenter</title>
</head>
<body>
    <main class="auth-main">
        <div class="auth-card">
            <h1>Регистрация</h1>
            
            <?php if ($success): ?>
                <div style="background: #e6f4ea; color: #1e8e3e; padding: 12px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
                    Регистрация успешна! <br>
                    <a href="login.php" style="color: #1e8e3e; font-weight: bold; text-decoration: underline;">Войти в аккаунт</a>
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

            <form method="POST" class="auth-form">
                <div class="form-group">
                    <label>ФИО</label>
                    <input type="text" name="fullname" placeholder="Ваше ФИО" value="<?= htmlspecialchars($_POST['fullname'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Логин</label>
                    <input type="text" name="login" placeholder="Логин" value="<?= htmlspecialchars($_POST['login'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Пароль</label>
                    <input type="password" name="password" placeholder="Минимум 6 символов">
                </div>
                <div class="form-group">
                    <label>Повтор пароля</label>
                    <input type="password" name="confirm" placeholder="Повторите пароль">
                </div>
                <button type="submit" class="btn-submit">Зарегистрироваться</button>
            </form>
            <p class="auth-switch">
                Уже есть аккаунт? <a href="login.php">Войти</a>
            </p>
        </div>
    </main>
</body>
</html>