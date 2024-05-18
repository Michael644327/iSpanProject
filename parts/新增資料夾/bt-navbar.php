<!-- 導覽列 -->
<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark sticky-top" style="z-index:2000;">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3" href="../index/index_.php">密室逃脫</a>
    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
            class="fas fa-bars"></i></button>
    <!-- Navbar Search-->

    <!-- <div class="sb-sidenav-footer"> -->

    <!-- </div> -->


    <!-- Navbar-->
    <ul class="navbar-nav ms-auto me-4 d-flex align-items-center">
        <li class="nav-item dropdown">
            <?php if (isset($_SESSION['admin'])): ?>
                <div class="navbar-brand ps-3">登入者：<?= $_SESSION['admin']['nickname'] ?></div>
            <?php else: ?>
                <div class="navbar-brand ps-3">請先登入 <i class="bi bi-caret-right-fill"></i></div>
            <?php endif ?>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
                aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <?php if (isset($_SESSION['admin'])): ?>
                    <!--有登入才顯示的-->
                    <li><a class="dropdown-item" href="../parts/logout.php">登出</a></li>
                <?php else: ?>
                    <!--沒分是否登入的-->
                    <li><a class="dropdown-item" href="#!" data-bs-toggle="modal" data-bs-target="#loginModal">登入</a></li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li><a class="dropdown-item" href="../parts/quick_login.php">快速登入</a></li>
                <?php endif ?>
            </ul>
        </li>
    </ul>
</nav>

<div id="layoutSidenav">
    <div id="layoutSidenav_nav" class="">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <a class="nav-link" href="../users/users.php">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-ghost"></i></i></div>
                        會員中心
                    </a>
                    <a class="nav-link" href="../orders/order-list.php">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-ghost"></i></i></div>
                        訂單列表
                    </a>

                    <!-- ----行程管理------ -->
                    <a class="nav-link collapsed" href="../themes/theme_list.php" data-bs-toggle="collapse"
                        data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-ghost"></i></i></div>
                        行程管理
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <!-- --------- -->
                    <div class="collapse" id="collapsePages" aria-labelledby="headingTwo"
                        data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="../themes/theme_list.php">行程列表</a>
                            <a class="nav-link" href="../themes/branch_list.php">分店管理</a>
                            <a class="nav-link" href="../themes/theme_list.php">主題管理</a>
                        </nav>
                    </div>
                    <!-- --------- -->

                    <!-- ----商品管理----- -->
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts"
                        aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-ghost"></i></i></div>
                        商品管理
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <!-- --------- -->
                    <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="../products/index.php">商品列表</a>
                            <a class="nav-link" href="../products/Warehousing.php">庫存管理</a>
                            <a class="nav-link" href="../products/coupon.php">優惠券管理</a>
                        </nav>
                    </div>
                    <!-- --------- -->

                    <a class="nav-link" href="../teams/teams.php">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-ghost"></i></i></div>
                        揪團系統
                    </a>

                </div>
            </div>



        </nav>
    </div>