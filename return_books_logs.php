<?php
session_start();
$page_title = "View Returned Book Logs";
require_once 'includes/header.php';
require_once 'includes/navbar.php';
require_once 'db_connection.php';
?>

<div class="wrapper">
    <?php require_once 'includes/sidebar.php'; ?>
    <div class="main-content-students p-4">
        <!-- Header Section with Breadcrumb -->
        <div class="mb-4">
            <h4 class="mb-3">
                <a href="return.php" class="text-decoration-none text-primary">Return Books</a>
                <i class="bi bi-chevron-right small"></i>
                Return Books Logs
            </h4>
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
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = "SELECT bb.*, s.firstname, s.lastname, b.title 
                                    FROM borrowed_books bb
                                    LEFT JOIN students s ON bb.student_id = s.student_id
                                    LEFT JOIN books b ON bb.book_isbn = b.isbn
                                    WHERE bb.status = 'Returned'
                                    ORDER BY bb.return_date DESC";

                            $result = mysqli_query($conn, $query);

                            while ($row = mysqli_fetch_assoc($result)) {
                                // Calculate if the return was on time or late
                                $due_date = new DateTime($row['due_date']);
                                $return_date = new DateTime($row['return_date']);
                                $status = $return_date > $due_date ? 'Late' : 'On Time';
                                $statusClass = $status === 'Late' ? 'text-danger fw-bold' : 'text-success fw-bold';

                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['student_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['lastname'] . ', ' . $row['firstname']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['book_isbn']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                                echo "<td>" . date('M d, Y', strtotime($row['borrow_date'])) . "</td>";
                                echo "<td>" . date('M d, Y', strtotime($row['due_date'])) . "</td>";
                                echo "<td>" . date('M d, Y', strtotime($row['return_date'])) . "</td>";
                                echo "<td class='$statusClass'>" . $status . "</td>";
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(document).ready(function() {
        $('#returnLogsTable').DataTable({
            responsive: true,
            pageLength: 25,
            lengthMenu: [25, 50, 75, 100],
            order: [
                [6, 'desc']
            ], // Sort by Return Date by default
            language: {
                lengthMenu: "Show _MENU_ entries",
                search: "Search:",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                paginate: {
                    previous: "Previous",
                    next: "Next"
                },
                emptyTable: "No returned books records found"
            }
        });
    });
</script>

</body>

</html>