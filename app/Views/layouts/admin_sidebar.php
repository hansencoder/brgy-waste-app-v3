<?php
// Get current user info from session
$fullName = $_SESSION['user_name'] ?? 'Secretary Rose';
$firstName = isset($_SESSION['user_name']) ? explode(' ', trim($_SESSION['user_name']))[0] : 'Admin';
$role = $_SESSION['user_role'] ?? 'Admin User';

// Determine active page for highlighting
$currentUri = $_SERVER['REQUEST_URI'];
$isActive = function($path) use ($currentUri) {
    if ($path === '/admin') {
        return (strpos($currentUri, '/admin') !== false && strpos($currentUri, '/admin/') === false) || 
               strpos($currentUri, '/admin/dashboard') !== false;
    }
    return strpos($currentUri, $path) !== false;
};

// Fetch pending reports count for badge
$db = new Database();
$db->query("SELECT COUNT(*) as count FROM reports r JOIN report_statuses rs ON r.status_id = rs.status_id WHERE rs.status_name = 'Pending'");
$pendingCount = (int)($db->single()['count'] ?? 0);

// Check if currently on Reports page
$isReportsPage = $isActive('/admin/reports') || strpos($currentUri, '/admin/viewReport') !== false;

// If visiting the reports page, store that current pending reports have been viewed
if ($isReportsPage) {
    $_SESSION['reports_seen_count'] = $pendingCount;
}

$seenCount = (int)($_SESSION['reports_seen_count'] ?? 0);
$showPendingBadge = ($pendingCount > 0) && ($pendingCount > $seenCount) && !$isReportsPage;

// Read server-side cookie for initial collapsed state (0ms latency, zero flicker)
$isCollapsedCookie = (isset($_COOKIE['admin_sidebar_collapsed']) && $_COOKIE['admin_sidebar_collapsed'] === '1');
$initialCollapsedClass = $isCollapsedCookie ? 'is-collapsed' : '';

// Fetch system branding & barangay customization
try {
    $db->query("SELECT system_name, system_short_name, system_logo, barangay_name, municipality, province FROM barangays LIMIT 1");
    $brgyBranding = $db->single();
} catch (Exception $e) {
    $brgyBranding = null;
}
$sysShortName = !empty($brgyBranding['system_short_name']) ? $brgyBranding['system_short_name'] : 'WasteWatch';
$brgyName = !empty($brgyBranding['barangay_name']) ? $brgyBranding['barangay_name'] : 'Dulong Bayan';
$brgyMuni = !empty($brgyBranding['municipality']) ? $brgyBranding['municipality'] : 'Talavera';
$brgyProv = !empty($brgyBranding['province']) ? $brgyBranding['province'] : 'Nueva Ecija';
$sysMotto = "Brgy. {$brgyName}, {$brgyMuni}";
$sysLogo = format_asset_url($brgyBranding['system_logo'] ?? '');
?>

<style>
    /* Collapsible Sidebar & Mobile Drawer Styles */
    /* Mobile backdrop overlay */
    #mobileSidebarBackdrop {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.55);
        z-index: 45;
        backdrop-filter: blur(2px);
        -webkit-backdrop-filter: blur(2px);
        transition: opacity 0.25s ease;
    }
    #mobileSidebarBackdrop.open { display: block; }
    /* Mobile sidebar drawer */
    #adminSidebarDrawer.mobile-open {
        display: flex !important;
        transform: translateX(0) !important;
    }
    /* Collapsible Sidebar Styles */
    #adminSidebarDrawer {
        transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), padding 0.3s ease;
    }
    #adminSidebarDrawer.is-collapsed {
        width: 5rem !important; /* w-20 = 80px */
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
    }
    #adminSidebarDrawer.is-collapsed .sidebar-text,
    #adminSidebarDrawer.is-collapsed .sidebar-brand-text,
    #adminSidebarDrawer.is-collapsed .sidebar-section-title,
    #adminSidebarDrawer.is-collapsed .sidebar-user-details {
        display: none !important;
    }
    #adminSidebarDrawer.is-collapsed .sidebar-link {
        justify-content: center !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
    #adminSidebarDrawer.is-collapsed .sidebar-badge {
        position: absolute;
        top: 6px;
        right: 6px;
        padding: 2px 5px;
        font-size: 9px;
    }
    #adminSidebarDrawer.is-collapsed .sidebar-user-card {
        justify-content: center !important;
        padding: 0.5rem !important;
    }
</style>

<!-- Mobile Backdrop Overlay -->
<div id="mobileSidebarBackdrop" onclick="closeMobileSidebar()"></div>

