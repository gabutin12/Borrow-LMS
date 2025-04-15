<?php
@session_start();
$page_title = "Settings";
@require_once 'db_connection.php';
require_once 'includes/header.php';
require_once 'includes/navbar.php';

// Fetch current settings from database
$settings_query = "SELECT fine_amount, max_borrow_days FROM system_settings WHERE id = 1";
$settings_result = mysqli_query($conn, $settings_query);
$settings = mysqli_fetch_assoc($settings_result);

// Set default values if no settings found
$fine_amount = $settings['fine_amount'] ?? 10.00;
$max_borrow_days = $settings['max_borrow_days'] ?? 7;
?>

<div class="wrapper">
    <?php require_once 'includes/sidebar.php'; ?>
    <div class="main-content">
        <!-- Error/Success Messages -->
        <div class="container-fluid">
            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                    <?php if ($_SESSION['message_type'] == 'success'): ?>
                        <i class="bi bi-check-circle-fill me-2"></i>
                    <?php else: ?>
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <?php endif; ?>
                    <?php
                    echo $_SESSION['message'];
                    unset($_SESSION['message']);
                    unset($_SESSION['message_type']);
                    ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
        </div>

        <div class="container-fluid">
            <!-- Header Section -->
            <div class="mb-4">
                <h4 class="mb-3">
                    <a href="dashboard.php" class="text-decoration-none text-primary">Dashboard</a>
                    <i class="bi bi-chevron-right small"></i>
                    Settings
                </h4>
            </div>

            <div class="row">
                <!-- System Settings -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-gear-fill me-2"></i>System Settings
                            </h5>
                        </div>
                        <div class="card-body">
                            <form id="fineSettingsForm" method="POST" action="update_settings.php">
                                <div class="mb-3">
                                    <label for="fineAmount" class="form-label">Late Return Fine (₱/day)</label>
                                    <div class="input-group">
                                        <span class="input-group-text">₱</span>
                                        <input type="number"
                                            class="form-control"
                                            id="fineAmount"
                                            name="fineAmount"
                                            value="<?php echo htmlspecialchars($fine_amount); ?>"
                                            min="0"
                                            step="1"
                                            required>
                                    </div>
                                    <small class="text-muted">Current fine rate: ₱<?php echo number_format($fine_amount, 2); ?> per day</small>
                                </div>
                                <div class="mb-3">
                                    <label for="maxBorrowDays" class="form-label">Maximum Borrow Duration (Days)</label>
                                    <input type="number"
                                        class="form-control"
                                        id="maxBorrowDays"
                                        name="maxBorrowDays"
                                        value="<?php echo htmlspecialchars($max_borrow_days); ?>"
                                        min="1"
                                        required>
                                </div>
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-save me-2"></i>Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Admin Settings -->
                <div class="col-md-6 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-shield-lock-fill me-2"></i>Admin Settings
                            </h5>
                        </div>
                        <div class="card-body">
                            <form id="adminSettingsForm" method="POST" action="update_admin.php">
                                <div class="mb-3">
                                    <label for="currentPassword" class="form-label">Current Password</label>
                                    <input type="password" class="form-control" id="currentPassword" name="currentPassword" required>
                                </div>
                                <div class="mb-3">
                                    <label for="newPassword" class="form-label">New Password</label>
                                    <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                                </div>
                                <div class="mb-3">
                                    <label for="confirmPassword" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                                </div>
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-key-fill me-2"></i>Change Password
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Password validation
        const adminForm = document.getElementById('adminSettingsForm');
        adminForm.addEventListener('submit', function(e) {
            const newPass = document.getElementById('newPassword').value;
            const confirmPass = document.getElementById('confirmPassword').value;

            if (newPass !== confirmPass) {
                e.preventDefault();
                alert('New passwords do not match!');
            }
        });

        // Fine amount validation
        const fineForm = document.getElementById('fineSettingsForm');
        fineForm.addEventListener('submit', function(e) {
            const fineAmount = document.getElementById('fineAmount').value;
            if (fineAmount < 0) {
                e.preventDefault();
                alert('Fine amount cannot be negative!');
            }
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/main.js"></script>
</body>

</html>