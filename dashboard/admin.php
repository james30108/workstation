<?php include('process/function.php');
if (file_exists("process/project/$system_style/function_child.php")) { include("process/project/$system_style/function_child.php"); }

$page 			  = isset($_GET['page']) ? $_GET['page'] : false;
$admin_id  		  = $_SESSION['admin_id'];
$sql_check_login  = mysqli_query($connect, "SELECT * FROM system_admin WHERE admin_id = '$admin_id' ");
$data_check_login = mysqli_fetch_array($sql_check_login);
$admin_id     	  = $data_check_login['admin_id'];
$admin_status     = $data_check_login['admin_status'];
$page_type        = basename($_SERVER['PHP_SELF']);

$lang 			  = $system_lang == 1 ? $data_check_login['admin_lang'] : 0;
include("process/include_lang.php");

if ($data_check_login) { ?>
	<!DOCTYPE html>
	<html xmlns="http://www.w3.org/1999/xhtml" class="<?php echo "$theme_mode $theme_header $theme_sidebar" ?>">

	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<?php if ($system_mobile == 1) { ?>
			<meta name="viewport" content="width=device-width, initial-scale=1">
		<?php } ?>
		<!--favicon-->
		<link rel="icon" href="assets/images/etc/<?php echo $logo_icon ?>" />
		<!--plugins-->
		<link href="assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
		<link href="assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
		<link href="assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
		<link href="assets/plugins/highcharts/css/highcharts.css" rel="stylesheet" />

		<!-- loader-->
		<link href="assets/css/pace.min.css" rel="stylesheet" />
		<script src="assets/js/pace.min.js"></script>
		<!-- Bootstrap CSS -->
		<link href="assets/css/bootstrap.min.css" rel="stylesheet">
		<link href="assets/css/bootstrap-extended.css" rel="stylesheet">
		<link href="assets/css/app.css" rel="stylesheet">
		<link href="assets/css/icons.css" rel="stylesheet">
		<!-- Theme Style CSS -->
		<link rel="stylesheet" href="assets/css/dark-theme.css" />
		<link rel="stylesheet" href="assets/css/semi-dark.css" />
		<link rel="stylesheet" href="assets/css/header-colors.css" />

		<!-- ---------------- liner tree ------------------- -->
		<link rel="stylesheet" media="screen" HREF="assets/css/tree.css" />

		<!-- ------------------ Font ----------------------- -->
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">

		<!-- ------------------ JS ----------------------- -->
		<script src="assets/js/jquery-3.6.0.min.js"></script>
		<script src="assets/js/main.js"></script>


		<link type="text/css" href="editor.css" rel="stylesheet" />
		<!-- Styles -->
		<style>
			body {
				font-family: 'Kanit', sans-serif;
				font-size: 15px;
			}
		</style>
	</head>

	<body>
		<!--wrapper-->
		<div class="wrapper">
			<!--sidebar wrapper -->
			<div class="sidebar-wrapper" data-simplebar="true">
				<div class="sidebar-header">
					<div>
						<h5 class="logo-text">Admin's Dashboard</h5>
					</div>
					<div class="toggle-icon ms-auto"><i class='bx bx-first-page'></i></div>
				</div>
				<!--navigation-->
				<ul class="metismenu" id="menu">
					<?php if ($system_webpage == 1) { ?>
						<li>
							<a href="../">
								<div class="parent-icon"><i class='bx bx-bookmark'></i></div>
								<div class="menu-title"><?php echo $l_webpage ?></div>
							</a>
						</li>
					<?php } ?>
					<li>
						<a href="admin.php">
							<div class="parent-icon"><i class='bx bx-home'></i></div>
							<div class="menu-title"><?php echo $l_index ?></div>
						</a>
					</li>
					<!-- ----------------------------- -->
					<?php if ($admin_status != 2 && $admin_status != 3) { ?>
						<li class="menu-label"><?php echo $l_member_manage ?></li>
						<li>
							<a href="admin.php?page=liner">
								<div class="parent-icon"><i class="fadeIn animated bx bx-git-repo-forked"></i></div>
								<div class="menu-title"><?php echo $l_member ?></div>
							</a>
						</li>
						<?php if ($system_insertmember == 1) { ?>
						<li>
							<a href="admin.php?page=insert_member">
								<div class="parent-icon"><i class="fadeIn animated bx bx-user-plus"></i></div>
								<div class="menu-title"><?php echo $l_member_insert ?></div>
							</a>
						</li>
						<?php } if ($system_buyer == 1) { ?>
						<li>
							<a href="admin.php?page=buyer">
								<div class="parent-icon"><i class="fadeIn animated bx bx-group"></i></div>
								<div class="menu-title"><?php echo $l_buyer ?></div>
							</a>
						</li>
					<?php } } ?>
					<!-- ----------------------------- -->
					<?php if ($system_ecommerce == 1) { ?>
					<li class="menu-label"><?php echo $l_order_manage ?></li>
					<?php if ($admin_status != 2 && $admin_status != 3) { ?>
						<li>
							<a href="javascript:;" class="has-arrow">
								<div class="parent-icon"><i class="fadeIn animated bx bx-store-alt"></i></div>
								<div class="menu-title"><?php echo $l_product ?></div>
							</a>
							<ul>
								<li><a href="admin.php?page=admin_product"><i class="bx bx-right-arrow-alt"></i><?php echo $l_product ?></a></li>
								<li><a href="admin.php?page=admin_product_type"><i class="bx bx-right-arrow-alt"></i><?php echo $l_product_type ?></a></li>
							</ul>
						</li>
					<?php } ?>
					<li>
						<a href="admin.php?page=order">
							<div class="parent-icon"><i class='bx bx-file'></i></div>
							<div class="menu-title">
								<?php 
								$sql = "SELECT * FROM system_order WHERE (order_status = 0) OR (order_status = 3)";
								echo $l_order;
								echo badge ($connect, $sql, 0);
								?>
							</div>
						</a>
					</li>
					<?php if ($system_admin_buy == 1 && $admin_status != 2 && $admin_status != 3) { ?>
						<li>
							<a href="admin.php?page=shopping&action=admin">
								<div class="parent-icon"><i class="fadeIn animated bx bx-shopping-bag"></i></div>
								<div class="menu-title">ซื้อสินค้าด่วน</div>
							</a>
						</li>
					<?php } } ?>
					<!-- ----------------------------- -->
					<?php if ($system_ewallet == 1 || $system_pay == 1) { ?>
						<li class="menu-label"><?php echo $l_ewallet_manage ?></li>
					<?php } if ($system_ewallet == 1 && $admin_status != 2 && $admin_status != 3) { ?>
						<li>
							<a href="javascript:;" class="has-arrow">
								<div class="parent-icon"><i class="fadeIn animated bx bx-wallet-alt"></i></div>
								<div class="menu-title">
									<?php 
									$sql = "SELECT * FROM system_deposit WHERE deposit_status = 0";
									echo $l_ewallet;
									echo badge ($connect, $sql, 0);
									?>
								</div>
							</a>
							<ul>
								<li><a href="admin.php?page=ewallet"><i class="bx bx-right-arrow-alt"></i><?php echo $l_etopup; ?></a></li>
								<li><a href="admin.php?page=report_ewallet&action=deposit"><i class="bx bx-right-arrow-alt"></i><?php echo $l_etopup_report; ?></a></li>
								<li><a href="admin.php?page=report_ewallet&action=tranfer"><i class="bx bx-right-arrow-alt"></i><?php echo $l_etranfer_report; ?></a></li>
							</ul>
						</li>
					<?php } if ($system_pay == 1) { ?>
						<li>
							<a href="javascript:;" class="has-arrow">
								<div class="parent-icon"><i class="fadeIn animated bx bx-dish"></i></div>
								<div class="menu-title">
									<?php 
									$sql = "SELECT * FROM system_pay_refer WHERE (pay_status = 0) AND (pay_type != 0)";
									echo $l_pay;
									echo badge ($connect, $sql, 0);
									?>
								</div>
							</a>
							<ul>
								<li>
									<a href="admin.php?page=admin_pay"><i class="bx bx-right-arrow-alt"></i><?php echo $l_pay_order; ?></a></li>
								<li><a href="admin.php?page=admin_pay&type=report"><i class="bx bx-right-arrow-alt"></i><?php echo $l_pay_report; ?></a></li>
							</ul>
						</li>
					<?php } ?>
					<!-- ----------------------------- -->
					<?php if ($system_thread == 1) { ?>
					<li class="menu-label">Thread Management</li>
					<li>
						<a href="javascript:;" class="has-arrow">
							<div class="parent-icon"><i class="fadeIn animated bx bx-chalkboard"></i></div>
							<div class="menu-title"><?php echo $l_thread ?></div>
						</a>
						<ul>
							<li><a href="admin.php?page=admin_thread"><i class="bx bx-right-arrow-alt"></i><?php echo $l_thread ?></a></li>
							<li><a href="admin.php?page=admin_thread_type"><i class="bx bx-right-arrow-alt"></i><?php echo $l_thread_type ?></a></li>
						</ul>
					</li>
					<?php } ?>
					<!-- ----------------------------- -->
					<?php if (file_exists("process/project/$system_style/admin_menu.php")) { include("process/project/$system_style/admin_menu.php"); } ?>
					<!-- ----------------------------- -->
					<?php if ($admin_status != 2 && $admin_status != 3) { ?>
						<li class="menu-label">Commission Management</li>
						<li>
							<a href="admin.php?page=admin_report_commission">
								<div class="parent-icon"><i class="fadeIn animated bx bx-award"></i></div>
								<div class="menu-title"><?php echo $l_com; ?></div>
							</a>
						</li>
						<li>
							<a href="admin.php?page=commission_list">
								<div class="parent-icon"><i class="fadeIn animated bx bx-detail"></i></div>
								<div class="menu-title"><?php echo $l_com_list; ?></div>
							</a>
						</li>
					<?php } if ($system_com_withdraw != 0 && $admin_status != 2 && $admin_status != 3) { 

						$withdraw_url = $system_com_withdraw == 2 ? "admin.php?page=special&action=withdraw" : "admin.php?page=admin_withdraw";
						?>
						<li>
							<a href="<?php echo $withdraw_url ?>">
								<div class="parent-icon"><i class="fadeIn animated bx bx-gift"></i></div>
								<div class="menu-title">
								<?php 
								$sql = "SELECT * FROM system_withdraw WHERE (withdraw_status = 0)";
								echo $l_withdraw;
								echo badge ($connect, $sql, 0);
								?>
								</div>
							</a>
						</li>
						<?php if ($system_com_withdraw == 1) { ?> 
						<li>
							<a href="admin.php?page=admin_report_withdraw">
								<div class="parent-icon"><i class="fadeIn animated bx bx-spreadsheet"></i></div>
								<div class="menu-title">รายงานการแจ้งถอน</div>
							</a>
						</li>
					<?php } } if ($admin_status != 2) { ?>
						<li class="menu-label"><?php echo $l_report_manage; ?></li>
						<?php if ($admin_status != 2 && $admin_status != 3) { ?>
							<li>
								<a href="admin.php?page=admin_report_member">
									<div class="parent-icon"><i class="fadeIn animated bx bx-spreadsheet"></i></div>
									<div class="menu-title"><?php echo $l_report_member; ?></div>
								</a>
							</li>
						<?php } if ($system_ecommerce == 1) { ?>
						<li>
							<a href="admin.php?page=admin_report_sold">
								<div class="parent-icon"><i class="fadeIn animated bx bx-spreadsheet"></i></div>
								<div class="menu-title"><?php echo $l_report_sold; ?></div>
							</a>
						</li>
					<?php } } ?>
					<!-- ----------------------------- -->
					<?php if ($admin_status <= 1) { ?>
						<li class="menu-label"><?php echo $l_config; ?></li>
						<li>
							<a href="admin.php?page=admin_config">
								<div class="parent-icon"><i class="fadeIn animated bx bx-cog"></i></div>
								<div class="menu-title"><?php echo $l_config; ?></div>
							</a>
						</li>
					<?php } ?>
					<!-- ----------------------------- -->
				</ul>
				<!--end navigation-->
			</div>
			<!--end sidebar wrapper -->
			<!--start header -->
			<header>
				<div class="topbar d-flex align-items-center">
					<nav class="navbar navbar-expand">
						<div class="mobile-toggle-menu me-3"><i class='bx bx-menu'></i></div>
						<div class="top-menu-left d-flex ms-auto">
							<ul class="navbar-nav align-items-center">

								<?php if ($admin_status != 2 && $admin_status != 3) { 

									if ($system_comment == 1) { ?>
									<li class="nav-item">
										<a class="nav-link position-relative bg-transparent" href="admin.php?page=comment">
											<i class='bx bx-comment align-middle font-22'></i>
											<?php
											$query 	= mysqli_query($connect, "SELECT * FROM system_comment WHERE (comment_type != 1) AND (comment_status = 0)");
											$comment = mysqli_num_rows($query);
											if ($comment != 0) {

												echo "<span class='alert-count'>$comment</span>";
											
											} ?>
										</a>
									</li>
									<?php } ?>
									<li class="nav-item">
										<a class="nav-link position-relative bg-transparent" href="admin.php?page=contact">
											<i class='bx bx-envelope align-middle font-22'></i>
											<?php
											$query 	= mysqli_query($connect, "SELECT * FROM system_contact WHERE contact_type NOT IN (1, 3) AND (contact_status = 0)");
											$contact = mysqli_num_rows($query);
											if ($contact != 0) {
												
												echo "<span class='alert-count'>$contact</span>";

											} ?>
										</a>
									</li>
								<?php } if ($system_lang == 1) { ?> 
									<li class="nav-item dropdown dropdown-large">
										<a class="nav-link position-relative bg-transparent dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
											<i class='bx bx-globe'></i>
										</a>
										<div class="dropdown-menu dropdown-menu-end" style="width:180px;height: 150px;">
											<a href="#">
												<div class="msg-header">
													<p class="msg-header-title text-secondary">Change Language</p>
												</div>
											</a>
											<div class="header-message-list">
												<a class="dropdown-item" href="process/setting_config.php?action=change_lang&lang=0&admin_id=<?php echo $admin_id ?>">
													<div class="d-flex align-items-center">
														Thai (ภาษาไทย) <?php echo $lang == 0 ? "<i class='bx bx-check ms-3 font-22 text-primary'></i>" : false ?>
													</div>
												</a>
												<a class="dropdown-item" href="process/setting_config.php?action=change_lang&lang=1&admin_id=<?php echo $admin_id ?>">
													<div class="d-flex align-items-center">
														English <?php echo $lang == 1 ? "<i class='bx bx-check ms-3 font-22 text-primary'></i>" : false ?>
													</div>
												</a>
											</div>
										</div>
									</li>
								<?php } ?>
								<li class="nav-item dropdown dropdown-large">
									<div class="dropdown-menu dropdown-menu-end">
										<div class="header-notifications-list">
										</div>
									</div>
								</li>
								<li class="nav-item dropdown dropdown-large">
									<div class="dropdown-menu dropdown-menu-end">
										<div class="header-message-list">
										</div>
									</div>
								</li>
							</ul>
						</div>
						<div class="user-box dropdown">
							<a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
								<i class="user-name bx bx-user-circle font-22"></i>
								<div class="user-info ps-3">
									<p class="user-name mb-0">
										<?php
										if 		($admin_status == 0) { echo $lang == 0 ? "โปรแกรมเมอร์" : "Programer"; } 
										elseif 	($admin_status == 1) { echo $lang == 0 ? "ผู้จัดการ" : "Manager"; } 
										elseif 	($admin_status == 2) { echo $lang == 0 ? "แอดมิน" : "Admin"; } 
										elseif 	($admin_status == 3) { echo $lang == 0 ? "แอดมินบัญชี" : "Accountant"; }
										?>
									</p>
								</div>
							</a>
							<ul class="dropdown-menu dropdown-menu-end">
								<li>
									<button type="button" data-bs-toggle="modal" data-bs-target="#change_password" class="dropdown-item"><i class="bx bx-user"></i> <?php echo $l_change_pass; ?></button>
								</li>
								<li>
									<div class="dropdown-divider mb-0"></div>
								</li>
								<li><a class="dropdown-item" href="admin.php?page=admin_logout"><i class='bx bx-log-out-circle'></i><span><?php echo $l_logout; ?></span></a>
								</li>
							</ul>
						</div>
					</nav>
				</div>
			</header>

			<!-- change password -->
			<div class="modal fade" id="change_password" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body text-left">
							<form class="row g-3" action="process/config_setting.php" method="post">
								<input type="hidden" name="admin_id" value="<?php echo $admin_id ?>">
								<!-- minlength="6" maxlength="20" -->
								<div class="col-12">
									<label class="form-label">Current Password <font color="red">*</font></label>
									<input type="text" class="form-control" name="admin_password" placeholder="Current Password" required>
								</div>
								<div class="col-12">
									<label class="form-label">New Password <font color="red">*</font></label>
									<input type="text" class="form-control" name="new_password_1" placeholder="New Password" required>
								</div>
								<div class="col-12">
									<label class="form-label">Confirm New Password <font color="red">*</font></label>
									<input type="text" class="form-control" name="new_password_2" placeholder="Confirm New Password" required>
								</div>
								<div class="col-12">
									<button name="action" value="admin_change_password" class="btn btn-primary btn-sm">Save</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>

			<!--end header -->
			<!--start page wrapper -->
			<div class="page-wrapper">
				<div class="page-content">
					<?php
					switch ($page) {
						case "admin_default":
							include('admin_default.php');
							break;

						// ---- member ----
						case "insert_member":
							include('all_insert_member.php');
							break;
						case "liner":
							include('all_liner.php');
							break;
						case "edit_profile":
							include('all_edit_profile.php');
							break;
						case "buyer":
							include('all_buyer.php');
							break;

						// ---- product and order ----
						case "admin_product":
							include('admin_product.php');
							break;
						case "admin_product_type":
							include('admin_product_type.php');
							break;
						case "order":
							include('admin_order.php');
							break;
						case "shopping":
							include('all_shopping.php');
							break;

						// ---- ewallet ----
						case "ewallet":
							include('admin_ewallet.php');
							break;
						case "admin_pay":
							include('admin_pay.php');
							break;

						// ---- thread ----
						case "admin_thread":
							include('admin_thread.php');
							break;
						case "admin_thread_type":
							include('admin_thread_type.php');
							break;
						
						// ---- withdraw ----	
						case "admin_withdraw":
							include('admin_withdraw.php');
							break;

						// ---- report ----
						case "admin_report_commission":
							include("admin_report_commission.php");
							break;
						case "admin_report_withdraw":
							include('admin_report_withdraw.php');
							break;
						case "commission_list":
							include("admin_report_commission_list.php");
							break;
						case "report_ewallet":
							include('all_report_ewallet.php');
							break;
						case "admin_report_commission_vip":
							include('admin_report_commission_vip.php');
							break;
						case "admin_report_member":
							include('admin_report_member.php');
							break;
						case "admin_report_sold":
							include('admin_report_sold.php');
							break;
						case "detail":
							include('all_detail.php');
							break;

						// ---- special ----
						case "special":
							include("process/project/$system_style/admin_special.php");
							break;

						// ---- config ----
						case "admin_config":
							include('admin_config.php');
							break;
						case "contact":
							include('admin_contact.php');
							break;
						case "comment":
							include('admin_comment.php');
							break;
						case "admin_logout":
							unset($_SESSION['admin_id']);
							header("location:admin_login.php");
							break;

						default:
							include('admin_default.php');
					} //ปิดเคส
					?>
				</div>
			</div>
			<!--end page wrapper -->
			<!--start overlay-->
			<div class="search-overlay"></div>
			<div class="overlay toggle-icon"></div>
			<!--end overlay-->
			<!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
			<!--End Back To Top Button-->
			<footer class="page-footer">
				<p class="mb-0"><?php echo $company . " Admin's Dashboard @ 2022" ?></p>
			</footer>
		</div>
		<!--end wrapper-->
		<!-- Bootstrap JS -->
		<script src="assets/js/bootstrap.bundle.min.js"></script>
		<!--plugins-->
		<script src="assets/js/jquery.min.js"></script>
		<script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
		<script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
		<script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
		<script src="assets/plugins/ckeditor5/ckeditor.js"></script>
		<script src="assets/plugins/highcharts/js/highcharts.js"></script>
		<script src="assets/plugins/highcharts/js/exporting.js"></script>
		<script src="assets/plugins/highcharts/js/variable-pie.js"></script>
		<script src="assets/plugins/highcharts/js/export-data.js"></script>
		<script src="assets/plugins/highcharts/js/accessibility.js"></script>
		<script src="assets/plugins/apexcharts-bundle/js/apexcharts.min.js"></script>
		<!-- <script src="assets/js/index.js"></script> -->
		<!--app JS-->
		<script src="assets/js/app.js"></script>
		<script>
			ClassicEditor
				.create(document.querySelector('#editor'), {})
				.catch(error => {
					console.error(error);
				});
			new PerfectScrollbar('.scroll');
		</script>
	</body>

	</html>
<?php
} else {
	unset($_SESSION['admin_id']);
	header('location:admin_login.php');
}
?>
