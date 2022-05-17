<?php 
$query      = mysqli_query($connect, "SELECT *, count(order_member) AS count_order, SUM(order_price) AS sum_price, SUM(order_amount) AS sum_amount
    FROM system_order 
    WHERE(order_create LIKE '%$month_now%') AND (order_member = '$member_id') AND (order_status = 1)");
$data       = mysqli_fetch_array($query);
$count_order= $data['count_order'];
$sum_amount = $data['sum_amount'];
$sum_price  = $data['sum_price'];
?>
<div class="col">
    <div class="card radius-10 overflow-hidden bg-dark bg-gradient">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div>
                    <p class="mb-0 text-white">สินค้าที่ขายได้ในเดือนนี้</p>
                    <h5 class="mb-0 text-white"><?php echo number_format($count_order) ?> รายการ</h5>
                </div>
                <div class="ms-auto text-white"><i class='bx bx-cart font-30'></i>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col">
    <div class="card radius-10 overflow-hidden bg-dark bg-gradient">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div>
                    <p class="mb-0 text-white">ยอดขายเดือนนี้</p>
                    <h5 class="mb-0 text-white"><?php echo number_format($sum_price) ?> บาท</h5>
                </div>
                <div class="ms-auto text-white"><i class='bx bx-bulb font-30'></i>
                </div>
            </div>
        </div>
    </div>
</div>