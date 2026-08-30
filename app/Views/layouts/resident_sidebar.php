<?php
// Get current user info from session
$fullName = $_SESSION['user_name'] ?? 'Resident';
$firstName = isset($_SESSION['user_name']) ? explode(' ', trim($_SESSION['user_name']))[0] : 'Resident';
$purok = $_SESSION['user_purok'] ?? 'Barangay Dulong Bayan';
$role = 'Resident';

// Determine active page for highlighting
$currentUri = $_SERVER['REQUEST_URI'];
$isActive = function($path) use ($currentUri) {
    if ($path === '/resident') {
        return (strpos($currentUri, '/resident') !== false && strpos($currentUri, '/resident/') === false) || 
               strpos($currentUri, '/resident/dashboard') !== false;
    }
    return strpos($currentUri, $path) !== false;
};

// Read server-side cookie for initial collapsed state
$isCollapsedCookie = (isset($_COOKIE['resident_sidebar_collapsed']) && $_COOKIE['resident_sidebar_collapsed'] === '1');
$initialCollapsedClass = $isCollapsedCookie ? 'is-collapsed' : '';

// Fetch system branding & barangay customization
try {
    $db = new Database();
    $db->query("SELECT system_name, system_short_name, system_logo, barangay_name, municipality, province FROM barangays LIMIT 1");
    $brgyBranding = $db->single();
} catch (Exception $e) {
    $brgyBranding = null;
}
$sysShortName = !empty($brgyBranding['system_short_name']) ? $brgyBranding['system_short_name'] : 'WasteWatch';
$brgyName = !empty($brgyBranding['barangay_name']) ? $brgyBranding['barangay_name'] : 'Dulong Bayan';
$brgyMuni = !empty($brgyBranding['municipality']) ? $brgyBranding['municipality'] : 'Talavera';
$sysMotto = "Brgy. {$brgyName}, {$brgyMuni}";
$sysLogo = format_asset_url($brgyBranding['system_logo'] ?? '');
?>

<style>
    /* Collapsible Sidebar & Mobile Drawer Styles */
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

    #residentSidebarDrawer.mobile-open {
        display: flex !important;
        transform: translateX(0) !important;
    }

    #residentSidebarDrawer {
        transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), padding 0.3s ease;
    }
    #residentSidebarDrawer.is-collapsed {
        width: 5rem !important; /* w-20 = 80px */
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
    }
    #residentSidebarDrawer.is-collapsed .sidebar-text,
    #residentSidebarDrawer.is-collapsed .sidebar-brand-text,
    #residentSidebarDrawer.is-collapsed .sidebar-section-title,
    #residentSidebarDrawer.is-collapsed .sidebar-user-details {
        display: none !important;
    }
    #residentSidebarDrawer.is-collapsed .sidebar-link {
        justify-content: center !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
    #residentSidebarDrawer.is-collapsed .sidebar-badge {
        position: absolute;
        top: 6px;
        right: 6px;
        padding: 2px 5px;
        font-size: 9px;
    }

    /* Floating Hover Tooltips when Collapsed */
    .sidebar-tooltip {
        display: none;
        position: fixed;
        left: 5.5rem;
        background: #0F172A;
        color: #ffffff;
        padding: 0.35rem 0.75rem;
        border-radius: 0.5rem;
        font-size: 0.75rem;
        font-weight: 700;
        white-space: nowrap;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.1);
        z-index: 9999;
        pointer-events: none;
    }
    #residentSidebarDrawer.is-collapsed .sidebar-link:hover .sidebar-tooltip {
        display: block;
    }
</style>

<!-- Mobile Backdrop Shadow Overlay -->
<div id="mobileSidebarBackdrop" onclick="toggleMobileSidebar()"></div>

