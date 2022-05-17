<div class="col-12 col-sm-8 mx-auto">
<?php
if ($page_type == "admin.php") { $member_id = $_GET['member_id']; }

$query  = mysqli_query($connect, "SELECT * FROM system_member WHERE member_id = '$member_id' ");
$data   = mysqli_fetch_array($query);

$member_id          = $data['member_id'];
$member_bank_own    = $data['member_bank_own'];
$member_bank_name   = $data['member_bank_name'];
$member_bank_id     = $data['member_bank_id'];
$member_pass        = $data['member_pass'];
$member_user        = $data['member_user'];
$member_image_bank  = $data['member_image_bank'];
$member_image_card  = $data['member_image_card'];
$member_image_cover = $data['member_image_cover'];

$member_name        = $data['member_name'];
$member_code_id     = $data['member_code_id'];
$member_tel         = $data['member_tel'];
$member_token_line  = $data['member_token_line'];
$member_email       = $data['member_email'];

?>
<title><?php echo $l_edimemer ?></title>
<?php if ($_GET['page'] == 'edit_profile') { ?>
    <div class="card-title d-flex align-items-center">
        <div><i class="bx bx-git-repo-forked me-1 font-22 text-primary"></i></div>
        <h5 class="mb-0 text-primary"><?php echo $l_edimemer ?></h5>
    </div>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page"><a href="admin.php?page=liner"><?php echo $l_member ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo $l_edimemer ?></li>
        </ol>
    </nav>
<?php } isset($_GET["status"]) ? alert ($_GET["status"], $_GET["message"], $lang) : false; ?>
<div class="card border-top border-0 border-4 border-primary">
    <div class="card-body p-1 pt-3 pb-3 p-sm-5">
        
        <table class="table table-borderless table-sm">
            <tbody>
            <tr>
                <th width="150"><?php echo $l_idcardimage ?> : </th>
                <?php echo $member_image_card != '' ? "<td><a href='assets/images/card/$member_image_card' target='_blank'>$l_idcardimage</a></td>" : "<td>$l_notfound</td>"; ?>
            </tr>
                <tr>
                    <th><?php echo $l_bankimage ?> : </th>
                    <?php echo $member_image_bank != '' ? "<td><a href='assets/images/card/$member_image_bank' target='_blank'>$l_bankimage</a></td>" : "<td>$l_notfound</td>"; ?>
                </tr>
            </tbody>
        </table>

        <!-- Profile -->
        <div class="col-12 border border-primary rounded p-4 mb-3">
            <form class="row g-3" action="process/setting_member.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="page"                value="<?php echo $page_type?>">
                <input type="hidden" name="member_id"           value="<?php echo $member_id ?>" id="member_id">
                <input type="hidden" name="member_image_card"   value="<?php echo $member_image_card ?>">
                <input type="hidden" name="member_image_cover"  value="<?php echo $member_image_cover ?>">
                
                <h6><?php echo $l_profile ?></h6>
                <div class="col-12">
                    <label  class="form-label"><?php echo $l_member_name ?> <font color="red">*</font></label>
                    <input type="text" class="form-control" name="member_name" value="<?php echo $member_name ?>" placeholder="<?php echo $l_member_name ?>" required>
                </div>
                <div class="col-12 col-sm-6">
                    <label class="form-label"><?php echo $l_idcard ?> <font color="red">*</font></label>
                    <input type="text" class="form-control" name="member_code_id" value="<?php echo $member_code_id ?>" placeholder="<?php echo $l_idcard ?>" maxlength="13" id="id_card2" required>
                </div>
                <div class="col-12 col-sm-6">
                    <label class="form-label"><?php echo $l_tel ?></label>
                    <input type="text" class="form-control" name="member_tel" value="<?php echo $member_tel ?>" maxlength="10" placeholder="<?php echo $l_tel ?>">
                </div>
                <!--
                <div class="col-12 col-sm-6">
                    <label class="form-label"><?php echo $l_line ?></label>
                    <input type="text" class="form-control" name="member_token_line" value="<?php echo $member_token_line ?>" placeholder="<?php echo $l_line ?>">
                </div>
                -->
                <div class="col-12">
                    <label class="form-label"><?php echo $l_email ?></label>
                    <input type="email" class="form-control" name="member_email" value="<?php echo $member_email ?>" placeholder="Example@example.com">
                </div>
                <div class="col-12">
                    <label for="formFile" class="form-label"><?php echo $l_idcardimage ?></label>
                    <input class="form-control" type="file" name="member_image_card_new">
                </div>
                <!--
                <div class="col-12 col-sm-6">
                    <label for="formFile" class="form-label"><?php echo $l_profileimage ?></label>
                    <input class="form-control" type="file" name="member_image_cover_new">
                </div>
                <hr class="my-3">
                <div class="col-12 col-sm-6">
                    <label class="form-label">ผู้รับมรดก</label>
                    <input type="text" class="form-control" name="member_heir_name" maxlength="100" value="<?php echo $data['member_heir_name'] ?>" placeholder="ผู้รับมรดก">
                </div>
                <div class="col-12 col-sm-6">
                    <label class="form-label">บัตรประชาชนผู้รับมรดก</label>
                    <input type="text" class="form-control" name="member_heir_id" maxlength="15" placeholder="บัตรประชาชนผู้รับมรดก" value="<?php echo $data['member_heir_id'] ?>">
                </div>
                -->
                <div class="col-12 d-flex">
                    <button name="action" value="edit_profile" class="btn btn-primary ms-auto"><?php echo $l_save ?></button>
                </div>
            </form>
        </div>

        <!-- Address -->
        <?php if ($system_address != 0) { ?>
        <div class="col-12 border border-primary rounded p-4 mb-3">
            <?php 
            $query = mysqli_query($connect, "SELECT * FROM system_address WHERE (address_member = '$member_id') AND (address_type = 0) ");
            $data  = mysqli_fetch_array($query);
            $address_id         = isset($data['address_id'])        ? $data['address_id']       : false;
            $address_detail     = isset($data['address_detail'])    ? $data['address_detail']   : false;
            $address_district   = isset($data['address_district'])  ? $data['address_district'] : false;
            $address_amphure    = isset($data['address_amphure'])   ? $data['address_amphure']  : false;
            $address_province   = isset($data['address_province'])  ? $data['address_province'] : false;
            $address_zipcode    = isset($data['address_zipcode'])   ? $data['address_zipcode']  : false;

            $query = mysqli_query($connect, "SELECT * FROM system_address WHERE (address_member = '$member_id') AND (address_type = 1) ");
            $data  = mysqli_fetch_array($query);
            $address_id_2         = isset($data['address_id'])        ? $data['address_id']       : false;
            $address_detail_2     = isset($data['address_detail'])    ? $data['address_detail']   : false;
            $address_district_2   = isset($data['address_district'])  ? $data['address_district'] : false;
            $address_amphure_2    = isset($data['address_amphure'])   ? $data['address_amphure']  : false;
            $address_province_2   = isset($data['address_province'])  ? $data['address_province'] : false;
            $address_zipcode_2    = isset($data['address_zipcode'])   ? $data['address_zipcode']  : false;
            ?>
            <form class="row g-3" action="process/setting_member.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="page"            value="<?php echo $page_type ?>">
                <input type="hidden" name="member_id"       value="<?php echo $member_id ?>">
                <input type="hidden" name="lang"            value="<?php echo $lang ?>" id="lang">
                <input type="hidden" name="address_id"      value="<?php echo $address_id ?>">
                <input type="hidden" name="address_id_2"    value="<?php echo $address_id_2 ?>">
                <?php if ($lang == 0) { ?>
                    <input type="hidden" name="address_amphure"     id="amphures_data"      value="<?php echo $address_amphure ?>">
                    <input type="hidden" name="address_district"    id="districts_data"     value="<?php echo $address_district ?>">
                    <input type="hidden" name="address_amphure_2"   id="amphures_data_2"    value="<?php echo $address_amphure_2 ?>">
                    <input type="hidden" name="address_district_2"  id="districts_data_2"   value="<?php echo $address_district_2 ?>">
                    <h6><?php echo $l_addridcard ?></h6>
                    <div class="col-12 col-sm-6">
                        <label class="form-label"><?php echo $l_address ?></label>
                        <input type="text" class="form-control" name="address_detail" value="<?php echo $address_detail ?>" placeholder="<?php echo $l_address ?>">
                    </div>
                    <div class="col-12 col-sm-6">
                        <label class="form-label"><?php echo $l_provinces ?></label>
                        <select class="form-select" name="address_province" id="provinces">
                            <option value=""><?php echo $l_provinces ?></option>
                            <?php 
                            $query = mysqli_query($connect, "SELECT * FROM system_provinces");
                            while ($data = mysqli_fetch_array($query)) { ?>
                                <option value="<?php echo $data['PROVINCE_ID'];?>" 
                                    <?php if ($data['PROVINCE_ID'] == $address_province) { echo "selected"; } ?> >
                                    <?php echo $data['PROVINCE_NAME'];?>        
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-12 col-sm-4" id="amphures">
                        <label class="form-label"><?php echo $l_amphures ?></label>
                        <select class="form-select" name="address_amphure" id="amphures_select">
                            <option value=""><?php echo $l_amphures ?></option>
                            <?php 
                            $query = mysqli_query($connect, "SELECT * FROM system_amphures WHERE PROVINCE_ID = '$address_province' ");
                            while ($data = mysqli_fetch_array($query)) { ?>
                                <option value="<?php echo $data['AMPHUR_ID'];?>" 
                                    <?php if ($data['AMPHUR_ID'] == $address_amphure) { echo "selected"; } ?> >
                                    <?php echo $data['AMPHUR_NAME'];?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-12 col-sm-4" id="districts">
                        <label class="form-label"><?php echo $l_district ?></label>
                        <select class="form-select" name="address_district" id="districts_select">
                            <option value=""><?php echo $l_district ?></option>
                            <?php 
                            $query = mysqli_query($connect, "SELECT * FROM system_districts WHERE AMPHUR_ID = '$address_amphure' ");
                            while ($data = mysqli_fetch_array($query)) { ?>
                                <option value="<?php echo $data['DISTRICT_CODE'];?>" 
                                    <?php if ($data['DISTRICT_CODE'] == $address_district) {echo "selected";}?> > 
                                    <?php echo $data['DISTRICT_NAME'];?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-12 col-sm-4" id="postcode">
                        <label class="form-label"><?php echo $l_zipcode ?></label>
                        <input type="text" class="form-control" name="address_zipcode" placeholder="<?php echo $l_zipcode ?>" value="<?php echo $address_zipcode ?>" maxlength=5 maxlength=5>
                    </div>
                    
                    <?php if ($system_address == 2) { ?>
                        <h6 class="mt-5">ที่อยู่สำหรับจัดส่งสินค้า</h6>
                        <div class="col-12 form-check form-switch ms-2 mt-3">
                            <input class="form-check-input" name="address_type" type="checkbox" id="flexSwitchCheckChecked">
                            <label class="form-check-label" for="flexSwitchCheckChecked">ที่อยู่เดียวกับที่อยู่ตามบัตรประชาชน</label>
                        </div>
                        <div class="col-12 col-sm-6 address mt-3">
                            <label class="form-label">ที่อยู่</label>
                            <input type="text" class="form-control" name="address_detail_2" value="<?php echo $address_detail_2 ?>">
                        </div>
                        <div class="col-12 col-sm-6 address mt-3">
                            <label class="form-label">จังหวัด</label>
                            <select class="form-select" name="address_province_2" id="provinces_2">
                                <option value="" selected>กรุณาเลือกจังหวัด</option>
                                <?php 
                                $query = mysqli_query($connect, "SELECT * FROM system_provinces");
                                while ($data = mysqli_fetch_array($query)) { ?>
                                    <option value="<?php echo $data['PROVINCE_ID'];?>" 
                                        <?php if ($data['PROVINCE_ID'] == $address_province_2) { echo "selected"; } ?> >
                                        <?php echo $data['PROVINCE_NAME'];?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-12 col-sm-4 address mt-3" id="amphures_2">
                            <label class="form-label">อำเภอ / เขต</label>
                            <select class="form-select" name="address_amphure_2" id="amphures_select_2">
                                <option value="">เลือกอำเภอ / เขต</option>
                                <?php 
                                $query = mysqli_query($connect, "SELECT * FROM system_amphures WHERE PROVINCE_ID = '$address_province_2' ");
                                while ($data = mysqli_fetch_array($query)) { ?>
                                    <option value="<?php echo $data['AMPHUR_ID'];?>" 
                                        <?php if ($data['AMPHUR_ID'] == $address_amphure_2) {echo "selected";}?> > 
                                        <?php echo $data['AMPHUR_NAME'];?>        
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-12 col-sm-4 address mt-3" id="districts_2">
                            <label class="form-label">ตำบล / แขวง</label>
                            <select class="form-select" name="address_district_2" id="districts_select_2">
                                <option value="" selected>ตำบล</option>
                                <?php 
                                $query = mysqli_query($connect, "SELECT * FROM system_districts WHERE AMPHUR_ID = '$address_amphure_2' ");
                                while ($data = mysqli_fetch_array($query)) {?>
                                    <option value="<?php echo $data['DISTRICT_CODE'];?>" 
                                        <?php if ($data['DISTRICT_CODE'] == $address_district_2) {echo "selected";}?> > 
                                        <?php echo $data['DISTRICT_NAME'];?> 
                                    </option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-12 col-sm-4 address mt-3" id="postcode_2">
                            <label class="form-label">รหัสไปรษณีย์</label>
                            <input type="text" name="address_zipcode_2" class="form-control" placeholder="กรุณากรอกรหัสไปรษณีย์" value="<?php echo $address_zipcode_2 ?>" maxlength=5 maxlength=5>
                        </div>
                <?php } } else { ?>
                    <div class="row">
                        <h6><?php echo $l_addridcard ?></h6>
                        <div class="col-12 col-sm-6 mt-3">
                            <label class="form-label"><?php echo $l_address ?></label>
                            <input type="text" class="form-control" name="address_detail" maxlength=100 value="<?php echo $address_detail ?>" placeholder="<?php echo $l_address ?>">
                        </div>
                        <div class="col-12 col-sm-6 mt-3">
                            <label class="form-label"><?php echo $l_provinces ?></label>
                            <input type="text" class="form-control" name="address_province" maxlength=30 value="<?php echo $address_province ?>" placeholder="<?php echo $l_provinces ?>">
                        </div>
                        <div class="col-12 col-sm-4 mt-3" id="amphures">
                            <label class="form-label"><?php echo $l_amphures ?></label>
                            <input type="text" class="form-control" name="address_amphure" maxlength=30 value="<?php echo $address_amphure ?>" placeholder="<?php echo $l_amphures ?>">
                        </div>
                        <div class="col-12 col-sm-4 mt-3" id="districts">
                            <label class="form-label"><?php echo $l_district ?></label>
                            <input type="text" class="form-control" name="address_district" maxlength=30 value="<?php echo $address_district ?>" placeholder="<?php echo $l_district ?>">
                        </div>
                        <div class="col-12 col-sm-4 mt-3" id="postcode">
                            <label class="form-label"><?php echo $l_zipcode ?></label>
                            <input type="text" class="form-control" name="address_zipcode" maxlength=5 value="<?php echo $address_zipcode ?>" maxlength=5 placeholder="<?php echo $l_zipcode ?>">
                        </div>
                    </div>
                <?php } ?>
                <div class="col-12 d-flex">
                    <button name="action" value="edit_address" class="btn btn-primary ms-auto"><?php echo $l_save ?></button>
                </div>
            </form>
        </div>
        <?php } ?>

        <!-- Bank -->
        <div class="col-12 border border-primary rounded p-4 mb-3">
            <form class="row  g-3" action="process/setting_member.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="page" value="<?php echo $page_type ?>">
                <input type="hidden" name="member_id" value="<?php echo $member_id ?>">
                <input type="hidden" name="member_image_bank" value="<?php echo $member_image_bank ?>">
                <h6><?php echo $l_bank ?></h6>
                <div class="col-12 col-sm-6">
                    <label class="form-label"><?php echo $l_bankown ?></label>
                    <input type="text" class="form-control" name="member_bank_own" value="<?php echo $member_bank_own ?>" placeholder="<?php echo $l_bankown ?>">
                </div>
                <div class="col-12 col-sm-6">
                    <label class="form-label"><?php echo $l_bankid ?> <font color=red>* <?php echo $l_validate ?></font></label>
                    <input type="text" class="form-control" name="member_bank_id" value="<?php echo $member_bank_id ?>" placeholder="<?php echo $l_bankid ?>" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" required>
                </div>
                <div class="col-12 col-sm-6">
                    <label class="form-label"><?php echo $l_bankname ?></label>
                    <select class="form-select" name="member_bank_name">
                        <option value=""><?php echo $l_bankname ?></option>
                        <?php 
                        $query = mysqli_query($connect, "SELECT * FROM system_bank");
                        while ($data = mysqli_fetch_array($query)) { 

                            $bank_id    = $data['bank_id'];
                            $bank_name  = $data['bank_name_th'];

                            echo "<option value='$bank_id' ";
                            echo $bank_id == $member_bank_name ? "selected" : false;
                            echo ">$bank_name</option>";

                        } ?>
                    </select>
                </div>
                <div class="col-12 col-sm-6">
                    <label for="formFile" class="form-label"><?php echo $l_bankimage ?></label>
                    <input class="form-control" type="file" name="member_image_bank_new">
                </div>
                <div class="col-12 d-flex">
                    <button name="action" value="edit_bank" class="btn btn-primary ms-auto"><?php echo $l_save ?></button>
                </div>
            </form>
        </div>

        <!-- Login -->
        <div class="col-12 border border-primary rounded p-4">
            <form class="row g-3" action="process/setting_member.php" method="post">
                <input type="hidden" name="page" value="<?php echo $page_type ?>">
                <input type="hidden" name="member_id" value="<?php echo $member_id ?>">
                <input type="hidden" name="member_pass" value="<?php echo $member_pass ?>">
                <h6><?php echo $l_login ?></h6>
                <div class="col-12 col-sm-4">
                    <label class="form-label"><?php echo $l_username ?></label>
                    <input type="text" class="form-control" name="member_user" value="<?php echo $member_user ?>" placeholder="<?php echo $l_username ?>" minlength="6" maxlength="15">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">password</label>
                    <input type="password" class="form-control" name="member_pass_new" placeholder="Password" minlength="8" maxlength="15">
                </div>
                <div class="col-12 col-sm-4">
                    <label class="form-label">re-password</label>
                    <input type="password" class="form-control" name="member_pass_recheck" placeholder="Re-Password" minlength="8" maxlength="15">
                </div>
                <div class="col-12 d-flex">
                    <button name="action" value="edit_login" class="btn btn-primary ms-auto"><?php echo $l_save ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>