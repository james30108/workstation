<?php include('../../../function.php');

if (!file_exists("../../../../assets/images/slips/")) { mkdir("../../../../assets/images/slips"); }

if ($_GET['action'] == 'confirm_admin') {

    $order_id       = $_GET['order_id'];
    $query          = mysqli_query($connect, "SELECT system_member.*, system_liner.*, system_order.* 
        FROM system_member 
        INNER JOIN system_order ON (system_member.member_id = system_order.order_member)
        INNER JOIN system_liner ON (system_member.member_id = system_liner.liner_member)
        WHERE order_id = '$order_id'");
    $data           = mysqli_fetch_array($query);

    // default data
    $member_id      = $data['order_member'];
    $liner_id       = $data['liner_id'];
    $liner_status   = $data['liner_status'];
    $point_bonus    = $data['order_point'];
    $point_detail   = "Buy for Point";
    $member_code    = $data['member_code'];
    $sum_bonus      = $data['member_point_month'];

    mysqli_query($connect, "UPDATE system_order     SET order_status = 4  WHERE order_id  = '$order_id'");
    mysqli_query($connect, "UPDATE system_pay_refer SET pay_status   = 1  WHERE pay_order = '$order_id' ");
    mysqli_query($connect, "UPDATE system_member    SET 
        member_point        = member_point          + '$point_bonus', 
        member_point_month  = member_point_month    + '$point_bonus' 
        WHERE member_id     = '$member_id' ");
    mysqli_query($connect, "INSERT INTO system_point (point_member, point_bonus, point_detail) VALUES ('$member_id', '$point_bonus', '$point_detail')");
    

    // member's sum point in month
    $query     = mysqli_query($connect, "SELECT * FROM system_member WHERE member_id = '$member_id' ");
    $data      = mysqli_fetch_array($query);
    $sum_point = $data['member_point_month'];

    // commisison to liner
    $array          = array($liner_id);
    while ($array) {
        
        $array_end  = end($array);
        $query      = mysqli_query($connect, "SELECT * FROM system_liner WHERE liner_id = '$array_end' ");
        $data       = mysqli_fetch_array($query);

        if      ($data['liner_direct'] != 0) { array_push($array, $data['liner_direct']); }
        else    { break; }

    }

    $count = 1; // จำนวนชั้น
    foreach ($array AS $key => $value) {

        if ($key == 0) { continue; }

        $query            = mysqli_query($connect, "SELECT system_member.*, system_liner.*
            FROM system_liner 
            INNER JOIN system_member ON (system_liner.liner_member = system_member.member_id)
            WHERE (liner_id = '$value')");
        $data             = mysqli_fetch_array($query);
        $point_member     = $data['member_id'];
        $member_status    = $data['member_status'];
        $upline_status    = $data['liner_status'];

        $bonus            = ( $point_bonus * $bonus_class ) / 100;
        $point_detail     = "Get Referal bonus from $member_code and this number is $count";
        
        // ---- member not block ----
        if ($member_status == 0) {
            
            if ($count > $com_number || $upline_status == 0) { continue; }
            $count++;

            mysqli_query($connect, "INSERT INTO system_point (
                point_member,      
                point_type,     
                point_bonus,  
                point_detail,
                point_order
            ) 
            VALUES (
                '$point_member',   
                1,              
                '$bonus',     
                '$point_detail',
                '$order_id'
            )");

            mysqli_query ($connect, "UPDATE system_liner SET liner_point = liner_point + '$bonus' WHERE liner_member  = '$point_member' ");
            
        }
        else { $count++; }

        if ($count > $com_number) { break; }
        
    }

    // liner2
    $query  = mysqli_query($connect, "SELECT * FROM system_liner2 WHERE liner2_member = '$member_id' ");
    $data   = mysqli_fetch_array($query);
    $liner2_id = $data['liner2_id'];

    if ($sum_point >= $com_ppm && $liner_status == 0) {
        $sum_com = $sum_bonus;
    } else {
        $sum_com = 0;
    }

    if ($sum_point < $com_ppm) { $plus_point = 0; } 
    else { $plus_point = 1; }

    if (!isset($data) && $sum_point >= $com_ppm) {
        
        $liner2_id = insert_support_code($connect, $member_id, $com_number, 0);
        insert_commission_2(
            $connect,
            $bonus_class_2,
            $member_code,
            $liner2_id,
            $point_bonus,
            $com_number,
            $plus_point,
            $sum_com
        );
        
    } elseif (isset($data)) {

        insert_commission_2(
            $connect,
            $bonus_class_2,
            $member_code,
            $liner2_id,
            $point_bonus,
            $com_number,
            $plus_point,
            $sum_com
        );

    }
    
    // update status
    if ($sum_point >= $com_ppm) {
        mysqli_query($connect, "UPDATE system_liner  SET liner_status  = 1 WHERE liner_member  = '$member_id' ");
        mysqli_query($connect, "UPDATE system_liner2 SET liner2_status = 1 WHERE liner2_member = '$member_id' ");
    }

    // url to
    header('location:../../../../admin.php?page=order&status=success&message=0');
    
} 

?>