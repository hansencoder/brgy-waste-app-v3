<?php
// Ensure database and models are loaded
require_once __DIR__ . '/../../init.php';
require_once __DIR__ . '/../../Models/Notification.php';

// Fetch branding details for supervisor topbar header
try {
    $topDb = new Database();
    $topDb->query("SELECT system_short_name, system_motto, system_logo, barangay_name FROM barangays LIMIT 1");
    $topBranding = $topDb->single();
} catch (Exception $e) {
    $topBranding = null;
}
$topSysShortName = !empty($topBranding['system_short_name']) ? $topBranding['system_short_name'] : 'WasteWatch';
$topSysMotto = !empty($topBranding['system_motto']) ? $topBranding['system_motto'] : 'Supervisor Portal';
$topBrgyName = !empty($topBranding['barangay_name']) ? $topBranding['barangay_name'] : 'Dulong Bayan';
$topSysLogo = format_asset_url($topBranding['system_logo'] ?? '');

$topUnreadCount = 0;
if (isset($_SESSION['user_id'])) {
    try {
        $topNotifModel = new Notification();
        $topUnreadCount = $topNotifModel->getUnreadCount($_SESSION['user_id']);
    } catch (Exception $e) {
        $topUnreadCount = 0;
    }
}
?>

<header class="h-16 flex items-center justify-between px-4 sm:px-6 bg-white border-b border-slate-200 sticky top-0 z-30 shadow-xs">
    <!-- Left Section: Desktop Collapse Toggle & Mobile Menu Trigger -->
    <div class="flex items-center gap-3">
        <!-- Desktop Sidebar Collapse Toggle -->
        <button type="button" onclick="toggleDesktopSidebar()" class="hidden lg:inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700 shadow-xs hover:bg-slate-50 transition" title="Toggle Sidebar">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="6" x2="20" y2="6"/><line x1="4" y1="18" x2="20" y2="18"/></svg>
        </button>

        <!-- Mobile Hamburger + Branding -->
        <div class="flex items-center gap-3 lg:hidden">
            <button id="mobileMenuButton" onclick="toggleMobileSidebar()" type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700 shadow-xs hover:bg-slate-50 transition" aria-label="Open navigation menu">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="4" y1="12" x2="20" y2="12"/><line x1="4" y1="6" x2="20" y2="6"/><line x1="4" y1="18" x2="20" y2="18"/></svg>
            </button>
            <!-- Mini branding in mobile topbar -->
            <div class="flex items-center gap-2">
                <div class="h-7 w-7 rounded-full flex items-center justify-center overflow-hidden shrink-0">
                    <?php if (!empty($topSysLogo)): ?>
                        <img src="<?php echo htmlspecialchars($topSysLogo); ?>" class="h-full w-full rounded-full object-cover" alt="Logo">
                    <?php else: ?>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#10B981]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    <?php endif; ?>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-bold text-slate-900 leading-tight truncate"><?php echo htmlspecialchars($topSysShortName); ?></p>
                    <p class="text-[10px] text-emerald-700 font-medium leading-none truncate">Supervisor Portal</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Section: Notification & Supervisor Profile -->
    <div class="flex items-center gap-3 ml-auto">
        <!-- Notification Bell Icon with Dynamic Red Indicator -->
        <button onclick="openNotificationPanel()" class="relative inline-flex items-center justify-center p-2 rounded-xl text-slate-600 hover:bg-slate-100 transition cursor-pointer" title="Notifications">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
            <span id="topbarNotificationDot" class="absolute top-1.5 right-1.5 flex h-2.5 w-2.5 <?php echo $topUnreadCount > 0 ? '' : 'hidden'; ?>">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-600"></span>
            </span>
        </button>

        <!-- Vertical Separator Divider -->
        <div class="h-6 w-px bg-slate-200 mx-1"></div>

        <!-- Supervisor Avatar & Name Pill (Navigates to Profile) -->
        <a href="<?php echo app_url('supervisor/profile'); ?>" class="flex items-center gap-2.5 p-1 pr-3 rounded-full hover:bg-slate-100 transition">
            <div class="h-8 w-8 rounded-full bg-[#0B2E22] text-white flex items-center justify-center text-xs font-bold shadow-xs">
                <?php echo strtoupper(substr($_SESSION['user_name'] ?? 'S', 0, 1)); ?>
            </div>
            <div class="hidden sm:block text-left">
                <span class="text-xs font-bold text-slate-800 tracking-tight block leading-tight"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Supervisor'); ?></span>
                <span class="text-[10px] font-medium text-emerald-700 block leading-none">Supervisor</span>
            </div>
        </a>
    </div>
</header>

<?php include __DIR__ . '/notification-panel.php'; ?>
