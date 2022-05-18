<!-- Start Top Header Bar -->
<section>
    <div class="container">
        <div class="row align-items-center my-5">
            <div class="col-12 col-sm-4 mb-3">
                <div class="logo text-center text-sm-start">
                    <a href="?page=home">
                        <h3 class="logo">UMEPLUS</h3>
                    </a>
                </div>
            </div>
            <div class="col-12 col-sm-8">
                <div class="row">
                    <div class="col-12 d-flex">
                        <nav class="navbar navbar-expand-lg d-none d-sm-block ms-sm-auto">
                            <ul class="navbar-nav">
                                <li class="dropdown"><a class="nav-link text-dark" href="?page=shop"><?php echo $l_product ?></a></li>
                                <li class="dropdown"><a class="nav-link text-dark" href="?page=package"><?php echo $l_package ?></a></li>
                                <li class="dropdown"><a class="nav-link text-dark" href="?page=thread"><?php echo $l_thread ?></a></li>
                                <!--<li class="dropdown mb-3"><a class="nav-link text-dark" href="?page=track">ตรวจสอบคำสั่งซื้อ</a></li>-->
                                <li class="dropdown"><a class="nav-link text-dark" href="?page=contact"><?php echo $l_contact ?></a></li>
                            </ul>
                        </nav>
                        <div class="d-sm-none d-block">
                            <a data-bs-toggle="offcanvas" href="#menu_canvas" role="button" aria-controls="offcanvasExample" title="Menu">
                                <i class="bx bx-menu" style="font-size: 30px;"></i>
                            </a>
                        </div>
                        <div class="vr mx-3 d-none d-sm-block"></div>
                        <?php if (isset($data_check_login)) { 

                            $query = mysqli_query($connect, "SELECT * FROM system_cart WHERE (cart_member_id = '$buyer_id')");
                            $cart  = mysqli_num_rows($query);
                            $cart_canvas= ($cart != 0) ? "#cart_canvas" : false;
                            ?>
                            <a data-bs-toggle="offcanvas" href="<?php echo $cart_canvas ?>" role="button" aria-controls="offcanvasExample" class="d-flex align-items-center me-sm-3 ms-auto ms-sm-0">
                                <?php echo $l_cart ?>
                                <i class="tf-ion-android-cart"></i>
                                <?php echo $cart != 0 ? "<span class='badge rounded-pill bg-danger ms-1'>$cart</span>" : false; ?>

                            </a>
                            <div class="vr mx-3 d-block d-sm-none"></div>
                            <a data-bs-toggle="offcanvas" href="#profile_canvas" role="button" aria-controls="offcanvasExample" class="d-flex align-items-center">
                                <?php echo $buyer_name ?>
                                <i class="bx bxs-user ms-1" style="font-size: 20px;"></i>
                                <?php
                                $sql = "SELECT * FROM system_contact WHERE contact_type NOT IN (0, 2) AND (contact_buyer = '$buyer_id') AND (contact_status = 0)";
                                echo badge ($connect, $sql, 0);
                                ?>
                            </a>
                        <?php } else { ?>
                            <a href="visitor_login.php" class="d-flex align-items-center">
                            <?php echo $l_login ?> <i class="bx bx-log-in" style="font-size: 20px;"></i>
                            </a>   
                        <?php } ?>              
                    </div>
                </div>
            </div>
        </div>

    </div>
</section><!-- End Top Header Bar -->
<hr class="d-block d-sm-none">

