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
        background: #0B2E22;
        color: #ffffff;
        padding: 0.35rem 0.75rem;
        border-radius: 0.5rem;
        font-size: 0.75rem;
        font-weight: 700;
        white-space: nowrap;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(16, 185, 129, 0.2);
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
<aside id="residentSidebarDrawer" class="<?php echo $initialCollapsedClass; ?> fixed inset-y-0 left-0 z-50 flex h-full w-64 -translate-x-full transform flex-col bg-[#0B2E22] text-slate-300 transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 border-r border-emerald-950/60 shadow-xl lg:shadow-none">
    
    <!-- Brand Header -->
    <div class="flex h-16 shrink-0 items-center gap-3 px-4 border-b border-emerald-900/40">
        <a href="/brgy-waste-app-v3/public/resident" class="flex items-center gap-3 min-w-0 flex-1 group">
            <div class="h-9 w-9 rounded-full bg-[#083528] flex items-center justify-center overflow-hidden border border-emerald-500/40 shrink-0 shadow-sm group-hover:scale-105 transition">
                <?php if (!empty($sysLogo)): ?>
                    <img src="<?php echo htmlspecialchars($sysLogo); ?>" class="h-full w-full object-cover">
                <?php else: ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#10B981]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                <?php endif; ?>
            </div>
            <div class="sidebar-brand-text min-w-0">
                <p class="text-sm font-extrabold text-white leading-tight tracking-tight truncate"><?php echo htmlspecialchars($sysShortName); ?></p>
                <p class="text-[11px] font-semibold text-emerald-400/80 leading-none truncate mt-0.5">Resident Portal</p>
            </div>
        </a>

        <!-- Mobile Close Button -->
        <button type="button" onclick="toggleMobileSidebar()" class="lg:hidden p-1.5 rounded-lg text-emerald-400 hover:text-white hover:bg-emerald-900/50">
            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    <!-- Navigation Menu Items -->
    <div class="flex-1 overflow-y-auto px-3 py-4 space-y-6 scrollbar-none">
        
        <!-- SECTION: CORE -->
        <div class="space-y-1">
            <p class="sidebar-section-title px-3 text-[10px] font-black uppercase tracking-wider text-emerald-400/50">Core</p>

            <!-- Dashboard Link -->
            <?php $activeDash = $isActive('/resident') && !$isActive('/resident/submit') && !$isActive('/resident/my_report') && !$isActive('/resident/profile') && !$isActive('/resident/announcements') && !$isActive('/resident/collection_schedule') && !$isActive('/resident/notification'); ?>
            <a href="/brgy-waste-app-v3/public/resident" class="sidebar-link relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition <?php echo $activeDash ? 'bg-[#083528] text-white border border-emerald-500/30 shadow-xs' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>">
                <?php if ($activeDash): ?>
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full"></span>
                <?php endif; ?>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $activeDash ? 'text-[#10B981]' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/>
                </svg>
                <span class="sidebar-text">Dashboard</span>
                <span class="sidebar-tooltip">Dashboard</span>
            </a>

            <!-- Submit Report Link -->
            <?php $activeSubmit = $isActive('/resident/submit'); ?>
            <a href="/brgy-waste-app-v3/public/resident/submit" class="sidebar-link relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition <?php echo $activeSubmit ? 'bg-[#083528] text-white border border-emerald-500/30 shadow-xs' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>">
                <?php if ($activeSubmit): ?>
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full"></span>
                <?php endif; ?>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $activeSubmit ? 'text-[#10B981]' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                <span class="sidebar-text">Submit Report</span>
                <span class="sidebar-tooltip">Submit Report</span>
            </a>

            <!-- My Reports Link -->
            <?php $activeMyReports = $isActive('/resident/my_report') || strpos($currentUri, '/resident/viewReport') !== false; ?>
            <a href="/brgy-waste-app-v3/public/resident/my_report" class="sidebar-link relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition <?php echo $activeMyReports ? 'bg-[#083528] text-white border border-emerald-500/30 shadow-xs' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>">
                <?php if ($activeMyReports): ?>
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full"></span>
                <?php endif; ?>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $activeMyReports ? 'text-[#10B981]' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                <span class="sidebar-text">My Reports</span>
                <span class="sidebar-tooltip">My Reports</span>
            </a>
        </div>

        <!-- SECTION: SERVICES -->
        <div class="space-y-1">
            <p class="sidebar-section-title px-3 text-[10px] font-black uppercase tracking-wider text-emerald-400/50">Services</p>

            <!-- Collection Schedule Link -->
            <?php $activeSched = $isActive('/resident/collection_schedule'); ?>
            <a href="/brgy-waste-app-v3/public/resident/collection_schedule" class="sidebar-link relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition <?php echo $activeSched ? 'bg-[#083528] text-white border border-emerald-500/30 shadow-xs' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>">
                <?php if ($activeSched): ?>
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full"></span>
                <?php endif; ?>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $activeSched ? 'text-[#10B981]' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                <span class="sidebar-text">Collection Schedule</span>
                <span class="sidebar-tooltip">Collection Schedule</span>
            </a>

            <!-- Announcements Link -->
            <?php $activeAnnounce = $isActive('/resident/announcements'); ?>
            <a href="/brgy-waste-app-v3/public/resident/announcements" class="sidebar-link relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition <?php echo $activeAnnounce ? 'bg-[#083528] text-white border border-emerald-500/30 shadow-xs' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>">
                <?php if ($activeAnnounce): ?>
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full"></span>
                <?php endif; ?>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $activeAnnounce ? 'text-[#10B981]' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m3 11 18-5v12L3 13v-2z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/>
                </svg>
                <span class="sidebar-text">Announcements</span>
                <span class="sidebar-tooltip">Announcements</span>
            </a>
        </div>

        <!-- SECTION: ACCOUNT -->
        <div class="space-y-1">
            <p class="sidebar-section-title px-3 text-[10px] font-black uppercase tracking-wider text-emerald-400/50">Account</p>

            <!-- Profile Link -->
            <?php $activeProfile = $isActive('/resident/profile'); ?>
            <a href="/brgy-waste-app-v3/public/resident/profile" class="sidebar-link relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition <?php echo $activeProfile ? 'bg-[#083528] text-white border border-emerald-500/30 shadow-xs' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>">
                <?php if ($activeProfile): ?>
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full"></span>
                <?php endif; ?>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $activeProfile ? 'text-[#10B981]' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                <span class="sidebar-text">Resident Profile</span>
                <span class="sidebar-tooltip">Resident Profile</span>
            </a>

            <!-- Notifications Link -->
            <?php $activeNotif = $isActive('/resident/notification'); ?>
            <a href="/brgy-waste-app-v3/public/resident/notification" class="sidebar-link relative flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition <?php echo $activeNotif ? 'bg-[#083528] text-white border border-emerald-500/30 shadow-xs' : 'text-slate-300 hover:text-white hover:bg-white/5'; ?>">
                <?php if ($activeNotif): ?>
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-[#10B981] rounded-r-full"></span>
                <?php endif; ?>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 <?php echo $activeNotif ? 'text-[#10B981]' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                </svg>
                <span class="sidebar-text">Notifications</span>
                <span class="sidebar-tooltip">Notifications</span>
            </a>
        </div>

    </div>

    <!-- Bottom User Profile Card & Logout -->
    <div class="shrink-0 p-3 border-t border-emerald-900/40">
        <div class="flex items-center justify-between p-2 rounded-xl bg-white/5 hover:bg-white/10 transition">
            <a href="/brgy-waste-app-v3/public/resident/profile" class="flex items-center gap-2.5 min-w-0 flex-1 group">
                <div class="h-8 w-8 rounded-full bg-[#083528] text-white flex items-center justify-center text-xs font-bold border border-emerald-500/30 shrink-0">
                    <?php echo strtoupper(substr($firstName, 0, 1)); ?>
                </div>
                <div class="sidebar-user-details min-w-0">
                    <p class="text-xs font-extrabold text-white truncate"><?php echo htmlspecialchars($fullName); ?></p>
                    <p class="text-[10px] font-semibold text-emerald-400/80 truncate"><?php echo htmlspecialchars($purok); ?></p>
                </div>
            </a>
            <a href="/brgy-waste-app-v3/public/auth/logout" class="p-1.5 text-slate-400 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition" title="Sign Out">
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