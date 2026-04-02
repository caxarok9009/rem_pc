<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (empty($_SESSION['user']) || $_SESSION['user']['position'] !== 'Администратор') {
    die('Доступ запрещён');
}

$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['error'], $_SESSION['success']);

$devices_catalog = $pdo->query("SELECT * FROM devices_catalog ORDER BY id")->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Администрирование справочника устройств</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="icon" href="../assets/images/rem_pc.ico" type="image/x-icon">
</head>
<body>

<a href="dashboard.php" class="back-button">Назад</a>

<div class="page-container">

    <?php if ($error): ?>
        <div class="alert alert-error small-alert"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success small-alert"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <h2 class="page-title">Справочник устройств</h2>

    <div class="actions-top">
        <a href="device_catalog_add.php" class="menu-btn">Добавить категорию и описание</a>
    </div>

    <?php if (count($devices_catalog) === 0): ?>
        <p>Устройства не найдены.</p>
    <?php else: ?>
        <div class="table-wrapper">
            <table class="clients-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Категория</th>
                        <th>Описание</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($devices_catalog as $d): ?>
                        <tr>
                            <td><?= (int)$d['id'] ?></td>
                            <td><?= htmlspecialchars($d['category'] ?? '') ?></td>
                            <td class="wrap-text"><?= htmlspecialchars($d['description'] ?? '') ?></td>
                            <td>
                                <div class="table-actions">
                                    <a href="../actions/device_catalog_edit.php?id=<?= $d['id'] ?>" class="btn-edit">Редактировать</a>
                                    <a href="../actions/delete_device_catalog.php?id=<?= $d['id'] ?>" class="btn-delete"
                                       onclick="return confirm('Удалить устройство?')">Удалить</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
