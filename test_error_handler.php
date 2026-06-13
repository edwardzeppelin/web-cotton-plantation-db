<?php

function userErrorHandler($errno, $errstr, $errfile, $errline) {
    echo "<div style='background: #ffebee; border: 2px solid #f44336; padding: 10px; margin: 10px 0;'>";
    echo "<strong>ОШИБКА:</strong> $errstr<br>";
    echo "<small>Файл: $errfile, Строка: $errline</small>";
    echo "</div>";
}

set_error_handler("userErrorHandler", E_USER_WARNING);

echo "<h1>Пример обработки ошибок</h1>";
echo "<hr>";

echo "<h2>1. Нефатальная ошибка (E_USER_WARNING)</h2>";

function calculateDistance($x1, $x2) {
    if (!is_numeric($x1) || !is_numeric($x2)) {
        trigger_error("Координаты должны быть числами!", E_USER_WARNING);
        return null;
    }
    return abs($x1 - $x2);
}

echo "Расчёт растояния между двумя точками: 5 и 'foo'<br>";

$result = calculateDistance(5, "foo");
echo "Результат: " . ($result ?? "ошибка") . "<br>";

echo "<hr><h2>2. Фатальная ошибка (E_USER_ERROR)</h2>";

function divide($a, $b) {
    if ($b == 0) {
        trigger_error("Деление на ноль запрещено!", E_USER_ERROR);
        return null;
    }
    return $a / $b;
}

echo "Делим 10 на 0<br>";
$result = divide(10, 0);
echo "Результат: $result<br>";

echo "<p>Эта строка не выполнится после фатальной ошибки</p>";
?>