<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

// Check if user is logged in and is admin
if (!isLoggedIn() || !isAdmin()) {
    header("Location: login.php");
    exit();
}

// Sample data - in real system this would come from database
$parkingSlots = [
    ['id' => 1, 'zone' => 'A', 'number' => 'A-01', 'type' => 'Standard', 'status' => 'Available'],
    ['id' => 2, 'zone' => 'A', 'number' => 'A-02', 'type' => 'Standard', 'status' => 'Occupied'],
    ['id' => 3, 'zone' => 'A', 'number' => 'A-03', 'type' => 'Disabled', 'status' => 'Available'],
    ['id' => 4, 'zone' => 'B', 'number' => 'B-01', 'type' => 'Standard', 'status' => 'Maintenance'],
    ['id' => 5, 'zone' => 'B', 'number' => 'B-02', 'type' => 'EV Charging', 'status' => 'Available'],
    ['id' => 6, 'zone' => 'C', 'number' => 'C-01', 'type' => 'Reserved', 'status' => 'Available'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Parking Slots | Parking Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
    <style>
        .slot-card {
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }
        
        .slot-available {
            background-color: #e8f5e9;
            border-left: 4px solid #4caf50;
        }
        
        .slot-occupied {
            background-color: #ffebee;
            border-left: 4px solid #f44336;
        }
        
        .slot-maintenance {
            background-color: #fff8e1;
            border-left: 4px solid #ffc107;
        }
        
        .slot-reserved {
            background-color: #e3f2fd;
            border-left: 4px solid #2196f3;
        }
        
        .slot-type {
            font-size: 0.8rem;
            padding: 3px 8px;
            border-radius: 4px;
            font-weight: 500;
        }
        
        .type-standard { background-color: #e0e0e0; color: #424242; }
        .type-disabled { background-color: #bbdefb; color: #0d47a1; }
        .type-ev { background-color: #c8e6c9; color: #2e7d32; }
        .type-reserved { background-color: #d1c4e9; color: #4527a0; }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <?php include '../includes/admin-sidebar.php'; ?>
        
        <div class="admin-content">
            <div class="container-fluid py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1>Manage Parking Slots</h1>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSlotModal">
                        <i class="fas fa-plus me-1"></i> Add New Slot
                    </button>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 class="mb-0">All Parking Slots</h5>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search slots...">
                                    <button class="btn btn-outline-secondary" type="button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php foreach ($parkingSlots as $slot): ?>
                            <div class="col-md-4">
                                <div class="slot-card slot-<?= strtolower($slot['status']) ?>">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h5 class="mb-0"><?= $slot['number'] ?></h5>
                                        <span class="badge bg-<?= 
                                            $slot['status'] === 'Available' ? 'success' : 
                                            ($slot['status'] === 'Occupied' ? 'danger' : 
                                            ($slot['status'] === 'Maintenance' ? 'warning' : 'primary')) 
                                        ?>">
                                            <?= $slot['status'] ?>
                                        </span>
                                    </div>
                                    <p class="mb-2">
                                        <span class="slot-type type-<?= strtolower(explode(' ', $slot['type'])[0]) ?>">
                                            <?= $slot['type'] ?>
                                        </span>
                                    </p>
                                    <p class="text-muted small mb-2">Zone: <?= $slot['zone'] ?></p>
                                    <div class="d-flex justify-content-end">
                                        <button class="btn btn-sm btn-outline-primary me-2 edit-slot" 
                                                data-slot-id="<?= $slot['id'] ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger delete-slot" 
                                                data-slot-id="<?= $slot['id'] ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Slot Modal -->
    <div class="modal fade" id="addSlotModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Parking Slot</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addSlotForm">
                        <div class="mb-3">
                            <label for="zone" class="form-label">Zone</label>
                            <select class="form-select" id="zone" required>
                                <option value="">Select Zone</option>
                                <option value="A">Zone A</option>
                                <option value="B">Zone B</option>
                                <option value="C">Zone C</option>
                                <option value="D">Zone D</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="slotNumber" class="form-label">Slot Number</label>
                            <input type="text" class="form-control" id="slotNumber" required>
                        </div>
                        <div class="mb-3">
                            <label for="slotType" class="form-label">Slot Type</label>
                            <select class="form-select" id="slotType" required>
                                <option value="Standard">Standard</option>
                                <option value="Disabled">Disabled</option>
                                <option value="EV Charging">EV Charging</option>
                                <option value="Reserved">Reserved</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="slotStatus" class="form-label">Status</label>
                            <select class="form-select" id="slotStatus" required>
                                <option value="Available">Available</option>
                                <option value="Occupied">Occupied</option>
                                <option value="Maintenance">Maintenance</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveSlot">Save Slot</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Handle save slot button
        document.getElementById('saveSlot').addEventListener('click', function() {
            // In a real system, this would submit to server
            alert('Slot saved successfully!');
            document.getElementById('addSlotForm').reset();
            $('#addSlotModal').modal('hide');
        });

        // Handle edit and delete buttons
        document.querySelectorAll('.edit-slot').forEach(button => {
            button.addEventListener('click', function() {
                const slotId = this.dataset.slotId;
                alert(`Editing slot ID: ${slotId}`);
                // In real system, this would open edit modal with slot data
            });
        });

        document.querySelectorAll('.delete-slot').forEach(button => {
            button.addEventListener('click', function() {
                const slotId = this.dataset.slotId;
                if (confirm('Are you sure you want to delete this parking slot?')) {
                    alert(`Deleted slot ID: ${slotId}`);
                    // In real system, this would send delete request to server
                }
            });
        });
    </script>
</body>
</html>