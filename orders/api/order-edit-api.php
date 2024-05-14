<?php
require __DIR__ . '/../../config/pdo-connect.php';
header('Content-Type: application/json');

ini_set('display_errors', 1);
error_reporting(E_ALL);

$response = [
    'success' => false,
    'bodyData' => $_POST,
    'message' => ''
];


try {
    $pdo->beginTransaction();

    $orderId = $_POST['orderId'];

    $orderUpdateSql = "UPDATE orders SET  
    district_id = ?, 
    address = ?, 
    recipient_name = ?, 
    mobile_phone = ?, 
    invoice_carrier = ?, 
    tax_id = ?, 
    member_carrier = ?,
    order_status = ?,
    last_modified_at = now() 
    WHERE id = ?";

    $orderUpdateStmt = $pdo->prepare($orderUpdateSql);

    $mobileInvoice = !empty($_POST['mobileInvoice']) ? $_POST['mobileInvoice'] : null;
    $taxId = !empty($_POST['taxId']) ? $_POST['taxId'] : null;
    $memberCarrier = !empty($_POST['memberInvoice']) ? $_POST['memberInvoice'] : null;
    
    $orderUpdateStmt->execute([
        $_POST['district'],
        $_POST['address'],
        $_POST['recipientName'],
        $_POST['recipientMobile'],
        $mobileInvoice,
        $taxId,
        $memberCarrier,
        'unpaid',
        $orderId
    ]);

    // 本次提交的商品
    $submittedProductIds = $_POST['productIds'];
    // 編輯前的商品（字串）
    $originalProductIdsString = isset($_POST['originalProductIds']) ? $_POST['originalProductIds'] : '';
    // 編輯前的商品轉成陣列
    $originalProductIdsArray = explode(',', $originalProductIdsString);
    // 比對本次提交的商品與編輯前的商品差異
    $idsToDelete = array_diff($originalProductIdsArray, $submittedProductIds);

    foreach ($idsToDelete as $productIdToDelete) {
        $orderDeleteStmt = $pdo->prepare("DELETE FROM order_details WHERE order_id = ? AND product_id = ?");
        $orderDeleteStmt->execute([$orderId, $productIdToDelete]);
    }

    foreach ($_POST['productIds'] as $index => $productId) {
        $quantity = $_POST['productQuantities'][$index];
        $unitPrice = $_POST['productUnitPrices'][$index];
        $orderDetailsUpdateSql = "INSERT INTO order_details
            (order_id, product_id, quantity, order_unit_price, created_at, last_modified_at)
            VALUES (?, ?, ?, ?, NOW(), NOW())
            ON DUPLICATE KEY UPDATE
            quantity = VALUES(quantity),
            last_modified_at = NOW()";

        $orderDetailsUpdateStmt = $pdo->prepare($orderDetailsUpdateSql);
        $orderDetailsUpdateStmt->execute([
            $orderId,
            $productId,
            $quantity,
            $unitPrice,
        ]);
    }


    foreach ($idsToDelete as $productIdToDelete) {
        $orderDeleteStmt = $pdo->prepare("DELETE FROM order_details WHERE order_id = ? AND product_id = ?");
        $orderDeleteStmt->execute([$orderId, $productIdToDelete]);
    }

    $pdo->commit();
    $response['success'] = true;
    $response['message'] = 'Order successfully updated.';
} catch (Exception $e) {
    $pdo->rollback();
    $response['message'] = "Error updating order: " . $e->getMessage();
    http_response_code(500);
}

echo json_encode($response);
