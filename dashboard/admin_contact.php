<?php isset($_GET["status"]) ? alert ($_GET["status"], $_GET["message"], $lang) : false;

$send_by = isset($_GET['send_by'])  ? $_GET['send_by']  : false;
$type    = isset($_GET['type'])     ? $_GET['type']     : false;
$id      = isset($_GET['id'])       ? $_GET['id']       : false;

$where   = isset($_GET['send_by']) && $send_by != '' ? " WHERE (contact_type = '$send_by') " : " WHERE contact_type NOT IN (1,3) ";

$url     = "admin.php?page=contact";
$sql     = "SELECT * FROM system_contact" . $where;
$order_by= " ORDER BY contact_status ASC, contact_create DESC ";

// send message
$message_href       = "#";
$message_class      = "btn btn-secondary";
if (isset($_GET['action']) && $send_by == 0) {
    $message_href   = "javascript:;";
    $message_class  = "btn btn-primary compose-mail-btn";
}
elseif (isset($_GET['action']) && $send_by == 2) {
    $query_buyer    = mysqli_query($connect, "SELECT * FROM system_contact WHERE contact_id = '$id'");
    $data_buyer     = mysqli_fetch_array($query_buyer);

    if ($data_buyer['contact_buyer'] != 0) {
        $message_href  = "javascript:;";
        $message_class = "btn btn-primary compose-mail-btn";
    }
}

// Menu's active
if ($send_by === false) { $menu1 = "active"; }
elseif ($send_by === '0') { $menu2 = "active"; }
elseif ($send_by == '1') { $menu3 = "active"; }
elseif ($send_by == '2') { $menu4 = "active"; }

?>
<title><?php echo $l_contact ?></title>
<div class="card-title d-flex align-items-center mb-3">
    <div><i class="bx bx-envelope me-1 font-22 text-primary"></i></div>
    <h5 class="mb-0 text-primary"><?php echo $l_contact ?></h5>
