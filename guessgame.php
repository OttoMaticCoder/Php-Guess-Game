<?php
session_start();

if (!isset($_SESSION['randomNumber'])) {
 $_SESSION['randomNumber'] = rand(1, 100);
 $_SESSION['attempts'] = 0;
}

$randomNumber = $_SESSION['randomNumber'];
$maxAttempts = 5;
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 $guess = (int)$_POST['guess'];
 $_SESSION['attempts']++;

 if ($guess < 1 || $guess > 100) {
  $message = "Your guess is not between 1 and 100, please try again.";
 } elseif ($guess < $randomNumber) {
  $message = "Please pick a higher number.";
 } elseif ($guess > $randomNumber) {
  $message = "Please pick a lower number.";
 } else {
  $message = "You win!";
  session_destroy();
 }

 if ($_SESSION['attempts'] >= $maxAttempts && $message !== "You win!") {
  $message = "You lose, the number to guess was $randomNumber.";
  session_destroy();
 }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
 <meta charset="UTF-8">
 <title>Guessing Game</title>
</head>

<body>
 <h1>Guess the Number!</h1>
 <p>Pick a number between 1 and 100. You have <?= $maxAttempts - ($_SESSION['attempts'] ?? 0) ?> attempts left.</p>

 <?php if (!empty($message)) : ?>
  <p><strong><?= $message ?></strong></p>
 <?php endif; ?>

 <?php if (empty($message) || strpos($message, 'win') === false && strpos($message, 'lose') === false): ?>
  <form method="post">
   <label for="guess">Your Guess:</label>
   <input type="number" name="guess" id="guess" required>
   <button type="submit">Submit</button>
  </form>
 <?php else: ?>
  <a href="<?= $_SERVER['PHP_SELF'] ?>">Play Again</a>
 <?php endif; ?>
</body>

</html>