<?php
// 包含 PDO 连接文件
require __DIR__ . '/../config/pdo-connect.php';

$title = '查看主題資料';

// 检查是否设置了 theme_id 参数
$theme_id = isset($_GET['theme_id']) ? intval($_GET['theme_id']) : 0;
if ($theme_id < 1) {
    header('Location: theme_list.php');
    exit;
}

// 查询主题数据
$sql = "SELECT * FROM `themes` WHERE theme_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$theme_id]);
$theme = $stmt->fetch();

// 如果查询结果为空，则重定向到主题列表页
if (empty($theme)) {
    header('Location: theme_list.php');
    exit;
}

$pageName = 'theme_view';
?>

<?php include __DIR__ . '/../parts/html-head.php' ?>
<?php include __DIR__ . '/../parts/bt-navbar.php' ?>

<style>
    .fs-5,
    .fs-6 {
        letter-spacing: 1px;
        /* Adjust the letter spacing value as needed */
    }
</style>

<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-12">
            <ul class="nav nav-pills mb-4 d-flex align-items-center" id="pills-tab" role="tablist">
                <li class="nav-item me-4" role="presentation">
                    <a href="theme_list.php" class="btn btn-outline-primary rounded-pill"><i
                            class="fa-solid fa-arrow-left me-2"></i> 回主題</a>
                </li>
                <li class="nav-item d-flex align-items-center">
                    <h4 class="mb-0 fw-bold fs-5"><i class="fa-solid fa-ghost me-2 fs-5"></i> 查看主題資料</h4>
                </li>
            </ul>
            <div class="card">
                <div class="card-body">
                    <ul class="list-group list-group-flush" style="letter-spacing: 2px;">
                        <li class="list-group-item"><strong class="fs-6">編號：</strong><span
                                class="text-truncate"><?= $theme['theme_id'] ?></span></li>
                        <li class="list-group-item"><strong class="fs-6">主題名稱：</strong><span
                                class="text-truncate"><?= $theme['theme_name'] ?></span></li>
                        <li class="list-group-item"><strong class="fs-6">主題圖片：</strong><br>
                            <img class="rounded-3 w-25 my-3" src="imgs/<?= $theme['theme_img'] ?>"
                                alt="<?= $theme['theme_name'] ?>">
                        </li>
                        <li class="list-group-item"><strong class="fs-6">主題描述：</strong><span
                                class="text-truncate"><?= $theme['theme_desc'] ?></span></li>
                        <li class="list-group-item"><strong class="fs-6">價錢：</strong><span
                                class="text-truncate"><?= $theme['price'] ?> 元</span></li>
                        <li class="list-group-item"><strong class="fs-6">難度：</strong><span
                                class="text-truncate"><?= $theme['difficulty'] ?> 星級</span></li>
                        <li class="list-group-item"><strong class="fs-6">遊玩人數：</strong><span
                                class="text-truncate"><?= $theme['suitable_players'] ?></span></li>
                        <li class="list-group-item"><strong class="fs-6">開始時間：</strong><span
                                class="text-truncate"><?= $theme['start_time'] ?></span></li>
                        <li class="list-group-item"><strong class="fs-6">結束時間：</strong><span
                                class="text-truncate"><?= $theme['end_time'] ?></span></li>
                        <li class="list-group-item"><strong class="fs-6">遊戲時長：</strong><span
                                class="text-truncate"><?= $theme['theme_time'] ?></span></li>
                        <li class="list-group-item"><strong class="fs-6">間隔時間：</strong><span
                                class="text-truncate"><?= $theme['intervals'] ?></span></li>
                        <li class="list-group-item"><strong class="fs-6">開始日：</strong><span
                                class="text-truncate"><?= $theme['start_date'] ?></span></li>
                        <li class="list-group-item"><strong class="fs-6">結束日：</strong><span
                                class="text-truncate"><?= $theme['end_date'] ?></span></li>
                        <!-- 添加其他主题信息 -->
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>





<?php include __DIR__ . '/../parts/html-foot.php' ?>