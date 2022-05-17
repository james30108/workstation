<?php include('../dashboard/process/function.php');

/*
// get token 
$url      = "http://43.254.133.173/~umeplusdev/app/v1.0/index.php/auth/";
$data     = "auth_user=UmeplusAcitve@OMC?uSER&auth_pass=~KVYQ2)c#WQ3@aPk";
$response = send_api ($connect, $url, $data, 'POST', 'test');
$token    = $response['DATA']['access_token'];
*/
/*
$url         = "http://43.254.133.173/~umeplusdev/app/v1.0/index.php/member/addjust/";
$data_array  = array(
    "ref_id" => '001', 
    "mcode"  => '2201000079', 
    "bonus"  => '1500',
    "date"   => '2021-08-11'
);
$data = http_build_query($data_array);
$response = send_api ($connect, $url, $data, 'POST', 'test');
print_r($response);
// member_code = 2201000079
?>