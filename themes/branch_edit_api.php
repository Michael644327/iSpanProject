<?php
require __DIR__ . '/../config/pdo-connect.php';
header('Content-Type: application/json');

$output = [
    'success' => false,
];

if (!isset($_POST['id'])) {
    $output['error'] = '缺少分店 ID';
    echo json_encode($output);
    exit;
}

// 更新操作的 SQL 查詢
$sql = "UPDATE `branches` SET
        `branch_name` = ?,
        `branch_address` = ?,
        `branch_phone` = ?,
        `open_time` = ?,
        `close_time` = ?,
        `branch_status` = ?
        WHERE `id` = ?";

$stmt = $pdo->prepare($sql);

// 執行更新操作
$result = $stmt->execute([
    $_POST['branch_name'],
    $_POST['branch_address'],
    $_POST['branch_phone'],
    $_POST['open_time'],
    $_POST['close_time'],
    $_POST['branch_status'],
    $_POST['id']
]);

// 更新分店與主題關聯的資料
$themeIds = isset($_POST['theme_id']) ? $_POST['theme_id'] : [];

// 刪除分店與主題關聯的舊資料
$deleteSql = "DELETE FROM `branch_themes` WHERE `branch_id` = ?";
$deleteStmt = $pdo->prepare($deleteSql);
$deleteStmt->execute([$_POST['id']]);

// 插入新的分店與主題關聯資料
$insertSql = "INSERT INTO `branch_themes` (`branch_id`, `theme_id`) VALUES (?, ?)";
$insertStmt = $pdo->prepare($insertSql);
foreach ($themeIds as $themeId) {
    $insertStmt->execute([$_POST['id'], $themeId]);
}




if ($result) {
    $output['success'] = true;
} else {
    $output['error'] = '更新失敗';
}

echo json_encode($output);
