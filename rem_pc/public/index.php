<?php
session_start();
if (!empty($_SESSION['user'])) {
    header('Location: /dashboard.php');
    exit;
}

$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Авторизация</title>
   <link rel="stylesheet" href="../assets/css/style.css"> 
   <link rel="icon" href="../assets/images/rem_pc.ico" type="image/x-icon">
</head>
<body>

<div class="login-container">
    <img src="../assets/images/logo.jpg" alt="Логотип" class="logo">

    <h1>Авторизация</h1>

    <form action="login.php" method="post">
        <input type="text" name="login" placeholder="Логин" required>
        <input type="password" name="password" placeholder="Пароль" required>

        <button type="submit">Войти</button>
    </form>

    <?php if ($error): ?>
<p class="error"><?= $error ?></p>
    <?php endif; ?>
</div>

</body>
</html>
