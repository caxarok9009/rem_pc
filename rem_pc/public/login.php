<?php
session_start();

require_once '../config/db.php';

$login = $_POST['login'] ?? '';
$password = $_POST['password'] ?? '';

$sql = "SELECT * FROM employees WHERE login = :login";
$stmt = $pdo->prepare($sql);
$stmt->execute(['login' => $login]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user'] = [
        'id' => $user['id'],
        'name' => $user['last_name'] . ' ' . $user['first_name']. ' ' . $user['middle_name'],
        'position' => $user['position']
    ];
    header('Location: /dashboard.php');
    exit;
} else {
    $_SESSION['error'] = 'Неверный логин или пароль';
    header('Location: /index.php');
    exit;
}
