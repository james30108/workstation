<?php

$query 		= mysqli_query($connect, "SELECT *, 
	SUM(order_price + order_freight) AS sum_price, 
	COUNT(*) AS count_order 
	FROM system_order 
	WHERE (order_status >= 4) AND (order_cut_report = 0)");

$data  		= mysqli_fetch_array($query);
$sum_price  = $data['sum_price'];
$count_order= $data['count_order'];
$today     	= datethai(date("Y-m-d"), 0, $lang);

if (!isset($_GET['action'])) { ?>
	<title><?php echo $l_report_sold ?></title>
	<div class="card border-top border-0 border-4 border-primary">
        <div class="card-body">
		<div class="card-title d-flex align-items-center">
			<div><i class="bx bx-file me-1 font-22 text-primary"></i></div>
			<h5 class="mb-0 text-primary"><?php echo $l_report_sold ?></h5>
		</div>
		<p class='text-danger'><?php echo $l_resold_text ?></p>
		<div class="table-responsive">
            <table class="table mb-0 text-center align-middle" >
                <thead class="table-light">
					<tr>
						<th><?php echo $l_com_roundnow ?></th>
						<th><?php echo $l_date ?></th>
						<th><?php echo $l_resold_money ?></th>
						<th><?php echo $l_resold_order ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?php echo report_now($connect, 1); ?></td>
						<td>
							<?php 
							echo $count_order > 0 ? "<a href='admin.php?page=admin_report_sold&action=report_sold_detail' title='รายละเอียด''>$today</a>" : $today ; ?>
						</td>
						<td><?php echo number_format($sum_price, 2) . $l_bath; ?></td>
						<td><?php echo number_format($count_order) . $l_list; ?></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	</div>
	<div class="card border-top border-0 border-4 border-primary">
        <div class="card-body">
		<div class="card-title d-flex align-items-center">
			<div><i class="bx bx-cabinet me-1 font-22 text-primary"></i></div>
			<h5 class="mb-0 text-primary"><?php echo $l_com_old ?></h5>
		</div>
		<div class="table-responsive">
			<table class="table mb-3 text-center align-middle">
                <thead class="table-light">
					<tr>
						<th><?php echo $l_com_round ?></th>
						<th><?php echo $l_com_datecut ?></th>
						<th><?php echo $l_resold_money ?></th>
						<th><?php echo $l_resold_order ?></th>
						<th><?php echo $l_detail ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
	                $sql    	= "SELECT * FROM system_report WHERE report_type = 1";
	                $order_by 	= " ORDER BY report_round DESC ";
	                $query  	= mysqli_query($connect, $sql . $order_by . $limit) or die(mysqli_error($connect));
					$empty  	= mysqli_num_rows($query);
					if ($empty > 0) {
					 	while ($data = mysqli_fetch_array($query)) { 

					 		$report_round   = number_format($data['report_round']);
					 		$report_point   = number_format($data['report_point'], 2) . $l_bath;
					 		$report_count   = number_format($data['report_count']) . $l_list;
					 		$report_id 		= $data['report_id'];
					 		$report_create 	= datethai($data['report_create'], 0, $lang);
					 		$report_url 	= $report_count > 0 ? "<a href='admin.php?page=admin_report_sold&action=sold_report&report_id=$report_id' title='Detail' target='_blank'>$report_create</a>" : $report_create;

					 		?>
						 	<tr>
								<td><?php echo $report_round ?></td>
								<td><?php echo $report_url ?></td>
								<td><?php echo $report_point ?></td>
								<td><?php echo $report_count ?></td>
								<td>
									<a href="admin_excel.php?action=sold&report_id=<?php echo $report_id ?>" target="_blank" title="Excel"><i class="bx bx-table me-3 font-22 text-primary"></i></a>
									<a href="admin_print.php?action=sold&report_id=<?php echo $report_id ?>" target="_blank" title="Report"><i class="bx bx-printer font-22 text-primary"></i></a>
								</td>
							</tr>
					<?php } } else { echo "<tr><td colspan='5'>$l_notfound</td></tr>"; } ?>
				</tbody>
			</table>
		</div>
		<?php 
        $url = "admin.php?page=admin_report_sold";
        pagination ($connect, $sql, $perpage, $page_id, $url); ?>
	</div>
	</div>
<?php } elseif (isset($_GET['action']) && $_GET['action'] == 'report_sold_detail') { ?>
	<title><?php echo $l_com_nowdeta ?></title>
	<div class="card-title d-flex align-items-center mb-3">
		<div><i class="bx bx-detail me-1 font-22 text-primary"></i></div>
        <h5 class="mb-0 text-primary"><?php echo $l_com_nowdeta ?></h5>
	</div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page"><a href="admin.php?page=admin_report_sold"><?php echo $l_report_sold ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $l_com_nowdeta ?></li>
        </ol>
    </nav>
    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body">
			<table class="table table-borderless table-sm">
				<tbody>
					<tr>
						<th width="180"><?php echo $l_com_round ?></th>
						<td><?php echo number_format($report_now) ?></td>
					</tr>
					<tr>
						<th><?php echo $l_resold_money ?></th>
						<td><?php echo number_format($sum_price, 2) . $l_bath ?></td>
					</tr>
					<tr>
						<th><?php echo $l_resold_order ?></th>
						<td><?php echo number_format($count_order) . $l_list ?></td>
					</tr>
				</tbody>
			</table>
            <table class="table mb-3 text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th><?php echo $l_order_code ?></th>
						<th><?php echo $l_price ?></th>
                        <th><?php echo $l_product_code ?></th>
                        <th><?php echo $l_quantity ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php
				$sql    = "SELECT system_order_detail.*, system_order.*, system_product.*
                		FROM system_order_detail
						INNER JOIN system_order ON (system_order_detail.order_detail_order = system_order.order_id)
						INNER JOIN system_product ON (system_order_detail.order_detail_product = system_product.product_id)
						WHERE (order_status >= 4) AND (order_cut_report = 0)";
                $order_by   = " ORDER BY order_status ASC, order_create DESC ";
                $query      = mysqli_query($connect, $sql . $order_by . $limit) or die(mysqli_error($connect)); 
                $empty      = mysqli_num_rows($query);
                if ($empty > 0 ) {
                    $i = 0;
                    while ($data = mysqli_fetch_array($query)) { 
                        $i++;
                        ?>
                        <tr>
                            <td><?php echo $i + $start ?></td>
                            <td>
                            	<a href="admin.php?page=order&action=order_detail&order_id=<?php echo $data['order_id'] ?>" target="_blank"><?php echo $data['order_code'] ?></a>
                            </td>
							<td><?php echo number_format($data['order_detail_price'] + $data['order_detail_freight'], 2) . $l_bath ?></td>
                            <td><?php echo $data['product_code'] ?></td>
                            <td><?php echo number_format($data['order_detail_amount']) . $l_piece ?></td>
                        </tr>
                <?php } } else { echo "<tr><td colspan='6'>$l_notfound</td></tr>"; } ?>
                </tbody>
            </table>
            <?php 
            $url = "admin.php?page=admin_report_sold&action=report_sold_detail";
            pagination ($connect, $sql, $perpage, $page_id, $url); ?>
		</div>
	</div>
<?php } elseif (isset($_GET['action']) && $_GET['action'] == 'sold_report') {

	$report_id 	= $_GET['report_id'];
	$query 		= mysqli_query($connect, "SELECT * FROM system_report WHERE report_id = '$report_id' ");
	$data   	= mysqli_fetch_array($query);

	$query_2 	= mysqli_query($connect, "SELECT system_report_detail.*, system_order_detail.* 
    	FROM system_order_detail
    	INNER JOIN system_report_detail ON (system_report_detail.report_detail_link = system_order_detail.order_detail_order)
    	WHERE report_detail_main = '$report_id' ") or die(mysqli_error($connect));
    $count_product = mysqli_num_rows($query_2);
	?>
	<title><?php echo $l_com_old ?></title>
	<div class="page-breadcrumb d-flex align-items-center mb-3">
        <div class="pe-3 text-primary">
            <div class="card-title d-flex align-items-center">
                <div><i class="bx bx-list-ul me-1 font-22 text-primary"></i></div>
                <h5 class="mb-0 text-primary"><?php echo $l_com_old ?></h5>
            </div>
        </div>
    </div>
	<div class="card border-top border-0 border-4 border-primary">
		<div class="card-body p-1 pt-3 pb-3 p-sm-5">
			<table class="table table-borderless table-sm">
				<tbody>
					<tr>
						<th width="180"><?php echo $l_com_round ?></th>
						<td><?php echo number_format($data['report_round']); ?></td>
					</tr>
					<tr>
						<th><?php echo $l_date ?></th>
						<td><?php echo datethai($data['report_create'], 0, $lang) ?></td>
					</tr>
					<tr>
						<th><?php echo $l_resold_money ?></th>
						<td><?php echo number_format($data['report_point']) . $l_bath ?></td>
					</tr>
					<tr>
						<th><?php echo $l_resold_order ?></th>
						<td><?php echo number_format($data['report_count']) . $l_list ?></td>
					</tr>
					<tr>
						<th><?php echo $l_resold_goods ?></th>
						<td><?php echo number_format($count_product) . $l_list ?></td>
					</tr>
					<tr>
						<th><?php echo $l_report ?></th>
						<td><a href="admin_print.php?action=sold&report_id=<?php echo $report_id ?>" target="_blank" title="พิมพ์รายงาน"><i class="bx bx-printer me-1 font-22 text-primary"></i></a>
						<a href="admin_excel.php?action=sold&report_id=<?php echo $report_id ?>" target="_blank" title="โหลด excel"><i class="bx bx-table me-3 font-22 text-primary"></i></a></td>
					</tr>
				</tbody>
			</table>
			<table class="table mb-3 text-center align-middle" >
                <thead class="table-light">
                    <tr>
						<th>#</th>
						<th><?php echo $l_order_code ?></th>
						<th><?php echo $l_date ?></th>
						<th><?php echo $l_price ?></th>
						<th><?php echo $l_product_code ?></th>
						<th><?php echo $l_quantity ?></th>
						<th><?php echo $l_name ?></th>
						<?php echo $system_delivery == 1 ? "<th>$l_address</th>" : false ?>
						<th><?php echo $l_tel ?></th>
						<th><?php echo $l_pay ?></th>
						<th><?php echo $l_report ?></th>
                    </tr>
				</thead>
				<tbody>
				<?php

				$sql    = "SELECT system_report_detail.*, system_order_detail.*, system_product.*, system_order.*
		    	FROM system_order_detail
		    	INNER JOIN system_report_detail ON (system_report_detail.report_detail_link = system_order_detail.order_detail_order)
		    	INNER JOIN system_product ON (system_product.product_id = system_order_detail.order_detail_product)
		    	INNER JOIN system_order ON (system_order.order_id = system_order_detail.order_detail_order)
		    	WHERE report_detail_main = '$report_id' ";
                
                $query  = mysqli_query($connect, $sql . $limit) or die(mysqli_error($connect));
				$empty  = mysqli_num_rows($query);
				$i 		= 0;
				if ($empty > 0) {
				while($data = mysqli_fetch_array ($query)) {
					$report_detail_point = $data['report_detail_point'];
					$order_type_buy 	 = $data['order_type_buy'];
					$i++;
					?>
                    <tr>
						<td><?php echo $i + $start ?></td>
                        <td>
                        	<a href="admin.php?page=order&action=order_detail&order_id=<?php echo $data['order_id'] ?>" target="_blank"><?php echo $data['order_code'] ?></a>
                        </td>
                        <td><?php echo datethai($data['order_create'], 0, $lang) ?></td>
						<td><?php echo number_format($data['order_detail_price'], 2) . $l_bath ?></td>
                        <td><?php echo $data['product_code'] ?></td>
                        <td><?php echo number_format($data['order_detail_amount']) . $l_piece ?></td>
                        <td><?php echo $data['order_buyer'] ?></td>
                        <?php if ($system_delivery == 1) { ?>
                        <td><?php echo $data['order_address'] .
                            " ตำบล/แขวง " . $data['order_district'] .
                            " อำเภอ/เขต " . $data['order_amphur'] .
                            " จังหวัด " . $data['order_province'] .
                            " รหัสไปรษณีย์ " . $data['order_zipcode'] ?>
                        </td>
                    	<?php } ?>
                        <td><?php echo $data['order_buyer_tel'] ?></td>
                        <td>
                            <?php 
                            if 	   ($order_type_buy == 0) {echo "<font color=gray>$l_order_pay0</font>";} 
                            elseif ($order_type_buy == 1) {echo "<font color=blue>$l_order_pay1</font>";} 
                            elseif ($order_type_buy == 2) {echo "$l_order_pay2";} 
                            elseif ($order_type_buy == 3) {echo "<font color=blue>$l_order_pay3</font>";} 
                            elseif ($order_type_buy == 4) {echo "<font color=blue>$l_order_pay4</font>";} 
                            elseif ($order_type_buy == 5) {echo "<font color=blue>$l_order_pay5</font>";} 
                            ?>
                        </td>
                        <td>
                            <a href="admin_print.php?action=order&order_id=<?php echo $data['order_id'] ?>" target="_blank"><i class="bx bx-printer me-1 font-22 text-primary"></i></a>
                        </td>
                    </tr>
					<?php } } else { echo "<tr><td colspan='7'>$l_notfound</td></tr>"; } ?>
				</tbody>
			</table>
			<?php
            $url = "admin.php?page=admin_report_sold&action=sold_report&report_id=$report_id";
            pagination ($connect, $sql, $perpage, $page_id, $url); ?>
		</div>
	</div>
<?php } ?>