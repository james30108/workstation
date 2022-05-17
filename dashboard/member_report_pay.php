<?php isset($_GET["status"]) ? alert ($_GET["status"], $_GET["message"], $lang) : false; ?>
<title><?php echo $l_pay_report ?></title>
<div class="card-title d-flex align-items-center mb-3">
    <div><i class="bx bx-credit-card me-1 font-22 text-primary"></i></div>
    <h5 class="mb-0 text-primary"><?php echo $l_pay_report ?></h5>
</div>
<div class="card border-top border-0 border-4 border-primary">
    <div class="card-body">
        <div class="table-responsive">
        <table class="table mb-0 text-center align-middle" >
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th><?php echo $l_date ?></th>
                    <th><?php echo $l_type ?></th>
                    <th><?php echo $l_title ?></th>
                    <th><?php echo $l_money ?></th>
                    <th><?php echo $l_status ?></th>
                    <th><?php echo $l_report ?></th>
                </tr>
            </thead>
            <tbody>
                <?php

                $date_start = isset($_GET['date_start']) ?  $_GET['date_start'] : false;
                $date_end   = isset($_GET['date_end']) ?    $_GET['date_end']   : false;
                $where      = "";

                if ($date_start != false || $date_end != false) {
                    $where  = " AND (pay_create BETWEEN '$date_start' AND '$date_end 23:59')";
                }
                $sql    = "SELECT system_member.*, system_pay_refer.* 
                    FROM system_member
                    INNER JOIN system_pay_refer ON (system_member.member_id = system_pay_refer.pay_member)
                    WHERE (member_id = '$member_id')" . $where;
                $query  = mysqli_query($connect, $sql . $limit);
                $empty  = mysqli_num_rows($query);
                if ($empty > 0) {
                    $i = 0;
                    while ($data  = mysqli_fetch_array($query)) {
                        $i++; 

                        $pay_id     = $data['pay_id'];
                        $pay_create = datethai($data['pay_create'], 0, $lang);
                        $pay_type   = $data['pay_type'] == 0 ? "ชำระเงินสินค้า" : "ชำระเงินค่าอื่นๆ" ;
                        $pay_title  = $data['pay_title'];
                        $pay_money  = number_format($data['pay_money'], 2) . $l_bath;
                        $pay_status = $data['pay_status'];
                        $pay_url    = "$page_type?page=detail&action=pay&pay_id=$pay_id"; 
                        ?>
                        <tr>
                            <td><?php echo $i + $start ?></td>
                            <td><?php echo $pay_create ?></td>
                            <td><?php echo $pay_type ?></td>
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
                                <a href="admin_print.php?action=pay_refer&pay_id=<?php echo $pay_id ?>" target="_blank" title="พิมพ์รายงาน"><i class="bx bx-printer me-1 font-22 text-primary"></i></a>
                            </td>
                        </tr>
                <?php } } else { echo "<tr><td colspan='7'>$l_notfound</td></tr>"; } ?>
            </tbody>
        </table>
        </div>
        <?php
        $url = "member.php?page=member_report_pay_refer";
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
        <form action="member.php" method="get" class="row g-3">
            <input type="hidden" name="page" value="member_report_pay_refer">
            <div class="col-12">
                <label class="form-label"><?php echo $l_datestart ?></label>
                <input type="date" class="form-control" name="date_start" value="<?php echo $date_start ?>">
            </div>
            <div class="col-12">
                <label class="form-label"><?php echo $l_dateend ?></label>
                <input type="date" class="form-control" name="date_end" value="<?php echo $date_end ?>">
            </div>
            <div class="col-12 mt-5">
                <button name="action" value="search" class="btn btn-primary btn-sm"><?php echo $l_search ?></button>
                <a href="member.php?page=member_report_pay" class="btn btn-secondary btn-sm"><?php echo $l_cancel ?></a>
            </div>
        </form>
    </div>
</div>