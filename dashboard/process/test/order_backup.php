<?php include('../dashboard/process/function.php');

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$method = $_SERVER["REQUEST_METHOD"];
$data   = json_decode(file_get_contents('php://input'), true);

if ($method == 'POST') {

  $order_id = $_POST['bill_ref']; 

  echo json_encode(array('status' => 'Insert Data Complete', 'bill_ref' => $order_id));

  /*
  $query    = mysqli_query($connect, "SELECT * FROM system_order WHERE order_id = '$order_id' ");
  $data     = mysqli_fetch_array($query);


  mysqli_query($connect, "UPDATE system_order SET order_status = 4 WHERE order_id = '$order_id' ");
  
  if ($data['order_member'] != 0) {
    $member_id  = $data['order_member'];
    $point      = $data['order_point']
    mysqli_query($connect, "UPDATE system_order SET member_point_month = member_point_month - '$point' ");
  }
  */
}
?>