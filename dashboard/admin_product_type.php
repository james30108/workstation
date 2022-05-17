<title><?php echo $l_product_type; ?></title>
<div class="col-12 col-sm-7 mx-auto mb-5">
<?php isset($_GET["status"]) ? alert ($_GET["status"], $_GET["message"], $lang) : false; ?>
<div class="card-title d-flex align-items-center mb-3">
    <div><i class="bx bx-store-alt me-1 font-22 text-primary"></i></div>
    <h5 class="col mb-0 text-primary"><?php echo $l_product_type; ?></h5>
    <button type="button" data-bs-toggle="modal" data-bs-target="#insert" class="btn btn-primary btn-sm ms-auto"><?php echo $l_producttype_insert; ?></button>
</div>
<div class="card border-top border-0 border-4 border-primary">
    <div class="card-body">
        <div class="table-responsive">
        <table class="table mb-0 text-center align-middle" >
            <thead class="table-light">
            <tr>
                <th>#</th>
                <th><?php echo $l_code; ?></th>
                <th><?php echo $l_name; ?></th>
                <th><?php echo $l_manage; ?></th>
            </tr>
        </thead>
        <tbody>
		<?php 
        $query  = mysqli_query($connect, "SELECT * FROM system_product_type");
        $empty  = mysqli_num_rows($query);
        if ($empty > 0) {
        $i = 0;
        while ($data = mysqli_fetch_array($query)) { 
        	$i++;

            $product_type_id  = $data['product_type_id'];
            $product_type_code= $data['product_type_code'];
            $product_type_name= $data['product_type_name'];
        	?>
        	<tr>
        		<td><?php echo $i ?></td>
        		<td><?php echo $product_type_code ?></td>
        		<td><?php echo $product_type_name ?></td>
        		<td>

        			<button type="button" data-bs-toggle="modal" data-bs-target="<?php echo "#edit_$product_type_id" ?>" class="me-1 bg-transparent border-0"><i class="bx bx-edit me-1 font-22 text-primary"></i></button>

                    <a href="process/setting_product.php?action=delete_product_type&product_type_id=<?php echo $product_type_id ?>"onclick="javascript:return confirm('กดตกลงเพื่อยืนยันการทำรายการ');"><i class="bx bx-trash me-1 font-22 text-primary"></i></a>
        		
                </td>
        	</tr>

        	<!-- Modal -->
			<div class="modal fade" id="<?php echo "edit_$product_type_id" ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			    <div class="modal-dialog">
			        <div class="modal-content">
			            <div class="modal-header">
			            <h5 class="modal-title" id="exampleModalLabel">
			                <i class="bx bx-edit me-1 font-22 text-primary"></i>
			                <?php echo $l_edit ." (". $product_type_name . ")" ?></h5>
			            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			            </div>
			            <div class="modal-body text-start">
			                <form class="row g-3" action="process/setting_product.php" method="post">
			                	<input type="hidden" name="product_type_id" value="<?php echo $product_type_id ?>">
			                    <div class="col-12 mt-3">
			                        <label  class="form-label"><?php echo $l_code ?> <font color="red">*</font></label>
			                        <input type="text" class="form-control" name="product_type_code" value="<?php echo $product_type_code ?>" placeholder="<?php echo $l_code ?>">
			                    </div>
			                    <div class="col-12 mt-3">
			                        <label  class="form-label"><?php echo $l_name ?> <font color="red">*</font></label>
			                        <input type="text" class="form-control" name="product_type_name" value="<?php echo $product_type_name ?>" placeholder="<?php echo $l_name ?>">
			                    </div>
			                    <div class="col-12 mt-3">
			                        <button name="action" value="edit_product_type" class="btn btn-primary btn-sm"><?php echo $l_save ?></button>
			                    </div>
			                </form>
			            </div>
			        </div>
			    </div>
			</div>
        <?php } } else { echo "<tr><td colspan='4'>$l_notfound</td></tr>"; } ?>
    </div>
</div>
</div>

<!-- Modal -->
<div class="modal fade" id="insert" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">
                <i class="bx bx-edit me-1 font-22 text-primary"></i>
                <?php echo $l_producttype_insert ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start">
                <form class="row g-3" action="process/setting_product.php" method="post">
                    <div class="col-12 mt-3">
                        <label  class="form-label"><?php echo $l_code ?> <font color="red">*</font></label>
                        <input type="text" class="form-control" name="product_type_code" placeholder="<?php echo $l_code ?>">
                    </div>
                    <div class="col-12 mt-3">
                        <label  class="form-label"><?php echo $l_name ?> <font color="red">*</font></label>
                        <input type="text" class="form-control" name="product_type_name" placeholder="<?php echo $l_name ?>">
                    </div>
                    <div class="col-12 mt-3">
                        <button name="action" value="insert_product_type" class="btn btn-primary btn-sm"><?php echo $l_save ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>