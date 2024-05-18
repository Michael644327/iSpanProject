<?php

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

require __DIR__ . '/../config/pdo-connect.php';
header('Content-Type: application/json');

$output = [
  'success' => false, // 有沒有新增成功
  'bodyData' => $_POST,
  'uploadResults' => [],
];

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

// TODO: 欄位資料檢查
if (!isset($_POST['theme_id'])) {
  echo json_encode($output);
  exit; // 結束 PHP 程式
}

// 處理文件上傳
$i = count($_FILES["uploadFile"]["name"]); // 計算上傳文件數量
$uploadedImagePath = null;
for ($j = 0; $j < $i; $j++) {
  if ($_FILES["uploadFile"]["error"][$j] == 0) {
    $fileName = $_FILES["uploadFile"]["name"][$j];
    $targetPath = "imgs/" . $fileName;
    if (move_uploaded_file($_FILES["uploadFile"]["tmp_name"][$j], $targetPath)) {
      $output['uploadResults'][] = "上傳成功: " . $fileName;
      // 將成功上傳的圖片路徑存儲在變量中
      $uploadedImagePath = $fileName;
    } else {
      $output['uploadResults'][] = "上傳失敗: " . $fileName;
    }
  }
}


// 更新数据库
$sql = "UPDATE `themes` SET 
`theme_name`=?,
`theme_img`=?,
`theme_desc`=?,
`price`=?,
`difficulty`=?,
`suitable_players`=?,
`start_time`=?,
`end_time`=?,
`theme_time`=?,
`intervals`=?,
`start_date`=?,
`end_date`=? WHERE theme_id=?";

$stmt = $pdo->prepare($sql);
$stmt->execute([
  $_POST['theme_name'],
  $uploadedImagePath, // 使用編輯頁面提交的圖片路徑
  $_POST['theme_desc'],
  $_POST['price'],
  $_POST['difficulty'],
  $_POST['suitable_players'],
  $_POST['start_time'],
  $_POST['end_time'],
  $themeTime,
  $intervals,
  $startDate,
  $endDate,
  $_POST['theme_id'],
]);

$output['success'] = !!$stmt->rowCount(); // 更新了幾筆數據

if ($output['success']) {
  // 获取最新的数据
  $theme_id = $_POST['theme_id'];
  $sql = "SELECT * FROM `themes` WHERE theme_id=?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$theme_id]);
  $output['row'] = $stmt->fetch();
}

echo json_encode($output);
