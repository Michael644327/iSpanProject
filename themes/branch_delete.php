<?php
require __DIR__ . '/../config/pdo-connect.php';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id < 1) {
    header('Location: branch_list.php');
    exit;
}

try {
    // 在刪除分店之前，先從 branch_themes 表中刪除與該分店相關聯的所有記錄
    $sql = "DELETE FROM `branch_themes` WHERE `branch_id` = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    // 然後刪除分店
    $sql = "DELETE FROM `branches` WHERE `id` = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    // 返回來源頁面
    $comeFrom = 'branch_list.php';
    if (isset($_SERVER['HTTP_REFERER'])) {
        $comeFrom = $_SERVER['HTTP_REFERER'];
    }
    header("Location: $comeFrom");
} catch (PDOException $e) {
    // 處理例外
    echo "刪除分店時出錯：" . $e->getMessage();
}

