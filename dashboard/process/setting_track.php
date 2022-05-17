<?php include('function.php');

if ($_POST['action'] == 'insert') {
	$order_id       	= $_POST['order_id'];
	$order_track_name 	= $_POST['order_track_name'];
	$order_track_id 	= strtoupper($_POST['order_track_id']);

	mysqli_query($connect, "UPDATE system_order SET order_status = 3, order_track_name = '$order_track_name', order_track_id = '$order_track_id' WHERE order_id = '$order_id' ");
	
	$query = mysqli_query($connect, "SELECT * FROM system_order WHERE order_id = '$order_id' ");
	$data  = mysqli_fetch_array($query);
	 
	$contact_member = $data['order_member'];
	$contact_buyer 	= $data['order_buyer_id'];
	$contact_name 	= "System Tracking";
	$contact_email 	= $data['order_buyer_email'];

	mysqli_query($connect, "INSERT INTO system_contact (
        contact_title, 
        contact_member, 
		contact_buyer, 
        contact_name, 
        contact_email, 
        contact_detail, 
        contact_type
	) 
    VALUES (
        'ทำการจัดส่งสินค้าเรียบร้อย',
        '$contact_member',
		'$contact_buyer',
        '$contact_name', 
        '$contact_email', 
        '$order_id', 
        3)");

	header('location:../admin.php?page=order&status=success&message=0');
}
elseif ($_GET['action'] == 'tracking') {
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
?>