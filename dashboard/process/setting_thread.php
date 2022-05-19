<?php include('function.php'); 

if (!file_exists("../assets/images/thread/")) { mkdir("../assets/images/thread/"); }

if ($_POST['action'] == 'insert_thread') {

    $thread_title     = $_POST['thread_title'];
    $thread_detail    = $_POST['thread_detail'];
    $thread_type      = $_POST['thread_type'];

    $type           = strrchr($_FILES['thread_image']['name'],".");     //เอาชื่อไฟล์เก่าออกให้เหลือแต่นามสกุล
    $thread_image     = "thread_" .  date('YmdHis') . $type;            //ตั้งชื่อไฟล์ใหม่โดยเอาเวลาไว้หน้าชื่อไฟล์เดิม
    move_uploaded_file($_FILES['thread_image']['tmp_name'],"../assets/images/thread/$thread_image");

    mysqli_query($connect, "INSERT INTO system_thread (thread_title, thread_type, thread_detail, thread_image) VALUES ('$thread_title', '$thread_type', '$thread_detail', '$thread_image')");
    mysqli_query($connect, "UPDATE system_thread_type SET thread_type_count = thread_type_count + 1 WHERE thread_type_id = '$thread_type' ");
   
    header("location:../admin.php?page=admin_thread&status=success&message=0");

}
elseif ($_POST['action'] == 'edit_thread') {

    $thread_id        = $_POST['thread_id'];  
    $thread_title     = $_POST['thread_title'];
    $thread_detail    = $_POST['thread_detail'];
    $thread_type      = $_POST['thread_type'];
    
    if ($_FILES['thread_image_new']['name'] != '') {

        unlink ("../assets/images/thread/" . $_POST['thread_image']);
        $type       = strrchr($_FILES['thread_image_new']['name'],".");       //เอาชื่อไฟล์เก่าออกให้เหลือแต่นามสกุล
        $thread_image = "thread_" .  date('YmdHis') . $type;                  //ตั้งชื่อไฟล์ใหม่โดยเอาเวลาไว้หน้าชื่อไฟล์เดิม
        move_uploaded_file($_FILES['thread_image_new']['tmp_name'],"../assets/images/thread/$thread_image");
    }
    else {
        $thread_image   = $_POST['thread_image'];
    }
    mysqli_query($connect, "UPDATE system_thread SET thread_title = '$thread_title', thread_type = '$thread_type', thread_detail = '$thread_detail', thread_image = '$thread_image' WHERE thread_id = '$thread_id' ");
    
    header("location:../admin.php?page=admin_thread&status=success&message=0");

}
elseif ($_GET['action'] == 'delete_thread') {

    $thread_id = $_GET['thread_id'];  
    unlink ("../assets/images/thread/" . $_GET['thread_image']);
    mysqli_query($connect, "DELETE FROM system_thread WHERE thread_id = '$thread_id' ");
    header("location:../admin.php?page=admin_thread&status=success&message=0");

}
elseif ($_POST['action'] == 'insert_thread_type') {
    
    $thread_type_name = $_POST['thread_type_name'];
    mysqli_query($connect, "INSERT INTO system_thread_type (thread_type_name) VALUES ('$thread_type_name')");
    header("location:../admin.php?page=admin_thread_type&status=success&meassage=0");

}
elseif ($_POST['action'] == 'edit_thread_type') {
    
    $thread_type_id     = $_POST['thread_type_id'];
    $thread_type_name   = $_POST['thread_type_name'];
    mysqli_query($connect, "UPDATE system_thread_type SET thread_type_name = '$thread_type_name' WHERE thread_type_id = '$thread_type_id' ");
    header("location:../admin.php?page=admin_thread_type&status=success&message=0");

}
elseif ($_GET['action'] == 'delete_thread_type') {
    
    $thread_type_id  = $_GET['thread_type_id'];
    $query           = mysqli_query($connect, "SELECT * FROM system_thread WHERE thread_type = '$thread_type_id'");
	while($data = mysqli_fetch_array($query)){
		@unlink("../assets/images/thread/" . $data['thread_image']);
	}

	mysqli_query($connect, "DELETE FROM system_thread WHERE thread_type = '$thread_type_id'");
	mysqli_query($connect, "DELETE FROM system_thread_type WHERE thread_type_id = '$thread_type_id'");

    header("location:../admin.php?page=admin_thread_type&status=success&message=0");

}
?>