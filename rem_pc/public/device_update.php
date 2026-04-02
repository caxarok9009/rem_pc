<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (
    empty($_SESSION['user']) ||
    ($_SESSION['user']['position'] !== 'Администратор'&&
    $_SESSION['user']['position'] !== 'Мастер'
    )
) {
    die('Доступ запрещён');
}

$user = $_SESSION['user'];
$success = '';
$error = '';

$devices = $pdo->query("
    SELECT ad.id, ad.device_uid, dc.category,
           CASE 
               WHEN c.client_type = 'company' THEN c.company_name
               ELSE c.last_name || ' ' || c.first_name
           END AS client_name
    FROM accepted_devices ad
    JOIN clients c ON c.id = ad.client_id
    JOIN devices_catalog dc ON dc.id = ad.device_catalog_id
    ORDER BY ad.received_at DESC
")->fetchAll();

$states = $pdo->query("SELECT * FROM device_states ORDER BY id")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $device_id = $_POST['device_id'] ?? null;
    $state_id = $_POST['state_id'] ?? null;
    $note = $_POST['note'] ?? '';

    if (!$device_id || !$state_id) {
        $error = 'Выберите устройство и статус';
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO device_movements (accepted_device_id, state_id, employee_id, moved_at, note)
            VALUES (:device_id, :state_id, :employee_id, NOW(), :note)
        ");
        $stmt->execute([
            'device_id' => $device_id,
            'state_id' => $state_id,
            'employee_id' => $user['id'],
            'note' => $note
        ]);
        header('Location: dashboard.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить запись о ходе работ</title>
    <link rel="stylesheet" href="../assets/css/style.css"> 
    <link rel="icon" href="../assets/images/rem_pc.ico" type="image/x-icon">
</head>
<body>

<a href="dashboard.php" class="back-button">Назад</a>

<div class="page-container">
    <h2 class="page-title">Добавить запись о ходе работ</h2>
    <div class="form-card">

    <?php if ($success): ?>
        <p class="success"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>
    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="post">
        <label>Выберите устройство:</label>
        <select name="device_id" required>
            <option value="">— выберите —</option>
            <?php foreach ($devices as $d): ?>
                <option value="<?= $d['id'] ?>">
                    <?= htmlspecialchars($d['device_uid']) ?> 
                    (<?= htmlspecialchars($d['client_name']) ?>, <?= htmlspecialchars($d['category']) ?>)
                </option>
            <?php endforeach; ?>
        </select>

        <label>Выберите статус:</label>
        <select name="state_id" required>
            <option value="">— выберите —</option>
            <?php foreach ($states as $s): ?>
                <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['status']) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Комментарий:</label>
        <textarea name="note" rows="4" placeholder="Добавьте комментарий к работе"></textarea>

        <button type="submit" class="menu-btn">Сохранить</button>
    </form>
</div>
</div>

</body>
</html>
