<?php include('process/function.php'); ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="assets/images/etc/<?php echo $logo_icon ?>" />

    <!-- Bootstrap CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/bootstrap-extended.css" rel="stylesheet">
    <link href="assets/css/app.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">

    <!-- ------------------ Font ----------------------- -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kanit&display=swap" rel="stylesheet">

    <!-- ------------------ JS ----------------------- -->
    <script src="assets/js/jquery-3.6.0.min.js"></script>

	<!-- Styles -->
	<style>
        body {
            background: url("assets/images/etc/bg-forgot-password.jpg");
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;

            font-family: 'Kanit', sans-serif;
            font-size: 15px;
        }
	</style>
    <title>Login</title>
</head>
<body>

<div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-sm-0">
<div class="container">
    <div class="row">
        <div class="col-12 col-md-8 col-lg-4 mx-auto">
            <?php if (isset($_GET['status'])) { ?>
                <div class="alert alert-warning border-0 bg-warning alert-dismissible fade show py-2 mb-3">
                    <div class="d-flex align-items-center">
                        <div class="ms-3">
                            <h6 class="mb-1 text-dark">Not Login</h6>
                            <div>Username or Password are wrong.</div>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php } ?>
            <div class="card">
                <div class="card-body">
                    <div class="p-4 rounded">
                        <h5 class="mb-5">Admin's Login</h5>
                        <div class="form-body">
                            <form class="row g-3" action="process/setting_config.php" method="post">
                                <div class="col-12">
                                    <label class="form-label">username</label>
                                    <input type="text" name="admin_user" class="form-control" placeholder="username" require>
                                </div>
                                <div class="col-12">
                                    <label for="inputChoosePassword" class="form-label">password</label>
                                    <div class="input-group" id="show_hide_password">
                                        <input type="password" name="admin_pass" class="form-control border-end-0" id="inputChoosePassword" placeholder="password" require> <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
                                    </div>
                                </div>
                                <div class="col-12 mt-3">
                                    <div class="d-grid">
                                        <button type="submit" name="action" value="admin_login" class="btn btn-primary"><i class="bx bxs-lock-open"></i>Login</button>
                                        <?php if ($system_webpage == 1) { ?>
                                        <a href="../" class="btn btn-white mt-1 border border-2"><i class='bx bx-arrow-back me-1'></i>Home</a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end row-->
</div>
</div>


<!--end wrapper-->
<!--Password show & hide js -->
<script>
    $(document).ready(function () {
        $("#show_hide_password a").on('click', function (event) {
            event.preventDefault();
            if ($('#show_hide_password input').attr("type") == "text") {
                $('#show_hide_password input').attr('type', 'password');
                $('#show_hide_password i').addClass("bx-hide");
                $('#show_hide_password i').removeClass("bx-show");
            } else if ($('#show_hide_password input').attr("type") == "password") {
                $('#show_hide_password input').attr('type', 'text');
                $('#show_hide_password i').removeClass("bx-hide");
                $('#show_hide_password i').addClass("bx-show");
            }
        });
    });
</script>
<!--app JS-->
<script src="assets/js/app.js"></script>
<?php session_write_close();?>

<script src="assets/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>
</html>