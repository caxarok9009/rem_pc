<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

if (empty($_SESSION['user']) || $_SESSION['user']['position'] !== 'Администратор') {
    die('Доступ запрещён');
}

if (empty($_POST['category'])) {
    $_SESSION['error'] = 'Категория обязательна';
    header('Location: ../admin_devices_catalog.php');
    exit;
}

$stmt = $pdo->prepare("
    INSERT INTO devices_catalog (category, description)
    VALUES (:category, :description)
");

$stmt->execute([
    'category' => $_POST['category'],
    'description' => $_POST['description'] ?? null
]);

$_SESSION['success'] = 'Категория добавлена';
header('Location: ../admin_devices_catalog.php');
exit;
