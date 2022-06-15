<?php include('../../../function.php');

/*
เงื่อนไขรหัส VIP 
- มีตำแหน่งเป็น 10,000$ โดยที่ไม่ต้องซื้อแพ็กเกจ
- รับ Matching ROI ได้ 4 ชั้น
- รับโบนัสค่าแนะนำได้ตามปกติ
- ไม่มีโบนัส ROI
- ซื้อแพ็กเกจไม่ได้
- เมื่อครบอายุ 40 สัปดาห์ก็จะรีเซ็ตกลายเป็นรหัสธรรมดา และต้องซื้อแพ็กเกจ จึงจะได้รับโบนัส ROI และ Matching ROI ใหม่

วิธีปรับค่า 
- set liner_status = 1, liner_type = 1, member_class = 6
*/

// Sale cut
    $report_round   = report_now ($connect, 1);
    sale_cut ($connect, $report_round);
//


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

        $query2 = mysqli_query($connect, "SELECT * FROM system_member WHERE member_id = '$member' ");
        $data2  = mysqli_fetch_array($query2);
        $member_class = $data2['member_class'];

        if ($member_class > 1) {

            mysqli_query($connect, "UPDATE system_liner SET liner_status = 1 WHERE liner_member = '$member' ");

        }
        else {

            mysqli_query($connect, "UPDATE system_liner SET liner_status = 0 WHERE liner_member = '$member' ");

        }

        mysqli_query($connect, "INSERT INTO system_report_detail (
            report_detail_main, 
            report_detail_link, 
            report_detail_point) 
        VALUES (
            '$report_id', 
            '$member', 
            '$point')");

    }
//


// Commision ROI
    $query = mysqli_query($connect, "SELECT system_member.*, system_liner.*, system_class.* 
            FROM system_member 
            INNER JOIN system_liner ON (system_member.member_id     = system_liner.liner_member) 
            INNER JOIN system_class ON (system_member.member_class  = system_class.class_id)
            WHERE (liner_status >= 1) AND (liner_etc <= 40)");
    while ($data = mysqli_fetch_array($query)) {

        $liner_point = ($data['class_up_level'] * $data['class_com']) / 100;
        $liner_day   = date('D', strtotime($data['liner_create']));
        $liner_create= date('Y-m-d', strtotime($data['liner_create']));
        $liner_id    = $data['liner_id'];
        $member_id   = $data['member_id'];
        $liner_etc   = $data['liner_etc'] + 1;
        $liner_type  = $data['liner_type'];
        $point_detail= "Get commission $liner_point bath in round $liner_etc";

        if ($liner_create != $today && $today_name == $liner_day && $liner_point > 0) {

            mysqli_query($connect, "UPDATE system_liner SET liner_etc = '$liner_etc' WHERE liner_id = '$liner_id' ");

            if ($liner_type == 0) {

                mysqli_query($connect, "UPDATE system_liner SET liner_point = liner_point + '$liner_point' WHERE liner_id = '$liner_id' ");

                mysqli_query($connect, "UPDATE system_member SET member_point_month = member_point_month + '$liner_point' WHERE member_id = '$member_id' ");

                mysqli_query($connect, "INSERT INTO system_point (
                    point_member, 
                    point_bonus, 
                    point_detail,
                    point_type) 
                VALUES (
                    '$liner_id', 
                    '$liner_point', 
                    '$point_detail',
                    2
                )");

            }
            //echo "today " . $today_name . " = " . $liner_day . " ID = " . $liner_id . "<br>";
            
        }
    }

    // Commission Macthing ROI
    $query = mysqli_query($connect, "SELECT * FROM system_liner ");
    $row   = mysqli_num_rows($query);

    for ($i = 1; $i <= $row ; $i++) { 
        
        $query          = mysqli_query($connect, "SELECT system_member.*, system_liner.*, system_class.* 
            FROM system_member 
            INNER JOIN system_liner ON (system_member.member_id     = system_liner.liner_member) 
            INNER JOIN system_class ON (system_member.member_class  = system_class.class_id)
            WHERE (liner_id = '$i') AND (liner_etc <= 20) AND (liner_status != 0) AND (liner_type = 0)");
        $data           = mysqli_fetch_array($query);

        if (!$data) { continue; }

        $liner_id       = $data['liner_id'];
        $liner_point    = ((($data['class_up_level'] * $data['class_com']) / 100) * $bonus_class_2) / 100;
        $liner_code     = $data['member_code'];
        $liner_day      = date('D', strtotime($data['liner_create']));
        $liner_create   = date('Y-m-d', strtotime($data['liner_create']));

        if ($today_name != $liner_day || $liner_create == $today) { continue; }

        $array          = array($liner_id);
        $count = 0;
        while ($array) {
            
            $array_end  = end($array);
            $query      = mysqli_query($connect, "SELECT * FROM system_liner WHERE (liner_id = '$array_end')");
            $data       = mysqli_fetch_array($query);

            if (!$data) { break; }

            if ($data['liner_direct'] != 0 && $count < $com_number) { 

                $count++;
                array_push($array, $data['liner_direct']);
                
            }
            else { break; }
        }

        echo $i . "<br>";
        print_r ($array);

        $count = 0; // จำนวนชั้น
        foreach ($array AS $key => $value) {

            if ($key == 0) { continue; }
            
            $query = mysqli_query($connect, "SELECT system_member.*, system_liner.*, system_class.* 
                FROM system_member 
                INNER JOIN system_liner ON (system_member.member_id     = system_liner.liner_member) 
                INNER JOIN system_class ON (system_member.member_class  = system_class.class_id)
            WHERE (liner_id = '$value')");
            $data  = mysqli_fetch_array($query);

            if (!$data) { continue; }

            $upline_id     = $data['liner_id'];
            $upline_status = $data['liner_status'];
            $upline_week   = $data['liner_etc'];
            $class_match_level= $data['class_match_level'];
            $point_detail  = "Get Commission $liner_point bath from $liner_code ";

            $count++;

            if ($class_match_level < $count || $upline_week > 40 || $upline_status == 0) {
                continue;
            }
            
            echo "upline id -- $value point $liner_point<br>";
            
            mysqli_query($connect, "UPDATE system_liner SET liner_point = liner_point + '$liner_point' WHERE liner_id = '$upline_id' ");

            mysqli_query($connect, "INSERT INTO system_point (
                point_member, 
                point_bonus, 
                point_detail,
                point_type) 
            VALUES (
                '$upline_id', 
                '$liner_point', 
                '$point_detail',
                3
            )");
            
            if ($com_number < $count) { break; }
        }
    }
//

// Reset Status
mysqli_query($connect, "UPDATE system_liner 
    INNER JOIN system_member ON (system_liner.liner_member = system_member.member_id)
    SET liner_status = 0, liner_etc = 0, member_class = 1, member_point = 0 
    WHERE liner_etc >= 40");
mysqli_query($connect, "UPDATE system_withdraw  SET withdraw_cut    = 1");
mysqli_query($connect, "UPDATE system_liner     SET liner_count_day = 0");

?>