<?php
require_once '../db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT image_data FROM games WHERE id = ?");
$stmt->execute([$id]);
$game = $stmt->fetch();

if ($game && $game['image_data']) {
    // Говорим браузеру, что это картинка (например, JPEG)
    header("Content-Type: image/jpeg");
    // Выводим сами бинарные данные
    echo $game['image_data'];
} else {
    // Если картинки нет, можно вывести заглушку (или ничего)
    http_response_code(404);
}