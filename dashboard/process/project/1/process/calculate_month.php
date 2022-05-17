<?php include('../../../function.php');

// Commission Cut
$report_now     = report_now ($connect, 0);
$query          = mysqli_query($connect, "SELECT *, SUM(member_point_month) AS sum_point, COUNT(*) AS count 
    FROM system_member 
    WHERE member_point_month > '$report_min'");

$data           = mysqli_fetch_array($query);
$sum_point      = $data['sum_point'];
$count_member   = $data['count'];

echo $sum_point . " / " . $count_member . "<br>";

mysqli_query($connect, "INSERT INTO system_report (
    report_point, 
    report_count, 
    report_round, 
    report_create) 
VALUES (
    '$sum_point', 
    '$count_member', 
    '$report_now', 
    '$yesterday')")
or die(mysqli_error($connect));
$report_id      = $connect -> insert_id;

// ---- insert bill default's detail ----
while ($data = mysqli_fetch_array($query_member)) {
    
    $member_id  = $data['member_id'];
    $point      = $data['member_point_month'];

    mysqli_query($connect, "INSERT INTO system_report_detail (
        report_detail_main, 
        report_detail_link, 
        report_detail_point) 
    VALUES (
        '$report_id', 
        '$member_id', 
        '$point')");

    mysqli_query($connect, "UPDATE system_member 
        SET member_point_month = 0 
        WHERE member_id = '$member_id' ");
}

// Reset Data to new Month
mysqli_query($connect, "UPDATE system_member SET member_point_month = 0, member_month = member_month + 1");
mysqli_query($connect, "UPDATE system_point  SET point_status = 1");
?>