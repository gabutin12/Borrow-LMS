<?php
include '../includes/session.php';

if (isset($_POST['add'])) {
    $course_code = $_POST['course_code'];
    $course_name = $_POST['course_name'];

    // Check if course code already exists
    $check_query = "SELECT * FROM courses WHERE course_code = ?";
    $check_stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($check_stmt, "s", $course_code);
    mysqli_stmt_execute($check_stmt);
    $result = mysqli_stmt_get_result($check_stmt);

    if ($result->num_rows > 0) {
        $_SESSION['error'] = 'Course code already exists';
        header('location: ../list_students.php?error=1&duplicate_title=1');
        exit();
    } else {
        // Insert into database
        $query = "INSERT INTO courses (course_code, course_name) VALUES (?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ss", $course_code, $course_name);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: ../courses.php?success=added");
            exit();
        } else {
            header("Location: ../courses.php?error=1&msg=" . urlencode(mysqli_error($conn)));
            exit();
        }
        // mysqli_stmt_close($stmt);
    }
} else {
    $_SESSION['error'] = 'Fill up add form first';
}

header('location: ../courses.php');
