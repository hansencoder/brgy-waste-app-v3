<?php include __DIR__ . '/../layouts/header.php'; ?>

<?php
$isLoggedIn = $data['isLoggedIn'] ?? false;
$role = $data['role'] ?? null;
$announcements = $data['announcements'] ?? [];
$schedules = $data['schedules'] ?? [];
$barangay = $data['barangay'] ?? [];
$unreadCount = $data['unreadCount'] ?? 0;
$barangayName = $barangay['barangay_name'] ?? 'Dulong Bayan';
$barangayAddress = $barangay['official_address'] ?? 'Barangay Hall, Dulong Bayan';
$barangayContact = $barangay['contact_number'] ?? '(02) 8-123-4567';
$barangayEmail = $barangay['official_email'] ?? 'brgy.dulongbayan@email.com';
$sysLogo = $barangay['system_logo'] ?? null;
if ($sysLogo && strpos($sysLogo, '/brgy-waste-app-v3') === false && strpos($sysLogo, '/public') === 0) {
    $sysLogo = '/brgy-waste-app-v3' . $sysLogo;
}
$sysShortName = $barangay['system_short_name'] ?? 'WasteWatch';
$sysMotto = $barangay['system_motto'] ?? 'SMART WASTE SOLUTIONS';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($sysShortName); ?> | <?php echo htmlspecialchars($barangayName); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Miranda+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Miranda Sans', sans-serif !important; font-optical-sizing: auto; }
        .pulse-dot { animation: pulse 2s infinite; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.3; } }
        .hero-wave { position: absolute; bottom: -2px; left: 0; right: 0; }
        .faq-question { transition: all 0.3s ease; }
        .faq-answer { transition: max-height 0.3s ease, padding 0.3s ease; }
        .mobile-menu { transition: transform 0.3s ease, opacity 0.3s ease; }
        .schedule-card { transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease; }
        .schedule-card:hover { transform: translateY(-4px); box-shadow: 0 12px 30px -12px rgba(0,0,0,0.15); border-color: #cbd5e1; }
        .announcement-item { transition: background 0.2s ease, border-color 0.2s ease; }
        .announcement-item:hover { background: #f8fafc; border-color: #cbd5e1; }
        .feature-card { transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease; }
        .feature-card:hover { transform: translateY(-4px); border-color: #6ee7b7; box-shadow: 0 12px 30px -12px rgba(0,0,0,0.1); }
        .step-card { transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease; }
        .step-card:hover { transform: translateY(-4px); box-shadow: 0 12px 30px -12px rgba(0,0,0,0.1); border-color: #6ee7b7; }
        
        /* Hero Background Slider Transition */
        .hero-slide { opacity: 0; transition: opacity 1s ease-in-out; }
        .hero-slide.active { opacity: 1; }

        /* Visible keyboard focus everywhere — buttons/links had no focus indicator */
        a:focus-visible, button:focus-visible {
            outline: 2px solid #10B981;
            outline-offset: 2px;
            border-radius: 4px;
        }
        @media (prefers-reduced-motion: reduce) {
            .pulse-dot, .schedule-card, .feature-card, .step-card, .announcement-item, .hero-slide { animation: none !important; transition: none !important; }
        }
    </style>
</head>
<body>

<!-- ============================================================ -->
<!-- NAVIGATION – Sticky Header                                   -->
<!-- ============================================================ -->
<nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <a href="/brgy-waste-app-v3/public/" class="flex items-center gap-3 flex-shrink-0">
                <div class="w-10 h-10 rounded-full bg-[#07281E] flex items-center justify-center text-white shadow-md shadow-[#07281E]/20 overflow-hidden border-2 border-emerald-500/30">
                    <?php if (!empty($sysLogo)): ?>
                        <img src="<?php echo htmlspecialchars($sysLogo); ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    <?php endif; ?>
                </div>
                <div>
                    <span class="font-extrabold text-slate-900 text-lg tracking-tight"><?php echo htmlspecialchars($sysShortName); ?></span>
                    <span class="hidden sm:block text-[11px] font-semibold text-slate-400 tracking-wide uppercase"><?php echo htmlspecialchars($sysMotto); ?></span>
                </div>
            </a>

            <!-- Desktop Nav Links -->
            <div class="hidden md:flex items-center gap-1">
                <a href="#features" class="px-3 py-2 text-sm font-semibold text-slate-600 hover:text-[#07281E] transition-colors">Features</a>
                <a href="#schedule" class="px-3 py-2 text-sm font-semibold text-slate-600 hover:text-[#07281E] transition-colors">Schedule</a>
                <a href="#announcements" class="px-3 py-2 text-sm font-semibold text-slate-600 hover:text-[#07281E] transition-colors">Announcements</a>
                <a href="#faq" class="px-3 py-2 text-sm font-semibold text-slate-600 hover:text-[#07281E] transition-colors">FAQs</a>
                <a href="#contact" class="px-3 py-2 text-sm font-semibold text-slate-600 hover:text-[#07281E] transition-colors">Contact</a>
            </div>

            <!-- Right Actions -->
            <div class="flex items-center gap-3">
                <?php if ($isLoggedIn): ?>
                    <a href="/brgy-waste-app-v3/public/index.php?url=<?php echo urlencode($role == 'resident' ? 'resident' : ($role == 'supervisor' ? 'supervisor' : 'admin')); ?>" class="hidden sm:inline-flex items-center gap-2 px-4 py-2 bg-[#10B981] text-white text-sm font-bold rounded-full shadow-lg shadow-emerald-500/20 hover:bg-emerald-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V21h14V9.5"/></svg>
                        Dashboard
                    </a>
                <?php else: ?>
                    <a href="/brgy-waste-app-v3/public/index.php?url=<?php echo urlencode('auth'); ?>" class="hidden sm:inline-flex text-sm font-semibold text-slate-600 hover:text-[#07281E] transition-colors">Sign In</a>
                    <a href="/brgy-waste-app-v3/public/index.php?url=<?php echo urlencode('auth/register'); ?>" class="inline-flex items-center gap-2 px-5 py-2 bg-[#10B981] text-white text-sm font-bold rounded-full shadow-lg shadow-emerald-500/20 hover:bg-emerald-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                        Register
                    </a>
                <?php endif; ?>
                <!-- Mobile Menu Toggle -->
                <button id="menuToggle" class="md:hidden p-2 text-slate-600 hover:text-[#07281E] transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                </button>
            </div>
        </div>
    </div>
</nav>

<!-- ============================================================ -->
<!-- MOBILE MENU – Overlay                                        -->
<!-- ============================================================ -->
<div id="mobileMenu" class="fixed inset-0 z-[60] bg-white/95 backdrop-blur-lg hidden flex-col p-6 pt-20">
    <button id="menuClose" class="absolute top-4 right-4 p-2 text-slate-500 hover:text-slate-800 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
    <div class="flex flex-col space-y-6 text-center">
        <a href="#features" class="text-lg font-semibold text-slate-700 hover:text-[#07281E]">Features</a>
        <a href="#schedule" class="text-lg font-semibold text-slate-700 hover:text-[#07281E]">Schedule</a>
        <a href="#announcements" class="text-lg font-semibold text-slate-700 hover:text-[#07281E]">Announcements</a>
        <a href="#faq" class="text-lg font-semibold text-slate-700 hover:text-[#07281E]">FAQs</a>
        <a href="#contact" class="text-lg font-semibold text-slate-700 hover:text-[#07281E]">Contact</a>
        <div class="pt-4 border-t border-slate-200 flex flex-col gap-3">
            <?php if ($isLoggedIn): ?>
                <a href="/brgy-waste-app-v3/public/index.php?url=<?php echo urlencode($role == 'resident' ? 'resident' : ($role == 'supervisor' ? 'supervisor' : 'admin')); ?>" class="w-full bg-[#10B981] text-white font-bold py-3 rounded-full">Dashboard</a>
            <?php else: ?>
                <a href="/brgy-waste-app-v3/public/index.php?url=<?php echo urlencode('auth'); ?>" class="w-full text-slate-600 font-semibold py-3">Sign In</a>
                <a href="/brgy-waste-app-v3/public/index.php?url=<?php echo urlencode('auth/register'); ?>" class="w-full bg-[#10B981] text-white font-bold py-3 rounded-full shadow-lg shadow-emerald-500/20">Register</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- HERO SECTION – Updated with Image Carousel                  -->
<!-- ============================================================ -->
<section class="relative bg-[#07281E] text-white overflow-hidden pt-10 pb-40">
    <!-- Background Carousel Container -->
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 hero-slide active" style="background-image: url('../assets/images/hero/hero1.jpg'); background-size: cover; background-position: center;"></div>
        <div class="absolute inset-0 hero-slide" style="background-image: url('../assets/images/hero/hero2.jpg'); background-size: cover; background-position: center;"></div>
        
        <div class="absolute inset-0 bg-gradient-to-br from-[#07281E]/100 via-[#07281E]/80 to-[#10B981]/30 pointer-events-none"></div>
        
        <button id="heroPrev" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 p-3 rounded-full bg-white/10 backdrop-blur-sm text-white hover:bg-white/20 transition-all duration-300 hover:scale-110 shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
        </button>
        
        <!-- Right Navigation -->
        <button id="heroNext" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 p-3 rounded-full bg-white/10 backdrop-blur-sm text-white hover:bg-white/20 transition-all duration-300 hover:scale-110 shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
        </button>
        
        <!-- Progress Dots -->
        <div id="heroDots" class="absolute bottom-8 left-1/2 -translate-x-1/2 z-20 flex gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-white" data-index="0"></span>
            <span class="w-2.5 h-2.5 rounded-full bg-white" data-index="1"></span>
        </div>
    </div>

    <!-- Text Content (Centered) -->
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto text-center">
            <!-- Title -->
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold mt-5 leading-[1.08] tracking-tight">
                Community-Powered Waste Reporting.
                <br>
                <span class="bg-gradient-to-r from-[#10B981] to-[#34D399] bg-clip-text text-transparent">Cleaner Tomorrow.</span>
            </h1>
            <!-- Subtitle -->
            <p class="text-base sm:text-lg text-emerald-100/80 mt-4 max-w-2xl mx-auto leading-relaxed font-medium">
                Report uncollected garbage, illegal dumping, and hazardous waste in <?php echo $barangayName; ?>.
                Track resolution status in real-time.
            </p>
            <!-- CTAs -->
            <div class="flex flex-wrap items-center justify-center gap-3 mt-8">
                <a href="/brgy-waste-app-v3/public/index.php?url=<?php echo $isLoggedIn ? urlencode($role == 'resident' ? 'resident/submit' : 'auth') : 'auth/register'; ?>" class="inline-flex items-center gap-2 px-6 py-3 bg-[#10B981] text-white font-bold rounded-full shadow-lg shadow-emerald-500/30 hover:bg-emerald-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                    Report Waste (Resident)
                </a>
                <a href="/brgy-waste-app-v3/public/index.php?url=guest" class="inline-flex items-center gap-2 px-6 py-3 bg-white/10 backdrop-blur-md border border-white/30 text-white font-bold rounded-full hover:bg-white/20 transition-colors shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    Report as Guest
                </a>
                <a href="/brgy-waste-app-v3/public/index.php?url=guest/track" class="inline-flex items-center gap-2 px-5 py-3 text-emerald-200 hover:text-white font-semibold text-sm transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    Track Report
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- SMART REPORTING / FEATURES SECTION                          -->
<!-- ============================================================ -->
<section id="features" class="py-16 bg-[#F8FAFC]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mt-2 tracking-tight">A smarter way for residents to help</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
            <!-- Left: Text & Buttons -->
            <div class="lg:col-span-5">
                <p class="text-slate-600 leading-relaxed text-base">
                    The Barangay Waste Reporting System empowers residents to actively participate in keeping their community clean.
                    By reporting waste issues through the platform, residents contribute real-time data that helps the barangay plan
                    more effective and efficient collection operations.
                </p>
                <div class="flex flex-wrap items-center gap-4 mt-6">
                    <?php if ($isLoggedIn && $role == 'resident'): ?>
                        <a href="/brgy-waste-app-v3/public/index.php?url=resident/submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#10B981] text-white font-bold rounded-full shadow-lg shadow-emerald-500/20 hover:bg-emerald-600 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                            Submit a Report
                        </a>
                        <a href="/brgy-waste-app-v3/public/index.php?url=resident" class="inline-flex items-center gap-2 px-5 py-2.5 border border-slate-300 text-slate-700 font-semibold rounded-lg hover:bg-slate-50 transition-colors">Dashboard</a>
                    <?php elseif ($isLoggedIn): ?>
                        <a href="/brgy-waste-app-v3/public/index.php?url=<?php echo urlencode($role); ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#10B981] text-white font-bold rounded-full shadow-lg shadow-emerald-500/20 hover:bg-emerald-600 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V21h14V9.5"/></svg>
                            Go to Dashboard
                        </a>
                    <?php else: ?>
                        <a href="/brgy-waste-app-v3/public/index.php?url=auth/register" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#10B981] text-white font-bold rounded-full shadow-lg shadow-emerald-500/20 hover:bg-emerald-600 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                            Create an Account
                        </a>
                        <a href="/brgy-waste-app-v3/public/index.php?url=auth" class="inline-flex items-center gap-2 px-5 py-2.5 border border-slate-300 text-slate-700 font-semibold rounded-lg hover:bg-slate-50 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                            Login
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Right: Feature Grid 2x2 -->
            <div class="lg:col-span-7">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Feature 1 -->
                    <div class="feature-card bg-white rounded-2xl border border-slate-200 p-5 shadow-[0_8px_25px_-12px_rgba(0,0,0,0.08)]">
                        <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                        <h4 class="font-bold text-slate-800">Precise Location Tagging</h4>
                        <p class="text-sm text-slate-500 mt-1">Pin waste locations on an interactive map for accurate reporting.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="feature-card bg-white rounded-2xl border border-slate-200 p-5 shadow-[0_8px_25px_-12px_rgba(0,0,0,0.08)]">
                        <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                        </div>
                        <h4 class="font-bold text-slate-800">Photo & Verification</h4>
                        <p class="text-sm text-slate-500 mt-1">Upload photos as evidence and verify report authenticity.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="feature-card bg-white rounded-2xl border border-slate-200 p-5 shadow-[0_8px_25px_-12px_rgba(0,0,0,0.08)]">
                        <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </div>
                        <h4 class="font-bold text-slate-800">Status Tracking</h4>
                        <p class="text-sm text-slate-500 mt-1">Monitor your report from submission to resolution in real-time.</p>
                    </div>

                    <!-- Feature 4 -->
                    <div class="feature-card bg-white rounded-2xl border border-slate-200 p-5 shadow-[0_8px_25px_-12px_rgba(0,0,0,0.08)]">
                        <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        </div>
                        <h4 class="font-bold text-slate-800">Direct Resolution</h4>
                        <p class="text-sm text-slate-500 mt-1">Barangay officials review and resolve reports efficiently.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- ANNOUNCEMENTS SECTION                                       -->
<!-- ============================================================ -->
<section id="announcements" class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-10">
            <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mt-2 tracking-tight">Announcements</h2>
            <p class="text-slate-500 mt-1 text-base">Stay informed about collection schedules, events, and community notices.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Left: Announcement List -->
            <div class="lg:col-span-5 space-y-3">
                <?php if (!empty($announcements)): ?>
                    <?php
                    $types = ['Urgent' => 'bg-red-100 text-red-700', 'Notice' => 'bg-cyan-50 text-cyan-700', 'Event' => 'bg-emerald-50 text-emerald-700', 'Update' => 'bg-amber-50 text-amber-700'];
                    $dots = ['Urgent' => 'bg-red-500', 'Notice' => 'bg-cyan-600', 'Event' => 'bg-emerald-600', 'Update' => 'bg-amber-500'];
                    ?>
                    <?php foreach (array_slice($announcements, 0, 5) as $item):
                        $type = 'Notice';
                        if (stripos($item['title'], 'collection') !== false || stripos($item['content'], 'collection') !== false) $type = 'Urgent';
                        elseif (stripos($item['title'], 'clean') !== false || stripos($item['title'], 'drive') !== false) $type = 'Event';
                        elseif (stripos($item['title'], 'update') !== false || stripos($item['title'], 'available') !== false) $type = 'Update';
                    ?>
                    <div class="announcement-item flex items-center justify-between p-3 rounded-xl border border-slate-100 hover:border-slate-200 cursor-pointer">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="w-2 h-2 rounded-full <?php echo $dots[$type] ?? 'bg-slate-400'; ?> flex-shrink-0"></span>
                            <div>
                                <p class="text-sm font-semibold text-slate-800 truncate"><?php echo htmlspecialchars($item['title']); ?></p>
                                <p class="text-xs text-slate-400"><?php echo date('M j, Y', strtotime($item['created_at'])); ?></p>
                            </div>
                        </div>
                        <span class="inline-flex rounded-full px-2 py-0.5 text-[9px] font-bold <?php echo $types[$type] ?? 'bg-slate-100 text-slate-700'; ?> flex-shrink-0 ml-2"><?php echo $type; ?></span>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="p-6 text-center text-slate-400 bg-slate-50 rounded-xl">No announcements yet — check back soon.</div>
                <?php endif; ?>
            </div>

            <!-- Right: Featured Announcement Card -->
            <div class="lg:col-span-7">
                <?php
                $featured = !empty($announcements) ? $announcements[0] : null;
                if ($featured):
                    $fTitle = $featured['title'] ?? 'Special collection today';
                    $fContent = $featured['content'] ?? 'Stay updated with barangay announcements.';
                    $fDate = date('M j, Y', strtotime($featured['created_at'] ?? 'now'));
                ?>
                <div class="bg-[#07281E] rounded-2xl p-6 md:p-8 text-white shadow-[0_24px_60px_-30px_rgba(7,40,30,0.5)]">
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-red-500/20 text-red-300 text-[10px] font-bold rounded-full border border-red-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-400 pulse-dot"></span>
                            Pinned
                        </span>
                        <span class="text-sm text-emerald-200/70"><?php echo $fDate; ?></span>
                    </div>
                    <h3 class="text-xl md:text-2xl font-extrabold mt-4 leading-tight"><?php echo htmlspecialchars($fTitle); ?></h3>
                    <p class="text-emerald-100/80 text-sm mt-3 leading-relaxed"><?php echo nl2br(htmlspecialchars(substr($fContent, 0, 150) . (strlen($fContent) > 150 ? '...' : ''))); ?></p>

                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-4 mt-6 pt-6 border-t border-white/10">
                        <div>
                            <p class="text-2xl font-black text-white"><?php echo count($announcements); ?></p>
                            <p class="text-[10px] text-emerald-200/60 font-medium uppercase tracking-wider">Active Notices</p>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-red-400"><?php echo count(array_filter($announcements, fn($a) => stripos($a['title'] ?? '', 'collection') !== false || stripos($a['content'] ?? '', 'collection') !== false)); ?></p>
                            <p class="text-[10px] text-emerald-200/60 font-medium uppercase tracking-wider">Urgent</p>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-amber-400"><?php echo count(array_filter($announcements, fn($a) => stripos($a['title'] ?? '', 'reschedule') !== false || stripos($a['title'] ?? '', 'postpone') !== false)); ?></p>
                            <p class="text-[10px] text-emerald-200/60 font-medium uppercase tracking-wider">Rescheduled</p>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="bg-[#07281E] rounded-2xl p-6 md:p-8 text-white flex flex-col items-center justify-center text-center min-h-[200px] gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-200/40"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                    <p class="text-emerald-200/60 text-sm">All quiet — no pinned announcements right now.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- GARBAGE COLLECTION SCHEDULE                                -->
<!-- ============================================================ -->
<section id="schedule" class="py-16 bg-[#F8FAFC]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-10">
            <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mt-2 tracking-tight">Garbage Collection Schedule</h2>
            <p class="text-slate-500 mt-1 text-base">Current schedule for <?php echo $barangayName; ?>. Please have your bins at the curb by the indicated time.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <?php if (!empty($schedules)): ?>
                <?php foreach ($schedules as $schedule):
                    $day = $schedule['collection_day'];
                    $wasteType = $schedule['waste_type'] ?? 'General';
                    $colors = $wasteTypeColors[$wasteType] ?? ['bg' => 'slate-100', 'text' => 'slate-600', 'accent' => 'slate-400'];
                    $start = date('g:i A', strtotime($schedule['start_time']));
                    $end = date('g:i A', strtotime($schedule['end_time']));
                    $puroks = $schedule['puroks'] ?? 'All Puroks';
                ?>
                <div class="schedule-card bg-white rounded-2xl border border-slate-200 border-l-4 border-l-<?php echo $colors['accent']; ?> p-5 shadow-[0_8px_25px_-12px_rgba(0,0,0,0.08)]">
                    <div class="flex items-center justify-between mb-3">
                        <span class="font-bold text-slate-900 text-lg"><?php echo $day; ?></span>
                        <span class="inline-flex rounded-full px-2.5 py-0.5 text-[9px] font-bold bg-<?php echo $colors['bg']; ?> text-<?php echo $colors['text']; ?>">
                            <?php echo htmlspecialchars($wasteType); ?>
                        </span>
                    </div>
                    <p class="text-sm font-semibold text-slate-600"><?php echo $start; ?> – <?php echo $end; ?></p>
                    <p class="text-xs text-slate-400 mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" class="inline mr-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        <?php echo htmlspecialchars($puroks); ?>
                    </p>
                    <?php if (!empty($schedule['special_notes'])): ?>
                        <p class="text-[10px] text-amber-600 mt-2 font-medium"><?php echo htmlspecialchars($schedule['special_notes']); ?></p>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full text-center text-slate-400 py-8 bg-white rounded-2xl border border-slate-200">Schedule not published yet — check back soon.</div>
            <?php endif; ?>
        </div>

        <!-- Notice Banner -->
        <?php
        $specialNotice = !empty($announcements) ? array_filter($announcements, fn($a) => stripos($a['title'] ?? '', 'collection') !== false || stripos($a['content'] ?? '', 'collection') !== false) : [];
        $notice = !empty($specialNotice) ? array_values($specialNotice)[0] : null;
        ?>
        <?php if ($notice): ?>
        <div class="mt-6 bg-amber-50 border border-amber-200 rounded-2xl p-5 flex items-start gap-4">
            <div class="w-9 h-9 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 flex-shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l8 15H4L12 3z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
            </div>
            <div>
                <h4 class="font-bold text-amber-800 text-sm"><?php echo htmlspecialchars($notice['title']); ?></h4>
                <p class="text-sm text-amber-700 mt-0.5"><?php echo nl2br(htmlspecialchars($notice['content'])); ?></p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- ============================================================ -->
<!-- MOBILE EXPERIENCE SECTION                                  -->
<!-- ============================================================ -->
<section class="py-16 bg-[#E6F4EA]/40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
            <!-- Left: Phone Mockup -->
            <div class="lg:col-span-5 flex justify-center">
                <div class="relative w-[280px] h-[560px] bg-[#07281E] rounded-[40px] border-[8px] border-slate-200 shadow-2xl overflow-hidden">
                    <!-- Phone Screen -->
                    <div class="absolute inset-[4px] bg-[#0B3024] rounded-[32px] overflow-hidden flex flex-col">
                        <!-- Status Bar -->
                        <div class="flex justify-between items-center px-6 pt-3">
                            <span class="text-white/70 text-[10px] font-bold">9:41</span>
                            <div class="flex gap-1">
                                <div class="w-4 h-2 rounded-sm bg-white/30"></div>
                                <div class="w-2 h-2 rounded-full bg-white/30"></div>
                            </div>
                        </div>
                        <!-- Map Preview -->
                        <div class="flex-1 m-3 rounded-xl bg-[#07281E] border border-white/10 relative overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-br from-emerald-900/20 to-transparent"></div>
                            <!-- Heatmap dots -->
                            <div class="absolute top-8 left-8 w-4 h-4 rounded-full bg-emerald-500/30 animate-pulse"></div>
                            <div class="absolute top-16 left-16 w-6 h-6 rounded-full bg-emerald-400/20"></div>
                            <div class="absolute top-24 left-32 w-8 h-8 rounded-full bg-emerald-300/15"></div>
                            <div class="absolute bottom-12 right-8 w-5 h-5 rounded-full bg-emerald-500/25"></div>
                            <div class="absolute bottom-8 right-20 w-3 h-3 rounded-full bg-emerald-400/30"></div>
                            <!-- Fake marker -->
                            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-4 h-4 rounded-full bg-[#10B981] border-2 border-white shadow-lg"></div>
                            <!-- Bottom label -->
                            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex items-center gap-1.5 bg-black/50 backdrop-blur px-3 py-1 rounded-full">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 pulse-dot"></span>
                                <span class="text-white text-[8px] font-bold">Live Heatmap · 3 hotspots</span>
                            </div>
                        </div>
                        <!-- Bottom Nav -->
                        <div class="flex justify-around px-4 pb-3 pt-1">
                            <span class="text-emerald-400/60 text-[8px] font-bold">🗺️ Map</span>
                            <span class="text-emerald-400/30 text-[8px] font-bold">📊 Stats</span>
                            <span class="text-emerald-400/30 text-[8px] font-bold">⚡ Alerts</span>
                            <span class="text-emerald-400/30 text-[8px] font-bold">👤 Profile</span>
                        </div>
                    </div>
                    <!-- Home indicator -->
                    <div class="absolute bottom-1.5 left-1/2 -translate-x-1/2 w-24 h-1 rounded-full bg-white/30"></div>
                </div>
            </div>

            <!-- Right: Feature List -->
            <div class="lg:col-span-7 space-y-6">
                <div>
                    <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mt-2 tracking-tight">Manage waste on the go</h2>
                    <p class="text-slate-500 mt-1 text-base">Full reporting, live analytics, and zone alerts — right from your pocket.</p>
                </div>

                <div class="space-y-4">
                    <!-- Feature 1 -->
                    <div class="flex items-start gap-4 p-4 bg-white rounded-2xl border border-slate-200 shadow-sm">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800">One-tap Reporting</h4>
                            <p class="text-sm text-slate-500">Capture, describe, and submit waste issues in under 20 seconds.</p>
                        </div>
                    </div>

                    <!-- Feature 2 -->
                    <div class="flex items-start gap-4 p-4 bg-white rounded-2xl border border-slate-200 shadow-sm">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800">Live Map View</h4>
                            <p class="text-sm text-slate-500">View real-time heatmap of waste reports and active hotspots.</p>
                        </div>
                    </div>

                    <!-- Feature 3 -->
                    <div class="flex items-start gap-4 p-4 bg-white rounded-2xl border border-slate-200 shadow-sm">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800">Instant Alerts</h4>
                            <p class="text-sm text-slate-500">Push notifications for report status changes and urgent notices.</p>
                        </div>
                    </div>

                    <!-- Feature 4 -->
                    <div class="flex items-start gap-4 p-4 bg-white rounded-2xl border border-slate-200 shadow-sm">
                        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800">Resident Portal Access</h4>
                            <p class="text-sm text-slate-500">Full dashboard access from anywhere, anytime on any device.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- HOW IT WORKS – 3 Steps                                     -->
<!-- ============================================================ -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mt-2 tracking-tight">Three steps to report waste</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Step 1 -->
            <div class="step-card bg-[#F8FAFC] rounded-2xl p-6 border border-slate-200 relative">
                <span class="inline-flex items-center justify-center w-8 h-8 bg-emerald-500 text-white font-bold text-sm rounded-full mb-4 shadow-lg shadow-emerald-500/20">01</span>
                <h4 class="font-bold text-slate-800">Register & Log In</h4>
                <p class="text-sm text-slate-500 mt-2 leading-relaxed">Create a free account using your email or mobile number. Verify via OTP and log in to access your personal resident dashboard.</p>
                <a href="/brgy-waste-app-v3/public/index.php?url=auth/register" class="inline-flex items-center gap-1 text-sm font-semibold text-[#10B981] mt-4 hover:underline">Get started →</a>
            </div>

            <!-- Step 2 -->
            <div class="step-card bg-[#F8FAFC] rounded-2xl p-6 border border-slate-200 relative">
                <span class="inline-flex items-center justify-center w-8 h-8 bg-emerald-500 text-white font-bold text-sm rounded-full mb-4 shadow-lg shadow-emerald-500/20">02</span>
                <h4 class="font-bold text-slate-800">Snap & Report</h4>
                <p class="text-sm text-slate-500 mt-2 leading-relaxed">Take a photo of the waste issue, describe it briefly, and pin the exact location on the map. Submissions go directly to the barangay.</p>
                <?php if ($isLoggedIn && $role == 'resident'): ?>
                    <a href="/brgy-waste-app-v3/public/index.php?url=resident/submit" class="inline-flex items-center gap-1 text-sm font-semibold text-[#10B981] mt-4 hover:underline">Submit now →</a>
                <?php endif; ?>
            </div>

            <!-- Step 3 -->
            <div class="step-card bg-[#F8FAFC] rounded-2xl p-6 border border-slate-200 relative">
                <span class="inline-flex items-center justify-center w-8 h-8 bg-emerald-500 text-white font-bold text-sm rounded-full mb-4 shadow-lg shadow-emerald-500/20">03</span>
                <h4 class="font-bold text-slate-800">Track Resolution</h4>
                <p class="text-sm text-slate-500 mt-2 leading-relaxed">Receive status updates as the barangay reviews and acts on your report — from pending to resolved, every step is visible.</p>
                <?php if ($isLoggedIn && $role == 'resident'): ?>
                    <a href="/brgy-waste-app-v3/public/index.php?url=resident/my_report" class="inline-flex items-center gap-1 text-sm font-semibold text-[#10B981] mt-4 hover:underline">View my reports →</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- FAQ SECTION                                                -->
<!-- ============================================================ -->
<section id="faq" class="py-16 bg-[#F8FAFC]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mt-2 tracking-tight">Frequently Asked Questions</h2>
            <p class="text-slate-500 mt-1 text-base">Everything you need to know about using the Waste Reporting System.</p>
        </div>

        <div class="space-y-3">
            <!-- FAQ Items -->
            <?php
            $faqs = [
                ['q' => 'How do I create an account?', 'a' => 'Click the "Register" button on the top right, fill in your name, email or phone number, and create a password. You will receive an OTP to verify your account.'],
                ['q' => 'How do I submit a waste report?', 'a' => 'After logging in, go to "Submit Report", take a photo, describe the issue, and pin the location on the map. Your report will be sent directly to the barangay.'],
                ['q' => 'What types of waste can be reported?', 'a' => 'You can report illegal dumping, overflowing garbage bins, uncollected garbage, construction waste, yard waste, hazardous waste, and other waste-related issues.'],
                ['q' => 'Why was my report marked as a duplicate?', 'a' => 'If a similar report already exists within 50 meters of your location, the system will suggest you support the existing report instead of creating a new one.'],
                ['q' => 'How can I support an existing report?', 'a' => 'When you try to submit a report and the system finds a nearby duplicate, you will see a popup with the option to "Support Existing Report" – click that to add your support.'],
                ['q' => 'How are hotspots generated on the map?', 'a' => 'Hotspots are created when multiple reports are submitted within 50 meters of each other. The heatmap uses color coding to show density, from green (low) to red (high).']
            ];
            ?>
            <?php foreach ($faqs as $index => $faq): ?>
            <div class="faq-item bg-white rounded-2xl border border-slate-200 overflow-hidden">
                <button class="faq-question w-full flex items-center justify-between p-5 text-left hover:bg-slate-50 transition-colors" data-target="faq-<?php echo $index; ?>">
                    <span class="font-semibold text-slate-800 text-sm"><?php echo htmlspecialchars($faq['q']); ?></span>
                    <svg class="w-5 h-5 text-slate-400 transition-transform duration-300 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div id="faq-<?php echo $index; ?>" class="faq-answer max-h-0 overflow-hidden px-5 text-sm text-slate-500 leading-relaxed"><?php echo htmlspecialchars($faq['a']); ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- CONTACT & FINAL CTA                                        -->
<!-- ============================================================ -->
<section id="contact" class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
            <!-- Contact Info Card -->
            <div class="lg:col-span-6 bg-white rounded-2xl border border-slate-200 p-6 md:p-8 shadow-[0_8px_25px_-12px_rgba(0,0,0,0.08)]">
                <span class="text-[#10B981] text-[20px] font-bold uppercase ">CONTACT US</span>
                <h3 class="text-2xl font-extrabold text-slate-900 mt-2">Reach the Barangay Office</h3>
                <p class="text-slate-500 mt-1 text-sm">For concerns that cannot be addressed through the system — or if you need direct assistance — reach out to the Barangay <?php echo $barangayName; ?> office.</p>

                <div class="mt-6 space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-700 text-sm">Address</p>
                            <p class="text-sm text-slate-500"><?php echo htmlspecialchars($barangayAddress); ?></p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-700 text-sm">Telephone</p>
                            <p class="text-sm text-slate-500"><?php echo htmlspecialchars($barangayContact); ?></p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-700 text-sm">Email</p>
                            <p class="text-sm text-slate-500"><?php echo htmlspecialchars($barangayEmail); ?></p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-700 text-sm">Office Hours</p>
                            <p class="text-sm text-slate-500">Monday – Friday, 8:00 AM – 5:00 PM</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA Card -->
            <div class="lg:col-span-6 bg-[#07281E] rounded-2xl p-6 md:p-8 text-white shadow-[0_24px_60px_-30px_rgba(7,40,30,0.5)] flex flex-col justify-center">
                <span class="text-[#10B981] text-[11px] font-bold uppercase tracking-[0.25em]">JOIN THE MOVEMENT</span>
                <h3 class="text-2xl sm:text-3xl font-extrabold mt-2 tracking-tight">Join the Waste Reporting System</h3>
                <p class="text-emerald-100/80 mt-2 text-sm leading-relaxed">
                    Create an account to submit reports and track resolution in real time.
                </p>
                <div class="flex flex-wrap items-center gap-3 mt-6">
                    <?php if ($isLoggedIn): ?>
                        <a href="/brgy-waste-app-v3/public/index.php?url=<?php echo urlencode($role == 'resident' ? 'resident' : ($role == 'supervisor' ? 'supervisor' : 'admin')); ?>" class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#10B981] text-white font-bold rounded-full shadow-lg shadow-emerald-500/30 hover:bg-emerald-600 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V21h14V9.5"/></svg>
                            Go to Dashboard
                        </a>
                    <?php else: ?>
                        <a href="/brgy-waste-app-v3/public/index.php?url=auth/register" class="inline-flex items-center gap-2 px-6 py-2.5 bg-[#10B981] text-white font-bold rounded-full shadow-lg shadow-emerald-500/30 hover:bg-emerald-600 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                            Register — It's Free
                        </a>
                        <a href="/brgy-waste-app-v3/public/index.php?url=auth" class="inline-flex items-center gap-2 px-6 py-2.5 border border-white/25 text-white font-semibold rounded-lg hover:bg-white/10 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                            Already have an account? Login
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- FOOTER                                                     -->
<!-- ============================================================ -->
<footer class="bg-[#051E17] text-slate-300 py-12 px-4">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Brand -->
            <div class="md:col-span-1">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl bg-[#10B981] flex items-center justify-center text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    <span class="font-extrabold text-white text-lg">WasteWatch</span>
                </div>
                <p class="text-sm text-slate-400 mt-3 leading-relaxed">
                    Barangay <?php echo $barangayName; ?> Waste Management Portal.<br>
                    Community-powered waste reporting.
                </p>
                <p class="text-xs text-slate-500 mt-4">© <?php echo date('Y'); ?> WasteWatch. All rights reserved.</p>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-white font-bold text-sm mb-4">Quick Links</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="/brgy-waste-app-v3/public/" class="text-slate-400 hover:text-white transition-colors">Home</a></li>
                    <li><a href="#features" class="text-slate-400 hover:text-white transition-colors">Features</a></li>
                    <li><a href="#schedule" class="text-slate-400 hover:text-white transition-colors">Schedule</a></li>
                    <li><a href="#announcements" class="text-slate-400 hover:text-white transition-colors">Announcements</a></li>
                    <li><a href="#faq" class="text-slate-400 hover:text-white transition-colors">FAQs</a></li>
                </ul>
            </div>

            <!-- Resources -->
            <div>
                <h4 class="text-white font-bold text-sm mb-4">Resources</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="/brgy-waste-app-v3/public/index.php?url=auth/register" class="text-slate-400 hover:text-white transition-colors">Create Account</a></li>
                    <li><a href="/brgy-waste-app-v3/public/index.php?url=auth" class="text-slate-400 hover:text-white transition-colors">Login</a></li>
                    <li><a href="#" class="text-slate-400 hover:text-white transition-colors">Privacy Policy</a></li>
                    <li><a href="#" class="text-slate-400 hover:text-white transition-colors">Terms of Service</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h4 class="text-white font-bold text-sm mb-4">Contact</h4>
                <ul class="space-y-2 text-sm">
                    <li class="flex items-start gap-2 text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="flex-shrink-0 mt-0.5"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        <?php echo htmlspecialchars($barangayAddress); ?>
                    </li>
                    <li class="flex items-start gap-2 text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="flex-shrink-0 mt-0.5"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <?php echo htmlspecialchars($barangayContact); ?>
                    </li>
                    <li class="flex items-start gap-2 text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="flex-shrink-0 mt-0.5"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                        <?php echo htmlspecialchars($barangayEmail); ?>
                    </li>
                </ul>
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-white/10 text-center text-xs text-slate-500">
            <p>Barangay <?php echo $barangayName; ?> Waste Management Portal · Smart Waste Solutions</p>
        </div>
    </div>
</footer>

<!-- ============================================================ -->
<!-- JAVASCRIPT                                                  -->
<!-- ============================================================ -->
<script>
    // ====== Hero Background Slider ======
    document.addEventListener('DOMContentLoaded', function() {
        const slides = document.querySelectorAll('.hero-slide');
        const dots = document.querySelectorAll('#heroDots span');
        const prevBtn = document.getElementById('heroPrev');
        const nextBtn = document.getElementById('heroNext');
        let currentIndex = 0;
        const totalSlides = slides.length;
        let interval;

        function goToSlide(index) {
            slides.forEach(slide => slide.classList.remove('active'));
            dots.forEach(dot => dot.classList.remove('bg-white/70', 'active-dot'));
            
            slides[index].classList.add('active');
            dots[index].classList.add('bg-white/70', 'active-dot');
            
            currentIndex = index;
        }

        function nextSlide() {
            goToSlide((currentIndex + 1) % totalSlides);
        }

        function prevSlide() {
            goToSlide((currentIndex - 1 + totalSlides) % totalSlides);
        }

        // Set initial state
        goToSlide(0);

        // Event Listeners
        nextBtn.addEventListener('click', function() {
            clearInterval(interval);
            nextSlide();
            startAutoPlay();
        });
        
        prevBtn.addEventListener('click', function() {
            clearInterval(interval);
            prevSlide();
            startAutoPlay();
        });

        dots.forEach(dot => {
            dot.addEventListener('click', function() {
                clearInterval(interval);
                goToSlide(parseInt(this.dataset.index));
                startAutoPlay();
            });
        });

        function startAutoPlay() {
            interval = setInterval(nextSlide, 5000);
        }
        startAutoPlay();
    });

    // ====== FAQ Accordion ======
    document.querySelectorAll('.faq-question').forEach(button => {
        button.addEventListener('click', function() {
            const target = document.getElementById(this.dataset.target);
            const isOpen = target.style.maxHeight && target.style.maxHeight !== '0px';

            // Close all
            document.querySelectorAll('.faq-answer').forEach(ans => {
                ans.style.maxHeight = '0px';
                ans.style.paddingTop = '0px';
                ans.style.paddingBottom = '0px';
                ans.parentElement.querySelector('.faq-question svg').style.transform = 'rotate(0deg)';
            });

            if (!isOpen) {
                target.style.maxHeight = target.scrollHeight + 'px';
                target.style.paddingTop = '16px';
                target.style.paddingBottom = '16px';
                this.querySelector('svg').style.transform = 'rotate(180deg)';
            }
        });
    });

    // ====== Mobile Menu ======
    const menuToggle = document.getElementById('menuToggle');
    const menuClose = document.getElementById('menuClose');
    const mobileMenu = document.getElementById('mobileMenu');

    if (menuToggle && mobileMenu) {
        menuToggle.addEventListener('click', () => {
            mobileMenu.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });
        menuClose.addEventListener('click', () => {
            mobileMenu.classList.add('hidden');
            document.body.style.overflow = '';
        });
        // Close on link click
        mobileMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
                document.body.style.overflow = '';
            });
        });
    }

    // ====== Smooth scroll for anchor links ======
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href === '#') return;
            const target = document.querySelector(href);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>