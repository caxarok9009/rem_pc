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

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM accepted_devices WHERE id = :id");
$stmt->execute(['id' => $id]);
$device = $stmt->fetch();

if (!$device) {
    die('Устройство не найдено');
}

$clients = $pdo->query("
    SELECT id,
    CASE
        WHEN client_type = 'company'
            THEN company_name
        ELSE last_name || ' ' || first_name
    END AS name
    FROM clients
    ORDER BY name
")->fetchAll();

$catalog = $pdo->query("
    SELECT id, category
    FROM devices_catalog
    ORDER BY category
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Список устройств</title>
    <link rel="stylesheet" href="../assets/css/style.css"> 
    <link rel="icon" href="../assets/images/rem_pc.ico" type="image/x-icon">
</head>
<body>
    <a href="../accepted_devices.php" class="back-button">Назад</a>

<div class="page-container">
<h2 class="page-title">Редактировать устройство</h2>

<div class="form-card">
<form action="device_update.php" method="post">

<input type="hidden" name="id" value="<?= $device['id'] ?>">

<label>Клиент</label>
<select name="client_id">
<?php foreach ($clients as $c): ?>
<option value="<?= $c['id'] ?>"
    <?= $c['id'] == $device['client_id'] ? 'selected' : '' ?>>
    <?= htmlspecialchars($c['name'] ?? '') ?>
</option>
<?php endforeach; ?>
</select>

<label>Тип устройства</label>
<select name="device_catalog_id">
<?php foreach ($catalog as $d): ?>
<option value="<?= $d['id'] ?>"
    <?= $d['id'] == $device['device_catalog_id'] ? 'selected' : '' ?>>
    <?= htmlspecialchars($d['category']) ?>
</option>
<?php endforeach; ?>
</select>

<label>Серийный номер</label>
<input type="text" name="serial_number"
value="<?= htmlspecialchars($device['serial_number'] ?? '') ?>">

<label>Комплектующие</label>
<input type="text" name="accessories"
value="<?= htmlspecialchars($device['accessories'] ?? '') ?>">

<label>Описание проблемы</label>
<textarea name="reported_problem"><?= htmlspecialchars($device['reported_problem'] ?? '') ?></textarea>

<label>Предполагаемая дата готовности</label>
<input type="date" name="expected_return_date"
value="<?= htmlspecialchars($device['expected_return_date'] ?? '') ?>">

<label>Гарантия</label>
<input type="date" name="warranty_months"
value="<?= htmlspecialchars($device['warranty_months'] ?? '') ?>">

<label>Заметки</label>
<input type="text" name="notes"
value="<?= htmlspecialchars($device['notes'] ?? '') ?>">

<button type="submit">Сохранить изменения</button>
</form>
</div>
</div>
</body>
</html>