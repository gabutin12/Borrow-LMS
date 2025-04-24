<?php
session_start();
$page_title = "Admin Management";
require_once 'includes/header.php';
require_once 'includes/navbar.php';
require_once 'db_connection.php';
?>

<div class="wrapper">
    <?php require_once 'includes/sidebar.php'; ?>
    <div class="main-content-admin">
        <?php
        if (isset($_GET['success'])) {
            $message = '';
            switch ($_GET['success']) {
                case 'added':
                    $message = 'Admin added successfully!';
                    break;
                case 'updated':
                    $message = 'Admin updated successfully!';
                    break;
                case 'deleted':
                    $message = 'Admin deleted successfully!';
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
                $error_msg = 'Username already exists!';
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
                Admin Management
            </h6>
            <button class="btn btn-primary mb-3">
                <i class="bi bi-plus"></i> New Admin
            </button>
        </div>

        <!-- Admin Table -->
        <div class="table-responsive">
            <table id="adminsTable" class="table table-bordered table-striped mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Photo</th>
                        <th>Username</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Date Added</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT *, DATE_FORMAT(created_at, '%M %d, %Y') as formatted_date FROM admin";
                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td class='text-center'><img src='" . (isset($row['photo']) ? htmlspecialchars($row['photo']) : 'images/default.jpg') . "' class='rounded-circle mx-auto d-block' width='60' height='60' alt='Admin Photo'></td>";
                            echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                            echo "<td>" . (isset($row['firstname']) ? htmlspecialchars($row['firstname']) : '') . "</td>";
                            echo "<td>" . (isset($row['lastname']) ? htmlspecialchars($row['lastname']) : '') . "</td>";
                            echo "<td>" . (isset($row['email']) ? htmlspecialchars($row['email']) : '') . "</td>";
                            echo "<td>" . htmlspecialchars($row['formatted_date']) . "</td>";
                            echo "<td><span class='badge bg-success'>Active</span></td>";
                            echo "<td>
                                <button class='btn btn-info btn-sm view-btn' data-id='" . htmlspecialchars($row['id']) . "'>
                                    <i class='bi bi-eye'></i> View
                                </button>
                                <button class='btn btn-success btn-sm edit-btn' data-id='" . htmlspecialchars($row['id']) . "'>
                                    <i class='bi bi-pencil'></i> Edit
                                </button>
                                <button class='btn btn-danger btn-sm delete-btn' data-id='" . htmlspecialchars($row['id']) . "'>
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

    <!-- Add Admin Modal -->
    <div class="modal fade" id="addAdminModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addAdminForm" method="POST" action="actions/admin_add.php" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control" required>
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
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Photo</label>
                            <input type="file" name="photo" class="form-control" accept="image/*">
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

    <!-- Edit Admin Modal -->
    <div class="modal fade" id="editAdminModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editAdminForm" method="POST" action="actions/admin_edit.php" enctype="multipart/form-data">
                    <input type="hidden" name="admin_id" id="admin_edit_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Current Photo</label>
                            <img id="current_photo_preview" src="" alt="Current Photo" class="img-thumbnail mb-2" style="max-width: 100px; display: block;">
                            <label class="form-label">Update Photo</label>
                            <input type="file" name="photo" class="form-control" accept="image/*">
                            <small class="form-text text-muted">Leave empty to keep current photo</small>
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
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
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

    <!-- View Admin Modal -->
    <div class="modal fade" id="viewAdminModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Admin Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <img id="view_photo" src="" alt="Admin Photo" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Username:</label>
                            <p id="view_username"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Email:</label>
                            <p id="view_email"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">First Name:</label>
                            <p id="view_firstname"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Last Name:</label>
                            <p id="view_lastname"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Date Added:</label>
                            <p id="view_date"></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Status:</label>
                            <p><span class="badge bg-success">Active</span></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Admin Modal -->
    <div class="modal fade" id="deleteAdminModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Delete Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="deleteAdminForm" method="POST" action="actions/admin_delete.php">
                    <input type="hidden" name="admin_id" id="admin_delete_id">
                    <div class="modal-body">
                        <p>Are you sure you want to delete this admin?</p>
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
        $('#adminsTable').DataTable({
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
            order: [[1, 'asc']], // Sort by username by default
            columnDefs: [{
                targets: [0, 7], // Photo and Action columns
                orderable: false
            }]
        });

        // Open Add Modal
        $('.btn-primary').click(function() {
            $('#addAdminModal').modal('show');
        });

        // Open View Modal
        $(document).on('click', '.view-btn', function() {
            var row = $(this).closest('tr');
            var username = row.find('td:eq(1)').text().trim();
            var firstname = row.find('td:eq(2)').text().trim();
            var lastname = row.find('td:eq(3)').text().trim();
            var email = row.find('td:eq(4)').text().trim();
            var date = row.find('td:eq(5)').text().trim();
            var photo = row.find('img').attr('src');

            $('#view_username').text(username);
            $('#view_firstname').text(firstname);
            $('#view_lastname').text(lastname);
            $('#view_email').text(email);
            $('#view_date').text(date);
            $('#view_photo').attr('src', photo);

            $('#viewAdminModal').modal('show');
        });

        // Open Edit Modal
        $(document).on('click', '.edit-btn', function() {
            var row = $(this).closest('tr');
            var adminId = $(this).data('id');
            var username = row.find('td:eq(1)').text().trim();
            var firstname = row.find('td:eq(2)').text().trim();
            var lastname = row.find('td:eq(3)').text().trim();
            var email = row.find('td:eq(4)').text().trim();
            var photo = row.find('img').attr('src');

            $('#admin_edit_id').val(adminId);
            $('#editAdminModal input[name="username"]').val(username);
            $('#editAdminModal input[name="firstname"]').val(firstname);
            $('#editAdminModal input[name="lastname"]').val(lastname);
            $('#editAdminModal input[name="email"]').val(email);
            $('#current_photo_preview').attr('src', photo);

            $('#editAdminModal').modal('show');
        });

        // Store original form values
        $('#editAdminModal').on('show.bs.modal', function() {
            var form = $('#editAdminForm');
            form.data('original', {
                username: form.find('input[name="username"]').val(),
                firstname: form.find('input[name="firstname"]').val(),
                lastname: form.find('input[name="lastname"]').val(),
                email: form.find('input[name="email"]').val()
            });
        });

        // Reset form on modal close
        $('#editAdminModal').on('hidden.bs.modal', function() {
            var form = $('#editAdminForm');
            var original = form.data('original');
            if (original) {
                form.find('input[name="username"]').val(original.username);
                form.find('input[name="firstname"]').val(original.firstname);
                form.find('input[name="lastname"]').val(original.lastname);
                form.find('input[name="email"]').val(original.email);
            }
            form[0].reset();
        });

        // Delete button click handler
        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();
            var adminId = $(this).data('id');
            if (adminId) {
                $('#admin_delete_id').val(adminId);
                $('#deleteAdminModal').modal('show');
            }
        });

        // Password validation in add form
        $('#addAdminForm').on('submit', function(e) {
            var password = $('input[name="password"]').val();
            var confirmPassword = $('input[name="confirm_password"]').val();

            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
                return false;
            }

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

        // Reset form and validation states on modal close
        $('#addAdminModal').on('hidden.bs.modal', function() {
            $('#addAdminForm')[0].reset();
            $('#addAdminForm').find('.is-invalid').removeClass('is-invalid');
        });
    });
</script>
</body>
</html>