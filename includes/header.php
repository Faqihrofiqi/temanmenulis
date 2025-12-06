<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?><?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo asset_url('assets/css/style.css'); ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="header">
        <nav class="navbar">
            <div class="container">
                <div class="nav-wrapper">
                    <div class="logo">
                        <img src="<?php echo SITE_URL; ?>assets/TEMANNULISMU .png" alt="<?php echo SITE_NAME; ?>" class="logo-image">
                        <span><?php echo SITE_NAME; ?></span>
                    </div>
                    <ul class="nav-menu" id="navMenu">
                        <li><a href="<?php echo SITE_URL; ?>/index.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">Home</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/services.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'services.php' ? 'active' : ''; ?>">Paket Jasa</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/blog.php" class="nav-link">Blog</a></li>
                        <li><a href="<?php echo SITE_URL; ?>/tutorial.php" class="nav-link">Tutorial</a></li>
                        <li class="dropdown">
                            <a href="#" class="nav-link">Layanan <i class="fas fa-chevron-down"></i></a>
                            <ul class="dropdown-menu">
                                <li><a href="<?php echo SITE_URL; ?>/services.php?category=skripsi">Skripsi</a></li>
                                <li><a href="<?php echo SITE_URL; ?>/services.php?category=thesis">Thesis</a></li>
                                <li><a href="<?php echo SITE_URL; ?>/services.php?category=disertasi">Disertasi</a></li>
                            </ul>
                        </li>
                    </ul>
                    <div class="nav-actions">
                        <button class="theme-toggle" id="themeToggle" title="Toggle Dark Mode">
                            <i class="fas fa-sun"></i>
                        </button>
                        <a href="<?php echo SITE_URL; ?>/check-order.php" class="btn-secondary">Cek Pesanan</a>
                    </div>
                    <button class="mobile-menu-toggle" id="mobileMenuToggle">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
            </div>
        </nav>
    </header>
    <main class="main-content">

