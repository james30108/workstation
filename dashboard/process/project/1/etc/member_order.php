<?php isset($_GET["status"]) ? alert ($_GET["status"], $_GET["message"], $lang) : false;

$type = isset($_GET["type"]) ? $_GET["type"] : false;

if ($type == 'report') {
    $title = $l_pay_report;
    $where = "(order_status != 0)";
}
else {
    $title = $l_order;
    $where = "(order_status = 0)";
}
?>
<title><?php echo $title ?></title>
<div class="page-breadcrumb d-flex align-items-center mb-3">
    <div class="pe-3 text-primary">
        <div class="card-title d-flex align-items-center">
            <div><i class="bx bx-check-square me-1 font-22 text-primary"></i></div>
            <h5 class="mb-0 text-primary"><?php echo $title ?></h5>
        </div>
    </div>
</div>
<div class="card border-top border-0 border-4 border-primary">
    <div class="card-body">
        <div class="table-responsive">
        <table class="table mb-0 text-center align-middle" >
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>order code</th>
                    <th><?php echo $l_date ?></th>
                    <th><?php echo $l_quantity ?></th>
                    <th><?php echo $l_price ?></th>
                    <th><?php echo $l_pay ?></th>
                    <th><?php echo $l_statusnmanage ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql        = "SELECT * FROM system_order WHERE (order_member = '$member_id') AND $where";
                $order_by   = " ORDER BY order_id DESC ";
                $query      = mysqli_query($connect, $sql . $order_by . $limit);
                $empty      = mysqli_num_rows($query);
                if($empty > 0){
                    $i = 0;
                    while($data = mysqli_fetch_array($query)){
                        
                        $i++;

                        $order_id       = $data['order_id'];
                        $order_code     = $data['order_code'];
                        $order_create   = datethai($data['order_create'], 0, $lang);
                        $order_amount   = number_format($data['order_amount']) . $l_piece ;
                        $order_price    = number_format($data['order_price'], 2) . $l_bath;
                        $order_type_buy = $data['order_type_buy'];
                        $order_status   = $data['order_status'];

                        $cancel_url = "process/setting_buy.php?action=cancel_order&order_id=$order_id&page_type=$page_type";
                        ?>
                        <tr>
                            <td><?php echo $i + $start ?></td>
                            <td>
                                <a href="member.php?page=detail&action=order&order_id=<?php echo $order_id ?>" target="_blank">
                                <?php echo $order_code ?>
                                </a>
                            </td>
                            <td><?php echo $order_create ?></td>
                            <td><?php echo $order_amount ?></td>
                            <td><?php echo $order_price ?></td>
                            <td>
                                <?php if ($order_type_buy == 0) {

                                    $query2      = mysqli_query($connect, "SELECT * FROM system_pay_refer WHERE pay_order = '$order_id'");
                                    $data2       = mysqli_fetch_array($query2);
                                    $pay_refer   = mysqli_num_rows($query2);

                                    if ($pay_refer > 0) { 

                                        $pay_id = $data2['pay_id']; ?>

                                        <a href="member.php?page=detail&action=pay&pay_id=<?php echo $pay_id ?>" target="_blank"><i class="bx bx-credit-card-front me-1 font-22 text-primary"></i></a>
                                        
                                        <a href="member_print.php?action=pay_refer&pay_id=<?php echo $pay_id ?>" target="_blank"><i class="bx bx-printer me-1 font-22 text-primary"></i></a>

                                <?php } else { echo $lang_all_notfound; } } 
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
                                    <?php if ($order_status == 0) { echo $l_order_status0; } 
                                    elseif ($order_status == 1) { echo "<font color=red>$l_order_status1</font>"; } 
                                    elseif ($order_status == 2) { echo "<font color=red>$l_order_status2</font>"; } 
                                    elseif ($order_status == 3) { echo "<font color=blue>$l_order_status3</font>"; } 
                                    elseif ($order_status >= 4) { echo "<font color=green>$l_order_status4</font>"; }
                                    ?>
                                </div>
                                </div>
                            </td>
                        </tr>
                <?php } } else { echo "<tr><td colspan='7'>$l_notfound</td></tr>"; } ?>
            </tbody>
        </table>
        </div>
        <?php
        $url = "member.php?page=member_order";
        pagination ($connect, $sql, $perpage, $page_id, $url); ?>
    </div>
</div>