<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
    <div class="navbar-left d-flex align-items-center justify-content-center">
        <span class="text-white fs-4 fw-bold">LMS</span>
    </div>
    <div class="navbar-right bg-white d-flex align-items-center justify-content-between w-100">
        <i class="fa-solid fa-bars" id="sidebarToggle"></i>
        
        <!-- Admin Profile and Logout -->
        <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center">
                <img src="<?php echo isset($_SESSION['admin_image']) && !empty($_SESSION['admin_image']) 
                    ? $_SESSION['admin_image'] 
                    : 'images/default.jpg'; ?>" 
                     alt="Admin" 
                     class="rounded-circle me-2"
                     style="width: 40px; height: 40px; object-fit: cover;">
                <span class="fw-medium me-3"><?php echo isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : 'Admin'; ?></span>
            </div>
            <div class="nav-item" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Logout">
                <a href="logout.php" class="nav-link">
                    <i class="bi bi-box-arrow-right fs-5"></i>
                </a>
            </div>
        </div>
    </div>
</nav>

<script>
document.getElementById('sidebarToggle').addEventListener('click', function() {
    document.querySelector('.wrapper').classList.toggle('sidebar-collapsed');
});
</script>