<?php include __DIR__ . '/../layouts/header.php'; ?>

<?php
$fullName      = $_SESSION['user_name'] ?? 'Juan Dela Cruz';
$notifications = $data['notifications'] ?? [];
$unread_count  = $data['unread_count'] ?? 0;
$total_count   = count($notifications);
?>

<style>
    body, * { font-family: 'Miranda Sans', sans-serif !important; font-optical-sizing: auto; }
</style>

<div class="flex h-screen bg-slate-50 overflow-hidden w-full">
    <!-- Resident Sidebar -->
    <?php include __DIR__ . '/../layouts/resident_sidebar.php'; ?>

    <!-- Main Content Wrapper -->
    <div class="flex flex-col flex-1 min-w-0 overflow-hidden">
        <!-- Resident Topbar -->
        <?php include __DIR__ . '/../layouts/resident_topbar.php'; ?>

        <!-- Scrollable Main Content -->
        <main class="flex-1 overflow-y-auto bg-slate-50 focus:outline-none">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6">

                <!-- Header Title Bar -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200">
                    <div>
                        <div class="flex items-center gap-2 text-xs font-bold text-emerald-700 uppercase tracking-wider mb-1">
                            <span>Resident Portal</span>
                            <span>•</span>
                            <span>Notifications</span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">System Alerts &amp; Updates</h1>
                        <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1">Review status changes to your waste reports and official notices from the barangay.</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                            <?php echo $unread_count; ?> Unread
                        </span>
                        <?php if ($unread_count > 0): ?>
                            <button type="button" onclick="markAllAsRead()" class="text-xs font-bold text-emerald-700 hover:text-emerald-900 underline cursor-pointer">
                                Mark all as read
                            </button>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Filter Strip -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-3.5 sm:p-4 flex items-center gap-2 flex-wrap">
                    <button type="button" onclick="filterNotif('all', this)" class="notif-tab active px-3.5 py-1.5 rounded-xl text-xs font-bold bg-[#0B2E22] text-white transition cursor-pointer">
                        All (<?php echo $total_count; ?>)
                    </button>
                    <button type="button" onclick="filterNotif('unread', this)" class="notif-tab px-3.5 py-1.5 rounded-xl text-xs font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 transition cursor-pointer">
                        Unread (<?php echo $unread_count; ?>)
                    </button>
                    <button type="button" onclick="filterNotif('report', this)" class="notif-tab px-3.5 py-1.5 rounded-xl text-xs font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 transition cursor-pointer">
                        Report Updates
                    </button>
                    <button type="button" onclick="filterNotif('announcement', this)" class="notif-tab px-3.5 py-1.5 rounded-xl text-xs font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 transition cursor-pointer">
                        Announcements
                    </button>
                </div>

                <!-- Notification Cards Stack -->
                <div class="space-y-3" id="notifList">
                    <?php if (!empty($notifications)): ?>
                        <?php foreach ($notifications as $item):
                            $type = strtolower($item['type'] ?? 'notice');
                            $isRead = !empty($item['is_read']);
                            $dateStr = !empty($item['created_at']) ? date('M d, Y • h:i A', strtotime($item['created_at'])) : 'Recent';
                            $title = $item['title'] ?? 'Notification';
                            $content = $item['content'] ?? $item['message'] ?? '';
                            
                            $iconBg = 'bg-slate-100 text-slate-700';
                            $typeLabel = 'Notice';
                            if (strpos($type, 'report') !== false) {
                                $iconBg = 'bg-blue-50 text-blue-700 border-blue-200';
                                $typeLabel = 'Report Update';
                            } elseif (strpos($type, 'announcement') !== false) {
                                $iconBg = 'bg-emerald-50 text-emerald-800 border-emerald-200';
                                $typeLabel = 'Announcement';
                            } elseif (strpos($type, 'urgent') !== false) {
                                $iconBg = 'bg-red-50 text-red-800 border-red-200';
                                $typeLabel = 'Urgent';
                            }
                        ?>
                        <div class="notif-card bg-white rounded-2xl border <?php echo $isRead ? 'border-slate-200 opacity-90' : 'border-emerald-300 ring-2 ring-emerald-500/10'; ?> shadow-xs p-4 sm:p-5 transition hover:shadow-md flex items-start gap-3.5"
                             data-read="<?php echo $isRead ? '1' : '0'; ?>"
                             data-type="<?php echo $type; ?>"
                             id="notif-<?php echo $item['id']; ?>">
                            
                            <div class="w-10 h-10 rounded-xl <?php echo $iconBg; ?> border flex items-center justify-center shrink-0 mt-0.5">
                                <?php if ($typeLabel === 'Report Update'): ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                <?php elseif ($typeLabel === 'Announcement'): ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 11 18-5v12L3 14v-3z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>
                                <?php else: ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                                <?php endif; ?>
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2 flex-wrap mb-1">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold border <?php echo $iconBg; ?>">
                                            <?php echo $typeLabel; ?>
                                        </span>
                                        <?php if (!$isRead): ?>
                                            <span class="px-1.5 py-0.2 rounded-full text-[9px] font-extrabold bg-emerald-600 text-white">NEW</span>
                                        <?php endif; ?>
                                    </div>
                                    <span class="text-[11px] font-mono text-slate-400"><?php echo $dateStr; ?></span>
                                </div>

                                <h3 class="text-sm font-extrabold text-slate-900"><?php echo htmlspecialchars($title); ?></h3>
                                <p class="text-xs text-slate-600 mt-1 leading-relaxed"><?php echo htmlspecialchars($content); ?></p>

                                <?php if (!$isRead): ?>
                                    <div class="mt-3 pt-2 border-t border-slate-100 flex justify-end">
                                        <button type="button" onclick="markSingleRead(<?php echo (int)$item['id']; ?>)" class="text-xs font-bold text-emerald-700 hover:text-emerald-900 transition cursor-pointer inline-flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                            <span>Mark as read</span>
                                        </button>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center text-slate-400 text-xs shadow-xs space-y-2">
                            <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                            </div>
                            <p>You have no notifications at this time.</p>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </main>
    </div>
