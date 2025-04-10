<?php
include '../includes/session.php';

if (isset($_POST['delete'])) {
    $isbn = mysqli_real_escape_string($conn, $_POST['isbn']);

    if (empty($isbn)) {
        $_SESSION['error'] = 'ISBN is missing';
        header('location: ../list_books.php');
        exit();
    }

    // Check if book exists and get picture path
    $check_query = "SELECT picture FROM books WHERE isbn = ?";
    $check_stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($check_stmt, "s", $isbn);
    mysqli_stmt_execute($check_stmt);
    $result = mysqli_stmt_get_result($check_stmt);

    if ($book = mysqli_fetch_assoc($result)) {
        // Delete the book
        $delete_query = "DELETE FROM books WHERE isbn = ?";
        $delete_stmt = mysqli_prepare($conn, $delete_query);
        mysqli_stmt_bind_param($delete_stmt, "s", $isbn);

        if (mysqli_stmt_execute($delete_stmt)) {
            // Delete book picture if it's not the default
            if ($book['picture'] != 'images/default-book.jpg') {
                @unlink("../" . $book['picture']);
            }
            $_SESSION['success'] = 'Book deleted successfully';
            header('location: ../list_books.php?success=deleted');
        } else {
            $_SESSION['error'] = 'Error deleting book: ' . mysqli_error($conn);
            header('location: ../list_books.php');
        }
        mysqli_stmt_close($delete_stmt);
    } else {
        $_SESSION['error'] = 'Book not found';
        header('location: ../list_books.php');
    }
    mysqli_stmt_close($check_stmt);
} else {
    $_SESSION['error'] = 'Select book to delete first';
    header('location: ../list_books.php');
}
exit();
