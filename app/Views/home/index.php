<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WasteWatch | Barangay Dulong Bayan</title>
    <?php include __DIR__ . '/../layouts/theme-scripts.php'; ?>
    <style>
        .badge-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.375rem 0.875rem;
            background: hsl(160 50% 40% / 0.1);
            color: hsl(160 50% 40%);
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .badge-pill svg {
            width: 16px;
            height: 16px;
        }
        .hero-title {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -0.02em;
            color: hsl(215 60% 20%);
        }
        .hero-title .highlight {
            color: hsl(160 50% 40%);
        }
        @media (min-width: 640px) {
            .hero-title {
                font-size: 2.5rem;
            }
        }
        @media (min-width: 768px) {
            .hero-title {
                font-size: 3.75rem;
                line-height: 1.1;
            }
        }
        .hero-description {
            font-size: 1rem;
            color: hsl(215 16% 47%);
            line-height: 1.7;
            max-width: 520px;
            margin: 0 auto;
        }
        @media (min-width: 768px) {
            .hero-description {
                font-size: 1.125rem;
            }
        }
        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.875rem 1.75rem;
            background: hsl(215 60% 25%);
            color: white;
            border-radius: 0.375rem;
            font-size: 1rem;
            font-weight: 600;
            text-decoration: none;
            transition: background-color 0.2s;
            border: none;
            cursor: pointer;
            min-height: 48px;
            width: 100%;
        }
        .btn-primary:hover {
            background: hsl(215 60% 20%);
        }
        .btn-primary svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }
        .btn-outline {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.875rem 1.75rem;
            background: white;
            color: hsl(215 60% 15%);
            border: 1px solid hsl(214 20% 88%);
            border-radius: 0.375rem;
            font-size: 1rem;
            font-weight: 500;
            text-decoration: none;
            transition: background-color 0.2s;
            cursor: pointer;
            min-height: 48px;
            width: 100%;
        }
        .btn-outline:hover {
            background: hsl(210 20% 97%);
        }
        @media (min-width: 640px) {
            .btn-primary, .btn-outline {
                width: auto;
            }
        }
        .how-card {
            padding: 1.5rem;
            background: hsl(210 20% 97%);
            border: 1px solid hsl(214 20% 88%);
            border-radius: 1rem;
            text-align: center;
        }
        @media (min-width: 768px) {
            .how-card {
                padding: 2rem;
            }
        }
        .icon-wrapper {
            width: 48px;
            height: 48px;
            background: hsl(215 60% 25% / 0.1);
            border-radius: 0.75rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }
        @media (min-width: 768px) {
            .icon-wrapper {
                margin-bottom: 1.25rem;
            }
        }
        .icon-wrapper svg {
            width: 24px;
            height: 24px;
            color: hsl(215 60% 25%);
        }
        .how-card h3 {
            font-size: 1rem;
            font-weight: 600;
            color: hsl(215 60% 15%);
            margin-bottom: 0.5rem;
        }
        @media (min-width: 768px) {
            .how-card h3 {
                font-size: 1.125rem;
                margin-bottom: 0.75rem;
            }
        }
        .how-card p {
            font-size: 0.875rem;
            color: hsl(215 16% 47%);
            line-height: 1.6;
        }
        @media (min-width: 768px) {
            .how-card p {
                font-size: 0.9375rem;
            }
        }
        .header-logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        @media (min-width: 640px) {
            .header-logo {
                gap: 0.625rem;
            }
        }
        .header-logo-icon {
            width: 36px;
            height: 36px;
            background: #E9C46A;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        @media (min-width: 640px) {
            .header-logo-icon {
                width: 40px;
                height: 40px;
            }
        }
        .header-logo-icon svg {
            width: 20px;
            height: 20px;
            color: white;
        }
        @media (min-width: 640px) {
            .header-logo-icon svg {
                width: 22px;
                height: 22px;
            }
        }
        .header-logo-text {
            display: flex;
            flex-direction: column;
        }
        .header-logo-title {
            font-size: 1rem;
            font-weight: 700;
            color: hsl(215 60% 15%);
            line-height: 1.2;
        }
        @media (min-width: 640px) {
            .header-logo-title {
                font-size: 1.125rem;
            }
        }
        .header-logo-subtitle {
            font-size: 0.625rem;
            color: hsl(215 16% 47%);
            display: none;
        }
        @media (min-width: 640px) {
            .header-logo-subtitle {
                font-size: 0.75rem;
                display: block;
            }
        }
        /* Mobile menu button */
        .mobile-menu-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: transparent;
            border: 1px solid hsl(214 20% 88%);
            border-radius: 0.375rem;
            cursor: pointer;
            color: hsl(215 60% 15%);
            transition: background-color 0.2s;
        }
        .mobile-menu-btn:hover {
            background: hsl(210 20% 97%);
        }
        .mobile-menu-btn svg {
            width: 20px;
            height: 20px;
        }
        @media (min-width: 768px) {
            .mobile-menu-btn {
                display: none;
            }
        }
        /* Mobile nav overlay */
        .mobile-nav-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 100;
            opacity: 0;
            transition: opacity 0.3s;
        }
        .mobile-nav-overlay.active {
            display: block;
            opacity: 1;
        }
        .mobile-nav {
            position: fixed;
            top: 0;
            right: -100%;
            width: 280px;
            max-width: 85%;
            height: 100%;
            background: white;
            z-index: 101;
            transition: right 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        .mobile-nav.active {
            right: 0;
        }
        .mobile-nav-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid hsl(214 20% 88%);
        }
        .mobile-nav-close {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            border: none;
            cursor: pointer;
            color: hsl(215 60% 15%);
            border-radius: 0.375rem;
        }
        .mobile-nav-close:hover {
            background: hsl(210 20% 97%);
        }
        .mobile-nav-close svg {
            width: 20px;
            height: 20px;
        }
        .mobile-nav-body {
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            flex: 1;
        }
        .mobile-nav-link {
            display: block;
            padding: 0.75rem 1rem;
            text-decoration: none;
            color: hsl(215 60% 15%);
            font-size: 1rem;
            font-weight: 500;
            border-radius: 0.375rem;
            transition: background-color 0.2s;
        }
        .mobile-nav-link:hover {
            background: hsl(210 20% 97%);
        }
        .mobile-nav-link.btn-primary-mobile {
            background: hsl(215 60% 25%);
            color: white;
            text-align: center;
            font-weight: 600;
        }
        .mobile-nav-link.btn-primary-mobile:hover {
            background: hsl(215 60% 20%);
        }
        .mobile-nav-divider {
            height: 1px;
            background: hsl(214 20% 88%);
            margin: 0.5rem 0;
        }
        /* Desktop nav */
        .desktop-nav {
            display: none;
        }
        @media (min-width: 768px) {
            .desktop-nav {
                display: flex;
            }
        }
    </style>
