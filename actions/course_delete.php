<?php
include '../includes/session.php';

if (isset($_POST['delete'])) {
    $course_code = isset($_POST['course_code']) ? trim($_POST['course_code']) : '';

    if (empty($course_code)) {
        $_SESSION['error'] = 'Course code is missing';
        header('location: ../courses.php');
        exit();
    }

    try {
        // First check if any students are using this course
        $check_students = "SELECT COUNT(*) as count FROM students WHERE course = ?";
        $stmt_students = mysqli_prepare($conn, $check_students);
        mysqli_stmt_bind_param($stmt_students, "s", $course_code);
        mysqli_stmt_execute($stmt_students);
        $result = mysqli_stmt_get_result($stmt_students);
        $student_count = mysqli_fetch_assoc($result)['count'];

        if ($student_count > 0) {
            $_SESSION['error'] = "Cannot delete course. It is being used by $student_count student(s).";
            mysqli_stmt_close($stmt_students);
            header('location: ../courses.php');
            exit();
        }
        mysqli_stmt_close($stmt_students);

        // If no students are using it, proceed with deletion
        $delete_query = "DELETE FROM courses WHERE course_code = ?";
        $delete_stmt = mysqli_prepare($conn, $delete_query);
        mysqli_stmt_bind_param($delete_stmt, "s", $course_code);

        if (mysqli_stmt_execute($delete_stmt)) {
            header("Location: ../courses.php?success=deleted");
            exit();
        } else {
            header("Location: ../courses.php?error=1&msg=" . urlencode(mysqli_error($conn)));
            exit();
        }
    } catch (Exception $e) {
        $_SESSION['error'] = 'Database error: ' . $e->getMessage();
    }
} else {
    $_SESSION['error'] = 'Invalid request';
}

header('location: ../courses.php');
exit();
