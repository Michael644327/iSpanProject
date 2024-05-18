<?php
require __DIR__ . '/../config/pdo-connect.php';
$title = '修改分店資料';

$branchId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($branchId < 1) {
    header('Location: branch_list.php');
    exit;
}

$sql = "SELECT * FROM `branches` WHERE id={$branchId}";

$row = $pdo->query($sql)->fetch();
if (empty($row)) {
    header('Location: branch_list.php');
    exit;
}

// 初始化已选择的主题数组
$selectedThemes = [];

// 检查是否有已选择的主题，并存储到 $selectedThemes 中
if (!empty($row['theme_id'])) {
    $selectedThemes = explode(',', $row['theme_id']);
}

$pageName = 'branch_add';

$branchThemesSql = "SELECT t.theme_id AS t_theme_id, bt.*, t.* 
FROM branch_themes AS bt
INNER JOIN themes AS t
ON t.theme_id = bt.theme_id
WHERE branch_id = ?
";

$branchThemesStmt = $pdo->prepare($branchThemesSql);
$branchThemesStmt->execute([$branchId]);
$branchThemes = $branchThemesStmt->fetchAll();

$selectedThemeIds = array_column($branchThemes, 't_theme_id');

$themesSql = "SELECT theme_id, theme_name FROM themes";
$themesStmt = $pdo->query($themesSql);
$themes = $themesStmt->fetchAll();

$selectedThemeIds = array_column($branchThemes, 't_theme_id');


$themesSql = "SELECT theme_id, theme_name FROM themes";
$themesStmt = $pdo->query($themesSql);
$themes = $themesStmt->fetchAll();

$selectedThemeIds = array_column($branchThemes, 't_theme_id');

?>

<?php include __DIR__ . '/../parts/html-head.php' ?>
<?php include __DIR__ . '/../parts/bt-navbar.php' ?>

<style>
    form .mb-4 .form-text {
        color: tomato;
        font-weight: 800;
    }
</style>


