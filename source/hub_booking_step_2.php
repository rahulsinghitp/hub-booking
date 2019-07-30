<?php
	require_once('connect.php');
?>
<!doctype html>
<html lang="en">
	<head>
			<!-- Required meta tags -->
			<meta charset="utf-8">
			<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

			<link href="font/css/all.css" rel="stylesheet"> <!--load all styles -->
			<!-- Bootstrap CSS -->
			<link rel="stylesheet" href="css/bootstrap.min.css">
			<link rel="stylesheet" href="css/custom.css">
			<link rel="stylesheet" type="text/css" href="css/slick.css"/>
			<link rel="stylesheet" type="text/css" href="css/slick-theme.css"/>
			<link rel="stylesheet" type="text/css" href="css/jquery-ui.min.css"/>
			<title>Hello, world!</title>
	</head>
	<body onload="startTimer()">
		<!-- Optional JavaScript -->
		<!-- jQuery first, then Popper.js, then Bootstrap JS -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/bootstrap.bundle.min.js"></script>
		<script type="text/javascript" src="js/slick.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui.min.js"></script>
		<script type="text/javascript" src="js/custom.js"></script>
		<div>Time left = <span id="timer">5:00</span></div>
		<input class="selected-date" readonly>
		<input class="selected-person" readonly>
		<input class="selected-time" readonly>
		<form id="hub-booking-step-2" class="dtp-picker-form otkit" action="booking_status.php"><input type="hidden" name="timezoneOffset" title="timezoneOffset" value="330">
			<input type="textfield" id="first-name" name="first-name" required="true">
			<input type="textfield" id="last-name" name="last-name" required="true">
			<input type="email" id="email" name="email" required="true">
			<input type="textarea" id="purpose" name="purpose">
			<input type="submit" id="book-the-hub" value="Book the Hub" class="button dtp-picker-button">
		</form>
	</body>
</html>
