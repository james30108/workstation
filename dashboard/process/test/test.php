<?php include('function.php');

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
?>