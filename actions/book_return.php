<?php
include '../includes/session.php';
require_once '../db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $return_books = isset($_POST['return_books']) ? $_POST['return_books'] : [];
    $return_date = mysqli_real_escape_string($conn, $_POST['return_date']);

    if (empty($student_id) || empty($return_books) || empty($return_date)) {
        header('location: ../return.php?error=1&msg=Missing required fields');
        exit();
    }

    try {
        mysqli_begin_transaction($conn);

        foreach ($return_books as $borrow_id) {
            // Verify the borrow record
            $check_query = "SELECT bb.*, b.isbn 
                          FROM borrowed_books bb
                          JOIN books b ON bb.book_isbn = b.isbn
                          WHERE bb.id = ? 
                          AND bb.student_id = ? 
                          AND bb.status = 'Borrowed'";

            $check_stmt = mysqli_prepare($conn, $check_query);
            mysqli_stmt_bind_param($check_stmt, "is", $borrow_id, $student_id);
            mysqli_stmt_execute($check_stmt);
            $result = mysqli_stmt_get_result($check_stmt);

            if ($borrowed = mysqli_fetch_assoc($result)) {
                // Update borrowed_books status
                $update_borrow = "UPDATE borrowed_books 
                                SET status = 'Returned', 
                                    return_date = STR_TO_DATE(?, '%m/%d/%Y')
                                WHERE id = ?";

                $stmt_borrow = mysqli_prepare($conn, $update_borrow);
                mysqli_stmt_bind_param($stmt_borrow, "si", $return_date, $borrow_id);
                mysqli_stmt_execute($stmt_borrow);

                // Update book stock
                $update_book = "UPDATE books 
                              SET stocks = stocks + 1,
                                  status = 'Available'
                              WHERE isbn = ?";

                $stmt_book = mysqli_prepare($conn, $update_book);
                mysqli_stmt_bind_param($stmt_book, "s", $borrowed['isbn']);
                mysqli_stmt_execute($stmt_book);
            }
        }

        mysqli_commit($conn);
        header('location: ../return.php?success=returned');
        exit();
    } catch (Exception $e) {
        mysqli_rollback($conn);
        header('location: ../return.php?error=1&msg=' . urlencode($e->getMessage()));
        exit();
    }
} else {
    header('location: ../return.php?error=1&msg=Invalid request');
    exit();
}
