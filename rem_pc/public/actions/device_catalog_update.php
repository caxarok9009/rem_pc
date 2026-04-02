<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

if (empty($_SESSION['user']) || $_SESSION['user']['position'] !== 'Администратор') {
    die('Доступ запрещён');
}

$stmt = $pdo->prepare("
    UPDATE devices_catalog
    SET category = :category,
        description = :description
    WHERE id = :id
");

$stmt->execute([
    'id' => $_POST['id'],
    'category' => $_POST['category'],
    'description' => $_POST['description'] ?? null
]);

$_SESSION['success'] = 'Изменения сохранены';
header('Location: ../admin_devices_catalog.php');
exit;
