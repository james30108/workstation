<?php include('../../../function.php');

$order_id 	= $_GET['order_id'];

$query = mysqli_query($connect, "SELECT * FROM system_order WHERE order_id = '$order_id' ");
$data  = mysqli_fetch_array($query); 

echo $data['order_status'];
?> 