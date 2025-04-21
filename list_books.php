<?php
session_start();
$page_title = "List of Books";
require_once 'includes/header.php';
require_once 'includes/navbar.php';
require_once 'db_connection.php';
?>

<div class="wrapper">
    <?php require_once 'includes/sidebar.php'; ?>
    <div class="main-content-books">

        <?php
        if (isset($_GET['success'])) {
            $message = '';
            switch ($_GET['success']) {
                case 'added':
                    $message = 'Book added successfully!';
                    break;
                case 'updated':
                    $message = 'Book updated successfully!';
                    break;
                case 'deleted':
                    $message = 'Book deleted successfully!';
                    break;
            }
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
    ' . $message . '
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
        }
        if (isset($_GET['error'])) {
            $error_msg = 'Operation failed! ' . (isset($_GET['msg']) ? $_GET['msg'] : '');
            if (isset($_GET['duplicate'])) {
                $error_msg = 'ISBN already exists!';
            }
            if (isset($_GET['duplicate_title'])) {
                $error_msg = 'Book title already exists!';
            }
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
    ' . $error_msg . '
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>';
        }
        ?>

        <!-- Header Section -->
        <div class="mb-4">
            <h4 class="mb-3">
                <a href="dashboard.php" class="text-decoration-none text-primary">Dashboard</a>
                <i class="bi bi-chevron-right small"></i>
                List of Books
            </h4>
            <button class="btn btn-primary mb-3">
                <i class="bi bi-plus"></i> New
            </button>
        </div>

        <!-- Books Table -->
        <div class="table-responsive">
            <table id="booksTable" class="table table-bordered table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Index #</th>
                        <th>Picture</th>
                        <th>ISBN</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Copyright</th>
                        <th>Stocks</th>
                        <th>Publisher</th>
                        <th>Status</th>
                        <th>Category</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT * FROM books";
                    $result = mysqli_query($conn, $query);
                    $index = 1;

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            // $statusClass = $row['status'] === 'Available' ? 'bg-success text-white' : 'bg-danger text-white';
                            $statusClass = $row['status'] === 'Available' ? 'text-success fw-bold' : 'text-danger fw-bold';

                            echo "<tr>";
                            echo "<td>" . $index . "</td>";
                            echo "<td><img src='" . htmlspecialchars($row['picture']) . "' class='rounded' width='60' height='70' alt='Book Cover'></td>";
                            echo "<td>" . htmlspecialchars($row['isbn']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['author']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['copyright_year']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['stocks']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['publisher']) . "</td>";
                            // echo "<td class='" . $statusClass . "'>" . htmlspecialchars($row['status']) . "</td>";
                            echo "<td class='$statusClass'>" . htmlspecialchars($row['status']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                            echo "<td>
                                    <button class='btn btn-success btn-sm edit-btn' data-isbn='" . htmlspecialchars($row['isbn']) . "'>
                                        <i class='bi bi-pencil'></i> Edit
                                    </button>
                                    <button class='btn btn-danger btn-sm delete-btn' data-isbn='" . htmlspecialchars($row['isbn']) . "'>
                                        <i class='bi bi-trash'></i> Delete
                                    </button>
                                </td>";
                            echo "</tr>";
                            $index++;
                        }
                    } else {
                        echo "<tr><td colspan='11' class='text-center'>No books found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Add Book Modal -->
    <div class="modal fade" id="addBookModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Book</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addBookForm" method="POST" action="actions/book_add.php" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">ISBN</label>
                            <input type="text" name="isbn" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Author</label>
                            <input type="text" name="author" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Copyright Year</label>
                            <input type="text" name="copyright_year" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Publisher</label>
                            <input type="text" name="publisher" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Stocks</label>
                            <input type="number" name="stocks" class="form-control" required min="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-select" required>
                                <?php
                                $query = "SELECT category_name FROM categories";
                                $result = mysqli_query($conn, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<option value='" . htmlspecialchars($row['category_name']) . "'>" . htmlspecialchars($row['category_name']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Picture</label>
                            <input type="file" name="picture" class="form-control" accept="image/*">
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


    <!-- Edit Book Modal -->
    <div class="modal fade" id="editBookModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Book</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editBookForm" method="POST" action="actions/book_edit.php" enctype="multipart/form-data">
                    <input type="hidden" name="isbn" id="edit_isbn">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" id="edit_title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Author</label>
                            <input type="text" name="author" id="edit_author" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Copyright Year</label>
                            <input type="text" name="copyright_year" id="edit_copyright" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Publisher</label>
                            <input type="text" name="publisher" id="edit_publisher" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Stocks</label>
                            <input type="number" name="stocks" id="edit_stocks" class="form-control" required min="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="category" id="edit_category" class="form-select" required>
                                <?php
                                $query = "SELECT category_name FROM categories";
                                $result = mysqli_query($conn, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<option value='" . htmlspecialchars($row['category_name']) . "'>" . htmlspecialchars($row['category_name']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Picture (optional)</label>
                            <input type="file" name="picture" class="form-control" accept="image/*">
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

    <!-- Delete Book Modal -->
    <div class="modal fade" id="deleteBookModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Book</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="deleteBookForm" method="POST" action="actions/book_delete.php">
                    <input type="hidden" name="isbn" id="delete_isbn">
                    <div class="modal-body">
                        <p>Are you sure you want to delete this book?</p>
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
        // Initialize DataTable
        var table = $('#booksTable').DataTable({
            processing: true,
            pageLength: 25,
            lengthMenu: [25, 50, 75, 100],
            language: {
                lengthMenu: "Show _MENU_ entries",
                search: "Search:",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                paginate: {
                    previous: "Previous",
                    next: "Next"
                },
                emptyTable: "No books found"
            },
            order: [
                [0, 'asc']
            ], // Sort by Index by default
            columnDefs: [{
                    targets: 1, // Picture column
                    orderable: false,
                    searchable: false
                },
                {
                    targets: -1, // Action column (last column)
                    orderable: false,
                    searchable: false
                }
            ],
            dom: '<"top"lf>rt<"bottom"ip>',
            buttons: []
        });

        // Delete button handler
        $(document).on('click', '.delete-btn', function() {
            var isbn = $(this).data('isbn');
            if (isbn) {
                $('#delete_isbn').val(isbn);
                $('#deleteBookModal').modal('show');
            } else {
                alert('Error: Could not determine ISBN');
            }
        });

        // Delete form submit handler
        $('#deleteBookForm').on('submit', function(e) {
            e.preventDefault(); // Prevent default form submission
            var isbn = $('#delete_isbn').val();

            if (!isbn) {
                alert('Error: No ISBN specified');
                return false;
            }

            // Submit form via AJAX
            $.ajax({
                type: 'POST',
                url: 'actions/book_delete.php',
                data: {
                    delete: true,
                    isbn: isbn
                },
                success: function(response) {
                    $('#deleteBookModal').modal('hide');
                    // Remove the row from DataTable
                    table.row($('.delete-btn[data-isbn="' + isbn + '"]').closest('tr'))
                        .remove()
                        .draw(false);

                    // Show success message
                    $('.main-content-books').prepend(
                        '<div class="alert alert-success alert-dismissible fade show" role="alert">' +
                        'Book deleted successfully!' +
                        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                        '</div>'
                    );

                    // Auto-hide alert after 5 seconds
                    $('.alert').delay(5000).fadeOut(500);
                },
                error: function() {
                    alert('Error deleting book');
                }
            });
        });

        // Auto-hide alerts after 5 seconds
        $('.alert').delay(5000).fadeOut(500);

        // Open Add Modal
        $('.btn-primary').click(function() {
            $('#addBookModal').modal('show');
        });

        // Edit button click handler
        $(document).on('click', '.edit-btn', function() {
            var row = $(this).closest('tr');
            var isbn = $(this).data('isbn');
            var title = row.find('td:eq(3)').text().trim();
            var author = row.find('td:eq(4)').text().trim();
            var copyright = row.find('td:eq(5)').text().trim();
            var stocks = row.find('td:eq(6)').text().trim();
            var publisher = row.find('td:eq(7)').text().trim();
            var category = row.find('td:eq(9)').text().trim();

            // Set form values
            $('#edit_isbn').val(isbn);
            $('#edit_title').val(title);
            $('#edit_author').val(author);
            $('#edit_copyright').val(copyright);
            $('#edit_stocks').val(stocks);
            $('#edit_publisher').val(publisher);
            $('#edit_category').val(category);

            // Store original values
            $('#editBookForm').data('original', {
                isbn: isbn,
                title: title,
                author: author,
                copyright: copyright,
                stocks: stocks,
                publisher: publisher,
                category: category
            });

            $('#editBookModal').modal('show');
        });

        // Edit form submit handler
        $('#editBookForm').on('submit', function(e) {
            var requiredFields = $(this).find('[required]');
            var isValid = true;

            requiredFields.each(function() {
                if (!$(this).val().trim()) {
                    isValid = false;
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields');
                return false;
            }
            return true; // Allow form submission if all fields are valid
        });

        // Reset validation on modal close
        $('#editBookModal').on('hide.bs.modal', function() {
            $('#editBookForm').find('.is-invalid').removeClass('is-invalid');
            var original = $('#editBookForm').data('original');
            if (original) {
                $('#edit_isbn').val(original.isbn);
                $('#edit_title').val(original.title);
                $('#edit_author').val(original.author);
                $('#edit_copyright').val(original.copyright);
                $('#edit_stocks').val(original.stocks);
                $('#edit_publisher').val(original.publisher);
                $('#edit_category').val(original.category);
            }
        });

        // Add success message handler
        if ($('.alert-success').length > 0) {
            console.log('Success message found'); // For debugging
        }
    });
</script>
</body>

</html>