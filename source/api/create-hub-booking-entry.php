<?php

require_once('../connect.php');

$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
$date = !empty($_POST['date']) ? date('Y-m-d', strtotime($_POST['date'])) : '';
$gmt_time = !empty($_POST['time_in_gmt']) ? $_POST['time_in_gmt'] : '';
$equipment_ids = !empty($_POST['eqiupment_ids']) ? $_POST['eqiupment_ids'] : '';
$purpose = !empty($_POST['purpose']) ? $_POST['purpose'] : '';
$no_of_person = !empty($_POST['persons']) ? $_POST['persons'] : '';
if (empty($user_id) || empty($date) || empty($gmt_time)) {
	$data = array(
		'success' => 0,
		'msg' => 'Insufficient data',
	);
	echo json_encode($data);
	exit();
}
$sql = "INSERT INTO `hub_booking` (`hub_booking_date`, `hub_booking_time`, `hub_booking_equipments`, `hub_booking_purpose`, `hub_booking_user_id`, `hub_booking_no_of_persons`) VALUES ('{$date}', '{$gmt_time}', '{$equipment_ids}', '{$purpose}', '{$user_id}', '{$no_of_person}');";
$result = mysqli_query($conn, $sql);
$data = array();
if ($result) {

	//	notification_email_for_new_account($params);
	$data['success'] = 1;
	$data['booking_id'] = mysqli_insert_id($conn);
	$data['msg'] = 'Your Booking is confirmed';

	// Redirect user to welcome page
	//	header("location: index.php");
}
else {
	$data['success'] = 0;
	$data['msg'] = 'Sorry! Unable to book the HUB for you';
}

echo json_encode($data);
