<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    header("Location: login.php");
    exit();
}

// Sample data - in real system this would come from database
$reports = [
    'daily_earnings' => [
        'labels' => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
        'data' => [450, 520, 480, 620, 750, 920, 680]
    ],
    'zone_utilization' => [
        'labels' => ['Zone A', 'Zone B', 'Zone C', 'Zone D'],
        'data' => [75, 60, 85, 45]
    ],
    'recent_transactions' => [
        ['id' => 1006, 'user' => 'David Wilson', 'amount' => 25.00, 'date' => '2023-06-16', 'status' => 'Completed'],
        ['id' => 1005, 'user' => 'Michael Brown', 'amount' => 15.00, 'date' => '2023-06-13', 'status' => 'Cancelled'],
        ['id' => 1004, 'user' => 'Sarah Williams', 'amount' => 20.00, 'date' => '2023-06-12', 'status' => 'Completed'],
        ['id' => 1003, 'user' => 'Robert Johnson', 'amount' => 40.00, 'date' => '2023-06-11', 'status' => 'Completed'],
        ['id' => 1002, 'user' => 'Jane Smith', 'amount' => 18.00, 'date' => '2023-06-10', 'status' => 'Completed']
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports | Parking Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .report-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .report-card h3 {
            font-size: 1.2rem;
            margin-bottom: 20px;
            color: #4361ee;
        }
        
        .transaction-status {
            font-weight: 500;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.85rem;
        }
        
        .status-completed { background-color: #e8f5e9; color: #2e7d32; }
        .status-cancelled { background-color: #ffebee; color: #c62828; }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <?php include '../includes/admin-sidebar.php'; ?>
        
        <div class="admin-content">
            <div class="container-fluid py-4">
                <h1 class="mb-4">Reports & Analytics</h1>
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="report-card">
                            <h3>Daily Earnings (Last 7 Days)</h3>
                            <canvas id="earningsChart" height="250"></canvas>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="report-card">
                            <h3>Zone Utilization</h3>
                            <canvas id="utilizationChart" height="250"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="report-card mt-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3>Recent Transactions</h3>
                        <button class="btn btn-outline-primary">
                            <i class="fas fa-download me-1"></i> Export
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Transaction ID</th>
                                    <th>User</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reports['recent_transactions'] as $transaction): ?>
                                <tr>
                                    <td>#<?= $transaction['id'] ?></td>
                                    <td><?= $transaction['user'] ?></td>
                                    <td>$<?= number_format($transaction['amount'], 2) ?></td>
                                    <td><?= $transaction['date'] ?></td>
                                    <td>
                                        <span class="transaction-status status-<?= strtolower($transaction['status']) ?>">
                                            <?= $transaction['status'] ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Daily Earnings Chart
        const earningsCtx = document.getElementById('earningsChart').getContext('2d');
        const earningsChart = new Chart(earningsCtx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($reports['daily_earnings']['labels']) ?>,
                datasets: [{
                    label: 'Daily Earnings ($)',
                    data: <?= json_encode($reports['daily_earnings']['data']) ?>,
                    backgroundColor: '#4361ee',
                    borderColor: '#3a56d4',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value;
                            }
                        }
                    }
                }
            }
        });

        // Zone Utilization Chart
        const utilizationCtx = document.getElementById('utilizationChart').getContext('2d');
        const utilizationChart = new Chart(utilizationCtx, {
            type: 'doughnut',
            data: {
                labels: <?= json_encode($reports['zone_utilization']['labels']) ?>,
                datasets: [{
                    data: <?= json_encode($reports['zone_utilization']['data']) ?>,
                    backgroundColor: [
                        '#4361ee',
                        '#3f37c9',
                        '#4cc9f0',
                        '#4895ef'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</body>
</html>