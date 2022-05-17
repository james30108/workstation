<?php include('function.php');

if (!file_exists("../assets/images/slips/")) { mkdir("../assets/images/slips"); }

if ($_POST['action'] == 'insert') {

    $pay_title  = $_POST['pay_title'];
    $pay_bank   = $_POST['pay_bank'];
    $pay_money  = $_POST['pay_money'];
    $pay_date   = $_POST['pay_date'];
    $pay_time   = $_POST['pay_time'];
    $pay_detail = $_POST['pay_detail'];
    $pay_member = $_POST['pay_member'];
    $pay_order  = $_POST['pay_order'];

    if ($_FILES['pay_image']['name'] != '') {
        $type       = strrchr($_FILES['pay_image']['name'],".");    //เอาชื่อไฟล์เก่าออกให้เหลือแต่นามสกุล
        $pay_image  = "slip_" .  date('YmdHis') . $type;             //ตั้งชื่อไฟล์ใหม่โดยเอาเวลาไว้หน้าชื่อไฟล์เดิม
        move_uploaded_file($_FILES['pay_image']['tmp_name'], "../assets/images/slips/" . $pay_image);
    }
    else {
        $pay_image  = "";
    }
    
    mysqli_query($connect, "INSERT INTO system_pay_refer (
        pay_title,      pay_bank,         pay_money,        pay_date,       pay_time, 
        pay_image,      pay_detail,       pay_member,       pay_order,      pay_type
    )
    VALUES (
        '$pay_title',   '$pay_bank',      '$pay_money',     '$pay_date',    '$pay_time', 
        '$pay_image',   '$pay_detail',    '$pay_member',    '$pay_order',   1
    )");
    
    header('location:../member.php?page=member_report_pay&status=success&message=0');
    
}
elseif ($_GET['action'] == 'confirm_pay_refer') {

    $pay_id = $_GET['pay_id'];
    mysqli_query($connect, "UPDATE system_pay_refer SET pay_status = 1 WHERE pay_id = '$pay_id' ");

    if (isset($_GET['page']) && $_GET['page'] == 'pay_refer_detail') {
        header("location:../admin.php?page=admin_pay&action=pay_refer_detail&pay_id=$pay_id");
    }
    else {
        header("location:../admin.php?page=admin_pay&status=success&message=0");
    }

}
elseif ($_GET['action'] == 'cancel_pay_refer') {

    $pay_id = $_GET['pay_id'];
    mysqli_query($connect, "UPDATE system_pay_refer SET pay_status = 2 WHERE pay_id = '$pay_id' ");

    if (isset($_GET['page']) && $_GET['page'] == 'pay_refer_detail') {
        header("location:../admin.php?page=admin_pay&action=pay_refer_detail&pay_id=$pay_id");
    }
    else {
        header("location:../admin.php?page=admin_pay&status=success&message=0");
    }

}
?>