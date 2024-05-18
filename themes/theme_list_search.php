<?php
// 包含 PDO 连接文件
require __DIR__ . '/../config/pdo-connect.php';

// 处理查询条件
$where = '';
$placeholders = [];
if (!empty($_GET['theme_name'])) {
  $where .= "theme_name LIKE ?";
  $placeholders[] = '%' . $_GET['theme_name'] . '%';
}

$sql = "SELECT `theme_id`,`theme_name`,`difficulty`,`suitable_players`,`theme_time`,`start_date`,`end_date` FROM `themes`"; // 选择要查询的字段
if (!empty($where)) {
  $sql .= " WHERE " . $where;
}

// 准备并执行查询
$stmt = $pdo->prepare($sql);
$stmt->execute($placeholders);
$rows = $stmt->fetchAll();

// 返回查询结果
header('Content-Type: application/json');
echo json_encode($rows);

// 结束脚本
exit;
