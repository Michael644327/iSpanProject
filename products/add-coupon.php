<?php include __DIR__ . '/../parts/html-head.php' ?>
<?php include 'components/navbar.php' ?>

<div class="container mt-2 py-2">
    <div class="row">
        <div class="col-10 col-lg-6 mx-auto">
            <h2 class="text-center p-3 mt-3"><i class="fa-solid fa-ghost"></i></i>新增優惠券</h2>





            <div class="col-12 my-5 text-center">
                <div class="d-flex justify-content-center">
                    <div class="d-flex align-items-center">最近的節日:</div>
                    <div class="btn btn-success" id="holiday"></div>
                    <!-- <a class="btn btn-success">使用節日名稱</a> -->
                </div>
            </div>

            <form name="form1" class="needs-validation" novalidate onsubmit="sendData(event)">


                <!-- 優惠名稱 -->
                <div class="m-3">
                    <label for="coupon_name" class="form-label">商品名稱</label>
                    <input type="text" id="coupon_name" name="coupon_name" class="form-control" required>
                    <div class="invalid-feedback">
                        請輸入優惠券名稱
                    </div>
                </div>
                <!-- 折扣 -->
                <div class="m-3">
                    <label for="discount" class="form-label">優惠折扣</label>
                    <select name="discount" id="discount" class="form-select" aria-label="Default select example" required>
                        <option selected disabled value="">優惠折扣</option>
                        <option value="0.9">9折</option>
                        <option value="0.8">8折</option>
                        <option value="0.7">7折</option>
                        <option value="0.6">6折</option>
                    </select>
                </div>
                <!-- 總數 -->
                <div class="m-3">
                    <label for="total_quantity" class="form-label">優惠券總量</label>
                    <input type="number" class="form-control" name="total_quantity" id="total_quantity" required>

                    <div class="invalid-feedback">
                        請輸入總張數
                    </div>
                </div>

                <!-- 使用群組 -->
                <div class="m-3">
                    <label for="user_group" class="form-label">優惠會員等級</label>
                    <input type="text" class="form-control" name="user_group" id="user_group" required>

                    <div class="invalid-feedback">
                        請輸入會員等級
                    </div>
                </div>

                <!-- 使用期限 -->
                <div class="m-3">
                    <label for="expiry_date" class="form-label">優惠期限</label>
                    <input type="date" class="form-control" name="expiry_date" id="expiry_date" required>

                    <div class="invalid-feedback">
                        請輸入優惠期限
                    </div>
                </div>




                <div class="text-end">
                    <input class="m-3 btn btn-primary" type="submit" value="新增優惠券">


            </form>
        </div>
    </div>


</div>
</div>

<script src="coupon/coupon.js"></script>
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

    $("#holiday").click(function() {
        let holiArr = ["限時折扣券", "感恩回饋券", "超值特惠券"];
        let random = Math.floor(Math.random() * 3);
        let holiday = $("#holiday").text();
        $("#coupon_name").val(holiday + holiArr[random]);
    })

    // 取得參照
    const nameField = document.form1.coupon_name;
    const discountField = document.form1.discount;
    const quantityField = document.form1.total_quantity;
    const dateField = document.form1.expiry_date;



    const sendData = e => {
        e.preventDefault();

        let isPass = true; // 表單有沒有通過檢查

        if (nameField.value === '') {
            isPass = false;
            console.log(1)
        }
        if (discountField.value === '') {
            isPass = false;
            console.log(2)
        }

        if (Number(quantityField.value) < 1) {
            isPass = false;
            console.log(4)
        }
        if (dateField.value === '') {
            isPass = false;
            console.log(2)
        }

        if (isPass) {
            const fd = new FormData(document.form1);
            fetch('api/add-coupon-api.php', {
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
</script>
<?php include 'components/foot.php' ?>