<?php
require __DIR__ . '/../config/pdo-connect.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id < 1) {
  header('Location: reservation_list.php');
  exit;
}
$sql = "DELETE FROM `reservations` WHERE `id`=$id";
$pdo->query($sql);

# $_SERVER['HTTP_REFERER']: 從哪個頁面連過來的
$comeFrom = 'reservation_list.php';
if (isset($_SERVER['HTTP_REFERER'])) {
  $comeFrom = $_SERVER['HTTP_REFERER'];
}
header("Location: $comeFrom");
