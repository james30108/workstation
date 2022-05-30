<div class="col-12 col-sm-8 mx-auto">
<div class="card-title d-flex align-items-center">
    <div><i class="bx bx-cog me-1 font-22 text-primary"></i></div>
    <h5 class="mb-0 text-primary">Config</h5>
</div>
<?php isset($_GET["status"]) ? alert ($_GET["status"], $_GET["message"], $lang) : false;

if (!isset($_GET['action'])) { ?>

    <title>Config</title>
    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body p-1 p-sm-5">        
        <form action="process/setting_config.php" method="post">
            <div class="border border-primary rounded p-4 mb-3">
                <div class="col-12 mt-3">
                    <label class="form-label">รูปแบบเว็บ</label>
                    <select class="form-select" name="10">
                        <option value="0" <?php if ($system_style == 0) {echo 'selected';} ?>>Idrinks</option>
                        <option value="1" <?php if ($system_style == 1) {echo 'selected';} ?>>Umeplus</option>
                        <option value="2" <?php if ($system_style == 2) {echo 'selected';} ?>>Alpha World Group</option>
                        <option value="3" <?php if ($system_style == 3) {echo 'selected';} ?>>BCP789</option>
                        <option value="4" <?php if ($system_style == 4) {echo 'selected';} ?>>Goldrex</option>
                        <option value="5" <?php if ($system_style == 5) {echo 'selected';} ?>>Metaone</option>
                    </select>
                </div>
            </div>
            <div class="border border-primary rounded p-4 mb-3">
                <div class="row">
                <h6>ตั้งค่ารายงาน</h6>
                <div class="col-12 col-sm-6 mt-3">
                    <label  class="form-label">การตัดยอด</label>
                    <select class="form-select" name="1">
                        <option value="1" <?php if ($report_type == 1) {echo 'selected';} ?>>กดตัดเอง</option>
                        <option value="0" <?php if ($report_type == 0) {echo 'selected';} ?>>ตัดอัตโนมัติ</option>
                    </select>
                </div>
                <div class="col-12 col-sm-6 mt-3">
                    <label  class="form-label">รูปแบบการคำนวน</label>
                    <select class="form-select" name="28">
                        <option value="1" <?php if ($report_style == 1) {echo 'selected';} ?>>คำนวนแบบรวม</option>
                        <option value="0" <?php if ($report_style == 0) {echo 'selected';} ?>>คำนวนแบบแยกเฉพาะ</option>
                    </select>
                </div>
                <div class="col-12 col-sm-6 mt-3">
                    <label class="form-label">ค่าธรรมเนียม</label>
                    <input type="number" class="form-control" name="2" value="<?php echo $report_fee1 ?>" required>
                </div>
                <div class="col-12 col-sm-6 mt-3">
                    <label class="form-label">เงินปันผลขั้นต่ำ</label>
                    <input type="number" class="form-control" name="3" value="<?php echo $report_min ?>" required>
                </div>
                <div class="col-12 col-sm-6 mt-3">
                    <label class="form-label">เงินปันผลสูงสุด</label>
                    <input type="number" class="form-control" name="4" value="<?php echo $report_max ?>" required>
                </div>
                <div class="col-12 col-sm-6 mt-3">
                    <label class="form-label">ค่าธรรมเนียม2 (%)</label>
                    <input type="number" class="form-control" name="5" step="any" value="<?php echo $report_fee2 ?>" required>
                </div>
                </div>
            </div>
            <div class="border border-primary rounded p-4 mb-3">
                <div class="row">
                <h6>ตั้งค่าเงินปันผล</h6>
                <div class="col-12 col-sm-6 mt-3">
                    <label  class="form-label">ระบบสมาชิก/เครือข่าย</label>
                    <select class="form-select" name="13">
                        <option value="1" <?php if ($system_liner == 1) {echo 'selected';} ?>>เปิด</option>
                        <option value="0" <?php if ($system_liner == 0) {echo 'selected';} ?>>ปิด</option>
                    </select>
                </div>
                <div class="col-12 col-sm-6 mt-3">
                    <label  class="form-label">ระบบเงินปันผลพิเศษ</label>
                    <select class="form-select" name="23">
                        <option value="1" <?php if ($system_liner2 == 1) {echo 'selected';} ?>>เปิด</option>
                        <option value="0" <?php if ($system_liner2 == 0) {echo 'selected';} ?>>ปิด</option>
                    </select>
                </div>
                <div class="col-12 col-sm-6 mt-3">
                    <label class="form-label">ค่ารักษายอด</label>
                    <input type="number" class="form-control" name="7" value="<?php echo $com_ppm ?>" required>
                </div>
                <div class="col-12 col-sm-6 mt-3">
                    <label class="form-label">จำนวนชั้นรับเงินปันผล</label>
                    <input type="number" class="form-control" name="8" value="<?php echo $com_number ?>" required>
                </div>
                <div class="col-12 col-sm-6 mt-3">
                    <label class="form-label">รูปแบบการรักษายอด</label>
                    <select class="form-select" name="9">
                        <option value="1" <?php if ($com_style == 1) {echo 'selected';} ?>>เงินปันผลแต่ละชั้นไม่เท่ากัน</option>
                        <option value="0" <?php if ($com_style == 0) {echo 'selected';} ?>>เงินปันผลเท่ากันทุกชั้น</option>
                    </select>
                </div>
                <div class="col-12 col-sm-6 mt-3">
                    <label  class="form-label">ระบบแจ้งถอนเงินปันผล</label>
                    <select class="form-select" name="27">
                        <option value="1" <?php if ($system_com_withdraw == 1) {echo 'selected';} ?>>เปิด</option>
                        <option value="0" <?php if ($system_com_withdraw == 0) {echo 'selected';} ?>>ปิด</option>
                        <option value="2" <?php if ($system_com_withdraw == 2) {echo 'selected';} ?>>Special (API)</option>
                    </select>
                </div>
                <div class="col-12 mt-3 d-flex">
                    <a href="admin.php?page=admin_config&action=setting_commision" class="btn btn-primary btn-sm ms-auto">ตั้งค่าเงินปันผล</a>
                </div>
                </div>
            </div>
            <div class="border border-primary rounded p-4 mb-3">
                <div class="row">
                <h6>ตั้งค่าระบบตำแหน่ง</h6>
                <div class="col-12 col-sm-6 mt-3">
                    <label class="form-label">ระบบตำแหน่ง</label>
                    <select class="form-select" name="17">
                        <option value="1" <?php if ($system_class == 1) {echo 'selected';} ?>>เปิด</option>
                        <option value="0" <?php if ($system_class == 0) {echo 'selected';} ?>>ปิด</option>
                    </select>
                </div>
                <div class="col-12 mt-3 d-flex">
                    <a href="admin.php?page=admin_config&action=setting_class" class="btn btn-primary btn-sm ms-auto">ตั้งค่าตำแหน่ง</a>
                </div>
                </div>
            </div>
            <div class="border border-primary rounded p-4 mb-3">
                <div class="row">
                <h6>ตั้งค่าอื่นๆ</h6>
                <div class="col-12 col-sm-6 mt-3">
                    <label class="form-label">สมาชิกสูงสุด <font color="red">(0=none)</font></label>
                    <input type="number" class="form-control" name="6" value="<?php echo $downline_max ?>" required>
                </div>
                <div class="col-12 col-sm-6 mt-3">
                    <label  class="form-label">หน้าเว็บเพจ</label>
                    <select class="form-select" name="14">
                        <option value="1" <?php if ($system_webpage == 1) {echo 'selected';} ?>>เปิด</option>
                        <option value="0" <?php if ($system_webpage == 0) {echo 'selected';} ?>>ปิด</option>
                    </select>
                </div>
                <div class="col-12 col-sm-6 mt-3">
                    <label  class="form-label">ระบบ responsive</label>
                    <select class="form-select" name="16">
                        <option value="1" <?php if ($system_mobile == 1) {echo 'selected';} ?>>เปิด</option>
                        <option value="0" <?php if ($system_mobile == 0) {echo 'selected';} ?>>ปิด</option>
                    </select>
                </div>
                <div class="col-12 col-sm-6 mt-3">
                    <label  class="form-label">ระบบซื้อด่วนของแอดมิน</label>
                    <select class="form-select" name="18">
                        <option value="1" <?php if ($system_admin_buy == 1) {echo 'selected';} ?>>เปิด</option>
                        <option value="0" <?php if ($system_admin_buy == 0) {echo 'selected';} ?>>ปิด</option>
                    </select>
                </div>
                <div class="col-12 col-sm-6 mt-3">
                    <label  class="form-label">กระเป๋าเงิน</label>
                    <select class="form-select" name="19">
                        <option value="1" <?php if ($system_ewallet == 1) {echo 'selected';} ?>>เปิด</option>
                        <option value="0" <?php if ($system_ewallet == 0) {echo 'selected';} ?>>ปิด</option>
                    </select>
                </div>
                <div class="col-12 col-sm-6 mt-3">
                    <label  class="form-label">วันหมดอายุ</label>
                    <select class="form-select" name="20">
                        <option value="1" <?php if ($system_member_expire == 1) {echo 'selected';} ?>>เปิด</option>
                        <option value="0" <?php if ($system_member_expire == 0) {echo 'selected';} ?>>ปิด</option>
                    </select>
                </div>
                <div class="col-12 col-sm-6 mt-3">
                    <label  class="form-label">ปิด-ปิดระบบ</label>
                    <select class="form-select" name="11">
                        <option value="1" <?php if ($system_switch == 1) {echo 'selected';} ?>>ปิด</option>
                        <option value="0" <?php if ($system_switch == 0) {echo 'selected';} ?>>เปิด</option>
                    </select>
                </div>
                <div class="col-12 col-sm-6 mt-3">
                    <label  class="form-label">ระบบสต๊อก</label>
                    <select class="form-select" name="21">
                        <option value="1" <?php if ($system_stock == 1) {echo 'selected';} ?>>เปิด</option>
                        <option value="0" <?php if ($system_stock == 0) {echo 'selected';} ?>>ปิด</option>
                    </select>
                </div>
                <div class="col-12 col-sm-6 mt-3">
                    <label  class="form-label">ระบบติดตามการขนส่ง</label>
                    <select class="form-select" name="22">
                        <option value="1" <?php if ($system_tracking == 1) {echo 'selected';} ?>>เปิด</option>
                        <option value="0" <?php if ($system_tracking == 0) {echo 'selected';} ?>>ปิด</option>
                    </select>
                </div>
                <div class="col-12 col-sm-6 mt-3">
                    <label  class="form-label">ภาษาของระบบ</label>
                    <select class="form-select" name="12">
                        <option value="1" <?php if ($system_lang == 1) {echo 'selected';} ?>>เปิด</option>
                        <option value="0" <?php if ($system_lang == 0) {echo 'selected';} ?>>ปิด</option>
                    </select>
                </div>
                <div class="col-12 col-sm-6 mt-3">
                    <label  class="form-label">ระบบสมาชิกเว็บเพจ</label>
                    <select class="form-select" name="15">
                        <option value="1" <?php if ($system_buyer == 1) {echo 'selected';} ?>>เปิด</option>
                        <option value="0" <?php if ($system_buyer == 0) {echo 'selected';} ?>>ปิด</option>
                    </select>
                </div>
                <div class="col-12 col-sm-6 mt-3">
                    <label  class="form-label">การบัททึกที่อยู่</label>
                    <select class="form-select" name="24">
                        <option value="0" <?php if ($system_address == 0) {echo 'selected';} ?>>ปิด</option>
                        <option value="1" <?php if ($system_address == 1) {echo 'selected';} ?>>เปิด - ที่อยู่ตามบัตร</option>
                        <option value="2" <?php if ($system_address == 2) {echo 'selected';} ?>>เปิด - ที่อยู่ตามบัตร & จัดส่ง</option>
                    </select>
                </div>
                <div class="col-12 col-sm-6 mt-3">
                    <label  class="form-label">ระบบชำระเงินประเภทอื่น</label>
                    <select class="form-select" name="25">
                        <option value="1" <?php if ($system_pay == 1) {echo 'selected';} ?>>เปิด</option>
                        <option value="0" <?php if ($system_pay == 0) {echo 'selected';} ?>>ปิด</option>
                    </select>
                </div>
                <div class="col-12 col-sm-6 mt-3">
                    <label  class="form-label">ระบบ Comment</label>
                    <select class="form-select" name="26">
                        <option value="1" <?php if ($system_comment == 1) {echo 'selected';} ?>>เปิด</option>
                        <option value="0" <?php if ($system_comment == 0) {echo 'selected';} ?>>ปิด</option>
                    </select>
                </div>
                <div class="col-12 col-sm-6 mt-3">
                    <label  class="form-label">สินค้าประเภที่ 2</label>
                    <select class="form-select" name="29">
                        <option value="1" <?php if ($system_product_type2 == 1) {echo 'selected';} ?>>เปิด</option>
                        <option value="0" <?php if ($system_product_type2 == 0) {echo 'selected';} ?>>ปิด</option>
                    </select>
                </div>
                </div>
            </div>

            <div class="col-12 mt-5 d-flex">
                <button name="action" value="setting_config" class="btn btn-primary px-5 ms-auto">บันทึก</button>
            </div>
        </form>
        </div>
    </div>

<?php } elseif (isset($_GET['action']) && $_GET['action'] == 'setting_commision') { ?>

    <title>ตั้งค่าเงินปันผล</title>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page"><a href="admin.php?page=admin_config">ตั้งค่า</a></li>
            <li class="breadcrumb-item active" aria-current="page">เงินปันผล</li>
        </ol>
    </nav>
    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body p-1 p-sm-5">        
        <form action="process/setting_config.php" method="post" class="row g-3">
            <div class="col-6">
                <div class="border border-success rounded p-4">
                    <h6 class="mb-3">เงินปันผลลำดับชั้น</h6>
                    <?php
                    if ($com_style == 1) {
                        $sql = "SELECT * FROM system_commission";
                    }
                    else {
                        $sql = "SELECT * FROM system_commission WHERE commission_id = 1";
                    }

                    $query = mysqli_query($connect, $sql);
                    $count = 0;
                    while ($data= mysqli_fetch_array($query)) { 
                        $count++;
                        ?>
                        <div class="col-12 d-flex">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1"><?php echo $data['commission_name'] ?></span>
                            <input type="number" step="any" class="form-control" name="<?php echo $data['commission_id'] ?>" value="<?php echo $data['commission_point'] ?>" placeholder="<?php echo $data['commission_name'] ?>" required>
                        </div>
                        </div>
                        <?php 
                        if ($count == $com_number) {
                            break;
                        }
                    } 
                    ?>
                </div>
            </div>
            <?php 
            if ($system_liner2 == 1) { ?>
                <div class="col-6">
                    <div class="border border-success rounded p-4">
                        <h6 class="mb-3">คอมมิชชั่นลำดับชั้นผังพิเศษ</h6>
                        <?php
                        if ($com_style == 1) {
                            $sql = "SELECT * FROM system_commission";
                        }
                        else {
                            $sql = "SELECT * FROM system_commission WHERE commission_id = 1";
                        }

                        $query = mysqli_query($connect, $sql);
                        $count = 0;
                        while ($data= mysqli_fetch_array($query)) { 
                            $count++;
                            ?>
                            <div class="col-12 d-flex">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="basic-addon1"><?php echo $data['commission_name'] ?></span>
                                <input type="number" step="any" class="form-control" name="com2_<?php echo $data['commission_id'] ?>" value="<?php echo $data['commission_point2'] ?>" placeholder="<?php echo $data['commission_name'] ?>" required>
                            </div>
                            </div>
                            <?php 
                            if ($count == $com_number) {
                                break;
                            }
                        } 
                        ?>
                    </div>
                </div>
            <?php } ?>
            <div class="col-12 mt-5 d-flex">
                <button name="action" value="setting_commision_class" class="btn btn-primary px-5 ms-auto">บันทึก</button>
            </div>
        </form>
        </div>
    </div>

<?php } elseif (isset($_GET['action']) && $_GET['action'] == 'setting_class') { ?>

    <title>ตั้งค่าตำแหน่ง</title>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page"><a href="admin.php?page=admin_config">ตั้งค่า</a></li>
            <li class="breadcrumb-item active" aria-current="page">ระบบตำแหน่ง</li>
        </ol>
    </nav>
    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body">
            <div class="table-responsive">
            <table class="table mb-0 text-center align-middle" >
                <thead class="table-light">
                <tr>
                    <th>ตำแหน่ง</th>
                    <th>คะแนนขั้นต่ำ</th>
                    <th>จำนวนชั้นคอมมิชชัน</th>
                    <th>รูปไอค่อน</th>
                    <th width="120">แก้ไข</th>
                </tr>
            </thead>
            <tbody>
                <form action="process/setting_config.php" method="post" enctype="multipart/form-data">
                <?php 
                if (!isset($_GET['edit_class_id'])) { ?> 
                    <tr>
                        <td><input type="text" class="form-control" name="class_name" required></td>
                        <td><input type="text" class="form-control" name="class_up_level" required></td>
                        <td><input type="text" class="form-control" name="class_match_level" required></td>
                        <td><input class="form-control" type="file" name="class_image" required></td>
                        <td><button name="action" value="insert_class" class="btn btn-primary ms-auto">เพิ่ม</button></td>
                    </tr>
                <?php }
                $query = mysqli_query($connect, "SELECT * FROM system_class");
                while ($data = mysqli_fetch_array($query)) { 

                    if (isset($_GET['edit_class_id']) && $_GET['edit_class_id'] == $data['class_id']) { ?>
                    <input type="hidden" name="class_id" value="<?php echo $data['class_id'] ?>">
                    <input type="hidden" name="class_image" value="<?php echo $data['class_image'] ?>">
                    <tr>
                        <td><input type="text" class="form-control" name="class_name" value="<?php echo $data['class_name'] ?>" required></td>
                        <td><input type="text" class="form-control" name="class_up_level" value="<?php echo $data['class_up_level'] ?>" required></td>
                        <td><input type="text" class="form-control" name="class_match_level" value="<?php echo $data['class_match_level'] ?>" required></td>
                        <td><input class="form-control" type="file" name="class_image_new"></td>
                        <td><button name="action" value="edit_class" class="btn btn-primary ms-auto">บันทึก</button></td>
                    </tr>
                    <?php } else { ?> 
                    <tr>
                        <td><?php echo $data['class_name'] ?></td>
                        <td><?php echo $data['class_up_level'] ?></td>
                        <td><?php echo $data['class_match_level'] ?></td>
                        <td><img src="assets/images/class/<?php echo $data['class_image'] ?>" class="rounded" style="width: 50px;height: 50px;object-fit:cover;"></td>
                        <td><a href="admin.php?page=admin_config&action=setting_class&edit_class_id=<?php echo $data['class_id'] ?>"><i class="bx bx-edit me-1 font-22 text-primary"></i></a></td>
                    </tr>
                <?php } } ?>
                </form>
            </tbody>
            </table>
            </div>
        </div>
    </div>

<?php } ?>

</div>