<?php include('../../../function.php');
set_time_limit(0);

// Commission Cut
$report_round     = report_now ($connect, 0);
commission_cut ($connect, $report_round, $point_type, $yesterday, $report_min);

// Reset Data to new Month
mysqli_query($connect, "UPDATE system_member SET member_point_month = 0, member_month = member_month + 1");
mysqli_query($connect, "UPDATE system_point  SET point_status = 1");
?>