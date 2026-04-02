<?php
session_start();
require_once '../config/db.php';

if (
    empty($_SESSION['user']) ||
    ($_SESSION['user']['position'] !== 'Приёмщик' &&
     $_SESSION['user']['position'] !== 'Администратор' &&
     $_SESSION['user']['position'] !== 'Мастер')
) {
    die('Доступ запрещён');
}

$userRole = $_SESSION['user']['position'];

$devices = $pdo->query("
    SELECT 
        ad.id,
        ad.device_uid,
        dc.category,
        CASE 
            WHEN c.client_type = 'company'
                THEN c.company_name
            ELSE c.last_name || ' ' || c.first_name
        END AS client_name
    FROM accepted_devices ad
    JOIN clients c ON c.id = ad.client_id
    JOIN devices_catalog dc ON dc.id = ad.device_catalog_id
    ORDER BY ad.received_at DESC
")->fetchAll();

$deviceInfo = null;
$history = [];

if (!empty($_GET['device_id'])) {
    $stmt = $pdo->prepare("
        SELECT 
            ad.device_uid,
            ad.serial_number,
            ad.reported_problem,
            dc.category
        FROM accepted_devices ad
        JOIN devices_catalog dc ON dc.id = ad.device_catalog_id
        WHERE ad.id = :id
    ");
    $stmt->execute(['id' => $_GET['device_id']]);
    $deviceInfo = $stmt->fetch();

    $stmt = $pdo->prepare("
        SELECT 
            dm.id as movement_id,
            dm.moved_at,
            ds.status,
            dm.note,
            e.id as employee_id,
            e.last_name,
            e.first_name
        FROM device_movements dm
        JOIN device_states ds ON ds.id = dm.state_id
        LEFT JOIN employees e ON e.id = dm.employee_id
        WHERE dm.accepted_device_id = :id
        ORDER BY dm.moved_at DESC
    ");
    $stmt->execute(['id' => $_GET['device_id']]);
    $history = $stmt->fetchAll();
}

$employees = $pdo->query("SELECT id, last_name, first_name FROM employees ORDER BY last_name")->fetchAll();
$states = $pdo->query("SELECT id, status FROM device_states ORDER BY id")->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>История устройства</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="icon" href="../assets/images/rem_pc.ico" type="image/x-icon">
</head>
<body>

<a href="dashboard.php" class="back-button">Назад</a>

<div class="page-container">
    <h2 class="page-title">История устройства</h2>

    <div class="form-card">
        <form method="get">
            <label>Выберите устройство</label>
            <select name="device_id" onchange="this.form.submit()">
                <option value="">— выберите —</option>
                <?php foreach ($devices as $d): ?>
                    <option value="<?= $d['id'] ?>"
                        <?= (!empty($_GET['device_id']) && $_GET['device_id'] == $d['id']) ? 'selected' : '' ?> >
                        <?= htmlspecialchars($d['device_uid'] ?? '') ?>
                        (<?= htmlspecialchars($d['client_name'] ?? '') ?>,
                         <?= htmlspecialchars($d['category'] ?? '') ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <?php if ($deviceInfo): ?>
            <hr>
            <h3>Информация об устройстве</h3>
            <p><b>Приёмный номер:</b> <?= htmlspecialchars($deviceInfo['device_uid'] ?? '') ?></p>
            <p><b>Тип:</b> <?= htmlspecialchars($deviceInfo['category'] ?? '') ?></p>
            <p><b>Серийный номер:</b> <?= htmlspecialchars($deviceInfo['serial_number'] ?? '') ?></p>
            <p><b>Проблема:</b> <?= htmlspecialchars($deviceInfo['reported_problem'] ?? '') ?></p>

            <h3>История состояний</h3>
            <div class="table-wrapper">
            <table class="history-table">
                <tr>
                    <th>Дата</th>
                    <th>Статус</th>
                    <th>Сотрудник</th>
                    <th>Комментарий</th>
                </tr>
                <?php foreach ($history as $h): ?>
                    <tr>
                        <td><?= htmlspecialchars($h['moved_at']) ?></td>
                        <td><?= htmlspecialchars($h['status']) ?></td>
                        <td><?= htmlspecialchars(trim(($h['last_name'] ?? '') . ' ' . ($h['first_name'] ?? ''))) ?></td>
                        <td class="wrap-text"><?= htmlspecialchars($h['note'] ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>
           
            </table>
            </div>
            
                    <?php if ($deviceInfo): ?>
            <?php if ($userRole === 'Администратор'): ?>
                <div style="display:flex; justify-content:center; gap:10px; margin-top:15px;">
                    <?php if ($userRole === 'Администратор'): ?>
                        <a href="actions/device_add_history.php?device_id=<?= $_GET['device_id'] ?>" 
                        class="menu-btn">Добавить</a>
                        
                   
                    <a href="actions/device_edit_history.php?device_id=<?= $_GET['device_id'] ?>" 
                  class="menu-btn">
                    Редактировать
                    </a>
                    <a href="actions/device_delete_history.php?device_id=<?= $_GET['device_id'] ?>" 
                        onclick="return confirm('Вы точно хотите удалить всю историю этого устройства?')"
                        class="menu-btn-delet">Удалить</a>
                     <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php endif; ?>

    </div>
</div>

</body>
</html>
