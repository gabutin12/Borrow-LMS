<?php
include '../includes/session.php';

if (isset($_POST['add'])) {
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $course = mysqli_real_escape_string($conn, $_POST['course']);
    $mobile_no = mysqli_real_escape_string($conn, $_POST['mobile_no']);
    $photo = 'images/students/default.jpg'; // Default image

    // Check if student ID already exists
    $check_query = "SELECT student_id FROM students WHERE student_id = ?";
    $check_stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($check_stmt, "s", $student_id);
    mysqli_stmt_execute($check_stmt);
    $result = mysqli_stmt_get_result($check_stmt);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['error'] = 'Student ID already exists';
        header('location: ../list_students.php?error=1&duplicate=1');
        exit();
    }
    mysqli_stmt_close($check_stmt);

    // Handle photo upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['photo']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $new_filename = $student_id . '.' . $ext;
            $target_dir = "../images/students/";

            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_dir . $new_filename)) {
                $photo = 'images/students/' . $new_filename;
            }
        }
    }

    // Insert student
    $query = "INSERT INTO students (student_id, firstname, lastname, course, mobile_no, photo) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssssss", $student_id, $firstname, $lastname, $course, $mobile_no, $photo);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = 'Student added successfully';
        header('location: ../list_students.php?success=added');
        exit();
    } else {
        $_SESSION['error'] = 'Error adding student: ' . mysqli_error($conn);
        header('location: ../list_students.php');
        exit();
    }
} else {
    $_SESSION['error'] = 'Fill up add form first';
    header('location: ../list_students.php');
}
exit();
