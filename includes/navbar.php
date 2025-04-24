<nav class="navbar navbar-expand-lg fixed-top">
    <div class="navbar-left">
        <a class="navbar-brand" href="#">
            <i class="fa-solid fa-book-open-reader"></i>
            <span class="ms-2">LMS</span>
        </a>
    </div>
    <div class="navbar-right">
        <!-- Sidebar Toggle Button -->
        <button id="sidebarToggle" class="btn btn-link">
            <i class="fa-solid fa-bars"></i>
        </button>
        
        <!-- Admin Profile -->
        <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center">
            <img src="<?php echo isset($_SESSION['admin_image']) && !empty($_SESSION['admin_image']) 
                ? $_SESSION['admin_image'] 
                : 'images/default.jpg'; ?>" 
                 alt="Admin" 
                 class="rounded-circle me-2"
                 style="width: 40px; height: 40px; object-fit: cover;">
            <span class="fw-medium me-2"><?php echo isset($_SESSION['admin_name']) ? $_SESSION['admin_name'] : 'Admin'; ?></span>
            <img src="images/verified/verified-fb2.png" 
                 alt="Verified" 
                 style="width: 24px; height: 24px;"
                 class="me-3">
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