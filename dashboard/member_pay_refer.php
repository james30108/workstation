<?php isset($_GET["status"]) ? alert ($_GET["status"], $_GET["message"], $lang) : false; ?>
<title><?php echo $l_pay_form ?></title>
<div class="page-breadcrumb d-flex align-items-center mb-3">
    <div class="pe-3 text-primary">
        <div class="card-title d-flex align-items-center">
            <div><i class="bx bx-dish me-1 font-22 text-primary"></i></div>
            <h5 class="mb-0 text-primary"><?php echo $l_pay_form ?></h5>
        </div>
    </div>
</div>
<div class="card border-top border-0 border-4 border-primary">
    <div class="card-body p-5">
	<div class="row">
		<div class="col-12 col-sm-5"><!--<img src="assets/images/etc/pay_refer.jpg" width=100%>--></div>
		<div class="col">
			<form action="process/setting_pay.php" method="post" class="row g-3" enctype="multipart/form-data">
				<input type="hidden" name="pay_member" value="<?php echo $member_id ?>">
                <input type="hidden" name="pay_order" value="<?php echo $order_id ?>">
				<div class="col-12">
					<label class="form-label"><?php echo $l_type ?></label>
					<input type="text" class="form-control" maxlength="100" name="pay_title" placeholder="เช่น ค่าสมัครสมาชิก ค่าต่ออายุสมาชิก หรือ ค่าสั่งซื้อสินค้า" required>
				</div>
				<div class="col-6">
					<label class="form-label"><?php echo $l_pay_to ?></label>
					<select name="pay_bank" class="form-control" required>
						<option value="" selected>กรุณาเลือกธนาคาร</option>                    				                        
						<option value="ธนาคารกสิกรไทย">ธนาคารกสิกรไทย </option>
					</select>
				</div>
				<div class="col-6">
					<label class="form-label"><?php echo $l_money ?></label>
					<input type="number" class="form-control" name="pay_money" placeholder="ยอดเงิน" required>
				</div>
				<div class="col-6">
					<label class="form-label"><?php echo $l_date ?></label>
					<input type="date" class="form-control" name="pay_date">
				</div>
				<div class="col-6">
					<label class="form-label"><?php echo $l_time ?></label>
					<input type="time" class="form-control" name="pay_time">
				</div>
				<div class="col-12">
					<label class="form-label"><?php echo $l_pay_slip ?></label>
					<input type="file" class="form-control" name="pay_image">
				</div>
				<div class="col-12">
					<label class="form-label"><?php echo $l_descrip ?></label>
					<textarea class="form-control" name="pay_detail" placeholder="<?php echo $l_descrip ?>"></textarea>
				</div>
				<div class="col-12 mt-5 d-flex">
                    <button class="btn btn-success btn-sm ms-auto ms-auto" name="action" value="insert"><?php echo $l_save ?></button>
				</div>
			</form>
		</div>
	</div>
	</div>
</div>