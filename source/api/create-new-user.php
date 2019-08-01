<?php

require_once('../connect.php');

$fname = $_POST['firstname'];
$lname = $_POST['lastname'];
$email = $_POST['email'];
$phone_number = $_POST['phone_number'];
if (empty($fname) || empty($lname) || empty($email) || empty($phone_number)) {
	$data = array();
	$data['msg'] = 'Sufficient data are not present for creating User account';
	$data['success'] = 0;
	echo json_encode($data);
	exit();
}

//
$params = array();
$params = $_POST;
$username = get_unique_username($conn, $fname, $lname, 0);
$pass = randomPassword();
$hash_password = md5($pass);
$params['username'] = $username;
$params['password'] = $pass;

$sql = "INSERT INTO `user` (`username`, `password`, `firstname`, `lastname`, `email`, `phone_number`) VALUES ('{$username}', '{$hash_password}', '{$fname}', '{$lname}', '{$email}', '{$phone_number}');";
$result = mysqli_query($conn, $sql);
$data = array();
if ($result) {

	//notification_email_for_new_account($params);
	session_start();

	// Store data in session variables
	$_SESSION["loggedin"] = true;
	$_SESSION["user_id"] = mysqli_insert_id($conn);
	$_SESSION["username"] = $username;
	$_SESSION["userpass"] = $userpass;
	$data['success'] = 1;
	$data['user_id'] = mysqli_insert_id($conn);
	$data['username'] = $username;
	$data['userpass'] = $pass;
	$data['msg'] = 'User account created Successfully';
}
else {
	$data['success'] = 0;
	$data['msg'] = 'Unable to create user account';
}

echo json_encode($data);

/**
 * Function to send email about new account
 */
function notification_email_for_new_account($params) {
  require_once('../phpmailer/class.phpmailer.php');
	$email = $params['email'];
	$fname = $params['firstname'];
	$lname = $params['lastname'];
	$username = $params['username'];
	$password = $params['password'];

	$message = '<html><head><title>Account Creation Detail</title></head><body>';
	$message .= '<h1>Hi ' . $fname . ' ' . $lname . ' !</h1>';
	$message .= '<p>Your account has been create on HUB</p>';
	$message .= '<p>Below is the Login Credentials of your account</p>';
	$message .= "<p>Username: {$username}</p>";
	$message .= "<p>Password: {$password}</p>";
	$message .= "</body></html>";

	// php mailer code starts
	$mail = new PHPMailer(true);
	$mail->IsSMTP(); // telling the class to use SMTP
	$mail->SMTPDebug = 0;                     // enables SMTP debug information (for testing)
	$mail->SMTPAuth = true;                  // enable SMTP authentication
	$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
	$mail->Host = "smtp.gmail.com";      // sets GMAIL as the SMTP server
	$mail->Port = 465;                   // set the SMTP port for the GMAIL server
	$mail->Username = 'mhatre.sushil20@gmail.com';
	$mail->Password = '';
	$mail->SetFrom('mhatre.sushil20@gmail.com', 'SUSHIL MHATRE');
	$mail->AddAddress($email);
	$mail->Subject = trim("New Account Created");
	$mail->MsgHTML($message);
	try {
		$mail->send();
		$msg = "An email has been sent for verfication.";
		$msgType = "success";
	}
	catch (Exception $ex) {
		$msg = $ex->getMessage();
		$msgType = "warning";
	}
}

