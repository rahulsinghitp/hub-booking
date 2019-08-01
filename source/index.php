<?php
	session_start();
	require_once('connect.php');
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
	<body class="bg-grey2">
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
							print '<div class="welcome-msg"> Welcome ' . $_SESSION['username'] . ' </div>';
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
        <img src="img/bg0.jpg" />
        <img src="img/bg0.jpg" />
        <img src="img/bg0.jpg" />
        <img src="img/bg0.jpg" />
      </div>
        <!-- Container End -->
    </section>
		<section class="hero-area  text-center slider-data" style="/* margin: 0 auto; */">
			<!-- Container Start -->
			<div class="container slider-container" >
				<div class="row select-option">
					<div class="col-md-12">
						<!-- Header Contetnt -->
						<div id="dtp-picker-4" data-event-prefix="" class="dtp-picker dtp-lang-en  with-search single-search  initialised">
							<form id="hub-booking-step-1" method="post" class="dtp-picker-form otkit" action="hub_booking_confirm.php"><input type="hidden" name="timezoneOffset"												title="timezoneOffset" value="330">
								<div class="dtp-picker-selectors-container">
									<div class="party-size-picker dtp-picker-selector select-native unselected-on-init people"> <a
										class="select-label dtp-picker-selector-link selected-person" tabindex="-1"> 1 Person </a>
										<select id="person-select" name="person-select" aria-label="party size" onchange="changeSelectedPerson()">
										<?php
											for ($i = 1; $i < 2; $i++) {
												print '<option value="' . $i . '">' . $i . ' Person</option>';
											}
										?>
										</select>
									</div>
									<div class="date-picker dtp-picker-selector">
										<input name="date" class="dtp-picker-selector-link date-label dtp-picker-label" type="text" id="datepicker" placeholder="Select a date">
									</div>
									<div class="time-picker dtp-picker-selector select-native unselected-on-init time">
										<a class="selected-time select-label dtp-picker-selector-link" tabindex="-1"> Select </a>
										<select required="required" id="time-slot-select" name="time-slot-select" aria-label="party size" onchange="changeSelectedTime()">
										<?php
											$time_slots = get_availiable_time_slots();
											foreach ($time_slots as $gmt_time => $time) {
												print '<option value="' . $gmt_time . '">' . $time . '</option>';
											}
										?>
										</select>
									</div>
								</div>
								<input type="submit" value=" " class="button dtp-picker-button">
							</form>
						</div>
					</div>
				</div>
				<div class="row slider-option">
					<div class="col-md-12">
						<h3>Select the equipment you require for your visit (if any)</h3>
						<!--p><span>Total 16 results</span> <span class="text-right">Total 16 results</span></p-->
					</div>
					<div class="col-md-12">
						<div class="slider">
						<?php
							$equipments = get_equipment_list($conn);
							foreach ($equipments as $equipment) {
								$equipment_desc = !empty($equipment['equipment_name']) ? $equipment['equipment_name'] : '';
								$id = $equipment['equipment_id'];
								$checkbox_id = "r{$id}";
								print '<div class="parent-slider"><label class="child-slide" for="' . $checkbox_id . '">';
								print '<div class="color"><img src="./equipment_images/' . $equipment['equipment_image_name'] . '" /></div>';
								print '<p class="slide-data">' . $equipment_desc . '</p></label>';
								print	'<input type="checkbox" name="rGroup" value="' . $id . '" id="' . $checkbox_id . '" /></div>';
							}
						?>
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