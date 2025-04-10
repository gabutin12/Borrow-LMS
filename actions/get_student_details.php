<?php
include '../includes/session.php';
require_once '../db_connection.php';

if (isset($_POST['student_id'])) {
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $query = "SELECT student_id, firstname, lastname, course, photo FROM students WHERE student_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $student_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($student = mysqli_fetch_assoc($result)) {
        echo json_encode([
            'success' => true,
            'data' => $student
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Student not found'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'No student ID provided'
    ]);
}
