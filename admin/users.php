<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    header("Location: login.php");
    exit();
}

// Sample data - in real system this would come from database
$users = [
    [
        'id' => 1,
        'name' => 'Admin User',
        'email' => 'admin@parking.com',
        'phone' => '(123) 456-7890',
        'role' => 'Admin',
        'status' => 'Active',
        'registered' => '2023-01-15'
    ],
    [
        'id' => 2,
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'phone' => '(234) 567-8901',
        'role' => 'User',
        'status' => 'Active',
        'registered' => '2023-02-20'
    ],
    [
        'id' => 3,
        'name' => 'Jane Smith',
        'email' => 'jane@example.com',
        'phone' => '(345) 678-9012',
        'role' => 'User',
        'status' => 'Active',
        'registered' => '2023-03-10'
    ],
    [
        'id' => 4,
        'name' => 'Robert Johnson',
        'email' => 'robert@example.com',
        'phone' => '(456) 789-0123',
        'role' => 'User',
        'status' => 'Suspended',
        'registered' => '2023-04-05'
    ],
    [
        'id' => 5,
        'name' => 'Sarah Williams',
        'email' => 'sarah@example.com',
        'phone' => '(567) 890-1234',
        'role' => 'User',
        'status' => 'Active',
        'registered' => '2023-05-15'
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users | Parking Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
        .user-status {
            font-weight: 500;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.85rem;
        }
        
        .status-active { background-color: #e8f5e9; color: #2e7d32; }
        .status-suspended { background-color: #ffebee; color: #c62828; }
        
        .user-role {
            font-weight: 500;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.85rem;
            background-color: #e3f2fd;
            color: #1565c0;
        }
        
        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #757575;
        }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <?php include '../includes/admin-sidebar.php'; ?>
        
        <div class="admin-content">
            <div class="container-fluid py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Manage Users</h1>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="fas fa-plus me-1"></i> Add User
                    </button>
                </div>
                
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="mb-0">All Users</h5>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search users...">
                                    <button class="btn btn-outline-secondary" type="button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Registered</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td>
                                            <div class="avatar">
                                                <?= substr($user['name'], 0, 1) ?>
                                            </div>
                                        </td>
                                        <td><?= $user['name'] ?></td>
                                        <td><?= $user['email'] ?></td>
                                        <td><?= $user['phone'] ?></td>
                                        <td>
                                            <span class="user-role"><?= $user['role'] ?></span>
                                        </td>
                                        <td>
                                            <span class="user-status status-<?= strtolower($user['status']) ?>">
                                                <?= $user['status'] ?>
                                            </span>
                                        </td>
                                        <td><?= $user['registered'] ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary me-1" 
                                                    data-bs-toggle="tooltip" 
                                                    title="View Profile">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning me-1" 
                                                    data-bs-toggle="tooltip" 
                                                    title="Edit User">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <?php if ($user['status'] === 'Active'): ?>
                                            <button class="btn btn-sm btn-outline-danger suspend-user" 
                                                    data-user-id="<?= $user['id'] ?>"
                                                    data-bs-toggle="tooltip" 
                                                    title="Suspend User">
                                                <i class="fas fa-user-slash"></i>
                                            </button>
                                            <?php else: ?>
                                            <button class="btn btn-sm btn-outline-success activate-user" 
                                                    data-user-id="<?= $user['id'] ?>"
                                                    data-bs-toggle="tooltip" 
                                                    title="Activate User">
                                                <i class="fas fa-user-check"></i>
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

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addUserForm">
                        <div class="mb-3">
                            <label for="userName" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="userName" required>
                        </div>
                        <div class="mb-3">
                            <label for="userEmail" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="userEmail" required>
                        </div>
                        <div class="mb-3">
                            <label for="userPhone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="userPhone">
                        </div>
                        <div class="mb-3">
                            <label for="userRole" class="form-label">Role</label>
                            <select class="form-select" id="userRole" required>
                                <option value="User">User</option>
                                <option value="Admin">Admin</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="userPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="userPassword" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveUser">Save User</button>
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

        // Handle save user button
        document.getElementById('saveUser').addEventListener('click', function() {
            // In a real system, this would submit to server
            alert('User saved successfully!');
            document.getElementById('addUserForm').reset();
            $('#addUserModal').modal('hide');
        });

        // Handle suspend/activate user buttons
        document.querySelectorAll('.suspend-user').forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.dataset.userId;
                if (confirm('Are you sure you want to suspend this user?')) {
                    alert(`User ${userId} suspended!`);
                    // In real system, this would send request to server
                }
            });
        });

        document.querySelectorAll('.activate-user').forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.dataset.userId;
                if (confirm('Are you sure you want to activate this user?')) {
                    alert(`User ${userId} activated!`);
                    // In real system, this would send request to server
                }
            });
        });
    </script>
</body>
</html>