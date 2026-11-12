<?php
// auth.php
// Запускаем сессию, если она еще не запущена
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Функция проверки: авторизован ли пользователь?
// Если нет — перенаправляем на страницу входа.
function require_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /GameCenter/pages/login.php");
        exit;
    }
}

// 2. Функция проверки: является ли пользователь админом?
// Если нет — показываем ошибку 403 (Доступ запрещен).
function require_admin() {
    require_login(); // Сначала проверяем, что он вообще вошел
    if ($_SESSION['user_role'] !== 'admin') {
        die("<h1 style='text-align:center; margin-top:50px;'>Доступ запрещен (403)</h1><p style='text-align:center;'><a href='/GameCenter/index.php'>Вернуться на главную</a></p>");
    }
}

// 3. Вспомогательная функция, чтобы удобно получать данные текущего юзера
function current_user() {
    return [
        'id' => $_SESSION['user_id'] ?? null,
        'name' => $_SESSION['user_name'] ?? null,
        'role' => $_SESSION['user_role'] ?? null
    ];
}
?>