<?php 

if (isset($_GET['order_id'])) {

    $_SESSION['order_id'] = $_GET['order_id'];
    header("location:$page_type?page=shopping&action=payment");
    die;
    
} 
elseif (isset($_SESSION['order_id'])) {

    $order_id = $_SESSION['order_id'];
    unset($_SESSION['order_id']);

} 
else {

    header("location:$page_type?page=order");
    die;

}

?>

<script type="text/javascript">
    
    $(document).ready(function(){    
        
        loadpayment();

    });

    function loadpayment(){
        
        $.get("process/project/2/process/ajax_payment.php", { order_id: <?php echo $order_id ?> }, function( data ) {

            if (data != 0) {
                window.location.replace("<?php echo $page_type ?>?page=order&type=report");
            }

        });
        setTimeout(loadpayment, 2000); 
    
    }

</script>
<?php 

$query = mysqli_query($connect, "SELECT system_member.*, system_order.* 
    FROM system_order 
    INNER JOIN system_member ON (system_order.order_member = system_member.member_id)
    WHERE order_id = '$order_id' ");
$data  = mysqli_fetch_array($query); 

$order_status   = $data['order_status'];
$order_code     = $data['order_code'];
$order_create   = datethai($data['order_create'], 0, $lang);
$order_price    = number_format($data['order_price'], 2) . $l_bath;
$api_price      = (int) $data['order_price'];

// API
//Use Authorization and partner_code from your account
$authorization  = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJwYXJ0bmVyX2NvZGUiOiJBUFciLCJ1dWlkIjoiZjdiMDQ1NjUtZDcwZS00NjkxLTg0OWMtNjVhNGM5YThmMjMzIn0.lwPrGBqTSWYmQLesoIStkua5iftBA4jjiAXMgTW994w";
$partner_code   = "APW";

// API URL
$url            = "https://api.1-2-pay.com/v1/create-qr-code";      
$ref1           = $member_id . date('YmdHis');  // change ref1 (max 18 digit)
$ref2           = $order_id;  // change ref1 (max 18 digit)
$channel_code   = "WEB";

// Setup request to send json via POST
$data = array(
    'amount'    => $api_price,
    'ref1'      => $ref1,
    'ref2'      => $ref2
);

$payload = json_encode($data);

$option = array(
    'Content-Type:application/json',
    'Content-Length: '  .strlen($payload),
    'Authorization:'    .$authorization,
    'location:'         .'lat,long',
    'device:WEB',
    'partner_code:'     .$partner_code,
    'channel:'          .$channel_code
);

// Start CURL
$ch = curl_init();

// Set CURL
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $option);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

// Run CURL
$result         = curl_exec($ch);
$error          = curl_error($ch);
$httpcode       = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$header_size    = curl_getinfo($ch,CURLINFO_HEADER_SIZE);

$header         = substr($result, 0, $header_size);
$body           = substr($result, $header_size);
$last_url       = curl_getinfo($ch,CURLINFO_EFFECTIVE_URL);

// Close CURL
curl_close($ch);

?> 
<style type="text/css">

    @media only screen and (min-width: 768px) {
        .qrcode {
            height: 400px;
            width:  400px;
            
        }
    }

</style>

<title><?php echo $l_pay ?></title>
<div class="col-12 col-sm-4 mx-auto">
    <div class="card-title d-flex align-items-center mb-3">
        <div><i class="bx bx-file me-1 font-22 text-primary"></i></div>
        <h5 class="mb-0 text-primary"><?php echo $l_pay ?></h5>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page"><a href="<?php echo $page_type ?>?page=order"><?php echo $l_order ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $l_pay ?></li>
        </ol>
    </nav>
    <div class="card border-top border-0 border-4 border-primary">
        <div class="card-body">
            <div class="my-3 d-block d-sm-flex">
                <div>
                    <?php echo "<b>$l_order_code $order_code</b>"; ?>
                    <h4 class="text-success"><?php echo "$l_invoice_totprice $order_price" ?></h4>
                </div>
            </div>
            <div class="row">

                <?php 

                //echo $httpcode;
                if ($httpcode == 201 || $httpcode == 400 ) {

                    $data_post  = json_decode($body, true);
                    $qrcode     = "data:image/png;base64," . $data_post['data']['code_image'];

                    echo "<img src='$qrcode' class='qrcode mx-sm-auto border border-primary'>";

                    //echo $data_post['data']['ref2'];
                }
                else {

                    echo "<h4 class='text-danger'>Error</h4>";

                }

                ?>
            </div>
        </div>
    </div>
</div>