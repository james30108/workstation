<?php include('../function.php'); 

if ($_GET['action'] == '0') { ?>
   <p>กรุณาชำระเงินก่อนทำการยืนยัน</p>
   <div class="row">
      <div class="form-group col-12">
         <label class="ms-3">ชื่อบัญชี <span class="required">*</span></label>
         <input type="text" class="form-control" name="pay_buyer" placeholder="ชื่อบัญชี" required>
      </div>
      <div class="form-group col-12 col-sm-6">
         <label class="ms-3">วันที่ในสลิป <span class="required">*</span></label>
         <input type="date" class="form-control" name="pay_date">
      </div>
      <div class="form-group col-12 col-sm-6">
         <label class="ms-3">เวลาในสลิป <span class="required">*</span></label>
         <input type="time" class="form-control" name="pay_time">
      </div>
      <div class="form-group col-12">
         <label class="ms-3">หลักฐานการโอน <span class="required">*</span></label>
         <input type="file" class="form-control" name="pay_image">
      </div>
   </div>
<?php } ?>