<?php
require __DIR__ . '/../config/pdo-connect.php';

header('Content-Type: application/json');

$output = [
  'success' => false,
  'postData' => $_POST,
  'error' => '',
  'code' => 0,
];

if (!isset($_POST['chat_id'])) {
  echo json_encode($output);
  exit;
}

$sql = "UPDATE `teams_chats`
        SET `chat_display` = 0
        WHERE chat_id=?";
$stmt = $pdo->prepare($sql);
$stmt->execute([
  $_POST['chat_id']
]);

$output['success'] = !!$stmt->rowCount();
echo json_encode($output);
