<?php
	session_start();
	require_once('connect.php');
	$readonly = '';
	if (!empty($_SESSION['user_id'])) {
		$user_id = $_SESSION['user_id'];
		$user_details = get_user_detail_by_email_id($conn, $user_id);
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
		<title>Hub Booking Confirm !</title>
	</head>
	<body onload="startTimer()" class="bg-grey">
		<nav class="navbar navbar-expand-lg   fixed-top">
			<div class="container"><a class="navbar-brand custom-brand" href="<?php print BASE_URL . 'index'; ?>"><img src="img/logo.jpg" /></a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav mr-auto"></ul>
					<form class="form-inline my-2 my-lg-0">
					<?php
						if (!empty($_SESSION['user_id'])) {
							print '<div class="welcome-msg"> Welcome ' . $_SESSION['username'] . ' </div>';
							print '<a href="' . BASE_URL . 'logout.php" class="btn btn-outline-success my-2 my-sm-0 logout">Logout</a>';
						}
						else {
							print '<a href="' . BASE_URL . 'signup.php" class="btn btn-outline-success my-2 my-sm-0 register">Register</a>';
							print '<a href="' . BASE_URL . 'login.php" class="btn btn-outline-success my-2 my-sm-0 login">Login</a>';
						}
					?>
					</form>
				</div>
			</div>
		</nav>
		<section class="form-fields mt-30">
			<div class="container">
				<div class="row booked-status">
					<div class="col-12 col-md-8 field-area">
						Thanks, <?php print $_POST['first-name']; ?> ! Your Hub is Booked for below Details
					</div>
				</div>
				<div class="row">
					<div class="col-12 col-md-8 field-area">
						<div class="almost-done">
							<div class="row thumbnail-row">
								<div class="col-2">
							  	<img src="img/book.svg" alt="..." class="img-thumbnail">
								</div>
								<div class="col-10">
									<h2 class="hub">The Hub</h2>
									<ul class="data-inputs">
								  	<li>
											<i class="fas fa-calendar-week"></i>
											<span><input class="selected-date" type="text" name="date" disabled></span>
											<span><input class="selected-time" type="text" name="time" disabled></span>
										</li>
										<li>
											<i class="fas fa-calendar-week"></i>
											<span><input class="selected-person" type="text" name="person" value="" disabled></span> Person
										</li>
										<li>
										</li>
										<input id="user-id" type="hidden" value="<?php print !empty($_SESSION['user_id']) ? $_SESSION['user_id'] : 0; ?>">
									</ul>
								</div>
							</div>
							</form>
							<div class="new-user-details"></div>
						</div>
					</div>
					<div class="col-12 col-md-4 know-more">
						<div class="wat-t0-know">
							<h3 class="block-title">The Hub Guidelines</h3>
							<ol class="knowmore-text">
								<li>AAA</li>
								<li>AAA</li>
								<li>AAA</li>
							</ol>
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