<?php
// Ensure database and models are loaded
require_once __DIR__ . '/../../init.php';
require_once __DIR__ . '/../../Models/Notification.php';

// Get current user info from session
$fullName = $_SESSION['user_name'] ?? 'Supervisor User';
$firstName = isset($_SESSION['user_name']) ? explode(' ', trim($_SESSION['user_name']))[0] : 'Supervisor';
$roleName = $_SESSION['user_role'] ?? 'Supervisor';

// Fetch branding details
try {
    $sideDb = new Database();
    $sideDb->query("SELECT system_name, system_short_name, system_motto, barangay_name, system_logo FROM barangays LIMIT 1");
    $sideBranding = $sideDb->single();
} catch (Exception $e) {
    $sideBranding = null;
}
$sideSysShortName = !empty($sideBranding['system_short_name']) ? $sideBranding['system_short_name'] : 'WasteWatch';
$sideBrgyName = !empty($sideBranding['barangay_name']) ? $sideBranding['barangay_name'] : 'Dulong Bayan';
$sideSysLogo = !empty($sideBranding['system_logo']) ? $sideBranding['system_logo'] : null;
if ($sideSysLogo && strpos($sideSysLogo, '/brgy-waste-app-v3') === false && strpos($sideSysLogo, '/public') === 0) {
    $sideSysLogo = '/brgy-waste-app-v3' . $sideSysLogo;
}

$sideUnreadCount = 0;
if (isset($_SESSION['user_id'])) {
    try {
        $sideNotifModel = new Notification();
        $sideUnreadCount = $sideNotifModel->getUnreadCount($_SESSION['user_id']);
    } catch (Exception $e) {
        $sideUnreadCount = 0;
    }
}

// Determine active page
$currentUri = $_SERVER['REQUEST_URI'] ?? '';
$isPageActive = function($keywords) use ($currentUri) {
    if (!is_array($keywords)) {
        $keywords = [$keywords];
    }
    foreach ($keywords as $kw) {
        if ($kw === 'supervisor' || $kw === 'dashboard') {
            if ((strpos($currentUri, 'url=supervisor') !== false || strpos($currentUri, '/supervisor') !== false) &&
                strpos($currentUri, 'reports') === false &&
                strpos($currentUri, 'view_report') === false &&
                strpos($currentUri, 'gis') === false &&
                strpos($currentUri, 'analytics') === false &&
                strpos($currentUri, 'schedule') === false &&
                strpos($currentUri, 'announcements') === false &&
                strpos($currentUri, 'notifications') === false &&
                strpos($currentUri, 'profile') === false) {
                return true;
            }
        } else {
            if (strpos($currentUri, $kw) !== false) {
                return true;
            }
        }
    }
    return false;
};

// Check collapsed cookie state
$isCollapsed = isset($_COOKIE['supervisor_sidebar_collapsed']) && $_COOKIE['supervisor_sidebar_collapsed'] === 'true';
?>

<!-- Mobile Sidebar Backdrop Overlay -->
<div id="mobileBackdrop" onclick="toggleMobileSidebar()" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-40 hidden lg:hidden transition-opacity"></div>

