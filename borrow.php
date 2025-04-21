<?php
session_start();
$page_title = "Borrow Books";
require_once 'includes/header.php';
require_once './includes/navbar.php';
require_once 'db_connection.php';
?>

<div class="wrapper">
    <?php require_once 'includes/sidebar.php'; ?>
    <div class="main-content-borrow">
        <?php
        if (isset($_GET['success'])) {
            $message = '';
            switch ($_GET['success']) {
                case 'borrowed':
                    $message = 'Books borrowed successfully!';
                    break;
                case 'returned':
                    $message = 'Books returned successfully!';
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
                Borrow Books
            </h6>
            <button class="btn btn-primary mb-3">
                <i class="bi bi-plus"></i> New
            </button>
        </div>

        <!-- Borrow Form -->
        <form method="POST" action="actions/book_borrow.php">
            <!-- Student Selection Section -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Select Student</h5>
                    <div class="row align-items-center">
                        <!-- Student Dropdown -->
                        <div class="col-md-8">
                            <select class="form-select" id="studentSelect" name="student_id" required>
                                <option value="">Select Student</option>
                                <?php
                                $query = "SELECT student_id, firstname, lastname FROM students ORDER BY lastname, firstname";
                                $result = mysqli_query($conn, $query);

                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo '<option value="' . htmlspecialchars($row['student_id']) . '">'
                                        . htmlspecialchars($row['student_id']) . ' - '
                                        . htmlspecialchars($row['lastname']) . ', '
                                        . htmlspecialchars($row['firstname'])
                                        . '</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <!-- View Borrowed Books Logs Button -->
                        <div class="col-md-4 text-end">
                            <a href="borrowed_books_logs.php" class="btn btn-info text-white">
                                <i class="bi bi-journal-text"></i> View Borrowed Book Logs
                            </a>
                        </div>
                    </div>


                </div>
            </div>

            <!-- Student Details Preview -->
            <div id="studentDetails" class="card shadow-sm mb-4" style="display: none;">
                <div class="card-body">
                    <h5 class="card-title mb-3">Student Details</h5>
                    <div class="row">
                        <div class="col-md-3">
                            <img id="studentPhoto" src="images/default.jpg" class="img-fluid rounded shadow-sm" alt="Student Photo" style="width: 400px; height: 400px;">
                        </div>
                        <div class="col-md-9">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="30%">Student ID:</th>
                                    <td id="detailStudentId"></td>
                                </tr>
                                <tr>
                                    <th>Full Name:</th>
                                    <td id="detailFullName"></td>
                                </tr>
                                <tr>
                                    <th>Course:</th>
                                    <td id="detailCourse"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Book Selection Section -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Select Books</h5>
                    <div class="table-responsive">
                        <table id="booksTable" class="table table-bordered table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Select</th>
                                    <th>ISBN</th>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Category</th>
                                    <th>Stocks</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = "SELECT * FROM books WHERE stocks > 0";
                                $result = mysqli_query($conn, $query);

                                while ($row = mysqli_fetch_assoc($result)) {
                                    $statusClass = $row['status'] === 'Available' ? 'text-success fw-bold' : 'text-danger fw-bold';
                                    echo "<tr>";
                                    echo "<td>";
                                    if ($row['status'] === 'Available') {
                                        echo "<input type='checkbox' name='selected_books[]' value='" . htmlspecialchars($row['isbn']) . "'>";
                                    }
                                    echo "</td>";
                                    echo "<td>" . htmlspecialchars($row['isbn']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['author']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['category']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['stocks']) . "</td>";
                                    echo "<td class='$statusClass'>" . htmlspecialchars($row['status']) . "</td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Borrow Button Section -->
            <div class="text-end mb-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-arrow-right-circle"></i> Borrow Selected Books
                </button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#booksTable').DataTable({
            responsive: true,
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            language: {
                lengthMenu: "Show _MENU_ entries",
                search: "Search:",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                paginate: {
                    previous: "Previous",
                    next: "Next"
                },
                emptyTable: "No books available for borrowing"
            },
            order: [
                [2, 'asc']
            ], // Sort by title by default
            columnDefs: [{
                targets: 0,
                orderable: false,
                searchable: false
            }]
        });

        // Handle student selection
        $('#studentSelect').on('change', function() {
            var studentId = $(this).val();
            if (studentId) {
                $.ajax({
                    url: 'actions/get_student_details.php',
                    type: 'POST',
                    data: {
                        student_id: studentId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#studentPhoto').attr('src', response.data.photo);
                            $('#detailStudentId').text(response.data.student_id);
                            $('#detailFullName').text(response.data.firstname + ' ' + response.data.lastname);
                            $('#detailCourse').text(response.data.course);
                            $('#studentDetails').show();
                        } else {
                            alert('Error: ' + response.message);
                            $('#studentDetails').hide();
                        }
                    },
                    error: function() {
                        alert('Error fetching student details');
                        $('#studentDetails').hide();
                    }
                });
            } else {
                $('#studentDetails').hide();
            }
        });
    });
</script>

</body>

</html>