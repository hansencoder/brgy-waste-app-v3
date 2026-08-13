<?php
// Get current user info from session
$fullName = $_SESSION['user_name'] ?? 'Resident';
$firstName = isset($_SESSION['user_name']) ? explode(' ', trim($_SESSION['user_name']))[0] : 'Resident';
$purok = $_SESSION['user_purok'] ?? 'Purok 1';

// Determine active page for highlighting
$currentUri = $_SERVER['REQUEST_URI'];
$isActive = function($path) use ($currentUri) {
    if ($path === '/resident') {
        return (strpos($currentUri, '/resident') !== false && strpos($currentUri, '/resident/') === false) || 
               strpos($currentUri, '/resident/dashboard') !== false;
    }
    return strpos($currentUri, $path) !== false;
};
?>

<!-- Mobile Topbar Header with Hamburger Menu Toggle -->
<div class="lg:hidden bg-[#041a14] border-b border-emerald-900/40 px-4 py-3 flex items-center justify-between sticky top-0 z-50">
    <div class="flex items-center gap-3">
        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#083528] border border-emerald-500/30 shadow-[0_0_12px_rgba(16,185,129,0.3)]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#10B981]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
        </div>
        <div>
            <p class="text-base font-extrabold text-white tracking-tight leading-none">Wastewatch</p>
            <p class="text-[10px] font-medium text-emerald-400/80 leading-tight">Resident Portal</p>
        </div>
    </div>
    <button type="button" id="residentSidebarToggle" onclick="document.getElementById('residentSidebarDrawer').classList.toggle('hidden')" class="p-2 rounded-xl bg-white/5 text-emerald-300 hover:bg-white/10 hover:text-white transition focus:outline-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>
</div>

