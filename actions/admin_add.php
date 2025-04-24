<?php
require_once '../includes/session.php';
require_once '../db_connection.php';

if (isset($_POST['add'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Check if username already exists
    $check_query = "SELECT id FROM admin WHERE username = ?";
    $check_stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($check_stmt, "s", $username);
    mysqli_stmt_execute($check_stmt);
    $result = mysqli_stmt_get_result($check_stmt);

    if ($result->num_rows > 0) {
        header('location: ../admin_management.php?error=1&msg=Username already exists');
        exit();
    }
    mysqli_stmt_close($check_stmt);

    // Handle photo upload
    $photo = 'images/default.jpg';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['photo']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $new_filename = uniqid('admin_') . '.' . $ext;
            $target_dir = "../images/admins/";
            
            // Create directory if it doesn't exist
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            
            $photo = "images/admins/" . $new_filename;
            move_uploaded_file($_FILES["photo"]["tmp_name"], "../" . $photo);
        }
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insert new admin
    $insert_query = "INSERT INTO admin (username, password, firstname, lastname, email, photo) VALUES (?, ?, ?, ?, ?, ?)";
    $insert_stmt = mysqli_prepare($conn, $insert_query);
    mysqli_stmt_bind_param($insert_stmt, "ssssss", $username, $hashed_password, $firstname, $lastname, $email, $photo);

    if (mysqli_stmt_execute($insert_stmt)) {
        mysqli_stmt_close($insert_stmt);
        header('location: ../admin_management.php?success=added');
        exit();
    } else {
        mysqli_stmt_close($insert_stmt);
        header('location: ../admin_management.php?error=1&msg=' . urlencode(mysqli_error($conn)));
        exit();
    }
} else {
    header('location: ../admin_management.php');
    exit();
}