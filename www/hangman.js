// Define the game data
var words = ['for', 'space', 'two', 'funny', 'friend'];
var word = '';
var guesses = [];
var maxGuesses = 6;

// Get the DOM elements
var wordEl = document.getElementById('word');
var guessesEl = document.getElementById('guesses');
var statusEl = document.getElementById('status');
var keyboardEl = document.getElementById('keyboard');
var newGameBtn = document.getElementById('new-game');

// Define the new game function
function newGame() {
  // Reset the game data
  word = words[Math.floor(Math.random() * words.length)];
  guesses = [];
  maxGuesses = 6;

  // Update the DOM elements
  updateWord();
  updateGuesses();
  updateStatus();
  updateKeyboard();
}

// Define the update word function
function updateWord() {
  var wordDisplay = '';
  for (var i = 0; i < word.length; i++) {
    if (guesses.includes(word[i])) {
      wordDisplay += word[i];
    } else {
      wordDisplay += '_';
    }
    wordDisplay += ' ';
  }
  wordEl.textContent = wordDisplay;
}

// Define the update guesses function
function updateGuesses() {
  guessesEl.textContent = 'Guesses: ' + guesses.join(', ');
}

// Define the update status function
function updateStatus() {
  if (maxGuesses === 0) {
    statusEl.textContent = 'You lose! The word was ' + word + '.';
    keyboardEl.querySelectorAll('button').forEach(function(btn) {
      btn.classList.add('disabled');
    });
  } else if (!word.split('').every(function(char) {
    return guesses.includes(char);
  })) {
    statusEl.textContent = '';
  } else {
    statusEl.textContent = 'You win!';
    keyboardEl.querySelectorAll('button').forEach(function(btn) {
      btn.classList.add('disabled');
    });
  }
}

// Define the update keyboard function
function updateKeyboard() {
  keyboardEl.querySelectorAll('button').forEach(function(btn) {
    btn.classList.remove('disabled');
    if (guesses.includes(btn.textContent)) {
      btn.classList.add('disabled');
    }
  });
}

// Define the handle guess function
function handleGuess(event) {
  var guess = event.target.textContent.toLowerCase();
  if (guesses.includes(guess)) {
    return;
  }
  guesses.push(guess);
  if (!word.includes(guess)) {
    maxGuesses--;
  }
  updateWord();
  updateGuesses();
  updateStatus();
  updateKeyboard();
}

// Add event listeners
newGameBtn.addEventListener('click', newGame);
keyboardEl.addEventListener('click', function(event) {
  if (event.target.tagName === 'BUTTON') {
    handleGuess(event);
  }
});

// Start a new game
newGame();

