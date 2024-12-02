<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? htmlspecialchars($page_title) : 'Admin Panel'; ?></title>

    <!-- CSS Links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="../css/admin.css" rel="stylesheet">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../assets/favicon.png">
</head>
<body class="<?= isset($_COOKIE['darkMode']) && $_COOKIE['darkMode'] === 'enabled' ? 'dark-mode' : '' ?>">

    <!-- Navbar -->
    <nav class="navbar navbar-dark bg-dark shadow-sm fixed-top">
        <div class="d-flex align-items-center justify-content-between w-100 px-3">
            <!-- Sidebar Toggle Button -->
            <button class="btn btn-dark border-0 d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Brand -->
            <a class="navbar-brand d-flex align-items-center" href="#">
                <i class="fas fa-building me-2"></i>
                <span>Admin Panel</span>
            </a>

            <!-- Right Section -->
            <div class="d-flex align-items-center">
                <!-- Dark Mode Toggle -->
                <button id="darkModeToggle" class="btn btn-outline-secondary me-3 d-flex align-items-center">
                    <i id="darkModeIcon" class="<?= isset($_COOKIE['darkMode']) && $_COOKIE['darkMode'] === 'enabled' ? 'fas fa-sun' : 'fas fa-moon'; ?> me-2"></i>
                    <span class="d-none d-md-inline">Dark Mode</span>
                </button>

                <!-- User Dropdown -->
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center dropdown-toggle" data-bs-toggle="dropdown">
                        <img src="https://static.vecteezy.com/system/resources/previews/020/429/953/non_2x/admin-icon-vector.jpg" 
                             alt="User Avatar" width="32" height="32" class="rounded-circle me-2">
                        <strong>Admin</strong>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="../admin/settings.php">Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="../logout.php">Sign out</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

</body>
</html>
