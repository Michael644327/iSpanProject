<?php
require __DIR__ . '/../config/pdo-connect.php';
$title = "新增團隊";
$pageName = 'team_add';
?>

<?php include __DIR__ . '/../parts/html-head.php' ?>
<?php include __DIR__ . '/../parts/bt-navbar.php' ?>
<style>
  form .mb-3 .form-text {
    color: red;
    font-weight: 800;
  }
</style>
<div class="container">
  <div class="row">
    <div class="col-6 pt-3 ">
      <div class="card mb-4">
        <div class="card-header">新增團隊</div>
        <div class="card-body">
          <form name="form1" onsubmit="sendData(event)">
            <div class="mb-3">
              <label for="team_title" class="form-label">團隊名稱</label>
              <input type="text" class="form-control" id="team_title" name="team_title">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="leader_id" class="form-label">團長ID</label>
              <input type="int" class="form-control" id="leader_id" name="leader_id">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="team_limit" class="form-label">團員上限</label>
              <input type="int" class="form-control" id="team_limit" name="team_limit">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="tour" class="form-label">行程</label>
              <select name="tour" id="tourSelect"></select>
              <div class="form-text"></div>
            </div>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">新增</button>
        </form>
      </div>
    </div>
  </div>
</div>
</div>
<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">新增成功</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-success" role="alert">
          資料新增成功
        </div>
      </div>
      <div class="modal-footer">

        <button type="button" class="btn btn-primary" onclick="location.href='./teams.php'">到列表頁</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續新增</button>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/js/scripts.php' ?>

<script>
  /* get themes */
  fetch('./api-get-themes.php')
    .then(response => response.json())
    .then(data => {
      // 將回傳的資料處理並填充到 select 元素中
      const tourSelect = document.getElementById('tourSelect');
      data.forEach(theme => {
        const option = document.createElement('option');
        option.value = theme.theme_id;
        option.textContent = `${theme.theme_id} - ${theme.theme_name}`;
        tourSelect.appendChild(option);
      });
    })
    .catch(error => console.error('Error:', error));

  const nameField = document.form1.team_title;
  const team_limit = document.form1.team_limit;
  $count_m_sql = "SELECT COUNT(team_leader) FROM teams where team_leader = #team_leader";
  $user_m_sql = "SELECT user_id FROM users";

  const sendData = e => {
    e.preventDefault(); // 不要讓 form1 以傳統的方式送出

    nameField.style.border = '1px solid #CCCCCC';
    nameField.nextElementSibling.innerText = '';
    team_limit.style.border = '1px solid #CCCCCC';
    team_limit.nextElementSibling.innerText = '';
    // TODO: 欄位資料檢查
    let isPass = true; // 表單有沒有通過檢查
    if (nameField.value.length < 2) {
      isPass = false;
      nameField.style.border = '1px solid red';
      nameField.nextElementSibling.innerText = '團隊名稱至少2個字';
    }
    if (parseInt(team_limit.value) > 8) {
      isPass = false;
      team_limit.style.border = '1px solid red';
      team_limit.nextElementSibling.innerText = '團員上限為8人';
    }

    // 有通過檢查, 才要送表單
    if (isPass) {
      const fd = new FormData(document.form1); // 沒有外觀的表單物件
      fetch('add-api.php', {
          method: 'POST',
          body: fd, // Content-Type: multipart/form-data
        }).then(r => r.json())
        .then(data => {
          console.log(data);
          if (data.success) {
            myModal.show();
          } else {}
        })
        .catch(ex => console.log(ex))
    }
  };

  const myModal = new bootstrap.Modal('#staticBackdrop')
</script>
<?php include __DIR__ . '/../parts/html-foot.php' ?>