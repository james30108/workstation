<?php include('function.php');

if ($_GET['action'] == 'commission') {

    $report_round = report_now ($connect, 0);
    commission_cut ($connect, $report_round, $point_type, $yesterday); 
    header('location:../admin.php?page=admin_report_commission&status=success');

} 
elseif ($_GET['action'] == 'sale') {

    $report_round = report_now ($connect, 1);
    sale_cut ($connect, $report_round);
    header('location:../admin.php?page=admin_report_sold&status=success');

}

?>