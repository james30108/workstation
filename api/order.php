<?php include('../dashboard/process/function.php');

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//$method = $_SERVER["REQUEST_METHOD"];
$data   = json_decode(file_get_contents('php://input'), true);

if ($data['bill_ref'] != '') {
  
  $order_id = $data['bill_ref'];
  
  mysqli_query($connect, "INSERT INTO system_log (log_type, log_number) VALUES (1, '$order_id')");
  mysqli_query($connect, "UPDATE system_order SET order_status = 2 WHERE order_id = '$order_id' ");
  mysqli_query($connect, "UPDATE system_pay_refer SET pay_status = 2 WHERE pay_order = '$order_id' ");
  mysqli_query($connect, "DELETE FROM system_point WHERE point_order = '$order_id' ");

  $query = mysqli_query($connect, "SELECT * FROM system_order WHERE order_id = '$order_id' ");
  $data  = mysqli_fetch_array($query);

  $order_create = date('Y-m',strtotime($data['order_create']));
  $order_member = $data['order_member'];
  $order_point  = $data['order_point'];
  $order_price  = $data['order_price'];

  mysqli_query($connect, "UPDATE system_member SET member_point = member_point - '$order_point' WHERE member_id = '$order_member' ");

  $query = mysqli_query($connect, "SELECT * FROM system_order WHERE (order_member = '$order_member') AND (order_create LIKE '%$order_create%'); ");
  $count = mysqli_num_rows($query);

  if ($order_create < $month_now) {
    
    if ($count == 1) {
      $report_count = 1;

      mysqli_query($connect, "DELETE system_report_detail 
        FROM system_report_detail
        INNER JOIN system_report ON (system_report.report_id = system_report_detail.report_detail_main)
        WHERE (report_detail_link = '$order_member') AND (report_create LIKE '%$order_create%') AND (report_type = 0) ");
    }
    else {
      $report_count = 0;

      mysqli_query($connect, "UPDATE system_report_detail 
        INNER JOIN system_report ON (system_report.report_id = system_report_detail.report_detail_main)
        SET report_detail_point = report_detail_point - '$order_point'
        WHERE (report_detail_link = '$order_member') AND (report_create LIKE '%$order_create%') AND (report_type = 0)");
    }

    mysqli_query($connect, "DELETE system_report_detail 
        FROM system_report_detail
        INNER JOIN system_report ON (system_report.report_id = system_report_detail.report_detail_main)
        WHERE (report_detail_link = '$order_id') AND (report_create LIKE '%$order_create%') AND (report_type = 1) ");
    
    mysqli_query($connect, "UPDATE system_report SET 
      report_point = report_point - '$order_point',  
      report_count = report_count - '$report_count'
      WHERE (report_type = 0) AND (report_create LIKE '%$order_create%') ");

    mysqli_query($connect, "UPDATE system_report SET 
      report_point = report_point - '$order_price',  
      report_count = report_count - '$report_count' 
      WHERE (report_type = 1) AND (report_create LIKE '%$order_create%') ");
  }
  else {
    mysqli_query($connect, "UPDATE system_member SET member_point_month = member_point_month - '$order_point' WHERE member_id = '$order_member' ");
  }

  echo json_encode(array('status' => 'Insert Data Complete', 'bill_ref' => $order_id));

} 
else {
  echo "Error";
}
?>