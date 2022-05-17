<?php include('function.php'); 

if (isset($_POST['action']) && $_POST['action'] == 'insert') {

    $buyer_direct   = $_POST['buyer_direct'];
    $buyer_name     = $_POST['buyer_name'];
    $buyer_tel      = $_POST['buyer_tel'];
    $buyer_email    = $_POST['buyer_email'];
    $buyer_address  = $_POST['buyer_address'];
    $buyer_district = $_POST['buyer_district'];
    $buyer_amphure  = $_POST['buyer_amphure'];
    $buyer_province = $_POST['buyer_province'];
    $buyer_zipcode  = $_POST['buyer_zipcode'];
    $buyer_pass     = $_POST['buyer_pass'];
    $buyer_pass_2   = $_POST['buyer_pass_2'];

    $query = mysqli_query($connect, "SELECT * FROM system_buyer WHERE (buyer_email = '$buyer_email' )");
    $data  = mysqli_fetch_array($query);

    if ($buyer_pass != $buyer_pass_2) {
        header("location:../../signin.php?status=warning&message=พาสเวิร์ดไม่ตรงกัน");
        die;
    }
    elseif ($data) {
        header("location:../../signin.php?status=warning&message=อีเมลนี้มีอยู่ในระบบแล้ว");
        die;
    }

    mysqli_query($connect, "INSERT INTO system_buyer (
		buyer_direct,		buyer_name, 		buyer_tel,
		buyer_email,		buyer_address,		buyer_district,			
		buyer_amphure,		buyer_province,		buyer_zipcode,		
		buyer_pass			
		)
		VALUES(
		'$buyer_direct',	'$buyer_name', 		'$buyer_tel',
		'$buyer_email',	    '$buyer_address',	'$buyer_district',			
		'$buyer_amphure', 	'$buyer_province',	'$buyer_zipcode',	
		'$buyer_pass'
        )")or die(mysqli_error($connect));
        $buyer_id = $connect -> insert_id;

    $_SESSION['buyer_id'] = $buyer_id;
    header("location:../../index.php");
}
elseif (isset($_POST['action']) && $_POST['action'] == 'edit_profile') {
    
    // --- ชื่อ รหัสสมาชิก อีเมล ----
    $buyer_id      = $_POST['buyer_id'];
    $buyer_name    = $_POST['buyer_name'];
    $buyer_tel     = $_POST['buyer_tel'];
    $buyer_email   = $_POST['buyer_email'];
    $buyer_address = $_POST['buyer_address'];
    $member_email  = $_POST['member_email'];
    $buyer_district= $_POST['buyer_district'];
    $buyer_amphure = $_POST['buyer_amphure'];
    $buyer_province= $_POST['buyer_province'];
    $buyer_zipcode = $_POST['buyer_zipcode'];

    // -----------------------------

    mysqli_query($connect, "UPDATE system_buyer 
    SET	buyer_name     = '$buyer_name', 
        buyer_tel     = '$buyer_tel',
        buyer_email    = '$buyer_email',
        buyer_address  = '$buyer_address', 
        buyer_district = '$buyer_district', 
        buyer_amphure  = '$buyer_amphure',
        buyer_province = '$buyer_province', 
        buyer_zipcode  = '$buyer_zipcode'
    WHERE buyer_id     = '$buyer_id'");

    header("location:../../?page=edit_profile");
}
elseif (isset($_POST['action']) && $_POST['action'] == 'edit_password') {

    // -------------------------------
    $buyer_id              = $_POST['buyer_id'];
    $buyer_pass            = $_POST['buyer_pass'];
    $buyer_pass_new        = $_POST['buyer_pass_new'];
    $buyer_pass_recheck    = $_POST['buyer_pass_recheck'];

    $query = mysqli_query($connect, "SELECT * FROM system_buyer WHERE (buyer_id = '$buyer_id') AND (buyer_pass = '$buyer_pass')");
    $data  = mysqli_fetch_array($query);

    if (!$data) {
        header("location:../../?page=edit_profile&status=ระบุพาสเวิร์ดเดิมไม่ถูกต้อง");
        die;
    }
    elseif ($buyer_pass_new != $buyer_pass_recheck) {
        header("location:../../?page=edit_profile&status=ระบุพาสเวิร์ดใหม่ไม่ตรงกัน");
        die;
    }
    else {
        mysqli_query($connect, "UPDATE system_buyer 
        SET	buyer_pass = '$buyer_pass_new'
        WHERE buyer_id = '$buyer_id'");

        header("location:../../?page=edit_profile&status=success");
    }
}
elseif (isset($_POST['action']) && $_POST['action'] == 'login') {
    $buyer_email = $_POST['buyer_email'];
	$buyer_pass  = $_POST['buyer_pass'];
	$query       = mysqli_query($connect, "SELECT * FROM system_buyer WHERE (buyer_email = '$buyer_email') AND (buyer_pass = '$buyer_pass') ");
    $data        = mysqli_fetch_array($query);
	
    if (isset($data) && $data['buyer_status'] == 0) {
		$_SESSION['buyer_id'] = $data['buyer_id'];
		header('location:../../index.php');
	}
    elseif (isset($data) && $data['buyer_status'] == 1) {
        header('location:../../visitor_login.php?status=warning&message=บัญชีของท่านถูกระงับการใช้งาน');
    }
    else {
        header('location:../../visitor_login.php?status=warning&message=ท่านระบุอีเมลหรือพาสเวิร์ดไม่ถูกต้อง');
	}
}
elseif (isset($_GET['action']) && $_GET['action'] == 'block') {

    $buyer_id = $_GET['buyer_id'];
	mysqli_query($connect, "UPDATE system_buyer SET buyer_status = 1 WHERE buyer_id = '$buyer_id'");
    header('location:../admin.php?page=buyer&status=success&message=0');
}
elseif (isset($_POST['action']) && $_POST['action'] == 'forget_password') {
    
    $buyer_email  = $_POST['buyer_email'];

    $query  = mysqli_query($connect, "SELECT * FROM system_buyer WHERE (buyer_email = '$buyer_email') limit 1")or die(mysqli_error($connect));
    $empty  = mysqli_num_rows($query);
    if( $empty == 1 ){
        $data   = mysqli_fetch_array($query);
        $detail = '<html>
            <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>$company</title>
            </head>
            <body>
            <h5>อีเมลแจ้งรหัสผ่านจาก $company</h5>
            <p>สวัสดีค่ะคุณ '.$data['buyer_name'].'</p>
            <p>Username และ Password เพื่อเข้าใช้งานระบบของคุณคือ</p>
            <b>Username :</b> '.$data['buyer_email'].'<br>
            <b>Password :</b> '.$data['buyer_pass'].'<br>
            <hr>
            <p>วันที่ส่ง : '.date("Y-m-d H:i").'</p>
            <p>ขอบคุณค่ะ</p>
            </body>
        </html>';

        $subject = "อีเมลแจ้งลืมรหัสผ่านจาก $company";
        $header .= "MIME-Version: 1.0\r\n";
        $header .= "Content-type: text/html; charset=utf-8\r\n";
        $header .= "From: $company";
        @mail($member_email, $subject, $detail, $header);
        header("Location:../member_login.php?action=forget_password&status=success&message=0");
    } else { 
        header("Location:../member_login.php?action=forget_password&status=warning&message=ไม่พบอีเมลนี้ในระบบ กรุณาติดต่อแอดมินได้ที่หน้าติดต่อเราค่ะ");
    }
}

?>