<?php isset($_GET["status"]) ? alert ($_GET["status"], $_GET["message"], $lang) : false;

$action       = isset($_GET['action']) ? $_GET['action'] : false;

if (!isset($_GET['action']) || $_GET['action'] == 'search') { 
    
    $sproduct_type_id    = isset($_GET['product_type_id'])  ? $_GET['product_type_id']  : "all";
    $sproduct_type2      = isset($_GET['product_type2'])    ? $_GET['product_type2']    : "all";
    $sproduct_name       = isset($_GET['product_name'])     ? $_GET['product_name']     : false;
    $action              = isset($_GET['action'])           ? $_GET['action']           : false;

    ?>
    <title><?php echo $l_product; ?></title>
    <style type="text/css">
        .text {
           overflow: hidden;
           text-overflow: ellipsis;
           display: -webkit-box;
           -webkit-line-clamp: 2; /* number of lines to show */
                   line-clamp: 2; 
           -webkit-box-orient: vertical;
           min-height: 50px;
        }
        .img {
            min-height: 300px;
            width: 100%;
            object-fit: cover;
        }
    </style>
    <div class="card-title d-flex align-items-center mb-3">
        <div><i class="bx bx-store-alt me-1 font-22 text-primary"></i></div>
        <h5 class="col mb-0 text-primary">
            <?php
            echo $sproduct_type2 != 'all' ? "Package" : $l_product;
            if ($sproduct_type_id != 'all') {
                $query  = mysqli_query($connect, "SELECT * FROM system_product_type WHERE product_type_id = '$sproduct_type_id'");
                $data   = mysqli_fetch_array($query);
                echo  " | " . $data['product_type_name'];
            } 
            ?>
        </h5>

        <a href="admin.php?page=admin_product&action=insert_product" class="btn btn-primary btn-sm ms-auto"><?php echo $l_product_insert; ?></a>

    </div>
    <div class="row">
        <?php
        $where               = "";

        if ($action == 'search') {
            
            $where  = " WHERE (product_name LIKE '%$sproduct_name%') ";
                
            if ($sproduct_type_id != 'all')  { $where .= " AND (product_type = '$sproduct_type_id')"; }
            if ($sproduct_type2 != 'all')    { $where .= " AND (product_type2 = '$sproduct_type2')"; }
            
        }

        $limit = " LIMIT $start, 52 ";
        $sql   = "SELECT system_product.*, system_product_type.*
                FROM system_product
                INNER JOIN system_product_type ON (system_product.product_type = system_product_type.product_type_id)" . $where;
        $order_by = " ORDER BY product_id DESC ";
        $query = mysqli_query($connect, $sql . $order_by . $limit) or die(mysqli_error($connect));
        while ($data = mysqli_fetch_array($query)) { 

            $product_id             = $data['product_id'];
            $product_name           = $data['product_name'];
            $product_type_name      = $data['product_type_name'];
            $product_code           = $data['product_code'];
            $product_image_cover    = $data['product_image_cover'] != '' ? "assets/images/products/" . $data['product_image_cover'] : "assets/images/etc/example_product.png";
            $product_name           = $data['product_name'];
            $product_price_member   = number_format($data['product_price_member'], 2);
            $product_point          = number_format($data['product_point'], 2);
            $product_amount         = number_format($data['product_amount'], 2);

            $list_url   = "admin.php?page=admin_product&action=list_amount&product_id=$product_id";
            $plus_url   = "admin.php?page=admin_product&action=plus_amount&product_id=$product_id";
            $edit_url   = "admin.php?page=admin_product&action=edit_product&product_id=$product_id";
            $delete_url = "process/setting_product.php?action=delete_product&product_id=$product_id";
            ?>
            <div class="col-12 col-sm-3 product-grid">
                <div class="card p-0">
                    <img src="<?php echo $product_image_cover ?>" alt="Product Cover" class="img">
                    <div class="p-3">
                        <h5 class="text">
                        <?php echo "<a href='admin.php?page=admin_product&action=product_detail&product_id=$product_id'>$product_name</a>" ?>
                        </h5>
                        <p class="mb-2"><?php echo $product_type_name . " " . $product_code ?></p>
                        <table class="table table-borderless table-sm" >
                            <tbody>
                                <tr>
                                    <th><?php echo $l_price; ?></th>
                                    <td><?php echo $product_price_member . $l_bath ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo $l_point; ?></th>
                                    <td><?php echo $product_point . $point_name ?></td>
                                </tr>
                                <?php echo $system_stock == 1 ? "<tr><th>In Stock</th><td>$product_amount $l_piece</td></tr>" : false; ?>
                            </tbody>
                        </table>
                        <hr>
                        <div class="d-flex">
                            <?php if ($system_stock == 1) { ?>

                            <a href="<?php echo $list_url ?>" class="me-1 ms-auto"><i class="bx bx-layer me-1 font-22 text-primary"></i></a>
                            <a href="<?php echo $plus_url ?>" class="me-1"><i class="bx bx-layer-plus me-1 font-22 text-success"></i></a>
                            
                            <?php } ?>

                            <a href="<?php echo $edit_url ?>" class="me-1"><i class="bx bx-edit me-1 font-22 text-primary"></i></a>
                            <a href="<?php echo $delete_url ?>" onclick="javascript:return confirm('Confirm ?');"><i class="bx bx-trash me-1 font-22 text-danger"></i></a>

                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

    <?php 
    $url = "$page_type?page=admin_product&product_type_id=$sproduct_type_id&product_type2=$sproduct_type2&product_name=$sproduct_name&action=search";
    pagination ($connect, $sql, $perpage, $page_id, $url); ?>

    <!--start switcher-->
    <div class="switcher-wrapper">
        <div class="switcher-btn"> <i class='bx bx-search'></i>
        </div>
        <div class="switcher-body">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 text-uppercase"><?php echo $l_search; ?></h5>
                <button type="button" class="btn-close ms-auto close-switcher" aria-label="Close"></button>
            </div>
            <hr/>
            <form action="admin.php" method="get" class="row g-3">
                <input type="hidden" name="page" value="admin_product">
                <div class="col-12">
                    <label  class="form-label"><?php echo $l_product_type; ?></label>
                    <select class="form-select" name="product_type_id" required>
                        <option value="all" selected><?php echo $l_all; ?></option>
                        <?php 
                        $query  = mysqli_query($connect, "SELECT * FROM system_product_type");
                        while ($data = mysqli_fetch_array($query)) {
                            $product_type_id    = $data['product_type_id'];
                            $product_type_code  = $data['product_type_code'];
                            $product_type_name  = $data['product_type_name'];
                            echo "<option value='$product_type_id'>[$product_type_code] $product_type_name</option>";
                        } 
                        ?>
                    </select>
                </div>
                <?php if ($system_product_type2 == 1) { ?>
                <div class="col-12">
                    <label  class="form-label"><?php echo $l_product_type2; ?></label>
                    <select class="form-select" name="product_type2" required>
                        <option value="all" selected><?php echo $l_all; ?></option>
                        <option value="0">Default Product</option>
                        <option value="1">Special Product</option>
                    </select>
                </div>
                <?php } ?>
                <div class="col-12">
                    <label  class="form-label"><?php echo $l_product_name; ?></label>
                    <input type="text" name="product_name" class="form-control me-1" placeholder="<?php echo $l_product_name; ?>">
                </div>
                <div class="col-12 mt-5">
                    <button name="action" value="search" class="btn btn-primary btn-sm"><?php echo $l_save; ?></button>
                    <a href="admin.php?page=admin_product" class="btn btn-secondary btn-sm"><?php echo $l_cancel; ?></a>
                </div>
            </form>
        </div>
    </div>

<?php } elseif (isset($_GET['action']) && $_GET['action'] == 'insert_product') { ?>

    <title><?php echo $l_product_insert; ?></title>
    <div class="col-12 col-sm-9 mx-auto">
    <div class="card-title d-flex align-items-center mb-3">
        <div><i class="bx bx-plus me-1 font-22 text-primary"></i></div>
        <h5 class="mb-0 text-primary"><?php echo $l_product_insert; ?></h5>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page"><a href="admin.php?page=admin_product"><?php echo $l_product; ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $l_product_insert; ?></li>
        </ol>
    </nav>
    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body">
            <form class="row g-3" action="process/setting_product.php" method="post" enctype="multipart/form-data">
                <div class="col-12">
                    <label class="form-label"><?php echo $l_product_name; ?> <font color="red">*</font></label>
                    <input type="text" class="form-control" name="product_name" placeholder="<?php echo $l_product_name; ?>" required>
                </div>
                <div class="col-12 col-sm-4">
                    <label  class="form-label"><?php echo $l_product_type; ?> <font color="red">*</font></label>
                    <select class="form-select" name="product_type" required>
                        <?php 
                        $query  = mysqli_query($connect, "SELECT * FROM system_product_type");
                        while ($data = mysqli_fetch_array($query)) {
                            $product_type_id    = $data['product_type_id'];
                            $product_type_code  = $data['product_type_code'];
                            $product_type_name  = $data['product_type_name'];
                            echo "<option value='$product_type_id'>[$product_type_code] $product_type_name</option>";
                        } 
                        ?>
                    </select>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label"><?php echo $l_product_code; ?> <font color="red">*</font></label>
                    <input type="text" class="form-control" name="product_code" placeholder="<?php echo $l_product_code; ?>" required>
                </div>
                <div class="col-12 col-sm-4">
                    <label  class="form-label"><?php echo $l_product_type2; ?></label>
                    <select class="form-select" name="product_type2" <?php echo $system_product_type2 == 0 ? "disabled": false; ?>>
                        <option value="0" selected>Default Product</option>
                        <option value="1">Special Product</option>
                    </select>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label"><?php echo $l_price; ?> <font color="red">*</font></label>
                    <input type="number" class="form-control" name="product_price" placeholder="<?php echo $l_price; ?>" required 
                    <?php echo $system_webpage == 0 ? " value='0' readonly " : false;?>>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label"><?php echo $l_pricemem; ?> <font color="red">*</font></label>
                    <input type="number" class="form-control" name="product_price_member" placeholder="<?php echo $l_pricemem; ?>" required>
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label"><?php echo $l_fright; ?> <font color="red">*</font></label>
                    <input type="number" class="form-control" name="product_freight" placeholder="<?php echo $l_fright; ?>" required <?php echo $system_address != 2 ? " value='0' readonly " : false;?>>
                </div>
                <div class="col-12 col-sm-6">
                    <label class="form-label"><?php echo $l_point; ?> <font color="red">*</font></label>
                    <input type="number" class="form-control"name="product_point" id="product_point" placeholder="<?php echo $l_point; ?>" required>
                </div>
                <div class="col-12 col-sm-6">
                    <label class="form-label"><?php echo $l_quantity; ?> <font color="red">*</font></label>
                    <input type="number" class="form-control" name="product_amount" placeholder="<?php echo $l_quantity; ?>" required <?php echo $system_stock == 0 ? " value='0' readonly " : false;?>>
                </div>
                <div class="col-12 col-sm-6">
                    <label for="formFile" class="form-label"><?php echo $l_product_imagecover; ?></label>
                    <input class="form-control" type="file" name="product_image_cover">
                </div>
                <div class="col-12 col-sm-6">
                    <label for="formFile" class="form-label"><?php echo $l_product_image; ?> (max 5 files)</label>
                    <input class="form-control" type="file" name="product_image[]" id="image" multiple>
                </div>
                <div class="col-12 ">
                    <label class="form-label"><?php echo $l_descrip; ?></label>
                    <textarea class="form-control" id="editor" name="product_detail"></textarea>
                </div>        
                <div class="col-12 mt-5">
                    <button name="action" value="insert_product" class="btn btn-success"><?php echo $l_save; ?></button>
                    <a href="admin.php?page=admin_product" class="btn btn-secondary"><?php echo $l_cancel; ?></a>
                </div>
            </form>
        </div>
    </div>
    </div>
    <script type="text/javascript">
        $(function() {
            $("#image").on("change", function() {
                if ($("#image")[0].files.length > 5) {
                    alert("Maximum Upload is 5 files");
                    $("#image").val("");
                } 
            });
        });
    </script>

<?php } elseif (isset($_GET['action']) && $_GET['action'] == 'edit_product') {
    
    $product_id = $_GET['product_id'];
    $query  = mysqli_query($connect, "SELECT system_product.*, system_product_type.*
            FROM system_product
            INNER JOIN system_product_type ON (system_product.product_type = system_product_type.product_type_id)
            WHERE product_id = '$product_id'") or die(mysql_error($connect));
    $data = mysqli_fetch_array($query); 

    $product_name       = $data['product_name'];
    $product_code       = $data['product_code'];
    $product_type       = $data['product_type'];
    $product_type2      = $data['product_type2'];
    $product_detail     = $data['product_detail'];
    $product_price      = $data['product_price'];
    $product_price_member = $data['product_price_member'];
    $product_point      = $data['product_point'];
    $product_freight    = $data['product_freight'];

    ?>
    <title><?php echo $l_product_edit; ?></title>
    <div class="card-title d-flex align-items-center mb-3">
        <div><i class="bx bx-edit me-1 font-22 text-primary"></i></div>
        <h5 class="mb-0 text-primary"><?php echo $l_product_edit; ?></h5>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page"><a href="admin.php?page=admin_product"><?php echo $l_product; ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $l_product_edit; ?></li>
        </ol>
    </nav>
    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body p-1 p-sm-5">
            <form class="row g-3" action="process/setting_product.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="product_id" value="<?php echo $product_id ?>">
                <div class="col-12 col-sm-8">
                    <div class="card border border-success">
                        <div class="card-body row">
                            <h6 class="mb-3"><?php echo $l_detail; ?></h6>
                            <div class="col-12 col-sm-6 mt-3">
                                <label class="form-label"><?php echo $l_product_name; ?> <font color="red">*</font></label>
                                <input type="text" class="form-control" name="product_name" value="<?php echo $product_name ?>" required>
                            </div>
                            <div class="col-12 col-sm-6 mt-3">
                                <label  class="form-label"><?php echo $l_product_type; ?> <font color="red">*</font></label>
                                <select class="form-select" name="product_type" required>
                                    <?php 
                                    $query  = mysqli_query($connect, "SELECT * FROM system_product_type");
                                    while ($data = mysqli_fetch_array($query)) {
                                        $product_type_id    = $data['product_type_id'];
                                        $product_type_code  = $data['product_type_code'];
                                        $product_type_name  = $data['product_type_name'];
                                        echo "<option value='$product_type_id'>[$product_type_code] $product_type_name</option>";
                                    } 
                                    ?>
                                </select>
                            </div>
                            <div class="col-12 col-sm-6 mt-3">
                                <label class="form-label"><?php echo $l_product_code; ?> <font color="red">*</font></label>
                                <input type="text" class="form-control" name="product_code" value="<?php echo $product_code ?>" required>
                            </div>
                            <div class="col-12 col-sm-6 mt-3">
                                <label  class="form-label"><?php echo $l_product_type2; ?> <font color="red">*</font></label>
                                <select class="form-select" name="product_type2" <?php echo $system_product_type2 == 0 ? "disabled": false; ?>>
                                    <option value="0" <?php echo $product_type2 == 0 ? "selected" : false; ?>>Default Product</option>
                                    <option value="1" <?php echo $product_type2 == 1 ? "selected" : false; ?>>Special Product</option>
                                </select>
                            </div>
                            <div class="col-12 mt-3">
                                <label class="form-label"><?php echo $l_descrip; ?></label>
                                <textarea class="form-control" id="editor" name="product_detail"><?php echo $product_detail ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-4">
                    <div class="card border border-success">
                        <div class="card-body row">
                            <h6 class="mb-3"><?php echo $l_product_pnp; ?></h6>
                            <div class="col-12 col-sm-6 mt-3">
                                <label class="form-label"><?php echo $l_price; ?> <font color="red">*</font></label>
                                <input type="number" class="form-control" name="product_price" value="<?php echo $product_price ?>" required <?php echo $system_webpage == 0 ? " value='0' readonly " : false;?>>
                            </div>
                            <div class="col-12 col-sm-6 mt-3">
                                <label class="form-label"><?php echo $l_pricemem; ?> <font color="red">*</font></label>
                                <input type="number" class="form-control" name="product_price_member" value="<?php echo $product_price_member ?>" required>
                            </div>
                            <div class="col-12 col-sm-6 mt-3">
                                <label class="form-label"><?php echo $l_point; ?> <font color="red">*</font></label>
                                <input type="number" class="form-control"name="product_point" value="<?php echo $product_point ?>" required>
                            </div>
                            <div class="col-12 col-sm-6 mt-3">
                                <label class="form-label"><?php echo $l_fright; ?> <font color="red">*</font></label>
                                <input type="number" class="form-control" name="product_freight" value="<?php echo $product_freight ?>" required <?php echo $system_address != 2 ? " value='0' readonly " : false; ?> >
                            </div>
                        </div>
                    </div>
                    <div class="card border border-success">
                        <div class="card-body row">
                            <a href="admin.php?page=admin_product&action=edit_image&product_id=<?php echo $product_id ?>"><?php echo $l_product_image_mana; ?></a>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <button name="action" value="edit_product" class="btn btn-success"><?php echo $l_save; ?></button>
                    <a href="admin.php?page=admin_product" class="btn btn-secondary"><?php echo $l_cancel; ?></a>
                </div>
            </form>
        </div>
    </div>

<?php } elseif (isset($_GET['action']) && $_GET['action'] == 'plus_amount') { 

    $product_id = $_GET['product_id'];
    $sql  = mysqli_query($connect, "SELECT system_product.*, system_product_type.*, system_product_amount.*
            FROM system_product
            INNER JOIN system_product_type   ON (system_product.product_type = system_product_type.product_type_id)
            INNER JOIN system_product_amount ON (system_product.product_id   = system_product_amount.product_amount_product)
            WHERE product_id = '$product_id'");
    $data = mysqli_fetch_array($sql); ?>
    <title>เพิ่มจำนวนสินค้า</title>
    <div class="col-12 col-sm-6 mx-auto">
    <div class="card-title d-flex align-items-center">
        <div><i class="bx bx-layer-plus me-1 font-22 text-primary"></i></div>
        <h5 class="mb-0 text-primary">เพิ่มจำนวนสินค้า</h5>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page"><a href="admin.php?page=admin_product">สินค้า</a></li>
            <li class="breadcrumb-item active" aria-current="page">เพิ่มจำนวนสินค้า</li>
        </ol>
    </nav>
    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body p-1 p-sm-5">

            <form class="row g-3" action="process/setting_product.php" method="post">
                <input type="hidden" name="product_id" value="<?php echo $product_id ?>">
                <table class="table table-borderless">
                    <tr>
                        <th>หมวดสินค้า</th>
                        <td><?php echo $data['product_type_name'] ?></td>
                    </tr>
                    <tr>
                        <th>รหัสสินค้า</th>
                        <td><?php echo $data['product_code'] ?></td>
                    </tr>
                    <tr>
                        <th>ชื่อสินค้า</th>
                        <td><?php echo $data['product_name'] ?></td>
                    </tr>
                    <tr>
                        <th>จำนวนสินค้าคงเหลือ</th>
                        <td><?php echo number_format($data['product_amount']) ?> ชิ้น</td>
                    </tr>
                    <tr>
                        <th>จำนวนที่เพิ่ม</th>
                        <td><input type="number" name="product_amount_new" class="form-control" size="20" placeholder="จำนวนสินค้า" required></td>
                    </tr>
                </table>
                <div class="col-12">
                    <button type="submit" class="btn btn-success btn-sm" name="action" value="plus_amount" >เพิ่มจำนวนสินค้า</button>
                    <a href="admin.php?page=admin_product" class="btn btn-secondary btn-sm">ยกเลิก</a>
                </div>
            </form>
        </div>
    </div>
    </div>

<?php } elseif (isset($_GET['action']) && $_GET['action'] == 'list_amount') { 
    $product_id = $_GET['product_id'];
    $query      = mysqli_query($connect, "SELECT * FROM system_product WHERE product_id = '$product_id'");
    $data       = mysqli_fetch_array($query);
    ?>

    <title>ประวัติการเพิ่มสินค้า</title>
    <div class="col-12 col-sm-6 mx-auto">
    <div class="card-title d-flex align-items-center">
        <div><i class="bx bx-layer me-1 font-22 text-primary"></i></div>
        <h5 class="mb-0 text-primary">ประวัติการเพิ่มสินค้า</h5>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page"><a href="admin.php?page=admin_product">สินค้า</a></li>
            <li class="breadcrumb-item active" aria-current="page">ประวัติการเพิ่มสินค้า</li>
        </ol>
    </nav>
    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body p-1 p-sm-5">
            <table class="table table-sm table-borderless">
                <tbody>
                    <tr>
                        <th width=120>ชื่อสินค้า</th>
                        <td><?php echo $data['product_name'] ?></td>
                    </tr>
                    <tr>
                        <th>คงเหลือ</th>
                        <td><?php echo number_format($data['product_amount']) ?></td>
                    </tr>
                </tbody>
            </table>
            <table class="table mb-0 text-center align-middle" >
                    <thead class="table-light">
                    <tr>
                        <th>ลำดับ</th>
                        <th>จำนวนเดิม</th>
                        <th>จำนวนที่เพิ่ม</th>
                        <th>วันที่เพิ่ม</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql_amount  = mysqli_query($connect, "SELECT system_product.*, system_product_amount.*
                        FROM system_product
                        INNER JOIN system_product_amount ON (system_product.product_id   = system_product_amount.product_amount_product)
                        WHERE product_id = '$product_id'
                        ORDER BY product_amount_id ASC");
                    $i = 0;
                    while ($data_amount = mysqli_fetch_array($sql_amount)) { 
                        $i++;
                        ?>
                        <tr>
                            <td><?php echo $i ?></td>
                            <td><?php echo number_format($data_amount['product_amount_old']) ?></td>
                            <td><?php echo number_format($data_amount['product_amount_new']) ?></td>
                            <td><?php echo datethai($data_amount['product_amount_create'], 0) ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    </div>  

<?php } elseif (isset($_GET['action']) && $_GET['action'] == 'product_detail') { 
    
    $product_id    = isset($_GET['product_id']) ? $_GET['product_id'] : false;
    $query = mysqli_query($connect, "SELECT system_product.*, system_product_type.*
        FROM system_product
        INNER JOIN system_product_type ON (system_product.product_type = system_product_type.product_type_id)
        WHERE product_id = '$product_id' ");
    $data  = mysqli_fetch_array($query);

    $product_name       = $data['product_name'];
    $product_code       = $data['product_code'];
    $product_detail     = $data['product_detail'];
    $product_price_member = number_format($data['product_price_member'], 2);
    $product_price      = number_format($data['product_price'], 2);
    $product_point      = number_format($data['product_point'], 2);
    $product_freight    = number_format($data['product_freight'], 2);
    $product_amount     = number_format($data['product_amount'], 2);
    $product_image_cover= $data['product_image_cover'] != '' ? "assets/images/products/" . $data['product_image_cover'] : "assets/images/etc/example_product.png";
    $product_type_name  = $data['product_type_name'];
    ?>
    <title><?php echo $product_name ?></title>
    <div class="col-12 col-sm-8 mx-auto">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page"><a href="admin.php?page=admin_product"><?php echo $l_product ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $l_detail ?></li>
        </ol>
    </nav>
    <div class="card">
        <div class="card-body">
        <div class="row g-3">
            <div class="col-12 col-sm-5">
                <img src="<?php echo $product_image_cover ?>" alt="ปกสินค้า" class="img-thumbnail">
            </div>
            <div class="col-12 col-sm-7">
                <h4><?php echo $data['product_name'] ?></h4>
                <div class="d-flex gap-3 py-3">
                    <div><?php echo $product_type_name . " " . $product_code ?></div>
                    <!--
                    <div class="text-success"><i class='bx bxs-cart-alt align-middle'></i> 134 orders</div>
                    -->
                </div>
                <div class="mb-3"> 
                    <span class="price h4"> ฿ <?php echo $product_price_member ?></span> 
                    <span class="text-muted"> (<?php echo $product_name ?>)</span> 
                </div>
                <p class="card-text fs-6"><?php echo $l_product_text ?></p>
                <dl class="row">
                    <dt class="col-sm-3"><?php echo $l_pricemem ?></dt>
                    <dd class="col-sm-9"><?php echo $product_price . $l_bath ?></dd>
                    <?php if ($product_freight > 0) { ?>
                    <dt class="col-sm-3"><?php echo $l_fright ?></dt>
                    <dd class="col-sm-9"><?php echo $product_freight . $l_bath ?></dd>
                    <?php } ?>
                    <dt class="col-sm-3"><?php echo $l_point ?></dt>
                    <dd class="col-sm-9"><?php echo $product_point . $point_name ?></dd>
                    <?php if ($system_stock == 1) { ?>
                    <dt class="col-sm-3"><?php echo $l_quantity ?></dt>
                    <dd class="col-sm-9"><?php echo $product_amount . $l_piece ?></dd>
                    <?php } ?>
                </dl>
            </div>
            <?php if ($product_detail != '') { ?>
                <hr>
                <div class="col-12">
                    <h6><?php echo $lang_all_descrip ?></h6>
                    <p><?php echo $product_detail ?></p>
                </div>
            <?php } ?>
        </div>
        </div>
    </div>
    </div>

<?php } elseif (isset($_GET['action']) && $_GET['action'] == 'edit_image') { 

    $product_id = $_GET['product_id'];
    $query      = mysqli_query($connect, "SELECT system_product.*, system_product_type.*
        FROM system_product
        INNER JOIN system_product_type ON (system_product.product_type = system_product_type.product_type_id)
        WHERE product_id = '$product_id'") or die(mysqli_error($connect));
    $data       = mysqli_fetch_array($query);
    ?>
    <title>จัดการรูปภาพ</title>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page"><a href="admin.php?page=admin_product">สินค้า</a></li>
            <li class="breadcrumb-item" aria-current="page"><a href="admin.php?page=admin_product&action=edit_product&product_id=<?php echo $_GET['product_id'] ?>">แก้ไขสินค้า</a></li>
            <li class="breadcrumb-item active" aria-current="page">แก้ไขรูปภาพ</li>
        </ol>
    </nav>
    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body p-1 p-sm-5">
            <div class="card-title d-flex align-items-center">
                <div><i class="bx bx-edit me-1 font-22 text-primary"></i>
                </div>
                <h5 class="mb-0 text-primary">แก้ไขรูปภาพ</h5>
            </div>
            <hr>
            <div class="row">
            <?php if ($data['product_image_cover'] != '') { ?>
                <div class="col-12 col-sm-4 position-relative p-3">
                <a href="assets/images/products/<?php echo $data['product_image_cover'] ?>" target="_blank">
                    <img src="assets/images/products/<?php echo $data['product_image_cover'] ?>" class="border border-5 border-warning" alt="ปกสินค้า" style="width:100%;height:350px;object-fit: cover;">
                </a>
                <div class="position-absolute top-0 start-0 m-4">
                    <p class="text-white">รูปปก</p>
                </div>
                <div class="position-absolute top-0 end-0 m-4">
                    <a href="process/setting_product.php?action=delete_image&image=cover&product_id=<?php echo $_GET['product_id'] ?>">
                        <i class="bx bx-trash font-30 text-danger"></i>
                    </a>
                </div>
                </div>
            <?php } else { ?>
                <div class="col-12 col-sm-4 position-relative p-3">
                <form action="process/setting_product.php" method="post" class="position-relative" id="image_cover" enctype="multipart/form-data">
                    <input type="hidden" name="product_id" value="<?php echo $_GET['product_id'] ?>">
                    <input type="hidden" name="image" value="cover">
                    <input type="hidden" name="action" value="edit_image">
                    <label for="file-input">
                        <img src="assets/images/bg-themes/1.png" class="border border-5 border-warning" style="width:100%;height:350px;object-fit: cover;">
                        <h2 class="position-absolute top-50 start-50 translate-middle text-white">อัปโหลดรูปปก</h2>
                    </label>
                    <input id="file-input" type="file" name="product_image" style="display: none;" onchange="submitform('image_cover')">
                </form>
                </div>
            <?php } 
                for ($i = 1; $i < 6; $i++) { 
                if ($data['product_image_' . $i] != '') { ?>
                    <div class="col-12 col-sm-4 position-relative p-3">
                    <a href="assets/images/products/<?php echo $data['product_image_' . $i] ?>" target="_blank">
                        <img src="assets/images/products/<?php echo $data['product_image_' . $i] ?>" alt="ปกสินค้า" style="width:100%;height:350px;object-fit: cover;">
                    </a>
                    <div class="position-absolute top-0 start-0 m-4">
                        <p class="text-white">รูปประกอบ <?php echo $i ?></p>
                    </div>
                    <div class="position-absolute top-0 end-0 m-4">
                        <a href="process/setting_product.php?action=delete_image&image=<?php echo $i ?>&product_id=<?php echo $_GET['product_id'] ?>">
                            <i class="bx bx-trash font-30 text-danger"></i>
                        </a>
                    </div>
                    </div>
                <?php } else { ?> 
                    <div class="col-12 col-sm-4 position-relative p-3">
                    <form action="process/setting_product.php" method="post" class="position-relative" id="<?php echo $i ?>" enctype="multipart/form-data">
                        <input type="hidden" name="product_id" value="<?php echo $_GET['product_id'] ?>">
                        <input type="hidden" name="image" value="<?php echo $i ?>">
                        <input type="hidden" name="action" value="edit_image">
                        <label for="file-input">
                            <img src="assets/images/bg-themes/2.png" style="width:100%;height:350px;object-fit: cover;">
                            <h2 class="position-absolute top-50 start-50 translate-middle text-white">อัปโหลดรูปปก</h2>
                        </label>
                        <input id="file-input" type="file" name="product_image" style="display: none;" onchange="submitform('<?php echo $i ?>')">
                    </form>
                    </div>
                <?php } } ?>
                </div>
            </div>
        </div>
    </div>
    <script>
        function submitform(id) {
            var a = document.getElementById(id).submit();
        }
    </script>

<?php } ?>