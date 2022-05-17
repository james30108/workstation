<?php include('function.php'); 

if (!file_exists("../assets/images/ewallet_slipe/")) {
    mkdir("../assets/images/ewallet_slipe/");
}

if ($_POST['action'] == 'insert') {

    $deposit_money  = $_POST['deposit_money'];
    $deposit_bank   = isset($_POST['deposit_bank'])  ? $_POST['deposit_bank']   : false;
    $deposit_date   = isset($_POST['deposit_date'])  ? $_POST['deposit_date']   : false;
    $deposit_time   = isset($_POST['deposit_time'])  ? $_POST['deposit_time']   : false;
    $deposit_member = $_POST['deposit_member'];
    $page           = $_POST['page'];
    $deposit_status = $_POST['page'] == 'admin.php'  ? 3 : 0;
    $deposit_detail = $_POST['page'] == 'member.php' ? $_POST['deposit_detail'] : "Topup By Admin";

    if (!empty($_FILES['deposit_slip']['name'])) {
        
        $type           = strrchr($_FILES['deposit_slip']['name'],".");    //เอาชื่อไฟล์เก่าออกให้เหลือแต่นามสกุล
        $deposit_slip   = "ewallet_" .  date('YmdHis') . $type;    //ตั้งชื่อไฟล์ใหม่โดยเอาเวลาไว้หน้าชื่อไฟล์เดิม
        move_uploaded_file($_FILES['deposit_slip']['tmp_name'], "../assets/images/ewallet_slipe/" . $deposit_slip);
    
    }
    else {
        $deposit_slip = "";
    }

    mysqli_query($connect, "INSERT INTO system_deposit 
        (deposit_member,    deposit_bank, 
        deposit_money,      deposit_date, 
        deposit_time,       deposit_slip, 
        deposit_detail,     deposit_status) 
    VALUES (
        '$deposit_member',  '$deposit_bank', 
        '$deposit_money',   '$deposit_date',
        '$deposit_time',    '$deposit_slip',
        '$deposit_detail',  '$deposit_status')");

    if ($page == 'admin.php') {

        mysqli_query($connect, "UPDATE system_member SET member_ewallet = member_ewallet + '$deposit_money' WHERE member_id = '$deposit_member' ");
    
    }

    header("location:../$page?page=ewallet&status=success&message=0");

}
elseif ($_POST['action'] == 'tranfer') {
    
    $tranfer_member = $_POST['tranfer_member'];
    $tranfer_money  = $_POST['tranfer_money'];
    $tranfer_to     = $_POST['tranfer_to'];
        
    mysqli_query($connect, "INSERT INTO system_tranfer (
        tranfer_member,  
        tranfer_to, 
        tranfer_money)
    VALUES (
        '$tranfer_member', 
        '$tranfer_to', 
        '$tranfer_money')");

    mysqli_query($connect, " UPDATE system_member SET member_ewallet = member_ewallet + '$tranfer_money' WHERE member_id = '$tranfer_to' ");

    mysqli_query($connect, " UPDATE system_member SET member_ewallet = member_ewallet - '$tranfer_money' WHERE member_id = '$tranfer_member' ");
    
    header("location:../member.php?page=member_ewallet&action=ewalet_tranfer&status=tranfer_success");

}
elseif ($_GET['action'] == 'confirm_deposit') {

    $deposit_id = $_GET['deposit_id'];
    $query      = mysqli_query($connect, "SELECT * FROM system_deposit WHERE deposit_id = '$deposit_id' ");
    $data       = mysqli_fetch_array($query);

    $member_id      = $data['deposit_member'];
    $member_ewallet = $data['deposit_money'];


    mysqli_query($connect, "UPDATE system_deposit SET deposit_status = 1 WHERE deposit_id = '$deposit_id' ");
    mysqli_query($connect, "UPDATE system_member  SET member_ewallet = member_ewallet + '$member_ewallet' WHERE member_id = '$member_id' ");

    header("location:../admin.php?page=ewallet&status=success&message=0");

}
elseif ($_GET['action'] == 'cancel_deposit') {

    $deposit_id = $_GET['deposit_id'];

    mysqli_query($connect, "UPDATE system_deposit SET deposit_status = 2 WHERE deposit_id = '$deposit_id' ");
    header("location:../admin.php?page=ewallet&status=success&message=0");
    
}
?>