<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

if (empty($_SESSION['user']) || $_SESSION['user']['position'] !== 'Администратор') {
    die('Доступ запрещён');
}

if (empty($_POST['status'])) {
    $_SESSION['error'] = 'Название состояния обязательно';
    header('Location: ../device_state_add.php');
    exit;
}

$stmt = $pdo->prepare("
    INSERT INTO device_states (status, description)
    VALUES (:status, :description)
");

$stmt->execute([
    'status' => $_POST['status'],
    'description' => $_POST['description'] ?? null
]);

$_SESSION['success'] = 'Состояние добавлено';
header('Location: ../admin_device_states.php');
exit;
