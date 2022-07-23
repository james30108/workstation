<?php 
session_start(); 
ob_start();
date_default_timezone_set('Asia/Bangkok');

include('connect.php');

// default variable
$date_now  = date("Y-m-d H:i:s");
$half_month= date("Y-m-16 00:00:00");
$month_now = date("Y-m");
$today     = date("Y-m-d");
$today_name= date("D");
$last_month= date('Y-m', strtotime('-1 month'));
$yesterday = date("Y-m-d", strtotime("-1 day"));
$company   = "Demo";
$logo_image= "logo.png"; 
$logo_icon = "icon.png";
$com_addr  = "";
$point_name= " PV";

#### Rules ####

    $report_type        = rule ($connect, 1);
    $report_fee1        = rule ($connect, 2);
    $report_min         = rule ($connect, 3);
    $report_max         = rule ($connect, 4);
    $report_fee2        = rule ($connect, 5);
    $downline_max       = rule ($connect, 6);
    $com_ppm            = rule ($connect, 7);
    $com_number         = rule ($connect, 8);
    $com_style          = rule ($connect, 9);
    $system_style       = rule ($connect, 10);
    $system_switch      = rule ($connect, 11);
    $system_lang        = rule ($connect, 12);
    $system_liner       = rule ($connect, 13);
    $system_webpage     = rule ($connect, 14);
    $system_buyer       = rule ($connect, 15);
    $system_mobile      = rule ($connect, 16);
    $system_class       = rule ($connect, 17);
    $system_admin_buy   = rule ($connect, 18);
    $system_ewallet     = rule ($connect, 19);
    $system_member_expire= rule ($connect, 20);
    $system_stock       = rule ($connect, 21);
    $system_tracking    = rule ($connect, 22);
    $system_liner2      = rule ($connect, 23);
    $system_address     = rule ($connect, 24);
    $system_pay         = rule ($connect, 25);
    $system_comment     = rule ($connect, 26);
    $system_com_withdraw= rule ($connect, 27);
    $report_style       = rule ($connect, 28);
    $system_product_type2= rule ($connect, 29);
    $system_ecommerce   = rule ($connect, 30);
    $system_thread      = rule ($connect, 31);
    $system_insertmember= rule ($connect, 32);

    // 1st class commission
    $query          = mysqli_query($connect, "SELECT * FROM system_commission WHERE commission_id = 1 ");
    $data           = mysqli_fetch_array($query);
    $bonus_class    = $data['commission_point'];
    $bonus_class_2  = $data['commission_point2'];

    // theme 
    $query          = mysqli_query($connect, "SELECT * FROM system_theme");
    $data           = mysqli_fetch_array($query);
    $theme_mode     = $data['theme_mode'];
    $theme_header   = $data['theme_header'];
    $theme_sidebar  = $data['theme_sidebar'];

    // Point Type In Report Process
    $point_type     = $report_style == 0 ? " (point_type = 1) " : " (point_type != 0) " ;
    
#### #### ####

#### pagination ####

    $perpage        = 50;
    if (!isset($_GET['page_id'])) { 
        $page_id    = 1;
        $start      = 0;
    } 
    else { 
        $page_id    = $_GET['page_id'];
        $start      = ($page_id - 1) * $perpage;
    }
    $limit          = " LIMIT $start, $perpage ";
    
#### ##### ####

// Special Function for project
if (file_exists('function_child.php')) { include("function_child.php"); }

// Badge
function badge ($connect, $sql, $type) {

    $query      = mysqli_query($connect, $sql);
    if ($type == 0) {
        $data   = mysqli_num_rows($query);
        $badge  = $data;
    }
    elseif ($type == 1) {
        $data   = mysqli_fetch_array($query);
        $badge  = isset($data['report_count']) ? $data['report_count'] : false;
    }
        
    if ($badge) { echo "<span class='badge rounded-pill bg-danger ms-1'>$badge</span>"; }
}

// Report Now
function report_now ($connect, $type) {

    $query          = mysqli_query($connect, "SELECT * FROM system_report WHERE report_type = '$type' ORDER BY report_round DESC");
    $report_id_now  = mysqli_fetch_array($query);
    $report_now     = isset($report_id_now) ? number_format($report_id_now['report_round'] + 1) : 1;
    //echo $report_now;
    return $report_now;
}

// Check Assinge
function chkEmpty ($data) {
    echo !empty($data) ? $data : "<font color=gray>none</font>";
}

// Config Rules
function rule ($connect, $rule) {
    $query          = mysqli_query($connect, "SELECT * FROM system_config WHERE config_id = '$rule' ");
    $data           = mysqli_fetch_array($query);
    return $data['config_value'];
}

