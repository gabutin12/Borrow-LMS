<?php
include '../includes/session.php';

if (isset($_POST['edit'])) {
    $old_course_code = mysqli_real_escape_string($conn, $_POST['old_course_code']);
    $new_course_code = mysqli_real_escape_string($conn, $_POST['course_code']);
    $course_name = mysqli_real_escape_string($conn, $_POST['course_name']);

    // Check if new course code already exists (only if course code is being changed)
    if ($old_course_code != $new_course_code) {
        $check_query = "SELECT * FROM courses WHERE course_code = ?";
        $check_stmt = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($check_stmt, "s", $new_course_code);
        mysqli_stmt_execute($check_stmt);
        $result = mysqli_stmt_get_result($check_stmt);

        if (mysqli_num_rows($result) > 0) {
            $_SESSION['error'] = 'Course code already exists';
            header('location: ../courses.php');
            exit();
        }
        mysqli_stmt_close($check_stmt);
    }

    // Check if course exists
    $check_query = "SELECT * FROM courses WHERE course_code = ?";
    $check_stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($check_stmt, "s", $old_course_code);
    mysqli_stmt_execute($check_stmt);
    $result = mysqli_stmt_get_result($check_stmt);

    if (mysqli_fetch_assoc($result)) {
        // Update course information
        $query = "UPDATE courses SET course_code = ?, course_name = ? WHERE course_code = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "sss", $new_course_code, $course_name, $old_course_code);

        if (mysqli_stmt_execute($stmt)) {
            // Also update the course code in students table
            $update_students = "UPDATE students SET course = ? WHERE course = ?";
            $stmt_students = mysqli_prepare($conn, $update_students);
            mysqli_stmt_bind_param($stmt_students, "ss", $new_course_code, $old_course_code);
            mysqli_stmt_execute($stmt_students);
            mysqli_stmt_close($stmt_students);

            mysqli_stmt_close($stmt);
            header("Location: ../courses.php?success=updated");
            exit();
        } else {
            mysqli_stmt_close($stmt);
            header("Location: ../courses.php?error=1&msg=" . urlencode(mysqli_error($conn)));
            exit();
        }
    } else {
        $_SESSION['error'] = 'Course not found';
    }
    mysqli_stmt_close($check_stmt);
} else {
    $_SESSION['error'] = 'Fill up edit form first';
}

header('location: ../courses.php');
exit();
