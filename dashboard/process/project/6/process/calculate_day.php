<?php include('function.php');

/*

ตัดยอดรายวัน

1. ตัดถอนเงิน
1. รีเซ็ตตัวนับสมาชิกรายวัน

*/

// Withdraw 
    $report_round   = report_now ($connect, 2);
    $query          = mysqli_query($connect, "SELECT SUM(withdraw.group_sum) AS sum, COUNT(*) AS count 
        FROM
            (SELECT *, SUM(withdraw_point) AS group_sum 
            FROM system_withdraw 
            WHERE (withdraw_cut = 0) AND (withdraw_status = 1)
            GROUP BY withdraw_member ) AS withdraw ") or die ($connect);
    $data           = mysqli_fetch_array($query);
    $report_point   = $data['sum'];
    $report_count   = $data['count'];

    echo "<br>" . $report_point . " / " . $report_count . "<br>";

    if ($report_count > 0) {

        mysqli_query($connect, "INSERT INTO system_report (
            report_point, 
            report_count, 
            report_round, 
            report_create,
            report_type) 
        VALUES (
            '$report_point', 
            '$report_count', 
            '$report_round', 
            '$today',
            2)")
        or die(mysqli_error($connect));
        $report_id   = $connect -> insert_id;
    
        $query = mysqli_query($connect, "SELECT * FROM system_withdraw WHERE (withdraw_cut = 0) AND (withdraw_status = 1)");
        while ($data = mysqli_fetch_array($query)) {
    
            $member  = $data['withdraw_member'];
            $point   = $data['withdraw_point'];

            mysqli_query($connect, "INSERT INTO system_report_detail (
                report_detail_main, 
                report_detail_link, 
                report_detail_point) 
            VALUES (
                '$report_id', 
                '$member', 
                '$point')");
    
        }

    }
//

// Reset Status
mysqli_query($connect, "UPDATE system_withdraw  SET withdraw_cut    = 1 WHERE withdraw_status IN (1,2)");
mysqli_query($connect, "UPDATE system_liner     SET liner_count_day = 0");

?>