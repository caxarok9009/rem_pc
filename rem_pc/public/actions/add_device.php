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

$sql = "
INSERT INTO accepted_devices (
    device_uid,
    client_id,
    device_catalog_id,
    serial_number,
    accessories,
    reported_problem,
    received_at,
    expected_return_date,
    warranty_months,
    notes
) VALUES (
    :device_uid,
    :client_id,
    :device_catalog_id,
    :serial_number,
    :accessories,
    :reported_problem,
    NOW(),
    :expected_return_date,
    :warranty_months,
    :notes
)
RETURNING id
";

$device_uid = 'DEV-' . time();

$stmt = $pdo->prepare($sql);
$stmt->execute([
    'device_uid' => $device_uid,
    'client_id' => $_POST['client_id'],
    'device_catalog_id' => $_POST['device_catalog_id'],
    'serial_number' => $_POST['serial_number'] ?? null,
    'accessories' => $_POST['accessories'] ?? null,
    'reported_problem' => $_POST['reported_problem'] ?? null,
    'expected_return_date' => !empty($_POST['expected_return_date'])
        ? $_POST['expected_return_date']
        : null,
    'notes' => $_POST['notes'] ?? null,
    'warranty_months' => !empty($_POST['warranty_months'])
        ? $_POST['warranty_months']
        : null,
]);

$accepted_device_id = $stmt->fetchColumn();

$state_id = $pdo->query("
    SELECT id FROM device_states WHERE status = 'Приём'
")->fetchColumn();

$sql = "
INSERT INTO device_movements (
    accepted_device_id,
    state_id,
    employee_id,
    moved_at,
    note
) VALUES (
    :accepted_device_id,
    :state_id,
    :employee_id,
    NOW(),
    'Устройство принято'
)
";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    'accepted_device_id' => $accepted_device_id,
    'state_id' => $state_id,
    'employee_id' => $_SESSION['user']['id']
]);

header('Location: /dashboard.php?success=device_added');
exit;
