<header class="h-16 flex items-center justify-between px-4 sm:px-6 bg-white border-b border-slate-200 sticky top-0 z-30">
    <div class="flex items-center gap-3 lg:hidden">
        <button id="mobileMenuButton" type="button" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 shadow-sm hover:bg-slate-50 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
        <div class="text-sm font-semibold text-slate-900">Wastewatch</div>
    </div>

    <div class="flex items-center gap-3">
        <button onclick="openNotificationPanel()" class="relative inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <?php if (!empty($unread_count)): ?>
                <span class="absolute -top-1 -right-1 flex h-5 min-w-[20px] items-center justify-center rounded-full bg-red-500 px-1.5 text-[10px] font-bold text-white"><?php echo min($unread_count, 99); ?></span>
            <?php endif; ?>
        </button>

        <a href="/brgy-waste-app-v3/public/admin/profile" class="hidden lg:inline-flex items-center gap-3 rounded-2xl bg-[#2A523D] px-4 py-2 text-sm font-semibold text-white hover:bg-[#1a3828] transition">
            <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[#10B981] font-semibold text-sm uppercase"><?php echo strtoupper(substr($_SESSION['user_name'] ?? 'A', 0, 1)); ?></span>
            <span><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin User'); ?></span>
        </a>

        <a href="/brgy-waste-app-v3/public/auth/logout" class="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition">
            Logout
        </a>
    </div>
</header>

<?php include __DIR__ . '/notification-panel.php'; ?>
