<?php 

// status cut
$query 	= mysqli_query($connect, "SELECT * FROM system_report WHERE (report_type = 90) AND (report_create LIKE '%$today%')");
$cut  	= mysqli_fetch_array($query);

$sql 	= "SELECT system_member.*, system_liner.*, system_class.* 
    FROM system_liner
    INNER JOIN system_member ON (system_member.member_id = system_liner.liner_member)
    INNER JOIN system_class  ON (system_liner.liner_etc = system_class.class_id)
    WHERE liner_etc >= 5";
$query  = mysqli_query($connect, $sql);
$count_1 = 0;
$count_2 = 0;
$count_3 = 0;

while ($data = mysqli_fetch_array($query)) {

    $upline_id = $data['liner_member'];
    $query2 = mysqli_query($connect, "SELECT * FROM system_liner WHERE (liner_direct = '$upline_id') AND (liner_etc >= 5) ");
    $count  = mysqli_num_rows($query2);

    if ($count >= 5)  { $count_1++; }
    if ($count >= 10) { $count_2++; }
    if ($count >= 20) { $count_3++; }

}

$point_bonus_1 	= $bonus_class > 0 && $count_1 > 0 ? (40 * $bonus_class) / 100 / $count_1 : 0;
$point_bonus_2 	= $bonus_class > 0 && $count_2 > 0 ? (5  * $bonus_class) / 100 / $count_2 : 0;
$point_bonus_3 	= $bonus_class > 0 && $count_3 > 0 ? (5  * $bonus_class) / 100 / $count_3 : 0;

$total_point 	= $point_bonus_1 + $point_bonus_2 + $point_bonus_3;

$cut_url 		= "process/project/$system_style/process/system_cut.php?action=cut_pool&count_1=$count_1&count_2=$count_2&count_3=$count_3";

