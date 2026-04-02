<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (
    empty($_SESSION['user']) ||
    $_SESSION['user']['position'] !== 'Администратор'
) {
    die('Доступ запрещён');
}

$error = $_SESSION['error'] ?? '';
$success = $_SESSION['success'] ?? '';
unset($_SESSION['error'], $_SESSION['success']);

$clients = $pdo->query("
    SELECT * FROM clients ORDER BY created_at DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Администрирование клиентов</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="icon" href="../assets/images/rem_pc.ico" type="image/x-icon">
</head>
<body>

<a href="dashboard.php" class="back-button">Назад</a>

<?php if ($error): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<?php if ($success): ?>
    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
<?php endif; ?>

<div class="page-container">
    <h2 class="page-title">Клиенты</h2>

    <div class="actions-top">
        <a href="clients.php" class="menu-btn"> Добавить клиента</a>
    </div>

    <?php if (count($clients) === 0): ?>
        <p>Клиенты не найдены.</p>
    <?php else: ?>
        <div class="table-wrapper">
            <table class="clients-table">
                <thead>
                    <tr>
                    <th>ID</th>
                    <th>Тип клиента</th>
                    <th>Фамилия</th>
                    <th>Имя</th>
                    <th>Отчество</th>
                    <th>Компания</th>
                    <th>Телефон</th>
                    <th>Почта</th>
                    <th>Адрес</th>
                    <th>Реквизиты</th>
                    <th>Комментарий</th>
                    <th>Дата добавления</th>
                    <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($clients as $c): ?>
                    <tr>
                        <td><?= (int)$c['id'] ?></td>
                        <td><?= htmlspecialchars($c['client_type'] ?? '') ?></td>
                        <td><?= htmlspecialchars($c['last_name'] ?? '') ?></td>
                        <td><?= htmlspecialchars($c['first_name'] ?? '') ?></td>
                        <td><?= htmlspecialchars($c['middle_name'] ?? '') ?></td>
                        <td><?= htmlspecialchars($c['company_name'] ?? '') ?></td>
                        <td><?= htmlspecialchars($c['phone'] ?? '') ?></td>
                        <td><?= htmlspecialchars($c['email'] ?? '') ?></td>
                        <td class="wrap-text"><?= htmlspecialchars($c['address'] ?? '') ?></td>
                        <td><?= htmlspecialchars($c['requisites'] ?? '') ?></td>
                        <td class="wrap-text"><?= htmlspecialchars($c['notes'] ?? '') ?></td>
                        <td><?= htmlspecialchars($c['created_at'] ?? '') ?></td>

                            <td><div class="table-actions">
                                <a href="../actions/client_edit.php?id=<?= $c['id'] ?>" class="btn-edit">Редактировать</a>
                                <a href="../actions/delete_client.php?id=<?= $c['id'] ?>"
                                   class="btn-delete"
                                   onclick="return confirm('Удалить клиента?')">
                                   Удалить
                                </a>
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
