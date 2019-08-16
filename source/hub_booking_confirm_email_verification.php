<?php
/**
 * @file
 * To confirm the booking of the Hub
 */
require_once('connect.php');
if (!empty($_GET['token']) && isset($_GET['token'])) {
	$token = $_GET['token'];
	$sql = mysqli_query($conn,"SELECT * FROM hub_booking WHERE hub_booking_confirm_hash = '$token'");
	$row = mysqli_fetch_array($sql);
	if (!empty($row))	{
		if (empty($row['hub_booking_confirmed'])) {
			$result1 = mysqli_query($conn,"UPDATE hub_booking SET hub_booking_confirmed='1' WHERE hub_booking_confirm_hash='$token'");
			$msg = "Your hub booking is confirmed successfully.";
		}
		else {
      $msg ="Hub booking confirmation is already done.";
		}
	}
	else {
    $msg ="Wrong hub booking confirmation token.";
  }
}
?>
<!doctype html>
<html lang="en">
	<head>
		<!-- Required meta tags -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link href="font/css/all.css" rel="stylesheet"> <!--load all styles -->
		<link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
		<!-- Bootstrap CSS -->
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/custom.css">
		<link rel="stylesheet" type="text/css" href="css/slick.css"/>
		<link rel="stylesheet" type="text/css" href="css/slick-theme.css"/>
		<link rel="stylesheet" type="text/css" href="css/jquery-ui.min.css"/>
		<title>The HUB</title>
	</head>
	<body class="verify-sec bg-grey2">
		<nav class="navbar navbar-expand-lg   fixed-top">
			<div class="container">
				<a class="navbar-brand custom-brand" href="<?php print BASE_URL . 'index'; ?>"><img src="img/logo.jpg" /></a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav mr-auto">
					</ul>
					<form class="form-inline my-2 my-lg-0">
					<?php
						if (!empty($_SESSION['user_id'])) {
							$name = get_user_detail_by_user_id($conn, $_SESSION['user_id']);
							$userName = $name['firstname']." ".$name['lastname'];
							print '<div class="welcome-msg"> Welcome ' . $userName . ' </div>';
							print '<a href="' . BASE_URL . 'logout.php" class="btn btn-outline-success my-2 my-sm-0 login">Logout</a>';
						}
						else {
							print '<a href="' . BASE_URL . 'signup.php" class="btn btn-outline-success my-2 my-sm-0 register">Register</a>';
							print '<a href="' . BASE_URL . 'login.php" class="btn btn-outline-success my-2 my-sm-0 login">Login</a>';
						}
					?>
					</form>
				<div>
			</div>
		</nav>
		<section class="hero-area bg-1 text-center">
			<div class=" slickslider-hero">
        <img src="img/bg0.jpg" />
      </div>
        <!-- Container End -->
    </section>
		<section class="verify-msg form-fields mt-30">
			<div class="container">
				<div class="row">
					<div class="col-12 col-md-8 field-area">
						<div class="verify-almost-done wrapper">
							<p><?php echo htmlentities($msg); ?></p>
						</div>
					</div>
				</div>
			</div>
	  </section>
		<!-- Optional JavaScript -->
		<!-- jQuery first, then Popper.js, then Bootstrap JS -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/bootstrap.bundle.min.js"></script>
		<script type="text/javascript" src="js/slick.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui.min.js"></script>
		<script type="text/javascript" src="js/custom.js"></script>
	</body>
</html>
