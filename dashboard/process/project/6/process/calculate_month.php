<?php include('function.php');

/*

ตัดยอดรายเดือน

1. รีเซ็ตตัวนับสมาชิกรายเดือน

*/

// Reset Data to new Month
mysqli_query($connect, "UPDATE system_member SET member_month = member_month + 1");
mysqli_query($connect, "UPDATE system_liner  SET liner_count_month = 0");

?>