<?php include('../../../function.php');

if ($_GET['action'] == 'withdraw') {
    
    $member_id      = $_GET['member_id'];
    $liner_point    = (double) $_GET['liner_point'];
    $commission     = (double) report_final ($liner_point, $report_fee1, $report_fee2, $l_bath, $report_max)[2];
    //$commission     = (double) 1.03;

    $query      = mysqli_query($connect, "SELECT system_member.*, system_bank.* 
        FROM system_member 
        INNER JOIN system_bank ON (system_member.member_bank_name = system_bank.bank_id)
        WHERE member_id = '$member_id' ");
    $data       = mysqli_fetch_array($query);

    $bankacc        = $data['member_bank_id'];
    $bankcode       = $data['bank_code'];
    $bankname       = $data['bank_name_th'];
    $accname        = $data['member_bank_own'];
    $mobileno       = $data['member_tel'];
    $transaction_by = $company;

    // API
    //Use Authorization and partner_code from your account
    $authorization  = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJwYXJ0bmVyX2NvZGUiOiJBUFciLCJ1dWlkIjoiZjdiMDQ1NjUtZDcwZS00NjkxLTg0OWMtNjVhNGM5YThmMjMzIn0.lwPrGBqTSWYmQLesoIStkua5iftBA4jjiAXMgTW994w";
    $partner_code   = "APW";

    // API URL
    $url            = "https://payout.1-2-pay.com/payout";   
    $ref1           = $member_id . date('YmdHis');
    $channel_code   = "WEB";

    // Setup request to send json via POST
    $data = array(
        'bankacc'       => $bankacc,
        'bankcode'      => $bankcode,
        'bankname'      => $bankname,
        'accname'       => $accname,
        'amount'        => $commission,
        'mobileno'      => $mobileno,
        'transaction_by'=> $transaction_by,
        'ref1'          => $ref1
    );

    print_r($data);

    $payload = json_encode($data);

    $option = array(
        'Content-Type:application/json',
        'Content-Length: '  .strlen($payload),
        'Authorization:'    .$authorization,
        'Partnercode:'      .$partner_code,
        'Channel:'          .$channel_code
    );

    // Start CURL
    $ch = curl_init();

    // Set CURL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $option);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    // Run CURL
    $result         = curl_exec($ch);
    $error          = curl_error($ch);
    $httpcode       = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $header_size    = curl_getinfo($ch,CURLINFO_HEADER_SIZE);

    $header         = substr($result, 0, $header_size);
    $body           = substr($result, $header_size);
    $last_url       = curl_getinfo($ch,CURLINFO_EFFECTIVE_URL);

    // Close CURL
    curl_close($ch);

    $data_post                  = json_decode($body, true);
    $logwd_status               = isset($data_post['status'])                ? $data_post['status']               : false;
    $logwd_message              = isset($data_post['message'])               ? $data_post['message']              : false;
    $logwd_payout_ref           = isset($data_post['payout_ref'])            ? $data_post['payout_ref']           : false;
    $logwd_transaction_id       = isset($data_post['transaction_id'])        ? $data_post['transaction_id']       : false;
    $logwd_transactionDate_time = isset($data_post['transactionDate_time'])  ? $data_post['transactionDate_time'] : false;
    $logwd_qrstring             = isset($data_post['qrstring'])              ? $data_post['qrstring']             : false;

    echo "<br>";
    print_r($data_post);

 
    if ($logwd_status == 1000) {

        mysqli_query($connect, "INSERT INTO system_log_withdraw (
            logwd_status,
            logwd_message,
            logwd_member,
            logwd_money,
            logwd_payout_ref,
            logwd_transaction_id,
            logwd_transactionDate_time,
            logwd_qrstring
            ) 
        VALUES (
            '$logwd_status',
            '$logwd_message',
            '$member_id',
            '$commission',
            '$logwd_payout_ref',
            '$logwd_transaction_id',
            '$logwd_transactionDate_time',
            '$logwd_qrstring'
        )");
        
        mysqli_query($connect, "INSERT INTO system_withdraw (withdraw_member, withdraw_point, withdraw_status) VALUES ('$member_id', '$commission', 1)");
        mysqli_query($connect, "UPDATE system_liner SET liner_status = 2, liner_point = liner_point - '$liner_point' WHERE liner_member = '$member_id' ");

        header("location:../../../../member.php?page=member_withdraw&status=success&message=0");
        
    }
    else {

        header("location:../../../../member.php?page=member_withdraw&status=warning&message=$logwd_message");

    }
    
}

?>