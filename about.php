<?php
session_start();
$page_title = "About Us";
require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>

<div class="wrapper">
    <?php require_once 'includes/sidebar.php'; ?>
    <div class="main-content-aboutus">
        <div class="container-fluid">
            <!-- Header Section -->
            <div class="mb-4">
                <h4 class="mb-3">
                    <a href="dashboard.php" class="text-decoration-none text-primary">Dashboard</a>
                    <i class="bi bi-chevron-right small"></i>
                    About Us
                </h4>
            </div>



            <!-- Library Kiosk Information Section -->
            <div class="kiosk-info mb-5">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h3 class="card-title text-primary mb-4">Library Front Desk Kiosk System</h3>
                        <p class="lead mb-4">
                            Our Library Kiosk System streamlines front desk operations by providing a centralized point for all library transactions.
                            Students can easily approach the front desk where librarians use this integrated system to manage borrowing, returns,
                            and other library services efficiently.
                        </p>
                        <div class="row g-4">
                            <!-- Service Points Dropdown -->
                            <div class="col-md-6">
                                <div class="dropdown-box">
                                    <div class="dropdown-trigger p-3 bg-light rounded">
                                        <h5 class="text-primary mb-0">
                                            <i class="bi bi-laptop me-2"></i>Front Desk Services
                                            <i class="bi bi-chevron-down float-end"></i>
                                        </h5>
                                    </div>
                                    <div class="dropdown-content">
                                        <ul class="list-unstyled feature-list p-3">
                                            <li class="mb-2">
                                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                                <span class="feature-text">Quick Book Borrowing Processing</span>
                                            </li>
                                            <li class="mb-2">
                                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                                <span class="feature-text">Streamlined Return Handling</span>
                                            </li>
                                            <li class="mb-2">
                                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                                <span class="feature-text">Student ID Verification</span>
                                            </li>
                                            <li class="mb-2">
                                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                                <span class="feature-text">Due Date Management</span>
                                            </li>
                                            <li class="mb-2">
                                                <i class="bi bi-check-circle-fill text-success me-2"></i>
                                                <span class="feature-text">Book Availability Checking</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Advantages Dropdown -->
                            <div class="col-md-6">
                                <div class="dropdown-box">
                                    <div class="dropdown-trigger p-3 bg-light rounded">
                                        <h5 class="text-primary mb-0">
                                            <i class="bi bi-graph-up-arrow me-2"></i>Kiosk Advantages
                                            <i class="bi bi-chevron-down float-end"></i>
                                        </h5>
                                    </div>
                                    <div class="dropdown-content">
                                        <ul class="list-unstyled feature-list p-3">
                                            <li class="mb-2">
                                                <i class="bi bi-star-fill text-warning me-2"></i>
                                                <span class="feature-text">Reduced Wait Times</span>
                                            </li>
                                            <li class="mb-2">
                                                <i class="bi bi-star-fill text-warning me-2"></i>
                                                <span class="feature-text">One-Stop Service Point</span>
                                            </li>
                                            <li class="mb-2">
                                                <i class="bi bi-star-fill text-warning me-2"></i>
                                                <span class="feature-text">Instant Transaction Records</span>
                                            </li>
                                            <li class="mb-2">
                                                <i class="bi bi-star-fill text-warning me-2"></i>
                                                <span class="feature-text">Enhanced Student Experience</span>
                                            </li>
                                            <li class="mb-2">
                                                <i class="bi bi-star-fill text-warning me-2"></i>
                                                <span class="feature-text">Real-time System Updates</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Team Section -->
            <div class="team-section mb-5">
                <h3 class="section-title text-center mb-5">Meet Our Development Team</h3>
                <div class="row g-4 justify-content-center">
                    <!-- Team Members (6 members) -->
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="team-member h-100">
                            <div class="member-img">
                                <img src="images/default.jpg" alt="Team Member" class="img-fluid">
                                <div class="social-icons">
                                    <a href="#" target="_blank"><i class="bi bi-github"></i></a>
                                    <a href="#" target="_blank"><i class="bi bi-linkedin"></i></a>
                                    <a href="mailto:#"><i class="bi bi-envelope"></i></a>
                                </div>
                            </div>
                            <div class="member-info">
                                <h5>Team Member 1</h5>
                                <span>Project Manager</span>
                                <p>Oversees project development and team coordination</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="team-member h-100">
                            <div class="member-img">
                                <img src="images/default.jpg" alt="Team Member" class="img-fluid">
                                <div class="social-icons">
                                    <a href="#" target="_blank"><i class="bi bi-github"></i></a>
                                    <a href="#" target="_blank"><i class="bi bi-linkedin"></i></a>
                                    <a href="mailto:#"><i class="bi bi-envelope"></i></a>
                                </div>
                            </div>
                            <div class="member-info">
                                <h5>Team Member 2</h5>
                                <span>Project Manager</span>
                                <p>Oversees project development and team coordination</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="team-member h-100">
                            <div class="member-img">
                                <img src="images/default.jpg" alt="Team Member" class="img-fluid">
                                <div class="social-icons">
                                    <a href="#" target="_blank"><i class="bi bi-github"></i></a>
                                    <a href="#" target="_blank"><i class="bi bi-linkedin"></i></a>
                                    <a href="mailto:#"><i class="bi bi-envelope"></i></a>
                                </div>
                            </div>
                            <div class="member-info">
                                <h5>Team Member 3</h5>
                                <span>Project Manager</span>
                                <p>Oversees project development and team coordination</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="team-member h-100">
                            <div class="member-img">
                                <img src="images/default.jpg" alt="Team Member" class="img-fluid">
                                <div class="social-icons">
                                    <a href="#" target="_blank"><i class="bi bi-github"></i></a>
                                    <a href="#" target="_blank"><i class="bi bi-linkedin"></i></a>
                                    <a href="mailto:#"><i class="bi bi-envelope"></i></a>
                                </div>
                            </div>
                            <div class="member-info">
                                <h5>Team Member 4</h5>
                                <span>Project Manager</span>
                                <p>Oversees project development and team coordination</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="team-member h-100">
                            <div class="member-img">
                                <img src="images/default.jpg" alt="Team Member" class="img-fluid">
                                <div class="social-icons">
                                    <a href="#" target="_blank"><i class="bi bi-github"></i></a>
                                    <a href="#" target="_blank"><i class="bi bi-linkedin"></i></a>
                                    <a href="mailto:#"><i class="bi bi-envelope"></i></a>
                                </div>
                            </div>
                            <div class="member-info">
                                <h5>Team Member 5</h5>
                                <span>Project Manager</span>
                                <p>Oversees project development and team coordination</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="team-member h-100">
                            <div class="member-img">
                                <img src="images/default.jpg" alt="Team Member" class="img-fluid">
                                <div class="social-icons">
                                    <a href="#" target="_blank"><i class="bi bi-github"></i></a>
                                    <a href="#" target="_blank"><i class="bi bi-linkedin"></i></a>
                                    <a href="mailto:#"><i class="bi bi-envelope"></i></a>
                                </div>
                            </div>
                            <div class="member-info">
                                <h5>Team Member 6</h5>
                                <span>Project Manager</span>
                                <p>Oversees project development and team coordination</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/main.js"></script>
<style>
    /* Kiosk Information Dropdown Styles */
    .dropdown-box {
        position: relative;
    }

    .dropdown-trigger {
        cursor: pointer;
        border: 1px solid rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .dropdown-trigger:hover {
        background-color: #f8f9fa;
        border-color: #2193b0;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 0 0 8px 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        z-index: 1000;
        margin-top: 2px;
    }

    .dropdown-box:hover .dropdown-content {
        display: block;
    }

    .dropdown-content .feature-list {
        margin: 0;
    }

    .dropdown-content li {
        padding: 8px;
        transition: all 0.3s ease;
    }

    .dropdown-content li:hover {
        background-color: #f8f9fa;
        transform: translateX(5px);
    }

    .dropdown-trigger .bi-chevron-down {
        transition: transform 0.3s ease;
    }

    .dropdown-box:hover .bi-chevron-down {
        transform: rotate(180deg);
    }
</style>
</body>

</html>