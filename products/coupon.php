<?php include 'db_select/select_coupon.php' ?>
<?php include __DIR__ . '/../parts/html-head.php' ?>
<?php include 'components/navbar.php' ?>




<div class="container mt-3 py-2">
    <div class="col-10 col-lg-6">
        <h2 class="p-3 mt-3">優惠券列表</h2>
    </div>
    <div class="row">

        <div class="col-8 mx-auto">

            <nav class="navbar navbar-light bg-light">

                <div class="container-fluid d-flex justify-content-between">
                    <form onsubmit="selectData()" class="d-flex" name="searchForm">
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="mySearch">
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>

                    <div>

                        <!-- 新增優惠券 -->
                        <a class="btn btn-primary" href="add-coupon.php">
                            <i class="bi bi-bag-plus-fill"></i>
                            新增優惠券
                        </a>
                    </div>
                </div>
            </nav>

            <!-- 表單開始 -->
            <table id="ProductTable" class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <!-- 8 -->
                        <th scope="col">編號</th>
                        <th scope="col">優惠券名稱</th>
                        <th scope="col">折扣</th>
                        <th scope="col">總數量</th>
                        <th scope="col">優惠群組</th>
                        <th scope="col">到期日</th>

                        <!-- TODO 做到這邊 -->
                        <th scope="col" class="text-center">操作</th>

                    </tr>
                </thead>
                <tbody>



                    <!-- TODO 還沒做列表內容 -->
                    <?php foreach ($all_coupon_row as $r) : ?>
                        <tr>
                            <td><?= $r['coupon_id'] ?></td>
                            <td><?= $r['coupon_name'] ?></td>
                            <td><?= $r['discount'] ?></td>
                            <td><?= $r['total_quantity'] ?></td>
                            <td><?= $r['user_group'] ?></td>
                            <td><?= $r['expiry_date'] ?></td>


                            <!-- 按鈕 -->
                            <td class="text-end">
                                <a class="btn btn-warning" href="edit-coupon.php?coupon_id=<?= $r['coupon_id'] ?>">
                                    <i class="bi bi-pen-fill"></i>編輯
                                </a>
                                <a class="btn btn-danger" href="javascript: deleteOne(<?= $r['coupon_id'] ?>)">
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
</div>


<?php include __DIR__ . '/../parts/scripts.php' ?>

<script>
    const deleteOne = (coupon_id) => {
        if (confirm(`是否要刪除編號為 ${coupon_id} 的資料?`)) {
            location.href = `delete-coupon.php?coupon_id=${coupon_id}`;
        }
    }
</script>

<?php include 'components/foot.php' ?>