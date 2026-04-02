<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (empty($_SESSION['user']) || $_SESSION['user']['position'] !== 'Администратор') {
    die('Доступ запрещён');
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить состояние устройства</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="icon" href="../assets/images/rem_pc.ico" type="image/x-icon">
</head>
<body>

<a href="admin_device_states.php" class="back-button">Назад</a>

<div class="page-container">
    <h2 class="page-title">Добавить состояние устройства</h2>

    <div class="form-card">
        <form action="../actions/device_state_create.php" method="post">
            <input type="text" name="status" placeholder="Название состояния" required>
            <textarea name="description" placeholder="Описание"></textarea>
            <button type="submit">Добавить</button>
        </form>
    </div>
</div>

</body>
</html>
