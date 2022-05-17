<?php 

$header_url       = "dashboard/assets/images/bg-themes/1.png";
$header_name      = !isset($_GET['action']) ? "ข้อมูลส่วนตัว" : "ประวัติการซื้อ";
$header_css       = !isset($_GET['action']) ? "btn btn-main" : "text-decoration-underline small";
$header_css2      = isset($_GET['action']) ? "btn btn-main" : "text-decoration-underline small";
$header_detail    = "หน้าแดสงผลข้อมูลส่วนตัว โดยสามารถทำกาแก้ไขข้อมูลได้ และสามารถเรียกดูข้อมูลรายการสั่งซื้อสินค้าของท่านได้";
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
      <a href="index.php?page=profile" class="me-3 <?php echo $header_css ?>">แก้ไขข้อมูลส่วนตัว</a>
      <a href="index.php?page=profile&action=order" class="me-3 <?php echo $header_css2 ?>">ประวัติการซื้อ</a>
   </div>
   <?php if (!isset($_GET['action'])) { ?>
      <form action="dashboard/process/setting_buyer.php" method="post" enctype="multipart/form-data" class="checkout-form mt-3">
         <input type="hidden" name="buyer_id" value="<?php echo $buyer_id ?>">
         <div class="checkout shopping">
            <div class="block billing-details">
               <h4 class="widget-title">ข้อมูลส่วนตัวของท่านจะนำไปใช้ในการจัดส่งสินค้า (กรุณากรอกตามความจริง)</h4>
               <div class="form-group">
                  <label>ชื่อผู้รับสินค้า</label>
                  <input type="text" name="buyer_name" class="form-control" value="<?php echo $header_name ?>" placeholder="ชื่อผู้รับสินค้า">
               </div>
               <div class="form-group">
                  <label>เบอร์โทร</label>
                  <input type="text" name="buyer_tel" class="form-control" value="<?php echo $buyer_tel ?>" placeholder="เบอร์โทร">
               </div>
               <div class="form-group">
                  <label>อีเมล</label>
                  <input type="email" name="buyer_email" class="form-control" value="<?php echo $buyer_email ?>" placeholder="อีเมล@email.com">
               </div>
               <div class="form-group">
                  <label>ที่อยู่</label>
                  <input type="text" name="buyer_address" class="form-control" value="<?php echo $buyer_address ?>" placeholder="ที่อยู่">
               </div>
               <div class="checkout-country-code clearfix">
                  <div class="form-group">
                     <label>ตำบล</label>
                     <input type="text" name="buyer_district" class="form-control" value="<?php echo $buyer_district ?>" placeholder="ตำบล">
                  </div>
                  <div class="form-group">
                     <label>อำเภอ</label>
                     <input type="text" name="buyer_amphure" class="form-control" value="<?php echo $buyer_amphure ?>" placeholder="อำเภอ">
                  </div>
               </div>
               <div class="checkout-country-code clearfix">
                  <div class="form-group">
                     <label>จังหวัด</label>
                     <input type="text" name="buyer_province" class="form-control" value="<?php echo $buyer_province ?>" placeholder="จังหวัด">
                  </div>
                  <div class="form-group">
                     <label>รหัสไปรษณีย์</label>
                     <input type="text" name="buyer_zipcode" class="form-control" value="<?php echo $buyer_zipcode ?>" name="zipcode" placeholder="ไปรษณีย์">
                  </div>
               </div>
               <button name="action" value="edit_profile" class="btn btn-main mt-3">บันทึกข้อมูล</button>
            </div>
         </div>
      </form>
      <hr class="my-3">
      <form action="dashboard/process/setting_buyer.php" method="post" enctype="multipart/form-data" class="checkout-form">
         <input type="hidden" name="buyer_id" value="<?php echo $buyer_id ?>">
         <div class="checkout shopping">
            <div class="block billing-details">
               <h4 class="widget-title">เปลี่ยนพาสเวิร์ด</h4>
               <div class="form-group">
                  <label>พาสเวิร์ดเดิม</label>
                  <input type="password" name="buyer_pass" class="form-control" placeholder="พาสเวิร์ดเดิม">
               </div>
               <div class="form-group">
                  <label>พาสเวิร์ดใหม่</label>
                  <input type="password" name="buyer_pass_new" class="form-control" minlength="6" maxlength="15" placeholder="พาสเวิร์ดใหม่">
               </div>
               <div class="form-group">
                  <label>ยืนยันพาสเวิร์ด</label>
                  <input type="password" name="buyer_pass_recheck" class="form-control" minlength="6" maxlength="15" placeholder="ยืนยันพาสเวิร์ด">
               </div>
               <button name="action" value="edit_password" class="btn btn-main mt-3">บันทึกข้อมูล</button>
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
                     <h5>ใบสั่งซื้อเลขที่ <?php echo $order_code ?></h5>
                     <?php echo "<p>$order_create</p><br>";
                     if ($order_status == 0) { 
                        echo "<font color=gray>รอการตรวจสอบ</font>";
                     } elseif ($order_status == 1 || $order_status == 2) { 
                        echo "<font color=red>ถูกยกเลิก</font>";
                     } elseif ($order_status >= 3 || $order_status == 4) { 
                        echo "<font color=green>จัดส่งเรียบร้อย</font>";
                     } elseif ($order_status == 5) { 
                        echo "<font color=green>เสร็จสิ้น</font>";
                     } ?>
                  </div>

                  <a href="?page=profile&action=order_detail&order_id=<?php echo $order_id ?>" title="รายละเอียด" class="ms-auto text-decoration-underline">รายละเอียด</a>
               </div>
               <hr class="my-3">
               
         <?php } } else { ?>
            <tr>
               <td colspan="5">ไม่มีรายการสั่งซื้อในระบบ</td>
            </tr>
         <?php }  

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

      $order_buyer   = $data['order_buyer'];
      $order_address   = $data['order_address'];
      $order_district   = $data['order_district'];
      $order_track_id   = $data['order_track_id'];
      $order_track_id   = $data['order_track_id'];
      ?>
      <hr>
      <a href="index.php?page=profile&action=order" class="me-3 text-decoration-underline small">ย้อนกลับ</a>
      <div class="my-5 row">
         <div class="col-12 col-sm-3 d-flex">
            <h5>ใบสั่งซื้อเลขที่<br><?php echo $order_code ?></h5>
            <hr class="vr ms-auto" style="height: 40px;">
         </div>
         <div class="col-4 col-sm-3 d-flex">
            <div>สถานะการจัดส่ง<br>
            <?php
            if ($order_status == 0) { 
               echo "<font color=gray>รอการตรวจสอบ</font>";
            } elseif ($order_status == 1 || $order_status == 2) { 
               echo "<font color=red>ถูกยกเลิก</font>";
            } elseif ($order_status >= 3 || $order_status == 4) { 
               echo "<font color=green>จัดส่งเรียบร้อย</font>";
            } elseif ($order_status == 5) { 
               echo "<font color=green>เสร็จสิ้น</font>";
            } 
            ?>
            </div>
            <hr class="vr ms-auto" style="height: 40px;">
         </div>
         
         <div class="col-4 col-sm-3 d-flex">ประเภทการส่ง<br><?php echo $order_track_name ?>
            <hr class="vr ms-auto" style="height: 40px;">
         </div>
         
         <div class="col-4 col-sm-3 d-flex">เลขติดตามพัสดุ<br><?php echo $order_track_id ?></div>
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
            <div class="col-4 col-sm-2"><?php echo "ยอดรวม : " . $order_detail_price * $order_detail_amount . " บาท" ?></div>
            <div class="col-4 col-sm-2">

            <?php if ($order_detail_review == 0) { ?>

               <button class="me-3 text-decoration-underline small bg-transparent border-0 text-primary" data-bs-toggle="modal" data-bs-target="#review_<?php echo $detail['order_detail_id'] ?>">เขียนรีวิว</button>   

            <?php } else { ?> 

               <div class="me-3 small">รีวิวเสร็จสิ้น</div>

            <?php } ?>

         </div>
         </div>
         <hr>

         <!-- Modal -->
         <div class="modal fade" id="review_<?php echo $order_detail_id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-header">
               <h5 class="modal-title" id="exampleModalLabel">รีวิวสินค้า</h5>
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
                  <button name="action" value="insert" class="btn btn-main my-3">บันทึกรีวิว</button>
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
                  <th>ผู้สั่งซื้อ</th>
                  <td><?php echo $data['order_buyer'] ?></td>
               </tr>
               <tr>
                  <th>สถานที่จัดส่งสินค้า</th>
                  <td>
                     <?php echo "$order_address ตำบล/แขวง $order_district อำเภอ/เขต $order_amphur จังหวัด $order_province รหัสไปรษณีย์ $order_zipcode" ?>
                  </td>
               </tr>
               <tr>
                  <th>เบอร์โทร</th>
                  <td><?php echo $order_buyer_tel ?></td>
               </tr>
               <?php if ($order_detail != '') { ?>
                  <tr>
                     <th>ข้อมูลเพิ่มเติม</th>
                     <td><?php echo $order_detail ?></td>
                  </tr>
               <?php } ?>
               <tr>
                  <th>วันที่สั่งซื้อ</th>
                  <td><?php echo $order_create ?></td>
               </tr>
               <tr>
                  <th>วิธีการชำระเงิน</th>
                  <td><?php echo $data['order_buyer_tel'] ?></td>
               </tr>
            </tbody>
         </table>
      </div>
      <div class="my-5">
            <h5 class="mb-3"><b>รายละเอียดการชำระเงิน</b></h5>
            <div class="d-flex">
               <div class="">ยอดรวม</div>
               <div class="ms-auto text-right"><?php echo number_format($order_price - $order_freight) ?> บาท</div>
            </div>
            <hr>
            <div class="d-flex">
               <div class="">ค่าขนส่ง</div>
               <div class="ms-auto text-right"><?php echo number_format($order_freight) ?> บาท</div>
            </div>
            <hr>
            <div class="d-flex bg-info bg-gradient p-3 text-white">
               <div class="">ยอดสั่งซื้อทั้งหมด</div>
               <div class="ms-auto text-right"><h5><?php echo number_format($order_price) ?> บาท</h5></div>
            </div>
      </div>
   <?php } ?>
</div>