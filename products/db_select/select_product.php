<?php
require __DIR__ . '/../../config/pdo-connect.php';  // 引入資料庫設定


// 取得所有商品數量sql語法
$t_sql = "SELECT COUNT(1) FROM product_management";

// 總筆數 rows
$totalRows = $pdo->query($t_sql)->fetch(pdo::FETCH_NUM)[0];


$allRows = [];

$sql = sprintf(
    "SELECT * FROM `product_management`"
);

if ($totalRows) {
    if(isset($_GET['my_search'])){
        $search_sql = "WHERE `product_name` LIKE ";
        
        $sql .= $search_sql;
        $stmt = $pdo->prepare($sql);
    
        $my_search ="%".$_GET['my_search']."%";
        $stmt->execute([$my_search     
        ]);
        // 取得搜尋資料
        $allRows = $stmt->fetchAll();
    exit;
    };
    // 取得全部資料
    $allRows = $pdo->query($sql)->fetchAll();



};



