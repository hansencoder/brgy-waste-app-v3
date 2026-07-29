<?php
// Get current user info from session (already set in AuthController)
$fullName = $_SESSION['user_name'] ?? 'Resident';
$firstName = isset($_SESSION['user_name']) ? explode(' ', trim($_SESSION['user_name']))[0] : 'Resident';
$purok = $_SESSION['user_purok'] ?? 'Purok 1';

// Determine active page for highlighting
$currentUri = $_SERVER['REQUEST_URI'];
$isActive = function($path) use ($currentUri) {
    return strpos($currentUri, $path) !== false;
};
?>

<aside class="w-full lg:w-80 bg-[#07281E] text-white px-6 py-7 lg:sticky lg:top-0 lg:h-screen lg:flex lg:flex-col">
    <div class="flex items-center gap-3">
        <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-[#10B981] shadow-lg shadow-emerald-500/20">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </div>
        <div>
            <p class="text-xl font-black tracking-tight">Wastewatch</p>
            <p class="text-[11px] uppercase tracking-[0.35em] text-emerald-200">Resident Portal</p>
        </div>
    </div>

    <nav class="mt-8 space-y-2">
        <!-- Dashboard -->
        <a href="/brgy-waste-app-v3/public/resident" class="flex items-center gap-3 rounded-2xl <?php echo $isActive('/resident') && !$isActive('/resident/submit') && !$isActive('/resident/my_report') && !$isActive('/resident/profile') && !$isActive('/resident/announcements') && !$isActive('/resident/collection_schedule') && !$isActive('/resident/notification') ? 'bg-[#10B981] text-white shadow-lg shadow-emerald-500/20' : 'text-emerald-100/90 hover:bg-white/10 hover:text-white'; ?> px-4 py-3 text-sm font-semibold">
            <span class="flex h-9 w-9 items-center justify-center rounded-xl <?php echo $isActive('/resident') && !$isActive('/resident/submit') ? 'bg-white/20' : 'bg-white/10'; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10.5 12 3l9 7.5"/><path d="M5 9.5V21h14V9.5"/></svg>
            </span>
            <span>
                <span class="block">Dashboard</span>
                <span class="text-[11px] font-medium text-emerald-100/70">Overview & stats</span>
            </span>
        </a>

        <!-- Submit Report -->
        <a href="/brgy-waste-app-v3/public/resident/submit" class="flex items-center gap-3 rounded-2xl <?php echo $isActive('/resident/submit') ? 'bg-[#10B981] text-white shadow-lg shadow-emerald-500/20' : 'text-emerald-100/90 hover:bg-white/10 hover:text-white'; ?> px-4 py-3 text-sm font-semibold">
            <span class="flex h-9 w-9 items-center justify-center rounded-xl <?php echo $isActive('/resident/submit') ? 'bg-white/20' : 'bg-white/10'; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
            </span>
            <span>
                <span class="block">Submit Report</span>
                <span class="text-[11px] font-medium text-emerald-100/70">Report a waste issue</span>
            </span>
        </a>

        <!-- My Reports -->
        <a href="/brgy-waste-app-v3/public/resident/my_report" class="flex items-center gap-3 rounded-2xl <?php echo $isActive('/resident/my_report') ? 'bg-[#10B981] text-white shadow-lg shadow-emerald-500/20' : 'text-emerald-100/90 hover:bg-white/10 hover:text-white'; ?> px-4 py-3 text-sm font-semibold">
            <span class="flex h-9 w-9 items-center justify-center rounded-xl <?php echo $isActive('/resident/my_report') ? 'bg-white/20' : 'bg-white/10'; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 3h10a2 2 0 0 1 2 2v14l-4-2-4 2-4-2-4 2V5a2 2 0 0 1 2-2Z"/></svg>
            </span>
            <span>
                <span class="block">My Reports</span>
                <span class="text-[11px] font-medium text-emerald-100/70">View your submissions</span>
            </span>
        </a>

        <!-- Collection Schedule -->
        <a href="/brgy-waste-app-v3/public/resident/collection_schedule" class="flex items-center gap-3 rounded-2xl <?php echo $isActive('/resident/collection_schedule') ? 'bg-[#10B981] text-white shadow-lg shadow-emerald-500/20' : 'text-emerald-100/90 hover:bg-white/10 hover:text-white'; ?> px-4 py-3 text-sm font-semibold">
            <span class="flex h-9 w-9 items-center justify-center rounded-xl <?php echo $isActive('/resident/collection_schedule') ? 'bg-white/20' : 'bg-white/10'; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>
            </span>
            <span>
                <span class="block">Collection Schedule</span>
                <span class="text-[11px] font-medium text-emerald-100/70">Pickup timetable</span>
            </span>
        </a>

        <!-- Announcements -->
        <a href="/brgy-waste-app-v3/public/resident/announcements" class="flex items-center gap-3 rounded-2xl <?php echo $isActive('/resident/announcements') ? 'bg-[#10B981] text-white shadow-lg shadow-emerald-500/20' : 'text-emerald-100/90 hover:bg-white/10 hover:text-white'; ?> px-4 py-3 text-sm font-semibold">
            <span class="flex h-9 w-9 items-center justify-center rounded-xl <?php echo $isActive('/resident/announcements') ? 'bg-white/20' : 'bg-white/10'; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16v12H7l-3 3z"/></svg>
            </span>
            <span>
                <span class="block">Announcements</span>
                <span class="text-[11px] font-medium text-emerald-100/70">Barangay notices</span>
            </span>
        </a>

        <!-- Notifications -->
        <a href="/brgy-waste-app-v3/public/resident/notification" class="flex items-center gap-3 rounded-2xl <?php echo $isActive('/resident/notification') ? 'bg-[#10B981] text-white shadow-lg shadow-emerald-500/20' : 'text-emerald-100/90 hover:bg-white/10 hover:text-white'; ?> px-4 py-3 text-sm font-semibold transition">
            <span class="flex h-9 w-9 items-center justify-center rounded-xl <?php echo $isActive('/resident/notification') ? 'bg-white/20' : 'bg-white/10'; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
            </span>
            <span>
                <span class="block">Notifications</span>
                <span class="text-[11px] font-medium <?php echo $isActive('/resident/notification') ? 'text-emerald-50/80' : 'text-emerald-100/70'; ?>">Alerts & updates</span>
            </span>
            <?php if (isset($unreadCount) && $unreadCount > 0): ?>
                <span class="ml-auto rounded-full bg-red-500 px-2.5 py-1 text-[11px] font-bold"><?php echo $unreadCount; ?></span>
            <?php endif; ?>
        </a>

        <!-- Profile -->
        <a href="/brgy-waste-app-v3/public/resident/profile" class="flex items-center gap-3 rounded-2xl <?php echo $isActive('/resident/profile') ? 'bg-[#10B981] text-white shadow-lg shadow-emerald-500/20' : 'text-emerald-100/90 hover:bg-white/10 hover:text-white'; ?> px-4 py-3 text-sm font-semibold">
            <span class="flex h-9 w-9 items-center justify-center rounded-xl <?php echo $isActive('/resident/profile') ? 'bg-white/20' : 'bg-white/10'; ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21a8 8 0 1 0-16 0"/><circle cx="12" cy="8" r="4"/></svg>
            </span>
            <span>
                <span class="block">Profile</span>
                <span class="text-[11px] font-medium text-emerald-100/70">Account settings</span>
            </span>
        </a>
    </nav>

    <div class="mt-8 rounded-3xl border border-white/10 bg-white/10 p-4 backdrop-blur">
        <div class="flex items-center gap-3">
            <div class="flex h-11 w-11 items-center justify-center rounded-full bg-[#10B981] font-bold text-white">
                <?php echo strtoupper(substr($firstName, 0, 1)); ?>
            </div>
            <div>
                <p class="font-semibold"><?php echo htmlspecialchars($fullName); ?></p>
                <p class="text-sm text-emerald-100/80">Resident · <?php echo htmlspecialchars($purok); ?></p>
            </div>
        </div>
        <a href="/brgy-waste-app-v3/public/auth/logout" class="mt-4 flex items-center gap-2 text-sm font-semibold text-emerald-100/90 transition hover:text-white">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
            Logout
        </a>
    </div>
</aside>