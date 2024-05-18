<?php
// exit; #新增完後面資料後，因為怕以後不小心點到，加上這行點到會直接離開程式
try {

    require __DIR__ . '/../config/pdo-connect.php';

    $pdo->beginTransaction();

    $lasts = ["何", "傅", "劉", "吳", "呂", "周", "唐", "孫", "宋", "張", "彭", "徐", "於", "曹", "曾", "朱", "李", "林", "梁", "楊", "沈", "王", "程", "羅", "胡", "董", "蕭", "袁", "許", "謝", "趙", "郭", "鄧", "鄭", "陳", "韓", "馬", "馮", "高", "黃"];

    $firsts = ["冠廷", "冠宇", "宗翰", "家豪", "彥廷", "承翰", "柏翰", "宇軒", "家瑋", "冠霖", "雅婷", "雅筑", "怡君", "佳穎", "怡萱", "宜庭", "郁婷", "怡婷", "詩涵", "鈺婷"];

    $areas = ["臺北市", "新北市", "桃園市", "臺中市", "臺南市", "高雄市", "新竹縣", "苗栗縣", "彰化縣", "南投縣", "雲林縣", "嘉義縣", "屏東縣", "宜蘭縣", "花蓮縣", "臺東縣", "澎湖縣", "金門縣", "連江縣", "基隆市", "新竹市", "嘉義市"];

    $number = '0123456789';

    $alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    $all = $number . $alphabet;

    function getRandomText($counts, $source)
    {
        $result1 = '';
        $sourceLength = strlen($source);
        for ($i = 1; $i <= $counts; $i++) {
            $idx1 = rand(0, $sourceLength - 1);
            $result1 .= $source[$idx1];
        }
        return $result1;
    }


    function getRandomArray($source)
    {
        $qwe = count($source);
        $result2 = $source[rand(0, $qwe - 1)];
        return $result2;
    }


    ///////////////////////////////////////////////////////////////////////


    $sql1 = "INSERT INTO `users`(
        `account`,
        `password`,
        `name`,
        `nick_name`,
        `gender`,
        `birthday`, 
        `mobile_phone`, 
        `invoice_carrier_id`,
        `tax_id`,
        `avatar`,
        `note`,
        `user_status`,
        `blacklist`,
        `created_at`,
        `last_modified_at`
    ) VALUES (
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
        ?,
        NOW(),
        NOW()
    )";
    $stmt1 = $pdo->prepare($sql1);

    for ($i = 0; $i < 1; $i++) {
        $account = 'email' . getRandomText(8, $number) . '@test.com';
        $password = password_hash($account, PASSWORD_DEFAULT);
        $name = getRandomArray($lasts) . getRandomArray($firsts);
        $nick_name = $name;
        $gender = rand(0, 1);
        $t = rand(strtotime('1985-01-01'), strtotime('2000-01-01'));
        $birthday = date('Y-m-d', $t);
        $mobile_phone = '09' . getRandomText(8, $number);
        $mobile_barcode = '/' . getRandomText(7, $all);
        $gui_number = getRandomText(8, $number);
        $avatar = 'default.gif';
        $note = getRandomText(rand(0, 100), $all);
        $status = rand(0, 1);
        $blacklist = rand(0, 1);



        $stmt1->execute([
            $account,
            $password,
            $name,
            $nick_name,
            $gender,
            $birthday,
            $mobile_phone,
            $mobile_barcode,
            $gui_number,
            $avatar,
            $note,
            $status,
            $blacklist
        ]);
    }


    $output = [];

    $output['success'] = boolval($stmt1->rowCount());
    $output['user_id'] = $pdo->lastInsertId();

    echo json_encode($output, JSON_UNESCAPED_UNICODE);

    /////////////////////////////////////////////////////////////////////////

    // 提交事務
    $pdo->commit();

} catch (PDOException $e) {
    // 如果出現錯誤，回滾事務
    $pdo->rollBack();


    $output = [

    ];

    $output['success'] = '新增記錄失敗';
    $output['error_message'] = $e->getMessage();

    echo json_encode($output, JSON_UNESCAPED_UNICODE);
}



