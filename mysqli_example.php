<?php

$db = mysqli_connect('localhost', 'root', '', 'cotton_plantation');

if (!$db) {
    die("Ошибка подключения: " . mysqli_connect_error());
}

echo "<h1>Тест MySQLi Prepared Statements</h1>";
echo "<hr>";


echo "<h2>1. SELECT запрос (правильный)</h2>";

$sql = "SELECT * FROM cotton_types WHERE price_per_ton > ? ORDER BY sort_name";
$stmt = mysqli_prepare($db, $sql);
if(!$stmt) {
    echo "Ошибка подготовки запроса: " . mysqli_error($db);
    exit();
}
echo "Запрос подготовлен<br>";

if(!mysqli_stmt_bind_param($stmt, 'd', $min_price)) {
    echo "Ошибка связывания параметров: " . mysqli_error($db);
    exit();
}
echo " Параметры связаны (d = decimal, значение: $min_price)<br>";

if(!mysqli_stmt_execute($stmt)) {
    echo " Ошибка выполнения запроса: " . mysqli_errori($db);
    exit();
}
echo " Запрос выполнен<br>";

$result = mysqli_stmt_get_result($stmt);
echo " Найдено записей: " . mysqli_num_rows($result) . "<br><br>";

while ($row = mysqli_fetch_assoc($result)) {
    echo $row['sort_name'] . " - " . $row['price_per_ton'] . " ₽<br>";
}

mysqli_stmt_close($stmt);


echo "<hr><h2>2. ОШИБКА: Несуществующая таблица</h2>";

$sql = "SELECT * FROM несуществующая_таблица WHERE id = ?";
$stmt = mysqli_prepare($db, $sql);
if(!$stmt) {
    echo "Ошибка подготовки запроса: " . mysqli_error($db);
    exit();
}
echo "Запрос подготовлен<br>";

mysqli_stmt_close($stmt);


?>