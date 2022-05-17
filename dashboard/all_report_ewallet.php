<?php 

$action = isset($_GET["action"]) ? $_GET["action"] : false;

if ($action == 'deposit' || $action == 'deposit_search') { 

    $smember_code   = isset($_GET["member_code"])   ? $_GET["member_code"]  : false;
    $date_start     = isset($_GET["date_start"])    ? $_GET["date_start"]   : false;
    $date_end       = isset($_GET["date_end"])      ? $_GET["date_end"]     : false;
    $status         = isset($_GET["status"])        ? $_GET["status"]       : false;

    if ($page_type == 'member.php') {
        $where = " WHERE (deposit_member = '$member_id' ) AND (deposit_status != 0) ";
    }
    elseif ($page_type == 'admin.php' && $action == 'deposit_search') {
        
        if      ($status != 'all') { $where = " WHERE (deposit_status = '$status')"; }
        else    { $where = " WHERE (deposit_status != 0) "; }
        
        if      ($smember_code != '') { $where .= " AND (member_code LIKE '%$smember_code%')"; }

        if ($date_start != '' && $date_end == '') {
            $where .= " AND (deposit_create BETWEEN '$date_start' AND NOW() )";
        }
        elseif ($date_start != '' && $date_end != '') {
            $where .= " AND (deposit_create BETWEEN '$date_start' AND '$date_end 23:59')";
        }
    }
    else {
        $where = " WHERE (deposit_status != 0) ";
    }
    $sql    = "SELECT system_member.*, system_deposit.* 
        FROM system_member
        INNER JOIN system_deposit 
        ON (system_member.member_id = system_deposit.deposit_member)" . $where;

    ?>
    <title><?php echo $l_etopup_report ?></title>
    <div class="page-breadcrumb d-flex align-items-center mb-3">
        <div class="card-title d-flex align-items-center">
            <div><i class="bx bx-credit-card-front me-1 font-22 text-primary"></i></div>
            <h5 class="mb-0 text-primary"><?php echo $l_etopup_report ?></h5>
        </div>
    </div>
    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body">
            <div class="table-responsive">
            <table class="table mb-0 text-center align-middle" >
                <thead class="table-light">
					<tr>
						<th>#</th>
						<th><?php echo $l_create; ?></th>
						<th><?php echo $l_member_code; ?></th>
						<th><?php echo $l_money; ?></th>
						<th><?php echo $l_date; ?></th>
                        <th><?php echo $l_time; ?></th>
                        <th><?php echo $l_status; ?></th>
					</tr>
				</thead>
				<tbody>
                    <?php
                    $query = mysqli_query($connect, $sql . $limit) or die(mysqli_error($connect));
                    $empty = mysqli_num_rows($query);
                    if ($empty > 0 ) {
                        $i     = 0;
                        while ($data = mysqli_fetch_array($query)) { 

                            $deposit_id     = $data['deposit_id'];
                            $deposit_create = datethai($data['deposit_create'], 0, $lang);
                            $member_code    = $data['member_code'];
                            $deposit_money  = number_format($data['deposit_money'], 2) . $l_bath;
                            $deposit_date   = $data['deposit_date'] != '' ? datethai($data['deposit_date'], 0, $lang) : "<font color=gray>none</font>";
                            $deposit_time   = $data['deposit_time'];
                            $deposit_status = $data['deposit_status'];

                            $ewalllet_url   = "$page_type?page=detail&action=ewallet&deposit_id=$deposit_id";

                            $i++;
                            ?>
                            <tr>
                                <td><?php echo $i + $start ?></td>
                                <td><a href="<?php echo $ewalllet_url ?>" target="_blank"><?php echo $deposit_create ?></a></td>
                                <td><?php echo $member_code ?></td>
                                <td><?php echo $deposit_money ?></td>
                                <td><?php echo $deposit_date ?></td>
                                <td><?php chkEmpty ($deposit_time) ?></td>
                                <td>
                                <?php 
                                if     ($deposit_status == 1) { echo "<font color=green>Success</font>"; }
                                elseif ($deposit_status == 2) { echo "<font color=red>Cancel</font>"; }
                                elseif ($deposit_status == 3) { echo "<font color=green>By Admin</font>"; }
                                ?>
                                </td>
                            </tr>
                    <?php } } else { echo "<tr><td colspan='8'>$l_notfound</td></tr>"; } ?>
				</tbody>
			</table>
			</div>
            <?php
            $url = "$page_type?page=report_ewallet&action=deposit";
            pagination ($connect, $sql, $perpage, $page_id, $url); ?>
		</div>
	</div>
    <?php if ($page_type == 'admin.php') { ?>
    <!--start switcher-->
    <div class="switcher-wrapper">
        <div class="switcher-btn"> <i class='bx bx-search'></i>
        </div>
        <div class="switcher-body">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 text-uppercase">Search</h5>
                <button type="button" class="btn-close ms-auto close-switcher" aria-label="Close"></button>
            </div>
            <hr/>
            <form action="admin.php" method="get" class="row g-3">
                <input type="hidden" name="page" value="report_ewallet">
                <div class="col-12">
                    <label class="form-label">Member code</label>
                    <input type="text" class="form-control" name="member_code" placeholder="รหัสสมาชิก" value="<?php echo $smember_code ?>">
                </div>
                <div class="col-12">
                    <label class="form-label">Date start</label>
                    <input type="date" class="form-control" name="date_start" value="<?php echo $date_start ?>">
                </div>
                <div class="col-12">
                    <label class="form-label">Date end (Today)</label>
                    <input type="date" class="form-control" name="date_end" value="<?php echo $date_end ?>">
                </div>
                <div class="col-12">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="all" <?php if ($status == false || $status == 'all') {echo "selected";} ?>>All</option>
                        <option value="1" <?php echo $status == '1' ? "selected" : false; ?>>Success</option>
                        <option value="2" <?php echo $status == '2' ? "selected" : false; ?>>Cancel</option>
                        <option value="3" <?php echo $status == '3' ? "selected" : false; ?>>By Admin</option>
                    </select>
                </div>
                <div class="col-12">
                    <button class="btn btn-success btn-sm" name="action" value="deposit_search">Search</button>
                    <a href="admin.php?page=report_ewallet&action=deposit" class="btn btn-secondary btn-sm">Cancel</a>
                </div>
            </form>
        </div>
    </div>
<?php } } elseif ($action == 'tranfer' || $action == 'tranfer_search') { 

    $smember_code   = isset($_GET["member_code"])   ? $_GET["member_code"]  : false;
    $date_start     = isset($_GET["date_start"])    ? $_GET["date_start"]   : false;
    $date_end       = isset($_GET["date_end"])      ? $_GET["date_end"]     : false;
    $status         = isset($_GET["status"])        ? $_GET["status"]       : false;

    if ($page_type == 'member.php') {

        $where = " WHERE (tranfer_member = '$member_id' )";

    }
    elseif ($page_type == 'admin.php' && $date_start != '' && $date_end == '') {

        $where = " WHERE (tranfer_create BETWEEN '$date_start' AND NOW() )";

    }
    elseif ($page_type == 'admin.php' && $date_start != '' && $date_end != '') {

        $where = " WHERE (tranfer_create BETWEEN '$date_start' AND '$date_end 23:59')";

    }
    $sql      = "SELECT * FROM system_tranfer" . $where;
    $order_by = " ORDER BY tranfer_create DESC ";

    ?>
    <title><?php echo $l_etranfer_report ?></title>
        <div class="page-breadcrumb d-flex align-items-center mb-3">
        <div class="card-title d-flex align-items-center">
            <div><i class="bx bx-transfer me-1 font-22 text-primary"></i></div>
            <h5 class="mb-0 text-primary"><?php echo $l_etranfer_report ?></h5>
        </div>
    </div>
    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body">
            <div class="table-responsive">
            <table class="table mb-0 text-center align-middle" >
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>วันที่</th>
                        <th>รหัสผู้โอน</th>
                        <th>ชื่อผู้โอน</th>
                        <th>รหัสผู้รับ</th>
                        <th>ชื่อผู้รับ</th>
                        <th>จำนวนเงิน</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query    = mysqli_query($connect, $sql . $order_by . $limit) or die(mysqli_error($connect));
                    $empty    = mysqli_num_rows($query);
                    $i        = 0;
                    if ($empty > 0) {
                        while ($data = mysqli_fetch_array($query)) {

                            $query1= mysqli_query($connect, "SELECT * FROM system_member WHERE member_id = '" . $data['tranfer_member'] . "' ");
                            $data1 = mysqli_fetch_array($query1);
                            $member_code = $data1['member_code'];
                            $member_name = $data1['member_name'];

                            $query2= mysqli_query($connect, "SELECT * FROM system_member WHERE member_id = '" . $data['tranfer_to'] . "' ");
                            $data2 = mysqli_fetch_array($query2);
                            $memberto_code = $data2['member_code'];
                            $memberto_name = $data2['member_name'];

                            $tranfer_create= datethai($data['ewallet_tranfer_create'], 0, $lang);
                            $tranfer_money = number_format($data['tranfer_money'], 2) . $l_bath;

                            $i++;
                            ?>
                            <tr>
                                <td><?php echo $i + $start ?></td>
                                <td><?php echo $tranfer_create ?></td>
                                <td><?php echo $member_code ?></td>
                                <td><?php echo $member_name ?></td>
                                <td><?php echo $memberto_code ?></td>
                                <td><?php echo $memberto_name ?></td>
                                <td><?php echo $tranfer_money ?></td>
                            </tr>
                    <?php } } else { echo "<tr><td colspan='8'>$l_notfound</td></tr>"; } ?>
                </tbody>
            </table>
            </div>
            <?php 
            $url = "admin.php?page=report_ewallet&action=tranfer";
            pagination ($connect, $sql, $perpage, $page_id, $url); ?>
        </div>
    </div>
    <?php if ($page_type == 'admin.php') { ?>
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
                <input type="hidden" name="page" value="report_ewallet">
                <div class="col-12">
                    <label class="form-label"><?php echo $l_datestart ?></label>
                    <input type="date" class="form-control" name="date_start" value="<?php echo $date_start ?>">
                </div>
                <div class="col-12">
                    <label class="form-label"><?php echo $l_dateend ?></label>
                    <input type="date" class="form-control" name="date_end" value="<?php echo $date_end ?>">
                </div>
                <div class="col-12">
                    <button class="btn btn-success btn-sm" name="action" value="tranfer_search"><?php echo $l_search ?></button>
                    <a href="admin.php?page=report_ewallet&action=tranfer" class="btn btn-secondary btn-sm"><?php echo $l_cancel ?></a>
                </div>
            </form>
        </div>
    </div>
<?php } } ?>