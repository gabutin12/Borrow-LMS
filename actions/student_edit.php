<?php
require_once '../db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and get form data
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $course = mysqli_real_escape_string($conn, $_POST['course']);
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $mobile_no = mysqli_real_escape_string($conn, $_POST['mobile_no']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);

    // Check if student exists
    $check_query = "SELECT photo FROM students WHERE student_id = ?";
    $check_stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($check_stmt, "s", $student_id);
    mysqli_stmt_execute($check_stmt);
    $result = mysqli_stmt_get_result($check_stmt);

    if ($student = mysqli_fetch_assoc($result)) {
        $photo = $student['photo']; // Keep existing photo by default

        // Handle new photo upload if provided
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['photo']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            if (in_array($ext, $allowed)) {
                // Delete old photo if it's not the default
                if ($photo != 'images/default.jpg') {
                    @unlink("../" . $photo);
                }

                $target_dir = "../images/students/";
                $photo = "images/students/" . $student_id . "." . $ext;
                move_uploaded_file($_FILES["photo"]["tmp_name"], "../" . $photo);
            }
        }

        // Update student information
        $query = "UPDATE students SET 
                  course = ?, 
                  photo = ?, 
                  firstname = ?, 
                  lastname = ?, 
                  mobile_no = ?, 
                  gender = ?
                  WHERE student_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sssssss", $course, $photo, $firstname, $lastname, $mobile_no, $gender, $student_id);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: ../list_students.php?success=updated&msg=Student updated successfully");
        } else {
            header("Location: ../list_students.php?error=update_failed&msg=" . urlencode(mysqli_error($conn)));
        }
        mysqli_stmt_close($stmt);
    } else {
        header("Location: ../list_students.php?error=not_found&msg=Student not found");
    }
    mysqli_stmt_close($check_stmt);
}
