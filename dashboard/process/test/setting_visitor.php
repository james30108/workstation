<?php include('function.php'); 

if (!file_exists("../assets/images/profiles/")) { mkdir("../assets/images/profiles/"); }

if ($_GET['action'] == 'facebook_insert') {

	$visitor_code 		= $_GET['visitor_code'];
	$visitor_name 		= $_GET['visitor_name'];
	$visitor_email 		= $_GET['visitor_email'];
	$visitor_cover_image 	= $_GET['visitor_cover_image'];

	$query = mysqli_query($connect, "SELECT * FROM system_visitor WHERE visitor_code = '$visitor_code' ");
	$data  = mysqli_fetch_array($query);

	if ($data) {
		$_SESSION['visitor_id'] = $visitor_id;
	}
	else {
		mysqli_query($connect, "INSERT INTO system_visitor (
			visitor_code,
			visitor_name,
			visitor_email,
			visitor_cover_image	
		)
		VALUES(
			'$visitor_code',
			'$visitor_name',
			'$visitor_email',
			'$visitor_cover_image'	
		)")or die(mysqli_error($connect));
		$visitor_id = $connect -> insert_id;

		$_SESSION['visitor_id'] = $visitor_id;
	}
	header("location:../../index.php");
}

?>