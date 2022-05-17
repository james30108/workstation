<?php

// insert commision 2
function insert_commission_2 (
    $connect,     $bonus_class_2,   $member_code, $liner2_id, 
    $point_bonus, $com_number,      $plus_point,  $sum_bonus) {

    // ---- Insert ID ----
    $array          = array($liner2_id);
    while ($array) {
        
        $array_end  = end($array);
        $query      = mysqli_query($connect, "SELECT * FROM system_liner2 WHERE liner2_id = '$array_end' ");
        $data       = mysqli_fetch_array($query);

        if      ($data['liner2_direct'] != 0) { array_push($array, $data['liner2_direct']); }
        else    { break; }
    }

    // ---- Find Class member And Insert Bonus ---
    $count = 1; // จำนวนชั้น
    foreach ($array AS $key => $value) {

        if ($key == 0) { continue; }

        $sql              = mysqli_query($connect, "SELECT * FROM system_liner2 WHERE (liner2_id = '$value')");
        $data             = mysqli_fetch_array($sql);
        $liner2_id        = $data['liner2_id'];
        $point_member     = $data['liner2_member'];
        $commission_code  = $data['liner2_code'];

        $bonus            = ( $point_bonus  * $bonus_class_2 ) / 100;
        $sum_commission   = ( $sum_bonus    * $bonus_class_2 ) / 100;  
        $point_detail     = $commission_code . " ได้รับ commision จากผัง autorun จากการซื้อสินค้าของสมาชิก " . $member_code . " ลำดับที่ " . $count;
        $liner2_point     = $bonus + $sum_commission; 

        $count++;
        
        if ($point_member != 0) {

            mysqli_query($connect, "INSERT INTO system_point (
                point_member,      
                point_type,     
                point_bonus,     
                point_detail) 
                VALUES (
                '$point_member',   
                2,
                '$bonus',        
                '$point_detail')");

        }
        
        // ---- update point in members get bonus -----
        if ($plus_point == 1) {
            mysqli_query ($connect, "UPDATE system_liner2 SET liner2_point = liner2_point + '$liner2_point' WHERE liner2_id = '$liner2_id' ");
        }
        if ($com_number < $count) { break; }
    }
}

