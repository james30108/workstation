<title><?php echo $l_member_insert ?></title>
<div class="col-12 col-sm-8 mx-auto">
<div class="page-breadcrumb d-flex align-items-center mb-3">
    <div class="pe-3 text-success">
        <div class="card-title d-flex align-items-center">
            <div><i class="bx bx-user-plus me-1 font-22"></i></div>
            <h5 class="mb-0 text-success"><?php echo $l_member_insert ?></h5>
        </div>
    </div>
</div>
<?php 
if ($page_type == "admin.php") {

    $member_id = 1;

}
elseif ($page_type == "signin_member.php" && isset($_GET['member_id'])) {

    $_SESSION['insert_liner_id'] = $_GET['member_id'];
    header("location:$page_type");
    die();
    
}
elseif ($page_type == "signin_member.php" && isset($_SESSION['insert_liner_id'])) {

    $member_id = $_SESSION['insert_liner_id'];

}
elseif ($page_type == "signin_member.php" && !isset($_SESSION['insert_liner_id']) && !isset($_GET['member_id'])) {

    header("location:member.php");
    die();

}

$query       = mysqli_query($connect, "SELECT * FROM system_member WHERE member_id = '$member_id' ");
$data        = mysqli_fetch_array($query);
$member_name = $data['member_name'];
$member_code = $data['member_code'];

isset($_GET["status"]) ? alert ($_GET["status"], $_GET["message"], $lang) : false;

