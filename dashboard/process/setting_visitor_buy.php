<?php include('function.php'); 

if (!file_exists("../assets/images/slips/")) { mkdir("../assets/images/slips/"); }

if      ($_GET['action'] == 'insert_cart') {

    $product_id  = $_GET['product_id'];
    $buyer_id    = $_GET['buyer_id'];

    if (!isset($_GET['product_quantity'])) {
        $quantity= 1;
    }
    else {
        $quantity= $_GET['product_quantity'];
    }
    $query       = mysqli_query($connect, "SELECT * FROM system_cart WHERE (cart_product_id = '$product_id') AND (cart_member_id = '$buyer_id') ");
    $data        = mysqli_fetch_array($query);
    
    // ถ้าไม่มีสินค้าในตะกร้า
    if (!$data) {
        mysqli_query($connect, "INSERT INTO system_cart (cart_member_id, cart_product_id, cart_product_amount) VALUES ('$buyer_id', '$product_id', '$quantity')");
    }
    // ถ้ามีสินค้าในตะกร้า
    else {
        mysqli_query($connect, "UPDATE system_cart SET cart_product_amount =  cart_product_amount + '$quantity' WHERE cart_id = '".$data['cart_id']."'") or die(mysql_error($connect));
    }
    header('location:../../?page=cart');
    
}
elseif  ($_GET['action'] == 'delete_cart') {
    
    mysqli_query($connect, "DELETE FROM system_cart WHERE cart_id = '" . $_GET['cart_id'] . "' ");
    header('location:../../?page=cart');

}
elseif  ($_GET['action'] == 'cancel_order') {

    mysqli_query($connect, "UPDATE system_order SET order_status = 2 WHERE order_id = '".$_GET['order_id']."'");
    mysqli_query($connect, "UPDATE system_pay_refer SET pay_status = 2 WHERE pay_order = '".$_GET['order_id']."'");
    
    $query = mysqli_query($connect, "SELECT * FROM system_order_detail WHERE order_detail_order = '".$_GET['order_id']."'");
    while ($data = mysqli_fetch_array($query)) {
        mysqli_query($connect, "UPDATE system_product SET product_amount = product_amount + '".$data['order_detail_amount']."' WHERE product_id = '".$data['order_detail_product']."'");
    }
    header('location:../admin.php?page=order&status=success');
    
}
?>