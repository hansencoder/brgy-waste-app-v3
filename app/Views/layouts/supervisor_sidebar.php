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
$sideSysLogo = format_asset_url($sideBranding['system_logo'] ?? '');

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
<div id="mobileSidebar" class="fixed inset-y-0 left-0 w-72 bg-[#FFFFFF] text-slate-800 z-50 transform -translate-x-full transition-transform duration-300 ease-in-out lg:hidden flex flex-col shadow-2xl border-r border-slate-200">
    <!-- Mobile Drawer Header -->
    <div class="h-16 flex items-center justify-between px-5 border-b border-slate-100 bg-[#FFFFFF]">
        <div class="flex items-center gap-3">
            <div class="h-9 w-9 rounded-full flex items-center justify-center overflow-hidden shrink-0">
                <?php if (!empty($sideSysLogo)): ?>
                    <img src="<?php echo htmlspecialchars($sideSysLogo); ?>" class="h-full w-full rounded-full object-cover" alt="Logo">
                <?php else: ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#10B981]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <?php endif; ?>
            </div>
            <div>
                <p class="text-sm font-bold text-slate-900 leading-tight"><?php echo htmlspecialchars($sideSysShortName); ?></p>
                <p class="text-[10px] text-emerald-700 font-semibold leading-none mt-0.5">Supervisor Portal</p>
            </div>
        </div>
        <button onclick="toggleMobileSidebar()" class="p-2 text-slate-400 hover:text-slate-700 rounded-lg hover:bg-slate-200/80 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Mobile Nav Links -->
    <nav class="flex-1 px-3 py-4 space-y-5 overflow-y-auto">
        <div>
            <p class="px-3 text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Core</p>
            <div class="space-y-1">
                <?php $mDash = $isPageActive('supervisor'); ?>
                <a href="<?php echo app_url('supervisor'); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition <?php echo $mDash ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/60'; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 <?php echo $mDash ? 'text-emerald-600' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M4 13h6c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v8c0 .55.45 1 1 1zm0 8h6c.55 0 1-.45 1-1v-4c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v4c0 .55.45 1 1 1zm10 0h6c.55 0 1-.45 1-1v-8c0-.55-.45-1-1-1h-6c-.55 0-1 .45-1 1v8c0 .55.45 1 1 1zm0-18v4c0 .55.45 1 1 1h6c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1h-6c-.55 0-1 .45-1 1z"/>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <?php $mRep = $isPageActive(['reports', 'view_report']); ?>
                <a href="<?php echo app_url('supervisor/reports'); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition <?php echo $mRep ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/60'; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 <?php echo $mRep ? 'text-emerald-600' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M4 19h16a1 1 0 0 0 1-1v-1a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1zM6 13h2a1 1 0 0 0 1-1V9a1 1 0 0 0-1-1H6a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1zm5 0h2a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1h-2a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1zm5 0h2a1 1 0 0 0 1-1v-4a1 1 0 0 0-1-1h-2a1 1 0 0 0-1 1v4a1 1 0 0 0 1 1z"/>
                    </svg>
                    <span>Reports</span>
                </a>
            </div>
        </div>

        <div>
            <p class="px-3 text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Operations &amp; GIS</p>
            <div class="space-y-1">
                <?php $mGis = $isPageActive('gis'); ?>
                <a href="<?php echo app_url('supervisor/gis'); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition <?php echo $mGis ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/60'; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 <?php echo $mGis ? 'text-emerald-600' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M20.5 3l-.16.03L15 5.1 9 3 3.36 4.9c-.21.07-.36.25-.36.48V20.5c0 .28.22.5.5.5l.16-.03L9 18.9l6 2.1 5.64-1.9c.21-.07.36-.25.36-.48V3.5c0-.28-.22-.5-.5-.5zM15 19l-6-2.11V5l6 2.11V19z"/>
                    </svg>
                    <span>GIS Monitor</span>
                </a>

                <?php $mAna = $isPageActive('analytics'); ?>
                <a href="<?php echo app_url('supervisor/analytics'); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition <?php echo $mAna ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/60'; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 <?php echo $mAna ? 'text-emerald-600' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
                    </svg>
                    <span>Analytics</span>
                </a>

                <?php $mSched = $isPageActive('schedule'); ?>
                <a href="<?php echo app_url('supervisor/schedule'); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition <?php echo $mSched ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/60'; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 <?php echo $mSched ? 'text-emerald-600' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20a2 2 0 0 0 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z"/>
                    </svg>
                    <span>Schedule</span>
                </a>
            </div>
        </div>

        <div>
            <p class="px-3 text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Communications</p>
            <div class="space-y-1">
                <?php $mAnn = $isPageActive('announcements'); ?>
                <a href="<?php echo app_url('supervisor/announcements'); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition <?php echo $mAnn ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/60'; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 <?php echo $mAnn ? 'text-emerald-600' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M4 9v6h4l5 5V4L8 9H4zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM15 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/>
                    </svg>
                    <span>Bulletins</span>
                </a>

                <?php $mNot = $isPageActive('notifications'); ?>
                <a href="<?php echo app_url('supervisor/notifications'); ?>" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold transition <?php echo $mNot ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/60'; ?>">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 <?php echo $mNot ? 'text-emerald-600' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.63-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.64 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/>
                        </svg>
                        <span>Notifications</span>
                    </div>
                    <?php if ($sideUnreadCount > 0): ?>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500 text-white font-mono"><?php echo $sideUnreadCount; ?></span>
                    <?php endif; ?>
                </a>
            </div>
        </div>

        <div>
            <p class="px-3 text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Account</p>
            <div class="space-y-1">
                <?php $mProf = $isPageActive('profile'); ?>
                <a href="<?php echo app_url('supervisor/profile'); ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition <?php echo $mProf ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/60'; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 <?php echo $mProf ? 'text-emerald-600' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                    <span>My Profile</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Mobile Footer Logout -->
    <div class="p-4 border-t border-slate-100 bg-slate-50">
        <a href="<?php echo app_url('index.php?url=auth/logout'); ?>" class="flex items-center justify-center gap-2 w-full py-2.5 px-4 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-semibold border border-rose-200 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            <span>Log Out</span>
        </a>
    </div>
