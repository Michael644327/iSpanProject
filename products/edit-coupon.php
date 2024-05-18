<?php include __DIR__ . '/../parts/html-head.php' ?>
<?php include 'components/navbar.php' ?>

<div class="container mt-5 py-2">
    <div class="row mt-5">
        <div class="col-10 col-lg-6 mx-auto">
            <h2 class="text-center p-3 mt-3"><i class="fa-solid fa-ghost"></i></i>編輯優惠券</h2>

            <!-- 還沒做 -->

            <form name="form1">



                <form name="form1" class="needs-validation" novalidate onsubmit="sendData(event)">

                    <!-- SELECT `coupon_id`, `coupon_name`, `discount`, `category_id`, `user_group`, `total_quantity`, `expiry_date`, `created_at`, `last_modified_by`, `last_modified_at` FROM `coupon` -->

                    <!-- 優惠名稱 -->
                    <div class="m-3">
                        <label for="coupon_id" class="form-label">商品名稱</label>
                        <input type="text" id="coupon_id" name="coupon_id" class="form-control" required>
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





                    <butto class="btn btn-primary" type="submit">編輯優惠券</butto>


                </form>
        </div>
    </div>


</div>
</div>


<script src="coupon/coupon.js"></script>
<?php include __DIR__ . '/../parts/scripts.php' ?>
<?php include 'components/foot.php' ?>