<!-- Main Admin Sidebar Component -->
<aside id="adminSidebarDrawer" class="hidden lg:flex fixed lg:relative inset-y-0 left-0 w-72 lg:w-72 bg-[#FFFFFF] text-slate-800 px-4 py-5 lg:sticky lg:top-0 lg:h-screen flex-col flex-shrink-0 border-r border-slate-200 z-50 lg:z-40 transition-transform duration-300 lg:transform-none shadow-xs <?php echo $initialCollapsedClass; ?>">
    <!-- Immediate Inline Execution to prevent layout flicker -->
    <script>
        (function() {
            var savedState = localStorage.getItem('admin_sidebar_collapsed');
            var sidebar = document.getElementById('adminSidebarDrawer');
            if (savedState === 'true' && sidebar && !sidebar.classList.contains('is-collapsed')) {
                sidebar.classList.add('is-collapsed');
            } else if (savedState === 'false' && sidebar && sidebar.classList.contains('is-collapsed')) {
                sidebar.classList.remove('is-collapsed');
            }
        })();
    </script>
    
    <!-- Brand Header -->
    <div class="flex items-center px-2 py-1 flex-shrink-0">
        <a href="<?php echo app_url('admin'); ?>" class="flex items-center gap-3 min-w-0">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full overflow-hidden">
                <?php if (!empty($sysLogo)): ?>
                    <img src="<?php echo htmlspecialchars($sysLogo); ?>" class="h-full w-full rounded-full object-cover" alt="Logo">
                <?php else: ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#10B981]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <?php endif; ?>
            </div>
            <div class="sidebar-brand-text min-w-0">
                <h1 class="text-xl font-extrabold text-slate-900 tracking-tight leading-snug truncate"><?php echo htmlspecialchars($sysShortName); ?></h1>
                <p class="text-xs font-semibold text-emerald-700 leading-none truncate mt-0.5"><?php echo htmlspecialchars($sysMotto); ?></p>
            </div>
        </a>
    </div>

    <!-- Navigation Scroll Area -->
    <nav class="mt-6 flex-1 overflow-y-auto space-y-6 pr-1 custom-scrollbar">
        
        <!-- Section: CORE -->
        <div>
            <p class="sidebar-section-title text-[10px] font-black uppercase tracking-[0.25em] text-slate-400 mb-2 px-3">CORE</p>
            <div class="space-y-1">
                <!-- Dashboard -->
                <?php $activeDash = $isActive('/admin') && !$isActive('/admin/reports') && !$isActive('/admin/accounts') && !$isActive('/admin/gis') && !$isActive('/admin/schedule') && !$isActive('/admin/announcements') && !$isActive('/admin/report_summaries') && !$isActive('/admin/settings') && !$isActive('/admin/auditLogs') && !$isActive('/admin/profile') && !$isActive('/admin/createStaff'); ?>
                <a href="<?php echo app_url('admin'); ?>" title="Dashboard" class="sidebar-link relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all <?php echo $activeDash ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80'; ?>">
                    <?php if ($activeDash): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-xs"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $activeDash ? 'text-emerald-600' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M4 13h6c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v8c0 .55.45 1 1 1zm0 8h6c.55 0 1-.45 1-1v-4c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v4c0 .55.45 1 1 1zm10 0h6c.55 0 1-.45 1-1v-8c0-.55-.45-1-1-1h-6c-.55 0-1 .45-1 1v8c0 .55.45 1 1 1zm0-18v4c0 .55.45 1 1 1h6c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1h-6c-.55 0-1 .45-1 1z"/>
                    </svg>
                    <span class="sidebar-text">Dashboard</span>
                </a>

                <!-- Reports -->
                <?php if (has_permission('view_reports')): ?>
                <?php $activeReports = $isReportsPage; ?>
                <a href="<?php echo app_url('admin/reports'); ?>" title="Reports" class="sidebar-link relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all <?php echo $activeReports ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80'; ?>">
                    <?php if ($activeReports): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-xs"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $activeReports ? 'text-emerald-600' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M4 19h16a1 1 0 0 0 1-1v-1a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1zM6 13h2a1 1 0 0 0 1-1V9a1 1 0 0 0-1-1H6a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1zm5 0h2a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1h-2a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1zm5 0h2a1 1 0 0 0 1-1v-4a1 1 0 0 0-1-1h-2a1 1 0 0 0-1 1v4a1 1 0 0 0 1 1z"/>
                    </svg>
                    <span class="sidebar-text">Reports</span>
                    <?php if ($showPendingBadge): ?>
                        <span class="sidebar-badge ml-auto rounded-full bg-[#FF4D4D] text-white text-[10px] font-black px-2 py-0.5 shadow-sm"><?php echo $pendingCount; ?></span>
                    <?php endif; ?>
                </a>
                <?php endif; ?>

                <!-- User Mgmt -->
                <?php if (has_permission('view_residents') || has_permission('manage_residents')): ?>
                <?php $activeUsers = $isActive('/admin/accounts'); ?>
                <a href="<?php echo app_url('admin/accounts'); ?>" title="User Management" class="sidebar-link relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all <?php echo $activeUsers ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80'; ?>">
                    <?php if ($activeUsers): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-xs"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $activeUsers ? 'text-emerald-600' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                    </svg>
                    <span class="sidebar-text">User Management</span>
                </a>
                <?php endif; ?>

                <!-- Settings -->
                <?php if (has_permission('view_settings') || has_permission('manage_settings')): ?>
                <?php $activeSettings = $isActive('/settings'); ?>
                <a href="<?php echo app_url('settings/barangay'); ?>" title="Settings" class="sidebar-link relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all <?php echo $activeSettings ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80'; ?>">
                    <?php if ($activeSettings): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-xs"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $activeSettings ? 'text-emerald-600' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58c.18-.14.23-.41.12-.61l-1.92-3.32c-.12-.22-.37-.29-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54c-.04-.24-.24-.41-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58c-.18.14-.23.41-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/>
                    </svg>
                    <span class="sidebar-text">Settings</span>
                </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Section: MANAGEMENT -->
        <div>
            <p class="sidebar-section-title text-[10px] font-black uppercase tracking-[0.25em] text-slate-400 mb-2 px-3">MANAGEMENT</p>
            <div class="space-y-1">
                <!-- Create Staff -->
                <?php if (has_permission('manage_residents')): ?>
                <?php $activeStaff = $isActive('/admin/createStaff'); ?>
                <a href="<?php echo app_url('admin/createStaff'); ?>" title="Create Staff" class="sidebar-link relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all <?php echo $activeStaff ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80'; ?>">
                    <?php if ($activeStaff): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-xs"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $activeStaff ? 'text-emerald-600' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V7H4v3H1v2h3v3h2v-3h3v-2H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                    <span class="sidebar-text">Create Staff</span>
                </a>
                <?php endif; ?>

                <!-- GIS Monitor -->
                <?php if (has_permission('view_reports')): ?>
                <?php $activeGis = $isActive('/admin/gis'); ?>
                <a href="<?php echo app_url('admin/gis'); ?>" title="GIS Monitor" class="sidebar-link relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all <?php echo $activeGis ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80'; ?>">
                    <?php if ($activeGis): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-xs"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $activeGis ? 'text-emerald-600' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M20.5 3l-.16.03L15 5.1 9 3 3.36 4.9c-.21.07-.36.25-.36.48V20.5c0 .28.22.5.5.5l.16-.03L9 18.9l6 2.1 5.64-1.9c.21-.07.36-.25.36-.48V3.5c0-.28-.22-.5-.5-.5zM15 19l-6-2.11V5l6 2.11V19z"/>
                    </svg>
                    <span class="sidebar-text">GIS Monitor</span>
                </a>
                <?php endif; ?>

                <!-- Schedule -->
                <?php if (has_permission('view_schedules')): ?>
                <?php $activeSched = $isActive('/admin/schedule') || strpos($currentUri, '/admin/editSchedule') !== false; ?>
                <a href="<?php echo app_url('admin/schedule'); ?>" title="Schedule" class="sidebar-link relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all <?php echo $activeSched ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80'; ?>">
                    <?php if ($activeSched): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-xs"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $activeSched ? 'text-emerald-600' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20a2 2 0 0 0 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z"/>
                    </svg>
                    <span class="sidebar-text">Schedule</span>
                </a>
                <?php endif; ?>

                <!-- Announcements -->
                <?php if (has_permission('view_announcements')): ?>
                <?php $activeAnnounce = $isActive('/admin/announcements'); ?>
                <a href="<?php echo app_url('admin/announcements'); ?>" title="Announcements" class="sidebar-link relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all <?php echo $activeAnnounce ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80'; ?>">
                    <?php if ($activeAnnounce): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-xs"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $activeAnnounce ? 'text-emerald-600' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M4 9v6h4l5 5V4L8 9H4zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM15 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/>
                    </svg>
                    <span class="sidebar-text">Announcements</span>
                </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Section: SYSTEM -->
        <div>
            <p class="sidebar-section-title text-[10px] font-black uppercase tracking-[0.25em] text-slate-400 mb-2 px-3">SYSTEM</p>
            <div class="space-y-1">
                <!-- Analytics -->
                <?php if (has_permission('view_analytics')): ?>
                <?php $activeAnalytics = $isActive('/admin/report_summaries'); ?>
                <a href="<?php echo app_url('admin/report_summaries'); ?>" title="Analytics" class="sidebar-link relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all <?php echo $activeAnalytics ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80'; ?>">
                    <?php if ($activeAnalytics): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-xs"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $activeAnalytics ? 'text-emerald-600' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/>
                    </svg>
                    <span class="sidebar-text">Analytics</span>
                </a>
                <?php endif; ?>

                <!-- Audit Logs -->
                <?php if (has_permission('view_audit_logs')): ?>
                <?php $activeAudit = $isActive('/admin/auditLogs'); ?>
                <a href="<?php echo app_url('admin/auditLogs'); ?>" title="Audit Logs" class="sidebar-link relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all <?php echo $activeAudit ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80'; ?>">
                    <?php if ($activeAudit): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-xs"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $activeAudit ? 'text-emerald-600' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                    </svg>
                    <span class="sidebar-text">Audit Logs</span>
                </a>
                <?php endif; ?>

                <!-- Profile -->
                <?php $activeProfile = $isActive('/admin/profile'); ?>
                <a href="<?php echo app_url('admin/profile'); ?>" title="Profile" class="sidebar-link relative flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-bold transition-all <?php echo $activeProfile ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80'; ?>">
                    <?php if ($activeProfile): ?>
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-xs"></span>
                    <?php endif; ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $activeProfile ? 'text-emerald-600' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                    <span class="sidebar-text">Profile</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Bottom User Profile Card -->
    <div class="mt-4 pt-3 flex-shrink-0">
        <div class="sidebar-user-card bg-white border border-slate-200/90 p-3 rounded-2xl flex items-center justify-between shadow-xs">
            <div class="flex items-center gap-3 min-w-0">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-100 border border-emerald-200 text-emerald-800 font-bold shadow-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </div>
                <div class="sidebar-user-details min-w-0">
                    <p class="font-bold text-sm text-slate-900 leading-tight truncate"><?php echo htmlspecialchars($fullName); ?></p>
                    <p class="text-[11px] font-semibold text-emerald-700 leading-tight truncate mt-0.5"><?php echo ucfirst(htmlspecialchars($role)); ?></p>
                </div>
            </div>
            <a href="<?php echo app_url('auth/logout'); ?>" title="Logout" class="sidebar-user-details p-2 rounded-xl text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition border border-transparent hover:border-rose-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            </a>
        </div>
    </div>
