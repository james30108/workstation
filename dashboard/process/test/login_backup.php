<?php include('../dashboard/process/function.php');
/*
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$method = $_SERVER["REQUEST_METHOD"];
$data   = json_decode(file_get_contents('php://input'), true);

if ($method == 'POST') {

  $member_id    = $data['member_id'];
  $member_name  = $data['name'];
  $member_tel   = $data['tel'];
  $member_email = $data['email'];  
  
  $query = mysqli_query($connect, "SELECT * FROM system_member WHERE member_id = '$member_id' ");
  $data  = mysqli_fetch_array($query);
  if (!isset($data)) {
    mysqli_query($connect, "INSERT INTO system_member (member_id, member_name, member_tel, member_email) VALUES ('$member_id', '$member_name', '$member_tel', '$member_email') ");
    header('location:../dashboard/member.php');
  }
  else {
    header('location:../dashboard/member.php');
  }
}
*/


if ($_GET['member_id'] != '') {
  $member_id    = $_GET['member_id'];
  $member_title = $_GET['title_name'];
  $member_name  = $_GET['name'];
  $member_tel   = $_GET['tel'];
  
  $query = mysqli_query($connect, "SELECT * FROM system_member WHERE member_id = '$member_id' ");
  $data  = mysqli_fetch_array($query);
  if (!isset($data)) {
    mysqli_query($connect, "INSERT INTO system_member (member_id, member_name, member_tel) VALUES ('$member_id', '$member_name', '$member_tel') ");

    echo json_encode(array(
      'status'      => 'Login Complete', 
      'member_id'   => $member_id, 
      'member_title'=> $member_title, 
      'member_name' => $member_name, 
      'member_tel'  => $member_tel));


    
    /*
    //header('location:../dashboard/member.php');
    $status   = http_response_code();
    if ($status == 200) {
      echo json_encode(['status' => 'success', 'status_code' => $status, 'message' => 'Insert Data Complete', 'data' => $data], JSON_UNESCAPED_UNICODE);
    } else {
      echo json_encode(['status' => 'error', 'message' => 'Not found'], JSON_UNESCAPED_UNICODE);    
    }
  }
  else {
    //header('location:../dashboard/member.php');
    $status   = http_response_code();
    if ($status == 200) {
      echo json_encode(['status' => 'success', 'status_code' => $status, 'message' => 'Insert Data Complete', 'data' => $data], JSON_UNESCAPED_UNICODE);
    } else {
      echo json_encode(['status' => 'error', 'message' => 'Not found'], JSON_UNESCAPED_UNICODE);    
    }
  }
  */
}

?>