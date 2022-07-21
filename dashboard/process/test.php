<?php 

$connect = mysqli_connect("localhost","bertvkxi_admin","yrR0BnuI6hPM","bertvkxi_lottery") or die ("เชื่อมต่อฐานข้อมูลไม่ได้");
mysqli_query($connect, "SET NAMES UTF8");


mysqli_query($connect, "UPDATE system_liner SET liner_point = liner_point + 1500 ");
?>