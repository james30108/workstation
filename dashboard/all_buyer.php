<?php isset($_GET["status"]) ? alert ($_GET["status"], $_GET["message"], $lang) : false;

if (!isset($_GET['action']) || $_GET['action'] == 'search') { 

    $action      = isset($_GET['action'])       ? $_GET['action']       : false;
    $buyer_name  = isset($_GET['buyer_name'])   ? $_GET['buyer_name']   : false;
    $member_code = isset($_GET['member_code'])  ? $_GET['member_code']  : false;
    $where       = "";

    if ($page_type == 'member.php') {
        $where   = " WHERE buyer_direct = '$member_id' ";
    }
    elseif ($page_type == 'admin.php' && $action == 'search') {
        $where   = " WHERE (member_code LIKE '%$member_code%') AND (buyer_name LIKE '%$buyer_name%') ";
    }
    
    $sql        = "SELECT system_buyer.*, system_member.* 
        FROM system_buyer
        INNER JOIN system_member ON (system_buyer.buyer_direct = system_member.member_id) " . $where;
    $order_by   = " ORDER BY member_id ";
    $query      = mysqli_query($connect, $sql . $order_by . $limit) or die(mysqli_error($connect));
    $empty      = mysqli_num_rows($query); ?>
    <title><?php echo $l_buyer ?></title>
    <div class="page-breadcrumb d-flex align-items-center">
        <div class="pe-3 text-primary">
            <div class="card-title d-flex align-items-center">
                <div><i class="bx bx-group me-1 font-22 text-primary"></i></div>
                <h5 class="mb-0 text-primary"><?php echo $l_buyer ?></h5>
            </div>
        </div>
    </div>
    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table mb-3 text-center align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th><?php echo $l_member_name ?></th>
                            <th><?php echo $l_tel ?></th>
                            <th><?php echo $l_linername ?></th>
                            <th><?php echo $l_create ?></th>
                            <?php echo $page_type == 'admin.php' ? "<th>$l_manage</th>" : false; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($empty > 0) {
                            $i = 0;
                            while ($data = mysqli_fetch_array($query)) {
                                $i++; 

                                $buyer_id       = $data['buyer_id'];
                                $buyer_name     = $data['buyer_name'];
                                $buyer_tel      = $data['buyer_tel'];
                                $buyer_name     = $data['buyer_name'];
                                $member_code    = $data['member_code'];
                                $buyer_create   = datethai($data['buyer_create'], 0, $lang);
                                $buyer_status   = $data['buyer_status'];
                                $buyer_name     = $data['buyer_name'];

                                $url_detail     = $page_type == 'admin.php' ? "admin.php?page=buyer&action=buyer_detail&buyer_id=$buyer_id" : "#";
                                $url_ban        = $page_type == 'admin.php' ? "process/setting_buyer.php?action=block&buyer_id=$buyer_id" : "#";
                                ?>
                                <tr>
                                    <td><?php echo $i + $start ?></td>
                                    <td><a href="<?php echo $url_detail ?>"><?php echo $buyer_name ?></a></td>
                                    <td><?php echo $buyer_tel ?></td>
                                    <td><?php echo $member_code ?></td>
                                    <td><?php echo $buyer_create ?></td>
                                    <?php 
                                    if ($page_type == 'admin.php') {
                                        echo "<td>";
                                        if ($buyer_status == 0) { ?>

                                            <a href="<?php echo $url_ban ?>" title="Ban" onclick="javascript:return confirm('Ban ?');">
                                                <i class="bx bx-block me-1 font-22 text-danger"></i>
                                            </a>

                                        <?php } else { echo "<font color=red>$l_mem_banned</font>"; } 
                                        echo "</td>"; 
                                    } 
                                    ?>
                                </tr>
                            <?php } } else { echo "<tr><td colspan='8'>$l_notfound</td></tr>"; } ?>
                    </tbody>
                </table>
            </div>
            <?php
            $url = "$page_type?page=buyer";
            pagination($connect, $sql, $perpage, $page_id, $url); ?>
        </div>
    </div>
    <!--start switcher-->
    <?php if ($page_type == 'admin.php') { ?>
    <div class="switcher-wrapper">
        <div class="switcher-btn"> <i class='bx bx-search'></i>
        </div>
        <div class="switcher-body">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 text-uppercase"><?php echo $l_search ?></h5>
                <button type="button" class="btn-close ms-auto close-switcher" aria-label="Close"></button>
            </div>
            <hr />
            <form action="<?php echo $page_type ?>" method="get" class="row g-3">
                <input type="hidden" name="page" value="buyer">
                <div class="col-12">
                    <label class="form-label"><?php echo $l_mem_upliner ?></label>
                    <input type="text" class="form-control" name="member_code" value="<?php echo $member_code ?>" placeholder="<?php echo $l_linercode ?>">
                </div>
                <div class="col-12">
                    <label class="form-label"><?php echo $l_mem_name ?></label>
                    <input type="text" class="form-control" name="buyer_name" value="<?php echo $buyer_name ?>" placeholder="<?php echo $l_name ?>">
                </div>
                <div class="col-12 mt-3">
                    <button name="action" value="search" class="btn btn-primary btn-sm"><?php echo $l_search ?></button>
                    <a href="<?php echo $page_type ?>?page=buyer" class="btn btn-secondary btn-sm"><?php echo $l_cancel ?></a>
                </div>
            </form>
        </div>
    </div>
<?php } } elseif (isset($_GET['action']) && $_GET['action'] == 'buyer_detail') {

    $query  = mysqli_query($connect, "SELECT system_buyer.*, system_member.* 
        FROM system_buyer
        INNER JOIN system_member ON (system_buyer.buyer_direct = system_member.member_id)
        WHERE buyer_id = '".$_GET['buyer_id']."' ");
    $data = mysqli_fetch_array($query); 

    $member_code    = $data['member_code'];
    $buyer_name     = $data['buyer_name'];
    $buyer_create   = datethai($data['buyer_create'], 0, $lang);
    $buyer_email    = $data['buyer_email'];
    $buyer_tel      = $data['buyer_tel'];
    $order_address  = $data['order_address'];
    $order_district = $data['order_district'];
    $order_amphur   = $data['order_amphur'];
    $order_province = $data['order_province'];
    $order_zipcode  = $data['order_zipcode'];
    ?>


    <title>ข้อมูลสมาชิก</title>
    <div class="col-12 col-sm-9 mx-auto">
    <div class="card-title d-flex align-items-center">
        <div><i class="bx bx-globe me-1 font-22 text-primary"></i></div>
        <h5 class="mb-0 text-primary">สมาชิก</h5>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page"><a href="<?php echo $page_type ?>?page=buyer">สมาชิกหน้าเว็บเพจ</a></li>
            <li class="breadcrumb-item active" aria-current="page">ข้อมูลสมาชิก</li>
        </ol>
    </nav>
    </div>
    <div class="col-12 col-sm-9 mx-auto">
        <div class="card border-top border-0 border-4 border-primary card_height">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless table-sm">
                        <tbody>
                            <tr>
                                <th>รหัสผู้แนะนำ</th>
                                <td><?php echo $member_code ?></td>
                            </tr>
                            <tr>
                                <th>ชื่อ - นามสกุล</th>
                                <td><?php echo $buyer_name ?></td>
                            </tr>
                            <tr>
                                <th>วันที่สมัคร</th>
                                <td><?php echo $buyer_create ?></td>
                            </tr>
                            <tr>
                                <th>อีเมล์</th>
                                <td><?php echo $buyer_email ?></td>
                            </tr>
                            <tr>
                                <th>เบอร์โทรฯ</th>
                                <td><?php echo $buyer_tel ?></td>
                            </tr>
                            <tr>
                                <th>ที่อยู่สำหรับจัดส่ง</th>
                                <td><?php echo "$order_address ตำบล/แขวง $order_district อำเภอ/เขต $order_amphur จังหวัด $order_province รหัสไปรษณีย์ $order_zipcode" ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-9 mx-auto">
        <div class="card border-top border-0 border-4 border-primary">
            <div class="card-body">
            <table class="table text-center align-middle mb-3">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>รหัสคำสั่งซื้อ</th>
                            <th>วันที่สั่ง</th>
                            <th>ราคารวม</th>
                            <th>ชำระเงิน</th>
                            <th>สถานะ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql        = "SELECT * FROM system_order WHERE order_buyer_id = '".$_GET['buyer_id']."' ";
                        $order_by   = " ORDER BY order_status ASC, order_create DESC ";
                        $query      = mysqli_query($connect, $sql . $order_by . $limit) or die(mysqli_error($connect));
                        $empty      = mysqli_num_rows($query);
                        if ($empty > 0) {
                            $i = 0;
                            while ($data = mysqli_fetch_array($query)) {
                                
                                $order_id = $data['order_id'];
                                $order_code = $data['order_code'];
                                $order_create = datethai($data['order_create'], 0, $lang);
                                $order_price = number_format($data['order_price'], 2) . $l_bath;
                                $order_type_buy = $data['order_type_buy'];
                                $order_status = $data['order_status'];

                                $detail_url = "admin.php?page=order&action=order_detail&order_id=$order_id";
                                $report_url = "admin_print.php?action=order&order_id=$order_id";

                                $i++;
                                ?>
                                <tr>
                                    <td><?php echo $i + $start ?></td>
                                    <td>
                                        <a href="<?php echo $detail_url ?>" target="_blank"><?php echo $order_code ?></a>
                                    </td>
                                    <td><?php echo $order_create ?></td>
                                    <td><?php echo $order_price ?></td>
                                    <td>
                                        <?php if ($order_type_buy == 0) {

                                            $query_pay_refer = mysqli_query($connect, "SELECT * FROM system_pay_refer WHERE pay_order = '$order_id' ");
                                            $data_pay_refer  = mysqli_fetch_array($query_pay_refer);
                                            $pay_refer       = mysqli_num_rows($query_pay_refer);

                                            if ($pay_refer > 0) { 

                                                $pay_id     = $data_pay_refer['pay_id'];
                                                $pay_url    = "admin.php?page=detail&action=pay&pay_id=$pay_id";
                                                $pay_report = "admin_print.php?action=pay_refer&pay_id=$pay_id";

                                                ?>

                                                <a href="<?php echo $pay_url ?>" target="_blank"><i class="bx bx-credit-card-front me-1 font-22 text-primary"></i></a>

                                                <a href="<?php echo $pay_report ?>" target="_blank"><i class="bx bx-printer me-1 font-22 text-primary"></i></a>

                                            <?php } else {
                                                echo "ไม่มี";
                                            }
                                        } 
                                        elseif ($order_type_buy == 1) {echo "<font color=blue>$l_order_pay1</font>";} 
                                        elseif ($order_type_buy == 2) {echo "$l_order_pay2";} 
                                        elseif ($order_type_buy == 3) {echo "<font color=blue>$l_order_pay3</font>";} 
                                        elseif ($order_type_buy == 4) {echo "<font color=blue>$l_order_pay4</font>";} 
                                        elseif ($order_type_buy == 5) {echo "<font color=blue>$l_order_pay5</font>";} 
                                        ?>
                                    </td>
                                    <td class="d-flex">
                                        <div class="d-flex align-items-center mx-auto">
                                            <?php 
                                            if     ($order_status == 0) { echo "<font color=red>$l_order_status0</font>"; } 
                                            elseif ($order_status == 1) { echo "<font color=red>$l_order_status1</font>"; } 
                                            elseif ($order_status == 2) { echo "<font color=red>$l_order_status2</font>"; } 
                                            elseif ($order_status >= 4) { echo "<font color=green>$l_order_status4</font>"; }
                                            
                                            if ($order_status != 0) { ?>

                                                <hr class="vr mx-1">
                                                <a href="<?php echo $report_url ?>" target="_blank"><i class="bx bx-printer font-22 text-primary"></i></a>

                                            <?php } ?>
                                        </div>
                                    </td>
                                </tr>
                        <?php } } else { echo "<tr><td colspan='10'>$l_notfound</td></tr>"; } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php } ?>