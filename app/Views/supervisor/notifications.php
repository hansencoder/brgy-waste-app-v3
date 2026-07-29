<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php
$notifications = $data['notifications'] ?? [];
$unreadCount = $data['unread_count'] ?? 0;
$totalCount = count($notifications);

function getNotificationTypeStyle($type) {
    $map = [
        'report_update' => ['bg' => 'emerald-50', 'text' => 'emerald-700', 'icon' => '<path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/>'],
        'announcement' => ['bg' => 'blue-50', 'text' => 'blue-700', 'icon' => '<path d="m3 11 18-5v12L3 13v-2z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/>'],
        'account' => ['bg' => 'purple-50', 'text' => 'purple-700', 'icon' => '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>'],
        'system' => ['bg' => 'gray-50', 'text' => 'gray-700', 'icon' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>'],
        'report_rejected' => ['bg' => 'red-50', 'text' => 'red-700', 'icon' => '<circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>'],
    ];
    return $map[$type] ?? $map['system'];
}
?>

<div class="min-h-screen bg-[#F8FAFC]">
    <div class="lg:flex lg:min-h-screen">
        <?php include __DIR__ . '/../layouts/supervisor_sidebar.php'; ?>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="sticky top-0 z-40 bg-white/90 backdrop-blur border-b border-slate-200/80 px-4 sm:px-6 py-3 flex flex-wrap items-center justify-between gap-2">
                <div class="min-w-0">
                    <h1 class="text-base sm:text-lg md:text-xl font-bold text-slate-900 tracking-tight truncate">Notifications</h1>
                    <p class="text-xs text-slate-500 font-medium truncate">Stay updated with barangay alerts and updates</p>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <?php if ($unreadCount > 0): ?>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-red-50 text-red-600 text-xs font-bold rounded-full border border-red-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                            <?php echo $unreadCount; ?> unread
                        </span>
                    <?php endif; ?>
                    <?php if ($totalCount > 0): ?>
                        <button onclick="markAllAsRead()" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 transition">Mark all read</button>
                    <?php endif; ?>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">

                    <!-- Stats -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
                        <div class="bg-white rounded-2xl border border-slate-200 p-4 text-center shadow-sm">
                            <p class="text-2xl font-black text-slate-900"><?php echo $totalCount; ?></p>
                            <p class="text-xs text-slate-500 font-medium mt-1">Total</p>
                        </div>
                        <div class="bg-white rounded-2xl border border-slate-200 p-4 text-center shadow-sm">
                            <p class="text-2xl font-black text-red-600"><?php echo $unreadCount; ?></p>
                            <p class="text-xs text-slate-500 font-medium mt-1">Unread</p>
                        </div>
                        <div class="bg-white rounded-2xl border border-slate-200 p-4 text-center shadow-sm">
                            <p class="text-2xl font-black text-emerald-600"><?php echo $totalCount - $unreadCount; ?></p>
                            <p class="text-xs text-slate-500 font-medium mt-1">Read</p>
                        </div>
                        <div class="bg-white rounded-2xl border border-slate-200 p-4 text-center shadow-sm">
                            <p class="text-2xl font-black text-blue-600">
                                <?php 
                                    $reportUpdates = array_filter($notifications, function($n) {
                                        return strpos($n['type'] ?? '', 'report') !== false;
                                    });
                                    echo count($reportUpdates);
                                ?>
                            </p>
                            <p class="text-xs text-slate-500 font-medium mt-1">Report Updates</p>
                        </div>
                    </div>

                    <!-- Notifications List -->
                    <div class="space-y-3">
                        <?php if (!empty($notifications)): ?>
                            <?php foreach ($notifications as $item):
                                $style = getNotificationTypeStyle($item['type'] ?? 'system');
                                $isRead = isset($item['is_read']) && $item['is_read'] == 1;
                                $timeAgo = !empty($item['created_at']) ? date('M j, Y g:i A', strtotime($item['created_at'])) : 'Recently';
                            ?>
                            <div class="notification-item bg-white rounded-2xl border border-slate-200 shadow-sm p-4 transition hover:shadow-md <?php echo $isRead ? 'opacity-75' : 'border-l-4 border-l-emerald-500'; ?>" data-id="<?php echo $item['id']; ?>" data-read="<?php echo $isRead ? 'true' : 'false'; ?>">
                                <div class="flex items-start gap-3">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl <?php echo $style['bg']; ?> text-<?php echo str_replace('text-', '', $style['text']); ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <?php echo $style['icon']; ?>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex flex-wrap items-start justify-between gap-2">
                                            <h3 class="text-sm font-bold text-slate-900 <?php echo $isRead ? 'font-semibold' : ''; ?>">
                                                <?php echo htmlspecialchars($item['title'] ?? 'Notification'); ?>
                                            </h3>
                                            <span class="text-xs text-slate-400 flex-shrink-0"><?php echo $timeAgo; ?></span>
                                        </div>
                                        <p class="text-sm text-slate-600 mt-1 leading-relaxed"><?php echo htmlspecialchars($item['content'] ?? $item['message'] ?? ''); ?></p>
                                        <div class="flex flex-wrap items-center gap-3 mt-2">
                                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-[10px] font-semibold <?php echo $style['bg']; ?> <?php echo $style['text']; ?>">
                                                <?php echo ucfirst(str_replace('_', ' ', $item['type'] ?? 'system')); ?>
                                            </span>
                                            <?php if (!$isRead): ?>
                                                <button onclick="markAsRead(<?php echo $item['id']; ?>, this)" class="text-[10px] font-semibold text-emerald-600 hover:text-emerald-700 transition">
                                                    Mark as read
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-12 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-300 mx-auto mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                                <p class="text-slate-500 font-medium">No notifications</p>
                                <p class="text-sm text-slate-400 mt-1">You're all caught up!</p>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </main>
        </div>
    </div>
</div>

<script>
// Mark a single notification as read
function markAsRead(id, button) {
    fetch('/brgy-waste-app-v3/public/api/notifications.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ notification_id: id })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update UI
            const item = button.closest('.notification-item');
            item.classList.remove('border-l-4', 'border-l-emerald-500');
            item.classList.add('opacity-75');
            item.dataset.read = 'true';
            button.remove();
            
            // Update unread count
            const unreadBadge = document.querySelector('.bg-red-50');
            if (unreadBadge) {
                const count = parseInt(unreadBadge.textContent);
                if (count > 1) {
                    unreadBadge.innerHTML = '<span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span> ' + (count - 1) + ' unread';
                } else {
                    unreadBadge.remove();
                }
            }
        }
    })
    .catch(error => console.error('Error:', error));
}

// Mark all notifications as read
function markAllAsRead() {
    if (!confirm('Mark all notifications as read?')) return;
    
    fetch('/brgy-waste-app-v3/public/api/notifications.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ mark_all: true })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>