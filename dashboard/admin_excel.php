<?php
include('process/function.php');

if      ($_GET['action'] == 'member_create') { $filename = "member_in_month.xls"; }
elseif  ($_GET['action'] == 'sold')          { $filename = "sold.xls"; }
elseif  ($_GET['action'] == 'finance') { 

    if      ($_GET["type"] == 'com')        { $filename = "commission.xls"; }
    elseif  ($_GET["type"] == 'report')     { $filename = "com.xls"; }
    elseif  ($_GET["type"] == 'withdraw')   { $filename = "withdraw.xls"; }
     
}
else { $filename = "Report.xls"; }


header("Content-Type: application/xls");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");

$admin_id         = $_SESSION['admin_id'];
$sql_check_login  = mysqli_query($connect, "SELECT * FROM system_admin WHERE admin_id = '$admin_id' ");
$data_check_login = mysqli_fetch_array($sql_check_login);
$admin_id         = $data_check_login['admin_id'];
$admin_status     = $data_check_login['admin_status'];
$page_type        = basename($_SERVER['PHP_SELF']);

$lang             = $system_lang == 1 ? $data_check_login['admin_lang'] : 0;
include("process/include_lang.php");

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
</head>
<body>
    
<?php if (isset($_GET['action']) && $_GET['action'] == 'member_create') { 

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

    ?>
	<div class="card-body m-3 mt-3">
        <div class="card-title d-flex align-items-center">
            <h5 class="mb-0 text-primary"><?php echo $l_report_member ?></h5>
        </div>
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
            </tbody>
        </table>
        <table class="table table-bordered text-center align-middle">
            <thead class="table-light">
                <tr>
                    <th><?php echo $l_date ?></th>
                    <th><?php echo $l_remem_count ?></th>
                    <th><?php echo $l_member_code ?></th>
                    <th><?php echo $l_member_name ?></th>
                    <th><?php echo $l_tel ?></th>
                </tr>
            </thead>
            <tbody>
            <?php 
            $query              = mysqli_query($connect, $sql . $limit);
            while ($data = mysqli_fetch_array($query)) { 
                $create         = date("Y-m-d", strtotime($data['member_create']));
                $member_number  = number_format($data['member_number']);
                ?>
                <tr>
                    <td><?php echo datethai($data['member_create'], 0, $lang) ?></td>
                    <td><?php echo $member_number ?> คน</td>
                    <td colspan="8"></td>
                </tr>
                <?php
                $query2 = mysqli_query($connect, "SELECT * FROM system_member $where AND  (member_create LIKE '%$create%')");             
                while ($data2 = mysqli_fetch_array($query2)) { 

                    $member_code  = "MEMBER-" . $data2['member_code'];
                    $member_name  = $data2['member_name'];
                    $member_tel   = "TEL-" . $data2['member_tel'];
                    ?>
                    <tr>
                        <td colspan="2"></td>
                        <td><?php echo $member_code ?></td>
                        <td><?php echo $member_name ?></td>
                        <td><?php chkEmpty ($member_tel) ?></td>
                    </tr>
                <?php } } ?>
            </tbody>
        </table>
    </div>

<?php } elseif (isset($_GET['action']) && $_GET['action'] == 'sold') {  

    $report_id  = $_GET['report_id'];
    $query      = mysqli_query($connect, "SELECT * FROM system_report WHERE report_id = '$report_id' ");
    $data       = mysqli_fetch_array($query);

    $query_2    = mysqli_query($connect, "SELECT system_report_detail.*, system_order_detail.* 
        FROM system_order_detail
        INNER JOIN system_report_detail ON (system_report_detail.report_detail_link = system_order_detail.order_detail_order)
        WHERE report_detail_main = '$report_id' ") or die(mysqli_error($connect));
    $count_product = mysqli_num_rows($query_2);
    ?>
    <div class="card-body m-3">
    <div class="page-breadcrumb d-flex align-items-center mb-3">
        <div class="pe-3 text-primary">
            <div class="card-title d-flex align-items-center">
                <div><i class="bx bx-list-ul me-1 font-22 text-primary"></i></div>
                <h5 class="mb-0 text-primary"><?php echo $l_resold_old ?></h5>
            </div>
        </div>
    </div>
    <table class="table table-borderless table-sm">
        <tbody>
            <tr>
                <th width="180"><?php echo $l_com_round ?></th>
                <td><?php echo number_format($data['report_round']); ?></td>
            </tr>
            <tr>
                <th><?php echo $l_date ?></th>
                <td><?php echo datethai($data['report_create'], 0, $lang) ?></td>
            </tr>
            <tr>
                <th><?php echo $l_resold_money ?></th>
                <td><?php echo number_format($data['report_point']) . $l_bath ?></td>
            </tr>
            <tr>
                <th><?php echo $l_resold_order ?></th>
                <td><?php echo number_format($data['report_count']) . $l_list ?></td>
            </tr>
            <tr>
                <th><?php echo $l_resold_goods ?></th>
                <td><?php echo number_format($count_product) . $l_list ?></td>
            </tr>
        </tbody>
    </table>
    <table class="table mb-3 text-center align-middle table-bordered">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th><?php echo $l_order_code ?></th>
                <th><?php echo $l_price ?></th>
                <th><?php echo $l_product_code ?></th>
                <th><?php echo $l_quantity ?></th>
                <th><?php echo $l_pay ?></th>
            </tr>
        </thead>
        <tbody>
        <?php

        $sql    = "SELECT system_report_detail.*, system_order_detail.*, system_product.*, system_order.*
        FROM system_order_detail
        INNER JOIN system_report_detail ON (system_report_detail.report_detail_link = system_order_detail.order_detail_order)
        INNER JOIN system_product ON (system_product.product_id = system_order_detail.order_detail_product)
        INNER JOIN system_order ON (system_order.order_id = system_order_detail.order_detail_order)
        WHERE report_detail_main = '$report_id' ";
        
        $query  = mysqli_query($connect, $sql) or die(mysqli_error($connect));
        $empty  = mysqli_num_rows($query);
        $i      = 0;
        if ($empty > 0) {
        while($data = mysqli_fetch_array ($query)) {
            $report_detail_point = $data['report_detail_point'];
            $order_type_buy      = $data['order_type_buy'];
            $i++;
            ?>
            <tr>
                <td><?php echo $i + $start ?></td>
                <td>
                    <a href="admin.php?page=order&action=order_detail&order_id=<?php echo $data['order_id'] ?>" target="_blank"><?php echo $data['order_code'] ?></a>
                </td>
                <td><?php echo number_format($data['order_detail_price'], 2) . $l_bath ?></td>
                <td><?php echo $data['product_code'] ?></td>
                <td><?php echo number_format($data['order_detail_amount']) . $l_list ?></td>
                <td>
                    <?php 
                    if     ($order_type_buy == 0) {echo "<font color=gray>$l_order_pay0</font>";} 
                    elseif ($order_type_buy == 1) {echo "<font color=blue>$l_order_pay1</font>";} 
                    elseif ($order_type_buy == 2) {echo "$l_order_pay2";} 
                    elseif ($order_type_buy == 3) {echo "<font color=blue>$l_order_pay3</font>";} 
                    elseif ($order_type_buy == 4) {echo "<font color=blue>$l_order_pay4</font>";} 
                    elseif ($order_type_buy == 5) {echo "<font color=blue>$l_order_pay5</font>";} 
                    ?>
                </td>
            </tr>
            <?php } } else { echo "<tr><td colspan='6'>$l_notfound</td></tr>"; } ?>
        </tbody>
    </table>
    </div>

<?php } elseif (isset($_GET['action']) && $_GET['action'] == 'finance') { 

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

        $sql = "SELECT system_member.*, system_point.*, SUM(point_bonus) AS sum 
            FROM system_point 
            INNER JOIN system_member ON (system_member.member_id = system_point.point_member)
            WHERE $point_type AND (point_status = 0)
            GROUP BY point_member
            HAVING sum >= '$report_min'";
        
    }
    elseif ($type == 'report') {

        $query  = mysqli_query($connect, "SELECT * FROM system_report WHERE report_id = '$report_id' ");
        $data   = mysqli_fetch_array($query);
        
        $title      = $l_com_old;
        $round      = number_format($data['report_round']);
        $create     = datethai($data['report_create'], 0, $lang);
        $point      = number_format($data['report_point'], 2) . $l_bath;
        $count      = number_format($data['report_count']);

        $sql = "SELECT system_member.*, system_report_detail.*, system_report.*
            FROM system_member
            INNER JOIN system_report_detail ON (system_member.member_id = system_report_detail.report_detail_link)
            INNER JOIN system_report        ON (system_report.report_id = system_report_detail.report_detail_main)
            WHERE report_detail_main = '$report_id' ";

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
        $excel_url  = "#";
        $print_url  = "#";

        $sql = "SELECT system_member.*, system_withdraw.*
            FROM system_withdraw
            INNER JOIN system_member ON (system_member.member_id = system_withdraw.withdraw_member)
            WHERE withdraw_cut = 0";

    }
    ?>
    <div class="card-body">
        <div class="card-title d-flex align-items-center">
            <h5 class="mb-0 text-primary"><?php echo $l_com_old ?> <?php if ($data['report_type'] == 1) { echo " (Withdrawals)";} ?></h5>
        </div>
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
            </tbody>
        </table>
        <table class="table mb-0 text-center table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th><?php echo $l_number ?></th>
                    <th><?php echo $l_member_code ?></th>
                    <th><?php echo $l_member_name ?></th>
                    <th><?php echo $l_idcard ?></th>
                    <?php echo $system_address == 2 ? "<th>$l_address</th>" : false ?>
                    <th><?php echo $l_bankname ?></th>
                    <th><?php echo $l_bankid ?></th>
                    <th><?php echo $l_com_pay ?></th>
                    <?php echo $type == 'withdraw' ? "<th>$l_status</th>" : false; ?>
                </tr>
            </thead>
            <tbody>
            <?php 
            $query      = mysqli_query($connect, $sql) or die(mysqli_error($connect));
            $i          = 0;
            while($data = mysqli_fetch_array ($query)) {

                $id_card = $data['member_code_id'] != '' ? "card-" . $data['member_code_id'] : false;
                $id_bank = $data['member_bank_id'] != '' ? "bank-" . $data['member_bank_id'] : false;
                
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
                    else { $money = report_final ($sum, $report_fee1, $report_fee2, $l_bath, $report_max)[0]; }
                    
                }
                $i++;
                ?>
                <tr>
                    <td><?php echo $i + $start ?></td>
                    <td><?php echo "member-" . $data['member_code'] ?></td>
                    <td><?php echo $data['member_name'] ?></td>
                    <td><?php chkEmpty ($id_card) ?></td>
                    <?php echo $system_address != 0 ? "<td>" . address ($connect, $data['member_id'], 0, $lang) . "</td>": false; ?>
                    <td><?php chkEmpty ($bank) ?></td>
                    <td><?php chkEmpty ($id_bank) ?></td>
                    <td><?php echo $money ?></td>
                    ?>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

<?php } ?>

</body>
</html>