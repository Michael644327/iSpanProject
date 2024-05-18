<?php

require __DIR__ . '/../config/pdo-connect.php';  // 引入資料庫設定



$warehousing_id = isset($_GET['warehousing_id']) ? intval($_GET['warehousing_id']) : 0;
// 驗證GET是否有值
if ($warehousing_id < 1) {
    header('Location:Warehousing.php');
    exit;
}


$w_sql = "SELECT * FROM `product_warehousing` WHERE warehousing_id={$warehousing_id}";
$warehousingRow = $pdo->query($w_sql)->fetch();

if (empty($warehousingRow)) {
    header('Location:Warehousing.php');
    exit;
}

// 搜尋取得所有商品
include 'db_select/select_product.php';
?>


<?php include __DIR__ . '/../parts/html-head.php' ?>
<?php include 'components/navbar.php' ?>


<!-- 新增form -->
<div class="container mt-5 py-2">
    <div class="row mt-5">
        <div class="col-10 mx-auto">
            <h2 class="text-center p-3 mt-3"><i class="fa-solid fa-ghost"></i></i>商品入庫</h2>
            <!-- ---- -->


            <form name="form1" class="needs-validation" novalidate onsubmit="sendData(event)">

                <!-- warehousing_id 送出表單攜帶的 -->
                <input type="hidden" name="warehousing_id" value="<?= $warehousing_id ?>">
                <!-- 新增人員，不可修改 -->
                <div>
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="warehousing_date" class="form-label">入庫日期</label>
                                <input type="datetime-local" class="form-control" id="warehousing_date" name="warehousing_date" value="<?= $warehousingRow['created_at'] ?>" disabled>
                                <div class="form-text"></div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="warehousing_person" class="form-label">入庫人員</label>
                                <input type="text" class="form-control" id="warehousing_person" name="warehousing_person" value="<?= $warehousingRow['warehousing_person'] ?>" disabled>
                                <div class="form-text"></div>
                            </div>
                        </div>
                    </div>
                    <!-- 最後編輯人員 -->
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="warehousing_date" class="form-label">最後編輯日</label>
                                <input type="datetime-local" class="form-control" id="warehousing_date" name="warehousing_date" value="<?= $warehousingRow['last_modified_at'] ?>" disabled>
                                <div class="form-text"></div>
                            </div>
                        </div>
                        <!-- TODO 目前最後編輯是跟入庫人員一樣，可編輯。之後開權限再連結員工名稱 -->
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="last_modified_by" class="form-label">最後編輯人員</label>
                                <input type="text" required class="form-control" id="last_modified_by" name="last_modified_by" value="<?= $warehousingRow['last_modified_by'] ?>">
                                <div class="invalid-feedback">請輸入編輯人員</div>
                            </div>
                        </div>
                    </div>




                </div>




                <table id="ProductTable" class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th scope="col">編號</th>
                            <th scope="col">入庫商品名稱</th>
                            <th scope="col">入庫數量</th>



                        </tr>
                    </thead>
                    <tbody>


                        <tr>
                            <td>1</td>
                            <td>
                                <select class="form-select" aria-label="Default select example" id="product_id" name="product_id">


                                    <?php foreach ($allRows as $r) :
                                        if ($r['product_id'] === $warehousingRow['product_id']) :

                                    ?>
                                            <option selected value="<?= $r['product_id'] ?>"><?= $r['product_name'] ?></option>
                                    <?php endif;
                                    endforeach; ?>

                                </select>
                            </td>
                            <td>
                                <input required type="number" class="form-control" id="quantity" name="quantity" value="<?= $warehousingRow['quantity'] ?>">
                                <div class="invalid-feedback">需大於一盒</div>
                            </td>

                        </tr>




                    </tbody>
                </table>

                <div class="text-end">
                    <input class="m-3 btn btn-primary" type="submit" value="編輯庫存">
                </div>
            </form>

        </div>
    </div>



</div>




<!-- modal 元件 -->
<?php include 'components/modal-edit.php' ?>





<?php include __DIR__ . '/../parts/scripts.php' ?>


<script>
    // BT表單驗證
    (function() {
        'use strict' //聲明嚴格模式


        var forms = document.querySelectorAll('.needs-validation')

        Array.prototype.slice.call(forms)
            .forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
    })()

    // ---------BT的end

    // 拿到表格內容的參照
    const lstmodiField = document.form1.last_modified_by;
    const QuantField = document.form1.quantity;



    const sendData = e => {
        e.preventDefault();

        let isPass = true; // 表單有沒有通過檢查

        if (lstmodiField.value === '') {
            isPass = false;
            console.log(1)
        }
        if (Number(QuantField.value) < 1) {
            isPass = false;
            console.log(2)
        }




        // 有通過檢查, 才要送表單
        if (isPass) {
            const fd = new FormData(document.form1);
            fetch('api/edit-warehousing-api.php', {
                    method: 'POST',
                    body: fd,
                }).then(r => r.json())
                .then(data => {
                    console.log('data');
                    console.log(data);
                    // 如果資料新增成功跳modal提示
                    if (data.success) {
                        myModal.show();
                    } else {
                        myModal2.show();
                    }
                })
                .catch(ex => console.log(ex))
        }

    };
    // 從bootstrap來的JS物件
    const myModal = new bootstrap.Modal('#staticBackdrop');
    const myModal2 = new bootstrap.Modal('#staticBackdrop2');
</script>

<?php include 'components/foot.php' ?>