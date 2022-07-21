<?php 

// Admin
function insert_admin ($admin_user) {
	mysql_query("INSERT INTO system_admin (admin_name, admin_user, admin_status) VALUES ('Admin', '$admin_user', 1)") or die(mysql_error());
}

function delete_admin ($username) {
	$admin_user = select("admins", " WHERE username = '$username' ");
    $admin_user = $admin_user["id"];
    del("system_admin","where admin_user = '$admin_user' ");
}

function login_admin ($login_id) {

	$admin_id   = select("system_admin", "WHERE admin_user = '$login_id' ");
	$_SESSION['admin_id'] = $admin_id['admin_id'];

}

// Member
function insert_member ($username, $name, $mobile, $bankname, $banknumber, $ref) {

	// Users ID
	$member_user    = select("users", "WHERE username = '$username' ");
	$member_user 	= $member_user['id'];

	// ฺBank
	$bank    = select("system_bank", "WHERE bank_acr = '$bankname' ");
	$bank_id = $bank['bank_id'];

	mysql_query("INSERT INTO system_member (
	    member_code, 
	    member_user, 
	    member_name, 
	    member_tel, 
	    member_bank_own, 
	    member_bank_name, 
	    member_bank_id		
	    )
	    VALUES (
	    '$username',
	    '$member_user',
	    '$name',
	    '$mobile',
	    '$name',
	    '$bank_id',
	    '$banknumber'
	    )") or die(mysql_error());
		
	$member_id 		= mysql_insert_id();
	$query 			= mysql_query("SELECT system_member.*, system_liner.* 
		FROM system_member
		INNER JOIN system_liner ON (system_member.member_id = system_liner.liner_member) 
		WHERE member_code = '$ref' ") or die(mysql_error());
	$liner_direct 	= mysql_fetch_array($query);
	$liner_direct   = $liner_direct['liner_id'];

	mysql_query("INSERT INTO system_liner (liner_member, liner_direct, liner_status) VALUES ('$member_id', '$liner_direct', 1)");

	// Plus liner
	$array = array($liner_direct);
	while ($array) {
		
		$array_end 	= end($array);
		$query 		= mysql_query("SELECT * FROM system_liner WHERE liner_member = '$array_end' ");
		$data 		= mysql_fetch_array($query);
		$liner_direct = $data['liner_direct'];
		$liner_member = $data['liner_member'];

		mysql_query("UPDATE system_liner SET liner_count = liner_count + 1, liner_count_day = liner_count_day + 1, liner_count_month = liner_count_month + 1 WHERE liner_member = '$liner_member' ");

		array_push($array, $liner_direct);

		if ($liner_direct == 0) { break; }
	}

}

function edit_member ($username, $name, $mobile, $bank_name, $bank_number, $userstatus) {

	// Users ID
	$member_user    = select("users", "WHERE username = '$username' ");
	$member_user 	= $member_user['id'];

	// Bank
	$status  = $userstatus == 1 ? 0 : 1;
	$bank    = select("system_bank", "WHERE bank_acr = '$bank_name' ");
	$bank_id = $bank['bank_id'];

	if ($bank_id) {
		mysql_query ("UPDATE system_member 
		SET	member_code      = '$username', 
			member_name      = '$name',
			member_tel   	 = '$mobile',
			member_bank_own  = '$name', 
			member_bank_name = '$bank_id', 
			member_bank_id   = '$bank_number',
			member_status 	 = '$status'	
		WHERE member_user    = '$member_user'");
	}
	else {

		echo "<script>alert('กรุณาใส่รหัสย่อธนาคารให้ถูกต้องเพื่อใช้ในระบบเงินปันผลลำดับชั้น')</script>";
		echo "<script>history.back()</script>";
		die ();

	}

}

function commission ($point_bonus, $member_user) {

	// Insert Bonus Default
    /*
    1. คอมมิชชั่นคำนวนตาม % ในแต่ละชั้น
    2. ไม่มีการ Roll Up
    */
	
	// Commission's Level
	$query = mysql_query("SELECT * FROM system_config WHERE config_id = 8 ");
    $data  = mysql_fetch_array($query);
    $com_number = $data['config_value'];

    $query          = mysql_query("SELECT system_member.*, system_liner.*
        FROM system_member 
        INNER JOIN system_liner ON (system_member.member_id = system_liner.liner_member)
        WHERE member_user = '$member_user'");
    $data           = mysql_fetch_array($query);

    // To Member
    $member_id      = $data['member_id'];
    $liner_id       = $data['liner_id'];
    $point_detail   = "เงินเดิมพัน";
    $member_code    = $data['member_code'];
    
	mysql_query ("UPDATE system_member SET 
		member_point        = member_point          + '$point_bonus', 
		member_point_month  = member_point_month    + '$point_bonus' 
		WHERE member_id     = '$member_id' ");
    mysql_query("INSERT INTO system_point (point_member, point_bonus, point_detail) VALUES ('$member_id', '$point_bonus', '$point_detail')");
	mysql_query("UPDATE system_liner SET liner_status = 1 WHERE liner_member = '$member_id' ");

    // To Upline 
	$array  = array($liner_id);
	while ($array) {
		
		$array_end  = end($array);
		$query      = mysql_query("SELECT * FROM system_liner WHERE liner_id = '$array_end' ");
		$data       = mysql_fetch_array($query);

		if      ($data['liner_direct'] != 0) { array_push($array, $data['liner_direct']); }
		else    { break; }

	}

	$count = 1; // จำนวนชั้น
	foreach ($array AS $key => $value) {

		if ($key == 0) { continue; }

		$query            = mysql_query("SELECT system_member.*, system_liner.*
			FROM system_liner 
			INNER JOIN system_member ON (system_liner.liner_member = system_member.member_id)
			WHERE (liner_member = '$value')");
		$data             = mysql_fetch_array($query);
		$point_member     = $data['member_id'];
		$member_status    = $data['member_status'];

		$query = mysql_query("SELECT * FROM system_commission WHERE commission_id = '$count' ");
		$data  = mysql_fetch_array($query);
		$bonus_class      = $data['commission_point'];

		$point            = ( $point_bonus * $bonus_class ) / 100;
		$point_detail     = "รับโบนัสจาก $member_code ซึ่งเป็นลำดับที่ $count";

		echo "<br>$value // commission = $point";

		// ---- member not block ----
		if ($member_status == 0) {

			$count++;

			mysql_query ("INSERT INTO system_point (point_member, point_type, point_bonus, point_detail) VALUES ('$point_member', 1, '$point', '$point_detail')");
			mysql_query ("UPDATE system_liner SET liner_point = liner_point + '$point' WHERE liner_member = '$point_member' ");

		}
	
		if ($count > $com_number) { break; }

	}

}
?>