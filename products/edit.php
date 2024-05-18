<?php
require __DIR__ . '/../config/pdo-connect.php';  // 引入資料庫設定
require __DIR__ . '/db_select/select_status.php'; //搜尋商品狀態


// 搜尋分類
include 'db_select/select_category.php';
include 'db_select/select_product.php';



// 確認product_id有沒有值，如果有轉成數字
$product_id = isset($_GET['product_id']) ? intval($_GET['product_id']) : 0;
if ($product_id < 1) {
    header('Location: index.html');
    exit;
}

// 有值則繼續執行SQL
$sql = "SELECT * FROM product_management WHERE product_id={$product_id}";
$row = $pdo->query($sql)->fetch();

if (empty($row)) {
    header('Location: index.php');
    exit;
}

$sql = "SELECT * FROM `product_img`
JOIN `product_management`
on `product_img` = `img_id`
WHERE product_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$product_id]);
$product_img_row = $stmt->fetchAll();



?>
<?php include 'components/head.php'; ?>

<?php include 'components/navbar.php'; ?>


<!-- 編輯form -->
<div class="container mt-3 py-2">
    <div class="row mt-5">
        <div class="col-10 col-lg-6 mx-auto">
            <h2 class="text-center p-3 mt-3"><i class="fa-solid fa-ghost"></i></i>商品資料編輯</h2>

            <form name="form1" class="needs-validation" novalidate onsubmit="sendData(event)">

                <!-- ----id 隱藏欄位 可送出值---- -->
                <input type="hidden" name="product_id" value="<?= $row['product_id'] ?>">
                <!-- ----id disabled---- -->
                <div class="m-3">
                    <label for="product_id" class="form-label">商品編號</label>
                    <input type="text" id="product_id" class="form-control" disabled value="<?= $row['product_id'] ?>">
                </div>

                <!-- 商品名稱 -->
                <div class="m-3">
                    <label for="product_name" class="form-label">商品名稱</label>
                    <input type="text" id="product_name" name="product_name" class="form-control" required value="<?= $row['product_name'] ?>">
                    <div class="invalid-feedback">
                        此為必填欄位
                    </div>
                </div>
                <!-- 價格 -->
                <div class="m-3">
                    <label for="price" class="form-label">商品單價</label>
                    <input type="number" class="form-control" name="price" id="price" required value="<?= $row['price'] ?>">

                    <div class="invalid-feedback">
                        請輸入商品單價
                    </div>
                </div>

                <!-- 商品分類  -->
                <div class="m-3">
                    <label for="category_id" class="form-label">商品分類</label>
                    <select name="category_id" id="category_id" required class="form-select" aria-label="Default select example">

                        <?php foreach ($category_all_row as $r) :
                            if ($r['category_id'] == $row['category_id']) :
                        ?>
                                <option selected value="<?= $r['category_id'] ?>"><?= $r['category_name'] ?></option>
                            <?php else :  ?>
                                <option value="<?= $r['category_id'] ?>"><?= $r['category_name'] ?></option>
                            <?php endif;  ?>
                        <?php endforeach; ?>

                    </select>
                </div>

                <!-- 配件 -->
                <div class="m-3">
                    <label for="components" class="form-label">配件</label>
                    <input type="text" class="form-control" name="components" id="components" required value="<?= $row['components'] ?>">

                    <div class="invalid-feedback">
                        請輸入桌遊配件
                    </div>
                </div>

                <!-- 玩家人數 -->
                <div class="m-3">
                    <label for="players" class="form-label">玩家人數</label>
                    <input type="text" class="form-control" name="players" id="players" required value="<?= $row['players'] ?>">

                    <div class="invalid-feedback">
                        請輸入玩家人數
                    </div>
                </div>

                <!-- 遊戲時長 -->
                <div class="m-3">
                    <label for="duration" class="form-label">遊戲時長</label>
                    <input type="number" class="form-control" name="duration" id="duration" required value="<?= $row['duration'] ?>">

                    <div class="invalid-feedback">
                        請輸入遊戲時長
                    </div>
                </div>

                <!-- 建議年齡 -->
                <div class="m-3">
                    <label for="age" class="form-label">建議遊玩年齡</label>
                    <input type="text" class="form-control" name="age" id="age" required value="<?= $row['age'] ?>">

                    <div class="invalid-feedback">
                        請輸入建議遊玩年齡
                    </div>
                </div>

                <!-- 狀態  -->
                <div class="m-3">
                    <label for="status" class="form-label">商品狀態</label>
                    <select name="status" id="status" required class="form-select" aria-label="Default select example">


                        <?php foreach ($status as $r) :
                            if ($r['status_id'] == $row['status']) : ?>
                                <option selected value="<?= $r['status_id'] ?>"><?= $r['status'] ?>
                                </option>

                            <?php else : ?>
                                <option value="<?= $r['status_id'] ?>"><?= $r['status'] ?>
                                </option>
                            <?php endif; ?>

                        <?php endforeach; ?>

                    </select>

                </div>



                <!-- 輸入商品介紹 -->
                <div class="m-3">
                    <label for="summary" class="form-label">商品介紹</label>
                    <textarea class="form-control" id="summary" name="summary" id="exampleFormControlTextarea1" rows="10"><?= $row['summary']; ?>
                </textarea>
                </div>
                <!-- TODO 商品圖片上傳 -->
                <div class="m-3">

                    <div id="original_img">

                        <div id="original_inp">
                            <!-- 取得原本商品圖 -->
                            <?php foreach ($product_img_row as $r) : ?>
                                <input type="hidden" name="product_img" value="<?= $r['img_id'] ?>">

                                <img class="img" width="200px" id="<?= $r['img_id'] ?>" src="imgs/<?= $r['file_name'] ?>" alt="">

                            <?php endforeach; ?>

                        </div>
                    </div>


                    <div class="btn btn-warning" onclick="photos.click()">點選上傳一張圖片</div>

                    <div hidden>
                        <input type="file" id="photos" name="photos[]" multiple accept="image/*" onchange="uploadFile()" />

                    </div>


                    <!-- <div class="show_img"></div> -->

                </div>


                <!-- ----隱藏欄位 可送出值---- -->
                <!-- TODO 最後編輯者，目前代入固定值 -->
                <input type="hidden" name="last_modified_by" value="員工">



                <div class="text-end">
                    <input class="m-3 btn btn-primary" type="submit" value="完成編輯">
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

    // TODO 圖片的JS
    const container = document.querySelector("#original_img");

    function uploadFile() {
        const fd = new FormData(document.form1);

        fetch("api/edit-photos-api.php", {
                method: "POST",
                body: fd, // enctype="multipart/form-data"
            })
            .then((r) => r.json())
            .then((data) => {
                console.log({
                    data
                });
                $('#original_inp').remove();

                if (data.success && data.files.length) {
                    let str = "";
                    for (let i of data.files) {
                        str += ` 
                        <div id="original_inp">
                        <input type="hidden" name="product_img" value="${data.newId}">
                        <div class="my-card">
                            <img width="200px" src="./imgs/${i}" alt=""/>
                        </div>
                        </div>`;
                    }
                    container.innerHTML = str;
                }
            });
    }

    // 圖片end

    // 取得參照
    const nameField = document.form1.product_name;
    const priceField = document.form1.price;
    const cmpField = document.form1.components;
    const playersField = document.form1.players;
    const durField = document.form1.duration;
    const ageField = document.form1.age;



    const sendData = e => {
        e.preventDefault(); // 不要讓 form1 以傳統的方式送出

        let isPass = true;

        console.log(isPass)

        if (nameField.value.length < 2) {
            isPass = false;
            console.log(1)
        }
        if (Number(priceField.value) < 1) {
            isPass = false;
            console.log(2)
        }
        if (cmpField.value.length < 2) {
            isPass = false;
            console.log(3)
        }
        if (playersField.value.length < 1) {
            isPass = false;
            console.log(4)
        }
        if (Number(durField.value) < 2) {
            isPass = false;
            console.log(5)
        }
        if (ageField.value.length < 2) {
            isPass = false;
            console.log(6)
        }

        console.log(isPass)


        if (isPass) {
            const fd = new FormData(document.form1); // 沒有外觀的表單物件
            fetch('api/edit-api.php', {
                    method: 'POST',
                    body: fd, // Content-Type: multipart/form-data
                }).then(r => r.json())
                .then(data => {
                    // 如果資料編輯成功跳modal提示
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
    const myModal = new bootstrap.Modal('#staticBackdrop')
    const myModal2 = new bootstrap.Modal('#staticBackdrop2')
</script>


<?php include 'components/foot.php' ?>