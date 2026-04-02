<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

if (empty($_SESSION['user']) || $_SESSION['user']['position'] !== 'Администратор') {
    die('Доступ запрещён');
}

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM employees WHERE id = :id");
$stmt->execute(['id' => $id]);
$employee = $stmt->fetch();

if (!$employee) {
    die('Сотрудник не найден');
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать сотрудника</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="icon" href="../assets/images/rem_pc.ico" type="image/x-icon">
</head>
<body>

<a href="../admin_employees.php" class="back-button">Назад</a>

<div class="page-container">
    <h2 class="page-title">Редактировать сотрудника</h2>

    <div class="form-card">
        <form action="employee_update.php" method="post">
            <input type="hidden" name="id" value="<?= $employee['id'] ?>">

            <input type="text" name="last_name" value="<?= htmlspecialchars($employee['last_name']) ?>" required>
            <input type="text" name="first_name" value="<?= htmlspecialchars($employee['first_name']) ?>" required>
            <input type="text" name="middle_name" value="<?= htmlspecialchars($employee['middle_name'] ?? '') ?>">

            <label>Должность</label>
            <label><select name="position">
                <?php foreach (['Администратор','Приёмщик','Мастер'] as $pos): ?>
                    <option value="<?= $pos ?>" <?= $employee['position'] === $pos ? 'selected' : '' ?>>
                        <?= $pos ?>
                    </option>
                <?php endforeach; ?>
            </select></label>

            <input type="text" name="phone" value="<?= htmlspecialchars($employee['phone'] ?? '') ?>">
            <input type="text" name="email" value="<?= htmlspecialchars($employee['email'] ?? '') ?>">
            <input type="text" name="login" value="<?= htmlspecialchars($employee['login']) ?>">
            <input type="text" name="password" placeholder="Введите новый пароль (если нужно)">

            <textarea name="notes"><?= htmlspecialchars($employee['notes'] ?? '') ?></textarea>

            <p><b>Дата добавления:</b> <?= htmlspecialchars($employee['created_at']) ?></p>

            <button type="submit">Сохранить</button>
        </form>
    </div>
</div>

</body>
</html>
