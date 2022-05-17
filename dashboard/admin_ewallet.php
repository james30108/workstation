
<?php isset($_GET["status"]) ? alert ($_GET["status"], $_GET["message"], $lang) : false; ?>
<title><?php echo $l_etopup ?></title>
<div class="card-title d-flex align-items-center mb-3">
    <div><i class="bx bx-credit-card-front me-1 font-22 text-primary"></i></div>
    <h5 class="col mb-0 text-primary"><?php echo $l_etopup ?></h5>
    
    <!-- Button trigger modal -->
    <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-primary btn-sm ms-auto d-none d-sm-block"><?php echo $l_etopup ?></button>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?php echo $l_etopup ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-start">
                    <form class="row g-3" action="process/setting_ewallet.php" method="post">
                        <input type="hidden" name="page" value="<?php echo $page_type ?>">
                        <div class="col-12">
                            <label  class="form-label"><?php echo $l_money ?> <font color="red">*</font></label>
                            <input type="text" class="form-control" name="deposit_money" placeholder="<?php echo $l_money ?>" required>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label  class="form-label"><?php echo $l_member_code ?> <font color="red">*</font></label>
                            <input type="text" class="form-control" name="member_code" id="check_member" placeholder="<?php echo $l_member_code ?>" required>
                        </div>
                        <div class="col-12 col-sm-6">
                            <input type="hidden" name="deposit_member" id="direct_id">
                            <label class="form-label"><?php echo $l_member_name ?> <font color="red">*</font></label>
                            <input type="text" class="form-control" id="direct_name" placeholder="กรุณากรอกรหัสผู้แนะนำให้ถูกต้อง" required onkeypress="return false;">
                        </div>
                        <div class="col-12">
                            <button name="action" value="insert" class="btn btn-primary btn-sm"><?php echo $l_save ?></button>
                        </div>
                    </form>
                </div>
            </div>
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
                    <th><?php echo $l_create; ?></th>
                    <th><?php echo $l_member_name; ?></th>
                    <th><?php echo $l_money; ?></th>
                    <th><?php echo $l_date; ?></th>
                    <th><?php echo $l_time; ?></th>
                    <th><?php echo $l_statusnmanage; ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql        = "SELECT system_member.*, system_deposit.* 
                    FROM system_member
                    INNER JOIN system_deposit ON (system_member.member_id = system_deposit.deposit_member)
                    WHERE deposit_status = 0";
                $order_by   = " ORDER BY deposit_status ASC, deposit_create DESC ";
                $query      = mysqli_query($connect, $sql . $order_by . $limit) or die(mysqli_error($connect)); 
                $empty      = mysqli_num_rows($query);
                if ($empty > 0) {
                    
                    $i     = 0;
                    while ($data = mysqli_fetch_array($query)) {
                        
                        $deposit_id     = $data['deposit_id'];
                        $deposit_create = datethai($data['deposit_create'], 0, $lang);
                        $deposit_date   = datethai($data['deposit_date'], 0, $lang);
                        $member_name    = $data['member_name'];
                        $deposit_money  = number_format($data['deposit_money'], 2) . $l_bath;
                        $deposit_time   = $data['deposit_time'];
                        $deposit_status = $data['deposit_status'];

                        $success_url   = "process/setting_ewallet.php?action=confirm_deposit&deposit_id=$deposit_id";
                        $cancel_url    = "process/setting_ewallet.php?action=cancel_deposit&deposit_id=$deposit_id";
                        $detail_url    = "$page_type?page=detail&action=ewallet&deposit_id=$deposit_id";

                        $i++;
                        ?>
                        <tr>
                            <td><?php echo $i + $start ?></td>
                            <td><a href="<?php echo $detail_url ?>" target="_blank"><?php echo $deposit_create ?></a></td>
                            <td><?php echo $member_name ?></td>
                            <td><?php echo $deposit_money ?></td>
                            <td><?php echo $deposit_date ?></td>
                            <td><?php echo $deposit_time ?></td>
                            <td>
                            <?php 
                            if     ($deposit_status == 0) { ?>

                                <a href="<?php echo $success_url ?>" onclick="javascript:return confirm('Confirm ?')"><i class="bx bx-archive-in me-1 font-22 text-success"></i></a>

                                <a href="<?php echo $cancel_url ?>" onclick="javascript:return confirm('Cancel ?')"><i class="bx bx-trash me-1 font-22 text-danger"></i></a>

                            <?php }
                            elseif ($deposit_status == 1) { echo "<font color=green>Success</font>"; }
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
        $url = "admin.php?page=admin_ewallet";
        pagination ($connect, $sql, $perpage, $page_id, $url); ?>
    </div>
</div>