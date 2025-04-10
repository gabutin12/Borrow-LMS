<?php
require_once '../db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = isset($_POST['student_id']) ? mysqli_real_escape_string($conn, $_POST['student_id']) : '';

    if (empty($student_id)) {
        header("Location: ../list_students.php?error=invalid&msg=Student ID is missing");
        exit();
    }

    // Check if student exists and get photo path
    $check_query = "SELECT photo FROM students WHERE student_id = ?";
    $check_stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($check_stmt, "s", $student_id);
    mysqli_stmt_execute($check_stmt);
    $result = mysqli_stmt_get_result($check_stmt);

    if ($student = mysqli_fetch_assoc($result)) {
        // Delete the student
        $delete_query = "DELETE FROM students WHERE student_id = ?";
        $delete_stmt = mysqli_prepare($conn, $delete_query);
        mysqli_stmt_bind_param($delete_stmt, "s", $student_id);

        if (mysqli_stmt_execute($delete_stmt)) {
            // Delete photo file if it's not the default
            if ($student['photo'] && $student['photo'] != 'images/default.jpg') {
                $photo_path = "../" . $student['photo'];
                if (file_exists($photo_path)) {
                    @unlink($photo_path);
                }
            }
            mysqli_stmt_close($delete_stmt);
            header("Location: ../list_students.php?success=deleted&msg=Student deleted successfully");
            exit();
        } else {
            mysqli_stmt_close($delete_stmt);
            header("Location: ../list_students.php?error=delete_failed&msg=" . urlencode(mysqli_error($conn)));
            exit();
        }
    } else {
        mysqli_stmt_close($check_stmt);
        header("Location: ../list_students.php?error=not_found&msg=Student not found");
        exit();
    }
}