<!-- Main Resident Sidebar Component -->
<aside id="residentSidebarDrawer" class="hidden lg:flex w-full lg:w-72 bg-[#041a14] text-white px-4 py-5 lg:sticky lg:top-0 lg:h-screen flex-col flex-shrink-0 border-r border-emerald-950/60 z-40">
    <!-- Brand Header -->
    <div class="flex items-center gap-3 px-2 py-1 flex-shrink-0">
        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-[#083528] border border-emerald-500/30 shadow-[0_0_18px_rgba(16,185,129,0.35)]">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#10B981]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
        </div>
        <div>
            <h1 class="text-xl font-extrabold text-white tracking-tight leading-snug">Wastewatch</h1>
            <p class="text-xs font-medium text-emerald-400/70 leading-none">Resident Portal</p>
        </div>
    </div>

    <!-- Navigation Scroll Area -->
    <nav class="mt-6 flex-1 overflow-y-auto space-y-6 pr-1 custom-scrollbar">
        <!-- Section: CORE -->
        <div>
            <p class="text-[10px] font-black uppercase tracking-[0.25em] text-emerald-400/50 mb-2 px-3">CORE</p>
            <div class="space-y-1">
                <!-- Dashboard -->
                <?php $activeDash = $isActive('/resident') && !$isActive('/resident/submit') && !$isActive('/resident/my_report') && !$isActive('/resident/profile') && !$isActive('/resident/announcements') && !$isActive('/resident/collection_schedule') && !$isActive('/resident/notification'); ?>
                <a href="/brgy-waste-app-v3/public/resident" class="relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all <?php echo $activeDash ? 'bg-[#083528] text-white border border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.15)]' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>">
                    <?php if ($activeDash): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-[0_0_8px_#10B981]"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 <?php echo $activeDash ? 'text-[#10B981]' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <!-- Submit Report -->
                <?php $activeSubmit = $isActive('/resident/submit'); ?>
                <a href="/brgy-waste-app-v3/public/resident/submit" class="relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all <?php echo $activeSubmit ? 'bg-[#083528] text-white border border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.15)]' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>">
                    <?php if ($activeSubmit): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-[0_0_8px_#10B981]"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 <?php echo $activeSubmit ? 'text-[#10B981]' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 5v14"/><path d="M5 12h14"/>
                    </svg>
                    <span>Submit Report</span>
                </a>

                <!-- My Reports -->
                <?php $activeMyReports = $isActive('/resident/my_report'); ?>
                <a href="/brgy-waste-app-v3/public/resident/my_report" class="relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all <?php echo $activeMyReports ? 'bg-[#083528] text-white border border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.15)]' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>">
                    <?php if ($activeMyReports): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-[0_0_8px_#10B981]"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 <?php echo $activeMyReports ? 'text-[#10B981]' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M7 3h10a2 2 0 0 1 2 2v14l-4-2-4 2-4-2-4 2V5a2 2 0 0 1 2-2Z"/>
                    </svg>
                    <span>My Reports</span>
                </a>
            </div>
        </div>

        <!-- Section: SERVICES -->
        <div>
            <p class="text-[10px] font-black uppercase tracking-[0.25em] text-emerald-400/50 mb-2 px-3">SERVICES</p>
            <div class="space-y-1">
                <!-- Collection Schedule -->
                <?php $activeSched = $isActive('/resident/collection_schedule'); ?>
                <a href="/brgy-waste-app-v3/public/resident/collection_schedule" class="relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all <?php echo $activeSched ? 'bg-[#083528] text-white border border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.15)]' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>">
                    <?php if ($activeSched): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-[0_0_8px_#10B981]"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 <?php echo $activeSched ? 'text-[#10B981]' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/>
                    </svg>
                    <span>Collection Schedule</span>
                </a>

                <!-- Announcements -->
                <?php $activeAnnounce = $isActive('/resident/announcements'); ?>
                <a href="/brgy-waste-app-v3/public/resident/announcements" class="relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all <?php echo $activeAnnounce ? 'bg-[#083528] text-white border border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.15)]' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>">
                    <?php if ($activeAnnounce): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-[0_0_8px_#10B981]"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 <?php echo $activeAnnounce ? 'text-[#10B981]' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m3 11 18-5v12L3 13v-2z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/>
                    </svg>
                    <span>Announcements</span>
                </a>

                <!-- Notifications -->
                <?php $activeNotif = $isActive('/resident/notification'); ?>
                <a href="/brgy-waste-app-v3/public/resident/notification" class="relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all <?php echo $activeNotif ? 'bg-[#083528] text-white border border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.15)]' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>">
                    <?php if ($activeNotif): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-[0_0_8px_#10B981]"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 <?php echo $activeNotif ? 'text-[#10B981]' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                    </svg>
                    <span>Notifications</span>
                    <?php if (isset($unreadCount) && $unreadCount > 0): ?>
                        <span class="ml-auto rounded-full bg-[#FF4D4D] text-white text-[10px] font-black px-2 py-0.5 shadow-sm"><?php echo $unreadCount; ?></span>
                    <?php endif; ?>
                </a>
            </div>
        </div>

        <!-- Section: ACCOUNT -->
        <div>
            <p class="text-[10px] font-black uppercase tracking-[0.25em] text-emerald-400/50 mb-2 px-3">ACCOUNT</p>
            <div class="space-y-1">
                <!-- Profile -->
                <?php $activeProfile = $isActive('/resident/profile'); ?>
                <a href="/brgy-waste-app-v3/public/resident/profile" class="relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all <?php echo $activeProfile ? 'bg-[#083528] text-white border border-emerald-500/30 shadow-[0_0_15px_rgba(16,185,129,0.15)]' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>">
                    <?php if ($activeProfile): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-[0_0_8px_#10B981]"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 <?php echo $activeProfile ? 'text-[#10B981]' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21a8 8 0 1 0-16 0"/><circle cx="12" cy="8" r="4"/>
                    </svg>
                    <span>Profile</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Bottom User Profile Card -->
    <div class="mt-4 pt-3 flex-shrink-0">
        <div class="bg-[#0B3326]/90 border border-emerald-500/20 p-3 rounded-2xl flex items-center justify-between shadow-lg">
            <div class="flex items-center gap-3 min-w-0">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-[#084232] border border-emerald-400/40 text-[#10B981] font-bold text-sm shadow-inner">
                    <?php echo strtoupper(substr($firstName, 0, 1)); ?>
                </div>
                <div class="min-w-0">
                    <p class="font-bold text-sm text-white leading-tight truncate"><?php echo htmlspecialchars($fullName); ?></p>
                    <p class="text-[11px] font-medium text-emerald-300/70 leading-tight truncate">Resident · <?php echo htmlspecialchars($purok); ?></p>
                </div>
            </div>
            <a href="/brgy-waste-app-v3/public/auth/logout" title="Logout" class="p-2 rounded-xl text-slate-300 hover:text-white hover:bg-white/10 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
            </a>
        </div>
    </div>
</aside>