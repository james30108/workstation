<title><?php echo $l_index ?></title>
<?php 
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
        array_push($income_month, datethai($data['order_create'], 1, $lang));
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
        array_push($commission_month, datethai($data['report_create'], 1, $lang));
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
        array_push($member_month, datethai($data['member_create'], 3, $lang));
    }
?>

<script type="text/javascript">
    $(function () {
        // chart 1
        var options = {
            series: [{
                name: '<?php echo $l_resold_money ?>',
                data: <?php echo json_encode($income) ?>
            }],
            chart: {
                type: 'area',
                foreColor: '#9a9797',
                height: 250,
                toolbar: {
                    show: false
                },
                zoom: {
                    enabled: false
                },
                dropShadow: {
                    enabled: false,
                    top: 3,
                    left: 14,
                    blur: 4,
                    opacity: 0.12,
                    color: '#8833ff',
                },
                sparkline: {
                    enabled: false
                }
            },
            markers: {
                size: 0,
                colors: ["#8833ff"],
                strokeColors: "#fff",
                strokeWidth: 2,
                hover: {
                    size: 7,
                }
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '45%',
                    endingShape: 'rounded'
                },
            },
            
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 3,
                curve: 'smooth'
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shade: 'light',
                    type: 'vertical',
                    shadeIntensity: 0.5,
                    gradientToColors: ['#fff'],
                    inverseColors: false,
                    opacityFrom: 0.8,
                    opacityTo: 0.5,
                    stops: [0, 100]
                }
            },
            colors: ["#8833ff"],
            grid: {
                show: true,
                borderColor: '#ededed',
                //strokeDashArray: 4,
            },
            yaxis: {
                labels: {
                    formatter: function (value) {
                        return value;
                    }
                },
            },
            xaxis: {
                categories: <?php echo json_encode($income_month) ?>,
            },
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
        Highcharts.chart('chart2', {
            chart: {
                type: 'variablepie',
                height: 400,
                styledMode: true
            },
            credits: {
                enabled: false
            },
            title: {
                text: '<?php echo $l_dash_status ?>'
            },
            tooltip: {
                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            accessibility: {
                point: {
                    valueSuffix: '%'
                }
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                    }
                }
            },
            series: [{
                minPointSize: 10,
                innerSize: '65%',
                zMin: 0,
                name: 'รายการ',
                data: [
                <?php if ($order_success > 0) { ?>
                {
                    name: 'เสร็จสิ้น',
                    y: <?php echo $order_success ?>,
                    z: 100,
                },
                <?php } if ($order_cancel > 0) { ?>
                {
                    name: 'ยกเลิก',
                    y: <?php echo $order_cancel ?>,
                    z: 100
                }, 
                <?php } if ($order_waited > 0) { ?>
                {
                    name: 'รอดำเนินการ',
                    y: <?php echo $order_waited ?>,
                    z: 100
                }
                <?php } ?>
                ]
            }]
        });

        // chart 3
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
                        return "" + val + " บาท"
                    }
                }
            }
        };
        var chart = new ApexCharts(document.querySelector("#chart3"), options);
        chart.render();

        // chart 4
        var options = {
            series: [{
                name: '<?php echo $l_member ?>',
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
        var chart = new ApexCharts(document.querySelector("#chart4"), options);
        chart.render();
    });
</script>
<div class="row row-cols-1 row-cols-lg-4">
    <div class="col">
        <div class="card radius-10 overflow-hidden bg-gradient-cosmic">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-white"><?php echo $l_dash_order ?></p>
                        <h5 class="mb-0 text-white">
                            <?php
                            $query = mysqli_query($connect, "SELECT * FROM system_order WHERE (order_status = 1) AND (order_create LIKE '%$month_now%') ");
                            $data  = mysqli_num_rows($query);
                            echo number_format($data) . $l_list;
                            ?>
                        </h5>
                    </div>
                    <div class="ms-auto text-white"><i class='bx bx-cart font-30'></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card radius-10 overflow-hidden bg-gradient-burning">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-white"><?php echo $l_dash_sold ?></p>
                        <h5 class="mb-0 text-white">
                            <?php
                            $query = mysqli_query($connect, "SELECT *, SUM(order_price) AS sum_point FROM system_order WHERE (order_status = 1) AND (order_create LIKE '%$month_now%') ");
                            $data  = mysqli_fetch_array($query);
                            echo number_format($data['sum_point'], 2) . $l_bath;
                            ?>
                        </h5>
                    </div>
                    <div class="ms-auto text-white"><i class='bx bx-wallet font-30'></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card radius-10 overflow-hidden bg-gradient-Ohhappiness">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-white"><?php echo $l_dash_com ?></p>
                        <h5 class="mb-0 text-white">
                            <?php
                            if ($system_style == 0) {
                                $query = mysqli_query($connect, "SELECT *, SUM(liner_point) AS sum_point FROM system_liner WHERE (liner_status != 0)");
                            }
                            else {
                                $query = mysqli_query($connect, "SELECT *, SUM(member_point_month) AS sum_point FROM system_member");
                            }
                            $data  = mysqli_fetch_array($query);
                            echo number_format($data['sum_point'], 2) . $l_bath;
                            ?>
                        </h5>
                    </div>
                    <div class="ms-auto text-white"><i class='bx bx-chat font-30'></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card radius-10 overflow-hidden bg-gradient-moonlit">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-white"><?php echo $l_dash_member ?></p>
                        <h5 class="mb-0 text-white">
                            <?php 
                            $query = mysqli_query($connect, "SELECT * FROM system_member WHERE (member_create LIKE '%$month_now%') ");
                            $data  = mysqli_num_rows($query);
                            echo number_format($data); 
                            ?> 
                        </h5>
                    </div>
                    <div class="ms-auto text-white"><i class='bx bx-bulb font-30'></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!--end row-->

<?php
    $query = mysqli_query($connect, "SELECT system_member.*, system_liner.*, system_class.* 
            FROM system_member 
            INNER JOIN system_liner ON (system_member.member_id     = system_liner.liner_member) 
            INNER JOIN system_class ON (system_member.member_class  = system_class.class_id)
            WHERE (liner_status >= 1) AND (liner_etc <= 39) AND (liner_type = 0)");
    while ($data = mysqli_fetch_array($query)) {

        $liner_point = ($data['class_up_level'] * $data['class_com']) / 100;
        $liner_day   = date('D', strtotime($data['liner_create']));
        $liner_create= date('Y-m-d', strtotime($data['liner_create']));
        $liner_id    = $data['liner_id'];
        $member_id   = $data['member_id'];
        $liner_etc   = $data['liner_etc'] + 1;
        $point_detail= "Get commission $liner_point bath in round $liner_etc";

        if ($liner_create != $today && $today_name == $liner_day && $liner_point > 0) {
            
            echo "Pass $liner_etc";

        }   
        /*

        if ($liner_create != $today && $today_name == $liner_day && $liner_point > 0 && $liner_etc <= 40) {

            echo "today " . $today_name . " = " . $liner_day . " ID = " . $liner_id . "<br>";

        }
        elseif ($liner_etc == 41) {

            echo "round = 41";

        }
        */
    }
?>

<div class="card radius-10">
    <div class="card-header border-bottom-0 bg-transparent">
        <div class="d-lg-flex align-items-center">
            <div>
                <h6 class="font-weight-bold mb-2 mb-lg-0"><?php echo $l_resold_money ?></h6>
            </div>
            <div class="ms-lg-auto">
                <?php if ($admin_status == 0 || $admin_status == 1) {
                    echo "<a href='admin.php?page=admin_report_sold' class='btn btn-primary radius-10 ms-lg-3 btn-sm'>$l_report</a>";
                } ?>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div id="chart1"></div>
    </div>
</div>

<div class="card radius-10">
    <div class="card-header border-bottom-0 bg-transparent">
        <div class="d-lg-flex align-items-center">
            <div>
                <h6 class="font-weight-bold mb-2 mb-lg-0"><?php echo $l_com ?></h6>
            </div>
            <div class="ms-lg-auto">
                <?php if ($admin_status == 0 || $admin_status == 1) { 
                    echo "<a href='admin.php?page=admin_report_commission' class='btn btn-primary radius-10 ms-lg-3 btn-sm'>$l_report</a>";
                } ?>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div id="chart3"></div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-lg-7 d-flex">
        <div class="card radius-10 w-100">
            <div class="card-body">
                <div class="" id="chart2"></div>
                <div class="d-flex align-items-center justify-content-between text-center mt-3">
                    <div>
                        <h5 class="mb-1 font-weight-bold"><?php echo $order_waited ?></h5>
                        <p class="mb-0 text-secondary"><?php echo $l_order_status0 ?></p>
                    </div>
                    <div class="mb-1">
                        <h5 class="mb-1 font-weight-bold"><?php echo $order_success ?></h5>
                        <p class="mb-0 text-secondary"><?php echo $l_order_status4 ?></p>
                    </div>
                    <div>
                        <h5 class="mb-1 font-weight-bold"><?php echo $order_cancel ?></h5>
                        <p class="mb-0 text-secondary"><?php echo $l_order_status1 ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-5">
        <div class="card radius-10 w-100">
            <div class="card-body">
                <h6 class="font-weight-bold mb-0"><?php echo $l_dash_bestsell ?></h6>
            </div>
            <div class="best-selling-products scroll p-3 mb-3">
                <?php 
                $query = mysqli_query($connect, "SELECT system_order.*, system_order_detail.*, system_product.*, 
                    SUM(order_detail_amount) AS count_order, order_detail_price * SUM(order_detail_amount) AS price
                    FROM system_order_detail
                    INNER JOIN system_order ON (system_order.order_id = system_order_detail.order_detail_order)
                    INNER JOIN system_product ON (system_order_detail.order_detail_product = system_product.product_id)
                    WHERE (order_status = 1) AND (order_create LIKE '%$month_now%')
                    GROUP BY order_detail_product
                    ORDER BY price DESC
                    LIMIT 10") or die(mysqli_error($connect));
                $i = 0;
                while ($data    = mysqli_fetch_array($query)) { ?>
                    <div class="d-flex align-items-center">
                        <div class="product-img">
                            <?php if ($data['product_image_cover'] != '') { ?>
                                <img src="<?php echo $data['product_image_cover'] ?>" alt="ปกสินค้า" class="img-thumbnail">
                            <?php } else { ?>
                                <img src="assets/images/products/example.png" alt="ปกสินค้า" class="img-thumbnail">
                            <?php } ?>
                        </div>
                        <div class="ps-3">
                            <h6 class="mb-0 font-weight-bold"><?php echo mb_strimwidth($data['product_name'], 0, 20, "...") ?></h6>
                            <p class="mb-0 text-secondary"><?php echo number_format($data['product_price']) ?> บาท/ขายได้ <?php echo $data['count_order'] ?> ชิ้น</p>
                        </div>
                        <p class="ms-auto mb-0 text-purple"><?php echo number_format($data['price']) . $l_bath ?></p>
                    </div>
                <?php if ($i != 9) { echo "<hr>"; }} ?>
            </div>
        </div>
    </div>
</div>

<div class="card radius-10">
    <div class="card-header border-bottom-0 bg-transparent">
        <div class="d-lg-flex align-items-center">
            <div>
                <h6 class="font-weight-bold mb-2 mb-lg-0"><?php echo $l_member ?></h6>
            </div>
            <div class="ms-lg-auto">
                <?php if ($admin_status == 0 || $admin_status == 1) { 
                    echo "<a href='admin.php?page=admin_report_member' class='btn btn-primary radius-10 ms-lg-3 btn-sm'>$l_report</a>";
                } ?>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div id="chart4"></div>
    </div>
</div>

<!--start switcher-->
<div class="switcher-wrapper">
    <div class="switcher-btn"> <i class='bx bx-cog bx-spin'></i>
    </div>
    <div class="switcher-body">
        <form action="process/setting_config.php" method="get">
        <div class="d-flex align-items-center">
            <h5 class="mb-0 text-uppercase">Theme Customizer</h5>
            <button type="button" class="btn-close ms-auto close-switcher" aria-label="Close"></button>
        </div>
        <hr/>
        <h6 class="mb-0">Theme Styles</h6>
        <hr/>
        <div class="header-colors-indigators">
            <div class="row row-cols-auto g-3">
                <select class="form-select" name="theme_type" id="theme_type">
                    <option value="">Choose Theme type</option>
                    <option value="theme_mode">theme mode</option>
                    <option value="theme_custom">custom</option>
                </select>
            </div>
        </div>
        <div id="theme_mode">
            <hr>
            <div class="d-flex align-items-center justify-content-between">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="theme_mode" id="mode" value="light-theme" <?php echo $theme_mode == "light-theme" ? "checked" : false; ?>>
                    <label class="form-check-label" for="lightmode">Light</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="theme_mode" id="mode" value="dark-theme" <?php echo $theme_mode == "dark-theme" ? "checked" : false; ?>>
                    <label class="form-check-label" for="darkmode">Dark</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="theme_mode" id="mode" value="semi-dark" <?php echo $theme_mode == "semi-theme" ? "checked" : false; ?>>
                    <label class="form-check-label" for="semidark">Semi Dark</label>
                </div>
            </div>
            <hr/>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="theme_mode" value="minimal-theme" <?php echo $theme_mode == "minimal-theme" ? "checked" : false; ?>>
                <label class="form-check-label" for="minimaltheme">Minimal Theme</label>
            </div>
        </div>
        <div id="theme_custom">
            <h6 class="mb-0 mt-3">Header Colors</h6>
            <hr/>
            <div class="header-colors-indigators">
                <div class="row row-cols-auto g-3">
                    <select class="form-select" name="theme_header" id="theme_header">
                        <option value="color-header headercolor1" <?php if ($theme_header == "color-header headercolor1") {echo "selected";} ?>>Blue</option>
                        <option value="color-header headercolor2" <?php if ($theme_header == "color-header headercolor2") {echo "selected";} ?>>Black</option>
                        <option value="color-header headercolor3" <?php if ($theme_header == "color-header headercolor3") {echo "selected";} ?>>Red</option>
                        <option value="color-header headercolor4" <?php if ($theme_header == "color-header headercolor4") {echo "selected";} ?>>Green</option>
                        <option value="color-header headercolor5" <?php if ($theme_header == "color-header headercolor5") {echo "selected";} ?>>Purple</option>
                        <option value="color-header headercolor6" <?php if ($theme_header == "color-header headercolor6") {echo "selected";} ?>>Brown</option>
                        <option value="color-header headercolor7" <?php if ($theme_header == "color-header headercolor7") {echo "selected";} ?>>Pink</option>
                        <option value="color-header headercolor8" <?php if ($theme_header == "color-header headercolor8") {echo "selected";} ?>>Orange</option>
                    </select>
                </div>
            </div>
            <hr/>
            <h6 class="mb-0">Sidebar Backgrounds</h6>
            <hr/>
            <div class="header-colors-indigators">
                <div class="row row-cols-auto g-3">
                    <select class="form-select" name="theme_sidebar" id="theme_sidebar">
                        <option value="color-sidebar sidebarcolor1" <?php if ($theme_sidebar == "color-sidebar sidebarcolor1") {echo "selected";} ?>>Blue-sky</option>
                        <option value="color-sidebar sidebarcolor2" <?php if ($theme_sidebar == "color-sidebar sidebarcolor2") {echo "selected";} ?>>Green</option>
                        <option value="color-sidebar sidebarcolor3" <?php if ($theme_sidebar == "color-sidebar sidebarcolor3") {echo "selected";} ?>>Black-Silver</option>
                        <option value="color-sidebar sidebarcolor4" <?php if ($theme_sidebar == "color-sidebar sidebarcolor4") {echo "selected";} ?>>Purple1</option>
                        <option value="color-sidebar sidebarcolor5" <?php if ($theme_sidebar == "color-sidebar sidebarcolor5") {echo "selected";} ?>>Purple2</option>
                        <option value="color-sidebar sidebarcolor6" <?php if ($theme_sidebar == "color-sidebar sidebarcolor6") {echo "selected";} ?>>Brown</option>
                        <option value="color-sidebar sidebarcolor7" <?php if ($theme_sidebar == "color-sidebar sidebarcolor7") {echo "selected";} ?>>Orange</option>
                        <option value="color-sidebar sidebarcolor8" <?php if ($theme_sidebar == "color-sidebar sidebarcolor8") {echo "selected";} ?>>Black-Blue</option>
                    </select>
                </div>
            </div>
        </div>
        <hr>
        <div class="col-12 mt-5 d-flex">
            <button name="action" value="setting_theme" class="btn btn-primary btn-sm ms-auto">Save</button>
        </div>
        </form>
    </div>
</div>
<!--end switcher-->

<script type="text/javascript">
    $(document).ready(function(){

        $("#theme_mode").hide();
        $("#theme_custom").hide();

        $("#theme_type").change(function(){
            var theme_type = $(this).val();
            if (theme_type != "" && theme_type == "theme_mode") {
                $("#theme_mode").show();
                $("#theme_custom").hide();
                $("#theme_header").val("");
                $("#theme_sidebar").val("");
            }
            else if (theme_type != "" && theme_type == "theme_custom") {
                $("#theme_mode").hide();
                $("#theme_custom").show();
                $("input:radio").attr("checked", false);
            }
        });

    });
</script>