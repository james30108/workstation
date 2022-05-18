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

$sql            = "SELECT * FROM system_product WHERE (product_price > 0) AND (product_type2 = 1) " . $where;


$header_url     = "dashboard/assets/images/bg-themes/7.png";
$header_name    = $l_package;
$header_detail  = "สินค้าจัดเซ็ตพิเศษ ในราคาสุดคุ้ม";
include('webpage_asset/include/include_header.php'); 
?>
<section class="products my-5">
	<div class="container">
		<div class="row g-3">
            <div class="col-12 col-sm-6">
                <form action="index.php" method="get">
                    <input type="hidden" name="page" value="shop">
                    <label class="form-label"><?php echo $l_search ?></label>
                    <input type="text" name="product_name" value="<?php echo $product_name != false ? $product_name : false; ?>" class="form-control" style="height: 38px;" placeholder="press enter to search">
                </form>
            </div>
			<div class="col-6 col-sm-3">
				<form action="index.php" method="get" id="form">
					<input type="hidden" name="page" value="shop">
                    <label class="form-label"><?php echo $l_product_type ?></label>
                    <select class="form-control" name="product_type" onchange="document.getElementById('form').submit()">
                    	<option value="all"><?php echo $l_all ?></option>
                    	<?php 
                    	$query = mysqli_query($connect, "SELECT system_product.* , system_product_type.* 
                            FROM system_product
                            INNER JOIN system_product_type ON (system_product.product_type = system_product_type.product_type_id)
                            WHERE (product_price > 0) AND (product_type2 = 1)
                            GROUP BY product_type
                            ORDER BY product_type_id DESC");
                    	while ($data = mysqli_fetch_array($query)) {

                            $product_type_id    = $data['product_type_id'];
                            $product_type_name  = $data['product_type_name'];

                            echo "<option value='$product_type_id' ";
                            echo $product_type != false && $product_type_id == $product_type ? "selected" : false;
                            echo ">$product_type_name</option>";

                        } ?>
                    </select>
                </form>
			</div>
			<div class="col-6 col-sm-3">
				<form action="index.php" method="get" id="form_order">
					<input type="hidden" name="page" value="shop">
                    <?php if (isset($_GET['product_type'])) { ?>
                        <input type="hidden" name="product_type" value="<?php echo $product_type ?>">
                    <?php } ?>
                    <label class="form-label"><?php echo $l_sort ?></label>
                    <select class="form-control" name="order_by" onchange="document.getElementById('form_order').submit()">
                        <option value="all"  <?php echo $order_by == 'all' ? "selected" : false; ?>><?php echo $l_product_new ?></option>
                    	<option value="ASC"  <?php echo $order_by == 'ASC' ? "selected" : false; ?>><?php echo $l_price_asc ?></option>
                        <option value="DESC" <?php echo $order_by == 'DESC' ? "selected" : false; ?>><?php echo $l_price_desc ?></option>
                    </select>
                </form>
			</div>
		</div>
        <?php 
        $query = mysqli_query($connect, $sql); 
        $count = mysqli_num_rows($query);
        echo "<p class='mt-3 text-dark'>$l_invoice_totqty $count $l_list</p>";
        ?>
        <hr class="mb-5 my-3">
		<div class="row g-3">
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
    				<div class="col-12 col-sm-6">
                        <div class="border border-1 p-3 border-warning">
                            <h5>
                                <a href="?page=product_single&product_id=<?php echo $product_id ?>">
                                    <?php echo $product_name ?></h5>
                                </a>
                            <p class="m-0">ราคา <?php echo $product_price ?></p><br>
                            <a href="<?php echo $cart_url ?>" title="Detail" class="text-decoration-underline"><?php echo $l_cart_insert ?></a>
                        </div>
                    </div>
            <?php } } else { echo "<p class='m-5 text-dark'>$l_notfound</p>"; } ?>
		</div>
        <?php $url = "?page=package"; 
        pagination_visitor ($connect, $sql, $perpage, $page_id, $url); ?>
	</div>
</section>