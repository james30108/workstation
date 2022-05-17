<?php if (isset($_GET['action']) && $_GET['action'] == 'commission_detail') {
	$query = mysqli_query($connect, "SELECT SUM(report_detail_point) AS sum_point, system_report.*, system_report_detail.*, system_member.*, system_liner.*
		FROM system_report
		INNER JOIN system_report_detail ON (system_report.report_id = system_report_detail.report_detail_main)
		INNER JOIN system_member ON (system_report_detail.report_detail_link = system_member.member_id)
		INNER JOIN system_liner ON (system_member.member_id = system_liner.liner_member)
		WHERE (liner_type = 1) AND (report_id = '".$_GET['report_id']."') 
		GROUP BY report_detail_main ");
	$data  = mysqli_fetch_array($query);
	?>
	<div class="col-12 col-sm-7 mx-auto">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page"><a href="admin.php">หน้าแรก</a></li>
            <li class="breadcrumb-item" aria-current="page"><a href="admin.php?page=admin_report_commission_vip">คอมมิชชั่น</a></li>
            <li class="breadcrumb-item active" aria-current="page">รอบปัจจุบัน</li>
        </ol>
    </nav>
    <div class="card border-top border-0 border-4 
		<?php
		if ($data['bill_type'] == 0) { echo " border-primary"; } 
		else {	echo " border-warning"; }
		?>">
	<div class="card-body p-1 pt-3 pb-3 p-sm-3">
		<div class="card-title d-flex align-items-center">
			<div><i class="bx bx-detail me-1 font-22 text-primary"></i></div>
			<h5 class="mb-0 text-primary">รายละเอียดค่าคอมมิชชั่นย้อนหลัง
				<?php
				if ($data['bill_type'] == 0) { echo " (ผังสมาชิก)"; } 
				else {	echo " (ผังคอมมิชชั่น)"; }
				?>
			</h5>
		</div>
		<hr>
		<table class="mb-3">
			<tbody>
				<tr>
					<th width="150">รอบที่</th>
					<td><?php echo $data['bill_round'] ?></td>
				</tr>
				<tr>
					<th>วันที่</th>
					<td><?php echo date("d-m-Y", strtotime($data['bill_create'])) ?></td>
				</tr>
				<tr>
					<th>ยอดเงินรวม</th>
					<td><?php echo $data['sum_point'] ?> บาท</td>
				</tr>
				<tr>
					<th>จัดการ</th>
					<td>
						<a href="admin.php?page=admin_report_commission&action=report_excel&bill_id=<?php echo $data['bill_id'] ?>&name=รายงานตัดยอดเงิน วันที่ <?php echo $data['bill_create'] ?>" target="_blank" title="โหลด excel"><i class="bx bx-table me-1 font-22 text-primary"></i></a>&nbsp;&nbsp;
						<a href="admin_report_commission_print.php?action=commisison_vip&bill_id=<?php echo $data['bill_id'] ?>" target="_blank" title="พิมพ์รายงาน"><i class="bx bx-printer me-1 font-22 text-primary"></i></a>
					</td>
				</tr>
			</tbody>
		</table>
        <table class="table table-bordered table-sm text-center align-middle">
    		<thead class="bg-warning align-middle" style="height: 40px">
            	<tr>
                    <th>รหัสสมาชิก</th>
                    <th>ชื่อในระบบ</th>
                    <?php if ($data['bill_type'] == 1) { echo "<th>รหัสผังคอมมิชชั่น</th>"; } ?>
                    <th>คอมมิชชั่น</th>
                </tr>
            </thead>
            <tbody>
            <?php
            if ($data['bill_type'] == 0) {
            	$query = mysqli_query($connect, "SELECT system_bill.*, system_bill_detail.*, system_member.*, system_liner.*
					FROM system_bill
					INNER JOIN system_bill_detail ON (system_bill.bill_id = system_bill_detail.bill_detail_bill)
					INNER JOIN system_member ON (system_bill_detail.bill_detail_member = system_member.member_id)
					INNER JOIN system_liner ON (system_member.member_id = system_liner.liner_member)
					WHERE (liner_type = 1) AND (bill_id = '".$_GET['bill_id']."') ");
            }
            else {
				$query = mysqli_query($connect, "SELECT SUM(bill_detail_point) AS sum_point, system_bill.*, system_bill_detail.*, system_member.*, system_commission_liner.*
					FROM system_bill
					INNER JOIN system_bill_detail ON (system_bill.bill_id = system_bill_detail.bill_detail_bill)
					INNER JOIN system_member ON (system_bill_detail.bill_detail_member = system_member.member_id)
					INNER JOIN system_commission_liner ON (system_member.member_id = system_commission_liner.commission_liner_member)
					WHERE (commission_liner_position = 1) AND (bill_id = '".$_GET['bill_id']."') ");
            }
			while ($data = mysqli_fetch_array($query)) { ?>
                <tr>
                    <td><?php echo $data['member_code'] ?></td>
                    <td><?php echo $data['member_name'] ?></td>
                    <?php if ($data['bill_type'] == 1) { echo "<td>" . $data['commission_liner_code'] . "</td>"; } ?>
                    <td><?php echo number_format($data['bill_detail_point'], 2) ?> บาท</td>
                </tr>
			<?php } ?>
            </tbody>
        </table>
	</div>
	</div>
	</div>
<?php } else { ?>
	<div class="row">
	    <div class="col-sm-6 col-12">
	    	<div class="card border-top border-0 border-4 border-primary">
			<div class="card-body p-1 pt-3 pb-3 p-sm-3">
				<div class="card-title d-flex align-items-center">
					<div><i class="bx bx-file me-1 font-22 text-primary"></i></div>
					<h5 class="mb-0 text-primary">เงินปันผล</h5>
				</div>
				<hr>
				<table class="mb-3">
					<tbody>
						<tr>
							<th>รอบที่</th>
							<td></td>
						</tr>
						<tr>
							<th>ยอดเงินรวมของรหัสบริษัท</th>
							<td></td>
						</tr>
					</tbody>
				</table>
				<div class="table-responsive">
					 <table class="table table-bordered table-sm text-center align-middle">
	                	<thead class="bg-warning align-middle" style="height: 40px">
							<tr>
								<th>รหัสสมาชิก</th>
								<th>ชื่อในระบบ</th>
								<th>เงินปันผลที่ได้รับ</th>
								<th>จำนวนสมาชิก</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							$query = mysqli_query($connect, "SELECT system_member.*, system_liner.* 
								FROM system_member
								INNER JOIN system_liner ON (system_member.member_id = system_liner.liner_member)
								WHERE liner_type = 1");
							while ($data  = mysqli_fetch_array($query)) { 
								$commission = $data['liner_point_mid'] + $data['liner_point_final'];
								?>
								<tr>
									<td><?php echo $data['member_code'] ?></td>
									<td><?php echo $data['member_name'] ?></td>
									<td><?php echo $commission ?> บาท</td>
									<td><?php echo $data['liner_count'] ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
			</div>
	    </div>
	    <div class="col-sm-6 col-12">
	       	<div class="card border-top border-0 border-4 border-warning">
			<div class="card-body p-1 pt-3 pb-3 p-sm-3">
				<div class="card-title d-flex align-items-center">
					<div><i class="bx bx-file me-1 font-22 text-primary"></i></div>
					<h5 class="mb-0 text-primary">เงินปันผลพิเศษ (Autorun)</h5>
				</div>
				<hr>
				<table class="mb-3">
					<tbody>
						<tr>
							<th>รอบที่</th>
							<td></td>
						</tr>
						<tr>
							<th>ยอดเงินรวมของรหัสบริษัท</th>
							<td></td>
						</tr>
					</tbody>
				</table>
				<div class="table-responsive">
					 <table class="table table-bordered table-sm text-center align-middle">
	                	<thead class="bg-warning align-middle" style="height: 40px">
							<tr>
								<th>รหัสสมาชิก</th>
								<th>รหัสในผัง</th>
								<th>เงินปันผลที่ได้รับ</th>
								<th>จำนวนสมาชิก</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							$query = mysqli_query($connect, "SELECT system_member.*, system_commission_liner.* 
								FROM system_member
								INNER JOIN system_commission_liner ON (system_member.member_id = system_commission_liner.commission_liner_member)
								WHERE commission_liner_position = 1");
							while ($data  = mysqli_fetch_array($query)) { 
								$commission = $data['commission_liner_point_mid'] + $data['commission_liner_point_final'];
								?>
								<tr>
									<td><?php echo $data['member_code'] ?></td>
									<td><?php echo $data['commission_liner_code'] ?></td>
									<td><?php echo $commission ?> บาท</td>
									<td><?php echo $data['commission_liner_count'] ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
			</div>
	    </div>
	    <div class="col-sm-6 col-12">
	    	<div class="card border-top border-0 border-4 border-primary">
			<div class="card-body p-1 pt-3 pb-3 p-sm-3">
				<div class="card-title d-flex align-items-center">
					<div><i class="bx bx-file me-1 font-22 text-primary"></i></div>
					<h5 class="mb-0 text-primary">เงินปันผลย้อนหลัง</h5>
				</div>
				<hr>
				<div class="table-responsive">
					 <table class="table table-bordered table-sm text-center align-middle">
	                	<thead class="bg-warning align-middle" style="height: 40px">
							<tr>
								<th>รอบวันที่</th>
								<th>เงินปันผล</th>
								<th>รายละเอียด</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$sql 	= "SELECT SUM(report_detail_point) AS sum_point, system_report.*, system_report_detail.*, system_member.*, system_liner.*
								FROM system_report
								INNER JOIN system_report_detail ON (system_report.report_id = system_report_detail.report_detail_main)
								INNER JOIN system_member ON (system_report_detail.report_detail_link = system_member.member_id)
								INNER JOIN system_liner ON (system_member.member_id = system_liner.liner_member)
								WHERE (liner_type = 1) AND (report_type = 0)
								GROUP BY report_detail_main";
							$query 	= mysqli_query($connect, $sql . $limit);
							while ($data  = mysqli_fetch_array($query)) { 
								?>
								<tr>
									<td><?php echo date("d-m-Y", strtotime($data['report_create'])) ?></td>
									<td><?php echo $data['sum_point'] ?> บาท</td>
									<td>
										<a href="admin.php?page=admin_report_commission_vip&action=commission_detail&report_id=<?php echo $data['report_id'] ?>" title="รายละเอียด"><i class="bx bx-detail me-1 font-22 text-primary"></i></a>
									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
			</div>
	    </div>
	    <div class="col-sm-6 col-12">
	       	<div class="card border-top border-0 border-4 border-warning">
			<div class="card-body p-1 pt-3 pb-3 p-sm-3">
				<div class="card-title d-flex align-items-center">
					<div><i class="bx bx-file me-1 font-22 text-primary"></i></div>
					<h5 class="mb-0 text-primary">เงินปันผลพิเศษย้อนหลัง</h5>
				</div>
				<hr>
				<div class="table-responsive">
					 <table class="table table-bordered table-sm text-center align-middle">
	                	<thead class="bg-warning align-middle" style="height: 40px">
							<tr>
								<th>รอบวันที่</th>
								<th>รหัสในผัง</th>
								<th>เงินปันผลที่ได้รับ</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$sql 	= "SELECT SUM(report_detail_point) AS sum_point, system_report.*, system_report_detail.*, system_member.*, system_liner.*
								FROM system_report
								INNER JOIN system_report_detail ON (system_report.report_id = system_report_detail.report_detail_main)
								INNER JOIN system_member ON (system_report_detail.report_detail_link = system_member.member_id)
								INNER JOIN system_liner ON (system_member.member_id = system_liner.liner_member)
								WHERE (liner_type = 1) AND (report_type = 1)
								GROUP BY report_detail_main";
							$query 	= mysqli_query($connect, $sql . $limit);
							while ($data  = mysqli_fetch_array($query)) { 
								?>
								<tr>
									<td><?php echo date("d-m-Y", strtotime($data['report_create'])) ?></td>
									<td><?php echo $data['sum_point'] ?> บาท</td>
									<td>
										<a href="admin.php?page=admin_report_commission_vip&action=commission_detail&report_id=<?php echo $data['report_id'] ?>" title="รายละเอียด"><i class="bx bx-detail me-1 font-22 text-primary"></i></a>
									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
			</div>
	    </div>
	</div>
<?php } ?>