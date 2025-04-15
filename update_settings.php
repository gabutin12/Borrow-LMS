<?php
session_start();
require_once 'db_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fine_amount = floatval($_POST['fineAmount']);
    $max_borrow_days = intval($_POST['maxBorrowDays']);

    $query = "UPDATE system_settings SET 
              fine_amount = ?, 
              max_borrow_days = ? 
              WHERE id = 1";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "di", $fine_amount, $max_borrow_days);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = "Settings updated successfully!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Error updating settings: " . mysqli_error($conn);
        $_SESSION['message_type'] = "danger";
    }

    mysqli_stmt_close($stmt);
    header("Location: settings.php");
    exit();
}
