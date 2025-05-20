<?php
require_once 'conf.php';  // якщо далі знадобиться підключення до БД

// 1) Згенерувати 100 випадкових цілих чисел від 0 до 10
$numbers = [];
for ($i = 0; $i < 100; $i++) {
    $numbers[] = rand(0, 10);
}

// 2) Порахувати, скільки разів кожне число зустрічається
$frequency = array_count_values($numbers);

// 3) Відібрати тільки ті, які повторюються (кількість > 1)
$repeated = array_filter($frequency, fn($count) => $count > 1);

?>
<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Варіант 6 — Повторювані числа</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        pre { background: #f4f4f4; padding: 10px; }
    </style>
</head>
<body>
    <h1>Варіант 6: Повторювані числа</h1>

    <h2>1. Згенерований масив (100 чисел 0–10):</h2>
    <pre><?php print_r($numbers); ?></pre>

    <h2>2. Частота появи всіх чисел:</h2>
    <pre><?php print_r($frequency); ?></pre>

    <h2>3. Лише ті, що повторюються (кількість &gt; 1):</h2>
    <pre><?php print_r($repeated); ?></pre>
</body>
</html>