<?php
$level = $_GET['level'] ?? 'easy';
$time = isset($_GET['time']) ? (int)$_GET['time'] : null;
$name = $_GET['name'] ?? 'Anonīms';

// Funkcija lasīt un sakārtot top 3 no faila
function getTopScores($filename) {
  if (!file_exists($filename)) return [];

  $lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  $scores = [];

  foreach ($lines as $line) {
    if (preg_match('/^(.*?) - (\d+)s$/', trim($line), $matches)) {
      $scores[] = ['name' => $matches[1], 'time' => (int)$matches[2]];
    }
  }

  usort($scores, function($a, $b) {
    return $a['time'] <=> $b['time'];
  });

  return array_slice($scores, 0, 3);
}
?>
<!DOCTYPE html>
<html lang="lv">
<head>
  <meta charset="UTF-8">
  <title>Rezultāti</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <h1>Spēle pabeigta!</h1>
  <h2>Tavs rezultāts (<?= htmlspecialchars($name) ?>): <?= htmlspecialchars($time) ?> sekundes</h2>

  <h2>Top 3 rezultāti līmenī <?= ucfirst(htmlspecialchars($level)) ?>:</h2>
  <table class="scores-table">
    <thead>
      <tr><th>Vārds</th><th>Laiks (sek)</th></tr>
    </thead>
    <tbody>
      <?php
      $topScores = getTopScores("scores_{$level}.txt");
      if (empty($topScores)) {
        echo '<tr><td colspan="2">Nav rezultātu.</td></tr>';
      } else {
        foreach ($topScores as $score) {
          echo '<tr><td>' . htmlspecialchars($score['name']) . '</td><td>' . htmlspecialchars($score['time']) . '</td></tr>';
        }
      }
      ?>
    </tbody>
  </table>
  <a href="index.php" class="btn">Atgriezties uz sākumu</a>
</body>
</html>
