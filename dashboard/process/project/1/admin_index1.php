<div class="col-12 col-sm">
    <div class="card radius-10 overflow-hidden bg-gradient-kyoto">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div>
                    <p class="mb-0 text-white"><?php echo $lang == 0 ? "ผู้ซื้อใหม่ในเดือนนี้": "Buyer in month"; ?></p>
                    <h5 class="mb-0 text-white">
                        <?php 
                        $query = mysqli_query($connect, "SELECT * FROM system_buyer WHERE (buyer_create LIKE '%$month_now%') ");
                        $data  = mysqli_num_rows($query);
                        echo number_format($data); 
                        ?> 
                    </h5>
                </div>
                <div class="ms-auto text-white"><i class='bx bx-user font-30'></i>
                </div>
            </div>
        </div>
    </div>
</div>