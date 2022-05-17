<?php include('process/function.php');

$page 				= isset($_GET['page']) ? $_GET['page'] : false;
$member_id          = $_SESSION['member_id'];

$sql_check_login    = mysqli_query($connect,"SELECT * FROM system_member WHERE member_id = '$member_id' ");
$data_check_login   = mysqli_fetch_array($sql_check_login);

$lang 				= $system_lang == 1 ? $data_check_login['member_lang'] : 0;
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
	
<?php if 	(isset($_GET['action']) && $_GET['action'] == 'pay_refer') { 
	
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

<?php } ?>

</body>
</html>