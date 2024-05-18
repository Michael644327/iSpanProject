<?php
require __DIR__ . '/../config/pdo-connect.php';  // 引入資料庫設定

// 搜尋取得入庫資料
require __DIR__ . '/db_select/select_warehousing.php';

?>


<?php include __DIR__ . '/../parts/html-head.php' ?>
<?php include '../parts/bt-navbar.php' ?>

<div class="container mt-3">
    <div class="row">
        <div class="col-12 mt-4 mb-3">
            <h2>商品庫存列表</h2>
        </div>

        <div class="col-8 mx-auto">

            <nav class="navbar navbar-light bg-light">
                <div class="container-fluid d-flex justify-content-between">

                    <form onsubmit="selectData()" class="d-flex" name="searchForm">
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="mySearch">
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>

                    <div>

                        <!-- TODO 新增庫存 -->
                        <a class="btn btn-primary" href="add-warehousing.php">
                            <i class="bi bi-bag-plus-fill"></i>
                            新增庫存
                        </a>
                    </div>
                </div>
            </nav>

            <!-- 表單開始 -->
            <table id="ProductTable" class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th scope="col">編號</th>
                        <th scope="col">入庫日期</th>
                        <th scope="col">入庫人員</th>
                        <th scope="col">最後編輯人員</th>
                        <th scope="col" class="text-center">操作</th>

                    </tr>
                </thead>
                <tbody>

                    <!-- TODO -->
                    <?php foreach ($allRows as $r) : ?>
                        <tr>
                            <td><?= $r['warehousing_id'] ?></td>
                            <td><?= $r['warehousing_date'] ?></td>
                            <td><?= $r['warehousing_person'] ?></td>
                            <td><?= $r['last_modified_by'] ?></td>

                            <!-- 按鈕 -->
                            <td class="text-end">
                                <a class="btn btn-warning" href="edit-warehousing.php?warehousing_id=<?= $r['warehousing_id'] ?>">
                                    <i class="bi bi-pen-fill"></i>編輯
                                </a>
                                <a class="btn btn-danger" href="javascript: deleteOne(<?= $r['warehousing_id'] ?>)">
                                    <i class="bi bi-trash-fill"></i>刪除
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>


                </tbody>
            </table>
            <!-- 表單結束 -->
        </div>

    </div>
</div>

<?php include __DIR__ . '/../parts/scripts.php' ?>
<script>
    const deleteOne = (warehousing_id) => {
        if (confirm(`是否要刪除編號為 ${warehousing_id} 的資料?`)) {
            location.href = `delete-warehousing.php?warehousing_id=${warehousing_id}`;
        }
    }
</script>

<?php include 'components/foot.php' ?>