<?php
/*
 * @author Shahrukh Khan
 * @website http://www.thesoftwareguy.in
 * @facebbok https://www.facebook.com/Thesoftwareguy7
 * @twitter https://twitter.com/thesoftwareguy7
 * @googleplus https://plus.google.com/+thesoftwareguyIn
 */
require_once('connect.php');
$msg = '';
if (!empty($_POST["fname"]) && !empty($_POST["lname"]) && !empty($_POST["pass1"]) && !empty($_POST["uemail"])) {
  require_once "phpmailer/class.phpmailer.php";
  $fname = trim($_POST["fname"]);
  $lname = trim($_POST["lname"]);
  $pass = trim($_POST["pass1"]);
  $email = trim($_POST["uemail"]);
  $phone_number = trim($_POST['phone_number']);
  $email_exists = is_useremail_exists($conn, $email);
  if (!empty($email_exists)) {
    $msg = "Email already exist";
    $msgType = "warning";
  }
  else {
    $username = get_unique_username($conn, $fname, $lname, 0);
    $password = md5($pass);
    $sql = "INSERT INTO `user` (`username`, `password`, `firstname`, `lastname`, `email`, `phone_number`) VALUES ('{$username}', '{$password}', '{$fname}', '{$lname}', '{$email}', '{$phone_number}');";
    $result = mysqli_query($conn, $sql);
    if ($result) {
      session_start();

      // Store data in session variables
      $_SESSION["loggedin"] = true;
      $_SESSION["user_id"] = mysqli_insert_id($conn);
      $_SESSION["username"] = $username;

      // Redirect user to welcome page
      header("location: index.php");
    }
    else {
      $msg = "Unable to create user account";
      $msgType = "warning";
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
      <?php if ($msg != "") { ?>
        <div class="alert alert-dismissable alert-<?php echo $msgType; ?>">
          <button data-dismiss="alert" class="close" type="button">x</button>
          <p><?php echo $msg; ?></p>
        </div>
      <?php } ?>
      <div class="container">
        <div class="row">
          <div class="col-12 col-md-8 field-area">
            <div class="almost-done wrapper">
              <form class="form-horizontal contactform" action="signup.php" method="post" name="f" onsubmit="return validateForm();">
                <div class="row">
                  <div class="form-group col-6">
                    <input type="textfield" required="required" id="fname" class="" name="fname">
                    <label for="input" class="control-label">First Name</label><i class="bar"></i>
                  </div>
                  <div class="form-group col-6">
                    <input type="textfield" required="required" id="lname" class="" name="lname">
                    <label for="input" class="control-label">Last Name</label><i class="bar"></i>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-6">
                    <input type="textfield" required="required" id="uemail" class="" name="uemail">
                    <label for="input" class="control-label">Email</label><i class="bar"></i>
                  </div>
                  <div class="form-group col-6">
                    <input type="textfield" required="required" id="phone-number" class="" name="phone_number">
                    <label for="input" class="control-label">Phone Number</label><i class="bar"></i>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-6">
                    <input type="password" required="required" id="pass1" class="" name="pass1">
                    <label for="input" class="control-label">Password</label><i class="bar"></i>
                  </div>
                  <div class="form-group col-6">
                    <input type="password" required="required" id="pass2" class="" name="pass2">
                    <label for="input" class="control-label">Confirm Password</label><i class="bar"></i>
                  </div>
                </div>
                <div class="button-container">
                  <input type="submit" value=" Signup " class="btn btn-primary">
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
    <script type="text/javascript">
      function validateForm() {

        var FName = $.trim($("#fname").val());
        var LName = $.trim($("#lname").val());
        var your_email = $.trim($("#uemail").val());
        var pass1 = $.trim($("#pass1").val());
        var pass2 = $.trim($("#pass2").val());


        // validate First name
        if (FName.length < 3) {
          alert("First Name must be atleast 3 character.");
          $("#fname").focus();
          return false;
        }

        // validate First name
        if (LName.length < 3) {
          alert("Last Name must be atleast 3 character.");
          $("#lname").focus();
          return false;
        }

        // validate email
        if (!isValidEmail(your_email)) {
          alert("Enter valid email.");
          $("#uemail").focus();
          return false;
        }

        // validate subject
        if (pass1 == "") {
          alert("Enter password");
          $("#pass1").focus();
          return false;
        } else if (pass1.length < 6) {
          alert("Password must be atleast 6 character.");
          $("#pass1").focus();
          return false;
        }

        if (pass1 != pass2) {
          alert("Password does not matched.");
          $("#pass2").focus();
          return false;
        }

        return true;
      }

      function isValidEmail(email) {
        var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
      }
    </script>

	</body>
</html>