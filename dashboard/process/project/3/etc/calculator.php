<?php include('function.php');

$month_last = date("2021-08");

$query = mysqli_query($connect, "SELECT *, SUM(point_bonus) AS sum_point FROM system_point WHERE (point_type = 0) AND (point_create LIKE '%$month_now%') GROUP BY point_member");
while ($data = mysqli_fetch_array($query)) {
    $member_id = $data['point_member'];
    $member_point_month = $data['sum_point'];
    echo $member_id . " - " . $member_point_month . "<br>";
    mysqli_query($connect, "UPDATE system_member SET member_point_month = $member_point_month WHERE member_id = '$member_id' ");
}


//mysqli_query($connect, "UPDATE sysem_member SET (member_month = member_month - 1)");

/*
$query_2      = mysqli_query($connect, "SELECT *, SUM(point_bonus) AS sum_point FROM system_point WHERE (point_type = 0) AND (point_create = '%$month_last%') GROUP BY point_member");
while ($data_2= mysqli_fetch_array($query_2)) {
    $sum_point  = $data_2['sum_point'];
    $member_id  = $data_2['point_member'];
    echo "member id = " . $member_id . " sumpoint_in_month = " . $sum_point . "<br>";

    $array          = array($member_id);
    while ($array) {
        
        $array_end  = end($array);
        $sql        = mysqli_query($connect, "SELECT * FROM system_liner WHERE liner_member = '$array_end' ");
        $data       = mysqli_fetch_array($sql);

        if ($data['liner_direct'] != 0) {
            array_push($array, $data['liner_direct']);
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

        $sql              = mysqli_query($connect, "SELECT system_member.*, system_liner.*, system_class.* 
            FROM system_liner 
            INNER JOIN system_member ON (system_liner.liner_member = system_member.member_id)
            INNER JOIN system_class ON (system_liner.liner_commission_class = system_class.class_id)
            WHERE (liner_member = '$value')");
        $data             = mysqli_fetch_array($sql);
        $point_member     = $data['member_id'];
        $member_status    = $data['member_status'];
        $class_match_level= $data['class_match_level'];
        $sql_bonus_class  = mysqli_query($connect, "SELECT * FROM system_bonus_class WHERE bonus_class_id = '$count' ");
        $data_bonus_class = mysqli_fetch_array($sql_bonus_class);
        $bonus            = ( $sum_point * $data_bonus_class['bonus_class_point'] ) / 100;

        // ---- member not block ----
        if ($member_status == 0) {
            
            // ---- give commision to member in class match level ----
            if ($class == 1 && $class_match_level < $count) {
                continue;
            }
            echo "----- id ที่ได้รับ bonus " . $point_member . " ได้รับโบนัส = " . $bonus . " ชั้นที่ = " . $count . " จากการซื้อของ ID " . $member_id . "<br>";
            $count++;
            
            mysqli_query ($connect, "UPDATE system_liner SET liner_point_final = liner_point_final + '$bonus' WHERE liner_id = '$point_member' ");

        }

        if ($class_number < $count) {
            break;
        }
    }
}
*/


