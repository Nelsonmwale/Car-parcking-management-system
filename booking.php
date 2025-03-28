<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Sample data - in a real system this would come from database
$parkingZones = [
    ['id' => 1, 'name' => 'Zone A', 'hourly_rate' => 5.00, 'available' => 25],
    ['id' => 2, 'name' => 'Zone B', 'hourly_rate' => 4.50, 'available' => 18],
    ['id' => 3, 'name' => 'Zone C', 'hourly_rate' => 4.00, 'available' => 12],
    ['id' => 4, 'name' => 'Premium Zone', 'hourly_rate' => 7.50, 'available' => 8]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Parking | <?= APP_NAME ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .booking-container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        
        .zone-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }
        
        .zone-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .zone-card h3 {
            color: var(--primary);
        }
        
        .availability {
            font-size: 0.9rem;
            color: var(--gray);
        }
        
        .available {
            color: var(--success);
            font-weight: bold;
        }
        
        .full {
            color: var(--danger);
        }
        
        .booking-form {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="booking-container">
        <h1 class="text-center mb-4">Book Your Parking Spot</h1>
        
        <div class="row">
            <div class="col-md-7">
                <h2 class="mb-3">Available Parking Zones</h2>
                
                <?php foreach ($parkingZones as $zone): ?>
                <div class="zone-card">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h3><?= $zone['name'] ?></h3>
                        <span class="badge bg-primary">$<?= number_format($zone['hourly_rate'], 2) ?>/hr</span>
                    </div>
                    
                    <p class="availability">
                        <span class="<?= $zone['available'] > 0 ? 'available' : 'full' ?>">
                            <?= $zone['available'] > 0 ? $zone['available'] . ' spots available' : 'Fully booked' ?>
                        </span>
                    </p>
                    
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <a href="#" class="btn btn-sm btn-outline-primary view-map" 
                           data-zone="<?= $zone['id'] ?>">
                            <i class="fas fa-map-marker-alt"></i> View Map
                        </a>
                        
                        <?php if ($zone['available'] > 0): ?>
                        <button class="btn btn-sm btn-primary book-now" 
                                data-zone-id="<?= $zone['id'] ?>"
                                data-zone-name="<?= $zone['name'] ?>"
                                data-hourly-rate="<?= $zone['hourly_rate'] ?>">
                            <i class="fas fa-calendar-check"></i> Book Now
                        </button>
                        <?php else: ?>
                        <button class="btn btn-sm btn-secondary" disabled>
                            <i class="fas fa-times-circle"></i> Not Available
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div class="col-md-5">
                <div class="booking-form">
                    <h3 class="text-center mb-4">Booking Details</h3>
                    <div class="text-center mb-4">
                        <i class="fas fa-parking fa-4x text-primary"></i>
                        <p class="mt-2">Select a zone to book</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Handle book now button clicks
        document.querySelectorAll('.book-now').forEach(button => {
            button.addEventListener('click', function() {
                const zoneId = this.dataset.zoneId;
                const zoneName = this.dataset.zoneName;
                const hourlyRate = this.dataset.hourlyRate;
                
                // Update booking form
                document.querySelector('.booking-form h3').textContent = `Book ${zoneName}`;
                document.querySelector('.booking-form p').textContent = `$${hourlyRate} per hour`;
                
                // In a real system, you would show a form with date/time picker
                // and calculate the total cost based on duration
                const formHTML = `
                    <form id="bookingForm">
                        <div class="mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col">
                                <label for="startTime" class="form-label">Start Time</label>
                                <input type="time" class="form-control" id="startTime" required>
                            </div>
                            <div class="col">
                                <label for="endTime" class="form-label">End Time</label>
                                <input type="time" class="form-control" id="endTime" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="vehicle" class="form-label">Vehicle Number</label>
                            <input type="text" class="form-control" id="vehicle" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-check-circle"></i> Confirm Booking
                        </button>
                    </form>
                `;
                
                document.querySelector('.booking-form').innerHTML = formHTML;
                
                // Handle form submission
                document.getElementById('bookingForm').addEventListener('submit', function(e) {
                    e.preventDefault();
                    alert(`Booking confirmed for ${zoneName}!`);
                    // In real system, this would submit to server
                });
            });
        });
    </script>
</body>
</html>