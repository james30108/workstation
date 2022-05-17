<title><?php echo $l_thread_type ?></title>
<div class="col-12 col-sm-9 mx-auto">

    <?php isset($_GET["status"]) ? alert ($_GET["status"], $_GET["message"], $lang) : false; ?>

    <div class="card-title d-flex align-items-center mb-3">

        <div><i class="bx bx-chalkboard me-1 font-22 text-primary"></i></div>
        <h5 class="col mb-0 text-primary"><?php echo $l_thread_type ?></h5>

        <button type="button" data-bs-toggle="modal" data-bs-target="#insert" class="btn btn-primary btn-sm ms-auto"><?php echo $l_thread_tyin ?></button>
        
    </div>
    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body">
            <div class="table-responsive">
            <table class="table mb-0 text-center align-middle" >
                <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th><?php echo $l_name ?></th>
                    <th><?php echo $l_thread_number ?></th>
                    <th><?php echo $l_manage ?></th>
                </tr>
            </thead>
            <tbody>
			<?php 
            $query  = mysqli_query($connect, "SELECT * FROM system_thread_type");
            $empty  = mysqli_num_rows($query);
            if ($empty > 0) {
            $i = 0;
            while ($data = mysqli_fetch_array($query)) { 
            	
                $thread_type_name   = $data['thread_type_name'];
                $thread_type_count  = $data['thread_type_count'];
                $thread_type_id     = $data['thread_type_id'];

                $thread_url         = "process/setting_thread.php?action=delete_thread_type&thread_type_id=$thread_type_id";

                $i++;
            	?>
            	<tr>
            		<td><?php echo $i ?></td>
            		<td><?php echo $thread_type_name ?></td>
            		<td><?php echo $thread_type_count ?></td>
            		<td>
            			<button type="button" data-bs-toggle="modal" data-bs-target="#edit_<?php echo $thread_type_id ?>" class="me-1 border-0 bg-transparent"><i class="bx bx-edit me-1 font-22 text-primary"></i></button>

                        <a href="<?php echo $thread_url ?>"onclick="javascript:return confirm('Delete ?');"><i class="bx bx-trash me-1 font-22 text-primary"></i></a>
            		</td>
            	</tr>

            	<!-- Modal -->
				<div class="modal fade" id="edit_<?php echo $thread_type_id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
				    <div class="modal-dialog">
				        <div class="modal-content">
				            <div class="modal-header">
				            <h5 class="modal-title" id="exampleModalLabel">
				                <i class="bx bx-edit me-1 font-22 text-primary"></i>
                                <?php echo "$l_edit ($thread_type_name)" ?>
                            </h5>
				            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				            </div>
				            <div class="modal-body text-start">
				                <form class="row g-3" action="process/setting_thread.php" method="post">
				                	<input type="hidden" name="thread_type_id" value="<?php echo $thread_type_id ?>">
				                    <div class="col-12 mt-3">
				                        <label  class="form-label"><?php echo $l_name ?> <font color="red">*</font></label>
				                        <input type="text" class="form-control" name="thread_type_name" value="<?php echo $thread_type_name ?>" placeholder="<?php echo $l_name ?>">
				                    </div>
				                    <div class="col-12 mt-3">
				                        <button name="action" value="edit_thread_type" class="btn btn-primary btn-sm"><?php echo $l_save ?></button>
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
                <?php echo $l_thread_tyin ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start">
                <form class="row g-3" action="process/setting_thread.php" method="post">
                    <div class="col-12 mt-3">
                        <label  class="form-label"><?php echo $l_name ?> <font color="red">*</font></label>
                        <input type="text" class="form-control" name="thread_type_name" placeholder="<?php echo $l_name ?>">
                    </div>
                    <div class="col-12 mt-3">
                        <button name="action" value="insert_thread_type" class="btn btn-primary btn-sm"><?php echo $l_save ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>