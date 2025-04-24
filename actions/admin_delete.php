<?php
require_once '../includes/session.php';
require_once '../db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $admin_id = mysqli_real_escape_string($conn, $_POST['admin_id']);

    // Check if admin exists
    $check_query = "SELECT photo FROM admin WHERE id = ?";
    $check_stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($check_stmt, "i", $admin_id);
    mysqli_stmt_execute($check_stmt);
    $result = mysqli_stmt_get_result($check_stmt);

    if ($admin = mysqli_fetch_assoc($result)) {
        // Delete admin's photo if it's not the default
        if ($admin['photo'] != 'images/default.jpg') {
            @unlink("../" . $admin['photo']);
        }

        // Delete admin
        $delete_query = "DELETE FROM admin WHERE id = ?";
        $delete_stmt = mysqli_prepare($conn, $delete_query);
        mysqli_stmt_bind_param($delete_stmt, "i", $admin_id);

        if (mysqli_stmt_execute($delete_stmt)) {
            header('location: ../admin_management.php?success=deleted');
            exit();
        } else {
            header('location: ../admin_management.php?error=1&msg=' . urlencode(mysqli_error($conn)));
            exit();
        }
        mysqli_stmt_close($delete_stmt);
    } else {
        header('location: ../admin_management.php?error=1&msg=Admin not found');
        exit();
    }
    mysqli_stmt_close($check_stmt);
} else {
    header('location: ../admin_management.php');
    exit();
}