<?php include('function.php'); 

if ($_POST['action'] == 'insert') {

    $contact_buyer  = isset($_POST['contact_buyer']) ? $_POST['contact_buyer'] : 0;
    $contact_member = isset($_POST['contact_member'])? $_POST['contact_member'] : 0;
    $contact_name   = $_POST['contact_name'];
    $contact_email  = $_POST['contact_email'];
    $contact_title  = $_POST['contact_title'];
    $contact_detail = $_POST['contact_detail'];
    $contact_type   = $_POST['contact_type'];

    if ($contact_buyer == 0 && $contact_member == 0 && $contact_type == 2) { die(); }

    if ($contact_type == 0) {
        $url = "Location:../member.php?page=contact&status=success&message=0";
    }
    elseif ($contact_type == 1) {
        $url = "Location:../admin.php?page=contact&status=success&message=0";
    }
    elseif ($contact_type == 2) {
        $url = "Location:../../?page=confirmation&status=contact";
    }
    
    mysqli_query($connect, "INSERT INTO system_contact (
        contact_title, 
        contact_member,
        contact_buyer, 
        contact_name, 
        contact_email, 
        contact_detail, 
        contact_type) 
    VALUES (
        '$contact_title',
        '$contact_member',
        '$contact_buyer',
        '$contact_name', 
        '$contact_email', 
        '$contact_detail', 
        '$contact_type')");
    header($url);
}
elseif ($_POST['action'] == 'read') {
    
    $contact_array = explode(",", $_POST['contact_array']);
    foreach ($contact_array AS $contact_id) {
        mysqli_query($connect, "UPDATE system_contact SET contact_status = 1 WHERE contact_id = '$contact_id' ");
    }
    header("location:../" . $_POST['page'] . "?page=contact&status=success&message=0");
}
elseif ($_POST['action'] == 'delete') {
    
    $array = explode(",", $_POST['contact_array']);
    foreach ($array AS $contact_id) {
        mysqli_query($connect, "DELETE FROM system_contact WHERE contact_id = '$contact_id' ");
    }

    header("location:../" . $_POST['page'] . "?page=contact&status=success&message=0");
}
?>