<!-- Main Resident Sidebar Component -->
<aside id="residentSidebarDrawer" class="<?php echo $initialCollapsedClass; ?> fixed inset-y-0 left-0 z-50 flex h-full w-64 -translate-x-full transform flex-col bg-[#FFFFFF] text-slate-800 transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 border-r border-slate-200 shadow-xl lg:shadow-none">
    
    <!-- Brand Header -->
    <div class="flex h-16 shrink-0 items-center gap-3 px-4 border-b border-slate-100">
        <a href="<?php echo app_url('resident'); ?>" class="flex items-center gap-3 min-w-0 flex-1 group">
            <div class="h-9 w-9 rounded-full flex items-center justify-center overflow-hidden shrink-0 group-hover:scale-105 transition">
                <?php if (!empty($sysLogo)): ?>
                    <img src="<?php echo htmlspecialchars($sysLogo); ?>" class="h-full w-full rounded-full object-cover" alt="Logo">
                <?php else: ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#10B981]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <?php endif; ?>
            </div>
            <div class="sidebar-brand-text min-w-0">
                <p class="text-sm font-extrabold text-slate-900 leading-tight tracking-tight truncate"><?php echo htmlspecialchars($sysShortName); ?></p>
                <p class="text-[11px] font-semibold text-emerald-700 leading-none truncate mt-0.5">Resident Portal</p>
            </div>
        </a>

        <!-- Mobile Close Button -->
        <button type="button" onclick="toggleMobileSidebar()" class="lg:hidden p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    <!-- Navigation Menu Items -->
    <div class="flex-1 overflow-y-auto px-3 py-4 space-y-6 scrollbar-none">
        
        <!-- SECTION: CORE -->
        <div class="space-y-1">
            <p class="sidebar-section-title px-3 text-[10px] font-black uppercase tracking-wider text-slate-400">Core</p>

            <!-- Dashboard Link -->
            <?php $activeDash = $isActive('/resident') && !$isActive('/resident/submit') && !$isActive('/resident/my_report') && !$isActive('/resident/profile') && !$isActive('/resident/announcements') && !$isActive('/resident/collection_schedule'); ?>
            <a href="<?php echo app_url('resident'); ?>" class="sidebar-link relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition <?php echo $activeDash ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80'; ?>">
                <?php if ($activeDash): ?>
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-xs"></span>
                <?php endif; ?>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $activeDash ? 'text-emerald-600' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M4 13h6c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v8c0 .55.45 1 1 1zm0 8h6c.55 0 1-.45 1-1v-4c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v4c0 .55.45 1 1 1zm10 0h6c.55 0 1-.45 1-1v-8c0-.55-.45-1-1-1h-6c-.55 0-1 .45-1 1v8c0 .55.45 1 1 1zm0-18v4c0 .55.45 1 1 1h6c.55 0 1-.45 1-1V4c0-.55-.45-1-1-1h-6c-.55 0-1 .45-1 1z"/>
                </svg>
                <span class="sidebar-text">Dashboard</span>
                <span class="sidebar-tooltip">Dashboard</span>
            </a>

            <!-- Submit Report Link -->
            <?php $activeSubmit = $isActive('/resident/submit'); ?>
            <a href="<?php echo app_url('resident/submit'); ?>" class="sidebar-link relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition <?php echo $activeSubmit ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80'; ?>">
                <?php if ($activeSubmit): ?>
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-xs"></span>
                <?php endif; ?>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $activeSubmit ? 'text-emerald-600' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm5 11h-4v4h-2v-4H7v-2h4V7h2v4h4v2z"/>
                </svg>
                <span class="sidebar-text">Submit Report</span>
                <span class="sidebar-tooltip">Submit Report</span>
            </a>

            <!-- My Reports Link -->
            <?php $activeMyReports = $isActive('/resident/my_report') || strpos($currentUri, '/resident/viewReport') !== false; ?>
            <a href="<?php echo app_url('resident/my_report'); ?>" class="sidebar-link relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition <?php echo $activeMyReports ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80'; ?>">
                <?php if ($activeMyReports): ?>
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-xs"></span>
                <?php endif; ?>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $activeMyReports ? 'text-emerald-600' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                </svg>
                <span class="sidebar-text">My Reports</span>
                <span class="sidebar-tooltip">My Reports</span>
            </a>
        </div>

        <!-- SECTION: SERVICES -->
        <div class="space-y-1">
            <p class="sidebar-section-title px-3 text-[10px] font-black uppercase tracking-wider text-slate-400">Services</p>

            <!-- Collection Schedule Link -->
            <?php $activeSched = $isActive('/resident/collection_schedule'); ?>
            <a href="<?php echo app_url('resident/collection_schedule'); ?>" class="sidebar-link relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition <?php echo $activeSched ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80'; ?>">
                <?php if ($activeSched): ?>
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-xs"></span>
                <?php endif; ?>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $activeSched ? 'text-emerald-600' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20a2 2 0 0 0 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z"/>
                </svg>
                <span class="sidebar-text">Collection Schedule</span>
                <span class="sidebar-tooltip">Collection Schedule</span>
            </a>

            <!-- Announcements Link -->
            <?php $activeAnnounce = $isActive('/resident/announcements'); ?>
            <a href="<?php echo app_url('resident/announcements'); ?>" class="sidebar-link relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition <?php echo $activeAnnounce ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80'; ?>">
                <?php if ($activeAnnounce): ?>
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-xs"></span>
                <?php endif; ?>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $activeAnnounce ? 'text-emerald-600' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M4 9v6h4l5 5V4L8 9H4zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM15 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"/>
                </svg>
                <span class="sidebar-text">Announcements</span>
                <span class="sidebar-tooltip">Announcements</span>
            </a>
        </div>

        <!-- SECTION: ACCOUNT -->
        <div class="space-y-1">
            <p class="sidebar-section-title px-3 text-[10px] font-black uppercase tracking-wider text-slate-400">Account</p>

            <!-- Profile Link -->
            <?php $activeProfile = $isActive('/resident/profile'); ?>
            <a href="<?php echo app_url('resident/profile'); ?>" class="sidebar-link relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition <?php echo $activeProfile ? 'bg-emerald-50 text-emerald-800 border border-emerald-200 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80'; ?>">
                <?php if ($activeProfile): ?>
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full shadow-xs"></span>
                <?php endif; ?>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $activeProfile ? 'text-emerald-600' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
                <span class="sidebar-text">Resident Profile</span>
                <span class="sidebar-tooltip">Resident Profile</span>
            </a>
        </div>

    </div>

    <!-- Bottom User Profile Card & Logout -->
    <div class="shrink-0 p-3 border-t border-slate-100">
        <div class="flex items-center justify-between p-2.5 rounded-2xl bg-white border border-slate-200/90 shadow-xs">
            <a href="<?php echo app_url('resident/profile'); ?>" class="flex items-center gap-2.5 min-w-0 flex-1 group">
                <div class="h-8 w-8 rounded-full bg-emerald-100 text-emerald-800 flex items-center justify-center text-xs font-bold border border-emerald-200 shrink-0">
                    <?php echo strtoupper(substr($firstName, 0, 1)); ?>
                </div>
                <div class="sidebar-user-details min-w-0">
                    <p class="text-xs font-extrabold text-slate-900 truncate"><?php echo htmlspecialchars($fullName); ?></p>
                    <p class="text-[10px] font-semibold text-emerald-700 truncate"><?php echo htmlspecialchars($purok); ?></p>
                </div>
            </a>
            <a href="<?php echo app_url('auth/logout'); ?>" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Sign Out">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            </a>
        </div>
    </div>

</aside>

<script>
    // Desktop Sidebar Collapse Toggle
    function toggleDesktopSidebar() {
        const sidebar = document.getElementById('residentSidebarDrawer');
        if (!sidebar) return;
        const isCollapsed = sidebar.classList.toggle('is-collapsed');
        // Persist state in cookie for 30 days
        document.cookie = `resident_sidebar_collapsed=${isCollapsed ? '1' : '0'}; path=/; max-age=${60 * 60 * 24 * 30}`;
    }

    // Mobile Sidebar Drawer Toggle
    function toggleMobileSidebar() {
        const sidebar = document.getElementById('residentSidebarDrawer');
        const backdrop = document.getElementById('mobileSidebarBackdrop');
        if (!sidebar) return;
        sidebar.classList.toggle('mobile-open');
        if (backdrop) backdrop.classList.toggle('open');
    }
</script>