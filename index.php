<?php include('dashboard/process/function.php');

if ($system_webpage == 0) {
    header("location:dashboard/member.php");
    die();
}

$page       = isset($_GET['page']) ? $_GET['page'] : false;
$buyer_id   = isset($_SESSION['buyer_id']) ? $_SESSION['buyer_id'] : false;
$lang       = 0;

if ($buyer_id) {

    $sql_check_login    = mysqli_query($connect, "SELECT * FROM system_buyer WHERE buyer_id = '$buyer_id' ");
    $data_check_login   = mysqli_fetch_array($sql_check_login);  
    $buyer_name         = $data_check_login['buyer_name'];
    $buyer_tel          = $data_check_login['buyer_tel'];
    $buyer_email        = $data_check_login['buyer_email'];
    $buyer_address      = $data_check_login['buyer_address'];
    $buyer_district     = $data_check_login['buyer_district'];
    $buyer_amphure      = $data_check_login['buyer_amphure'];
    $buyer_province     = $data_check_login['buyer_province'];
    $buyer_zipcode      = $data_check_login['buyer_zipcode'];
    $buyer_direct       = $data_check_login['buyer_direct'];
    $lang               = $system_lang == 1 ? $data_check_login['buyer_lang'] : 0;

}
if (isset($_GET['buyer_direct']) && $_GET['buyer_direct'] != '') {  
    
    $_SESSION['buyer_direct'] = $_GET['buyer_direct'];
    header("location:index.php");

}

include("dashboard/process/include_lang.php");

?>
<!doctype html>
<html lang="en">

<head>
    <!-- Basic Page Needs
    ================================================== -->
    <meta charset="utf-8">

    <!-- Mobile Specific Metas
    ================================================== -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Construction Html5 Template">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="author" content="Themefisher">
    <meta name="generator" content="Themefisher Constra HTML Template v1.0">

    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="dashboard/assets/images/etc/<?php echo $logo_icon ?>" />

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

    <!-- JS -->
    <script src="dashboard/assets/js/jquery-3.6.0.min.js"></script>

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

<body>
    <?php 
    include('webpage_asset/include/include_menu.php');
    switch ($page) {
        case "home":
            include('visitor_home.php');
            break;
        case "product_single":
            include('visitor_product_single.php');
            break;
        case "cart":
            include('visitor_cart.php');
            break;
        case "shop":
            include('visitor_shop.php');
            break;
        case "package":
            include('visitor_package.php');
            break;
        case "checkout":
            include('visitor_checkout.php');
            break;
        case "confirmation":
            include('visitor_confirmation.php');
            break;
        case "contact":
            include('visitor_contact.php');
            break;
        case "thread":
            include('visitor_thread.php');
            break;
        case "thread_single":
            include('visitor_thread_single.php');
            break;
        case "track":
            include('visitor_track.php');
            break;
        case "profile":
            include('visitor_profile.php');
            break;
        case "message":
            include('visitor_message.php');
            break;
        case "log_out":
            unset($_SESSION['buyer_id']);
            header("location:index.php");
            break;
        default:
            include('visitor_home.php');
    } ?>

    <footer class="footer section text-center">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <!--
                    <ul class="social-media">
                        <li>
                            <a href="https://www.facebook.com/themefisher">
                                <i class="tf-ion-social-facebook"></i>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.instagram.com/themefisher">
                                <i class="tf-ion-social-instagram"></i>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.twitter.com/themefisher">
                                <i class="tf-ion-social-twitter"></i>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.pinterest.com/themefisher/">
                                <i class="tf-ion-social-pinterest"></i>
                            </a>
                        </li>
                    </ul>
                    -->
                    <ul class="footer-menu text-uppercase">
                        <li>
                            <a href="?page=shop"><?php echo $l_product ?></a>
                        </li>
                        <li>
                            <a href="?page=package"><?php echo $l_package ?></a>
                        </li>
                        <li>
                            <a href="?page=thread"><?php echo $l_thread ?></a>
                        </li>
                        <li>
                            <a href="?page=contact"><?php echo $l_contact ?></a>
                        </li>
                        <!--
                        <li>
                            <a href="?page=track">ตรวจสอบคำสั่งซื้อ</a>
                        </li>
                        -->
                    </ul>
                    <img src="dashboard/assets/images/etc/<?php echo $logo_image ?>" class="border-0 m-5">
                    <!--
                    <p class="copyright-text">Copyright &copy;2021, Designed &amp; Developed by <a href="https://themefisher.com/">Themefisher</a></p>
                    -->
                </div>
            </div>
        </div>
    </footer>
    <button onclick="topFunction()" id="to_top" title="Go to top" class="to_top">Top</button>
    <!-- 
    Essential Scripts
    =====================================-->
    <script src="dashboard/assets/plugins/ckeditor5/ckeditor.js"></script>
    <!-- Main jQuery -->
    <script src="webpage_asset/plugins/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="webpage_asset/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Bootstrap Touchpin -->
    <script src="webpage_asset/plugins/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js"></script>
    <!-- Instagram Feed Js -->
    <script src="webpage_asset/plugins/instafeed/instafeed.min.js"></script>
    <!-- Video Lightbox Plugin -->
    <script src="webpage_asset/plugins/ekko-lightbox/dist/ekko-lightbox.min.js"></script>
    <!-- Count Down Js -->
    <script src="webpage_asset/plugins/syo-timer/build/jquery.syotimer.min.js"></script>

    <!-- slick Carousel -->
    <script src="webpage_asset/plugins/slick/slick.min.js"></script>
    <script src="webpage_asset/plugins/slick/slick-animation.min.js"></script>

    <!-- Google Mapl -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCC72vZw-6tGqFyRhhg5CkF2fqfILn2Tsw"></script>
    <script type="text/javascript" src="plugins/google-map/gmap.js"></script>

    <!-- Main Js File -->
    <script src="webpage_asset/js/script.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'))
            .catch(error => {
                console.error(error);
            });
        //Get the button
        var mybutton = document.getElementById("to_top");

        // When the user scrolls down 20px from the top of the document, show the button
        window.onscroll = function() {
            scrollFunction()
        };

        function scrollFunction() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                mybutton.style.display = "block";
            } else {
                mybutton.style.display = "none";
            }
        }

        // When the user clicks on the button, scroll to the top of the document
        function topFunction() {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        }
    </script>
</body>
</html>