<?php 

function insert_member ($username, $name, $mobile, $banknumber, $ref) {

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
	    '$username',
	    '$name',
	    '$mobile',
	    '$name',
	    2,
	    '$banknumber'
	    )") or die(mysql_error());
		
	$member_id 		= mysql_insert_id();
	$query 			= mysql_query("SELECT system_member.*, system_liner.* 
		FROM system_member
		INNER JOIN system_liner ON (system_member.member_id = system_liner.liner_member) 
		WHERE member_code = '$ref' ") or die(mysql_error());
	$liner_direct 	= mysql_fetch_array($query);
	$liner_direct   = $liner_direct['liner_id'];

	mysql_query("INSERT INTO system_liner (liner_member, liner_direct) VALUES ('$member_id', '$liner_direct')");

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

/*
function edit_member () {}

function delete_member () {}
*/
?>