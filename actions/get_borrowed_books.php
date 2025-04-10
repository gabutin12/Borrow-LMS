<?php
include '../includes/session.php';
require_once '../db_connection.php';

if (isset($_POST['student_id'])) {
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);

    $query = "SELECT bb.id, bb.book_isbn as isbn, b.title, 
              DATE_FORMAT(bb.borrow_date, '%M %d, %Y') as borrow_date,
              DATE_FORMAT(bb.due_date, '%M %d, %Y') as due_date
              FROM borrowed_books bb
              INNER JOIN books b ON bb.book_isbn = b.isbn
              WHERE bb.student_id = ? AND bb.status = 'Borrowed'
              ORDER BY bb.borrow_date DESC";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $student_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $books = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $books[] = $row;
    }

    echo json_encode([
        'success' => true,
        'data' => $books
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'No student ID provided'
    ]);
}
