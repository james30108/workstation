<?php 
$query= mysqli_query($connect, "SELECT * FROM system_class WHERE class_up_level <= '$member_point_month' ORDER BY class_id DESC");
$data = mysqli_fetch_array($query);
$classnm_id   = $data['class_id'];
$classnm_name = $data['class_name'];


if ($date_now >= $half_month) {

    if ($classnm_id > 1) { ?>

        <div class="col-12 col-sm">
            <div class="card radius-10 overflow-hidden bg-gradient-Ohhappiness">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-white">ผ่านการรักษายอด</p>
                            <h5 class="mb-0 text-white">ได้รับเงินปันผลเดือนหน้าในตำแหน่ง <?php echo $classnm_name ?></h5>
                        </div>
                        <div class="ms-auto text-white"><i class='bx bx-error-alt font-30'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php } elseif ($classnm_id == 1) { ?>

        <div class="col-12 col-sm">
            <div class="card radius-10 overflow-hidden bg-gradient-burning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-white">แจ้งเตือน</p>
                            <h5 class="mb-0 text-white">กรุณารักษายอดเพื่อรับเงินปันผลในเดือนหน้า</h5>
                        </div>
                        <div class="ms-auto text-white"><i class='bx bx-error-alt font-30'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php }
    
}
?>