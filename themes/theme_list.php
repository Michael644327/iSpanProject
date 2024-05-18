<?php

$title = '主題清單頁';
$pageName = 'theme_list';
?>
<?php

require __DIR__ . '/../config/pdo-connect.php';

$perPage = 20; # 每一頁最多有幾筆
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
if ($page < 1) {
  header("Location:?page=1");
  exit;
}

$t_sql = "SELECT COUNT(theme_id) FROM `themes`";

# 總筆數
$totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];

$totalPages = '';
$rows = [];
if ($totalRows) {
  # 總頁數
  $totalPages = ceil($totalRows / $perPage);
  if ($page > $totalPages) {
    header("Location:?page={$totalPages}");
    exit; // 結束這支程式
  }
  # 取得分頁資料
  $sql = sprintf(
    "SELECT * FROM `themes` ORDER BY theme_id  LIMIT %s, %s",
    ($page - 1) * $perPage,
    $perPage
  );
  $rows = $pdo->query($sql)->fetchAll();
}
?>

<?php include __DIR__ . '/../parts/html-head.php' ?>
<?php include '../products/components/navbar.php' ?>
<style>
  .cell-content {
    display: flex;
    align-items: center;
    height: 100%;
    /* 使內容垂直置中 */
    margin-top: 5px;
  }
</style>


<div class="container-fluid p-5">
  <!-- 分頁膠囊   -->
  <div class="container-fluid">
    <div class="row">
      <!-- 右邊表格 -->
      <div class="col-12">
        <!-- 分頁膠囊 -->
        <ul class="nav nav-pills mb-4" id="pills-tab" role="tablist">
          <li class="nav-item me-3" role="presentation">
            <button class="nav-link active rounded-pill fw-bold" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="true">主題列表</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill fw-bold" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">新增主題</button>
          </li>
          <li class="ms-auto">

            <!-- 查詢 -->
            <div class="container ms-5">
              <form id="searchForm" class="mb-3">
                <div class="row">
                  <div class="col-8">
                    <input type="text" class="form-control" placeholder="輸入主題名稱" name="theme_name">
                  </div>
                  <div class="col-3">
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
                  <th scope="col">主題</th>
                  <th scope="col">難度</th>
                  <th scope="col">遊玩人數</th>
                  <th scope="col">時長</th>
                  <th scope="col">開始日期</th>
                  <th scope="col">結束日期</th>
                  <th scope="col"><i class="fa-solid fa-file-lines"> 檢視</i></th>
                  <th scope="col"><i class="fa-solid fa-pen-to-square"> 編輯</i></th>
                  <th scope="col"><i class="fa-solid fa-trash"></i> 刪除</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($rows as $r) : ?>
                  <tr>
                    <td>
                      <div class="cell-content"><?= $r['theme_id'] ?></div>
                    </td>
                    <td>
                      <div class="cell-content"><?= $r['theme_name'] ?></div>
                    </td>
                    <td>
                      <div class="cell-content"><?= $r['difficulty'] ?></div>
                    </td>
                    <td>
                      <div class="cell-content"><?= $r['suitable_players'] ?></div>
                    </td>
                    <td>
                      <div class="cell-content"><?= $r['theme_time'] ?></div>
                    </td>
                    <td>
                      <div class="cell-content"><?= $r['start_date'] ?></div>
                    </td>
                    <td>
                      <div class="cell-content"><?= $r['end_date'] ?></div>
                    </td>
                    <td>
                      <div>
                        <a class="btn btn-dark" href="theme_content.php?theme_id=<?= $r['theme_id'] ?>">
                          <i class="fa-solid fa-file-lines"> 檢視</i>
                        </a>
                      </div>
                    </td>
                    <td>
                      <div>
                        <a class="btn btn-primary" href="theme_edit.php?theme_id=<?= $r['theme_id'] ?>">
                          <i class="fa-solid fa-pen-to-square"> 編輯</i>
                        </a>
                      </div>
                    </td>
                    <td>
                      <div>
                        <a class="btn btn-danger" href="theme_delete.php?theme_id=<?= $r['theme_id'] ?>" onclick="return confirm('是否要刪除編號為<?= $r['theme_id'] ?>的資料')">
                          <i class="fa-solid fa-trash text-white"> 刪除</i>
                        </a>
                      </div>
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

    fetch('theme_list_search.php?' + queryString) // 將查詢字符串附加到 URL 中
      .then(response => response.json())
      .then(data => {
        var tableBody = document.createElement('tbody');
        data.forEach(theme => {
          var row = document.createElement('tr');
          row.innerHTML = `
        <td>${theme.theme_id}</td>
        <td>${theme.theme_name}</td>
        <td>${theme.difficulty}</td>
        <td>${theme.suitable_players}</td>
        <td>${theme.theme_time}</td>
        <td>${theme.start_date}</td>
        <td>${theme.end_date}</td>
        <td><a class="btn btn-dark" href="theme_content.php?theme_id=${theme.theme_id}"><i class="fa-solid fa-file-lines"> 檢視</i></a></td>
        <td><a class="btn btn-primary" href="theme_edit.php?theme_id=${theme.theme_id}"><i class="fa-solid fa-pen-to-square"> 編輯</i></a></td>
        <td><a <a class="btn btn-danger" href="theme_delete.php?theme_id=${theme.theme_id}" onclick="return confirm('是否要刪除編號為${theme.theme_id}的資料')"><i class="fa-solid fa-trash text-white"> 刪除</i></a></td>
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