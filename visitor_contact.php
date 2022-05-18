<?php isset($_GET["status"]) ? alert ($_GET["status"], $_GET["message"]) : false; 


if (isset($data_check_login)) {
	$query = mysqli_query($connect, "SELECT * FROM system_buyer WHERE buyer_id = '$buyer_id' ");
    $data  = mysqli_fetch_array($query);	 
}
$contact_name  = isset($data_check_login) ? $data['buyer_name'] : false;
$contact_email = isset($data_check_login) ? $data['buyer_email'] : false;
$contact_buyer = isset($data_check_login) ? $data['buyer_id'] : 0;


$header_url     = "dashboard/assets/images/bg-themes/1.png";
$header_name    = $l_contact;
$header_detail  = "ช่องทางการติดต่อกับทีมงาน";
include('webpage_asset/include/include_header.php'); 
?>
<section class="page-wrapper">
	<div class="contact-section">
		<div class="container">
			<div class="row">
				<!-- Contact Form -->
				<div class="contact-form col-md-6 " >
					<form id="contact-form" method="post" action="dashboard/process/setting_contact.php" class="row g-3">
						<input type="hidden" name="contact_type" value="2">
						<input type="hidden" name="contact_buyer" value="<?php echo $contact_buyer ?>">
						<div class="col-12">
							<input type="text" placeholder="<?php echo $l_name ?>" class="form-control" name="contact_name" value="<?php echo $contact_name ?>">
						</div>
						<div class="col-12">
							<input type="email" placeholder="<?php echo $l_email ?>" class="form-control" name="contact_email" value="<?php echo $contact_email ?>">
						</div>
						<div class="col-12">
							<input type="text" placeholder="<?php echo $l_title ?>" class="form-control" name="contact_title">
						</div>
						<div class="col-12">
							<textarea rows="6" placeholder="<?php echo $l_detail ?>" class="form-control" name="contact_detail"></textarea>	
						</div>
						<div id="mail-success" class="success">
							ขอบคุณค่ะ ข้อความของท่านถูกบันทึกเข้าสู่ระบบเรียบร้อย Thank you. The Mailman is on His Way :)
						</div>
						<div id="mail-fail" class="error">
							ไม่สามารถบันทึกข้อความเข้าสู่ระบบได้ :(
						</div>
						
						<div id="cf-submit">
							<button id="contact-submit" class="btn btn-transparent" name="action" value="insert"><?php echo $l_submit ?></button>
						</div>						
					</form>
				</div>
				<!-- ./End Contact Form -->
				
				<!-- Contact Details -->
				<!--
				<div class="contact-details col-md-6 " >
					<div class="google-map">
						<div id="map"></div>
					</div>
					<ul class="contact-short-info" >
						<li>
							<i class="tf-ion-ios-home"></i>
							<span>Khaja Road, Bayzid, Chittagong, Bangladesh</span>
						</li>
						<li>
							<i class="tf-ion-android-phone-portrait"></i>
							<span>Phone: +880-31-000-000</span>
						</li>
						<li>
							<i class="tf-ion-android-globe"></i>
							<span>Fax: +880-31-000-000</span>
						</li>
						<li>
							<i class="tf-ion-android-mail"></i>
							<span>Email: hello@example.com</span>
						</li>
					</ul>
					<div class="social-icon">
						<ul>
							<li><a class="fb-icon" href="https://www.facebook.com/themefisher"><i class="tf-ion-social-facebook"></i></a></li>
							<li><a href="https://www.twitter.com/themefisher"><i class="tf-ion-social-twitter"></i></a></li>
							<li><a href="https://themefisher.com/"><i class="tf-ion-social-dribbble-outline"></i></a></li>
							<li><a href="https://themefisher.com/"><i class="tf-ion-social-googleplus-outline"></i></a></li>
							<li><a href="https://themefisher.com/"><i class="tf-ion-social-pinterest-outline"></i></a></li>
						</ul>
					</div>
				</div>
				-->
				<!-- / End Contact Details -->
			</div> <!-- end row -->
		</div> <!-- end container -->
	</div>
</section>