/*
// -------- Insert ID ---------
$array          = array($member_id);
while ($array) {
    
    $array_end  = end($array);
    $sql        = mysqli_query($connect, "SELECT * FROM system_liner WHERE liner_member = '$array_end' ");
    $data       = mysqli_fetch_array($sql);

    if ($data['liner_direct'] != 0) {
        array_push($array, $data['liner_direct']);
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

    $sql              = mysqli_query($connect, "SELECT system_member.*, system_liner.*, system_class.* 
        FROM system_liner 
        INNER JOIN system_member ON (system_liner.liner_member = system_member.member_id)
        INNER JOIN system_class ON (system_liner.liner_commission_class = system_class.class_id)
        WHERE (liner_member = '$value')");
    $data             = mysqli_fetch_array($sql);
    $point_member     = $data['member_id'];
    $commission_code  = $data['member_code'];
    $member_status    = $data['member_status'];
    $class_match_level= $data['class_match_level'];

    if ($class_swich == 0) {
        $sql_bonus_class  = mysqli_query($connect, "SELECT * FROM system_bonus_class WHERE bonus_class_id = 1 ");
    }
    else {
        $sql_bonus_class  = mysqli_query($connect, "SELECT * FROM system_bonus_class WHERE bonus_class_id = '$count' ");
    }
    
    $data_bonus_class = mysqli_fetch_array($sql_bonus_class);
    $bonus            = ( $point_bonus * $data_bonus_class['bonus_class_point'] ) / 100;
    $point_detail     = "ได้รับค่าคอมมิชชั่นจากการซื้อสินค้าของสมาชิก " . $member_code . " ซึ่งเป็นลำดับที่ " . $count;
    $sum_commission   = ( $sum_bonus * $data_bonus_class['bonus_class_point'] ) / 100;

    // ---- member not block ----
    if ($member_status == 0) {
        
        // ---- give commision to member in class match level ----
        if ($class == 1 && $class_match_level < $count) {
            continue;
        }
        $count++;
        mysqli_query ($connect, "UPDATE system_liner SET liner_point_final = liner_point_final + '$bonus' WHERE liner_id = '$point_member' ");
    }

    if ($class_number < $count) {
        break;
    }
}
*/
/*
for ($i=2; $i <= 5000 ; $i++) { 
    $array = array($i);
    while ($array) {
        
        $array_end  = end($array);
        $sql        = mysqli_query($connect, "SELECT * FROM system_liner WHERE liner_member = '$array_end' ");
        $data       = mysqli_fetch_array($sql);

        if ($data['liner_direct'] != 0) {

            mysqli_query($connect, "UPDATE system_liner SET liner_count = liner_count + 1 WHERE liner_member = '" . $data['liner_direct'] . "' ");
            array_push($array, $data['liner_direct']);
            
        }
        else {
            break;
        }
    }
}
echo "เสร็จสิ้น";
*/
/*
$query = mysqli_query($connect, "SELECT * FROM system_member");
while ($data = mysqli_fetch_array($query)) {
    
    $member_id  = $data['member_id'];

    $query_2    = mysqli_query($connect, "SELECT *, SUM(point_bonus) AS sum_point FROM system_point WHERE (point_type = 29) AND (point_member = '$member_id')");
    $data_2     = mysqli_fetch_array($query_2);
    $sum_point  = $data_2['sum_point'];

    echo $member_id . " bonus = " . $sum_point . "<br>";
    mysqli_query($connect, "UPDATE system_member SET member_point = '$sum_point' WHERE member_id = '$member_id' ");
}

$query = mysqli_query($connect, "SELECT * FROM system_member");
while ($data = mysqli_fetch_array($query)) {
    
    $member_id  = $data['member_id'];

    $query_2    = mysqli_query($connect, "SELECT *, SUM(point_bonus) AS sum_point FROM system_point WHERE (point_type = 0) AND (point_member = '$member_id')");
    $data_2     = mysqli_fetch_array($query_2);
    $sum_point  = $data_2['sum_point'];

    echo $member_id . " bonus = " . $sum_point . "<br>";
    mysqli_query($connect, "UPDATE system_member SET member_point = member_point + '$sum_point' WHERE member_id = '$member_id' ");
}
*/
/*
$query = mysqli_query($connect, "SELECT * FROM system_member");
while ($data = mysqli_fetch_array($query)) {
    
    $member_id   = $data['member_id'];
    $member_class= $data['member_class'];

    if ($data['member_month'] < 4) {
        mysqli_query($connect, "UPDATE system_liner SET liner_commission_class = '$member_class' WHERE liner_member = '$member_id' ");
    }
    else {
        $last_month = date('Y-m', strtotime("-1 month"));
        $query_2    = mysqli_query($connect, "SELECT *, SUM(point_bonus) AS sum_point FROM system_point WHERE (point_type = 29) AND (point_member = '$member_id') AND (point_create LIKE '%$last_month%')");
        $data_2     = mysqli_fetch_array($query_2);
        $sum_point  = $data_2['sum_point'];

        $sql_class  = mysqli_query($connect, "SELECT * FROM system_class WHERE class_up_level <= '$sum_point' ORDER BY class_id DESC");
        $data_class = mysqli_fetch_array($sql_class);
        $class_id   = $data_class['class_id'];
        
        mysqli_query($connect, "UPDATE system_liner SET liner_commission_class = '$class_id' WHERE (liner_member = '$member_id') AND (liner_type = 0)");
        
        echo "member id = " . $member_id . " sumpoint_last_month = " . $sum_point . " class = " . $class_id . "<br>";
    }
}
*/
?>