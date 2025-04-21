<?php
session_start();
$page_title = "List of Students";
require_once 'includes/header.php';
require_once 'includes/navbar.php';

// Database connection
require_once 'db_connection.php'; // Ensure this file contains your database connection code
?>

<div id="debug" style="display:none;">
    <?php
    if (isset($_GET['error'])) {
        echo "Error: " . htmlspecialchars($_GET['error']);
        if (isset($_GET['msg'])) {
            echo "<br>Message: " . htmlspecialchars($_GET['msg']);
        }
    }
    ?>
</div>
<div class="wrapper">
    <?php require_once 'includes/sidebar.php'; ?>
    <div class="main-content-students">
        <?php
        if (isset($_GET['success'])) {
            $message = '';
            switch ($_GET['success']) {
                case 'added':
                    $message = 'Student added successfully!';
                    break;
                case 'updated':
                    $message = 'Student updated successfully!';
                    break;
                case 'deleted':
                    $message = 'Student deleted successfully!';
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
                $error_msg = 'Student ID already exists!';
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
                List of Students
            </h4>
            <button class="btn btn-primary mb-3">
                <i class="bi bi-plus"></i> New
            </button>
        </div>

        <!-- Students Table -->
        <div class="table-responsive">
            <table id="studentsTable" class="table table-bordered table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Course</th>
                        <th>Photo</th>
                        <th>Student ID</th>
                        <th>Firstname</th>
                        <th>Lastname</th>
                        <th>Mobile No.</th>
                        <th>Gender</th>
                        <th>Date Registered</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT *, DATE_FORMAT(date_registered, '%M %d, %Y') as formatted_date FROM students";
                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['course']) . "</td>";
                            echo "<td><img src='" . htmlspecialchars($row['photo']) . "' class='rounded-circle' width='60' height='70' alt='Student Photo style='display: block; margin-left: auto; margin-right: auto;'></td>";
                            echo "<td>" . htmlspecialchars($row['student_id']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['firstname']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['lastname']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['mobile_no']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['gender']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['formatted_date']) . "</td>";
                            echo "<td>
                        <button class='btn btn-success btn-sm edit-btn' data-id='" . htmlspecialchars($row['student_id']) . "'>
                            <i class='bi bi-pencil'></i> Edit
                        </button>
                        <button class='btn btn-danger btn-sm delete-btn' data-id='" . htmlspecialchars($row['student_id']) . "'>
                            <i class='bi bi-trash'></i> Delete
                        </button>
                    </td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Student Modal -->
    <div class="modal fade" id="addStudentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addStudentForm" method="POST" action="actions/student_add.php" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Course</label>
                            <select name="course" class="form-select" required>
                                <?php
                                $query = "SELECT course_code FROM courses";
                                $result = mysqli_query($conn, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<option value='" . htmlspecialchars($row['course_code']) . "'>" . htmlspecialchars($row['course_code']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Student ID</label>
                            <input type="text" name="student_id" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">First Name</label>
                            <input type="text" name="firstname" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="lastname" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mobile No.</label>
                            <input type="text" name="mobile_no" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Photo</label>
                            <input type="file" name="photo" class="form-control" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select" required>
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
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

    <!-- Edit Student Modal -->
    <div class="modal fade" id="editStudentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editStudentForm" method="POST" action="actions/student_edit.php" enctype="multipart/form-data">
                    <input type="hidden" name="student_id" id="student_edit_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Course</label>
                            <select name="course" class="form-select" required>
                                <?php
                                $query = "SELECT course_code FROM courses";
                                $result = mysqli_query($conn, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<option value='" . htmlspecialchars($row['course_code']) . "'>" . htmlspecialchars($row['course_code']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Photo</label>
                            <input type="file" name="photo" class="form-control" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">First Name</label>
                            <input type="text" name="firstname" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="lastname" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mobile No.</label>
                            <input type="text" name="mobile_no" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Gender</label>
                            <select name="gender" class="form-select" required>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Student Modal -->
    <div class="modal fade" id="deleteStudentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="deleteStudentForm" method="POST" action="actions/student_delete.php">
                    <input type="hidden" name="student_id" id="student_delete_id">
                    <div class="modal-body">
                        <p>Are you sure you want to delete this student?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete</button>
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
        $('#studentsTable').DataTable({
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
                [2, 'asc']
            ], // Sort by Student ID by default
            columnDefs: [{
                    targets: 1, // Photo column
                    orderable: false
                },
                {
                    targets: 7, // Action column
                    orderable: false
                }
            ]
        });
    });

    // Replace the existing modal event handlers with this code
    $(document).ready(function() {
        // Open Add Modal
        $('.btn-primary').click(function() {
            $('#addStudentModal').modal('show');
        });

        // Open Edit Modal
        $(document).on('click', '.edit-btn', function() {
            var row = $(this).closest('tr');
            var course = row.find('td:eq(0)').text().trim();
            var student_id = row.find('td:eq(2)').text().trim();
            var firstname = row.find('td:eq(3)').text().trim();
            var lastname = row.find('td:eq(4)').text().trim();
            var mobile_no = row.find('td:eq(5)').text().trim();

            // Set values in edit form
            $('#editStudentModal select[name="course"]').val(course);
            $('#editStudentModal input[name="student_id"]').val(student_id);
            $('#editStudentModal input[name="firstname"]').val(firstname);
            $('#editStudentModal input[name="lastname"]').val(lastname);
            $('#editStudentModal input[name="mobile_no"]').val(mobile_no);
            $('#student_edit_id').val(student_id);

            // Store original values as data attributes
            $('#editStudentForm').data('original', {
                course: course,
                student_id: student_id,
                firstname: firstname,
                lastname: lastname,
                mobile_no: mobile_no
            });

            $('#editStudentModal').modal('show');
        });

        // Handle modal close/hide
        $('#editStudentModal').on('hidden.bs.modal', function() {
            // Reset form
            $('#editStudentForm')[0].reset();

            // Restore original values if they exist
            var original = $('#editStudentForm').data('original');
            if (original) {
                $('#editStudentModal select[name="course"]').val(original.course);
                $('#editStudentModal input[name="student_id"]').val(original.student_id);
                $('#editStudentModal input[name="firstname"]').val(original.firstname);
                $('#editStudentModal input[name="lastname"]').val(original.lastname);
                $('#editStudentModal input[name="mobile_no"]').val(original.mobile_no);
            }
        });

        // Delete button click handler
        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();
            var studentId = $(this).data('id');

            if (studentId) {
                // Set the student ID in the delete modal form
                $('#student_delete_id').val(studentId);
                $('#deleteStudentModal').modal('show');
            } else {
                alert('Error: Could not determine student ID');
            }
        });

        // Delete form submit handler
        $('#deleteStudentForm').on('submit', function(e) {
            var studentId = $('#student_delete_id').val();
            if (!studentId) {
                e.preventDefault();
                alert('Error: No student ID specified');
                return false;
            }
        });

        // Add form submit handler
        $('#addStudentForm').on('submit', function(e) {
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
            return true;
        });

        // Reset form on modal close
        $('#addStudentModal').on('hidden.bs.modal', function() {
            $('#addStudentForm')[0].reset();
            $('#addStudentForm').find('.is-invalid').removeClass('is-invalid');
        });
    });
</script>
</body>

</html>