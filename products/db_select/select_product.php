<?php
require __DIR__ . '/../../config/pdo-connect.php';  // 引入資料庫設定


// 未使用此檔案

// 取得所有商品數量sql語法
$t_sql = "SELECT COUNT(1) FROM product_management";

// 總筆數 rows
$totalRows = $pdo->query($t_sql)->fetch(pdo::FETCH_NUM)[0];


$allRows = [];

$sql = sprintf(
    "SELECT * FROM `product_management`"
);

// if ($totalRows) {

//     if(isset($_GET['my_search'])){
//         echo "進來ㄌ"."</br>";
//         $search_sql = " WHERE `product_name` LIKE ?";
        
//         $sql .= $search_sql;
//         $stmt = $pdo->prepare($sql);

//         echo $sql."</br>";


//         $my_search[] =" %".$_GET['my_search']."% ";

//         var_dump($my_search) ;
//         $stmt->execute(
//             $my_search  
//         );
//         // 取得搜尋資料
//         $allRows = $stmt->fetchAll();
//         var_dump($allRows);

//     };
//     // 取得全部資料
//     // $allRows = $pdo->query($sql)->fetchAll();


// };



// if(isset($_GET['my_search'])){

//         echo "進來ㄌ"."</br>";

//         $search_sql = "SELECT * FROM `product_management` WHERE `product_name` LIKE ?";
        
       
//         $stmt = $pdo->prepare($search_sql);

//         echo "</br>";

        
//         $my_search[] ="%".$_GET['my_search']."%";
//         var_dump($my_search) ;

//         $stmt->execute(
//             $my_search  
//         );
//         // 取得搜尋資料
//         $all_page = $stmt->fetchAll();
//         var_dump($all_page);
       

//     };