<?php include('process/function.php');

if (file_exists("process/project/$system_style/function_child.php")) { include("process/project/$system_style/function_child.php"); }

$page 				= isset($_GET['page']) ? $_GET['page'] : false;
$member_id          = $_SESSION['member_id'];

$sql_check_login    = mysqli_query($connect,"SELECT * FROM system_member WHERE member_id = '$member_id' ");
$data_check_login   = mysqli_fetch_array($sql_check_login);
$page_type        	= basename($_SERVER['PHP_SELF']);

$lang 							= $system_lang == 1 ? $data_check_login['member_lang'] : 0;
include("process/include_lang.php");

if ($data_check_login) { ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" class="color-sidebar sidebarcolor3 color-header headercolor2">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<?php if($system_mobile == 1) { ?>
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
	

	<link type="text/css" href="editor.css" rel="stylesheet"/>
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
					<h5 class="logo-text">Member's Dashboard</h5>
				</div>
				<div class="toggle-icon ms-auto"><i class='bx bx-first-page'></i>
				</div>
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
					<a href="member.php">
						<div class="parent-icon"><i class='bx bx-home'></i>
						</div>
						<div class="menu-title"><?php echo $l_index ?></div>
					</a>
				</li>
				<!-- ----------------------------- -->
				<li class="menu-label"><?php echo $l_member_manage ?></li>
				<li>
					<a href="member.php?page=insert_member">
						<div class="parent-icon"><i class='fadeIn animated bx bx-user-plus' ></i>
						</div>
						<div class="menu-title"><?php echo $l_member_insert ?></div>
					</a>
				</li>
				<?php if ($system_liner == 1) { ?>
				<li>
					<a href="member.php?page=liner&action=liner_tree&main_id=yes">
						<div class="parent-icon"><i class='fadeIn animated bx bx-shape-polygon' ></i>
						</div>
						<div class="menu-title"><?php echo $l_member ?></div>
					</a>
				</li>
				<?php } if ($system_buyer == 1) { ?>
				<li>
					<a href="member.php?page=buyer">
						<div class="parent-icon"><i class="fadeIn animated bx bx-group"></i></div>
						<div class="menu-title"><?php echo $l_buyer ?></div>
					</a>
				</li>
				<?php } ?>
				<!-- ----------------------------- -->
				<li class="menu-label">E-Commerce</li>
				<li>
					<a href="member.php?page=shopping">
						<div class="parent-icon"><i class='bx bx-store-alt' ></i>
						</div>
						<div class="menu-title"><?php echo $l_buy ?></div>
					</a>
				</li>
				<li>
					<a href="javascript:;" class="has-arrow">
						<div class="parent-icon"><i class='bx bx-check-square'></i>
						</div>
						<div class="menu-title"><?php echo $l_order ?></div>
					</a>
					<ul>
						<li><a href="member.php?page=order"><i class="bx bx-right-arrow-alt"></i><?php echo $l_order ?></a></li>
						<li><a href="member.php?page=order&type=report"><i class="bx bx-right-arrow-alt"></i><?php echo $l_report ?></a></li>
					</ul>
				</li>
				<!-- ----------------------------- -->
				<?php if ($system_ewallet == 1 || $system_pay == 1 || $system_com_withdraw != 0) { ?>
						<li class="menu-label"><?php echo $l_ewallet_manage ?></li>
				<?php } if ($system_pay == 1) { ?>
				<li>
					<a href="javascript:;" class="has-arrow">
						<div class="parent-icon"><i class='bx bx-dish'></i>
						</div>
						<div class="menu-title"><?php echo $l_pay ?></div>
					</a>
					<ul>
						<li><a href="member.php?page=member_pay_refer"><i class="bx bx-right-arrow-alt"></i><?php echo $l_pay ?></a></li>
						<li><a href="member.php?page=member_report_pay"><i class="bx bx-right-arrow-alt"></i><?php echo $l_pay_report ?></a></li>
					</ul>
				</li>
				<?php } if ($system_ewallet == 1) { ?>
				<li>
					<a href="javascript:;" class="has-arrow">
						<div class="parent-icon"><i class='bx bx-wallet'></i>
						</div>
						<div class="menu-title"><?php echo $l_ewallet ?></div>
					</a>
					<ul>
						<li><a href="member.php?page=ewallet"><i class="bx bx-right-arrow-alt"></i><?php echo $l_etopup; ?></a></li>
						<li><a href="member.php?page=ewallet&action=tranfer"><i class="bx bx-right-arrow-alt"></i><?php echo $l_tranfer; ?></a></li>
						<hr>
						<li><a href="member.php?page=report_ewallet&action=deposit"><i class="bx bx-right-arrow-alt"></i><?php echo $l_etopup_report; ?></a></li>
						<li><a href="member.php?page=report_ewallet&action=tranfer"><i class="bx bx-right-arrow-alt"></i><?php echo $l_etranfer_report; ?></a></li>
					</ul>
				</li>
				<?php } if ($system_com_withdraw != 0) { ?>
				<li>
					<a href="member.php?page=member_withdraw">
						<div class="parent-icon"><i class="fadeIn animated bx bx-gift"></i></div>
						<div class="menu-title"><?php echo $l_withdraw ?></div>
					</a>
				</li>
				<?php } ?>
				<!-- ----------------------------- -->
				<li class="menu-label">Commission Management</li>
				<li>
					<a href="member.php?page=report_commission">
						<div class="parent-icon"><i class="fadeIn animated bx bx-award"></i></div>
						<div class="menu-title"><?php echo $l_report_com; ?></div>
					</a>
				</li>
				<li>
					<a href="member.php?page=commission_list">
						<div class="parent-icon"><i class="fadeIn animated bx bx-detail"></i></div>
						<div class="menu-title"><?php echo $l_com_list; ?></div>
					</a>
				</li>
				<!-- ----------------------------- -->
				<?php if (file_exists("process/project/$system_style/member_menu.php")) { include("process/project/$system_style/member_menu.php"); } ?>
				<!-- ----------------------------- -->
			</ul>
			<!--end navigation-->
		</div>
		<!--end sidebar wrapper -->
		<!--start header -->
		<header>
			<div class="topbar d-flex align-items-center shadow-none border-light-2 border-bottom">
				<nav class="navbar navbar-expand me-0">
					<div class="mobile-toggle-menu text-white me-3"><i class='bx bx-menu'></i></div>
					<div class="top-menu ms-auto">
						<ul class="navbar-nav align-items-center">
							<li class="nav-item">
								<a class="nav-link text-white" href="member.php?page=contact">
									<i class='bx bx-message'></i>
								</a>
							</li>
							<?php if ($system_lang == 1) { ?> 
								<li class="nav-item dropdown dropdown-large">
									<a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
										<i class='bx bx-globe'></i>
									</a>
									<div class="dropdown-menu dropdown-menu-end" style="width:180px;height: 150px;">
										<a href="#">
											<div class="msg-header">
												<p class="msg-header-title text-secondary">Change Language</p>
											</div>
										</a>
										<div class="header-message-list">
											<a class="dropdown-item" href="process/setting_member.php?action=change_lang&lang=0&member_id=<?php echo $member_id ?>">
												<div class="d-flex align-items-center">
													Thai (ภาษาไทย) <?php echo $lang == 0 ? "<i class='bx bx-check ms-3 font-22 text-primary'></i>" : false ?>
												</div>
											</a>
											<a class="dropdown-item" href="process/setting_member.php?action=change_lang&lang=1&member_id=<?php echo $member_id ?>">
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
					<div class="user-box dropdown border-light-2">
						<a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
							<?php if ($data_check_login['member_image_cover'] != '') { ?>
								<img src="assets/images/profiles/<?php echo $data_check_login['member_image_cover']; ?> " class="user-img" alt="รูปโปรไฟล์">
							<?php } else { ?> 
								<i class="bx bx-user-circle text-white font-20"></i>
							<?php } ?>
							<!-- <div class="user-info ps-3"> -->
							<div class="ps-3">
								<p class="user-name mb-0 text-white"><?php echo $data_check_login['member_name'];?></p>
								<p class="designattion mb-0">Member</p>
							</div>
						</a>
						<ul class="dropdown-menu dropdown-menu-end">
							<li><a class="dropdown-item" href="member.php?page=edit_profile"><i class="bx bx-edit"></i><span><?php echo $l_edimemer; ?></span></a>
							</li>
							<li>
								<div class="dropdown-divider mb-0"></div>
							</li>
							<li><a class="dropdown-item" href="member.php?page=member_logout"><i class='bx bx-log-out-circle'></i><span><?php echo $l_logout; ?></span></a>
							</li>
						</ul>
					</div>
				</nav>
			</div>
		</header>
		<!--end header -->
		<!--start page wrapper -->
		<div class="page-wrapper">
			<div class="page-content">
			<?php
			switch($page){
				case "insert_member":
					include('all_insert_member.php');
					break;
				case "liner":
					include('all_liner.php');
					break;
				case "buyer":
					include('all_buyer.php');
					break;
				case "shopping":
					include('all_shopping.php');
					break;
				case "order":
					include('member_order.php');
					break;
				case "member_pay_refer":
					include('member_pay_refer.php');
					break;
				case "ewallet":
					include('member_ewallet.php');
					break;
				case "member_report_pay":
					include('member_report_pay.php');
					break;
				case "member_report_finace":
					include('member_report_finace.php');
					break;
				case "report_ewallet":
					include('all_report_ewallet.php');
					break;
				case "report_commission":
					include('member_report_com.php');
					break;
				case "commission_list":
					include('member_report_comlist.php');
					break;
				case "member_withdraw":
					include('member_withdraw.php');
					break;
				case "detail":
					include('all_detail.php');
					break;
				case "contact":
					include('member_contact.php');
					break;
				case "edit_profile":
					include('all_edit_profile.php');
					break;
				
				// ---- special ----
				case "special":
					include("process/project/$system_style/member_special.php");
					break;

				case "member_logout":
					unset($_SESSION['member_id']);
					header("location:member_login.php");
					break;
				default:
					include("member_default.php");
			}//ปิดเคส
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
			<p class="mb-0"><?php echo $company . " Member's Dashboard @ 2022" ?></p>
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
		new PerfectScrollbar('.best-selling-products');
		new PerfectScrollbar('.recent-reviews');
		new PerfectScrollbar('.support-list');

		ClassicEditor
			.create( document.querySelector( '#editor' ) )
			.catch( error => {
				console.error( error );
			} );
    </script>
</body>
</html>

<?php 
} 
else {
    unset($_SESSION['member_id']);
    header('location:member_login.php');
} 
?>