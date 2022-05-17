<?php 
$type =  isset($_GET['type']) ? $_GET['type'] : false;

if ($type == 'report') {
    $title = $l_pay_report;
    $where = " WHERE (pay_status != 0) AND (pay_order = 0) ";
}
else {
    $title = $l_pay;
    $where = " WHERE (pay_status = 0) AND (pay_order = 0) ";
}

$sql    = "SELECT system_pay_refer.*, system_member.* 
    FROM system_pay_refer
    INNER JOIN system_member ON (system_pay_refer.pay_member = system_member.member_id)
    " . $where . "
    ORDER BY pay_create DESC";
$query = mysqli_query($connect, $sql . $limit) or die(mysqli_error($connect)); 

?>
<title><?php echo $title ?></title>
<?php isset($_GET["status"]) ? alert ($_GET["status"], $_GET["message"], $lang) : false; ?>
<div class="card-title d-flex align-items-center mb-3">
    <div><i class="bx bx-dish me-1 font-22 text-primary"></i></div>
    <h5 class="mb-0 text-primary"><?php echo $title ?></h5>
</div>
<div class="card border-top border-0 border-4 border-primary">
    <div class="card-body">
        <div class="table-responsive">
        <table class="table mb-3 text-center align-middle" >
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th><?php echo $l_date ?></th>
                    <th><?php echo $l_member_code ?></th>
                    <th><?php echo $l_title ?></th>
                    <th><?php echo $l_money ?></th>
                    <th><?php echo $l_status ?></th>
                    <th><?php echo $l_manage ?></th>
                </tr>
            </thead>
            <tbody>
            <?php
                
            $empty = mysqli_num_rows($query);
            if ($empty > 0) {
                $i = 0;
                while ($data = mysqli_fetch_array($query)) { 
                    $i++;

                    $pay_id     = $data['pay_id'];
                    $member_code= $data['member_code'];
                    $pay_create = datethai($data['pay_create'], 0, $lang);
                    $pay_title  = $data['pay_title'];
                    $pay_money  = number_format($data['pay_money'], 2) . $l_bath;
                    $pay_status = $data['pay_status'];
                    $pay_url    = "admin.php?page=detail&action=pay&pay_id=$pay_id"; 

                    ?>
                    <tr>
                        <td><?php echo $i + $start ?></td>
                        <td><?php echo $pay_create ?></td>
                        <td><?php echo $member_code ?></td>
                        <td>
                            <a href="<?php echo $pay_url ?>" target="_blank" title="รายละเอียด"><?php echo $pay_title ?></a>
                        </td>
                        <td><?php echo $pay_money ?></td>
                        <td>
                            <?php 
                            if     ($pay_status == 1) {echo "<font color=green>$l_order_status4</font>";} 
                            elseif ($pay_status == 2) {echo "<font color=red>$l_order_status1</font>";} 
                            elseif ($pay_status == 0) {echo "$l_order_status0";}
                            ?>
                        </td>
                        <td>
                            <?php if ($pay_status == 0) { ?>

                                <a href="process/setting_pay.php?action=confirm_pay_refer&pay_id=<?php echo $pay_id ?>" onClick="javascript:return confirm('Confirm ?');"><i class="bx bx-check-circle me-1 font-24 text-success"></i></a>

                                <a href="process/setting_pay.php?action=cancel_pay_refer&pay_id=<?php echo $pay_id ?>" onClick="javascript:return confirm('Cancel ?');"><i class="bx bx-x-circle me-1 font-24 text-danger"></i></a>

                            <?php } ?>

                            <a href="admin_print.php?action=pay_refer&pay_id=<?php echo $pay_id ?>" target="_blank" title="Report"><i class="bx bx-printer me-1 font-22 text-primary"></i></a>
                        </td>
                    </tr>
            <?php } } else { echo "<tr><td colspan='7'>$l_notfound</td></tr>"; } ?>
            </tbody>
        </table>
        </div>
        <?php 
        $url = "admin.php?page=admin_pay";
        pagination ($connect, $sql, $perpage, $page_id, $url); ?>
    </div>
</div>