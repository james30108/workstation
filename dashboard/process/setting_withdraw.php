<?php include('function.php');

$member_id      = $_GET['member_id'];
$liner_point    = $_GET['liner_point'] >= $report_max && $report_max != 0 ? $report_max : $_GET['liner_point'];
$withdraw_point = report_final ($liner_point, $report_fee1, $report_fee2, $l_bath, $report_max)[2];

// Special withdraw
if ($system_com_withdraw == 2) { 

    header("Location:project/$system_style/process/setting_withdraw.php?action=withdraw&member_id=$member_id&liner_point=$liner_point");
    die;

}

// Default
if ($_GET['action'] == 'withdraw') {
    
    mysqli_query($connect, "INSERT INTO system_withdraw (withdraw_member, withdraw_point, withdraw_fullpoint, withdraw_create) VALUES ('$member_id', '$withdraw_point', '$liner_point', '$date_now')");
    mysqli_query($connect, "UPDATE system_liner SET liner_status = 2, liner_point = liner_point - '$liner_point' WHERE liner_member = '$member_id' ");
    header("location:../member.php?page=member_withdraw&status=success&message=0");

}
elseif  ($_GET['action'] == 'confirm_withdraw') {
    
    $withdraw_id = $_GET['withdraw_id'];
    $member_id   = $_GET['member_id'];
    mysqli_query($connect, "UPDATE system_withdraw  SET withdraw_status = 1 WHERE withdraw_id = '$withdraw_id' ");
    mysqli_query($connect, "UPDATE system_liner     SET liner_status = 1    WHERE liner_member = '$member_id' ");
    header("location:../admin.php?page=admin_withdraw&status=success&message=0");

}
elseif  ($_GET['action'] == 'cancel_withdraw') {
    
    $withdraw_id    = $_GET['withdraw_id'];
    $member_id      = $_GET['member_id'];
    $liner_point    = $_GET['withdraw_fullpoint'];
    mysqli_query($connect, "UPDATE system_withdraw  SET withdraw_status = 2 WHERE withdraw_id = '$withdraw_id' ");
    mysqli_query($connect, "UPDATE system_liner     SET liner_status = 1, liner_point = liner_point + '$liner_point' WHERE liner_member = '$member_id' ");
    header("location:../admin.php?page=admin_withdraw&status=success&message=0");

}


?>