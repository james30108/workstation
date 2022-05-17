<?php 

$send_by = isset($_GET['send_by'])  ? $_GET['send_by']  : false;
$type    = isset($_GET['type'])     ? $_GET['type']     : false;
$id      = isset($_GET['id'])       ? $_GET['id']       : false;

if ($type != false && $type != 'all') {
    $where  = " WHERE (comment_type = $type) AND (comment_name != 'Admin') ";
}
elseif($send_by != false) {
    $where  = " WHERE comment_name = 'Admin' ";
}
else {
    $where  = " WHERE comment_name != 'Admin' ";
}

if ($id === false || $send_by == 'admin') {
    $message_href  = "#";
    $message_class = "btn btn-secondary";
}
else {
    $message_href  = "javascript:;";
    $message_class = "btn btn-primary compose-mail-btn";
}

if ($type === false && $send_by === false) { $menu1 = "active"; }
elseif ($type == '1') { $menu2 = "active"; }
elseif ($type === '0') { $menu3 = "active"; }
elseif ($send_by == 'admin') { $menu4 = "active"; }

$url     = "admin.php?page=comment";
$sql     = "SELECT * FROM system_comment " . $where;
$order_by= " ORDER BY comment_status ASC, comment_create DESC ";

?>

<title><?php echo $l_comment ?></title>
<div class="card-title d-flex align-items-center mb-3">
    <div><i class="bx bx-comment me-1 font-22 text-primary"></i></div>
    <h5 class="mb-0 text-primary"><?php echo $l_comment ?></h5>
