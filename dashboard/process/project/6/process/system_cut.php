<?php include('../../../function.php');

if ($_GET['action'] == 'commission') {

    $report_round = report_now ($connect, 0);
    commission_cut ($connect, $report_round, $point_type, $yesterday, $report_min);
    header('location:../../../../admin.php?page=admin_report_commission&status=success&message=0');

} 
elseif ($_GET['action'] == 'sale') {

    $report_round = report_now ($connect, 1);
    sale_cut ($connect, $report_round);
    header('location:../../../../admin.php?page=admin_report_sold&status=success&message=0');

}

?>