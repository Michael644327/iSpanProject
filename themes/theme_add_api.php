<?php

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

require __DIR__ . '/../config/pdo-connect.php';
header('Content-Type: application/json');

$output = [
  'success' => false, # 有沒有新增成功
  'bodyData' => $_POST,
  'newId' => 0,
];

//TODO: 欄位資料檢查
if (!isset($_POST['theme_name'])) {
  echo json_encode($output);
  exit; # 結束 php 程式
}

$startDate = strtotime($_POST['start_date']);
if ($startDate === false) {
  $startDate = null;
} else {
  $startDate = date('Y-m-d', $startDate);
}

$endDate = strtotime($_POST['end_date']);
if ($endDate === false) {
  $endDate = null;
} else {
  $endDate = date('Y-m-d', $endDate);
}
$themeTime = intval($_POST['theme_time']);
$intervals = intval($_POST['intervals']);
$difficulty = intval($_POST['difficulty']);

$i = count($_FILES["uploadFile"]["name"]); // 计算上传文件数量
$uploadResults = [];
for ($j = 0; $j < $i; $j++) {
  if ($_FILES["uploadFile"]["error"][$j] == 0) {
    $fileName = $_FILES["uploadFile"]["name"][$j];
    if (move_uploaded_file($_FILES["uploadFile"]["tmp_name"][$j], "imgs/" . $fileName)) {
      $uploadResults[] = basename($fileName); // 将成功上传的文件名添加到数组中，不包含路径
    } else {
      $uploadResults[] = "上傳失敗";
    }
  }
}


$output['uploadResults'] = $uploadResults;

$sql = "INSERT INTO `themes`(
 `theme_name`, `theme_desc`, `theme_img`, `price`, `difficulty`, `suitable_players`, `start_time`, `end_time`, `theme_time`, `intervals`, `start_date`, `end_date`, `created_at`, `last_modified_by`) VALUES (
    ?,
    ?,
    ?,
    ?,
    ?,
    ?,
    ?,
    ?,
    ?,
    ?,
    ?,
    ?,
    NOW(),
    ? )";

// 检查是否至少有一个文件成功上传
if (!empty($uploadResults)) {
  $imagePath = $uploadResults[0]; // 使用第一个上传的图片路径
} else {
  $imagePath = ""; // 如果没有成功上传文件，设置图片路径为空字符串
}

// 执行数据库插入操作
$stmt = $pdo->prepare($sql);
$stmt->execute([
  $_POST['theme_name'],
  $_POST['theme_desc'],
  $imagePath, // 使用第一个上传的图片路径或者为空字符串
  $_POST['price'],
  $difficulty,
  $_POST['suitable_players'],
  $_POST['start_time'],
  $_POST['end_time'],
  $themeTime,
  $intervals,
  $startDate,
  $endDate,
  "11111",
]);

$output['success'] = !!$stmt->rowCount(); # 新增了几笔
$output['newId'] = $pdo->lastInsertId(); # 取得最近新增数据的主键


echo json_encode($output);
