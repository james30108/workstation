<?php
// ---- point per month ----
$sql_per_month  = mysqli_query($connect, "SELECT * FROM system_config WHERE config_id = 2 ");
$data_per_month = mysqli_fetch_array($sql_per_month);
$point_per_month= $data_per_month['config_value'];

$query          = mysqli_query($connect, "SELECT system_member.*, system_liner.*
    FROM system_member
    INNER JOIN system_liner ON (system_member.member_id = system_liner.liner_member)
    WHERE member_id = '$member_id' ");
$data           = mysqli_fetch_array($query);
$commission     = $data['liner_point_mid'] + $data['liner_point_final'];

$query_class    = mysqli_query($connect, "SELECT * FROM system_class WHERE class_up_level <= '".$data['member_point_month']."' ORDER BY class_id DESC");
$data_class     = mysqli_fetch_array($query_class);
?>

<div class="dash-wrapper bg-dark">
<div class="row row-cols-1 row-cols-md-2 row-cols-xl-2 row-cols-xxl-2">

<div class="col border-end border-light-2">
<div class="card bg-transparent shadow-none mb-0">
    <div class="card-body text-center">
        <p class="mb-1 text-white">คะแนนรักษายอดเดือนนี้</p>  
        <h3 class="mb-3 text-white"><?php echo $data['member_point_month'] ?> PV</h3>

        <?php if ($data['liner_status'] != 0) { ?>
            <p class="font-13 text-white"><span class="text-success">ได้รับเงินปันผลในเดือนนี้</p>
        <?php } else { ?>
            <p class="font-13 text-red"><span class="text-danger">ไม่ได้รับเงินปันผลในเดือนนี้</p>
        <?php } ?>

        <?php if ($data['member_month'] >= 3 && $date_now >= $half_month && $class == 1 && $update_class_ppm == 1 && $data_class['class_id'] >= 2) { ?>
            <p class="font-13 text-white"><span class="text-success">ได้รับเงินปันผลเดือนหน้าในตำแหน่ง <?php echo $data_class['class_name'] ?></p>
        <?php } elseif ($data['member_month'] >= 3 && $date_now >= $half_month && $class == 1 && $update_class_ppm == 1 && $data_class['class_id'] < 2) { ?>
            <p class="font-13 text-red"><span class="text-danger">กรุณารักษายอดเพื่อรับเงินปันผลในเดือนหน้า</p>
        <?php } ?>
    </div>
</div>
</div>
<div class="col border-light-2">
<div class="card bg-transparent shadow-none mb-0">
    <div class="card-body text-center">
        <p class="mb-1 text-white">สมาชิกที่แนะนำในเดือนนี้</p>  
        <h3 class="mb-3 text-white"><?php echo $data['liner_count_month'] ?> คน</h3>
        <?php if ($expire == 1 && $data['liner_count_month'] == 0) { ?>
            <p class="font-13 text-white"><span class="text-danger">หากไม่มีการแนะนำสมาชิกภายในวันที่ <?php echo date("Y-m-d",strtotime($data['liner_expire'])); ?> จะถูกลบออกจากระบบ</p>
        <?php } ?>
    </div>
</div>
</div>
</div><!--end row-->
</div>

<!------------------------------------------------------------>
<style type="text/css">
    @media only screen and (min-width: 768px) {
      /* For desktop: */
      .card_height { height : 350px; }
    }
</style>
<div class="row">
    <div class="col-12 col-sm-6">
        <div class="card border-top border-0 border-4 border-primary card_height">
            <div class="card-body p-1 pt-3 pb-3 p-sm-5">
                <div class="card-title d-flex align-items-center">
                    <div><i class="bx bx-id-card me-1 font-22 text-primary"></i>
                    </div>
                    <h5 class="mb-0 text-primary">ข้อมูลบัญชีผู้ใช้งานระบบ</h5>
                </div>
                <hr>
                <div class="table-responsive">
                <table class="table table-borderless table-sm">
                    <tbody>
                        <tr>
                            <td style="width:120px"><b>รหัสสมาชิก</b></td>
                            <td><?php echo $data['member_code'] ?></td>
                        </tr>
                        <tr>
                            <td><b>ชื่อ - นามสกุล</b></td>
                            <td><?php echo $data['member_name'] ?></td>
                        </tr>
                        <tr>
                            <td><b>สมัครเมื่อ</b></td>
                            <td><?php echo $data['liner_create'] ?></td>
                        </tr>
                        <?php if ($expire == 1) { ?>
                        <tr>
                            <td><b>วันหมดอายุ</b></td>
                            <td><?php echo $data['liner_expire'] ?></td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td><b>ลิงค์ผู้แนะนำ</b></td>
                            <td>
                                <?php //echo "http://idrinks56.com/visitor_insert.php?member_id=" . $member_id; ?>
                                <?php echo "https://bcp789.com/visitor_insert.php?member_id=" . $member_id; ?>
                            </td>
                            <!--
                            <td style="position: relative;">
                                <input type="text" value="www.idrinks56.com/register_member.php?upline_id=<?php echo $smid; ?>" id="myInput" style="border:0px;width:auto" readonly>
                                <button class="btn btn-primary btn-sm" onclick="myFunction()" style="padding:2px;"> Copy</button>
                            </td>
                            -->
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6">
        <div class="card border-top border-0 border-4 border-primary card_height">
            <div class="card-body p-1 pt-3 pb-3 p-sm-5">
                <div class="card-title d-flex align-items-center">
                    <div><i class="bx bx-current-location me-1 font-22 text-primary"></i>
                    </div>
                    <h5 class="mb-0 text-primary">ข้อมูลการติดต่อ</h5>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table table-borderless table-sm">
                        <tbody>
                        <tr>
                            <th style="width:120px"><b>อีเมล์</b></td>
                            <td><?php echo $data['member_email'] ?></td>
                        </tr>
                        <tr>
                            <th>เบอร์โทรศัพท์</td>
                            <td><?php echo $data['member_tel'] ?></td>
                        </tr>
                        <tr>
                            <th>Token Line</td>
                            <td><?php echo $data['member_token_line'] ?></td>
                        </tr>
                        <tr>
                            <th>ที่อยู่สำหรับส่งสินค้า</td>
                            <td>
                                <?php 
                                // ---- ทำที่จัดส่ง ----

                                $sql_provinces  = mysqli_query($connect, "SELECT * FROM system_provinces WHERE PROVINCE_ID = '" . $data['member_provinces_2'] . "' ");
                                $province       = mysqli_fetch_array($sql_provinces);

                                $sql_amphures   = mysqli_query($connect, "SELECT * FROM system_amphures WHERE AMPHUR_ID  = '" . $data['member_amphures_2'] . "' ");
                                $amphures       = mysqli_fetch_array($sql_amphures);

                                $sql_districts  = mysqli_query($connect, "SELECT * FROM system_districts WHERE DISTRICT_CODE  = '" . $data['member_districts_2'] . "' ");
                                $districts      = mysqli_fetch_array($sql_districts);

                                $address        = $data['member_address_2'] . "  ตำบล/แขวง ". $districts['DISTRICT_NAME'] . "<br>อำเภอ/เขต " . $amphures['AMPHUR_NAME'] . "   จังหวัด " . $province['PROVINCE_NAME'] . "<br>รหัสไปรษณีย์ " . $data['member_zipcodes_2'];

                                // ---------------------------------------
                                echo $address;
                                ?>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6">
        <div class="card border-top border-0 border-4 border-primary card_height">
            <div class="card-body p-1 pt-3 pb-3 p-sm-5">
                <div class="card-title d-flex align-items-center">
                    <div><i class="bx bx-credit-card-front me-1 font-22 text-primary"></i>
                    </div>
                    <h5 class="mb-0 text-primary">ข้อมูลบัญชีธนาคาร</h5>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table table-borderless table-sm">
                        <tbody>
                            <tr>
                                <td style="width:120px"><b>ธนาคาร</b></td>
                                <td><?php echo $data['member_bank_name'] ?></td>
                            </tr>
                            <tr>
                                <td><b>สาขา</b></td>
                                <td><?php echo $data['member_bank_area'] ?></td>
                            </tr>
                            <tr>
                                <td><b>เลขที่บัญชี</b></td>
                                <td><?php echo $data['member_bank_id'] ?></td>
                            </tr>
                            <tr>
                                <td><b>ชื่อบัญชี</b></td>
                                <td><?php echo $data['member_bank_own'] ?></td>
                            </tr>
                            <tr>
                                <td><b>ประเภทบัญชี</b></td>
                                <td><?php echo $data['member_bank_type'] ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php if ($commission_liner_2 == 1) { ?>
    <div class="col-12 col-sm-6">
        <div class="card border-top border-0 border-4 border-primary card_height">
            <div class="card-body p-1 pt-3 pb-3 p-sm-5">
                <div class="card-title d-flex align-items-center">
                    <div><i class="bx bx-pyramid me-1 font-22 text-primary"></i>
                    </div>
                    <h5 class="mb-0 text-primary">ข้อมูลกำไรปันผล</h5>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table table-borderless table-sm">
                        <?php 
                        $query      = mysqli_query($connect, "SELECT * FROM system_commission_liner WHERE commission_liner_member = '$member_id' ");
                        $data       = mysqli_fetch_array($query);
                        $commission = $data['commission_liner_point_mid'] + $data['commission_liner_point_final'];
                        ?>
                        <tbody>
                            <tr>
                                <th width=120>ลูกค้า</th>
                                <td><?php echo $data['commission_liner_count'] ?> คน</td>
                            </tr>
                            <tr>
                                <th>เงินปันผล</th>
                                <td><?php echo $commission ?> บาท</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>
<script>
function myFunction() {
    
  /* Get the text field */
  var copyText = document.getElementById("myInput");

  /* Select the text field */
  copyText.select();
  copyText.setSelectionRange(0, 99999); /* For mobile devices */

  /* Copy the text inside the text field */
  navigator.clipboard.writeText(copyText.value);
  
  /* Alert the copied text */
  alert("Copied the text: " + copyText.value);
  
}
</script>