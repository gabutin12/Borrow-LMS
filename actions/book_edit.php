<?php
include '../includes/session.php';

if (isset($_POST['edit'])) {
    $isbn = mysqli_real_escape_string($conn, $_POST['isbn']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $copyright_year = mysqli_real_escape_string($conn, $_POST['copyright_year']);
    $stocks = mysqli_real_escape_string($conn, $_POST['stocks']);
    $publisher = mysqli_real_escape_string($conn, $_POST['publisher']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $status = ($stocks > 0) ? 'Available' : 'Not Available';

    // Validate required fields
    if (empty($isbn) || empty($title) || empty($author)) {
        header('location: ../list_books.php?error=1&msg=Missing required fields');
        exit();
    }

    // Check if book exists
    $check_query = "SELECT picture FROM books WHERE isbn = ?";
    $check_stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($check_stmt, "s", $isbn);
    mysqli_stmt_execute($check_stmt);
    $result = mysqli_stmt_get_result($check_stmt);

    if ($book = mysqli_fetch_assoc($result)) {
        $picture = $book['picture']; // Keep existing picture by default

        // Handle new picture upload if provided
        if (isset($_FILES['picture']) && $_FILES['picture']['error'] == 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $filename = $_FILES['picture']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            if (in_array($ext, $allowed)) {
                // Delete old picture if it's not the default
                if ($picture != 'images/books/default.jpg') {
                    @unlink("../" . $picture);
                }

                $new_filename = $isbn . '.' . $ext;
                $target_dir = "../images/books/";

                // Create directory if it doesn't exist
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }

                if (move_uploaded_file($_FILES['picture']['tmp_name'], $target_dir . $new_filename)) {
                    $picture = 'images/books/' . $new_filename;
                }
            }
        }

        // Update book information
        $update_query = "UPDATE books SET 
            title = ?, 
            author = ?, 
            copyright_year = ?, 
            stocks = ?, 
            publisher = ?, 
            category = ?, 
            status = ?, 
            picture = ? 
            WHERE isbn = ?";

        $update_stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param(
            $update_stmt,
            "sssisssss",
            $title,
            $author,
            $copyright_year,
            $stocks,
            $publisher,
            $category,
            $status,
            $picture,
            $isbn
        );

        if (mysqli_stmt_execute($update_stmt)) {
            $_SESSION['success'] = 'Book updated successfully';
            header('location: ../list_books.php?success=updated');
            exit();
        } else {
            $_SESSION['error'] = mysqli_error($conn);
            header('location: ../list_books.php?error=1&msg=' . urlencode(mysqli_error($conn)));
            exit();
        }
        
    } else {
        header('location: ../list_books.php?error=1&msg=Book not found');
        exit();
    }
    
} else {
    header('location: ../list_books.php?error=1&msg=Invalid request');
    exit();
}
