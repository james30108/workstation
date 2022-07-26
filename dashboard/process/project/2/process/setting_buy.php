<?php include('../../../function.php');

if (!file_exists("../../../../assets/images/slips/")) { mkdir("../../../../assets/images/slips"); }

/*
member_point = เก็บคะแนนรักษายอดทั้งหมด ใช้ปรับตำแหน่ง
member_point_month = โปรเจกนี้ไว้เก็บค่าคอมที่ใช้แสดงประจำเดือน
*/

if ($_GET['action'] == 'confirm_admin') {
    
    $order_id       = $_GET['order_id'];
    $query          = mysqli_query($connect, "SELECT system_member.*, system_liner.*, system_order.* 
        FROM system_member 
        INNER JOIN system_order ON (system_member.member_id = system_order.order_member)
        INNER JOIN system_liner ON (system_member.member_id = system_liner.liner_member)
        WHERE order_id = '$order_id'");
    $data           = mysqli_fetch_array($query);

    // default data
    $member_id      = $data['order_member'];
    $liner_id       = $data['liner_id'];
    $liner_direct   = $data['liner_direct'];
    $liner_status   = $data['liner_status'];
    $point_bonus    = $data['order_point'];
    $point_detail   = "Buy Package";
    $member_code    = $data['member_code'];
    $sum_bonus      = $data['member_point_month'];
    $member_month   = $data['member_month'];

    if ($data['order_status'] != 0) { die; }
    
    mysqli_query($connect, "UPDATE system_order     SET order_status = 4    WHERE order_id  = '$order_id'");
    mysqli_query($connect, "UPDATE system_pay_refer SET pay_status   = 1    WHERE pay_order = '$order_id'");
    
    if ($liner_status == 0) {
        mysqli_query($connect, "UPDATE system_liner SET 
            liner_status    = 1,   
            liner_create    = '$today'
        WHERE liner_id  = '$liner_id'");
    }
    
    mysqli_query($connect, "UPDATE system_member SET member_point = member_point + '$point_bonus' WHERE member_id = '$member_id' ");
    mysqli_query($connect, "INSERT INTO system_point (point_member, point_bonus, point_detail) VALUES ('$member_id', '$point_bonus', '$point_detail')");

    // Update Class
    $query              = mysqli_query($connect, "SELECT * FROM system_member WHERE member_id = '$member_id' ");
    $data               = mysqli_fetch_array($query);
    $member_point       = $data['member_point'];
    $member_class       = $data['member_class'];

    $query  = mysqli_query($connect, "SELECT * FROM system_class WHERE class_up_level <= '$member_point' ORDER BY class_id DESC");
    $class  = mysqli_fetch_array($query);
    $class  = $class['class_id'];

    mysqli_query($connect, "UPDATE system_member SET member_class = '$class' WHERE member_id = '$member_id' ");
    /*
    if ($member_class < $class) {
        mysqli_query($connect, "UPDATE system_member SET member_class = '$class' WHERE member_id = '$member_id' ");
    }
    */
    
    // commisison to liner
    $query            = mysqli_query($connect, "SELECT * FROM system_liner WHERE (liner_id = '$liner_direct')");
    $data             = mysqli_fetch_array($query);
    $point_member     = $data['liner_member'];
    $bonus            = ( $point_bonus * $bonus_class ) / 100;
    $point_detail     = "Get Referal bonus from $member_code";

    mysqli_query($connect, "INSERT INTO system_point (
        point_member,      
        point_type,     
        point_bonus,  
        point_detail,
        point_order
    ) 
    VALUES (
        '$point_member',   
        1,              
        '$bonus',     
        '$point_detail',
        '$order_id'
    )");

    mysqli_query ($connect, "UPDATE system_liner  SET liner_point = liner_point + '$bonus' WHERE liner_member = '$point_member' ");
    mysqli_query ($connect, "UPDATE system_member SET member_point_month = member_point_month + '$bonus' WHERE member_id = '$point_member' ");

    // หาก VIP ซื้อสินค้าก็จะเปลี่ยนจาก VIP เป็นรหัสธรรมดาทันที
    mysqli_query ($connect, "UPDATE system_liner SET liner_type = 0 WHERE liner_member = '$member_id' ");

    header('location:../../../../admin.php?page=order&status=success&message=0');

}

?>