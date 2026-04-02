<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (empty($_SESSION['user']) || $_SESSION['user']['position'] !== 'Администратор') {
    die('Доступ запрещён');
}

$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['error'], $_SESSION['success']);

$device_states = $pdo->query("SELECT * FROM device_states ORDER BY id")->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Администрирование состояния устройств</title>
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

    <h2 class="page-title">Состояния устройств</h2>

    <div class="actions-top">
        <a href="device_state_add.php" class="menu-btn">Добавить состояние устройства</a>
    </div>

    <?php if (count($device_states) === 0): ?>
        <p>Состояния устройств не найдены.</p>
    <?php else: ?>
        <div class="table-wrapper">
            <table class="clients-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Состояние</th>
                        <th>Описание</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($device_states as $s): ?>
                        <tr>
                            <td><?= (int)$s['id'] ?></td>
                            <td><?= htmlspecialchars($s['status'] ?? '') ?></td>
                            <td class="wrap-text"><?= htmlspecialchars($s['description'] ?? '') ?></td>
                            <td>
                                <div class="table-actions">
                                    <a href="../actions/device_state_edit.php?id=<?= $s['id'] ?>" class="btn-edit">Редактировать</a>
                                    <a href="../actions/delete_device_state.php?id=<?= $s['id'] ?>" class="btn-delete"
                                       onclick="return confirm('Удалить состояние?')">Удалить</a>
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
