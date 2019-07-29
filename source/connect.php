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
    $gmt_time = gmdate('h:i A', $start_time);
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
  $result = mysqli_query($conn, $query);
  $hub_booking_list = array();
  if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_array($result)) {
      $id = $row['hub_booking_id'];
      $hub_booking_list[$id] = $row;
    }
  }
}