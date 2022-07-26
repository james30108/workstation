<?php include('process/function.php');

<<<<<<< HEAD
$page_type= basename($_SERVER['PHP_SELF']);
$lang       = isset($_GET["lang"]) ? $_GET["lang"] : 0;
=======
$page_type  = basename($_SERVER['PHP_SELF']);
$lang 		= isset($_GET["lang"]) ? $_GET["lang"] : 0;
>>>>>>> 8ca2fae388e0e8441783a4e260f7bed51ac2e5b1
include("process/include_lang.php");

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<?php if($system_mobile == 1) { ?>
		<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php } ?>
	<!--favicon-->
	<link rel="icon" href="assets/images/etc/<?php echo $logo_icon ?>" />

	<!-- loader-->
	<link href="assets/css/pace.min.css" rel="stylesheet" />
	<script src="assets/js/pace.min.js"></script>
	<!-- Bootstrap CSS -->
	<link href="assets/css/bootstrap.min.css" rel="stylesheet">
	<link href="assets/css/bootstrap-extended.css" rel="stylesheet">
	<link href="assets/css/app.css" rel="stylesheet">
	<link href="assets/css/icons.css" rel="stylesheet">
	<!-- Theme Style CSS -->
	<link rel="stylesheet" href="assets/css/dark-theme.css" />
	<link rel="stylesheet" href="assets/css/semi-dark.css" />
	<link rel="stylesheet" href="assets/css/header-colors.css" />

	<!-- ------------------ Font ----------------------- -->
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">

	<!-- ------------------ JS ----------------------- -->
   <script src="assets/js/jquery-3.6.0.min.js"></script>
   <script src="assets/js/main.js"></script>
	
	<link type="text/css" href="editor.css" rel="stylesheet"/>

  <!-- Styles -->
  <style>
    body {
      font-family: 'Kanit', sans-serif;
      font-size: 15px;
    }
  </style>
</head>
<body>
	
	<div class="mt-5">
		<?php include('all_insert_member.php') ?>
	</div>

	<!-- Bootstrap JS -->
	<script src="assets/js/bootstrap.bundle.min.js"></script>
	<!--plugins-->
	<script src="assets/js/jquery.min.js"></script>
	<!--app JS-->
	<script src="assets/js/app.js"></script>
</body>
</html>