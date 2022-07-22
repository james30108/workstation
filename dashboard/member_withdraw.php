<?php 

$query = mysqli_query($connect, "SELECT * FROM system_liner WHERE liner_member = '$member_id' ");
$data  = mysqli_fetch_array($query);
$liner_point  		= $data['liner_point'];
$member_status  	= $data['liner_status'];
$withdraw_url 		= "process/setting_withdraw.php?action=withdraw&member_id=$member_id&liner_point=$liner_point";

?>
<title><?php echo $l_withdraw ?></title>
<div class="col-12 col-sm-7 mx-auto">

	<?php isset($_GET["status"]) ? alert ($_GET["status"], $_GET["message"], $lang) : false; ?>

    <div class="card radius-10 overflow-hidden bg-gradient-Ohhappiness">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div>
                    <p class="mb-2 text-white"><?php echo $l_com ?></p>
                    <h5 class="mb-0 text-white">
                        <?php echo report_final ($liner_point, $report_fee1, $report_fee2, $l_bath, $report_max)[0] ;?>
                    </h5>
                </div>
                <?php if ($member_status != 2 && $liner_point >= $report_min && $liner_point > 0) { ?>
                    <div class="ms-auto">
                        <a href="<?php echo $withdraw_url ?>" class="d-flex align-items-center border border-white btn text-white" onclick="javascript:return confirm('Confirm ?');">
                            Withdraw <i class='bx bx-gift font-30'></i>
                        </a>
                    </div>
                <?php } elseif ($member_status == 2) { ?>
                    <div class="ms-auto text-white">
                        <div class="d-flex text-white align-items-center"> | Success & Waiting <i class='bx bx-coffee font-30'></i></div>
                    </div>
                <?php } else { ?>
                    <div class="ms-auto text-white"><i class='bx bx-dollar-circle font-30'></i></div>
                <?php } ?>
            </div>
        </div>
    </div>

	<p class='text-primary'><?php echo "$l_com_min $report_min $l_bath" ?></p>
	<div class="card border-top border-0 border-4 border-primary">
		<div class="card-body">
			<div class="card-title d-flex align-items-center">
				<div><i class="bx bx-cabinet me-1 font-22 text-primary"></i></div>
				<h5 class="mb-0 text-primary"><?php echo $l_com_old ?></h5>
			</div>
			<hr>
			<div class="table-responsive">
				<table class="table mb-0 text-center align-middle" >
	            	<thead class="table-light">
						<tr>
							<th>#</th>
							<th><?php echo $l_date ?></th>
							<th><?php echo $l_com_money ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
		                $sql    	= "SELECT system_report.*, system_report_detail.* 
		                	FROM system_report 
		                	INNER JOIN system_report_detail ON (system_report.report_id = system_report_detail.report_detail_main)
		                	WHERE (report_type = 2) AND (report_detail_link = '$member_id')
		                	ORDER BY report_round DESC";
		                $query  	= mysqli_query($connect, $sql . $limit) or die(mysqli_error($connect));
						$empty  	= mysqli_num_rows($query);
						$i 			= 0;
						if ($empty > 0) {
						 	while ($data = mysqli_fetch_array($query)) { $i++; ?>
							 	<tr>
									<td><?php echo $i + $start ?></td>
									<td><?php echo datethai($data['report_create'], 0, $lang) ?></td>
									<td><?php echo number_format($data['report_detail_point'], 2) . $l_bath ?></td>
								</tr>
						<?php } } else { echo "<tr><td colspan='5'>$l_notfound</td></tr>"; } ?>
					</tbody>
				</table>
			</div>
			<?php 
	        $url = "member.php?page=member_withdraw";
	        pagination ($connect, $sql, $perpage, $page_id, $url); ?>
		</div>
	</div>
</div>