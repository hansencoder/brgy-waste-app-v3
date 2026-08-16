<?php include __DIR__ . '/../layouts/header.php'; ?>

<?php
$isLoggedIn = $data['isLoggedIn'] ?? false;
$role = $data['role'] ?? null;
$announcements = $data['announcements'] ?? [];
$schedules = $data['schedules'] ?? [];
$barangay = $data['barangay'] ?? [];
$unreadCount = $data['unreadCount'] ?? 0;
$barangayName = $barangay['barangay_name'] ?? 'Dulong Bayan';
$barangayAddress = $barangay['official_address'] ?? 'Barangay Hall, Dulong Bayan, Talavera, Nueva Ecija';
$barangayContact = $barangay['contact_number'] ?? '(044) 940-1234 / 0917-123-4567';
$barangayEmail = $barangay['official_email'] ?? 'brgy.dulongbayan@gmail.com';
$sysLogo = $barangay['system_logo'] ?? null;
if ($sysLogo && strpos($sysLogo, '/brgy-waste-app-v3') === false && strpos($sysLogo, '/public') === 0) {
    $sysLogo = '/brgy-waste-app-v3' . $sysLogo;
}
$sysShortName = $barangay['system_short_name'] ?? 'WasteWatch';
$sysMotto = $barangay['system_motto'] ?? 'SMART WASTE SOLUTIONS';

$wasteTypeColors = [
    'General' => ['bg' => 'bg-slate-100 text-slate-700', 'accent' => 'border-l-slate-400', 'badge' => 'bg-slate-100 text-slate-700'],
    'Biodegradable' => ['bg' => 'bg-emerald-50 text-emerald-800', 'accent' => 'border-l-emerald-500', 'badge' => 'bg-emerald-50 text-emerald-800'],
    'Recyclable' => ['bg' => 'bg-blue-50 text-blue-800', 'accent' => 'border-l-blue-500', 'badge' => 'bg-blue-50 text-blue-800'],
    'Non-Biodegradable' => ['bg' => 'bg-blue-50 text-blue-800', 'accent' => 'border-l-blue-500', 'badge' => 'bg-blue-50 text-blue-800'],
    'Residual' => ['bg' => 'bg-amber-50 text-amber-900', 'accent' => 'border-l-amber-500', 'badge' => 'bg-amber-50 text-amber-900'],
    'Residual Waste' => ['bg' => 'bg-amber-50 text-amber-900', 'accent' => 'border-l-amber-500', 'badge' => 'bg-amber-50 text-amber-900'],
    'Special / Hazardous' => ['bg' => 'bg-purple-50 text-purple-900', 'accent' => 'border-l-purple-500', 'badge' => 'bg-purple-50 text-purple-900'],
];
$mapConfig = $data['mapConfig'] ?? [];
$publicReports = $data['publicReports'] ?? [];

// Calculate map statistics & points
$reportPoints = [];
$mapStats = [
    'total' => count($publicReports),
    'pending' => 0,
    'verified' => 0,
    'in_progress' => 0,
    'resolved' => 0
];

foreach ($publicReports as $pr) {
    $st = strtolower(trim($pr['status'] ?? ''));
    if ($st === 'resolved') {
        $mapStats['resolved']++;
    } elseif ($st === 'verified') {
        $mapStats['verified']++;
    } elseif ($st === 'in progress' || $st === 'dispatched') {
        $mapStats['in_progress']++;
    } else {
        $mapStats['pending']++;
    }

    $photoUrl = null;
    if (!empty($pr['photo_path'])) {
        $photoUrl = (strpos($pr['photo_path'], '/brgy-waste-app-v3') === false && strpos($pr['photo_path'], '/public') === 0)
            ? '/brgy-waste-app-v3' . $pr['photo_path']
            : $pr['photo_path'];
    }

    $reportPoints[] = [
        'id' => $pr['id'],
        'lat' => (float)$pr['latitude'],
        'lng' => (float)$pr['longitude'],
        'status' => $pr['status'] ?? 'Pending',
        'status_color' => $pr['status_color'] ?? '#F59E0B',
        'category' => $pr['waste_category'] ?? 'General Waste',
        'purok' => $pr['purok'] ?? 'Barangay Area',
        'desc' => !empty($pr['description']) ? mb_strimwidth($pr['description'], 0, 75, '...') : 'Community waste incident reported.',
        'date' => date('M d, Y', strtotime($pr['submission_date'])),
        'photo' => $photoUrl
    ];
}
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
        html { scroll-behavior: smooth !important; }
        * { font-family: 'Miranda Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important; font-optical-sizing: auto; }
        
        .pulse-dot { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.2; } }
        
        .hover-lift { transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease; }
        .hover-lift:hover { transform: translateY(-3px); box-shadow: 0 12px 30px -12px rgba(0,0,0,0.08); }
        
        .hero-slide { opacity: 0; transition: opacity 1s cubic-bezier(0.4, 0, 0.6, 1); }
        .hero-slide.active { opacity: 1; }
        
        .mobile-menu { transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s ease; }
        .faq-answer { transition: max-height 0.3s ease, padding 0.3s ease; }
        
        a:focus-visible, button:focus-visible {
            outline: 2px solid #10B981;
            outline-offset: 2px;
            border-radius: 6px;
        }
        
        @media (prefers-reduced-motion: reduce) {
            .pulse-dot, .hover-lift, .hero-slide, .mobile-menu, .faq-answer { animation: none !important; transition: none !important; }
        }
    </style>
</head>
<body class="antialiased bg-[#F8FAFC] text-slate-700">

<!-- ============================================================ -->
<!-- NAVIGATION – Sticky Header                                   -->
<!-- ============================================================ -->
<nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <a href="/brgy-waste-app-v3/public/" class="flex items-center gap-3 flex-shrink-0 group">
                <div class="w-10 h-10 rounded-full bg-[#07281E] flex items-center justify-center text-white shadow-sm overflow-hidden border border-emerald-500/20 group-hover:border-emerald-400/50 transition-colors">
                    <?php if (!empty($sysLogo)): ?>
                        <img src="<?php echo htmlspecialchars($sysLogo); ?>" class="w-full h-full object-cover">
                    <?php else: ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    <?php endif; ?>
                </div>
                <div>
                    <span class="font-bold text-slate-900 text-base sm:text-lg tracking-tight"><?php echo htmlspecialchars($sysShortName); ?></span>
                    <span class="hidden sm:block text-[10px] font-medium text-emerald-700 tracking-wide uppercase">Brgy. <?php echo htmlspecialchars($barangayName); ?></span>
                </div>
            </a>

            <!-- Desktop Nav Links -->
            <div class="hidden lg:flex items-center gap-1 text-xs sm:text-sm font-medium text-slate-600">
                <a href="#features" class="px-3 py-2 hover:text-emerald-800 transition-colors rounded-lg hover:bg-emerald-50">Features</a>
                <a href="#community-map" class="px-3 py-2 hover:text-emerald-800 transition-colors rounded-lg hover:bg-emerald-50 font-semibold text-emerald-800 flex items-center gap-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Community Map
                </a>
                <a href="#community-guide" class="px-3 py-2 hover:text-emerald-800 transition-colors rounded-lg hover:bg-emerald-50">Waste Guide &amp; Tips</a>
                <a href="#penalties" class="px-3 py-2 hover:text-emerald-800 transition-colors rounded-lg hover:bg-emerald-50">Penalties &amp; Laws</a>
                <a href="#schedule" class="px-3 py-2 hover:text-emerald-800 transition-colors rounded-lg hover:bg-emerald-50">Schedule &amp; Notes</a>
                <a href="#announcements" class="px-3 py-2 hover:text-emerald-800 transition-colors rounded-lg hover:bg-emerald-50">Bulletins</a>
                <a href="#faq" class="px-3 py-2 hover:text-emerald-800 transition-colors rounded-lg hover:bg-emerald-50">FAQs</a>
                <a href="#contact" class="px-3 py-2 hover:text-emerald-800 transition-colors rounded-lg hover:bg-emerald-50">Contact</a>
            </div>

            <!-- Right Actions -->
            <div class="flex items-center gap-3">
                <?php if ($isLoggedIn): ?>
                    <a href="/brgy-waste-app-v3/public/index.php?url=<?php echo urlencode($role == 'resident' ? 'resident' : ($role == 'supervisor' ? 'supervisor' : 'admin')); ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-[#0B2E22] hover:bg-[#083528] text-white text-xs font-semibold rounded-xl shadow-xs transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V21h14V9.5"/></svg>
                        Dashboard
                    </a>
                <?php else: ?>
                    <a href="/brgy-waste-app-v3/public/index.php?url=<?php echo urlencode('auth'); ?>" class="hidden sm:inline-flex text-xs font-medium text-slate-700 hover:text-emerald-800 transition-colors">Sign In</a>
                    <a href="/brgy-waste-app-v3/public/index.php?url=<?php echo urlencode('auth/register'); ?>" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#10B981] hover:bg-emerald-600 text-white text-xs font-semibold rounded-xl shadow-xs transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                        Register
                    </a>
                <?php endif; ?>
                <!-- Mobile Menu Toggle -->
                <button id="menuToggle" class="lg:hidden p-2 text-slate-600 hover:text-slate-900 transition-colors" aria-label="Toggle menu">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                </button>
            </div>
        </div>
    </div>
