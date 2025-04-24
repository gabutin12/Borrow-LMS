<?php
require_once '../includes/session.php';
require_once '../db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $admin_id = mysqli_real_escape_string($conn, $_POST['admin_id']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Check if admin exists
    $check_query = "SELECT photo FROM admin WHERE id = ?";
    $check_stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($check_stmt, "i", $admin_id);
    mysqli_stmt_execute($check_stmt);
    $result = mysqli_stmt_get_result($check_stmt);

    if ($admin = mysqli_fetch_assoc($result)) {
        $photo = $admin['photo']; // Keep existing photo by default

        // Check if username already exists for other admins
        $name_check_query = "SELECT id FROM admin WHERE username = ? AND id != ?";
        $name_check_stmt = mysqli_prepare($conn, $name_check_query);
        mysqli_stmt_bind_param($name_check_stmt, "si", $username, $admin_id);
        mysqli_stmt_execute($name_check_stmt);

        if (mysqli_stmt_get_result($name_check_stmt)->num_rows > 0) {
            mysqli_stmt_close($name_check_stmt);
            mysqli_stmt_close($check_stmt);
            header('location: ../admin_management.php?error=1&msg=Username already exists');
            exit();
        }
        mysqli_stmt_close($name_check_stmt);

        // Handle photo upload if provided
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['photo']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            if (in_array($ext, $allowed)) {
                // Delete old photo if it's not the default
                if ($photo != 'images/default.jpg') {
                    @unlink("../" . $photo);
                }

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

        // Update admin information
        $query = "UPDATE admin SET 
                  username = ?, 
                  firstname = ?, 
                  lastname = ?, 
                  email = ?, 
                  photo = ?
                  WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sssssi", $username, $firstname, $lastname, $email, $photo, $admin_id);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            mysqli_stmt_close($check_stmt);
            header('location: ../admin_management.php?success=updated');
            exit();
        } else {
            mysqli_stmt_close($stmt);
            mysqli_stmt_close($check_stmt);
            header('location: ../admin_management.php?error=1&msg=' . urlencode(mysqli_error($conn)));
            exit();
        }
    } else {
        mysqli_stmt_close($check_stmt);
        header('location: ../admin_management.php?error=1&msg=Admin not found');
        exit();
    }
} else {
    header('location: ../admin_management.php');
    exit();
}