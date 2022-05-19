<?php 

isset($_GET["status"]) ? alert ($_GET["status"], $_GET["message"], $lang) : false;

$type = isset($_GET['type']) ? $_GET['type'] : false;

if ($type === "0") {
    $where = " AND (contact_type = 0) ";
    $url   = "member.php?page=contact&type=0";
}
else {
    $where = " AND (contact_type = 1) ";
    $url   = "member.php?page=contact";
}
$sql     = "SELECT * FROM system_contact WHERE (contact_member = '$member_id') " . $where;
$order_by= " ORDER BY contact_status ASC, contact_create DESC ";

// Menu's active
if ($type === false) { $menu1 = "active"; }
else { $menu2 = "active"; }
?>
<title><?php echo $l_contact ?></title>
<div class="email-wrapper">
    <div class="email-sidebar">
        <div class="email-sidebar-header d-grid"> 
            <a href="javascript:;" class="btn btn-primary compose-mail-btn"><i class='bx bx-plus me-2'></i>  <?php echo $l_send ?></a>
        </div>
        <div class="email-sidebar-content">
            <div class="email-navigation">
                <div class="list-group list-group-flush"> 
                    <a href="member.php?page=contact" class="list-group-item d-flex align-items-center <?php echo $menu1 ?>">
                        <i class='bx bxs-inbox me-3 font-20'></i><span><?php echo $l_contact_index ?></span>
                    </a>
                    <a href="member.php?page=contact&type=0" class="list-group-item d-flex align-items-center <?php echo $menu2 ?>">
                        <i class='bx bxs-send me-3 font-20'></i><span><?php echo $l_sended ?></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="email-header d-xl-flex align-items-center">
        <?php if ($type == false) { ?>
        <form action="process/setting_contact.php" method="post" class="d-flex align-items-center">
            <div class="email-toggle-btn"><i class='bx bx-menu'></i></div>
            <input type="hidden" name="contact_array"   value="" id="comment_array">
            <input type="hidden" name="page"    value="<?php echo $page_type ?>">
            <button name="action" value="read" class="btn btn-white ms-2"><i class='bx bx-refresh me-0'></i></button>
            <button name="action" value="delete" onclick="javascript:return confirm('Confirm');" class="btn btn-white ms-2"><i class='bx bx-trash me-0'></i></button>
        </form>
        <?php } ?>
        <div class="ms-auto d-flex align-items-center mt-3">
            <?php pagination ($connect, $sql, $perpage, $page_id, $url); ?>
        </div>
    </div>
    <div class="email-content scroll">
        <?php if (!isset($_GET['action'])) { ?>
            <div class="">
                <div class="email-list">
                    <?php
                    $query = mysqli_query($connect, $sql . $order_by . $limit);
                    $empty = mysqli_num_rows($query);
                    if ($empty > 0) {
                        while ($data = mysqli_fetch_array($query)) { 
                            $id     = $data['contact_id'];
                            $detail = $data['contact_title'];
                            $create = $data['contact_create'];
                            $status = $data['contact_status'];
                            if ($type === '0') {
                                $name = "You";
                            }
                            else {
                                $name = "Admin";
                            }
                            ?>
                            <a href="member.php?page=contact&action=contact&id=<?php echo $id ?>">
                                <div class="d-md-flex align-items-center email-message px-3 py-2">
                                    <div class="d-flex align-items-center email-actions">
                                        <input class="form-check-input me-3" type="checkbox" value="<?php echo $id ?>" />
                                        <?php if ($status == 0) { ?>
                                            <small class='bx bxs-circle me-2 text-primary'></small>
                                        <?php } else { ?>
                                            <small class='bx bxs-circle me-2'></small>
                                        <?php } ?>
                                        <p class="mb-0"><b><?php echo mb_strimwidth($name, 0, 20, "...") ?></b></p>
                                    </div>
                                    <div class="">
                                        <p class="mb-0"><?php echo mb_strimwidth($detail, 0, 90, "...") ?></p>
                                    </div>
                                    <div class="ms-auto">
                                        <p class="mb-0 email-time"><?php echo datethai($create, 2, $lang) ?></p>
                                    </div>
                                </div>
                            </a> 
                    
                    <?php } } else { echo "<p class='m-5'>$l_notfound</p>"; } ?>        
                    
                </div>
            </div>
        <?php } else {
            
            $sql = "SELECT * FROM system_contact WHERE contact_id = " . $_GET['id'];
            $query = mysqli_query($connect, $sql);
            $data  = mysqli_fetch_array($query);

            $id     = $data['contact_id'];
            $email  = $data['contact_email'];
            $title  = $data['contact_title'];
            $detail = $data['contact_detail'];
            $create = $data['contact_create'];
            $status = $data['contact_status'];
            if ($data['contact_type'] == '0') {
                $name = "You";
            }
            else {
                $name = "Admin";
            }
            if ($data['contact_type'] == 1) {
                mysqli_query($connect, "UPDATE system_contact SET contact_status = 1 WHERE contact_id = " . $_GET['id']);
            }

            if ($data_check_login['member_image_cover'] != '') {
                $profile_image= "assets/images/profiles/" . $data_check_login['member_image_cover'];
            }
            else {
                $profile_image= "assets/images/etc/example_member.jpg";
            }
            ?>
            <div class="email-read-box p-3">
                <h4><?php echo $title ?></h4>
                <hr>
                <div class="d-flex align-items-center">
                    <img src="<?php echo $profile_image ?>" width="42" height="42" class="rounded-circle" alt="" />
                    <div class="flex-grow-1 ms-2">
                        <p class="mb-0 font-weight-bold"><?php echo $name ?></p>
                        <p class="mb-0 font-weight-bold"><?php echo $email ?></p>
                    </div>
                    <p class="mb-0 chat-time ps-5 ms-auto"><?php echo datethai($create, 2, $lang) ?></p>
                </div>
                <div class="email-read-content px-md-5 py-5"><?php echo $detail ?></div>
            </div>
        <?php } ?>
    </div>

    <div class="compose-mail-popup">
        <div class="card">
            <div class="card-header bg-dark text-white py-2 cursor-pointer">
                <div class="d-flex align-items-center">
                    <div class="compose-mail-title"><?php echo $l_send ?></div>
                    <div class="compose-mail-close ms-auto">x</div>
                </div>
            </div>
            <div class="card-body">
                <form class="email-form" action="process/setting_contact.php" method="post">
                    <?php 
                    $query = mysqli_query($connect, "SELECT * FROM system_member WHERE member_id = '$member_id'");
                    $data  = mysqli_fetch_array($query);
                    ?>
                    <input type="hidden" name="contact_member"  value="<?php echo $member_id ?>">
                    <input type="hidden" name="contact_name"    value="<?php echo $data['member_name'] ?>">
                    <input type="hidden" name="contact_email"   value="<?php echo $data['member_email'] ?>">
                    <input type="hidden" name="contact_type"    value="0">
                    <div class="mb-3">
                        <p>ถึง ผู้ดูแลระบบ</p>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="contact_title" class="form-control" placeholder="Header" required>
                    </div>
                    <div class="mb-3">
                        <textarea class="form-control" name="contact_detail" placeholder="Deatil" rows="10" cols="10"></textarea>
                    </div>
                    <div class="mb-0">
                        <div class="d-flex align-items-center">
                            <div class="">
                                <div class="btn-group"><button name="action" value="insert" class="btn btn-primary"><?php echo $l_send ?></button></div>
                            </div>
                            <div class="ms-auto">
                                <button type="reset" class="btn border-0 btn-sm btn-white"><i class="lni lni-trash"></i></button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="overlay email-toggle-btn-mobile"></div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var tmp = [];
        $(":checkbox").change(function() {
            var checked = $(this).val();
            if ($(this).is(':checked')) {
                tmp.push(checked);
                $("#comment_array").val(tmp);
                
            } else {
                tmp.splice($.inArray(checked, tmp),1);
                $("#comment_array").val(tmp);
            }
        });
    });
</script>