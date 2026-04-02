<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

if (empty($_SESSION['user']) || $_SESSION['user']['position'] !== 'Администратор') {
    die('Доступ запрещён');
}

$id = (int)($_GET['id'] ?? 0);

try {
    $stmt = $pdo->prepare("DELETE FROM employees WHERE id = :id");
    $stmt->execute(['id' => $id]);

    $_SESSION['success'] = 'Сотрудник удалён';
} catch (PDOException $e) {
    $_SESSION['error'] = 'Нельзя удалить сотрудника (он используется в истории устройств)';
}

header('Location: ../admin_employees.php');
exit;

