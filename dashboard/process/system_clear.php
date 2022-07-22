<?php include('function.php');

function delete_folder ($direct) {

	if (!file_exists($direct)) { return; }

	$directory 		= opendir($direct);
	while(($data 	= readdir($directory))!==false) {
		
		$file = $direct . "/" . $data;
		@Unlink ($file);
	}

	closedir($directory);
	rmdir($direct);
}

mysqli_query($connect, "TRUNCATE TABLE system_address");

mysqli_query($connect, "TRUNCATE TABLE system_liner");
mysqli_query($connect, "TRUNCATE TABLE system_member");

mysqli_query($connect, "INSERT INTO system_member (member_name, member_code, member_pass, member_user) VALUES ('member', '0000001', '1234', 'member')");
mysqli_query($connect, "INSERT INTO system_liner (liner_member) VALUES ('1')");


mysqli_query($connect, "TRUNCATE TABLE system_product");
mysqli_query($connect, "TRUNCATE TABLE system_product_type");
delete_folder("../assets/images/products");
delete_folder("../assets/images/slips");


mysqli_query($connect, "TRUNCATE TABLE system_comment");
mysqli_query($connect, "TRUNCATE TABLE system_report");
mysqli_query($connect, "TRUNCATE TABLE system_report_detail");
mysqli_query($connect, "TRUNCATE TABLE system_cart");
mysqli_query($connect, "TRUNCATE TABLE system_thread");
mysqli_query($connect, "TRUNCATE TABLE system_thread_type");
mysqli_query($connect, "TRUNCATE TABLE system_contact");
mysqli_query($connect, "TRUNCATE TABLE system_deposit");
mysqli_query($connect, "TRUNCATE TABLE system_tranfer");
mysqli_query($connect, "TRUNCATE TABLE system_liner2");
mysqli_query($connect, "TRUNCATE TABLE system_order");
mysqli_query($connect, "TRUNCATE TABLE system_order_detail");
mysqli_query($connect, "TRUNCATE TABLE system_pay_refer");
mysqli_query($connect, "TRUNCATE TABLE system_point");
mysqli_query($connect, "TRUNCATE TABLE system_product_amount");
mysqli_query($connect, "TRUNCATE TABLE system_notification");
mysqli_query($connect, "TRUNCATE TABLE system_buyer");
mysqli_query($connect, "TRUNCATE TABLE system_package");
mysqli_query($connect, "TRUNCATE TABLE system_withdraw");
mysqli_query($connect, "TRUNCATE TABLE system_visitor");
mysqli_query($connect, "TRUNCATE TABLE system_log");
mysqli_query($connect, "TRUNCATE TABLE system_log_payment");
mysqli_query($connect, "TRUNCATE TABLE system_log_withdraw");

delete_folder("../assets/images/card");
delete_folder("../assets/images/thread");
delete_folder("../assets/images/ewallet_slipe");
delete_folder("../assets/images/profiles");

echo "System clear";

header("location:../admin.php");
?>