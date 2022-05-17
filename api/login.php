<?php include('../dashboard/process/function.php');

if ($_GET['member_id'] != '') {
  
  $member_code  = iconv('TIS-620', 'UTF-8', $_GET['member_id']);
  $member_title = iconv('TIS-620', 'UTF-8', $_GET['title_name']);
  $member_name  = iconv('TIS-620', 'UTF-8', $_GET['name']);
  $member_tel   = iconv('TIS-620', 'UTF-8', $_GET['tel']);
  
  $query = mysqli_query($connect, "SELECT * FROM system_member WHERE member_code = '$member_code' ");
  $data  = mysqli_fetch_array($query);
  
  if (!isset($data)) {

    mysqli_query($connect, "INSERT INTO system_log (log_number, log_data1, log_data2, log_data3) VALUES ('$member_code', '$member_title', '$member_name', '$member_tel')");

    mysqli_query($connect, "INSERT INTO system_member (member_code, member_title_name, member_name, member_tel) VALUES ('$member_code', '$member_title', '$member_name', '$member_tel') ");
    $member_id = $connect -> insert_id;
    
    
    $_SESSION['member_id'] = $member_id;
  }
  else {
    
    $_SESSION['member_id'] = $data['member_id'];
  }

  echo json_encode(array(
    'status'      => 'Login Complete', 
    'member_id'   => $member_code, 
    'title_name'  => $member_title, 
    'name'        => $member_name, 
    'tel'         => $member_tel));

  header("location:../dashboard/member.php");
}
?>