</nav>

<!-- ============================================================ -->
<!-- MOBILE MENU – Side Drawer                                    -->
<!-- ============================================================ -->
<div id="mobileMenu" class="fixed inset-0 z-[60] bg-black/40 backdrop-blur-sm hidden">
    <div class="absolute right-0 top-0 h-full w-72 bg-white shadow-2xl p-6 mobile-menu transform translate-x-full">
        <button id="menuClose" class="absolute top-4 right-4 p-2 text-slate-500 hover:text-slate-800 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
        <div class="flex flex-col space-y-3 mt-8">
            <a href="#features" class="text-sm font-medium text-slate-700 hover:text-emerald-700 py-1.5">Features</a>
            <a href="#community-map" class="text-sm font-semibold text-emerald-800 hover:text-emerald-900 py-1.5 flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                Community Map
            </a>
            <a href="#community-guide" class="text-sm font-medium text-slate-700 hover:text-emerald-700 py-1.5">Waste Guide &amp; Tips</a>
            <a href="#penalties" class="text-sm font-medium text-slate-700 hover:text-emerald-700 py-1.5">Penalties &amp; Laws</a>
            <a href="#schedule" class="text-sm font-medium text-slate-700 hover:text-emerald-700 py-1.5">Schedule &amp; Collection Notes</a>
            <a href="#announcements" class="text-sm font-medium text-slate-700 hover:text-emerald-700 py-1.5">Announcements</a>
            <a href="#faq" class="text-sm font-medium text-slate-700 hover:text-emerald-700 py-1.5">FAQs</a>
            <a href="#contact" class="text-sm font-medium text-slate-700 hover:text-emerald-700 py-1.5">Contact</a>
            
            <div class="pt-4 border-t border-slate-200 flex flex-col gap-2.5 mt-2">
                <?php if ($isLoggedIn): ?>
                    <a href="/brgy-waste-app-v3/public/index.php?url=<?php echo urlencode($role == 'resident' ? 'resident' : ($role == 'supervisor' ? 'supervisor' : 'admin')); ?>" class="w-full bg-[#0B2E22] text-white font-semibold py-2.5 text-center text-xs rounded-xl shadow-xs">Go to Dashboard</a>
                <?php else: ?>
                    <a href="/brgy-waste-app-v3/public/index.php?url=<?php echo urlencode('auth'); ?>" class="w-full text-slate-700 font-medium py-2 text-center text-xs">Sign In</a>
                    <a href="/brgy-waste-app-v3/public/index.php?url=<?php echo urlencode('auth/register'); ?>" class="w-full bg-[#10B981] text-white font-semibold py-2.5 text-center text-xs rounded-xl shadow-md">Register Free Account</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- HERO SECTION                                                 -->
