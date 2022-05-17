<?php include('../../../function.php');

if ($_GET['action'] == 'commission') {
    
    $report_round = report_now ($connect, 0);
    commission_cut ($connect, $report_round, $point_type, $today);  
    mysqli_query($connect, "UPDATE system_liner SET liner_point = 0");
    header("location:../../../../admin.php?page=admin_report_commission&status=success&message=0");

} 
elseif ($_POST['action'] == 'insert_pool') {
    
    $commission = $_POST['commission'];
    mysqli_query($connect, "UPDATE system_commission SET commission_point2 = '$commission' WHERE commission_id = 1 ");
    header("location:../../../../admin.php?page=special&action=com&status=success&message=0");

} 
elseif ($_GET['action'] == 'cut_pool') {

    $report_round       = report_now ($connect, 90); 

    $count_1            = $_GET['count_1'];
    $count_2            = $_GET['count_2'];
    $count_3            = $_GET['count_3'];

    $point_bonus_1      = (40 * $bonus_class) / 100 / $count_1;
    $point_bonus_2      = (5 *  $bonus_class) / 100 / $count_2;
    $point_bonus_3      = (5 *  $bonus_class) / 100 / $count_3;
    $sum_point          = $point_bonus_1 + $point_bonus_2 + $point_bonus_3;

    mysqli_query($connect, "INSERT INTO system_point (point_type, point_bonus, point_detail) VALUES (3, '$bonus_class', 'commission Pool') ");

    mysqli_query($connect, "INSERT INTO system_report (report_point, report_count, report_round, report_type) VALUES ('$sum_point', '$count_1', '$report_round', 90)")or die(mysqli_error($connect));
    $report_id      = $connect -> insert_id;

    $query       = mysqli_query($connect, "SELECT system_member.*, system_liner.*, system_class.* 
        FROM system_liner
        INNER JOIN system_member ON (system_member.member_id = system_liner.liner_member)
        INNER JOIN system_class ON (system_liner.liner_etc = system_class.class_id)
        WHERE liner_etc >= 5");

    while ($data = mysqli_fetch_array($query)) {

        $upline_id  = $data['liner_member'];
        $query2     = mysqli_query($connect, "SELECT * FROM system_liner WHERE (liner_direct = '$upline_id') AND (liner_etc >= 5) ");
        $count      = mysqli_num_rows($query2);
        $downline   = mysqli_fetch_array($query2);

        $commission = 0;

        if ($count >= 5) {

            $commission = $commission + $point_bonus_1;
            mysqli_query($connect, "INSERT INTO system_point (point_member, point_type, point_bonus, point_detail) VALUES ('$upline_id', 2, '$point_bonus_1', 'pool bonus กองที่ 1 (40%) จากโบนัส $commission สมาชิกที่ได้รับร่วมกันมี $count_1 คน') ");

        }
        if ($count >= 10) {

            $commission = $commission + $point_bonus_2;
            mysqli_query($connect, "INSERT INTO system_point (point_member, point_type, point_bonus, point_detail) VALUES ('$upline_id', 2, '$point_bonus_2', 'pool bonus กองที่ 2 (5%) จากโบนัส $commission สมาชิกที่ได้รับร่วมกันมี $count_2 คน') ");

        }
        if ($count >= 20) {

            $commission = $commission + $point_bonus_3;
            mysqli_query($connect, "INSERT INTO system_point (point_member, point_type, point_bonus, point_detail) VALUES ('$upline_id', 2, '$point_bonus_3', 'pool bonus กองที่ 3 (5%) จากโบนัส $commission สมาชิกที่ได้รับร่วมกันมี $count_3 คน') ");

        }

        if ($count >= 5) {

            mysqli_query($connect, "INSERT INTO system_report_detail (report_detail_main, report_detail_link, report_detail_point) 
            VALUES ('$report_id', '$upline_id', '$commission')");

        }
    }

  
    $query = mysqli_query($connect, "SELECT * FROM system_member WHERE member_month >= 4");
    while ($data = mysqli_fetch_array($query)) {

        mysqli_query($connect, "UPDATE system_liner SET liner_etc = 1 WHERE liner_member = '".$data['member_id']."' ");

    }

    header("location:../../../../admin.php?page=special&action=com&status=success&message=0");
    
} 
?>