</div>

<!-- Desktop Persistent Sidebar -->
<aside id="desktopSidebar" class="hidden lg:flex flex-col <?php echo $isCollapsed ? 'w-20' : 'w-64'; ?> bg-[#FFFFFF] text-slate-800 h-screen sticky top-0 border-r border-slate-200 flex-shrink-0 transition-all duration-300 z-40 shadow-xs">
    
    <!-- Branding Header -->
    <div class="h-16 flex items-center <?php echo $isCollapsed ? 'justify-center px-0' : 'justify-between px-5'; ?> border-b border-slate-100 bg-[#FFFFFF]">
        <a href="<?php echo app_url('supervisor'); ?>" class="flex items-center gap-3 overflow-hidden group">
            <div class="h-9 w-9 rounded-full flex items-center justify-center overflow-hidden shrink-0 group-hover:scale-105 transition">
                <?php if (!empty($sideSysLogo)): ?>
                    <img src="<?php echo htmlspecialchars($sideSysLogo); ?>" class="h-full w-full rounded-full object-cover" alt="Logo">
                <?php else: ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#10B981]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <?php endif; ?>
            </div>
            <div class="sidebar-text <?php echo $isCollapsed ? 'hidden' : ''; ?> min-w-0">
                <p class="text-sm font-bold text-slate-900 leading-tight truncate"><?php echo htmlspecialchars($sideSysShortName); ?></p>
                <p class="text-[10px] text-emerald-700 font-semibold leading-none truncate mt-0.5">Supervisor Portal</p>
            </div>
        </a>
    </div>

    <!-- Navigation Scroll Area -->
    <nav class="flex-1 px-3 py-4 space-y-5 overflow-y-auto custom-scrollbar">
        
        <!-- CORE -->
        <div>
            <p class="sidebar-text <?php echo $isCollapsed ? 'hidden' : ''; ?> px-3 text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Core</p>
            <div class="space-y-1">
                <!-- Dashboard -->
                <?php $dDash = $isPageActive('supervisor'); ?>
                <a href="<?php echo app_url('supervisor'); ?>" class="relative flex items-center <?php echo $isCollapsed ? 'justify-center px-0' : 'gap-3 px-3'; ?> py-2.5 rounded-xl text-xs font-semibold transition group <?php echo $dDash ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80'; ?>" title="Dashboard">
                    <?php if ($dDash): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-[#10B981] rounded-r-full shadow-xs"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $dDash ? 'text-emerald-600' : 'text-slate-400 group-hover:text-slate-600'; ?>" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M4 13h6c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v8c0 .55.45 1 1 1zm0 8h6c.55 0 1-.45 1-1v-4c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v4c0 .55.45 1 1 1zm10 0h6c.55 0 1-.45 1-1v-8c0-.55-.45-1-1-1h-6c-.55 0-1 .45-1 1v8c0 .55.45 1 1 1zm0-18v4c0 .55.45 1 1 1h6c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1h-6c-.55 0-1 .45-1 1z"/>
                    </svg>
                    <span class="sidebar-text <?php echo $isCollapsed ? 'hidden' : ''; ?> truncate font-bold">Dashboard</span>
                </a>

                <!-- Reports -->
                <?php $dRep = $isPageActive(['reports', 'view_report']); ?>
                <a href="<?php echo app_url('supervisor/reports'); ?>" class="relative flex items-center <?php echo $isCollapsed ? 'justify-center px-0' : 'gap-3 px-3'; ?> py-2.5 rounded-xl text-xs font-semibold transition group <?php echo $dRep ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80'; ?>" title="Reports">
                    <?php if ($dRep): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-[#10B981] rounded-r-full shadow-xs"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $dRep ? 'text-emerald-600' : 'text-slate-400 group-hover:text-slate-600'; ?>" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M4 19h16a1 1 0 0 0 1-1v-1a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1zM6 13h2a1 1 0 0 0 1-1V9a1 1 0 0 0-1-1H6a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1zm5 0h2a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1h-2a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1zm5 0h2a1 1 0 0 0 1-1v-4a1 1 0 0 0-1-1h-2a1 1 0 0 0-1 1v4a1 1 0 0 0 1 1z"/>
                    </svg>
                    <span class="sidebar-text <?php echo $isCollapsed ? 'hidden' : ''; ?> truncate font-bold">Reports</span>
                </a>
            </div>
        </div>

        <!-- OPERATIONS & GIS -->
        <div>
            <p class="sidebar-text <?php echo $isCollapsed ? 'hidden' : ''; ?> px-3 text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Operations &amp; GIS</p>
            <div class="space-y-1">
                <!-- GIS Monitor -->
                <?php $dGis = $isPageActive('gis'); ?>
                <a href="<?php echo app_url('supervisor/gis'); ?>" class="relative flex items-center <?php echo $isCollapsed ? 'justify-center px-0' : 'gap-3 px-3'; ?> py-2.5 rounded-xl text-xs font-semibold transition group <?php echo $dGis ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80'; ?>" title="GIS Monitor">
                    <?php if ($dGis): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-[#10B981] rounded-r-full shadow-xs"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $dGis ? 'text-emerald-600' : 'text-slate-400 group-hover:text-slate-600'; ?>" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M20.5 3l-.16.03L15 5.1 9 3 3.36 4.9c-.21.07-.36.25-.36.48V20.5c0 .28.22.5.5.5l.16-.03L9 18.9l6 2.1 5.64-1.9c.21-.07.36-.25.36-.48V3.5c0-.28-.22-.5-.5-.5zM15 19l-6-2.11V5l6 2.11V19z"/>
                    </svg>
                    <span class="sidebar-text <?php echo $isCollapsed ? 'hidden' : ''; ?> truncate font-bold">GIS Monitor</span>
                </a>

                <!-- Analytics -->
                <?php $dAna = $isPageActive('analytics'); ?>
                <a href="<?php echo app_url('supervisor/analytics'); ?>" class="relative flex items-center <?php echo $isCollapsed ? 'justify-center px-0' : 'gap-3 px-3'; ?> py-2.5 rounded-xl text-xs font-semibold transition group <?php echo $dAna ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80'; ?>" title="Analytics">
                    <?php if ($dAna): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-[#10B981] rounded-r-full shadow-xs"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $dAna ? 'text-emerald-600' : 'text-slate-400 group-hover:text-slate-600'; ?>" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
                    </svg>
                    <span class="sidebar-text <?php echo $isCollapsed ? 'hidden' : ''; ?> truncate font-bold">Analytics</span>
                </a>

                <!-- Collection Schedule -->
                <?php $dSchedule = $isPageActive('schedule'); ?>
                <a href="<?php echo app_url('supervisor/schedule'); ?>" class="relative flex items-center <?php echo $isCollapsed ? 'justify-center px-0' : 'gap-3 px-3'; ?> py-2.5 rounded-xl text-xs font-semibold transition group <?php echo $dSchedule ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80'; ?>" title="Collection Schedule">
                    <?php if ($dSchedule): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-[#10B981] rounded-r-full shadow-xs"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $dSchedule ? 'text-emerald-600' : 'text-slate-400 group-hover:text-slate-600'; ?>" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20a2 2 0 0 0 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z"/>
                    </svg>
                    <span class="sidebar-text <?php echo $isCollapsed ? 'hidden' : ''; ?> truncate font-bold">Schedule</span>
                </a>
            </div>
        </div>

        <!-- COMMUNICATIONS -->
        <div>
            <p class="sidebar-text <?php echo $isCollapsed ? 'hidden' : ''; ?> px-3 text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Communications</p>
            <div class="space-y-1">
                <!-- Announcements -->
                <?php $dAnn = $isPageActive('announcements'); ?>
                <a href="<?php echo app_url('supervisor/announcements'); ?>" class="relative flex items-center <?php echo $isCollapsed ? 'justify-center px-0' : 'gap-3 px-3'; ?> py-2.5 rounded-xl text-xs font-semibold transition group <?php echo $dAnn ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80'; ?>" title="Bulletins & Announcements">
                    <?php if ($dAnn): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-[#10B981] rounded-r-full shadow-xs"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $dAnn ? 'text-emerald-600' : 'text-slate-400 group-hover:text-slate-600'; ?>" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M4 9v6h4l5 5V4L8 9H4zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM15 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/>
                    </svg>
                    <span class="sidebar-text <?php echo $isCollapsed ? 'hidden' : ''; ?> truncate font-bold">Bulletins</span>
                </a>

                <!-- Notifications -->
                <?php $dNot = $isPageActive('notifications'); ?>
                <a href="<?php echo app_url('supervisor/notifications'); ?>" class="relative flex items-center <?php echo $isCollapsed ? 'justify-center px-0' : 'justify-between px-3'; ?> py-2.5 rounded-xl text-xs font-semibold transition group <?php echo $dNot ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80'; ?>" title="Notifications">
                    <?php if ($dNot): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-[#10B981] rounded-r-full shadow-xs"></span>
                    <?php endif; ?>
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $dNot ? 'text-emerald-600' : 'text-slate-400 group-hover:text-slate-600'; ?>" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 22c1.1 0 2-.9 2-2h-4c0 1.1.9 2 2 2zm6-6v-5c0-3.07-1.63-5.64-4.5-6.32V4c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5v.68C7.64 5.36 6 7.92 6 11v5l-2 2v1h16v-1l-2-2z"/>
                        </svg>
                        <span class="sidebar-text <?php echo $isCollapsed ? 'hidden' : ''; ?> truncate font-bold">Notifications</span>
                    </div>
                    <?php if ($sideUnreadCount > 0): ?>
                        <span class="sidebar-text <?php echo $isCollapsed ? 'hidden' : ''; ?> px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500 text-white font-mono"><?php echo $sideUnreadCount; ?></span>
                    <?php endif; ?>
                </a>
            </div>
        </div>

        <!-- ACCOUNT -->
        <div>
            <p class="sidebar-text <?php echo $isCollapsed ? 'hidden' : ''; ?> px-3 text-[10px] font-black text-slate-400 uppercase tracking-wider mb-1.5">Account</p>
            <div class="space-y-1">
                <!-- Profile -->
                <?php $dProf = $isPageActive('profile'); ?>
                <a href="<?php echo app_url('supervisor/profile'); ?>" class="relative flex items-center <?php echo $isCollapsed ? 'justify-center px-0' : 'gap-3 px-3'; ?> py-2.5 rounded-xl text-xs font-semibold transition group <?php echo $dProf ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80'; ?>" title="My Profile">
                    <?php if ($dProf): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-5 bg-[#10B981] rounded-r-full shadow-xs"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $dProf ? 'text-emerald-600' : 'text-slate-400 group-hover:text-slate-600'; ?>" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                    <span class="sidebar-text <?php echo $isCollapsed ? 'hidden' : ''; ?> truncate font-bold">My Profile</span>
                </a>
            </div>
        </div>

    </nav>

    <!-- Desktop User Footer -->
    <div class="p-3 border-t border-slate-200">
        <div class="flex items-center <?php echo $isCollapsed ? 'justify-center p-1.5' : 'justify-between p-2.5'; ?> gap-2 bg-white border border-slate-200/90 rounded-2xl shadow-xs">
            <a href="<?php echo app_url('supervisor/profile'); ?>" class="flex items-center gap-2.5 min-w-0 group" title="View Profile">
                <div class="h-8 w-8 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs font-bold border border-emerald-200 shrink-0 shadow-2xs">
                    <?php echo strtoupper(substr($_SESSION['user_name'] ?? 'S', 0, 1)); ?>
                </div>
                <div class="sidebar-text <?php echo $isCollapsed ? 'hidden' : ''; ?> min-w-0">
                    <p class="text-xs font-bold text-slate-900 truncate leading-tight group-hover:text-emerald-700 transition"><?php echo htmlspecialchars($fullName); ?></p>
                    <p class="text-[10px] font-semibold text-emerald-700 truncate leading-none mt-0.5">Supervisor</p>
                </div>
            </a>
            
            <a href="<?php echo app_url('index.php?url=auth/logout'); ?>" class="sidebar-text <?php echo $isCollapsed ? 'hidden' : ''; ?> p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Log Out">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
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