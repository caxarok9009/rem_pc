<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

if (
    empty($_SESSION['user']) ||
    ($_SESSION['user']['position'] !== 'Мастер' && 
     $_SESSION['user']['position'] !== 'Администратор')
) {
    die('Доступ запрещён');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $device_id = $_POST['device_id'] ?? null;
    $history = $_POST['history'] ?? [];

    foreach ($history as $history_id => $h) {
        $params = [
            'moved_at' => $h['moved_at'],
            'state_id' => $h['state_id'],
            'note'     => $h['note'],
            'id'       => $history_id
        ];

        if (isset($h['employee_id'])) {
            $sql = "
                UPDATE device_movements
                SET moved_at = :moved_at,
                    state_id = :state_id,
                    note = :note,
                    employee_id = :employee_id
                WHERE id = :id
            ";
            $params['employee_id'] = $h['employee_id'];
        } else {
            $sql = "
                UPDATE device_movements
                SET moved_at = :moved_at,
                    state_id = :state_id,
                    note = :note
                WHERE id = :id
            ";
        }

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
    }

    header("Location: ../../device_history.php?device_id=$device_id");
    exit;
}
