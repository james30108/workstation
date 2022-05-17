<?php include('../../../function.php');

mysqli_query($connect, "UPDATE system_member SET member_point_month = 0, member_month = member_month + 1");
mysqli_query($connect, "UPDATE system_liner  SET liner_count_day = 0, liner_count_month = 0");

?>