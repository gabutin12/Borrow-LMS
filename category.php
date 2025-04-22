<?php
session_start();
$page_title = "Category";
require_once 'includes/header.php';
require_once 'includes/navbar.php';
require_once 'db_connection.php';
?>

<div class="wrapper">
    <?php require_once 'includes/sidebar.php'; ?>
    <div class="main-content-category">

        <?php
        if (isset($_GET['success'])) {
            $message = '';
            switch ($_GET['success']) {
                case 'added':
                    $message = 'Category added successfully!';
                    break;
                case 'updated':
                    $message = 'Category updated successfully!';
                    break;
                case 'deleted':
                    $message = 'Category deleted successfully!';
                    break;
            }
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            ' . $message . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
        }
        if (isset($_GET['error'])) {
            $error_msg = 'Operation failed! ' . (isset($_GET['msg']) ? $_GET['msg'] : '');
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            ' . $error_msg . '
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
        }
        ?>

        <!-- Header Section -->
        <div class="mb-4">
            <h6 class="mb-3">
                <a href="dashboard.php" class="text-decoration-none text-primary">Dashboard</a>
                <i class="bi bi-chevron-right small"></i>
                Category
            </h6>
            <button class="btn btn-primary mb-3">
                <i class="bi bi-plus"></i> New Category
            </button>
        </div>

        <!-- Categories Table -->
        <div class="table-responsive">
            <table id="categoriesTable" class="table table-bordered table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Index #</th>
                        <th>Category</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT * FROM categories";
                    $result = mysqli_query($conn, $query);
                    $index = 1;

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . $index . "</td>";
                            echo "<td>" . htmlspecialchars($row['category_name']) . "</td>";
                            echo "<td>
                                    <button class='btn btn-success btn-sm edit-btn' data-id='" . $row['id'] . "'>
                                        <i class='bi bi-pencil'></i> Edit
                                    </button>
                                    <button class='btn btn-danger btn-sm delete-btn' data-id='" . $row['id'] . "'>
                                        <i class='bi bi-trash'></i> Delete
                                    </button>
                                </td>";
                            echo "</tr>";
                            $index++;
                        }
                    } else {
                        echo "<tr><td colspan='3' class='text-center'>No categories found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>


    <!-- Add Category Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addCategoryForm" method="POST" action="actions/category_add.php">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Category Name</label>
                            <input type="text" name="category_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="add" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editCategoryForm" method="POST" action="actions/category_edit.php">
                    <input type="hidden" name="category_id" id="category_edit_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Category Name</label>
                            <input type="text" name="category_name" id="category_edit_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="edit" class="btn btn-success">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Category Modal -->
    <div class="modal fade" id="deleteCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <form id="deleteCategoryForm" method="POST" action="actions/category_delete.php">
                    <input type="hidden" name="category_id" id="category_delete_id">
                    <div class="modal-body">
                        <p>Are you sure you want to delete this category?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        // DataTable initialization (your existing code)
        $('#categoriesTable').DataTable({
            pageLength: 25,
            lengthMenu: [25, 50, 75, 100],
            language: {
                lengthMenu: "Show _MENU_ entries",
                search: "Search:",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                paginate: {
                    previous: "Previous",
                    next: "Next"
                }
            },
            order: [
                [0, 'asc']
            ], // Sort by Index by default
            columnDefs: [{
                targets: 2, // Action column
                orderable: false
            }]
        });

        // Open Add Modal
        $('.btn-primary').click(function() {
            $('#addCategoryModal').modal('show');
        });

        // Open Edit Modal
        $(document).on('click', '.edit-btn', function() {
            var row = $(this).closest('tr');
            var categoryId = $(this).data('id');
            var categoryName = row.find('td:eq(1)').text().trim();

            console.log('Editing category:', categoryId, categoryName); // For debugging

            // Set values in edit form
            $('#category_edit_id').val(categoryId);
            $('#category_edit_name').val(categoryName);

            // Store original values
            $('#editCategoryForm').data('original', {
                category_id: categoryId,
                category_name: categoryName
            });

            $('#editCategoryModal').modal('show');
        });

        // Handle Edit Form Submit
        $('#editCategoryForm').on('submit', function(e) {
            var categoryName = $('#category_edit_name').val().trim();
            var categoryId = $('#category_edit_id').val();

            if (!categoryName) {
                e.preventDefault();
                alert('Please enter a category name');
                return false;
            }

            if (!categoryId) {
                e.preventDefault();
                alert('Category ID is missing');
                return false;
            }

            // Form is valid, let it submit
            return true;
        });

        // Handle Edit Modal Close
        $('#editCategoryModal').on('hidden.bs.modal', function() {
            // Reset form validation
            $('#editCategoryForm').removeClass('was-validated');

            // Reset form to original values if they exist
            var original = $('#editCategoryForm').data('original');
            if (original) {
                $('#category_edit_id').val(original.category_id);
                $('#category_edit_name').val(original.category_name);
            } else {
                $('#editCategoryForm')[0].reset();
            }
        });

        // Open Delete Modal
        $(document).on('click', '.delete-btn', function() {
            var categoryId = $(this).data('id');
            console.log('Deleting category:', categoryId); // For debugging

            if (categoryId) {
                $('#category_delete_id').val(categoryId);
                $('#deleteCategoryModal').modal('show');
            } else {
                alert('Error: Could not determine category ID');
            }
        });

        // Form submit handlers
        $('#addCategoryForm').on('submit', function(e) {
            if (!$('input[name="category_name"]').val().trim()) {
                e.preventDefault();
                alert('Please enter a category name');
                return false;
            }
        });

        $('#deleteCategoryForm').on('submit', function(e) {
            var categoryId = $('#category_delete_id').val();
            if (!categoryId) {
                e.preventDefault();
                alert('Error: No category selected');
                return false;
            }
        });
    });
</script>
</body>

</html>