<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

if (empty($_SESSION['user']) || $_SESSION['user']['position'] !== 'Администратор') {
    die('Доступ запрещён');
}

$id = (int)($_GET['id'] ?? 0);

try {
    $stmt = $pdo->prepare("DELETE FROM devices_catalog WHERE id = :id");
    $stmt->execute(['id' => $id]);

    $_SESSION['success'] = 'Устройство удалено';
} catch (PDOException $e) {
    $_SESSION['error'] = 'Нельзя удалить — устройство используется в принятых';
}

header('Location: ../admin_devices_catalog.php');
exit;
