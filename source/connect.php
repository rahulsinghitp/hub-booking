<?php

// db credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'webapp');
define('BASE_URL', 'http://localhost/hub-booking/source/');

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
function get_user_detail_by_email_id($conn, $user_id) {
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