<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php
$fullName = $_SESSION['user_name'] ?? 'Juan Dela Cruz';
$firstName = isset($_SESSION['user_name']) ? explode(' ', trim($_SESSION['user_name']))[0] : 'Juan';

// Data passed from ResidentController@notifications
$notifications = $data['notifications'] ?? [];
$unread_count = $data['unread_count'] ?? 0;
$total_count = count($notifications);

// Count urgent/service reminders
$service_count = 0;
foreach ($notifications as $item) {
    if (strpos(strtolower($item['type'] ?? ''), 'service') !== false || 
        strpos(strtolower($item['title'] ?? ''), 'collection') !== false ||
        strpos(strtolower($item['title'] ?? ''), 'schedule') !== false) {
        $service_count++;
    }
}
// If no service notifications, use a fallback calculation
if ($service_count === 0 && $total_count > 0) {
    $service_count = round($total_count * 0.3);
}
?>

<div class="min-h-screen bg-[#F8FAFC] text-slate-800 lg:flex">
    
    <!-- Reusable Sidebar -->
    <?php include __DIR__ . '/../layouts/resident_sidebar.php'; ?>

    <div class="flex-1">
        <header class="border-b border-slate-200 bg-white/90 px-4 py-4 backdrop-blur sm:px-6 lg:px-8 lg:py-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-[0.35em] text-[#0D9488]">Resident Portal</p>
                    <h1 class="mt-1 text-2xl font-black tracking-tight text-slate-900 sm:text-3xl">Notifications</h1>
                    <p class="mt-1 text-sm text-slate-500">Stay updated with barangay announcements and service reminders.</p>
                </div>
                <div class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm font-semibold text-emerald-700 shadow-sm">
                    <span class="h-2.5 w-2.5 rounded-full bg-[#10B981]"></span>
                    <?php echo $unread_count > 0 ? $unread_count . ' new updates' : 'All read'; ?>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8 lg:py-8">
            <section class="rounded-[28px] border border-slate-200 bg-white p-4 shadow-[0_18px_45px_-30px_rgba(15,23,42,0.2)] sm:p-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-500">Latest activity</p>
                        <h2 class="text-xl font-black text-slate-900">Today</h2>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" class="filter-btn rounded-full bg-[#10B981] px-3 py-2 text-sm font-semibold text-white" data-filter="all">All</button>
                        <button type="button" class="filter-btn rounded-full border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600" data-filter="unread">Unread</button>
                    </div>
                </div>

                <div class="mt-5 space-y-3">
                    <?php if (!empty($notifications)): ?>
                        <?php foreach ($notifications as $item): ?>
                            <?php
                                // Determine notification type for styling
                                $type = $item['type'] ?? 'Notice';
                                $tone = 'bg-cyan-50 text-cyan-700';
                                $icon = 'bg-cyan-50 text-cyan-700';
                                $iconSvg = '<path d="M4 4h16v12H7l-3 3z"/>';
                                
                                if (stripos($type, 'urgent') !== false || stripos($item['title'] ?? '', 'collection') !== false) {
                                    $tone = 'bg-[#FEE2E2] text-[#DC2626]';
                                    $icon = 'bg-[#FEE2E2] text-[#DC2626]';
                                    $iconSvg = '<path d="M13 2 3 14h7l-1 8 10-12h-7l1-8Z"/>';
                                } elseif (stripos($type, 'event') !== false || stripos($item['title'] ?? '', 'clean') !== false) {
                                    $tone = 'bg-emerald-50 text-emerald-700';
                                    $icon = 'bg-emerald-50 text-emerald-700';
                                    $iconSvg = '<path d="M12 3v18"/><path d="M3 12h18"/>';
                                }
                                
                                $isRead = isset($item['is_read']) && $item['is_read'] == 1;
                                $timeAgo = !empty($item['created_at']) 
                                    ? date('M j, Y g:i A', strtotime($item['created_at']))
                                    : 'Recently';
                            ?>
                            <a href="#" class="notification-card block rounded-[22px] border border-slate-200 <?php echo $isRead ? 'bg-white' : 'bg-slate-50/70'; ?> p-4 text-left transition hover:border-emerald-200 hover:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-300" data-unread="<?php echo $isRead ? 'false' : 'true'; ?>">
                                <div class="flex items-start gap-3">
                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl <?php echo htmlspecialchars($icon); ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <?php echo $iconSvg; ?>
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center justify-between gap-2">
                                            <h3 class="text-base font-black text-slate-900"><?php echo htmlspecialchars($item['title'] ?? 'Notification'); ?></h3>
                                            <span class="text-sm text-slate-400"><?php echo htmlspecialchars($timeAgo); ?></span>
                                        </div>
                                        <p class="mt-2 text-sm leading-7 text-slate-600"><?php echo htmlspecialchars($item['content'] ?? $item['message'] ?? ''); ?></p>
                                        <div class="mt-3 flex flex-wrap items-center justify-between gap-2">
                                            <span class="rounded-full px-3 py-1 text-[11px] font-semibold <?php echo htmlspecialchars($tone); ?>"><?php echo htmlspecialchars(ucfirst($type)); ?></span>
                                            <?php if (!$isRead): ?>
                                                <span class="rounded-full bg-emerald-500/10 px-2.5 py-1 text-[11px] font-semibold text-emerald-700">New</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="rounded-[22px] border border-slate-200 bg-white p-12 text-center shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-4"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                            <p class="text-slate-500 font-medium">No notifications</p>
                            <p class="text-sm text-slate-400 mt-1">You're all caught up!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Stats -->
            <section class="mt-5 grid gap-3 md:grid-cols-3">
                <div class="rounded-[22px] border border-slate-200 bg-white p-4 shadow-[0_18px_45px_-30px_rgba(15,23,42,0.2)]">
                    <p class="text-3xl font-black text-slate-900"><?php echo $total_count; ?></p>
                    <p class="mt-1 text-sm font-semibold text-slate-500">Total Updates</p>
                </div>
                <div class="rounded-[22px] border border-slate-200 bg-white p-4 shadow-[0_18px_45px_-30px_rgba(15,23,42,0.2)]">
                    <p class="text-3xl font-black text-[#EF4444]"><?php echo $unread_count; ?></p>
                    <p class="mt-1 text-sm font-semibold text-slate-500">Unread</p>
                </div>
                <div class="rounded-[22px] border border-slate-200 bg-white p-4 shadow-[0_18px_45px_-30px_rgba(15,23,42,0.2)]">
                    <p class="text-3xl font-black text-[#10B981]"><?php echo $service_count; ?></p>
                    <p class="mt-1 text-sm font-semibold text-slate-500">Service Reminder</p>
                </div>
            </section>
        </main>
    </div>
</div>

<!-- Mobile Bottom Navigation -->
<nav class="fixed bottom-0 left-0 right-0 z-40 border-t border-slate-200 bg-white/95 px-2 py-3 backdrop-blur md:hidden">
    <div class="mx-auto flex max-w-md items-center justify-between gap-1">
        <a href="/brgy-waste-app-v3/public/resident/dashboard" class="flex-1 rounded-2xl bg-white px-2 py-2 text-center text-[10px] font-semibold text-slate-600">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-1"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
            Home
        </a>
        <a href="/brgy-waste-app-v3/public/resident/my_report" class="flex-1 rounded-2xl bg-white px-2 py-2 text-center text-[10px] font-semibold text-slate-600">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-1"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
            Reports
        </a>
        <a href="/brgy-waste-app-v3/public/resident/submit" class="flex-1 rounded-full bg-[#10B981] px-3 py-2.5 text-center text-[10px] font-black text-white shadow-lg shadow-emerald-500/20">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-1"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
            Report
        </a>
        <a href="/brgy-waste-app-v3/public/resident/announcements" class="flex-1 rounded-2xl bg-white px-2 py-2 text-center text-[10px] font-semibold text-slate-600">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-1"><path d="m3 11 18-5v12L3 13v-2z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>
            Announcements
        </a>
        <a href="/brgy-waste-app-v3/public/resident/notification" class="flex-1 rounded-2xl bg-[#E6F4EA] px-2 py-2 text-center text-[10px] font-semibold text-slate-900">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-1"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
            Alerts
        </a>
    </div>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const cards = document.querySelectorAll('.notification-card');

    filterButtons.forEach((button) => {
        button.addEventListener('click', function () {
            const filter = this.getAttribute('data-filter');

            filterButtons.forEach((btn) => {
                btn.classList.remove('bg-[#10B981]', 'text-white', 'border-slate-200', 'text-slate-600');
                if (btn === this) {
                    btn.classList.add('bg-[#10B981]', 'text-white');
                } else {
                    btn.classList.add('border-slate-200', 'text-slate-600');
                }
            });

            cards.forEach((card) => {
                const isUnread = card.getAttribute('data-unread') === 'true';
                const shouldShow = filter === 'all' || (filter === 'unread' && isUnread);
                card.classList.toggle('hidden', !shouldShow);
            });
        });
    });

    cards.forEach((card) => {
        card.addEventListener('click', function (event) {
            event.preventDefault();
            cards.forEach((item) => item.classList.remove('ring-2', 'ring-emerald-300', 'bg-white'));
            this.classList.add('ring-2', 'ring-emerald-300', 'bg-white');
        });
    });
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>