<?php
$header_url     = "dashboard/assets/images/bg-themes/1.png";
$header_name    = $l_inbox;
$header_detail  = "ข้อมูลการแจ้งเตือนต่างๆ จากระบบ";
include('webpage_asset/include/include_header.php'); 
?>
<div class="container">
    <?php if (!isset($_GET['action'])) { 
        $query = mysqli_query($connect, "SELECT * FROM system_contact WHERE (contact_buyer = '$buyer_id') AND ((contact_type = 1) OR (contact_type = 3)) ");
        $empty = mysqli_num_rows($query);
        if ($empty > 0) {
            while ($data = mysqli_fetch_array($query)) { 
                
                $status         = $data['contact_status'] == 0 ? "border-primary" : "border-secondary"; 
                $contact_type   = $data['contact_type'];
                $contact_detail = $data['contact_detail'];
                $contact_id     = $data['contact_id'];
                $contact_name   = $data['contact_name'];
                $contact_title  = $data['contact_title'];
                $contact_create = datethai($data['contact_create'], 0, $lang);
                
                ?>
                <div class="d-flex my-5 border-start border-4 ps-3 <?php echo $status ?>">
                    <div>
                        <?php if ($contact_type == 3) { 
                            $query_order = mysqli_query($connect, "SELECT * FROM system_order WHERE (order_id = '$contact_detail')");
                            $data_order  = mysqli_fetch_array($query_order);
                            echo "<h5>" . $data_order['order_code'] . "</h5>"; 
                        } 
                        ?>
                        <h5><?php echo $contact_title ?></h5>
                        <p class="m-0"><?php echo $contact_create ?></p><br>
                        <div class="small">By <?php echo $contact_name ?></div> 
                    </div>
                    <a href="?page=message&action=message_detail&contact_id=<?php echo $contact_id ?>" title="Detail" class="ms-auto text-decoration-underline"><?php echo $l_detail ?></a>
                </div>
                <hr>
    <?php } } else { echo "<p class='m-5'>$l_notfound</p>"; }
    
    } 
    else {     

        $contact_id = $_GET['contact_id'];

        mysqli_query($connect, "UPDATE system_contact SET contact_status = 1 WHERE contact_id = '$contact_id' ");

        $query = mysqli_query($connect, "SELECT * FROM system_contact WHERE contact_id = '$contact_id' ");
        $data  = mysqli_fetch_array($query); ?>

        <div class="row p-5">
            <a href="?page=message" class="mb-5 text-decoration-underline small"><?php echo $l_back ?></a>
            <h5><?php echo $data['contact_title'] ?></h5>
            <p><?php echo datethai($data['contact_create'], 0, $lang) ?></p>
            <hr>
            <p><?php echo $l_detail ?></p>
            <?php if ($data['contact_type'] == 3) { 
                $query_order = mysqli_query($connect, "SELECT * FROM system_order WHERE order_id = '".$data['contact_detail']."' ");
                $order       = mysqli_fetch_array($query_order); ?>

                <p class="text-dark mb-5">คำสั่งซื้อของท่านผ่านการตรวจสอบเรียบร้อย ขณะนี้สินค้าได้ถูกจัดส่งเรียบร้อยแล้ว</p>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th><?php echo $l_order_code ?></th>
                            <td><a href="?page=profile&action=order_detail&order_id=<?php echo $order['order_id'] ?>" class="text-decoration-underline text-primary" target="_blank"><?php echo $order['order_code'] ?></a></td>
                        </tr>
                        <tr>
                            <th><?php echo $l_status ?></th>
                            <td><font color=green><?php echo $l_order_status3 ?></font></td>
                        </tr>
                        <tr>
                            <th><?php echo $l_trackname ?></th>
                            <td><?php echo $order['order_track_name'] ?></td>
                        </tr>
                        <tr>
                            <th><?php echo $l_trackid ?></th>
                            <td><?php echo $order['order_track_id'] ?></td>
                        </tr>
                    </tbody>
                </table>
                <p class="text-dark mt-5">ท่านสามารถนำหมายเลขติดตามพัสดุไปตรวจสอบสถานะการจัดส่งได้ที่เว็บเพจของบริษํท <?php echo $order['order_track_name'] ?> ได้เลยค่ะ ขอบพระคุณค่ะ</p>
            </div>
        <?php } else { 
            echo $data['contact_detail'];    
    } } ?>
</div>