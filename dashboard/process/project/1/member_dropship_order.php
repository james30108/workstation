<?php if (!isset($_GET['action']) || $_GET['action'] == 'search') {
 
    $order_code   = isset($_GET['order_code']) ? $_GET['order_code']        : false;
    $order_create = isset($_GET['order_create']) ? $_GET['order_create']    : false;
    $member_code  = isset($_GET['member_code']) ? $_GET['member_code']      : false;
    $order_status = isset($_GET['order_status']) ? $_GET['order_status']    : false;
    $where        = "";
    
    if (isset($_GET['action']) && $_GET['action'] == 'search') {
        $where  = " AND (order_code LIKE '%$order_code%') AND (order_create LIKE '%$order_create%') ";
        
        if ($order_status != 'all') {
            $where .= " AND (order_status = '$order_status')";
        }
    }
    $sql    = "SELECT system_order.*, system_pay_refer.* 
        FROM system_order
        INNER JOIN system_pay_refer ON (system_order.order_member = system_pay_refer.pay_order)
        WHERE (order_member = '$member_id') " . $where;
    $order_by   = " ORDER BY order_status ASC, order_create DESC ";
    $query      = mysqli_query($connect, $sql . $order_by . $limit) or die(mysqli_error($connect)); 
    $empty      = mysqli_num_rows($query);
    ?>
    <title>รายงานยอดขาย</title>
    <div class="card-title d-flex align-items-center mb-3">
        <div><i class="bx bx-file me-1 font-22 text-primary"></i>
        </div>
        <h5 class="mb-0 text-primary">รายการสั่งซื้อสินค้า</h5>
    </div>    
    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body">
            <div class="table-responsive">
            <table class="table mb-0 text-center align-middle">
                <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>รหัสคำสั่งซื้อ</th>
                            <th>ชื่อผู้ซื้อ</th>
                            <th>วันที่สั่ง</th>
                            <th>จำนวน</th>
                            <th>ราคา</th>
                            <th>สถานะ</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($empty > 0 ) {
                        $i = 0;
                        while ($data = mysqli_fetch_array($query)) { 
                            $i++;
                            ?>
                            <tr>
                                <td><?php echo $i + $start ?></td>
                                <td>
                                    <a href="member.php?page=member_dropship_order&action=order_detail&order_id=<?php echo $data['order_id'] ?>" target="_blank">
                                        <?php echo $data['order_code'] ?>
                                    </a>
                                </td>
                                <td><?php echo $data['order_buyer'] ?></td>
                                <td><?php echo datethai($data['order_create'], 0) ?></td>
                                <td><?php echo number_format($data['order_amount']) ?> ชิ้น</td>
                                <td><?php echo number_format($data['order_price'] + $data['order_freight'], 2) ?> บาท</td>
                                <td>
                                    <?php if ($data['order_status'] == 0) { 
                                        echo "<font color=gray>รอการตรวจสอบ</font>";
                                    } elseif ($data['order_status'] == 1) {
                                        echo "<font color=red>ยกเลิกโดยแอดมิน</font>";
                                    } elseif ($data['order_status'] == 2) {
                                        echo "<font color=red>ยกเลิกโดยสมาชิก</font>";
                                    } elseif ($data['order_status'] == 3) { 
                                        echo "<font color=green>จัดส่งสินค้าแล้ว</font>"; 
                                    } elseif ($data['order_status'] >= 4) {
                                        echo "<font color=green>เสร็จสิ้น</font>";
                                    } ?>
                                </td>
                            </tr>
                    <?php } } else { ?>
                        <tr><td colspan="7">ไม่พบข้อมูลในระบบ</td></tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php 
            $url = "admin.php?page=admin_order";
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
            <form action="member.php" method="get" class="row g-3">
                <input type="hidden" name="page" value="member_dropship_order">
                <div class="col-12">
                    <label class="form-label">รหัสคำสั่งซื้อ</label>
                    <input type="text" class="form-control" name="order_code" placeholder="รหัสคำสั่งซื้อ" value="<?php echo $order_code ?>">
                </div>
                <?php if ($system_style == 0) { ?>             
                    <div class="col-12">
                        <label class="form-label">รหัสสมาชิก</label>
                        <input type="text" class="form-control" name="member_code" placeholder="รหัสสมาชิก" value="<?php echo $member_code ?>">
                    </div>       
                <?php } ?>
                <div class="col-12">
                    <label class="form-label">วันที่สั่งซื้อ</label>
                    <input type="date" class="form-control" name="order_create" value="<?php echo $order_create ?>">
                </div>
                <div class="col-12">
                    <label class="form-label">สถานะ</label>
                    <select name="order_status" class="form-control">
                        <option value="all" <?php if (!isset($_GET['order_status']) || $_GET['order_status'] == 'all') { echo "selected"; } ?>>ทั้งหมด</option>
                        <option value="0" <?php if (isset($_GET['order_status']) && $_GET['order_status'] == '0') { echo "selected"; } ?>>รอการยืนยัน</option>
                        <option value="1" <?php if (isset($_GET['order_status']) && $_GET['order_status'] == '1') { echo "selected"; } ?>>ยืนยันแล้ว</option>
                        <option value="2" <?php if (isset($_GET['order_status']) && $_GET['order_status'] == '2') { echo "selected"; } ?>>ยกเลิก</option>
                    </select>
                </div>
                <div class="col-12">
                    <button class="btn btn-success btn-sm" name="action" value="search">ค้นหา</button>
                    <a href="member.php?page=member_dropship_order" class="btn btn-secondary btn-sm" type="reset">คืนค่า</a>
                </div>
            </form>
        </div>
    </div>
<?php } elseif (isset($_GET['action']) && $_GET['action']== 'order_detail') {     
    $query = mysqli_query($connect, "SELECT system_member.*, system_order.* 
        FROM system_order 
        INNER JOIN system_member ON (system_order.order_member = system_member.member_id)
        WHERE order_id = '".$_GET['order_id']."'  ORDER BY order_id DESC"); 
    $data  = mysqli_fetch_array($query); 
    ?>
    <title>ใบกำกับสินค้า</title>
    <div class="col-12 col-sm-8 mx-auto">
    <div class="card-title d-flex align-items-center mb-3">
        <div><i class="bx bx-file me-1 font-22 text-primary"></i>
        </div>
        <h5 class="mb-0 text-primary">ใบกำกับสินค้า</h5>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page"><a href="member.php?page=member_dropship_order">รายการสั่งซื้อ</a></li>
            <li class="breadcrumb-item active" aria-current="page">ใบกำกับสินค้า</li>
        </ol>
    </nav>
    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body">
            <div class="col-6 m-3">
                <b>รหัสคำสั่งซื้อ <?php echo $data['order_code'] ?></b><br>
                วันที่ <?php echo datethai($data['order_create'], 0) ?>
            </div>
            <div class="border border-2 p-3 mb-3">
                <table class="table table-borderless table-sm">
                <tbody>
                    <tr>
                        <th>ผู้สั่งซื้อ</th>
                        <td><?php echo $data['order_buyer'] ?></td>
                    </tr>
                    <tr>
                        <th>สถานที่จัดส่งสินค้า</th>
                        <td><?php echo $data['order_address'] .
                                " ตำบล/แขวง " . $data['order_district'] .
                                " อำเภอ/เขต " . $data['order_amphur'] .
                                " จังหวัด " . $data['order_province'] .
                                " รหัสไปรษณีย์ " . $data['order_zipcode'] ?>
                        </td>
                    </tr>
                    <tr>
                        <th>เบอร์โทร</th>
                        <td><?php echo $data['order_buyer_tel'] ?></td>
                    </tr>
                    <?php if ($data['order_detail'] != '') { ?>
                        <tr>
                            <th>ข้อมูลเพิ่มเติม</th>
                            <td><?php echo $data['order_detail'] ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-sm text-center align-middle">
                    <thead>
                        <tr>
                            <th>สินค้า</th>
                            <th width=100>จำนวน</th>
                            <th width=100>ราคา</th>
                            <th width=100>ค่าขนส่ง</th>
                            <th width=100>คะแนน</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $sql_detail 	= mysqli_query($connect, "SELECT system_order_detail.*, system_product.* 
                            FROM system_order_detail
                            INNER JOIN system_product ON (system_order_detail.order_detail_product = system_product.product_id)
                            WHERE order_detail_order = '" . $data['order_id'] . "' ");
                        while ($detail 	= mysqli_fetch_array($sql_detail)) { ?>
                            <tr>
                                <td><?php echo $detail['product_name'] ?></td>
                                <td><?php echo number_format($detail['order_detail_amount']) ?> ชิ้น</td>
                                <td><?php echo number_format($detail['order_detail_price'], 2) ?> บาท</td>
                                <td><?php echo number_format($detail['order_detail_freight'], 2) ?> บาท</td>
                                <td><?php echo number_format($detail['order_detail_point'], 2) ?> PV</td>
                            </tr>
                            <?php } ?>
                        <tr height=30><td style="background-color: #E6E6E6" colspan="5"></td></tr>
                        <tr>
                            <td style="background-color: #E6E6E6" colspan="2"></td>
                            <th>จำนวนรวม</th>
                            <td><?php echo number_format($data['order_amount']) ?></td>
                            <td>ชิ้น</td>
                        </tr>
                        <tr>
                            <td style="background-color: #E6E6E6" colspan="2"></td>
                            <th>คะแนนรวม</th>
                            <td><?php echo number_format($data['order_point'], 2) ?></td>
                            <td>PV</td>
                        </tr>
                        <tr>
                            <td style="background-color: #E6E6E6" colspan="2"></td>
                            <th>ราคารวม</th>
                            <td><?php echo number_format($data['order_price'] + $data['order_freight'], 2) ?></td>
                            <td>บาท</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
<?php } ?>