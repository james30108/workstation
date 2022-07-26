<?php include('function.php');


$query  = mysqli_query($connect, "SELECT * FROM system_class WHERE class_up_level <= '3300' ORDER BY class_id DESC");
$class  = mysqli_fetch_array($query);
$class  = $class['class_id'];

mysqli_query($connect, "UPDATE system_member SET member_class = '$class' WHERE member_id = 47 ");
?>