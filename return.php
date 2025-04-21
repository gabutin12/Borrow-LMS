<?php
session_start();
$page_title = "Return Books";
require_once 'includes/header.php';
require_once 'includes/navbar.php';
require_once 'db_connection.php';
?>

<div class="wrapper">
    <?php require_once 'includes/sidebar.php'; ?>
    <div class="main-content-returns">
        <?php
        if (isset($_GET['success'])) {
            $message = '';
            switch ($_GET['success']) {
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
                Return Books
            </h6>
        </div>

        <!-- Return Form -->
        <form method="POST" action="actions/book_return.php">
            <!-- Student Selection Section -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <!-- Student Dropdown -->
                        <div class="col-md-8">
                            <select class="form-select" id="studentSelect" name="student_id" required>
                                <option value="">Select Student</option>
                                <?php
                                $query = "SELECT DISTINCT s.student_id, s.firstname, s.lastname 
                                        FROM students s 
                                        INNER JOIN borrowed_books bb ON s.student_id = bb.student_id 
                                        WHERE bb.status = 'Borrowed'
                                        ORDER BY s.lastname, s.firstname";
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

                        <!-- View Return Logs Button -->
                        <div class="col-md-4 text-end">
                            <a href="return_books_logs.php" class="btn btn-info text-white">
                                <i class="bi bi-journal-text"></i> View Return Logs
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

            <!-- Borrowed Books Table -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Borrowed Books</h5>
                    <div class="table-responsive">
                        <table id="borrowedBooksTable" class="table table-bordered table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Select</th>
                                    <th>ISBN</th>
                                    <th>Title</th>
                                    <th>Borrow Date</th>
                                    <th>Due Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="borrowedBooksList">
                                <!-- Table content will be loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Return Button Section -->
            <div class="text-end mb-4">
                <button type="button" class="btn btn-primary" id="returnButton" disabled data-bs-toggle="modal" data-bs-target="#returnDateModal">
                    <i class="bi bi-arrow-return-left"></i> Return Selected Books
                </button>
            </div>

            <!-- Return Date Modal -->
            <div class="modal fade" id="returnDateModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Select Return Date</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form id="returnForm" method="POST" action="actions/book_return.php">
                            <div class="modal-body">
                                <!-- Hidden inputs -->
                                <input type="hidden" name="student_id" id="modalStudentId">
                                <div id="selectedBooksInputs"></div>

                                <div class="mb-3">
                                    <label for="returnDate" class="form-label">Return Date</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control flatpickr" id="returnDate" name="return_date" required readonly>
                                        <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Confirm Return</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script> -->

<script>
    $(document).ready(function() {
        // Initialize DataTable
        var table = $('#borrowedBooksTable').DataTable({
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
                emptyTable: "No borrowed books found"
            }
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

        // Handle student selection
        $('#studentSelect').on('change', function() {
            var studentId = $(this).val();
            if (studentId) {
                // Fetch borrowed books for selected student
                $.ajax({
                    url: 'actions/get_borrowed_books.php',
                    type: 'POST',
                    data: {
                        student_id: studentId
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            // Clear existing table rows
                            table.clear();

                            // Add new rows
                            response.data.forEach(function(book) {
                                var dueDate = new Date(book.due_date);
                                var today = new Date();
                                var statusClass = today > dueDate ? 'text-danger' : 'text-warning';
                                var statusText = today > dueDate ? 'Overdue' : 'Borrowed';

                                table.row.add([
                                    '<input type="checkbox" name="return_books[]" value="' + book.id + '">',
                                    book.isbn,
                                    book.title,
                                    book.borrow_date,
                                    book.due_date,
                                    '<span class="' + statusClass + '">' + statusText + '</span>'
                                ]);
                            });

                            table.draw();
                            updateReturnButton();
                        }
                    },
                    error: function() {
                        alert('Error fetching borrowed books');
                    }
                });
            } else {
                table.clear().draw();
                updateReturnButton();
            }
        });

        // Handle checkbox changes
        $(document).on('change', 'input[name="return_books[]"]', function() {
            updateReturnButton();
        });

        function updateReturnButton() {
            var checkedBooks = $('input[name="return_books[]"]:checked').length;
            $('#returnButton').prop('disabled', checkedBooks === 0);
        }

        // Remove or comment out the old datepicker initialization
        /*$('#returnDate').datepicker({
            format: 'mm/dd/yyyy',
            autoclose: true,
            startDate: '+5d',
            todayHighlight: true
        });*/

        // Update the Flatpickr initialization
        flatpickr("#returnDate", {
            dateFormat: "m/d/Y",
            minDate: new Date().fp_incr(5), // Set minimum date to 5 days from today
            maxDate: new Date().fp_incr(30),
            defaultDate: new Date().fp_incr(5), // Set default to exactly 5 days from today
            disableMobile: true,
            theme: "light",
            onChange: function(selectedDates, dateStr, instance) {
                // Validate the selected date is at least 5 days from today
                const minDate = new Date();
                minDate.setDate(minDate.getDate() + 5);
                if (selectedDates[0] < minDate) {
                    alert('Return date must be at least 5 days from today');
                    instance.setDate(minDate);
                }
            }
        });

        // Handle return button click
        $('#returnButton').on('click', function() {
            var studentId = $('#studentSelect').val();
            var selectedBooks = [];

            $('input[name="return_books[]"]:checked').each(function() {
                selectedBooks.push($(this).val());
            });

            if (selectedBooks.length === 0) {
                alert('Please select at least one book to return');
                return;
            }

            // Clear previous inputs
            $('#selectedBooksInputs').empty();

            // Add hidden inputs for selected books
            selectedBooks.forEach(function(bookId) {
                $('#selectedBooksInputs').append(
                    `<input type="hidden" name="return_books[]" value="${bookId}">`
                );
            });

            // Set student ID in modal
            $('#modalStudentId').val(studentId);

            // Initialize datepicker
            $('#returnDate').datepicker({
                format: 'mm/dd/yyyy',
                autoclose: true,
                todayHighlight: true
            });

            // Set current date as default
            var today = new Date();
            $('#returnDate').datepicker('update', today);
        });

        // Form validation before submit
        $('#returnForm').on('submit', function(e) {
            var returnDate = $('#returnDate').val();
            if (!returnDate) {
                e.preventDefault();
                alert('Please select a return date');
                return false;
            }

            var selectedDate = new Date(returnDate);
            var minDate = new Date();
            minDate.setDate(minDate.getDate() + 5);

            if (selectedDate < minDate) {
                e.preventDefault();
                alert('Return date must be at least 5 days from today');
                return false;
            }
        });

    });
</script>

<script>
    $(document).ready(function() {
        // Initialize Flatpickr
        // Update the Flatpickr initialization in return.php
        flatpickr("#returnDate", {
            dateFormat: "m/d/Y",
            minDate: "today",
            maxDate: new Date().fp_incr(30),
            defaultDate: new Date().fp_incr(5), // Set default to 5 days from today
            disableMobile: true,
            theme: "light"
        });

        // Handle return button click
        $('#returnButton').on('click', function() {
            var studentId = $('#studentSelect').val();
            var selectedBooks = [];

            $('input[name="return_books[]"]:checked').each(function() {
                selectedBooks.push($(this).val());
            });

            if (selectedBooks.length === 0) {
                alert('Please select at least one book to return');
                return;
            }

            // Clear previous inputs
            $('#selectedBooksInputs').empty();

            // Add hidden inputs for selected books
            selectedBooks.forEach(function(bookId) {
                $('#selectedBooksInputs').append(
                    `<input type="hidden" name="return_books[]" value="${bookId}">`
                );
            });

            // Set student ID in modal
            $('#modalStudentId').val(studentId);

            // Reset and show modal
            $('#returnDate').val(new Date().toLocaleDateString('en-US'));
            $('#returnDateModal').modal('show');
        });

        // Form validation before submit
        $('#returnForm').on('submit', function(e) {
            e.preventDefault();

            var returnDate = $('#returnDate').val();
            if (!returnDate) {
                alert('Please select a return date');
                return false;
            }

            // Submit the form using AJAX
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#returnDateModal').modal('hide');
                    location.reload();
                },
                error: function(xhr) {
                    alert('Error processing return: ' + xhr.responseText);
                }
            });
        });
    });
</script>

</body>

</html>