<!-- ============================================================ -->
<section class="relative bg-[#07281E] text-white overflow-hidden py-20 lg:py-28 min-h-[540px] flex items-center justify-center">
    <!-- Background Carousel Container -->
    <div class="absolute inset-0 z-0">
        <!-- Background Slides -->
        <div class="absolute inset-0 hero-slide active bg-[url('../assets/images/hero/hero1.jpg')] bg-cover bg-center"></div>
        <div class="absolute inset-0 hero-slide bg-[url('../assets/images/hero/hero2.jpg')] bg-cover bg-center"></div>
        <!-- Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-br from-[#07281E]/40 via-[#07281E]/80 to-emerald-900/50 pointer-events-none"></div>
        
        <button id="heroPrev" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 p-2 rounded-full bg-white/10 backdrop-blur-md text-white hover:bg-white/20 transition-all duration-300 hover:scale-110">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6"/></svg>
        </button>
        <button id="heroNext" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 p-2 rounded-full bg-white/10 backdrop-blur-md text-white hover:bg-white/20 transition-all duration-300 hover:scale-110">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
        </button>
        
        <div id="heroDots" class="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 flex gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-white/30 cursor-pointer transition-colors" data-index="0"></span>
            <span class="w-2.5 h-2.5 rounded-full bg-white/30 cursor-pointer transition-colors" data-index="1"></span>
        </div>
    </div>

    <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center flex flex-col items-center justify-center">
        <div class="max-w-4xl mx-auto space-y-4">
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold leading-tight tracking-tight text-white">
                Tungo sa Mas Malinis at
                <span class="block text-emerald-400">Mas Maayos na Barangay.</span>
            </h1>
            
            <p class="text-xs sm:text-base text-emerald-100/90 max-w-2xl mx-auto leading-relaxed font-normal">
                I-report ang mga problema sa basura, illegal na pagtatapon, at hazardous na basura gamit ang ating Waste Management System.
            </p>
            
            <div class="flex flex-wrap items-center justify-center gap-3 pt-3">
                <a href="/brgy-waste-app-v3/public/index.php?url=<?php echo $isLoggedIn ? urlencode($role == 'resident' ? 'resident/submit' : 'auth') : 'auth/register'; ?>" class="inline-flex items-center gap-2 px-6 py-3 bg-[#10B981] hover:bg-emerald-500 text-white font-semibold text-xs sm:text-sm rounded-xl shadow-md transition active:scale-[0.98]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                    <span>Report Waste (Resident)</span>
                </a>
                <a href="/brgy-waste-app-v3/public/index.php?url=guest" class="inline-flex items-center gap-2 px-5 py-3 bg-white/10 hover:bg-white/20 border border-white/20 text-white font-semibold text-xs sm:text-sm rounded-xl backdrop-blur-xs transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    <span>Report as Guest</span>
                </a>
                <a href="/brgy-waste-app-v3/public/index.php?url=guest/track" class="inline-flex items-center gap-2 px-4 py-3 text-emerald-200 hover:text-white font-medium text-xs sm:text-sm transition">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    <span>Track Status</span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- SMART REPORTING / FEATURES SECTION                          -->
<!-- ============================================================ -->
<section id="features" class="py-16 sm:py-20 bg-white relative z-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Section Header -->
        <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16 space-y-2">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-slate-900 tracking-tight">
                A Smarter Way for Residents to Help
            </h2>
            <p class="text-xs sm:text-sm md:text-base text-slate-500 max-w-2xl mx-auto leading-relaxed font-normal">
                Empowering every household in Barangay <?php echo htmlspecialchars($barangayName); ?> with rapid waste logging, automated duplicate detection, and transparent resolution tracking.
            </p>
        </div>

        <!-- 4-Card Modern Feature Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 lg:gap-6">
            
            <!-- Feature 01: GPS Pinning & Map -->
            <div class="group relative bg-white rounded-3xl border border-slate-200/90 p-6 shadow-xs hover:shadow-xl hover:border-emerald-400 hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 to-teal-400 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                
                <div class="space-y-4">
                    <!-- Card Top Header -->
                    <div class="flex items-center justify-between">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center border border-emerald-100 group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300 shadow-2xs">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>
                            </svg>
                        </div>
                        <span class="font-mono text-xs font-bold text-slate-400 group-hover:text-emerald-700 transition-colors">01</span>
                    </div>

                    <!-- Category & Title -->
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 block mb-1">Geolocation</span>
                        <h3 class="text-base font-bold text-slate-900 tracking-tight group-hover:text-emerald-800 transition-colors">GPS Pinning &amp; Map</h3>
                    </div>

                    <!-- Description -->
                    <p class="text-xs sm:text-[13px] text-slate-500 font-normal leading-relaxed">
                        Automatically captures precise device GPS coordinates or allows interactive manual pinpointing for clear municipal collection crew dispatch.
                    </p>
                </div>

                <!-- Footer Feature Tag -->
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-slate-400 font-medium">Precision Target</span>
                    <span class="inline-flex items-center gap-1 font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-100">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        Sub-meter GPS
                    </span>
                </div>
            </div>

            <!-- Feature 02: Photo Proof & Forensics -->
            <div class="group relative bg-white rounded-3xl border border-slate-200/90 p-6 shadow-xs hover:shadow-xl hover:border-blue-400 hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 to-cyan-400 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                <div class="space-y-4">
                    <!-- Card Top Header -->
                    <div class="flex items-center justify-between">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-700 flex items-center justify-center border border-blue-100 group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300 shadow-2xs">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/>
                            </svg>
                        </div>
                        <span class="font-mono text-xs font-bold text-slate-400 group-hover:text-blue-700 transition-colors">02</span>
                    </div>

                    <!-- Category & Title -->
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-blue-700 block mb-1">Visual Evidence</span>
                        <h3 class="text-base font-bold text-slate-900 tracking-tight group-hover:text-blue-800 transition-colors">Photo Proof &amp; Forensic</h3>
                    </div>

                    <!-- Description -->
                    <p class="text-xs sm:text-[13px] text-slate-500 font-normal leading-relaxed">
                        Attach high-resolution camera photos so officers instantly inspect waste conditions, hazard level, and allocate the correct collection truck type.
                    </p>
                </div>

                <!-- Footer Feature Tag -->
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-slate-400 font-medium">Visual Proof</span>
                    <span class="inline-flex items-center gap-1 font-semibold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-md border border-blue-100">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                        Live Preview
                    </span>
                </div>
            </div>

            <!-- Feature 03: 50m Proximity Scanner -->
            <div class="group relative bg-white rounded-3xl border border-slate-200/90 p-6 shadow-xs hover:shadow-xl hover:border-purple-400 hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-purple-500 to-indigo-400 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                <div class="space-y-4">
                    <!-- Card Top Header -->
                    <div class="flex items-center justify-between">
                        <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-700 flex items-center justify-center border border-purple-100 group-hover:scale-110 group-hover:bg-purple-600 group-hover:text-white transition-all duration-300 shadow-2xs">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/>
                            </svg>
                        </div>
                        <span class="font-mono text-xs font-bold text-slate-400 group-hover:text-purple-700 transition-colors">03</span>
                    </div>

                    <!-- Category & Title -->
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-purple-700 block mb-1">Queue Optimization</span>
                        <h3 class="text-base font-bold text-slate-900 tracking-tight group-hover:text-purple-800 transition-colors">50m Duplicate Scanner</h3>
                    </div>

                    <!-- Description -->
                    <p class="text-xs sm:text-[13px] text-slate-500 font-normal leading-relaxed">
                        Smart radius detection flags active reports in real time, preventing repetitive tickets while letting neighbors add upvotes and endorsements.
                    </p>
                </div>

                <!-- Footer Feature Tag -->
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-slate-400 font-medium">Smart Scanner</span>
                    <span class="inline-flex items-center gap-1 font-semibold text-purple-700 bg-purple-50 px-2 py-0.5 rounded-md border border-purple-100">
                        <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                        50m Auto-Check
                    </span>
                </div>
            </div>

            <!-- Feature 04: Live Status Stepper -->
            <div class="group relative bg-white rounded-3xl border border-slate-200/90 p-6 shadow-xs hover:shadow-xl hover:border-amber-400 hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-500 to-orange-400 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                <div class="space-y-4">
                    <!-- Card Top Header -->
                    <div class="flex items-center justify-between">
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-700 flex items-center justify-center border border-amber-100 group-hover:scale-110 group-hover:bg-amber-600 group-hover:text-white transition-all duration-300 shadow-2xs">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                            </svg>
                        </div>
                        <span class="font-mono text-xs font-bold text-slate-400 group-hover:text-amber-700 transition-colors">04</span>
                    </div>

                    <!-- Category & Title -->
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-amber-700 block mb-1">Accountability</span>
                        <h3 class="text-base font-bold text-slate-900 tracking-tight group-hover:text-amber-800 transition-colors">Live Status Stepper</h3>
                    </div>

                    <!-- Description -->
                    <p class="text-xs sm:text-[13px] text-slate-500 font-normal leading-relaxed">
                        Track progress transparently with public real-time logs from Submission &rarr; Officer Verification &rarr; Crew Dispatch &rarr; Full Resolution.
                    </p>
                </div>

                <!-- Footer Feature Tag -->
                <div class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-between text-xs">
                    <span class="text-slate-400 font-medium">Tracking Status</span>
                    <span class="inline-flex items-center gap-1 font-semibold text-amber-800 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-100">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                        Real-time Feed
                    </span>
                </div>
            </div>

        </div>

    </div>
</section>

<!-- ============================================================ -->
<!-- INTERACTIVE COMMUNITY MAP SECTION                           -->
<!-- ============================================================ -->
<section id="community-map" class="py-16 sm:py-20 bg-slate-50 border-t border-slate-200 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Section Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div class="space-y-2 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-semibold">
                    <span class="w-2 h-2 rounded-full bg-emerald-600 animate-pulse"></span>
                    <span>Live Geospatial Transparency</span>
                </div>
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-slate-900 tracking-tight">
                    Barangay Waste &amp; Sanitation Map
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 font-normal leading-relaxed">
                    Interactive public overview of reported waste locations, ongoing municipal cleanups, and official Purok boundaries in Barangay <?php echo htmlspecialchars($barangayName); ?>.
                </p>
            </div>

            <!-- Quick Action Links -->
            <div class="flex items-center gap-2.5 flex-shrink-0">
                <a href="/brgy-waste-app-v3/public/index.php?url=guest" class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#0B2E22] hover:bg-[#07281E] text-white text-xs font-semibold rounded-xl shadow-xs transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                    <span>Pin New Report</span>
                </a>
                <a href="/brgy-waste-app-v3/public/index.php?url=guest/track" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white hover:bg-slate-100 text-slate-700 text-xs font-semibold rounded-xl border border-slate-200 shadow-2xs transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    <span>Track ID</span>
                </a>
            </div>
        </div>

        <!-- Filter & Control Bar -->
        <div class="bg-white rounded-2xl border border-slate-200 p-3 sm:p-4 shadow-2xs flex flex-wrap items-center justify-between gap-3">
            
            <!-- Filter Pills -->
            <div class="flex flex-wrap items-center gap-1.5 text-xs font-medium">
                <span class="text-slate-400 font-semibold text-[11px] uppercase tracking-wider mr-1 hidden sm:inline">Filter:</span>
                <button type="button" onclick="filterLandingMap('all')" id="filter-btn-all" class="landing-map-filter-btn px-3 py-1.5 rounded-xl bg-slate-900 text-white font-semibold shadow-2xs transition cursor-pointer">
                    All Reports (<?php echo count($reportPoints); ?>)
                </button>
                <button type="button" onclick="filterLandingMap('active')" id="filter-btn-active" class="landing-map-filter-btn px-3 py-1.5 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 transition cursor-pointer">
                    Active / Dispatched (<?php echo $mapStats['pending'] + $mapStats['verified'] + $mapStats['in_progress']; ?>)
                </button>
                <button type="button" onclick="filterLandingMap('resolved')" id="filter-btn-resolved" class="landing-map-filter-btn px-3 py-1.5 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 transition cursor-pointer">
                    Resolved (<?php echo $mapStats['resolved']; ?>)
                </button>
            </div>

            <!-- Layer & Polygon Controls -->
            <div class="flex items-center gap-2">
                <button type="button" id="togglePurokBtn" onclick="toggleLandingPuroks()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 text-emerald-800 border border-emerald-200 text-xs font-semibold transition cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
                    <span>Purok Boundaries</span>
                </button>
                <button type="button" id="toggleMapTypeBtn" onclick="toggleLandingTileLayer()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 text-slate-700 hover:bg-slate-200 text-xs font-semibold transition cursor-pointer border border-slate-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>
                    <span id="mapTypeLabel">Satellite View</span>
                </button>
            </div>
        </div>

        <!-- Map Canvas Card -->
        <div class="relative bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-xs">
            <div id="landingMap" class="w-full h-[500px] sm:h-[580px] z-10"></div>

            <!-- Floating Map Legend -->
            <div class="absolute bottom-4 left-4 z-20 bg-white/95 backdrop-blur-md rounded-2xl border border-slate-200/90 p-3 shadow-md hidden sm:block max-w-xs">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block mb-2">Live Map Legend</span>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-500 shadow-xs"></span>
                        <span class="text-slate-600 font-medium">Pending / Open</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-blue-500 shadow-xs"></span>
                        <span class="text-slate-600 font-medium">Verified</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-purple-500 shadow-xs"></span>
                        <span class="text-slate-600 font-medium">Dispatched</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-xs"></span>
                        <span class="text-slate-600 font-medium">Resolved</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Real-Time Metrics Strip beneath Map -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            
            <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-2xs">
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">Total Mapped</span>
                <div class="text-2xl font-bold text-slate-900 mt-1"><?php echo count($reportPoints); ?></div>
                <span class="text-xs text-slate-500 font-normal">Geo-tagged submissions</span>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-2xs">
                <span class="text-[11px] font-semibold text-amber-800 uppercase tracking-wider block">Active Cleanups</span>
                <div class="text-2xl font-bold text-amber-900 mt-1"><?php echo $mapStats['pending'] + $mapStats['verified'] + $mapStats['in_progress']; ?></div>
                <span class="text-xs text-slate-500 font-normal">Ongoing operations</span>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-2xs">
                <span class="text-[11px] font-semibold text-emerald-800 uppercase tracking-wider block">Resolved Sites</span>
                <div class="text-2xl font-bold text-emerald-900 mt-1"><?php echo $mapStats['resolved']; ?></div>
                <span class="text-xs text-slate-500 font-normal">Successfully cleared</span>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-2xs">
                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider block">Jurisdiction</span>
                <div class="text-2xl font-bold text-slate-900 mt-1"><?php echo count($mapConfig['puroks'] ?? []); ?> Puroks</div>
                <span class="text-xs text-slate-500 font-normal">Full community coverage</span>
            </div>

        </div>

    </div>
</section>

<!-- ============================================================ -->
<!-- 1. COMMUNITY GUIDE & WASTE MANAGEMENT TIPS                   -->
<!-- ============================================================ -->
<section id="community-guide" class="py-14 bg-slate-100/70 border-y border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        
        <div class="text-center max-w-3xl mx-auto space-y-1.5">
            <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Community Waste Management Guide</h2>
            <p class="text-xs sm:text-sm text-slate-500">Practical segregation standards and ecological waste management practices for all households in Barangay <?php echo htmlspecialchars($barangayName); ?>.</p>
        </div>

        <!-- 4-Category Segregation Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            
            <!-- Category 1: Biodegradable -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-2xs flex flex-col justify-between space-y-4">
                <div>
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <span class="px-2 py-0.5 rounded text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-100">
                            🟢 Biodegradable
                        </span>
                        <span class="text-[11px] text-slate-400">Nabubulok</span>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900">Organic &amp; Kitchen Waste</h3>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">Organic materials that naturally decompose into nutrient-rich soil compost.</p>
                    
                    <div class="mt-3 pt-3 border-t border-slate-100 space-y-1.5 text-xs text-slate-600">
                        <p class="font-semibold text-emerald-800">Accepted Items:</p>
                        <ul class="space-y-1 text-[11px] text-slate-500 list-disc list-inside">
                            <li>Leftover food &amp; fruit peels</li>
                            <li>Vegetable scraps &amp; eggshells</li>
                            <li>Garden trimmings, leaves &amp; grass</li>
                            <li>Coffee grounds &amp; tea bags</li>
                        </ul>
                    </div>
                </div>
                <div class="bg-emerald-50/70 rounded-xl p-2.5 text-[11px] text-emerald-900">
                    💡 <em>Tip:</em> Keep in a covered bin to prevent flies; ideal for backyard composting.
                </div>
            </div>

            <!-- Category 2: Recyclable -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-2xs flex flex-col justify-between space-y-4">
                <div>
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <span class="px-2 py-0.5 rounded text-xs font-semibold bg-blue-50 text-blue-800 border border-blue-100">
                            🔵 Recyclable
                        </span>
                        <span class="text-[11px] text-slate-400">Pang-Recycle</span>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900">Dry &amp; Clean Reusables</h3>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">Factory-processed materials that can be remanufactured at recycling facilities.</p>
                    
                    <div class="mt-3 pt-3 border-t border-slate-100 space-y-1.5 text-xs text-slate-600">
                        <p class="font-semibold text-blue-800">Accepted Items:</p>
                        <ul class="space-y-1 text-[11px] text-slate-500 list-disc list-inside">
                            <li>Plastic bottles (PET), caps &amp; containers</li>
                            <li>Cardboard boxes &amp; clean paper/cartons</li>
                            <li>Aluminum cans &amp; tin containers</li>
                            <li>Glass bottles &amp; beverage jars</li>
                        </ul>
                    </div>
                </div>
                <div class="bg-blue-50/70 rounded-xl p-2.5 text-[11px] text-blue-900">
                    💡 <em>Tip:</em> Rinse and dry beverage bottles before placing in recycling bins.
                </div>
            </div>

            <!-- Category 3: Residual -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-2xs flex flex-col justify-between space-y-4">
                <div>
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <span class="px-2 py-0.5 rounded text-xs font-semibold bg-amber-50 text-amber-900 border border-amber-100">
                            🟡 Residual Waste
                        </span>
                        <span class="text-[11px] text-slate-400">Di-Nabubulok</span>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900">Non-Recyclable Trash</h3>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">Waste that cannot be composted or recycled, destined for sanitary landfills.</p>
                    
                    <div class="mt-3 pt-3 border-t border-slate-100 space-y-1.5 text-xs text-slate-600">
                        <p class="font-semibold text-amber-900">Accepted Items:</p>
                        <ul class="space-y-1 text-[11px] text-slate-500 list-disc list-inside">
                            <li>Sanitary napkins &amp; disposable diapers</li>
                            <li>Single-use sachet wrappers &amp; chips foil</li>
                            <li>Worn-out rags, textiles &amp; ceramics</li>
                            <li>Soiled plastic packaging &amp; styrofoam</li>
                        </ul>
                    </div>
                </div>
                <div class="bg-amber-50/70 rounded-xl p-2.5 text-[11px] text-amber-950">
                    💡 <em>Tip:</em> Pack tightly in durable, tied trash bags to avoid spillage.
                </div>
            </div>

            <!-- Category 4: Special & Hazardous -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-2xs flex flex-col justify-between space-y-4">
                <div>
                    <div class="flex items-center justify-between gap-2 mb-3">
                        <span class="px-2 py-0.5 rounded text-xs font-semibold bg-purple-50 text-purple-900 border border-purple-100">
                            🟣 Special / Toxic
                        </span>
                        <span class="text-[11px] text-slate-400">Mapanganib</span>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900">Hazardous &amp; E-Waste</h3>
                    <p class="text-xs text-slate-500 mt-1 leading-relaxed">Items containing toxic, corrosive, or flammable components requiring special handling.</p>
                    
                    <div class="mt-3 pt-3 border-t border-slate-100 space-y-1.5 text-xs text-slate-600">
                        <p class="font-semibold text-purple-900">Accepted Items:</p>
                        <ul class="space-y-1 text-[11px] text-slate-500 list-disc list-inside">
                            <li>Dry cell batteries &amp; car batteries</li>
                            <li>Fluorescent tubes, CFLs &amp; bulbs</li>
                            <li>Expired pharmaceuticals &amp; paint cans</li>
                            <li>Electronics, cords &amp; phone batteries</li>
                        </ul>
                    </div>
                </div>
                <div class="bg-purple-50/70 rounded-xl p-2.5 text-[11px] text-purple-950">
                    ⚠️ <em>Caution:</em> Never mix with regular trash; wrap broken glass and bring to MRF.
                </div>
            </div>

        </div>

        <!-- 4 Practical Community Tips Cards -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-7 shadow-2xs">
            <h3 class="text-base font-bold text-slate-900 mb-5">
                💡 Best Practices for Barangay <?php echo htmlspecialchars($barangayName); ?> Households
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                <div class="flex items-start gap-3">
                    <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-800 font-bold text-xs flex items-center justify-center shrink-0">1</span>
                    <div>
                        <h4 class="text-xs sm:text-sm font-semibold text-slate-800">Practice 3R's at Home</h4>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Reduce single-use plastic, bring eco-bags to the market (<em>talipapa</em>), and reuse durable glass jars.</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-800 font-bold text-xs flex items-center justify-center shrink-0">2</span>
                    <div>
                        <h4 class="text-xs sm:text-sm font-semibold text-slate-800">Proper Bin Placement</h4>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Place segregated bins curbside by 6:00 AM on your designated collection day. Do not leave out overnight.</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-800 font-bold text-xs flex items-center justify-center shrink-0">3</span>
                    <div>
                        <h4 class="text-xs sm:text-sm font-semibold text-slate-800">Wrap Sharp Objects</h4>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Wrap broken glass, needles, and tin lids in cardboard or newspaper before bagging to protect waste collectors.</p>
                    </div>
                </div>

                <div class="flex items-start gap-3">
                    <span class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-800 font-bold text-xs flex items-center justify-center shrink-0">4</span>
                    <div>
                        <h4 class="text-xs sm:text-sm font-semibold text-slate-800">Report Uncollected Piles</h4>
                        <p class="text-xs text-slate-500 mt-1 leading-relaxed">Use this app to snap and report any missed trash or illegal dumps in your purok for rapid dispatch.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- ============================================================ -->
<!-- 2. PROHIBITED ACTIONS AND PENALTIES                          -->
<!-- ============================================================ -->
<section id="penalties" class="py-14 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
        
        <div class="text-center max-w-3xl mx-auto space-y-1.5">
            <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Prohibited Actions &amp; Penalties</h2>
            <p class="text-xs sm:text-sm text-slate-500">In strict compliance with <strong>Republic Act No. 9003</strong> (Ecological Solid Waste Management Act of 2000) and Municipal / Barangay Ordinances.</p>
        </div>

        <!-- Law Summary Alert -->
        <div class="bg-red-50/60 rounded-2xl border border-red-200 p-5 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="flex items-start gap-3">
                <div class="w-9 h-9 rounded-xl bg-red-600 text-white flex items-center justify-center font-bold text-base shrink-0 shadow-xs">
                    ⚖️
                </div>
                <div>
                    <h3 class="text-sm font-bold text-red-950">Strict Enforcement Policy</h3>
                    <p class="text-xs text-red-900/80 mt-0.5 leading-relaxed">
                        Barangay Tanods and Municipal Environmental Officers are authorized to issue citation tickets to violators. CCTV cameras monitor key streets and waterways 24/7.
                    </p>
                </div>
            </div>
            <span class="px-2.5 py-1 rounded-full bg-red-100 text-red-800 font-semibold text-[10px] uppercase tracking-wider shrink-0">
                Zero Tolerance
            </span>
        </div>

        <!-- Prohibitions Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

            <?php if (!empty($penaltyRules)): ?>
                <?php foreach ($penaltyRules as $rule): ?>
                <div class="bg-slate-50 rounded-2xl border border-slate-200 p-5 flex flex-col justify-between space-y-4 hover:border-red-300 transition">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-semibold uppercase tracking-wider text-red-600">
                                Offense <?php echo str_pad((int)$rule['offense_no'], 2, '0', STR_PAD_LEFT); ?>
                            </span>
                            <?php if (!empty($rule['legal_ref'])): ?>
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-red-100 text-red-800">
                                    <?php echo htmlspecialchars($rule['legal_ref']); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <h4 class="text-sm font-bold text-slate-900"><?php echo htmlspecialchars($rule['title']); ?></h4>
                        <?php if (!empty($rule['description'])): ?>
                            <p class="text-xs text-slate-500 mt-1 leading-relaxed"><?php echo htmlspecialchars($rule['description']); ?></p>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($rule['fine_range']) || !empty($rule['alt_penalty'])): ?>
                    <div class="pt-3 border-t border-slate-200 space-y-1 text-xs">
                        <?php if (!empty($rule['fine_range'])): ?>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500">Fine:</span>
                            <span class="font-semibold text-red-700"><?php echo htmlspecialchars($rule['fine_range']); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($rule['alt_penalty'])): ?>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500">Alternative:</span>
                            <span class="text-slate-700"><?php echo htmlspecialchars($rule['alt_penalty']); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>

            <?php else: ?>
                <div class="col-span-full text-center py-12 bg-slate-50 rounded-2xl border border-slate-200">
                    <div class="w-12 h-12 rounded-2xl bg-red-50 border-2 border-red-100 flex items-center justify-center mx-auto mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    <p class="text-sm font-bold text-slate-600">No penalty rules published yet.</p>
                    <p class="text-xs text-slate-400 mt-1">Administrators can add rules from Settings → Rules &amp; Penalties.</p>
                </div>
            <?php endif; ?>

        </div>

    </div>
