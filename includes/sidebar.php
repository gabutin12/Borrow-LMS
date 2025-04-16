<div class="sidebar" id="sidebarMenu">
    <!-- Profile Section -->
    <div class="profile-section shadow-sm bg-dark rounded">
        <div class="d-flex align-items-center p-3">
            <div class="position-relative">
                <img src="images/default.jpg" class="rounded-circle shadow-sm" width="45" height="45" alt="Profile">
                <span class="position-absolute bottom-0 end-0 p-1 bg-success border border-light rounded-circle"></span>
            </div>
            <div class="ms-3">
                <h6 class="text-white mb-0 fw-semibold"><?php echo htmlspecialchars($_SESSION['username']); ?></h6>
                <small class="text-success">Active Now</small>
            </div>
        </div>
    </div>

    <!-- Navigation Links -->
    <ul class="nav nav-pills flex-column shadow-sm">
        <!-- Reports Section -->
        <li class="nav-header mt-3">
            <span class="nav-header-text">HOME</span>
        </li>
        <li class="nav-item">
            <a href="dashboard.php" class="nav-link <?php echo ($page_title == 'Dashboard') ? 'active' : ''; ?>">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <!-- Management Section -->
        <li class="nav-header">
            <span class="nav-header-text">MANAGE</span>
        </li>
        <!-- Transactions Dropdown -->
        <li class="nav-item">
            <a class="nav-link dropdown-toggle d-flex justify-content-between align-items-center <?php echo ($page_title == 'Borrow Books' || $page_title == 'Return Books') ? '' : 'collapsed'; ?>" href="#" data-bs-toggle="collapse" data-bs-target="#transactionsDropdown" aria-expanded="<?php echo ($page_title == 'Borrow Books' || $page_title == 'Return Books') ? 'true' : 'false'; ?>">
                <span><i class="bi bi-arrow-left-right"></i> Transaction</span>
            </a>
            <ul class="collapse nav flex-column ms-4 <?php echo ($page_title == 'Borrow Books' || $page_title == 'Return Books') ? 'show' : ''; ?>" id="transactionsDropdown">
                <li class="nav-item">
                    <a href="borrow.php" class="nav-link <?php echo ($page_title == 'Borrow Books') ? 'active' : ''; ?>">
                        <i class="bi bi-arrow-down-circle"></i> Borrow Books
                    </a>
                </li>
                <li class="nav-item">
                    <a href="return.php" class="nav-link <?php echo ($page_title == 'Return Books') ? 'active' : ''; ?>">
                        <i class="bi bi-arrow-up-circle"></i> Return Books
                    </a>
                </li>
            </ul>
        </li>

        <!-- Books Dropdown -->
        <li class="nav-item">
            <a class="nav-link dropdown-toggle d-flex justify-content-between align-items-center <?php echo ($page_title == 'List of Books' || $page_title == 'Category') ? '' : 'collapsed'; ?>" href="#" data-bs-toggle="collapse" data-bs-target="#booksDropdown" aria-expanded="<?php echo ($page_title == 'List of Books' || $page_title == 'Category') ? 'true' : 'false'; ?>">
                <span><i class="bi bi-book"></i> Books</span>
            </a>
            <ul class="collapse nav flex-column ms-4 <?php echo ($page_title == 'List of Books' || $page_title == 'Category') ? 'show' : ''; ?>" id="booksDropdown">
                <li class="nav-item">
                    <a href="list_books.php" class="nav-link <?php echo ($page_title == 'List of Books') ? 'active' : ''; ?>">
                        <i class="bi bi-journal-text"></i> List of Books
                    </a>
                </li>
                <li class="nav-item">
                    <a href="category.php" class="nav-link <?php echo ($page_title == 'Category') ? 'active' : ''; ?>">
                        <i class="bi bi-tags"></i> Category
                    </a>
                </li>
            </ul>
        </li>

        <!-- Students Dropdown -->
        <li class="nav-item">
            <a class="nav-link dropdown-toggle d-flex justify-content-between align-items-center <?php echo ($page_title == 'List of Students' || $page_title == 'Courses') ? '' : 'collapsed'; ?>" href="#" data-bs-toggle="collapse" data-bs-target="#studentsDropdown" aria-expanded="<?php echo ($page_title == 'List of Students' || $page_title == 'Courses') ? 'true' : 'false'; ?>">
                <span><i class="bi bi-people"></i> Students</span>
            </a>
            <ul class="collapse nav flex-column ms-4 <?php echo ($page_title == 'List of Students' || $page_title == 'Courses') ? 'show' : ''; ?>" id="studentsDropdown">
                <li class="nav-item">
                    <a href="list_students.php" class="nav-link <?php echo ($page_title == 'List of Students') ? 'active' : ''; ?>">
                        <i class="bi bi-person-lines-fill"></i> List of Students
                    </a>
                </li>
                <li class="nav-item">
                    <a href="courses.php" class="nav-link <?php echo ($page_title == 'Courses') ? 'active' : ''; ?>">
                        <i class="bi bi-bookmark"></i> Courses
                    </a>
                </li>
            </ul>
        </li>

        <!-- Management Settings -->
        <li class="nav-header">
            <span class="nav-header-text">MANAGE SETTINGS</span>
        </li>
        <li class="nav-item">
            <a class="nav-link dropdown-toggle d-flex justify-content-between align-items-center <?php echo ($page_title == 'Settings' || $page_title == 'Admin Management' || $page_title == 'Reports' || $page_title == 'Change Password' || $page_title == 'About Us') ? '' : 'collapsed'; ?>"
                href="#"
                data-bs-toggle="collapse"
                data-bs-target="#settingsDropdown"
                aria-expanded="<?php echo ($page_title == 'Settings' || $page_title == 'Admin Management' || $page_title == 'Reports' || $page_title == 'Change Password' || $page_title == 'About Us') ? 'true' : 'false'; ?>">
                <span><i class="bi bi-gear"></i> Settings</span>
            </a>
            <ul class="collapse nav flex-column ms-4 <?php echo ($page_title == 'Settings' || $page_title == 'Admin Management' || $page_title == 'Reports' || $page_title == 'Change Password' || $page_title == 'About Us') ? 'show' : ''; ?>" id="settingsDropdown">
                <li class="nav-item">
                    <a href="settings.php" class="nav-link <?php echo ($page_title == 'Settings') ? 'active' : ''; ?>">
                        <i class="bi bi-sliders"></i> System Settings
                    </a>
                </li>
                <li class="nav-item">
                    <a href="admin_management.php" class="nav-link <?php echo ($page_title == 'Admin Management') ? 'active' : ''; ?>">
                        <i class="bi bi-person-circle"></i> Admin Management
                    </a>
                </li>
                <li class="nav-item">
                    <a href="reports.php" class="nav-link <?php echo ($page_title == 'Reports') ? 'active' : ''; ?>">
                        <i class="bi bi-file-earmark-text"></i> Reports
                    </a>
                </li>
                <li class="nav-item">
                    <a href="change_password.php" class="nav-link <?php echo ($page_title == 'Change Password') ? 'active' : ''; ?>">
                        <i class="bi bi-key"></i> Change Password
                    </a>
                </li>
                <li class="nav-item">
                    <a href="about.php" class="nav-link <?php echo ($page_title == 'About Us') ? 'active' : ''; ?>">
                        <i class="bi bi-info-circle"></i> About us
                    </a>
                </li>
                <li class="nav-item">
                    <a href="logout.php" class="nav-link">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</div>