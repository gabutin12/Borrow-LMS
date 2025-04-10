<?php
include '../includes/session.php';
require_once '../db_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $selected_books = isset($_POST['selected_books']) ? $_POST['selected_books'] : [];

    if (empty($student_id)) {
        header('location: ../borrow.php?error=1&msg=Please select a student');
        exit();
    }

    if (empty($selected_books)) {
        header('location: ../borrow.php?error=1&msg=Please select books to borrow');
        exit();
    }

    try {
        mysqli_begin_transaction($conn);

        foreach ($selected_books as $isbn) {
            // Check current stock
            $stock_query = "SELECT stocks FROM books WHERE isbn = ? AND status = 'Available'";
            $stock_stmt = mysqli_prepare($conn, $stock_query);
            mysqli_stmt_bind_param($stock_stmt, "s", $isbn);
            mysqli_stmt_execute($stock_stmt);
            $result = mysqli_stmt_get_result($stock_stmt);
            $book = mysqli_fetch_assoc($result);

            if (!$book || $book['stocks'] <= 0) {
                throw new Exception("Book with ISBN $isbn is not available");
            }

            // Insert into borrowed_books
            $borrow_date = date('Y-m-d');
            $due_date = date('Y-m-d', strtotime('+7 days'));
            $status = 'Borrowed';

            $insert_query = "INSERT INTO borrowed_books (student_id, book_isbn, borrow_date, due_date, status) 
                           VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insert_query);
            mysqli_stmt_bind_param($stmt, "sssss", $student_id, $isbn, $borrow_date, $due_date, $status);
            mysqli_stmt_execute($stmt);

            // Decrease book stock
            $update_query = "UPDATE books 
                           SET stocks = stocks - 1,
                               status = CASE 
                                   WHEN stocks - 1 = 0 THEN 'Not Available'
                                   ELSE 'Available'
                               END
                           WHERE isbn = ? AND stocks > 0";
            $update_stmt = mysqli_prepare($conn, $update_query);
            mysqli_stmt_bind_param($update_stmt, "s", $isbn);
            mysqli_stmt_execute($update_stmt);
        }

        mysqli_commit($conn);
        header('location: ../borrow.php?success=borrowed');
        exit();
    } catch (Exception $e) {
        mysqli_rollback($conn);
        header('location: ../borrow.php?error=1&msg=' . urlencode($e->getMessage()));
        exit();
    }
} else {
    header('location: ../borrow.php?error=1&msg=Invalid request');
    exit();
}
