<title>เสร็จสิ้นการสั่งซื้อสินค้า</title>
<?php 
$order = isset($_GET["order"]) ? $_GET['order'] : false; 
$query = mysqli_query($connect, "SELECT * FROM system_order WHERE order_id = '$order' ");
$data  = mysqli_fetch_array($query);
?>
<!-- Page Wrapper -->
<section class="page-wrapper success-msg">
  <div class="container">
    <div class="row">
      <div class="col-12 col-sm-6 mx-auto">
        <div class="block text-center">
        	<i class="tf-ion-android-checkmark-circle"></i>
          <?php if ($_GET['status'] == 'buy') { ?>
            <h2 class="text-center my-3">ขอบคุณสำหรับคำสั่งซื้อค่ะ</h2>
            <p>คำสั่งซื้อของท่านดำเนินการเรียบร้อยแล้ว ทางเราจะเร่งตรวจสอบและจัดส่งสินค้าไปให้ท่านโดยเร็วที่สุดค่ะ</p>
            <hr>
            <p>หมายเลขตรวจสอบสินค้าของท่านคือ : <font color="black"><?php echo $data['order_code'] ?></font> สามารถนำไปตรวจสอบสถานะในการจัดส่งได้เลยค่ะ</p>
            <a href="?page=shop" class="btn btn-main mt-20">เลือกซื้อสินค้าต่อ</a>
          <?php } elseif ($_GET['status'] == 'email_noti') { ?>
            <h2 class="text-center my-3">บันทึกอีเมลเรียบร้อย</h2>
            <p>ท่านได้สมัครการรับข้อมูล และข่าวสารต่างๆจากทางเราเรียบร้อย หลังจากนี้เมื่อมีข้อมูลให้ๆอัปเดต จะมีการส่งข้อความเข้าไปทางอีเมลของท่านค่ะ</p>
            <?php } elseif ($_GET['status'] == 'contact') { ?>
          <h2 class="text-center my-3">ส่งข้อความเรียบร้อย</h2>
            <p>ข้อความของท่านได้ถูกส่งเข้าสู่ระบบเรียบร้อย เจ้าหน้าที่ได้รับข้อมูลการร้องเรียนของท่านแล้ว ทางเราขอขอบพระคุณสำหรับข้อติชมต่างๆมากเลยค่ะ</p>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</section><!-- /.page-warpper -->