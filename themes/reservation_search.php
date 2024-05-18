<?php
// 包含 PDO 连接文件
require __DIR__ . '/../config/pdo-connect.php';

// 处理查询条件
$where = '';
$placeholders = [];
if (!empty($_GET['user_name'])) {
    $where .= "u.name LIKE ?";
    $placeholders[] = '%' . $_GET['user_name'] . '%';
}

$sql = "SELECT r.*, u.name AS user_name, u.mobile_phone, u.account, t.theme_name, b.branch_name
     FROM `reservations` AS r 
     LEFT JOIN `users` AS u ON r.user_id = u.user_id 
     LEFT JOIN `themes` AS t ON r.theme_id = t.theme_id
     LEFT JOIN `branches` AS b ON r.branch_id = b.id";

if (!empty($where)) {
    $sql .= " WHERE " . $where;
}

$sql .= " ORDER BY r.id";

// 准备并执行查询
$stmt = $pdo->prepare($sql);
$stmt->execute($placeholders);
$rows = $stmt->fetchAll();

// 返回查询结果
header('Content-Type: application/json');
echo json_encode($rows);

// 结束脚本
exit;