</section>

<!-- ============================================================ -->
<!-- 3. GARBAGE COLLECTION SCHEDULE & IMPORTANT NOTES             -->
<!-- ============================================================ -->
<section id="schedule" class="py-14 bg-slate-100/70 border-t border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Garbage Collection Schedule</h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Official route schedule for all Puroks in Barangay <?php echo htmlspecialchars($barangayName); ?>.</p>
            </div>
            <a href="/brgy-waste-app-v3/public/index.php?url=resident/collection_schedule" class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-700 hover:text-emerald-900 transition">
                <span>View Full Interactive Calendar</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
            </a>
        </div>

        <!-- Schedule Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <?php if (!empty($schedules)): ?>
                <?php foreach ($schedules as $schedule):
                    $day = $schedule['collection_day'];
                    $wasteType = $schedule['waste_type'] ?? 'General';
                    $start = date('g:i A', strtotime($schedule['start_time']));
                    $end = date('g:i A', strtotime($schedule['end_time']));
                    $puroks = $schedule['puroks'] ?? 'All Puroks';

                    // Dynamic theme configuration per waste type
                    $wtLower = strtolower(trim($wasteType));
                    if (strpos($wtLower, 'bio') !== false && strpos($wtLower, 'non') === false) {
                        $topGrad = 'from-emerald-500 to-teal-400';
                        $badgeStyle = 'bg-emerald-50 text-emerald-800 border-emerald-200/80';
                        $dotColor = 'bg-emerald-500';
                    } elseif (strpos($wtLower, 'non-bio') !== false || strpos($wtLower, 'recyc') !== false) {
                        $topGrad = 'from-blue-500 to-cyan-400';
                        $badgeStyle = 'bg-blue-50 text-blue-800 border-blue-200/80';
                        $dotColor = 'bg-blue-500';
                    } elseif (strpos($wtLower, 'resid') !== false) {
                        $topGrad = 'from-amber-500 to-orange-400';
                        $badgeStyle = 'bg-amber-50 text-amber-900 border-amber-200/80';
                        $dotColor = 'bg-amber-500';
                    } elseif (strpos($wtLower, 'hazard') !== false || strpos($wtLower, 'special') !== false || strpos($wtLower, 'toxic') !== false) {
                        $topGrad = 'from-purple-500 to-pink-400';
                        $badgeStyle = 'bg-purple-50 text-purple-900 border-purple-200/80';
                        $dotColor = 'bg-purple-500';
                    } else {
                        $topGrad = 'from-slate-600 to-slate-400';
                        $badgeStyle = 'bg-slate-100 text-slate-700 border-slate-200';
                        $dotColor = 'bg-slate-500';
                    }
                ?>
                <div class="group relative bg-white rounded-3xl border border-slate-200/90 p-5 sm:p-6 shadow-xs hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between overflow-hidden">
                    
                    <!-- Top Gradient Accent Line -->
                    <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r <?php echo $topGrad; ?> opacity-80 group-hover:opacity-100 transition-opacity"></div>

                    <div class="space-y-4">
                        <!-- Top Row: Day + Waste Type Tag -->
                        <div class="flex items-start justify-between gap-2">
                            <div class="flex items-center gap-2.5">
                                <div class="w-10 h-10 rounded-2xl bg-slate-50 border border-slate-200/80 flex flex-col items-center justify-center shadow-2xs group-hover:bg-slate-100 transition-colors shrink-0">
                                    <span class="text-[9px] font-bold uppercase tracking-wider text-slate-400 leading-none"><?php echo strtoupper(substr($day, 0, 3)); ?></span>
                                    <svg class="w-3.5 h-3.5 text-slate-600 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                        <line x1="16" y1="2" x2="16" y2="6"/>
                                        <line x1="8" y1="2" x2="8" y2="6"/>
                                        <line x1="3" y1="10" x2="21" y2="10"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-900 tracking-tight leading-tight"><?php echo htmlspecialchars($day); ?></h3>
                                    <span class="text-[11px] text-slate-400 font-medium">Weekly Route</span>
                                </div>
                            </div>

                            <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[10px] sm:text-[11px] font-semibold <?php echo $badgeStyle; ?> border shrink-0">
                                <span class="w-1.5 h-1.5 rounded-full <?php echo $dotColor; ?>"></span>
                                <?php echo htmlspecialchars($wasteType); ?>
                            </span>
                        </div>

                        <!-- Pickup Window Pill -->
                        <div class="bg-slate-50/80 rounded-2xl p-3 border border-slate-100 flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2 min-w-0">
                                <div class="w-7 h-7 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0 border border-emerald-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"/>
                                        <polyline points="12 6 12 12 16 14"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400 block leading-none mb-0.5">Pickup Time</span>
                                    <span class="text-xs font-bold font-mono text-slate-900 truncate block"><?php echo $start; ?> – <?php echo $end; ?></span>
                                </div>
                            </div>
                            <span class="text-[10px] font-bold text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-100 shrink-0">Active</span>
                        </div>

                        <!-- Designated Puroks -->
                        <div class="space-y-1.5 pt-0.5">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-rose-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path d="M12 21s-6-5.333-6-10a6 6 0 0 1 12 0c0 4.667-6 10-6 10z"/>
                                    <circle cx="12" cy="11" r="2"/>
                                </svg>
                                Coverage Areas
                            </span>
                            <p class="text-xs font-medium text-slate-700 leading-snug">
                                <?php echo htmlspecialchars($puroks); ?>
                            </p>
                        </div>

                        <!-- Special Notes Banner (if any) -->
                        <?php if (!empty($schedule['special_notes'])): ?>
                            <div class="bg-amber-50/90 border border-amber-200/70 rounded-2xl p-2.5 flex items-start gap-2 text-xs text-amber-900 leading-relaxed">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-amber-600 mt-0.5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                                </svg>
                                <span class="text-[11px] font-medium"><?php echo htmlspecialchars($schedule['special_notes']); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Footer Route Indicator -->
                    <div class="mt-4 pt-3.5 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-400">
                        <span class="font-medium">Scheduled Route</span>
                        <span class="text-emerald-800 font-semibold flex items-center gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Regular Service
                        </span>
                    </div>

                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-span-full text-center text-slate-400 py-10 bg-white rounded-3xl border border-slate-200 text-xs shadow-xs">Schedule not published yet — check back soon.</div>
            <?php endif; ?>
        </div>

        <!-- CRITICAL NOTES ON GARBAGE COLLECTION -->
        <div class="bg-[#0B2E22] rounded-2xl p-5 sm:p-7 text-white shadow-sm space-y-5">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-emerald-500/20 text-emerald-300 flex items-center justify-center font-bold text-base border border-emerald-500/30">
                    📋
                </div>
                <div>
                    <h3 class="text-base sm:text-lg font-bold text-white">Important Notes on Garbage Collection</h3>
                    <p class="text-xs text-emerald-200/80 mt-0.5">Please review these mandatory guidelines before putting out your household waste</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 pt-1 border-t border-emerald-900/80 text-xs leading-relaxed">
                <?php if (!empty($collectionNotes)): ?>
                    <?php foreach ($collectionNotes as $idx => $note): ?>
                        <div class="space-y-1 bg-white/5 p-3.5 rounded-xl border border-white/5">
                            <p class="font-semibold text-emerald-300 text-xs sm:text-sm">
                                <?php echo ($idx + 1) . '. ' . htmlspecialchars($note['title']); ?>
                            </p>
                            <p class="text-emerald-100/70"><?php echo htmlspecialchars($note['content']); ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-full flex flex-col items-center justify-center py-8 text-center space-y-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-emerald-600/40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        <p class="text-xs text-emerald-300/60 font-semibold">No collection notes published yet.<br>Add them from Settings → Collection Notes.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</section>

