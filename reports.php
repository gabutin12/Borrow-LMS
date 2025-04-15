<?php
session_start();
$page_title = "Reports";
require_once 'includes/header.php';
require_once 'includes/navbar.php';
require_once 'db_connection.php';
?>

<div class="wrapper">
    <?php require_once 'includes/sidebar.php'; ?>
    <div class="main-content-reports">
        <div class="container-fluid">
            <!-- Header Section -->
            <div class="mb-4">
                <h4 class="mb-3">
                    <a href="dashboard.php" class="text-decoration-none text-primary">Dashboard</a>
                    <i class="bi bi-chevron-right small"></i>
                    Reports
                </h4>
            </div>

            <!-- Report Types Tabs -->
            <ul class="nav nav-tabs mb-4" id="reportsTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active"
                        id="borrowed-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#borrowed"
                        type="button"
                        role="tab">
                        <i class="bi bi-arrow-down-circle"></i> Borrowed Books
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link"
                        id="returned-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#returned"
                        type="button"
                        role="tab">
                        <i class="bi bi-arrow-up-circle"></i> Returned Books
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link"
                        id="students-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#students"
                        type="button"
                        role="tab">
                        <i class="bi bi-people"></i> Student Reports
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="reportsTabContent">
                <!-- Borrowed Books Report -->
                <div class="tab-pane fade show active" id="borrowed" role="tabpanel">
                    <div class="card shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Currently Borrowed Books</h5>
                            <button class="btn btn-primary btn-sm" onclick="printReport('borrowed')">
                                <i class="bi bi-printer"></i> Print Report
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="borrowedTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Student ID</th>
                                            <th>Student Name</th>
                                            <th>Book ISBN</th>
                                            <th>Book Title</th>
                                            <th>Borrow Date</th>
                                            <th>Due Date</th>
                                            <th>Status</th>
                                            <th>Days Late</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $query = "SELECT bb.*, s.firstname, s.lastname, b.title,
                                                 DATEDIFF(CURRENT_DATE, bb.due_date) as days_late
                                                 FROM borrowed_books bb
                                                 LEFT JOIN students s ON bb.student_id = s.student_id
                                                 LEFT JOIN books b ON bb.book_isbn = b.isbn
                                                 WHERE bb.status = 'Borrowed'
                                                 ORDER BY bb.borrow_date DESC";
                                        $result = mysqli_query($conn, $query);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $days_late = max(0, $row['days_late']);
                                            $status_class = $days_late > 0 ? 'text-danger' : 'text-warning';
                                            echo "<tr>";
                                            echo "<td>" . htmlspecialchars($row['student_id']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['lastname'] . ', ' . $row['firstname']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['book_isbn']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                                            echo "<td>" . date('M d, Y', strtotime($row['borrow_date'])) . "</td>";
                                            echo "<td>" . date('M d, Y', strtotime($row['due_date'])) . "</td>";
                                            echo "<td class='{$status_class} fw-bold'>" . ($days_late > 0 ? 'Overdue' : 'Borrowed') . "</td>";
                                            echo "<td>" . ($days_late > 0 ? $days_late : '-') . "</td>";
                                            echo "</tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Returned Books Report -->
                <div class="tab-pane fade" id="returned" role="tabpanel">
                    <div class="card shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Returned Books History</h5>
                            <button class="btn btn-primary btn-sm" onclick="printReport('returned')">
                                <i class="bi bi-printer"></i> Print Report
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="returnedTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Student ID</th>
                                            <th>Student Name</th>
                                            <th>Book ISBN</th>
                                            <th>Book Title</th>
                                            <th>Borrow Date</th>
                                            <th>Due Date</th>
                                            <th>Return Date</th>
                                            <th>Return Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $query = "SELECT bb.*, s.firstname, s.lastname, b.title,
                                                 CASE 
                                                    WHEN bb.return_date <= bb.due_date THEN 'On Time'
                                                    ELSE 'Late'
                                                 END as return_status
                                                 FROM borrowed_books bb
                                                 LEFT JOIN students s ON bb.student_id = s.student_id
                                                 LEFT JOIN books b ON bb.book_isbn = b.isbn
                                                 WHERE bb.status = 'Returned'
                                                 ORDER BY bb.return_date DESC";
                                        $result = mysqli_query($conn, $query);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $status_class = $row['return_status'] === 'Late' ? 'text-danger' : 'text-success';
                                            echo "<tr>";
                                            echo "<td>" . htmlspecialchars($row['student_id']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['lastname'] . ', ' . $row['firstname']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['book_isbn']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                                            echo "<td>" . date('M d, Y', strtotime($row['borrow_date'])) . "</td>";
                                            echo "<td>" . date('M d, Y', strtotime($row['due_date'])) . "</td>";
                                            echo "<td>" . date('M d, Y', strtotime($row['return_date'])) . "</td>";
                                            echo "<td class='{$status_class} fw-bold'>" . $row['return_status'] . "</td>";
                                            echo "</tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Student Reports -->
                <div class="tab-pane fade" id="students" role="tabpanel">
                    <div class="card shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Student Borrowing Statistics</h5>
                            <button class="btn btn-primary btn-sm" onclick="printReport('students')">
                                <i class="bi bi-printer"></i> Print Report
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="studentsTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Student ID</th>
                                            <th>Student Name</th>
                                            <th>Course</th>
                                            <th>Total Books Borrowed</th>
                                            <th>Currently Borrowed</th>
                                            <th>Total Returned</th>
                                            <th>On-Time Returns</th>
                                            <th>Late Returns</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $query = "SELECT 
                                                    s.student_id,
                                                    s.firstname,
                                                    s.lastname,
                                                    s.course,
                                                    COUNT(bb.id) as total_borrowed,
                                                    SUM(CASE WHEN bb.status = 'Borrowed' THEN 1 ELSE 0 END) as currently_borrowed,
                                                    SUM(CASE WHEN bb.status = 'Returned' THEN 1 ELSE 0 END) as total_returned,
                                                    SUM(CASE WHEN bb.status = 'Returned' AND bb.return_date <= bb.due_date THEN 1 ELSE 0 END) as ontime_returns,
                                                    SUM(CASE WHEN bb.status = 'Returned' AND bb.return_date > bb.due_date THEN 1 ELSE 0 END) as late_returns
                                                FROM students s
                                                LEFT JOIN borrowed_books bb ON s.student_id = bb.student_id
                                                GROUP BY s.student_id
                                                ORDER BY total_borrowed DESC";
                                        $result = mysqli_query($conn, $query);
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<tr>";
                                            echo "<td>" . htmlspecialchars($row['student_id']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['lastname'] . ', ' . $row['firstname']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['course']) . "</td>";
                                            echo "<td>" . $row['total_borrowed'] . "</td>";
                                            echo "<td>" . $row['currently_borrowed'] . "</td>";
                                            echo "<td>" . $row['total_returned'] . "</td>";
                                            echo "<td class='text-success'>" . $row['ontime_returns'] . "</td>";
                                            echo "<td class='text-danger'>" . $row['late_returns'] . "</td>";
                                            echo "</tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>
<script src="js/main.js"></script>

</body>

<script>
    $(document).ready(function() {
        // Initialize Bootstrap tabs
        const tabElements = document.querySelectorAll('button[data-bs-toggle="tab"]');
        tabElements.forEach(function(tabElement) {
            new bootstrap.Tab(tabElement);
        });

        // Handle tab switching
        $('#reportsTab button').on('click', function(e) {
            e.preventDefault();
            $(this).tab('show');
        });

        // Initialize DataTables for all tables
        $('#borrowedTable, #returnedTable, #studentsTable').DataTable({
            pageLength: 25,
            lengthMenu: [25, 50, 75, 100],
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'copy',
                    className: 'btn btn-secondary btn-sm'
                },
                {
                    extend: 'excel',
                    className: 'btn btn-success btn-sm'
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-danger btn-sm'
                }
            ]
        });
    });

    // Updated Print report function
    function printReport(reportType) {
        // Import jsPDF
        const {
            jsPDF
        } = window.jspdf;

        // Create new PDF document
        const doc = new jsPDF('landscape');

        // Set document properties
        doc.setProperties({
            title: `${reportType} Report`,
            subject: 'Library Management System Report',
            author: 'LMS Admin',
            keywords: 'library, report, books',
            creator: 'LMS System'
        });

        // Add header
        doc.setFontSize(18);
        doc.setTextColor(41, 128, 185);
        doc.text('Library Management System - Reports', 15, 15);

        doc.setFontSize(14);
        doc.setTextColor(0, 0, 0);
        doc.text(`${reportType.charAt(0).toUpperCase() + reportType.slice(1)} Report`, 15, 25);

        doc.setFontSize(10);
        doc.text(`Generated on: ${new Date().toLocaleString()}`, 15, 30);

        // Generate table
        doc.autoTable({
            html: `#${reportType}Table`,
            startY: 35,
            theme: 'grid',
            styles: {
                fontSize: 8,
                cellPadding: 2
            },
            headStyles: {
                fillColor: [41, 128, 185],
                textColor: 255,
                fontSize: 9,
                fontStyle: 'bold',
                halign: 'center'
            },
            alternateRowStyles: {
                fillColor: [245, 245, 245]
            },
            margin: {
                top: 35
            }
        });

        // Save the PDF
        try {
            doc.save(`${reportType}_report_${new Date().toISOString().split('T')[0]}.pdf`);
        } catch (error) {
            console.error('PDF generation failed:', error);
            alert('Failed to generate PDF. Please try again.');
        }
    }
</script>

<?php require_once 'includes/footer.php'; ?>

</html>