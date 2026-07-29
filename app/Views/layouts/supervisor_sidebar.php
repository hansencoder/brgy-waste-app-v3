<?php
$fullName = $_SESSION['user_name'] ?? 'Supervisor User';
$firstName = isset($_SESSION['user_name']) ? explode(' ', trim($_SESSION['user_name']))[0] : 'Supervisor';
$unreadCount = $data['unread_count'] ?? 0;

$currentUri = $_SERVER['REQUEST_URI'];
$isActive = function($path) use ($currentUri) {
    return strpos($currentUri, $path) !== false;
};
?>

<aside class="w-full lg:w-72 bg-[#07281E] text-white px-5 py-5 lg:sticky lg:top-0 lg:h-screen lg:flex lg:flex-col lg:overflow-hidden">
    <!-- Logo -->
    <div class="flex items-center gap-2.5 flex-shrink-0">
        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#10B981] shadow-lg shadow-emerald-500/20">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </div>
        <div>
            <p class="text-base font-black tracking-tight leading-tight">Wastewatch</p>
            <p class="text-[10px] uppercase tracking-[0.3em] text-emerald-200 leading-tight">Supervisor</p>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="mt-5 space-y-1 flex-1 overflow-y-auto pr-1">
        <!-- Dashboard -->
        <a href="/brgy-waste-app-v3/public/index.php?url=supervisor" class="flex items-center gap-2.5 rounded-xl <?php echo $isActive('/supervisor') && !$isActive('/supervisor/reports') && !$isActive('/supervisor/gis') && !$isActive('/supervisor/analytics') && !$isActive('/supervisor/schedule') && !$isActive('/supervisor/announcements') && !$isActive('/supervisor/notifications') && !$isActive('/supervisor/profile') ? 'bg-[#10B981] text-white shadow-lg shadow-emerald-500/20' : 'text-emerald-100/90 hover:bg-white/10 hover:text-white'; ?> px-3 py-2 text-xs font-semibold">
            <span class="flex h-7 w-7 items-center justify-center rounded-lg <?php echo $isActive('/supervisor') && !$isActive('/supervisor/reports') ? 'bg-white/20' : 'bg-white/10'; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V21h14V9.5"/></svg>
            </span>
            <span>
                <span class="block text-[12px] leading-tight">Dashboard</span>
                <span class="text-[9px] font-medium text-emerald-100/70 leading-tight">Overview &amp; KPIs</span>
            </span>
        </a>

        <!-- Reports Monitoring -->
        <a href="/brgy-waste-app-v3/public/index.php?url=supervisor/reports" class="flex items-center gap-2.5 rounded-xl <?php echo $isActive('/supervisor/reports') || strpos($currentUri, '/supervisor/view_report') !== false ? 'bg-[#10B981] text-white shadow-lg shadow-emerald-500/20' : 'text-emerald-100/90 hover:bg-white/10 hover:text-white'; ?> px-3 py-2 text-xs font-semibold">
            <span class="flex h-7 w-7 items-center justify-center rounded-lg <?php echo $isActive('/supervisor/reports') || strpos($currentUri, '/supervisor/view_report') !== false ? 'bg-white/20' : 'bg-white/10'; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
            </span>
            <span>
                <span class="block text-[12px] leading-tight">Reports</span>
                <span class="text-[9px] font-medium text-emerald-100/70 leading-tight">Track &amp; review</span>
            </span>
        </a>

        <!-- GIS Monitoring -->
        <a href="/brgy-waste-app-v3/public/index.php?url=supervisor/gis" class="flex items-center gap-2.5 rounded-xl <?php echo $isActive('/supervisor/gis') ? 'bg-[#10B981] text-white shadow-lg shadow-emerald-500/20' : 'text-emerald-100/90 hover:bg-white/10 hover:text-white'; ?> px-3 py-2 text-xs font-semibold">
            <span class="flex h-7 w-7 items-center justify-center rounded-lg <?php echo $isActive('/supervisor/gis') ? 'bg-white/20' : 'bg-white/10'; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
            </span>
            <span>
                <span class="block text-[12px] leading-tight">GIS Monitor</span>
                <span class="text-[9px] font-medium text-emerald-100/70 leading-tight">Map &amp; heatmap</span>
            </span>
        </a>

        <!-- Analytics -->
        <a href="/brgy-waste-app-v3/public/index.php?url=supervisor/analytics" class="flex items-center gap-2.5 rounded-xl <?php echo $isActive('/supervisor/analytics') ? 'bg-[#10B981] text-white shadow-lg shadow-emerald-500/20' : 'text-emerald-100/90 hover:bg-white/10 hover:text-white'; ?> px-3 py-2 text-xs font-semibold">
            <span class="flex h-7 w-7 items-center justify-center rounded-lg <?php echo $isActive('/supervisor/analytics') ? 'bg-white/20' : 'bg-white/10'; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg>
            </span>
            <span>
                <span class="block text-[12px] leading-tight">Analytics</span>
                <span class="text-[9px] font-medium text-emerald-100/70 leading-tight">Statistics &amp; insights</span>
            </span>
        </a>

        <!-- Collection Schedule -->
        <a href="/brgy-waste-app-v3/public/index.php?url=supervisor/schedule" class="flex items-center gap-2.5 rounded-xl <?php echo $isActive('/supervisor/schedule') ? 'bg-[#10B981] text-white shadow-lg shadow-emerald-500/20' : 'text-emerald-100/90 hover:bg-white/10 hover:text-white'; ?> px-3 py-2 text-xs font-semibold">
            <span class="flex h-7 w-7 items-center justify-center rounded-lg <?php echo $isActive('/supervisor/schedule') ? 'bg-white/20' : 'bg-white/10'; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>
            </span>
            <span>
                <span class="block text-[12px] leading-tight">Schedule</span>
                <span class="text-[9px] font-medium text-emerald-100/70 leading-tight">Pickup timetable</span>
            </span>
        </a>

        <!-- Announcements -->
        <a href="/brgy-waste-app-v3/public/index.php?url=supervisor/announcements" class="flex items-center gap-2.5 rounded-xl <?php echo $isActive('/supervisor/announcements') ? 'bg-[#10B981] text-white shadow-lg shadow-emerald-500/20' : 'text-emerald-100/90 hover:bg-white/10 hover:text-white'; ?> px-3 py-2 text-xs font-semibold">
            <span class="flex h-7 w-7 items-center justify-center rounded-lg <?php echo $isActive('/supervisor/announcements') ? 'bg-white/20' : 'bg-white/10'; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 11 18-5v12L3 13v-2z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>
            </span>
            <span>
                <span class="block text-[12px] leading-tight">Announcements</span>
                <span class="text-[9px] font-medium text-emerald-100/70 leading-tight">Barangay notices</span>
            </span>
        </a>

        <!-- Notifications -->
        <a href="/brgy-waste-app-v3/public/index.php?url=supervisor/notifications" class="flex items-center gap-2.5 rounded-xl <?php echo $isActive('/supervisor/notifications') ? 'bg-[#10B981] text-white shadow-lg shadow-emerald-500/20' : 'text-emerald-100/90 hover:bg-white/10 hover:text-white'; ?> px-3 py-2 text-xs font-semibold">
            <span class="flex h-7 w-7 items-center justify-center rounded-lg <?php echo $isActive('/supervisor/notifications') ? 'bg-white/20' : 'bg-white/10'; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
            </span>
            <span>
                <span class="block text-[12px] leading-tight">Notifications</span>
                <span class="text-[9px] font-medium text-emerald-100/70 leading-tight">Alerts &amp; updates</span>
            </span>
            <?php 
                $unreadCount = $data['unread_count'] ?? 0;
                if ($unreadCount > 0): 
            ?>
                <span class="ml-auto rounded-full bg-red-500 px-2 py-0.5 text-[9px] font-bold"><?php echo $unreadCount; ?></span>
            <?php endif; ?>
        </a>

        <!-- Profile -->
        <a href="/brgy-waste-app-v3/public/index.php?url=supervisor/profile" class="flex items-center gap-2.5 rounded-xl <?php echo $isActive('/supervisor/profile') ? 'bg-[#10B981] text-white shadow-lg shadow-emerald-500/20' : 'text-emerald-100/90 hover:bg-white/10 hover:text-white'; ?> px-3 py-2 text-xs font-semibold">
            <span class="flex h-7 w-7 items-center justify-center rounded-lg <?php echo $isActive('/supervisor/profile') ? 'bg-white/20' : 'bg-white/10'; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21a8 8 0 1 0-16 0"/><circle cx="12" cy="8" r="4"/></svg>
            </span>
            <span>
                <span class="block text-[12px] leading-tight">Profile</span>
                <span class="text-[9px] font-medium text-emerald-100/70 leading-tight">Account settings</span>
            </span>
        </a>
    </nav>

    <!-- Profile Card -->
    <div class="flex-shrink-0 mt-3 rounded-2xl border border-white/10 bg-white/10 p-3">
        <div class="flex items-center gap-2.5">
            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-[#10B981] font-bold text-white text-sm">
                <?php echo strtoupper(substr($firstName, 0, 1)); ?>
            </div>
            <div class="flex-1 min-w-0">
                <p class="font-semibold text-sm leading-tight truncate"><?php echo htmlspecialchars($fullName); ?></p>
                <p class="text-[10px] text-emerald-100/80 leading-tight">Supervisor</p>
            </div>
        </div>
        <a href="/brgy-waste-app-v3/public/auth/logout" class="mt-2.5 flex w-full items-center justify-center gap-1.5 rounded-lg border border-white/10 bg-white/5 py-1.5 text-[10px] font-semibold text-emerald-100/90 transition hover:bg-white/10 hover:text-white">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            Logout
        </a>
    </div>
</aside>