<!-- ============================================================ -->
<!-- ANNOUNCEMENTS SECTION                                       -->
<!-- ============================================================ -->
<section id="announcements" class="py-14 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        <div>
            <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Latest Bulletins &amp; Advisories</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Official announcements regarding community cleanups, schedule changes, and notices.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Left: List -->
            <div class="lg:col-span-5 space-y-2.5">
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
                    <div class="flex items-center justify-between p-3.5 rounded-xl border border-slate-200 hover:border-emerald-300 hover:bg-emerald-50/40 transition cursor-pointer group">
                        <div class="flex items-center gap-3 min-w-0">
                            <span class="w-2 h-2 rounded-full <?php echo $dots[$type] ?? 'bg-slate-400'; ?> flex-shrink-0"></span>
                            <div>
                                <p class="text-xs font-semibold text-slate-800 truncate"><?php echo htmlspecialchars($item['title']); ?></p>
                                <p class="text-[11px] text-slate-400 mt-0.5"><?php echo date('M j, Y', strtotime($item['created_at'])); ?></p>
                            </div>
                        </div>
                        <span class="inline-flex rounded px-2 py-0.5 text-[10px] font-medium <?php echo $types[$type] ?? 'bg-slate-100 text-slate-700'; ?> flex-shrink-0 ml-2"><?php echo $type; ?></span>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="p-8 text-center text-slate-400 bg-slate-50 rounded-2xl border border-slate-200 text-xs">No announcements yet — check back soon.</div>
                <?php endif; ?>
            </div>

            <!-- Right: Featured Card -->
            <div class="lg:col-span-7">
                <?php
                $featured = !empty($announcements) ? $announcements[0] : null;
                if ($featured):
                    $fTitle = $featured['title'] ?? 'Special collection today';
                    $fContent = $featured['content'] ?? 'Stay updated with barangay announcements.';
                    $fDate = date('M j, Y', strtotime($featured['created_at'] ?? 'now'));
                ?>
                <div class="bg-[#07281E] rounded-2xl p-6 sm:p-7 text-white shadow-sm h-full flex flex-col justify-between space-y-5">
                    <div>
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 bg-red-500/20 text-red-300 text-[10px] font-semibold rounded-full border border-red-500/30">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-400 pulse-dot"></span>
                                Featured Bulletin
                            </span>
                            <span class="text-xs text-emerald-200/70"><?php echo $fDate; ?></span>
                        </div>
                        <h3 class="text-lg sm:text-xl font-bold mt-3 leading-snug"><?php echo htmlspecialchars($fTitle); ?></h3>
                        <p class="text-emerald-100/80 text-xs sm:text-sm mt-2 leading-relaxed"><?php echo nl2br(htmlspecialchars(mb_substr($fContent, 0, 200) . (strlen($fContent) > 200 ? '...' : ''))); ?></p>
                    </div>

                    <div class="grid grid-cols-3 gap-4 pt-4 border-t border-white/10 text-center sm:text-left">
                        <div>
                            <p class="text-xl font-bold text-white"><?php echo count($announcements); ?></p>
                            <p class="text-[10px] text-emerald-200/60 font-medium uppercase tracking-wider mt-0.5">Active Notices</p>
                        </div>
                        <div>
                            <p class="text-xl font-bold text-red-400"><?php echo count(array_filter($announcements, fn($a) => stripos($a['title'] ?? '', 'collection') !== false || stripos($a['content'] ?? '', 'collection') !== false)); ?></p>
                            <p class="text-[10px] text-emerald-200/60 font-medium uppercase tracking-wider mt-0.5">Urgent</p>
                        </div>
                        <div>
                            <p class="text-xl font-bold text-emerald-400"><?php echo count(array_filter($announcements, fn($a) => stripos($a['title'] ?? '', 'clean') !== false || stripos($a['title'] ?? '', 'drive') !== false)); ?></p>
                            <p class="text-[10px] text-emerald-200/60 font-medium uppercase tracking-wider mt-0.5">Drives</p>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="bg-[#07281E] rounded-2xl p-6 text-white flex flex-col items-center justify-center text-center min-h-[220px] gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-200/40"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                    <p class="text-emerald-200/60 text-xs">All quiet — no pinned announcements right now.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- HOW IT WORKS – 3 Steps                                      -->
