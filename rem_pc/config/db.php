<?php

$host = 'localhost';
$port = '5432';           
$dbname = 'rem_pc2';
$user = 'postgres';
$password = '123456';

try {
    $pdo = new PDO(
        "pgsql:host=$host;port=$port;dbname=$dbname",
        $user,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die('Ошибка подключения' . $e->getMessage());
}