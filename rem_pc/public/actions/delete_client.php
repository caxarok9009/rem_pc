<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

if (
    empty($_SESSION['user']) ||
    !in_array($_SESSION['user']['position'], ['Администратор', 'Приёмщик'])
) {
    die('Доступ запрещён');
}

$id = (int)($_GET['id'] ?? 0);

try {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM accepted_devices WHERE client_id = :id");
    $stmt->execute(['id' => $id]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        $_SESSION['error'] = 'Нельзя удалить клиента — у него есть зарегистрированные устройства';
    } else {
        $stmt = $pdo->prepare("DELETE FROM clients WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $_SESSION['success'] = 'Клиент удалён';
    }
} catch (PDOException $e) {
    $_SESSION['error'] = 'Ошибка базы данных';
}


if ($_SESSION['user']['position'] === 'Администратор') {
    header('Location: ../admin_clients.php');
} else {
    header('Location: ../clients_list.php');
}
exit;
