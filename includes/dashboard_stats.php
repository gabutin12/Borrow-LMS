<?php
function getDashboardStats($conn)
{
    // Get total books count
    $books_query = "SELECT COUNT(*) as total_books FROM books";
    $books_result = mysqli_query($conn, $books_query);
    $total_books = mysqli_fetch_assoc($books_result)['total_books'];

    // Get total students count
    $students_query = "SELECT COUNT(*) as total_students FROM students";
    $students_result = mysqli_query($conn, $students_query);
    $total_students = mysqli_fetch_assoc($students_result)['total_students'];

    // Get returned books count
    $returned_query = "SELECT COUNT(*) as returned_books FROM borrowed_books WHERE status = 'Returned'";
    $returned_result = mysqli_query($conn, $returned_query);
    $returned_books = mysqli_fetch_assoc($returned_result)['returned_books'];

    // Get borrowed books count
    $borrowed_query = "SELECT COUNT(*) as borrowed_books FROM borrowed_books WHERE status = 'Borrowed'";
    $borrowed_result = mysqli_query($conn, $borrowed_query);
    $borrowed_books = mysqli_fetch_assoc($borrowed_result)['borrowed_books'];

    return array(
        'total_books' => $total_books,
        'total_students' => $total_students,
        'returned_books' => $returned_books,
        'borrowed_books' => $borrowed_books
    );
}
