<?php
// exit;
// 庫存列表的假資料

require __DIR__ . '/../../config/pdo-connect.php';  // 引入資料庫設定



$lasts = ["何", "傅", "劉", "吳", "呂", "周", "唐", "孫", "宋", "張", "彭", "徐", "於", "曹", "曾", "朱", "李", "林", "梁", "楊", "沈", "王", "程", "羅", "胡", "董", "蕭", "袁", "許", "謝", "趙", "郭", "鄧", "鄭", "陳", "韓", "馬", "馮", "高", "黃"];

$firsts = ["冠廷", "冠宇", "宗翰", "家豪", "彥廷", "承翰", "柏翰", "宇軒", "家瑋", "冠霖", "雅婷", "雅筑", "怡君", "佳穎", "怡萱", "宜庭", "郁婷", "怡婷", "詩涵", "鈺婷"];



$sql = "INSERT INTO `product_warehousing`(
`product_id`, 
`quantity`, 
`warehousing_person`, 
`warehousing_date`, 
`created_at`, 
`last_modified_by`, 
`last_modified_at`) VALUES (
        ?,
        ?,
        ?,
        ?,
        ?,
        ?,
        ?
    )";

$stmt = $pdo->prepare($sql);

for ($i = 0; $i < 15; $i++) {

    $product_id =  rand(1, 20);
    $quantity =  rand(1, 100);

    shuffle($lasts);
    shuffle($firsts);
    $warehousing_person = $lasts[1] . $firsts[1];

    $t = rand(strtotime('2022-01-01'), strtotime('2024-01-01'));
    $warehousing_date =  date('Y-m-d', $t);

    $created_at =  date('Y-m-d', $t);

    $last_modified_by = $lasts[0] . $firsts[0];

    $last_modified_at =  date('Y-m-d', $t);




    $stmt->execute([
        $product_id,
        $quantity,
        $warehousing_person,
        $warehousing_date,
        $created_at,
        $last_modified_by,
        $last_modified_at
    ]);
}

echo json_encode([
    $stmt->rowCount(), // 影響的資料筆數
    $pdo->lastInsertId(), // 最新的新增資料的主鍵
]);


/*
https://www.ntdtv.com/b5/2017/05/14/a1324156.html


let d = `01李 02王 03張 04劉 05陳 06楊 07趙 08黃 09周 10吳
11徐 12孫 13胡 14朱 15高 16林 17何 18郭 19馬 20羅
21梁 22宋 23鄭 24謝 25韓 26唐 27馮 28於 29董 30蕭
31程 32曹 33袁 34鄧 35許 36傅 37沈 38曾 39彭 40呂`.split('').sort().slice(119);
JSON.stringify(d);

// ---------------------
https://freshman.tw/namerank

let ar = [];
$('table').eq(0).find('tr>td:nth-of-type(2)').each(function(i, el){
    ar.push(el.innerText);
});
$('table').eq(1).find('tr>td:nth-of-type(2)').each(function(i, el){
    ar.push(el.innerText);
});
JSON.stringify(ar);

// -------------------
https://www.president.gov.tw/Page/106
let ar = [];
$('.btn.btn-default.alluser').each(function(i, el){
    ar.push(el.innerText);
});
JSON.stringify(ar);

*/