<?php 
$query = mysqli_query($connect, "SELECT * FROM system_cart WHERE cart_buyer_id = '$buyer_id' ");
$count = mysqli_num_rows($query);

if ($count <= 0) { header('location:index.php'); } 

$header_url     = "dashboard/assets/images/bg-themes/1.png";
$header_name    = $l_cart;
$header_detail  = "ตะกร้าสินค้าส่วนตัวของท่าน";
include('webpage_asset/include/include_header.php'); 

?>
<div class="page-wrapper">
  <div class="cart shopping">
    <div class="container">
      <div class="row">
        <div class="col-12 col-sm-8 mx-auto">
          <div class="block">
            <div class="product-list">
              <form method="post">
                <table class="table">
                  <thead>
                    <tr>
                      <th><?php echo $l_product_name ?></th>
                      <th><?php echo $l_quantity ?></th>
                      <th><?php echo $l_invoice_totprice ?></th>
                      <th><?php echo $l_manage ?></th>
                    </tr>
                  </thead>
                  <tbody>
                  	<?php 
                    $query = mysqli_query($connect, "SELECT system_cart.*, system_product.*
                    FROM system_cart
                    INNER JOIN system_product ON (system_cart.cart_product_id = system_product.product_id)
                    WHERE (cart_buyer_id = '$buyer_id')");
                  	while ($data = mysqli_fetch_array($query)) {
                      $quantity  = $data['cart_product_amount']; 
                  		$price     = $data['product_price'] * $quantity;
                  		?>
											<tr>
	                      <td height="80">
	                        <div class="product-info">
	                          <img width="80" src="images/shop/cart/cart-1.jpg" alt="" />
	                          <a href="?page=product_single&product_id=<?php echo $data['product_id'] ?>"><?php echo $data['product_name'] ?></a>
	                        </div>
	                      </td>
	                      <td><?php echo "$quantity $l_piece" ?></td>
	                      <td><?php echo number_format($price) . $l_bath ?></td>
	                      <td>
	                        <a class="product-remove" href="dashboard/process/setting_visitor_buy.php?action=delete_cart&cart_id=<?php echo $data['cart_id'] ?>"><?php echo $l_delete ?></a>
	                      </td>
	                    </tr>
                  	<?php } ?>
                  </tbody>
                </table>
                  <a href="?page=checkout" class="btn btn-main pull-right"><?php echo $l_pay ?></a>
                  <a href="?page=shop" class="m-3 text-decoration-underline small"><?php echo $l_buy_more ?></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>