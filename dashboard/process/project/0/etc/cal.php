<?php include('function.php'); 
set_time_limit(0);

/*
// commission percent for support code 
for ($i = 0; $i < 10710; $i++) { 
	insert_support_code ($connect, $com2_max, 0, $com2_number, 2);
}
*/
/*
// เช็คว่ามีอัปไลน์นี้ในระบบไหม
$query = mysqli_query($connect, "SELECT * FROM system_liner2");
while ($data = mysqli_fetch_array($query)) {
	//echo "ID " . $data['liner2_direct'] . " count " . $data['count'] . "<br>";

	$query2 = mysqli_query($connect, "SELECT * FROM system_liner2 WHERE liner2_id = '".$data['liner2_direct']."' ");
	$data2  = mysqli_fetch_array($query2);

	if (!$data2) {
		echo "ID " . $data['liner2_id'] . " ไม่มีอัปไลน์ <br>";
	}
}
*/

// เพิ่มคะแนนจากการสั่งซื้อสินค้าให้ member ในผัง liner2 ย้อนหลัง
/*
for ($i = 2239; $i <= 3504; $i++) { 

	$query1 = mysqli_query($connect, "SELECT * FROM system_order WHERE order_id = '$i' ");
	$data1  = mysqli_fetch_array($query1);
	if ($data1['order_status'] != 1) {
		continue;
	}
	echo "ID " . $data1['order_code'] . "<br>";
	mysqli_query($connect, "UPDATE system_liner2 SET liner2_status = 1 WHERE liner2_member = '".$data1['order_member']."'");
	
	// ---- Insert ID ----
	$query = mysqli_query($connect, "SELECT * FROM system_liner2 WHERE liner2_member = '".$data1['order_member']."' ");
	$data  = mysqli_fetch_array($query);

	$array          = array($data['liner2_id']);
	while ($array) {
		
		$array_end  = end($array);
		$query      = mysqli_query($connect, "SELECT * FROM system_liner2 WHERE liner2_id = '$array_end' ");
		$data       = mysqli_fetch_array($query);

		if ($data['liner2_direct'] != 0) {
			array_push($array, $data['liner2_direct']);
		}
		else {
			break;
		}
	}

	foreach ($array AS $key => $value) {

		if ($key == 0) {
			continue;
		}
		echo "id = " . $value . " +2 point <br>";
		mysqli_query($connect, "UPDATE system_liner2 SET liner2_point = liner2_point + 2 WHERE liner2_id = '$value'");
	}
	
}
*/
/*
// ย้าย member ในชั้นหนึ่งไปแทน sp ในอีกชั้นหนึ่ง
$query = mysqli_query($connect, "SELECT * FROM system_liner2 WHERE liner2_type = 0 AND liner2_class >= 7 ");
while ($data  = mysqli_fetch_array($query)) {
	
	echo "ID = " . $data['liner2_id'] . " ชั้นที่ " . $data['liner2_class'] . "<br>";
	//mysqli_query($connect, "UPDATE system_liner2 SET liner2_type = 1, liner2_member = 0  WHERE liner2_id = '".$data['liner2_id']."' ");
	//mysqli_query($connect, "UPDATE system_liner2 SET liner2_type = 0, liner2_member = '".$data['liner2_member']."'  WHERE liner2_type = 1 LIMIT 1 ");
}
*/


// UPDATE `system_liner2` SET `liner2_sp_count`=0
/*
// เช็คว่าไอดีไหนมีลูกข่ายเกิน 5 คน
$query = mysqli_query($connect, "SELECT *, COUNT(liner2_direct) AS count FROM system_liner2 GROUP BY liner2_direct HAVING count > 5");
while ($data = mysqli_fetch_array($query)) {
	//echo "ID " . $data['liner2_direct'] . " count " . $data['count'] . "<br>";

	$query2 = mysqli_query($connect, "SELECT * FROM system_liner2 WHERE liner2_direct = '".$data['liner2_direct']."' LIMIT 1");
	$data2  = mysqli_fetch_array($query2);

	echo "ID " . $data['liner2_direct'] . " count " . $data['count'] . "ลูกข่าย " . $data2['liner2_id'] ."<br>";
	//mysqli_query($connect, "DELETE FROM system_liner2 WHERE liner2_id = '".$data2['liner2_id']."' ");

}
*/

