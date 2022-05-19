<title><?php echo $l_pay ?></title>
<script>  
   $(document).ready(function(){
      $("#order_type_buy").change(function(){
         var order_type_buy = $(this).val();
         $.get( "dashboard/process/ajax/ajax_visitor_buy.php", { action: order_type_buy }, function( data ) {
            $("#type_buy").html( data );
         });
      });
   });
</script>
<section class="page-header">
   <div class="container">
   <nav aria-label="breadcrumb">
      <h1 class="page-name"><?php echo $l_pay ?></h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page"><a href="index.php"><?php echo $l_home ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $l_pay ?></li>
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
                     <label><?php echo $l_order_buyer ?></label>
                     <input type="text" name="order_buyer" class="form-control" value="<?php echo $buyer_name ?>" placeholder="<?php echo $l_order_buyer ?>">
                  </div>
                  <div class="form-group">
                     <label><?php echo $l_tel ?></label>
                     <input type="text" name="order_buyer_tel" class="form-control" value="<?php echo $buyer_tel ?>" placeholder="<?php echo $l_tel ?>">
                  </div>
                  <div class="form-group">
                     <label><?php echo $l_email ?></label>
                     <input type="email" name="order_buyer_email" class="form-control" value="<?php echo $buyer_email ?>" placeholder="email@email.com">
                  </div>
                  <div class="form-group">
                     <label><?php echo $l_address ?></label>
                     <input type="text" name="order_address" class="form-control" value="<?php echo $buyer_address ?>" placeholder="<?php echo $l_address ?>">
                  </div>
                   <div class="checkout-country-code clearfix">
                     <div class="form-group">
                        <label><?php echo $l_district ?></label>
                        <input type="text" name="order_district" class="form-control" value="<?php echo $buyer_district ?>" placeholder="<?php echo $l_district ?>">
                     </div>
                     <div class="form-group" >
                        <label><?php echo $l_amphures ?></label>
                        <input type="text" name="order_amphur" class="form-control" value="<?php echo $buyer_amphure ?>" placeholder="<?php echo $l_amphures ?>">
                     </div>
                  </div>
                  <div class="checkout-country-code clearfix">
                     <div class="form-group">
                        <label><?php echo $l_provinces ?></label>
                        <input type="text" name="order_province" class="form-control" value="<?php echo $buyer_province ?>" placeholder="<?php echo $l_provinces ?>">
                     </div>
                     <div class="form-group" >
                        <label><?php echo $l_zipcode ?></label>
                        <input type="text" name="order_zipcode" class="form-control" value="<?php echo $buyer_zipcode ?>" name="zipcode" placeholder="<?php echo $l_zipcode ?>">
                     </div>
                  </div>
               </div>
               <div class="block">
                  <h4 class="widget-title"><?php echo $l_paydetail ?></h4>
                  <div class="form-group half-width padding-left">
                     <select class="form-control" name="order_type_buy" id="order_type_buy" required>
                           <option value="0" selected><?php echo $l_order_pay0 ?></option>
                           <option value="4"><?php echo $l_order_pay4 ?></option>
                           <option value="5"><?php echo $l_order_pay5 ?></option>
                     </select>
                  </div>
                  <div id="type_buy">
                     <p class="my-3 text-danger"><?php echo $lang == 0 ? "กรุณาชำระเงินก่อนทำการยืนยัน" : "Please pay before submit order" ; ?></p>
                     <div class="row">
                        <div class="form-group col-12">
                           <label class="ms-3"><?php echo $l_bankown ?> <span class="required">*</span></label>
                           <input type="text" class="form-control" name="pay_buyer" placeholder="<?php echo $l_bankown ?>" required>
                        </div>
                        <div class="form-group col-12 col-sm-6">
                           <label class="ms-3"><?php echo $l_date ?> <span class="required">*</span></label>
                           <input type="date" class="form-control" name="pay_date">
                        </div>
                        <div class="form-group col-12 col-sm-6">
                           <label class="ms-3"><?php echo $l_time ?> <span class="required">*</span></label>
                           <input type="time" class="form-control" name="pay_time">
                        </div>
                        <div class="form-group col-12">
                           <label class="ms-3"><?php echo $l_pay_slip ?> <span class="required">*</span></label>
                           <input type="file" class="form-control" name="pay_image">
                        </div>
                     </div>
                  </div>
                  <button name="action" value="confirm_buyer" class="btn btn-main mt-20"><?php echo $l_submit ?></button>
               </div>
            </div>
            <div class="col-md-4">
               <div class="product-checkout-details">
                  <div class="block">
                     <h4 class="widget-title"><?php echo $l_checkbill_sum ?></h4>
                     <?php
                     $order_price   = 0;
                     $order_freight = 0;
                     $order_point   = 0;
                     $order_amount  = 0;
                     $query = mysqli_query($connect, "SELECT system_cart.*, system_product.*
                        FROM system_cart
                        INNER JOIN system_product ON (system_cart.cart_product_id = system_product.product_id)
                        WHERE (cart_buyer_id = '$buyer_id')");
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
                        $product_freight     = $data['product_freight'] > 0 ? "($l_fright " . $data['product_freight'] . " )" : false;
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
                                    <p class="price"><?php echo "$quantity x $product_price $l_bath $product_freight" ?></p>
                                    <a class="product-remove" href="dashboard/process/setting_visitor_buy.php?action=delete_cart&cart_id=<?php echo $cart_id ?>"><?php echo $l_delete ?></a>
                                 </div>
                              </div>
                           </div>
                        </div>
                     <?php } ?>
                     <ul class="summary-prices">
                        <li>
                           <span><?php echo $l_invoice_pdt ?> :</span>
                           <span class="price"><?php echo number_format($order_price - $order_freight) . $l_bath ?></span>
                        </li>
                        <?php if ($order_freight > 0) { ?>
                        <li>
                           <span><?php echo $l_fright ?> :</span>
                           <span><?php echo number_format($order_freight) . $l_bath ?></span>
                        </li>
                        <?php } ?>
                     </ul>
                     <div class="summary-total">
                        <span><?php echo $l_invoice_totprice ?></span>
                        <span><?php echo number_format($order_price) . $l_bath ?></span>
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
   <input type="hidden" name="order_member"     value="<?php echo $buyer_direct ?>">
   <input type="hidden" name="order_price"      value="<?php echo $order_price ?>">
   <input type="hidden" name="order_freight"    value="<?php echo $order_freight ?>">
   <input type="hidden" name="order_point"      value="<?php echo $order_point ?>">
   <input type="hidden" name="order_amount"     value="<?php echo $order_amount ?>">
   <input type="hidden" name="pay_money"        value="<?php echo $order_price ?>">
   </form>
</div>