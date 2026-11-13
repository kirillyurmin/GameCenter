<?php
require_once '../db.php';
require_once '../auth.php';
require_login();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // Проверяем, принадлежит ли игра пользователю (или он админ)
    $stmt = $pdo->prepare("SELECT user_id FROM games WHERE id = ?");
    $stmt->execute([$id]);
    $game = $stmt->fetch();

    if ($game && ($game['user_id'] == $_SESSION['user_id'] || $_SESSION['user_role'] === 'admin')) {
        $stmt = $pdo->prepare("DELETE FROM games WHERE id = ?");
        $stmt->execute([$id]);
    }
}

// Проверяем, откуда пришел пользователь
$redirect = isset($_GET['from']) && $_GET['from'] === 'my_games' ? 'my_games.php' : 'catalog.php';

header("Location: " . $redirect);
exit;
?>