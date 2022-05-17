<?php 

$perpage        = 42;
if (!isset($_GET['page_id'])) { 
    $page_id    = 1;
    $start      = 0;
} 
else { 
    $page_id    = $_GET['page_id'];
    $start      = ($page_id - 1) * $perpage;
}

$limit   = " LIMIT $start, $perpage ";
$where   = "";
$order_by= "  ORDER BY product_id ASC ";

$product_type = isset($_GET['product_type']) ? $_GET['product_type'] : false;
$product_name = isset($_GET['product_name']) ? $_GET['product_name'] : false;
$order_by     = isset($_GET['order_by'])     ? $_GET['order_by']     : false;

if ($product_type != false && $product_type != 'all') {
    $where = " AND (product_type = '$product_type') ";
}
elseif ($product_name != false) {
    $where = " AND (product_name LIKE '%$product_name%') ";
}

if ($order_by != false && $order_by != 'all') {
    $order_by = " ORDER BY product_price $order_by ";
}

$sql   = "SELECT * FROM system_product WHERE (product_price > 0) AND (product_type2 = 0) " . $where;
?>

<title>สินค้า</title>
<style type="text/css">
    .img-responsive {
        height: 200px;
        object-fit: cover;
    }
    @media only screen and (max-width: 600px) {
        .img-responsive {
            height: 150;
        }
    }
