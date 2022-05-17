<?php include('../function.php'); 

$lang = $system_lang == 1 ? $data_check_login['member_lang'] : 0;
include("../include_lang.php");

if ($_GET['action'] == '0') { ?>

   <div class="col-12">
      <div class="card p-3 border border-1 border-success position-relative">
         <img src="assets/images/etc/kasikorn.png" class="image mx-sm-auto">
      </div>
   </div>
   <div class="col-12 col-sm-4">
      <label class="form-label"><?php echo $l_bankown ?> <span class="text-danger">*</span></label>
      <input type="text" class="form-control" name="pay_buyer" value="<?php echo $member_bank_own ?>" placeholder="<?php echo $l_bankown ?>" required>
   </div>
   <div class="col-12 col-sm-4">
      <label class="form-label"><?php echo $l_date ?> <span class="text-danger">*</span></label>
      <input type="date" class="form-control" name="pay_date" required>
   </div>
   <div class="col-12 col-sm-4">
      <label class="form-label"><?php echo $l_time ?> <span class="text-danger">*</span></label>
      <input type="time" class="form-control" name="pay_time" required>
   </div>
   <div class="col-12">
      <label class="form-label"><?php echo $l_pay_slip ?> <span class="text-danger">*</span></label>
      <input type="file" class="form-control" name="pay_image" required>
   </div>

<?php } elseif ($_GET['action'] == '1' || $_GET['action'] == '3') { ?>

   <div class="col-12 mt-3">
   <div class="card p-3 position-relative border border-1 border-success">
      <div class="card-title d-flex align-items-center">
         <div><i class="bx bx-wallet-alt me-1 font-22 text-success"></i></div>
         <h5 class="mb-0 text-success"><?php echo $l_ewallet ?>
            <?php echo number_format($_GET['ewallet'], 2) . $l_bath ?>
         </h5>
      </div>
   </div>
   </div>

<?php } elseif ($_GET['action'] == '4') { ?>

   <div class="col-12 mt-3">
   <div class="card p-3 position-relative border border-1 border-success">
      <div class="card-title d-flex align-items-center">
         เมื่อกดยืนยันคำสั่งซื้อแล้ว QRCode สำหรับชำระเงินจึงจะแสดงออกมา
      </div>
   </div>
   </div>

<?php } ?>