<?php 

$thread_id = $_GET['thread_id'];

$query = mysqli_query($connect, "SELECT system_thread.*, system_thread_type.*
	FROM system_thread
	INNER JOIN system_thread_type ON (system_thread.thread_type = system_thread_type.thread_type_id)
	WHERE thread_id = '$thread_id' ");
$data  = mysqli_fetch_array($query);

$thread_title 		= $_GET['thread_title'];
$thread_image 		= $_GET['thread_image'];
$thread_create 		= datethai($data['thread_create'], 0, $system_lang);
$thread_type 		= $_GET['thread_type'];
$thread_type_name 	= $_GET['thread_type_name'];
$thread_detail 		= $_GET['thread_detail'];

$comment_direct = isset($_GET['comment_direct']) ? $_GET['comment_direct'] : 0;
?>
<title><?php echo $data['thread_title'] ?></title>
<section class="page-header">
	<div class="container">
	<nav aria-label="breadcrumb">
		<h1 class="page-name">รายละเอียด</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item" aria-current="page"><a href="index.php">หน้าแรก</a></li>
            <li class="breadcrumb-item" aria-current="page"><a href="?page=thread">ประชาสัมพันธ์</a></li>
            <li class="breadcrumb-item active" aria-current="page">รายละเอียด</li>
        </ol>
    </nav>
	</div>
</section>
<section class="page-wrapper">
	<div class="container">
		<div class="row">
			<div class="col-md-8">
				<div class="post post-single position-relative">
					<div class="post-thumb">
						<img class="img-responsive" width="100%" src="dashboard/assets/images/thread/<?php echo $thread_image ?>" alt="รูปปก">
					</div>
					<h2 class="post-title"><?php echo $thread_title ?></h2>
					<div class="post-meta">
						<ul>
							<li><i class="tf-ion-ios-calendar"></i> <?php echo $thread_create ?></li>
							<li><i class="tf-ion-android-person"></i> เพยแพร่โดย แอดมิน</li>
							<li><a href="index.php?page=thread&thread_type=<?php echo $thread_type ?>"><i class="tf-ion-ios-pricetags"></i> <?php echo $thread_type_name ?></a></li>
							<!--
							<li><a href="#!"><i class="tf-ion-chatbubbles"></i> 4 COMMENTS</a></li>
							-->
						</ul>
					</div>
					<div class="post-content post-excerpt">
						<?php echo $thread_detail ?>
				    </div>


					<div class="post-comments-form mt-5" id="comment">
					<h3 class="post-sub-heading">ความคิดเห็น</h3>
				    <?php
				    $perpage        = 5;
				    if (!isset($_GET['page_id'])) { 
				        $page_id    = 1;
				        $start      = 0;
				    } 
				    else { 
				        $page_id    = $_GET['page_id'];
				        $start      = ($page_id - 1) * $perpage;
		    		}
		            $limit          = " LIMIT $start, $perpage ";
		            $sql   			= "SELECT * FROM system_comment WHERE (comment_type = 1) AND (comment_link = '$thread_id') AND (comment_direct = 0) ORDER BY comment_id DESC"; 
					$query = mysqli_query($connect, $sql . $limit);
					$empty = mysqli_num_rows($query);
					if ($empty > 0) {
						while ($data = mysqli_fetch_array($query)) { ?>
					        <div class="mb-5">
					            <h6><?php echo $data['comment_name'] ?></h6>
					            <div class="mt-1">
					            	<time class="small"><?php echo datethai($data['comment_create'], 2, $system_lang) ?></time>
					            	<!--
					            	<a class="comment-button ms-5" href="?page=thread_single&thread_id=<?php echo $_GET['thread_id'] ?>&comment_direct=<?php echo $data['comment_id'] ?>"><i class="tf-ion-chatbubbles"></i> ตอบกลับ</a>
					            	-->
					            </div>
					            <p class="mt-3"><?php echo $data['comment_detail'] ?></p>
					        </div>
					        <?php 
					        $query2 = mysqli_query($connect, "SELECT * FROM system_comment WHERE (comment_link = '".$_GET['thread_id']."') AND (comment_direct = '".$data['comment_id']."') ");
					        while ($data2  = mysqli_fetch_array($query2)) { ?>
						        <div class="ms-5 mb-5">
						            <h6>
						            	<i class="bx bx-subdirectory-right me-3"></i>
						            	<?php echo $data2['comment_name'] ?>
						            </h6>
						            <time class="small"><?php echo datethai($data2['comment_create'], 2) ?></time>
						            <p class="mt-3"><?php echo $data2['comment_detail'] ?></p>
						        </div>
					<?php } } } else { ?>
						<div class="mb-5">
					    	<p>ไม่มีความคิดเห็น</p>
				        </div>
					<?php } ?>
					</div>
					
					<?php
		            $query          = mysqli_query($connect, $sql);
		            $total_record   = mysqli_num_rows($query);
		            $total_page     = ceil($total_record / $perpage);
		            $front_n_back   = 3;
		            $first          = $page_id - $front_n_back;
		            $last           = $page_id + $front_n_back;
		            
		            if( $first <= 1)                { $first    = 1;}
		            if( $last >= $total_page )      { $last     = $total_page; }
		            
		            if ($total_record > $perpage) { ?>
		            <div class="d-flex">
		                <div class="mx-auto">
		                <ul class="pagination post-pagination">
		                    <li><a href="?page=thread_single&thread_id=<?php echo $_GET['thread_id'] ?>&page_id=1#comment">&laquo;</a></li>
		                    <?php for ($i = $first; $i <= $last; $i++) { ?>
		                    <li class="<?php if($page_id == $i){echo 'active';} ?>">
		                        <a href="?page=thread_single&thread_id=<?php echo $_GET['thread_id'] ?>&page_id=<?php echo $i; ?>#comment"><?php echo $i; ?></a>
		                    </li>
		                    <?php } 
		                    if ($page_id < $total_page) { ?>
		                        <li><a href="?page=thread_single&thread_id=<?php echo $_GET['thread_id'] ?>&page_id=<?php echo $total_page; ?>#comment">&raquo;</a></li>
		                    <?php } ?>
		                </ul>
		                </div>
		            </div>
		            <?php } if (isset($data_check_login)) { ?>
					<div class="post-comments-form mt-5">
					<h3 class="post-sub-heading">เพิ่มความเห็น</h3>
					<form method="post" action="dashboard/process/setting_comment.php" class="row g-3">
						<input type="hidden" name="comment_buyer" value="<?php echo $buyer_id ?>">
						<input type="hidden" name="comment_link" 	value="<?php echo $_GET['thread_id'] ?>">
						<input type="hidden" name="comment_direct" 	value="<?php echo $comment_direct ?>">
						<input type="hidden" name="comment_type" 	value="1">
						<div class="col-12">
							<input type="text" name="comment_name" value="<?php echo $data_check_login['buyer_name'] ?>" class="form-control" placeholder="ชื่อ *" maxlength="100" readonly>
						</div>
						<div class="col-12">
							<textarea name="comment_detail" class="form-control" rows="6" placeholder="ความคิดเห็น(กรุณาใช้ข้อความที่สุภาพ)" maxlength="400"></textarea>
						</div>
				        <div class="d-flex">
		                    <button name="action" value="insert" class="btn btn-main">ส่งความเห็น</button>
		                </div>
					</form>
					</div>
					<?php } ?>
				</div>
			</div>
			<div class="col-md-4">
			<aside class="sidebar">
				<div class="widget widget-subscription">
					<h4 class="widget-title">รับการแจ้งเตือน</h4>
					<form action="dashboard/process/config_setting.php" method="post">
					  <div class="form-group">
					    <input type="email" class="form-control" placeholder="อีเมลของท่าน" name="noti_email">
					  </div>
					  <button name="action" value="insert_notification" class="btn btn-main">ติดตาม</button>
					</form>
				</div>
			
				<div class="widget widget-category">
					<h4 class="widget-title">หมวดหมู่</h4>
					<ul class="widget-category-list">
						<?php
						$query = mysqli_query($connect, "SELECT * FROM system_thread_type");
						while ($data = mysqli_fetch_array($query)) { ?>
				        	<li><a href="#!"><?php echo $data['thread_type_name'] ?></a></li>
				    	<?php } ?>
				    </ul>
				</div>
				
				<div class="widget widget-latest-post">
					<h4 class="widget-title">ประชาสัมพันธ์ล่าสุด</h4>
					<?php 
					$query = mysqli_query($connect, "SELECT * FROM system_thread WHERE thread_id != '".$_GET['thread_id']."' LIMIT 4");
					while ($data = mysqli_fetch_array($query)) { ?>
						<div class="row media mb-3">
							<div class="col-5">
								<a class="pull-left" href="?page=thread_single&thread_id=<?php echo $data['thread_id'] ?>">
								<img class="media-object" style="height: 100px;object-fit: cover;" src="dashboard/assets/images/thread/<?php echo $data['thread_image'] ?>" alt="รูปปก">
							</a>
							</div>
							
							<div class="col-7 media-body">
								<h4 class="media-heading"><a href="?page=thread_single&thread_id=<?php echo $data['thread_id'] ?>"><?php echo $data['thread_title'] ?></a></h4>
								<p><?php echo datethai($data['thread_create'], 0) ?></p>
							</div>
						</div>
					<?php } ?>
				</div>
			</aside>
		</div>
		</div>
	</div>
</section>