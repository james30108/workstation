<?php
$header_url     = "dashboard/assets/images/bg-themes/1.png";
$header_name    = "รายการแจ้งเตือน";
$header_detail  = "ข้อมูลการแจ้งเตือนต่างๆ จากระบบ";
include('webpage_asset/include/include_header.php'); 
?>
<div class="container">
    <?php if (!isset($_GET['action'])) { 
        $query = mysqli_query($connect, "SELECT * FROM system_contact WHERE (contact_buyer = '$buyer_id') AND ((contact_type = 1) OR (contact_type = 3)) ");
        $empty = mysqli_num_rows($query);
        if ($empty > 0) {
            while ($data = mysqli_fetch_array($query)) { 
                $status  = $data['contact_status'] == 0 ? "border-primary" : "border-secondary"; ?>
                <div class="d-flex my-5 border-start border-4 ps-3 <?php echo $status ?>">
                    <div>
                        <?php if ($data['contact_type'] == 3) { 
                            $query_order = mysqli_query($connect, "SELECT * FROM system_order WHERE (order_id = '".$data['contact_detail']."')");
                            $data_order  = mysqli_fetch_array($query_order);
                            echo "<h5>" . $data_order['order_code'] . "</h5>"; 
                        } 
                        ?>
                        <h5><?php echo $data['contact_title'] ?></h5>
                        <p class="m-0"><?php echo datethai($data['contact_create'], 0, $system_lang) ?></p><br>
                        <div class="small">By <?php echo $data['contact_name'] ?></div> 
                    </div>
                    <a href="?page=message&action=message_detail&contact_id=<?php echo $data['contact_id'] ?>" title="รายละเอียด" class="ms-auto text-decoration-underline">รายละเอียด</a>
                </div>
                <hr>
            <?php } 
        } else { 
            echo "<p class='m-5'>ไม่มีรายการแจ้งเตือน</p>";
        } 
    } else {     
        mysqli_query($connect, "UPDATE system_contact SET contact_status = 1 WHERE contact_id = '".$_GET['contact_id']."' ");

        $query = mysqli_query($connect, "SELECT * FROM system_contact WHERE contact_id = '".$_GET['contact_id']."' ");
        $data  = mysqli_fetch_array($query); ?>

        <div class="row p-5">
            <a href="?page=message" class="mb-5 text-decoration-underline small">ย้อนกลับ</a>
            <h5><?php echo $data['contact_title'] ?></h5>
            <p><?php echo datethai($data['contact_create'], 0, $system_lang) ?></p>
            <hr>
            <p>รายละเอียด</p>
            <?php if ($data['contact_type'] == 3) { 
                $query_order = mysqli_query($connect, "SELECT * FROM system_order WHERE order_id = '".$data['contact_detail']."' ");
                $order       = mysqli_fetch_array($query_order); ?>

                <p class="text-dark mb-5">คำสั่งซื้อของท่านผ่านการตรวจสอบเรียบร้อย ขณะนี้สินค้าได้ถูกจัดส่งเรียบร้อยแล้ว</p>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th>หมายเลขคำสั่งซื้อ</th>
                            <td><a href='?page=profile&action=order_detail&order_id=' class='text-decoration-underline text-primary' target='_blank'><?php echo $order['order_code'] ?></a></td>
                        </tr>
                        <tr>
                            <th>สถานะ</th>
                            <td><font color=green>ถูกจัดส่งแล้ว</font></td>
                        </tr>
                        <tr>
                            <th>จัดส่งโดย</th>
                            <td><?php echo $order['order_track_name'] ?></td>
                        </tr>
                        <tr>
                            <th>หมายเลขติดตามพัสดุ</th>
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