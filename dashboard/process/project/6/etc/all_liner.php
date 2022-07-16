<?php if (!isset($_GET['action']) || $_GET['action'] == 'search') { 

    $smember_code    = isset($_GET['member_code'])      ? $_GET['member_code']      : false;
    $smember_keyword = isset($_GET['member_keyword'])   ? $_GET['member_keyword']   : false;
    
    if ($page_type == 'member.php') {
        header("location:member.php?page=liner&action=liner_tree&member_id=$member_id");
    }

    $where = isset($_GET['search']) ? " WHERE (member_code LIKE '%$smember_code%') AND (member_name LIKE '%$smember_keyword%' OR member_tel LIKE '%$smember_keyword%' OR member_code_id LIKE '%$smember_keyword%')" : false;

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
                        <th><?php echo $l_tel; ?></th>
                        <?php echo $system_liner == 1 ? "<th>$l_numliner</th>" : false;?>
                        <th><?php echo $l_datesingnin; ?></th>
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

                    $class_name     = $system_class == 1 ? $data['class_name'] : false;
                    $liner_count    = $system_liner == 1 ? $data['liner_count'] : false;
                    $liner_expire   = $system_liner == 1 ? datethai($data['liner_expire'], 0 , $lang) : false;

                    $member_create  = datethai($data['member_create'], 0, $lang);
                    $member_status  = $data['member_status'];
                    ?>
                    <tr>
                        <td><?php echo $i + $start ?></td>
                        <td><?php echo $member_code ?></td>
                        <?php echo "<td><a href='#'>$member_name</a></td>" ?>
                        <td><?php chkEmpty ($member_tel) ?></td>
                        <?php echo $system_liner == 1 ? "<th>$liner_count</th>" : false;?>
                        <td><?php echo $member_create ?></td>
                        <td>
                            <?php if ($member_status == 0) {
                                
                                echo "<a href='$page_type?page=liner&action=liner_tree&member_id=$member_id' class='me-1' title='ผังสายงานสมาชิก'><i class='bx bx-vector me-1 font-22 text-primary'></i></a>"; 
                                
                            } else { echo "<font color=red>$l_mem_banned</font>"; } ?>
                        </td>
                    </tr>
                <?php } } else { echo "<tr><td colspan='8'>$l_notfound</td></tr>"; } ?>
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
                    <input type="text" class="form-control" name="member_code" value="<?php echo $smember_code ?>" placeholder="<?php echo $l_mem_searchcode; ?>">
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