####
	/*
	$array          = array($id);
    while ($array) {
        
        $array_end  = end($array);
        $query      = mysqli_query($connect, "SELECT * FROM system_liner2 WHERE liner2_id = '$array_end' ");
        $data       = mysqli_fetch_array($query);

        if ($data['liner2_direct'] != 0) {
            array_push($array, $data['liner2_direct']);
        }
        else {
            break;
        }
    }

    // ---- Find Class member And Insert Bonus ---
    $count = 1; // จำนวนชั้น
    foreach ($array AS $key => $value) {

        if ($key == 0) {
            continue;
        }

        $sql              = mysqli_query($connect, "SELECT * FROM system_liner2 WHERE (liner2_id = '$value')");
        $data             = mysqli_fetch_array($sql);
        $point_member     = $data['liner2_id'];
        echo $point_member . "<br>";
        $count++;

        // ---- update point in members get bonus -----
        echo "id " . $point_member . "ได้คะแนน +2 <br>";
        mysqli_query ($connect, "UPDATE system_liner2 SET liner2_point = liner2_point + 2 WHERE liner2_id = '$point_member' ");
        if ($com2_number < $count) {
            break;
        }
    }
	*/
	//mysqli_query($connect, "UPDATE system_liner2 SET liner2_point = liner2_sp_count * (( 1000 * $bonus_class_2 ) / 100)");
####


$start 	= $_GET['start'];
$end 	= $_GET['end'];
/*
// เพิ่ม count
for ($i = 0; $i <= 20000 ; $i++) { 
//for ($i = $start; $i <= $end; $i++) { 
	$query = mysqli_query($connect, "SELECT * FROM system_liner2 WHERE (liner2_id = '$i')");
	$data  = mysqli_fetch_array($query);
	$type  = $data['liner2_type'];

	// ---- Insert ID ----
	$array          = array($data['liner2_id']);
	while ($array) {
		
		$array_end  = end($array);
		$query      = mysqli_query($connect, "SELECT * FROM system_liner2 WHERE liner2_id = '$array_end' ");
		$data       = mysqli_fetch_array($query);

		if ($data['liner2_direct'] != 0) {
			array_push($array, $data['liner2_direct']);
		}
		else {
			break;
		}
	}

	foreach ($array AS $key => $value) {

		if ($key == 0) {
			continue;
		}

		mysqli_query($connect, "UPDATE system_liner2 SET liner2_count = liner2_count + 1 WHERE liner2_id = '$value'");
	}
	
	//print_r($array); 
	echo $i . "<br>";
}
*/

/*
// เพิ่ม count โดยนับเฉพาะรหัสที่เป็นรหัส support code
for ($i = 0; $i <= 20000 ; $i++) { 
//for ($i = $start; $i <= $end; $i++) { 
	$query = mysqli_query($connect, "SELECT * FROM system_liner2 WHERE (liner2_id = '$i') AND (liner2_type = 1) AND (liner2_class <= 7)");
	$data  = mysqli_fetch_array($query);

	// ---- Insert ID ----
	$array          = array($data['liner2_id']);
	while ($array) {
		
		$array_end  = end($array);
		$query      = mysqli_query($connect, "SELECT * FROM system_liner2 WHERE liner2_id = '$array_end' ");
		$data       = mysqli_fetch_array($query);

		if ($data['liner2_direct'] != 0) {
			array_push($array, $data['liner2_direct']);
		}
		else {
			break;
		}
	}
	
	$count = 0; // จำนวนชั้น
	foreach ($array AS $key => $value) {

		if ($key == 0) {
			continue;
		}
		$count++;
		//mysqli_query($connect, "UPDATE system_liner2 SET liner2_count = liner2_count + 1 WHERE liner2_id = '$value'");

		if ($count <= $com2_number) {
			mysqli_query($connect, "UPDATE system_liner2 SET liner2_sp_count = liner2_sp_count + 1, liner2_point = liner2_point + 2 WHERE liner2_id = '$value'");
		}
	}
	
	//print_r($array); 
	echo $i . "<br>";
}
*/
echo "เสร็จสิ้น";
?>