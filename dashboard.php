<?php
session_start();
$page_title = "Dashboard";
require_once 'includes/header.php';
require_once 'includes/navbar.php';
require_once 'db_connection.php';
require_once 'includes/dashboard_stats.php';

// Get all dashboard statistics
$stats = getDashboardStats($conn);
$total_books = $stats['total_books'];
$total_students = $stats['total_students'];
$returned_books = $stats['returned_books'];
$borrowed_books = $stats['borrowed_books'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admin WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Set session variables
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_name'] = $row['username'];
            $_SESSION['admin_image'] = $row['admin_image'];

            header("Location: dashboard.php");
            exit();
        }
    }

    // If login fails
    $_SESSION['error'] = "Invalid username or password";
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Dashboard</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <div class="wrapper">
        <?php require_once 'includes/sidebar.php'; ?>
        <div class="main-content">
            <!-- Header Section -->
            <div class="mb-4 text-bold">
                <h2 class="mb-3">Dashboard</h2>
            </div>
            <div class="con tainer-fluid py-4">
                <div class="row g-4">
                    <!-- Total Books Card -->
                    <div class="col-12 col-md-6 col-xl-3">
                        <a href="list_books.php" class="text-decoration-none">
                            <div class="card shadow-sm hover-card stat-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h5 class="card-title text-muted">Total Books</h5>
                                            <p class="card-text fs-2 fw-bold mb-0"><?php echo $total_books; ?></p>
                                        </div>
                                        <div class="icon-shape bg-primary bg-opacity-10 text-primary rounded p-3">
                                            <i class="bi bi-book fs-4"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Total Students Card -->
                    <div class="col-12 col-md-6 col-xl-3">
                        <a href="list_students.php" class="text-decoration-none">
                            <div class="card shadow-sm hover-card stat-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h5 class="card-title text-muted">Total Students</h5>
                                            <p class="card-text fs-2 fw-bold mb-0"><?php echo $total_students; ?></p>
                                        </div>
                                        <div class="icon-shape bg-success bg-opacity-10 text-success rounded p-3">
                                            <i class="bi bi-people fs-4"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Returned Books Card -->
                    <div class="col-12 col-md-6 col-xl-3">
                        <a href="return_books_logs.php" class="text-decoration-none">
                            <div class="card shadow-sm hover-card stat-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h5 class="card-title text-muted">Returned Books</h5>
                                            <p class="card-text fs-2 fw-bold mb-0"><?php echo $returned_books; ?></p>
                                        </div>
                                        <div class="icon-shape bg-info bg-opacity-10 text-info rounded p-3">
                                            <i class="bi bi-arrow-return-left fs-4"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Borrowed Books Card -->
                    <div class="col-12 col-md-6 col-xl-3">
                        <a href="borrowed_books_logs.php" class="text-decoration-none">
                            <div class="card shadow-sm hover-card stat-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h5 class="card-title text-muted">Borrowed Books</h5>
                                            <p class="card-text fs-2 fw-bold mb-0"><?php echo $borrowed_books; ?></p>
                                        </div>
                                        <div class="icon-shape bg-warning bg-opacity-10 text-warning rounded p-3">
                                            <i class="bi bi-arrow-right-circle fs-4"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

            <!-- After the cards section, add this new section for charts -->
            <div class="row mt-4">
                <!-- Pie Chart -->
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Library Statistics</h5>
                            <div style="position: relative; height: 300px;">
                                <canvas id="pieChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Bar Chart -->
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Books Status</h5>
                            <div style="position: relative; height: 300px;">
                                <canvas id="barChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- After the charts section -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Library Gallery</h5>
                    </div>
                    <div class="card-body p-0">
                        <?php include('./includes/slide.php'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Update the chart configurations

        // Pie Chart
        const pieCtx = document.getElementById('pieChart').getContext('2d');
        new Chart(pieCtx, {
            type: 'pie',
            data: {
                labels: ['Total Books', 'Total Students', 'Returned Books', 'Borrowed Books'],
                datasets: [{
                    data: [
                        <?php echo $total_books; ?>,
                        <?php echo $total_students; ?>,
                        <?php echo $returned_books; ?>,
                        <?php echo $borrowed_books; ?>
                    ],
                    backgroundColor: [
                        'rgba(13, 110, 253, 0.7)', // primary
                        'rgba(25, 135, 84, 0.7)', // success
                        'rgba(13, 202, 240, 0.7)', // info
                        'rgba(255, 193, 7, 0.7)' // warning
                    ],
                    borderColor: [
                        'rgba(13, 110, 253, 1)',
                        'rgba(25, 135, 84, 1)',
                        'rgba(13, 202, 240, 1)',
                        'rgba(255, 193, 7, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'right',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: {
                                size: 12
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: 'Overall Library Statistics',
                        font: {
                            size: 16,
                            weight: 'bold'
                        },
                        padding: {
                            bottom: 15
                        }
                    }
                }
            }
        });

        // Bar Chart
        const barCtx = document.getElementById('barChart').getContext('2d');
        new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: ['Total Books', 'Borrowed', 'Returned'],
                datasets: [{
                    label: 'Total Books',
                    data: [<?php echo $total_books; ?>, 0, 0],
                    backgroundColor: 'rgba(13, 110, 253, 0.7)',
                    borderColor: 'rgba(13, 110, 253, 1)',
                    borderWidth: 1
                }, {
                    label: 'Borrowed Books',
                    data: [0, <?php echo $borrowed_books; ?>, 0],
                    backgroundColor: 'rgba(255, 193, 7, 0.7)',
                    borderColor: 'rgba(255, 193, 7, 1)',
                    borderWidth: 1
                }, {
                    label: 'Returned Books',
                    data: [0, 0, <?php echo $returned_books; ?>],
                    backgroundColor: 'rgba(13, 202, 240, 0.7)',
                    borderColor: 'rgba(13, 202, 240, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'right',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'rect',
                            font: {
                                size: 12
                            }
                        }
                    },
                    title: {
                        display: true,
                        text: 'Books Distribution',
                        font: {
                            size: 16,
                            weight: 'bold'
                        },
                        padding: {
                            bottom: 15
                        }
                    }
                }
            }
        });
    </script>
    <script src="js/scripts.js"></script>
    <script src="js/dashboard.js"></script>
    <script src="js/slide.js"></script>
    <!-- footer -->
    <?php require_once 'includes/footer.php'; ?>
    </body>
</html>