<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

if (
    empty($_SESSION['user']) ||
    ($_SESSION['user']['position'] !== 'Мастер' && 
     $_SESSION['user']['position'] !== 'Администратор')
) {
    die('Доступ запрещён');
}


$device_id = $_GET['device_id'] ?? null;
if (!$device_id) {
    die('Не выбрано устройство');
}

$stmt = $pdo->prepare("DELETE FROM device_movements WHERE accepted_device_id = :device_id");
$stmt->execute(['device_id' => $device_id]);

header("Location: ../device_history.php?device_id=" . $device_id);
exit;
