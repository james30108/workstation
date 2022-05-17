<?php if ($data_direct) { ?>
    <a href="admin.php?page=special&action=liner_detail&liner2_id=<?php echo $data_direct['liner2_id'] ?>" class="btn btn-primary btn-sm"><i class="bx bx-caret-up me-1 font-22 text-white"></i> อัปไลน์</a>
<?php  } 

// ------------- ฟังก์ชั่นคำนวนหาลูกข่าย ----------------
function member_type ($id, $connect) {

    $query = "SELECT * FROM system_liner2 WHERE liner2_id = '$id' ";
    $data = mysqli_query($connect, $query);
    return $data;
}
?>

<div class="tree d-flex">
<ul class="mx-auto">
<li>
    <?php
    // ------ แสดงไอค่อน member ชั้นที่ 1 ----------
    modal_2($connect, $data['liner2_id']);

    // -------------- หาชั้นที่  2 ต่อ ------------------
    $query_check_2  = mysqli_query($connect, "SELECT * FROM system_liner2 WHERE liner2_direct = '".$data['liner2_id']."'");
    $check_2        = mysqli_fetch_array($query_check_2);

    if ($check_2) {
        echo "<ul style='display:flex;'>";
        $query_liner_2  = mysqli_query($connect, "SELECT * FROM system_liner2 WHERE liner2_direct = '".$data['liner2_id']."'");
        while ($liner_2 = mysqli_fetch_array($query_liner_2)) {

            echo "<li style='text-align: center;'>";
            // นำ id liner มาเทียบหา id member
            $query_member_2 = member_type ($liner_2['liner2_id'], $connect);
            $member_2       = mysqli_fetch_array($query_member_2);

            // ------ แสดงไอค่อน member ชั้นที่ 2 ----------
            modal_2($connect, $member_2['liner2_id']);

            // ----------- หาชั้นที่ 3 ต่อ ----------------------
            $query_check_3  = mysqli_query($connect, "SELECT * FROM system_liner2 WHERE liner2_direct = '".$member_2['liner2_id']."' ");
            $check_3        = mysqli_fetch_array($query_check_3);

            if ($check_3) {

                echo "<ul style='display:flex;'>";
                $query_liner_3  = mysqli_query($connect, "SELECT * FROM system_liner2 WHERE liner2_direct = '".$member_2['liner2_id']."' ");
                while ($liner_3 = mysqli_fetch_array($query_liner_3)) {

                    echo "<li style='text-align: center;'>";

                    $query_member_3 = member_type ($liner_3['liner2_id'], $connect);
                    $member_3       = mysqli_fetch_array($query_member_3);

                    // ------ แสดงไอค่อน member ชั้นที่ 2 ----------
                    modal_2($connect, $member_3['liner2_id']);

                    // ------------ ปุ่มกดเพื่อดูลูกข่ายในชั้นที่ 3 -----------
                    $query_liner_4  = mysqli_query($connect, "SELECT * FROM system_liner2 WHERE liner2_direct = '".$member_3['liner2_id']."' ");
                    $liner_4        = mysqli_fetch_array($query_liner_4);
                    if ($liner_4) { ?>
                        <form action="" method="get">
                            <input type="hidden" name="page" value="special">
                            <input type="hidden" name="action" value="liner_detail">
                            <button name="liner2_id" value="<?php echo $liner_3['liner2_id'] ?>" class="btn btn-primary btn-sm" style="margin-top:5px;width: 80px;">
                                 ดูสายงาน
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