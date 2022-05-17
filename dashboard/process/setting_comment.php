<?php include('function.php'); 

if ($_POST['action'] == 'insert') {

	$comment_link 	= $_POST['comment_link'];
    $comment_direct = $_POST['comment_direct'];
	$comment_type 	= $_POST['comment_type'];
	$comment_detail = $_POST['comment_detail'];

    $comment_link 	= $_POST['comment_link'];
    $comment_link 	= $_POST['comment_link'];
    $comment_link 	= $_POST['comment_link'];

    if (isset($_POST['comment_member'])) {
        $comment_member = $_POST['comment_member'];

        $query          = mysqli_query($connect, "SELECT * FROM system_member WHERE member_id = '$comment_member'");
        $data           = mysqli_fetch_array($query);
        $comment_name   = $data['member_name'];
        $comment_status = 0;
        $comment_buyer 	= 0;
    }
    elseif (!isset($_POST['comment_member']) && !isset($_POST['comment_name'])) {
        $comment_member = 0;
        $comment_name   = "Admin";
        $comment_status = 1;
        $comment_buyer 	= 0;
    }
    elseif (!isset($_POST['comment_member']) && isset($_POST['comment_name'])) {
        $comment_member = 0;
        $comment_name   = $_POST['comment_name'];
        $comment_status = 0;
        $comment_buyer 	= $_POST['comment_buyer'];
    }

    mysqli_query($connect, "INSERT INTO system_comment (
    	comment_link,
        comment_direct, 
    	comment_type, 
    	comment_name,
        comment_member, 
    	comment_detail,
        comment_status,
        comment_buyer) 
    VALUES (
    	'$comment_link',
        '$comment_direct', 
    	'$comment_type', 
    	'$comment_name',
        '$comment_member', 
    	'$comment_detail',
        '$comment_status',
        '$comment_buyer'
    )");
    
    if (isset($_POST['page']) && $_POST['page'] == 'backend') {
        header("location:../admin.php?page=comment");
    }
    elseif ($comment_type == 0 && isset($_POST['page']) && $_POST['page'] != 'backend') {
        header("location:../" . $_POST['page'] . "?page=shopping&action=show_product&product_id=" . $comment_link  . '#comment');
    }
    elseif ($comment_type == 1 && isset($_POST['page']) && $_POST['page'] != 'backend') {
        header("location:../" . $_POST['page'] . "?page=admin_blog&action=show_blog&blog_id=" . $comment_link  . '#comment');
    }
    elseif ($comment_type == 0 && !isset($_POST['page'])) {
        mysqli_query($connect, "UPDATE system_order_detail SET order_detail_review = 1 WHERE order_detail_id = '".$_POST['order_detail_id']."'");
    	header('location:../../?page=profile&action=order_detail');
        //header('location:../../?page=product_single&product_id=' . $comment_link  . '#comment');
    }
    elseif ($comment_type == 1 && !isset($_POST['page'])) {
    	header('location:../../?page=blog_single&blog_id=' . $comment_link . '#comment');
    }
}
elseif ($_POST['action'] == 'read') {
    
    $comment_array = explode(",", $_POST['comment_array']);
    foreach ($comment_array AS $comment_id) {
        mysqli_query($connect, "UPDATE system_comment SET comment_status = 1 WHERE comment_id = '$comment_id' ");
    }
    header('location:../admin.php?page=admin_comment&status=success');
}
elseif ($_POST['action'] == 'delete') {
    
    $comment_array = explode(",", $_POST['comment_array']);
    foreach ($comment_array AS $comment_id) {
        mysqli_query($connect, "DELETE FROM system_comment WHERE comment_id = '$comment_id' ");
    }
    header("location:../".$_POST['page']."?page=comment&status=success");
}
