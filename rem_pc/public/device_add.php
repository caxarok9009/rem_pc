<?php
session_start();
require_once '../config/db.php';

if (
    empty($_SESSION['user']) ||
    ($_SESSION['user']['position'] !== 'Приёмщик' &&
     $_SESSION['user']['position'] !== 'Администратор')
) {
    die('Доступ запрещён');
}


$clients = $pdo->query("
    SELECT
        id,
        email,
        CASE
            WHEN client_type = 'company'
                THEN company_name
            ELSE last_name || ' ' || first_name || ' ' || COALESCE(middle_name, '')
        END AS client_name
    FROM clients
    ORDER BY client_name
")->fetchAll();

$devices = $pdo->query("
    SELECT id, category
    FROM devices_catalog
    ORDER BY category
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация устройства</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="icon" href="../assets/images/rem_pc.ico" type="image/x-icon">
</head>
<body>
<a href="dashboard.php" class="back-button">Назад</a>

<div class="page-container">
    <h2 class="page-title">Регистрация устройства</h2>

    <div class="form-card">
        <form action="../actions/add_device.php" method="post">

    <label>Клиент</label>
    <select name="client_id" required>
        <option value="">Выберите клиента</option>
        <?php foreach ($clients as $c): ?>
            <option value="<?= $c['id'] ?>">
                <?= htmlspecialchars($c['client_name']) ?>
                <?= $c['email'] ? ' (' . htmlspecialchars($c['email']) . ')' : '' ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Тип устройства</label>
    <select name="device_catalog_id" required>
        <option value="">Выберите тип устройства</option>
        <?php foreach ($devices as $d): ?>
            <option value="<?= $d['id'] ?>">
                <?= htmlspecialchars($d['category']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label>Серийный номер</label>
    <input type="text" name="serial_number">

    <label>Комплектующие</label>
    <input type="text" name="accessories">

    <label>Описание проблемы</label>
    <textarea name="reported_problem"></textarea>

    <label>Предполагаемая дата готовности</label>
    <input type="date" name="expected_return_date">

    <label>Гарантия</label>
    <input type="date" name="warranty_months">

    <label>Заметки</label>
    <input type="text" name="notes">


    <button type="submit">Сохранить</button>
</form>

    </div>
</div>

</body>
</html>
