<?php isset($_GET["status"]) ? alert ($_GET["status"], $_GET["message"], $lang) : false;

if(!isset($_GET['action'])) { ?>
	<title><?php echo $l_etopup ?></title>
	<div class="page-breadcrumb d-flex align-items-center mb-3">
	    <div class="pe-3 text-primary">
	        <div class="card-title d-flex align-items-center">
	            <div><i class="bx bx-wallet me-1 font-22 text-primary"></i></div>
	            <h5 class="mb-0 text-primary"><?php echo "$l_etopup | $l_balance " . $data_check_login['member_ewallet'] . $l_bath;?></h5>
	        </div>
	    </div>
	</div>
    <div class="card border-top border-0 border-4 border-primary">
    	<div class="card-body">
			<div class="row">
				<div class="col-12 col-sm-5">
					<img src="" width=100% />
				</div>
				<div class="col">
					<form action="process/setting_ewallet.php" method="post" enctype="multipart/form-data">
						<input type="hidden" name="deposit_member" value="<?php echo $member_id ?>">
						<input type="hidden" name="page" value="<?php echo $page_type ?>">
						<div class="mb-3">
							<label class="form-label"><?php echo $l_money ?></label>
							<input type="number" class="form-control" placeholder="<?php echo $l_money ?>" name="deposit_money" required>
						</div>
						<div class="row">
							<div class="col">
								<div class="mb-3">
									<label class="form-label"><?php echo $l_date ?></label>
									<input type="date" class="form-control" placeholder="<?php echo $l_date ?>" name="deposit_date" required>
								</div>
							</div>
							<div class="col">
								<div class="mb-3">
									<label class="form-label"><?php echo $l_time ?></label>
									<input type="time" class="form-control" placeholder="<?php echo $l_time ?>" name="deposit_time" required>
								</div>
							</div>
						</div>
						<div class="mb-3">
							<label class="form-label"><?php echo $l_pay_slip ?></label>
							<input type="file" class="form-control" placeholder="<?php echo $l_pay_slip ?>" name="deposit_slip" required>
						</div>
						<div class="mb-3">
							<label class="form-label"><?php echo $l_other ?></label>
							<textarea class="form-control" name="deposit_detail" placeholder="<?php echo $l_other ?>"></textarea>
						</div>
						<div class="d-flex mt-5">
							<button name="action" value="insert" class="btn btn-primary btn-sm ms-auto"><?php echo $l_save ?></button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
<?php } elseif($_GET['action'] == 'tranfer') { ?>
	<title><?php echo $l_tranfer ?></title>
	<script>  
	    $(document).ready(function(){

	        // Show Upline
	        $("#direct_code").change(function(){
	            var direct_code = $(this).val();
	            
	            $.get( "process/ajax/ajax_check_member.php", { direct_code: direct_code }, function( data ) {

	                if (data == 'none') {
	                    alert('This member code is not in the system');
	                    $("#direct_code").val("");
	                    $("#direct_id").val("");
	                }
	                else {
	                    let direct      = data.split(",");
	                    let direct_id   = direct[0];
	                    let direct_name = direct[1];

	                    if (direct_id == '<?php echo $member_id ?>') { 
	                    	alert('This is your member code');
	                    	$("#direct_code").val("");
	                    	$("#direct_id").val("");
	                    }
	                    else {
	                    	$("#direct_id").val(direct_id);
	                    }
	                }
	            });
	        });

	        // Check money
	        $("#money").change(function(){
	            var money 	= $(this).val();
	            var balance = <?php echo $data_check_login['member_ewallet'] ?>;
	            if (money > balance) {
	            	alert("Not enough money.");
	            	$("#money").val("");
	            }
	        });
	    });
	</script>
	<div class="col-12 col-sm-7 mx-auto">
	<div class="page-breadcrumb d-flex align-items-center mb-3">
	    <div class="pe-3 text-primary">
	        <div class="card-title d-flex align-items-center">
	            <div><i class="bx bx-transfer-alt me-1 font-22 text-primary"></i></div>
	            <h5 class="mb-0 text-primary"><?php echo "$l_tranfer | $l_balance " . $data_check_login['member_ewallet'] . $l_bath?></h5>
	        </div>
	    </div>
	</div>
    <div class="card border-top border-0 border-4 border-primary">
    	<div class="card-body">
			<form action="process/setting_ewallet.php" method="post" class="row g-3">
				<input type="hidden" name="tranfer_member" value="<?php echo $member_id; ?>">
				<input type="hidden" name="tranfer_to" id="direct_id">
				<div class="col-12 col-sm-6">
					<label class="form-label"><?php echo $l_money ?></label>
					<input type="number" class="form-control" placeholder="<?php echo $l_money ?>" id="money" name="tranfer_money" required>
				</div>
				<div class="col-12 col-sm-6">
                    <label  class="form-label"><?php echo $l_member_code ?> <font color="red">*</font></label>
                    <input type="text" class="form-control" name="member_code" id="direct_code" placeholder="<?php echo $l_member_code ?>" required>
                </div>
				<div class="d-flex mt-5">
					<button name="action" value="tranfer" class="btn btn-primary btn-sm ms-auto" onclick="javascript:return confirm('Confirm ?')"><?php echo $l_save ?></button>
				</div>
			</form>
		</div>
	</div>
	</div>
<?php } ?>