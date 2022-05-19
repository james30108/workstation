<?php include('dashboard/process/function.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Basic Page Needs
    ================================================== -->
  <meta charset="utf-8">
  <title>Login</title>
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
  <link rel="stylesheet" href="path/to/assets/content-styles.css" type="text/css">

  <!-- bootstrap.min css -->
  <link rel="stylesheet" href="webpage_asset/plugins/bootstrap/css/bootstrap.css">
  <link href="dashboard/assets/css/icons.css" rel="stylesheet">

  <!-- Animate css -->
  <link rel="stylesheet" href="webpage_asset/plugins/animate/animate.css">
  <!-- Slick Carousel -->
  <link rel="stylesheet" href="webpage_asset/plugins/slick/slick.css">
  <link rel="stylesheet" href="webpage_asset/plugins/slick/slick-theme.css">

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

    .logo {
      font-family: 'Times New Roman', Times, serif;
      font-size: 50px;
      font-weight: bold;
      margin: 0;
    }
  </style>
</head>

<body id="body">

  <section class="signin-page account">
    <div class="container">
      <div class="row">
        <div class="col-12 col-sm-5 mx-auto">
          <?php if (!isset($_GET['page'])) { ?>
            <div class="mt-3">
            <?php isset($_GET["status"]) ? alert ($_GET["status"], $_GET["message"], 0) : false; ?>
            </div>
            <div class="block text-center mt-3">
              <a class="logo" href="index.php">
                <img src="dashboard/assets/images/etc/logo.png" alt="">
              </a>
              <h2 class="text-center">ยินดีต้อนรับ</h2>
              <form class="text-left clearfix" action="dashboard/process/setting_buyer.php" method="post">
                <div class="form-group mb-3">
                  <input type="email" class="form-control" name="buyer_email" placeholder="อีเมล">
                </div>
                <div class="form-group mb-3">
                  <input type="password" class="form-control" name="buyer_pass" placeholder="พาสเวิร์ด">
                </div>
                <div class="text-center">
                  <button class="btn btn-main text-center" name="action" value="login">เข้าสู่ระบบ</button>
                </div>
              </form>
              <p class="mt-5 d-inline-flex">
                <a href="visitor_signin.php" class="text-decoration-underline">สร้างบัญชีใหม่</a>
                <div class="vr mx-3"></div>
                <a href="visitor_login.php?page=forget_password" class="text-decoration-underline">ลืมรหัสผ่าน</a>
                <div class="vr mx-3"></div>
                <a href="index.php" class="text-decoration-underline">กลับหน้าหลัก</a>
              </p> 
            </div>
          <?php } elseif (isset($_GET['page']) && $_GET['page'] == 'forget_password') { ?>
            <div class="block text-center mt-3">
              <a class="logo" href="index.php">
                <img src="dashboard/assets/images/etc/logo.png" alt="">
              </a>
              <h2>แจ้งการลืมรหัสผ่าน</h2>
              <p class="text-dark mt-3">ข้อมูลรหัสผ่านของท่านจะถูกจัดส่งไปที่อีเมล</p>
              <form class="text-left clearfix" action="dashboard/process/setting_buyer.php" method="post">
                <div class="form-group mb-3">
                  <input type="email" class="form-control" name="buyer_email" placeholder="อีเมล">
                </div>
                <div class="text-center">
                  <button class="btn btn-main text-center" name="action" value="forget_password">ส่งข้อมูล</button>
                </div>
              </form>
              <p class="mt-5 d-inline-flex">
                <a href="visitor_signin.php" class="text-decoration-underline">สร้างบัญชีใหม่</a>
                <div class="vr mx-3"></div>
                <a href="visitor_login.php" class="text-decoration-underline">เข้าสู่ระบบ</a>
                <div class="vr mx-3"></div>
                <a href="index.php" class="text-decoration-underline">กลับหน้าหลัก</a>
              </p> 
            </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </section>

  <!-- 
    Essential Scripts
    =====================================-->

  <!-- Main jQuery -->
  <script src="webpage_asset/plugins/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap 3.1 -->
  <script src="webpage_asset/plugins/bootstrap/js/bootstrap.min.js"></script>
  <!-- Bootstrap Touchpin -->
  <script src="webpage_asset/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js"></script>

  <!-- Main Js File -->
  <script src="js/script.js"></script>

</body>

</html>