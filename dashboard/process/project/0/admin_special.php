<?php if (isset($_GET['action']) && $_GET['action'] == 'liner') { 

    $url_insert = "process/project/0/process/setting_insert_sp.php?action=insert&number=";
    ?>
    <title>ผังเงินปันผลพิเศษ</title>
    <div class="card-title d-block d-sm-flex align-items-center mb-3">
        <div class="card-title d-flex align-items-center mb-3 mb-sm-0">
            <i class="bx bx-credit-card-front me-1 font-22 text-primary"></i>
            <h5 class="col mb-0 text-primary">ผังเงินปันผลพิเศษ</h5>
        </div>
        <div class="d-flex ms-auto">
        <a href="<?php echo $url_insert . "1" ?>" class="btn btn-primary btn-sm ms-auto me-1" onclick="javascript:return confirm('Confirm ?');"> เพิ่ม 1 รหัส</a>
        <a href="<?php echo $url_insert . "5" ?>" class="btn btn-primary btn-sm ms-auto me-1" onclick="javascript:return confirm('Confirm ?');"> เพิ่ม 5 รหัส</a>
        <a href="<?php echo $url_insert . "10" ?>" class="btn btn-primary btn-sm ms-auto me-1" onclick="javascript:return confirm('Confirm ?');"> เพิ่ม 10 รหัส</a>
        </div>
    </div>
    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body">
            <div class="table-responsive">
            <table class="table mb-3 text-center align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>ชั้นที่</th>
                        <th>รหัสในผัง</th>
                        <th>ประเภท</th>
                        <th>ชื่อ - สกุล</th>
                        <th>รหัสสมาชิก</th>
                        <th>ตำแหน่ง</th>
                        <th>รักษายอด</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                $sql    = "SELECT * FROM system_liner2 WHERE liner2_type = 0 ORDER BY liner2_id DESC";
                $query  = mysqli_query($connect, $sql . $limit) or die(mysqli_error($connect));
                $empty  = mysqli_num_rows($query);
                if ($empty > 0) {
                     $i = 0;
                while ($data = mysqli_fetch_array($query)) { 
                    $i++;
                    ?>
                    <tr>
                        <td><?php echo $i + $start ?></td>
                        <td><?php echo $data['liner2_class'] ?></td>
                        <td>
                            <a href="admin.php?page=special&action=liner_detail&liner2_id=<?php echo $data['liner2_id'] ?>" class="me-1" title="Liner 2"><?php echo $data['liner2_code'] ?></a>
                        </td>
                        <?php 
                        if ($data['liner2_type'] == 0) {
                            $sql_member  = mysqli_query($connect, "SELECT * FROM system_member WHERE member_id = '".$data['liner2_member']."' ");
                            $data_member = mysqli_fetch_array($sql_member);

                            echo "<td>member</td>";
                            echo "<td>". $data_member['member_name'] ."</td>";
                            echo "<td>". $data_member['member_code'] ."</td>";

                        }
                        else {
                            echo "<td>support code</td>";
                            echo "<td> - </td>";
                            echo "<td> - </td>";
                        }
                        if      ( $data['liner2_position'] == 0 )   {echo "<td> ปกติ </td>";}
                        else    { echo "<td><font color=green> รหัสบริษัท </font></td>";}
                        if      ( $data['liner2_status'] == 0 )     { echo "<td><font color=red> รักษายอดยังไม่ผ่าน </font></td>";}
                        else    { echo "<td><font color=green> รักษายอดผ่าน </font></td>";}
                        ?>
                    </tr>
                <?php } } else { ?>
                    <tr><td colspan="8">ไม่พบข้อมูลในระบบ</td></tr>
                <?php } ?>
                </tbody>
            </table>
            </div>
            <?php 
            $url = "admin.php?page=special";
            pagination ($connect, $sql, $perpage, $page_id, $url); ?>
        </div>
    </div>
    <!--start switcher-->
    <div class="switcher-wrapper">
        <div class="switcher-btn"> <i class='bx bx-search'></i>
        </div>
        <div class="switcher-body">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 text-uppercase">ค้นหา</h5>
                <button type="button" class="btn-close ms-auto close-switcher" aria-label="Close"></button>
            </div>
            <hr/>
            <form action="admin.php" method="get" class="row g-3">
                <input type="hidden" name="page" value="special">
                <div class="col-12">
                    <label class="form-label">รหัสในผัง</label>
                    <input type="text" class="form-control" name="liner2_code" value="<?php echo $_GET['liner2_code'] ?>" placeholder="รหัสสมาชิก">
                </div>
                <div class="col-12">
                    <label class="form-label">สถานะ</label>
                    <select name="liner2_type" class="form-control">
                        <option value="all" <?php if (!isset($_GET['liner2_type']) && $_GET['liner2_type'] == 'all') { echo "selected"; } ?>>ทั้งหมด</option>
                        <option value="0" <?php if (isset($_GET['liner2_type']) && $_GET['liner2_type'] == '0') { echo "selected"; } ?>>member code</option>
                        <option value="1" <?php if (isset($_GET['liner2_type']) && $_GET['liner2_type'] == '1') { echo "selected"; } ?>>support code</option>
                    </select>
                </div>
                <div class="col-12 mt-3">
                    <button name="action" value="search" class="btn btn-primary btn-sm">ค้นหา</button>
                    <a href="admin.php?page=special" class="btn btn-secondary btn-sm">ยกเลิก</a>
                </div>
            </form>
        </div>
    </div>

<?php } elseif(isset($_GET['action']) && $_GET['action'] == 'liner_detail') { 

    $query= mysqli_query($connect, "SELECT * FROM system_liner2 WHERE liner2_id = '".$_GET['liner2_id']."' ");
    $data = mysqli_fetch_array($query);
    ?>
    <title>ผังเงินปันผลพิเศษ</title>
    <style type="text/css">
        @media only screen and (min-width: 768px) {
          /* For desktop: */
          .card_height { height : 250px; }
        }
    </style>
    <div class="card-title d-flex align-items-center">
        <div><i class="bx bx-pyramid me-1 font-22 text-primary"></i></div>
        <h5 class="mb-0 text-primary">ผังเงินปันผลพิเศษ</h5>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page"><a href="admin.php?page=special&action=liner">ผังเงินปันผล</a></li>
            <li class="breadcrumb-item active" aria-current="page">รายละเอียด</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-12 col-sm-6">
        <div class="card border-top border-0 border-4 border-primary card_height">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless table-sm">
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
                            <?php } else { ?>
                                <tr>
                                    <th>ประเภท</th>
                                    <td>support code</td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <th>ตำแหน่ง</th>
                                <td><?php echo $data['liner2_position'] == 0 ? "ธรรมดา" : "VIP" ;?>    </td>
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
                                <td><?php echo datethai($data['liner2_create'], 0, $system_lang) ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6">
        <div class="card border-top border-0 border-4 border-primary card_height">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless table-sm">
                        <tbody>
                            <tr>
                                <th>จำนวนสมาชิกรวม</th>
                                <td><?php echo $data['liner2_count'] ?> รหัส</td>
                            </tr>
                            <tr>
                                <th>จำนวนสมาชิก 1 ชั้น</th>
                                <td><?php echo $data['liner2_downline'] ?> รหัส</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card border-top border-0 border-4 border-primary">
            <div class="card-body" style="position: relative;width: 100%;overflow-x: scroll;">
                <?php include('process/include_liner2.php'); ?>
            </div>
            </div>
        </div>
    </div>

<?php } elseif (isset($_GET['action']) && $_GET['action'] == 'static') { ?>

    <title>สถิติผังเงินปันผล</title>
    <div class="col-12 col-sm-7 mx-auto mb-5">
    <div class="card-title d-flex align-items-center mb-3">
        <div><i class="bx bx-store-alt me-1 font-22 text-primary"></i></div>
        <h5 class="col mb-0 text-primary">สถิติผังเงินปันผล</h5>
    </div>
    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body">
            <div class="table-responsive">
            <table class="table mb-0 text-center align-middle mb-3">
                <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>ชั้น</th>
                    <th>จำนวนรหัส</th>
                    <th>รหัสสมาชิก</th>
                    <th>Support Code</th>
                    <th>สถานะชั้น</th>
                </tr>
            </thead>
            <tbody>
            <?php 
            $query  = mysqli_query($connect, "SELECT *, 
                COUNT(liner2_class) AS class_count,
                SUM(if(liner2_type = 0, 1, 0)) AS class_member,
                SUM(if(liner2_type = 1, 1, 0)) AS class_sp
                FROM system_liner2 
                GROUP BY liner2_class");
            $empty  = mysqli_num_rows($query);
            if ($empty > 0) {
            $i = 0;
            while ($data = mysqli_fetch_array($query)) { 
                $i++;
                ?>
                <tr>
                    <td><?php echo $i ?></td>
                    <td><?php echo $data['liner2_class'] ?></td>
                    <td><?php echo number_format($data['class_count']) ?></td>
                    <td><?php echo number_format($data['class_member']) ?></td>
                    <td><?php echo number_format($data['class_sp']) ?></td>
                    <td>
                        <?php
                        $class = $data['liner2_class'] - 1;
                        $full  = (pow(5, $class)); 
                        if ($data['class_count'] == $full) {
                            echo "<font color=green>เต็มชั้นแล้ว</font>";
                        }
                        else {
                            $number = $full - $data['class_count'];
                            echo number_format($number) . " / " . number_format($full);
                        }
                        ?>
                    </td>
                </tr>
            <?php } } else { ?>
                <tr><td colspan="6">ไม่พบข้อมูลในระบบ</td></tr>
            <?php } ?>
        </div>
    </div>
    </div>

<?php } elseif (isset($_GET['action']) && $_GET['action'] == 'com') { 

    $query          = mysqli_query($connect, "SELECT *, SUM(liner2_point) AS sum_point FROM system_liner2 WHERE (liner2_status = 1) AND (liner2_type = 0) AND (liner2_point >= '$report_min')");
    $data           = mysqli_fetch_array($query);
    $sum_point      = $data['sum_point'];

    $sql            = "SELECT system_member.*, system_liner2.* 
            FROM system_liner2 
            INNER JOIN system_member ON (system_liner2.liner2_member = system_member.member_id)
            WHERE (liner2_status != 0) AND (liner2_point >= '$report_min') AND (liner2_type = 0)";
    $query          = mysqli_query($connect, $sql);
    $count_member   = mysqli_num_rows($query);
    $cut_url        = "process/project/$system_style/system_cut.php?action=special"; 
    ?>
    <title>รายงานเงินปันผลพิเศษ</title>
    <div class="col-12 col-sm-7 mx-auto">
    <div class="card border-top border-0 border-4 border-warning">
        <div class="card-body p-1 pt-3 pb-3 p-sm-3">
            <div class="card-title d-flex align-items-center">
                <div><i class="bx bx-file me-1 font-22 text-primary"></i></div>
                <h5 class="mb-0 text-primary">รายการตัดยอดรอบปัจจุบัน (ผังพิเศษ)</h5>
            </div>
            <hr>
            <div class="table-responsive">
                <table class="table mb-0 text-center align-middle" >
                    <thead class="table-light">
                        <tr>
                            <th>รอบปัจจุบัน</th>
                            <th>วันที่</th>
                            <th>ยอดเงิน</th>
                            <th>สมาชิก</th>
                            <th>เครื่องมือ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php report_now ($connect, 2); ?></td>
                            <td>
                                <?php if ($count_member == 0 ) { 
                                    echo datethai(date("Y-m-d"), 0, $system_lang);
                                } else { ?>
                                    <a href="admin.php?page=special&action=com_now" title="Detail" target="_blank"><?php echo datethai(date("Y-m-d"), 0, $system_lang) ?></a>
                                <?php } ?>
                            </td>
                            <td><?php echo number_format($sum_point, 2);?> บาท</td>
                            <td><?php echo number_format($count_member) ;?> คน</td>
                            <td>
                                <?php if ($count_member > 0) { ?>

                                    <a href="<?php echo $cut_url ?>" title="Cut" onclick="javascript:return confirm('Confirm ?');"><i class="bx bx-cut me-1 font-22 text-primary"></i></a>

                                <?php } else { ?>

                                    <i class="bx bx-cut me-1 font-22 text-secondary"></i>

                                <?php } ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card border-top border-0 border-4 border-warning">
        <div class="card-body p-1 pt-3 pb-3 p-sm-3">
            <div class="card-title d-flex align-items-center">
                <div><i class="bx bx-cabinet me-1 font-22 text-primary"></i></div>
                <h5 class="mb-0 text-primary">รายการตัดยอดเงินย้อนหลัง (ผังพิเศษ)</h5>
            </div>
            <hr>
            <div class="table-responsive">
                <table class="table mb-0 text-center align-middle" >
                    <thead class="table-light">
                        <tr>
                            <th>รอบที่  </th>
                            <th>วันที่ตัดยอดเงิน</th>
                            <th>ยอดเงินก่อนโอน</th>
                            <th>สมาชิก</th>
                            <th>เครื่องมือ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql        = "SELECT * FROM system_report WHERE report_type = 2";
                        $order_by   = " ORDER BY report_round DESC ";
                        $query      = mysqli_query($connect, $sql . $order_by . $limit) or die(mysqli_error($connect));
                        $empty      = mysqli_num_rows($query);
                        if ($empty > 0) {
                            while ($data = mysqli_fetch_array($query)) { ?>
                                <tr>
                                    <td><?php echo number_format($data['report_round']) ?></td>
                                    <td>
                                        <a href="admin.php?page=detail&action=com&report_id=<?php echo $data['report_id'] ?>" title="รายละเอียด" target="_blank"><?php echo datethai($data['report_create'], 0, $system_lang) ?></a>
                                    </td>
                                    <td><?php echo number_format($data['report_point'], 2) ?> บาท</td>
                                    <td><?php echo number_format($data['report_count']) ?> คน</td>
                                    <td>
                                        <a href="admin_excel.php?action=commission&report_id=<?php echo $data['report_id'] ?>" target="_blank" title="Excel"><i class="bx bx-table me-1 font-22 text-primary"></i></a>&nbsp;&nbsp;
                                        <a href="admin_print.php?action=commisison&report_id=<?php echo $data['report_id'] ?>" target="_blank" title="Report"><i class="bx bx-printer me-1 font-22 text-primary"></i></a>&nbsp;&nbsp;
                                    </td>
                                </tr>
                        <?php } } else { ?>
                            <tr><td colspan="5">ไม่พบข้อมูลในระบบ</td></tr>
                        <?php } ?> 
                    </tbody>
                </table>
            </div>
            <?php 
            $url = "admin.php?page=special&action=com";
            pagination ($connect, $sql, $perpage, $page_id, $url); ?>
        </div>
    </div>
    </div>

<?php } elseif (isset($_GET['action']) && $_GET['action'] == 'com_now') { 

    $query          = mysqli_query($connect, "SELECT *, SUM(liner2_point) AS sum_point FROM system_liner2 WHERE (liner2_status = 1) AND (liner2_type = 0) AND (liner2_point >= '$report_min')");
    $data           = mysqli_fetch_array($query);
    $sum_point      = $data['sum_point'];

    $sql            = "SELECT system_member.*, system_liner2.* 
            FROM system_liner2 
            INNER JOIN system_member ON (system_liner2.liner2_member = system_member.member_id)
            WHERE (liner2_status != 0) AND (liner2_point >= '$report_min') AND (liner2_type = 0)";
    $query          = mysqli_query($connect, $sql);
    $count_member   = mysqli_num_rows($query);

    $i   = 0; 
    ?>
    <title>รายละเอียดตัดยอดเงินปัจจุบัน (ผังพิเศษ)</title>
    <div class="card-title d-flex align-items-center mb-3">
        <div><i class="bx bx-detail me-1 font-22 text-primary "></i></div>
        <h5 class="mb-0 text-primary">รายละเอียดตัดยอดเงินปัจจุบัน (ผังพิเศษ)</h5>
    </div>
    <div class="card border-top border-0 border-4 border-warning">
        <div class="card-body">
            <table class="table table-borderless table-sm">
                <tbody>
                    <tr>
                        <th width="180">รอบที่</th>
                        <td><?php report_now ($connect, 2); ?></td>
                    </tr>
                    <tr>
                        <th>ยอดเงินรวม</th>
                        <td><?php echo number_format($sum_point, 2) ?> บาท</td>
                    </tr>
                    <tr>
                        <th>จำนวนสมาชิก</th>
                        <td><?php echo number_format($count_member) ?> คน</td>
                    </tr>
                    <tr>
                        <th>ยอดขั้นต่ำ / คน</th>
                        <td><?php echo number_format($report_min, 2) ?> บาท</td>
                    </tr>
                    <tr>
                        <th>ยอดจ่ายสูงสุด / คน</th>
                        <td><?php echo number_format($report_max, 2) ?> บาท</td>
                    </tr>
                    <tr>
                        <th>รายงาน</th>
                        <td>
                            <a href="admin_print.php?action=commisison2_now" target="_blank" title="พิมพ์รายงาน"><i class="bx bx-printer me-1 font-22 text-primary"></i></a>
                            <a href="admin_excel.php?action=commisison2_now" target="_blank" title="พิมพ์รายงาน"><i class="bx bx-table me-1 font-22 text-primary"></i></a>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table class="table mb-0 text-center align-middle" >
                <thead class="table-light">
                    <tr>
                        <th>ลำดับ</th>
                        <th>รหัส</th>
                        <th>ชื่อ-สกุล</th>
                        <th>บัตรประชาชน</th>
                        <th>ที่อยู่</th>
                        <th>ธนาคาร</th>
                        <th>เลขบัญชี</th>
                        <?php 
                        if ($report_fee2 > 0 && $report_fee1 > 0) {
                            echo "<th>เงินรายได้";
                            if ($report_fee2 > 0)    { echo " -" . $report_fee2 . "% "; }
                            if ($report_fee1 > 0)    { echo " -" . $report_fee1 . " บาท(ค่าธรรมเนียม)"; }
                            echo "</th>";
                        }
                        ?>
                        <th>เงินโอนสุทธิ</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $query_member = mysqli_query($connect, $sql . $limit) or die(mysqli_error($connect));
                while ($data  = mysqli_fetch_array($query_member)) {
                    
                    $member_point = $data['liner2_point'];
                    $i++;
                    ?>
                    <tr>
                        <td><?php echo $i + $start ?></td>
                        <td><?php echo $data['member_code'] ?></td>
                        <td><?php echo $data['member_name'] ?></td>
                        <td><?php echo $data['member_code_id'] ?></td>
                        <td><?php echo address ($connect, $data['member_code'], 0) ?></td>
                        <td><?php echo $data['member_bank_name'] ?></td>
                        <td><?php echo $data['member_bank_id'] ?></td>
                        <?php
                        if ($report_fee2 > 0 && $report_fee1 > 0) {
                            echo "<td>" . $member_point;
                            if ($report_fee2 > 0) {
                                $vat_point = ( $member_point * $report_fee2 ) / 100; 
                                echo " -" . $vat_point; 
                            }
                            if ($report_fee1 > 0) {
                                echo " -" . $report_fee1; 
                            }
                            echo "</td>";
                        }
                        else { $vat_point = 0; }
                        echo "<td>";
                            $bonus = $member_point - $vat_point - $report_fee1;
                            if ($report_max != 0 && $bonus > $report_max) {
                                echo number_format($report_max, 2) . " บาท <font color=red>(limit)</font>";
                            }
                            elseif ($bonus <= $report_max){
                                echo number_format($bonus, 2) . " บาท";
                            }
                            elseif ($report_max == 0){
                                echo number_format($bonus, 2) . " บาท";
                            }
                        echo "</td>";
                        ?>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <?php 
            $url = "admin.php?page=special&action=com_now";
            pagination ($connect, $sql, $perpage, $page_id, $url); ?>
        </div>
    </div>
    
<?php } ?>