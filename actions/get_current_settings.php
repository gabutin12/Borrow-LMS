<?php
require_once 'db_connection.php';

header('Content-Type: application/json');

$settings_query = "SELECT fine_amount, max_borrow_days FROM system_settings WHERE id = 1";
$settings_result = mysqli_query($conn, $settings_query);
$settings = mysqli_fetch_assoc($settings_result);

echo json_encode($settings);
