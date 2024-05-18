<?php

// -----頁面切換

// 取得所有商品數量sql語法
$t_sql = "SELECT COUNT(1) FROM product_management";

// 總筆數 rows
$totalRows = $pdo->query($t_sql)->fetch(pdo::FETCH_NUM)[0];


$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

if ($page < 1) {
    header('Location: ?page=1');
    exit;
}

$t_sql = "SELECT COUNT(1) FROM product_management";


$perPage = 10;
$totalPages = 0;
$all_page = [];

// ----- 

if ($totalRows) {

    if (isset($_GET['my_search'])) {

        $search_sql = "SELECT * FROM `product_management` WHERE `product_id` LIKE :userSearch OR
        `product_name` LIKE :userSearch";

        $stmt = $pdo->prepare($search_sql);
        $my_search = "%" . $_GET['my_search'] . "%";

        $stmt->execute(
            ['userSearch' =>$my_search]
        );
        // 取得搜尋資料
        $all_page = $stmt->fetchAll();
        
    } else {
        # 總頁數
        $totalPages = ceil($totalRows / $perPage);
        if ($page > $totalPages) {
            header("Location: ?page={$totalPages}");
            exit; # 結束這支程式
        }

        # 取得分頁資料
        $sql = sprintf(
            "SELECT * FROM `product_management` ORDER BY product_id DESC LIMIT %s, %s",
            ($page - 1) * $perPage,
            $perPage
        );
        // 取得全部資料
        $all_page = $pdo->query($sql)->fetchAll();
    }
}
// -----頁面切換