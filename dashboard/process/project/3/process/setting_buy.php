<?php include('../../../function.php');

if (!file_exists("../../../../assets/images/slips/")) { mkdir("../../../../assets/images/slips"); }

if ($_GET['action'] == 'confirm_admin') {
    
    $page           = isset($_GET['page']) ? $_GET['page'] : "admin.php";
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
    $point_detail   = "ซื้อรักษายอด";
    $member_code    = $data['member_code'];
    $sum_bonus      = $data['member_point_month'];
    $order_type_buy = $data['order_type_buy'];

    if ($data['order_status'] == 4) { die(); }
    
    // ewallet
    $ewallet        = $order_type_buy == 1 || $order_type_buy == 3 ? 1 : 0 ;

    mysqli_query($connect, "UPDATE system_order     SET order_status = 4  WHERE order_id  = '$order_id'");
    mysqli_query($connect, "UPDATE system_pay_refer SET pay_status   = 1  WHERE pay_order = '$order_id' ");
    mysqli_query($connect, "UPDATE system_member    SET 
        member_point        = member_point          + '$point_bonus', 
        member_point_month  = member_point_month    + '$point_bonus' 
        WHERE member_id     = '$member_id' ");
    mysqli_query($connect, "INSERT INTO system_point (point_member, point_bonus, point_detail) VALUES ('$member_id', '$point_bonus', '$point_detail')");

    // Member's sum point in month
    $query     = mysqli_query($connect, "SELECT * FROM system_member WHERE member_id = '$member_id' ");
    $data      = mysqli_fetch_array($query);
    $member_point_month = $data['member_point_month'];
    $member_point   = $data['member_point'];
    $member_month   = $data['member_month'];
    $member_class   = $data['member_class'];

    // Update Class
    $query = mysqli_query($connect, "SELECT * FROM system_class WHERE class_up_level <= '$member_point' ORDER BY class_id DESC");
    $data  = mysqli_fetch_array($query);
    $class_id1 = $data['class_id'];

    $query = mysqli_query($connect, "SELECT * FROM system_class WHERE class_up_level <= '$member_point_month' ORDER BY class_id DESC");
    $data  = mysqli_fetch_array($query);
    $class_id2 = $data['class_id'];

    $class_id = $member_month < 4 ? $class_id1 : $class_id2;

    mysqli_query($connect, "UPDATE system_member SET member_class = '$class_id1' WHERE member_id     = '$member_id' ");
    mysqli_query($connect, "UPDATE system_liner  SET liner_etc    = '$class_id'  WHERE (liner_member = '$member_id') AND (liner_type = 0) ");

    // Insert Bonus Default
    /*
    1. คอมมิชชั่นคำนวนตาม % ในแต่ละชั้นของตำแหน่ง
    2. ถ้ายังรักษายอดไม่ผ่านก็จะไม่บวกคะแนน
    3. ถ้ารักษายอดผ่านก็จะทำการบวกคะแนนลงไป
    4. มีการ Roll Up
    */

    if ($liner_status == 0 && $member_point_month >= $com_ppm) { 

        mysqli_query($connect, "UPDATE system_liner SET liner_status = 1 WHERE liner_member = '$member_id' ");
        $commission = $member_point_month; 

    }
    elseif ($liner_status == 1) { $commission = $point_bonus; }
    else    { $commission = 0; }

    

    if ($commission > 0) {

        $array  = array($liner_id);
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

            $query            = mysqli_query($connect, "SELECT system_member.*, system_liner.*, system_class.* 
                FROM system_liner 
                INNER JOIN system_member ON (system_liner.liner_member = system_member.member_id)
                INNER JOIN system_class  ON (system_liner.liner_etc = system_class.class_id)
                WHERE (liner_member = '$value')");
            $data             = mysqli_fetch_array($query);
            $point_member     = $data['member_id'];
            $commission_code  = $data['member_code'];
            $member_status    = $data['member_status'];
            $class_match_level= $data['class_match_level'];

            $query = mysqli_query($connect, "SELECT * FROM system_commission WHERE commission_id = '$count' ");
            $data  = mysqli_fetch_array($query);
            $bonus_class      = $data['commission_point'];

            $point            = ( $commission * $bonus_class ) / 100;
            $point_detail     = "รับโบนัสจาก $member_code ซึ่งเป็นลำดับที่ $count";

            echo "<br>$value // commission = $";

            // ---- member not block ----
            if ($member_status == 0) {

                if ($class_match_level < $count) { continue; }
                $count++;

                mysqli_query($connect, "INSERT INTO system_point (point_member, point_type, point_bonus, point_detail) VALUES ('$point_member', 1, '$point', '$point_detail')");

                mysqli_query ($connect, "UPDATE system_liner SET liner_point = liner_point + '$point' WHERE liner_member = '$point_member' ");

            }
       
            if ($count > $com_number) { break; }

        }

    }

    // url to
    header("location:../../../../$page?page=order&status=success&message=0");

}

?>