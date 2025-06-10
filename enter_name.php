<?php
$level = $_GET['level'] ?? 'easy';
?>
<!DOCTYPE html>
<html lang="lv">
<head>
  <meta charset="UTF-8">
  <title>Ievadi Vārdu</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <h1>Grūtības līmenis: <?= htmlspecialchars(ucfirst($level)) ?></h1>
  <form action="game.php" method="get" class="name-form">
    <input type="hidden" name="level" value="<?= htmlspecialchars($level) ?>">
    <label for="name">Ievadi savu vārdu:</label><br>
    <input type="text" name="name" id="name" required minlength="2" maxlength="20" autocomplete="off" placeholder="Tavs Vārds">
    <br>
    <button type="submit">Sākt Spēli</button>
  </form>
</body>
</html>


