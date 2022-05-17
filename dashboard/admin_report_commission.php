<?php 

// status cut
$query 	= mysqli_query($connect, "SELECT * FROM system_report WHERE (report_type = 0) AND (report_create LIKE '%$today%')");
$cut  	= mysqli_fetch_array($query);

$query  = mysqli_query($connect, "SELECT SUM(sum.sum_point_member) AS sum_point, COUNT(*) AS count
	FROM
	    (SELECT *, SUM(point_bonus) AS sum_point_member FROM system_point 
	    WHERE $point_type AND (point_status = 0) AND (point_member != 0)
	    GROUP BY point_member
	    HAVING (sum_point_member >= '$report_min')) AS sum
	    ") or die ($connect);
$data           = mysqli_fetch_array($query);
$count_member   = $data['count'];
$sum_point   	= $count_member > 0 ? $data['sum_point'] : 0;
$today     	    = datethai(date("Y-m-d"), 0, $lang);

$cut_url 		= "process/project/$system_style/process/system_cut.php?action=commission";
$now_url 		= $count_member == 0 ? $today : "<a href='admin.php?page=detail&action=finance&type=com' title='Detail' target='_blank'>$today</a>";
$i = 0;

?>

<title><?php echo $l_com ?></title>
<div class="col-12 col-sm-7 mx-auto">

<?php isset($_GET["status"]) ? alert ($_GET["status"], $_GET["message"], $lang) : false; ?>

<div class="card border-top border-0 border-4 border-primary">
	<div class="card-body">
		<div class="card-title d-flex align-items-center">
			<i class="bx bx-file me-1 font-22 text-primary"></i>
			<h5 class="mb-0 text-primary"><?php echo $l_com_now ?></h5>
			<button type="button" data-bs-toggle="modal" data-bs-target="#now_search" class="btn btn-primary btn-sm ms-auto"><i class='bx bx-search'></i> <?php echo $l_search ?></button>
		</div>
		<hr>
		<?php 
		if 		($report_type == 1 && $cut)  { echo "<p class='text-success'>ตัดยอดวันนี้ไปแล้ว</p>"; } 
        elseif 	($report_type == 1 && !$cut) { echo "<p class='text-danger'>ยังไม่ได้ตัดยอดในวันนี้</p>"; }
		?>
		<div class="table-responsive">
			<table class="table mb-0 text-center align-middle" >
            	<thead class="table-light">
					<tr>
						<th><?php echo $l_com_roundnow ?></th>
						<th><?php echo $l_com_datecut ?></th>
						<th><?php echo $l_com_money ?></th>
						<th><?php echo $l_numliner ?></th>
						<th><?php echo $l_manage ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?php echo report_now ($connect, 0); ?></td>
						<td><?php echo $now_url ?></td>
						<td><?php echo number_format($sum_point, 2) .$l_bath ?></td>
						<td><?php echo number_format($count_member) ?></td>
						<td>
							<?php 
							if ($count_member == 0) {

								echo $report_type == 1 ? "<i class='bx bx-cut me-1 font-22 text-secondary'></i>" : false;
								echo "<i class='bx bx-printer me-1 font-22 text-secondary'></i>";

							} else { 
								if ($report_type == 1 && !$cut) { ?>
									
									<a href="<?php echo $cut_url ?>" title="Cut" onclick="javascript:return confirm('Confirm ?');"><i class="bx bx-cut me-1 font-22 text-primary"></i></a>
								
							 	<?php } elseif ($report_type == 1 && $cut) { ?>

									<i class="bx bx-cut me-1 font-22 text-secondary"></i>

								<?php } ?>

								<a href="admin_print.php?action=finance&type=com" target="_blank" title="Report"><i class="bx bx-printer me-1 font-22 text-primary"></i></a>

							<?php } ?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
</div>

<!-- Now Search Modal -->
<div class="modal fade" id="now_search" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"><i class='bx bx-search'></i> <?php echo $l_search ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-start">
                <form class="row g-3" action="admin.php" method="get" target="_blank">
                	<input type="hidden" name="page" 		value="detail">
                	<input type="hidden" name="type" 		value="com">
                	<input type="hidden" name="member_id" 	id="direct_id">
                    <div class="col-12 col-sm-6">
                        <label  class="form-label"><?php echo $l_member_code ?> <font color="red">*</font></label>
                        <input type="text" class="form-control" id="check_member" placeholder="<?php echo $l_member_code ?>" required>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label"><?php echo $l_member_name ?> <font color="red">*</font></label>
                        <input type="text" class="form-control" id="direct_name" placeholder="กรุณากรอกรหัสผู้แนะนำให้ถูกต้อง" readonly>
                    </div>
                    <div class="col-12 mt-3">
                        <button name="action" value="finance_search" class="btn btn-primary btn-sm"><?php echo $l_search ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php 
$report_type = 0;
include("process/include_admin_report.php");
?>