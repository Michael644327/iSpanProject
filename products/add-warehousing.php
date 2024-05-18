<?php
require __DIR__ . '/../config/pdo-connect.php';  // 引入資料庫設定

// 搜尋所有商品
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

                <div>
                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="warehousing_date" class="form-label">入庫日期</label>
                                <input type="datetime-local" class="form-control" id="warehousing_date" name="warehousing_date" required>
                                <div class="invalid-feedback">請輸入日期</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label for="warehousing_person" class="form-label">入庫人員</label>
                                <input type="text" class="form-control" id="warehousing_person" name="warehousing_person" required>
                                <div class="invalid-feedback">請輸入入庫人員</div>
                            </div>
                        </div>
                    </div>




                </div>

                <!-- 增加table的按鈕 -->
                <div class="mb-3">
                    <button type="button" class="btn btn-warning" onclick="addItem()">+</button>
                </div>


                <table id="ProductTable" class="table table-striped table-hover align-middle">
                    <thead>
                        <tr>
                            <th scope="col">編號</th>
                            <th scope="col">入庫日期</th>
                            <th scope="col">入庫數量</th>
                            <th scope="col">刪除欄位</th>


                        </tr>
                    </thead>
                    <tbody class="c-container">

                        <tr>
                            <td>1</td>
                            <td>
                                <select class="form-select" aria-label="Default select example" id="product_id" name="product_id[]" required>
                                    <option selected value="">選擇商品</option>

                                    <?php foreach ($allRows as $r) : ?>
                                        <option value="<?= $r['product_id'] ?>"><?= $r['product_name'] ?></option>
                                    <?php endforeach; ?>

                                </select>
                            </td>
                            <td>
                                <input type="number" class="form-control" id="quantity" name="quantity[]" required>
                                <div class="invalid-feedback">需大於1盒</div>
                            </td>
                            <td></td>
                        </tr>

                        <!-- <tr class="c-container"></tr> -->


                    </tbody>
                </table>

                <div class="text-end">
                    <input class="m-3 btn btn-primary" type="submit" value="更新庫存">
                </div>
            </form>

        </div>
    </div>



</div>




<!-- modal 元件 -->
<?php include 'components/modal-add.php' ?>



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

    // 取得參照
    const wdateField = document.form1.warehousing_date;
    const wpersonField = document.form1.warehousing_person;
    // const pIdField = document.form1.product_id;
    const quanField = document.form1.quantity;



    const sendData = e => {
        e.preventDefault();

        let isPass = true; // 表單有沒有通過檢查

        if (wdateField.value === '') {
            isPass = false;
            console.log(1)
        }
        if (wpersonField.value.length < 1) {
            isPass = false;
            console.log(2)
        }
        // if (pIdField.value.length < 2) {
        //     isPass = false;
        // console.log(3)
        // }
        if (Number(quanField.value) < 1) {
            isPass = false;
            console.log(4)
        }

        if (isPass) {
            const fd = new FormData(document.form1);
            fetch('api/add-warehousing-api.php', {
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
    }


    // 從bootstrap來的JS物件
    const myModal = new bootstrap.Modal('#staticBackdrop');
    const myModal2 = new bootstrap.Modal('#staticBackdrop2');

    const c_container = $('.c-container');
    let count = 1;
    const itemFpl = () => {

        return ` <tr class="tr">
                            <td>${count}</td>
                            <td>
                                <select class="form-select" aria-label="Default select example" id="product_id" name="product_id[]" required>
                                    <option selected value="">選擇商品</option>

                                    <?php foreach ($allRows as $r) : ?>
                                        <option value="<?= $r['product_id'] ?>"><?= $r['product_name'] ?></option>
                                    <?php endforeach; ?>

                                </select>
                            </td>
                            <td>
                                <input type="number" class="form-control" id="quantity" name="quantity[]" required>
                                <div class="invalid-feedback">請輸入數量</div>
                            </td>
                            <td>
                            <button type="button" class="btn btn-danger" onclick="removeItem(event)">刪除</button>
                            </td>
                            </tr>    `;
    };

    function addItem() {
        count++;
        c_container.append(itemFpl())

    }

    function removeItem(event) {
        const $el = $(event.target);

        $el.closest('.tr').remove();

    }
</script>

<?php include 'components/foot.php' ?>