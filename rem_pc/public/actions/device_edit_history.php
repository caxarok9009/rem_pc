<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

if (
    empty($_SESSION['user']) ||
    ($_SESSION['user']['position'] !== 'Мастер' && 
     $_SESSION['user']['position'] !== 'Администратор')
) {
    die('Доступ запрещён');
}

$userRole = $_SESSION['user']['position'];
$device_id = $_GET['device_id'] ?? null;
if (!$device_id) {
    die('Не выбран прибор');
}

$deviceInfo = $pdo->prepare("
    SELECT ad.device_uid, dc.category
    FROM accepted_devices ad
    JOIN devices_catalog dc ON dc.id = ad.device_catalog_id
    WHERE ad.id = :id
");
$deviceInfo->execute(['id' => $device_id]);
$deviceInfo = $deviceInfo->fetch();

$history = $pdo->prepare("
    SELECT dm.id, dm.moved_at, ds.status, dm.note, e.id as employee_id, e.first_name, e.last_name
    FROM device_movements dm
    JOIN device_states ds ON ds.id = dm.state_id
    LEFT JOIN employees e ON e.id = dm.employee_id
    WHERE dm.accepted_device_id = :id
    ORDER BY dm.moved_at DESC
");
$history->execute(['id' => $device_id]);
$history = $history->fetchAll();

$states = $pdo->query("SELECT * FROM device_states ORDER BY id")->fetchAll();
$employees = $pdo->query("SELECT id, first_name, last_name FROM employees ORDER BY last_name")->fetchAll();
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактирование истории устройства</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="icon" href="../assets/images/rem_pc.ico" type="image/x-icon">
</head>
<body>

<a href="../device_history.php?device_id=<?= $device_id ?>" class="back-button">Назад</a>

<div class="page-container">
    <h2>Редактирование истории устройства: <?= htmlspecialchars($deviceInfo['device_uid']) ?></h2>

    <form method="post" action="device_update_history_action.php">
        <input type="hidden" name="device_id" value="<?= $device_id ?>">
        <div class="table-wrapper">
        <table class="history-table" style="margin-top: 20px;">
            <tr>
                <th>Дата</th>
                <th>Статус</th>
                <th>Сотрудник</th>
                <th>Комментарий</th>
            </tr>

            <?php foreach ($history as $h): ?>
                <tr>
                    <td>
                        <input type="datetime-local" name="history[<?= $h['id'] ?>][moved_at]" 
                               value="<?= date('Y-m-d\TH:i', strtotime($h['moved_at'])) ?>"
                               style="width: 180px; display: block; margin: 0 auto;">
                    </td>
                    <td>
                        <select name="history[<?= $h['id'] ?>][state_id]" 
                                style="width: 200px; display: block; margin: 0 auto; padding: 5px;">
                            <?php foreach ($states as $s): ?>
                                <option value="<?= $s['id'] ?>" <?= ($s['status'] === $h['status']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($s['status']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                            <td style="text-align:center;">
                                <?php
                                    $employeeName = isset($h['first_name'], $h['last_name']) 
                                        ? trim($h['last_name'].' '.$h['first_name']) 
                                        : '—';
                                ?>
                                <?php if ($userRole === 'Администратор'): ?>
                                    <select name="history[<?= $h['id'] ?>][employee_id]" style="width: 160px; padding: 4px;">
                                        <?php foreach ($employees as $e): ?>
                                            <option value="<?= $e['id'] ?>" <?= ($e['id'] == $h['employee_id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($e['last_name'].' '.$e['first_name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php else: ?>
                                    <?= htmlspecialchars($employeeName) ?>
                                <?php endif; ?>
                            </td>
                    <td>
                        <input type="text" name="history[<?= $h['id'] ?>][note]" 
                               value="<?= htmlspecialchars($h['note'] ?? '') ?>" 
                               class="wrap-text" style="width: 90%; margin: 0 auto; display: block;">
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        </div>
        <div style="display: flex; justify-content: center; margin-top: 20px;">
            <button type="submit" class="menu-btn" style="padding:10px 30px; font-size:16px;">
                Сохранить
            </button>
        </div>
    </form>

</div>

</body>
</html>
