<?php 
$query = mysqli_query($connect, "SELECT * FROM system_liner WHERE liner_member = '$member_id' ");
$data  = mysqli_fetch_array($query);

if ($data['liner_etc'] == 39) { ?>

    <div class="col-12 col-sm">
        <div class="card radius-10 overflow-hidden bg-gradient-burning">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-white">Alert</p>
                        <h5 class="mb-0 text-white">Your commission round nearly expire. Plese topup money into program for reset it.</h5>
                    </div>
                    <div class="ms-auto text-white"><i class='bx bx-error-alt font-30'></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php } ?>