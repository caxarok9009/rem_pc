<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (
    empty($_SESSION['user']) ||
    ($_SESSION['user']['position'] !== 'Приёмщик' &&
     $_SESSION['user']['position'] !== 'Администратор'&&
     $_SESSION['user']['position'] !== 'Мастер')
) {
    die('Доступ запрещён');
}
$userRole = $_SESSION['user']['position'];
try {
    $stmt = $pdo->query("
        SELECT 
            ad.id,
            ad.device_uid,
            ad.serial_number,
            ad.accessories,
            ad.reported_problem,
            ad.received_at,
            ad.expected_return_date,
            ad.notes,
            ad.warranty_months,
            c.client_type,
            c.first_name,
            c.last_name,
            c.middle_name,
            c.company_name,
            dc.category
        FROM accepted_devices ad
        JOIN clients c ON c.id = ad.client_id
        JOIN devices_catalog dc ON dc.id = ad.device_catalog_id
        ORDER BY ad.received_at DESC
    ");
    $devices = $stmt->fetchAll();
} catch (PDOException $e) {
    die('Ошибка при получении устройств: ' . $e->getMessage());
}
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
<a href="dashboard.php" class="back-button">Назад</a>

<div class="page-container">
    <h2>Список принятых устройств</h2>

    <?php if ( $userRole === 'Администратор'): ?>
        <div style="text-align:center; margin: 20px 0;">
            <a href="device_add.php" class="menu-btn">Добавить устройство</a>
        </div>
    <?php endif; ?>

    <?php if (count($devices) === 0): ?>
        <p>Устройства не найдены.</p>
    <?php else: ?>
        <div class="table-wrapper">
        <table class="clients-table">
            <thead>
                <tr>
                    <th>Приёмный номер</th>
                    <th>Категория</th>
                    <th>Серийный номер</th>
                    <th>Клиент</th>
                    <th>Тип клиента</th>
                    <th>Комплектующие</th>
                    <th>Проблема</th>
                    <th>Дата приёма</th>
                    <th>Предполагаемая дата готовности</th>
                    <th>Комментарий</th>
                    <th>Гарантия</th>

                    <?php if ($userRole === 'Приёмщик' || $userRole === 'Администратор'): ?>
                         <th>Действия</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($devices as $d): ?>
                    <tr>
                        <td><?= htmlspecialchars($d['device_uid']?? '') ?></td>
                        <td><?= htmlspecialchars($d['category']?? '') ?></td>
                        <td><?= htmlspecialchars($d['serial_number']?? '') ?></td>
                        <td>
                            <?= htmlspecialchars(
                                $d['client_type'] === 'company' 
                                    ? $d['company_name'] 
                                    : trim($d['last_name'] . ' ' . $d['first_name'] . ' ' . $d['middle_name'])
                            ) ?>
                        </td>
                        <td><?= htmlspecialchars($d['client_type']?? '') ?></td>
                        <td class="wrap-text"><?= htmlspecialchars($d['accessories']?? '') ?></td>
                        <td class="wrap-text"><?= htmlspecialchars($d['reported_problem']?? '') ?></td>
                        <td><?= htmlspecialchars($d['received_at']?? '') ?></td>
                        <td><?= htmlspecialchars($d['expected_return_date']?? '') ?></td>
                        <td class="wrap-text"><?= htmlspecialchars($d['notes'] ?? '') ?></td>
                        <td><?= htmlspecialchars($d['warranty_months']?? '') ?></td>
                       <?php if ($userRole === 'Приёмщик' || $userRole === 'Администратор'): ?>
                        <td>
                            <div class="table-actions">
                                <a href="../actions/device_edit.php?id=<?= $d['id'] ?>" class="btn-edit">Редактировать</a>
                                <?php if ($userRole === 'Приёмщик' || $userRole === 'Администратор'): ?>
                                    <a href="../actions/device_delete.php?id=<?= $d['id'] ?>" 
                                    class="btn-delete" 
                                    onclick="return confirm('Вы точно хотите удалить это устройство?')">
                                    Удалить
                                    </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>
</body>
</html>
