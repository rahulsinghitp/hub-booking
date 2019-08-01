<?php

// db credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'webapp');
define('BASE_URL', 'http://192.168.0.81/hub-booking/source/');

if (date_default_timezone_get() != 'Asia/Dubai') {
  date_default_timezone_set('Asia/Dubai');
}

// Connect with the database.
function connect() {
  $connect = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

  if (mysqli_connect_errno($connect)) {
    die("Failed to connect:" . mysqli_connect_error());
  }

  mysqli_set_charset($connect, "utf8");

  return $connect;
}

$conn = connect();

/**
 * Get Equipment List
 */
function get_equipment_list($conn) {
  $query = "SELECT * FROM hub_equipments";
  $result = mysqli_query($conn, $query);
  $equipments = array();
  if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_array($result)) {
      $id = $row['equipment_id'];
      $equipments[$id] = $row;
    }
  }

  return $equipments;
}

/**
 * Get time slots
 */
function get_availiable_time_slots() {
  $duration = 60;  // split by 60 mins
  $start_time = strtotime('00.00');
  $end_time = strtotime('23:00');
  $add_mins  = $duration * 60;
  $time_slots = array();
  while ($start_time <= $end_time) {
    $gmt_time = gmdate('H:i', $start_time);
    $time = date("h:i A", $start_time);
    $time_slots[$gmt_time] = $time;
    $start_time += $add_mins; // to check endtie=me
  }

  return $time_slots;
}

/**
 * Get Hub Booking list depending on Params
 */
function get_hub_booking_list($param = array()) {
  if (empty($param['connection'])) {
    return array();
  }
  $conn = $param['connection'];
  $query = "SELECT * FROM hub_booking";
  if (!empty($param['hub_booking_date'])) {
    $query .= " WHERE hub_booking_date='{$param['hub_booking_date']}'";
  }
  $query .= ';';
  $result = mysqli_query($conn, $query);
  $hub_booking_list = array();
  if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_array($result)) {
      $id = $row['hub_booking_id'];
      $hub_booking_list[$id] = $row;
      $hub_booking_list[$id]['custom_hub_booking_time'] = date('H:i', strtotime($row['hub_booking_time']));
    }
  }

  return $hub_booking_list;
}

/**
 * Get Last Hub booking ID
 */
function get_last_hub_booking_id($conn) {
  $query = "SELECT hub_booking_id FROM hub_booking ORDER BY hub_booking_entry_datetime DESC LIMIT 0, 1";
  if (!empty($param['hub_booking_date'])) {
  }
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_array($result);
  $last_hub_booking_id = isset($row['hub_booking_id']) ? $row['hub_booking_id'] : '';

  return $last_hub_booking_id;
}

/**
 * Get User details for email ID
 */
function get_user_detail_by_user_id($conn, $user_id) {
  $sql = "SELECT user_id, username, password, email, firstname, lastname, phone_number FROM user WHERE user_id='{$user_id}' LIMIT 0, 1";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_array($result);

  return !empty($row['user_id']) ? $row : array();
}

/**
 * Get Unique username depending on Firstname and Lastname
 */
function get_unique_username($connect, $firstname, $lastname, $count = 0) {
  if (empty($firstname) || empty($lastname)) {
    return '';
  }
  $trimmed_firstname = str_replace(' ', '_', strtolower($firstname));
  $trimmed_lastname = str_replace(' ', '_', strtolower($lastname));
  $username = $trimmed_firstname . '.' . $trimmed_lastname;
  $username .= !empty($count) ? '.' . $count : '';
  if (is_username_exists($connect, $username)) {
    $index = $count + 1;
    $username = get_unique_username($connect, $firstname, $lastname, $index);
  }
  else {
    return $username;
  }

  return $username;
}

/**
 * Function to check username already exist or not in system
 */
function is_username_exists($connect, $username) {
  $sql = "SELECT COUNT(*) AS count FROM user WHERE username='{$username}'";
  $result = mysqli_query($connect, $sql);
  $row = mysqli_fetch_array($result);
  return !empty($row['count']) ? true : false;
}


/**
 * function to create a new user
 */
