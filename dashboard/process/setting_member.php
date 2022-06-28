<?php include('function.php'); 

if (!file_exists("../assets/images/card/")) 	{ mkdir("../assets/images/card/"); }
if (!file_exists("../assets/images/profiles/")) { mkdir("../assets/images/profiles/"); }

if     (isset($_POST['action']) && $_POST['action'] == 'insert') {

	// ---- เรียงลำดับตัวแปรจากในฐานข้อมูล ----
	$query 	= mysqli_query($connect, "SELECT * FROM system_member ORDER BY member_id DESC");
    $data 	= mysqli_fetch_array($query);
   
    $plus 	= $data['member_id'] + 1;
    if 		($plus <= 9) {$zero="000000";} 
    elseif 	($plus <= 99) {$zero="00000";} 
    elseif 	($plus <= 999) {$zero="0000";} 
    elseif 	($plus <= 9999) {$zero="000";} 
    elseif 	($plus <= 99999) {$zero="00";} 
    elseif 	($plus <= 999999) {$zero="0";} 
    else 	{$zero="";}
    $code = $zero . $plus;

    // ---- ชื่อ รหัสสมาชิก อีเมล ----
    $member_code 		= $code;
    $page 				= $_POST['page'];
    $liner_direct 		= $_POST['liner_direct'];
	$member_email 		= $_POST['member_email'];
	$member_title_name 	= $_POST['member_title_name'];
	$member_name 		= $_POST['member_name'];
	$member_token_line 	= isset($_POST['member_token_line']) ? $_POST['member_token_line'] : false;
	$member_tel 		= $_POST['member_tel'];
	$member_code_id 	= $_POST['member_code_id'];
	$member_pass  		= substr($member_code_id,-6);
	
	// ---- ข้อมูลที่อยู่ ----
	$address_detail 	= isset($_POST['address_detail']) 	? $_POST['address_detail'] 		: false;
	$address_province 	= isset($_POST['address_province']) ? $_POST['address_province'] 	: false;
	$address_amphure 	= isset($_POST['address_amphure']) 	? $_POST['address_amphure'] 	: false;
	$address_district 	= isset($_POST['address_district']) ? $_POST['address_district'] 	: false;
	$address_zipcode 	= isset($_POST['address_zipcode']) 	? $_POST['address_zipcode'] 	: false;

	// ---- adress style ----
	$address_type 		= $_POST['address_type'];

	if ($address_type != '' || $system_style == 1) {	
		$address_detail_2 	= $_POST['address_detail'];
		$address_province_2 = $_POST['address_province'];
		$address_amphure_2 	= $_POST['address_amphure'];
		$address_district_2 = $_POST['address_district'];
		$address_zipcode_2 	= $_POST['address_zipcode'];
	}
	else {
		$address_detail_2 	= isset($_POST['address_detail_2']) 	? $_POST['address_detail_2'] 	: false;
		$address_province_2 = isset($_POST['address_province_2']) 	? $_POST['address_province_2'] 	: false;
		$address_amphure_2 	= isset($_POST['address_amphure_2']) 	? $_POST['address_amphure_2'] 	: false;
		$address_district_2 = isset($_POST['address_district_2']) 	? $_POST['address_district_2'] 	: false;
		$address_zipcode_2 	= isset($_POST['address_zipcode_2']) 	? $_POST['address_zipcode_2'] 	: false;
	}

	// ----- ธนาคาร ---------------
	$member_bank_own 	= isset($_POST['member_bank_own']) 	? $_POST['member_bank_own'] 	: false;
	$member_bank_name 	= isset($_POST['member_bank_name']) ? $_POST['member_bank_name'] 	: false;
	$member_bank_id 	= isset($_POST['member_bank_id']) 	? $_POST['member_bank_id'] 		: false;
	
	mysqli_query($connect, "INSERT INTO system_member (
		member_code,			member_email, 			member_pass,
		member_title_name,		member_name,			member_user,			
		member_tel,				member_bank_own,		member_bank_name,		
		member_bank_id,			member_code_id,			member_token_line			
		)
		VALUES(
		'$member_code',			'$member_email', 		'$member_pass',
		'$member_title_name',	'$member_name',			'$member_code',			
		'$member_tel', 			'$member_bank_own',		'$member_bank_name',	
		'$member_bank_id',		'$member_code_id',		'$member_token_line'
		)")or die(mysqli_error($connect));
	$member_id = $connect -> insert_id;

	if ($address_detail != false) {
		mysqli_query($connect, "INSERT INTO system_address (
			address_member, address_detail, address_district, address_amphure, address_province, address_zipcode)
		VALUES (
			'$member_id', '$address_detail', '$address_district', '$address_amphure', '$address_province', '$address_zipcode'
		)")or die(mysqli_error($connect));
			 
	}
	if ($address_detail_2 != false) {
		mysqli_query($connect, "INSERT INTO system_address (
			address_member, address_detail, address_district, address_amphure, address_province, address_zipcode, address_type)
		VALUES (
			'$member_id', '$address_detail_2', '$address_district_2', '$address_amphure_2', '$address_province_2', '$address_zipcode_2', 1
		)")or die(mysqli_error($connect));
			 
	}

	if ($system_liner == 1) { 
		
		$liner_expire 	= date('Y-m-d',strtotime('+3 month'))."<br>";
		
		mysqli_query($connect, "INSERT INTO system_liner (liner_member, liner_direct, liner_expire) VALUES ('$member_id', '$liner_direct', '$liner_expire')");

		// line notification and plus liner
		$array = array($liner_direct);
		while ($array) {
			
			$array_end 	= end($array);
			$query 		= mysqli_query($connect, "SELECT * FROM system_liner WHERE liner_member = '$array_end' ");
			$data 		= mysqli_fetch_array($query);
			$liner_direct = $data['liner_direct'];
			$liner_member = $data['liner_member'];

			if ($liner_direct != 0) {

				mysqli_query($connect, "UPDATE system_liner SET liner_count = liner_count + 1, liner_count_day = liner_count_day + 1, liner_count_month = liner_count_month + 1 WHERE liner_member = '$liner_member' ");

				$query 	= mysqli_query($connect, "SELECT * FROM system_member WHERE member_id = '$liner_direct' ");
				$data 	= mysqli_fetch_array($query);
				$token 	= $data['member_token'];
				$message= "มีสมาชิกในสายงานเพิ่ม โดยรหัสสมาชิกที่สมัครเข้ามาคือ " . $member_code;

				line ($token, $message);
				array_push($array, $liner_direct);
			}
			else {
				mysqli_query($connect, "UPDATE system_liner SET liner_count = liner_count + 1, liner_count_day = liner_count_day + 1, liner_count_month = liner_count_month + 1 WHERE liner_member = 1 ");
				break;
			}
		}
		// -----------------------------------------------
	}

	// Send Username & Password to Email
	$detail = '<html>
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title> ' . $company . ' </title>
		</head>
		<body>
		<h3>อีเมลแจ้งรหัสผ่านจาก ' . $company . ' </h3>
		<p>สวัสดีค่ะคุณ ' . $member_name . '</p>
		<p>Username และ Password เพื่อเข้าใช้งานระบบของคุณคือ</p>
		<b>Username :</b> ' . $member_code . '<br>
		<b>Password :</b> ' . $member_pass . '<br>
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


	header("location:../$page?page=insert_member&status=success&message=0&downline_id=$member_id");

}
elseif (isset($_POST['action']) && $_POST['action'] == 'edit_member') {

	$member_id 		= isset($_POST['member_id']) 	? $_POST['member_id'] 		: false;
	$direct_id 		= isset($_POST['direct_id']) 	? $_POST['direct_id'] 		: false;
	$member_class 	= isset($_POST['member_class']) ? $_POST['member_class'] 	: false;

	if ($member_class != false && $member_class != "") {
		mysqli_query($connect, "UPDATE system_member SET member_class = '$member_class' WHERE member_id = '$member_id' ");
	}

	if ($direct_id != false && $direct_id != "") {
		
		if ($member_id == $direct_id) {

			header("Location:../admin.php?page=liner&status=warning&message=You assign your member code");
			die();

		}

		$query 			= mysqli_query($connect, "SELECT * FROM system_liner WHERE (liner_member = '$direct_id') ");
		$data 			= mysqli_fetch_array($query);
		$liner_direct   = $data['liner_id'];

		mysqli_query($connect, "UPDATE system_liner SET liner_direct = '$liner_direct' WHERE liner_member = '$member_id' ");


	}
	
	header('Location:../admin.php?page=liner&status=success&message=0');

}
elseif (isset($_POST['action']) && $_POST['action'] == 'edit_profile') {
    
    $page          		= $_POST['page'];
    // --- ชื่อ รหัสสมาชิก อีเมล ----
    $member_id          = $_POST['member_id'];
    $member_name        = $_POST['member_name'];
    $member_code_id     = $_POST['member_code_id'];
    $member_token_line 	= isset($_POST['member_token_line']) ? $_POST['member_token_line'] : false;
    $member_tel         = $_POST['member_tel'];
    $member_email       = $_POST['member_email'];
    $member_image_card  = $_POST['member_image_card'];
    $member_image_cover = $_POST['member_image_cover'];
	/*
	$member_heir_name  	= $_POST['member_heir_name'];
    $member_heir_id  	= $_POST['member_heir_id'];
	*/
    // -----------------------------
    $query          = mysqli_query($connect, "SELECT * FROM system_member WHERE (member_code_id = '$member_code_id') AND (member_id != '$member_id') ");
    $check_code_id  = mysqli_fetch_array($query);

    if ($check_code_id) {
    	header("location:../$page?page=edit_profile&status=warning&message=รหัสบัตรประชาชนนี้มีในระบบแล้ว&member_id=$member_id");
    	die();
    }
    else {

        if ($_FILES['member_image_card_new']['name'] != '') {

            // ------ ลบรูปเก่าออก -------
            if ($member_image_card != '') {
                unlink ("../assets/images/card/$member_image_card");
            }

            // -------- ขั้นตอนการเปลี่ยนชื่อไฟล์ และย้ายไฟล์รูป -----------
            $type = strrchr($_FILES['member_image_card_new']['name'],".");    //เอาชื่อไฟล์เก่าออกให้เหลือแต่นามสกุล
            $member_image_card = "codeid_" .  date('YmdHis') . $type;    //ตั้งชื่อไฟล์ใหม่โดยเอาเวลาไว้หน้าชื่อไฟล์เดิม
            move_uploaded_file($_FILES['member_image_card_new']['tmp_name'], "../assets/images/card/$member_image_card");

        }

        if ($_FILES['member_image_cover_new']['name'] != '') {

            // ------ ลบรูปเก่าออก -------
            if ($member_image_cover != '') {
                unlink ("../assets/images/profiles/$member_image_cover");
            }

            // -------- ขั้นตอนการเปลี่ยนชื่อไฟล์ และย้ายไฟล์รูป -----------
            $type = strrchr($_FILES['member_image_cover_new']['name'],".");    	//เอาชื่อไฟล์เก่าออกให้เหลือแต่นามสกุล
            $member_image_cover = "profile_" .  date('YmdHis') . $type;    		//ตั้งชื่อไฟล์ใหม่โดยเอาเวลาไว้หน้าชื่อไฟล์เดิม
            move_uploaded_file($_FILES['member_image_cover_new']['tmp_name'], "../assets/images/profiles/$member_image_cover");

        }
        
        mysqli_query($connect, "UPDATE system_member 
        SET	member_name         = '$member_name', 
            member_code_id      = '$member_code_id',
            member_tel          = '$member_tel',
            member_token_line   = '$member_token_line',
            member_image_card   = '$member_image_card', 
            member_image_cover  = '$member_image_cover', 
            member_email        = '$member_email'
        WHERE member_id         = '$member_id'");

        header("location:../$page?page=edit_profile&status=success&message=0&member_id=$member_id");
    }

}
elseif (isset($_POST['action']) && $_POST['action'] == 'edit_address') {
    
    $page          		= $_POST['page'];

    $member_id          = $_POST['member_id'];
    $address_id 		= $_POST['address_id'];
	$address_id_2 		= $_POST['address_id_2'];
    
	$address_detail 	= $_POST['address_detail'];
	$address_province 	= $_POST['address_province'];
	$address_amphure 	= $_POST['address_amphure'];
	$address_district 	= $_POST['address_district'];
	$address_zipcode 	= $_POST['address_zipcode'];

	// ---- adress style ----
	$address_type 		= $_POST['address_type'];

	if ($address_type != '' || $system_style == 1) {	
		$address_detail_2 	= $_POST['address_detail'];
		$address_province_2 = $_POST['address_province'];
		$address_amphure_2 	= $_POST['address_amphure'];
		$address_district_2 = $_POST['address_district'];
		$address_zipcode_2 	= $_POST['address_zipcode'];
	}
	else {
		$address_detail_2 	= $_POST['address_detail_2'];
		$address_province_2 = $_POST['address_province_2'];
		$address_amphure_2 	= $_POST['address_amphure_2'];
		$address_district_2 = $_POST['address_district_2'];
		$address_zipcode_2 	= $_POST['address_zipcode_2'];
	}

	if ($address_detail != '') {
		
		mysqli_query($connect, "REPLACE INTO system_address (
			address_id, 
			address_member, 
			address_detail, 
			address_district, 
			address_amphure, 
			address_province, 
			address_zipcode)
			VALUES (
			'$address_id', 
			'$member_id', 
			'$address_detail', 
			'$address_district', 
			'$address_amphure', 
			'$address_province', 
			'$address_zipcode'
			)")or die(mysqli_error($connect));
	}
	if ($address_detail_2 != '') {
		
		mysqli_query($connect, "REPLACE INTO system_address (
			address_id, 
			address_member, 
			address_detail, 
			address_district, 
			address_amphure, 
			address_province, 
			address_zipcode, 
			address_type)
			VALUES (
			'$address_id_2', 
			'$member_id', 
			'$address_detail_2', 
			'$address_district_2', 
			'$address_amphure_2', 
			'$address_province_2', 
			'$address_zipcode_2', 
			1
			)")or die(mysqli_error($connect));
	}
    header("location:../$page?page=edit_profile&status=success&message=0&member_id=$member_id");

}
elseif (isset($_POST['action']) && $_POST['action'] == 'edit_bank') {
    
	$page          		= $_POST['page'];

    // ข้อมูลธนาคาร 
    $member_id          = $_POST['member_id'];
    $member_bank_id     = $_POST['member_bank_id'];
    $member_bank_own    = $_POST['member_bank_own'];
    $member_bank_name   = $_POST['member_bank_name'];
    $member_image_bank  = $_POST['member_image_bank'];

    if ($_FILES['member_image_bank_new']['name'] != '') {

        // ลบรูปเก่าออก 
        if ($member_image_bank != '') {
            unlink ("../assets/images/card/" . $member_image_bank);
        }

        // ขั้นตอนการเปลี่ยนชื่อไฟล์ และย้ายไฟล์รูป 
        $type = strrchr($_FILES['member_image_bank_new']['name'],".");    //เอาชื่อไฟล์เก่าออกให้เหลือแต่นามสกุล
        $member_image_bank = "bank_" .  date('YmdHis') . $type;    //ตั้งชื่อไฟล์ใหม่โดยเอาเวลาไว้หน้าชื่อไฟล์เดิม
        move_uploaded_file($_FILES['member_image_bank_new']['tmp_name'], "../assets/images/card/" . $member_image_bank);

    }
    mysqli_query($connect, "UPDATE system_member 
    SET	member_bank_id      = '$member_bank_id', 
        member_bank_name    = '$member_bank_name', 
        member_bank_own     = '$member_bank_own', 
        member_image_bank   = '$member_image_bank'
    WHERE member_id  = '$member_id'");
    
    header("location:../$page?page=edit_profile&status=success&message=0&member_id=$member_id");

}
elseif (isset($_POST['action']) && $_POST['action'] == 'edit_login') {

	$page          			= $_POST['page'];

    $member_id              = $_POST['member_id'];
    $member_user            = $_POST['member_user'];
    $member_pass            = $_POST['member_pass'];
    $member_pass_new        = $_POST['member_pass_new'];
    $member_pass_recheck    = $_POST['member_pass_recheck'];

    // เช็คไอดีซ้ำ 
    $sql_check_id       = mysqli_query($connect, "SELECT * FROM system_member WHERE (member_user = '$member_user') AND (member_id != '$member_id') ");
    $check_id           = mysqli_fetch_array($sql_check_id);

    if ($check_id) {
        header("location:../" . $_POST['page'] . "?page=edit_profile&status=warning&message=ชื่อที่ใช้เข้าสู่ระบบนี้มีอยู่ในระบบแล้ว&member_id=" . $member_id);
        die;
    }
    elseif ($member_pass_recheck != '' && $member_pass_new != $member_pass_recheck) {
        header("location:../" . $_POST['page'] . "?page=edit_profile&status=warning&message=ระบุพาสเวิร์ดไม่ตรงกัน&member_id=" . $member_id);
        die;
    }
    else {
        if ($member_pass_new != '') {
            mysqli_query($connect, "UPDATE system_member 
            SET	member_user = '$member_user', 
                member_pass = '$member_pass_new'
            WHERE member_id = '$member_id'");
        }
        else {
            mysqli_query($connect, "UPDATE system_member 
            SET	member_user = '$member_user'
            WHERE member_id = '$member_id'");
        }

        header("location:../$page?page=edit_profile&status=success&message=0&member_id=$member_id");

    }

}
elseif (isset($_GET['action']) && $_GET['action'] == 'block_member') {

	$member_id 		= $_GET['member_id'];

	mysqli_query($connect, "UPDATE system_member SET member_status = 1 WHERE member_id = '$member_id' ");
	mysqli_query($connect, "UPDATE system_commission_liner SET commission_liner_type = 1 WHERE commission_liner_member = '$member_id' ");

	header('Location:../admin.php?page=liner&status=success&message=0');

}
elseif (isset($_GET['action']) && $_GET['action'] == 'change_lang') {

	$member_id 		= $_GET['member_id'];
	$member_lang 	= $_GET['lang'];

	mysqli_query($connect, "UPDATE system_member SET member_lang = '$member_lang' WHERE member_id = '$member_id' ");

	header('Location:../member.php');

}
?>