if (isset($_GET['status']) && $_GET['status'] == 'success') {
    $query_downline_id = mysqli_query($connect, "SELECT * FROM system_member WHERE member_id = '".$_GET['downline_id']."' ");
    $downline_id       = mysqli_fetch_array($query_downline_id);
    ?>
    <div class="card border-top border-0 border-4 border-success">
        <div class="card-body p-1 p-sm-3">
            <div class="card-title d-flex align-items-center">
                <div><i class="bx bx-check-double me-1 font-22 text-success"></i></div>
                <h5 class="mb-0 text-success">Username and Password</h5>
            </div>
            <hr>
            <table class="table table-borderless table-sm">
                <tbody>
                    <tr>
                        <th width="100">Username :</th>
                        <td><?php echo $downline_id['member_user'] ?></td>
                    </tr>
                    <tr>
                        <th>Password :</th>
                        <td><?php echo $downline_id['member_pass'] ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
<?php } ?>
<div class="card border-top border-0 border-4 border-success">
    <div class="card-body p-1 p-sm-5">
        <form action="process/setting_member.php" method="post">
            <input type="hidden" name="page" value="<?php echo $page_type ?>">
            <input type="hidden" name="lang" value="<?php echo $lang ?>" id="lang">
            
            <!-- Upliner -->
            <?php if ($system_liner == 1) { ?>
                <div class="col-12 border border-success rounded p-4 mb-3">
                    <div class="row">
                    <h6><?php echo $l_liner ?></h6>
                    <div class="col-12 col-sm-6 mt-3">
                        <label class="form-label"><?php echo $l_linercode ?> <font color="red">*</font></label>
                        <input type="text" class="form-control" id="check_upline" name="direct_code" value="<?php echo $member_code ?>"
                        <?php if($page_type== "member.php"||$page_type=="signin_member.php"){echo "readonly";}?> required>
                    </div>
                    <div class="col-12 col-sm-6 mt-3" >

                        <?php
                        $query = mysqli_query($connect, "SELECT * FROM system_liner WHERE liner_member = '$member_id'");
                        $data  = mysqli_fetch_array($query);
                        $liner_id = $data['liner_id'];
                        ?>

                        <input type="hidden" name="liner_direct" id="direct_id" value="<?php echo $liner_id ?>">
                        <label class="form-label"><?php echo $l_linername ?> <font color="red">*</font></label>
                        <input type="text" class="form-control" id="direct_name" value="<?php echo $member_name ?>" placeholder="กรุณากรอกรหัสผู้แนะนำให้ถูกต้อง" required onkeypress="return false;">
                    </div>
                    </div>
                </div>
            <?php } ?>

            <!-- Profile -->
            <div class="col-12 border border-success rounded p-4 mb-3">
                <div class="row g-3">
                <h6><?php echo $l_profile ?></h6>
                <div class="col-12 col-sm-3">
                    <label  class="form-label"><?php echo $l_titlename ?></label>
                    <select class="form-select" name="member_title_name">
                        <option value="" selected><?php echo $l_titlename ?></option>
                        <option value="<?php echo $l_mister ?>"><?php echo $l_mister ?></option>
                        <option value="<?php echo $l_mrs ?>"><?php echo $l_mrs ?></option>
                        <option value="<?php echo $l_miss ?>"><?php echo $l_miss ?></option>
                    </select>
                </div>
                <div class="col-12 col-sm-9">
                    <label  class="form-label"><?php echo $l_member_name ?> <font color="red">*</font></label>
                    <input type="text" class="form-control" id="check_name" name="member_name" placeholder="<?php echo $l_member_name ?>" required>
                </div>
                <div class="col-12 col-sm-6">
                    <label class="form-label"><?php echo $l_idcard ?> <font color="red">*</font></label>
                    <input type="text" class="form-control" name="member_code_id" id="id_card" maxlength="13" placeholder="<?php echo $l_idcard ?>" required>
                </div>
                <div class="col-12 col-sm-6">
                    <label class="form-label"><?php echo $l_tel ?></label>
                    <input type="text" class="form-control" maxlength="10" name="member_tel" placeholder="<?php echo $l_tel ?>">
                </div>
                <div class="col-12">
                    <label class="form-label"><?php echo $l_email ?></label>
                    <input type="email" class="form-control" id="check_email" name="member_email" placeholder="Example@example.com">
                </div>
                <!--
                <div class="col-12 col-sm-6">
                    <label class="form-label"><?php echo $l_line ?></label>
                    <input type="text" class="form-control" name="member_token_line" placeholder="<?php echo $l_line ?>">
                </div>
                -->
                </div>
            </div>

            <!-- Address -->
            <?php if ($system_address != 0) { ?>
            <div class="col-12 border border-success rounded p-4 mb-3">
                <?php if ($lang == 0) { ?>
                    <input type="hidden" name="address_amphure" id="amphures_data">
                    <input type="hidden" name="address_district" id="districts_data">
                    <input type="hidden" name="address_amphure_2" id="amphures_data_2">
                    <input type="hidden" name="address_district_2" id="districts_data_2">
                    <div class="row g-3">
                        <h6><?php echo $l_addridcard ?></h6>
                        <div class="col-12 col-sm-6">
                            <label class="form-label"><?php echo $l_address ?></label>
                            <input type="text" class="form-control" name="address_detail" placeholder="<?php echo $l_address ?>">
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label"><?php echo $l_provinces ?></label>
                            <select class="form-select" name="address_province" id="provinces">
                                <option value="" selected><?php echo $l_provinces ?></option>
                                <?php 
                                    $sql_dist       = mysqli_query($connect, "SELECT * FROM system_provinces");
                                    while ($data    = mysqli_fetch_array($sql_dist)) {
                                        ?>
                                        <option value="<?php echo $data['PROVINCE_ID'];?>"><?php echo $data['PROVINCE_NAME'];?></option>
                                        <?php
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-12 col-sm-4" id="amphures">
                            <label class="form-label"><?php echo $l_amphures ?></label>
                            <select class="form-select" disabled>
                                <option value="" selected><?php echo $l_amphures ?></option>
                            </select>
                        </div>
                        <div class="col-12 col-sm-4" id="districts">
                            <label class="form-label"><?php echo $l_district ?></label>
                            <select class="form-select" disabled>
                                <option value="" selected><?php echo $l_district ?></option>
                            </select>
                        </div>
                        <div class="col-12 col-sm-4" id="postcode">
                            <label class="form-label"><?php echo $l_zipcode ?></label>
                            <input type="text" class="form-control" placeholder="<?php echo $l_zipcode ?>" disabled>
                        </div>
                    </div>
                    <?php if ($system_address == 2) { ?>
                    <!------- ที่อยู่จัดส่งสินค้า -------->
                    <div class="row g-3">
                        <h6 class="mt-5">ที่อยู่สำหรับจัดส่งสินค้า</h6>
                        <div class="col-12 form-check form-switch ms-2">
                            <input class="form-check-input" name="address_type" type="checkbox" id="switch">
                            <label class="form-check-label" for="switch">ที่อยู่เดียวกับที่อยู่ตามบัตรประชาชน</label>
                        </div>
                        <div class="col-12 col-sm-6 switch">
                            <label class="form-label"><?php echo $l_address ?></label>
                            <input type="text" class="form-control" name="address_detail_2" placeholder="<?php echo $l_address ?>">
                        </div>
                        <div class="col-12 col-sm-6 switch">
                            <label class="form-label"><?php echo $l_provinces ?></label>
                            <select class="form-select" name="address_province_2" id="provinces_2">
                                <option value="" selected><?php echo $l_provinces ?></option>
                                <?php 
                                    $sql_dist = mysqli_query($connect, "SELECT * FROM system_provinces");
                                    while ($data = mysqli_fetch_array($sql_dist)) {
                                        ?>
                                        <option value="<?php echo $data['PROVINCE_ID'];?>"><?php echo $data['PROVINCE_NAME'];?></option>
                                        <?php
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-12 col-sm-4 switch" id="amphures_2">
                            <label class="form-label"><?php echo $l_amphures ?></label>
                            <select class="form-select" disabled>
                                <option value="" selected><?php echo $l_amphures ?></option>
                            </select>
                        </div>
                        <div class="col-12 col-sm-4 switch" id="districts_2">
                            <label class="form-label"><?php echo $l_district ?></label>
                            <select class="form-select" disabled>
                                <option value="" selected><?php echo $l_district ?></option>
                            </select>
                        </div>
                        <div class="col-12 col-sm-4 switch" id="postcode_2">
                            <label class="form-label"><?php echo $l_zipcode ?></label>
                            <input type="text" class="form-control" placeholder='กรุณากรอกรหัสไปรษณีย์' disabled>
                        </div>
                    </div>
                <?php } } else { ?>
                    <div class="row g-3">
                        <h6><?php echo $l_addridcard ?></h6>
                        <div class="col-12 col-sm-6">
                            <label class="form-label"><?php echo $l_address ?></label>
                            <input type="text" class="form-control" name="address_detail" maxlength=100 placeholder="<?php echo $l_address ?>">
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label"><?php echo $l_provinces ?></label>
                            <input type="text" class="form-control" name="address_province" maxlength=30 placeholder="<?php echo $l_provinces ?>">
                        </div>
                        <div class="col-12 col-sm-4" id="amphures">
                            <label class="form-label"><?php echo $l_amphures ?></label>
                            <input type="text" class="form-control" name="address_amphure" maxlength=30 placeholder="<?php echo $l_amphures ?>">
                        </div>
                        <div class="col-12 col-sm-4" id="districts">
                            <label class="form-label"><?php echo $l_district ?></label>
                            <input type="text" class="form-control" name="address_district" maxlength=30 placeholder="<?php echo $l_district ?>">
                        </div>
                        <div class="col-12 col-sm-4" id="postcode">
                            <label class="form-label"><?php echo $l_zipcode ?></label>
                            <input type="text" class="form-control" name="address_zipcode" maxlength=5 maxlength=5 placeholder="<?php echo $l_zipcode ?>">
                        </div>
                    </div>
                <?php } ?> 
            </div>
            <?php } ?>

            <!-- Bank -->
            <div class="col-12 border border-success rounded p-4 mb-3">
                <div class="row g-3">
                <h6><?php echo $l_bank ?></h6>
                <div class="col-12 col-sm-6">
                    <label class="form-label"><?php echo $l_bankown ?></label>
                    <input type="text" class="form-control" name="member_bank_own" placeholder="<?php echo $l_bankown ?>">
                </div>
                <div class="col-12 col-sm-6">
                    <label class="form-label"><?php echo $l_bankid ?> <font color=red>* <?php echo $l_validate ?></font></label>
                    <input type="text" class="form-control" name="member_bank_id" placeholder="<?php echo $l_bankid ?>" id="id_bank" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))">
                </div>
                <div class="col-12">
                    <label class="form-label"><?php echo $l_bankname ?></label>
                    <select class="form-select" name="member_bank_name">
                        <option value=""><?php echo $l_bankname ?></option>
                        <?php 
                        $query = mysqli_query($connect, "SELECT * FROM system_bank");
                        while ($data = mysqli_fetch_array($query)) { 

                            $bank_id    = $data['bank_id'];
                            $bank_name  = $data['bank_name_th'];

                            echo "<option value='$bank_id'>$bank_name</option>";

                        } ?>
                    </select>
                </div>
                </div>
            </div>

            <div class="col-12">
                <button name="action" value="insert" class="btn btn-success px-5"><?php echo $l_save ?></button>
                <?php if ($page_type == "signin_member.php") { ?>
                    <!--<a href="member.php" class="btn btn-link">Dashboard</a>-->
                <?php } ?>
            </div>
        </form>
    </div>
</div>
</div>