function create_new_user_account($params) {
  $fname = $params['first-name'];
  $lname = $params['last-name'];
  $email = $params['email'];
  $phone_number = $params['phone-number'];
  $connect = $params['connect'];
  $data = array();

  // All required data are present or not
  if (empty($fname) || empty($lname) || empty($email) || empty($phone_number)) {
    $data = array(
      'success' => false,
      'msg' => 'Insufficient data',
    );
    return $data;
  }

  // Check useremail exists or not
  $email_exists = is_useremail_exists($connect, $email);
  if ($email_exists) {
    $data = array(
      'success' => false,
      'msg' => 'Your account is already created by this email ! Please use your existing account to book the Hub',
    );
    return $data;
  }

  // Get unique username
  $username = get_unique_username($connect, $fname, $lname, 0);
  $pass = randomPassword();
  $hash_password = md5($pass);
  $params['username'] = $username;
  $params['password'] = $pass;
  $sql = "INSERT INTO `user` (`username`, `password`, `firstname`, `lastname`, `email`, `phone_number`) VALUES ('{$username}', '{$hash_password}', '{$fname}', '{$lname}', '{$email}', '{$phone_number}');";
  $result = mysqli_query($connect, $sql);
  if ($result) {
    $data = array(
      'success' => true,
      'msg' => 'Your account is created successfully !',
      'username' => $username,
      'password' => $pass,
      'user_id' => mysqli_insert_id($connect)
    );
  }
  else {
    $data = array(
      'success' => false,
      'msg' => 'Unable to create your account',
    );
  }

  return $data;
}

/**
 * Random Password Generator
 */
function randomPassword() {
	$alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
  $pass = array(); //remember to declare $pass as an array
  $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
  for ($i = 0; $i < 8; $i++) {
  	$n = rand(0, $alphaLength);
    $pass[] = $alphabet[$n];
	}

  return implode($pass); //turn the array into a string
}


/**
 * Function to check username already exist or not in system
 */
function is_useremail_exists($connect, $email) {
  $sql = "SELECT COUNT(*) AS count FROM user WHERE email='{$email}'";
  $result = mysqli_query($connect, $sql);
  $row = mysqli_fetch_array($result);
  return !empty($row['count']) ? true : false;
}

/**
 * Function to make an entry Hub Booking details
 */
function create_hub_booking_entry($params) {
  $user_id = isset($params['user_id']) ? $params['user_id'] : '';
  $date = !empty($params['date']) ? date('Y-m-d', strtotime($params['date'])) : '';
  $gmt_time = !empty($params['time-in-gmt']) ? $params['time-in-gmt'] : '';
  $equipment_ids = !empty($params['selected-equipment']) ? $params['selected-equipment'] : '';
  $purpose = !empty($params['purpose']) ? $params['purpose'] : '';
  $no_of_person = !empty($params['person']) ? $params['person'] : '';
  $data = array();
  $conn = $params['connect'];
  if (empty($user_id) || empty($date) || empty($gmt_time)) {
    $data = array(
      'success' => 0,
      'msg' => 'Insufficient data',
    );

    return $data;
  }
  $is_hub_is_booked = is_hub_is_booked($conn, $date, $gmt_time);
  if ($is_hub_is_booked) {
    $data = array(
      'success' => 0,
      'msg' => 'The Hub is Already booked following details',
    );

    return $data;
  }
  $sql = "INSERT INTO `hub_booking` (`hub_booking_date`, `hub_booking_time`, `hub_booking_equipments`, `hub_booking_purpose`, `hub_booking_user_id`, `hub_booking_no_of_persons`) VALUES ('{$date}', '{$gmt_time}', '{$equipment_ids}', '{$purpose}', '{$user_id}', '{$no_of_person}');";
  $result = mysqli_query($conn, $sql);
  if ($result) {

    //	notification_email_for_new_account($params);
    $data['success'] = 1;
    $data['booking_id'] = mysqli_insert_id($conn);
    $data['msg'] = 'Thanks ' . $params['first-name'] . ' ! Your Booking is confirmed';
  }
  else {
    $data['success'] = 0;
    $data['msg'] = 'Sorry! Unable to book the HUB for you';
  }

  return $data;
}

/**
 * Function to check username already exist or not in system
 */
function is_hub_is_booked($connect, $date, $time) {
  $sql = "SELECT COUNT(*) AS count FROM hub_booking WHERE hub_booking_date='{$date}' AND hub_booking_time='{$time}'";
  $result = mysqli_query($connect, $sql);
  $row = mysqli_fetch_array($result);
  return !empty($row['count']) ? true : false;
}
