<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

if (empty($_SESSION['user']) || $_SESSION['user']['position'] !== 'Администратор') {
    die('Доступ запрещён');
}

$id = (int)($_GET['id'] ?? 0);

try {
    $stmt = $pdo->prepare("DELETE FROM device_states WHERE id = :id");
    $stmt->execute(['id' => $id]);

    $_SESSION['success'] = 'Состояние удалено';
} catch (PDOException $e) {
    if ($e->getCode() === '23503') {
        $_SESSION['error'] = 'Нельзя удалить состояние — оно используется в истории устройств';
    } else {
        $_SESSION['error'] = 'Ошибка удаления: ' . $e->getMessage();
    }
}

header('Location: ../admin_device_states.php');
exit;