// insert support code
function insert_support_code ($connect, $member_id, $com_number, $commission) {

    $query          = mysqli_query($connect, "SELECT * FROM system_liner2 WHERE (liner2_downline BETWEEN 0 AND 4) ORDER BY liner2_id");
    $data           = mysqli_fetch_array($query);

    $liner2_direct  = $data['liner2_id'];
    $liner2_member  = $data['liner2_member'];
    $liner2_class   = $data['liner2_class'] + 1;

    if ($member_id != 0) {
        $liner2_member = $member_id;
        $liner2_type   = 0;
    }
    else {
        $liner2_member = 0;
        $liner2_type   = 1;
    }

    // ---- เรียงลำดับตัวแปรจากในฐานข้อมูล ----
        $query  = mysqli_query($connect, "SELECT * FROM system_liner2 ORDER BY liner2_id DESC");
        $data   = mysqli_fetch_array($query);
       
        $plus   = $data['liner2_id'] + 1;
        if     ( $plus <= 9 ) { $zero="com-000000"; } 
        elseif ( $plus <= 99 ) { $zero="com-00000"; } 
        elseif ( $plus <= 999 ) { $zero="com-0000"; } 
        elseif ( $plus <= 9999 ) { $zero="com-000"; } 
        elseif ( $plus <= 99999 ) { $zero="com-00"; } 
        elseif ( $plus <= 999999 ) { $zero="com-0"; } 
        else   { $zero=""; }
        $liner2_code = $zero . $plus;
    // ----

    // ---- insert support code ----
    mysqli_query ($connect, "INSERT INTO system_liner2 (
        liner2_code,      
        liner2_direct,    
        liner2_type,    
        liner2_class,    
        liner2_member) 
    VALUES (
        '$liner2_code',   
        '$liner2_direct', 
        '$liner2_type', 
        '$liner2_class', 
        '$liner2_member')");
    $main_id = $connect -> insert_id;    
    
    // ---- line notification and plus liner ----
    $array = array($liner2_direct);
    $count = 1; // จำนวนชั้น
    while ($array) {
        
        $array_end        = end($array);
        $query            = mysqli_query($connect, "SELECT * FROM system_liner2 WHERE liner2_id = '$array_end' ");
        $data             = mysqli_fetch_array($query);
        $liner2_direct    = $data['liner2_direct'];
        $liner2_id        = $data['liner2_id'];
        $liner2_downline  = $data['liner2_downline'];
        
        if ($liner2_id != 0) {

            mysqli_query($connect, "UPDATE system_liner2 SET liner2_count = liner2_count + 1 WHERE liner2_id  = '$liner2_id' ");
            
            if ($liner2_downline < 5) {

                mysqli_query($connect, "UPDATE system_liner2 SET liner2_downline = liner2_downline + 1 WHERE liner2_id = '$liner2_id' ");
            
            }
            if ($liner2_type == 1 && $com_number >= $count) {

                mysqli_query ($connect, "UPDATE system_liner2 
                    SET liner2_point    = liner2_point + '$commission',
                        liner2_sp_count = liner2_sp_count + 1 
                    WHERE liner2_id     = '$liner2_id' ");
                
            }
            $count++;
            array_push($array, $liner2_direct);
        }
        else { break; }
    } 
    return $main_id;
}

// liner2 icon
function modal_2 ($connect, $id) { 
    $sql  = mysqli_query($connect, "SELECT * FROM system_liner2 WHERE liner2_id = '$id' ");
    $data = mysqli_fetch_array($sql);

    if ($data['liner2_type'] == 0) {
        $sql_member     = mysqli_query($connect, "SELECT * FROM system_member WHERE member_id = '".$data['liner2_member']."' ");
        $data_member    = mysqli_fetch_array($sql_member);
        $name           = $data_member['member_name'];
        if ($data['liner2_status'] == 0) {
            $image      = "commission_liner_icon.jpg";
        }
        else {
            $image      = "commission_liner_icon_green.jpg";
        }
        
    }
    else {

        $image = "commission_liner_icon_brown.jpg";
        $name = "support_code";
    }
    ?>
    <a href="#" style="pointer-events: none;"><img src='assets/images/class/<?php echo $image ?>' width=60 height=60 /></a>
    <br>
    <span>
        <?php echo "<font color='blue' size='2'>" . $data['liner2_code'] . "<br>" . mb_substr($name,0,15,'utf-8') . "..</font>"; ?>
        <br>
        <button type="button" class="btn btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal_<?php echo $id ?>" style="width: 80px;">เพิ่มเติม
        </button>
    </span>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal_<?php echo $id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-body">
            <table class="table table-borderless table-sm" style="text-align: left;">
                <tbody>
                    <tr>
                        <th>รหัสในผัง</th>
                        <td><?php echo $data['liner2_code'] ?></td>
                    </tr>
                    <?php 
                    if ($data['liner2_type'] == 0) {
                        $sql_member  = mysqli_query($connect, "SELECT * FROM system_member WHERE member_id = '".$data['liner2_member']."' ");
                        $data_member = mysqli_fetch_array($sql_member); ?>
                        <tr>
                            <th>ประเภท</th>
                            <td>member</td>
                        </tr>
                        <tr>
                            <th>รหัสสมาชิก</th>
                            <td><?php echo $data_member['member_code'] ?></td>
                        </tr>
                        <tr>
                            <th>ชื่อ-สกุล</th>
                            <td><?php echo $data_member['member_name'] ?></td>
                        </tr>
                    <?php } else { ?>
                        <tr>
                            <th>ประเภท</th>
                            <td>support code</td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <th>ตำแหน่ง</th>
                        <td>
                            <?php 
                            if ($data['liner2_status'] == 0) {
                                echo "ธรรมดา";
                            }
                            else {
                                echo "VIP";
                            }
                            ?>    
                        </td>
                    </tr>
                    <tr>
                        <th>อัปไลน์</th>
                        <td>
                            <?php
                            $sql_direct  = mysqli_query($connect, "SELECT * FROM system_liner2 WHERE liner2_id = '".$data['liner2_direct']."' ");
                            $data_direct = mysqli_fetch_array($sql_direct);
                            echo $data_direct['liner2_code'];
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>ลำดับชั้นในตาราง</th>
                        <td>ชั้นที่ <?php echo $data['liner2_class'] ?></td>
                    </tr>
                    <tr>
                        <th>วันที่เพิ่มข้อมูล</th>
                        <td><?php echo $data['liner2_create'] ?></td>
                    </tr>
                </tbody>
            </table>
            <hr>
            <div class="d-flex">
                <div class="ms-auto">
                    <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">close</button>
                </div>  
            </div>
        </div>
    </div>
    </div>
    </div>
    <?php
}

?>