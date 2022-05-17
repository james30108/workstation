<?php

$action          = isset($_GET['action'])           ? $_GET['action']           : false;
$sorder_code     = isset($_GET['order_code'])       ? $_GET['order_code']       : false;
$sorder_create   = isset($_GET['order_create'])     ? $_GET['order_create']     : false;
$sorder_status   = isset($_GET['order_status'])     ? $_GET['order_status']     : false;
$smember_code    = isset($_GET['member_code'])      ? $_GET['member_code']      : false;
$sorder_type_buy = isset($_GET['order_type_buy'])   ? $_GET['order_type_buy']   : false;
$url             = "process/project/$system_style/process/setting_buy.php";

// where condition
$where      = "";
if (isset($_GET['order_id'])) {
    $where  = " WHERE order_id = " . $_GET['order_id'];
} 
elseif ($action == 'search') {
    $where  = " WHERE (order_code LIKE '%$sorder_code%') AND (member_code LIKE '%$smember_code%') AND (order_create LIKE '%$sorder_create%') ";

    if      ($sorder_status == '1') { $where .= " AND ((order_status = 1) OR (order_status = 2))"; }
    elseif  ($sorder_status == '4') { $where .= " AND (order_status >= 4)"; }
    elseif  ($sorder_status == '0' || $sorder_status == '3') { $where .= " AND (order_status = '$sorder_status')"; }
    
    if      ($sorder_type_buy != 'all') { $where .= " AND (order_type_buy = '$sorder_type_buy')"; }
} 

// query condition
$sql    = "SELECT system_order.*, system_member.* 
    FROM system_order
    INNER JOIN system_member ON (system_order.order_member = system_member.member_id)" . $where;

isset($_GET["status"]) ? alert ($_GET["status"], $_GET["message"], $lang) : false;
?>
<title><?php echo $l_order ?></title>
<div class="card-title d-flex align-items-center mb-3">
    <div><i class="bx bx-file me-1 font-22 text-primary"></i>
    </div>
    <h5 class="mb-0 text-primary"><?php echo $l_order ?></h5>
