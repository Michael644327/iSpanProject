<?php
// include __DIR__ . '/../basic-url.php';
if (!isset($_SESSION)) {
    session_start();
}
// if ($_SERVER['REQUEST_URI'] !== '/iSpanproject/index/index_.php') {
//     if (!isset($_SESSION['admin'])) {
//         header('Location: ../index/index_.php');
//         exit;
//     }
// }


?>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= isset($title) ? "$title | 密室逃脫" : '塊陶啊' ?></title>
    <link rel="icon" type="image/x-icon" href="../imgs/favicon.ico">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- JQuery -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- 套版的navbar要加的 -->
    <link href="../css/styles.css" rel="stylesheet" />
    <link rel="stylesheet" href="../css/order-list.css">
    <style>
        .alert.alert-danger.opacity-0 {
            transition: all 1s ease-out;
        }
    </style>

</head>

<body>



    <div class="modal fade" id="loginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title px-4">登入</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body my-3">

                    <form name="loginForm" id="loginForm" onsubmit="loginData(event)" class="px-4">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">帳號</label>
                            <input type="text" class="form-control" name="account">
                            <div class="form-text"></div>
                        </div>
                        <div class="mb-">
                            <label for="exampleInputPassword1" class="form-label">密碼</label>
                            <input type="password" class="form-control" name="password">
                            <div class="form-text"></div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="alert alert-danger opacity-0 m-0 w-50 text-center" role="alert" style="transition: all 500ms ease-out;">
                                　
                            </div>
                            <div>
                                <button type="button" class="btn btn-secondary me-3" data-bs-dismiss="modal">關閉</button>
                                <button type="submit" class="btn btn-primary">登入</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>


    <script>
        const loginData = function(e) {
            e.preventDefault();
            let loginForm = document.querySelector('#loginForm')
            let {
                account: accountEl,
                password: passwordEl,
            } = loginForm;
            const fields = [accountEl, passwordEl];

            for (let el of fields) {
                el.style.border = '1px solid #CCC';
                el.nextElementSibling.innerHTML = '';
            }

            let isPass = true;
            if (accountEl.value.length == 0) {
                isPass = false;
                accountEl.style.border = '1px solid red';
                accountEl.nextElementSibling.innerHTML = '請填寫帳號';
            }
            if (passwordEl.value.length == 0) {
                isPass = false;
                passwordEl.style.border = '1px solid red';
                passwordEl.nextElementSibling.innerHTML = '請填寫密碼';
            }

            if (isPass) {
                let sendData = new FormData(document.loginForm); // 沒有外觀的表單物件



                fetch(`../parts/login_api.php`, {
                    method: 'POST',
                    body: sendData,
                }).then(r => r.json()).then(data => {
                    let failureInfo = document.querySelector('#loginForm .alert');
                    failureInfo.innerHTML = '       ';

                    if (data.success) {
                        window.location.reload();
                    } else {
                        failureInfo.innerHTML = data.error;
                    }
                    failureInfo.classList.replace('opacity-0', 'opacity-100');
                    setTimeout(function() {
                        failureInfo.classList.replace('opacity-100', 'opacity-0');
                    }, 3000);
                });
            };
        }
    </script>