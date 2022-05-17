<?php include('../function.php');

// Sale Cut
$report_round   = report_now ($connect, 1);
sale_cut ($connect, $report_round);

// Reset
mysqli_query($connect, "UPDATE system_liner SET liner_count_day = 0");

?>