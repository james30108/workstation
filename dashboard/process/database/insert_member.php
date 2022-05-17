<?php 
include('../function.php');

mysqli_query($connect, "TRUNCATE TABLE system_liner");
mysqli_query($connect, "TRUNCATE TABLE system_member");

mysqli_query($connect, "INSERT INTO system_member (member_name, member_code) VALUES ('member', '0000001')");
mysqli_query($connect, "INSERT INTO system_member (member_name, member_code) VALUES ('member_2', '0000002')");
mysqli_query($connect, "INSERT INTO system_member (member_name, member_code) VALUES ('member_3', '0000003')");
mysqli_query($connect, "INSERT INTO system_member (member_name, member_code) VALUES ('member_4', '0000004')");
mysqli_query($connect, "INSERT INTO system_member (member_name, member_code) VALUES ('member_5', '0000005')");
mysqli_query($connect, "INSERT INTO system_member (member_name, member_code) VALUES ('member_6', '0000006')");
mysqli_query($connect, "INSERT INTO system_member (member_name, member_code) VALUES ('member_7', '0000007')");
mysqli_query($connect, "INSERT INTO system_member (member_name, member_code) VALUES ('member_8', '0000008')");
mysqli_query($connect, "INSERT INTO system_member (member_name, member_code) VALUES ('member_9', '0000009')");
mysqli_query($connect, "INSERT INTO system_member (member_name, member_code) VALUES ('member_10', '0000010')");
mysqli_query($connect, "INSERT INTO system_member (member_name, member_code) VALUES ('member_11', '0000011')");
mysqli_query($connect, "INSERT INTO system_member (member_name, member_code) VALUES ('member_12', '0000012')");
mysqli_query($connect, "INSERT INTO system_member (member_name, member_code) VALUES ('member_13', '0000013')");
mysqli_query($connect, "INSERT INTO system_member (member_name, member_code) VALUES ('member_14', '0000014')");
mysqli_query($connect, "INSERT INTO system_member (member_name, member_code) VALUES ('member_15', '0000015')");
mysqli_query($connect, "INSERT INTO system_member (member_name, member_code) VALUES ('member_16', '0000016')");
mysqli_query($connect, "INSERT INTO system_member (member_name, member_code) VALUES ('member_17', '0000017')");
mysqli_query($connect, "INSERT INTO system_member (member_name, member_code) VALUES ('member_18', '0000018')");
mysqli_query($connect, "INSERT INTO system_member (member_name, member_code) VALUES ('member_19', '0000019')");
mysqli_query($connect, "INSERT INTO system_member (member_name, member_code) VALUES ('member_20', '0000020')");
mysqli_query($connect, "INSERT INTO system_member (member_name, member_code) VALUES ('member_21', '0000021')");
mysqli_query($connect, "INSERT INTO system_member (member_name, member_code) VALUES ('member_22', '0000022')");
mysqli_query($connect, "INSERT INTO system_member (member_name, member_code) VALUES ('member_23', '0000023')");
mysqli_query($connect, "INSERT INTO system_member (member_name, member_code) VALUES ('member_24', '0000024')");
mysqli_query($connect, "INSERT INTO system_member (member_name, member_code) VALUES ('member_25', '0000025')");
mysqli_query($connect, "INSERT INTO system_member (member_name, member_code) VALUES ('member_26', '0000026')");
mysqli_query($connect, "INSERT INTO system_member (member_name, member_code) VALUES ('member_27', '0000027')");
mysqli_query($connect, "INSERT INTO system_member (member_name, member_code) VALUES ('member_28', '0000028')");
mysqli_query($connect, "INSERT INTO system_member (member_name, member_code) VALUES ('member_29', '0000029')");

mysqli_query($connect, "INSERT INTO system_liner (liner_member, liner_direct) VALUES (1, 0)");
mysqli_query($connect, "INSERT INTO system_liner (liner_member, liner_direct) VALUES (2, 1)");
mysqli_query($connect, "INSERT INTO system_liner (liner_member, liner_direct) VALUES (3, 2)");
mysqli_query($connect, "INSERT INTO system_liner (liner_member, liner_direct) VALUES (4, 3)");
mysqli_query($connect, "INSERT INTO system_liner (liner_member, liner_direct) VALUES (5, 4)");
mysqli_query($connect, "INSERT INTO system_liner (liner_member, liner_direct) VALUES (6, 5)");
mysqli_query($connect, "INSERT INTO system_liner (liner_member, liner_direct) VALUES (7, 6)");
mysqli_query($connect, "INSERT INTO system_liner (liner_member, liner_direct) VALUES (8, 7)");
mysqli_query($connect, "INSERT INTO system_liner (liner_member, liner_direct) VALUES (9, 1)");
mysqli_query($connect, "INSERT INTO system_liner (liner_member, liner_direct) VALUES (10, 1)");
mysqli_query($connect, "INSERT INTO system_liner (liner_member, liner_direct) VALUES (11, 1)");
mysqli_query($connect, "INSERT INTO system_liner (liner_member, liner_direct) VALUES (12, 1)");
mysqli_query($connect, "INSERT INTO system_liner (liner_member, liner_direct) VALUES (13, 1)");
mysqli_query($connect, "INSERT INTO system_liner (liner_member, liner_direct) VALUES (14, 1)");
mysqli_query($connect, "INSERT INTO system_liner (liner_member, liner_direct) VALUES (15, 1)");
mysqli_query($connect, "INSERT INTO system_liner (liner_member, liner_direct) VALUES (16, 1)");
mysqli_query($connect, "INSERT INTO system_liner (liner_member, liner_direct) VALUES (17, 1)");
mysqli_query($connect, "INSERT INTO system_liner (liner_member, liner_direct) VALUES (18, 1)");
mysqli_query($connect, "INSERT INTO system_liner (liner_member, liner_direct) VALUES (19, 1)");
mysqli_query($connect, "INSERT INTO system_liner (liner_member, liner_direct) VALUES (20, 1)");
mysqli_query($connect, "INSERT INTO system_liner (liner_member, liner_direct) VALUES (21, 1)");
mysqli_query($connect, "INSERT INTO system_liner (liner_member, liner_direct) VALUES (22, 1)");
mysqli_query($connect, "INSERT INTO system_liner (liner_member, liner_direct) VALUES (23, 1)");
mysqli_query($connect, "INSERT INTO system_liner (liner_member, liner_direct) VALUES (24, 1)");
mysqli_query($connect, "INSERT INTO system_liner (liner_member, liner_direct) VALUES (25, 1)");
mysqli_query($connect, "INSERT INTO system_liner (liner_member, liner_direct) VALUES (26, 1)");
mysqli_query($connect, "INSERT INTO system_liner (liner_member, liner_direct) VALUES (27, 1)");
mysqli_query($connect, "INSERT INTO system_liner (liner_member, liner_direct) VALUES (28, 1)");
mysqli_query($connect, "INSERT INTO system_liner (liner_member, liner_direct) VALUES (29, 1)");

mysqli_query($connect, "UPDATE system_member SET member_pass = 1234, member_user = member_code, member_class = 5");
mysqli_query($connect, "UPDATE system_liner  SET liner_status = 1, liner_etc = 5");


?>