<?php
include '../includes/session.php';

if (isset($_POST['add'])) {
    $category_name = mysqli_real_escape_string($conn, $_POST['category_name']);

    if (empty($category_name)) {
        $_SESSION['error'] = 'Category name is required';
        header('location: ../category.php');
        exit();
    }

    // Check if category already exists
    $check_query = "SELECT * FROM categories WHERE category_name = ?";
    $check_stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($check_stmt, "s", $category_name);
    mysqli_stmt_execute($check_stmt);
    $result = mysqli_stmt_get_result($check_stmt);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['error'] = 'Category already exists';
    } else {
        // Insert new category
        $query = "INSERT INTO categories (category_name) VALUES (?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $category_name);

        if (mysqli_stmt_execute($stmt)) {
            header('location: ../category.php?success=added');
            exit();
        } else {
            header('location: ../category.php?error=1&msg=' . urlencode(mysqli_error($conn)));
            exit();
        }
        // mysqli_stmt_close($stmt);
    }
    mysqli_stmt_close($check_stmt);
} else {
    $_SESSION['error'] = 'Fill up add form first';
    header('location: ../category.php');
    exit();
}
