<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    header("Location: login.php");
    exit();
}

// Sample data - in real system this would come from database
$bookings = [
    [
        'id' => 1001,
        'user' => 'John Doe',
        'slot' => 'A-12',
        'start_time' => '2023-06-15 09:00',
        'end_time' => '2023-06-15 17:00',
        'status' => 'Active',
        'amount' => 40.00
    ],
    [
        'id' => 1002,
        'user' => 'Jane Smith',
        'slot' => 'B-05',
        'start_time' => '2023-06-15 10:30',
        'end_time' => '2023-06-15 14:30',
        'status' => 'Active',
        'amount' => 18.00
    ],
    [
        'id' => 1003,
        'user' => 'Robert Johnson',
        'slot' => 'C-08',
        'start_time' => '2023-06-14 08:00',
        'end_time' => '2023-06-14 18:00',
        'status' => 'Completed',
        'amount' => 40.00
    ],
    [
        'id' => 1004,
        'user' => 'Sarah Williams',
        'slot' => 'A-03',
        'start_time' => '2023-06-16 11:00',
        'end_time' => '2023-06-16 15:00',
        'status' => 'Upcoming',
        'amount' => 20.00
    ],
    [
        'id' => 1005,
        'user' => 'Michael Brown',
        'slot' => 'D-02',
        'start_time' => '2023-06-13 13:00',
        'end_time' => '2023-06-13 16:00',
        'status' => 'Cancelled',
        'amount' => 15.00
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings | Parking Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
        .booking-status {
            font-weight: 500;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.85rem;
        }
        
        .status-active { background-color: #e8f5e9; color: #2e7d32; }
        .status-upcoming { background-color: #e3f2fd; color: #1565c0; }
        .status-completed { background-color: #f5f5f5; color: #424242; }
        .status-cancelled { background-color: #ffebee; color: #c62828; }
        
        .table-responsive {
            overflow-x: auto;
        }
        
        .table th {
            white-space: nowrap;
        }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <?php include '../includes/admin-sidebar.php'; ?>
        
        <div class="admin-content">
            <div class="container-fluid py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Manage Bookings</h1>
                    <div>
                        <button class="btn btn-outline-secondary me-2">
                            <i class="fas fa-download me-1"></i> Export
                        </button>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filterModal">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Booking ID</th>
                                        <th>User</th>
                                        <th>Parking Slot</th>
                                        <th>Start Time</th>
                                        <th>End Time</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($bookings as $booking): ?>
                                    <tr>
                                        <td>#<?= $booking['id'] ?></td>
                                        <td><?= $booking['user'] ?></td>
                                        <td><?= $booking['slot'] ?></td>
                                        <td><?= $booking['start_time'] ?></td>
                                        <td><?= $booking['end_time'] ?></td>
                                        <td>$<?= number_format($booking['amount'], 2) ?></td>
                                        <td>
                                            <span class="booking-status status-<?= strtolower($booking['status']) ?>">
                                                <?= $booking['status'] ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary me-1" 
                                                    data-bs-toggle="tooltip" 
                                                    title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning me-1" 
                                                    data-bs-toggle="tooltip" 
                                                    title="Edit Booking">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <?php if ($booking['status'] === 'Active' || $booking['status'] === 'Upcoming'): ?>
                                            <button class="btn btn-sm btn-outline-danger" 
                                                    data-bs-toggle="tooltip" 
                                                    title="Cancel Booking">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center mt-4">
                                <li class="page-item disabled">
                                    <a class="page-link" href="#" tabindex="-1">Previous</a>
                                </li>
                                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item">
                                    <a class="page-link" href="#">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Filter Bookings</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="filterForm">
                        <div class="mb-3">
                            <label for="filterStatus" class="form-label">Status</label>
                            <select class="form-select" id="filterStatus">
                                <option value="">All Statuses</option>
                                <option value="Active">Active</option>
                                <option value="Upcoming">Upcoming</option>
                                <option value="Completed">Completed</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="filterDate" class="form-label">Date Range</label>
                            <div class="input-daterange input-group">
                                <input type="date" class="form-control" name="start" id="filterStartDate">
                                <span class="input-group-text">to</span>
                                <input type="date" class="form-control" name="end" id="filterEndDate">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="filterZone" class="form-label">Parking Zone</label>
                            <select class="form-select" id="filterZone">
                                <option value="">All Zones</option>
                                <option value="A">Zone A</option>
                                <option value="B">Zone B</option>
                                <option value="C">Zone C</option>
                                <option value="D">Zone D</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="applyFilters">Apply Filters</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Handle apply filters button
        document.getElementById('applyFilters').addEventListener('click', function() {
            // In a real system, this would apply the filters and refresh the table
            alert('Filters applied!');
            $('#filterModal').modal('hide');
        });

        // Handle cancel booking buttons
        document.querySelectorAll('.btn-outline-danger').forEach(button => {
            button.addEventListener('click', function() {
                if (confirm('Are you sure you want to cancel this booking?')) {
                    // In a real system, this would send a request to cancel the booking
                    alert('Booking cancelled!');
                }
            });
        });
    </script>
</body>
</html>