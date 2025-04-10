<?php
include '../includes/session.php';

if (isset($_POST['add'])) {
    $isbn = mysqli_real_escape_string($conn, $_POST['isbn']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $copyright_year = mysqli_real_escape_string($conn, $_POST['copyright_year']);
    $stocks = mysqli_real_escape_string($conn, $_POST['stocks']);
    $publisher = mysqli_real_escape_string($conn, $_POST['publisher']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $status = ($stocks > 0) ? 'Available' : 'Not Available';
    $picture = 'images/default-book.jpg'; // Default image

    // Handle picture upload
    if (isset($_FILES['picture']) && $_FILES['picture']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['picture']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $new_filename = $isbn . '.' . $ext;
            $target_dir = "../images/books/";

            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            if (move_uploaded_file($_FILES['picture']['tmp_name'], $target_dir . $new_filename)) {
                $picture = 'images/books/' . $new_filename;
            }
        }
    }

    // Check if ISBN exists
    $check_query = "SELECT isbn FROM books WHERE isbn = ?";
    $check_stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($check_stmt, "s", $isbn);
    mysqli_stmt_execute($check_stmt);

    if (mysqli_stmt_get_result($check_stmt)->num_rows > 0) {
        $_SESSION['error'] = 'ISBN already exists';
        header('location: ../list_books.php');
        exit();
    }
    mysqli_stmt_close($check_stmt);

    // Insert book
    $query = "INSERT INTO books (isbn, title, author, copyright_year, stocks, publisher, category, status, picture) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssssissss", $isbn, $title, $author, $copyright_year, $stocks, $publisher, $category, $status, $picture);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = 'Book added successfully';
        header('location: ../list_books.php?success=added');
    } else {
        $_SESSION['error'] = 'Error adding book: ' . mysqli_error($conn);
        header('location: ../list_books.php');
    }
    mysqli_stmt_close($stmt);
} else {
    $_SESSION['error'] = 'Fill up add form first';
    header('location: ../list_books.php');
}
exit();
