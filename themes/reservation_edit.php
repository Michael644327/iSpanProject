<?php
require __DIR__ . '/../config/pdo-connect.php';
$title = '修改預約資料';

// 檢查 URL 中是否存在預約 ID，如果不存在，則導向到預約列表頁面
if (!isset($_GET['id'])) {
    header("Location: branch_reservation_details.php");
    exit;
}

$reservation_id = intval($_GET['id']);

// 從資料庫中獲取預約詳細信息
$sql = "SELECT r.*, u.name AS user_name, u.mobile_phone, u.account, t.theme_name, b.branch_name
        FROM `reservations` AS r 
        LEFT JOIN `users` AS u ON r.user_id = u.user_id 
        LEFT JOIN `themes` AS t ON r.theme_id = t.theme_id
        LEFT JOIN `branches` AS b ON r.branch_id = b.id
        WHERE r.id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$reservation_id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// 如果沒有找到對應的預約，則導向到預約列表頁面
if (!$row) {
    header("Location: branch_reservation_details.php");
    exit;
}
?>

<?php include __DIR__ . '/../parts/html-head.php' ?>
<?php include __DIR__ . '/../parts/bt-navbar.php' ?>

<style>
    form .mb-4 .form-text {
        color: tomato;
        font-weight: 800;
    }
</style>

<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-10">
            <!-- 表單開始 -->
            <form name="form1" onsubmit="sendData(event)" class="px-3 pt-1">
                <!-- 隱藏的預約 ID 欄位 -->
                <input type="hidden" name="reservation_id" value="<?= $row['id'] ?>">

                <!-- 其他表單輸入欄位 -->
                <div class="mb-4 col-2">
                    <label for="id" class="form-label fw-bold">編號</label>
                    <input type="text" class="form-control" disabled value="<?= $row['id'] ?>">
                </div>

                <div class="mb-4 col-2">
                    <label for="user_name" class="form-label fw-bold">姓名</label>
                    <input type="text" class="form-control" disabled value="<?= $row['user_name'] ?>">
                </div>

                <div class="mb-4 col-2">
                    <label for="mobile_phone" class="form-label fw-bold">電話</label>
                    <input type="text" class="form-control" value="<?= $row['mobile_phone'] ?>">
                </div>

                <div class="mb-4 col-2">
                    <label for="account" class="form-label fw-bold">信箱</label>
                    <input type="text" class="form-control" value="<?= $row['account'] ?>">
                </div>

                <div class="mb-4 col-2">
                    <label for="theme_name" class="form-label fw-bold">主題名稱</label>
                    <input type="text" class="form-control" value="<?= $row['theme_name'] ?>">
                </div>

                <div class="mb-4 col-2">
                    <label for="participants" class="form-label fw-bold">人數</label>
                    <input type="text" class="form-control" value="<?= $row['participants'] ?>">
                </div>

                <div class="mb-4 col-2">
                    <label for="re_datetime" class="form-label fw-bold">預約時間</label>
                    <input type="text" class="form-control" value="<?= $row['re_datetime'] ?>">
                </div>

                <!-- 其他輸入欄位 -->

                <!-- 提交按鈕 -->
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">修改</button>
                </div>
            </form>
            <!-- 表單結束 -->
        </div>
    </div>
</div>

<?php include __DIR__ . '/../parts/scripts.php' ?>

<!-- Modal 彈跳視窗 -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <!-- 略 -->
</div>

<div class="modal fade" id="staticBackdrop2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <!-- 略 -->
</div>

<script>
    const sendData = e => {
        e.preventDefault();

        const fd = new FormData(document.form1);

        // 將預約 ID 添加到 FormData 對象中
        fd.append('reservation_id', '<?= $reservation_id ?>');


        fetch('reservation_edit_api.php', {
                method: 'POST',
                body: fd // Content-Type: multipart/form-data
            })
            .then(r => r.json())
            .then(data => {
                console.log(data);
                if (data.success) {
                    myModal.show(); // 顯示成功的模態框
                } else {
                    myModal2.show(); // 顯示失敗的模態框
                }
            })
            .catch(ex => console.log(ex));
    };

    const myModal = new bootstrap.Modal('#staticBackdrop');
    const myModal2 = new bootstrap.Modal('#staticBackdrop2');
</script>
<?php include __DIR__ . '/../parts/html-foot.php' ?>