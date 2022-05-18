<?php include('dashboard/process/function.php');
/*
if (isset($_GET['buyer_direct']) && $_GET['buyer_direct'] != '') {  
  $_SESSION['buyer_direct'] = $_GET['buyer_direct'];
  header("location:signin.php");
}
*/

$buyer_direct = isset($_SESSION['buyer_direct']) ? $_SESSION['buyer_direct'] : 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Basic Page Needs
  ================================================== -->
  <meta charset="utf-8">
  <title>Signin</title>

  <!-- Mobile Specific Metas
    ================================================== -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="description" content="Construction Html5 Template">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
  <meta name="author" content="Themefisher">
  <meta name="generator" content="Themefisher Constra HTML Template v1.0">

  <!-- Favicon -->
  <link rel="shortcut icon" type="image/x-icon" href="webpage_asset/images/favicon.png" />

  <!-- Themefisher Icon font -->
  <link rel="stylesheet" href="webpage_asset/plugins/themefisher-font/style.css">
  <!-- bootstrap.min css -->
  <link rel="stylesheet" href="webpage_asset/plugins/bootstrap/css/bootstrap.min.css">

  <!-- JS -->
  <script src="dashboard/assets/js/jquery-3.6.0.min.js"></script>


  <!-- Main Stylesheet -->
  <link rel="stylesheet" href="webpage_asset/css/style.css">
  <!-- Font -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">
  <!-- Styles -->
  <style>
    body {
      font-family: 'Kanit', sans-serif;
      font-size: 15px;
    }

    .account .block {
      background-color: #fff;
      border: 1px solid #dedede;
      padding: 30px;
      margin: 50px 0;
    }
  </style>
  <script>  
    $(document).ready(function(){

        //  แสดงชื่ออัปไลน์ 
        $("#direct_code").change(function(){
            var direct_code = $(this).val();
            $.get( "dashboard/process/ajax/ajax_visitor_insert.php", { direct_code: direct_code }, function( data ) {

              if (data == 'none') {
                  alert('This member code is not in the system');
                  $("#direct_name").val("");
                  $("#direct_id").val("");
              }
              else {
                  let direct      = data.split(",");
                  let direct_id   = direct[0];
                  let direct_name = direct[1];

                  $("#direct_id").val(direct[0]);
                  $("#direct_name").val(direct[1]);
              }

            });
        });

    });
  </script>
</head>
<body id="body">
  <section class="signin-page account">
    <div class="container">
      <div class="row">
        <div class="col-12 col-sm-6 mx-auto">
          <div class="mt-3">
          <?php isset($_GET["status"]) ? alert ($_GET["status"], $_GET["message"], $system_lang) : false; ?>
          </div>
          <div class="block text-center mt-3">
            <a class="logo" href="index.php">
              <img src="dashboard/assets/images/etc/logo.png" alt="">
            </a>
            <h2 class="text-center">สร้างบัญชีใหม่</h2>
            <form class="row g-3" action="dashboard/process/setting_buyer.php" method="post">
              <?php
                $query = mysqli_query($connect, "SELECT * FROM system_member WHERE member_id = '$buyer_direct' ");
                $data  = mysqli_fetch_array($query);
                $member_name = $data['member_name'];
                $member_code = $data['member_code'];
              ?>
              <div class="col-12 col-sm-6">
                <input type="text" class="form-control" id="direct_code" name="direct_code" value="<?php echo $member_code ?>" placeholder="รหัสผู้แนะนำ" required>
              </div>
              <div class="col-12 col-sm-6">
                <input type="hidden" name="buyer_direct" id="direct_id" value="<?php echo $buyer_direct ?>">
                <input type="text" class="form-control" id="direct_name" value="<?php echo $member_name ?>" placeholder="กรุณากรอกรหัสผู้แนะนำให้ถูกต้อง" required onkeypress="return false;">
              </div>
              <hr>
              <div class="col-12">
                <input type="text" class="form-control" name="buyer_name" placeholder="ชื่อ-สกุล" required>
              </div>
              <div class="col-12">
                <input type="text" class="form-control" name="buyer_tel" placeholder="หมายเลขโทรศัพท์">
              </div>
              <div class="col-12">
                <input type="email" class="form-control" name="buyer_email" placeholder="อีเมล">
              </div>
              <div class="col-12">
                <input type="text" class="form-control" name="buyer_address" placeholder="ที่อยู่">
              </div>
              <div class="col-12 col-sm-6">
                <input type="text" class="form-control" name="buyer_district" placeholder="ตำบล">
              </div>
              <div class="col-12 col-sm-6">
                <input type="text" class="form-control" name="buyer_amphure" placeholder="อำเภอ">
              </div>
              <div class="col-12">
                <input type="text" class="form-control" name="buyer_province" placeholder="จังหวัด">
              </div>
              <div class="col-12">
                <input type="text" class="form-control" name="buyer_zipcode" placeholder="รหัสไปรษณีย์">
              </div>
              <hr class="my-3">
              <div class="mb-1">
                <input type="password" class="form-control" name="buyer_pass" placeholder="รหัสผ่าน">
              </div>
              <div class="mb-1">
                <input type="password" class="form-control" name="buyer_pass_2" placeholder="ยืนยันรหัสผ่าน">
              </div>
              <div class="text-center">
                <button name="action" value="insert" class="btn btn-main text-center">สร้างบัญชี</button>
              </div>
            </form>
            <p class="mt-5 d-inline-flex">
              <a href="visitor_login.php" class="text-decoration-underline"> เข้าสู่ระบบ</a>
              <div class="vr mx-3"></div>
              <a href="index.php" class="text-decoration-underline"> กลับหน้าหลัก</a>
            </p> 
          </div>
        </div>
      </div>
    </div>
  </section>

</body>

</html>