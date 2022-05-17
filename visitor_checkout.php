<?php 

$buyer_name    = $data_check_login['buyer_name'];
$buyer_tel     = $data_check_login['buyer_tel'];
$buyer_email   = $data_check_login['buyer_email'];
$buyer_address = $data_check_login['buyer_address'];
$buyer_district= $data_check_login['buyer_district'];
$buyer_amphure = $data_check_login['buyer_amphure'];
$buyer_province= $data_check_login['buyer_province'];
$buyer_zipcode = $data_check_login['buyer_zipcode'];

?>
<title>ชำระเงิน</title>
<script>  
   $(document).ready(function(){
      $("#order_type_buy").change(function(){
         var order_type_buy = $(this).val();
         
         $.get( "dashboard/process/ajax/ajax_visitor_buy.php", { action: order_type_buy }, function( data ) {
            //alert(order_type_buy);
            $("#type_buy").html( data );
         });
      });

   });
</script>
<section class="page-header">
   <div class="container">
   <nav aria-label="breadcrumb">
      <h1 class="page-name">ชำระเงิน</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page"><a href="index.php">หน้าหลัก</a></li>
            <li class="breadcrumb-item active" aria-current="page">ชำระเงิน</li>
        </ol>
    </nav>
   </div>
</section>
<div class="page-wrapper">
   <form action="dashboard/process/setting_buy.php" method="post" enctype="multipart/form-data" class="checkout-form">
   <div class="checkout shopping">
      <div class="container">
         <div class="row">
            <div class="col-md-8">
               <div class="block billing-details">
                  <h4 class="widget-title">สำหรับจัดส่งสินค้า (กรุณากรอกตามความจริง)</h4>
                  <div class="form-group">
                     <label>ชื่อผู้รับสินค้า</label>
                     <input type="text" name="order_buyer" class="form-control" value="<?php echo $buyer_name ?>" placeholder="ชื่อผู้รับสินค้า">
                  </div>
                  <div class="form-group">
                     <label>เบอร์โทร</label>
                     <input type="text" name="order_buyer_tel" class="form-control" value="<?php echo $buyer_tel ?>" placeholder="เบอร์โทร">
                  </div>
                  <div class="form-group">
                     <label>อีเมล</label>
                     <input type="email" name="order_buyer_email" class="form-control" value="<?php echo $buyer_email ?>" placeholder="อีเมล@email.com">
                  </div>
                  <div class="form-group">
                     <label>ที่อยู่</label>
                     <input type="text" name="order_address" class="form-control" value="<?php echo $buyer_address ?>" placeholder="ที่อยู่">
                  </div>
                   <div class="checkout-country-code clearfix">
                     <div class="form-group">
                        <label>ตำบล</label>
                        <input type="text" name="order_district" class="form-control" value="<?php echo $buyer_district ?>" placeholder="อำเภอ">
                     </div>
                     <div class="form-group" >
                        <label>อำเภอ</label>
                        <input type="text" name="order_amphur" class="form-control" value="<?php echo $buyer_amphure ?>" placeholder="อำเภอ">
                     </div>
                  </div>
                  <div class="checkout-country-code clearfix">
                     <div class="form-group">
                        <label>จังหวัด</label>
                        <input type="text" name="order_province" class="form-control" value="<?php echo $buyer_province ?>" placeholder="จังหวัด">
                     </div>
                     <div class="form-group" >
                        <label>รหัสไปรษณีย์</label>
                        <input type="text" name="order_zipcode" class="form-control" value="<?php echo $buyer_zipcode ?>" name="zipcode" placeholder="ไปรษณีย์">
                     </div>
                  </div>
               </div>
               <div class="block">
                  <h4 class="widget-title">ชำระเงิน</h4>
                  <div class="form-group half-width padding-left">
                     <select class="form-control" name="order_type_buy" id="order_type_buy" required>
                           <option value="0" selected>โอนจ่าย</option>
                           <option value="4">ชำระเงินผ่านบัตรเครดิต</option>
                           <option value="5">ชำระเงินปลายทาง</option>
                     </select>
                  </div>
                  <div id="type_buy">
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
                  </div>
                  <button name="action" value="confirm_buyer" class="btn btn-main mt-20">ยืนยันคำสั่งซื้อ</button>
               </div>
            </div>
            <div class="col-md-4">
               <div class="product-checkout-details">
                  <div class="block">
                     <h4 class="widget-title">สรุปรายการสั่งซื้อ</h4>
                     <?php
                     $order_price   = 0;
                     $order_freight = 0;
                     $order_point   = 0;
                     $order_amount  = 0;
                     $query = mysqli_query($connect, "SELECT system_cart.*, system_product.*
                        FROM system_cart
                        INNER JOIN system_product ON (system_cart.cart_product_id = system_product.product_id)
                        WHERE (cart_member_id = '$buyer_id')");
                     while ($data = mysqli_fetch_array($query)) {
                        
                        // Default Data 
                        $quantity     = $data['cart_product_amount'];
                        $freight      = $data['product_freight'] * $quantity;
                        $price        = $data['product_price'] * $quantity;
                        $point        = $data['product_point'] * $quantity;
                        
                        // Total Data
                        $order_price   += $price + $freight;
                        $order_freight += $freight;
                        $order_point   += $point;
                        $order_amount  += $quantity;
                        
                        $product_image_cover = $data['product_image_cover'] != "" ? $data['product_image_cover'] : "dashboard/assets/images/etc/example.png";
                        $product_id          = $data['product_id'];
                        $product_name        = $data['product_name'];
                        $product_price       = number_format($data['product_price']);
                        $product_freight     = $data['product_freight'] > 0 ? "(ขนส่ง " . $data['product_freight'] . " )" : false;
                        $cart_id             = $data['cart_id'];

                        ?>
                        <div class="media product-card mb-3">
                           <div class="row">
                              <div class="col-4">
                                 <a class="pull-left" href="?page=product_single&product_id=<?php echo $product_id ?>">
                                    <img src="<?php echo $product_image_cover ?>" alt="Cover" class="img-thumbnail border border-1">
                                 </a>
                              </div>
                              <div class="col-8">
                                 <div class="media-body">
                                    <h6><a href="?page=product_single&product_id=<?php echo $product_id ?>"><?php echo $product_name ?></a></h6>
                                    <p class="price"><?php echo $quantity ?> x <?php echo $product_price ?> บาท 
                                    <?php echo $product_freight ?>
                                    </p>
                                    <a class="product-remove" href="dashboard/process/setting_visitor_buy.php?action=delete_cart&cart_id=<?php echo $cart_id ?>">ลบ</a>
                                 </div>
                              </div>
                           </div>
                        </div>
                     <?php } ?>
                     <ul class="summary-prices">
                        <li>
                           <span>ราคาสินค้าทั้งหมด:</span>
                           <span class="price"><?php echo number_format($order_price - $order_freight) ?> บาท</span>
                        </li>
                        <?php if ($order_freight > 0) { ?>
                        <li>
                           <span>ค่าขนส่ง:</span>
                           <span><?php echo number_format($order_freight) ?> บาท</span>
                        </li>
                        <?php } ?>
                     </ul>
                     <div class="summary-total">
                        <span>รวมทั้งหมด</span>
                        <span><?php echo number_format($order_price) ?> บาท</span>
                     </div>
                     <div class="verified-icon">
                        <img src="webpage_asset/images/shop/verified.png">
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <input type="hidden" name="order_buyer_id"   value="<?php echo $buyer_id ?>">
   <input type="hidden" name="order_member"     value="<?php echo $data_check_login['buyer_direct'] ?>">
   <input type="hidden" name="order_price"      value="<?php echo $order_price ?>">
   <input type="hidden" name="order_freight"    value="<?php echo $order_freight ?>">
   <input type="hidden" name="order_point"      value="<?php echo $order_point ?>">
   <input type="hidden" name="order_amount"     value="<?php echo $order_amount ?>">
   <input type="hidden" name="pay_money"        value="<?php echo $order_price ?>">
   </form>
</div>