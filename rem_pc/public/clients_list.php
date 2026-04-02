<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (
    empty($_SESSION['user']) ||
    ($_SESSION['user']['position'] !== 'Приёмщик' &&
     $_SESSION['user']['position'] !== 'Администратор')
) {
    die('Доступ запрещён');
}

try {
    $stmt = $pdo->query("SELECT * FROM clients ORDER BY id DESC");
    $clients = $stmt->fetchAll();
} catch (PDOException $e) {
    die('Ошибка при получении клиентов: ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Список клиентов</title>
    <link rel="stylesheet" href="../assets/css/style.css"> 
    <link rel="icon" href="../assets/images/rem_pc.ico" type="image/x-icon">
    </head>
<body>
<a href="dashboard.php" class="back-button">Назад</a>
<div class="page-container">
    <h2>Список клиентов</h2>

    <?php if (count($clients) === 0): ?>
        <p>Клиенты не найдены.</p>
    <?php else: ?>
    <div class="table-wrapper">
        <table class="clients-table">
            <thead>
                <tr>
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
                <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?= htmlspecialchars($client['client_type'] ?? '') ?></td>
                        <td><?= htmlspecialchars($client['last_name'] ?? '') ?></td>
                        <td><?= htmlspecialchars($client['first_name'] ?? '') ?></td>
                        <td><?= htmlspecialchars($client['middle_name'] ?? '') ?></td>
                        <td><?= htmlspecialchars($client['company_name'] ?? '') ?></td>
                        <td><?= htmlspecialchars($client['phone'] ?? '') ?></td>
                        <td><?= htmlspecialchars($client['email'] ?? '') ?></td>
                        <td class="wrap-text"><?= htmlspecialchars($client['address'] ?? '') ?></td>
                        <td><?= htmlspecialchars($client['requisites'] ?? '') ?></td>
                        <td class="wrap-text"><?= htmlspecialchars($client['notes'] ?? '') ?></td>
                        <td><?= htmlspecialchars($client['created_at'] ?? '') ?></td>
                        <td><div class="table-actions">
                                <a href="../actions/client_edit.php?id=<?= $client['id'] ?>" class="btn-edit">Редактировать</a>
<?php if ($_SESSION['user']['position'] === 'Администратор'): ?>
    <a href="../actions/delete_client.php?id=<?= $client['id'] ?>"
       class="btn-delete"
       onclick="return confirm('Удалить клиента?')">
       Удалить
    </a>
<?php endif; ?>
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