</div>
<?php isset($_GET["status"]) ? alert ($_GET["status"], $_GET["message"]) : false; ?>
<div class="email-wrapper">
    <div class="email-sidebar">
        <div class="email-sidebar-header d-grid"> 
        <?php echo "<a href='$message_href' class='btn btn-secondary $message_class'><i class='bx bx-plus me-2'></i> $l_send</a>" ?>
        </div>
        <div class="email-sidebar-content">
        <div class="email-navigation">
        <div class="list-group list-group-flush"> 
            <a href="admin.php?page=comment" class="list-group-item d-flex align-items-center <?php echo $menu1 ?>">
                <i class='bx bx-message me-3 font-20'></i><span><?php echo $l_webpage ?></span>
            </a>
            <?php if ($system_webpage == 1) { ?>
            <a href="admin.php?page=comment&type=1" class="list-group-item d-flex align-items-center <?php echo $menu2 ?>">
                <i class="bx bx-subdirectory-right me-3"></i>
                <i class='bx bx-chalkboard me-3 font-20'></i><span><?php echo $l_blog ?></span>
            </a>
            <?php } ?>
            <a href="admin.php?page=comment&type=0" class="list-group-item d-flex align-items-center <?php echo $menu3 ?>">
                <i class="bx bx-subdirectory-right me-3"></i>
                <i class='bx bx-file-blank me-3 font-20'></i><span><?php echo $l_product ?></span>
            </a>
            <a href="admin.php?page=comment&send_by=admin" class="list-group-item d-flex align-items-center <?php echo $menu4 ?>">
                <i class='bx bxs-send me-3 font-20'></i><span><?php echo $l_sended ?></span>
            </a>
        </div>
        </div>
        </div>
    </div>
    <div class="email-header d-xl-flex align-items-center">
        <form action="process/setting_comment.php" method="post" class="d-flex align-items-center">
            <div class="email-toggle-btn"><i class='bx bx-menu'></i></div>
            <input type="hidden" name="page" value="<?php echo $page_type ?>">
            <input type="hidden" name="comment_array" value="" id="comment_array">
            <button name="action" value="read" class="btn btn-white ms-2"><i class='bx bx-refresh me-0'></i>
            </button>
            <button name="action" value="delete" onclick="javascript:return confirm('ยืนยันการทำรายการ');" class="btn btn-white ms-2"><i class='bx bx-trash me-0'></i>
            </button>
        </form>
        <div class="ms-auto d-flex align-items-center mt-3">
            <?php pagination ($connect, $sql, $perpage, $page_id, $url); ?>
        </div>
    </div>
    <div class="email-content scroll">
        <?php if (!isset($_GET['id'])) { ?>
            <div class="">
                <div class="email-list">
                    <?php
                    $query = mysqli_query($connect, $sql . $order_by . $limit);
                    $empty = mysqli_num_rows($query);
                    if ($empty > 0) {
                        while ($data = mysqli_fetch_array($query)) { 
                            $id     = $data['comment_id'];
                            $name   = mb_strimwidth($data['comment_name'], 0, 20, "...");
                            $detail = mb_strimwidth($data['comment_detail'], 0, 50, "...");
                            $create = datethai($data['comment_create'], 2, $lang);
                            $status = $data['comment_status'];
                            $type   = $data['comment_type'];
                            $url_open = "admin.php?page=comment&type=$type&send_by=$send_by&id=$id";
                            ?>
                            <a href="<?php echo $url_open ?>">
                            <div class="d-md-flex align-items-center email-message px-3 py-2">
                                <div class="d-flex align-items-center email-actions">
                                <input class="form-check-input me-3" type="checkbox" value="<?php echo $id ?>" />
                                    <small class='bx bxs-circle me-2 <?php echo $status == 0 ? "text-primary" : false; ?>'></small>
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
                    </tbody>
                </div>
            </div>
        <?php } else { 
            mysqli_query($connect, "UPDATE system_comment SET comment_status = 1 WHERE comment_id = $id");

            $sql = "SELECT * FROM system_comment WHERE comment_id = $id";
            $query = mysqli_query($connect, $sql);
            $data  = mysqli_fetch_array($query);

            $id     = $data['comment_id'];
            $name   = $data['comment_name'];
            $detail = $data['comment_detail'];
            $create = $data['comment_create'];
            $status = $data['comment_status'];
            $type   = $data['comment_type'];
            $link   = $data['comment_link'];
            $member = $data['comment_member'];
            
            if ($type == 0) {
                $query1 = mysqli_query($connect, "SELECT * FROM system_product WHERE product_id = '$link' ");
                $data1  = mysqli_fetch_array($query1);
                $title = "$l_source : <a href='../?page=product_single&product_id=".$data1['product_id']."' target='_blank'>".$data1['product_name']."</a>";
            }
            else {
                $query1 = mysqli_query($connect, "SELECT * FROM system_blog WHERE blog_id = '$link' ");
                $data1  = mysqli_fetch_array($query1);
                $title = "$l_source : <a href='../?page=blog_single&blog_id==".$data1['blog_id']."' target='_blank'>".$data1['blog_name']."</a>";
            }

            if ($member != 0) {
                $query = mysqli_query($connect, "SELECT * FROM system_member WHERE member_id = '$member' ");
                $data  = mysqli_fetch_array($query);
                $email = $data['member_email'];
                if ($data['member_image_cover'] != '') {
                    $profile_image = "assets/images/profiles/" . $data['member_image_cover'];
                }
                else {
                    $profile_image = "assets/images/etc/example_member.jpg";
                }
            }
            else {
                $member = $data['comment_buyer'];
                $query = mysqli_query($connect, "SELECT * FROM system_buyer WHERE buyer_id = '$member' ");
                $data  = mysqli_fetch_array($query);
                $email = $data['buyer_email'];
                $profile_image = "assets/images/etc/example_member.jpg";
            }            
            ?>
            <div class="email-read-box p-3">
                <h4><?php echo $title ?></h4>
                <hr>
                <div class="d-flex align-items-center">
                    <img src="<?php echo $profile_image ?>" width="42" height="42" class="rounded-circle" alt="" />
                    <div class="flex-grow-1 ms-2">
                        <p class="mb-0 font-weight-bold"><?php echo $name ?></p>
                        <?php if ($member == 0) { ?>
                            <p class="mb-0 font-weight-bold"><?php echo $email ?></p>
                        <?php } ?>
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
                    <div class="compose-mail-title"><?php echo $l_mess_send ?></div>
                    <div class="compose-mail-close ms-auto">x</div>
                </div>
            </div>
            <div class="card-body">
                <form class="email-form" action="process/setting_comment.php" method="POST">
                    <input type="hidden" name="comment_link"    value="<?php echo $link ?>">
                    <input type="hidden" name="comment_direct"  value="<?php echo $id ?>">
                    <input type="hidden" name="comment_type"    value="<?php echo $type ?>">
                    <input type="hidden" name="page"            value="backend">
                    <div class="mb-3">
                        <input type="text" class="form-control" placeholder="To" value="<?php echo $name ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <textarea class="form-control" name="comment_detail" placeholder="Detail" rows="10" cols="10"></textarea>
                    </div>
                    <div class="mb-0">
                        <div class="d-flex align-items-center">
                            <div class="">
                                <div class="btn-group">
                                    <button name="action" value="insert" class="btn btn-primary"><?php echo $l_mess_send ?></button>
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
                $("#comment_array").val(tmp);
                
            } else {
                tmp.splice($.inArray(checked, tmp),1);
                $("#comment_array").val(tmp);
            }
        });
    });
</script>