<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (empty($_SESSION['user']) || $_SESSION['user']['position'] !== 'Администратор') {
    die('Доступ запрещён');
}

$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['error'], $_SESSION['success']);

$employees = $pdo->query("SELECT * FROM employees ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Администрирование сотрудников</title>
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

    <h2 class="page-title">Сотрудники</h2>

    <div class="actions-top">
        <a href="employee_add.php" class="menu-btn">Добавить сотрудника</a>
    </div>

    <?php if (count($employees) === 0): ?>
        <p>Сотрудники не найдены.</p>
    <?php else: ?>
        <div class="table-wrapper">
            <table class="clients-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Фамилия</th>
                        <th>Имя</th>
                        <th>Отчество</th>
                        <th>Должность</th>
                        <th>Телефон</th>
                        <th>Почта</th>
                        <th>Логин</th>
                        <th>Дата добавления</th>
                        <th>Комментарий</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($employees as $e): ?>
                        <tr>
                            <td><?= (int)$e['id'] ?></td>
                            <td><?= htmlspecialchars($e['last_name'] ?? '') ?></td>
                            <td><?= htmlspecialchars($e['first_name'] ?? '') ?></td>
                            <td><?= htmlspecialchars($e['middle_name'] ?? '') ?></td>
                            <td><?= htmlspecialchars($e['position'] ?? '') ?></td>
                            <td><?= htmlspecialchars($e['phone'] ?? '') ?></td>
                            <td><?= htmlspecialchars($e['email'] ?? '') ?></td>
                            <td><?= htmlspecialchars($e['login'] ?? '') ?></td>
                            <td><?= htmlspecialchars($e['created_at'] ?? '') ?></td>
                            <td><?= htmlspecialchars($e['notes'] ?? '') ?></td>
                            <td>
                                <div class="table-actions">
                                    <a href="../actions/employee_edit.php?id=<?= $e['id'] ?>" class="btn-edit">Редактировать</a>
                                    <a href="../actions/delete_employee.php?id=<?= $e['id'] ?>"
                                       class="btn-delete"
                                       onclick="return confirm('Удалить сотрудника?')">Удалить</a>
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
