<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

if (
    empty($_SESSION['user']) ||
    ($_SESSION['user']['position'] !== 'Приёмщик' &&
     $_SESSION['user']['position'] !== 'Администратор')
) {
    die('Доступ запрещён');
}

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = $pdo->prepare("DELETE FROM accepted_devices WHERE id = :id");
    $stmt->execute(['id' => $id]);
}

header('Location: ../accepted_devices.php');
exit;
?>
