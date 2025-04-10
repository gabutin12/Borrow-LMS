<?php
include '../includes/session.php';

if (isset($_POST['student_id']) && isset($_POST['books'])) {
    $student_id = mysqli_real_escape_string($conn, $_POST['student_id']);
    $books = $_POST['books'];
    $borrow_date = date('Y-m-d');
    $due_date = date('Y-m-d', strtotime('+7 days')); // 7 days from now
    $status = 'Borrowed';

    try {
        // Start transaction
        mysqli_begin_transaction($conn);

        foreach ($books as $isbn) {
            // Check if book is available
            $check_query = "SELECT stocks FROM books WHERE isbn = ? AND status = 'Available' AND stocks > 0";
            $check_stmt = mysqli_prepare($conn, $check_query);
            mysqli_stmt_bind_param($check_stmt, "s", $isbn);
            mysqli_stmt_execute($check_stmt);
            $result = mysqli_stmt_get_result($check_stmt);

            if ($book = mysqli_fetch_assoc($result)) {
                // Insert borrow record
                $borrow_query = "INSERT INTO borrowed_books (student_id, book_isbn, borrow_date, due_date, status) 
                               VALUES (?, ?, ?, ?, ?)";
                $borrow_stmt = mysqli_prepare($conn, $borrow_query);
                mysqli_stmt_bind_param($borrow_stmt, "sssss", $student_id, $isbn, $borrow_date, $due_date, $status);
                mysqli_stmt_execute($borrow_stmt);

                // Update book stocks
                $new_stocks = $book['stocks'] - 1;
                $new_status = $new_stocks > 0 ? 'Available' : 'Not Available';

                $update_query = "UPDATE books SET stocks = ?, status = ? WHERE isbn = ?";
                $update_stmt = mysqli_prepare($conn, $update_query);
                mysqli_stmt_bind_param($update_stmt, "iss", $new_stocks, $new_status, $isbn);
                mysqli_stmt_execute($update_stmt);

                mysqli_stmt_close($borrow_stmt);
                mysqli_stmt_close($update_stmt);
            }
            mysqli_stmt_close($check_stmt);
        }

        // Commit transaction
        mysqli_commit($conn);
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        // Rollback on error
        mysqli_rollback($conn);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
}
