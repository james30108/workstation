<title><?php echo $company ?></title>
<style type="text/css">
    .img-responsive {
        height: 300px;
        object-fit: cover;
    }
    .img_thread {
        width: 100%;
        height: 400px;
        object-fit: cover;
    }
    .package_section {
        background-image: url('dashboard/assets/images/bg-themes/7.png');
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-position: center; 
        background-color:rgba(0, 0, 0, 0.1);
    }
    @media only screen and (max-width: 600px) {
        .img-responsive {
            height: 200px;
        }
    }
</style>
<section class="bg-gray">
    <div class="container">
        <div class="row">
            <div class="title text-center my-3">
                <h2>สินค้าใหม่</h2>
            </div>
        </div>
        <div class="row">
            <?php 
            $product_title = "สินค้าใหม่";
            $style_card    = "";
            $query = mysqli_query($connect, "SELECT system_product.*, system_product_type.* 
                FROM system_product
                INNER JOIN system_product_type ON (system_product.product_type = system_product_type.product_type_id)
                WHERE (product_image_cover != '') AND (product_price > 0) AND (product_type2 = 0)
                ORDER BY product_id ASC
                LIMIT 12");
            while ($data = mysqli_fetch_array($query)) {
                include('webpage_asset/include/include_product.php');
            } ?>
        </div>
        <!--
        <hr class="my-5">
        <div class="row">
            <div class="title">
                <h2>สินค้ายอดฮิต</h2>
            </div>
        </div>
        <div class="row">
            <?php /*
            $product_title = "สินค้าแนะนำ";
            $query = mysqli_query($connect, "SELECT system_product.*, system_product_type.* 
                FROM system_product
                INNER JOIN system_product_type ON (system_product.product_type = system_product_type.product_type_id)
                WHERE (product_image_cover != '') AND (product_price > 0) AND (product_type2 = 0)
                ORDER BY product_id ASC
                LIMIT 16");
            while ($data = mysqli_fetch_array($query)) {
                include('webpage_asset/include/include_product.php');
            } */ ?>
        </div>
        -->
    </div>
</section>

<section class="py-3 package_section">
    <div class="container">
        <div class="row">
            <div class="title text-center">
                <h2>แพ็กเกจพิเศษ</h2>
            </div>
        </div>
        <div class="row g-3">
            <?php 
            $query = mysqli_query($connect, "SELECT system_product.*, system_product_type.* 
                FROM system_product
                INNER JOIN system_product_type ON (system_product.product_type = system_product_type.product_type_id)
                WHERE (product_price > 0) AND (product_type2 = 1)
                ORDER BY product_id ASC
                LIMIT 8");
            while ($data = mysqli_fetch_array($query)) { 

                $product_id     = $data['product_id'];
                $product_name   = $data['product_name'];
                $product_price  = number_format($data['product_price']) . $l_bath;

                if (isset($data_check_login) && $system_style == 1) {
                    
                    $cart_url = "dashboard/process/setting_visitor_buy.php?action=insert_cart&buyer_id=$buyer_id&product_id=$product_id&product-quantity=1";

                } 
                else {

                    $cart_url = "visitor_login.php";

                }
                ?>
                <div class="col-12 col-sm-6">
                    <div class="border border-1 p-3 border-warning bg-white">
                        <h5>
                            <a href="?page=product_single&product_id=<?php echo $product_id ?>">
                                <?php echo $data['product_name'] ?>
                            </a>
                        </h5>
                        <p class="m-0">ราคา <?php echo $product_price ?></p><br>
                        <a href="<?php echo $cart_url ?>" title="Detail" class="text-decoration-underline">เพิ่มลงตะกร้า</a>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<section class="bg-gray">
    <div class="container">
        <hr class="my-5">
        <div class="row g-3">
            <div class="d-flex align-items-center">
                <h5>ข่าวสารการประชาสัมพันธ์</h5>
                <a href="?page=thread" class="text-decoration-underline ms-auto">ข่าวสารทั้งหมด</a>
            </div>
            <?php 
            $query = mysqli_query($connect, "SELECT * FROM system_thread LIMIT 2");
            while ($data = mysqli_fetch_array($query)) { ?>
                <div class="col-12 col-sm-6">
                    <div class="position-relative">
                        <img src="dashboard/assets/images/thread/<?php echo $data['thread_image'] ?>" class="img_thread">
                        <div class="position-absolute bottom-0 end-0 m-3 p-3 bg-light bg-gradient">
                            <h6><?php echo mb_strimwidth($data['thread_title'], 0, 70, "...")  ?></h6>
                            <div class="d-flex">
                                <a href="" class="ms-auto text-decoration-underline small">อ่านเพิ่มเติม</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>

<!-- Start Call To Action -->
<section class="call-to-action bg-gray section">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center">
                <div class="title">
                    <h2>ติดตามเพื่อรับข่าวสารจากทางเรา</h2>
                </div>
                <div class="col-10 col-sm-6 mx-auto">
                    <form action="dashboard/process/setting_config.php" method="post">
                        <div class="input-group">
                            <input type="email" class="form-control" name="noti_email" placeholder="Example@email.com" aria-label="ระบุอีเมลลงในช่องเพื่อรับข้อมุลจากทางเรา">
                            <button class="btn btn-dark" style="width: 200px;" name="action" value="insert_notification">ติดตาม</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!--
<section class="section instagram-feed">
    <div class="container">
        <div class="row">
            <div class="title">
                <h2>อินสตาแกรมของเรา</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="instagram-slider" id="instafeed" data-accessToken="IGQVJYeUk4YWNIY1h4OWZANeS1wRHZARdjJ5QmdueXN2RFR6NF9iYUtfcGp1NmpxZA3RTbnU1MXpDNVBHTzZAMOFlxcGlkVHBKdjhqSnUybERhNWdQSE5hVmtXT013MEhOQVJJRGJBRURn"></div>
            </div>
        </div>
    </div>
</section>
-->