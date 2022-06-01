<?php 
$query = mysqli_query($connect, "SELECT * FROM system_liner WHERE liner_member = '$member_id' ");
$data  = mysqli_fetch_array($query);
$com_round = $data['liner_status'] == 1 ? $data['liner_etc'] : "None" ;
?>
<div class="col-12 col-sm">
    <div class="card radius-10 overflow-hidden bg-gradient-Ohhappiness">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div>
                    <p class="mb-0 text-white"><?php echo $l_promotion; ?></p>
                    <h5 class="mb-0 text-white">
                        <button type="button" data-bs-toggle="modal" data-bs-target="#promotion" class="bg-transparent border-0 text-white text-decoration-underline"><?php echo $l_detail; ?></button>
                    </h5>
                </div>
                <div class="ms-auto text-white"><i class='bx bx-bulb font-30'></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Promotion Modal -->
<div class="modal fade" id="promotion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"><?php echo $l_promotion; ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img src="assets/images/etc/promotion.jpg" class="img-fluid border-0">
                <img src="assets/images/etc/promotion2.jpg" class="img-fluid border-0 mt-1">
            </div>
        </div>
    </div>
</div>
    
<div class="col-12 col-sm">
    <div class="card radius-10 overflow-hidden bg-gradient-Ohhappiness">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div>
                    <p class="mb-0 text-white"><?php echo $lang == 0 ? "รอบการรับปันผล" : "Round Commission ROI"; ?></p>
                    <h5 class="mb-0 text-white"><?php echo $com_round; ?></h5>
                </div>
                <div class="ms-auto text-white"><i class='bx bx-rocket font-30'></i>
                </div>
            </div>
        </div>
    </div>
</div>