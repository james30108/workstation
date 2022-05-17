<?php include('../../../function.php');

// Commission Cut
$report_now = report_now ($connect, 0);

$query  = mysqli_query($connect, "SELECT SUM(sum.sum_point_member) AS sum_point, COUNT(*) AS count
    FROM
        (SELECT *, SUM(point_bonus) AS sum_point_member FROM system_point 
        WHERE (point_type != 0) AND (point_status = 0) AND (point_member != 0)
        GROUP BY point_member
        HAVING (sum_point_member >= '$report_min')) AS sum
        ") or die ($connect);
$data           = mysqli_fetch_array($query);
$report_point   = $data['sum_point'];
$report_count   = $data['count'];

echo "<br>" . $report_point . " / " . $report_count;

mysqli_query($connect, "INSERT INTO system_report (
    report_point, 
    report_count, 
    report_round, 
    report_create) 
VALUES (
    '$report_point', 
    '$report_count', 
    '$report_now', 
    '$yesterday')")
or die(mysqli_error($connect));
$report_detail_main   = $connect -> insert_id;


$query = mysqli_query($connect, "SELECT *, SUM(point_bonus) AS sum_point 
    FROM system_point 
    WHERE (point_type != 0) AND (point_status = 0)
    GROUP BY point_member
    HAVING sum_point >= '$report_min'");
while ($data = mysqli_fetch_array($query)) {

    echo $data['point_member'] . " / " . $data['sum_point'] . "<br>";

    $report_detail_link   = $data['point_member'];
    $report_detail_point  = $data['sum_point'];

    mysqli_query($connect, "INSERT INTO system_report_detail (
        report_detail_main, 
        report_detail_link, 
        report_detail_point) 
    VALUES (
        '$report_detail_main', 
        '$report_detail_link', 
        '$report_detail_point')");
    
}

// Reset Data to new Month
mysqli_query($connect, "UPDATE system_member SET member_point_month = 0, member_month = member_month + 1");
mysqli_query($connect, "UPDATE system_point  SET point_status = 1");
mysqli_query($connect, "UPDATE system_liner  SET liner_count_month = 0");

?>