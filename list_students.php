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
            <h6 class="mb-3">
                <a href="dashboard.php" class="text-decoration-none text-primary">Dashboard</a>
                <i class="bi bi-chevron-right small"></i>
                List of Students
            </h6>
            <button class="btn btn-primary mb-3">
                <i class="bi bi-plus"></i> New Student
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
                        <th>Year Level</th>
                        <th>Date Registered</th>
                        <th>Status</th>
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
                            echo "<td>" . htmlspecialchars($row['year_level']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['formatted_date']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                            // Action buttons
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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addStudentForm" method="POST" action="actions/student_add.php" enctype="multipart/form-data">
                    <div class="modal-body">
                        <!-- Centered Photo Section -->
                        <div class="text-center mb-4">
                            <div class="d-flex justify-content-center">
                                <img id="photo_preview" src="images/default.jpg" alt="Student Photo"
                                    class="img-thumbnail mb-2"
                                    style="max-width: 100px; height: 100px; object-fit: cover;">
                            </div>
                            <div class="mt-2">
                                <label class="form-label">Upload Photo</label>
                                <input type="file" name="photo" class="form-control" accept="image/*"
                                    onchange="previewImage(this);">
                                <small class="form-text text-muted">Upload student photo (optional)</small>
                            </div>
                        </div>

                        <!-- Two Column Layout -->
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Course</label>
                                    <select name="course" class="form-select" required>
                                        <option value="">Select Course</option>
                                        <?php
                                        $query = "SELECT course_code FROM courses";
                                        $result = mysqli_query($conn, $query);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<option value='" . htmlspecialchars($row['course_code']) . "'>" .
                                                htmlspecialchars($row['course_code']) . "</option>";
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
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-6">

                                <div class="mb-3">
                                    <label class="form-label">Mobile No.</label>
                                    <input type="text" name="mobile_no" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Gender</label>
                                    <select name="gender" class="form-select" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Year Level</label>
                                    <select name="year_level" class="form-select" required>
                                        <option value="">Select Year Level</option>
                                        <option value="1st Year">1st Year</option>
                                        <option value="2nd Year">2nd Year</option>
                                        <option value="3rd Year">3rd Year</option>
                                        <option value="4th Year">4th Year</option>
                                    </select>
                                </div>
                            </div>
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
        <div class="modal-dialog modal-lg"> <!-- Changed to modal-lg for wider modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Student</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editStudentForm" method="POST" action="actions/student_edit.php" enctype="multipart/form-data">
                    <input type="hidden" name="student_id" id="student_edit_id">
                    <input type="hidden" name="current_photo" id="current_photo">
                    <div class="modal-body">
                        <!-- Centered Photo Section -->
                        <div class="text-center mb-4">
                            <label class="form-label">Current Photo</label>
                            <div class="d-flex justify-content-center">
                                <img id="current_photo_preview" src="" alt="Current Photo"
                                    class="img-thumbnail mb-2"
                                    style="max-width: 100px; height: 100px; object-fit: cover;">
                            </div>
                            <div class="mt-2">
                                <label class="form-label">Update Photo</label>
                                <input type="file" name="photo" class="form-control" accept="image/*" onchange="previewImage(this);">
                                <small class="form-text text-muted">Leave empty to keep current photo</small>
                            </div>
                        </div>

                        <!-- Two Column Layout -->
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Course</label>
                                    <select name="course" class="form-select" required>
                                        <?php
                                        $query = "SELECT course_code FROM courses";
                                        $result = mysqli_query($conn, $query);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<option value='" . htmlspecialchars($row['course_code']) . "'>" .
                                                htmlspecialchars($row['course_code']) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">First Name</label>
                                    <input type="text" name="firstname" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" name="lastname" class="form-control" required>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Mobile No.</label>
                                    <input type="text" name="mobile_no" class="form-control" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Year Level</label>
                                    <select name="year_level" class="form-select" required>
                                        <option value="">Select Year Level</option>
                                        <option value="1st Year">1st Year</option>
                                        <option value="2nd Year">2nd Year</option>
                                        <option value="3rd Year">3rd Year</option>
                                        <option value="4th Year">4th Year</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Gender</label>
                                    <select name="gender" class="form-select" required>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                    targets: -1, // Action column (last column)
                    orderable: false
                },
                {
                    // Add custom sorting for Year Level
                    targets: 7, // Year Level column
                    type: 'string',
                    render: function(data, type, row) {
                        if (type === 'sort') {
                            // Convert year level to a number for sorting
                            return data.replace(/(\d+)st|\d+nd|\d+rd|\d+th/g, '$1');
                        }
                        return data;
                    }
                }
            ]
        });
    });

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
            var gender = row.find('td:eq(6)').text().trim();
            var year_level = row.find('td:eq(7)').text().trim();
            var photo_url = row.find('td:eq(1) img').attr('src');

            // Set values in edit form
            $('#editStudentModal select[name="course"]').val(course);
            $('#editStudentModal input[name="student_id"]').val(student_id);
            $('#editStudentModal input[name="firstname"]').val(firstname);
            $('#editStudentModal input[name="lastname"]').val(lastname);
            $('#editStudentModal input[name="mobile_no"]').val(mobile_no);
            $('#editStudentModal select[name="gender"]').val(gender);
            $('#editStudentModal select[name="year_level"]').val(year_level);
            $('#student_edit_id').val(student_id);
            $('#current_photo').val(photo_url);
            $('#current_photo_preview').attr('src', photo_url);

            // Store original values
            $('#editStudentForm').data('original', {
                course: course,
                student_id: student_id,
                firstname: firstname,
                lastname: lastname,
                mobile_no: mobile_no,
                gender: gender,
                year_level: year_level,
                photo_url: photo_url
            });

            $('#editStudentModal').modal('show');
        });

        // Validate Mobile Number
        $('input[name="mobile_no"]').on('input', function() {
            let value = $(this).val();
            // Allow only numbers and limit to 11 digits
            if (!/^\d{0,11}$/.test(value)) {
                $(this).val(value.replace(/[^\d]/g, '').substring(0, 11));
            }
        });

        // Handle modal close/hide
        $('#editStudentModal').on('hidden.bs.modal', function() {
            $('#editStudentForm')[0].reset();
            $('#current_photo_preview').attr('src', '');
            $('.is-invalid').removeClass('is-invalid');

            var original = $('#editStudentForm').data('original');
            if (original) {
                $('#editStudentModal select[name="course"]').val(original.course);
                $('#editStudentModal input[name="student_id"]').val(original.student_id);
                $('#editStudentModal input[name="firstname"]').val(original.firstname);
                $('#editStudentModal input[name="lastname"]').val(original.lastname);
                $('#editStudentModal input[name="mobile_no"]').val(original.mobile_no);
                $('#editStudentModal select[name="gender"]').val(original.gender);
                $('#editStudentModal select[name="year_level"]').val(original.year_level);
                $('#current_photo_preview').attr('src', original.photo_url);
            }
        });

        // Delete button handler with confirmation
        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();
            var studentId = $(this).data('id');
            var studentName = $(this).closest('tr').find('td:eq(3)').text().trim() + ' ' +
                $(this).closest('tr').find('td:eq(4)').text().trim();

            if (studentId) {
                $('#student_delete_id').val(studentId);
                $('.student-name').text(studentName); // Add this span in your delete modal
                $('#deleteStudentModal').modal('show');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Could not determine student ID'
                });
            }
        });

        // Form validation
        function validateForm($form) {
            var isValid = true;
            var requiredFields = $form.find('[required]');

            requiredFields.each(function() {
                if (!$(this).val().trim()) {
                    isValid = false;
                    $(this).addClass('is-invalid');
                    $(this).next('.invalid-feedback').remove();
                    $(this).after('<div class="invalid-feedback">This field is required</div>');
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).next('.invalid-feedback').remove();
                }
            });

            return isValid;
        }

        // Add form submit handler
        $('#addStudentForm').on('submit', function(e) {
            if (!validateForm($(this))) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please fill in all required fields correctly'
                });
                return false;
            }
            return true;
        });

        // Edit form submit handler
        $('#editStudentForm').on('submit', function(e) {
            if (!validateForm($(this))) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please fill in all required fields correctly'
                });
                return false;
            }
            return true;
        });

        // Reset forms on modal close
        $('.modal').on('hidden.bs.modal', function() {
            var $form = $(this).find('form');
            $form[0].reset();
            $form.find('.is-invalid').removeClass('is-invalid');
            $form.find('.invalid-feedback').remove();
        });
    });


    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                // Check which modal is being used and update the corresponding preview
                if ($(input).closest('#addStudentModal').length > 0) {
                    $('#photo_preview').attr('src', e.target.result);
                } else if ($(input).closest('#editStudentModal').length > 0) {
                    $('#current_photo_preview').attr('src', e.target.result);
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
</body>

</html>