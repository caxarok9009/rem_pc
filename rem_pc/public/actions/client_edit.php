<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

if (
    empty($_SESSION['user']) ||
    !in_array($_SESSION['user']['position'], ['Администратор', 'Приёмщик'])
) {
    die('Доступ запрещён');
}

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM clients WHERE id = :id");
$stmt->execute(['id' => $id]);
$client = $stmt->fetch();

if (!$client) {
    die('Клиент не найден');
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактировать клиента</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="icon" href="../assets/images/rem_pc.ico" type="image/x-icon">
</head>
<body>

<?php
$backUrl = ($_SESSION['user']['position'] === 'Администратор')
    ? '../admin_clients.php'
    : '../clients_list.php';
?>

<a href="<?= $backUrl ?>" class="back-button">Назад</a>
<input type="hidden" name="from" value="<?= $_SESSION['user']['position'] ?>">

<div class="page-container">
    <h2 class="page-title">Редактировать клиента</h2>

    <div class="form-card">
        <form action="client_update.php" method="post">
        <input type="hidden" name="id" value="<?= $client['id'] ?>">
        <input type="hidden" name="role" value="<?= $_SESSION['user']['position'] ?>">
        

            <label>Тип клиента</label>
            <label><select name="client_type">
                <option value="Физическое лицо" <?= $client['client_type'] === 'Физическое лицо' ? 'selected' : '' ?>>Физическое лицо</option>
                <option value="Юридическое лицо" <?= $client['client_type'] === 'Юридическое лицо' ? 'selected' : '' ?>>Юридическое лицо</option>
            </select></label>

            <input type="text" name="last_name" value="<?= htmlspecialchars($client['last_name'] ?? '') ?>" placeholder="Фамилия">
            <input type="text" name="first_name" value="<?= htmlspecialchars($client['first_name'] ?? '') ?>" placeholder="Имя">
            <input type="text" name="middle_name" value="<?= htmlspecialchars($client['middle_name'] ?? '') ?>" placeholder="Отчество">

            <input type="text" name="company_name" value="<?= htmlspecialchars($client['company_name'] ?? '') ?>" placeholder="Компания">
            <input type="text" name="phone" value="<?= htmlspecialchars($client['phone'] ?? '') ?>" placeholder="Телефон">
            <input type="text" name="email" value="<?= htmlspecialchars($client['email'] ?? '') ?>" placeholder="Почта">
            <input type="text" name="address" value="<?= htmlspecialchars($client['address'] ?? '') ?>" placeholder="Адрес">
            <input type="text" name="requisites" value="<?= htmlspecialchars($client['requisites'] ?? '') ?>" placeholder="Реквизиты">
            <textarea name="notes" placeholder="Комментарий"><?= htmlspecialchars($client['notes'] ?? '') ?></textarea>
             <p><b>Дата регистрации:</b>
                <?= htmlspecialchars($client['created_at']) ?>
            </p>
            
            <button type="submit">Сохранить изменения</button>
        </form>
    </div>
</div>

</body>
</html>
