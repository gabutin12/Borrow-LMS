<?php
include '../includes/session.php';

if (isset($_POST['category_id'])) {
    $category_id = mysqli_real_escape_string($conn, $_POST['category_id']);

    if (empty($category_id)) {
        echo json_encode(['error' => 'Category ID is missing']);
        exit();
    }

    // Check if category exists
    $check_query = "SELECT id FROM categories WHERE id = ?";
    $check_stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($check_stmt, "i", $category_id);
    mysqli_stmt_execute($check_stmt);
    $result = mysqli_stmt_get_result($check_stmt);

    if (mysqli_num_rows($result) > 0) {
        // Delete the category
        $delete_query = "DELETE FROM categories WHERE id = ?";
        $delete_stmt = mysqli_prepare($conn, $delete_query);
        mysqli_stmt_bind_param($delete_stmt, "i", $category_id);

        if (mysqli_stmt_execute($delete_stmt)) {
            $_SESSION['success'] = 'Category deleted successfully';
            header('location: ../category.php?success=deleted');
        } else {
            $_SESSION['error'] = 'Error deleting category: ' . mysqli_error($conn);
            header('location: ../category.php');
        }
        mysqli_stmt_close($delete_stmt);
    } else {
        $_SESSION['error'] = 'Category not found';
        header('location: ../category.php');
    }
    mysqli_stmt_close($check_stmt);
} else {
    $_SESSION['error'] = 'Category ID not provided';
    header('location: ../category.php');
}
exit();
