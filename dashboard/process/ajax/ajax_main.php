<?php include('../function.php'); 

if (isset($_GET['lang'])) { $lang = $_GET['lang']; }
include("../include_lang.php");

// provinces
if (isset($_GET['provinces'])) {
	
	echo "<label class='form-label'>$l_amphures</label>";
	echo "<select class='form-select' name='address_amphure' id='amphures_select' required>";
	echo "<option value='' selected>$l_amphures</option>";
	
	$provinces  = $_GET['provinces'];
	$sql        = mysqli_query($connect, "SELECT * FROM system_amphures WHERE PROVINCE_ID = '$provinces' ");
	while ($data = mysqli_fetch_array($sql)) {

		$id 	= $data['AMPHUR_ID'];
		$name 	= $data['AMPHUR_NAME'];
		echo "<option value='$id'>$name</option>";

	}
	echo "</select>";
}

// amphures
if (isset($_GET['amphures'])) {
	
	echo "<label class='form-label'>$l_district</label>";
	echo "<select class='form-select' name='address_district' id='districts_select' required>";
	echo "<option value='' selected>$l_district</option>";

	$amphures    = $_GET['amphures'];
	$sql         = mysqli_query($connect, "SELECT * FROM system_districts WHERE AMPHUR_ID = '$amphures' ");
	while ($data = mysqli_fetch_array($sql)) {

		$id 	= $data['DISTRICT_CODE'];
		$name 	= $data['DISTRICT_NAME'];
		echo "<option value='$id'>$name</option>";
	
	}
	echo "</select>";
}

// districts
if (isset($_GET['districts'])) {
	
	echo "<label class='form-label'>$l_zipcode</label>";

	$districts  = $_GET['districts'];
	$sql        = mysqli_query($connect, "SELECT * FROM system_zipcodes WHERE district_code = '$districts' ");
	$data       = mysqli_fetch_array($sql);
	if ($data) {
		echo "<input type='text' class='form-control' name='address_zipcode' id='postcode_data' value='" . $data['zipcode'] . "' maxlength=5 maxlength=5>";
	}
	else {
		echo "<input type='text' class='form-control' name='address_zipcode' id='postcode_data' maxlength=5 maxlength=5 placeholder='กรุณากรอกรหัสไปรษณีย์'>";
	}
}

// provinces_2
if (isset($_GET['provinces_2'])) {
	
	echo "<label class='form-label'>$l_amphures</label>";
	echo "<select class='form-select' name='address_amphure_2' id='amphures_select_2' required>";
	echo "<option value='' selected>$l_amphures</option>";
	
	$provinces_2 = $_GET['provinces_2'];
	$sql       	 = mysqli_query($connect, "SELECT * FROM system_amphures WHERE PROVINCE_ID = '$provinces_2' ");
	while ($data = mysqli_fetch_array($sql)) {

		$id 	= $data['AMPHUR_ID'];
		$name 	= $data['AMPHUR_NAME'];
		echo "<option value='$id'>$name</option>";

	}
	echo "</select>";
}

// amphures_2
if (isset($_GET['amphures_2'])) {
	
	echo "<label class='form-label'>$l_district</label>";
	echo "<select class='form-select' name='address_district_2' id='districts_select_2' required>";
	echo "<option value='' selected>$l_district</option>";

	$amphures_2  = $_GET['amphures_2'];
	$sql 		 = mysqli_query($connect, "SELECT * FROM system_districts WHERE AMPHUR_ID = '$amphures_2' ");
	while ($data = mysqli_fetch_array($sql)) {

		$id 	= $data['DISTRICT_CODE'];
		$name 	= $data['DISTRICT_NAME'];
		echo "<option value='$id'>$name</option>";

	}
	echo "</select>";
}

// districts_2
if (isset($_GET['districts_2'])) {
	
	echo "<label class='form-label'>$l_zipcode</label>";

	$districts_2 = $_GET['districts_2'];
	$sql 		 = mysqli_query($connect, "SELECT * FROM system_zipcodes WHERE district_code = '$districts_2' ");
	$data 		 = mysqli_fetch_array($sql);
	if ($data) {
		echo "<input type='text' class='form-control' name='address_zipcode_2' id='postcode_data_2' value='" . $data['zipcode'] . "' maxlength=5 maxlength=5>";
	}
	else {
		echo "<input type='text' class='form-control' name='address_zipcode_2' id='postcode_data_2' maxlength=5 maxlength=5 placeholder='กรุณากรอกรหัสไปรษณีย์'>";
	}
}

// check upline
if (isset($_GET['check_upline'])) {

	$direct = $_GET['check_upline'];
	$query  = mysqli_query($connect, "SELECT system_member.*, system_liner.* 
		FROM system_member 
		INNER JOIN system_liner ON (system_member.member_id = system_liner.liner_member)
		WHERE member_code = '$direct' ");
	$data  = mysqli_fetch_array($query);
	$dir_id= $data['liner_id']; 

	$query = mysqli_query($connect, "SELECT * FROM system_liner WHERE liner_direct = '$dir_id' ");
	$count = mysqli_num_rows($query);
	
	if ($downline_max != 0 && $count > $downline_max) {
		echo "full";
	}
	else if (isset($data)) {
		echo $data['liner_id'] . "," . $data['member_name'];
	} 
	elseif (!isset($data)) {
		echo "none";
	}
}

// check icard (for insert)
if (isset($_GET['id_card'])) { 

	$id_card= $_GET['id_card'];
	
	$query = mysqli_query($connect, "SELECT * FROM system_member WHERE member_code_id = '$id_card' ");
	$data  = mysqli_fetch_array($query);

	if (!isset($data)) {
		echo $id_card;
	}
	
} 

// check icard (for edit)
if (isset($_GET['id_card2'])) { 

	$id_card= $_GET['id_card2'];
	$id 	= $_GET['id'];

	$query = mysqli_query($connect, "SELECT * FROM system_member WHERE (member_code_id = '$id_card') AND (member_id != '$id') ");
	$data  = mysqli_fetch_array($query);

	if (!isset($data)) {
		echo $id_card;
	}
	
} 

// Check Member
if (isset($_GET['direct_code'])) {

	$direct = $_GET['direct_code'];
	$query  = mysqli_query($connect, "SELECT * FROM system_member WHERE member_code = '$direct' ");
	$data   = mysqli_fetch_array($query);
	
	if (isset($data)) {
		echo $data['member_id'] . "," . $data['member_name'];
	} 
	elseif (!isset($data)) {
		echo "none";
	}
}

?>