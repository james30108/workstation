<?php include('../dashboard/process/function.php');

$url      = "http://43.254.133.173/~umeplusdev/app/v1.0/index.php/member/product/info";
$data     = "pcode=ALL&pgcode=ALL";
$response = send_api ($connect, $url, $data, 'POST', 'test');
//print_r($response);
//echo $response['DATA']['product'][1]['pcode'];

/*
//
// Process's insert and update product in product database 
//
*/

// update product in product database
foreach ($response['DATA']['product'] as $key) {
  
  $product_code         = $key['pcode'];
  $product_name         = $key['pdesc'];
  $product_price        = $key['customer_price'];
  $product_price_member = $key['price'];
  $product_amount       = $key['qty'];
  $product_group        = $key['groupname'];
  $product_unit         = $key['unit'];
  $product_image_cover  = $key['picture'];
  $product_weight       = $key['weight'];
  
  //$product_point        = $key['pv'];
  $product_point        = $key['customer_price'] - $key['price'];
  if ($product_point <= 0) {
    $product_point      = 0;
  }

  $query = mysqli_query($connect, "SELECT * FROM system_product WHERE product_code = '$product_code' ");
  $count = mysqli_num_rows($query);
  
  if ($count == 0) {
    
    echo $product_code . " price = " . $product_price . " / customer_price = " . $product_price_member . " จำนวน = " . $product_amount . "<br>";
    mysqli_query($connect, "INSERT INTO system_product (
      product_code, 
      product_name,
      product_price,
      product_price_member,
      product_point,
      product_amount,
      product_unit,
      product_group,
      product_weight,
      product_image_cover
    ) VALUES (
      '$product_code', 
      '$product_name',
      '$product_price',
      '$product_price_member',
      '$product_point',
      '$product_amount',
      '$product_unit',
      '$product_group',
      '$product_weight',
      '$product_image_cover') ");
  }
  else {

    echo "เปลี่ยนแล้ว";
    mysqli_query($connect, "UPDATE system_product SET 
      product_name        = '$product_name',
      product_price       = '$product_price',
      product_price_member= '$product_price_member',
      product_point       = '$product_point',
      product_amount      = '$product_amount',
      product_unit        = '$product_unit',
      product_group       = '$product_group',
      product_weight      = '$product_weight',
      product_image_cover = '$product_image_cover'
    WHERE product_code    = '$product_code' ");
  }
  
  //mysqli_query($connect, "REPLACE INTO system_product SET product_code = '$product_code' ");
  
}


/*
//
// Process's insert and update package in product database 
//
*/

// update package in product database
foreach ($response['DATA']['package'] as $key) {
  
  $product_code         = $key['pcode'];
  $product_name         = $key['pdesc'];
  $product_price        = $key['customer_price'];
  $product_price_member = $key['price'];
  $product_amount       = $key['qty'];
  $product_group        = $key['groupname'];
  $product_unit         = $key['unit'];
  $product_image_cover  = $key['picture'];
  $product_weight       = $key['weight'];

  //$product_point        = $key['pv'];
  $product_point        = $key['customer_price'] - $key['price'];
  if ($product_point <= 0) {
    $product_point      = 0;
  }
  
  $query = mysqli_query($connect, "SELECT * FROM system_product WHERE product_code = '$product_code' ");
  $count = mysqli_num_rows($query);
  
  //echo $count . "<br>";
  
  if ($count == 0) {
    
    echo $product_code . "<br>";
    mysqli_query($connect, "INSERT INTO system_product (
      product_type2,
      product_code, 
      product_name,
      product_price,
      product_price_member,
      product_point,
      product_amount,
      product_unit,
      product_group,
      product_weight,
      product_image_cover
    ) VALUES (
      1,
      '$product_code', 
      '$product_name',
      '$product_price',
      '$product_price_member',
      '$product_point',
      '$product_amount',
      '$product_unit',
      '$product_group',
      '$product_weight',
      '$product_image_cover') ");
  }
  else {

    echo "เปลี่ยนแล้ว";
    mysqli_query($connect, "UPDATE system_product SET 
      product_name        = '$product_name',
      product_price       = '$product_price',
      product_price_member= '$product_price_member',
      product_point       = '$product_point',
      product_amount      = '$product_amount',
      product_unit        = '$product_unit',
      product_group       = '$product_group',
      product_weight      = '$product_weight',
      product_image_cover = '$product_image_cover'
    WHERE product_code    = '$product_code' ");
  }

}


/*
//
// Process's reset package database
//
*/

// update package in package database
mysqli_query($connect, "TRUNCATE TABLE system_package");
foreach ($response['DATA']['package'] as $key) {

  $product_code = $key['pcode'];
  $query        = mysqli_query($connect, "SELECT * FROM system_product WHERE product_code = '$product_code' ");
  $data         = mysqli_fetch_array($query);
  $package_main = $data['product_id'];

  echo $key['pcode'] . " - " . $key['pdesc'] , " ---- <br>";
  
  foreach ($key['pInpk'] as $package) {

    $product_code     = $package['pcode'];
    $query            = mysqli_query($connect, "SELECT * FROM system_product WHERE product_code = '$product_code' ");
    $data             = mysqli_fetch_array($query);
    $package_product  = isset($data) ? $data['product_id'] : 0;
    $package_name     = $package['pdesc'];
    $package_point    = $package['pv'];
    $package_price    = $package['price'];
    $package_quantity = $package['qty'];

    echo $package_main . " // " . $key['pcode'] . "-->" . $package['pcode'] . " --- " . $package['pdesc'] . " // product id =" . $package_product . "<br>";

    mysqli_query($connect, "INSERT INTO system_package (
      package_main,
      package_product,
      package_name,
      package_point,
      package_price,
      package_quantity
    ) VALUES (
      '$package_main',
      '$package_product',
      '$package_name',
      '$package_point',
      '$package_price',
      '$package_quantity'
    )");
  }
  
}


/*
//
// Process's reset product type database
//
*/

// รีเซ็ตตาราง
mysqli_query($connect, "TRUNCATE TABLE system_product_type");
mysqli_query($connect, "INSERT INTO system_product_type (product_type_code, product_type_name) VALUES ('PT-1', 'สินค้าอื่นๆ')");
mysqli_query($connect, "UPDATE system_product SET product_type = 1 ");

// ขั้นตอนการเพิ่มหมายเลขสินค้า
$query  = mysqli_query($connect, "SELECT * FROM system_product WHERE product_group != '' GROUP BY product_group ");
$i    = 1;
while ($data = mysqli_fetch_array($query)) {
  
  $i++;
  $product_type_code = "PT-" . $i; 
  $product_type_name = $data['product_group'];
  echo $data['product_group'] . "<br>";

  mysqli_query($connect, "INSERT INTO system_product_type (product_type_code, product_type_name) VALUES ('$product_type_code', '$product_type_name') ");

}

// ขั้นตอนการเพิ่มไอดีประเภทสินค้า
$query  = mysqli_query($connect, "SELECT * FROM system_product_type ");
while ($data = mysqli_fetch_array($query)) {
  
  $product_group = $data['product_type_name'];
  $product_type  = $data['product_type_id'];
  mysqli_query($connect, "UPDATE system_product SET product_type = '$product_type' WHERE product_group = '$product_group' ");

}

echo "เสร็จสิ้น";
?>