<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

if (empty($_SESSION['user']) || $_SESSION['user']['position'] !== 'Администратор') {
    die('Доступ запрещён');
}

$stmt = $pdo->prepare("
    UPDATE device_states SET
        status = :status,
        description = :description
    WHERE id = :id
");

$stmt->execute([
    'id' => $_POST['id'],
    'status' => $_POST['status'],
    'description' => $_POST['description'] ?? null
]);

$_SESSION['success'] = 'Состояние обновлено';
header('Location: ../admin_device_states.php');
exit;
