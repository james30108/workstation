<?php if ($admin_status != 2 && $admin_status != 3) { ?>
<li class="menu-label">Special</li>
<li>
	<a href="javascript:;" class="has-arrow">
		<div class="parent-icon"><i class="fadeIn animated bx bx-pyramid"></i></div>
		<div class="menu-title">เงินปันผลพิเศษ</div>
	</a>
	<ul>
		<li><a href="admin.php?page=special&action=liner"><i class="bx bx-right-arrow-alt"></i>ผังเงินปันผลพิเศษ</a></li>
		<li><a href="admin.php?page=special&action=static"><i class="bx bx-right-arrow-alt"></i>สถิติผัง</a></li>
		<li><a href="admin.php?page=special&action=com"><i class="bx bx-right-arrow-alt"></i>เงินปันผล</a></li>
		<li><a href="admin.php?page=commission_list&point_type=2&date_start=&date_end=&action=search"><i class="bx bx-right-arrow-alt"></i>รายละเอียด</a></li>
	</ul>
</li>
<?php } ?>