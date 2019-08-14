<?php

	// Initialize the session
	session_start();

	// Check if the user is already logged in, if yes then redirect him to welcome page
	if(!empty($_SESSION["loggedin"])){
		header("location: index.php");
		exit();
	}

	// Include config file
	require_once('connect.php');

	// Define variables and initialize with empty values
	$username = $password = "";
	$username_err = $password_err = "";

	// Processing form data when form is submitted
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$trimmed_username = !empty($_POST['username']) ? trim($_POST["username"]) : '';
		$trimmed_password = !empty($_POST['password']) ? trim($_POST["password"]) : '';

		// Check if username is empty
		if(empty($trimmed_username)) {
			$username_err = "Please enter username.";
		}
		else{
			$username = $trimmed_username;
		}

		// Check if password is empty
		if(empty($trimmed_password)){
			$password_err = "Please enter your password.";
		}
		else{
			$password = $trimmed_password;
		}


		// Validate credentials
		if (empty($username_err) && empty($password_err)) {
			$hash_password = md5($trimmed_password);

			// Prepare a select statement
			$sql = "SELECT user_id, username, password, email, is_active FROM user WHERE username='{$trimmed_username}' AND password='{$hash_password}' LIMIT 0, 1";
			$result = mysqli_query($conn, $sql);
			$row = mysqli_fetch_array($result);
			if (!empty($row['user_id'])) {

				if (!empty($row['is_active'])) {
					// Store data in session variables
					$_SESSION["loggedin"] = true;
					$_SESSION["user_id"] = $row['user_id'];
					$_SESSION["username"] = $trimmed_username;

					// Redirect user to welcome page
					header("location: index.php");
				}
				else {

					// Display an error message if Account is not activated
					$password_err = "Your account is not activated yet please activate it from the email which is sent at the time of Registration";
				}
			}
			else {

				// Display an error message if password or username is not valid
				$password_err = "The username or password you entered was not valid. Please enter it again";
			}
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
		<title>HUB Booking Confirm !</title>
	</head>
	<body class="bg-grey">
		<nav class="navbar navbar-expand-lg   fixed-top">
			<div class="container"><a class="navbar-brand custom-brand" href="<?php print BASE_URL . 'index'; ?>"><img src="img/logo.jpg" /></a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav mr-auto"></ul>
				</div>
			</div>
		</nav>
		<section class="form-fields mt-30">
			<div class="container">
				<div class="row">
					<div class="col-12 col-md-8 field-area">
						<div class="almost-done wrapper">
							<h2>Login</h2>
							<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
								<div class="row">
									<div class="form-group col-12">
										<input type="textfield" name="username" class="" required="required" value="<?php echo $username; ?>">
										<label for="input" class="control-label">Username</label><i class="bar"></i>
										<span class="help-block"><?php echo $username_err; ?></span>
									</div>
									<div class="form-group col-12">
										<input type="password" name="password" required="required" class="">
										<label for="input" class="control-label">Password</label><i class="bar"></i>
										<span class="help-block"><?php echo $password_err; ?></span>
									</div>
								</div>
								<div class="button-container">
									<input type="submit" value=" Login " class="btn btn-primary">
								</div>
							</form>
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