<!-- Mobile Off-Canvas Drawer -->
<div id="mobileSidebar" class="fixed inset-y-0 left-0 w-72 bg-[#07281E] text-white z-50 transform -translate-x-full transition-transform duration-300 ease-in-out lg:hidden flex flex-col shadow-2xl">
    <!-- Mobile Drawer Header -->
    <div class="h-16 flex items-center justify-between px-5 border-b border-emerald-900/60 bg-[#062018]">
        <div class="flex items-center gap-3">
            <div class="h-9 w-9 rounded-full bg-[#0B2E22] flex items-center justify-center overflow-hidden border border-emerald-500/40 shrink-0">
                <?php if (!empty($sideSysLogo)): ?>
                    <img src="<?php echo htmlspecialchars($sideSysLogo); ?>" class="h-full w-full object-cover">
                <?php else: ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#10B981]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <?php endif; ?>
            </div>
            <div>
                <p class="text-sm font-bold text-white leading-tight"><?php echo htmlspecialchars($sideSysShortName); ?></p>
                <p class="text-[10px] text-emerald-400 font-medium leading-none">Supervisor Portal</p>
            </div>
        </div>
        <button onclick="toggleMobileSidebar()" class="p-2 text-emerald-400/80 hover:text-white rounded-lg hover:bg-white/5 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Mobile Nav Links -->
    <nav class="flex-1 px-3 py-4 space-y-5 overflow-y-auto">
        <div>
            <p class="px-3 text-[10px] font-semibold text-emerald-400/60 uppercase tracking-wider mb-1.5">Core</p>
            <div class="space-y-1">
                <?php $mDash = $isPageActive('supervisor'); ?>
                <a href="/brgy-waste-app-v3/public/supervisor" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition <?php echo $mDash ? 'bg-[#0B2E22] text-white border border-emerald-500/30' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 <?php echo $mDash ? 'text-emerald-400' : 'text-slate-400'; ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
                    <span>Dashboard</span>
                </a>

                <?php $mRep = $isPageActive(['reports', 'view_report']); ?>
                <a href="/brgy-waste-app-v3/public/supervisor/reports" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition <?php echo $mRep ? 'bg-[#0B2E22] text-white border border-emerald-500/30' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 <?php echo $mRep ? 'text-emerald-400' : 'text-slate-400'; ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                    <span>Reports</span>
                </a>
            </div>
        </div>

        <div>
            <p class="px-3 text-[10px] font-semibold text-emerald-400/60 uppercase tracking-wider mb-1.5">Operations &amp; GIS</p>
            <div class="space-y-1">
                <?php $mGis = $isPageActive('gis'); ?>
                <a href="/brgy-waste-app-v3/public/supervisor/gis" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition <?php echo $mGis ? 'bg-[#0B2E22] text-white border border-emerald-500/30' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 <?php echo $mGis ? 'text-emerald-400' : 'text-slate-400'; ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/><line x1="9" y1="3" x2="9" y2="18"/><line x1="15" y1="6" x2="15" y2="21"/></svg>
                    <span>GIS Monitor</span>
                </a>

                <?php $mAna = $isPageActive('analytics'); ?>
                <a href="/brgy-waste-app-v3/public/supervisor/analytics" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition <?php echo $mAna ? 'bg-[#0B2E22] text-white border border-emerald-500/30' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 <?php echo $mAna ? 'text-emerald-400' : 'text-slate-400'; ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 2a10 10 0 0 1 10 10h-10z"/></svg>
                    <span>Analytics</span>
                </a>

                <?php $mSched = $isPageActive('schedule'); ?>
                <a href="/brgy-waste-app-v3/public/supervisor/schedule" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition <?php echo $mSched ? 'bg-[#0B2E22] text-white border border-emerald-500/30' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 <?php echo $mSched ? 'text-emerald-400' : 'text-slate-400'; ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    <span>Schedule</span>
                </a>
            </div>
        </div>

        <div>
            <p class="px-3 text-[10px] font-semibold text-emerald-400/60 uppercase tracking-wider mb-1.5">Communications</p>
            <div class="space-y-1">
                <?php $mAnn = $isPageActive('announcements'); ?>
                <a href="/brgy-waste-app-v3/public/supervisor/announcements" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition <?php echo $mAnn ? 'bg-[#0B2E22] text-white border border-emerald-500/30' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 <?php echo $mAnn ? 'text-emerald-400' : 'text-slate-400'; ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="m3 11 18-5v12L3 14v-3z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>
                    <span>Bulletins</span>
                </a>

                <?php $mNot = $isPageActive('notifications'); ?>
                <a href="/brgy-waste-app-v3/public/supervisor/notifications" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold transition <?php echo $mNot ? 'bg-[#0B2E22] text-white border border-emerald-500/30' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 <?php echo $mNot ? 'text-emerald-400' : 'text-slate-400'; ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                        <span>Notifications</span>
                    </div>
                    <?php if ($sideUnreadCount > 0): ?>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500 text-white font-mono"><?php echo $sideUnreadCount; ?></span>
                    <?php endif; ?>
                </a>
            </div>
        </div>

        <div>
            <p class="px-3 text-[10px] font-semibold text-emerald-400/60 uppercase tracking-wider mb-1.5">Account</p>
            <div class="space-y-1">
                <?php $mProf = $isPageActive('profile'); ?>
                <a href="/brgy-waste-app-v3/public/supervisor/profile" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition <?php echo $mProf ? 'bg-[#0B2E22] text-white border border-emerald-500/30' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 <?php echo $mProf ? 'text-emerald-400' : 'text-slate-400'; ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 1 0-16 0"/></svg>
                    <span>My Profile</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Mobile Footer Logout -->
    <div class="p-4 border-t border-emerald-900/60 bg-[#062018]">
        <a href="/brgy-waste-app-v3/public/index.php?url=auth/logout" class="flex items-center justify-center gap-2 w-full py-2.5 px-4 rounded-xl bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 text-xs font-semibold transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            <span>Log Out</span>
        </a>
    </div>
