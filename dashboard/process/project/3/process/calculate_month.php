<?php include('../../../function.php');
    
$query = mysqli_query($connect, "SELECT * FROM system_member WHERE member_month >= 3");
while ($data = mysqli_fetch_array($query)) {

    $member_id   = $data['member_id'];
    $point_month = $data['member_point_month'];
    $query2      = mysqli_query($connect, "SELECT * FROM system_class WHERE class_up_level <= '$point_month' ORDER BY class_id DESC");
    $data2       = mysqli_fetch_array($query2);
    $class_id    = $data2['class_id'];

    mysqli_query($connect, "UPDATE system_liner SET liner_etc = '$class_id' WHERE liner_member = '$member_id' ");

    // update status point per month
    if ($class_id > 1) {
        mysqli_query($connect, "UPDATE system_liner SET liner_status = 1 WHERE liner_member = '$member_id' ");
    }
    elseif ($class_id == 1) {
        mysqli_query($connect, "UPDATE system_liner SET liner_status = 0 WHERE liner_member = '$member_id' ");
    }

}
mysqli_query($connect, "UPDATE system_member SET member_point_month = 0, member_month = member_month + 1");
mysqli_query($connect, "UPDATE system_point  SET point_status = 1");
mysqli_query($connect, "UPDATE system_liner  SET liner_count_day = 0, liner_count_month = 0, liner_point = 0");

// liner VIP
mysqli_query($connect, "UPDATE system_liner  SET liner_status = 1, liner_etc = 6 WHERE liner_type = 1");

?>