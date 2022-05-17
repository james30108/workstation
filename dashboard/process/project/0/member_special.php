<title>รายงานรายได้</title>
<style type="text/css">
    @media only screen and (min-width: 768px) {
      /* For desktop: */
      .card_height { height : 300px; }
    }
</style>
<div class="row">
    <div class="col-12 col-sm-6">
        <div class="card border-top border-0 border-4 border-primary card_height">
            <div class="card-body p-1 pt-3 pb-3 p-sm-5">
                <div class="card-title d-flex align-items-center">
                    <div><i class="bx bx-coin-stack me-1 font-22 text-primary"></i></div>
                    <h5 class="mb-0 text-primary">รายงานการเงิน</h5>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table table-borderless table-sm">
                        <tbody>
                            <tr>
                                <th>ยอดเงินซื้อสินค้าทั้งหมด</th>
                                <td>
                                <?php
                                $query  = mysqli_query($connect, "SELECT *, SUM(point_bonus) AS all_buy FROM system_point WHERE (point_member = '$member_id') AND (point_type = 0) ");
                                $data   = mysqli_fetch_array($query);
                                echo number_format($data['all_buy'], 2) . " บาท"; 
                                ?>
                                </td>
                            </tr>
                            <tr>
                                <th>ยอดเงินซื้อสินค้าในเดือนนี้</th>
                                <td>
                                <?php
                                $query  = mysqli_query($connect, "SELECT *, SUM(point_bonus) AS all_buy FROM system_point WHERE (point_member = '$member_id') AND (point_type = 0) AND (point_create LIKE '%$month_now%')");
                                $data   = mysqli_fetch_array($query);
                                echo number_format($data['all_buy'], 2) . " บาท"; 
                                ?>
                                </td>
                            </tr>
                            <?php if ($system_ewallet == 1) { ?>
                            <tr>
                                <th>ยอดเงินในกระเป๋าเงินคงเหลือ</th>
                                <td><?php echo number_format($data_check_login['member_ewallet'], 2) . " บาท";  ?></td>
                            </tr>
                            <tr>
                                <th>ซื้อสินค้าผ่าน 'กระเป๋าเงิน' ทั้งหมด</th>
                                <td>
                                <?php
                                $query  = mysqli_query($connect, "SELECT *, SUM(point_bonus) AS all_buy FROM system_point WHERE (point_member = '$member_id') AND (point_type = 0) AND (point_ewallet = 1)");
                                $data   = mysqli_fetch_array($query);
                                echo number_format($data['all_buy'], 2) . " บาท"; 
                                ?>
                                </td>
                            </tr>
                            <tr>
                                <th>ยอดเติมเงินเข้า 'กระเป๋าเงิน' ทั้งหมด</th>
                                <td>
                                <?php
                                $query  = mysqli_query($connect, "SELECT *, SUM(ewallet_deposit_money) AS ewallet_sum FROM system_ewallet_deposit WHERE (ewallet_deposit_member = '$member_id') AND (ewallet_deposit_status = 1)");
                                $data   = mysqli_fetch_array($query);
                                echo number_format($data['ewallet_sum'], 2) . " บาท"; ; 
                                ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6">
        <div class="card border-top border-0 border-4 border-primary card_height">
            <div class="card-body p-1 pt-3 pb-3 p-sm-5">
                <div class="card-title d-flex align-items-center">
                    <div><i class="bx bx-notepad me-1 font-22 text-primary"></i></div>
                    <h5 class="mb-0 text-primary">รายงานเงินปันผล</h5>
                </div>
                <hr>
                <div class="table-responsive">
                    <table class="table table-borderless table-sm">
                        <tbody>
                            <tr>
                                <th>คะแนนสะสม</th>
                                <td>
                                <?php
                                $query = mysqli_query($connect, "SELECT * FROM system_liner WHERE liner_member = '$member_id' ");
                                $data  = mysqli_fetch_array($query);
                                $commission = $data['liner_point'];
                                echo number_format($commission, 2);
                                ?>
                                </td>
                            </tr>
                            <tr>
                                <th>กำไรคืนกลับที่เคยได้รับทั้งหมด</th>
                                <td>
                                <?php
                                $query  = mysqli_query($connect, "SELECT system_report.*, system_report_detail.*, SUM(report_detail_point) AS commistion_all FROM system_report 
                                    INNER JOIN system_report_detail ON (system_report.report_id = system_report_detail.report_detail_main) 
                                    WHERE (report_detail_link = '$member_id') AND (report_type = 0)");
                                $data   = mysqli_fetch_array($query);
                                echo number_format($data['commistion_all'], 2) . " บาท"; 
                                ?>
                                </td>
                            </tr>
                            <tr>
                                <th>กำไรคืนกลับเดือนที่แล้ว</th>
                                <td>
                                <?php
                                $query  = mysqli_query($connect, "SELECT system_report.*, system_report_detail.*, SUM(report_detail_point) AS commistion_all FROM system_report 
                                    INNER JOIN system_report_detail ON (system_report.report_id = system_report_detail.report_detail_main) 
                                    WHERE (report_detail_link = '$member_id') AND (report_type = 0) AND (report_create LIKE '%$last_month%')");
                                $data   = mysqli_fetch_array($query);
                                if ($data['commistion_all'] == 0) {
                                    echo "ไม่ได้รับเงินปันผล";
                                }
                                else {
                                    echo number_format($data['commistion_all'], 2) . " บาท"; 
                                }
                                ?>
                                </td>
                            </tr>
                            <tr>
                                <th>การรักษายอดเดือนนี้</th>
                                <td>
                                <?php
                                $query  = mysqli_query($connect, "SELECT sum(point_bonus) AS sum_point 
                                    FROM system_point 
                                    WHERE point_member = '$member_id' 
                                    AND point_type  = 0 
                                    AND point_create LIKE '%$month_now%'");
                                $data   = mysqli_fetch_array($query);

                                if ( $data['sum_point'] >= $com_ppm ) { ?>
                                    <font color=green>ได้รับเงินปันผลในเดือนนี้</font>
                                <?php } else { ?>
                                    <font color=red>ไม่ได้รับเงินปันผลในเดือนนี้</font>
                                <?php } ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>