<?php 
    date_default_timezone_set('Asia/Bangkok');
    include('connect.php');
    $member_user    = "0000047";
    $member_email   = "independente30108@gmail.com";
    $compant        = "test";

    $query  = mysqli_query($connect, "SELECT * FROM system_member WHERE (member_email = '$member_email') AND (member_user = '$member_user') limit 1")or die(mysqli_error($connect));
    $empty  = mysqli_num_rows($query);
    if( $empty == 1 ) {
        $data = mysqli_fetch_array($query);
        $member_pass = $data['member_pass'];

            $detail = '<html>
            <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>$company</title>
            </head>
            <body>
            <h1>อีเมลแจ้งรหัสผ่านจาก $company</h1>
            <p>สวัสดีค่ะคุณ dsad</p>
            <p>Username และ Password เพื่อเข้าใช้งานระบบของคุณคือ</p>
            <b>Username :</b> $member_user <br>
            <b>Password :</b> $member_pass <br>
            <hr>
            <p>วันที่ส่ง : '.date("Y-m-d H:i").'</p>
            <p>ขอบคุณค่ะ</p>
            </body>
        </html>';

        $subject       =  "อีเมลแจ้งลืมรหัสผ่านจาก $company";
        $header       .= "MIME-Version: 1.0\r\n";
        $header       .= "Content-type: text/html; charset=utf-8\r\n";
        $header       .= "From: test";
        @mail($member_email, $subject, $detail, $header);
       
        echo "have";
    } else { 
        echo "not have";
    }
?>