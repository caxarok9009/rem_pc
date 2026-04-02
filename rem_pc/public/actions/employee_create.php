<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

if (empty($_SESSION['user']) || $_SESSION['user']['position'] !== 'Администратор') {
    die('Доступ запрещён');
}
$stmt = $pdo->prepare("SELECT COUNT(*) FROM employees WHERE login = :login");
$stmt->execute(['login' => $_POST['login']]);

if ($stmt->fetchColumn() > 0) {
    $_SESSION['error'] = 'Такой логин уже существует';
    header('Location: ../admin_employees.php');
    exit;
}

$passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);

$stmt = $pdo->prepare("
    INSERT INTO employees (
        last_name, first_name, middle_name,
        position, phone, email,
        login, password, notes, created_at
    ) VALUES (
        :last_name, :first_name, :middle_name,
        :position, :phone, :email,
        :login, :password, :notes, NOW()
    )
");

$stmt->execute([
    'last_name'   => $_POST['last_name'],
    'first_name'  => $_POST['first_name'],
    'middle_name' => $_POST['middle_name'] ?? null,
    'position'    => $_POST['position'],
    'phone'       => $_POST['phone'] ?? null,
    'email'       => $_POST['email'] ?? null,
    'login'       => $_POST['login']?? null,
    'password'    => $passwordHash,
    'notes'       => $_POST['notes'] ?? null
]);

$_SESSION['success'] = 'Сотрудник добавлен';
header('Location: ../admin_employees.php');
exit;
