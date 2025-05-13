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
    <div class="main-content-settings p-4">
        <!-- Header Section -->
        <div class="mb-4">
            <h6 class="mb-3">
                <a href="dashboard.php" class="text-decoration-none text-primary">Dashboard</a>
                <i class="bi bi-chevron-right small"></i>
                Settings Management
            </h6>
        </div>

        <div class="row">
            <!-- Borrow Settings -->
            <div class="col-md-6 mb-4">
                <div class="card settings-card shadow-sm border-0">
                    <div class="card-header settings-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-book-half me-2"></i>Borrow Settings
                        </h5>
                        <!-- <small class="text-muted">Configure borrowing rules and fines</small> -->
                    </div>
                    <div class="card-body settings-body">
                        <form id="fineSettingsForm" method="POST" action="update_settings.php">
                            <div class="setting-item mb-4">
                                <label for="fineAmount" class="form-label fw-semibold">Late Return Fine (₱/day)</label>
                                <div class="input-group">
                                    <span class="input-group-text border-primary bg-light">₱</span>
                                    <input type="number"
                                        class="form-control border-primary"
                                        id="fineAmount"
                                        name="fineAmount"
                                        value="<?php echo htmlspecialchars($fine_amount); ?>"
                                        min="0"
                                        step="1"
                                        required>
                                </div>
                                <small class="text-muted mt-2 d-block">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Current fine rate: ₱<?php echo number_format($fine_amount, 2); ?> per day
                                </small>
                            </div>
                            <div class="setting-item mb-4">
                                <label for="maxBorrowDays" class="form-label fw-semibold">Maximum Borrow Duration</label>
                                <div class="input-group">
                                    <input type="number"
                                        class="form-control border-primary"
                                        id="maxBorrowDays"
                                        name="maxBorrowDays"
                                        value="<?php echo htmlspecialchars($max_borrow_days); ?>"
                                        min="1"
                                        required>
                                    <span class="input-group-text border-primary bg-light">Days</span>
                                </div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-settings">
                                    <i class="bi bi-save me-2"></i>Update Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Admin Settings
            <div class="col-md-6 mb-4">
                <div class="card settings-card shadow-sm border-0">
                    <div class="card-header settings-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-shield-lock me-2"></i>Admin Security
                        </h5>                        
                    </div>
                    <div class="card-body settings-body">
                        <form id="adminSettingsForm" method="POST" action="update_admin.php">
                            <div class="setting-item mb-3">
                                <label for="currentPassword" class="form-label fw-semibold">Current Password</label>
                                <div class="input-group">
                                    <span class="input-group-text border-primary bg-light">
                                        <i class="bi bi-key"></i>
                                    </span>
                                    <input type="password" class="form-control border-primary" id="currentPassword" name="currentPassword" required>
                                </div>
                            </div>
                            <div class="setting-item mb-3">
                                <label for="newPassword" class="form-label fw-semibold">New Password</label>
                                <div class="input-group">
                                    <span class="input-group-text border-primary bg-light">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                    <input type="password" class="form-control border-primary" id="newPassword" name="newPassword" required>
                                </div>
                            </div>
                            <div class="setting-item mb-4">
                                <label for="confirmPassword" class="form-label fw-semibold">Confirm New Password</label>
                                <div class="input-group">
                                    <span class="input-group-text border-primary bg-light">
                                        <i class="bi bi-lock-fill"></i>
                                    </span>
                                    <input type="password" class="form-control border-primary" id="confirmPassword" name="confirmPassword" required>
                                </div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-settings">
                                    <i class="bi bi-shield-check me-2"></i>Change Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div> -->
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