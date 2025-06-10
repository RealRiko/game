<?php
$level = $_GET['level'] ?? 'easy';
$name = $_GET['name'] ?? 'Anonīms';
$name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');

$sizes = ['easy' => 3, 'medium' => 4, 'hard' => 5];
$size = $sizes[$level] ?? 3;
$totalCards = $size * $size;

// Pāru skaits
$hasFake = $totalCards % 2 !== 0;
$pairs = floor($totalCards / 2);

// Kāršu ģenerēšana
$cards = [];
for ($i = 1; $i <= $pairs; $i++) {
  $cards[] = $i;
  $cards[] = $i;
}

if ($hasFake) {
  $cards[] = 'X'; // Fake karte
}

shuffle($cards);
?>
<!DOCTYPE html>
<html lang="lv">
<head>
  <meta charset="UTF-8">
  <title>Spēle - <?= ucfirst($level) ?></title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <h1>Spēles līmenis: <?= ucfirst($level) ?></h1>
  <h2>Spēlētājs: <?= $name ?></h2>

  <div class="game-board" style="grid-template-columns: repeat(<?= $size ?>, 1fr); max-width: <?= $size * 110 ?>px;">
    <?php foreach ($cards as $card): ?>
      <?php if ($card === 'X'): ?>
       <div class="card fake" data-fake="1" onclick="flipCard(this)">
  <div class="card-inner">
    <div class="card-front">?</div>
    <div class="card-back">❌</div>
  </div>
</div>

      <?php else: ?>
        <div class="card" data-card="<?= $card ?>" onclick="flipCard(this)">
          <div class="card-inner">
            <div class="card-front">?</div>
            <div class="card-back"><?= $card ?></div>
          </div>
        </div>
      <?php endif; ?>
    <?php endforeach; ?>
  </div>

  <script>
    let firstCard = null;
    let secondCard = null;
    let lockBoard = false;
    let matchedPairs = 0;
    const totalPairs = <?= $pairs ?>;
    let startTime = Date.now();
    const playerName = <?= json_encode($name) ?>;
    const level = <?= json_encode($level) ?>;

    function flipCard(card) {
      if (lockBoard || card.classList.contains('flipped')) return;

      // Fake karte
      if (card.classList.contains('fake')) {
        card.classList.add('flipped');
        lockBoard = true;
        setTimeout(() => {
          card.classList.remove('flipped');
          lockBoard = false;
        }, 800);
        return;
      }

      card.classList.add('flipped');

      if (!firstCard) {
        firstCard = card;
        return;
      }

      secondCard = card;
      checkForMatch();
    }

    function checkForMatch() {
      const isMatch = firstCard.dataset.card === secondCard.dataset.card;
      isMatch ? disableCards() : unflipCards();
    }

    function disableCards() {
      matchedPairs++;
      firstCard.onclick = null;
      secondCard.onclick = null;
      resetBoard();
      if (matchedPairs === totalPairs) endGame();
    }

    function unflipCards() {
      lockBoard = true;
      setTimeout(() => {
        firstCard.classList.remove('flipped');
        secondCard.classList.remove('flipped');
        resetBoard();
      }, 1000);
    }

    function resetBoard() {
      [firstCard, secondCard, lockBoard] = [null, null, false];
    }

    function endGame() {
      let timeTaken = Math.floor((Date.now() - startTime) / 1000);

      fetch('save_score.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `time=${timeTaken}&level=${level}&name=${encodeURIComponent(playerName)}`
      })
      .then(response => response.json())
      .then(data => {
        window.location.href = `scores.php?level=${level}&time=${data.time}&name=${encodeURIComponent(playerName)}`;
      });
    }
  </script>
</body>
</html>