</div>

<!-- Desktop Persistent Sidebar -->
<aside id="desktopSidebar" class="hidden lg:flex flex-col <?php echo $isCollapsed ? 'w-20' : 'w-64'; ?> bg-[#07281E] text-white h-screen sticky top-0 border-r border-emerald-950 flex-shrink-0 transition-all duration-300 z-40 shadow-xl">
    
    <!-- Branding Header -->
    <div class="h-16 flex items-center <?php echo $isCollapsed ? 'justify-center px-0' : 'justify-between px-5'; ?> border-b border-emerald-900/60 bg-[#062018]/50">
        <a href="/brgy-waste-app-v3/public/supervisor" class="flex items-center gap-3 overflow-hidden group">
            <div class="h-9 w-9 rounded-full bg-[#0B2E22] flex items-center justify-center overflow-hidden border border-emerald-500/40 shrink-0 group-hover:scale-105 transition">
                <?php if (!empty($sideSysLogo)): ?>
                    <img src="<?php echo htmlspecialchars($sideSysLogo); ?>" class="h-full w-full object-cover">
                <?php else: ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#10B981]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <?php endif; ?>
            </div>
            <div class="sidebar-text <?php echo $isCollapsed ? 'hidden' : ''; ?> min-w-0">
                <p class="text-sm font-bold text-white leading-tight truncate"><?php echo htmlspecialchars($sideSysShortName); ?></p>
                <p class="text-[10px] text-emerald-400 font-medium leading-none truncate">Supervisor Portal</p>
            </div>
        </a>
    </div>

    <!-- Navigation Scroll Area -->
    <nav class="flex-1 px-3 py-4 space-y-5 overflow-y-auto custom-scrollbar">
        
        <!-- CORE -->
        <div>
            <p class="sidebar-text <?php echo $isCollapsed ? 'hidden' : ''; ?> px-3 text-[10px] font-semibold text-emerald-400/60 uppercase tracking-wider mb-1.5">Core</p>
            <div class="space-y-1">
                <!-- Dashboard -->
                <?php $dDash = $isPageActive('supervisor'); ?>
                <a href="/brgy-waste-app-v3/public/supervisor" class="relative flex items-center <?php echo $isCollapsed ? 'justify-center px-0' : 'gap-3 px-3'; ?> py-2.5 rounded-xl text-xs font-semibold transition group <?php echo $dDash ? 'bg-[#0B2E22] text-white border border-emerald-500/30' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>" title="Dashboard">
                    <?php if ($dDash): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-emerald-400 rounded-r-full"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $dDash ? 'text-emerald-400' : 'text-slate-400 group-hover:text-white'; ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
                    <span class="sidebar-text <?php echo $isCollapsed ? 'hidden' : ''; ?> truncate">Dashboard</span>
                </a>

                <!-- Reports -->
                <?php $dRep = $isPageActive(['reports', 'view_report']); ?>
                <a href="/brgy-waste-app-v3/public/supervisor/reports" class="relative flex items-center <?php echo $isCollapsed ? 'justify-center px-0' : 'gap-3 px-3'; ?> py-2.5 rounded-xl text-xs font-semibold transition group <?php echo $dRep ? 'bg-[#0B2E22] text-white border border-emerald-500/30' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>" title="Reports">
                    <?php if ($dRep): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-emerald-400 rounded-r-full"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $dRep ? 'text-emerald-400' : 'text-slate-400 group-hover:text-white'; ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                    <span class="sidebar-text <?php echo $isCollapsed ? 'hidden' : ''; ?> truncate">Reports</span>
                </a>
            </div>
        </div>

        <!-- OPERATIONS & GIS -->
        <div>
            <p class="sidebar-text <?php echo $isCollapsed ? 'hidden' : ''; ?> px-3 text-[10px] font-semibold text-emerald-400/60 uppercase tracking-wider mb-1.5">Operations &amp; GIS</p>
            <div class="space-y-1">
                <!-- GIS Monitor -->
                <?php $dGis = $isPageActive('gis'); ?>
                <a href="/brgy-waste-app-v3/public/supervisor/gis" class="relative flex items-center <?php echo $isCollapsed ? 'justify-center px-0' : 'gap-3 px-3'; ?> py-2.5 rounded-xl text-xs font-semibold transition group <?php echo $dGis ? 'bg-[#0B2E22] text-white border border-emerald-500/30' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>" title="GIS Monitor">
                    <?php if ($dGis): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-emerald-400 rounded-r-full"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $dGis ? 'text-emerald-400' : 'text-slate-400 group-hover:text-white'; ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/><line x1="9" y1="3" x2="9" y2="18"/><line x1="15" y1="6" x2="15" y2="21"/></svg>
                    <span class="sidebar-text <?php echo $isCollapsed ? 'hidden' : ''; ?> truncate">GIS Monitor</span>
                </a>

                <!-- Analytics -->
                <?php $dAna = $isPageActive('analytics'); ?>
                <a href="/brgy-waste-app-v3/public/supervisor/analytics" class="relative flex items-center <?php echo $isCollapsed ? 'justify-center px-0' : 'gap-3 px-3'; ?> py-2.5 rounded-xl text-xs font-semibold transition group <?php echo $dAna ? 'bg-[#0B2E22] text-white border border-emerald-500/30' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>" title="Analytics">
                    <?php if ($dAna): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-emerald-400 rounded-r-full"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $dAna ? 'text-emerald-400' : 'text-slate-400 group-hover:text-white'; ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 2a10 10 0 0 1 10 10h-10z"/></svg>
                    <span class="sidebar-text <?php echo $isCollapsed ? 'hidden' : ''; ?> truncate">Analytics</span>
                </a>

                <!-- Collection Schedule -->
                <?php $dSchedule = $isPageActive('schedule'); ?>
                <a href="/brgy-waste-app-v3/public/supervisor/schedule" class="relative flex items-center <?php echo $isCollapsed ? 'justify-center px-0' : 'gap-3 px-3'; ?> py-2.5 rounded-xl text-xs font-semibold transition group <?php echo $dSchedule ? 'bg-[#0B2E22] text-white border border-emerald-500/30' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>" title="Collection Schedule">
                    <?php if ($dSchedule): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-emerald-400 rounded-r-full"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $dSchedule ? 'text-emerald-400' : 'text-slate-400 group-hover:text-white'; ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    <span class="sidebar-text <?php echo $isCollapsed ? 'hidden' : ''; ?> truncate">Schedule</span>
                </a>
            </div>
        </div>

        <!-- COMMUNICATIONS -->
        <div>
            <p class="sidebar-text <?php echo $isCollapsed ? 'hidden' : ''; ?> px-3 text-[10px] font-semibold text-emerald-400/60 uppercase tracking-wider mb-1.5">Communications</p>
            <div class="space-y-1">
                <!-- Announcements -->
                <?php $dAnn = $isPageActive('announcements'); ?>
                <a href="/brgy-waste-app-v3/public/supervisor/announcements" class="relative flex items-center <?php echo $isCollapsed ? 'justify-center px-0' : 'gap-3 px-3'; ?> py-2.5 rounded-xl text-xs font-semibold transition group <?php echo $dAnn ? 'bg-[#0B2E22] text-white border border-emerald-500/30' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>" title="Bulletins & Announcements">
                    <?php if ($dAnn): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-emerald-400 rounded-r-full"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $dAnn ? 'text-emerald-400' : 'text-slate-400 group-hover:text-white'; ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="m3 11 18-5v12L3 14v-3z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>
                    <span class="sidebar-text <?php echo $isCollapsed ? 'hidden' : ''; ?> truncate">Bulletins</span>
                </a>

                <!-- Notifications -->
                <?php $dNot = $isPageActive('notifications'); ?>
                <a href="/brgy-waste-app-v3/public/supervisor/notifications" class="relative flex items-center <?php echo $isCollapsed ? 'justify-center px-0' : 'justify-between px-3'; ?> py-2.5 rounded-xl text-xs font-semibold transition group <?php echo $dNot ? 'bg-[#0B2E22] text-white border border-emerald-500/30' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>" title="Notifications">
                    <?php if ($dNot): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-emerald-400 rounded-r-full"></span>
                    <?php endif; ?>
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $dNot ? 'text-emerald-400' : 'text-slate-400 group-hover:text-white'; ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                        <span class="sidebar-text <?php echo $isCollapsed ? 'hidden' : ''; ?> truncate">Notifications</span>
                    </div>
                    <?php if ($sideUnreadCount > 0): ?>
                        <span class="sidebar-text <?php echo $isCollapsed ? 'hidden' : ''; ?> px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500 text-white font-mono"><?php echo $sideUnreadCount; ?></span>
                    <?php endif; ?>
                </a>
            </div>
        </div>

        <!-- ACCOUNT -->
        <div>
            <p class="sidebar-text <?php echo $isCollapsed ? 'hidden' : ''; ?> px-3 text-[10px] font-semibold text-emerald-400/60 uppercase tracking-wider mb-1.5">Account</p>
            <div class="space-y-1">
                <!-- Profile -->
                <?php $dProf = $isPageActive('profile'); ?>
                <a href="/brgy-waste-app-v3/public/supervisor/profile" class="relative flex items-center <?php echo $isCollapsed ? 'justify-center px-0' : 'gap-3 px-3'; ?> py-2.5 rounded-xl text-xs font-semibold transition group <?php echo $dProf ? 'bg-[#0B2E22] text-white border border-emerald-500/30' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>" title="My Profile">
                    <?php if ($dProf): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-emerald-400 rounded-r-full"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $dProf ? 'text-emerald-400' : 'text-slate-400 group-hover:text-white'; ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 1 0-16 0"/></svg>
                    <span class="sidebar-text <?php echo $isCollapsed ? 'hidden' : ''; ?> truncate">My Profile</span>
                </a>
            </div>
        </div>

    </nav>

    <!-- Desktop User Footer -->
    <div class="p-3 border-t border-emerald-900/60 bg-[#062018]/50">
        <div class="flex items-center <?php echo $isCollapsed ? 'justify-center' : 'justify-between'; ?> gap-2">
            <a href="/brgy-waste-app-v3/public/supervisor/profile" class="flex items-center gap-2.5 min-w-0 group" title="View Profile">
                <div class="h-8 w-8 rounded-full bg-[#0B2E22] text-white flex items-center justify-center text-xs font-bold border border-emerald-500/30 shrink-0">
                    <?php echo strtoupper(substr($_SESSION['user_name'] ?? 'S', 0, 1)); ?>
                </div>
                <div class="sidebar-text <?php echo $isCollapsed ? 'hidden' : ''; ?> min-w-0">
                    <p class="text-xs font-semibold text-white truncate leading-tight group-hover:text-emerald-300 transition"><?php echo htmlspecialchars($fullName); ?></p>
                    <p class="text-[10px] text-emerald-400/70 truncate leading-none">Supervisor</p>
                </div>
            </a>
            
            <a href="/brgy-waste-app-v3/public/index.php?url=auth/logout" class="sidebar-text <?php echo $isCollapsed ? 'hidden' : ''; ?> p-1.5 text-slate-400 hover:text-red-400 hover:bg-white/5 rounded-lg transition" title="Log Out">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            </a>
        </div>
    </div>

