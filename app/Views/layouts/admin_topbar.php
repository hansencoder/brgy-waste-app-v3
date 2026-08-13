<header class="h-16 flex items-center justify-between px-4 sm:px-6 bg-[#FAFAFA] border-b border-slate-200/80 sticky top-0 z-30">
    <!-- Left Section: Mobile Menu Trigger -->
    <div class="flex items-center gap-3 lg:hidden">
        <button id="mobileMenuButton" type="button" class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700 shadow-sm hover:bg-slate-50 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <span class="text-sm font-bold text-slate-800">WasteWatch</span>
    </div>

    <!-- Right Section: Notification & Admin Profile (Matching Reference Top App Bar) -->
    <div class="flex items-center gap-3 ml-auto">
        <!-- Notification Bell Icon with Red Indicator -->
        <button onclick="openNotificationPanel()" class="relative inline-flex items-center justify-center p-2 rounded-xl text-slate-600 hover:bg-slate-200/60 transition" title="Notifications">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <span class="absolute top-1.5 right-1.5 flex h-2.5 w-2.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-600"></span>
            </span>
        </button>

        <!-- Vertical Separator Divider -->
        <div class="h-6 w-px bg-slate-200 mx-1"></div>

        <!-- Admin Avatar & Name Pill (Navigates to Profile) -->
        <a href="/brgy-waste-app-v3/public/admin/profile" class="flex items-center gap-2.5 p-1 pr-3 rounded-full hover:bg-slate-200/60 transition">
            <div class="h-8 w-8 rounded-full bg-[#194D33] text-white flex items-center justify-center text-xs font-bold shadow-xs">
                <?php echo strtoupper(substr($_SESSION['user_name'] ?? 'A', 0, 1)); ?>
            </div>
            <span class="text-xs font-bold text-slate-800 tracking-tight"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?></span>
        </a>
    </div>
</header>

<?php include __DIR__ . '/notification-panel.php'; ?>