</div>
<div class="card border-top border-0 border-4 border-primary">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table text-center align-middle mb-3">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th><?php echo $l_order_code ?></th>
                        <?php echo $system_style != 1 ? "<th>$l_member_code</th>" : false; ?>
                        <th><?php echo $l_order_buyer ?></th>
                        <th><?php echo $l_create ?></th>
                        <th><?php echo $l_quantity ?></th>
                        <th><?php echo $l_price ?></th>
                        <th><?php echo $l_pay ?></th>
                        <th><?php echo $l_statusnmanage ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $order_by   = " ORDER BY FIELD (order_status, 0, 3, 4, 5, 1, 2), order_create DESC ";
                    $query      = mysqli_query($connect, $sql . $order_by . $limit) or die(mysqli_error($connect));
                    $empty      = mysqli_num_rows($query);
                    if ($empty > 0) {
                        $i = 0;
                        while ($data = mysqli_fetch_array($query)) {
                            $i++; 

                            $order_id       = $data['order_id'];
                            $order_code     = $data['order_code'];
                            $member_code    = $data['member_code'];
                            $order_buyer    = $data['order_buyer'];
                            $order_create   = datethai($data['order_create'], 0, $lang);
                            $order_amount   = number_format($data['order_amount']) . $l_piece;
                            $order_price    = number_format($data['order_price'], 2) . $l_bath;
                            $order_type_buy = $data['order_type_buy'];
                            $order_status   = $data['order_status'];
                            $order_id       = $data['order_id'];

                            ?>
                            <tr>
                                <td><?php echo $i + $start ?></td>
                                <td>
                                    <?php echo "<a href='admin.php?page=detail&action=order&order_id=$order_id' target='_blank'>$order_code</a>" ?>
                                </td>
                                <?php echo $system_style != 1 ? "<td>$member_code</td>" : false; ?>
                                <td><?php echo $order_buyer ?></td>
                                <td><?php echo $order_create ?></td>
                                <td><?php echo $order_amount ?></td>
                                <td><?php echo $order_price ?></td>
                                <td>
                                    <?php if ($order_type_buy == 0) {
                                        $query2 = mysqli_query($connect, "SELECT * FROM system_pay_refer WHERE pay_order = '$order_id'");
                                        $data2  = mysqli_fetch_array($query2);
                                        $pay_refer  = mysqli_num_rows($query2);
                                        if ($pay_refer > 0) { 
                                            $pay_id = $data2['pay_id']; ?>

                                            <a href="admin.php?page=detail&action=pay&pay_id=<?php echo $pay_id ?>" target="_blank"><i class="bx bx-credit-card-front me-1 font-22 text-primary"></i></a>
                                            
                                            <a href="admin_print.php?action=pay_refer&pay_id=<?php echo $pay_id ?>" target="_blank"><i class="bx bx-printer me-1 font-22 text-primary"></i></a>

                                        <?php } else {
                                            echo $lang_all_notfound;
                                        }
                                    } 
                                    elseif ($order_type_buy == 1) {echo "<font color=blue>$l_order_pay1</font>";} 
                                    elseif ($order_type_buy == 2) {echo "$l_order_pay2";} 
                                    elseif ($order_type_buy == 3) {echo "<font color=blue>$l_order_pay3</font>";} 
                                    elseif ($order_type_buy == 4) {echo "<font color=blue>$l_order_pay4</font>";} 
                                    elseif ($order_type_buy == 5) {echo "<font color=blue>$l_order_pay5</font>";} 
                                    ?>
                                </td>
                                <td>
                                    <div class="d-flex">
                                    <div class="d-flex align-items-center mx-auto">
                                        <?php if ($order_status == 0 || $order_status == 3) {

                                            if ($system_tracking == 0 || $order_status == 3) { ?>

                                                <a <?php echo "href='$url?action=confirm_admin&order_id=$order_id'"; ?> onclick="javascript:return confirm('Confirm ?')" title="จัดเก็บคำสั่งซื้อ"><i class="bx bx-archive-in mx-1 font-22 text-success"></i></a>

                                            <?php } if ($system_tracking == 1 && $order_status == 0) { ?>

                                                <button type="button" data-bs-toggle="modal" <?php echo "data-bs-target='#track_$order_id'" ?> class="mx-1 bg-white border border-0" title="Tracking"><i class="bx bx-package me-1 font-22 text-primary"></i></button>

                                            <?php } ?>

                                            <hr class="vr mx-1">
                                            <a <?php echo "href='process/setting_buy.php?action=cancel_order&page_type=$page_type&order_id=$order_id'"; ?> onclick="javascript:return confirm('Confirm ?')" title="Cancel"><i class="bx bx-trash mx-1 font-22 text-danger"></i></a>

                                        <?php } 
                                        elseif ($order_status == 1) { echo "<font color=red>$l_order_status1</font>"; } 
                                        elseif ($order_status == 2) { echo "<font color=red>$l_order_status2</font>"; } 
                                        elseif ($order_status >= 4) { echo "<font color=green>$l_order_status4</font>"; }

                                        if ($order_status >= 3) { ?>

                                            <hr class="vr mx-1">
                                            <a href="admin_print.php?action=order&order_id=<?php echo $order_id ?>" target="_blank"><i class="bx bx-printer mx-1 font-22 text-primary"></i></a>

                                        <?php } ?>
                                    </div>
                                    </div>
                                </td>
                            </tr>

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
                                                <input type="hidden" name="order_id" value="<?php echo $data['order_id'] ?>">
                                                <div class="col-12 mt-3">
                                                    <label class="form-label">ชื่อบริษัทขนส่ง</label>
                                                    <input type="text" class="form-control" name="order_track_name" value="Flash Express" placeholder="ชื่อบริษัทขนส่ง" readonly>
                                                </div>
                                                <div class="col-12 mt-3">
                                                    <label class="form-label">รหัสติดตามพัสดุ <font color="red">*</font></label>
                                                    <input type="text" class="form-control" name="order_track_id" value="<?php echo $data['order_track_id'] ?>" placeholder="รหัสติดตามพัสดุ">
                                                </div>
                                                <div class="col-12 mt-3">
                                                    <button name="action" value="insert" class="btn btn-primary btn-sm">บันทึก</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } } else { echo "<tr><td colspan='10'>$l_notfound</td></tr>"; } ?>
                </tbody>
            </table>
        </div>
        <?php
        $url = "admin.php?page=order&order_code=$sorder_code&order_create=$sorder_create&order_status=$sorder_status&action=search";
        pagination($connect, $sql, $perpage, $page_id, $url); ?>
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
        <hr />
        <form action="admin.php" method="get" class="row g-3">
            <input type="hidden" name="page" value="order">
            <div class="col-12">
                <label class="form-label"><?php echo $l_order_code ?></label>
                <input type="text" class="form-control" name="order_code" placeholder="<?php echo $l_order_code ?>" value="<?php echo $sorder_code ?>">
            </div>
                <div class="col-12">
                    <label class="form-label"><?php echo $system_style != 1 ? $l_member_code : "รหัสผู้แนะนำ"; ?></label>
                    <input type="text" class="form-control" name="member_code" placeholder="<?php echo $l_member_code ?>" value="<?php echo $smember_code ?>">
                </div>
            <div class="col-12">
                <label class="form-label"><?php echo $l_create ?></label>
                <input type="date" class="form-control" name="order_create" value="<?php echo $sorder_create ?>">
            </div>
            <div class="col-12">
                <label class="form-label"><?php echo $l_pay ?></label>
                <select name="order_type_buy" class="form-control">
                    <option value="all"<?php if (!isset($_GET['order_type_buy']) || $_GET['order_type_buy'] == 'all') {echo "selected";} ?>><?php echo $l_all ?></option>
                    <option value="0" <?php echo $sorder_type_buy == '0' ? "selected" : false; ?>><?php echo $l_order_pay0 ?></option>
                    <option value="1" <?php echo $sorder_type_buy == '1' ? "selected" : false; ?>><?php echo $l_order_pay1 ?></option>
                    <option value="2" <?php echo $sorder_type_buy == '2' ? "selected" : false; ?>><?php echo $l_order_pay2 ?></option>
                    <option value="3" <?php echo $sorder_type_buy == '3' ? "selected" : false; ?>><?php echo $l_order_pay3 ?></option>
                    <option value="4" <?php echo $sorder_type_buy == '4' ? "selected" : false; ?>><?php echo $l_order_pay4 ?></option>
                    <option value="5" <?php echo $sorder_type_buy == '5' ? "selected" : false; ?>><?php echo $l_order_pay5 ?></option>
                </select>
            </div>
            <div class="col-12">
                <label class="form-label"><?php echo $l_status ?></label>
                <select name="order_status" class="form-control">
                    <option value="all" <?php echo $sorder_status == 'all' || $sorder_status == false ? "selected" : false; ?>><?php echo $l_all ?></option>
                    <option value="0" <?php echo $sorder_status == '0' ? "selected" : false; ?>><?php echo $l_order_status0 ?></option>
                    <option value="1" <?php echo $sorder_status == '1' ? "selected" : false; ?>><?php echo $l_order_status1 ?></option>
                    <option value="3" <?php echo $sorder_status == '3' ? "selected" : false; ?>><?php echo $l_order_status3 ?></option>
                    <option value="4" <?php echo $sorder_status == '4' ? "selected" : false; ?>><?php echo $l_order_status4 ?></option>
                </select>
            </div>
            <div class="col-12">
                <button class="btn btn-success btn-sm" name="action" value="search"><?php echo $l_save ?></button>
                <a href="admin.php?page=order" class="btn btn-secondary btn-sm" type="reset"><?php echo $l_cancel ?></a>
            </div>
        </form>
    </div>
</div>
