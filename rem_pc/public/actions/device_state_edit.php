<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

if (empty($_SESSION['user']) || $_SESSION['user']['position'] !== 'Администратор') {
    die('Доступ запрещён');
}

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM device_states WHERE id = :id");
$stmt->execute(['id' => $id]);
$state = $stmt->fetch();

if (!$state) {
    die('Состояние не найдено');
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать состояние</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="icon" href="../assets/images/rem_pc.ico" type="image/x-icon">
</head>
<body>

<a href="../admin_device_states.php" class="back-button">Назад</a>

<div class="page-container">
    <h2 class="page-title">Редактировать состояние</h2>

    <div class="form-card">
        <form action="device_state_update.php" method="post">
            <input type="hidden" name="id" value="<?= $state['id'] ?>">

            <input type="text" name="status"
                   value="<?= htmlspecialchars($state['status']) ?>" required>

            <textarea name="description"><?= htmlspecialchars($state['description'] ?? '') ?></textarea>

            <button type="submit">Сохранить</button>
        </form>
    </div>
</div>

</body>
</html>
