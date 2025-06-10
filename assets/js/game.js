let firstCard = null;
let secondCard = null;
let lockBoard = false;
let matchedPairs = 0;
const totalCards = document.querySelectorAll('.card:not(.fake)').length;
const totalPairs = totalCards / 2;
let startTime = Date.now();

function flipCard(card) {
  if (lockBoard || card.classList.contains('flipped')) return;

  // Ja karte ir fake – parādi uz brīdi un paslēp atkal
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
  let level = new URLSearchParams(window.location.search).get('level');
  let username = sessionStorage.getItem('username') || 'Spēlētājs';

  fetch('save_score.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `time=${timeTaken}&level=${level}&name=${username}`
  })
    .then(response => response.json())
    .then(data => {
      window.location.href = `scores.php?time=${data.time}`;
    });
}
