<?php include('../dashboard/process/function.php');

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data_get   = file_get_contents('php://input');
$data       = json_decode($data_get,true);

file_put_contents('log_withdraw.txt', date('Y-m-d H:i:s') . " / " . $data_get);

$logwd_status               = isset($data['status'])                ? $data['status']               : false;
$logwd_message              = isset($data['message'])               ? $data['message']              : false;
$logwd_payout_ref           = isset($data['payout_ref'])            ? $data['payout_ref']           : false;
$logwd_transaction_id       = isset($data['transaction_id'])        ? $data['transaction_id']       : false;
$logwd_transactionDate_time = isset($data['transactionDate_time'])  ? $data['transactionDate_time'] : false;
$logwd_qrstring             = isset($data['qrstring'])              ? $data['qrstring']             : false;

mysqli_query($connect, "INSERT INTO system_log_withdraw (
    logwd_status,
    logwd_message,
    logwd_payout_ref,
    logwd_transaction_id,
    logwd_transactionDate_time,
    logwd_qrstring
    ) 
VALUES (
    '$logwd_status',
    '$logwd_message',
    '$logwd_payout_ref',
    '$logwd_transaction_id',
    '$logwd_transactionDate_time',
    '$logwd_qrstring'
)");

if($data['status'] == '1000'){
    
    echo "Success" . $logwd_message;
    //mysqli_query($connect, "UPDATE system_order SET order_status = 4 WHERE order_id = '$payment_ref3' ");

}
?>