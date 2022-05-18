<style type="text/css">
    .page-header {
        background-image: url('dashboard/assets/images/bg-themes/1.png');
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-position: center; 
    }
</style>
<title><?php echo $l_thread ?></title>
<section class="page-header">
    <div class="container">
    <h3 class="page-name text-white"><?php echo $l_thread ?></h3>
    <p class="text-white">ข่าวสารและการประชาสัมพันธ์ต่างๆ</p>
    </div>
</section>
<div class="page-wrapper">
<div class="container">
	<div class="row">
		<div class="col-md-8">
			<?php
			$perpage        = 20;
		    if (!isset($_GET['page_id'])) { 
		        $page_id    = 1;
		        $start      = 0;
		    } 
		    else { 
		        $page_id    = $_GET['page_id'];
		        $start      = ($page_id - 1) * $perpage;
		    }
		    $limit          = " LIMIT $start, $perpage ";

		    $thread_type  	= isset($_GET['thread_type']) ? $_GET['thread_type'] : false;

		    $where = $thread_type != false ? " WHERE thread_type = '$thread_type' ": false;

			$sql   	  = "SELECT system_thread.*, system_thread_type.*
				FROM system_thread
				INNER JOIN system_thread_type ON (system_thread.thread_type = system_thread_type.thread_type_id)" . $where;
			$order_by = " ORDER BY thread_id DESC ";
			$query 	  = mysqli_query($connect, $sql . $order_by . $limit);
			while ($data = mysqli_fetch_array($query)) {

				$thread_id 			= $data['thread_id'];
				$thread_image 		= $data['thread_image'];
				$thread_create 		= datethai($data['thread_create'], 0, $system_lang);
				$thread_type_name 	= $data['thread_type_name'];
				$thread_intro 		= $data['thread_intro'];
				$thread_title 		= $data['thread_title'];

				?>
	    		<div class="post">
				<div class="post-media post-thumb">
					<a href="?page=thread_single&thread_id=<?php echo $thread_id ?>">
						<img src="dashboard/assets/images/thread/<?php echo $data['thread_image'] ?>" style="height: 300px;object-fit: cover;" alt="Cover">
					</a>
				</div>
				<h2 class="post-title"><a href="?page=thread_single&thread_id=<?php echo $thread_id ?>"><?php echo $thread_title ?></a></h2>
				<div class="post-meta">
					<ul>
						<li><i class="tf-ion-ios-calendar"></i> <?php echo $thread_create ?></li>
						<li><i class="tf-ion-android-person"></i> เผยแพร่โดย แอดมิน</li>
						<li><a href="#!"><i class="tf-ion-ios-pricetags"></i> <?php echo $thread_type_name ?></a></li>
						<!--
						<li><a href="#!"><i class="tf-ion-chatbubbles"></i> 4 COMMENTS</a></li>
						-->
					</ul>
				</div>
				<div class="post-content">
					<p><?php echo $data['thread_intro'] ?></p>
					<a href="?page=thread_single&thread_id=<?php echo $thread_id ?>" class="btn btn-main"><?php echo $l_detail ?></a>
				</div>
				</div>
			<?php }
			$url = "?page=thread"; 
       	 	pagination_visitor ($connect, $sql, $perpage, $page_id, $url); ?>
      	</div>
      	<div class="col-md-4 mt-5 mt-sm-0">
			<aside class="sidebar">
				<div class="widget widget-subscription">
					<h4 class="widget-title"><?php echo $l_follow_text ?></h4>
					<form action="dashboard/process/config_setting.php" method="post">
					  <div class="form-group">
					    <input type="email" class="form-control" placeholder="Your Email" name="noti_email">
					  </div>
					  <button name="action" value="insert_notification" class="btn btn-main"><?php echo $l_follow ?></button>
					</form>
				</div>
			
				<div class="widget widget-category">
					<h4 class="widget-title"><?php echo $l_type ?></h4>
					<ul class="widget-category-list">
						<?php
						$query = mysqli_query($connect, "SELECT * FROM system_thread_type");
						while ($data = mysqli_fetch_array($query)) { 

							$thread_type_id   = $data['thread_type_id'];
							$thread_type_name = $data['thread_type_name'];
							?>

				        	<li><a href="index.php?page=thread&thread_type=<?php echo $thread_type_id ?>"><?php echo $thread_type_name ?></a></li>

				    	<?php } ?>
				    </ul>
				</div>
				
				<div class="widget widget-latest-post">
					<h4 class="widget-title"><?php echo $l_thread_newer ?></h4>
					<?php 
					$query = mysqli_query($connect, "SELECT * FROM system_thread ORDER BY thread_id DESC LIMIT 4");
					while ($data = mysqli_fetch_array($query)) { 

						$thread_id   	= $data['thread_id'];
						$thread_image   = $data['thread_image'];
						$thread_title   = mb_strimwidth($data['thread_title'], 0, 60, "...");
						$thread_create  = datethai($data['thread_create'], 0, $system_lang);
						?>
						<div class="row media mb-3">
							<div class="col-5">
								<a class="pull-left" href="?page=thread_single&thread_id=<?php echo $thread_id ?>">
								<img class="media-object" style="height: 100px;object-fit: cover;" src="dashboard/assets/images/thread/<?php echo $thread_image ?>" alt="Image Cover">
							</a>
							</div>
							<div class="col-7 media-body">
								<h4 class="media-heading"><a href="?page=thread_single&thread_id=<?php echo $thread_id ?>">
									<?php echo $thread_title ?></a>
								</h4>
								<p><?php echo $thread_create ?></p>
							</div>
						</div>
					<?php } ?>
				</div>
			</aside>
		</div>
	</div>
</div>
</div>