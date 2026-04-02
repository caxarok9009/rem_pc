<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

if (empty($_SESSION['user']) || $_SESSION['user']['position'] !== 'Администратор') {
    die('Доступ запрещён');
}

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM devices_catalog WHERE id = :id");
$stmt->execute(['id' => $id]);
$device = $stmt->fetch();

if (!$device) {
    die('Устройство не найдено');
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать устройство</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="icon" href="../assets/images/rem_pc.ico" type="image/x-icon">
</head>
<body>

<a href="../admin_devices_catalog.php" class="back-button">Назад</a>

<div class="page-container">
    <h2 class="page-title">Редактировать устройство</h2>

    <div class="form-card">
        <form action="device_catalog_update.php" method="post">
            <input type="hidden" name="id" value="<?= $device['id'] ?>">

            <input type="text" name="category"
                   value="<?= htmlspecialchars($device['category']) ?>" required>

            <textarea name="description"><?= htmlspecialchars($device['description'] ?? '') ?></textarea>

            <button type="submit">Сохранить</button>
        </form>
    </div>
</div>

</body>
</html>
