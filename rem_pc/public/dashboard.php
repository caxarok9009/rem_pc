<?php
session_start();
if (empty($_SESSION['user'])) {
    header('Location: /index.php');
    exit;
}

$user = $_SESSION['user'];
$position = $user['position'];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Панель управления</title>
    <link rel="stylesheet" href="../assets/css/style.css"> 
    <link rel="icon" href="../assets/images/rem_pc.ico" type="image/x-icon">
</head>
<body>

<div class="header">
    <img src="../assets/images/logo.jpg" alt="Логотип" class="logo">

    <div class="user-menu">
    <h1 class="user-name" id="userName">
        <?= htmlspecialchars($user['name']) ?>

        <?php if ($position === 'Приёмщик'): ?>
            <img src="../assets/images/priem.png" alt="Приёмщик" title="Приёмщик" class="role-icon">
        <?php elseif ($position === 'Мастер'): ?>
            <img src="../assets/images/master.png" alt="Мастер" title="Мастер" class="role-icon">
        <?php elseif ($position === 'Администратор'): ?>
            <img src="../assets/images/admin.png" alt="Администратор" title="Администратор" class="role-icon">
        <?php endif; ?>
    </h1>
    <ul class="dropdown" id="dropdownMenu">
        <li><?= htmlspecialchars($user['position']) ?></li>
        <li><a href="logout.php">Выйти</a></li>
    </ul>
</div>
</div>

<div class="dashboard-container">
    <h2>Панель управления</h2>

    <?php if ($position  === 'Приёмщик'): ?>
        <div class="menu-buttons">
            <a href="clients_list.php" class="menu-btn">Показать всех клиентов</a>
            <a href="clients.php" class="menu-btn">Зарегистрировать клиента</a>
            <a href="device_add.php" class="menu-btn">Зарегистрировать устройство</a>
            <a href="device_history.php" class="menu-btn">Показать историю устройства</a>
            <a href="accepted_devices.php" class="menu-btn">Показать список устройств</a>
        </div>
        <?php elseif ($position === 'Мастер'): ?>
        <div class="menu-buttons">
            <a href="accepted_devices.php" class="menu-btn">Просмотр принятых устройств</a>
            <a href="device_update.php" class="menu-btn">Добавить запись о ходе работ</a>
            <a href="device_history.php" class="menu-btn">Просмотр истории устройства</a>
        </div>

    <?php elseif ($position === 'Администратор'): ?>
    <div class="menu-buttons">
        <a href="admin_clients.php" class="menu-btn">Клиенты</a>
        <a href="admin_employees.php" class="menu-btn">Сотрудники</a>
        <a href="admin_devices_catalog.php" class="menu-btn">Справочник устройств</a>
        <a href="admin_device_states.php" class="menu-btn">Состояния устройств</a>
        <a href="accepted_devices.php" class="menu-btn">Принятые устройства</a>
        <a href="device_history.php" class="menu-btn">История состояний</a>
    </div>
    <?php endif; ?>
</div>



<script>
document.addEventListener('DOMContentLoaded', function() {
    const userName = document.getElementById('userName');
    const dropdown = document.getElementById('dropdownMenu');

    if (!userName || !dropdown) return;

    userName.addEventListener('click', function(e) {
        e.stopPropagation();
        dropdown.classList.toggle('active');
    });

    document.addEventListener('click', function() {
        dropdown.classList.remove('active');
    });
});
</script>
</body>
</html>