<!-- ============================================================ -->
<section class="py-14 bg-slate-50 border-t border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Three Steps to Report Waste</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="bg-white rounded-2xl p-6 border border-slate-200 hover-lift text-center relative shadow-2xs">
                <span class="inline-flex items-center justify-center w-9 h-9 bg-[#10B981] text-white font-bold text-xs rounded-xl mb-3 shadow-sm">01</span>
                <h4 class="font-bold text-slate-900 text-sm">Register &amp; Log In</h4>
                <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">Create a free resident account using your email or mobile number to track all your barangay incident reports in one place.</p>
                <a href="/brgy-waste-app-v3/public/index.php?url=auth/register" class="inline-flex items-center gap-1 text-xs font-semibold text-[#10B981] mt-3 hover:underline">Get started →</a>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 hover-lift text-center relative shadow-2xs">
                <span class="inline-flex items-center justify-center w-9 h-9 bg-[#10B981] text-white font-bold text-xs rounded-xl mb-3 shadow-sm">02</span>
                <h4 class="font-bold text-slate-900 text-sm">Snap &amp; Pin Report</h4>
                <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">Take a photo of the waste issue, describe it briefly, and pin the exact location on the interactive map.</p>
                <?php if ($isLoggedIn && $role == 'resident'): ?>
                    <a href="/brgy-waste-app-v3/public/index.php?url=resident/submit" class="inline-flex items-center gap-1 text-xs font-semibold text-[#10B981] mt-3 hover:underline">Submit now →</a>
                <?php endif; ?>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-200 hover-lift text-center relative shadow-2xs">
                <span class="inline-flex items-center justify-center w-9 h-9 bg-[#10B981] text-white font-bold text-xs rounded-xl mb-3 shadow-sm">03</span>
                <h4 class="font-bold text-slate-900 text-sm">Track Resolution</h4>
                <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">Receive real-time progress updates as barangay officers inspect, dispatch collection crews, and resolve the report.</p>
                <?php if ($isLoggedIn && $role == 'resident'): ?>
                    <a href="/brgy-waste-app-v3/public/index.php?url=resident/my_report" class="inline-flex items-center gap-1 text-xs font-semibold text-[#10B981] mt-3 hover:underline">View my reports →</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- FAQ SECTION – Clean Accordions                              -->
<!-- ============================================================ -->
<section id="faq" class="py-14 bg-white border-t border-slate-200">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-8">
            <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">Frequently Asked Questions</h2>
            <p class="text-slate-500 mt-1 text-xs sm:text-sm">Common questions about waste reporting and barangay collection operations.</p>
        </div>

        <div class="space-y-2.5">
            <?php
            $faqs = [
                ['q' => 'How do I create a resident account?', 'a' => 'Click the "Register" button on the top right, fill in your name, email or phone number, and create a password. You will receive an OTP code to verify your resident identity.'],
                ['q' => 'How do I submit a waste report?', 'a' => 'After logging in, click "Report Waste", upload a photo of the incident, select the category/volume, and pin the location on the map.'],
                ['q' => 'What types of waste can be reported?', 'a' => 'You can report illegal dumping, uncollected roadside trash, overflowing bins, hazardous/chemical waste, construction debris, and blocked drainage canals.'],
                ['q' => 'Why was my report flagged for duplicate check?', 'a' => 'If another resident has already logged an incident within 50 meters of your coordinates, the system lets you "Support" the existing report to boost dispatch priority.'],
                ['q' => 'Can I report as a guest without creating an account?', 'a' => 'Yes! Click "Report as Guest" on the homepage. You will receive a unique tracking reference code to check your report status anytime.'],
                ['q' => 'How are waste hotspots generated on the map?', 'a' => 'Hotspots are automatically plotted by clustering verified reports geographically, guiding municipal dump trucks to priority zones.']
            ];
            ?>
            <?php foreach ($faqs as $index => $faq): ?>
            <div class="bg-slate-50 rounded-2xl border border-slate-200 overflow-hidden shadow-2xs hover:border-emerald-300 transition">
                <button class="faq-question w-full flex items-center justify-between p-4 sm:p-5 text-left hover:bg-slate-100/60 transition cursor-pointer" data-target="faq-<?php echo $index; ?>" aria-expanded="false">
                    <span class="font-semibold text-slate-800 text-xs sm:text-sm"><?php echo htmlspecialchars($faq['q']); ?></span>
                    <svg class="w-4 h-4 text-slate-400 transition-transform duration-300 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div id="faq-<?php echo $index; ?>" class="faq-answer max-h-0 overflow-hidden px-4 sm:px-5 text-xs sm:text-sm text-slate-600 leading-relaxed"><?php echo htmlspecialchars($faq['a']); ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- CONTACT & FINAL CTA                                          -->
