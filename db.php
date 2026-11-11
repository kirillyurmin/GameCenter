<?php
// db.php
$host = 'localhost';
$dbname = 'gamecenter_db';
$username = 'root';
$password = 'root'; // В MAMP по умолчанию пароль от root - это 'root'

try {
    // Создаем подключение через PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Включаем режим ошибок, чтобы видеть проблемы, если они будут
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Подключение успешно!"; // Раскомментируй эту строку, если хочешь проверить
} catch (PDOException $e) {
    die("Ошибка подключения к БД: " . $e->getMessage());
}
?>