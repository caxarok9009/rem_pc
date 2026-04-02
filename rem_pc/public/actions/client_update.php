<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

if (
    empty($_SESSION['user']) ||
    !in_array($_SESSION['user']['position'], ['Администратор', 'Приёмщик'])
) {
    die('Доступ запрещён');
}
$role = $_POST['role'] ?? $_SESSION['user']['position'];

$stmt = $pdo->prepare("
    UPDATE clients SET
        client_type = :client_type,
        last_name = :last_name,
        first_name = :first_name,
        middle_name = :middle_name,
        company_name = :company_name,
        phone = :phone,
        email = :email,
        address = :address,
        requisites = :requisites,
        notes = :notes
    WHERE id = :id
");

$stmt->execute([
    'id' => $_POST['id'],
    'client_type' => $_POST['client_type'] ?? null,
    'last_name' => $_POST['last_name'] ?? null,
    'first_name' => $_POST['first_name'] ?? null,
    'middle_name' => $_POST['middle_name'] ?? null,
    'company_name' => $_POST['company_name'] ?? null,
    'phone' => $_POST['phone'] ?? null,
    'email' => $_POST['email'] ?? null,
    'notes' => $_POST['notes'] ?? null,
    'address' => $_POST['address'] ?? null,
    'requisites' => $_POST['requisites'] ?? null
]);

if ($role === 'Администратор') {
    header('Location: ../admin_clients.php');
} else {
    header('Location: ../clients_list.php');
}
exit;
