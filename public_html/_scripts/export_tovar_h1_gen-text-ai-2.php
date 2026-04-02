<?php
header('Content-Type: text/html; charset=utf-8');

// Название товара
$name = 'Сенсор Пыли Cubic AM1002';

// Запрос к API
$url = 'https://test.gassensor.ru/backend/opisanie-ai?name=' . urlencode($name);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 120);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$result = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    echo 'Ошибка: ' . $error;
} else {
    echo $result;
}
?>