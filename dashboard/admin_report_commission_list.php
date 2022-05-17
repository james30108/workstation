<title><?php echo $l_com_list ?></title>
<div class="card-title d-flex align-items-center mb-3">
    <div><i class="bx bx-detail me-1 font-22 text-primary"></i></div>
    <h5 class="mb-0 text-primary"><?php echo $l_com_list ?></h5>
</div>
<div class="card border-top border-0 border-4 border-primary">
    <div class="card-body">
        <table class="table table-borderless table-sm mb-3">
            <tbody>
                <th width=200><?php echo $l_com ?></th>
                <td>
                <?php 

                $action     = isset($_GET['action']) ? $_GET['action'] : false;
                $point_type = isset($_GET['point_type']) ? $_GET['point_type'] : "all";
                $date_start = isset($_GET['date_start']) ? $_GET['date_start'] : false;
                $date_end   = isset($_GET['date_end'])   ? $_GET['date_end']   : false;
                $where      = " WHERE (point_type != 0) AND (point_member != 0) ";

                if ($action == 'search') {

                    if ($point_type != 'all') { $where = " WHERE (point_type = '$point_type') AND (point_member != 0) "; }
                    if ($date_end == '') { $date_end = $today; }
                    if ($date_start != false || $_GET['date_end'] != false) {
                        $where  .= " AND (point_create BETWEEN '$date_start' AND '$date_end 23:59')";
                    }
                }

                $query = mysqli_query($connect, "SELECT *, SUM(point_bonus) AS sum_point FROM system_point" . $where);
                $data  = mysqli_fetch_array($query);
                echo number_format($data['sum_point'], 2) . $l_bath; 
                ?>
                </td>
            </tbody>
        </table>
        <div class="table-responsive">
            <table class="table mb-3 text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th><?php echo $l_create ?></th>
                        <th><?php echo $l_member_code ?></th>
                        <th><?php echo $l_type ?></th>
                        <th><?php echo $l_com ?></th>                 
                        <th><?php echo $l_descrip ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql    = "SELECT system_point.*, system_member.*
                        FROM system_point 
                        INNER JOIN system_member ON (system_point.point_member = system_member.member_id)"
                        . $where . " ORDER BY point_id DESC ";
                    $query  = mysqli_query($connect, $sql . $limit);
                    $empty  = mysqli_num_rows($query);
                    if ($empty > 0) {
                    $i   = 0; 
                    while ( $data = mysqli_fetch_array($query) ) { 
                        $i++;
                        ?>
                        <tr>
                            <td><?php echo $i + $start ?></td>
                            <td><?php echo datethai($data['point_create'], 0, $lang) ?></td>
                            <td><?php echo $data['member_code'] ?></td>
                            <td>
                            <?php 
                            if ($data['point_type'] == 1) {
                                $order_id = $data['point_order'];
                                $query2 = mysqli_query($connect, "SELECT * FROM system_order WHERE order_id = '$order_id' ");
                                $data2  = mysqli_fetch_array($query2);
                                echo $data2['order_code']; 
                            }
                            elseif ($data['point_type'] == 2) {
                                echo "ROI Bonus";
                            }
                            elseif ($data['point_type'] == 3) {
                                echo "Matching ROI Bonus";
                            }
                            ?>   
                            </td>
                            <td><font color=green><?php echo number_format($data['point_bonus'], 2) . $l_bath ; ?></font></td>
                            <td><?php echo $data['point_detail'] ?></td>
                        </tr>
                    <?php } } else { echo "<tr><td colspan='6'>$l_notfound</td></tr>"; } ?>
                </tbody>
            </table>
        </div>  
        <?php 
        $url = "admin.php?page=commission_list&action=search&date_start=$date_start&date_end=$date_end&point_type=$point_type";
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
        <form action="" method="get" class="row g-3">
            <input type="hidden" name="page" value="commission_list">
            <div class="col-12">
                <label class="form-label"><?php echo $l_type ?></label>
                <select name="point_type" class="form-control">
                    <option value="all" <?php if ($point_type == false || $point_type == 'all') {echo "selected";} ?>><?php echo $l_all ?></option>
                    <option value="1" <?php echo $point_type == '1' ? "selected" : false; ?>><?php echo "Order Bonus" ?></option>
                    <option value="2" <?php echo $point_type == '2' ? "selected" : false; ?>><?php echo "ROI Bonus" ?></option>
                    <option value="3" <?php echo $point_type == '3' ? "selected" : false; ?>><?php echo "Matching ROI Bonus" ?></option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label"><?php echo $l_datestart ?></label>
                <input type="date" class="form-control" name="date_start" value="<?php echo $date_start ?>">
            </div>
            <div class="col-12">
                <label class="form-label"><?php echo $l_dateend ?></label>
                <input type="date" class="form-control" name="date_end" value="<?php echo $date_end ?>">
            </div>
            <div class="col-12">
                <button class="btn btn-primary btn-sm" name="action" value="search"><?php echo $l_search ?></button>
                <a href="admin.php?page=commission_list" class="btn btn-secondary btn-sm"><?php echo $l_cancel ?></a>
            </div>
        </form>
    </div>
</div>