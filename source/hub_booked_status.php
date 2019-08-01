<?php
	session_start();
	require_once('connect.php');
	$readonly = '';
	$class = 'success';
	$msg = '';
	$availiable_timeslots = get_availiable_time_slots();
	$is_hub_booked = true;

	// Create New User account
	$params = $_POST;
	$params['connect'] = $conn;
	if (!empty($_SESSION['user_id'])) {
		$user_id = $_SESSION['user_id'];
		$user_details = get_user_detail_by_user_id($conn, $user_id);
	}
	else {

		$user_account_details = create_new_user_account($params);
		$class = !empty($user_account_details['success']) ? 'success' : 'danger';
		$msg = !empty($user_account_details['msg']) ? $user_account_details['msg'] : '';
		$is_user_account_created = $user_account_details['success'];
		if ($is_user_account_created) {

			// Store data in session variables
      $_SESSION["loggedin"] = true;
      $_SESSION["user_id"] = $user_account_details['user_id'];
      $_SESSION["username"] = $user_account_details['username'];
		}
		$user_id = isset($user_account_details['user_id']) ? $user_account_details['user_id'] : '';
	}

	// If User ID exists make the entry of Hub booking
	if (!empty($user_id)) {
		$params['user_id'] = $user_id;
		$hub_booking_details = create_hub_booking_entry($params);
		$class = !empty($hub_booking_details['success']) ? 'success' : 'danger';
		$is_hub_booked = $hub_booking_details['success'];
		$msg = !empty($hub_booking_details['msg']) ? $hub_booking_details['msg'] : '';
		$equipment_list = get_equipment_list($conn);
		$selected_equipment_ids = !empty($_POST['selected-equipment']) ? explode(',', $_POST['selected-equipment']) : array();
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
						<div class="alert alert-<?php print $class; ?>"><?php print $msg; ?></div>
					</div>
				</div>
				<div class="row">
					<div class="col-12 col-md-8 field-area">
						<div class="almost-done">
							<div class="thumbnail-row">
                <div class="hub-image"><img src="img/book.svg" alt="..." class="img-thumbnail"></div>
								<div class="hub-data">
                  <h2 class="hub">Hub</h2>
                  <ul class="data-inputs  ">
										<li>
                      <i class="fas fa-calendar-week"></i>
                      <span><?php print $_POST['date']; ?></span>
                    </li>
                    <li>
                      <i class="far fa-clock"></i>
                      <span><?php print $availiable_timeslots[$_POST['time-in-gmt']]; ?></span>
                    </li>
                    <li>
                      <i class="far fa-user"></i>
                      <span><?php print $_POST['person']; ?> Person</span>
                    </li>
									</ul>
                </div>
              </div>
							<?php if (!empty($selected_equipment_ids)) { ?>
								<div class="row slider-option">
									<div class="col-md-12">
										<h3>Selected equipments for your visit</h3>
									</div>
									<div class="col-md-12">
										<div class="slider">
										<?php
											foreach ($selected_equipment_ids as $id) {
												$equipment_desc = !empty($equipment_list[$id]['equipment_name']) ? $equipment_list[$id]['equipment_name'] : '';
												$checkbox_id = "r{$id}";
												print '<div class="parent-slider"><label class="child-slide" for="' . $checkbox_id . '">';
												print '<div class="color"><img src="./equipment_images/' . $equipment_list[$id]['equipment_image_name'] . '" /></div>';
												print '<p class="slide-data">' . $equipment_desc . '</p></label></div>';
											}
										?>
										</div>
									</div>
								</div>
							<?php } ?>
						</div>
					</div>
					<div class="col-12 col-md-4 know-more">
						<div class="wat-t0-know">
							<h3 class="block-title">
								<?php
									if(!empty($_SESSION['user_id'])) {
										$user_details = get_user_detail_by_user_id($conn, $_SESSION['user_id']);
										print '<span class="username-initials">' . ucfirst(substr($user_details['firstname'], 0, 1)) . '' . ucfirst(substr($user_details['lastname'], 0, 1)) . '</span>';
										print $user_details['firstname'] . ' ' . $user_details['lastname'];
									}
								?>
							</h3>
							<?php
							  if (!empty($is_user_account_created)) {
									print '<div class="alert alert-success">';
									print 'Login Credetials: ';
									print '<div> Username: ' . $user_account_details['username'] . '</div>';
									print '<div> Password: ' . $user_account_details['password'] . '</div>';
									print '</div>';
								}
							?>
							<ul class="knowmore-text">
								<li>AAA</li>
								<li>AAA</li>
								<li>AAA</li>
							</ul>
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