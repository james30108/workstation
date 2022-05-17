<?php include('process/function.php'); 

$admin_id  		  = $_SESSION['admin_id'];
$sql_check_login  = mysqli_query($connect, "SELECT * FROM system_admin WHERE admin_id = '$admin_id' ");
$data_check_login = mysqli_fetch_array($sql_check_login);
$admin_id     	  = $data_check_login['admin_id'];
$admin_status     = $data_check_login['admin_status'];

$lang 			  = $system_lang == 1 ? $data_check_login['admin_lang'] : 0;
include("process/include_lang.php");

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="assets/images/etc/<?php echo $logo_icon ?>" />
	<!--plugins-->
	<link href="assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
	<link href="assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
	<link href="assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />

	<!-- Bootstrap CSS -->
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/css/bootstrap-extended.css" rel="stylesheet">
	<link href="assets/css/app.css" rel="stylesheet">
	<link href="assets/css/icons.css" rel="stylesheet">
	<!-- Theme Style CSS -->
	<link rel="stylesheet" href="assets/css/dark-theme.css" />
	<link rel="stylesheet" href="assets/css/semi-dark.css" />
	<link rel="stylesheet" href="assets/css/header-colors.css" />

	<!-- ------------------ Font ----------------------- -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">

	<!-- ------------------ JS ----------------------- -->
    <script src="assets/js/jquery-3.6.0.min.js"></script>
	
    <!-- Styles -->
    <style>
      body {
        font-family: 'Kanit', sans-serif;
        font-size: 12px;
      }
    </style>
    <script type="text/javascript">
    	javascript:window.print();
    </script>
	<title>Print</title>
</head>
<body>
	
