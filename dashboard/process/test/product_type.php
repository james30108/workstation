<?php include('../function.php'); 

/*
//
// เอกสารการเพิ่มข้อมูลหมวดหมู่สินค้าจากการดึง API
//
*/

// รีเซ็ตตาราง
mysqli_query($connect, "TRUNCATE TABLE system_product_type");
mysqli_query($connect, "INSERT INTO system_product_type (product_type_code, product_type_name) VALUES ('PT-1', 'สินค้าอื่นๆ')");
mysqli_query($connect, "UPDATE system_product SET product_type = 1 ");


// ขั้นตอนการเพิ่มหมายเลขสินค้า
$query 	= mysqli_query($connect, "SELECT * FROM system_product WHERE product_group != '' GROUP BY product_group ");
$i 		= 1;
while ($data = mysqli_fetch_array($query)) {
	
	$i++;
	$product_type_code = "PT-" . $i; 
	$product_type_name = $data['product_group'];
	echo $data['product_group'] . "<br>";

	mysqli_query($connect, "INSERT INTO system_product_type (product_type_code, product_type_name) VALUES ('$product_type_code', '$product_type_name') ");

}

// ขั้นตอนการเพิ่มไอดีประเภทสินค้า
$query 	= mysqli_query($connect, "SELECT * FROM system_product_type ");
while ($data = mysqli_fetch_array($query)) {
	
	$product_group = $data['product_type_name'];
	$product_type  = $data['product_type_id'];
	mysqli_query($connect, "UPDATE system_product SET product_type = '$product_type' WHERE product_group = '$product_group' ");

}
?>