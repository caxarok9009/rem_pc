<?php
session_start();
require_once '../config/db.php';

if ($_SESSION['user']['position'] !== 'Приёмщик' && $_SESSION['user']['position'] !== 'Администратор') {
    die('Доступ запрещён');
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить клиента</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="icon" href="../assets/images/rem_pc.ico" type="image/x-icon">
</head>
<body>
<a href="dashboard.php" class="back-button">Назад</a>
<div class="page-container">
    <h2 class="page-title">Добавить клиента</h2>

    <div class="form-card">
        <form action="../actions/add_client.php" method="post">

            <label><select name="client_type" required>
                <option value="Физическое лицо">Физическое лицо</option>
                <option value="Юридическое лицо">Юридическое лицо</option>
            </select></label>
            <input type="text" name="last_name" placeholder="Фамилия">
            <input type="text" name="first_name" placeholder="Имя">
            <input type="text" name="middle_name" placeholder="Отчество">

            <input type="text" name="company_name" placeholder="Название компании">
            <input type="text" name="phone" placeholder="Телефон">
            <input type="text" name="email" placeholder="Email">
            <input type="text" name="address" placeholder="Адрес">
            <input type="text" name="requisites" placeholder="Реквизиты">
            <input type="text" name="notes" placeholder="Комментарий">

            <button type="submit">Сохранить</button>
        </form>
    </div>
</div>

</body>
</html>
