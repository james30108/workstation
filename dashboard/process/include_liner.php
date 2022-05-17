<?php
// find liner_member
$query1 = mysqli_query($connect, "SELECT * FROM system_liner WHERE liner_id = '".$data['liner_direct']."'");
$data1  = mysqli_fetch_array($query1);

if ($page_type == 'member.php' && $_SESSION['liner_id'] != $member_id) { // Member ?>

    <a href="member.php?page=liner&action=liner_tree&member_id=<?php echo $data1['liner_member'] ?>&direct=yes" class="btn btn-primary btn-sm"><i class="bx bx-caret-up me-1 font-22 text-white"></i> Upliner</a>

<?php } elseif ($page_type == 'admin.php' && $_SESSION['liner_id'] != 1) { // Admin ?>

    <a href="admin.php?page=liner&action=liner_tree&member_id=<?php echo $data1['liner_member'] ?>&direct=yes" class="btn btn-primary btn-sm"><i class="bx bx-caret-up me-1 font-22 text-white"></i> Upliner</a>

<?php } 

// ------------- ฟังก์ชั่นคำนวนหาลูกข่าย ----------------
function member_type ($id, $connect) {

    $sql = "SELECT system_member.*, system_liner.*, system_class.*
        FROM system_member
        INNER JOIN system_liner ON (system_liner.liner_member = system_member.member_id)
        INNER JOIN system_class ON (system_class.class_id = system_member.member_class)
        WHERE liner_id = '$id' ";
    $query = mysqli_query($connect, $sql);
    return $query;
}

#### pagination ####

    $perpage    = 5; 
    if (isset($_GET['page_id'])) {
        $page   = $_GET['page_id'];
        $start  = ($page - 1) * $perpage;
    } 
    else {
        $page   = 1;
    }
    $limit      = " LIMIT $start, $perpage ";
    $prepage    = $page - 1;
    $nextpage   = $page + 1; 

    $query      = mysqli_query($connect, "SELECT * FROM system_liner WHERE liner_direct = '".$data['member_id']."'");
    $total_record   = mysqli_num_rows($query);
    $total_page     = ceil($total_record / $perpage);


    if  ($total_record > $perpage && $page_id > 1) { ?>
        
        <a href="<?php echo $page_type ?>?page=liner&action=liner_tree&page_id=<?php echo $prepage ?>" class="btn btn-primary btn-sm"><i class="bx bx-caret-left me-1 font-22 text-white"></i> <?php echo $l_prev ?></a>

    <?php } if  ($total_record > $perpage && $page_id < $total_page) { ?>

        <a href="<?php echo $page_type ?>?page=liner&action=liner_tree&page_id=<?php echo $nextpage ?>" class="btn btn-primary btn-sm"><i class="bx bx-caret-right me-1 font-22 text-white"></i> <?php echo $l_next ?></a>

    <?php } if ($total_record > $perpage) { ?>
        <div class="m-2"><?php echo $page . " / " .$total_page; ?></div>
    <?php } 

#### ##### ####

?>
<div class="tree d-flex">
<ul class="mx-auto">
<li>
    <?php
    // ------ แสดงไอค่อน member ชั้นที่ 1 ----------
    modal(  $data['member_id'],     $data['member_code'],   $data['member_name'],
            $data['member_tel'],    $data['member_email'],  $data['member_create'], 
            $data['liner_status'],  $data['class_image'],   $system_class);

    // -------------- หาชั้นที่  2 ต่อ ------------------
    $query_check_2  = mysqli_query($connect, "SELECT * FROM system_liner WHERE liner_direct = '".$data['liner_id']."'");
    $check_2        = mysqli_fetch_array($query_check_2);

    if ($check_2) {
        echo "<ul style='display:flex;'>";
        $query_liner_2  = mysqli_query($connect, "SELECT * FROM system_liner WHERE liner_direct = '".$data['liner_id']."'" . $limit);
        while ($liner_2 = mysqli_fetch_array($query_liner_2)) {

            echo "<li style='text-align: center;'>";
            // นำ id liner มาเทียบหา id member
            $query_member_2 = member_type ($liner_2['liner_id'], $connect);
            $member_2       = mysqli_fetch_array($query_member_2);

            // ------ แสดงไอค่อน member ชั้นที่ 2 ----------
            modal(  $member_2['member_id'],     $member_2['member_code'],   $member_2['member_name'],
                    $member_2['member_tel'],    $member_2['member_email'],  $member_2['member_create'],
                    $member_2['liner_status'],  $member_2['class_image'],   $system_class );

            // ----------- หาชั้นที่ 3 ต่อ ----------------------
            $query_check_3  = mysqli_query($connect, "SELECT * FROM system_liner WHERE liner_direct = '".$member_2['liner_id']."' ");
            $check_3        = mysqli_fetch_array($query_check_3);

            if ($check_3) {

                echo "<ul style='display:flex;'>";
                $query_liner_3  = mysqli_query($connect, "SELECT * FROM system_liner WHERE liner_direct = '".$member_2['liner_id']."' ");
                while ($liner_3 = mysqli_fetch_array($query_liner_3)) {

                    echo "<li style='text-align: center;'>";

                    $query_member_3 = member_type ($liner_3['liner_id'], $connect);
                    $member_3       = mysqli_fetch_array($query_member_3);

                    // ------ แสดงไอค่อน member ชั้นที่ 2 ----------
                    modal(  $member_3['member_id'],     $member_3['member_code'],   $member_3['member_name'],
                            $member_3['member_tel'],    $member_3['member_email'],  $member_3['member_create'],
                            $member_3['liner_status'],  $member_3['class_image'],   $system_class );

                    // ------------ ปุ่มกดเพื่อดูลูกข่ายในชั้นที่ 3 -----------
                    $query_liner_4  = mysqli_query($connect, "SELECT * FROM system_liner WHERE liner_direct = '".$member_3['liner_id']."' ");
                    $liner_4        = mysqli_fetch_array($query_liner_4);
                    if ($liner_4) { ?>
                        <form action="" method="get">
                            <input type="hidden" name="page" value="liner">
                            <input type="hidden" name="action" value="liner_tree">
                            <input type="hidden" name="direct" value="yes">
                            <button name="member_id" value="<?php echo $member_3['liner_member'] ?>" class="btn btn-primary btn-sm" style="margin-top:5px;width: 80px;">
                                liner
                            </button>
                        </form>
                    <?php }
                    // -- ชั้นที่ 3 ---
                    echo "</li>";
                }
                echo "</ul>";
            } 
            // ---- ชั้นที่ 2 -----
            echo "</li>";
        }
        echo "</ul>";
    } ?>
<!-- ชั้นที่ 1 -->
</li>
</ul>
</div>