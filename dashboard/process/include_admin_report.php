<div class="col-12 col-sm-7 mx-auto">
<div class="card border-top border-0 border-4 border-primary">
	<div class="card-body">
		<div class="card-title d-flex align-items-center">
			<div><i class="bx bx-cabinet me-1 font-22 text-primary"></i></div>
			<h5 class="mb-0 text-primary"><?php echo $l_com_old ?></h5>
			<button type="button" data-bs-toggle="modal" data-bs-target="#backdate_search" class="btn btn-primary btn-sm ms-auto"><i class='bx bx-search'></i> <?php echo $l_search ?></button>
		</div>
		<hr>
		<div class="table-responsive">
			<table class="table mb-0 text-center align-middle" >
            	<thead class="table-light">
					<tr>
						<th><?php echo $l_com_round ?></th>
						<th><?php echo $l_com_datecut ?></th>
						<th><?php echo $l_com_money ?></th>
						<th><?php echo $l_member ?></th>
						<th><?php echo $l_manage ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
	                $sql    	= "SELECT * FROM system_report WHERE report_type = $report_type";
	                $order_by 	= " ORDER BY report_round DESC ";
	                $query  	= mysqli_query($connect, $sql . $order_by . $limit) or die(mysqli_error($connect));
					$empty  	= mysqli_num_rows($query);
					if ($empty > 0) {
					 	while ($data = mysqli_fetch_array($query)) { 

					 		$report_id 		= $data['report_id'];
					 		$report_round 	= number_format($data['report_round']);
					 		$report_create 	= datethai($data['report_create'], 0, $lang);
					 		$report_count 	= number_format($data['report_count']);
					 		$report_point 	= number_format($data['report_point'], 2) . $l_bath;
					 		
					 		$excel_url 	= "admin_excel.php?action=finance&type=com&report_id=$report_id";
					 		$report_url = "admin_print.php?action=finance&type=report&report_id=$report_id";
					 		$detail_url = "admin.php?page=detail&action=finance&type=report&report_id=$report_id";

					 		?>
						 	<tr>
								<td><?php echo $report_round ?></td>
								<td>
									<a href="<?php echo $detail_url ?>" title="Detail" target="_blank">
									<?php echo $report_create ?></a>
								</td>
								<td><?php echo $report_point ?></td>
								<td><?php echo $report_count ?></td>
								<td>
									<a href="<?php echo $excel_url ?>" target="_blank" title="Excel"><i class="bx bx-table me-1 font-22 text-primary me-1"></i></a>

									<a href="<?php echo $report_url ?>" target="_blank" title="Report"><i class="bx bx-printer me-1 font-22 text-primary"></i></a>
								</td>
							</tr>
					<?php } } else { echo "<tr><td colspan='5'>$l_notfound</td></tr>"; } ?>
				</tbody>
			</table>
		</div>
		<?php 
        $url = "admin.php?page=admin_report_commission";
        pagination ($connect, $sql, $perpage, $page_id, $url); ?>
	</div>
</div>
</div>

<!-- Back date Search Modal -->
<div class="modal fade" id="backdate_search" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"><i class='bx bx-search'></i> <?php echo $l_search ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start">
                <form class="row g-3" action="admin.php"  	method="get" target="_blank">
                	<input type="hidden" name="page" 	  	value="detail">
                	<input type="hidden" name="report_type" value="<?php echo $report_type ?>">
                	<input type="hidden" name="member_id" 	id="direct_id2">
                	<input type="hidden" name="type" 		value="report">
                    <div class="col-12 col-sm-6">
                        <label  class="form-label"><?php echo $l_member_code ?> <font color="red">*</font></label>
                        <input type="text" class="form-control" id="check_member2" placeholder="<?php echo $l_member_code ?>" required>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label"><?php echo $l_member_name ?> <font color="red">*</font></label>
                        <input type="text" class="form-control" id="direct_name2" placeholder="กรุณากรอกรหัสผู้แนะนำให้ถูกต้อง" readonly>
                    </div>
                    <div class="col-12 mt-3">
                        <button name="action" value="finance_search" class="btn btn-primary btn-sm"><?php echo $l_search ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>