</div>

<script>
    function filterNotif(filter, btn) {
        document.querySelectorAll('.notif-tab').forEach(b => {
            b.className = 'notif-tab px-3.5 py-1.5 rounded-xl text-xs font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 transition cursor-pointer';
        });
        btn.className = 'notif-tab active px-3.5 py-1.5 rounded-xl text-xs font-bold bg-[#0B2E22] text-white transition cursor-pointer';

        const cards = document.querySelectorAll('.notif-card');
        cards.forEach(c => {
            const isRead = c.getAttribute('data-read') === '1';
            const type = c.getAttribute('data-type') || '';

            if (filter === 'all') {
                c.style.display = '';
            } else if (filter === 'unread') {
                c.style.display = !isRead ? '' : 'none';
            } else if (filter === 'report') {
                c.style.display = type.includes('report') ? '' : 'none';
            } else if (filter === 'announcement') {
                c.style.display = type.includes('announcement') ? '' : 'none';
            }
        });
    }

    function markSingleRead(id) {
        fetch('/brgy-waste-app-v3/public/resident/markNotificationRead', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=' + encodeURIComponent(id)
        })
        .then(() => {
            const card = document.getElementById('notif-' + id);
            if (card) {
                card.setAttribute('data-read', '1');
                card.classList.remove('border-emerald-300', 'ring-2', 'ring-emerald-500/10');
                card.classList.add('border-slate-200', 'opacity-90');
                const btn = card.querySelector('button');
                if (btn) btn.parentElement.remove();
            }
        })
        .catch(() => {});
    }

    function markAllAsRead() {
        fetch('/brgy-waste-app-v3/public/resident/markAllNotificationsRead', {
            method: 'POST'
        })
        .then(() => {
            window.location.reload();
        })
        .catch(() => {});
    }
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>