<!--cart-->
<div class="offcanvas offcanvas-end" tabindex="-1" id="cart_canvas" aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasExampleLabel"><?php echo $l_cart ?></h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <?php
        $query = mysqli_query($connect, "SELECT system_cart.*, system_product.*
        FROM system_cart
        INNER JOIN system_product ON (system_cart.cart_product_id = system_product.product_id)
        WHERE (cart_member_id = '$buyer_id')");
        $count_cart = mysqli_num_rows($query);
        $total      = 0;
        if ($count_cart > 0) { ?>
            <div class="">
                <?php while ($data = mysqli_fetch_array($query)) {
                    
                    $quantity  = $data['cart_product_amount'];
                    $price     = $data['product_price'] * $quantity;
                    $total     = $total + $price;

                    $product_id     = $data['product_id'];
                    $product_name   = $data['product_name'];
                    $cart_id        = $data['cart_id'];
                    $image          = $data['product_image_cover'] != '' ? $data['product_image_cover'] : "dashboard/assets/images/etc/example.png";

                    ?>
                    <div class="position-relative">
                        <div class="row">
                            <div class="col-4 ms-auto">
                                <a class="pull-left" href="?page=product_single&product_id=<?php echo $product_id ?>">
                                    <img src="<?php echo $image ?>" alt="Image Cover" class="img-thumbnail border border-1">
                                </a>
                            </div>
                            <div class="col-8 text-start">
                                <h6 class="mb-0"><a href="?page=product_single&product_id=<?php echo $product_id ?>"><?php echo $product_name ?></a></h6>
                                <div class="text-secondary mb-1">
                                    <span><?php echo $quantity ?> x</span>
                                    <span><?php echo number_format($data['product_price']) ?></span>
                                </div>
                                <h6><?php echo number_format($price) . $l_bath ?></h6>
                                <a href="dashboard/process/setting_visitor_buy.php?action=delete_cart&cart_id=<?php echo $cart_id ?>" class="text-decoration-underline small text-danger"><?php echo $l_delete ?></a>
                            </div>
                        </div>
                    </div>
                    <hr>
                <?php } ?>
                <div class="cart-summary">
                    <span><?php echo $l_invoice_totprice ?></span>
                    <span class="total-price"><?php echo number_format($total) . $l_bath ?></span>
                </div>
                <ul class="text-center cart-buttons">
                    <li><a href="?page=cart" class="btn btn-small"><?php echo $l_detail ?></a></li>
                    <li><a href="?page=checkout" class="btn btn-small btn-solid-border"><?php echo $l_pay ?></a></li>
                </ul>
            </div>
        <?php } ?>
    </div>
</div>

<!--profile-->
<div class="offcanvas offcanvas-end" tabindex="-1" id="profile_canvas" aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title d-flex align-items-center" id="offcanvasExampleLabel">
            <i class="bx bxs-user me-3" style="font-size: 25px;"></i>
            <?php echo $data_check_login['buyer_name']; ?>
        </h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <form action="" method="get">
            <input type="hidden" name="page" value="shop">
            <input type="text" name="product_name" value="<?php echo isset($_GET['product_name']) ? $_GET['product_name'] : false; ?>" class="form-control" placeholder="ค้นหาสินค้า">
        </form>
        <ul class="list-group list-group-flush text-start mt-5">
            <li class="mb-3"><a href="?page=profile"><?php echo $l_profile ?></a></li>
            <li class="mb-3"><a href="?page=profile&action=order"><?php echo $l_order ?></a></li>
            <li class="mb-3"><a href="?page=message">
            <?php 

                echo $l_inbox;
                $sql = "SELECT * FROM system_contact WHERE contact_type NOT IN (0, 2) AND (contact_buyer = '$buyer_id') AND (contact_status = 0)";
                echo badge ($connect, $sql, 0);
            
            ?>
            </a></li>
            <?php if ($system_lang == 1) { ?> 

                <li class="dropdown">
                    <a class="dropdown-toggle bg-transparent" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false"><?php echo $l_lang ?></a>
                    <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="dashboard/process/setting_buyer.php?action=change_lang&lang=0&buyer_id=<?php echo $buyer_id ?>">Thai (ภาษาไทย) <?php echo $lang == 0 ? "<i class='bx bx-check ms-3 font-22 text-primary'></i>" : false ?></a></li>
                    <li><a class="dropdown-item" href="dashboard/process/setting_buyer.php?action=change_lang&lang=1&buyer_id=<?php echo $buyer_id ?>">English <?php echo $lang == 1 ? "<i class='bx bx-check ms-3 font-22 text-primary'></i>" : false ?></a></li>
                    </ul>
                </li>

            <?php } ?>
            <li class="mb-3"><hr></li>
            <li><a href="?page=log_out"><?php echo $l_logout ?></a></li>
        </ul>
    </div>
</div>

<!--menu-->
<div class="offcanvas offcanvas-start" tabindex="-1" id="menu_canvas" aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasExampleLabel"><?php echo $l_menu ?></h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="list-group list-group-flush text-start mt-5">
            <li class="mb-3"><a href="index.php"><?php echo $l_index ?></a></li>
            <li class="mb-3"><a href="?page=shop"><?php echo $l_product ?></a></li>
            <li class="mb-3"><a href="?page=package"><?php echo $l_package ?></a></li>
            <li class="mb-3"><a href="?page=thread"><?php echo $l_thread ?></a></li>
            <li class="mb-3"><a href="?page=contact"><?php echo $l_contact ?></a></li>
        </ul>
    </div>
</div>