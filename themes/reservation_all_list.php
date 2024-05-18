<?php

$title = '預約清單頁';
$pageName = 'reservation_list';

require __DIR__ . '/../config/pdo-connect.php';

$perPage = 20; // 每一頁最多有幾筆
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
  header("Location:?page=1");
  exit;
}

// 獲取總筆數
$t_sql = "SELECT COUNT(id) FROM `reservations`";
$totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];

// 計算總頁數
$totalPages = ceil($totalRows / $perPage);

// 如果請求的頁數大於總頁數，將用戶重新導向到最後一頁
if ($page > $totalPages) {
  header("Location:?page={$totalPages}");
  exit; // 結束程式
}

// 取得分頁資料
$sql = sprintf(
  "SELECT r.*, u.name AS user_name, u.mobile_phone, u.account, t.theme_name,b.branch_name
   FROM `reservations` AS r 
   LEFT JOIN `users` AS u ON r.user_id = u.user_id 
   LEFT JOIN `themes` AS t ON r.theme_id = t.theme_id
   LEFT JOIN `branches` AS b ON r.branch_id = b.id
   ORDER BY r.id 
   LIMIT %s, %s",
  ($page - 1) * $perPage,
  $perPage
);
$rows = $pdo->query($sql)->fetchAll();

// 確保你的 reservations 表中有 theme_id 欄位，並且它與 themes 表中的 theme_id 欄位相關聯。

$rows = $pdo->query($sql)->fetchAll();

// 取得主題資料
$themesSql = "SELECT theme_id, theme_name FROM themes";
$themesStmt = $pdo->query($themesSql);
$themes = $themesStmt->fetchAll();

// 取得會員資料
$userSql = "SELECT user_id, name, mobile_phone, account FROM users";
$usersStmt = $pdo->query($userSql);
$users = $usersStmt->fetchAll();

// 取得分店資料
$branchSql = "SELECT id, branch_name FROM branches";
$branchStmt = $pdo->query($branchSql);
$branches = $branchStmt->fetchAll();

?>

<?php include __DIR__ . '/../parts/html-head.php' ?>
<?php include __DIR__ . '/../parts/bt-navbar.php' ?>

