<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

if (empty($_SESSION['user']) || $_SESSION['user']['position'] !== 'Администратор') {
    die('Доступ запрещён');
}
$password = $_POST['password'] ?? '';

if (!empty($password)) {
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
} else {
    $stmt = $pdo->prepare("SELECT password FROM employees WHERE id = ?");
    $stmt->execute([$id]);
    $hashedPassword = $stmt->fetchColumn();
}
$stmt = $pdo->prepare("
    UPDATE employees SET
        last_name = :last_name,
        first_name = :first_name,
        middle_name = :middle_name,
        position = :position,
        phone = :phone,
        email = :email,
        login = :login,
        password = :password,
        notes = :notes
    WHERE id = :id
");

$stmt->execute([
    'id'          => $_POST['id'],
    'last_name'   => $_POST['last_name'] ?? null,
    'first_name'  => $_POST['first_name'] ?? null,
    'middle_name' => $_POST['middle_name'] ?? null,
    'position'    => $_POST['position'],
    'phone'       => $_POST['phone'] ?? null,
    'email'       => $_POST['email'] ?? null,
    'login'       => $_POST['login'] ?? null,
    'password' => $hashedPassword,
    'notes'       => $_POST['notes'] ?? null
]);

$_SESSION['success'] = 'Данные сотрудника обновлены';
header('Location: ../admin_employees.php');
exit;
