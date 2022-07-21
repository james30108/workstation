<?php include('function.php'); 

if     (isset($_POST['action']) && $_POST['action'] == 'setting_config') {

    $report_type        = isset($_POST['1']) ? $_POST['1'] : $report_type;
    $report_fee1        = isset($_POST['2']) ? $_POST['2'] : $report_fee1;
    $report_min         = isset($_POST['3']) ? $_POST['3'] : $report_min;
    $report_max         = isset($_POST['4']) ? $_POST['4'] : $report_max;
    $report_fee2        = isset($_POST['5']) ? $_POST['5'] : $report_fee2;
    $downline_max       = isset($_POST['6']) ? $_POST['6'] : $downline_max;
    $com_ppm            = isset($_POST['7']) ? $_POST['7'] : $com_ppm;
    $com_number         = isset($_POST['8']) ? $_POST['8'] : $com_number;
    $com_style          = isset($_POST['9']) ? $_POST['9'] : $com_style;
    $system_style       = isset($_POST['10']) ? $_POST['10'] : $system_style;
    $system_switch      = isset($_POST['11']) ? $_POST['11'] : $system_switch;
    $system_lang        = isset($_POST['12']) ? $_POST['12'] : $system_lang;
    $system_liner       = isset($_POST['13']) ? $_POST['13'] : $system_liner;
    $system_webpage     = isset($_POST['14']) ? $_POST['14'] : $system_webpage;
    $system_buyer       = isset($_POST['15']) ? $_POST['15'] : $system_buyer;
    $system_mobile      = isset($_POST['16']) ? $_POST['16'] : $system_mobile;
    $system_class       = isset($_POST['17']) ? $_POST['17'] : $system_class;
    $system_admin_buy   = isset($_POST['18']) ? $_POST['18'] : $system_admin_buy;
    $system_ewallet     = isset($_POST['19']) ? $_POST['19'] : $system_ewallet;
    $system_member_expire= isset($_POST['20']) ? $_POST['20'] : $system_member_expire;
    $system_stock       = isset($_POST['21']) ? $_POST['21'] : $system_stock;
    $system_tracking    = isset($_POST['22']) ? $_POST['22'] : $system_tracking;
    $system_liner2      = isset($_POST['23']) ? $_POST['23'] : $system_liner2;
    $system_address     = isset($_POST['24']) ? $_POST['24'] : $system_address;
    $system_pay         = isset($_POST['25']) ? $_POST['25'] : $system_pay;
    $system_comment     = isset($_POST['26']) ? $_POST['26'] : $system_comment;
    $system_com_withdraw= isset($_POST['27']) ? $_POST['27'] : $system_com_withdraw;
    $report_style       = isset($_POST['28']) ? $_POST['28'] : $report_style;
    $system_product_type2= isset($_POST['29']) ? $_POST['29'] : $system_product_type2;
    $system_ecommerce   = isset($_POST['30']) ? $_POST['30'] : $system_ecommerce;
    $system_thread      = isset($_POST['31']) ? $_POST['31'] : $system_thread;
    $system_insertmember= isset($_POST['32']) ? $_POST['32'] : $system_insertmember;

    mysqli_query($connect, "UPDATE system_config SET config_value = '$report_type'          WHERE config_id = 1 ");
    mysqli_query($connect, "UPDATE system_config SET config_value = '$report_fee1'          WHERE config_id = 2 ");
    mysqli_query($connect, "UPDATE system_config SET config_value = '$report_min'           WHERE config_id = 3 ");
    mysqli_query($connect, "UPDATE system_config SET config_value = '$report_max'           WHERE config_id = 4 ");
    mysqli_query($connect, "UPDATE system_config SET config_value = '$report_fee2'          WHERE config_id = 5 ");
    mysqli_query($connect, "UPDATE system_config SET config_value = '$downline_max'         WHERE config_id = 6 ");
    mysqli_query($connect, "UPDATE system_config SET config_value = '$com_ppm'              WHERE config_id = 7 ");
    mysqli_query($connect, "UPDATE system_config SET config_value = '$com_number'           WHERE config_id = 8 ");
    mysqli_query($connect, "UPDATE system_config SET config_value = '$com_style'            WHERE config_id = 9 ");
    mysqli_query($connect, "UPDATE system_config SET config_value = '$system_style'         WHERE config_id = 10 ");
    mysqli_query($connect, "UPDATE system_config SET config_value = '$system_switch'        WHERE config_id = 11 ");
    mysqli_query($connect, "UPDATE system_config SET config_value = '$system_lang'          WHERE config_id = 12 ");
    mysqli_query($connect, "UPDATE system_config SET config_value = '$system_liner'         WHERE config_id = 13 ");
    mysqli_query($connect, "UPDATE system_config SET config_value = '$system_webpage'       WHERE config_id = 14 ");
    mysqli_query($connect, "UPDATE system_config SET config_value = '$system_buyer'         WHERE config_id = 15 ");
    mysqli_query($connect, "UPDATE system_config SET config_value = '$system_mobile'        WHERE config_id = 16 ");
    mysqli_query($connect, "UPDATE system_config SET config_value = '$system_class'         WHERE config_id = 17 ");
    mysqli_query($connect, "UPDATE system_config SET config_value = '$system_admin_buy'     WHERE config_id = 18 ");
    mysqli_query($connect, "UPDATE system_config SET config_value = '$system_ewallet'       WHERE config_id = 19 ");
    mysqli_query($connect, "UPDATE system_config SET config_value = '$system_member_expire' WHERE config_id = 20 ");
    mysqli_query($connect, "UPDATE system_config SET config_value = '$system_stock'         WHERE config_id = 21 ");
    mysqli_query($connect, "UPDATE system_config SET config_value = '$system_tracking'      WHERE config_id = 22 ");
    mysqli_query($connect, "UPDATE system_config SET config_value = '$system_liner2'        WHERE config_id = 23 ");
    mysqli_query($connect, "UPDATE system_config SET config_value = '$system_address'       WHERE config_id = 24 ");
    mysqli_query($connect, "UPDATE system_config SET config_value = '$system_pay'           WHERE config_id = 25 ");
    mysqli_query($connect, "UPDATE system_config SET config_value = '$system_comment'       WHERE config_id = 26 ");
    mysqli_query($connect, "UPDATE system_config SET config_value = '$system_com_withdraw'  WHERE config_id = 27 ");
    mysqli_query($connect, "UPDATE system_config SET config_value = '$report_style'         WHERE config_id = 28 ");
    mysqli_query($connect, "UPDATE system_config SET config_value = '$system_product_type2' WHERE config_id = 29 ");
    mysqli_query($connect, "UPDATE system_config SET config_value = '$system_ecommerce'     WHERE config_id = 30 ");
    mysqli_query($connect, "UPDATE system_config SET config_value = '$system_thread'        WHERE config_id = 31 ");
    mysqli_query($connect, "UPDATE system_config SET config_value = '$system_insertmember'  WHERE config_id = 32 ");

    header("location:../admin.php?page=admin_config&status=success&message=0");

}
elseif (isset($_POST['action']) && $_POST['action'] == 'setting_commision_class') {

    
    $query = mysqli_query($connect, "SELECT * FROM system_commission ");
    while ($data = mysqli_fetch_array($query)) {
        
        $commission_point = $_POST[$data['commission_id']];
        mysqli_query($connect, "UPDATE system_commission 
            SET commission_point = '$commission_point' 
            WHERE commission_id = '".$data['commission_id']."' ");
    }

    $query = mysqli_query($connect, "SELECT * FROM system_commission ");
    while ($data = mysqli_fetch_array($query)) {
        
        $class              = 'com2_' . $data['commission_id'];
        $commission_point2  = $_POST[$class];  
        mysqli_query($connect, "UPDATE system_commission 
            SET commission_point2   = '$commission_point2' 
            WHERE commission_id     = '".$data['commission_id']."' ");
    }
    

   header("location:../admin.php?page=admin_config&status=success&message=0");

}
elseif (isset($_POST['action']) && $_POST['action'] == 'insert_class') {

    $class_name         = $_POST['class_name'];
    $class_up_level     = $_POST['class_up_level'];
    $class_match_level  = $_POST['class_match_level'];

    // -------- ขั้นตอนการเปลี่ยนชื่อไฟล์ และย้ายไฟล์รูป -----------
    $type = strrchr($_FILES['class_image']['name'],".");    //เอาชื่อไฟล์เก่าออกให้เหลือแต่นามสกุล
    $class_image = "class_" .  date('YmdHis') . $type;    //ตั้งชื่อไฟล์ใหม่โดยเอาเวลาไว้หน้าชื่อไฟล์เดิม
    move_uploaded_file($_FILES['class_image']['tmp_name'], "../assets/images/class/" . $class_image);

    mysqli_query($connect, "INSERT INTO system_class (class_name, class_image, class_up_level, class_match_level)VALUES ('$class_name', '$class_image', '$class_up_level', '$class_match_level')");
    
    header("location:../admin.php?page=admin_config&action=setting_class&status=success&message=0");

}
elseif (isset($_POST['action']) && $_POST['action'] == 'edit_class') {

    $class_id           = $_POST['class_id'];
    $class_image        = $_POST['class_image'];
    $class_name         = $_POST['class_name'];
    $class_up_level     = $_POST['class_up_level'];
    $class_match_level  = $_POST['class_match_level'];
    
    if ($_FILES['class_image_new']['name'] != '') {

        // ------ ลบรูปเก่าออก -------
        if ($class_image != '') {
            unlink ("../assets/images/class/" . $class_image);
        }

        // -------- ขั้นตอนการเปลี่ยนชื่อไฟล์ และย้ายไฟล์รูป -----------
        $type = strrchr($_FILES['class_image_new']['name'],".");    //เอาชื่อไฟล์เก่าออกให้เหลือแต่นามสกุล
        $class_image = "class_" .  date('YmdHis') . $type;    //ตั้งชื่อไฟล์ใหม่โดยเอาเวลาไว้หน้าชื่อไฟล์เดิม
        move_uploaded_file($_FILES['class_image_new']['tmp_name'], "../assets/images/class/" . $class_image);

    }
    mysqli_query($connect, "UPDATE system_class 
        SET class_name          = '$class_name', 
            class_image         = '$class_image',
            class_up_level      = '$class_up_level',
            class_match_level   = '$class_match_level' 
        WHERE class_id          = '$class_id'");
    
    header("location:../admin.php?page=admin_config&action=setting_class&status=success&message=0");

}
elseif (isset($_POST['action']) && $_POST['action'] == 'member_login') {

	$member_user = $_POST['member_user'];
	$member_pass = $_POST['member_pass'];
	$sql         = mysqli_query($connect, "SELECT * FROM system_member WHERE (member_user = '$member_user') AND (member_pass = '$member_pass') ");
    $data        = mysqli_fetch_array($sql);
	
    if($data){
		$_SESSION['member_id'] = $data['member_id'];
		header('location:../member.php');
	}
    else{
        header('location:../member_login.php?status=not_login');
	}

}
elseif (isset($_POST['action']) && $_POST['action'] == 'forget_password') {
    
    $member_user   = $_POST['member_user'];
    $member_email  = $_POST['member_email'];

    $query  = mysqli_query($connect, "SELECT * FROM system_member WHERE (member_email = '$member_email') AND (member_user = '$member_user') limit 1")or die(mysqli_error($connect));
    $empty  = mysqli_num_rows($query);
    if( $empty == 1 ){
        
        $data = mysqli_fetch_array($query);

            $detail = '<html>
            <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title> ' . $company . ' </title>
            </head>
            <body>
            <h3>อีเมลแจ้งรหัสผ่านจาก ' . $company . ' </h3>
            <p>สวัสดีค่ะคุณ ' . $data['member_name'] . '</p>
            <p>Username และ Password เพื่อเข้าใช้งานระบบของคุณคือ</p>
            <b>Username :</b> ' . $data['member_user'] . '<br>
            <b>Password :</b> ' . $data['member_pass'] . '<br>
            <hr>
            <p>วันที่ส่ง : ' . date("Y-m-d H:i") . '</p>
            <p>ขอบคุณค่ะ</p>
            </body>
        </html>';

        $subject       = "อีเมลแจ้งลืมรหัสผ่านจาก " . $company;
        $header       .= "MIME-Version: 1.0\r\n";
        $header       .= "Content-type: text/html; charset=utf-8\r\n";
        $header       .= "From: $company"; // * ชื่อบริษัทที่ส่งต้องห้ามมีช่องว่าง ไม่งั้นจะส่งอีเมลไม่ได้
        @mail($member_email, $subject, $detail, $header);
        //header("Location:../member_login.php?action=forget_password&status=success");

    } else { 
        header("Location:../member_login.php?action=forget_password&status=error");
    }

}
elseif (isset($_POST['action']) && $_POST['action'] == 'admin_login') {
    
    $admin_user     = $_POST['admin_user'];
    $admin_pass     = $_POST['admin_pass'];
    $sql            = mysqli_query($connect, "SELECT * FROM system_admin WHERE (admin_user = '$admin_user') AND (admin_pass = '$admin_pass') ");
    $data           = mysqli_fetch_array($sql);
    
    if($data){
        $_SESSION['admin_id'] = $data['admin_id'];
        header('location:../admin.php');
    }
    else{
        header('location:../admin_login.php?status=not_login');
    }

} 
elseif (isset($_POST['action']) && $_POST['action'] == 'insert_notification') {
    
    $noti_email = $_POST['noti_email'];

    $query = mysqli_query($connect, "SELECT * FROM system_notification WHERE noti_email = '$noti_email' ");
    $data  = mysqli_fetch_array($query);

    if (!$data) {
        mysqli_query($connect, "INSERT INTO system_notification (noti_email) VALUES ('$noti_email')");
    } 
    
    header('location:../../?page=confirmation&status=email_noti');

}
elseif (isset($_POST['action']) && $_POST['action'] == 'admin_change_password') {
    
    $admin_id       = $_POST['admin_id'];
    $admin_password = $_POST['admin_password'];
    $new_password_1 = $_POST['new_password_1'];
    $new_password_2 = $_POST['new_password_2'];

    $query = mysqli_query($connect, "SELECT * FROM system_admin WHERE (admin_id = '$admin_id') AND (admin_pass = '$admin_password') ");
    $data  = mysqli_fetch_array($query); 

    if ($new_password_1 != $new_password_2) {
        header('location:../admin.php?status=password_not_duplicate');
        die;
    }
    elseif (!isset($data)) {
        header('location:../admin.php?status=password_not_found');
        die;
    }
    else {
        mysqli_query($connect, "UPDATE system_admin 
            SET admin_pass = '$new_password_1'
            WHERE admin_id = '$admin_id'");
        header('Location:../admin.php?status=success');
    }

}
elseif (isset($_GET['action']) && $_GET['action'] == 'tracking') {

    $order_code         = $_GET['order_code'];
    $order_buyer_email  = $_GET['order_buyer_email'];

    $query = mysqli_query($connect, "SELECT * FROM system_order WHERE (order_code LIKE '%$order_code%') AND (order_buyer_email = '$order_buyer_email')");
    $data  = mysqli_fetch_array($query);

    if ($data) {
        header('location:../../?page=track&action=have&order_code=' . $data['order_code']);
    }
    else {
        header('location:../../?page=track&action=not_have');
    }

}
elseif (isset($_GET['action']) && $_GET['action'] == 'setting_theme') {

    $theme_mode     = $_GET['theme_mode'];
    $theme_header   = $_GET['theme_header'];
    $theme_sidebar  = $_GET['theme_sidebar'];
    
    mysqli_query($connect, "UPDATE system_theme SET theme_mode = '$theme_mode', theme_header = '$theme_header', theme_sidebar = '$theme_sidebar' ");

    header("location:../admin.php");

}
elseif (isset($_GET['action']) && $_GET['action']  == 'change_lang') {

    $admin_id   = $_GET['admin_id'];
    $admin_lang = $_GET['lang'];

    mysqli_query($connect, "UPDATE system_admin SET admin_lang = '$admin_lang' WHERE admin_id = '$admin_id' ");

    header('Location:../admin.php');

}
?>