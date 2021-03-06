<?php 

$header_url       = "dashboard/assets/images/bg-themes/1.png";
$header_name      = !isset($_GET['action']) ? "ข้อมูลส่วนตัว" : "ประวัติการซื้อ";
$header_css       = !isset($_GET['action']) ? "btn btn-main" : "text-decoration-underline small";
$header_css2      = isset($_GET['action']) ? "btn btn-main" : "text-decoration-underline small";
$header_detail    = "หน้าแสดงผลข้อมูลส่วนตัว โดยสามารถทำกาแก้ไขข้อมูลได้ และสามารถเรียกดูข้อมูลรายการสั่งซื้อสินค้าของท่านได้";
include('webpage_asset/include/include_header.php');

$header_name    = $data_check_login['buyer_name'];
$buyer_tel      = $data_check_login['buyer_tel'];
$buyer_email    = $data_check_login['buyer_email'];
$buyer_address  = $data_check_login['buyer_address'];
$buyer_district = $data_check_login['buyer_district'];
$buyer_amphure  = $data_check_login['buyer_amphure'];
$buyer_province = $data_check_login['buyer_province'];
$buyer_zipcode  = $data_check_login['buyer_zipcode'];

?>
<div class="container">
   <div class="my-3">
      <a href="index.php?page=profile" class="me-3 <?php echo $header_css ?>"><?php echo $l_editprofile ?></a>
      <a href="index.php?page=profile&action=order" class="me-3 <?php echo $header_css2 ?>"><?php echo $l_resold_order ?></a>
   </div>
   <?php if (!isset($_GET['action'])) { ?>
      <form action="dashboard/process/setting_buyer.php" method="post" enctype="multipart/form-data" class="checkout-form mt-3">
         <input type="hidden" name="buyer_id" value="<?php echo $buyer_id ?>">
         <div class="checkout shopping">
            <div class="block billing-details">
               <h4 class="widget-title">ข้อมูลส่วนตัวของท่านจะนำไปใช้ในการจัดส่งสินค้า (กรุณากรอกตามความจริง)</h4>
               <div class="form-group">
                  <label><?php echo $l_name ?></label>
                  <input type="text" name="buyer_name" class="form-control" value="<?php echo $header_name ?>" placeholder="<?php echo $l_name ?>">
               </div>
               <div class="form-group">
                  <label><?php echo $l_tel ?></label>
                  <input type="text" name="buyer_tel" class="form-control" value="<?php echo $buyer_tel ?>" placeholder="<?php echo $l_tel ?>">
               </div>
               <div class="form-group">
                  <label><?php echo $l_email ?></label>
                  <input type="email" name="buyer_email" class="form-control" value="<?php echo $buyer_email ?>" placeholder="Example@email.com">
               </div>
               <div class="form-group">
                  <label><?php echo $l_address ?></label>
                  <input type="text" name="buyer_address" class="form-control" value="<?php echo $buyer_address ?>" placeholder="<?php echo $l_address ?>">
               </div>
               <div class="checkout-country-code clearfix">
                  <div class="form-group">
                     <label><?php echo $l_district ?></label>
                     <input type="text" name="buyer_district" class="form-control" value="<?php echo $buyer_district ?>" placeholder="<?php echo $l_district ?>">
                  </div>
                  <div class="form-group">
                     <label><?php echo $l_amphures ?></label>
                     <input type="text" name="buyer_amphure" class="form-control" value="<?php echo $buyer_amphure ?>" placeholder="<?php echo $l_amphures ?>">
                  </div>
               </div>
               <div class="checkout-country-code clearfix">
                  <div class="form-group">
                     <label><?php echo $l_provinces ?></label>
                     <input type="text" name="buyer_province" class="form-control" value="<?php echo $buyer_province ?>" placeholder="<?php echo $l_provinces ?>">
                  </div>
                  <div class="form-group">
                     <label><?php echo $l_zipcode ?></label>
                     <input type="text" name="buyer_zipcode" class="form-control" value="<?php echo $buyer_zipcode ?>" name="zipcode" placeholder="<?php echo $l_zipcode ?>">
                  </div>
               </div>
               <button name="action" value="edit_profile" class="btn btn-main mt-3"><?php echo $l_save ?></button>
            </div>
         </div>
      </form>
      <hr class="my-3">
      <form action="dashboard/process/setting_buyer.php" method="post" enctype="multipart/form-data" class="checkout-form">
         <input type="hidden" name="buyer_id" value="<?php echo $buyer_id ?>">
         <div class="checkout shopping">
            <div class="block billing-details">
               <h4 class="widget-title"><?php echo $l_change_pass ?></h4>
               <div class="form-group">
                  <label>Password</label>
                  <input type="password" name="buyer_pass" class="form-control" placeholder="Current Password">
               </div>
               <div class="form-group">
                  <label>New Password</label>
                  <input type="password" name="buyer_pass_new" class="form-control" minlength="6" maxlength="15" placeholder="New Password">
               </div>
               <div class="form-group">
                  <label>Re Password</label>
                  <input type="password" name="buyer_pass_recheck" class="form-control" minlength="6" maxlength="15" placeholder="Renew Password">
               </div>
               <button name="action" value="edit_password" class="btn btn-main mt-3"><?php echo $l_save ?></button>
            </div>
         </div>
      </form>
   <?php } elseif (isset($_GET['action']) && $_GET['action'] == 'order') { ?>
      <div class="mt-5">
         <?php
         $perpage        = 20;
         if (!isset($_GET['page_id'])) { 
             $page_id    = 1;
             $start      = 0;
         } 
         else { 
             $page_id    = $_GET['page_id'];
             $start      = ($page_id - 1) * $perpage;
         }

         $limit = " LIMIT $start, $perpage ";

         $sql   = "SELECT * FROM system_order WHERE order_buyer_id = '$buyer_id' ORDER BY order_id DESC";
         $query = mysqli_query($connect, $sql . $limit);
         $count = mysqli_num_rows($query);
         if ($count > 0) {
            while ($data = mysqli_fetch_array($query)) { 

               $order_id      = $data['order_id'];
               $order_code    = $data['order_code'];
               $order_status  = $data['order_status'];
               $order_create  = datethai($data['order_create'], 0, $lang);
               ?>

               <div class="d-flex col-12">
                  <div>
                     <h5><?php echo "$l_invoice $order_code" ?></h5>
                     <?php echo "<p>$order_create</p><br>";
                        if       ($order_status == 0) { echo "<font color=gray>$l_order_status0</font>";} 
                        elseif   ($order_status == 1 || $order_status == 2) { echo "<font color=red>$l_order_status1</font>"; } 
                        elseif   ($order_status >= 3 || $order_status == 4) { echo "<font color=green>$l_order_status3</font>"; } 
                        elseif   ($order_status == 5) { echo "<font color=green>$l_order_status4</font>"; } 
                     ?>
                  </div>
                  <a href="?page=profile&action=order_detail&order_id=<?php echo $order_id ?>" title="Detail" class="ms-auto text-decoration-underline"><?php echo $l_detail ?></a>
               </div>
               <hr class="my-3">
               
         <?php } } else { echo "<tr><td colspan='5'>$l_notfound</td></tr>"; } 

         $url = "?page=profile&action=order"; 
         pagination_visitor ($connect, $sql, $perpage, $page_id, $url); ?>

      </div>
   <?php } elseif (isset($_GET['action']) && $_GET['action'] == 'order_detail') {

      if (isset($_GET['order_id']) && $_GET['order_id'] != '') {  
         $_SESSION['order_id'] = $_GET['order_id'];
         header("location:?page=profile&action=order_detail");
      }

      $order_id = isset($_SESSION['order_id']) ? $_SESSION['order_id'] : header("location:?page=profile&action=order");

      $query = mysqli_query($connect, "SELECT * FROM system_order WHERE order_id = '$order_id' ");
      $data  = mysqli_fetch_array($query); 

      $order_id         = $data['order_id'];
      $order_code       = $data['order_code'];
      $order_status     = $data['order_status'];
      $order_track_name = $data['order_track_name'];
      $order_track_id   = $data['order_track_id'];

      $order_buyer      = $data['order_buyer'];
      $order_address    = $data['order_address'];
      $order_district   = $data['order_district'];
      $order_track_id   = $data['order_track_id'];
      $order_track_id   = $data['order_track_id'];
      ?>
      <hr>
      <a href="index.php?page=profile&action=order" class="me-3 text-decoration-underline small"><?php echo $l_back ?></a>
      <div class="my-5 row">
         <div class="col-12 col-sm-3 d-flex">
            <h5><?php echo $l_invoice ?> <br><?php echo $order_code ?></h5>
            <hr class="vr ms-auto" style="height: 40px;">
         </div>
         <div class="col-4 col-sm-3 d-flex">
            <div>
            <?php echo "$l_status<br>";
               if       ($order_status == 0) { echo "<font color=gray>$l_order_status0</font>";} 
               elseif   ($order_status == 1 || $order_status == 2) { echo "<font color=red>$l_order_status1</font>"; } 
               elseif   ($order_status >= 3 || $order_status == 4) { echo "<font color=green>$l_order_status3</font>"; } 
               elseif   ($order_status == 5) { echo "<font color=green>$l_order_status4</font>"; } 
            ?>
            </div>
            <hr class="vr ms-auto" style="height: 40px;">
         </div>
         
         <div class="col-4 col-sm-3 d-flex"><?php echo $l_trackname ?><br><?php echo $order_track_name ?>
            <hr class="vr ms-auto" style="height: 40px;">
         </div>
         
         <div class="col-4 col-sm-3 d-flex"><?php echo $l_trackid ?><br><?php echo $order_track_id ?></div>
      </div>
      
      <?php 
      $sql_detail     = mysqli_query($connect, "SELECT system_order_detail.*, system_product.* 
         FROM system_order_detail
         INNER JOIN system_product ON (system_order_detail.order_detail_product = system_product.product_id)
         WHERE order_detail_order = '$order_id' ");
      while ($detail  = mysqli_fetch_array($sql_detail)) { 

         $product_id          = $detail['product_id'];
         $product_name        = $detail['product_name'];
         $order_detail_price  = $detail['order_detail_price'];
         $order_detail_amount = $detail['order_detail_amount'];
         $order_detail_review = $detail['order_detail_review'];
         $order_detail_id     = $detail['order_detail_id'];
         ?>
         <div class="row my-3">

            <div class="col-12 col-sm-6">
               <b>
                  <a href="?page=product_single&product_id=<?php echo $product_id ?>" target="_blank">
                     <?php echo $product_name ?>
                  </a>
               </b>
            </div>

            <div class="col-4 col-sm-2"><?php echo number_format($order_detail_price) . " x " . $order_detail_amount ?></div>
            <div class="col-4 col-sm-2"><?php echo "ยอดรวม : " . $order_detail_price * $order_detail_amount . $l_bath ?></div>
            <div class="col-4 col-sm-2">

            <?php if ($order_detail_review == 0) { ?>

               <button class="me-3 text-decoration-underline small bg-transparent border-0 text-primary" data-bs-toggle="modal" data-bs-target="#review_<?php echo $detail['order_detail_id'] ?>"><?php echo $l_review_insert ?></button>   

            <?php } else { ?> 

               <div class="me-3 small"><?php echo $l_review_complete ?></div>

            <?php } ?>

         </div>
         </div>
         <hr>

         <!-- Modal -->
         <div class="modal fade" id="review_<?php echo $order_detail_id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-header">
               <h5 class="modal-title" id="exampleModalLabel"><?php echo $l_review ?></h5>
               <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
               </div>
               <div class="modal-body">
               <form method="post" action="dashboard/process/setting_comment.php">
                  <input type="hidden" name="comment_buyer"    value="<?php echo $buyer_id ?>">
						<input type="hidden" name="comment_link"     value="<?php echo $product_id ?>">
						<input type="hidden" name="comment_type"     value="0">
                  <input type="hidden" name="order_detail_id"  value="<?php echo $order_detail_id ?>">
                  <input type="hidden" name="comment_name"     value="<?php echo $data_check_login['buyer_name'] ?>">

                  <p><?php echo $product_name ?></p>
                  <textarea name="comment_detail" class="form-control" rows="6" placeholder="ความคิดเห็น(กรุณาใช้ข้อความที่สุภาพ)" maxlength="400"></textarea>
                  <button name="action" value="insert" class="btn btn-main my-3"><?php echo $l_save ?></button>
               </form>
               </div>
            </div>
         </div>
         </div>

      <?php } 

      $order_id         = $data['order_id'];
      $order_code       = $data['order_code'];
      $order_status     = $data['order_status'];
      $order_track_name = $data['order_track_name'];
      $order_track_id   = $data['order_track_id'];
      
      $order_buyer      = $data['order_buyer'];
      $order_address    = $data['order_address'];
      $order_district   = $data['order_district'];
      $order_amphur     = $data['order_amphur'];
      $order_province   = $data['order_province'];
      $order_zipcode    = $data['order_zipcode'];
      $order_buyer_tel  = $data['order_buyer_tel'];
      $order_detail     = $data['order_detail'];
      $order_price      = $data['order_price'];
      $order_freight    = $data['order_freight'];
      $order_create     = datethai($data['order_create'], 0, $lang);
      $order_freight    = $data['order_freight'];

      ?>
      <div class="border border-1 p-3 my-5">
         <table class="table table-borderless table-sm">
            <tbody>
               <tr>
                  <th><?php echo $l_name ?></th>
                  <td><?php echo $data['order_buyer'] ?></td>
               </tr>
               <tr>
                  <th><?php echo $l_invoice_addr ?></th>
                  <td>
                     <?php echo "$order_address $l_district $order_district $l_amphures $order_amphur $l_provinces $order_province $l_zipcode $order_zipcode" ?>
                  </td>
               </tr>
               <tr>
                  <th><?php echo $l_tel ?></th>
                  <td><?php echo $order_buyer_tel ?></td>
               </tr>
               <?php if ($order_detail != '') { ?>
                  <tr>
                     <th><?php echo $l_descrip ?></th>
                     <td><?php echo $order_detail ?></td>
                  </tr>
               <?php } ?>
               <tr>
                  <th><?php echo $l_date ?></th>
                  <td><?php echo $order_create ?></td>
               </tr>
               <tr>
                  <th>วิธีการชำระเงิน</th>
                  <td><?php //echo $data['order_buyer_tel'] ?></td>
               </tr>
            </tbody>
         </table>
      </div>
      <div class="my-5">
            <h5 class="mb-3"><b><?php echo $l_paydetail ?></b></h5>
            <div class="d-flex">
               <div class=""><?php echo $l_invoice_pdt ?></div>
               <div class="ms-auto text-right"><?php echo number_format($order_price - $order_freight) . $l_bath ?></div>
            </div>
            <hr>
            <div class="d-flex">
               <div class=""><?php echo $l_invoice_totfri ?></div>
               <div class="ms-auto text-right"><?php echo number_format($order_freight) . $l_bath ?></div>
            </div>
            <hr>
            <div class="d-flex bg-info bg-gradient p-3 text-white">
               <div class=""><?php echo $l_invoice_totprice ?></div>
               <div class="ms-auto text-right"><h5><?php echo number_format($order_price) . $l_bath ?></h5></div>
            </div>
      </div>
   <?php } ?>
</div>