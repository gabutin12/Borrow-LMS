<?php
session_start();
$page_title = "Courses";
require_once 'includes/header.php';
require_once 'includes/navbar.php';
require_once 'db_connection.php';
?>

<div class="wrapper">
    <?php require_once 'includes/sidebar.php'; ?>
    <div class="main-content-courses p-4">
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
            if (isset($_GET['duplicate_title'])) {
                $error_msg = 'Title already exists!';
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
                Courses
            </h4>
            <button class="btn btn-primary mb-3">
                <i class="bi bi-plus"></i> New
            </button>
        </div>

        <!-- Courses Table -->
        <div class="table-responsive">
            <table id="coursesTable" class="table table-bordered table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Course Code</th>
                        <th>Course Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT * FROM courses";
                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($row['course_code']) . "</td>"; // First column
                            echo "<td>" . htmlspecialchars($row['course_name']) . "</td>"; // Second column
                            echo "<td>
                                    <button class='btn btn-success btn-sm edit-btn' data-course='" . htmlspecialchars($row['course_code']) . "'>
                                        <i class='bi bi-pencil'></i> Edit
                                    </button>
                                    <button class='btn btn-danger btn-sm delete-btn' data-course='" . htmlspecialchars($row['course_code']) . "'>
                                        <i class='bi bi-trash'></i> Delete
                                    </button>
                                </td>"; // Third column
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3' class='text-center'>No courses found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Course Modal -->
    <div class="modal fade" id="addCourseModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Course</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addCourseForm" method="POST" action="actions/course_add.php">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Course Code</label>
                            <input type="text" name="course_code" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Course Name</label>
                            <input type="text" name="course_name" class="form-control" required>
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

    <!-- Edit Course Modal -->
    <div class="modal fade" id="editCourseModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Course</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editCourseForm" method="POST" action="actions/course_edit.php">
                    <input type="hidden" name="old_course_code" id="edit_old_course_code">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Course Code</label>
                            <input type="text" name="course_code" id="edit_course_code" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Course Name</label>
                            <input type="text" name="course_name" id="edit_course_name" class="form-control" required>
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

    <!-- Delete Course Modal -->
    <div class="modal fade" id="deleteCourseModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Course</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="deleteCourseForm" method="POST" action="actions/course_delete.php">
                    <input type="hidden" name="course_code" id="delete_course_code">
                    <div class="modal-body">
                        <p>Are you sure you want to delete this course?</p>
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
        $('#coursesTable').DataTable({
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
            ], // Sort by Course Code by default
            columnDefs: [{
                targets: 2, // Action column
                orderable: false
            }]
        });

        // Open Add Modal
        $('.btn-primary').click(function() {
            $('#addCourseModal').modal('show');
        });

        // Open Edit Modal
        // Replace the existing edit button click handler
        $(document).on('click', '.edit-btn', function() {
            var courseCode = $(this).data('course');
            var courseName = $(this).closest('tr').find('td:eq(1)').text().trim();

            console.log('Editing course:', courseCode); // For debugging

            $('#edit_old_course_code').val(courseCode); // Store original course code
            $('#edit_course_code').val(courseCode); // Set current course code
            $('#edit_course_name').val(courseName);
            $('#editCourseModal').modal('show');
        });
        // Delete button click handler
        $(document).on('click', '.delete-btn', function() {
            var courseCode = $(this).data('course');

            console.log('Deleting course:', courseCode); // For debugging

            if (courseCode) {
                $('#delete_course_code').val(courseCode);
                $('#deleteCourseModal').modal('show');
            } else {
                alert('Error: Could not determine course code');
            }
        });

        // Form submit handlers
        $('#editCourseForm').on('submit', function(e) {
            var courseCode = $('#edit_course_code').val();
            if (!courseCode) {
                e.preventDefault();
                alert('No course code specified');
                return false;
            }
        });

        $('#deleteCourseForm').on('submit', function(e) {
            var courseCode = $('#delete_course_code').val();
            if (!courseCode) {
                e.preventDefault();
                alert('No course code specified');
                return false;
            }
        });
    });
</script>
</body>

</html>