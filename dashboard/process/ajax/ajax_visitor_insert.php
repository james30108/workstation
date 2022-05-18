<?php include('../function.php'); 

// แสดงรหัสอัปไลน์ 
if (isset($_GET['direct_code'])) {

	$direct_code = $_GET['direct_code'];
	$query       = mysqli_query($connect, "SELECT * FROM system_member WHERE member_code = '$direct_code' ");
	$data        = mysqli_fetch_array($query);

	if (isset($data)) {
		echo $data['member_id'] . "," . $data['member_name'];
	} 
	elseif (!isset($data)) {
		echo "none";
	}
	
} 

?>