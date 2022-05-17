<?php 
$query      = mysqli_query($connect, "SELECT *, count(order_member) AS count 
    FROM system_order 
    WHERE order_member = '$member_id'
    GROUP BY MONTH(order_create)
    ORDER BY order_create ASC
    LIMIT 5
    ") or die(mysqli_error($connect));
$order_sold  = array();
$order_month = array();
while ($data = mysqli_fetch_array($query)) {
    array_push($order_sold, $data['count']);
    array_push($order_month, datethai($data['order_create'], 1, $lang));
}
?>
<script type="text/javascript">
    $(function () {
        // chart 3
        var options = {
            series: [{
                name: 'รายการขาย',
                data: <?php echo json_encode($order_sold) ?>
            }],
            chart: {
                foreColor: '#9a9797',
                type: 'bar',
                height: 320,
                stacked: true,
                toolbar: {
                    show: false
                },
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '18%',
                    endingShape: 'rounded'
                },
            },
            legend: {
                show: false,
                position: 'top',
                horizontalAlign: 'left',
                offsetX: -20
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            colors: ["#e62e2e", "#29cc39", "#0dcaf0"],
            yaxis: {
                labels: {
                    formatter: function (value) {
                        return value;
                    }
                },
            },
            xaxis: {
                categories: <?php echo json_encode($order_month) ?>
            },
            fill: {
                opacity: 1
            },
            grid: {
               show: true,
               borderColor: '#ededed',
               strokeDashArray: 4,
            },
            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        height: 310,
                    },
                    plotOptions: {
                        bar: {
                            columnWidth: '30%'
                        }
                    }
                }
            }],
            tooltip: {
                theme: 'dark',
                y: {
                    formatter: function (val) {
                        return "" + val + " รายการ"
                    }
                }
            }
        };
        var chart = new ApexCharts(document.querySelector("#chart3"), options);
        chart.render();

    });
</script>
<div class="row">
    <div class="col-12 col-lg-7">
        <div class="card radius-10">
            <div class="card-header border-bottom-0 bg-transparent">
                <div class="d-lg-flex align-items-center">
                    <div>
                        <h6 class="font-weight-bold mb-2 mb-lg-0">ยอดขายสินค้าย้อนหลัง</h6>
                    </div>
                    <div class="font-22 ms-auto"><i class="bx bx-dots-horizontal-rounded"></i>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="chart3"></div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-5">
        <div class="card radius-10">
            <div class="card-body">
                <div class="revenue-by-channel">
                    <h6 class="font-weight-bold">สินค้าขายดีของท่านในเดือนนี้</h6>
                    <?php
                    $query = mysqli_query($connect, "SELECT system_order.*, system_order_detail.*, system_product.*, 
                        SUM(order_detail_amount) AS count_order, order_detail_price * SUM(order_detail_amount) AS price
                        FROM system_order_detail
                        INNER JOIN system_order ON (system_order.order_id = system_order_detail.order_detail_order)
                        INNER JOIN system_product ON (system_order_detail.order_detail_product = system_product.product_id)
                        WHERE (order_member = '$member_id') AND (order_status = 1) AND (order_create LIKE '%$month_now%')
                        GROUP BY order_detail_product
                        ORDER BY price DESC
                        LIMIT 3") or die(mysqli_error($connect));
                    $sum_price_top  = 0;
                    while ($data    = mysqli_fetch_array($query)) {
                        $percent    = ($data['price'] * 100) / $sum_price;
                        $sum_price_top = $sum_price_top + $data['price']
                        ;
                        ?>
                        <div class="progress-wrapper mt-3">
                            <div class="d-flex align-items-center">
                                <div class="text-secondary"><?php echo mb_strimwidth($data['product_name'], 0, 20, "...") ?></div>
                                <div class="ms-auto pe-4"><?php echo number_format($data['price']) ?> บาท</div>
                                <div class="text-success"><?php echo number_format($percent, 2) ?>%</div>
                            </div>
                            <div class="progress mt-2" style="height:3px;">
                                <div class="progress-bar" role="progressbar" style="width: <?php echo number_format($percent) ?>%"></div>
                            </div>
                        </div>
                    <?php } 
                    $price = $sum_price - $sum_price_top;
                    if ($price > 0) { 
                        $percent = ($price * 100) / $sum_price;
                        ?>
                        <div class="progress-wrapper mt-3">
                            <div class="d-flex align-items-center">
                                <div class="text-secondary">อื่นๆ</div>
                                <div class="ms-auto pe-4"><?php echo number_format($price, 2) ?> บาท</div>
                                <div class="text-success"><?php echo number_format($percent, 2) ?>%</div>
                            </div>
                            <div class="progress mt-2" style="height:3px;">
                                <div class="progress-bar" role="progressbar" style="width: <?php echo number_format($percent) ?>%"></div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>