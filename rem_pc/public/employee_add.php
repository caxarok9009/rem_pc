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
    <title>Добавить сотрудника</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="icon" href="../assets/images/rem_pc.ico" type="image/x-icon">
</head>
<body>

<a href="admin_employees.php" class="back-button">Назад</a>

<div class="page-container">
    <h2 class="page-title">Добавить сотрудника</h2>

    <div class="form-card">
        <form action="../actions/employee_create.php" method="post">

            <input type="text" name="last_name" placeholder="Фамилия" required>
            <input type="text" name="first_name" placeholder="Имя" required>
            <input type="text" name="middle_name" placeholder="Отчество">

            <label>Должность</label>
            <label><select name="position" required>
                <option value="Администратор">Администратор</option>
                <option value="Приёмщик">Приёмщик</option>
                <option value="Мастер">Мастер</option>
            </select></label>

            <input type="text" name="phone" placeholder="Телефон">
            <input type="text" name="email" placeholder="Почта">

            <input type="text" name="login" placeholder="Логин" required>
            <input type="text" name="password" placeholder="Пароль" required>

            <textarea name="notes" placeholder="Комментарий"></textarea>

            <button type="submit">Добавить</button>
        </form>
    </div>
</div>

</body>
</html>
