<?php 

$query = mysqli_query($connect, "SELECT *, SUM(point_bonus) AS sum  FROM system_point 
        WHERE $point_type AND (point_status = 0) AND (point_member = '$member_id') ");
$data  = mysqli_fetch_array($query);
$point = $data['sum'];

?>
<title><?php echo $l_report_com ?></title>
<style type="text/css">
    @media only screen and (min-width: 768px) {
      /* For desktop: */
      .card_height { height : 300px; }
    }
</style>
<div class="col-12 col-sm-7 mx-auto mb-5">
    <div class="card-title d-flex align-items-center">
        <div><i class="bx bx-cut me-1 font-22 text-primary"></i></div>
        <h5 class="mb-0 text-primary"><?php echo $l_com_old ?></h5>
    </div>
    
    <div class="card radius-10 overflow-hidden bg-gradient-Ohhappiness">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div>
                    <p class="mb-0 text-white"><?php echo $l_dash_com ?></p>
                    <h5 class="mb-0 text-white">
                        <?php echo report_final ($point, $report_fee1, $report_fee2, $l_bath, $report_max)[1] ?>
                    </h5>
                </div>
                <div class="ms-auto text-white"><i class='bx bx-dollar-circle font-30'></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table mb-0 text-center align-middle" >
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th><?php echo $l_date ?></th>
                            <th><?php echo $l_com ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql    = "SELECT system_report.*, system_report_detail.* FROM system_report 
                            INNER JOIN system_report_detail ON (system_report.report_id = system_report_detail.report_detail_main) 
                            WHERE (report_detail_link = '$member_id') AND (report_type = 0) ORDER BY report_round DESC";
                        $query  = mysqli_query($connect, $sql . $limit);
                        $empty  = mysqli_num_rows($query);
                        if ($empty > 0) { 
                            $count       = 0;
                            while( $data = mysqli_fetch_array($query)) {
                                $count++;
                                ?>
                                <tr>
                                    <td><?php echo number_format($count) ?></td>
                                    <td><?php echo datethai($data['report_create'], 0, $lang) ?></td>
                                    <td><?php echo number_format($data['report_detail_point'], 2) . $l_bath ?></td>
                                </tr>
                        <?php } } else { echo "<tr><td colspan='3'>$l_notfound</td></tr>"; } ?>
                    </tbody>
                </table>
            </div>
             <?php
                $url = "member.php?page=report_commission";
                pagination ($connect, $sql, $perpage, $page_id, $url); ?>
        </div>
    </div>
</div>