</div>
<div class="email-wrapper">
    <div class="email-sidebar">
        <div class="email-sidebar-header d-grid"> 
            <a href="<?php echo $message_href ?>" class="<?php echo $message_class ?>"><i class='bx bx-plus me-2'></i> <?php echo $l_send ?></a>
        </div>
        <div class="email-sidebar-content">
        <div class="email-navigation">
        <div class="list-group list-group-flush"> 
        <a href="admin.php?page=contact" class="list-group-item d-flex align-items-center <?php echo $menu1 ?>">
            <i class='bx bxs-inbox me-3 font-20'></i><span><?php echo $l_contact_index ?></span>
            <?php if ($contact != 0) { ?>
                <span class="badge bg-primary rounded-pill ms-auto"><?php echo $contact; ?></span>
            <?php } ?>
        </a>
        <a href="admin.php?page=contact&send_by=0" class="list-group-item d-flex align-items-center <?php echo $menu2 ?>">
            <i class="bx bx-subdirectory-right me-3"></i>
            <i class='bx bx-user me-3 font-20'></i><span><?php echo $l_member ?></span>
        </a>
        <?php if ($system_buyer == 1) { ?>
        <a href="admin.php?page=contact&send_by=2" class="list-group-item d-flex align-items-center <?php echo $menu4 ?>">
            <i class="bx bx-subdirectory-right me-3"></i>
            <i class='bx bx-globe me-3 font-20'></i><span><?php echo $l_buyer ?></span>
        </a>
        <?php } ?>
        <a href="admin.php?page=contact&send_by=1" class="list-group-item d-flex align-items-center <?php echo $menu3 ?>">
            <i class='bx bxs-send me-3 font-20'></i><span><?php echo $l_sended ?></span>
        </a>
        </div>
        </div>
        </div>
    </div>
    <div class="email-header d-xl-flex align-items-center">
        <form action="process/setting_contact.php" method="post" class="d-flex align-items-center">
            <div class="email-toggle-btn"><i class='bx bx-menu'></i></div>
            <input type="hidden" name="contact_array" value="" id="contact_array">
            <input type="hidden" name="page" value="<?php echo $page_type ?>">
            <button name="action" value="read" class="btn btn-white ms-2"><i class='bx bx-refresh me-0'></i>
            </button>
            <button name="action" value="delete" onclick="javascript:return confirm('ยืนยันการทำรายการ');" class="btn btn-white ms-2"><i class='bx bx-trash me-0'></i>
            </button>
        </form>
        <div class="ms-auto d-flex align-items-center mt-3">
            <?php pagination ($connect, $sql, $perpage, $page_id, $url); ?>
        </div>
    </div>
    <div class="email-content scroll pt-3 pt-sm-0">
        <?php if (!isset($_GET['action'])) { ?>
            <div class="">
                <div class="email-list">
                    <?php
                    $query = mysqli_query($connect, $sql . $order_by . $limit);
                    $empty = mysqli_num_rows($query);
                    if ($empty > 0) {
                        while ($data = mysqli_fetch_array($query)) { 
                            $id     = $data['contact_id'];
                            $member = $data['contact_member'];
                            $name   = mb_strimwidth($data['contact_name'], 0, 20, "...");
                            $email  = $data['contact_email'];
                            $detail = mb_strimwidth($data['contact_title'], 0, 50, "...");
                            $create = datethai($data['contact_create'], 2, $lang);
                            $status = $data['contact_status'] == 0 ? "text-primary" : false;
                            $send_by= $data['contact_type'];
                            ?>
                            <a href="admin.php?page=contact&action=contact&send_by=<?php echo $send_by ?>&id=<?php echo $id ?>">
                                <div class="d-md-flex align-items-center email-message px-3 py-2">
                                    <div class="d-flex align-items-center email-actions">
                                        <input class="form-check-input me-3" type="checkbox" value="<?php echo $id ?>" />
                                        <small class='bx bxs-circle me-2 <?php echo $status ?>'></small>
                                        <p class="mb-0"><b><?php echo $name ?></b></p>
                                    </div>
                                    <div class="">
                                        <p class="mb-0"><?php echo $detail ?></p>
                                    </div>
                                    <div class="ms-auto">
                                        <p class="mb-0 email-time"><?php echo $create ?></p>
                                    </div>
                                </div>
                            </a>         
                    <?php } } else { echo "<p class='m-5'>$l_notfound</p>"; } ?>
                </div>
            </div>
        <?php } else { 
            $id    = isset($_GET['id']) ? $_GET['id'] : false;
            $sql   = "SELECT * FROM system_contact WHERE contact_id = $id";
            $query = mysqli_query($connect, $sql);
            $data  = mysqli_fetch_array($query);
            
            $id     = $data['contact_id'];
            $member = $data['contact_member'];
            $buyer  = $data['contact_buyer'];
            $name   = $data['contact_name'];
            $title  = $data['contact_title'];
            $email  = $data['contact_email'];
            $detail = $data['contact_detail'];
            $create = $data['contact_create'];
            $status = $data['contact_status'];
            $send_by= $data['contact_type'];

            if ($data['contact_type'] != 1) {
                mysqli_query($connect, "UPDATE system_contact SET contact_status = 1 WHERE contact_id = $id");
            }
            ?>
            <div class="email-read-box p-3">
                <h4><?php echo $title ?></h4>
                <hr>
                <div class="d-flex align-items-center">
                    <?php if ($data['contact_member'] != 0) { 

                        $query2 = mysqli_query($connect, "SELECT * FROM system_member WHERE member_id = '$member'");
                        $data2  = mysqli_fetch_array($query2);

                        if ($data2['member_image_cover'] != "") {

                            $image_cover = $data2['member_image_cover'];
                            $image       = "<img src='assets/images/profiles/$image_cover' width='42' height='42' class='rounded-circle' alt='รูปโปรไฟล์'>";

                        }
                        else {  $image = "<i class='bx bx-user-circle text-dark font-35'></i>"; }
                    } else {    $image = "<i class='bx bx-user-circle text-dark font-35'></i>"; } 

                    echo $image;
                    ?>
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
                <form class="email-form" action="process/setting_contact.php" method="POST">
                    <input type="hidden" name="contact_member"  value="<?php echo $member ?>">
                    <input type="hidden" name="contact_buyer"   value="<?php echo $buyer ?>">
                    <input type="hidden" name="contact_name"    value="Admin">
                    <input type="hidden" name="contact_email"   value="<?php echo $email ?>">
                    <input type="hidden" name="contact_type"    value="1">
                    <div class="mb-3">
                        <input type="text" class="form-control" placeholder="To" value="<?php echo $name ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="contact_title" class="form-control" placeholder="Header" value="" />
                    </div>
                    <div class="mb-3">
                        <textarea class="form-control" name="contact_detail" placeholder="Deatil" rows="10" cols="10"></textarea>
                    </div>
                    <div class="mb-0">
                        <div class="d-flex align-items-center">
                            <div class="">
                                <div class="btn-group">
                                    <button name="action" value="insert" class="btn btn-primary"><?php echo $l_send ?></button>
                                </div>
                            </div>
                            <div class="ms-auto">
                                <button type="reset" class="btn border-0 btn-sm btn-white"><i class="lni lni-trash"></i>
                                </button>
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
                $("#contact_array").val(tmp);
                
            } else {
                tmp.splice($.inArray(checked, tmp),1);
                $("#contact_array").val(tmp);
            }
        });
    });
</script>