<?php if 		(isset($_GET['action']) && $_GET['action'] == 'finance') { 

    $report_id  = isset($_GET["report_id"]) ? $_GET["report_id"]    : false;
    $type       = isset($_GET["type"])      ? $_GET["type"]         : false;

    if ($type == 'com') {

        $query  = mysqli_query($connect, "SELECT SUM(com.sum_com) AS sum, COUNT(*) AS count
            FROM
                (SELECT *, SUM(point_bonus) AS sum_com FROM system_point 
                WHERE $point_type AND (point_status = 0) AND (point_member != 0)
                GROUP BY point_member
                HAVING (sum_com >= '$report_min')) AS com");
        $data   = mysqli_fetch_array($query);
        
        $title      = $l_com_nowdeta;
        $create     = datethai($today, 0, $lang);
        $point      = number_format($data['sum'], 2) . $l_bath;
        $count      = number_format($data['count']);
        $round      = report_now ($connect, 0);

        $sql = "SELECT system_member.*, system_point.*, SUM(point_bonus) AS sum 
            FROM system_point 
            INNER JOIN system_member ON (system_member.member_id = system_point.point_member)
            WHERE $point_type AND (point_status = 0)
            GROUP BY point_member
            HAVING sum >= '$report_min'";
        
    }
    elseif ($type == 'report') {

        $query  = mysqli_query($connect, "SELECT * FROM system_report WHERE report_id = '$report_id' ");
        $data   = mysqli_fetch_array($query);
        
        $title      = $l_com_old;
        $round      = number_format($data['report_round']);
        $create     = datethai($data['report_create'], 0, $lang);
        $point      = number_format($data['report_point'], 2) . $l_bath;
        $count      = number_format($data['report_count']);

        $sql = "SELECT system_member.*, system_report_detail.*, system_report.*
            FROM system_member
            INNER JOIN system_report_detail ON (system_member.member_id = system_report_detail.report_detail_link)
            INNER JOIN system_report        ON (system_report.report_id = system_report_detail.report_detail_main)
            WHERE report_detail_main = '$report_id' ";

    }
    elseif ($type == 'withdraw') {

        $query  = mysqli_query($connect, "SELECT SUM(withdraw.group_sum) AS sum, COUNT(*) AS count 
            FROM
                (SELECT *, SUM(withdraw_point) AS group_sum 
                FROM system_withdraw 
                WHERE (withdraw_cut = 0) AND (withdraw_status != 2)
                GROUP BY withdraw_member) AS withdraw ") or die ($connect);
        $data  = mysqli_fetch_array($query);
        
        $title      = $l_com_nowdeta;
        $create     = datethai($today, 0, $lang);
        $point      = number_format($data['sum'], 2) . $l_bath;
        $count      = number_format($data['count']);
        $round      = report_now ($connect, 0);
        $excel_url  = "#";
        $print_url  = "#";

        $sql = "SELECT system_member.*, system_withdraw.*
            FROM system_withdraw
            INNER JOIN system_member ON (system_member.member_id = system_withdraw.withdraw_member)
            WHERE withdraw_cut = 0";

    }

    ?>
    <title><?php echo $title ?></title>
    <div class="m-3">
    <div class="card-title d-flex align-items-center">
        <div><i class="bx bx-list-ul me-1 font-22 text-primary"></i></div>
        <h5 class="mb-0 text-primary"><?php echo $title ?></h5>
    </div>
    <table class="table table-borderless table-sm">
        <tbody>
            <tr>
                <th width=200px><?php echo $l_com_round ?></th>
                <td><?php echo $round ?></td>
            </tr>
            <tr>
                <th><?php echo $l_com_datecut ?></th>
                <td><?php echo $create ?></td>
            </tr>
            <tr>
                <th><?php echo $l_com_money ?></th>
                <td><?php echo $point ?></td>
            </tr>
            <tr>
                <th><?php echo $l_numliner ?></th>
                <td><?php echo $count ?></td>
            </tr>
            <?php if ($report_max > 0) { ?>
            <tr>
                <th><?php echo $l_com_max ?></th>
                <td><?php echo number_format($report_max, 2) . $l_bath ?></td>
            </tr>
            <?php } if ($report_fee1 > 0) { ?>
            <tr>
                <th><?php echo $l_fee1 ?></th>
                <td><?php echo number_format($report_fee1, 2) . $l_bath ?></td>
            </tr>
            <?php } if ($report_fee2 > 0) { ?>
            <tr>
                <th><?php echo $l_fee2 ?></th>
                <td><?php echo $report_fee2 . "%" ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <table class="table mb-3 text-center align-middle">
        <thead class="table-light">
            <tr>
                <th><?php echo $l_number ?></th>
                <th><?php echo $l_member_code ?></th>
                <th><?php echo $l_member_name ?></th>
                <th><?php echo $l_idcard ?></th>
                <?php echo $system_address != 0 ? "<th>$l_address</th>": false; ?>
                <th><?php echo $l_bankname ?></th>
                <th><?php echo $l_bankid ?></th>
                <th><?php echo $l_com_pay ?></th>
                <?php echo $type == 'withdraw' ? "<th>$l_status</th>" : false; ?>
            </tr>
        </thead>
        <tbody>
        <?php
        $query  = mysqli_query($connect, $sql) or die(mysqli_error($connect));
        $i      = 0;
        while($data = mysqli_fetch_array ($query)) {

            if ($data['member_bank_name'] != 0) {

                $bank_id= $data['member_bank_name'];
                $query2 = mysqli_query($connect, "SELECT * FROM system_bank WHERE bank_id = '$bank_id' ");
                $data2  = mysqli_fetch_array($query2);
                $bank   = $data2['bank_name_th'];

            }
            else { $bank = ""; }

            if      ($type == 'com')    { $sum = $data['sum']; }
            elseif  ($type == 'report') { $sum = $data['report_detail_point']; }
            
            if ($type == 'withdraw') {

                $money = $data['withdraw_point'] . $l_bath;
                if      ($data['withdraw_status'] == 0) { $status = "<font color=gray>$l_order_status0</font>"; }
                elseif  ($data['withdraw_status'] == 1) { $status = "<font color=green>$l_order_status4</font>"; }
                elseif  ($data['withdraw_status'] == 2) { $status = "<font color=red>$l_order_status1</font>"; }
            
            }
            else {

                // report's withdraw
                if (isset($data['report_type']) && $data['report_type'] == 2) { $money = $sum . $l_bath; }
                else { $money = report_final ($sum, $report_fee1, $report_fee2, $l_bath, $report_max)[0]; }
                
            }

            $i++;
            ?>
            <tr>
                <td><?php echo $i + $start ?></td>
                <td><?php echo $data['member_code'] ?></td>
                <td><?php echo $data['member_name'] ?></td>
                <td><?php chkEmpty ($data['member_code_id']) ?></td>
                <?php echo $system_address != 0 ? "<td>" . address ($connect, $data['member_id'], 0, $lang) . "</td>": false; ?>
                <td><?php chkEmpty ($bank) ?></td>
                <td><?php chkEmpty ($data['member_bank_id']) ?></td>
                <td><?php echo $money ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    </div>

<?php } elseif 	(isset($_GET['action']) && $_GET['action'] == 'order') { 
	
	$query = mysqli_query($connect, "SELECT system_member.*, system_order.* 
		FROM system_order 
		INNER JOIN system_member ON (system_order.order_member = system_member.member_id)
		WHERE order_id = '".$_GET['order_id']."'  ORDER BY order_id DESC"); 

	$data  = mysqli_fetch_array($query);

	$order_id       = $data['order_id'];
    $order_code     = $data['order_code'];
    $order_buyer    = $data['order_buyer'];
    $order_create   = datethai($data['order_create'], 0, $lang);
    $order_amount   = number_format($data['order_amount']);
    $order_price    = number_format($data['order_price'], 2);
    $order_freight  = number_format($data['order_freight'], 2);
    $order_point    = number_format($data['order_point'], 2);
    $order_type_buy = $data['order_type_buy'];
    $order_status   = $data['order_status'];
    $order_track_id = $data['order_track_id'];
    $order_track_name = $data['order_track_name'];

    $member_code    = $data['member_code'];
    $member_code_id = $data['member_code_id'];
    $member_name    = $data['member_name'];
    $order_buyer_tel= $data['order_buyer_tel'];
    $order_detail   = $data['order_detail'];
    $member_code    = $data['member_code'];
    $member_code    = $data['member_code'];
    $member_code    = $data['member_code'];
	?>
	<div class="row card-body m-3">
		<div class="col-3">
			<img src="assets/images/etc/<?php echo $logo_image ?>" alt="Logo" style="width:100%;align-items: center;">
		</div>
		<div class="col-9">
			<h5><?php echo "$l_invoice_comtitle $company $l_invoice_comlast" ?></h5>
			<h6>ที่อยู่</h6>
		</div>
		<div class="col-8 mt-5 text-center">
		<h6>ใบเสร็จรับเงิน/ใบกำกับภาษี/ใบส่งสินค้า</h6>
		RECEIPT/TAX INVOICE/PACKING LIST
		</div>
		<div class="col-4 text-start mt-5">
			<?php 
			echo "<h6>$order_code</h6>";
			echo "$l_date $order_create"; 
			?>
		</div>
		<div class="col-12 border border-2 border-dark my-3 p-3">
			<table class="table table-borderless table-sm">
                <tbody>
                    <?php if ($system_style != 1) { ?>
                        <tr>
                            <th><?php echo $l_member_code ?></th>
                            <td><?php echo $member_code ?></td>
                        </tr>
                        <tr>
                            <th><?php echo $l_idcard ?></th>
                            <td><?php echo $member_code_id ?></td>
                        </tr>
                    <?php } else { ?>
                        <tr>
                            <th>รหัสผู้แนะนำ</th>
                            <td><?php echo $member_code ?></td>
                        </tr>
                        <tr>
                            <th>ชื่อผู้แนะนำ</th>
                            <td><?php echo $member_name ?></td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <th><?php echo $l_order_buyer ?></th>
                        <td><?php echo $order_buyer ?></td>
                    </tr>
                    <?php if ($system_address == 2) { ?>
                        <tr>
                            <th><?php echo $l_invoice_addr ?></th>
                            <td><?php echo $data['order_address'] .
                                    " ตำบล/แขวง " . $data['order_district'] .
                                    " อำเภอ/เขต " . $data['order_amphur'] .
                                    " จังหวัด " . $data['order_province'] .
                                    " รหัสไปรษณีย์ " . $data['order_zipcode'] ?>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <th><?php echo $l_tel ?></th>
                        <td><?php echo $order_buyer_tel ?></td>
                    </tr>
                    <?php echo $order_detail != '' ? "<tr><th>ข้อมูลเพิ่มเติม</th><td>$order_detail</td></tr>": false; ?>
                </tbody>
            </table>
		</div>
		<table class="table table-bordered border-5 border-dark mb-5 table-sm">
			<thead>
				<tr>
					<th><?php echo $l_number ?></th>
					<th><?php echo $l_product_code ?></th>
					<th><?php echo $l_detail ?></th>
					<th><?php echo $l_quantity ?></th>
					<th><?php echo $l_invoice_ppp ?></th>
					<th><?php echo $l_fright ?></th>
					<th><?php echo $l_total ?></th>
				</tr>
			</thead>
			<tbody>
				<?php 
				$query_2 = mysqli_query($connect, "SELECT system_order.*, system_order_detail.*, system_product.*
					FROM system_order
					INNER JOIN system_order_detail ON (system_order.order_id = system_order_detail.order_detail_order)
					INNER JOIN system_product ON (system_product.product_id = system_order_detail.order_detail_product)
					WHERE order_detail_order = '".$_GET['order_id']."'")or die(mysqli_error($connect));
				$i = 0;
				$product_price = 0;
				while ($data_2  = mysqli_fetch_array($query_2)) {
					$i++;
					$product_price = $product_price + ($data_2['order_detail_price'] * $data_2['order_detail_amount'])
					?>
					<tr>
						<td><?php echo $i ?></td>
						<td><?php echo $data_2['product_code'] ?></td>
						<td><?php echo $data_2['product_name'] ?></td>
						<td><?php echo number_format($data_2['order_detail_amount']) ?></td>
						<td><?php echo number_format($data_2['order_detail_price'], 2) ?></td>
						<td><?php echo number_format($data_2['order_detail_freight'], 2) ?></td>
						<td>
							<?php 
							$total = ($data_2['order_detail_price'] + $data_2['order_detail_freight']) * $data_2['order_detail_amount'];
							echo number_format($total, 2) ?>
						</td>
					</tr>
				<?php } ?>
				<tr>
					<td colspan="5"></td>
					<td><?php echo $l_invoice_pdt ?></td>
					<td><?php echo number_format($product_price, 2); ?></td>
				</tr>
				<tr>
					<td colspan="5"></td>
					<td><?php echo $l_invoice_totqty ?></td>
					<td><?php echo $order_amount ?></td>
				</tr>
				<tr>
					<td colspan="5"></td>
					<td><?php echo $l_invoice_totfri ?></td>
					<td><?php echo $order_freight ?></td>
				</tr>
				<tr>
					<td colspan="5"></td>
					<td><?php echo $l_invoice_totprice ?></td>
					<td><?php echo $order_price ?></td>
				</tr>
			</tbody>
		</table>
		<div class="col-8 text-end mb-5"><?php echo $l_invoice_text ?></div>
		<div class="col-4 text-center mb-5"><?php echo "$l_invoice_comtitle $company $l_invoice_comlast" ?></div>
		<div class="col-4 text-center">
			<?php echo $l_invoice_namegetm ?> <br><br><br>
			----/------/------
		</div>
		<div class="col-4 text-center">
			<?php echo $l_invoice_namegetp ?> <br><br><br>
			----/------/------
		</div>
		<div class="col-4 text-center">
			<?php echo $l_invoice_namesign ?>  <br><br><br>
			----/------/------
		</div>
	</div>

<?php } elseif 	(isset($_GET['action']) && $_GET['action'] == 'member_create') { 

	$member_code    = isset($_GET['member_code']) ? $_GET['member_code'] : false;
    $member_keyword = isset($_GET['member_keyword']) ? $_GET['member_keyword'] : false;
    $date_start     = isset($_GET['date_start']) ? $_GET['date_start'] : false;
    $date_end       = isset($_GET['date_end']) ? $_GET['date_end'] : false;

    $where   = "";
    $where_2 = "";
    if (isset($_GET['action'])) {
        $where  = " WHERE (member_code LIKE '%$member_code%') AND (member_name LIKE '%$member_keyword%' OR member_tel LIKE '%$member_keyword%' OR member_code_id LIKE '%$member_keyword%')";
    }

    if (($date_start != false && $date_start != '') || ($date_end != false && $date_end!='')) {
        $where_2 = " AND (member_create BETWEEN '$date_start' AND '$date_end 23:59')";
    }
    $sql   = "SELECT *, COUNT(member_create) AS member_number FROM system_member" . $where . $where_2 . " GROUP BY DATE(member_create)";
	?>
	
	<div class="card-body m-3 mt-3">
		<div class="card-title d-flex align-items-center">
			<h5 class="mb-0 text-primary"><?php echo $l_report_member ?></h5>
		</div>
        <table class="table table-borderless table-sm">
            <tbody>
               <tr>
                    <th width="100"><?php echo $l_datestart ?></th>
                    <td><?php echo $date_start!=false&&$date_start!='' ? datethai($date_start, 0, $lang) : $l_notassign;?></td>
                </tr>
                <tr>
                    <th><?php echo $l_dateend ?></th>
                    <td><?php echo $date_end!=false&&$date_end!='' ? datethai($date_end, 0, $lang) : $l_notassign;?></td>
                </tr>
            </tbody>
        </table>
		<table class="table table-bordered text-center align-middle">
            <thead class="table-light">
				<tr>
                    <th><?php echo $l_date ?></th>
                    <th><?php echo $l_remem_count ?></th>
                    <th><?php echo $l_member_code ?></th>
                    <th><?php echo $l_member_name ?></th>
                    <th><?php echo $l_tel ?></th>
				</tr>
			</thead>
			<tbody>
            <?php 
            $query 				= mysqli_query($connect, $sql . $limit);
            while ($data = mysqli_fetch_array($query)) { 
            	$create  		= date("Y-m-d", strtotime($data['member_create']));
            	$member_number 	= number_format($data['member_number']);
            	?>
                <tr>
                    <td><?php echo datethai($data['member_create'], 0, $lang) ?></td>
                    <td><?php echo $member_number ?> คน</td>
                    <td colspan="8"></td>
                </tr>
                <?php
                $query2 = mysqli_query($connect, "SELECT * FROM system_member $where AND  (member_create LIKE '%$create%')");             
                while ($data2 = mysqli_fetch_array($query2)) { 

                	$member_code  = $data2['member_code'];
                	$member_name  = $data2['member_name'];
                	$member_tel   = $data2['member_tel'];
                	?>
                    <tr>
                        <td colspan="2"></td>
                        <td><?php echo $member_code ?></td>
                        <td><?php echo $member_name ?></td>
                        <td><?php chkEmpty ($member_tel) ?></td>
                    </tr>
                <?php } } ?>
			</tbody>
		</table>
	</div>

<?php } elseif 	(isset($_GET['action']) && $_GET['action'] == 'member') { 

	if ($system_liner == 1) {
		$query = mysqli_query($connect, "SELECT system_member.*, system_liner.* 
	        FROM system_member
	        INNER JOIN system_liner ON (system_member.member_id = system_liner.liner_member)
	        WHERE member_id = '".$_GET['member_id']."'");
	}
	else {
		$query = mysqli_query($connect, "SELECT * FROM system_member WHERE member_id = '".$_GET['member_id']."'");
	}
    $data  = mysqli_fetch_array($query); 
    $member_image_card 	= $data['member_image_card'];
    $member_image_bank 	= $data['member_image_bank'];
    $image_card = $member_image_card != '' ? "<img src='assets/images/card/$member_image_card' alt='IDcard' class='border border-secondary w-100 images'>" : " $l_notfound";
    $image_bank = $member_image_bank != '' ? "<img src='assets/images/card/$member_image_bank' alt='Bank' class='border border-secondary w-100 images'>" : " $l_notfound";
    $member_name= $data['member_title_name'] . $data['member_name'];
    $member_bank= $data['member_bank_name'] . " | Owner " . $data['member_bank_own'];

    ?>
    <style type="text/css">
    	.images {
    		height:250px;
    		object-fit: cover;
    	}
    </style>
	<div class="card-body m-3">
		<div class="card-title d-flex align-items-center">
			<h5 class="mb-0 text-primary"><?php echo $l_report_member ?></h5>
		</div>
		<p><?php echo $l_date . " : " . datethai($date_now, 0, $lang) ?></p>
		<div class="row">
			<?php if ($system_liner == 1) { ?>
			<div class="col-6">
				<?php echo "<b>$l_idcardimage</b>$image_card"; ?>
			</div>
			<div class="col-6">
				<?php echo "<b>$l_bankimage</b>$image_bank"; ?>
			</div>
			<?php } ?>
			<div class="col-12 mt-3">
				<h6><?php echo $l_profile ?></h6>
				<table class="table table-bordered table-sm">
					<tbody>
						<tr>
							<th width="200"><?php echo $l_member_code ?></th>
							<td><?php echo $data['member_code'] ?></td>
						</tr>
						<tr>
							<th><?php echo $l_member_name ?></th>
							<td><?php echo $member_name ?></td>
						</tr>
						<tr>
							<th><?php echo $l_tel ?></th>
							<td><?php echo $data['member_tel'] ?></td>
						</tr>
						<tr>
							<th><?php echo $l_email ?></th>
							<td><?php echo $data['member_email'] ?></td>
						</tr>
						<tr>
							<th><?php echo $l_addridcard ?></th>
							<?php echo $system_address != 0 ? "<td>" . address ($connect, $data['member_id'], 0, $lang) . "</td>": false; ?>
						</tr>
						<?php if ($system_style == 0) { ?>
						<tr>
							<th><?php echo $l_report_member ?></th>
							<td><?php address ($connect, $data['member_id'], 1, $lang) ?></td>
						</tr>
						<?php } ?>
						<tr>
							<th><?php echo $l_bankname ?></th>
							<td><?php echo $member_bank ?></td>
						</tr>
						<tr>
							<th><?php echo $l_bankid ?></th>
							<td><?php echo $data['member_bank_id'] ?></td>
						</tr>
						<?php if ($system_style == 0) { ?>
						<tr>
							<th>ผู้รับมรดก</th>
							<td><?php echo $data['member_heir_name'] ?></td>
						</tr>
						<tr>
							<th>บัตรประชาชนผู้รับมรดก</th>
							<td><?php echo $data['member_heir_id'] ?></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
				<hr>
				<h6><?php echo $l_static ?></h6>
				<table class="table table-bordered table-sm">
					<tbody>
						<tr>
							<th width="200">Month in System</th>
							<td><?php echo number_format($data['member_month']) ?></td>
						</tr>
						<?php if ($com_ppm > 0) { ?>
						<tr>
							<th>Total Point</th>
							<td><?php echo number_format($data['member_point'], 2) . $point_name ?></td>
						</tr>
						<tr>
							<th>Point Per Month</th>
							<td><?php echo number_format($data['member_point_month'], 2) . $point_name ?></td>
						</tr>
						<?php } if ($system_ewallet == 1) { ?>
						<tr>
							<th>E-Wallet</th>
							<td><?php echo number_format($data['member_ewallet'], 2, $l_bath) ?></td>
						</tr>
						<?php } if ($system_liner == 1) { ?>
						<tr>
							<th><?php echo $l_numliner ?></th>
							<td><?php echo number_format($data['liner_count']) ?></td>
						</tr>
						<tr>
							<th><?php echo $l_dash_member ?></th>
							<td><?php echo number_format($data['liner_count_month']) ?></td>
						</tr>
						<tr>
							<th><?php echo $l_remem_linerd ?></th>
							<td><?php echo number_format($data['liner_count_day']) ?></td>
						</tr>
						<tr>
							<th><?php echo $l_remem_liner ?></th>
							<td><?php 
							$query_2 = mysqli_query($connect, "SELECT system_member.*, system_liner.* 
								FROM system_member
								INNER JOIN system_liner ON (system_member.member_id = system_liner.liner_member)
								WHERE member_id = '".$data['liner_direct']."' ");
							$data_2  = mysqli_fetch_array($query_2);
							echo $data_2['member_code'] ?></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

<?php } elseif 	(isset($_GET['action']) && $_GET['action'] == 'pay_refer') { 
	
	$pay_id 	= $_GET['pay_id'];

    $query  	= mysqli_query($connect, "SELECT system_pay_refer.*, system_order.*
        FROM system_pay_refer
        INNER JOIN system_order ON (system_pay_refer.pay_order = system_order.order_id)
        WHERE pay_id = '$pay_id' ");
    $data 		= mysqli_fetch_array($query);

    $pay_image  = $data['pay_image'];
    $pay_buyer  = $data['pay_buyer'];
    $pay_title  = $data['pay_title'];
    $pay_order  = $data['pay_order'];
    $order_code = $data['order_code'];
    $pay_bank   = $data['pay_bank'];
    $pay_money  = number_format($data['pay_money'], 2) . $l_bath;
    $pay_date   = datethai($data['pay_date'], 0, $lang);
    $pay_time   = date("h:i น.", strtotime($data['pay_time']));
    $pay_detail = $data['pay_detail'];
    $pay_create = datethai($data['pay_create'], 2, $lang);
    $pay_type   = $data['pay_type'];
    $pay_status = $data['pay_status'];

	?>
	<div class="card-body m-3">
		<div class="card-title d-flex align-items-center mb-3">
	        <div><i class="bx bx-dish me-1 font-22 text-primary"></i></div>
	        <h5 class="mb-0 text-primary"><?php echo $l_paydetail ?></h5>
	    </div>
        <div class="row">
            <div class="col-12 col-sm-6">
                <?php if ($pay_image != '') { ?>
                    <a href="assets/images/slips/<?php echo $pay_image ?>" target="_blank">
                        <img src="assets/images/slips/<?php echo $pay_image ?>" class="img-fluid mb-5 mb-sm-0">
                    </a>
                <?php } else { echo "<p>$l_notfound</p>"; } ?>
            </div>
            <div class="col">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th><?php echo $l_pay_buyer ?></th>
                            <td><?php echo $pay_buyer ?></td>
                        </tr>
                        <tr>
                            <th><?php echo $l_order_code ?></th>
                            <td><?php echo $order_code ?></td>
                        </tr>
                        <?php echo $pay_bank != '' ? "<tr><th>$l_pay_to</th><td>$pay_bank</td></tr>" : false; ?>
                        <tr>
                            <th><?php echo $l_money ?></th>
                            <td><?php echo $pay_money ?></td>
                        </tr>
                        <tr>
                            <th><?php echo $l_date ?></th>
                            <td><?php echo $pay_date ?></td>
                        </tr>
                        <tr>
                            <th><?php echo $l_time ?></th>
                            <td><?php echo $pay_time ?></td>
                        </tr>
                        <?php echo $pay_detail != '' ? "<tr><th>$l_descrip</th><td>$pay_detail</td></tr>" : false; ?>
                        <tr>
                            <th><?php echo $l_create ?></th>
                            <td><?php echo $pay_create ?></td>
                        </tr>
                        <tr>
                            <th><?php echo $l_type ?></th>
                            <td><?php echo $pay_type == 0 ? "Order Payment" : "Other Payment" ; ?></td>
                        </tr>
                        <tr>
                            <th><?php echo $l_status ?></th>
                            <td>
                            <?php 
                            if      ($pay_status == 1) { echo "<font color='green'>$l_order_status1</font>"; } 
                            elseif  ($pay_status == 2) { echo "<font color='red'>$l_order_status2</font>"; } 
                            else    { echo "<font color='black'>$l_order_status0</font>"; } 
                            ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
	</div>

<?php } elseif 	(isset($_GET['action']) && $_GET['action'] == 'sold') { 

	$report_id 	= $_GET['report_id'];
	$query 		= mysqli_query($connect, "SELECT * FROM system_report WHERE report_id = '$report_id' ");
	$data   	= mysqli_fetch_array($query);

	$query_2 	= mysqli_query($connect, "SELECT system_report_detail.*, system_order_detail.* 
    	FROM system_order_detail
    	INNER JOIN system_report_detail ON (system_report_detail.report_detail_link = system_order_detail.order_detail_order)
    	WHERE report_detail_main = '$report_id' ") or die(mysqli_error($connect));
    $count_product = mysqli_num_rows($query_2);

	?>
	<div class="card-body m-3">
	<div class="page-breadcrumb d-flex align-items-center mb-3">
        <div class="pe-3 text-primary">
            <div class="card-title d-flex align-items-center">
                <div><i class="bx bx-list-ul me-1 font-22 text-primary"></i></div>
                <h5 class="mb-0 text-primary"><?php echo $l_resold_old ?></h5>
            </div>
        </div>
    </div>
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
		</tbody>
	</table>
	<table class="table mb-3 text-center align-middle table-bordered">
        <thead class="table-light">
            <tr>
				<th>#</th>
				<th><?php echo $l_order_code ?></th>
				<th><?php echo $l_date ?></th>
				<th><?php echo $l_price ?></th>
				<th><?php echo $l_product_code ?></th>
				<th><?php echo $l_quantity ?></th>
				<th><?php echo $l_name ?></th>
				<?php echo $system_address == 2 ? "<th>$l_address</th>" : false ?>
				<th><?php echo $l_tel ?></th>
				<th><?php echo $l_pay ?></th>
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
        
        $query  = mysqli_query($connect, $sql) or die(mysqli_error($connect));
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
                <td><?php echo $data['order_code'] ?></td>
                <td><?php echo datethai($data['order_create'], 0, $lang) ?></td>
				<td><?php echo number_format($data['order_detail_price'], 2) . $l_bath ?></td>
                <td><?php echo $data['product_code'] ?></td>
                <td><?php echo number_format($data['order_detail_amount']) . $l_piece ?></td>
                <td><?php echo $data['order_buyer'] ?></td>
                <?php if ($system_address == 2) { ?>
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
            </tr>
			<?php } } else { echo "<tr><td colspan='6'>$l_notfound</td></tr>"; } ?>
		</tbody>
	</table>
	</div>

<?php } elseif 	(isset($_GET['action']) && $_GET['action'] == 'commisison2_now') {
	
	// ---- bill now ----
	$query			= mysqli_query($connect, "SELECT * FROM system_report WHERE report_type = 1 ORDER BY report_round DESC");
	$report_id_now 	= mysqli_fetch_array($query);
	$report_now		= isset($report_id_now['report_round']) ? $report_id_now['report_round'] + 1 : 1;

	$query 			= mysqli_query($connect, "SELECT *, SUM(liner2_point) AS sum_point FROM system_liner2 WHERE (liner2_status = 1) AND (liner2_type = 0) AND (liner2_point >= '$report_min')");
	$data 			= mysqli_fetch_array($query);
	$sum_point 		= $data['sum_point'];

	$sql 			= "SELECT system_member.*, system_liner2.* 
			FROM system_liner2 
			INNER JOIN system_member ON (system_liner2.liner2_member = system_member.member_id)
			WHERE (liner2_status != 0) AND (liner2_point >= '$report_min') AND (liner2_type = 0)";
	$query 			= mysqli_query($connect, $sql);
	$count_member 	= mysqli_num_rows($query);

	$i   = 0; 
	?>
	<title>รายละเอียดตัดยอดเงินปัจจุบัน (ผังพิเศษ)</title>
	<div class="card-title d-flex align-items-center mb-3">
		<div><i class="bx bx-detail me-1 font-22 text-primary "></i></div>
		<h5 class="mb-0 text-primary">รายละเอียดตัดยอดเงินปัจจุบัน (ผังพิเศษ)</h5>
	</div>
	<div class="card border-top border-0 border-4 border-warning">
		<div class="card-body">
			<table class="table table-borderless table-sm">
				<tbody>
					<tr>
						<th width="180">รอบที่</th>
						<td><?php echo $report_now; ?></td>
					</tr>
					<tr>
						<th>ยอดเงินรวม</th>
						<td><?php echo number_format($sum_point, 2) ?> บาท</td>
					</tr>
					<tr>
						<th>จำนวนสมาชิก</th>
						<td><?php echo number_format($count_member) ?> คน</td>
					</tr>
					<tr>
						<th>ยอดขั้นต่ำ / คน</th>
						<td><?php echo number_format($report_min, 2) ?> บาท</td>
					</tr>
					<tr>
						<th>ยอดจ่ายสูงสุด / คน</th>
						<td><?php echo number_format($report_max, 2) ?> บาท</td>
					</tr>
				</tbody>
			</table>
            <table class="table mb-0 text-center align-middle" >
                <thead class="table-light">
                	<tr>
                    	<th>ลำดับ</th>
                        <th>รหัส</th>
                        <th>ชื่อ-สกุล</th>
                        <th>บัตรประชาชน</th>
                        <th>ที่อยู่</th>
                        <th>ธนาคาร</th>
						<th>เลขบัญชี</th>
						<?php 
						if ($report_fee2 > 0 && $report_fee1 > 0) {
							echo "<th>เงินรายได้";
							if ($report_fee2 > 0) 	{ echo " -" . $report_fee2 . "% "; }
							if ($report_fee > 0) 	{ echo " -" . $report_fee1 . " บาท(ค่าธรรมเนียม)"; }
							echo "</th>";
						}
						?>
                        <th>เงินโอนสุทธิ</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $query_member = mysqli_query($connect, $sql) or die(mysqli_error($connect));
                while ($data  = mysqli_fetch_array($query_member)) {
                	if ($system_style == 0) {
                		$member_point = $data['liner2_point'];
                	}
                	else {
                		$member_point = $data['member_point_month'];
                	}
                	$i++;
	                ?>
	                <tr>
	                    <td><?php echo $i + $start ?></td>
	                    <td><?php echo $data['member_code'] ?></td>
	                    <td><?php echo $data['member_name'] ?></td>
	                    <td><?php echo $data['member_code_id'] ?></td>
	                    <?php echo $system_address != 0 ? "<td>" . address ($connect, $data['member_id'], 0, $lang) . "</td>": false; ?>
	                    <td><?php echo $data['member_bank_name'] ?></td>
	                    <td><?php echo $data['member_bank_id'] ?></td>
	                    <?php
	                    if ($report_fee2 > 0 && $report_fee1 > 0) {
		                    echo "<td>" . $member_point;
							if ($report_fee2 > 0) {
								$vat_point = ( $member_point * $report_fee2 ) / 100; 
								echo " -" . $vat_point; 
							}
							if ($report_fee1 > 0) {
								echo " -" . $report_fee1; 
							}
		                    echo "</td>";
						}
						else { $vat_point = 0; }
                    	?>
						<td><?php echo number_format($member_point, 2) . " บาท"; ?></td>
	                </tr>
                <?php } ?>
                </tbody>
            </table>
		</div>
	</div>

<?php } ?>

</body>
</html>