// Pagination in visitor index
function pagination_visitor ($connect, $sql, $perpage, $page_id, $url) {
    $query          = mysqli_query($connect, $sql);
    $total_record   = mysqli_num_rows($query);
    $total_page     = ceil($total_record / $perpage);
    $front_n_back   = 3;
    $first          = $page_id - $front_n_back;
    $last           = $page_id + $front_n_back;
    
    if( $first <= 1)           { $first = 1;}
    if( $last >= $total_page ) { $last  = $total_page; }
    
    if ($total_record > $perpage) { ?>
        <div class="d-flex">
            <div class="mx-auto">
            <ul class="pagination post-pagination">
                <li><a href="<?php echo $url ?>&page_id=1">&laquo;</a></li>
                <?php for ($i = $first; $i <= $last; $i++) { ?>
                <li class="<?php if($page_id == $i){echo 'active';} ?>">
                    <a href="<?php echo $url ?>&page_id=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
                <?php } 
                if ($page_id < $total_page) { ?>
                    <li><a href="<?php echo $url ?>&page_id=<?php echo $total_page;?>">&raquo;</a></li>
                <?php } ?>
            </ul>
            </div>
        </div>
    <?php } 
}

// Pagination in dashboard
function pagination ($connect, $sql, $perpage, $page_id, $url) {
    $query          = mysqli_query($connect, $sql);
    $total_record   = mysqli_num_rows($query);
    $total_page     = ceil($total_record / $perpage);
    $front_n_back   = 3;
    $first          = $page_id - $front_n_back;
    $last           = $page_id + $front_n_back;
    
    if( $first <= 1)                { $first    = 1;}
    if( $last >= $total_page )      { $last     = $total_page; }
    
    if ($total_record > $perpage) { ?>
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-end">
            <li class="page-item">
                <a class="page-link" href="<?php echo $url ?>&page_id=1" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>

            <?php 
            for ($i = $first; $i <= $last; $i++) { ?>

                <li class="page-item <?php if($page_id == $i){echo 'active';} ?>">
                    <a class="page-link" href="<?php echo $url ?>&page_id=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
            <?php 
            } 
            
            if ($page_id < $total_page) { ?>
            <li class="page-item">
                <a class="page-link" href="<?php echo $url ?>&page_id=<?php echo $total_page;?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
            <?php } ?>
        </ul>
    </nav>
    <?php } 
}

// API
function send_api ($connect, $url, $data, $method, $token) {
    $options = array(
      'http'   => array(
      'method' => $method,
      'content'=> $data,
      'header' => "Content-Type: application/x-www-form-urlencoded\r\n" .
                  "Accept: application/json\r\n" . 
                  "Authorization: Bearer " . $token
      )
    );
    $context  = stream_context_create($options);
    $result   = file_get_contents($url, false, $context);
    $response = json_decode($result, true);
    return $response;
}

// Alert
function alert ($status, $message, $system_lang) {
    if ($status == 'success') { 
        $color  = "success";
        $text   = "white";
        $header = $system_lang == 0 ? "เสร็จสิ้น" : "success";
        $message= $system_lang == 0 ? "ข้อมูลถูกบันทึกเข้าสู่ระบบเรียบร้อย" : "The data has already been saved into system";
    }
    else { 
        $color  = "warning";
        $text   = "dark";
        $header = $system_lang == 0 ? "ไม่สามารถทำรายการได้" : "error";
    } 
    if ($status != '') {
    ?>
    <div class="alert alert-<?php echo $color ?> border-0 bg-<?php echo $color ?> alert-dismissible fade show py-2">
        <div class="d-flex align-items-center">
            <div class="font-35 text-<?php echo $text ?>"><i class='bx bx-info-circle'></i>
            </div>
            <div class="ms-3">
                <h6 class="mb-0 text-<?php echo $text ?>"><?php echo $header ?></h6>
                <div class="text-<?php echo $text ?>"><?php echo $message ?></div>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php
    }   
}

