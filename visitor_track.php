<title>ตรวจสอบคำสั่งซื้อ</title>
<section class="page-header">
	<div class="container">
	<nav aria-label="breadcrumb">
		<h1 class="page-name">ตรวจสอบคำสั่งซื้อ</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page"><a href="index.php">หน้าแรก</a></li>
            <li class="breadcrumb-item active" aria-current="page">ตรวจสอบคำสั่งซื้อ</li>
        </ol>
    </nav>
	</div>
</section>
<section class="page-wrapper">
	<div class="contact-section">
		<div class="container">
			<div class="row">
				<div class="contact-form col-12 col-sm-6">
					<form id="contact-form" method="get" action="dashboard/process/setting_config.php" class="row g-3">
						<div class="col-12">
							<input type="text" placeholder="หมายเลขคำสั่งซื้อ *ตัวอย่าง 0000001" class="form-control" name="order_code">
						</div>
						<div class="col-12">
							<input type="text" placeholder="อีเมลที่ใช้ในการซื้อสินค้า" class="form-control" name="order_buyer_email">
						</div>
						<div id="cf-submit">
							<button id="contact-submit" class="btn btn-transparent" name="action" value="tracking">ยืนยัน</button>
						</div>						
					</form>
				</div>
				<div class="col-12 col-sm-6">
					<?php 
					if (isset($_GET['action']) && $_GET['action'] == 'have') {
						
						$order_code = $_GET['order_code'];
						$query = mysqli_query($connect, "SELECT * FROM system_order WHERE order_code = '$order_code' ");
						$data = mysqli_fetch_array($query); ?>

						<div class="border border-2 p-3">
							<h6>คำสั่งซื้อของท่าน</h6>
							<p><?php echo $data['order_code'] ?></p>
							<h6>
								สถานะ 
								<?php
								if ($data['order_status'] == 0) {
									echo "รอการตรวจสอบจากเจ้าหน้าที่";
								} elseif ($data['order_status'] == 1) {
									echo "<font color=green>ตรวจสอบเรียบร้อย กำลังจัดส่ง</font>";
								} elseif ($data['order_status'] == 2) {
									echo "<font color=red>คำสั่งซื้อถูกยกเลิกโดยเจ้าหน้าที่ </font>";
								}
								?>
							</h6>
						</div>
					<?php } if (isset($_GET['action']) && $_GET['action'] == 'not_have') { ?>
						
						<div class="border border-2 p-3">
							<h6 class="text-danger">ไม่พบคำสั่งสั่งซื้อนี้ในระบบ</h6>
						</div>
					<?php }  ?>
				</div>
			</div>
		</div>
	</div>
</section>