</aside>

<script>
    // --- Desktop Sidebar Collapse/Expand Handler ---
    function toggleDesktopSidebar() {
        const sidebar = document.getElementById('adminSidebarDrawer');
        const icon = document.getElementById('collapseIcon');
        const btn = document.getElementById('sidebarCollapseBtn');
        if (!sidebar) return;

        const isCollapsed = sidebar.classList.toggle('is-collapsed');
        
        // Save state in BOTH Cookie AND LocalStorage for 100% consistency across all pages
        document.cookie = "admin_sidebar_collapsed=" + (isCollapsed ? "1" : "0") + "; path=/; max-age=31536000";
        localStorage.setItem('admin_sidebar_collapsed', isCollapsed ? 'true' : 'false');

        if (icon) {
            icon.style.transform = isCollapsed ? 'rotate(180deg)' : 'rotate(0deg)';
        }
        if (btn) {
            btn.title = isCollapsed ? 'Expand Sidebar' : 'Collapse Sidebar';
        }
    }

    // --- Mobile Sidebar Drawer Toggle ---
    function toggleMobileSidebar() {
        const sidebar = document.getElementById('adminSidebarDrawer');
        const backdrop = document.getElementById('mobileSidebarBackdrop');
        if (!sidebar) return;

        const isOpen = sidebar.classList.contains('mobile-open');
        if (isOpen) {
            closeMobileSidebar();
        } else {
            openMobileSidebar();
        }
    }

    function openMobileSidebar() {
        const sidebar = document.getElementById('adminSidebarDrawer');
        const backdrop = document.getElementById('mobileSidebarBackdrop');
        if (!sidebar) return;
        sidebar.classList.remove('hidden');
        sidebar.classList.add('mobile-open');
        if (backdrop) backdrop.classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function closeMobileSidebar() {
        const sidebar = document.getElementById('adminSidebarDrawer');
        const backdrop = document.getElementById('mobileSidebarBackdrop');
        if (!sidebar) return;
        sidebar.classList.remove('mobile-open');
        if (backdrop) backdrop.classList.remove('open');
        document.body.style.overflow = '';
        // Delay hiding so transition plays
        setTimeout(() => {
            if (!sidebar.classList.contains('mobile-open')) {
                sidebar.classList.add('hidden');
            }
        }, 280);
    }

    // --- Sync Icon Orientation on Load ---
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('adminSidebarDrawer');
        const icon = document.getElementById('collapseIcon');
        const btn = document.getElementById('sidebarCollapseBtn');

        if (sidebar && sidebar.classList.contains('is-collapsed')) {
            if (icon) icon.style.transform = 'rotate(180deg)';
            if (btn) btn.title = 'Expand Sidebar';
        }
    });
</script>