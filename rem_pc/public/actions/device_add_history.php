<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

if (
    empty($_SESSION['user']) ||
    $_SESSION['user']['position'] !== 'Администратор'
) {
    die('Доступ запрещён');
}

$user = $_SESSION['user'];
$device_id = $_GET['device_id'] ?? null;

if (!$device_id) {
    die('Не выбрано устройство');
}


$stmt = $pdo->prepare("SELECT device_uid FROM accepted_devices WHERE id = :id");
$stmt->execute(['id' => $device_id]);
$device = $stmt->fetch();
if (!$device) {
    die('Устройство не найдено');
}


$employees = $pdo->query("SELECT id, last_name, first_name FROM employees ORDER BY last_name")->fetchAll();
$states = $pdo->query("SELECT id, status FROM device_states ORDER BY id")->fetchAll();

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $state_id = $_POST['state_id'] ?? null;
    $employee_id = $_POST['employee_id'] ?? null;
    $moved_at = $_POST['moved_at'] ?? date('Y-m-d H:i:s');
    $note = $_POST['note'] ?? '';

    if (!$state_id || !$employee_id) {
        $error = 'Выберите сотрудника и статус';
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO device_movements (accepted_device_id, state_id, employee_id, moved_at, note)
            VALUES (:device_id, :state_id, :employee_id, :moved_at, :note)
        ");
        $stmt->execute([
            'device_id' => $device_id,
            'state_id' => $state_id,
            'employee_id' => $employee_id,
            'moved_at' => $moved_at,
            'note' => $note
        ]);

        header("Location: ../device_history.php?device_id=" . $device_id);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить запись в историю</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="icon" href="../assets/images/rem_pc.ico" type="image/x-icon">
</head>
<body>

<a href="../device_history.php?device_id=<?= $device_id ?>" class="back-button">Назад</a>

<div class="page-container">
    <h2 class="page-title">Добавить запись для устройства <?= htmlspecialchars($device['device_uid']) ?></h2>

    <div class="form-card">
        <?php if ($success): ?>
            <p class="success"><?= htmlspecialchars($success) ?></p>
        <?php endif; ?>
        <?php if ($error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="post">
            <label>Дата и время:</label>
            <input type="datetime-local" name="moved_at" value="<?= date('Y-m-d\TH:i') ?>" required>

            <label>Статус:</label>
            <select name="state_id" required>
                <option value="">— выберите —</option>
                <?php foreach ($states as $s): ?>
                    <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['status']) ?></option>
                <?php endforeach; ?>
            </select>

            <label>Сотрудник:</label>
            <select name="employee_id" required>
                <option value="">— выберите —</option>
                <?php foreach ($employees as $e): ?>
                    <option value="<?= $e['id'] ?>"><?= htmlspecialchars($e['last_name'] . ' ' . $e['first_name']) ?></option>
                <?php endforeach; ?>
            </select>

            <label>Комментарий:</label>
            <textarea name="note" rows="4" placeholder="Комментарий"></textarea>

            <div style="text-align:center; margin-top:10px;">
                <button type="submit" class="menu-btn">Сохранить</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