// Address
function address ($connect, $member_id, $type, $system_lang) {

    if ($system_lang == 0) {
        $query = mysqli_query($connect, "SELECT system_address.*, system_amphures.*, system_districts.*, system_provinces.* 
            FROM system_address 
            INNER JOIN system_amphures ON (system_address.address_amphure = system_amphures.AMPHUR_ID)
            INNER JOIN system_districts ON (system_address.address_district = system_districts.DISTRICT_CODE)
            INNER JOIN system_provinces ON (system_address.address_province = system_provinces.PROVINCE_ID)
            WHERE (address_member = '$member_id') AND (address_type = '$type') ");
        $data  = mysqli_fetch_array($query);
        if ($data) {
            $address_detail   = $data['address_detail'];
            $address_district = $data['DISTRICT_NAME'];
            $address_amphure  = $data['AMPHUR_NAME'];
            $address_province = $data['PROVINCE_NAME'];
            $address_zipcode  = $data['address_zipcode'];

            $address = $address_detail . " ตำบล/แขวง " . $address_district . "<br>อำเภอ/เขต " . $address_amphure . " จังหวัด " . $address_province . "<br>รหัสไปรษณีย์ " . $address_zipcode;
        } 
        else {
            $address = "<font color=gray>none</font>";
        }
    }
    else {
        $query = mysqli_query($connect, "SELECT * FROM system_address WHERE (address_member = '$member_id') AND (address_type = '$type') ");
        $data  = mysqli_fetch_array($query);
        if ($data) {
            $address_detail   = $data['address_detail'];
            $address_district = $data['address_district'];
            $address_amphure  = $data['address_amphure'];
            $address_province = $data['address_province'];
            $address_zipcode  = $data['address_zipcode'];

            $address = $address_detail . " District " . $address_district . "<br>Amphure " . $address_amphure . " Province " . $address_province . "<br>Zipcode " . $address_zipcode;
        } 
        else {
            $address = "<font color=gray>none</font>";
        }
    }
    return $address;
}

// Date
function datethai($data, $type, $lang) {

    
    $strMonth   = date("n",strtotime($data));
    $strDay     = date("j",strtotime($data));
    $strHour    = date("H",strtotime($data));
    $strMinute  = date("i",strtotime($data));
    $strSeconds = date("s",strtotime($data));
    if ($lang == 0) {
        $strYear      = date("Y",strtotime($data))+543;
        $strMonthCut  = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
        $strMonthThai =$strMonthCut[$strMonth];
    }
    else {
        $strYear      = date("Y",strtotime($data));
        $strMonthThai = date("M",strtotime($data));
    }
    
    
    if ($type == 0) {
        return "$strDay $strMonthThai $strYear";
    }
    elseif ($type == 1) {
        return "$strDay $strMonthThai";
    }
    elseif ($type == 2) {
        return "$strDay $strMonthThai $strYear, $strHour:$strMinute";
    }
    elseif ($type == 3) {
        return "$strMonthThai";
    }
}

// line token
function line ($token, $message) {
    /*
    $headers = "['Content-Type: application/x-www-form-urlencoded','Authorization: Bearer '" . $token . "]";
    //$fields = "message=" . $message;
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_URL, 'https://notify-api.line.me/api/notify');
    curl_setopt( $ch, CURLOPT_POST, 1);
    curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0); 
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0); 
    curl_setopt( $ch, CURLOPT_POST, 1); 
    curl_setopt( $ch, CURLOPT_POSTFIELDS, 'message=' . $message);
    curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec( $ch );
    curl_close( $ch );
    */
}

// Icon member in liner tree
function modal ($id, $code, $name, $tel, $email, $date, $status, $class_image, $class) {
    
    if      ($class == 1)                   { $image = "assets/images/class/$class_image"; } 
    elseif  ($class == 0 && $status == 1)   { $image = "assets/images/class/member_icon_green.jpg"; }
    elseif  ($class == 0 && $status == 0)   { $image = "assets/images/class/member_icon.jpg"; } ?>
    
    <a href="#" style="pointer-events: none;"><img src="<?php echo $image ?>" width=60 height=60 /></a>
    <br>
    <span>
        <?php echo "<font color='blue' size='2'>$code<br>" . mb_substr($name,0,10,'utf-8') . "..</font>"; ?>
        <br>
        <button type="button" class="btn btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal_<?php echo $id ?>" style="width: 80px;">detail
        </button>
    </span>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal_<?php echo $id ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-body">
            <table class="table table-borderless table-sm text-start">
                <tbody>
                    <tr>
                        <th>member code</th>
                        <td><?php echo $code ?></td>
                    </tr>
                    <tr>
                        <th>name</th>
                        <td><?php echo $name ?></td>
                    </tr>
                    <tr>
                        <th>tel.</th>
                        <td><?php echo $tel ?></td>
                    </tr>
                    <tr>
                        <th>email</th>
                        <td><?php echo $email ?></td>
                    </tr>
                    <tr>
                        <th>created</th>
                        <td><?php echo datethai($date, 0, 1) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    </div>
    </div>
    <?php
}

// Sale Cut
function sale_cut ($connect, $report_round) {

    $query          = mysqli_query($connect, "SELECT *, SUM(order_price + order_freight) AS sum_price FROM system_order WHERE (order_status >= 4) AND (order_cut_report = 0)");
    $data           = mysqli_fetch_array($query);
    $sum_price      = $data['sum_price'];

    $query          = mysqli_query($connect, "SELECT * FROM system_order WHERE (order_status >= 4) AND (order_cut_report = 0)");
    $count_order    = mysqli_num_rows($query);

    if ($count_order > 0) { 

        mysqli_query($connect, "INSERT INTO system_report (report_point, report_count, report_round, report_type) VALUES ('$sum_price', '$count_order', '$report_round', 1)")or die(mysqli_error($connect));
        $report_id      = $connect -> insert_id;

        // Insert report default's detail
        while ($data = mysqli_fetch_array($query)) {

            $order_id   = $data['order_id'];
            $order_price= $data['order_price'] + $data['order_freight'];
            mysqli_query($connect, "INSERT INTO system_report_detail (report_detail_main, report_detail_link, report_detail_point) 
                VALUES ('$report_id', '$order_id', '$order_price')");
            mysqli_query($connect, "UPDATE system_order SET order_cut_report = 1 WHERE order_id = '$order_id' ");
        }

        mysqli_query($connect, "UPDATE system_order SET order_cut_report = 1 WHERE (order_status = 1) OR (order_status = 2)");
        
    }
}

// Commission Cut
function commission_cut ($connect, $report_round, $point_type, $report_create, $report_min) {
    
    $query          = mysqli_query($connect, "SELECT SUM(sum.sum_point_member) AS sum_point, COUNT(*) AS count
    FROM
        (SELECT *, SUM(point_bonus) AS sum_point_member FROM system_point 
        WHERE $point_type AND (point_status = 0)
        GROUP BY point_member
        HAVING (sum_point_member >= '$report_min')) AS sum
        ") or die ($connect);
    $report_count   = mysqli_num_rows($query);
    $data           = mysqli_fetch_array($query);
    $report_point   = $data['sum_point'];
    $report_count   = $data['count'];

    echo $report_point . " / " . $report_count;

    mysqli_query($connect, "INSERT INTO system_report (
        report_point, 
        report_count, 
        report_round, 
        report_create) 
    VALUES (
        '$report_point', 
        '$report_count', 
        '$report_round', 
        '$report_create')")
    or die(mysqli_error($connect));
    $report_detail_main   = $connect -> insert_id;

    $query = mysqli_query($connect, "SELECT *, SUM(point_bonus) AS sum_point 
        FROM system_point 
        WHERE $point_type AND (point_status = 0)
        GROUP BY point_member
        HAVING (sum_point >= '$report_min')")  or die ($connect);
    while ($data = mysqli_fetch_array($query)) {

        echo $data['point_member'] . " / " . $data['sum_point'] . "<br>";

        $report_detail_link   = $data['point_member'];
        $report_detail_point  = $data['sum_point'];

        mysqli_query($connect, "INSERT INTO system_report_detail (
            report_detail_main, 
            report_detail_link, 
            report_detail_point) 
        VALUES (
            '$report_detail_main', 
            '$report_detail_link', 
            '$report_detail_point')");

        mysqli_query($connect, "UPDATE system_point SET point_status = 1 WHERE (point_status = 0) AND (point_type = 1) AND (point_member = '$report_detail_link')");
        
    }
}

// Commission's Report
function report_final ($point, $report_fee1, $report_fee2, $l_bath, $report_max) {

    $vat_point      = ( $point * $report_fee2 ) / 100; 
    $pay            = $point - $vat_point - $report_fee1;
    $pay_double     = number_format($pay, 2, '.', '');
    $point_format   = number_format($point, 2);
    $pay_format     = $pay > 0 ? number_format($pay, 2) . $l_bath : 0;

    if ($report_max != 0 && $pay > $report_max) {
        $pay_format = number_format($report_max, 2) . $l_bath . " <font color=red>(limit)</font>";
    }

    if ($report_fee2 != 0 || $report_fee1 != 0) {

        $pay_show = $pay > 0 ? "<div class='d-flex'><div class='d-flex align-items-center mx-auto'>$point_format<i class='bx bx-right-arrow-alt mx-1 font-22 text-secondary'></i>$pay_format</div></div>" : 0;
  
    }
    else {
        $pay_show = $pay_format;
    }

    // for PHP Underver. 7.0.
    //return $pay_show;

    return array($pay_show, $pay_format, $pay_double);
}

/*
//
// Produced by nathasophon khamdechsak
//
*/

?>