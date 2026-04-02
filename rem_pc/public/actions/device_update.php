<?php
session_start();
require_once '../../config/db.php';

if (
    empty($_SESSION['user']) ||
    ($_SESSION['user']['position'] !== 'Приёмщик' &&
     $_SESSION['user']['position'] !== 'Администратор')
) {
    die('Доступ запрещён');
}

$sql = "
UPDATE accepted_devices SET
    client_id = :client_id,
    device_catalog_id = :device_catalog_id,
    serial_number = :serial_number,
    accessories = :accessories,
    reported_problem = :reported_problem,
    expected_return_date = :expected_return_date,
    warranty_months = :warranty_months,
    notes = :notes
WHERE id = :id
";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    'id' => $_POST['id'],
    'client_id' => $_POST['client_id'],
    'device_catalog_id' => $_POST['device_catalog_id'],
    'serial_number' => $_POST['serial_number'] ?: null,
    'accessories' => $_POST['accessories'] ?: null,
    'reported_problem' => $_POST['reported_problem'] ?: null,
    'expected_return_date' => $_POST['expected_return_date'] ?: null,
    'warranty_months' => $_POST['warranty_months'] ?: null,
    'notes' => $_POST['notes'] ?: null
]);

header('Location: ../accepted_devices.php');
exit;