</aside>

<!-- Global Sidebar Collapse & Drawer JavaScript -->
<script>
    function toggleDesktopSidebar() {
        const sidebar = document.getElementById('desktopSidebar');
        const textElements = sidebar.querySelectorAll('.sidebar-text');
        const isCurrentlyCollapsed = sidebar.classList.contains('w-20');
        
        if (isCurrentlyCollapsed) {
            sidebar.classList.remove('w-20');
            sidebar.classList.add('w-64');
            textElements.forEach(el => el.classList.remove('hidden'));
            document.cookie = "supervisor_sidebar_collapsed=false; path=/; max-age=31536000";
        } else {
            sidebar.classList.remove('w-64');
            sidebar.classList.add('w-20');
            textElements.forEach(el => el.classList.add('hidden'));
            document.cookie = "supervisor_sidebar_collapsed=true; path=/; max-age=31536000";
        }

        // Trigger map resize if present
        setTimeout(() => {
            if (typeof map !== 'undefined' && map.invalidateSize) {
                map.invalidateSize();
            }
        }, 300);
    }

    function toggleMobileSidebar() {
        const sidebar = document.getElementById('mobileSidebar');
        const backdrop = document.getElementById('mobileBackdrop');
        const isOpen = !sidebar.classList.contains('-translate-x-full');

        if (isOpen) {
            sidebar.classList.add('-translate-x-full');
            backdrop.classList.add('hidden');
            document.body.style.overflow = '';
        } else {
            sidebar.classList.remove('-translate-x-full');
            backdrop.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }
</script>