<?php include('../../../function.php');
set_time_limit(0);

// Sale cut
  $report_round   = report_now ($connect, 1);
  sale_cut ($connect, $report_round);
//

?>