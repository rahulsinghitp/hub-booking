<?php

require_once('connect.php');
$params = array(
	'connection' => $conn,
	'hub_booking_date' => !empty($_POST['booking_date']) ? date('Y-m-d', strtotime($_POST['booking_date'])) : '',
);

// Hub Booking List
$hub_booking_list = get_hub_booking_list($params);
echo json_encode($hub_booking_list);
