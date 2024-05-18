<?php
$title = '預約清單頁';
$pageName = 'branch_reservation_details';

require __DIR__ . '/../config/pdo-connect.php';

// 檢查 URL 中是否存在分店 ID，如果不存在，則導向到分店列表頁面
if (!isset($_GET['id'])) {
  header("Location: branch_list.php");
  exit;
}

$branchId = intval($_GET['id']);

$perPage = 20; // 每一頁最多有幾筆
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
  header("Location:?id={$branchId}&page=1");
  exit;
}

// 獲取總筆數
$t_sql = "SELECT COUNT(id) FROM `reservations` WHERE branch_id = ?";
$totalRows = $pdo->prepare($t_sql);
$totalRows->execute([$branchId]);
$totalRows = $totalRows->fetch(PDO::FETCH_NUM)[0];

// 計算總頁數
$totalPages = ceil($totalRows / $perPage);

// 如果請求的頁數大於總頁數，將用戶重新導向到最後一頁
if ($page > $totalPages) {
  header("Location:?id={$branchId}&page={$totalPages}");
  exit;
}

// 取得分頁資料
$sql = sprintf(
  "SELECT r.*, u.name AS user_name, u.mobile_phone, u.account, t.theme_name, b.branch_name
   FROM `reservations` AS r 
   LEFT JOIN `users` AS u ON r.user_id = u.user_id 
   LEFT JOIN `themes` AS t ON r.theme_id = t.theme_id
   LEFT JOIN `branches` AS b ON r.branch_id = b.id
   WHERE r.branch_id = ? 
   ORDER BY r.id 
   LIMIT %s, %s",
  ($page - 1) * $perPage,
  $perPage
);

$rows = $pdo->prepare($sql);
$rows->execute([$branchId]);
$rows = $rows->fetchAll();
?>
<?php include __DIR__ . '/../parts/html-head.php' ?>
<?php include '../products/components/navbar.php' ?>
<style>
  .align-middle {
    display: flex;
    align-items: center;
    height: 100%;
    margin-top: 10px;
    /* 這個是為了讓內容在垂直方向上居中對齊 */
  }
</style>

<div class="container-fluid p-5">
  <!-- 分頁膠囊   -->
  <div class="container-fluid px-3">
    <div class="row">
      <!-- 右邊表格 -->
      <div class="col">
        <!-- 分頁膠囊 -->
        <ul class="nav nav-pills mb-4" id="pills-tab" role="tablist">
          <li class="nav-item me-4" role="presentation">
            <a href="branch_list.php"><button type="button" class="btn btn-outline-primary rounded-pill"><i class="fa-solid fa-arrow-left"></i> 回分店</button></a>
          </li>
          <li class="nav-item me-5" role="presentation">
            <button class="nav-link active rounded-pill fw-bold" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">預約列表</button>
          </li>
          <li class="ms-auto">

            <!-- 查詢 -->
            <div class="container ms-5">
              <form id="searchForm" class="mb-3">
                <div class="row">
                  <div class="col-8">
                    <input type="text" class="form-control" placeholder="输入顧客名字" name="user_name">
                  </div>
                  <div class="col-4">
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i></button>
                  </div>
                </div>
              </form>
            </div>
          </li>
        </ul>



        <!-- 清單 -->
        <div class="tab-content" id="pills-tabContent">
          <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">

            <!-- 表單 -->
            <table id="themeListTable" class="table table-striped table-hover">
              <thead>
                <tr id="themeListTableHead">
                  <th scope="col">#</th>
                  <th scope="col">姓名</th>
                  <th scope="col">電話</th>
                  <th scope="col">信箱</th>
                  <th scope="col">主題名稱</th>
                  <th scope="col">人數</th>
                  <th scope="col">預約時間</th>
                  <th scope="col"><i class="fa-solid fa-trash"> 刪除</i></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($rows as $r) : ?>
                  <tr>
                    <td>
                      <div class="align-middle"><?= $r['id'] ?></div>
                    </td>
                    <td>
                      <div class="align-middle"><?= $r['user_name'] ?></div>
                    </td>
                    <td>
                      <div class="align-middle"><?= $r['mobile_phone'] ?></div>
                    </td>
                    <td>
                      <div class="align-middle"><?= $r['account'] ?></div>
                    </td>
                    <td>
                      <div class="align-middle"><?= $r['theme_name'] ?></div>
                    </td>
                    <td>
                      <div class="align-middle"><?= $r['participants'] ?></div>
                    </td>
                    <td>
                      <div class="align-middle"><?= $r['re_datetime'] ?></div>
                    </td>
                    <td>
                      <a class="btn btn-danger" href="reservation-delete.php?id=<?= $r['id'] ?>" onclick="return confirm('是否要刪除編號為<?= $r['id'] ?>的資料')">
                        <i class="fa-solid fa-trash text-white"></i> 刪除
                      </a>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>


            </table>


            <!-- 分頁按鈕 -->
            <div class="col-12 d-flex justify-content-end mt-5">
              <nav aria-label="Page navigation example m-auto">
                <ul class="pagination">

                  <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Previous">
                      <i class="fa-solid fa-angle-left"></i>
                    </a>
                  </li>
                  <?php for ($i = max(1, $page - 5); $i <= min($page + 5, $totalPages); $i++) : ?>
                    <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                      <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                  <?php endfor ?>
                  <li class="page-item <?= $page == $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Next">
                      <i class="fa-solid fa-angle-right"></i>
                    </a>
                  </li>

                </ul>
              </nav>
            </div>
          </div>
          <!-- 新增主題 -->
          <div class="tab-pane fade mb-5" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
            <?php include __DIR__ . '/theme_add.php' ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../parts/scripts.php' ?>

<script>
  document.getElementById('searchForm').addEventListener('submit', function(event) {
    event.preventDefault(); // 阻止表單提交

    var formData = new FormData(this);
    var queryString = new URLSearchParams(formData).toString(); // 將表單數據轉換為 URL 查詢字符串

    fetch('reservation_search.php?' + queryString) // 將查詢字符串附加到 URL 中
      .then(response => response.json())
      .then(data => {
        var tableBody = document.createElement('tbody');
        data.forEach(reservation => {
          var row = document.createElement('tr');
          row.innerHTML = `
            <td>${reservation.id}</td>
            <td>${reservation.user_name}</td>
            <td>${reservation.mobile_phone}</td>
            <td>${reservation.account}</td>
            <td>${reservation.theme_name}</td>
            <td>${reservation.participants}</td>
            <td>${reservation.re_datetime}</td>
            <td>
              <a class="btn btn-danger" href="reservation-delete.php?id=${reservation.id}" onclick="return confirm('是否要刪除編號為${reservation.id}的資料')">
              <i class="fa-solid fa-trash text-white"> 刪除</i>
              </a>
            </td>
          `;
          tableBody.appendChild(row);
        });
        var table = document.getElementById('themeListTable');
        table.querySelector('tbody').remove(); // 移除先前的 tbody 元素
        table.appendChild(tableBody); // 添加新的 tbody 元素
      });
  });
</script>

<?php include __DIR__ . '/../parts/html-foot.php' ?>