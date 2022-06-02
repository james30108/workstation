<title><?php echo $l_index ?></title>
<?php 

    $query = mysqli_query($connect, "SELECT system_member.*, system_liner.*, system_class.* 
        FROM system_member
        INNER JOIN system_liner ON (system_member.member_id     = system_liner.liner_member)
        INNER JOIN system_class ON (system_member.member_class  = system_class.class_id)
        WHERE member_id = '$member_id' ");
    $data  = mysqli_fetch_array($query);
    $member_point_month = $data['member_point_month'];
    $member_commission  = $data['liner_point'];
    $member_ewallet     = $data['member_ewallet'];
    $member_downline    = $data['liner_count_month'];
    $member_status      = $data['liner_status'];
    $class_name         = $data['class_name'];

    // chart 1
    $query = mysqli_query($connect, "SELECT system_report.*, system_report_detail.* FROM system_report 
        INNER JOIN system_report_detail ON (system_report.report_id = system_report_detail.report_detail_main) 
        WHERE (report_detail_link = '$member_id') AND (report_type = 0)
        LIMIT 12 ");
    $commission       = array();
    $commission_month = array();
    while ($data = mysqli_fetch_array($query)) {
        array_push($commission, $data['report_detail_point']);
        array_push($commission_month, datethai($data['report_create'], 1, $system_lang));
    }

    // chart 1
    $query = mysqli_query($connect, "SELECT *, SUM(order_price) AS sum_point 
        FROM system_order 
        WHERE (order_status = 1) 
        GROUP BY MONTH(order_create)
        ORDER BY order_create ASC
        LIMIT 12 ");
    $income       = array();
    $income_month = array();
    while ($data = mysqli_fetch_array($query)) {
        array_push($income, $data['sum_point']);
        array_push($income_month, datethai($data['order_create'], 1, $system_lang));
    }

    // chart 2
    $query = mysqli_query($connect, "SELECT *, 
        COUNT(order_id) AS order_total,
        SUM(CASE WHEN order_status = 0 THEN 1 ELSE 0 END) AS order_waited,
        SUM(CASE WHEN order_status = 1 THEN 1 ELSE 0 END) AS order_success,
        SUM(CASE WHEN order_status = 2 THEN 1 ELSE 0 END) AS order_cancel
        FROM system_order 
        WHERE (order_create LIKE '%$month_now%')") or die(mysqli_error($connect));
    $data = mysqli_fetch_array($query);
    $order_waited   =  $data['order_waited'];
    $order_success  =  $data['order_success'];
    $order_cancel   =  $data['order_cancel'];

    // chart 3
    $query = mysqli_query($connect, "SELECT *
        FROM system_report 
        WHERE (report_type = 0) 
        ORDER BY report_round ASC
        LIMIT 6 ");
    $commission       = array();
    $commission_month = array();
    while ($data = mysqli_fetch_array($query)) {
        array_push($commission, $data['report_point']);
        array_push($commission_month, datethai($data['report_create'], 1, $system_lang));
    }

    // chart 4
    $query = mysqli_query($connect, "SELECT *, COUNT(*) AS member_total
        FROM system_member 
        GROUP BY MONTH(member_create) 
        ORDER BY member_create ASC
        LIMIT 12 ");
    $member       = array();
    $member_month = array();
    while ($data = mysqli_fetch_array($query)) {
        array_push($member, $data['member_total']);
        array_push($member_month, datethai($data['member_create'], 3, $system_lang));
    }
?>
<script type="text/javascript">
    $(function () {

        // chart 1
        var options = {
            series: [{
                name: '<?php echo $l_com ?>',
                data: <?php echo json_encode($commission) ?>
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
                categories: <?php echo json_encode($commission_month) ?>
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
                        return "" + val + "<?php echo $l_bath ?>"
                    }
                }
            }
        };
        var chart = new ApexCharts(document.querySelector("#chart1"), options);
        chart.render();

        // chart 2
        var options = {
            series: [{
                name: '<?php echo $l_com ?>',
                data: <?php echo json_encode($member) ?>
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
                    //endingShape: 'rounded'
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
            colors: ["#228B22", "#29cc39", "#0dcaf0"],
            yaxis: {
                labels: {
                    formatter: function (value) {
                        return Math.floor(value);
                    }
                },
            },
            xaxis: {
                categories: <?php echo json_encode($member_month) ?>
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
                        return "" + val
                    }
                }
            }
        };
        var chart = new ApexCharts(document.querySelector("#chart2"), options);
        chart.render();
    });
</script>
<?php if (file_exists("process/project/$system_style/member_index_alert.php")) { include("process/project/$system_style/member_index_alert.php"); } ?>
<div class="row">
    <?php if ($com_ppm > 0) { ?>
        <div class="col-12 col-sm">
            <div class="card radius-10 overflow-hidden bg-gradient-cosmic">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-white"><?php echo $l_remem_ppm ?></p>
                            <h5 class="mb-0 text-white">
                                <?php
                                if ($member_status == 0) {
                                    echo number_format($member_point_month) . $l_point;
                                }   
                                else {
                                    echo "รักษายอดผ่าน";
                                }
                                ?>
                            </h5>
                        </div>
                        <div class="ms-auto text-white"><i class='bx bx-lock-open-alt font-30'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="col-12 col-sm">
        <div class="card radius-10 overflow-hidden bg-gradient-Ohhappiness">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-white"><?php echo $l_com ?></p>
                        <h5 class="mb-0 text-white">
                            <?php echo number_format($member_commission, 2) . $l_bath;?>
                        </h5>
                    </div>
                    <div class="ms-auto text-white"><i class='bx bx-dollar-circle font-30'></i></div>
                </div>
            </div>
        </div>
    </div>
    <?php if ($system_class == 1) { ?>
    <div class="col-12 col-sm">
        <div class="card radius-10 overflow-hidden bg-gradient-cosmic">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-white"><?php echo $l_class ?></p>
                        <h5 class="mb-0 text-white"><?php echo $class_name ?></h5>
                    </div>
                    <div class="ms-auto text-white"><i class='bx bx-lock-open-alt font-30'></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } if ($system_ewallet > 0) { ?>
    <div class="col-12 col-sm">
        <div class="card radius-10 overflow-hidden bg-gradient-burning">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-white"><?php echo $l_ewallet ?></p>
                        <h5 class="mb-0 text-white">
                            <?php echo number_format($member_ewallet) . $l_bath; ?>
                        </h5>
                    </div>
                    <div class="ms-auto text-white"><i class='bx bx-wallet font-30'></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php } 

    if (file_exists("process/project/$system_style/member_index1.php")) { include("process/project/$system_style/member_index1.php"); } ?>

</div><!--end row-->

<div class="card radius-10 bg-dark bg-gradient">
    <div class="card-body">
        <h6 class="text-white"><?php echo $l_link ?></h6>
        <div class="d-flex align-items-center">
            <button class="btn btn-primary btn-sm" onclick="copyToClipboard('#copy_text')">Copy</button>
            <hr class="vr text-white mx-3">
            <h5 class="font-weight-bold text-white mb-0 text-truncate" id="copy_text">
                <?php echo "goldrex.biz/dashboard/signin_member.php?member_id=$member_id"; ?>
            </h5>
        </div>
    </div>
</div>

<div class="card radius-10">
    <div class="card-header border-bottom-0 bg-transparent">
        <div class="d-lg-flex align-items-center">
            <div>
                <h6 class="font-weight-bold mb-2 mb-lg-0"><?php echo $l_dash_income ?></h6>
            </div>
            <div class="ms-lg-auto">
                <a href="member.php?page=report_commission" class="btn btn-primary radius-10 ms-lg-3 btn-sm"><?php echo $l_report ?></a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div id="chart1"></div>
    </div>
</div>

<div class="row">
    <div class="col-12 d-flex">
        <div class="card radius-10 w-100">
            <div class="card-header border-bottom-0 bg-transparent">
                <div class="d-lg-flex align-items-center">
                    <div>
                        <h6 class="font-weight-bold mb-2 mb-lg-0">Mining of cryptocurrency</h6>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="chart2"></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function copyToClipboard(element) {
      var $temp = $("<input>");
      $("body").append($temp);
      $temp.val($(element).text()).select();
      document.execCommand("copy");
      $temp.remove();
      alert("Copy to Clipboard");
    }
</script>