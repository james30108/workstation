<?php include('function.php');

if (!file_exists("../assets/images/slips/")) { mkdir("../assets/images/slips"); }

if      (isset($_GET['action'])  && $_GET['action'] == 'insert_cart') {

    $page        = $_GET['page'];
    $product_id  = $_GET['product_id'];
    $member_id   = $_GET['member_id'];
    $query       = mysqli_query($connect, "SELECT * FROM system_cart WHERE (cart_product_id = '$product_id') AND (cart_member_id = '$member_id') ");
    $data        = mysqli_fetch_array($query);
    $cart_amount = $data['cart_product_amount'];
    $cart_id     = $data['cart_id'];
    $query       = mysqli_query($connect, "SELECT * FROM system_product WHERE product_id = '$product_id' ");
    $data        = mysqli_fetch_array($query);
    $product_amount = $data['product_amount'];

    if ($member_id == 0 || $product_id == 0) { die(); }

    // ---- ถ้าสินค้าอยู่ในตะกร้า และ สินค้าในสต๊อกไม่พอที่จะเพิ่ม ----
    if ($system_stock == 1 && $cart_amount && $cart_amount >= $product_amount) {

        header("location:../$page?page=shopping&status=warning&message=สินค้าไม่พอที่จะเพิ่มลงตะกร้า");

    }
    // ---- ถ้ามีรายการสินค้านี้ในตะกร้า และมีจำนวนพอที่จะเพิ่มได้ ----
    elseif ($system_stock == 1 && $cart_amount && $cart_amount < $product_amount) {

        mysqli_query($connect, "UPDATE system_cart SET cart_product_amount =  cart_product_amount + 1 WHERE cart_id = '$cart_id'") or die(mysql_error($connect));
        header("location:../$page?page=shopping&action=cart_setting&status=success&message=0");

    }
    // ---- ถ้าไม่มีรายการสินค้าในตะกร้า และจำนวนเหลืออยู่ ----
    elseif ($system_stock == 1 && $product_amount != 0) {

        mysqli_query($connect, "INSERT INTO system_cart (cart_member_id, cart_product_id) VALUES ('$member_id', '$product_id')");
        header("location:../$page?page=shopping&action=cart_setting&status=success&message=0");

    }
    // ---- ถ้าไม่มีรายการสินค้าในตะกร้า และจำนวนสินค้าไม่พอ ----
    elseif ($system_stock == 1 && !$cart_id) {

        header("location:../$page?page=shopping&status=warning&message=สินค้าไม่พอที่จะเพิ่มลงตะกร้า");

    } 
    // ---- ไม่มีระบสต๊อก ----
    elseif ($system_stock == 0 && $cart_amount) {

        mysqli_query($connect, "UPDATE system_cart SET cart_product_amount =  cart_product_amount + 1 WHERE cart_id = '$cart_id'") or die(mysql_error($connect));
        header("location:../$page?page=shopping&action=cart_setting&status=success&message=0");

    }
    elseif ($system_stock == 0) {

        mysqli_query($connect, "INSERT INTO system_cart (cart_member_id, cart_product_id) VALUES ('$member_id', '$product_id')");
        header("location:../$page?page=shopping&action=cart_setting&status=success&message=0");

    }

} 
elseif  (isset($_GET['action'])  && $_GET['action'] == 'update_cart') {

    $cart_id    = $_GET['cart_id'];
    $member_id  = $_GET['member_id'];

    if (!isset($_GET['update'])) {
        $cart_product_amount = $_GET['cart_product_amount'];
        if ($cart_product_amount < 1) {
            header("location:../" . $_GET['page'] . "?page=shopping&action=cart_setting&status=warning&message=ต้องใส่จำนวนมากกว่า0");
            die;
        } else {
            mysqli_query($connect, "UPDATE system_cart SET cart_product_amount = '$cart_product_amount' WHERE cart_id = '$cart_id'");
        }
    } elseif ($_GET['update'] == 'minus') {
        mysqli_query($connect, "UPDATE system_cart SET cart_product_amount = cart_product_amount - 1 WHERE cart_id = '$cart_id'");
    } elseif ($_GET['update'] == 'plus') {
        mysqli_query($connect, "UPDATE system_cart SET cart_product_amount = cart_product_amount + 1 WHERE cart_id = '$cart_id'");
    }
    header("location:../" . $_GET['page'] . "?page=shopping&action=cart_setting&status=success&message=0");

} 
elseif  (isset($_GET['action'])  && $_GET['action'] == 'cancel_cart') {

    mysqli_query($connect, "DELETE FROM system_cart WHERE cart_member_id = '" . $_GET['member_id'] . "' ");
    header("location:../" . $_GET['page'] . "?page=shopping&status=success&message=0");

} 
elseif  (isset($_GET['action'])  && $_GET['action'] == 'delete_cart') {

    mysqli_query($connect, "DELETE FROM system_cart WHERE cart_id = '" . $_GET['cart_id'] . "' ");
    header("location:../" . $_GET['page'] . "?page=shopping&action=cart_setting&status=success&message=0");

} 
elseif  (isset($_GET['action'])  && $_GET['action'] == 'cancel_order') {

    $order_id       = $_GET['order_id'];
    $page_type      = $_GET['page_type'];
    $order_status   = $page_type == 'member.php' ? 2 : 1;

    mysqli_query($connect, "UPDATE system_order     SET order_status = '$order_status' WHERE order_id = '$order_id'");
    mysqli_query($connect, "UPDATE system_pay_refer SET pay_status   = 2 WHERE pay_order = '$order_id' ");

    if ($system_stock == 1) {
        $query = mysqli_query($connect, "SELECT * FROM system_order_detail WHERE order_detail_order = '$order_id'");
        while ($data = mysqli_fetch_array($query)) {

            $order_detail_amount    = $_GET['order_detail_amount'];
            $order_detail_product   = $_GET['order_detail_product'];

            mysqli_query($connect, "UPDATE system_product SET product_amount = product_amount + '$order_detail_amount' WHERE product_id = '$order_detail_product'");

        }
    }
    header("location:../$page_type?page=order&status=success&message=0");

}
elseif  (isset($_POST['action']) && $_POST['action'] == 'confirm_member') {

    $page           = $_POST['page'];
    $order_member   = $_POST['order_member'];
    $order_price    = $_POST['order_price'];
    $order_amount   = $_POST['order_amount'];
    $order_point    = $_POST['order_point'];
    $order_freight  = $_POST['order_freight'];
    $order_type_buy = isset($_POST['order_type_buy'])   ? $_POST['order_type_buy']  : 0;
    $order_detail   = isset($_POST['order_detail'])     ? $_POST['order_detail']    : false;

    $order_buyer    = isset($_POST['order_buyer'])      ? $_POST['order_buyer']     : false;
    $order_buyer_tel= isset($_POST['order_buyer_tel'])  ? $_POST['order_buyer_tel'] : false;

    $order_address  = isset($_POST['order_address'])    ? $_POST['order_address']   : false;
    $order_district = isset($_POST['order_district'])   ? $_POST['order_district']  : false;
    $order_amphur   = isset($_POST['order_amphur'])     ? $_POST['order_amphur']    : false;
    $order_province = isset($_POST['order_province'])   ? $_POST['order_province']  : false;
    $order_zipcode  = isset($_POST['order_zipcode'])    ? $_POST['order_zipcode']   : false;

    // Check Stock
    if ($system_stock == 1) {
        $query = mysqli_query($connect, "SELECT system_cart.*, system_product.* 
            FROM system_cart 
            INNER JOIN system_product ON (system_cart.cart_product_id = system_product.product_id)
            WHERE cart_member_id = '$order_member' ");
        while ($data = mysqli_fetch_array($query)) {

            $cart_id        = $data['cart_id'];
            $product_amount = $data['product_amount'];
            $cart_amount    = $data['cart_product_amount'];

            if ($cart_amount > $product_amount) {
                mysqli_query($connect, "DELETE FROM system_cart WHERE cart_id = '$cart_id' ")or die(mysqli_error());
                header("location:../$page?page=shopping&status=warning&message=Out of Stock");
                die;
            }
        }
    }

    // Ewallet
    if ($order_type_buy == 1 || $order_type_buy == 3) {
        mysqli_query($connect, "UPDATE system_member SET member_ewallet = member_ewallet - $order_price - $order_freight WHERE member_id = '$order_member' ");
    }

    // Insert order
    $query = mysqli_query($connect, "SELECT * FROM system_order ORDER BY order_id DESC");
    $data  = mysqli_fetch_array($query);

    $order_id = $data['order_id'] + 1;
    if      ( $order_id <= 9 ) { $zero = "ORDER-000000"; } 
    elseif  ( $order_id <= 99 ) { $zero = "ORDER-00000"; } 
    elseif  ( $order_id <= 999 ) { $zero = "ORDER-0000"; } 
    elseif  ( $order_id <= 9999 ) { $zero = "ORDER-000"; } 
    elseif  ( $order_id <= 99999 ) { $zero = "ORDER-00"; } 
    elseif  ( $order_id <= 999999 ) { $zero = "ORDER-0"; } 
    else    { $zero = ""; }
    $order_code = $zero . $order_id;

    mysqli_query($connect, "INSERT INTO system_order (
        order_code,         order_member,       order_amount,       order_price,        order_point, 
        order_address,      order_freight,      order_detail,       order_type_buy,     order_buyer,
        order_buyer_tel,    order_district,     order_amphur,       order_province,     order_zipcode)
    VALUES (
        '$order_code',      '$order_member',    '$order_amount',    '$order_price',     '$order_point', 
        '$order_address',   '$order_freight',   '$order_detail',    '$order_type_buy',  '$order_buyer',
        '$order_buyer_tel', '$order_district',  '$order_amphur',    '$order_province',  '$order_zipcode'
    )") or die(mysqli_error($connect));
    $order_id = $connect->insert_id;

    $query = mysqli_query($connect, "SELECT system_cart.*, system_product.* 
        FROM system_cart 
        INNER JOIN system_product ON (system_cart.cart_product_id = system_product.product_id)
        WHERE cart_member_id = '$order_member' ");
    while ($data = mysqli_fetch_array($query)) {

        $product_id     = $data['cart_product_id'];
        $product_amount = $data['cart_product_amount'];
        $product_price  = $data['product_price_member'];
        $product_point  = $data['product_point'];
        $product_freight= $data['product_freight'];

        // Minus in Stock
        if ($system_stock == 1) {
            mysqli_query($connect, "UPDATE system_product SET product_amount = product_amount - '$product_amount' WHERE product_id = '$product_id' ");
        }

        // Insert order detail
        mysqli_query($connect, "INSERT INTO system_order_detail (
            order_detail_order,    
            order_detail_product,           
            order_detail_amount, 
            order_detail_price,                 
            order_detail_point,
            order_detail_freight)
        VALUES (
            '$order_id', 
            '$product_id',     
            '$product_amount',
            '$product_price',    
            '$product_point',
            '$product_freight'

        )") or die(mysqli_error($connect));
    }

    // Delete cart
    mysqli_query($connect, "DELETE FROM system_cart WHERE cart_member_id = '$order_member' ") or die(mysqli_error($connect));

    // Payment 
    if ($order_type_buy == 0) {
        
        $pay_title  = "Payment from $order_code";
        $pay_buyer  = $_POST['pay_buyer'];
        $pay_bank   = $_POST['pay_bank'] ? $_POST['pay_bank'] : false;
        $pay_date   = $_POST['pay_date'];
        $pay_time   = $_POST['pay_time'];
        $type       = strrchr($_FILES['pay_image']['name'],".");    //เอาชื่อไฟล์เก่าออกให้เหลือแต่นามสกุล
        $pay_image  = "slip_" .  date('YmdHis') . $type;            //ตั้งชื่อไฟล์ใหม่โดยเอาเวลาไว้หน้าชื่อไฟล์เดิม
        move_uploaded_file($_FILES['pay_image']['tmp_name'], "../assets/images/slips/" . $pay_image);

        mysqli_query($connect, "INSERT INTO system_pay_refer (
            pay_title,      pay_bank,         pay_money,        pay_date,       pay_time, 
            pay_image,      pay_member,       pay_order,        pay_buyer
        )
        VALUES (
            '$pay_title',   '$pay_bank',      '$order_price',   '$pay_date',    '$pay_time', 
            '$pay_image',   '$order_member',  '$order_id',      '$pay_buyer'
        )");
        
        header("location:../$page?page=order&status=success&message=0");

    } 
    elseif ($order_type_buy == 1 || $order_type_buy == 2 || $order_type_buy == 3) {
    
        $url = "location:project/$system_style/process/setting_buy.php?action=confirm_admin&order_type_buy=$order_type_buy&page=$page&order_id=$order_id";
        header($url); 

    }
    elseif ($order_type_buy == 4) {
        
        header("location:../$page?page=shopping&action=payment&order_id=$order_id"); 

    }

} 
elseif  (isset($_POST['action']) && $_POST['action'] == 'confirm_buyer') {
    
    #### insert order ####
    $order_buyer_id     = $_POST['order_buyer_id'];
    $order_member       = $_POST['order_member'];
    $order_buyer        = $_POST['order_buyer'];
    $order_buyer_tel    = $_POST['order_buyer_tel'];
    $order_buyer_email  = $_POST['order_buyer_email'];
    $order_freight      = $_POST['order_freight'];
    $order_price        = $_POST['order_price'];
    
    $order_type_buy     = $_POST['order_type_buy'];
    $order_amount       = $_POST['order_amount'];
    $order_point        = $_POST['order_point'];

    $order_address      = isset($_POST['order_address'])    ? $_POST['order_address']   : false;
    $order_district     = isset($_POST['order_district'])   ? $_POST['order_district']  : false;
    $order_amphur       = isset($_POST['order_amphur'])     ? $_POST['order_amphur']    : false;
    $order_province     = isset($_POST['order_province'])   ? $_POST['order_province']  : false;
    $order_zipcode      = isset($_POST['order_zipcode'])    ? $_POST['order_zipcode']   : false;

    // Insert order
    $query = mysqli_query($connect, "SELECT * FROM system_order ORDER BY order_id DESC");
    $data  = mysqli_fetch_array($query);

    $order_id = $data['order_id'] + 1;
    if      ( $order_id <= 9 ) { $zero = "ORDER-000000"; } 
    elseif  ( $order_id <= 99 ) { $zero = "ORDER-00000"; } 
    elseif  ( $order_id <= 999 ) { $zero = "ORDER-0000"; } 
    elseif  ( $order_id <= 9999 ) { $zero = "ORDER-000"; } 
    elseif  ( $order_id <= 99999 ) { $zero = "ORDER-00"; } 
    elseif  ( $order_id <= 999999 ) { $zero = "ORDER-0"; } 
    else    { $zero = ""; }
    $order_code = $zero . $order_id;

    mysqli_query($connect, "INSERT INTO system_order (
        order_code,         order_member,       order_amount,       order_price,        order_point, 
        order_address,      order_freight,      order_buyer_tel,    order_buyer_email,  order_buyer,
        order_district,     order_amphur,       order_province,     order_zipcode,      order_buyer_id,
        order_type_buy
    ) VALUES (
        '$order_code',      '$order_member',    '$order_amount',    '$order_price',     '$order_point', 
        '$order_address',   '$order_freight',   '$order_buyer_tel', '$order_buyer_email','$order_buyer',
        '$order_district',  '$order_amphur',    '$order_province',  '$order_zipcode',   '$order_buyer_id',
        '$order_type_buy'
    )") or die(mysqli_error($connect));
    $order_id = $connect->insert_id;


    $query = mysqli_query($connect, "SELECT system_cart.*, system_product.* 
        FROM system_cart 
        INNER JOIN system_product ON (system_cart.cart_product_id = system_product.product_id)
        WHERE cart_buyer_id = '$order_buyer_id' ");
    while ($data = mysqli_fetch_array($query)) {

        $product_id     = $data['cart_product_id'];
        $product_amount = $data['cart_product_amount'];
        $product_price  = $data['product_price'];
        $product_point  = $data['product_point'];
        $product_freight= $data['product_freight'];

        // Minus in Stock
        if ($system_stock == 1) {
            mysqli_query($connect, "UPDATE system_product SET product_amount = product_amount - '$product_amount' WHERE product_id = '$product_id' ");
        }

        // Insert order detail
        mysqli_query($connect, "INSERT INTO system_order_detail (
            order_detail_order,    
            order_detail_product,           
            order_detail_amount, 
            order_detail_price,                 
            order_detail_point,
            order_detail_freight)
        VALUES (
            '$order_id', 
            '$product_id',     
            '$product_amount',
            '$product_price',    
            '$product_point',
            '$product_freight'

        )") or die(mysqli_error($connect));
    }

    // Delete cart
    mysqli_query($connect, "DELETE FROM system_cart WHERE cart_buyer_id = '$order_buyer_id' ") or die(mysqli_error($connect));


    // Payment
    if ($order_type_buy == 0) {

        $pay_title  = "Payment from $order_code";
        $pay_buyer  = $_POST['pay_buyer'];
        $pay_bank   = isset($_POST['pay_bank']) ? $_POST['pay_bank'] : false;
        $pay_date   = isset($_POST['pay_date']) ? $_POST['pay_date'] : false;
        $pay_time   = isset($_POST['pay_time']) ? $_POST['pay_time'] : false;
        $type       = strrchr($_FILES['pay_image']['name'],".");    //เอาชื่อไฟล์เก่าออกให้เหลือแต่นามสกุล
        $pay_image  = "slip_" .  date('YmdHis') . $type;            //ตั้งชื่อไฟล์ใหม่โดยเอาเวลาไว้หน้าชื่อไฟล์เดิม
        move_uploaded_file($_FILES['pay_image']['tmp_name'], "../assets/images/slips/" . $pay_image);

        mysqli_query($connect, "INSERT INTO system_pay_refer (
            pay_title,      pay_bank,         pay_money,        pay_date,       pay_time, 
            pay_image,      pay_order,        pay_buyer
        )
        VALUES (
            '$pay_title',   '$pay_bank',      '$order_price',   '$pay_date',    '$pay_time', 
            '$pay_image',   '$order_id',      '$pay_buyer'
        )");

    }

    // Send Track ID
    $detail = "<html>
        <head>
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
        <title>$company</title>
        </head>
        <body>
        <h1>แจ้งคำสั่งซื้อจาก $company</h1>
        <p>เรียน " . $order_buyer . "</p>
        <p>ขณะนี้ระบบได้ทำการบันทึกคำสั่งซื้อสินค้าของท่านเรียบร้อยแล้ว ทางทีมงานของเราจะรีบดำเนินการตรวจสอบข้อมูลของท่านโดยเร็วที่สุด</p>
        <b>รหัสคำสั่งซื้อของท่านคือ " . $order_id . "<br>
        <b>หากท่านมีปัญหาหรือต้องการปรึกษากับทางทีมงาน ให้ติดต่อมาที่ช่องทางการติดต่อที่หน้าเว็บเพจของเราได้ตลอด 24 ชั่วโมง แล้วเราจะรีบดำเนินการติดต่อกลับไปโดยเร็วที่สุด<br>
        <hr>
        <p>วันที่ส่ง : " . datethai($data_now, 0, 1) . "</p>
        <p>ขอบพระคุณที่ให้ความไว้วางใจและอุดหนุนสินค้าของเรา</p>
        </body>
    </html>";

    $subject       = "แจ้งคำสั่งซื้อจาก $company";
    $header       .= "MIME-Version: 1.0\r\n";
    $header       .= "Content-type: text/html; charset=utf-8\r\n";
    $header       .= "From: $company";
    mail($member_email, $subject, $detail, $header);

    header("location:../../?page=confirmation&status=buy&order=$order_id");
    
}
?>