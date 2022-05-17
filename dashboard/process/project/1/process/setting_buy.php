<?php include('../../../function.php');

if (!file_exists("../../../../assets/images/slips/")) { mkdir("../../../../assets/images/slips"); }

if ($_GET['action'] == 'confirm_admin') {
    
    $order_id       = $_GET['order_id'];
    $query          = mysqli_query($connect, "SELECT * FROM system_order WHERE order_id = '$order_id'");
    $data           = mysqli_fetch_array($query);

    // ---- default data ----
    $member_id      = $data['order_member'];
    $point_bonus    = $data['order_point'];
    $point_detail   = "เงินปันผลจากการแนะนำสินค้า";
    $order_code     = $data['order_code'];
    $order_price    = $data['order_price'];
    $order_buyer_tel= $data['order_buyer_tel'];
    $order_address  = $data['order_address'];
    $order_district = $data['order_district'];
    $order_amphur   = $data['order_amphur'];
    $order_province = $data['order_province'];
    $order_zipcode  = $data['order_zipcode'];
    $order_buyer    = $data['order_buyer'];
    $pay_order      = $data['order_id'];

    mysqli_query($connect, "UPDATE system_order     SET order_status = 4 WHERE order_id  = '$order_id'");
    mysqli_query($connect, "UPDATE system_pay_refer SET pay_status   = 1 WHERE pay_order = '$pay_order' ");

    if ($member_id != '') {
        mysqli_query($connect, "INSERT INTO system_point (
            point_member,
            point_order, 
            point_bonus, 
            point_detail, 
            point_type) 
        VALUES (
            '$member_id',
            '$order_id',  
            '$point_bonus', 
            '$point_detail', 
            1)");
        $point_id   = $connect -> insert_id;
        
        mysqli_query($connect, "UPDATE system_member SET 
            member_point        = member_point + '$point_bonus', 
            member_point_month  = member_point_month + '$point_bonus' 
        WHERE member_id = '$member_id' ");

        $query      = mysqli_query($connect, "SELECT * FROM system_member WHERE member_id = '$member_id' ");
        $data       = mysqli_fetch_array($query);
        $member_code= $data['member_code'];
    }

    $query          = mysqli_query($connect, "SELECT * FROM system_order_detail WHERE order_detail_order = '$pay_order'");
    $array_code     = array();
    $array_qty      = array();
    while ($data = mysqli_fetch_array($query)) {
        
        $qty        = $data['order_detail_amount'];
        $query_code = mysqli_query($connect, "SELECT * FROM system_product WHERE product_id = '".$data['order_detail_product']."' ");
        $data_code  = mysqli_fetch_array($query_code);
        $code       = $data_code['product_code'];
        array_push($array_code, $code);
        array_push($array_qty , $qty);
    }
    echo $product_code = implode(",", $array_code);
    echo "<br>";
    echo $product_qty  = implode(",", $array_qty);
    echo "<br>";

    // ---- api ----
    $url  = "http://43.254.133.173/~umeplusdev/app/v1.0/index.php/member/cart/sale/";

    $data_array     = array(
      "mem_id"      => $member_code, 
      "sadate"      => $data_now, 
      "order_id"    => $pay_order,
      "pcode"       => $product_code, 
      "qty"         => $product_qty, 
      "total_pv"    => $point_bonus,
      "total_price" => $order_price, 
      "discount"    => 0, 
      "sa_type"     => 1,
      "cname"       => $order_buyer, 
      "caddress"    => $order_address, 
      "cdistrictId" => $order_district,
      "camphurId"   => $order_amphur, 
      "cprovinceId" => $order_province, 
      "czip"        => $order_zipcode,
      "cmobile"     => $order_buyer_tel, 
      "send"        => 1, 
      "inv_code"    => "ON001",
      "paychannel"  => 3,
      "locationbase"=> 1,
      "channel"     => 3
    );
    $data = http_build_query($data_array);
    $response = send_api ($connect, $url, $data, 'POST', 'test');
    print_r($response);
    echo "<br>";

    $url         = "http://43.254.133.173/~umeplusdev/app/v1.0/index.php/member/addjust/";
    $data_array  = array(
        "ref_id" => $point_id, 
        "mcode"  => $member_code, 
        "bonus"  => $point_bonus,
        "date"   => $date_now
    );
    $data = http_build_query($data_array);
    $response = send_api ($connect, $url, $data, 'POST', 'test');
    print_r($response);
    
    // ---- ---- ----
    header('location:../../../../admin.php?page=order&status=success&message=0');
}
?>