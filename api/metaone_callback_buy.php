<?php include('../dashboard/process/function.php');

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$data_get   = file_get_contents('php://input');
$data       = json_decode($data_get,true);

file_put_contents('log_notify.txt', 
    date('Y-m-d H:i:s')     . " / " .
    $data_get               . " / " . 
    $data['ref1']           . " / " . 
    $data['one2pay_ref']    . " / " . 
    $data['ref3']);

$payment_resp_code  = $data['resp_code'];
$payment_resp_msg   = $data['resp_msg'];
$payment_command    = $data['command'];
$payment_bank_ref   = $data['bank_ref'];
$payment_tranx_id   = $data['tranx_id'];
$payment_one2pay_ref= $data['one2pay_ref'];
$payment_create     = date("Y-m-d H:i:s", strtotime($data['datetime']));
$payment_effdate    = date("Y-m-d", strtotime($data['effdate']));
$payment_amount     = $data['amount'];
$payment_cusname    = $data['cusname'];
$payment_ref1       = $data['ref1'];
$payment_ref2       = $data['ref2'];
$payment_ref3       = $data['ref3'];

if ($payment_resp_code != '') {

    mysqli_query($connect, "INSERT INTO system_log_payment (
        payment_resp_code,
        payment_resp_msg,
        payment_command,
        payment_bank_ref,
        payment_tranx_id,
        payment_one2pay_ref,
        payment_create,
        payment_effdate,
        payment_amount,
        payment_cusname,
        payment_ref1,
        payment_ref2,
        payment_ref3
        ) 
    VALUES (
        '$payment_resp_code',
        '$payment_resp_msg',
        '$payment_command',
        '$payment_bank_ref',
        '$payment_tranx_id',
        '$payment_one2pay_ref',
        '$payment_create',
        '$payment_effdate',
        '$payment_amount',
        '$payment_cusname',
        '$payment_ref1',
        '$payment_ref2',
        '$payment_ref3'
    )");

}

if($data['resp_code'] == '200'){
    
    echo "Success" . $payment_ref3;
    //mysqli_query($connect, "UPDATE system_order SET order_status = 4 WHERE order_id = '$payment_ref3' ");
    header("Location:../dashboard/process/project/$system_style/process/setting_buy.php?action=confirm_admin&order_id=$payment_ref3");

}

file_put_contents('log_test.txt', date('Y-m-d H:i:s') . " /// code = ". $data['resp_code'] . " // order_id = " . $data['ref3'] . " /// variable order_id = " . $payment_ref3);
?>