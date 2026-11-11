<?php
require_once '../db.php';

// Получаем ID из GET
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // Удаляем запись
    $stmt = $pdo->prepare("DELETE FROM games WHERE id = ?");
    $stmt->execute([$id]);
}

// Перенаправляем обратно в каталог
header("Location: catalog.php");
exit;
?>