<div class="container-fluid p-5">
  <!-- 分頁膠囊   -->
  <div class="container-fluid px-3">
    <div class="row">
      <!-- 右邊表格 -->
      <div class="col">
        <!-- 分頁膠囊 -->
        <ul class="nav nav-pills mb-4" id="pills-tab" role="tablist">
          <li class="nav-item me-4" role="presentation">
            <a href="branch_list.php"><button type="button" class="btn btn-outline-primary rounded-pill"><i
                  class="fa-solid fa-arrow-left"></i> 回分店</button></a>
          </li>
          <li class="nav-item me-5" role="presentation">
            <button class="nav-link active rounded-pill fw-bold" id="pills-home-tab" data-bs-toggle="pill"
              data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
              aria-selected="true">預約列表</button>
          </li>
          <li class="ms-auto">

            <!-- 查詢 -->
            <div class="container ms-5">
              <form id="searchForm" class="mb-3">
                <div class="row">
                  <div class="col-8">
                    <input type="text" class="form-control" placeholder="输入分店名稱" name="branch_name">
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
            <table id="themeListTable" class="table table-striped">
              <thead>
                <tr id="themeListTableHead">
                  <th scope="col">#</th>
                  <th scope="col">姓名</th>
                  <th scope="col">電話</th>
                  <th scope="col">信箱</th>
                  <th scope="col">分店</th>
                  <th scope="col">主題名稱</th>
                  <th scope="col">人數</th>
                  <th scope="col">預約時間</th>
                  <th scope="col"><i class="fa-solid fa-pen-to-square"></i></th>
                  <th scope="col"><i class="fa-solid fa-trash"></i></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($rows as $r): ?>
                  <tr>
                    <td><?= $r['id'] ?></td>
                    <td><?= $r['user_name'] ?></td>
                    <td><?= $r['mobile_phone'] ?></td>
                    <td><?= $r['account'] ?></td> <!-- 這裡是 users 表中的信箱資料嗎？如果是，應該改成對應的欄位 -->
                    <td><?= $r['branch_name'] ?></td>
                    <td><?= $r['theme_name'] ?></td>
                    <td><?= $r['participants'] ?></td>
                    <td><?= $r['re_datetime'] ?></td>
                    <td>
                      <a href="reservation_edit.php?id=<?= $r['id'] ?>">
                        <i class="fa-solid fa-pen-to-square"></i>
                      </a>
                    </td>
                    <td><a href="reservation-delete.php?id=<?= $r['id'] ?>"
                        onclick="return confirm('是否要刪除編號為<?= $r['id'] ?>的資料')">
                        <i class="fa-solid fa-trash text-danger"></i>
                      </a></td>
                  </tr>
                <?php endforeach; ?>

              </tbody>
            </table>


            <!-- 分頁按鈕 -->
            <div class="col-12 d-flex justify-content-end mt-5">
              <nav aria-label="Page navigation example m-auto">
                <ul class="pagination">
                  <li class="page-item ">
                    <a class="page-link" href="#">
                      <i class="fa-solid fa-angles-left"></i>
                    </a>
                  </li>
                  <li class="page-item ">
                    <a class="page-link" href="#">
                      <i class="fa-solid fa-angle-left"></i>
                    </a>
                  </li>
                  <?php for ($i = $page - 5; $i <= $page + 5; $i++):
                    if ($i >= 1 and $i <= $totalPages): ?>
                      <li class="page-item <?= $page == $i ? 'active' : '' ?> ">
                        <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                      </li>
                    <?php endif;
                  endfor ?>
                  <li class="page-item">
                    <a class="page-link" href="#">
                      <i class="fa-solid fa-angle-right"></i>
                    </a>
                  </li>
                  <li class="page-item ">
                    <a class="page-link" href="#">
                      <i class="fa-solid fa-angles-right"></i>
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
  // document.getElementById('searchForm').addEventListener('submit', function (event) {
  //   event.preventDefault(); // 阻止表單提交

  //   var formData = new FormData(this);
  //   var queryString = new URLSearchParams(formData).toString(); // 將表單數據轉換為 URL 查詢字符串

  //   fetch('theme_list_search.php?' + queryString) // 將查詢字符串附加到 URL 中
  //     .then(response => response.json())
  //     .then(data => {
  //       var tableBody = document.createElement('tbody');
  //       data.forEach(theme => {
  //         var row = document.createElement('tr');
  //         row.innerHTML = `
  //       <td>${theme.theme_id}</td>
  //       <td>${theme.theme_name}</td>
  //       <td>${theme.difficulty}</td>
  //       <td>${theme.suitable_players}</td>
  //       <td>${theme.theme_time}</td>
  //       <td>${theme.start_date}</td>
  //       <td>${theme.end_date}</td>
  //       <td><a href="theme_content.php?theme_id=${theme.theme_id}"><i class="fa-solid fa-file-lines text-secondary"></i></a></td>
  //       <td><a href="theme_edit.php?theme_id=${theme.theme_id}"><i class="fa-solid fa-pen-to-square"></i></a></td>
  //       <td><a href="theme_delete.php?theme_id=${theme.theme_id}" onclick="return confirm('是否要刪除編號為${theme.theme_id}的資料')"><i class="fa-solid fa-trash text-danger"></i></a></td>
  //     `;
  //         tableBody.appendChild(row);
  //       });
  //       document.getElementById('themeListTable').innerHTML = '';
  //       document.getElementById('themeListTable').appendChild(tableBody)
  //     });
  // });
</script>

<?php include __DIR__ . '/../parts/html-foot.php' ?>