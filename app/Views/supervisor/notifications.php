<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php
$notifications = $data['notifications'] ?? [];
$unreadCount = (int)($data['unread_count'] ?? 0);
$totalCount = count($notifications);

function getSupervisorNotifStyle($type) {
    $map = [
        'report_update' => ['bg' => 'bg-emerald-50 text-emerald-700 border-emerald-100', 'badge' => 'bg-emerald-50 text-emerald-800'],
        'announcement' => ['bg' => 'bg-blue-50 text-blue-700 border-blue-100', 'badge' => 'bg-blue-50 text-blue-800'],
        'account' => ['bg' => 'bg-purple-50 text-purple-700 border-purple-100', 'badge' => 'bg-purple-50 text-purple-800'],
        'report_rejected' => ['bg' => 'bg-red-50 text-red-700 border-red-100', 'badge' => 'bg-red-50 text-red-800'],
    ];
    return $map[$type] ?? ['bg' => 'bg-slate-100 text-slate-700 border-slate-200', 'badge' => 'bg-slate-100 text-slate-700'];
}
?>

<div class="min-h-screen bg-[#F8FAFC] flex">
    
    <!-- Sidebar -->
    <?php include __DIR__ . '/../layouts/supervisor_sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
        
        <!-- Topbar -->
        <?php include __DIR__ . '/../layouts/supervisor_topbar.php'; ?>

        <!-- Page Body -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8 space-y-6 max-w-4xl mx-auto w-full">

            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Supervisor Notifications</h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Real-time alerts, citizen reports, and system notices</p>
                </div>

                <div class="flex items-center gap-2">
                    <?php if ($unreadCount > 0): ?>
                    <button onclick="markAllAsRead()" class="px-3.5 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 text-xs font-semibold border border-emerald-200/60 transition cursor-pointer">
                        Mark All as Read
                    </button>
                    <?php endif; ?>
                </div>
            </div>

            <!-- 4 Metric Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
                <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-2xs text-center">
                    <span class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider block">Total Alerts</span>
                    <p class="text-xl sm:text-2xl font-bold text-slate-900 font-mono mt-1"><?php echo $totalCount; ?></p>
                </div>
                <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-2xs text-center">
                    <span class="text-[11px] font-semibold text-amber-600 uppercase tracking-wider block">Unread</span>
                    <p class="text-xl sm:text-2xl font-bold text-amber-600 font-mono mt-1"><?php echo $unreadCount; ?></p>
                </div>
                <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-2xs text-center">
                    <span class="text-[11px] font-semibold text-emerald-600 uppercase tracking-wider block">Read</span>
                    <p class="text-xl sm:text-2xl font-bold text-emerald-600 font-mono mt-1"><?php echo max($totalCount - $unreadCount, 0); ?></p>
                </div>
                <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-2xs text-center">
                    <span class="text-[11px] font-semibold text-blue-600 uppercase tracking-wider block">Reports</span>
                    <p class="text-xl sm:text-2xl font-bold text-blue-600 font-mono mt-1">
                        <?php echo count(array_filter($notifications, fn($n) => strpos($n['type'] ?? '', 'report') !== false)); ?>
                    </p>
                </div>
            </div>

            <!-- Notifications Feed List -->
            <div class="space-y-3">
                <?php if (!empty($notifications)): ?>
                    <?php foreach ($notifications as $item):
                        $st = getSupervisorNotifStyle($item['type'] ?? 'system');
                        $isRead = !empty($item['is_read']);
                    ?>
                    <div class="p-4 sm:p-5 rounded-2xl border bg-white shadow-2xs transition flex items-start justify-between gap-4 <?php echo $isRead ? 'border-slate-200 opacity-80' : 'border-emerald-300 ring-2 ring-emerald-500/10'; ?>" id="notif-<?php echo $item['id']; ?>">
                        <div class="flex items-start gap-3.5">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 border <?php echo $st['bg']; ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                            </div>
                            <div class="space-y-1">
                                <div class="flex items-center gap-2">
                                    <h3 class="text-xs sm:text-sm font-bold text-slate-900"><?php echo htmlspecialchars($item['title'] ?? 'Notification'); ?></h3>
                                    <?php if (!$isRead): ?>
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    <?php endif; ?>
                                </div>
                                <p class="text-xs text-slate-600 leading-relaxed"><?php echo htmlspecialchars($item['content'] ?? $item['message'] ?? ''); ?></p>
                                <span class="text-[11px] text-slate-400 font-mono block"><?php echo date('M d, Y \a\t g:i A', strtotime($item['created_at'] ?? 'now')); ?></span>
                            </div>
                        </div>

                        <?php if (!$isRead): ?>
                        <button onclick="markAsRead(<?php echo $item['id']; ?>)" class="text-[11px] font-semibold text-emerald-700 hover:text-emerald-900 shrink-0 cursor-pointer">
                            Mark Read
                        </button>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="py-12 text-center text-slate-400 bg-white rounded-2xl border border-slate-200 text-xs">
                        No notifications found in your inbox.
                    </div>
                <?php endif; ?>
            </div>

        </main>

    </div>
</div>

<script>
function markAsRead(id) {
    fetch('/brgy-waste-app-v3/public/supervisor/markNotificationRead', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'notification_id=' + id
    }).then(r => r.json()).then(res => {
        if (res.success) {
            location.reload();
        }
    });
}

function markAllAsRead() {
    fetch('/brgy-waste-app-v3/public/supervisor/markAllNotificationsRead', {
        method: 'POST'
    }).then(r => r.json()).then(res => {
        if (res.success) {
            location.reload();
        }
    });
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>