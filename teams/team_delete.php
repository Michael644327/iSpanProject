<?php

require __DIR__ . '/../config/pdo-connect.php';

$team_id = isset($_GET['team_id']) ? intval($_GET['team_id']) : 0;
if ($team_id < 1) {
  header('Location: teams.php');
  exit;
}

$sql = "UPDATE `teams` SET 
    `team_display` = 0,
    `last_modified_at` = NOW()
WHERE team_id=$team_id";
$pdo->query($sql);

# $_SERVER['HTTP_REFERER']: 從哪個頁面連過來的
$comeFrom = 'teams.php';
if (isset($_SERVER['HTTP_REFERER'])) {
  $comeFrom = $_SERVER['HTTP_REFERER'];
}

header("Location: $comeFrom");