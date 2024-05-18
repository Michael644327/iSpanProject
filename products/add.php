<!-- 搜尋分類 -->
<?php include 'db_select/select_category.php' ?>

<!-- 狀態查詢 -->
<?php include 'db_select/select_status.php' ?>
<?php include __DIR__ . '/../parts/html-head.php' ?>
<?php include 'components/navbar.php' ?>



<!-- 新增form -->
<div class="container mt-3 py-2">
    <div class="row">
        <div class="col-10 col-lg-6 mx-auto">
            <h2 class="text-center p-3 mt-3"><i class="fa-solid fa-ghost"></i></i>新商品上架</h2>

            <form name="form1" class="needs-validation" novalidate onsubmit="sendData(event)">

                <!-- 商品名稱 -->
                <div class="m-3">
                    <label for="product_name" class="form-label">商品名稱</label>
                    <input type="text" id="product_name" name="product_name" class="form-control" required>
                    <div class="invalid-feedback">
                        請輸入商品名稱
                    </div>
                </div>
                <!-- 商品分類 -->
                <div class="m-3">
                    <label for="category_id" class="form-label">商品分類</label>
                    <select name="category_id" id="category_id" class="form-select" aria-label="Default select example" required>
                        <option selected disabled value="">商品分類</option>
                        <?php foreach ($category_all_row as $r) : ?>
                            <option value="<?= $r['category_id'] ?>"><?= $r['category_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <!-- 價格 -->
                <div class="m-3">
                    <label for="price" class="form-label">商品單價</label>
                    <input type="number" class="form-control" name="price" id="price" required>

                    <div class="invalid-feedback">
                        請輸入商品單價
                    </div>
                </div>



                <!-- 配件 -->
                <div class="m-3">
                    <label for="components" class="form-label">配件</label>
                    <input type="text" class="form-control" name="components" id="components" required>

                    <div class="invalid-feedback">
                        請輸入遊戲配件
                    </div>
                </div>

                <!-- 玩家人數 -->
                <div class="m-3">
                    <label for="players" class="form-label">玩家人數</label>
                    <input type="text" class="form-control" name="players" id="players" required>

                    <div class="invalid-feedback">
                        請輸入玩家人數
                    </div>
                </div>

                <!-- 遊戲時長 -->
                <div class="m-3">
                    <label for="duration" class="form-label">遊戲時長</label>
                    <input type="number" class="form-control" name="duration" id="duration" required>

                    <div class="invalid-feedback">
                        請輸入遊戲時長
                    </div>
                </div>

                <!-- 建議年齡 -->
                <div class="m-3">
                    <label for="age" class="form-label">建議遊玩年齡</label>
                    <input type="text" class="form-control" name="age" id="age" required>

                    <div class="invalid-feedback">
                        請輸入建議遊玩年齡
                    </div>
                </div>

                <!-- 狀態   -->
                <div class="m-3">
                    <label for="status" class="form-label">商品狀態</label>



                    <select name="status" id="status" class="form-select" aria-label="Default select example" required>

                        <option selected disabled value="">狀態</option>
                        <?php foreach ($status as $r) : ?>

                            <option value="<?= $r['status_id'] ?>"><?= $r['status'] ?>
                            </option>

                        <?php endforeach; ?>

                    </select>

                    <div class="invalid-feedback">
                        請輸入商品狀態
                    </div>
                </div>

                <!-- 欄位新增end -->

                <!-- 輸入商品介紹 -->
                <div class="m-3">
                    <label for="summary" class="form-label">商品介紹</label>
                    <textarea class="form-control" id="summary" name="summary" rows="10"></textarea>
                </div>

                <!-- 商品圖片 -->
                <div class="m-3">

                    <div class="btn btn-warning" onclick="photos.click()">點選上傳一張圖片</div>

                    <div hidden>
                        <input type="file" id="photos" name="photos[]" multiple accept="image/*" onchange="uploadFile()" />

                    </div>


                    <div class="show_img"></div>

                </div>



                <div class="text-end">
                    <input class="m-3 btn btn-primary" type="submit" value="上架商品">
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

    // TODO 圖片的JS
    const container = document.querySelector(".show_img");

    function uploadFile() {
        const fd = new FormData(document.form1);

        fetch("api/add-photos-api.php", {
                method: "POST",
                body: fd, // enctype="multipart/form-data"
            })
            .then((r) => r.json())
            .then((data) => {
                console.log({
                    data
                });
                if (data.success && data.files.length) {
                    let str = "";
                    for (let i of data.files) {
                        str += `
                        <input type="hidden" name="product_img" value="${data.newId}">
            <div class="my-card">
              <img
                src="./imgs/${i}"
                alt=""
              />
            </div>
            `;
                    }
                    container.innerHTML = str;

                }
            });
    }

    // 圖片end

    // 取得參照
    const nameField = document.form1.product_name;
    const priceField = document.form1.price;
    const cateField = document.form1.category_id;
    const cmpField = document.form1.components;
    const playersField = document.form1.players;
    const durField = document.form1.duration;
    const ageField = document.form1.age;





    const sendData = e => {
        e.preventDefault();

        let isPass = true; // 表單有沒有通過檢查

        if (nameField.value.length < 2) {
            isPass = false;
        }
        if (Number(priceField.value) < 1) {
            isPass = false;
        }
        if (cateField.value === '') {
            isPass = false;

        }

        if (cmpField.value.length < 2) {
            isPass = false;
        }
        if (playersField.value.length < 2) {
            isPass = false;
        }
        if (Number(durField.value) < 2) {
            isPass = false;
        }
        if (ageField.value.length < 2) {
            isPass = false;
        }




        if (isPass) {
            const fd = new FormData(document.form1);
            fetch('api/add-api.php', {
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