<?php
// exit; #新增完後面資料後，因為怕以後不小心點到，加上這行點到會直接離開程式
try {

  require __DIR__ . '/../../config/pdo-connect.php';

  $pdo->beginTransaction();

  $lasts = ["何", "傅", "劉", "吳", "呂", "周", "唐", "孫", "宋", "張", "彭", "徐", "於", "曹", "曾", "朱", "李", "林", "梁", "楊", "沈", "王", "程", "羅", "胡", "董", "蕭", "袁", "許", "謝", "趙", "郭", "鄧", "鄭", "陳", "韓", "馬", "馮", "高", "黃"];

  $firsts = ["冠廷", "冠宇", "宗翰", "家豪", "彥廷", "承翰", "柏翰", "宇軒", "家瑋", "冠霖", "雅婷", "雅筑", "怡君", "佳穎", "怡萱", "宜庭", "郁婷", "怡婷", "詩涵", "鈺婷"];

  $road = ["民族路", "民權路", "中正路", "中山路", "仁愛路", "忠孝路", "三民路", "松江路", "合江街", "青海路", "華西街", "北平路", "重慶南路", "永康街", "長沙街", "承德路", "南京路", "西安路", "長春路", "長安路", "鳳林路", "漢口街", "迪化街", "更新街", "信義路"];

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

  $sql_district = "SELECT * FROM district";
  $district_data = $pdo->query($sql_district)->fetchAll();

  $sql_users = "SELECT count(user_id) count FROM users";
  $users_rows = $pdo->query($sql_users)->fetch();



  $sql2 = "INSERT INTO `address`(
`user_id`,
`district_id`,
`address`,
`recipient_name`,
`mobile_phone`,
`type`
) VALUES (
?,
?,
?,
?,
?,
?)";

  $stmt2 = $pdo->prepare($sql2);

  for ($i = 0; $i < 1; $i++) {
    $FK_user_id = rand(1, $users_rows['count']);

    $result = $district_data[rand(0, count($district_data) - 1)];

    $district_id = $result['id'];
    $address_line = $road[rand(0, 24)] . rand(1, 150) . '巷' . rand(1, 150) . '號';
    $receiver = getRandomArray($lasts) . getRandomArray($firsts);
    $mobile_phone = '09' . getRandomText(8, $number);
    $type = '0';



    $stmt2->execute([
      $FK_user_id,
      $district_id,
      $address_line,
      $receiver,
      $mobile_phone,
      $type,
    ]);
  }


  echo json_encode([
    $stmt2->rowCount(), // 影響的資料筆數
    $pdo->lastInsertId(), // 最新的新增資料的主鍵
  ]);






  // 提交事務
  $pdo->commit();

  echo "新增記錄成功";
} catch (PDOException $e) {
  // 如果出現錯誤，回滾事務
  $pdo->rollBack();

  echo "新增記錄失敗：" . $e->getMessage();
}
