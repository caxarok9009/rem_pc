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
INSERT INTO clients (
    client_type,
    first_name,
    last_name,
    middle_name,
    company_name,
    phone,
    email,
    address,
    requisites,
    notes,
    created_at
) VALUES (
    :client_type,
    :first_name,
    :last_name,
    :middle_name,
    :company_name,
    :phone,
    :email,
    :address,
    :requisites,
    :notes,
    NOW()
)";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'client_type'  => $_POST['client_type'] ?: null,
        'first_name'   => $_POST['first_name'] ?: null,
        'last_name'    => $_POST['last_name'] ?: null,
        'middle_name'  => $_POST['middle_name'] ?: null,
        'company_name' => $_POST['company_name'] ?: null,
        'phone'        => $_POST['phone'] ?: null,
        'email'        => $_POST['email'] ?: null,
        'address'      => $_POST['address'] ?: null,
        'requisites'   => $_POST['requisites'] ?: null,
        'notes'        => $_POST['notes'] ?: null,
        
    ]);
    header('Location: /dashboard.php?success=client_added');
    exit;
} catch (PDOException $e) {
    die('Ошибка при добавлении клиента: ' . $e->getMessage());
}
