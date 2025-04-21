<?php
session_start();
$page_title = "View Returned Book Logs";
require_once 'includes/header.php';
require_once 'includes/navbar.php';
require_once 'db_connection.php';

// Fetch the fine amount and max borrow days from the system_settings table
$settings_query = "SELECT fine_amount, max_borrow_days FROM system_settings WHERE id = 1";
$settings_result = mysqli_query($conn, $settings_query);
$settings_row = mysqli_fetch_assoc($settings_result);
$fine_amount = $settings_row['fine_amount'];
$max_borrow_days = $settings_row['max_borrow_days'];
?>

<div class="wrapper">
    <?php require_once 'includes/sidebar.php'; ?>
    <div class="main-content-students p-4">


        <!-- Header Section with Breadcrumb -->
        <div class="mb-4">
            <h6 class="mb-3">
                <a href="return.php" class="text-decoration-none text-primary">Return Books</a>
                <i class="bi bi-chevron-right small"></i>
                Return Books Logs
            </h6>
        </div>

        <!-- Display Current Settings -->
        <div class="alert alert-info">
            <strong>Current Settings:</strong><br>
            Maximum Borrow Days: <strong><?php echo $max_borrow_days; ?></strong>,<br>
            Fine Amount: <strong>₱<?php echo number_format($fine_amount, 2); ?></strong> per day.
        </div>

        <!-- Logs Table -->
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="returnLogsTable" class="table table-bordered table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Student ID</th>
                                <th>Student Name</th>
                                <th>Book ISBN</th>
                                <th>Book Title</th>
                                <th>Borrow Date</th>
                                <th>Due Date</th>
                                <th>Return Date</th>
                                <th>Status</th>
                                <th>Fine (₱)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT bb.*, s.firstname, s.lastname, b.title,
                                      DATEDIFF(bb.return_date, bb.due_date) as days_late
                                      FROM borrowed_books bb
                                      LEFT JOIN students s ON bb.student_id = s.student_id
                                      LEFT JOIN books b ON bb.book_isbn = b.isbn
                                      WHERE bb.status = 'Returned'
                                      ORDER BY bb.return_date DESC";

                            $result = mysqli_query($conn, $query);

                            while ($row = mysqli_fetch_assoc($result)) {
                                $borrow_date = new DateTime($row['borrow_date']);
                                $due_date = clone $borrow_date;
                                $due_date->modify("+$max_borrow_days days");

                                $return_date = new DateTime($row['return_date']);
                                $status = $return_date > $due_date ? 'Late' : 'On Time';
                                $statusClass = $status === 'Late' ? 'text-danger fw-bold' : 'text-success fw-bold';

                                $days_late = $return_date > $due_date ? $return_date->diff($due_date)->days : 0;
                                $fine = $days_late * $fine_amount;

                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['student_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['lastname'] . ', ' . $row['firstname']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['book_isbn']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                                echo "<td>" . date('M d, Y', strtotime($row['borrow_date'])) . "</td>";
                                echo "<td>" . $due_date->format('M d, Y') . "</td>";
                                echo "<td>" . date('M d, Y', strtotime($row['return_date'])) . "</td>";
                                echo "<td class='$statusClass'>" . $status . "</td>";
                                echo "<td class='text-end'>" . ($fine > 0 ? number_format($fine, 2) : '-') . "</td>";
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script src="js/main.js"></script>

<script>
    $(document).ready(function() {
        $('#returnLogsTable').DataTable({
            responsive: true,
            pageLength: 25,
            lengthMenu: [25, 50, 75, 100],
            order: [
                [6, 'desc']
            ], // Sort by Return Date
            language: {
                lengthMenu: "Show _MENU_ entries",
                search: "Search:",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                paginate: {
                    previous: "Previous",
                    next: "Next"
                },
                emptyTable: "No returned books records found"
            },
            columns: [
                null, // Student ID
                null, // Student Name
                null, // Book ISBN
                null, // Book Title
                null, // Borrow Date
                null, // Due Date
                null, // Return Date
                null, // Status
                {
                    className: 'text-end',
                    render: function(data, type, row) {
                        return type === 'display' && data !== '-' ? '₱' + parseFloat(data).toFixed(2) : data;
                    }
                } // Fine amount
            ]
        });
    });
</script>
</body>

</html>