<?php include('../function.php'); 

if ($_GET['action'] == '0') { ?>
   <p>กรุณาชำระเงินก่อนทำการยืนยัน</p>
   <div class="checkout-product-details">
      <div class="payment">
         <div class="card-details">
            <div class="checkout-country-code clearfix">
            <div class="form-group">
               <label>ชื่อบัญชี <span class="required">*</span></label>
               <input type="text" class="form-control" name="pay_buyer" placeholder="เจ้าของบัญชี" required>
            </div>
            </div>
            <div class="checkout-country-code clearfix">
               <div class="form-group half-width padding-left">
                  <label>วันที่ในสลิป <span class="required">*</span></label>
                  <input type="date" class="form-control" name="pay_date" >
               </div>
               <div class="form-group half-width padding-left">
                  <label>เวลาในสลิป <span class="required">*</span></label>
                  <input type="time" class="form-control" name="pay_time" >
               </div>
            </div>
            <div class="form-group half-width padding-left">
               <label>หลักฐานการโอน <span class="required">*</span></label>
               <input type="file" class="form-control" name="pay_image" >
            </div>
         </div>
      </div>
   </div>
<?php } ?>