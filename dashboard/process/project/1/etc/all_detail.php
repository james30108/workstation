<?php if ($_GET['action'] == 'pay') {
    
    $pay_id = $_GET['pay_id'];
    $query  = mysqli_query($connect, "SELECT * FROM system_pay_refer WHERE pay_id = '$pay_id' ");
    $data   = mysqli_fetch_array($query);

    $pay_image  = $data['pay_image'];
    $pay_buyer  = $data['pay_buyer'];
    $pay_title  = $data['pay_title'];
    $pay_order  = $data['pay_order'];
    $pay_bank   = $data['pay_bank'];
    $pay_money  = number_format($data['pay_money'], 2) . $l_bath;
    $pay_date   = datethai($data['pay_date'], 0, $lang);
    $pay_time   = date("h:i น.", strtotime($data['pay_time']));
    $pay_detail = $data['pay_detail'];
    $pay_create = datethai($data['pay_create'], 2, $lang);
    $pay_type   = $data['pay_type'];
    $pay_status = $data['pay_status'];

    if ($pay_order != 0) {
        
        $query  = mysqli_query($connect, "SELECT * FROM system_order WHERE order_id = '$pay_order' ");
        $data   = mysqli_fetch_array($query);
        $url    = "process/project/$system_style/process/setting_buy.php";

        $order_status   = $data['order_status'];
        $order_create   = datethai($data['order_create'], 0, $lang);
        $order_amount   = $data['order_amount'] . $l_piece;
        $order_point    = number_format($data['order_point']);
        $order_price    = number_format($data['order_price'], 2) . $l_bath;
        $order_id       = $data['order_id'];
        $order_track_id = $data['order_track_id'];
        $order_code     = $data['order_code'];

    }
    ?>
    <title><?php echo $l_paydetail ?></title>
    <div class="col-12 col-sm-9 mx-auto">
    <div class="card-title d-flex align-items-center mb-3">
        <div><i class="bx bx-dish me-1 font-22 text-primary"></i></div>
        <h5 class="mb-0 text-primary"><?php echo $l_paydetail ?></h5>
    </div>
    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-sm-6">
                    <?php if ($pay_image != '') { ?>
                        <a href="assets/images/slips/<?php echo $pay_image ?>" target="_blank">
                            <img src="assets/images/slips/<?php echo $pay_image ?>" class="img-fluid mb-5 mb-sm-0">
                        </a>
                    <?php } else { echo "<p>$l_notfound</p>"; } ?>
                </div>
                <div class="col">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th><?php echo $l_pay_buyer ?></th>
                                <td><?php echo $pay_buyer ?></td>
                            </tr>
                            <?php if ($pay_order != 0) { ?>
                            <tr>
                                <th><?php echo $l_order_code ?></th>
                                <td><a <?php echo "href='$page_type?page=detail&action=order&order_id=$pay_order'"; ?> target="_blank"><?php echo $order_code ?></a></td>
                            </tr>
                            <?php } ?>
                            <?php echo $pay_bank != '' ? "<tr><th>$l_pay_to</th><td>$pay_bank</td></tr>" : false; ?>
                            <tr>
                                <th><?php echo $l_money ?></th>
                                <td><?php echo $pay_money ?></td>
                            </tr>
                            <tr>
                                <th><?php echo $l_date ?></th>
                                <td><?php echo $pay_date ?></td>
                            </tr>
                            <tr>
                                <th><?php echo $l_time ?></th>
                                <td><?php echo $pay_time ?></td>
                            </tr>
                            <?php echo $pay_detail != '' ? "<tr><th>$l_descrip</th><td>$pay_detail</td></tr>" : false; ?>
                            <tr>
                                <th><?php echo $l_create ?></th>
                                <td><?php echo $pay_create ?></td>
                            </tr>
                            <tr>
                                <th><?php echo $l_type ?></th>
                                <td><?php echo $pay_type == 0 ? "Order Payment" : "Other Payment" ; ?></td>
                            </tr>
                            <tr>
                                <th><?php echo $l_status ?></th>
                                <td>
                                <?php 
                                if      ($pay_status == 1) { echo "<font color='green'>$l_order_status4</font>"; } 
                                elseif  ($pay_status == 2) { echo "<font color='red'>$l_order_status1</font>"; } 
                                else    { echo "<font color='black'>$l_order_status0</font>"; } 
                                ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <?php if ($page_type == 'admin.php' && $pay_order != 0) { ?>
                    <hr>
                    <div>
                    <?php if ($order_status == 0 || $order_status == 3) { ?>
                        <h6 class="mb-3"><u><?php echo $l_order ?></u></h6>
                        <table class="table mb-0 text-center align-middle" >
                            <thead class="table-light">
                                <tr>
                                    <th><?php echo $l_order_code ?></th>
                                    <th><?php echo $l_quantity ?></th>
                                    <th><?php echo $l_point ?></th>
                                    <th><?php echo $l_price ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?php echo $order_code ?></td>
                                    <td><?php echo $order_amount ?></td>
                                    <td><?php echo $order_point . $point_name ?></td>
                                    <td><?php echo $order_price ?></td>
                                </tr>
                            </tbody>
                        </table>                            
                    <?php } 
                    elseif ($order_status == 1 || $order_status == 2) { echo "<h6 class='text-danger'>$l_order_status1</h6>"; } 
                    elseif ($order_status >= 4) { echo "<h6 class='text-success'>$l_order_status4</h6>"; } 
                    ?>
                        <div class="d-flex">
                        <div class="d-flex align-items-center">
                            <?php if ($order_status == 0 || $order_status == 3) {

                                if ($system_tracking == 0 || $order_status == 3) { ?>

                                   <a <?php echo "href='$url?action=confirm_admin&order_id=$order_id'"; ?> onclick="javascript:return confirm('ยืนยันการทำรายการ')" title="จัดเก็บคำสั่งซื้อ"><i class="bx bx-archive-in mx-1 font-22 text-success"></i></a>

                                <?php } if ($system_tracking == 1 && $order_status == 0) { ?>

                                    <button type="button" data-bs-toggle="modal" <?php echo "data-bs-target='#track_$order_id'" ?> class="mx-1 bg-white border border-0" title="ข้อมูลการขนส่ง"><i class="bx bx-package me-1 font-22 text-primary"></i></button>

                                <?php } ?>

                                <hr class="vr mx-1">
                                <a <?php echo "href='process/setting_buy.php?action=cancel_order&page_type=$page_type&order_id=$order_id'"; ?> onclick="javascript:return confirm('ยืนยันการทำรายการ')" title="ยกเลิกคำสั่งซื้อ"><i class="bx bx-trash mx-1 font-22 text-danger"></i></a>

                            <?php } if ($order_status >= 3) { ?>

                                <a href="admin_print.php?action=order&order_id=<?php echo $order_id ?>" target="_blank"><i class="bx bx-printer mx-1 font-22 text-primary"></i></a>

                            <?php } ?>
                        </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    </div>

    <div class="modal fade" id="<?php echo "track_$order_id" ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        <i class="bx bx-navigation me-1 font-22 text-primary"></i>
                        เพิ่ม/แก้ไขเลขพัสดุ
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-left">
                    <form class="row g-3" action="process/setting_track.php" method="post">
                        <input type="hidden" name="order_id" value="<?php echo $order_id ?>">
                        <div class="col-12 mt-3">
                            <label class="form-label">ชื่อบริษัทขนส่ง</label>
                            <input type="text" class="form-control" name="order_track_name" value="Flash Express" placeholder="ชื่อบริษัทขนส่ง" readonly>
                        </div>
                        <div class="col-12 mt-3">
                            <label class="form-label">รหัสติดตามพัสดุ <font color="red">*</font></label>
                            <input type="text" class="form-control" name="order_track_id" value="<?php echo $order_track_id ?>" placeholder="รหัสติดตามพัสดุ">
                        </div>
                        <div class="col-12 mt-3">
                            <button name="action" value="insert" class="btn btn-primary btn-sm">บันทึก</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php } elseif ($_GET['action'] == 'order') {

    $order_id = $_GET['order_id'];
    $query = mysqli_query($connect, "SELECT system_member.*, system_order.* 
        FROM system_order 
        INNER JOIN system_member ON (system_order.order_member = system_member.member_id)
        WHERE order_id = '$order_id' ");
    $data  = mysqli_fetch_array($query); 

    $order_id       = $data['order_id'];
    $order_code     = $data['order_code'];
    $order_buyer    = $data['order_buyer'];
    $order_create   = datethai($data['order_create'], 0, $lang);
    $order_amount   = number_format($data['order_amount']) . $l_piece;
    $order_price    = number_format($data['order_price'], 2) . $l_bath;
    $order_point    = number_format($data['order_point'], 2);
    $order_type_buy = $data['order_type_buy'];
    $order_status   = $data['order_status'];
    $order_track_id = $data['order_track_id'];
    $order_track_name = $data['order_track_name'];

    $member_code    = $data['member_code'];
    $member_code_id = $data['member_code_id'];
    $member_name    = $data['member_name'];
    $order_buyer_tel= $data['order_buyer_tel'];
    $order_detail   = $data['order_detail'];
    $member_code    = $data['member_code'];
    $member_code    = $data['member_code'];
    $member_code    = $data['member_code'];

    ?>
    <title><?php echo $l_invoice ?></title>
    <div class="col-12 col-sm-8 mx-auto">
        <div class="card-title d-flex align-items-center mb-3">
            <div><i class="bx bx-file me-1 font-22 text-primary"></i></div>
            <h5 class="mb-0 text-primary"><?php echo $l_invoice ?></h5>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item" aria-current="page"><a href="<?php echo $page_type ?>?page=order"><?php echo $l_order ?></a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $l_invoice ?></li>
            </ol>
        </nav>
        <div class="card border-top border-0 border-4 border-primary">
            <div class="card-body">
                <div class="my-3 d-block d-sm-flex">
                    <?php 
                    echo "<div><b>$l_order_code $order_code</b><br>$l_create $order_create</div>";
                    echo $order_track_id != '' ? "<div class='ms-auto mt-sm-0 mt-3'><b>$order_track_name</b><br>$order_track_id</div>" : false ; 
                    ?>
                </div>
                <div class="border border-2 p-3 mb-3">
                    <table class="table table-borderless table-sm">
                        <tbody>
                            <?php if ($system_style != 1) { ?>
                                <tr>
                                    <th><?php echo $l_member_code ?></th>
                                    <td><?php echo $member_code ?></td>
                                </tr>
                                <tr>
                                    <th><?php echo $l_idcard ?></th>
                                    <td><?php echo $member_code_id ?></td>
                                </tr>
                            <?php } else { ?>
                                <tr>
                                    <th>รหัสผู้แนะนำ</th>
                                    <td><?php echo $member_code ?></td>
                                </tr>
                                <tr>
                                    <th>ชื่อผู้แนะนำ</th>
                                    <td><?php echo $member_name ?></td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <th><?php echo $l_order_buyer ?></th>
                                <td><?php echo $order_buyer ?></td>
                            </tr>
                            <?php if ($system_address == 2) { ?>
                                <tr>
                                    <th><?php echo $l_invoice_addr ?></th>
                                    <td><?php echo $data['order_address'] .
                                            " ตำบล/แขวง " . $data['order_district'] .
                                            " อำเภอ/เขต " . $data['order_amphur'] .
                                            " จังหวัด " . $data['order_province'] .
                                            " รหัสไปรษณีย์ " . $data['order_zipcode'] ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <th><?php echo $l_tel ?></th>
                                <td><?php echo $order_buyer_tel ?></td>
                            </tr>
                            <?php echo $order_detail != '' ? "<tr><th>ข้อมูลเพิ่มเติม</th><td>$order_detail</td></tr>": false; ?>
                        </tbody>
                    </table>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm text-center align-middle">
                        <thead>
                            <tr>
                                <th><?php echo $l_product ?></th>
                                <th width=100><?php echo $l_quantity ?></th>
                                <th width=100><?php echo $l_price ?></th>
                                <th width=100><?php echo $l_fright ?></th>
                                <th width=100><?php echo $l_point ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql_detail     = mysqli_query($connect, "SELECT system_order_detail.*, system_product.* 
                            FROM system_order_detail
                            INNER JOIN system_product ON (system_order_detail.order_detail_product = system_product.product_id)
                            WHERE order_detail_order = '$order_id' ");
                            while ($detail  = mysqli_fetch_array($sql_detail)) { ?>
                                <tr>
                                    <td><?php echo $detail['product_name'] ?></td>
                                    <td><?php echo number_format($detail['order_detail_amount']) ?></td>
                                    <td><?php echo number_format($detail['order_detail_price'], 2) ?></td>
                                    <td><?php echo number_format($detail['order_detail_freight'], 2) ?></td>
                                    <td><?php echo number_format($detail['order_detail_point'], 2) ?></td>
                                </tr>
                            <?php } ?>
                            <tr height=30>
                                <td style="background-color: #E6E6E6" colspan="5"></td>
                            </tr>
                            <tr>
                                <td style="background-color: #E6E6E6" colspan="2"></td>
                                <th><?php echo $l_invoice_totqty ?></th>
                                <td><?php echo $order_amount ?></td>
                                <td><?php echo $l_piece ?></td>
                            </tr>
                            <tr>
                                <td style="background-color: #E6E6E6" colspan="2"></td>
                                <th><?php echo $l_invoice_totpoint ?></th>
                                <td><?php echo $order_point ?></td>
                                <td><?php echo $point_name ?></td>
                            </tr>
                            <tr>
                                <td style="background-color: #E6E6E6" colspan="2"></td>
                                <th><?php echo $l_invoice_totprice ?></th>
                                <td><?php echo $order_price ?></td>
                                <td><?php echo $l_bath ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<?php } elseif ($_GET['action'] == 'ewallet') {

    $query  = mysqli_query($connect, "SELECT system_member.*, system_deposit.* 
        FROM system_member
        INNER JOIN system_deposit ON (system_member.member_id = system_deposit.deposit_member)
        WHERE deposit_id = '".$_GET['deposit_id']."' ");
    $data = mysqli_fetch_array($query); 

    $image = $data['deposit_slip'] != '' ? "<a href='assets/images/ewallet_slipe/".$data['deposit_slip']."' target='_blank'>
                            <img src='assets/images/ewallet_slipe/".$data['deposit_slip']."' alt='Slip' class='img-fluid mb-5 mb-sm-0'>
                        </a>" : "<p>$l_notfound</p>";
    $deposit_id     = $data['deposit_id'];
    $deposit_create = datethai($data['deposit_create'], 0, $lang);
    $member_code    = $data['member_code'];
    $member_name    = $data['member_name'];
    $deposit_money  = number_format($data['deposit_money'], 2) . $l_bath;
    $deposit_date   = $data['deposit_date'] != '' ? datethai($data['deposit_date'], 0, $lang) : "<font color=gray>none</font>";
    $deposit_time   = $data['deposit_time'];
    $deposit_status = $data['deposit_status'];
    $deposit_detail = $data['deposit_detail'];

    $success_url   = "process/setting_ewallet.php?action=confirm_deposit&deposit_id=$deposit_id";
    $cancel_url    = "process/setting_ewallet.php?action=cancel_deposit&deposit_id=$deposit_id";

    ?>
    <title>รายละเอียดการเติมเงิน</title>
    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body p-1 pt-3 p-sm-5">
            <div class="card-title d-flex align-items-center">
                <div><i class="bx bx-detail me-1 font-22 text-primary"></i></div>
                <h5 class="mb-0 text-primary">รายละเอียดการเติมเงิน</h5>
            </div>
            <hr>
            <div class="row">
                <div class="col-12 col-sm-6 mb-3"><?php echo $image ?></div>
                <div class="col-12 col-sm-6">
                    <table class="table table-borderless">
                        <tr>
                            <th><?php echo $l_create; ?></th>
                            <td><?php echo $deposit_create ?></td>
                        </tr>
                        <tr>
                            <th><?php echo $l_member_code; ?></th>
                            <td><?php echo $member_code ?></td>
                        </tr>
                        <tr>
                            <th><?php echo $l_name; ?></th>
                            <td><?php echo $member_name ?></td>
                        </tr>
                        <tr>
                            <th><?php echo $l_money; ?></th>
                            <td><?php echo $deposit_money ?></td>
                        </tr>
                        <tr>
                            <th><?php echo $l_date; ?></th>
                            <td><?php echo $deposit_date ?></td>
                        </tr>
                        <tr>
                            <th><?php echo $l_time; ?></th>
                            <td><?php chkEmpty ($deposit_time) ?></td>
                        </tr>
                        <tr>
                            <th><?php echo $l_detail; ?></th>
                            <td><?php chkEmpty ($deposit_detail) ?></td>
                        </tr>
                        <tr>
                            <th>สถานะ</th>
                            <td>
                            <?php 
                            if     ($deposit_status == 0) { echo "Waiting"; }
                            elseif ($deposit_status == 1) { echo "<font color=green>Success</font>"; }
                            elseif ($deposit_status == 2) { echo "<font color=red>Cancel</font>"; }
                            elseif ($deposit_status == 3) { echo "<font color=green>By Admin</font>"; }
                            ?>  
                            </td>
                        </tr>
                    </table>
                    <hr>
                     <?php if ($deposit_status == 0) { ?>

                        <a href="<?php echo $success_url ?>" onclick="javascript:return confirm('Confirm ?')"><i class="bx bx-archive-in me-1 font-22 text-success"></i></a>

                        <a href="<?php echo $cancel_url ?>" onclick="javascript:return confirm('Cancel ?')"><i class="bx bx-trash me-1 font-22 text-danger"></i></a>

                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

<?php } elseif ($_GET['action'] == 'finance_search') { 

    $member_id      = $_GET['member_id'];
    $report_type    = isset($_GET["report_type"])   ? $_GET["report_type"]  : false;
    $type           = isset($_GET["type"])          ? $_GET["type"]         : false;

    if ($type == 'com') {

       $query = mysqli_query($connect, "SELECT system_member.*, system_point.*, SUM(point_bonus) AS sum_point 
            FROM system_point 
            INNER JOIN system_member ON (system_member.member_id = system_point.point_member)
            WHERE $point_type AND (point_status = 0) AND (member_id = '$member_id')
            GROUP BY point_member
            HAVING sum_point >= '$report_min'") or die(mysqli_error($connect)); 

    }
    elseif ($type == 'report') {

        $query = mysqli_query($connect, "SELECT system_member.*, system_report_detail.*, system_report.* 
            FROM system_member
            INNER JOIN system_report_detail ON (system_member.member_id = system_report_detail.report_detail_link)
            INNER JOIN system_report        ON (system_report.report_id = system_report_detail.report_detail_main)
            WHERE (report_detail_link = '$member_id') AND (report_type = $report_type) ") or die(mysqli_error($connect));

    }
    elseif ($type == 'withdraw') {

        $query  = mysqli_query($connect, "SELECT system_member.*, system_withdraw.*
            FROM system_withdraw
            INNER JOIN system_member ON (system_member.member_id = system_withdraw.withdraw_member)
            WHERE withdraw_cut = 0") or die ($connect);

        echo "withdraw";
    } 
    ?>

    <title><?php echo $l_search ?></title>
    <div class="card-title d-flex align-items-center mb-3">
        <i class="bx bx-detail me-1 font-22 text-primary "></i>
        <h5 class="mb-0 text-primary"><?php echo $l_search ?></h5>
    </div>
    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body">
            <table class="table table-borderless table-sm">
                <tbody>
                    <?php if ($report_min > 1) { ?>
                    <tr>
                        <th width="150"><?php echo $l_com_min ?></th>
                        <td><?php echo number_format($report_min, 2) . $l_bath ?></td>
                    </tr>
                    <?php } if ($report_max > 0) { ?>
                    <tr>
                        <th width="150"><?php echo $l_com_max ?></th>
                        <td><?php echo number_format($report_max, 2) . $l_bath ?></td>
                    </tr>
                    <?php } if ($report_fee1 > 0) { ?>
                    <tr>
                        <th width="150"><?php echo $l_fee1 ?></th>
                        <td><?php echo number_format($report_fee1, 2) . $l_bath ?></td>
                    </tr>
                    <?php } if ($report_fee2 > 0) { ?>
                    <tr>
                        <th width="150"><?php echo $report_fee2 ?></th>
                        <td><?php echo $report_fee2 . "%" ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <table class="table mb-3 text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th><?php echo $l_member_code ?></th>
                        <th><?php echo $l_member_name ?></th>
                        <?php 
                        echo $type == 'report' ? "<th>$l_com_round</th>" : false;
                        echo $type == 'report' ? "<th>$l_com_datecut</th>" : false;
                        ?>
                        <th><?php echo $l_bankname ?></th>
                        <th><?php echo $l_bankid ?></th>
                        <th><?php echo $l_com_pay ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $empty = mysqli_num_rows($query);

                if ($empty > 0) {
                    while ($data = mysqli_fetch_array($query)) { 

                        $member_code    = $data['member_code'];
                        $member_name    = $data['member_name'];

                        if ($data['member_bank_name'] != '') {

                            $bank_id= $data['member_bank_name'];
                            $query2 = mysqli_query($connect, "SELECT * FROM system_bank WHERE bank_id = '$bank_id' ");
                            $data2  = mysqli_fetch_array($query2);
                            $bank   = $data2 ? $data2['bank_name_th'] : false;

                        }
                        else { $bank = ""; }
                        
                        if ($type != 'report') { $point = $data['sum_point']; }
                        elseif ($type == 'report') {

                            $report_round   = $data['report_round'];
                            $report_id      = $data['report_id'];
                            $point          = $data['report_detail_point'];
                            $report_create  = datethai($data['report_create'], 0, $lang);
                            $detail_url     = "<a href='admin.php?page=detail&action=com&report_id=$report_id' target='_blank'>$report_create</a>";

                        }
                        ?>
                    <tr>
                        
                        <td><?php echo $member_code ?></td>
                        <td><?php echo $member_name ?></td>
                        <?php 
                        echo $type == 'report' ? "<td>$report_round</td>" : false;
                        echo $type == 'report' ? "<td>$detail_url</td>" : false;
                        ?>
                        <td><?php chkEmpty ($bank) ?></td>
                        <td><?php chkEmpty ($data['member_bank_id']) ?></td>
                        <td><?php echo report_final ($point, $report_fee1, $report_fee2, $l_bath, $report_max); ?></td>
                    </tr>
                <?php } } else { echo "<tr><td colspan='7'>$l_notfound</td></tr>"; } ?>
                </tbody>
            </table>
        </div>
    </div>

<?php } elseif ($_GET['action'] == 'finance') { 

    $report_id  = isset($_GET["report_id"]) ? $_GET["report_id"]    : false;
    $type       = isset($_GET["type"])      ? $_GET["type"]         : false;

    if ($type == 'com') {

        $query  = mysqli_query($connect, "SELECT SUM(com.sum_com) AS sum, COUNT(*) AS count
            FROM
                (SELECT *, SUM(point_bonus) AS sum_com FROM system_point 
                WHERE $point_type AND (point_status = 0) AND (point_member != 0)
                GROUP BY point_member
                HAVING (sum_com >= '$report_min')) AS com");
        $data   = mysqli_fetch_array($query);
        
        $title      = $l_com_nowdeta;
        $create     = datethai($today, 0, $lang);
        $point      = number_format($data['sum'], 2) . $l_bath;
        $count      = number_format($data['count']);
        $round      = report_now ($connect, 0);
        $excel_url  = "admin_excel.php?action=finance&type=com";
        $print_url  = "admin_print.php?action=finance&type=com";

        $sql = "SELECT system_member.*, system_point.*, SUM(point_bonus) AS sum 
            FROM system_point 
            INNER JOIN system_member ON (system_member.member_id = system_point.point_member)
            WHERE $point_type AND (point_status = 0)
            GROUP BY point_member
            HAVING sum >= '$report_min'";
        
    }
    elseif ($type == 'withdraw') {

        $query  = mysqli_query($connect, "SELECT SUM(withdraw.group_sum) AS sum, COUNT(*) AS count 
            FROM
                (SELECT *, SUM(withdraw_point) AS group_sum 
                FROM system_withdraw 
                WHERE (withdraw_cut = 0) AND (withdraw_status != 2)
                GROUP BY withdraw_member) AS withdraw ") or die ($connect);
        $data  = mysqli_fetch_array($query);
        
        $title      = $l_com_nowdeta;
        $create     = datethai($today, 0, $lang);
        $point      = number_format($data['sum'], 2) . $l_bath;
        $count      = number_format($data['count']);
        $round      = report_now ($connect, 0);
        $excel_url  = "admin_excel.php?action=finance&type=withdraw";
        $print_url  = "admin_print.php?action=finance&type=withdraw";

        $sql = "SELECT system_member.*, system_withdraw.*
            FROM system_withdraw
            INNER JOIN system_member ON (system_member.member_id = system_withdraw.withdraw_member)
            WHERE withdraw_cut = 0";
        
    }
    elseif ($type == 'report') {

        $query  = mysqli_query($connect, "SELECT * FROM system_report WHERE report_id = '$report_id' ");
        $data   = mysqli_fetch_array($query);
        
        $title      = $l_com_old;
        $round      = number_format($data['report_round']);
        $create     = datethai($data['report_create'], 0, $lang);
        $point      = number_format($data['report_point'], 2) . $l_bath;
        $count      = number_format($data['report_count']);
        $excel_url  = "admin_excel.php?action=finance&type=report&report_id=$report_id";
        $print_url  = "admin_print.php?action=finance&type=report&report_id=$report_id";


        $sql = "SELECT system_member.*, system_report_detail.*, system_report.*
            FROM system_member
            INNER JOIN system_report_detail ON (system_member.member_id = system_report_detail.report_detail_link)
            INNER JOIN system_report        ON (system_report.report_id = system_report_detail.report_detail_main)
            WHERE report_detail_main = '$report_id' ";

    }

    ?>
    <title><?php echo $title ?></title>
    <div class="card-title d-flex align-items-center mb-3">
        <div><i class="bx bx-list-ul me-1 font-22 text-primary"></i></div>
        <h5 class="mb-0 text-primary"><?php echo $title ?></h5>
    </div>
    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body">
            <table class="table table-borderless table-sm">
                <tbody>
                    <tr>
                        <th width=200px><?php echo $l_com_round ?></th>
                        <td><?php echo $round ?></td>
                    </tr>
                    <tr>
                        <th><?php echo $l_com_datecut ?></th>
                        <td><?php echo $create ?></td>
                    </tr>
                    <tr>
                        <th><?php echo $l_com_money ?></th>
                        <td><?php echo $point ?></td>
                    </tr>
                    <tr>
                        <th><?php echo $l_numliner ?></th>
                        <td><?php echo $count ?></td>
                    </tr>
                    <?php if ($report_max > 0) { ?>
                    <tr>
                        <th><?php echo $l_com_max ?></th>
                        <td><?php echo number_format($report_max, 2) . $l_bath ?></td>
                    </tr>
                    <?php } if ($report_fee1 > 0) { ?>
                    <tr>
                        <th><?php echo $l_fee1 ?></th>
                        <td><?php echo number_format($report_fee1, 2) . $l_bath ?></td>
                    </tr>
                    <?php } if ($report_fee2 > 0) { ?>
                    <tr>
                        <th><?php echo $l_fee2 ?></th>
                        <td><?php echo $report_fee2 . "%" ?></td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <th><?php echo $l_report ?></th>
                        <td>
                            <a href="<?php echo $excel_url ?>" target="_blank" title="Excel"><i class="bx bx-table me-3 font-22 text-primary"></i></a>
                            <a href="<?php echo $print_url ?>" target="_blank" title="Report"><i class="bx bx-printer font-22 text-primary"></i></a>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table class="table mb-3 text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th><?php echo $l_number ?></th>
                        <th><?php echo $l_member_code ?></th>
                        <th><?php echo $l_member_name ?></th>
                        <th><?php echo $l_idcard ?></th>
                        <?php echo $system_address != 0 ? "<th>$l_address</th>": false; ?>
                        <th><?php echo $l_bankname ?></th>
                        <th><?php echo $l_bankid ?></th>
                        <th><?php echo $l_com_pay ?></th>
                        <?php echo $type == 'withdraw' ? "<th>$l_status</th>" : false; ?>
                    </tr>
                </thead>
                <tbody>
                <?php
                $query  = mysqli_query($connect, $sql . $limit) or die(mysqli_error($connect));
                $i      = 0;
                while($data = mysqli_fetch_array ($query)) {

                    if ($data['member_bank_name'] != 0) {

                        $bank_id= $data['member_bank_name'];
                        $query2 = mysqli_query($connect, "SELECT * FROM system_bank WHERE bank_id = '$bank_id' ");
                        $data2  = mysqli_fetch_array($query2);
                        $bank   = $data2['bank_name_th'];

                    }
                    else { $bank = ""; }

                    if      ($type == 'com')    { $sum = $data['sum']; }
                    elseif  ($type == 'report') { $sum = $data['report_detail_point']; }
                    
                    if ($type == 'withdraw') {

                        $money = $data['withdraw_point'] . $l_bath;
                        if      ($data['withdraw_status'] == 0) { $status = "<font color=gray>$l_order_status0</font>"; }
                        elseif  ($data['withdraw_status'] == 1) { $status = "<font color=green>$l_order_status4</font>"; }
                        elseif  ($data['withdraw_status'] == 2) { $status = "<font color=red>$l_order_status1</font>"; }
                    
                    }
                    else {

                        // report's withdraw
                        if (isset($data['report_type']) && $data['report_type'] == 2) { $money = $sum . $l_bath; }
                        else { $money = report_final ($sum, $report_fee1, $report_fee2, $l_bath, $report_max); }
                        
                    }
                    
                    $i++;
                    ?>
                    <tr>
                        <td><?php echo $i + $start ?></td>
                        <td><?php echo $data['member_code'] ?></td>
                        <td><?php echo $data['member_name'] ?></td>
                        <td><?php chkEmpty ($data['member_code_id']) ?></td>
                        <?php echo $system_address != 0 ? "<td>" . address ($connect, $data['member_id'], 0, $lang) . "</td>" : false; ?>
                        <td><?php chkEmpty ($bank) ?></td>
                        <td><?php chkEmpty ($data['member_bank_id']) ?></td>
                        <td><?php echo $money ?></td>
                        <?php echo $type == 'withdraw' ? "<td>$status</td>" : false; ?>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php
            $url = "admin.php?page=detail&action=finance&type=$type&report_id=$report_id";
            pagination ($connect, $sql, $perpage, $page_id, $url); ?>
        </div>
    </div>

<?php } ?>