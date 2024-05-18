<?php
require __DIR__ . '/../config/pdo-connect.php';

header('Content-Type: application/json');

$output = [
  'success' => false, # 有沒有新增成功
  'postData' => $_POST,
  'error' => '',
  'code' => 0, # 追踨程式執行的編號
];

// TODO: 欄位資料檢查
if (!isset($_POST['join_user_id'])) {
  echo json_encode($output);
  exit; # 結束 php 程式
}

$sql = "INSERT INTO teams_members(
  `join_team_id`, `join_user_id`, `create_at`)
  VALUES
  (?, ?,NOW())";

$stmt = $pdo->prepare($sql);
$stmt->execute([
  $_POST['join_team_id'],
  $_POST['join_user_id']
]);

$output['success'] = !!$stmt->rowCount();
echo json_encode($output);
