<?php include('../function.php'); 

// API
//Use Authorization and partner_code from your account
$authorization  = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJwYXJ0bmVyX2NvZGUiOiJBUFciLCJ1dWlkIjoiZjdiMDQ1NjUtZDcwZS00NjkxLTg0OWMtNjVhNGM5YThmMjMzIn0.lwPrGBqTSWYmQLesoIStkua5iftBA4jjiAXMgTW994w";
$partner_code   = "APW";


// API URL
$url            = "https://payout.1-2-pay.com/payout";      
$ref1           = date('YmdHis');
$channel_code   = "WEB";

// Setup request to send json via POST
$data = array(
    'bankacc'       => 1032144932,
    'bankcode'      => 004,
    'bankname'      => 'KASIKORN BANK',
    'accname'       => 'Nathasophon',
    'amount'        => 1,
    'mobileno'      => '0996932072',
    'transaction_by'=> 'developer',
    'ref1'          => $ref1
);


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


if ($httpcode == 1000) {

    $data_post  = json_decode($body, true);

}
else {

    $data_post = array(
        'code'      => "500",
        'status'    => "error",
        'msg_th'    => $body,
        'msg_en'    => $body
    );

}

print_r($data_post);

?>