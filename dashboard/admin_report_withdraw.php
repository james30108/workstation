<?php 

$query  = mysqli_query($connect, "SELECT SUM(withdraw.group_sum) AS sum, COUNT(*) AS count 
	FROM
	    (SELECT *, SUM(withdraw_point) AS group_sum 
	    FROM system_withdraw 
	    WHERE (withdraw_cut = 0) AND (withdraw_status != 2)
	    GROUP BY withdraw_member) AS withdraw ") or die ($connect);
$data  = mysqli_fetch_array($query);

$count  	= $data['count'];
$sum 		= $count > 0 ? $data['sum'] : 0;
$today     	= datethai(date("Y-m-d"), 0, $lang);
$now_url 	= $count == 0 ? $today : "<a href='admin.php?page=detail&action=finance&type=withdraw' title='Detail' target='_blank'>$today</a>";

?>

<title><?php echo $l_withdraw ?></title>
<div class="col-12 col-sm-7 mx-auto">
<div class="card border-top border-0 border-4 border-primary">
	<div class="card-body">
		<div class="card-title d-flex align-items-center">
			<div><i class="bx bx-file me-1 font-22 text-primary"></i></div>
			<h5 class="mb-0 text-primary"><?php echo $l_withdraw ?></h5>
			<button type="button" data-bs-toggle="modal" data-bs-target="#now_search" class="btn btn-primary btn-sm ms-auto"><i class='bx bx-search'></i> <?php echo $l_search ?></button>
		</div>
		<hr>
		<p class='text-danger'>Last Withdrawals / <?php echo $l_resold_text ?></p>
		<div class="table-responsive">
			<table class="table mb-0 text-center align-middle" >
            	<thead class="table-light">
					<tr>
						<th><?php echo $l_com_round ?></th>
						<th><?php echo $l_date ?></th>
						<th><?php echo $l_money ?></th>
						<th><?php echo $l_member ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?php echo report_now ($connect, 2); ?></td>
						<td><?php echo $now_url ?></td>
						<td><?php echo number_format($sum, 2) . $l_bath ?></td>
						<td><?php echo number_format($count) ?></td>
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
                	<input type="hidden" name="type" 		value="withdraw">
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
$report_type = 2;
include("process/include_admin_report.php");
?>