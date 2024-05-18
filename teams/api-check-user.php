<?php
/* 抓可用user */
$sql = "SELECT user_id
        FROM users";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$user_c = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>