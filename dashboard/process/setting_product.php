<?php include('function.php');

if ($_POST['action'] == 'insert_product_type') {

    $product_type_code = $_POST['product_type_code'];
    $product_type_name = $_POST['product_type_name'];

    mysqli_query($connect, "INSERT INTO system_product_type (product_type_code, product_type_name) VALUES ('$product_type_code', '$product_type_name')");
    header('location:../admin.php?page=admin_product_type&status=success&message=0');

}
elseif ($_POST['action'] == 'edit_product_type') {

    $product_type_id    = $_POST['product_type_id'];
    $product_type_code  = $_POST['product_type_code'];
    $product_type_name  = $_POST['product_type_name'];

    mysqli_query($connect, "UPDATE system_product_type SET product_type_code = '$product_type_code', product_type_name = '$product_type_name' WHERE product_type_id = '$product_type_id' ");
    header('location:../admin.php?page=admin_product_type&status=success&message=0');

}
elseif($_GET['action'] == 'delete_product_type') {
    
    $product_type_id    = $_GET['product_type_id'];
    $query = mysqli_query($connect, "SELECT * FROM system_product WHERE product_type = '$product_type_id'");
	while($data = mysqli_fetch_array($query)){
		@unlink("../assets/images/products/" . $data['product_image']);
	}

	mysqli_query($connect, "DELETE FROM system_product WHERE product_type = '$product_type_id'")or die(mysql_error($connect));
	mysqli_query($connect, "DELETE FROM system_product_type WHERE product_type_id = '$product_type_id'")or die(mysql_error($connect));
    header('location:../admin.php?page=admin_product_type&status=success&message=0');

}
elseif ($_POST['action'] == 'insert_product') {
	
	$product_type 	        = $_POST['product_type'];
    $product_type2          = isset($_POST['product_type2']) ? $_POST['product_type2'] : false;
	$product_code 	        = $_POST['product_code'];
	$product_name 	        = $_POST['product_name'];
	$product_price 	        = $_POST['product_price'];
    $product_price_member   = $_POST['product_price_member'];
	$product_point 	        = $_POST['product_point'];
	$product_freight 	    = $_POST['product_freight'];
	$product_detail         = $_POST['product_detail'];
	$product_amount         = $_POST['product_amount'];
	
    if (!file_exists("../assets/images/products/")) {
        mkdir("../assets/images/products/");
    }
    if (!empty($_FILES['product_image_cover']['name'])) {
    	$type 		        = strrchr($_FILES['product_image_cover']['name'],".");    //เอาชื่อไฟล์เก่าออกให้เหลือแต่นามสกุล
	    $product_image_cover= "product_" .  date('YmdHis') . $type;             //ตั้งชื่อไฟล์ใหม่โดยเอาเวลาไว้หน้าชื่อไฟล์เดิม
	    move_uploaded_file($_FILES['product_image_cover']['tmp_name'], "../assets/images/products/$product_image_cover");
    }
    else {
    	$product_image_cover= "";
    }

	mysqli_query($connect, "INSERT INTO system_product (
        product_type,   product_name,   product_detail,
        product_price,  product_point,  product_freight,
        product_code,   product_amount, product_image_cover,  
        product_price_member, product_type2)
    VALUES (
        '$product_type',    '$product_name',    '$product_detail', 
        '$product_price',   '$product_point',   '$product_freight',
        '$product_code',    '$product_amount',  '$product_image_cover',   
        '$product_price_member', '$product_type2')")
    or die(mysqli_error($connect));
    
	$product_id = $connect -> insert_id;
	mysqli_query($connect, "INSERT INTO system_product_amount (product_amount_product, product_amount_old, product_amount_new) VALUES('$product_id', 0, '$product_amount')")
    or die(mysqli_error($connect));
    
    if (!empty($_FILES['product_image']['name'][0])) {

        foreach($_FILES['product_image']['tmp_name'] as $key => $val) {
            $i             = $key + 1;
            $type          = strrchr($_FILES['product_image']['name'][$key],".");
            $product_image = "product_" .  date('YmdHis') . "_" . rand() . $type;
            move_uploaded_file($_FILES['product_image']['tmp_name'][$key], "../assets/images/products/" . $product_image);
            mysqli_query($connect, "UPDATE system_product SET product_image_" . $i . " = '$product_image' WHERE product_id = '$product_id' ") or die(mysqli_error($connect));
        }
        
    }

    #### send news to email ####
        $query  = mysqli_query($connect, "SELECT * FROM system_notification");
        while ($data = mysqli_fetch_array($query)) {
            $detail = "<html>
            <head>
            <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
                <title>$company</title>
            </head>
            <body>
                <h1>สินค้าใหม่จาก $company</h1>
                <p>สินค้าใหม่ในระบบ</p>
                <b>ชื่อสินค้า และ detail<br>
                <hr>
                <p>วันที่ส่ง : " . date("Y-m-d H:i") . "</p>
            </body>
            </html>";

            $subject       =  "อีเมลแจ้งลืมรหัสผ่านจาก $company";
            $header       .= "MIME-Version: 1.0\r\n";
            $header       .= "Content-type: text/html; charset=utf-8\r\n";
            $header       .= "From: $company";
            @mail($member_email, $subject, $detail, $header);
        }
    #### ####

	header('location:../admin.php?page=admin_product&status=success&message=0');

}
elseif ($_POST['action'] == 'edit_product') {

    $product_id 	        = $_POST['product_id'];
    $product_type 	        = $_POST['product_type'];
	$product_code 	        = $_POST['product_code'];
	$product_name 	        = $_POST['product_name'];
	$product_price 	        = $_POST['product_price'];
    $product_price_member   = $_POST['product_price_member'];
	$product_point 	        = $_POST['product_point'];
	$product_freight 	    = $_POST['product_freight'];
	$product_detail         = $_POST['product_detail'];
    $product_image          = $_POST['product_image'];
    $product_type2          = isset($_POST['product_type2']) ? $_POST['product_type2'] : false;


    if ($_FILES['product_image_new']['name'] != '') {

        unlink ("../assets/images/products/" . $product_image);
        $type 		    = strrchr($_FILES['product_image_new']['name'],".");    //เอาชื่อไฟล์เก่าออกให้เหลือแต่นามสกุล
	    $product_image 	= "product_" .  date('YmdHis') . $type;             //ตั้งชื่อไฟล์ใหม่โดยเอาเวลาไว้หน้าชื่อไฟล์เดิม
	    move_uploaded_file($_FILES['product_image_new']['tmp_name'], "../assets/images/products/$product_image");
    }

	mysqli_query($connect, "UPDATE system_product SET 
        product_type    = '$product_type',  product_name  = '$product_name',  product_detail  = '$product_detail',
        product_price   = '$product_price', product_point = '$product_point', product_freight = '$product_freight',
        product_code    = '$product_code',  product_price_member= '$product_price_member', 
        product_type2   = '$product_type2'
    WHERE product_id    = '$product_id' ")
    or die(mysqli_error($connect));  

    header('location:../admin.php?page=admin_product&status=success&message=0');

}
elseif ($_POST['action'] == 'plus_amount') {

	$product_id 	        = $_POST['product_id']; 	
	$product_amount_new	    = $_POST['product_amount_new'];
    $product_amount_create  = date("Y-m-d H:i:s");
	
	//เพิ่มเก็บประวัติการเพิ่มจำนวนสินค้า
    $sql    = mysqli_query($connect, "SELECT * FROM system_product WHERE product_id = '$product_id' ");
    $data   = mysqli_fetch_array($sql);

	mysqli_query($connect, "INSERT INTO system_product_amount (
        product_amount_product,     product_amount_old, 
        product_amount_new,         product_amount_create)
        VALUES (
        '$product_id',      '".$data['product_amount']."',
        '$product_amount_new',  '$product_amount_create')");
	
    mysqli_query($connect, "UPDATE system_product SET product_amount = product_amount + '$product_amount_new' where product_id = '$product_id'");
	header('location:../admin.php?page=admin_product&status=success&message=0');

}
elseif ($_GET['action'] == 'delete_product') {
    
    $product_id = $_GET['product_id'];     
    
    $query = mysqli_query($connect, "SELECT * FROM system_product WHERE product_id = '$product_id'");
    $data  = mysqli_fetch_array($query);
    @unlink("../assets/images/products/" . $data['product_image_cover']);
    @unlink("../assets/images/products/" . $data['product_image_1']);
    @unlink("../assets/images/products/" . $data['product_image_2']);
    @unlink("../assets/images/products/" . $data['product_image_3']);
    @unlink("../assets/images/products/" . $data['product_image_4']);
    @unlink("../assets/images/products/" . $data['product_image_5']);

    mysqli_query($connect, "DELETE FROM system_product WHERE product_id = '$product_id'")or die(mysqli_error($connect));
    mysqli_query($connect, "DELETE FROM system_product_amount WHERE product_amount_product = '$product_id'")or die(mysqli_error($connect));
    
    header('location:../admin.php?page=admin_product&status=success&message=0');

}
elseif ($_GET['action'] == 'delete_image') {

    $product_id = $_GET['product_id'];
    $image      = $_GET['image'];

    $query      = mysqli_query($connect, "SELECT * FROM system_product WHERE product_id = '$product_id'");
    $data       = mysqli_fetch_array($query);

    if ($image == 'cover') {
        @unlink("../assets/images/products/" . $data['product_image_cover']);
        mysqli_query ($connect , "UPDATE system_product SET product_image_cover = null WHERE product_id = '$product_id' ");
    }
    else {
        @unlink("../assets/images/products/" . $data['product_image_' . $image]);
        mysqli_query ($connect , "UPDATE system_product SET product_image_" . $image . " = null WHERE product_id = '$product_id' ");
    }
    header("location:../admin.php?page=admin_product&action=edit_image&status=success&message=0&product_id=$product_id");

}
elseif ($_POST['action'] == 'edit_image') {

    $product_id = $_POST['product_id'];
    $image      = $_POST['image'];

    $query      = mysqli_query($connect, "SELECT * FROM system_product WHERE product_id = '$product_id'");
    $data       = mysqli_fetch_array($query);
    if ($image == 'cover') {
        @unlink("../assets/images/products/" . $data['product_image_cover']);
        $type           = strrchr($_FILES['product_image']['name'], ".");
        $product_image  = "product_" .  date('YmdHis') . $type;
        move_uploaded_file($_FILES['product_image']['tmp_name'], "../assets/images/products/" . $product_image);
        mysqli_query ($connect , "UPDATE system_product SET product_image_cover = '$product_image' WHERE product_id = '$product_id' ");
    }
    else {
        @unlink("../assets/images/products/" . $data['product_image_' . $image]);
        $type           = strrchr($_FILES['product_image']['name'], ".");
        $product_image  = "product_" .  date('YmdHis') . "_" . rand() . $type;
        move_uploaded_file($_FILES['product_image']['tmp_name'], "../assets/images/products/" . $product_image);
        mysqli_query ($connect , "UPDATE system_product SET product_image_" . $image . " = '$product_image' WHERE product_id = '$product_id' ");
    }
    header("location:../admin.php?page=admin_product&action=edit_image&status=success&message=0&product_id=$product_id");

}
?>