<!-- ============================================================ -->
<section id="contact" class="py-14 bg-slate-50 border-t border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-stretch">
            <!-- Contact Info -->
            <div class="lg:col-span-6 bg-white rounded-2xl border border-slate-200 p-6 sm:p-7 shadow-2xs">
                <h3 class="text-lg sm:text-xl font-bold text-slate-900">Reach the Barangay Office</h3>
                <p class="text-slate-500 mt-1 text-xs sm:text-sm leading-relaxed">For inquiries regarding waste management, bulk garbage collection, or emergency concerns.</p>

                <div class="mt-5 space-y-3.5">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 flex-shrink-0 border border-emerald-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-800 text-xs">Official Address</p>
                            <p class="text-xs text-slate-500 mt-0.5"><?php echo htmlspecialchars($barangayAddress); ?></p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 flex-shrink-0 border border-emerald-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-800 text-xs">Hotline &amp; Telephone</p>
                            <p class="text-xs text-slate-500 mt-0.5"><?php echo htmlspecialchars($barangayContact); ?></p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 flex-shrink-0 border border-emerald-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-slate-800 text-xs">Official Email</p>
                            <p class="text-xs text-slate-500 mt-0.5"><?php echo htmlspecialchars($barangayEmail); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA Card -->
            <div class="lg:col-span-6 bg-[#07281E] rounded-2xl p-6 sm:p-7 text-white shadow-sm flex flex-col justify-center space-y-3.5">
                <span class="text-emerald-400 text-xs font-semibold uppercase tracking-wider">JOIN THE COMMUNITY</span>
                <h3 class="text-xl sm:text-2xl font-bold tracking-tight">Keep Barangay <?php echo htmlspecialchars($barangayName); ?> Clean &amp; Safe</h3>
                <p class="text-emerald-100/80 text-xs sm:text-sm leading-relaxed">Register your resident account to submit reports, track collection status, and stay updated on local ordinances.</p>
                <div class="flex flex-wrap items-center gap-3 pt-2">
                    <?php if ($isLoggedIn): ?>
                        <a href="/brgy-waste-app-v3/public/index.php?url=<?php echo urlencode($role == 'resident' ? 'resident' : ($role == 'supervisor' ? 'supervisor' : 'admin')); ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#10B981] hover:bg-emerald-500 text-white font-semibold rounded-xl text-xs transition">
                            Go to Dashboard →
                        </a>
                    <?php else: ?>
                        <a href="/brgy-waste-app-v3/public/index.php?url=auth/register" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#10B981] hover:bg-emerald-500 text-white font-semibold rounded-xl text-xs shadow-sm transition">
                            Register Free Account
                        </a>
                        <a href="/brgy-waste-app-v3/public/index.php?url=auth" class="inline-flex items-center gap-2 px-4 py-2.5 border border-white/20 hover:bg-white/10 text-white font-semibold rounded-xl text-xs transition">
                            Login
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- FOOTER                                                       -->
<!-- ============================================================ -->
<footer class="bg-[#051E17] text-slate-300 py-10 px-4 border-t border-emerald-950">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Brand -->
            <div>
                <div class="flex items-center gap-2.5 mb-3">
                    <div class="w-8 h-8 rounded-full bg-[#07281E] flex items-center justify-center text-white shadow-sm overflow-hidden border border-emerald-500/20">
                        <?php if (!empty($sysLogo)): ?>
                            <img src="<?php echo htmlspecialchars($sysLogo); ?>" class="w-full h-full object-cover" alt="<?php echo htmlspecialchars($sysShortName); ?> Logo">
                        <?php else: ?>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        <?php endif; ?>
                    </div>
                    <span class="font-bold text-white text-base"><?php echo htmlspecialchars($sysShortName); ?></span>
                </div>
                <p class="text-xs text-slate-400 leading-relaxed">
                    Barangay <?php echo htmlspecialchars($barangayName); ?> Waste Management Portal.<br>
                    Community-powered waste reporting.
                </p>
                <p class="text-[11px] text-slate-500 mt-3">© <?php echo date('Y'); ?> <?php echo htmlspecialchars($sysShortName); ?>. All rights reserved.</p>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-white font-semibold text-xs mb-3 uppercase tracking-wider">Navigation</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="/brgy-waste-app-v3/public/" class="text-slate-400 hover:text-white transition">Home</a></li>
                    <li><a href="#features" class="text-slate-400 hover:text-white transition">Features</a></li>
                    <li><a href="#community-guide" class="text-slate-400 hover:text-white transition">Waste Guide</a></li>
                    <li><a href="#penalties" class="text-slate-400 hover:text-white transition">Penalties &amp; Laws</a></li>
                    <li><a href="#schedule" class="text-slate-400 hover:text-white transition">Schedule &amp; Notes</a></li>
                </ul>
            </div>

            <!-- Resources -->
            <div>
                <h4 class="text-white font-semibold text-xs mb-3 uppercase tracking-wider">Services</h4>
                <ul class="space-y-2 text-xs">
                    <li><a href="/brgy-waste-app-v3/public/index.php?url=guest" class="text-slate-400 hover:text-white transition">Report as Guest</a></li>
                    <li><a href="/brgy-waste-app-v3/public/index.php?url=guest/track" class="text-slate-400 hover:text-white transition">Track Incident</a></li>
                    <li><a href="/brgy-waste-app-v3/public/index.php?url=auth/register" class="text-slate-400 hover:text-white transition">Resident Registration</a></li>
                    <li><a href="/brgy-waste-app-v3/public/index.php?url=auth" class="text-slate-400 hover:text-white transition">Sign In</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h4 class="text-white font-semibold text-xs mb-3 uppercase tracking-wider">Barangay Hall</h4>
                <ul class="space-y-2 text-xs">
                    <li class="flex items-start gap-2 text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="flex-shrink-0 mt-0.5"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                        <?php echo htmlspecialchars($barangayAddress); ?>
                    </li>
                    <li class="flex items-start gap-2 text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="flex-shrink-0 mt-0.5"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        <?php echo htmlspecialchars($barangayContact); ?>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>

