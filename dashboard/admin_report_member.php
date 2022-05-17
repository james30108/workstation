<div class="col-12 col-sm-7 mx-auto">
<?php if (!isset($_GET['action']) ||  $_GET['action'] == 'search') { 

    $member_code    = isset($_GET['member_code']) ? $_GET['member_code'] : false;
    $member_keyword = isset($_GET['member_keyword']) ? $_GET['member_keyword'] : false;
    $date_start     = isset($_GET['date_start']) ? $_GET['date_start'] : false;
    $date_end       = isset($_GET['date_end']) ? $_GET['date_end'] : false;

    $where   = "";
    $where_2 = "";
    if (isset($_GET['action'])) {
        $where  = " WHERE (member_code LIKE '%$member_code%') AND (member_name LIKE '%$member_keyword%' OR member_tel LIKE '%$member_keyword%' OR member_code_id LIKE '%$member_keyword%')";
    }

    if (($date_start != false && $date_start != '') || ($date_end != false && $date_end!='')) {
        $where_2 = " AND (member_create BETWEEN '$date_start' AND '$date_end 23:59')";
    }
    $sql   = "SELECT *, COUNT(member_create) AS member_number FROM system_member" . $where . $where_2 . " GROUP BY DATE(member_create)";

    $print_url = "admin_print.php?action=member_create&member_code=$member_code&member_keyword=$member_keyword&date_start=$date_start&date_end=$date_end";
    $excel_url = "admin_excel.php?action=member_create&member_code=$member_code&member_keyword=$member_keyword&date_start=$date_start&date_end=$date_end";
    ?>
    <title><?php echo $l_report_member ?></title>
    <div class="card-title d-flex align-items-center mb-3">
        <div><i class="bx bx-git-repo-forked me-1 font-22 text-primary"></i></div>
        <h5 class="mb-0 text-primary"><?php echo $l_report_member ?></h5>
    </div>
    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body">
            <table class="table table-borderless table-sm">
                <tbody>
                    <tr>
                        <th width="100"><?php echo $l_datestart ?></th>
                        <td><?php echo $date_start!=false&&$date_start!='' ? datethai($date_start, 0, $lang) : $l_notassign;?></td>
                    </tr>
                    <tr>
                        <th><?php echo $l_dateend ?></th>
                        <td><?php echo $date_end!=false&&$date_end!='' ? datethai($date_end, 0, $lang) : $l_notassign;?></td>
                    </tr>
                    <tr>
                        <th><?php echo $l_report ?></th>
                        <td><a href="<?php echo $print_url ?>" target="_blank" title="Reprot"><i class="bx bx-printer me-1 font-22 text-primary"></i></a>
                            <a href="<?php echo $excel_url ?>" target="_blank" title="Excel"><i class="bx bx-table me-1 font-22 text-primary"></i></a>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="table-responsive">
            <table class="table mb-0 text-center align-middle mb-3">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th><?php echo $l_date ?></th>
                        <th><?php echo $l_remem_count ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                
                $query = mysqli_query($connect, $sql .$limit);
                $i = 0;
                while ($data = mysqli_fetch_array($query)) {
                    $i++;

                    $member_create = datethai($data['member_create'], 0, $lang);
                    $member_number = number_format($data['member_number']);
                    $member_url    = "admin.php?page=admin_report_member&action=member_in_day&member_create=".date("Y-m-d", strtotime($data['member_create']))."&member_number=$member_number";
                    ?>
                    <tr>
                        <td><?php echo $i ?></td>
                        <td><a href="<?php echo $member_url ?>" ><?php echo $member_create ?></a></td>
                        <td><?php echo $member_number ?> คน</td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            </div>
            <?php 
            $url = "admin.php?page=admin_report_member";
            pagination ($connect, $sql, $perpage, $page_id, $url); ?>
        </div>
    </div>
    <!--start switcher-->
    <div class="switcher-wrapper">
        <div class="switcher-btn"> <i class='bx bx-search'></i>
        </div>
        <div class="switcher-body">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 text-uppercase"><?php echo $l_search ?></h5>
                <button type="button" class="btn-close ms-auto close-switcher" aria-label="Close"></button>
            </div>
            <hr/>
            <form action="admin.php" method="get" class="row g-3">
                <input type="hidden" name="page" value="admin_report_member">
                <div class="col-12">
                    <label class="form-label"><?php echo $l_mem_searchcode ?></label>
                    <input type="text" class="form-control" name="member_code" value="<?php echo $member_code ?>" placeholder="<?php echo $l_member_code ?>">
                </div>
                <div class="col-12">
                    <label class="form-label"><?php echo $l_mem_searchdetail ?></label>
                    <input type="text" class="form-control" name="member_keyword" value="<?php echo $member_keyword ?>" placeholder="<?php echo $l_mem_searchdetail_box ?>">
                </div>
                <div class="col-12">
                    <label class="form-label"><?php echo $l_datestart ?></label>
                    <input type="date" class="form-control" name="date_start">
                </div>
                <div class="col-12">
                    <label class="form-label"><?php echo $l_dateend ?></label>
                    <input type="date" class="form-control" name="date_end">
                </div>
                <div class="col-12">
                    <button name="action" value="search" class="btn btn-primary btn-sm"><?php echo $l_search ?></button>
                    <a href="admin.php?page=admin_report_member" class="btn btn-secondary btn-sm"><?php echo $l_cancel ?></a>
                </div>
            </form>
        </div>
    </div>
<?php } elseif (isset($_GET['action']) && $_GET['action'] == 'member_in_day') {

    $member_create  = $_GET['member_create'];
    $member_number  = $_GET['member_number'];
    $report_url     = "admin_print.php?action=member_create&date_start=$member_create&date_end=$member_create";

    ?>
    <title><?php echo $l_remem_detail ?></title>
    <div class="card-title d-flex align-items-center mb-3">
        <div><i class="bx bx-git-repo-forked me-1 font-22 text-primary"></i></div>
        <h5 class="mb-0 text-primary"><?php echo $l_remem_detail ?></h5>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page"><a href="admin.php?page=admin_report_member"><?php echo $l_report_member ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $l_detail ?></li>
        </ol>
    </nav>
    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body">
            <table class="table table-borderless table-sm">
                <tbody>
                    <tr>
                        <th width="100"><?php echo $l_date ?></th>
                        <td><?php echo datethai($member_create, 0, $lang); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo $l_member ?></th>
                        <td><?php echo number_format($member_number); ?> คน</td>
                    </tr>
                    <tr>
                        <th><?php echo $l_report ?></th>
                        <td><a href="<?php echo $report_url ?>" target="_blank" title="พิมพ์รายงาน"><i class="bx bx-printer me-1 font-22 text-primary"></i></a></td>
                    </tr>
                </tbody>
            </table>
            <div class="table-responsive">
            <table class="table mb-0 text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th><?php echo $l_member_code ?></th>
                        <th><?php echo $l_member_name ?></th>
                        <th><?php echo $l_tel ?></th>
                        <th><?php echo $l_report ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                $sql   = "SELECT * FROM system_member WHERE (member_create LIKE '%$member_create%')";
                $query = mysqli_query($connect, $sql . $limit); 
                $i     = 0;
                while ($data = mysqli_fetch_array($query)) {
                    $i++;
                    ?>
                    <tr>
                        <td><?php echo $i ?></td>
                        <td><?php echo $data['member_code'] ?></td>
                        <td><?php echo $data['member_name'] ?></td>
                        <td><?php chkEmpty ($data['member_tel']) ?></td>
                        <td><a href="admin_print.php?action=member&member_id=<?php echo $data['member_id'] ?>" target="_blank" title="Report"><i class="bx bx-printer me-1 font-22 text-primary"></i></a></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            </div>
            <?php 
            $url = "admin.php?page=admin_report_member&action=member_in_day&member_create=$member_create&member_number=$member_number";
            pagination ($connect, $sql, $perpage, $page_id, $url); ?>
        </div>
    </div>
<?php } ?>
</div>