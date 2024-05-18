<?php
require __DIR__ . '/../config/pdo-connect.php';

require __DIR__ . '/../config/pdo-connect.php';

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assume all fields are required, you may need to adjust this according to your requirements
    $id = isset($_POST['id']) ? intval($_POST['id']) : null;
    $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : null; // 获取用户ID
    $mobile_phone = isset($_POST['mobile_phone']) ? $_POST['mobile_phone'] : null;
    $account = isset($_POST['account']) ? $_POST['account'] : null;
    $theme_name = isset($_POST['theme_name']) ? $_POST['theme_name'] : null;
    $participants = isset($_POST['participants']) ? intval($_POST['participants']) : null;
    $re_datetime = isset($_POST['re_datetime']) ? $_POST['re_datetime'] : null;

    // Update reservation data in the database
    $sql = "UPDATE `reservations` AS r
            LEFT JOIN `users` AS u ON r.user_id = u.user_id 
            LEFT JOIN `themes` AS t ON r.theme_id = t.theme_id
            LEFT JOIN `branches` AS b ON r.branch_id = b.id
            SET r.user_id = ?, 
                r.account = ?, 
                t.theme_name = ?, 
                r.participants = ?, 
                r.re_datetime = ?
            WHERE r.id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id, $account, $theme_name, $participants, $re_datetime, $id]);

    // Check if the update was successful
    if ($stmt->rowCount() > 0) {
        // If successful, return success message
        echo json_encode(['success' => true]);
        exit;
    } else {
        // If unsuccessful, return error message
        echo json_encode(['success' => false, 'message' => 'Failed to update reservation.']);
        exit;
    }
} else {
    // If not a POST request, return error message
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}
