<?php
$time = isset($_POST['time']) ? (int)$_POST['time'] : 0;
$level = $_POST['level'] ?? 'easy';
$name = trim($_POST['name'] ?? 'Anonīms');

if ($time > 0 && preg_match('/^[a-zA-Z0-9āčēģīķļņōŗšūžĀČĒĢĪĶĻŅŌŖŠŪŽ\s\-]+$/u', $name)) {
  $file = "scores_{$level}.txt";
  $entry = $name . " - {$time}s\n";
  file_put_contents($file, $entry, FILE_APPEND | LOCK_EX);
  echo json_encode(['time' => $time]);
} else {
  http_response_code(400);
  echo json_encode(['error' => 'Invalid input']);
}
?>
