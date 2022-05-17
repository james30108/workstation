<title>คำนวนหมายเลขชั้นและจำนวนลูกข่ายทั้งหมด</title>
<?php include('../function.php'); 
set_time_limit(0);

// **** คำนวนหมายเลขชั้นและจำนวนลูกข่ายทั้งหมด ****

// รีเซ็ตค่าในตารางก่อนคำนวน
mysqli_query($connect, "UPDATE system_liner 
	SET liner_count 		= 0,
		liner_count_day 	= 0,
		liner_count_month 	= 0 ");

// คำนวนจำนวนลูกทีม
$query = mysqli_query($connect, "SELECT * FROM system_liner");
$count = mysqli_num_rows($query);
for ($i = 0; $i <= $count ; $i++) { 
	
	$array 	= array($i);
	while ($array) {
		
		$array_end 	= end($array);
		$query 		= mysqli_query($connect, "SELECT * FROM system_liner WHERE liner_id = '$array_end' ");
		$data 		= mysqli_fetch_array($query);
		$liner_direct = $data['liner_direct'];

		if ($liner_direct != 0) {

			mysqli_query($connect, "UPDATE system_liner SET liner_count = liner_count + 1 WHERE liner_id = '$liner_direct' ");
			array_push($array, $liner_direct);
		}
		else {
			mysqli_query($connect, "UPDATE system_liner SET liner_count = liner_count + 1 WHERE liner_member = 1 ");
			break;
		}
	}

}

echo "เสร็จสิ้น";
?>
