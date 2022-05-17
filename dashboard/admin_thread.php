<?php isset($_GET["status"]) ? alert ($_GET["status"], $_GET["message"], $lang) : false;

if (!isset($_GET['action']) || $_GET['action'] == 'search') { ?>
    
    <style type="text/css">
        .image {
            width: 100px;
            height: 100px;
            object-fit: cover;
        }
    </style>
    <title><?php echo $l_thread ?></title>
    <div class="card-title d-flex align-items-center mb-3">
        <div><i class="bx bx-chalkboard me-1 font-22 text-primary"></i></div>
        <h5 class="mb-0 text-primary"><?php echo $l_thread ?></h5>
        <a href="admin.php?page=admin_thread&action=insert_thread" class="btn btn-primary ms-auto btn-sm"><?php echo $l_thread_insert ?></a>
    </div>
    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body">
            <div class="table-responsive">
            <table class="table text-center align-middle mb-3">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th><?php echo $l_image_cover ?></th>
                        <th><?php echo $l_title ?></th>
                        <th><?php echo $l_create ?></th>
                        <th><?php echo $l_manage ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql        = "SELECT * FROM system_thread";
                    $order_by   = " ORDER BY thread_id DESC ";
                    $query      = mysqli_query($connect, $sql . $order_by . $limit) or die(mysqli_error($connect)); 
                    $empty      = mysqli_num_rows($query); 
                    $i          = 0;
                    if ($empty > 0) {
                        while ($data = mysqli_fetch_array($query)) { 

                            $thread_id      = $data['thread_id'];
                            $thread_image   = $data['thread_image'];
                            $thread_create  = datethai($data['thread_create'], 0, $lang);
                            $thread_title   = mb_strimwidth($data['thread_title'], 0, 70, "...");

                            $thread_url     = "admin.php?page=admin_thread&action=show_thread&thread_id=$thread_id";
                            $edit_url       = "admin.php?page=admin_thread&action=edit_thread&thread_id=$thread_id";
                            $delete_url     = "process/setting_thread.php?action=delete_thread&thread_id=$thread_id&thread_image=$thread_image";
                            $image_cover    = "assets/images/thread/$thread_image";

                            $i++;
                            ?>
                            <tr>
                                <td><?php echo $i + $start ?></td>
                                <td><img src="<?php echo $image_cover ?>" alt="Image Cover" class="image"></td>
                                <td><a href="<?php echo $thread_url ?>" target="_blank"><?php echo $thread_title ?></a></td>
                                <td><?php echo $thread_create ?></td>
                                <td>
                                    
                                    <a href="<?php echo $edit_url ?>"><i class="bx bx-edit me-1 font-22 text-primary"></i></a>
                                    
                                    <a href="<?php echo $delete_url ?>" onclick="javascript:return confirm('Delete ?');"><i class="bx bx-trash me-1 font-22 text-primary"></i></a>
                                </td>
                            </tr>
                    <?php } } else { echo "<tr><td colspan='6'>$l_notfound</td></tr>"; } ?>
                </tbody>
            </table>
            </div>
            <?php 
            $url = "admin.php?page=admin_thread";
            pagination ($connect, $sql, $perpage, $page_id, $url); ?>
        </div>
    </div>

<?php } elseif (isset($_GET['action']) && $_GET['action'] == 'insert_thread') { ?>

    <title><?php echo $l_thread_insert ?></title>
    <div class="card-title d-flex align-items-center">
        <div><i class="bx bx-chalkboard me-1 font-22 text-primary"></i></div>
        <h5 class="mb-0 text-primary"><?php echo $l_thread_insert ?></h5>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page"><a href="admin.php?page=admin_thread"><?php echo $l_thread ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $l_thread_insert ?></li>
        </ol>
    </nav>
    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body">
            <form class="row g-3" action="process/setting_thread.php" method="post" enctype="multipart/form-data">
                <div class="col-12">
                    <label class="form-label"><?php echo $l_title ?> <font color="red">*</font></label>
                    <input type="text" class="form-control" name="thread_title" placeholder="<?php echo $l_title ?>" required>
                </div>
                <div class="col-12 col-sm-6">
                    <label class="form-label"><?php echo $l_thread_type ?> <font color="red">*</font></label>
                    <select name="thread_type" class="form-select" require>
                        <option value=""><?php echo $l_thread_type ?></option>
                        <?php 
                        $query  = mysqli_query($connect, "SELECT * FROM system_thread_type");
                        while ($data = mysqli_fetch_array($query)) {

                            $thread_type_id   = $data['thread_type_id'];
                            $thread_type_name = $data['thread_type_name'];

                            echo "<option value='$thread_type_id'>$thread_type_name</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-12 col-sm-6">
                    <label class="form-label"><?php echo $l_image_cover ?> <font color="red">*</font></label>
                    <input type="file" class="form-control" name="thread_image">
                </div>
                <div class="col-12">
                    <label class="form-label"><?php echo $l_detail ?> <font color="red">*</font></label>
                    <textarea type="file" id="editor" name="thread_detail"></textarea>
                </div>
                <div class="col-12 mt-5">
                    <button name="action" value="insert_thread" class="btn btn-primary"><?php echo $l_save ?></button>
                </div>
            </form>
        </div>
    </div>

<?php } elseif (isset($_GET['action']) && $_GET['action'] == 'edit_thread') { 

    $query = mysqli_query($connect, "SELECT * FROM system_thread WHERE thread_id = '".$_GET['thread_id']."' ");
    $data  = mysqli_fetch_array($query);

    $thread_id      = $data['thread_id'];
    $thread_image   = $data['thread_image'];
    $thread_title   = $data['thread_title'];
    $thread_detail  = $data['thread_detail'];
    $thread_type    = $data['thread_type'];
    ?>
    <title><?php echo $l_thread_edit ?></title>
    <style type="text/css">
        ,image {
            width: 100%;
            height: 300px;
            object-fit:cover;
        }
    </style>
    <div class="col-8 mx-auto">
        <div class="card-title d-flex align-items-center">
            <div><i class="bx bx-chalkboard me-1 font-22 text-primary"></i></div>
            <h5 class="mb-0 text-primary"><?php echo $l_thread_edit ?></h5>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item" aria-current="page"><a href="admin.php?page=admin_thread"><?php echo $l_thread ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $l_thread_edit ?></li>
            </ol>
        </nav>
        <div class="card border-top border-0 border-4 border-primary">
            <div class="card-body p-1 p-sm-5">
                <form class="row g-3 mt-3" action="process/setting_thread.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="thread_id" value="<?php echo $thread_id ?>">
                    <input type="hidden" name="thread_image" value="<?php echo $thread_image ?>">
                    <img src="assets/images/thread/<?php echo $thread_image ?>" alt="image Cover" class="rounded image">
                    <div class="col-12">
                        <label class="form-label"><?php echo $l_thread_edit ?> <font color="red">*</font></label>
                        <input type="text" class="form-control" name="thread_title" value="<?php echo $thread_title ?>" required>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label"><?php echo $l_type ?> <font color="red">*</font></label>
                        <select name="thread_type" class="form-select" require>
                            <option value=""><?php echo $l_type ?></option>
                            <?php 

                            $query  = mysqli_query($connect, "SELECT * FROM system_thread_type");
                            while ($data = mysqli_fetch_array($query)) {

                                $thread_type_id     = $data['thread_type_id'];
                                $thread_type_name   = $data['thread_type_name'];

                                echo "<option value='$thread_type_id'"; 
                                if ($thread_type_id == $thread_type){ echo "selected"; }
                                echo ">$thread_type_name</option>";
                            }

                            ?>
                        </select>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label"><?php echo $l_image_cover ?> <font color="red">*</font></label>
                        <input type="file" class="form-control" name="thread_image_new">
                    </div>
                    <div class="col-12">
                        <label class="form-label"><?php echo $l_detail ?> <font color="red">*</font></label>
                        <textarea id="editor" name="thread_detail"><?php echo $thread_detail ?></textarea>
                    </div>
                    <div class="col-12 mt-5">
                        <button name="action" value="edit_thread" class="btn btn-primary"><?php echo $l_save ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php } elseif (isset($_GET['action']) && $_GET['action'] == 'show_thread') { 

    $query = mysqli_query($connect, "SELECT * FROM system_thread WHERE thread_id = '".$_GET['thread_id']."' ");
    $data  = mysqli_fetch_array($query);
    ?>
    <title><?php echo $data['thread_title'] ?></title>
    <div class="col-12 col-sm-7 mx-auto">
    <div class="card-title d-flex align-items-center">
        <div><i class="bx bx-book-bookmark me-1 font-22 text-primary"></i></div>
        <h5 class="mb-0 text-primary"><?php echo $data['thread_title'] ?></h5>
    </div>
    <div class="col-12 mx-auto">
        <div class="card border-top border-0 border-4 border-primary">
            <div class="card-body">
                <img src="assets/images/thread/<?php echo $data['thread_image'] ?>" alt="Image Cover" class="img-fluid rounded mb-5">
                <?php echo $data['thread_detail'] ?>
                <hr class="mt-5">
                <div class="d-flex">
                    <div class="ms-auto"><?php echo $l_create . " " . datethai($data['thread_create'], 0, $lang) ?></div>
                </div>
            </div>
        </div>
    </div>
    </div>

<?php } ?>