</style>
<?php 
$header_url     = "dashboard/assets/images/bg-themes/1.png";
$header_name    = "สินค้า";
$header_detail  = "สินค้าคุณภาพพรีเมี่ยม ในคาราสบายกระเป๋า มีมากมายให้ท่านได้เลือกสรร";
include('webpage_asset/include/include_header.php'); 
?>
<section class="products my-5">
	<div class="container">
		<div class="row g-3">
            <div class="col-12 col-sm-6">
                <form action="index.php" method="get">
                    <input type="hidden" name="page" value="shop">
                    <label class="form-label">ค้นหาสินค้า</label>
                    <input type="text" name="product_name" value="<?php echo $product_name != false ? $product_name : false; ?>" class="form-control" style="height: 38px;" placeholder="กด Enter เพื่อค้นหาสินค้า">
                </form>
            </div>
			<div class="col-6 col-sm-3">
				<form action="index.php" method="get" id="form">
					<input type="hidden" name="page" value="shop">
                    <label class="form-label">ประเภทสินค้า</label>
                    <select class="form-control" name="product_type" onchange="document.getElementById('form').submit()">
                    	<option value="all">ทั้งหมด</option>
                    	<?php 
                    	$query = mysqli_query($connect, "SELECT system_product.* , system_product_type.* 
                            FROM system_product
                            INNER JOIN system_product_type ON (system_product.product_type = system_product_type.product_type_id)
                            WHERE (product_price > 0) AND (product_type2 = 0)
                            GROUP BY product_type
                            ORDER BY product_type_id DESC");
                    	while ($data = mysqli_fetch_array($query)) {

                            $product_type_id    = $data['product_type_id'];
                            $product_type_name  = $data['product_type_name'];

                            echo "<option value='$product_type_id' ";
                            echo $product_type != false && $product_type_id == $product_type ? "selected" : false;
                            echo ">$product_type_name</option>";

                    	} 
                        ?>
                    </select>
                </form>
			</div>
			<div class="col-6 col-sm-3">
				<form action="index.php" method="get" id="form_order">
					<input type="hidden" name="page" value="shop">
                    <?php if (isset($_GET['product_type'])) { ?>
                        <input type="hidden" name="product_type" value="<?php echo $product_type ?>">
                    <?php } ?>
                    <label class="form-label">จัดเรียง</label>
                    <select class="form-control" name="order_by" onchange="document.getElementById('form_order').submit()">
                    	<option value="all"  <?php echo $order_by == 'all' ? "selected" : false; ?>>สินค้าใหม่</option>
                    	<option value="ASC"  <?php echo $order_by == 'ASC' ? "selected" : false; ?>>ราคาน้อยที่สุด</option>
                        <option value="DESC" <?php echo $order_by == 'DESC' ? "selected" : false; ?>>ราคาสูงที่สุด</option>
                    </select>
                </form>
			</div>
		</div>
        <?php 
        $query = mysqli_query($connect, $sql); 
        $count = mysqli_num_rows($query);
        echo "<p class='mt-3 text-dark'>จำนวนสินค้าทั้งหมด $count รายการ</p>";
        ?>
        <hr class="mb-5 my-3">
		<div class="row">
			<?php
			$query = mysqli_query($connect, $sql . $order_by . $limit);
            $empty = mysqli_num_rows($query); 
            if ($empty > 0) {
                while($data = mysqli_fetch_array($query)) { 

                    $product_id         = $data['product_id'];
                    $product_name       = $data['product_name'];
                    $product_type_id    = $data['product_type'];
                    $product_detail     = $data['product_detail'];
                    $product_price      = number_format($data['product_price']) . $l_bath;

                    $image_cover        = $data['product_image_cover'] != "" ? $data['product_image_cover'] : "dashboard/assets/images/etc/example.png" ;

                    if (isset($data_check_login)) {
                        $cart_url = "dashboard/process/setting_visitor_buy.php?action=insert_cart&buyer_id=$buyer_id&product_id=$product_id&product-quantity=1";
                    }
                    else {
                        $cart_url = "visitor_login.php";
                    }

                    ?>
				<div class="col-6 col-sm-2">
                    <div class="product-item">
                        <div class="product-thumb">
                            
                            <!-- <span class="bage">Sale</span> -->
                            <img src="<?php echo $image_cover ?>" alt="Image Cover" class="img-responsive border border-1">
                            <div class="preview-meta">
                                <ul>
                                    <li>
                                        <span data-bs-toggle="modal" data-bs-target="#product-modal_<?php echo $product_id ?>">
                                            <i class="tf-ion-ios-search-strong"></i>
                                        </span>
                                    </li>
                                    <li>
                                        <a href="<?php echo $cart_url ?>"><i class="tf-ion-android-cart"></i></a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="product-content">
                            <h4>
                                <a href="?page=product_single&product_id=<?php echo $product_id ?>"><?php echo $product_name ?></a>
                            </h4>
                            <p class="price"><?php echo $product_price ?></p>
                        </div>
                    </div>
                </div>
                <!-- Modal -->
                <div class="modal product-modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" id="product-modal_<?php echo $product_id ?>">
                    <div class="modal-dialog modal-lg modal-fullscreen-sm-down " role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <button type="button" class="btn-close position-absolute top-0 end-0 m-4" data-bs-dismiss="modal" aria-label="Close"></button>
                                <div class="row">
                                    <div class="col-md-8 col-sm-6 col-xs-12">
                                        <div class="modal-image">
                                            <img src="<?php echo $image_cover ?>" alt="Image Cover" class="img-responsive border border-1">
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-xs-12">
                                        <div class="product-short-details">
                                            <h2 class="product-title"><?php echo $product_name ?></h2>
                                            <p class="product-price"><?php echo $product_price ?></p>
                                            <p class="product-short-description"><?php echo $product_detail ?></p>
                                            <a href="<?php echo $cart_url ?>" class="btn btn-main">เพิ่มลงสู่ตะกร้า</a><br>
                                            <a href="?page=product_single&product_id=<?php echo $product_id ?>" class="btn btn-transparent">รายละอียด</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
			<?php } } else { ?>
                <p class="m-5 text-dark">ไม่มีข้อมูลในระบบ</p>
            <?php } ?>	
		</div>
        <?php $url = "?page=shop"; 
        pagination_visitor ($connect, $sql, $perpage, $page_id, $url); ?>
	</div>
</section>