if (isset($_GET['action']) && $_GET['action'] == 'com') { ?>

	<title>Pool Bonus</title>
	<div class="col-12 col-sm-7 mx-auto">
	<div class="card border-top border-0 border-4 border-warning">
		<div class="card-body">
			<div class="card-title d-flex align-items-center">
	            <div><i class="bx bx-star me-1 font-22 text-primary"></i></div>
	            <h5 class="mb-0 text-primary">เงินปันผลรอบวีไอพีรอบปัจจุบัน (พูลโบนัส)</h5>

	            <!-- Button trigger modal -->
	            <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-primary btn-sm ms-auto d-none d-sm-block">กำหนดวงเงินปันผล</button>

	            <!-- Modal -->
	            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	                <div class="modal-dialog">
	                    <div class="modal-content">
	                        <div class="modal-header">
	                        <h5 class="modal-title" id="exampleModalLabel">กำหนดวงเงินปันผล</h5>
	                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	                        </div>
	                        <div class="modal-body text-start">
	                            <form class="row g-3" action="process/project/<?php echo $system_style ?>/process/system_cut.php" method="post">
	                                <input type="hidden" name="count_1" value="<?php echo $count_1 ?>">
	                                <input type="hidden" name="count_2" value="<?php echo $count_2 ?>">
	                                <input type="hidden" name="count_3" value="<?php echo $count_3 ?>">
	                                <div class="col-12">
	                                    <label  class="form-label">จำนวนเงิน <font color="red">*</font></label>
	                                    <input type="text" class="form-control" name="commission" placeholder="จำนวนเงิน" required>
	                                </div>
	                                <div class="col-12">
	                                    <button name="action" value="insert_pool" class="btn btn-primary btn-sm">บันทึก</button>
	                                </div>
	                            </form>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>
	        <hr>
	        <table class="table table-borderless table-sm">
	            <tr>
	                <th width="120">เงินปันผล</th>
	                <td><?php echo "$bonus_class_2 บาท"; ?></td>
	            </tr>
	            <tr>
	                <th>สถานะ</th>
	                <td><?php echo $cut ? "<font color=green>ตัดยอดในเดือนนี้แล้ว</font>" : "<font color=red>ยังไม่ได้ตัดยอดในเดือนนี้</font>"; ?>
	                </td>
	            </tr>
	            <?php if ($report_min > 1) { ?>
					<tr>
						<th><?php echo $l_com_min ?></th>
						<td><?php echo number_format($report_min, 2) . $l_bath ?></td>
					</tr>
					<?php } if ($report_max > 0) { ?>
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
	        </table>
	        <font color="red">* กรุณากดตัดยอดก่อนวันที่ 1 ของเดือนถัดไป และต้องทำการกดตัดยอดเงินปันผลธรรมดาก่อน</font>
	        <div class="table-responsive">
	            <table class="table my-3 text-center align-middle">
                	<thead class="table-light">
	                    <tr>
	                        <th>รอบปัจจุบัน</th>
	                        <th>VIP มากกว่า 5</th>
	                        <th>VIP มากกว่า 10</th>
	                        <th>VIP มากกว่า 20</th>
	                        <th>เครื่องมือ</th>
	                    </tr>
	                </thead>
	                <tbody>
	                    <tr>
	                        <td><?php echo report_now ($connect, 90); ?></td>
	                        <td><?php echo $count_1 ;?> คน</td>
	                        <td><?php echo $count_2 ;?> คน</td>
	                        <td><?php echo $count_3 ;?> คน</td>
	                        <td>
	                            <?php if ($count_1 > 0 && !$cut) { ?>

	                                <a href="<?php echo $cut_url ?>" title="Cut" onclick="javascript:return confirm('Confirm ?');"><i class="bx bx-cut me-1 font-22 text-primary"></i></a>
	                                
	                                <a href="admin.php?page=special&action=com_detail" title="Detail" target="_blank"><i class="bx bx-detail me-1 font-22 text-primary"></i></a>

	                            <?php } else { ?>

	                                <i class="bx bx-cut me-1 font-22 text-secondary"></i>
	                                <i class="bx bx-detail me-1 font-22 text-secondary"></i>

	                            <?php } ?>
	                        </td>
	                    </tr>
	                </tbody>
	            </table>
	        </div>
	    </div>
	</div>
	</div>

	<?php 
	$report_type = 90;
	include("process/include_admin_report.php");
	?>
	
<?php } elseif (isset($_GET['action']) && $_GET['action'] == 'com_detail') { ?>

	<title>รายละเอียดตัดยอดเงินปัจจุบัน</title>
    <div class="card border-top border-0 border-4 border-warning">
		<div class="card-body p-1 pt-3 pb-3 p-sm-3">
			<div class="card-title d-flex align-items-center">
				<div><i class="bx bx-detail me-1 font-22 text-primary"></i></div>
				<h5 class="mb-0 text-primary">รายละเอียดตัดยอดเงินปัจจุบัน (พูลโบนัส)</h5>
			</div>
			<hr>
			<table class="table table-borderless table-sm">
				<tr>
					<th width="200">เงินปันผล</th>
					<td><?php echo "$bonus_class_2 บาท"; ?></td>
				</tr>
				<tr>
					<th>VIP 5 คนขึ้นไป</th>
					<td><?php echo "$count_1 คน / $point_bonus_1 บาท" ;?></td>
				</tr>
				<tr>
					<th>VIP 10 คนขึ้นไป</th>
					<td><?php echo "$count_2 คน / $point_bonus_2 บาท" ;?></td>
				</tr>
				<tr>
					<th>VIP 20 คนขึ้นไป</th>
					<td><?php echo "$count_3 คน / $point_bonus_3 บาท" ;?></td>
				</tr>
				<tr>
					<th>ยอดรวมที่ต้องจ่ายทั้งหมด</th>
					<td><?php echo $total_point ?> บาท</td>
				</tr>
			</table>
            <table class="table mb-3 text-center align-middle">
                <thead class="table-light">
                	<tr>
                    	<th><?php echo $l_number ?></th>
                        <th><?php echo $l_member_code ?></th>
                        <th><?php echo $l_member_name ?></th>
                        <th><?php echo $l_idcard ?></th>
                        <th><?php echo $l_address ?></th>
                        <th><?php echo $l_bankname ?></th>
                        <th><?php echo $l_bankid ?></th>
                        <th>VIP</th>
                        <th><?php echo $l_com_pay ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php
			    $query 	= mysqli_query($connect, $sql . $limit);
			    $i 		= 0;
			    while ($data = mysqli_fetch_array($query)) {

			    	$upline_id      = $data['liner_member'];

			        $query2 = mysqli_query($connect, "SELECT * FROM system_liner WHERE (liner_direct = '$upline_id') AND (liner_etc >= 5) ");
			        $count_downline = mysqli_num_rows($query2);
			        $data_downline  = mysqli_fetch_array($query2);

			        if ($data['member_bank_name'] != '') {

                        $bank_id= $data['member_bank_name'];
                        $query3 = mysqli_query($connect, "SELECT * FROM system_bank WHERE bank_id = '$bank_id' ");
                        $data3  = mysqli_fetch_array($query3);
                        $bank   = $data3['bank_name_th'];

                    }
                    else { $bank = ""; }

			   		$i++;
			   		$commission = 0;
			   		
			   		if ($count_downline >= 5)  { $commission = $commission + $point_bonus_1; }
			   		if ($count_downline >= 10) { $commission = $commission + $point_bonus_2; }
			   		if ($count_downline >= 20) { $commission = $commission + $point_bonus_3; }

			   		if ($count_downline >= 5) { ?>
			        <tr>
			        	<td><?php echo $i + $start ?></td>
                        <td><?php echo $data['member_code'] ?></td>
                        <td><?php echo $data['member_name'] ?></td>
                        <td><?php chkEmpty ($data['member_code_id']) ?></td>
                        <td><?php echo address ($connect, $data['member_code'], 0, $lang) ?></td>
                        <td><?php chkEmpty ($bank) ?></td>
                        <td><?php chkEmpty ($data['member_bank_id']) ?></td>
                        <td><?php echo $count_downline ?> คน</td>
                        <td><?php echo report_final ($commission, $report_fee1, $report_fee2, $l_bath, $report_max)[0] ?></td>
			        </tr>
			   		<?php } } ?>
                </tbody>
            </table>
            <?php 
            $url = "admin.php?page=special&action=com_detail";
            pagination ($connect, $sql, $perpage, $page_id, $url); ?>
		</div>
	</div>

<?php } ?>