<?php isset($_GET["status"]) ? alert ($_GET["status"], $_GET["message"], $lang) : false;

if (!isset($_GET['action']) || $_GET['action'] == 'search') {

    $sproduct_type_id   = isset($_GET['product_type_id']) ? $_GET['product_type_id'] : false;
    $sproduct_name      = isset($_GET['product_name'])    ? $_GET['product_name']    : false;
    $sproduct_type2     = isset($_GET['product_type_id']) ? $_GET['product_type_id'] : false;

    if ($page_type == 'admin.php') {

        $member_code = isset($_GET['member_code'])      ? $_GET['member_code']      : false;
        $shopping_id = isset($_SESSION['shopping_id'])  ? $_SESSION['shopping_id']  : false;

        if ($member_code != false) {
            $query = mysqli_query($connect, "SELECT * FROM system_member WHERE member_code = '$member_code' ");
        } else {
            $query = mysqli_query($connect, "SELECT * FROM system_member WHERE member_id = '$shopping_id' ");
        }
        $data = mysqli_fetch_array($query);
        if ($data) {
            $_SESSION['shopping_id'] = $data['member_id'];
            $member_id  = $_SESSION['shopping_id'];
            $title      = $l_buy_byadmin . " | " . $data['member_code'];
        } elseif (!isset($data)) {
            header('location:admin.php?page=shopping&action=admin');
        }
    } elseif ($page_type == 'member.php') {
        $title = $l_package;
    } 

    ?>
    <title><?php echo $title ?></title>
    <style type="text/css">
        .text {
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            /* number of lines to show */
            line-clamp: 2;
            -webkit-box-orient: vertical;
            min-height: 50px;
        }
    </style>
    <div class="card-title d-flex align-items-center mb-3">
        <div><i class="bx bx-store-alt me-1 font-22 text-primary"></i></div>
        <h5 class="mb-0 text-primary">
            <?php
            echo $title;
            if ($sproduct_type_id != false && $sproduct_type_id != 'all') {
                $query = mysqli_query($connect, "SELECT * FROM system_product_type WHERE product_type_id = '$sproduct_type_id' ");
                $data  = mysqli_fetch_array($query);
                echo " | " . $data['product_type_name'];
            }
            ?>
        </h5>
        <div class="ms-auto">
            <a href="<?php echo $page_type ?>?page=shopping&action=cart_setting" class="btn btn-primary btn-sm ms-auto"><?php echo $l_cart ?> <i class="bx bx-cart me-0"></i></a>
        </div>
    </div>
    <div class="row">
        <?php
        $where = "";
        if (isset($_GET['action'])) {
            $where  = " WHERE (product_name LIKE '%$sproduct_name%') 
                AND (product_type2 = '$sproduct_type2') ";

            if ($_GET['product_type_id'] != 'all') {
                $where .= " AND (product_type = '$sproduct_type_id')";
            }
        }
        $limit = " LIMIT $start, 52 ";
        $sql = "SELECT system_product.*, system_product_type.*
            FROM system_product
            INNER JOIN system_product_type ON (system_product.product_type = system_product_type.product_type_id)" . $where;
        $query = mysqli_query($connect, $sql . $limit) or die(mysqli_error($connect));
        $empty = mysqli_num_rows($query);
        if ($empty > 0) {
            while ($data = mysqli_fetch_array($query)) { 

                $product_id     = $data['product_id'];
                $product_name   = $data['product_name'];
                $product_title  = $data['product_type_name'] . " " . $data['product_code'];
                $product_price_member = number_format($data['product_price_member'], 2) . $l_bath;
                $product_point  = $data['product_point'] . $point_name;
                $product_type2  = $data['product_type2'];
                $cart_url       = "process/setting_buy.php?action=insert_cart&product_id=$product_id&page=$page_type&member_id=$member_id";
                $product_image_cover = $data['product_image_cover'] != '' ? "assets/images/products/" . $data['product_image_cover'] : "assets/images/etc/example_product.png" ;
                $product_url    = "<a href='$page_type?page=shopping&action=show_product&product_id=$product_id'>$product_name</a>";

                ?>
                <div class="col-12 col-sm-3 product-grid">
                    <div class="card p-0">
                        <img src="<?php echo $product_image_cover ?>" alt="ปกสินค้า" style="width:100%;height:250px;object-fit: cover;">
                        <div class="p-3">
                            <h5 class="text"><?php echo $product_url ?></h5>
                            <div class="mb-2 "><?php echo $product_title ?></div>
                            <table class="table table-borderless table-sm">
                                <tbody>
                                    <tr>
                                        <th><?php echo $l_price ?></th>
                                        <td><?php echo $product_price_member ?></td>
                                    </tr>
                                    <tr>
                                        <th><?php echo $l_point ?></th>
                                        <td><?php echo $product_point ?></td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="d-flex">
                                <a href="<?php echo $cart_url ?>" class="btn btn-success btn-sm me-1 ms-auto"><?php echo $l_pickpackage ?></a>
                            </div>
                        </div>
                    </div>
                </div>
        <?php } } else { echo "<div class='col-12 mx-auto'>$l_notfound</div>"; } ?>
    </div>

    <?php
    $url = "$page_type?page=shopping";
    pagination($connect, $sql, $perpage, $page_id, $url); ?>

    <!--start switcher-->
    <div class="switcher-wrapper">
        <div class="switcher-btn"> <i class='bx bx-search'></i>
        </div>
        <div class="switcher-body">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 text-uppercase"><?php echo $l_search ?></h5>
                <button type="button" class="btn-close ms-auto close-switcher" aria-label="Close"></button>
            </div>
            <hr />
            <form action="<?php echo $page_type ?>" method="get" class="row g-3">
                <input type="hidden" name="page" value="shopping">
                <div class="col-12">
                    <label class="form-label"><?php echo $l_product_type ?></label>
                    <select class="form-select" name="product_type_id" required>
                        <option value="all" selected><?php echo $l_all ?></option>
                        <?php
                        $query  = mysqli_query($connect, "SELECT * FROM system_product_type");
                        while ($data = mysqli_fetch_array($query)) { 
                            $product_type_id    = $data['product_type_id'];
                            $product_type_code  = $data['product_type_code'];
                            $product_type_name  = $data['product_type_name'];
                            echo "<option value='$product_type_id'>[$product_type_code] $product_type_name</option>";
                        } ?>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label"><?php echo $l_product_name ?></label>
                    <input type="text" name="product_name" class="form-control" placeholder="<?php echo $l_product_name ?>">
                </div>
                <div class="col-12 mt-5">
                    <button name="action" value="search" class="btn btn-primary btn-sm"><?php echo $l_search ?></button>
                    <a <?php echo "href='$page_type?page=shopping'" ?> class="btn btn-secondary btn-sm"><?php echo $l_cancel ?></a>
                </div>
            </form>
        </div>
    </div>

<?php } elseif (isset($_GET['action']) && $_GET['action'] == 'cart_setting') {
    
    if ($page_type == "admin.php") {
        $member_id = $_SESSION['shopping_id'];
    } 

    ?>
    <title><?php echo $l_cart ?></title>
    <div class="card-title d-flex align-items-center">
        <div><i class="bx bx-cart me-1 font-22 text-primary"></i></div>
        <h5 class="mb-0 text-primary"><?php echo $l_cart ?></h5>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page"><a href="<?php echo $page_type ?>?page=shopping"><?php echo $l_package ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $l_cart ?></li>
        </ol>
    </nav>
    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body p-1 pt-3 pb-3 p-sm-5">
            <div class="card-title d-flex align-items-center">
                <div><i class="bx bx-cart me-1 font-22 text-primary"></i></div>
                <h5 class="mb-0 text-primary"><?php echo $l_cart ?></h5>
            </div>
            <hr>
            <div class="row">
                <?php
                $sql   = mysqli_query($connect, "SELECT system_cart.*, system_member.*, system_product.*
                    FROM system_cart
                    INNER JOIN system_member ON (system_cart.cart_member_id = system_member.member_id)
                    INNER JOIN system_product ON (system_cart.cart_product_id = system_product.product_id)
                    WHERE (cart_member_id = '$member_id')");
                $empty = mysqli_num_rows($sql);
                if ($empty > 0) {

                    $checkbill_url  = "$page_type?page=shopping&action=check_bill";
                    $checkbill_class= "btn btn-primary btn-sm ms-auto";

                    while ($data = mysqli_fetch_array($sql)) {
                        
                        $price_total = number_format($data['cart_product_amount'] * $data['product_price_member'], 2) . $l_bath;
                        $point_total = number_format($data['cart_product_amount'] * $data['product_point'], 2) . $point_name;
                        $product_image_cover    = $data['product_image_cover'] != '' ? "assets/images/products/" . $data['product_image_cover'] : "assets/images/etc/example_product.png" ;
                        $cart_id                = $data['cart_id'];
                        $cart_product_amount    = $data['cart_product_amount'];
                        $product_amount         = $data['product_amount'];

                        if ($data['cart_product_amount'] == 1) {
                            $minus_url  = "#";
                            $minus_class= "btn btn-outline-secondary";
                        } else {
                            $minus_url= "process/setting_buy.php?action=update_cart&cart_id=$cart_id&update=minus&page=$page_type";
                            $minus_class= "btn btn-outline-primary";
                        }

                        if ($system_stock == 1 && $product_amount < $cart_product_amount) {
                            $plus_url   = "#";
                            $plus_class = "btn btn-outline-secondary";
                        } else {
                            $plus_url= "process/setting_buy.php?action=update_cart&cart_id=$cart_id&update=plus&page=$page_type";
                            $plus_class = "btn btn-outline-primary"; 
                        }
                        ?>
                        <div class="col-12">
                            <div class="card p-5 position-relative border border-1 border-primary">
                                <div class="row">
                                    <div class="col-12 col-sm-3 mb-3 mb-sm-0">
                                        <img src="<?php echo $product_image_cover ?>" alt="ปกสินค้า" style="width:100%;object-fit: cover;">
                                    </div>
                                    <div class="col-12 col-sm-9 ps-sm-5">
                                        <h5><?php echo $data['product_name'] ?></h5>
                                        <p><?php echo $data['product_code'] ?></p>
                                        <hr>
                                        <h6 class="text-danger"><?php echo $price_total ?></h6>
                                        <p><?php echo $point_total ?></p>
                                        <label class="form-label mt-1 mt-sm-5"><?php echo $l_quantity ?></label><br>
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <a href="<?php echo $minus_url ?>" class="<?php echo $minus_class ?>"><?php echo $l_minus ?></a>
                                            <form action="process/setting_buy.php" method="get" id="form_<?php echo $cart_id ?>">
                                                <input type="hidden" name="page" value="<?php echo $page_type ?>">
                                                <input type="hidden" name="cart_id" value="<?php echo $cart_id ?>">
                                                <input type="hidden" name="action" value="update_cart">
                                                <input type="number" name="cart_product_amount" value="<?php echo $cart_product_amount ?>" placeholder="1" class="form-control" style="width: 70px;" min="1" max="100" onfocusout="submitform('form_<?php echo $cart_id ?>')">
                                            </form>
                                            <a href="<?php echo $plus_url ?>" class="<?php echo $plus_class ?>"><?php echo $l_plus ?></a>
                                        </div>
                                    </div>
                                </div>
                                <div class="position-absolute top-0 end-0 m-3">
                                    <a <?php echo "href='process/setting_buy.php?action=delete_cart&cart_id=$cart_id&page=$page_type'" ?> >
                                        <i class="bx bx-trash me-1 font-22 text-danger"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                <?php } } else { 
                    $checkbill_url  = "$page_type?page=shopping&action=check_bill";
                    $checkbill_class= "btn btn-secondary btn-sm ms-auto";
                    echo "<p>$l_notfound</p>"; 
                } ?>
            </div>
            <div class="d-flex">
                <a href="<?php echo $checkbill_url ?>" class="<?php echo $checkbill_class ?>"><?php echo $l_submit ?></a>
            </div>
        </div>
    </div>
    <script>
        function submitform(id) {
            var a = document.getElementById(id).submit();
        }
    </script>

<?php } elseif (isset($_GET['action']) && $_GET['action'] == 'check_bill') {

    if ($page_type == 'admin.php') {
        $member_id   = $_SESSION['shopping_id'];
        $buy_default = 2;
        $buy_ewallet = 3;
    } else {
        $buy_default = 0;
        $buy_ewallet = 1;
    } 
    $cancel_url = "process/setting_buy.php?action=cancel_cart&member_id=$member_id&page=$page_type";
    $query      = mysqli_query($connect, "SELECT system_cart.*, system_member.*, system_product.*
            FROM system_cart
            INNER JOIN system_member ON (system_cart.cart_member_id = system_member.member_id)
            INNER JOIN system_product ON (system_cart.cart_product_id = system_product.product_id)
            WHERE (cart_member_id = '$member_id')");
    
    $product_price_total    = 0;
    $order_freight          = 0;
    $order_point            = 0;
    $order_amount           = 0;

    ?>
    <script>  
        $(document).ready(function(){

            $("#order_type_buy").change(function(){
                var order_type_buy = $(this).val();
                var ewallet        = $("#ewallet").val();
                $.get( "process/ajax/ajax_buy_payment.php", { action: order_type_buy, ewallet: ewallet }, function( data ) {
                    $("#type_buy").html( data );
                });

            });

        });
    </script>
    <style type="text/css">
        @media only screen and (min-width: 768px) {
            .image {
                height: 150px;
                object-fit: cover;
                width: 550px;
            }
        
    </style>
    <title><?php echo $l_checkbill ?></title>
    <div class="card-title d-flex align-items-center">
        <div><i class="bx bx-dish font-22 text-primary"></i></div>
        <h5 class="mb-0 text-primary"><?php echo $l_checkbill ?></h5>
    </div>
    <nav aria-label="breadcrumb" class="d-flex">
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page"><a href="<?php echo $page_type ?>?page=shopping"><?php echo $l_package ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $l_checkbill ?></li>
        </ol>
        <div class="ms-auto">
            <a href="<?php echo $page_type ?>?page=shopping&action=cart_setting" class="btn btn-primary btn-sm ms-auto"><?php echo $l_cart ?> <i class="bx bx-cart me-0"></i></a>
        </div>
    </nav>
    <?php
    
    $empty = mysqli_num_rows($query);
    if ($empty > 0) {
        while ($data = mysqli_fetch_array($query)) {

            $member_ewallet     = $data['member_ewallet'];

            // ----------------------

            $product_price      = $data['product_price_member'] * $data['cart_product_amount']; // ค่าสินค้า
            $product_freight    = $data['product_freight']      * $data['cart_product_amount']; // ค่าขนส่ง
            $product_point      = $data['product_point']        * $data['cart_product_amount']; // คะแนน

            // ----------------------

            $product_price_total    += $product_price; // ราคาสินค้ารวม
            $order_freight          += $product_freight; // ค่าขนส่งรวม
            $order_point            += $product_point; // คะแนนรวม
            $order_amount           += $data['cart_product_amount']; // จำนวนรวม

            // ----------------------
            $image_cover = $data['product_image_cover'] != '' ? "assets/images/products/" . $data['product_image_cover'] : "assets/images/etc/example_product.png" ;
            ?>

            <div class="card p-5 position-relative border border-1 border-primary">
                <div class="row">
                    <div class="col-12 col-sm-2 mb-3 mb-sm-0">
                        <img src="<?php echo $image_cover ?>" alt="ปกสินค้า" style="width:100%;object-fit: cover;">
                    </div>
                    <div class="col-12 col-sm-10 ps-sm-5">
                        <h5><?php echo $data['product_name'] ?></h5>
                        <p><?php echo $data['product_code'] ?></p>
                        <hr>
                        <h6 class="text-danger"><?php echo number_format($product_price, 2) . $l_bath ?></h6>
                        <p><?php echo number_format($product_point, 2) . $point_name ?></p>
                    </div>
                </div>
            </div>

        <?php } } else { echo "<p>$l_notfound</p>"; } ?>

    <div class="card p-5 position-relative border border-1 border-primary">
        <div class="card-title d-flex align-items-center">
            <div><i class="bx bx-list-check me-1 font-22 text-primary"></i></div>
            <h5 class="mb-0 text-primary"><?php echo $l_checkbill_sum ?></h5>
        </div>
        <hr>
        <h6 class="text-danger">
            <?php
            $order_price = $product_price_total + $order_freight;
            echo $l_invoice_totprice . " " . number_format($order_price, 2) . $l_bath;
            ?>
        </h6>
        <h6> <?php echo $l_invoice_totpoint . " " . number_format($order_point, 2) . $point_name ?></h6>
    </div>

    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body p-3 p-sm-3">
            <div class="card-title d-flex align-items-center">
                <div><i class="bx bx-task me-1 font-22 text-primary"></i></div>
                <h5 class="mb-0 text-primary"><?php echo $l_resold_order ?></h5>
            </div>
            <hr>
            <div class="card p-3 position-relative border border-1 border-success">
                <form action="process/setting_buy.php" method="post" class="row" enctype="multipart/form-data">
                    <input type="hidden" name="page"            value="<?php echo $page_type ?>">
                    <input type="hidden" name="order_price"     value="<?php echo $order_price; ?>">
                    <input type="hidden" name="order_amount"    value="<?php echo $order_amount; ?>">
                    <input type="hidden" name="order_point"     value="<?php echo $order_point; ?>">
                    <input type="hidden" name="order_freight"   value="<?php echo $order_freight; ?>">
                    <input type="hidden" name="order_member"    value="<?php echo $member_id; ?>">
                    <input type="hidden" name="member_ewallet"  value="<?php echo $member_ewallet; ?>" id="ewallet">
                    <?php
                    if ($system_delivery == 1) {
                        $query = mysqli_query($connect, "SELECT system_address.*, system_member.*, system_amphures.*, system_districts.*, system_provinces.* 
                            FROM system_address 
                            INNER JOIN system_member ON (system_address.address_member = system_member.member_id)
                            INNER JOIN system_amphures ON (system_address.address_amphure = system_amphures.AMPHUR_ID)
                            INNER JOIN system_districts ON (system_address.address_district = system_districts.DISTRICT_CODE)
                            INNER JOIN system_provinces ON (system_address.address_province = system_provinces.PROVINCE_ID)
                            WHERE (address_member = '$member_id') AND (address_type = 1) ");
                        $data  = mysqli_fetch_array($query);
                        $address_detail   = $data['address_detail'];
                        $address_district = $data['DISTRICT_NAME'];
                        $address_amphure  = $data['AMPHUR_NAME'];
                        $address_province = $data['PROVINCE_NAME'];
                        $address_zipcode  = $data['address_zipcode'];
                        $member_name      = $data['member_name'];
                        $member_tel       = $data['member_tel'];
                        $member_bank_own  = $data['member_bank_own'];
                        $detail_row       = "12";
                    }
                    else {
                        $query = mysqli_query($connect, "SELECT * FROM system_member WHERE (member_id = '$member_id')");
                        $data  = mysqli_fetch_array($query);
                        $member_name      = $data['member_name'];
                        $member_bank_own  = $data['member_bank_own'];
                        $member_tel       = $data['member_tel'];
                        $detail_row       = "1";
                    }
                    
                    ?>
                    <div class="col-12">
                        <br>
                        <div class="form-group mb-3">
                        <label class="form-label"><?php echo $l_paydetail ?></label>
                        <select class="form-control" name="order_type_buy" id="order_type_buy" required>
                            <option value="4">Scan QRCode</option>
                        </select>
                        </div>
                        <hr class="text-success my-3">
                    </div>
                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                            <label class="form-label"><?php echo $l_member_name ?></label>
                            <input type="text" class="form-control" name="order_buyer" value="<?php echo $member_name ?>" required>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label"><?php echo $l_tel ?></label>
                            <input type="text" class="form-control" name="order_buyer_tel" value="<?php echo $member_tel ?>" required>
                        </div>
                    </div>
                    
                    <div class="col-12 mt-5 d-flex">
                        
                        <a href="<?php echo $page_type ?>?page=shopping&action=cart_setting" class="btn btn-success m-1 ms-auto"><?php echo $l_edit ?></a>
                        
                        <a href="<?php echo $cancel_url ?>" class="btn btn-danger m-1"><?php echo $l_cancel ?></a>

                        <button name="action" value="confirm_member" class="btn btn-primary m-1" onclick="javascript:return confirm('Confirm ?')" id="submit_button"><?php echo $l_submit ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php } elseif (isset($_GET['action']) && $_GET['action'] == 'show_product') {

    $product_id = isset($_GET['product_id'])  ? $_GET['product_id']  : false;
    $query = mysqli_query($connect, "SELECT * FROM system_product WHERE product_id = '$product_id' ");
    $data  = mysqli_fetch_array($query);

    if ($data['product_image_cover'] != '') {
        $image_cover = "assets/images/products/" . $data['product_image_cover'];
    } else {
        $image_cover = "assets/images/etc/example_product.png";
    } ?>
    <title><?php echo $l_detail ?></title>
    <div class="col-12 col-sm-9 mx-auto">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item" aria-current="page"><a href="<?php echo $page_type ?>?page=shopping"><?php echo $l_product ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $l_detail ?></li>
            </ol>
        </nav>
        <div class="card border-top border-0 border-4 border-primary">
            <div class="card-body p-1 pt-3 pb-3 p-sm-3">
                <div class="row">
                    <div class="col-12 col-sm-6">
                        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                            <ol class="carousel-indicators">
                                <li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"></li>
                                <?php for ($i = 1; $i < 6; $i++) {
                                    if ($data['product_image_' . $i] != '') { ?>
                                        <li data-bs-target="#carouselExampleIndicators" data-bs-slide-to="<?php echo $i ?>"></li>
                                <?php } } ?>
                            </ol>
                            <div class="carousel-inner">
                                <div class="carousel-item active position-relative">
                                    <img src="<?php echo $image_cover ?>" class="d-block w-100" alt="รูปปก" style="width:100%;height:650px;object-fit: cover;">
                                    <div class="position-absolute top-0 start-0 m-4">
                                        <p class="text-white"><?php echo $l_product_imagecover ?></p>
                                    </div>
                                </div>
                                <?php for ($i = 1; $i < 6; $i++) {
                                    if ($data['product_image_' . $i] != '') { ?>
                                        <div class="carousel-item position-relative">
                                            <img src="assets/images/products/<?php echo $data['product_image_' . $i]; ?>" class="d-block w-100" alt="รูปประกอบ" style="width:100%;height:650px;object-fit: cover;">
                                            <div class="position-absolute top-0 start-0 m-4">
                                                <p class="text-white"><?php echo $l_product_image . $i ?></p>
                                            </div>
                                        </div>
                                <?php } } ?>
                            </div>
                            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-bs-slide="prev"> <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden"><?php echo $l_prev ?></span>
                            </a>
                            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-bs-slide="next"> <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden"><?php echo $l_next ?></span>
                            </a>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 p-5">
                        <h3 class="text-primary"><?php echo $data['product_name'] ?></h3>
                        <p class="text-secondary"><?php echo $data['product_code'] ?></p>
                        <hr class="my-5">
                        <table class="table table-borderless fs-6 text">
                            <tbody>
                                <tr>
                                    <th width="120"><?php echo $l_pricemem ?></th>
                                    <td class="text-danger"><?php echo number_format($data['product_price_member'], 2) . $l_bath ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo $l_point ?></th>
                                    <td class="text-success"><?php echo number_format($data['product_point'], 2) . $point_name ?></td>
                                </tr>
                                <?php if ($data['product_freight'] > 0) { ?>
                                    <tr>
                                        <th><?php echo $l_fright ?></th>
                                        <td><?php echo number_format($data['product_freight'], 2) . $l_bath ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if ($data['product_detail'] != '') { ?>
                        <p class="mt-5 text-dark fs-6 text fw-bold"><?php echo $l_detail ?></p>
                        <div class="p-3">
                            <?php echo $data['product_detail'] ?>
                        </div>
                    <?php }

                    // Comment
                    if ($system_comment == 1) {

                    $query = mysqli_query($connect, "SELECT * FROM system_comment WHERE (comment_link = '$product_id') AND (comment_type = 0)");
                    $count = mysqli_num_rows($query);
                    ?>
                    <div class="card-body">
                        <hr class="text-primary">
                        <h5 class="my-5"><?php echo $data_comment > 0 ? "$l_comment ($count)" : $l_comment; ?></h5>
                        <?php
                        $limit          = " LIMIT $start, 10 ";
                        $sql   = "SELECT * FROM system_comment WHERE (comment_type = 0) AND (comment_link = '$product_id') AND (comment_direct = 0) ORDER BY comment_id DESC";
                        $query = mysqli_query($connect, $sql . $limit);
                        $empty = mysqli_num_rows($query);
                        if ($empty > 0) {
                            while ($data = mysqli_fetch_array($query)) {

                                $comment_create = datethai($data2['comment_create'], 2, $lang);
                                $comment_id     = $data['comment_id'];
                                $comment_member = $data['comment_member'];
                                $comment_name   = $data['comment_name'];
                                $comment_detail = $data['comment_detail'];
                                $comment_url    = "?page=product_single&product_id=$product_id&comment_direct=$comment_id";
                                $comment_member = $data['comment_member'];
                                $comment_member = $data['comment_member'];


                                if ($comment_create == 0) {
                                    
                                    $profile_image = "assets/images/etc/example_member.jpg";

                                } 
                                else {
                                    
                                    $query_member = mysqli_query($connect, "SELECT * FROM system_member WHERE member_id = '$comment_member' ");
                                    $data_member  = mysqli_fetch_array($query_member);
                                    $member_image_cover = $data_member['member_image_cover'];
                                    
                                    if ($member_image_cover != '') {
                                        
                                        $profile_image = "assets/images/profiles/$member_image_cover";

                                    } 
                                    else {

                                        $profile_image = "assets/images/etc/example_member.jpg";

                                    }
                                } ?>
                                <div class="d-flex align-items-center">
                                    <img src="<?php echo $profile_image ?>" class="align-self-start rounded-circle p-1 border" width="90" height="90" alt="รูปโปรไฟล์">
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="my-0"><?php echo $comment_name ?></h5>
                                        <div class="d-flex">
                                            <p class="small"><?php echo $comment_create ?></p>
                                            <hr class="vr mx-2 my-0">
                                            <a class="small" href="<?php echo $comment_url ?>"><i class="tf-ion-chatbubbles"></i> <?php echo $l_send ?></a>
                                        </div>
                                        <p><?php echo $comment_detail ?></p>
                                    </div>
                                </div>
                                <?php
                                $query2 = mysqli_query($connect, "SELECT * FROM system_comment WHERE (comment_link = '$product_id') AND (comment_direct = '$comment_id') ");
                                while ($data2  = mysqli_fetch_array($query2)) { ?>
                                    <div class="d-flex align-items-center ms-5 mt-5">
                                        <img src="<?php echo $profile_image ?>" class="align-self-start rounded-circle p-1 border" width="50" height="50" alt="รูปโปรไฟล์">
                                        <div class="flex-grow-1 ms-3">
                                            <h5 class="my-0">ตอบกลับโดย : <?php echo $data2['comment_name'] ?></h5>
                                            <div class="d-flex">
                                                <p class="small"><?php echo datethai($data2['comment_create'], 2) ?></p>
                                            </div>
                                            <p><?php echo $data2['comment_detail'] ?></p>
                                        </div>
                                    </div>

                        <?php } echo "<hr>"; } } else { echo "<div class='mb-5'><p>$l_notfound (Comment)</p></div>"; } 

                        $url = "$page_type?page=shopping&action=show_product&product_id=$product_id";
                        pagination($connect, $sql, $perpage, $page_id, $url); ?>
                        
                        <form method="post" action="process/setting_comment.php" class="row g-3">
                            <input type="hidden" name="comment_link"    value="<?php echo $product_id ?>">
                            <input type="hidden" name="page"            value="<?php echo $page_type ?>">
                            <input type="hidden" name="comment_direct"  value="<?php echo $comment_direct ?>">
                            <input type="hidden" name="comment_member"  value="<?php echo $member_id ?>">
                            <input type="hidden" name="comment_type"    value="0">
                            <div class="col-12">
                                <label class="form-label">เพิ่มความเห็น</label>
                                <textarea name="comment_detail" class="form-control" rows="6" placeholder="<?php echo $l_commentdeta ?>" maxlength="400" required></textarea>
                            </div>
                            <div class="d-flex">
                                <button name="action" value="insert" class="btn btn-primary btn-sm"><?php echo $l_send ?></button>
                            </div>
                        </form>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

<?php } elseif (isset($_GET['action']) && $_GET['action'] == 'admin') { ?>

    <title>ซื้อสินค้าให้สมาชิก</title>
    <div class="col-12 col-sm-4 mx-auto">
        <div class="card border-top border-0 border-4 border-primary">
            <div class="card-body p-1 pt-3 pb-3 p-sm-3">
                <div class="card-title d-flex align-items-center">
                    <div><i class="bx bx-search me-1 font-22 text-primary"></i>
                    </div>
                    <h5 class="mb-0 text-primary">ซื้อสินค้าอัติโนมัติให้กับสมาชิก</h5>
                </div>
                <hr>
                <form action="admin.php" method="get">
                    <div class="col-12">
                        <label class="form-label">รหัสสมาชิก</label>
                        <input type="text" class="form-control" name="member_code" placeholder="รหัสสมาชิก" required>
                    </div>
                    <button class="btn btn-primary btn-sm mt-5" name="page" value="shopping">ซื้อสินค้า</button>
                </form>
            </div>
        </div>
    </div>

<?php } elseif (isset($_GET['action']) && $_GET['action'] == 'payment') { 

    if (file_exists("process/project/$system_style/payment_special.php")) { 

        include("process/project/$system_style/payment_special.php"); 

    }

} 

?>