</head>
<body class="bg-background text-foreground font-sans antialiased min-h-screen flex flex-col">

    <!-- Header -->
    <header class="sticky top-0 z-50 border-b border-border bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="header-logo">
                    <div class="header-logo-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    <div class="header-logo-text">
                        <span class="header-logo-title">WasteWatch</span>
                        <span class="header-logo-subtitle">Barangay Dulong Bayan</span>
                    </div>
                </div>
                <!-- Desktop Navigation -->
                <nav class="desktop-nav items-center gap-3">
                    <?php if ($data['isLoggedIn']): ?>
                        <a href="/brgy-waste-app-v3/public/auth" class="text-sm font-medium text-muted-foreground hover:text-foreground transition-colors">Log In</a>
                        <a href="/brgy-waste-app-v3/public/<?php echo $data['role'] == 'resident' ? 'resident' : 'admin'; ?>" class="inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground px-4 py-2 text-sm font-semibold shadow-sm hover:bg-primary/90 transition-colors">Dashboard</a>
                    <?php else: ?>
                        <a href="/brgy-waste-app-v3/public/auth" class="text-sm font-medium text-muted-foreground hover:text-foreground transition-colors px-3 py-2">Log In</a>
                        <a href="/brgy-waste-app-v3/public/auth/register" class="inline-flex items-center justify-center rounded-md bg-primary text-primary-foreground px-4 py-2 text-sm font-semibold shadow-sm hover:bg-primary/90 transition-colors">Register</a>
                    <?php endif; ?>
                </nav>
                <!-- Mobile Menu Button -->
                <button class="mobile-menu-btn" onclick="toggleMobileNav()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/></svg>
                </button>
            </div>
        </div>
    </header>

    <!-- Mobile Navigation Overlay -->
    <div class="mobile-nav-overlay" id="mobileNavOverlay" onclick="toggleMobileNav()"></div>
    <div class="mobile-nav" id="mobileNav">
        <div class="mobile-nav-header">
            <div class="header-logo">
                <div class="header-logo-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <div class="header-logo-text">
                    <span class="header-logo-title">WasteWatch</span>
                </div>
            </div>
            <button class="mobile-nav-close" onclick="toggleMobileNav()">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
        <div class="mobile-nav-body">
            <?php if ($data['isLoggedIn']): ?>
                <a href="/brgy-waste-app-v3/public/auth" class="mobile-nav-link">Log In</a>
                <a href="/brgy-waste-app-v3/public/<?php echo $data['role'] == 'resident' ? 'resident' : 'admin'; ?>" class="mobile-nav-link btn-primary-mobile">Dashboard</a>
            <?php else: ?>
                <a href="/brgy-waste-app-v3/public/auth" class="mobile-nav-link">Log In</a>
                <a href="/brgy-waste-app-v3/public/auth/register" class="mobile-nav-link btn-primary-mobile">Register</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Hero Section -->
    <section class="py-12 sm:py-16 md:py-24 lg:py-32" style="background: hsl(210 20% 97%);">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="badge-pill mb-4 sm:mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                For a Cleaner Community
            </div>
            <h1 class="hero-title mb-4 sm:mb-6">
                Report Waste Issues in<br>
                <span class="highlight">Barangay Dulong Bayan</span>
            </h1>
            <p class="hero-description mb-6 sm:mb-8 md:mb-10">
                A cross-platform waste reporting system with geospatial mapping and analytics. Help keep your barangay clean by reporting waste issues directly to your local government.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center max-w-sm sm:max-w-none mx-auto sm:mx-0 px-4 sm:px-0">
                <?php if ($data['isLoggedIn']): ?>
                    <a href="/brgy-waste-app-v3/public/<?php echo $data['role'] == 'resident' ? 'resident' : 'admin'; ?>" class="btn-primary">
                        Go to Dashboard
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </a>
                <?php else: ?>
                    <a href="/brgy-waste-app-v3/public/auth/register" class="btn-primary">
                        Get Started
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </a>
                    <a href="/brgy-waste-app-v3/public/auth" class="btn-outline">
                        Log In to Your Account
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-12 sm:py-16 md:py-20 bg-white border-t border-border">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-2xl sm:text-3xl font-bold text-center text-foreground mb-8 sm:mb-10 md:mb-14">How It Works</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6 lg:gap-8 max-w-5xl mx-auto">
                <!-- Report Waste -->
                <div class="how-card">
                    <div class="icon-wrapper">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                    </div>
                    <h3>Report Waste</h3>
                    <p>Take a photo, describe the issue, and pin the location on the map. We verify if it is within barangay boundaries.</p>
                </div>

                <!-- Track & Analyze -->
                <div class="how-card">
                    <div class="icon-wrapper">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                    </div>
                    <h3>Track & Analyze</h3>
                    <p>Monitor report progress from Pending to Verified to Resolved. View analytics dashboards and heatmaps.</p>
                </div>

                <!-- Stay Informed -->
                <div class="how-card sm:col-span-2 md:col-span-1">
                    <div class="icon-wrapper">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                    </div>
                    <h3>Stay Informed</h3>
                    <p>Receive notifications on report status updates, account approvals, and barangay announcements.</p>
                </div>
            </div>
        </div>
    </section>


    <script>
        function toggleMobileNav() {
            const overlay = document.getElementById('mobileNavOverlay');
            const nav = document.getElementById('mobileNav');
            overlay.classList.toggle('active');
            nav.classList.toggle('active');
            document.body.style.overflow = nav.classList.contains('active') ? 'hidden' : '';
        }

        // Close mobile nav on window resize if open
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) {
                const overlay = document.getElementById('mobileNavOverlay');
                const nav = document.getElementById('mobileNav');
                if (nav.classList.contains('active')) {
                    overlay.classList.remove('active');
                    nav.classList.remove('active');
                    document.body.style.overflow = '';
                }
            }
        });
    </script>

</body>
</html>
