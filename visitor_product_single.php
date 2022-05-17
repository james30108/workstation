<?php
$product_id = isset($_GET['product_id']) ? $_GET['product_id'] : false;
$query 		= mysqli_query($connect, "SELECT * FROM system_product WHERE product_id > '$product_id' ORDER BY product_id LIMIT 1");
$data 		= mysqli_fetch_array($query);
$next		= isset($data['product_id']) ? $data['product_id'] : false;
$query 		= mysqli_query($connect, "SELECT * FROM system_product WHERE product_id < '$product_id' ORDER BY product_id LIMIT 1");
$data 		= mysqli_fetch_array($query);
$previous   = isset($data['product_id']) ? $data['product_id'] : false;
$query 		= mysqli_query($connect, "SELECT system_product.*, system_product_type.* 
    FROM system_product
    INNER JOIN system_product_type ON (system_product.product_type = system_product_type.product_type_id)
    WHERE product_id = '$product_id' ");
$data 		= mysqli_fetch_array($query);

// variable
$product_id 	= $data['product_id'];
$product_name 	= $data['product_name'];
$product_price 	= $data['product_price'];
$product_type 	= $data['product_type'];
$product_type_name = $data['product_type_name'];
$product_freight= $data['product_freight'];
$product_weight = $data['product_weight'];
$product_detail = $data['product_detail'];
$product_type2  = $data['product_type2'];

$product_image_cover = ($data['product_image_cover'] != '') ? $data['product_image_cover'] : "dashboard/assets/images/etc/example.png";

// login or not
if (isset($data_check_login)) {

	$query_cart = mysqli_query($connect, "SELECT * FROM system_cart WHERE (cart_member_id = '$buyer_id') AND (cart_product_id = '$product_id')");
	$data_cart  = mysqli_fetch_array($query_cart);
	$number 	= isset($data_cart) ? $data_cart['cart_product_amount'] : 1;

    $cart_url = "<button class='btn btn-main' id='cart_insert' name='action' value='insert_cart'>เพิ่มลงตะกร้า</button>";
} 
else {
    $cart_url = "<a href='visitor_login.php' class='btn btn-main' id='cart_insert'>เพิ่มลงตะกร้า</a>";
}

// product or package
if ($product_type2 == 0) {
	$package_sql    = "SELECT system_package.*, system_product.* 
		FROM system_product 
		INNER JOIN system_package ON (system_product.product_id = system_package.package_main)
		WHERE (package_product = '$product_id') AND (product_type2 = 1) AND (product_price > 0)
		GROUP BY package_main";
	$package_title  = "";
	$package_border = "border-warning";
	$package_text   = "แพ็กเกจที่เกี่ยวข้อง";
	$empty_text     = "ไม่มีแพ็กเกจ";
}
else {
	$package_sql    = "SELECT system_package.*, system_product.* 
		FROM system_product 
		INNER JOIN system_package ON (system_product.product_id = system_package.package_product)
		WHERE (package_main = '$product_id') AND (product_type2 = 0) AND (product_price > 0)
		GROUP BY package_main";
	$package_title  = "<div class='p-2 bg-warning mb-3 text-center'><strong>แพ็กเกจพิเศษ</strong></div>";
	$package_border = "";
	$package_text   = "สินค้าที่เกี่ยวข้อง";
	$empty_text     = "สินค้าในรายการนี้ไม่มีแบบแยกขายหน้าเว็บเพจ";
}

//$number 	= isset($_SESSION['cart'][$product_id]) ? $_SESSION['cart'][$product_id] : 1;
//$comment_direct = isset($_GET['comment_direct']) ? $_GET['comment_direct'] : 0;
?>

<style type="text/css">
	.img-responsive {
		height: 300px;
		width: 100%;
		object-fit: cover;
	}
	.image_cover {
		width:100%;
		height:500px;
		object-fit: cover;
	}

	@media only screen and (max-width: 600px) {
		.img-responsive {
			height: 200px;
		}
	}
	.product_detail p {
		font-size: 20px;
		text-align: justify;
		text-justify: inter-word;
	}

	.product_detail blockquote {
		background: #f9f9f9;
		border-left: 10px solid #ccc;
		padding: 2%;
	}

	.button {
		background-color: white;
		border: none;
		color: black;
		padding: 15px 35px;
		text-align: center;
		text-decoration: none;
		display: inline-block;
	}

	.button.active {
		background-color: black;
		color: white;
	}
</style>
<title>แสดงสินค้า</title>
<div class="container">
	<div class="row my-3">
		<div class="col-7">
			<nav aria-label="breadcrumb">
				<ol class="breadcrumb">
					<li class="breadcrumb-item" aria-current="page"><a href="?page=shop">สินค้า</a></li>
					<li class="breadcrumb-item active" aria-current="page">รายละเอียด</li>
				</ol>
			</nav>
		</div>
		<div class="col-5 d-flex">
			<ol class="product-pagination ms-auto d-flex">
				<?php if ($previous != '') { ?>
					<li><a href="?page=product_single&product_id=<?php echo $previous ?>" class="me-3 mx-sm-2"><i class="tf-ion-ios-arrow-left"></i> ก่อนหน้า</a></li>
				<?php }
				if ($next != '') { ?>
					<li><a href="?page=product_single&product_id=<?php echo $next ?>" class="mx-sm-2">ถัดไป <i class="tf-ion-ios-arrow-right"></i></a></li>
				<?php } ?>
			</ol>
		</div>
	</div>
	<div class="row mb-3">
		<div class="col-md-5">
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
						<img src="<?php echo $product_image_cover ?>" alt="ปกสินค้า" class="d-block w-100 border border-1">                  	
					</div>
					<div class="position-absolute top-0 start-0 m-4">
                        <p class="text-dark">รูปปกสินค้า</p>
                    </div>
					<?php for ($i = 1; $i < 6; $i++) {
						if ($data['product_image_' . $i] != '') { ?>
							<div class="carousel-item position-relative">
								<img src="dashboard/assets/images/products/<?php echo $data['product_image_' . $i] ?>" class="d-block w-100 image_cover" alt="Image Cover">
								<div class="position-absolute top-0 start-0 m-4">
									<p class="text-white">รูปประกอบ <?php echo $i ?></p>
								</div>
							</div>
					<?php } } ?>
				</div>
				<a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-bs-slide="prev"> <span class="carousel-control-prev-icon" aria-hidden="true"></span>
					<span class="visually-hidden">ก่อนหน้า</span>
				</a>
				<a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-bs-slide="next"> <span class="carousel-control-next-icon" aria-hidden="true"></span>
					<span class="visually-hidden">ถัดไป</span>
				</a>
			</div>
		</div>
		<div class="col-md-7">
			<div class="single-product-details mt-5 mt-sm-0">
				<?php echo $package_title ?>
				<h4 class=""><?php echo $product_name ?></h4>
				<h5 class="mb-5">ราคา <?php echo number_format($product_price) . " บาท" ?></h5>
				<div class="border border-1 p-3 <?php echo $package_border ?>">
					<?php 
					echo "<p>$package_text</p>";
					$query = mysqli_query($connect, $package_sql);
					$empty = mysqli_num_rows($query);
					if ($empty > 0) {
					while ($data = mysqli_fetch_array($query)) { ?>
						<p class="my-3 text-dark text-decoration-underline">
							<a href="?page=product_single&product_id=<?php echo $data['product_id'] ?>"><?php echo $data['product_name']; ?></a>
						</p>
					<?php } } else { 
						echo "<p class='my-3 text-dark'>$empty_text</p>"; 
					} ?>
				</div>
				<table class="table table-borderless mt-3">
					<tbody>
						<tr>
							<th>ประเภทสินค้า</th>
							<td><a href="?page=shop&product_type=<?php echo $product_type ?>" class=""><?php echo $product_type_name ?></a></td>
						</tr>
						<?php if ($product_freight > 0) { ?>
						<tr>
							<th>ค่าขนส่ง / ชิ้น</th>
							<td><?php echo $product_freight ?> บาท</td>
						</tr>
						<?php } if ($product_weight > 0) { ?>
						<tr>
							<th>น้ำหนัก</th>
							<td><?php echo $product_weight ?> กรัม</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
				<form action="dashboard/process/setting_visitor_buy.php" method="get">
				<input type="hidden" name="buyer_id" value="<?php echo $buyer_id ?>">
				<input type="hidden" name="product_id" value="<?php echo $product_id ?>">
				<hr class="">
				<div class="input-group mb-3 ">
				  	<input type="number" min="1" name="product_quantity" class="form-control" value="<?php echo $number ?>" aria-label="จำนวนสินค้า" aria-describedby="cart_insert">
				  	<?php echo $cart_url ?>
				</div>
				</form>
			</div>
		</div>
	</div>

	<hr class="d-block d-sm-none mt-5">
	<ul class="nav nav-pills mb-3 mt-5 " id="pills-tab" role="tablist">
		<li class="nav-item" role="presentation">
			<button class="button me-1 <?php if ($product_detail != '' || $product_type2 == 1) { echo "active";} ?>" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#detail" type="button" role="tab" aria-controls="pills-home" aria-selected="true">รายละเอียด</button>
		</li>
		<li class="nav-item" role="presentation">
			<button class="button <?php if ($product_detail == '' && $product_type2 == 0) { echo "active";} ?>" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#comment" type="button" role="tab" aria-controls="pills-profile" aria-selected="false">ความเห็น (รีวิว)</button>
		</li>
	</ul>

	<div class="tab-content" id="pills-tabContent">

		<div class="tab-pane fade border border-1 p-3 p-sm-5 <?php if ($product_detail != '' || $product_type2 == 1) { echo "show active";} ?>" id="detail" role="tabpanel" aria-labelledby="pills-home-tab">
			<?php if ($product_type2 == 0 && $product_detail != '') { 
				echo "<h3 class='post-sub-heading'>รายละเอียดสินค้า</h3>";
				echo $product_detail;
			} elseif ($product_type2 == 0 && $product_detail == '') { 
				echo "<h3 class='post-sub-heading'>รายละเอียดสินค้า</h3>";
				echo "<p>ไม่มีข้อมูลรายละเอียดสินค้า</p>";
			} elseif ($product_type2 == 1) { ?>
				<h3 class="post-sub-heading">รายละเอียดสินค้าทั้งหมดในแพ็กเกจ</h3>
				<table class="table table-bordered">
	            	<thead>
	            		<tr>
	            			<th>ชื่อสินค้า</th>
	            			<th>ราคาปกติ</th>
	            			<th>* ราคาพิเศษ</th>
	            			<th>จำนวน</th>
	            		</tr>
	            	</thead>
	            	<tbody>
	            		<?php
	            		$query = mysqli_query($connect, "SELECT * FROM system_package WHERE (package_main = '$product_id') AND (package_price > 0)");
						while ($data = mysqli_fetch_array($query)) { 

							$package_product 	= $data['package_product'];
							$package_name 		= $data['package_name'];
							$package_quantity 	= $data['package_quantity'];
							$package_price 		= number_format($data['package_price']);

							if ($package_product != 0) {
	            				$query_product = mysqli_query($connect, "SELECT * FROM system_product WHERE product_id = '$package_product' ");
	            				$data_product  = mysqli_fetch_array($query_product);
	            				$price = number_format($data_product['product_price']);
	            			}
	            			else {
	            				$price = "ไม่มีขาย";
	            			}
						?>
	            		<tr>
	            			<td><?php echo $package_name ?></td>
	            			<td><?php echo $price ?></td>
	            			<td class="text-success"><?php echo $package_price ?></td>
	            			<td><?php echo $package_quantity ?> ชิ้น / กล่อง</td>
	            		</tr>

	            		<?php } ?>
	            	</tbody>
				</table>
				<?php 
				$query = mysqli_query($connect, "SELECT * FROM system_package WHERE (package_main = '$product_id') AND (package_price = 0)");
				$empty = mysqli_num_rows($query);
				if ($empty > 0) { ?>
					<p>สินค้าที่แถมฟรี</p>
					<table class="table table-bordered text-success border-success mb-5">
		            	<thead>
		            		<tr>
		            			<th>ชื่อสินค้า</th>
		            			<th>จำนวน</th>
		            		</tr>
		            	</thead>
		            	<tbody>
		            		<?php while ($data = mysqli_fetch_array($query)) { ?>
		            		<tr>
		            			<td><?php echo $data['package_name'] ?></td>
		            			<td><?php echo $data['package_quantity'] ?> ชิ้น / กล่อง</td>
		            		</tr>
		            		<?php } ?>
		            	</tbody>
					</table>
				<?php } ?> 
				<p class="text-dark small"><b>หมายเหตุ</b> ข้อมูลรายละเอียดสินค้าจะทำการแสดงราคาปกติและราคาพิเศษ ซึ่งสินค้าราคาพิเศษจะจำหน่ายให้เฉพาะสมาชิกที่มีบัญชีตัวแทนขายเท่านั้น หากท่านมีความประสงค์ต้องการสมัครเป็นตัวแทนขาย สามารถติดต่อเข้ามาได้เลยค่ะ </p>
			<?php } ?>
		</div>

		<div class="tab-pane fade border border-1 p-3 p-sm-5 <?php if ($product_detail == '' && $product_type2 == 0) { echo "show active";} ?>" id="comment" role="tabpanel" aria-labelledby="pills-profile-tab">
			<div class="post-comments-form" id="comment">
				<h3 class="post-sub-heading">รีวิวสินค้า</h3>
				<?php
				$perpage        = 5;
				if (!isset($_GET['page_id'])) { 
				    $page_id    = 1;
				    $start      = 0;
				} 
				else { 
				    $page_id    = $_GET['page_id'];
				    $start      = ($page_id - 1) * $perpage;
				}
				$limit = " LIMIT $start, $perpage ";
				$sql   = "SELECT * FROM system_comment WHERE (comment_type = 0) AND (comment_link = '$product_id') AND (comment_direct = 0) ORDER BY comment_id DESC";
				$query = mysqli_query($connect, $sql . $limit);
				$empty = mysqli_num_rows($query);

				if ($empty > 0) {

					while ($data = mysqli_fetch_array($query)) { 

						$comment_id 	= $data['comment_id'];
						$comment_name 	= $data['comment_name'];
						$comment_detail = $data['comment_detail'];
						$comment_create = datethai($data['comment_create'], 2, $system_lang);

						?>
						<div class="mb-5">
							<h6><?php echo $comment_name ?></h6>
							<div class="mt-1">
								<time class="small"><?php echo $comment_create ?></time>
								<!--
			            		<a class="comment-button ms-5" href="?page=product_single&product_id=<?php echo $product_id ?>&comment_direct=<?php echo $data['comment_id'] ?>"><i class="tf-ion-chatbubbles"></i> ตอบกลับ</a>
			            		-->
							</div>
							<p class="mt-3"><?php echo $comment_detail ?></p>
						</div>
						<?php
						$query2 = mysqli_query($connect, "SELECT * FROM system_comment WHERE (comment_link = '$product_id') AND (comment_direct = '$comment_id') ");
						while ($data2  = mysqli_fetch_array($query2)) { 

							$comment_name2 		= $data2['comment_name'];
							$comment_detail2 	= $data2['comment_detail'];
							$comment_create2 	= datethai($data2['comment_create'], 2, $system_lang);
							?>
							<div class="ms-5 mb-5">
								<h6>
									<i class="bx bx-subdirectory-right me-3"></i>
									<?php echo $comment_name2 ?>
								</h6>
								<time class="small"><?php echo $comment_create2 ?></time>
								<p class="mt-3"><?php echo $comment_detail2 ?></p>
							</div>
					<?php } } } else { ?> 

					<div class="mb-5"><p>ไม่มีรีวิวสินค้า</p></div>

				<?php } ?>
			</div>
			<?php $url = "?page=product_single&product_id=$product_id"; 
        	pagination_visitor ($connect, $sql, $perpage, $page_id, $url); 

			/* if ($data_check_login) { ?>
				<div class="post-comments-form mt-5">
					<h3 class="post-sub-heading">เพิ่มความเห็น</h3>
					<form method="post" action="dashboard/process/setting_comment.php" class="row g-3">
						<input type="hidden" name="comment_buyer" value="<?php echo $buyer_id ?>">
						<input type="hidden" name="comment_link" value="<?php echo $product_id ?>">
						<input type="hidden" name="comment_direct" value="<?php echo $comment_direct ?>">
						<input type="hidden" name="comment_type" value="0">
						<div class="col-12">
							<input type="text" name="comment_name" value="<?php echo $data_check_login['buyer_name'] ?>" class="form-control" placeholder="ชื่อ *" maxlength="100" readonly>
						</div>
						<div class="col-12">
							<textarea name="comment_detail" class="form-control" rows="6" placeholder="ความคิดเห็น(กรุณาใช้ข้อความที่สุภาพ)" maxlength="400"></textarea>
						</div>
						<div class="d-flex">
							<button name="action" value="insert" class="btn btn-main">ส่งความเห็น</button>
						</div>
					</form>
				</div>
			<?php } */ ?>
		</div>
	</div>
</div>
<section class="products related-products section">
	<div class="container">
		<div class="row">
			<div class="title text-center">
				<h2>สินค้าแนะนำ</h2>
			</div>
		</div>
		<div class="row">
		<?php
		$product_title = "สินค้าแนะนำ";
		$query = mysqli_query($connect, "SELECT * FROM system_product WHERE (product_id != '$product_id') ORDER BY product_id ASC LIMIT 0, 8");
		while ($data = mysqli_fetch_array($query)) { 
			include('webpage_asset/include/include_product.php');
		} ?>
		</div>
	</div>
</section>