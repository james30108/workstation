<?php
$action             = isset($_GET['action'])              ? $_GET['action']             : false;
$smember_code       = isset($_GET['member_code'])         ? $_GET['member_code']        : false;
$swithdraw_create   = isset($_GET['withdraw_create'])     ? $_GET['withdraw_create']    : false;
$swithdraw_status   = isset($_GET['withdraw_status'])     ? $_GET['withdraw_status']    : false;

// where condition
$where      = "";

if ($smember_code != "")        { $where .= " AND (member_code IN ($smember_code))"; }
if ($swithdraw_create != "")    { $where .= " AND (withdraw_create LIKE '%$swithdraw_create%')"; }
if ($swithdraw_status != 'all' && $action == "search") { $where .= " AND (withdraw_status = '$swithdraw_status')"; }

// query condition
$sql = "SELECT system_member.*, system_withdraw.*
    FROM system_withdraw
    INNER JOIN system_member ON (system_member.member_id = system_withdraw.withdraw_member)
    WHERE withdraw_cut = 0" . $where;

isset($_GET["status"]) ? alert ($_GET["status"], $_GET["message"], $lang) : false;
?>
<title><?php echo $l_withdraw ?></title>
<div class="card-title d-flex align-items-center mb-3">
    <div><i class="bx bx-file me-1 font-22 text-primary"></i>
    </div>
    <h5 class="mb-0 text-primary"><?php echo $l_withdraw ?></h5>
</div>
<div class="card border-top border-0 border-4 border-primary">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table text-center align-middle mb-3">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th><?php echo $l_date ?></th>
                        <th><?php echo $l_member_code ?></th>
                        <th><?php echo $l_member_name ?></th>
                        <th><?php echo $l_bankname ?></th>
                        <th><?php echo $l_bankid ?></th>
                        <th><?php echo $l_com_pay ?></th>
                        <th><?php echo $l_statusnmanage ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $order_by   = " ORDER BY FIELD (withdraw_status, 0, 1, 2), withdraw_create DESC ";
                    $query      = mysqli_query($connect, $sql . $order_by . $limit) or die(mysqli_error($connect));
                    $empty      = mysqli_num_rows($query);
                    if ($empty > 0) {
                        $i = 0;
                        while ($data = mysqli_fetch_array($query)) {
                            $i++; 
                            $withdraw_id     = $data['withdraw_id'];
                            $withdraw_status = $data['withdraw_status'];
                            $member_id       = $data['member_id'];
                            $withdraw_point  = number_format($data['withdraw_point'], 2) . $l_bath;
                            $withdraw_fullpoint = $data['withdraw_fullpoint'];
                            if ($data['member_bank_name'] != 0) {

                                $bank_id= $data['member_bank_name'];
                                $query2 = mysqli_query($connect, "SELECT * FROM system_bank WHERE bank_id = '$bank_id' ");
                                $data2  = mysqli_fetch_array($query2);
                                $bank   = $data2['bank_name_th'];
        
                            }
                            else { $bank = ""; }
                            
                            ?>
                            <tr>
                            <td><?php echo $i + $start ?></td>
                            <td><?php echo datethai($data['withdraw_create'], 2, $lang); ?></td>
                            <td><?php echo $data['member_code'] ?></td>
                            <td><?php echo $data['member_name'] ?></td>
                            <td><?php chkEmpty ($bank) ?></td>
                            <td><?php chkEmpty ($data['member_bank_id']) ?></td>
                            <td><?php echo $withdraw_point ?></td>
                            <td>
                                <div class="d-flex">
                                <div class="d-flex align-items-center mx-auto">
                                    <?php if ($withdraw_status == 0) { ?>
                                        <a <?php echo "href='process/setting_withdraw.php?action=confirm_withdraw&withdraw_id=$withdraw_id&member_id=$member_id'"; ?> onclick="javascript:return confirm('Confirm ?')" title="Confirm"><i class="bx bx-archive-in mx-1 font-22 text-success"></i></a>
                                        <hr class="vr mx-3">
                                        <a <?php echo "href='process/setting_withdraw.php?action=cancel_withdraw&withdraw_id=$withdraw_id&member_id=$member_id&withdraw_fullpoint=$withdraw_fullpoint'"; ?> onclick="javascript:return confirm('Confirm ?')" title="Cancel"><i class="bx bx-trash mx-1 font-22 text-danger"></i></a>
                                    <?php } 
                                    elseif ($withdraw_status == 1) { echo "<font color=green>$l_order_status4</font>"; } 
                                    elseif ($withdraw_status == 2) { echo "<font color=red>$l_order_status1</font>"; } 
                                    ?>
                                </div>
                                </div>
                            </td>
                            </tr>
                            
                        <?php } } else { echo "<tr><td colspan='8'>$l_notfound</td></tr>"; } ?>
                </tbody>
            </table>
        </div>
        <?php
        $url = "admin.php?page=admin_withdraw&member_code=$smember_code&withdraw_create=$swithdraw_create&withdraw_status=$swithdraw_status&action=search";
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
            <input type="hidden" name="page" value="admin_withdraw">
                <div class="col-12">
                    <label class="form-label"><?php echo $l_member_code ?></label>
                    <input type="text" class="form-control" name="member_code" placeholder="<?php echo $l_member_code ?>" value="<?php echo $smember_code ?>">
                </div>
            <div class="col-12">
                <label class="form-label"><?php echo $l_create ?></label>
                <input type="date" class="form-control" name="withdraw_create" value="<?php echo $swithdraw_create ?>">
            </div>
            <div class="col-12">
                <label class="form-label"><?php echo $l_status ?></label>
                <select name="withdraw_status" class="form-control">
                    <option value="all" <?php echo $swithdraw_status == 'all' || $swithdraw_status == false ? "selected" : false; ?>><?php echo $l_all ?></option>
                    <option value="0" <?php echo $swithdraw_status == '0' ? "selected" : false; ?>><?php echo $l_order_status0 ?></option>
                    <option value="1" <?php echo $swithdraw_status == '1' ? "selected" : false; ?>><?php echo $l_order_status4 ?></option>
                    <option value="2" <?php echo $swithdraw_status == '2' ? "selected" : false; ?>><?php echo $l_order_status1 ?></option>
                </select>
            </div>
            <div class="col-12">
                <button class="btn btn-success btn-sm" name="action" value="search"><?php echo $l_save ?></button>
                <a href="admin.php?page=admin_withdraw" class="btn btn-secondary btn-sm" type="reset"><?php echo $l_cancel ?></a>
            </div>
        </form>
    </div>
</div>
