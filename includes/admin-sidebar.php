<!-- Admin Sidebar Navigation -->
<div class="admin-sidebar">
    <div class="sidebar-header text-center py-4">
        <h4><i class="fas fa-parking me-2"></i>Parking Admin</h4>
        <p class="text-muted small mb-0">Welcome, <?= $_SESSION['name'] ?? 'Admin' ?></p>
    </div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'dashboard.php' ? 'active' : '' ?>" href="dashboard.php">
                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'slots.php' ? 'active' : '' ?>" href="slots.php">
                <i class="fas fa-car me-2"></i> Parking Slots
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'bookings.php' ? 'active' : '' ?>" href="bookings.php">
                <i class="fas fa-calendar-check me-2"></i> Bookings
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'users.php' ? 'active' : '' ?>" href="users.php">
                <i class="fas fa-users me-2"></i> Users
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'reports.php' ? 'active' : '' ?>" href="reports.php">
                <i class="fas fa-chart-bar me-2"></i> Reports
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) === 'settings.php' ? 'active' : '' ?>" href="settings.php">
                <i class="fas fa-cog me-2"></i> Settings
            </a>
        </li>
        <li class="nav-item mt-4">
            <a class="nav-link text-danger" href="logout.php">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
        </li>
    </ul>
</div>