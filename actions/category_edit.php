<?php
include '../includes/session.php';

if (isset($_POST['edit'])) {
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);
    $category_name = mysqli_real_escape_string($conn, $_POST['category_name']);

    // Validate input
    if (empty($category_name)) {
        header('location: ../category.php?error=1&msg=Category name is required');
        exit();
    }

    // Check if category exists
    $check_query = "SELECT id FROM categories WHERE id = ?";
    $check_stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($check_stmt, "i", $category_id);
    mysqli_stmt_execute($check_stmt);
    $result = mysqli_stmt_get_result($check_stmt);

    if ($result->num_rows > 0) {
        // Check if new name already exists for other categories
        $name_check_query = "SELECT id FROM categories WHERE category_name = ? AND id != ?";
        $name_check_stmt = mysqli_prepare($conn, $name_check_query);
        mysqli_stmt_bind_param($name_check_stmt, "si", $category_name, $category_id);
        mysqli_stmt_execute($name_check_stmt);

        if (mysqli_stmt_get_result($name_check_stmt)->num_rows > 0) {
            header('location: ../category.php?error=1&msg=Category name already exists');
            exit();
        }
        mysqli_stmt_close($name_check_stmt);

        // Update category
        $update_query = "UPDATE categories SET category_name = ? WHERE id = ?";
        $update_stmt = mysqli_prepare($conn, $update_query);
        mysqli_stmt_bind_param($update_stmt, "si", $category_name, $category_id);

        if (mysqli_stmt_execute($update_stmt)) {
            $_SESSION['success'] = 'Category updated successfully';
            header('location: ../category.php?success=updated');
            exit();
        } else {
            header('location: ../category.php?error=1&msg=' . urlencode(mysqli_error($conn)));
            exit();
        }
    } else {
        header('location: ../category.php?error=1&msg=Category not found');
        exit();
    }
} else {
    header('location: ../category.php?error=1&msg=Invalid request');
    exit();
}
