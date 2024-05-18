<?php
require __DIR__ . '/../config/pdo-connect.php';
$title = "修改團隊資料";

$team_id = isset($_GET['team_id']) ? intval($_GET['team_id']) : 0;
if ($team_id < 1) {
  header('Location: teams.php');
  exit;
}

$sql = "SELECT * FROM teams 
        join `users` on leader_id = users.user_id
        join `themes` on `tour` = themes.theme_id
        WHERE team_id={$team_id}";

$row = $pdo->query($sql)->fetch();
if (empty($row)) {
  header('Location: teams.php');
  exit;
}
$sql2 = "SELECT * FROM teams_status";
$stmt2 = $pdo->prepare($sql2);
$stmt2->execute();
$status = $stmt2->fetchAll(PDO::FETCH_ASSOC);

/* fetch 留言 串用戶暱稱*/
$sql_c = "SELECT nick_name, chat_text, create_at
        FROM teams_chats
        join `users` on chat_by = users.user_id
        WHERE chat_at={$team_id}";

$stmt_c = $pdo->prepare($sql_c);
$stmt_c->execute();
$chats = $stmt_c->fetchAll(PDO::FETCH_ASSOC);

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
    <div class="col-6">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">編輯資料</h5>
          <form name="form1" onsubmit="sendData(event)">
            <div class="mb-3">
              <input type="hidden" class="form-control" name="team_id" value="<?= $row['team_id'] ?>">
            </div>
            <div class="mb-3">
              <label for="team_title" class="form-label">團隊名稱</label>
              <input type="text" class="form-control" id="team_title" name="team_title" value="<?= $row['team_title'] ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="leader_id" class="form-label">團長</label>
              <input type="int" class="form-control" id="leader_id" name="leader_id" disabled value="<?= $row['leader_id'], ' - ', $row['nick_name'] ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="team_limit" class="form-label">團員上限</label>
              <input type="int" class="form-control" id="team_limit" name="team_limit" value="<?= $row['team_limit'] ?>">
              <div class="form-text"></div>
            </div>
            <div class="mb-3">
              <label for="tour" class="form-label">行程</label>
              <select name="tour" id="tourSelect"></select>
            </div>
            <div class="mb-3">
              <label for="status" class="form-label">團隊狀態</label>
              <?php foreach ($status as $status_i) : ?>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="team_status" id="teams_status_<?php echo $status_i['status_id'] ?>" value="<?php echo $status_i['status_id'] ?>" <?php if ($status_i['status_id'] == $row['team_status']) echo 'checked'; ?>>
                  <label class="form-check-label" for="inlineRadio<?php echo $status_i['status_id'] ?>"><?php echo $status_i['status_text'] ?></label>
                </div>
              <?php endforeach; ?>
            </div>
            <button type="submit" class="btn btn-primary">修改</button>
          </form>
        </div>
      </div>
    </div>
    <!-- <div class="col-6">
      <div class="card">
        <div class="card-body">
        <h5 class="card-title">編輯留言</h5>
        <?php foreach ($chats as $chat_i) : ?>
          <h5>留言者：<?php echo $chat_i['nick_name']; ?></h5>
          <p>內文：<?php echo $chat_i['chat_text']; ?></p>
          <p>留言時間：<?php echo $chat_i['create_at']; ?></p>
          <hr>
        <?php endforeach; ?>
        </div>
      </div>
    </div> -->
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">修改成功</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-success" role="alert">資料修改成功
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="location.href='./teams.php'">到列表頁</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續編輯</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="staticBackdrop2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">修改成功</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="alert alert-success" role="alert">資料沒有修改
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="location.href='./teams.php'">到列表頁</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續編輯</button>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . './js/scripts.php' ?>
<script>
  const team_title = document.form1.team_title;
  const team_limit = document.form1.team_limit;

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

        // 檢查是否該選項應該被設置為 selected
        if (theme.theme_id == <?php echo $row['theme_id']; ?>) {
          option.selected = true;
        }
        tourSelect.appendChild(option);
      });
    })
    .catch(error => console.error('Error:', error));



  const sendData = e => {
    e.preventDefault(); // 不要讓 form1 以傳統的方式送出

    team_title.style.border = '1px solid #CCCCCC';
    team_title.nextElementSibling.innerText = '';
    team_limit.style.border = '1px solid #CCCCCC';
    team_limit.nextElementSibling.innerText = '';
    // TODO: 欄位資料檢查
    let isPass = true; // 表單有沒有通過檢查
    if (team_title.value.length < 2) {
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
      fetch('edit-api.php', {
          method: 'POST',
          body: fd, // Content-Type: multipart/form-data
        }).then(r => r.json())
        .then(data => {
          console.log(data);
          if (data.success) {
            myModal.show();
          } else {
            myModal2.show();
          }
        })
        .catch(ex => console.log(ex))
    }
  };

  const myModal = new bootstrap.Modal('#staticBackdrop')
  const myModal2 = new bootstrap.Modal('#staticBackdrop2')
</script>
<?php include __DIR__ . '/../parts/html-foot.php' ?>