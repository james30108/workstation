<?php include('../../../function.php');


if ($_GET['action'] == 'commission') {
 
    commission_cut ($connect, report_now ($connect, 0), $point_type, $today);  
    mysqli_query($connect, "UPDATE system_liner SET liner_point = 0");
    header('location:../../../../admin.php?page=admin_report_commission&status=success&message=0');

} 
elseif ($_GET['action'] == 'special') {

    $report_round   = report_now ($connect, 2); 
    $query          = mysqli_query($connect, "SELECT *, SUM(liner2_point) AS sum, COUNT(*) AS count FROM system_liner2 WHERE (liner2_status != 0) AND (liner2_point >= '$report_min') AND (liner2_type = 0)");
    $data           = mysqli_fetch_array($query);
    $sum_point      = $data['sum'];
    $count_member   = $data['count'];

    mysqli_query($connect, "INSERT INTO system_report (report_point, report_count, report_round, report_type) VALUES ('$sum_point', '$count_member', '$report_round', 2)")or die(mysqli_error($connect));
    $report_id      = $connect -> insert_id;

    // ---- insert bill default's detail ----
    $query = mysqli_query($connect, "SELECT * FROM system_liner2 WHERE (liner2_status != 0) AND (liner2_point >= '$report_min') AND (liner2_type = 0)");
    while ($data = mysqli_fetch_array($query)) {
        
        $report_detail_link   = $data['liner2_member'];
        $report_detail_point  = $data['liner2_point'];

        mysqli_query($connect, "INSERT INTO system_report_detail (report_detail_main, report_detail_link, report_detail_point) 
            VALUES ('$report_id', '$report_detail_link', '$report_detail_point')");

    }
    
    mysqli_query($connect, "UPDATE system_liner2 SET liner2_status = 0, liner2_point = liner2_sp_count * (( 1000 * '$bonus_class_2' ) / 100)");
    mysqli_query($connect, "UPDATE system_liner2 SET liner2_status = 1 WHERE (liner2_position = 1) OR (liner2_type = 1)"); 
    
    header('location:../../../../admin.php?page=special&action=com&status=success&message=0');

}
?>