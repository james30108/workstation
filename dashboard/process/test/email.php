<?php 
	$member_email  = "independente30108@gmail.com";
    $detail = '<html>
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>test</title>
    </head>
    <body>
    <h1>อีเมลแจ้งรหัสผ่านจาก $company</h1>
    <p>สวัสดีค่ะคุณ test</p>
    <p>Username และ Password เพื่อเข้าใช้งานระบบของคุณคือ</p>
    <b>Username :</b> 00001<br>
    <b>Password :</b> 000256<br>
    <hr>
    <p>วันที่ส่ง : '.date("Y-m-d H:i").'</p>
    <p>ขอบคุณค่ะ</p>
    </body>
</html>';

$subject       = "อีเมลแจ้งลืมรหัสผ่านจาก test";
$header       .= "MIME-Version: 1.0\r\n";
$header       .= "Content-type: text/html; charset=utf-8\r\n";
$header       .= "From: test";
@mail($member_email, $subject, $detail, $header);

?>