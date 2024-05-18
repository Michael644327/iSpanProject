<?php

$title = '新增主題';
$pageName = 'theme_add';
?>

<?php include __DIR__ . '/../parts/html-head.php' ?>

<style>
    form .mb-4 .form-text {
        color: tomato;
        font-weight: 800;
    }
</style>


<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form name="form1" class="p-3" onsubmit="sendData(event)">
                        <div class="mb-4 col-5">
                            <label for="theme_name" class="form-label fw-bold">主題名稱</label>
                            <input type="text" class="form-control" id="theme_name" name="theme_name">
                            <div class="form-text"></div>
                        </div>

                        <div class="mb-4 col-5 ">
                            <label for="theme_img" class="form-label fw-bold">主題圖片</label>
                            <input type="file" class="form-control" name="uploadFile[]" multiple="multiple" id="theme_img">
                            <?php if (!empty($row['theme_img'])) : ?>
                                <img class="w-100 mt-2 rounded-3" src="imgs/<?= $row['theme_img'] ?>" alt="Uploaded Image">
                            <?php endif; ?>
                        </div>

                        <div class="mb-4 col-8 mt-5">
                            <label for="theme_desc" class="form-label fw-bold">主題描述</label>
                            <textarea class="form-control" id="theme_desc" name="theme_desc" cols="30" rows="3" placeholder="請輸入250字以內"></textarea>
                            <div class="form-text"></div>
                        </div>

                        <div class="mb-4 col-5">
                            <label for="price" class="form-label fw-bold">價錢</label>
                            <input type="text" class="form-control" id="price" name="price" placeholder="請輸入價錢">
                            <div class="form-text"></div>
                        </div>

                        <div class="row">
                            <div class="mb-4 col-5">
                                <label for="difficulty" class="form-label fw-bold">難度</label>
                                <select class="form-select" aria-label="Default select example" id="difficulty" name="difficulty">
                                    <option selected>難度</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>
                            <div class="mb-4 col-5">
                                <label for="suitable_players" class="form-label fw-bold">適合遊玩人數</label>
                                <input type="text" class="form-control" id="suitable_players" name="suitable_players" placeholder="請輸入 _ ~ _ 人">
                                <div class="form-text"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-4 col-5">
                                <label for="start_time" class="form-label fw-bold">開始時間</label>
                                <input type="text" class="form-control" id="start_time" name="start_time" placeholder="ex. 早上 9:00">
                                <div class="form-text"></div>
                            </div>

                            <div class="mb-4 col-5">
                                <label for="end_time" class="form-label fw-bold">結束時間</label>
                                <input type="text" class="form-control" id="end_time" name="end_time" placeholder="ex. 晚上 21:00">
                                <div class="form-text"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-4 col-5">
                                <label for="theme_time" class="form-label fw-bold">主題時長</label>
                                <select class="form-select" aria-label="Default select example" id="theme_time" name="theme_time">
                                    <option selected>時長</option>
                                    <option value="60">60</option>
                                    <option value="90">90</option>
                                    <option value="120">120</option>
                                </select>
                            </div>

                            <div class="mb-4 col-5">
                                <label for="intervals" class="form-label fw-bold">間隔時間</label>
                                <select class="form-select" aria-label="Default select example" id="intervals" name="intervals">
                                    <option selected>間隔</option>
                                    <option value="30">30</option>
                                    <option value="60">60</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="mb-4 col-5">
                                <label for="start_date" class="form-label fw-bold">開始日</label>
                                <input type="date" class="form-control" id="start_date" name="start_date">
                                <div class="form-text"></div>
                            </div>
                            <div class="mb-4 col-5">
                                <label for="end_date" class="form-label fw-bold">結束日</label>
                                <input type="date" class="form-control" id="end_date" name="end_date">
                                <div class="form-text"></div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end me-3">
                            <button type="submit" class="btn btn-primary">新增</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal bt 彈跳視窗-->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">新增成功</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-success" role="alert">
                    資料新增成功 d(`･∀･)b
                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-primary" onclick="location.href='theme_list.php'">到主題頁</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">繼續新增</button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../parts/scripts.php' ?>



<script>
    document.getElementById('theme_img').addEventListener('change', function(event) {
        var file = event.target.files[0]; // 获取选择的文件
        var reader = new FileReader(); // 创建一个文件读取器

        reader.onload = function(e) {
            document.querySelector('.w-100').setAttribute('src', e.target.result); // 将新选择的图片设置为img的src属性值
        };

        reader.readAsDataURL(file); // 读取文件
    });


    const nameField = document.getElementById('theme_name');
    const imgField = document.getElementById('theme_img');
    const descField = document.getElementById('theme_desc');
    const priceField = document.getElementById('price');
    const diffField = document.getElementById('difficulty');
    const playersField = document.getElementById('suitable_players');
    const startTimeField = document.getElementById('start_time');
    const endTime = document.getElementById('end_time');
    const themeTime = document.getElementById('theme_time');
    const intervals = document.getElementById('intervals');
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');

    console.log(document.form1.name);

    const sendData = e => {
        e.preventDefault(); // 不要讓 form1 以傳統的方式送出

        nameField.style.border = '1px solid #CCCCCC';
        nameField.nextElementSibling.innerText = '';

        imgField.style.border = '1px solid #CCCCCC';

        descField.style.border = '1px solid #CCCCCC';
        descField.nextElementSibling.innerText = '';

        priceField.style.border = '1px solid #CCCCCC';
        priceField.nextElementSibling.innerText = '';

        diffField.style.border = '1px solid #CCCCCC';

        playersField.style.border = '1px solid #CCCCCC';
        playersField.nextElementSibling.innerText = '';

        startTimeField.style.border = '1px solid #CCCCCC';
        startTimeField.nextElementSibling.innerText = '';

        endTime.style.border = '1px solid #CCCCCC';
        endTime.nextElementSibling.innerText = '';

        themeTime.style.border = '1px solid #CCCCCC';
        intervals.style.border = '1px solid #CCCCCC';
        startDate.style.border = '1px solid #CCCCCC';
        endDate.style.border = '1px solid #CCCCCC';

        // TODO: 欄位資料檢查

        let isPass = true; // 表單有沒有通過檢查
        if (nameField.value.length < 1) {
            isPass = false;
            nameField.style.border = '1px solid tomato';
            nameField.nextElementSibling.innerText = '請填寫主題名稱';
        }
        if (imgField.value.length === 0) {
            isPass = false;
            imgField.style.border = '1px solid tomato';
        }
        if (descField.value.length < 1) {
            isPass = false;
            descField.style.border = '1px solid tomato';
            descField.nextElementSibling.innerText = '請填寫描述';
        } else if (descField.value.length > 250) {
            isPass = false;
            descField.style.border = '1px solid tomato';
            descField.nextElementSibling.innerText = '超過字數限制';
        }
        if (priceField.value.length < 1) {
            isPass = false;
            priceField.style.border = '1px solid tomato';
            priceField.nextElementSibling.innerText = '請填寫價錢';
        }

        // 檢查是否已經存在警告訊息元素
        var existingWarningMessage = diffField.parentNode.querySelector('.warning-message');

        if (diffField.value === '難度') {
            isPass = false;
            diffField.style.border = '1px solid tomato';

            // 如果不存在警告訊息元素，則創建並插入
            if (!existingWarningMessage) {
                var warningMessage = document.createElement('div');
                warningMessage.textContent = '請選擇一個不同的難度等級!';
                warningMessage.style.fontSize = '13px';
                warningMessage.style.fontWeight = 'bold';
                warningMessage.style.marginTop = '5px';
                warningMessage.style.color = 'tomato';
                warningMessage.className = 'warning-message'; // 添加一個唯一的類名

                // 插入警告訊息元素到 diffField 下面
                diffField.parentNode.insertBefore(warningMessage, diffField.nextSibling);
            }
        } else {
            // 如果不再是 '難度'，且存在警告訊息元素，則移除
            if (existingWarningMessage) {
                existingWarningMessage.parentNode.removeChild(existingWarningMessage);
            }
        }

        if (playersField.value.length < 1) {
            isPass = false;
            playersField.style.border = '1px solid tomato';
            playersField.nextElementSibling.innerText = '請填寫遊玩人數 _ ~ _ 人';
        }
        if (startTimeField.value.length < 1) {
            isPass = false;
            startTimeField.style.border = '1px solid tomato';
            startTimeField.nextElementSibling.innerText = '請填寫開始時間';
        }
        if (endTime.value.length < 1) {
            isPass = false;
            endTime.style.border = '1px solid tomato';
            endTime.nextElementSibling.innerText = '請填寫結束時間';
        }

        // 檢查是否已經存在警告訊息元素
        var existingWarningMessage = themeTime.parentNode.querySelector('.warning-message');

        if (themeTime.value === '時長') {
            isPass = false;
            themeTime.style.border = '1px solid tomato';

            // 如果不存在警告訊息元素，則創建並插入
            if (!existingWarningMessage) {
                var warningMessage = document.createElement('div');
                warningMessage.textContent = '請選擇時長';
                warningMessage.style.fontSize = '13px';
                warningMessage.style.fontWeight = 'bold';
                warningMessage.style.marginTop = '5px';
                warningMessage.style.color = 'tomato';
                warningMessage.className = 'warning-message'; // 添加一個唯一的類名

                // 插入警告訊息元素到 下面
                themeTime.parentNode.insertBefore(warningMessage, themeTime.nextSibling);
            }
        } else {
            // 如果不再是 '時長'，且存在警告訊息元素，則移除
            if (existingWarningMessage) {
                existingWarningMessage.parentNode.removeChild(existingWarningMessage);
            }
        }

        // 檢查是否已經存在警告訊息元素
        var existingWarningMessage = intervals.parentNode.querySelector('.warning-message');

        if (intervals.value === '間隔') {
            isPass = false;
            intervals.style.border = '1px solid tomato';

            // 如果不存在警告訊息元素，則創建並插入
            if (!existingWarningMessage) {
                var warningMessage = document.createElement('div');
                warningMessage.textContent = '請選擇間隔時間';
                warningMessage.style.fontSize = '13px';
                warningMessage.style.fontWeight = 'bold';
                warningMessage.style.marginTop = '5px';
                warningMessage.style.color = 'tomato';
                warningMessage.className = 'warning-message'; // 添加一個唯一的類名

                // 插入警告訊息元素到 diffField 下面
                intervals.parentNode.insertBefore(warningMessage, intervals.nextSibling);
            }
        } else {
            // 如果不再是 '難度'，且存在警告訊息元素，則移除
            if (existingWarningMessage) {
                existingWarningMessage.parentNode.removeChild(existingWarningMessage);
            }
        }

        // 檢查是否已經存在警告訊息元素
        var existingWarningMessage = startDate.parentNode.querySelector('.warning-message');

        if (startDate.value === '') {
            // 日期是必填欄位，如果沒有輸入則顯示錯誤
            isPass = false;
            startDate.style.border = '1px solid tomato';
            startDate.nextElementSibling.innerText = '請選擇開始日期';
        }

        // 檢查是否已經存在警告訊息元素
        var existingWarningMessage = startDate.parentNode.querySelector('.warning-message');

        if (endDate.value === '') {
            // 日期是必填欄位，如果沒有輸入則顯示錯誤
            isPass = false;
            endDate.style.border = '1px solid tomato';
            endDate.nextElementSibling.innerText = '請選擇結束日期';
        }


        // 有通過檢查, 才要送表單
        if (isPass) {
            const fd = new FormData(document.form1); // 沒有外觀的表單物件

            fetch('theme_add_api.php', {
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