<?php if (!isset($_GET['action']) || $_GET['action'] == 'search') { 

    $smember_code    = isset($_GET['member_code'])      ? $_GET['member_code']      : false;
    $smember_keyword = isset($_GET['member_keyword'])   ? $_GET['member_keyword']   : false;
    
    if ($page_type == 'member.php') {
        header("location:member.php?page=liner&action=liner_tree&member_id=$member_id");
    }

    $where = isset($_GET['action']) && $_GET['action'] == 'search' ? " WHERE (member_code IN ($smember_code)) AND (member_name LIKE '%$smember_keyword%' OR member_tel LIKE '%$smember_keyword%' OR member_code_id LIKE '%$smember_keyword%')" : false;

    if ($system_liner == 1) {
        $sql    = "SELECT system_member.*, system_liner.*, system_class.* 
            FROM system_member 
            INNER JOIN system_liner ON (system_member.member_id = system_liner.liner_member)
            INNER JOIN system_class ON (system_member.member_class = system_class.class_id)" . $where;
    }
    else {
        $sql    = "SELECT * FROM system_member " . $where;
    }
    
    $order_by   = " ORDER BY member_id DESC";

    isset($_GET["status"]) ? alert ($_GET["status"], $_GET["message"], $lang) : false;
    ?>

    <title><?php echo $l_member; ?></title>
    <div class="page-breadcrumb d-flex align-items-center">
        <div class="pe-3 text-primary">
            <div class="card-title d-flex align-items-center">
                <div><i class="bx bx-git-repo-forked me-1 font-22 text-primary"></i></div>
                <h5 class="mb-0 text-primary"><?php echo $l_member; ?></h5>
            </div>
        </div>
    </div>
    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body">
            <div class="table-responsive">
            <table class="table mb-3 text-center align-middle" >
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th><?php echo $l_member_code; ?></th>
                        <th><?php echo $l_member_name; ?></th>
                        <?php echo $system_class == 1 ? "<th>$l_class</th>" : false;?>
                        <th><?php echo $l_password; ?></th>
                        <th><?php echo $l_tel; ?></th>
                        <th><?php echo $l_idcard; ?></th>
                        <?php echo $system_liner == 1 ? "<th>$l_numliner</th>" : false;?>
                        <th><?php echo $l_datesingnin; ?></th>
                        <?php echo $system_member_expire == 1 ? "<th>$l_expire</th>" : false;?>
                        <th>Offer Code</th>
                        <th><?php echo $l_manage; ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                $query      = mysqli_query($connect, $sql . $order_by . $limit) or die(mysqli_error($connect));
                $empty      = mysqli_num_rows($query);
                if ($empty > 0) {
                $i = 0;
                while ($data = mysqli_fetch_array($query)) { 
                    $i++;

                    $member_id      = $data['member_id'];
                    $member_code    = $data['member_code'];
                    $member_id      = $data['member_id'];
                    $member_name    = $data['member_name'];
                    $member_pass    = $data['member_pass'];
                    $member_tel     = $data['member_tel'];
                    $member_code_id = $data['member_code_id'];
                    $liner_type     = $data['liner_type'];
                    $liner_status   = $data['liner_status'];

                    $class_name     = $system_class == 1 ? $data['class_name'] : false;
                    $liner_count    = $system_liner == 1 ? $data['liner_count'] : false;
                    $liner_expire   = $system_liner == 1 ? datethai($data['liner_expire'], 0 , $lang) : false;

                    $member_create  = datethai($data['member_create'], 0, $lang);
                    $member_status  = $data['member_status'];
                    ?>
                    <tr>
                        <td><?php echo $i + $start ?></td>
                        <td><?php echo $member_code ?></td>
                        <?php echo "<td><a href='$page_type?page=edit_profile&member_id=$member_id'>$member_name</a></td>" ?>
                        <?php echo $system_class == 1 ? "<th>$class_name</th>" : false;?>
                        <td><?php echo $member_pass ?></td>
                        <td><?php chkEmpty ($member_tel) ?></td>
                        <td><?php chkEmpty ($member_code_id) ?></td>
                        <?php echo $system_liner == 1 ? "<th>$liner_count</th>" : false;?>
                        <td><?php echo $member_create ?></td>
                        <?php 
                        if($system_member_expire==1&&$liner_expire<$date_now){echo "<td><font color=red>$liner_expire</font></td>";}
                        elseif($system_member_expire==1&&$liner_expire>=$date_now){echo "<td>$liner_expire</td>";} 
                        ?>  
                        <td><?php echo $liner_type == 1 ? "<font color=blue>Yes</font>" : "No";?></td> 
                        <td>
                            <?php if ($member_status == 0) {
                                echo "<a href='$page_type?page=liner&action=liner_tree&member_id=$member_id' class='me-1' title='ผังสายงานสมาชิก'><i class='bx bx-vector me-1 font-22 text-primary'></i></a>"; 
                                
                                if ($system_class == 1 || $system_liner == 1) {
                                    echo "<button type='button' data-bs-toggle='modal' data-bs-target='#exampleModal_$member_id' class='me-1 bg-white border border-0'><i class='bx bx-edit me-1 font-22 text-primary'></i></button>";
                                } ?>
                                
                                <a href="process/setting_member.php?action=block_member&member_id=<?php echo $member_id ?>" title="ระงับการใช้งาน" onclick="javascript:return confirm('ต้องการระงับการใช้งานผู้ใช้ท่านนี้ ?');">
                                    <i class="bx bx-block me-1 font-22 text-danger"></i>
                                </a>

                                <!-- Modal -->
                                <?php if ($system_class == 1 || $system_liner == 1) { ?>
                                    <div class="modal fade" id="exampleModal_<?php echo $member_id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">
                                                    <i class="bx bx-edit me-1 font-22 text-primary"></i>
                                                    แก้ไข (<?php echo $member_code ?>)</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-start">
                                                    <div class="col-12 border border-success rounded p-4 mb-3">
                                                    <form class="row g-3" action="process/setting_member.php" method="post">
                                                        <input type="hidden" name="member_id" value="<?php echo $member_id ?>">
                                                        <input type="hidden" name="direct_id" id="direct_id">
                                                        <?php if ($system_liner == 1 && $admin_status == 0 && $member_id != 1) { ?>
                                                        <div class="col-12 col-sm-6">
                                                            <label  class="form-label"><?php echo $l_member_code ?> <font color="red">*</font></label>
                                                            <input type="text" class="form-control" id="check_member" placeholder="<?php echo $l_member_code ?>" required>
                                                        </div>
                                                        <div class="col-12 col-sm-6">
                                                            <label class="form-label"><?php echo $l_member_name ?> <font color="red">*</font></label>
                                                            <input type="text" class="form-control" id="direct_name" placeholder="กรอกรหัสผู้แนะนำให้ถูกต้อง" readonly>
                                                        </div>
                                                        <?php } if ($system_class == 1) { ?>
                                                            <div class="col-12">
                                                            <label class="form-label">ย้ายตำแหน่ง</label>
                                                            <select class="form-select" name="member_class">
                                                                <?php 
                                                                    $query_class = mysqli_query($connect, "SELECT * FROM system_class");
                                                                    while ($data_class = mysqli_fetch_array($query_class)) { ?>
                                                                        <option value="<?php echo $data_class['class_id'];?>" 
                                                                            <?php if ($data_class['class_id'] == $data['member_class']) { echo "selected"; } ?>>
                                                                            <?php echo $data_class['class_name'];?>    
                                                                        </option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                        <?php } ?>
                                                        <div class="col-12">
                                                            <button name="action" value="edit_member" class="btn btn-primary btn-sm">Save</button>
                                                        </div>
                                                    </form>
                                                    </div>
                                                    <div class="col-12 border border-success rounded p-4 mb-3">
                                                    <form class="row g-3" action="process/setting_member.php" method="post">
                                                        <input type="hidden" name="member_id" value="<?php echo $member_id ?>">
                                                        <div class="col-12 col-sm-6">
                                                            <label class="form-label">Offer Code</label>
                                                            <select class="form-select" name="liner_type">
                                                                <option value="0" <?php echo $liner_type == 0 ? "selected" : false; ?>>No</option>
                                                                <option value="1" <?php echo $liner_type == 1 ? "selected" : false; ?>>Yes</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-12 col-sm-6">
                                                            <label class="form-label">Status</label>
                                                            <select class="form-select" name="liner_status" disabled>
                                                                <option value="0" <?php echo $liner_status == 0 ? "selected" : false; ?>>No</option>
                                                                <option value="1" <?php echo $liner_status == 1 ? "selected" : false; ?>>Yes</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-12">
                                                            <button name="action" value="offer_member" class="btn btn-primary btn-sm">Save</button>
                                                        </div>
                                                    </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            <?php } } else { echo "<font color=red>$l_mem_banned</font>"; } ?>
                        </td>
                    </tr>
                <?php } } else { echo "<tr><td colspan='10'>$l_notfound</td></tr>"; } ?>
                </tbody>
            </table>
            </div>
            <?php 
            $url = "$page_type?page=liner";
            pagination ($connect, $sql, $perpage, $page_id, $url); ?>
        </div>
    </div>
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
            <form action="<?php echo $page_type ?>" method="get" class="row g-3">
                <input type="hidden" name="page" value="liner">
                <div class="col-12">
                    <label class="form-label"><?php echo $l_mem_searchcode; ?></label>
                    <textarea class="form-control" name="member_code" rows="5" placeholder="Example 0000001,0000002"><?php echo $smember_code ?></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label"><?php echo $l_mem_searchdetail; ?></label>
                    <input type="text" class="form-control" name="member_keyword" value="<?php echo $smember_keyword ?>" placeholder="<?php echo $l_mem_searchdetail_box; ?>">
                </div>
                <div class="col-12 mt-3">
                    <button name="action" value="search" class="btn btn-primary btn-sm"><?php echo $l_search; ?></button>
                    <a href="<?php echo "$page_type?page=liner" ?>" class="btn btn-secondary btn-sm"><?php echo $l_cancel; ?></a>
                </div>
            </form>
        </div>
    </div>

<?php } elseif(isset($_GET['action']) && $_GET['action'] == 'liner_tree') { 

    if ($page_type == 'admin.php' && isset($_GET['member_id'])) {
        $_SESSION['liner_id'] = $_GET['member_id'];
    }
    elseif ($page_type == 'member.php') {
        if (isset($_GET['main_id'])) {
            $_SESSION['liner_id'] = $member_id;
        }
        elseif (isset($_GET['direct']) && isset($_GET['member_id'])) {
            $_SESSION['liner_id'] = $_GET['member_id'];
            header("location:member.php?page=liner&action=liner_tree");
        }
    }
    $sql  = mysqli_query($connect, "SELECT system_member.*, system_liner.*, system_class.*
        FROM system_member
        INNER JOIN system_liner ON (system_member.member_id = system_liner.liner_member)
        INNER JOIN system_class ON (system_class.class_id = system_member.member_class)
        WHERE member_id = '".$_SESSION['liner_id']."' ");
    $data = mysqli_fetch_array($sql);
    ?>
    <style type="text/css">
        @media only screen and (min-width: 768px) {
          /* For desktop: */
          .card_height { min-height : 280px; }
        }
    </style>
    <title><?php echo $l_profile ?></title>
    <?php if ($page_type == 'admin.php') { ?>
        <div class="card-title d-flex align-items-center">
            <div><i class="bx bx-git-repo-forked me-1 font-22 text-primary"></i></div>
            <h5 class="mb-0 text-primary"><?php echo $l_member ?></h5>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item" aria-current="page"><a href="<?php echo $page_type ?>?page=liner"><?php echo $l_member ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $l_profile ?></li>
            </ol>
        </nav>
    <?php } ?>
    <div class="row">
        <div class="col-12 col-sm-6">
            <div class="card border-top border-0 border-4 border-primary card_height">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless table-sm">
                            <tbody>
                                <tr>
                                    <th><?php echo $l_member_code ?></th>
                                    <td><?php chkEmpty ($data['member_code']) ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo $l_member_name ?></th>
                                    <td><?php chkEmpty ($data['member_title_name'] . " " . $data['member_name']) ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo $l_datesingnin ?></th>
                                    <td><?php echo datethai($data['member_create'], 0, $lang) ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo $l_email ?></th>
                                    <td><?php chkEmpty ($data['member_email']) ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo $l_tel ?></th>
                                    <td><?php chkEmpty ($data['member_tel']) ?></td>
                                </tr>
                                <?php if ($system_address != 0) { ?>
                                <tr>
                                    <th><?php echo $l_address ?></th>
                                    <td><?php echo address ($connect, $data['member_id'], 0, $lang) ?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6">
            <div class="card border-top border-0 border-4 border-primary card_height">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless table-sm">
                            <tbody>
                                <?php if ($system_liner == 1) { ?>
                                <tr>
                                    <th><?php echo $l_remem_linera ?></th>
                                    <td><?php echo $data['liner_count'] ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo $l_remem_linerd ?></th>
                                    <td><?php echo $data['liner_count_day'] ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo $l_remem_linerm ?></th>
                                    <td><?php echo $data['liner_count_month'] ?></td>
                                </tr>
                                <?php } if ($com_ppm > 0) { ?>
                                <tr>
                                    <th><?php echo $l_remem_ppm ?></th>
                                    <td><?php echo $data['member_point_month'] ?></td>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <th><?php echo $l_dash_com ?></th>
                                    <td><?php echo $commission = $data['liner_point'] ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo $l_status ?></th>
                                    <td><?php echo $data['liner_type'] == 0 ? "Default" : "Company Member"; ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if ($system_liner == 1) { ?>
    <div class="row">
        <div class="col-12">
            <div class="card border-top border-0 border-4 border-primary">
            <div class="card-body position-relative w-100" style=";overflow-x: scroll;">
                    <?php include('process/include_liner.php'); ?>
                </div>
            </div>
        </div>
    </div>

<?php } } ?>