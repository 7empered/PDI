<?php require_once 'form.php'; ?>
<!DOCTYPE html>
<html lang="uk">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Реєстрація студента</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <h2>Реєстрація студента</h2>

    <form method="post" action="index.php">
      <input type="hidden" name="action" value="add">

      <label for="firstName">Ім’я:</label>
      <input type="text" id="firstName" name="firstName" required>

      <label for="lastName">Прізвище:</label>
      <input type="text" id="lastName" name="lastName" required>

      <label for="patronymic">По-батькові:</label>
      <input type="text" id="patronymic" name="patronymic" required>

      <label>Стать:</label>
      <label><input type="radio" name="gender" value="male" required> Чоловіча</label>
      <label><input type="radio" name="gender" value="female"> Жіноча</label>

      <label for="group">Група:</label>
      <select id="group" name="group" required>
        <option value="" disabled selected>– оберіть групу –</option>
        <option>Б-121-22-1-ПІ</option>
        <option>Б-121-22-2-ПІ</option>
        <option>Б-121-22-3-ПІ</option>
      </select>

      <button type="submit">Додати</button>
    </form>

    <h3>Список студентів</h3>
    <table>
      <thead>
        <tr>
          <th>Ім’я</th><th>Прізвище</th><th>По-батькові</th>
          <th>Стать</th><th>Група</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($_SESSION['students'] as $s): ?>
        <tr>
          <td><?= htmlspecialchars($s['firstName']) ?></td>
          <td><?= htmlspecialchars($s['lastName']) ?></td>
          <td><?= htmlspecialchars($s['patronymic']) ?></td>
          <td><?= $s['gender'] === 'male' ? 'Чоловіча' : 'Жіноча' ?></td>
          <td><?= htmlspecialchars($s['group']) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <div class="export-container">
      <a href="index.php?export=json" class="export-btn">Експорт JSON</a>
      <a href="index.php?export=xml"  class="export-btn">Експорт XML</a>
    </div>

    <div class="import-container">
      <form method="post" action="index.php" enctype="multipart/form-data">
        <input type="hidden" name="action" value="import">
        <input type="file" name="file" accept=".json,.xml" required>
        <button type="submit">Імпортувати із файлу</button>
      </form>
    </div>
  </div>
</body>
</html>