<div class="container my-5">
    <div class="row">
        <div class="col-10">
            <ul class="nav nav-pills mb-4 d-flex align-items-center" id="pills-tab" role="tablist">
                <li class="nav-item me-4" role="presentation">
                    <a href="branch_list.php"><button type="button" class="btn btn-outline-primary rounded-pill"><i class="fa-solid fa-arrow-left"></i> 回分店</button></a>
                </li>
                <li class="nav-item me-4 d-flex align-items-center pt-2">
                    <h4 class="fw-bold"><i class="fa-solid fa-ghost"> </i> 編輯分店</h4>
                </li>
            </ul>
            <div class="card">
                <div class="card-body">
                    <form name="form1" class="p-3" onsubmit="sendData(event)" action="branch_edit.php?id=<?php echo $branchId; ?>" method="post">

                        <div class="mb-4 col-2">
                            <label for="id" class="form-label">編號</label>
                            <input type="text" class="form-control" disabled value="<?= $row['id'] ?>">
                        </div>

                        <div class="mb-4 col-5">
                            <label for="branch_name" class="form-label fw-bold">分店名字</label>
                            <input type="text" class="form-control" id="branch_name" name="branch_name" value="<?= $row['branch_name'] ?>">
                            <div class="form-text"></div>
                        </div>

                        <div class="mb-4 col-10">
                            <label for="branch_name" class="form-label fw-bold">遊玩行程主題</label>
                            <div>
                                <?php foreach ($themes as $theme) : ?>
                                    <div class="form-check form-check-inline me-3 mb-3">
                                        <input class="form-check-input" type="checkbox" id="theme_<?php echo $theme['theme_id']; ?>" name="theme_id[]" value="<?php echo $theme['theme_id']; ?>" <?php if (in_array($theme['theme_id'], $selectedThemeIds)) echo 'checked'; ?>>
                                        <label class="form-check-label" for="theme_<?php echo $theme['theme_id']; ?>"><?php echo $theme['theme_name']; ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="mb-4 col-5">
                            <label for="branch_phone" class="form-label fw-bold">電話</label>
                            <input type="text" class="form-control" id="branch_phone" name="branch_phone" placeholder="請輸入電話" value="<?= $row['branch_phone'] ?>">
                            <div class="form-text"></div>
                        </div>

                        <div class="row">
                            <div class="mb-4 col-5">
                                <label for="open_time" class="form-label fw-bold">開門時間</label>
                                <input type="text" class="form-control" id="open_time" name="open_time" placeholder="請輸入時間" value="<?= $row['open_time'] ?>">
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-4 col-5">
                                <label for="close_time" class="form-label fw-bold">閉門時間</label>
                                <input type="text" class="form-control" id="close_time" name="close_time" placeholder="請輸入時間" value="<?= $row['close_time'] ?>">
                                <div class="form-text"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-4 col-5">
                                <label for="branch_status" class="form-label fw-bold">營業狀態</label>
                                <select class="form-select" aria-label="Default select example" id="branch_status" name="branch_status">
                                    <option value="" selected disabled>請選擇狀態</option>
                                    <option value="新開幕" <?= $row['branch_status'] == "新開幕" ? 'selected' : '' ?>>新開幕
                                    </option>
                                    <option value="營業中" <?= $row['branch_status'] == "營業中" ? 'selected' : '' ?>>營業中
                                    </option>
                                    <option value="裝潢中" <?= $row['branch_status'] == "裝潢中" ? 'selected' : '' ?>>裝潢中
                                    </option>
                                    <option value="停止營業" <?= $row['branch_status'] == "停止營業" ? 'selected' : '' ?>>停止營業
                                    </option>
                                </select>
                                <div class="form-text"></div>
                            </div>


                            <div class="mb-4 col-10">
                                <label for="branch_address" class="form-label fw-bold">地址</label>
                                <textarea class="form-control" id="branch_address" name="branch_address" cols="30" rows="3" placeholder="請輸入地址"><?= $row['branch_address'] ?></textarea>
                                <div class="form-text"></div>
                            </div>

                        </div>

                        <div class="d-flex justify-content-end me-3">
                            <button type="submit" class="btn btn-primary">編輯</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal bt 彈跳視窗-->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">修改成功</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success" role="alert">
                    資料修改成功 d(`･∀･)b
                </div>
            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-primary" onclick="location.href='branch_list.php'">到分店頁</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續編輯</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="staticBackdrop2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">修改失敗</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" role="alert">
                    資料修改失敗...
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="location.href='branch_list.php'">到分店頁</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續編輯</button>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../parts/scripts.php' ?>



<script>
    const selectedThemes = Array.from(document.querySelectorAll('input[name="theme_id[]"]:checked'));
    // 在这里检查 selectedThemes 是否包含了正确的已选择主题信息

    const branchId = <?= $branchId ?>;
    const nameField = document.getElementById('branch_name');
    const phoneField = document.getElementById('branch_phone');
    const openTimeField = document.getElementById('open_time');
    const closeTimeField = document.getElementById('close_time');
    const statusField = document.getElementById('branch_status');
    const addressField = document.getElementById('branch_address');
    const form = document.form1;


    const sendData = e => {
        e.preventDefault();

        nameField.style.border = '1px solid #CCCCCC';
        nameField.nextElementSibling.innerText = '';

        phoneField.style.border = '1px solid #CCCCCC';
        phoneField.nextElementSibling.innerText = '';

        openTimeField.style.border = '1px solid #CCCCCC';
        openTimeField.nextElementSibling.innerText = '';

        closeTimeField.style.border = '1px solid #CCCCCC';
        closeTimeField.nextElementSibling.innerText = '';

        statusField.style.border = '1px solid #CCCCCC';
        statusField.nextElementSibling.innerText = '';

        addressField.style.border = '1px solid #CCCCCC';
        addressField.nextElementSibling.innerText = '';

        let isPass = true; // 表單有沒有通過檢查

        if (nameField.value.trim() === '') {
            isPass = false;
            nameField.style.border = '1px solid tomato';
            nameField.nextElementSibling.innerText = '請填寫分店名稱';
        }

        const selectedThemes = Array.from(document.querySelectorAll('input[name="theme_id[]"]:checked'));
        if (selectedThemes.length === 0) {
            isPass = false;
            const themeCheckboxes = document.querySelectorAll('input[name="theme_id[]"]');
            themeCheckboxes.forEach(checkbox => {
                checkbox.nextElementSibling.style.color = 'tomato';
            });
        } else {
            const themeCheckboxes = document.querySelectorAll('input[name="theme_id[]"]');
            themeCheckboxes.forEach(checkbox => {
                checkbox.nextElementSibling.style.color = ''; // 重置颜色
            });
        }

        if (phoneField.value.trim() === '') {
            isPass = false;
            phoneField.style.border = '1px solid tomato';
            phoneField.nextElementSibling.innerText = '請填寫電話';
        }

        if (openTimeField.value.trim() === '') {
            isPass = false;
            openTimeField.style.border = '1px solid tomato';
            openTimeField.nextElementSibling.innerText = '請填寫開門時間';
        }

        if (closeTimeField.value.trim() === '') {
            isPass = false;
            closeTimeField.style.border = '1px solid tomato';
            closeTimeField.nextElementSibling.innerText = '請填寫閉門時間';
        }

        if (statusField.value === '') {
            isPass = false;
            statusField.style.border = '1px solid tomato';
            statusField.nextElementSibling.innerText = '請選擇營業狀態';
        }

        if (addressField.value.trim() === '') {
            isPass = false;
            addressField.style.border = '1px solid tomato';
            addressField.nextElementSibling.innerText = '請填寫地址';
        }

        if (isPass) {
            const fd = new FormData(document.form1);
            fd.append('id', branchId); // 確保分店的 ID 被添加到 FormData 對象中

            fetch('branch_edit_api.php', {
                    method: 'POST',
                    body: fd, // Content-Type: multipart/form-data
                }).then(r => r.json())
                .then(data => {
                    console.log(data);
                    if (data.success) {
                        myModal.show(); // 顯示成功的模態框
                    } else {
                        myModal2.show(); // 顯示失敗的模態框
                    }
                })
                .catch(ex => console.log(ex))

        }
    };
    const myModal = new bootstrap.Modal('#staticBackdrop');
    const myModal2 = new bootstrap.Modal('#staticBackdrop2');
</script>
<?php include __DIR__ . '/../parts/html-foot.php' ?>