<!-- ============================================================ -->
<!-- JAVASCRIPT                                                   -->
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
            dots.forEach(dot => dot.classList.remove('bg-white', 'opacity-100'));
            
            if (slides[index]) slides[index].classList.add('active');
            if (dots[index]) dots[index].classList.add('bg-white', 'opacity-100');
            
            currentIndex = index;
        }

        function nextSlide() { goToSlide((currentIndex + 1) % totalSlides); }
        function prevSlide() { goToSlide((currentIndex - 1 + totalSlides) % totalSlides); }

        goToSlide(0);

        const resetTimer = () => { clearInterval(interval); startAutoPlay(); };

        if (nextBtn) nextBtn.addEventListener('click', () => { resetTimer(); nextSlide(); });
        if (prevBtn) prevBtn.addEventListener('click', () => { resetTimer(); prevSlide(); });
        dots.forEach(dot => {
            dot.addEventListener('click', function() {
                resetTimer();
                goToSlide(parseInt(this.dataset.index));
            });
        });

        function startAutoPlay() { interval = setInterval(nextSlide, 5000); }
        startAutoPlay();
    });

    // ====== FAQ Accordion ======
    document.querySelectorAll('.faq-question').forEach(button => {
        button.addEventListener('click', function() {
            const target = document.getElementById(this.dataset.target);
            const isOpen = target.style.maxHeight && target.style.maxHeight !== '0px';

            document.querySelectorAll('.faq-answer').forEach(ans => {
                ans.style.maxHeight = '0px';
                ans.style.paddingTop = '0px';
                ans.style.paddingBottom = '0px';
                const p = ans.parentElement;
                if (p) {
                    const icon = p.querySelector('.faq-question svg');
                    if (icon) icon.style.transform = 'rotate(0deg)';
                    const q = p.querySelector('.faq-question');
                    if (q) q.setAttribute('aria-expanded', 'false');
                }
            });

            if (!isOpen) {
                target.style.maxHeight = target.scrollHeight + 'px';
                target.style.paddingTop = '16px';
                target.style.paddingBottom = '16px';
                const icon = this.querySelector('svg');
                if (icon) icon.style.transform = 'rotate(180deg)';
                this.setAttribute('aria-expanded', 'true');
            }
        });
    });

    // ====== Mobile Menu (Side Drawer) ======
    const menuToggle = document.getElementById('menuToggle');
    const menuClose = document.getElementById('menuClose');
    const mobileMenu = document.getElementById('mobileMenu');
    const drawer = mobileMenu?.querySelector('.mobile-menu');

    function openMenu() {
        if (!mobileMenu || !drawer) return;
        mobileMenu.classList.remove('hidden');
        setTimeout(() => { drawer.classList.remove('translate-x-full'); }, 10);
        document.body.style.overflow = 'hidden';
    }

    function closeMenu() {
        if (!mobileMenu || !drawer) return;
        drawer.classList.add('translate-x-full');
        setTimeout(() => { mobileMenu.classList.add('hidden'); }, 300);
        document.body.style.overflow = '';
    }

    if (menuToggle && mobileMenu) {
        menuToggle.addEventListener('click', openMenu);
        menuClose?.addEventListener('click', closeMenu);
        mobileMenu.addEventListener('click', function(e) {
            if (e.target === this) closeMenu();
        });
        mobileMenu.querySelectorAll('a').forEach(link => link.addEventListener('click', closeMenu));
    }

    // ====== Leaflet Community Map Initialization ======
    (function() {
        const mapContainer = document.getElementById('landingMap');
        if (!mapContainer || typeof L === 'undefined') return;

        const defaultCenter = [<?php echo (float)($mapConfig['center']['lat'] ?? 15.558); ?>, <?php echo (float)($mapConfig['center']['lng'] ?? 120.803); ?>];
        const defaultZoom = <?php echo (int)($mapConfig['center']['zoom'] ?? 15); ?>;

        const landingMap = L.map('landingMap', {
            center: defaultCenter,
            zoom: defaultZoom,
            scrollWheelZoom: false,
            zoomControl: true
        });

        // Click map to enable scroll wheel zoom; disable on mouse leave to keep page scrolling smooth
        landingMap.on('click', () => { landingMap.scrollWheelZoom.enable(); });
        mapContainer.addEventListener('mouseleave', () => { landingMap.scrollWheelZoom.disable(); });

        // Tile Layers
        const streetTiles = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors',
            maxZoom: 19
        });

        const satelliteTiles = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri',
            maxZoom: 19
        });

        const labelsTiles = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
            attribution: '',
            maxZoom: 19
        });

        const satelliteGroup = L.layerGroup([satelliteTiles, labelsTiles]);

        // Default to Street tiles
        streetTiles.addTo(landingMap);
        let activeTileMode = 'street';

        window.toggleLandingTileLayer = function() {
            const labelEl = document.getElementById('mapTypeLabel');
            if (activeTileMode === 'street') {
                landingMap.removeLayer(streetTiles);
                satelliteGroup.addTo(landingMap);
                activeTileMode = 'satellite';
                if (labelEl) labelEl.textContent = 'Street View';
            } else {
                landingMap.removeLayer(satelliteGroup);
                streetTiles.addTo(landingMap);
                activeTileMode = 'street';
                if (labelEl) labelEl.textContent = 'Satellite View';
            }
        };

        // Barangay Boundary
        const brgyBoundaryData = <?php echo json_encode($mapConfig['boundary_geojson'] ?? null); ?>;
        if (brgyBoundaryData) {
            try {
                const brgyGeo = typeof brgyBoundaryData === 'string' ? JSON.parse(brgyBoundaryData) : brgyBoundaryData;
                L.geoJSON(brgyGeo, {
                    style: {
                        color: '#0B2E22',
                        weight: 2.5,
                        fillColor: '#10B981',
                        fillOpacity: 0.04,
                        dashArray: '6,6'
                    }
                }).addTo(landingMap);
            } catch(e) {}
        }

        // Purok Boundaries Layer Group
        const puroksLayerGroup = L.layerGroup();
        const puroksData = <?php echo json_encode($mapConfig['puroks'] ?? []); ?>;
        let puroksVisible = true;

        puroksData.forEach(p => {
            if (p.polygon_geometry) {
                try {
                    const geo = typeof p.polygon_geometry === 'string' ? JSON.parse(p.polygon_geometry) : p.polygon_geometry;
                    if (geo) {
                        L.geoJSON(geo, {
                            style: {
                                color: '#10B981',
                                weight: 1.5,
                                fillColor: '#10B981',
                                fillOpacity: 0.08
                            }
                        }).bindPopup(`<strong style="font-family: 'Miranda Sans', sans-serif; font-size: 12px;">${p.purok_name}</strong>`).addTo(puroksLayerGroup);
                    }
                } catch(e) {}
            }
        });
        puroksLayerGroup.addTo(landingMap);

        window.toggleLandingPuroks = function() {
            const btn = document.getElementById('togglePurokBtn');
            if (puroksVisible) {
                landingMap.removeLayer(puroksLayerGroup);
                puroksVisible = false;
                if (btn) {
                    btn.classList.remove('bg-emerald-50', 'text-emerald-800', 'border-emerald-200');
                    btn.classList.add('bg-slate-100', 'text-slate-600', 'border-slate-200');
                }
            } else {
                puroksLayerGroup.addTo(landingMap);
                puroksVisible = true;
                if (btn) {
                    btn.classList.remove('bg-slate-100', 'text-slate-600', 'border-slate-200');
                    btn.classList.add('bg-emerald-50', 'text-emerald-800', 'border-emerald-200');
                }
            }
        };

        // Report Markers
        const allReports = <?php echo json_encode($reportPoints); ?>;
        const markersLayerGroup = L.featureGroup();

        function renderMarkers(filterType) {
            markersLayerGroup.clearLayers();

            allReports.forEach(r => {
                const st = (r.status || '').toLowerCase();
                const isResolved = st === 'resolved';
                const isActive = !isResolved;

                if (filterType === 'resolved' && !isResolved) return;
                if (filterType === 'active' && !isActive) return;

                const markerColor = isResolved ? '#10B981' : (st === 'verified' ? '#3B82F6' : (st === 'in progress' || st === 'dispatched' ? '#8B5CF6' : '#F59E0B'));

                const customIcon = L.divIcon({
                    html: `<div style="background-color: ${markerColor}; width: 14px; height: 14px; border-radius: 50%; border: 2.5px solid white; box-shadow: 0 2px 8px rgba(0,0,0,0.35);"></div>`,
                    className: '',
                    iconSize: [14, 14],
                    iconAnchor: [7, 7]
                });

                const photoHtml = r.photo ? `<div style="margin-bottom: 8px; border-radius: 8px; overflow: hidden; max-height: 100px;"><img src="${r.photo}" style="width: 100%; height: 80px; object-fit: cover;"></div>` : '';

                const popupContent = `
                    <div style="font-family: 'Miranda Sans', sans-serif; min-width: 180px; max-width: 220px;">
                        ${photoHtml}
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 4px;">
                            <span style="font-size: 10px; font-weight: 700; color: ${markerColor}; text-transform: uppercase; letter-spacing: 0.5px;">${r.status}</span>
                            <span style="font-size: 10px; color: #94A3B8;">${r.date}</span>
                        </div>
                        <h4 style="font-size: 13px; font-weight: 700; color: #0F172A; margin: 0 0 2px 0; line-height: 1.3;">${r.category}</h4>
                        <p style="font-size: 11px; color: #64748B; margin: 0 0 6px 0;">📍 ${r.purok}</p>
                        <p style="font-size: 11px; color: #475569; margin: 0 0 8px 0; line-height: 1.4;">${r.desc}</p>
                        <a href="/brgy-waste-app-v3/public/index.php?url=guest/track&track_id=${r.id}" style="display: inline-block; font-size: 11px; font-weight: 700; color: #10B981; text-decoration: none;">Track Incident →</a>
                    </div>
                `;

                const marker = L.marker([r.lat, r.lng], { icon: customIcon }).bindPopup(popupContent);
                markersLayerGroup.addLayer(marker);
            });

            markersLayerGroup.addTo(landingMap);
        }

        renderMarkers('all');

        window.filterLandingMap = function(type) {
            document.querySelectorAll('.landing-map-filter-btn').forEach(btn => {
                btn.className = "landing-map-filter-btn px-3 py-1.5 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 transition cursor-pointer";
            });
            const activeBtn = document.getElementById(`filter-btn-${type}`);
            if (activeBtn) {
                activeBtn.className = "landing-map-filter-btn px-3 py-1.5 rounded-xl bg-slate-900 text-white font-semibold shadow-2xs transition cursor-pointer";
            }
            renderMarkers(type);
        };
    })();

    // ====== Smooth scroll for all anchor navigation links ======
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (!href || href === '#') return;
            const target = document.querySelector(href);
            if (target) {
                e.preventDefault();
                const offset = 75;
                const bodyRect = document.body.getBoundingClientRect().top;
                const elementRect = target.getBoundingClientRect().top;
                const elementPosition = elementRect - bodyRect;
                const offsetPosition = elementPosition - offset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
</script>

    </body>
</html>