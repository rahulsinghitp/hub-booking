<?php
	session_start();
	require_once('connect.php');
	$readonly = '';
	if (!empty($_SESSION['user_id'])) {
		$user_id = $_SESSION['user_id'];
		$user_details = get_user_detail_by_email_id($conn, $user_id);
		$readonly = 'readonly';
	}

	// If standard values are empty then return to index
	if (empty($_POST['person-select']) || empty($_POST['date']) || empty($_POST['time-slot-select'])) {

		// Redirect user to welcome page
		header("location: index.php");
	}
	var_export($_POST);
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
							print '<a href="' . BASE_URL . 'logout.php" class="btn btn-outline-success my-2 my-sm-0 login">Logout</a>';
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
				<div class="row">
					<div class="col-12 col-md-8 field-area">
						<div class="almost-done">
							<div class="timer-clock">
								<h3 class="block-title">You are almost done!</h3>
								<!--div>Time left = <span id="timer">5:00</span></div-->
							</div>
							<div class="booking-msg"></div>
							<div class="thumbnail-row">
                <div class="hub-image"><img src="img/book.svg" alt="..." class="img-thumbnail"></div>
								<div class="hub-data">
                  <h2 class="hub">Hub</h2>
                  <ul class="data-inputs  ">
                    <li>
                      <i class="fas fa-calendar-week"></i>
                      <span><input class="selected-date" type="text" name="date" value="" disabled></span>
                    </li>
                    <li>
                      <i class="far fa-clock"></i>
                      <span><input class="selected-time" type="text" name="time" value="" disabled></span>
                    </li>
                    <li>
                      <i class="far fa-user"></i>
                      <span><input class="selected-person" type="text" name="person" value="" disabled></span>
                    </li>
									</ul>
                </div>
              </div>
							<form id="hub-booking-step-2" method="post" class="dtp-picker-form otkit" action="hub_booked_status.php">
								<h4>Fill up the <span class="blue">form</span> to book your slot</h4>
								<div class="row">
									<input type="hidden" name="date" value="<?php print $_POST['date']; ?>" disabled>
									<input type="hidden" name="person" value="<?php print $_POST['person-select']; ?>" disabled>
									<input type="hidden" name="time-in-gmt" value="<?php print $_POST['time-slot-select']; ?>" disabled>
									<input id="userid" type="hidden" name="user-id" value="<?php print !empty($_SESSION['user_id']) ? $_SESSION['user_id'] : 0; ?>">
								</div>
								<div class="row">
									<div class="form-group col-6 <?php print $readonly; ?>">
										<input type="textfield" id="first-name" name="first-name" value="<?php print !empty($user_details['firstname']) ? $user_details['firstname'] : ''; ?>" required="required" <?php print $readonly; ?>/>
										<label for="input" class="control-label">First Name</label><i class="bar"></i>
									</div>
									<div class="form-group col-6 <?php print $readonly; ?>">
										<input type="textfield" id="last-name" name="last-name" value="<?php print !empty($user_details['lastname']) ? $user_details['lastname'] : ''; ?>" required="required" <?php print $readonly; ?>/>
										<label for="input" class="control-label">Last Name</label><i class="bar"></i>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-6 <?php print $readonly; ?>">
										<input type="textfield" id="phone-number" value="<?php print !empty($user_details['phone_number']) ? $user_details['phone_number'] : ''; ?>" name="phone-number" required="required" <?php print $readonly; ?>/>
										<label for="input" class="control-label">Phone Number</label><i class="bar"></i>
									</div>
									<div class="form-group col-6 <?php print $readonly; ?>">
										<input type="email" id="email" name="email" value="<?php print !empty($user_details['email']) ? $user_details['email'] : ''; ?>" required="required" <?php print $readonly; ?>/>
										<label for="input" class="control-label">Email</label><i class="bar"></i>
									</div>
								</div>
								<div class="row">
									<div class="form-group col-12">
										<textarea id="purpose" name="purpose"></textarea>
										<label for="textarea" class="control-label">Other Notes</label><i class="bar"></i>
									</div>
								</div>
								<div class="button-container">
									<input type="submit" name="submit" value=" Complete Reservation " class="button">
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