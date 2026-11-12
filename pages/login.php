<?php
require_once '../db.php';
require_once '../auth.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($login) || empty($password)) {
        $error = 'Заполните все поля.';
    } else {
        // Ищем пользователя по логину
        $stmt = $pdo->prepare("SELECT * FROM users WHERE login = ?");
        $stmt->execute([$login]);
        $user = $stmt->fetch();

        // Проверяем пароль (password_verify сравнивает введенный пароль и хеш из БД)
        if ($user && password_verify($password, $user['password'])) {
            // Успешный вход — создаем сессию
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['login'];
            $_SESSION['user_role'] = $user['role'];
            
            header("Location: ../index.php");
            exit;
        } else {
            $error = 'Неверный логин или пароль.';
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
    <title>Вход - GameCenter</title>
</head>
<body>
    <main class="auth-main">
        <div class="auth-card">
            <h1>Вход</h1>
            
            <?php if ($error): ?>
                <div style="background: #fce8e6; color: #d93025; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="auth-form">
                <div class="form-group">
                    <label>Логин</label>
                    <input type="text" name="login" placeholder="Ваш логин" value="<?= htmlspecialchars($_POST['login'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Пароль</label>
                    <input type="password" name="password" placeholder="Ваш пароль">
                </div>
                <button type="submit" class="btn-submit">Войти</button>
            </form>
            <p class="auth-switch">
                Нет аккаунта? <a href="register.php">Зарегистрироваться</a>
            </p>
        </div>
    </main>
</body>
</html>