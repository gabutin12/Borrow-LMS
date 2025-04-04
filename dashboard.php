<?php
session_start();
$page_title = "Dashboard";
require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>

<div class="wrapper">
    <?php require_once 'includes/sidebar.php'; ?>
    <div class="main-content">
        <div class="container-fluid py-4">
            <div class="row g-4">
                <!-- Total Books Card -->
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="card-title text-muted">Total Books</h5>
                                    <p class="card-text fs-2 fw-bold mb-0">150</p>
                                </div>
                                <div class="icon-shape bg-primary bg-opacity-10 text-primary rounded p-3">
                                    <i class="bi bi-book fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Students Card -->
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="card-title text-muted">Total Students</h5>
                                    <p class="card-text fs-2 fw-bold mb-0">75</p>
                                </div>
                                <div class="icon-shape bg-success bg-opacity-10 text-success rounded p-3">
                                    <i class="bi bi-people fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Returned Today Card -->
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="card-title text-muted">Returned Today</h5>
                                    <p class="card-text fs-2 fw-bold mb-0">12</p>
                                </div>
                                <div class="icon-shape bg-info bg-opacity-10 text-info rounded p-3">
                                    <i class="bi bi-arrow-return-left fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Borrowed Today Card -->
                <div class="col-12 col-md-6 col-xl-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="card-title text-muted">Borrowed Today</h5>
                                    <p class="card-text fs-2 fw-bold mb-0">8</p>
                                </div>
                                <div class="icon-shape bg-warning bg-opacity-10 text-warning rounded p-3">
                                    <i class="bi bi-arrow-right-circle fs-4"></i>
                                </div>
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
</body>

</html>