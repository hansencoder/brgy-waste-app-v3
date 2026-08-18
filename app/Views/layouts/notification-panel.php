<?php
// Fetch notifications from database
require_once __DIR__ . '/../../init.php';
require_once __DIR__ . '/../../Models/Notification.php';

$userId = $_SESSION['user_id'] ?? null;
$userRole = $_SESSION['user_role'] ?? '';
$isAdminUser = in_array($userRole, ['administrator', 'captain', 'secretary', 'kagawad']);
$notifications = [];
$unreadCount = 0;

if ($userId) {
    try {
        $notificationModel = new Notification();
        $notifications = $notificationModel->getUserNotifications($userId, 40);
        $unreadCount = $notificationModel->getUnreadCount($userId);
    } catch (Exception $e) {
        $notifications = [];
        $unreadCount = 0;
    }
}
?>

<!-- Notification Panel Backdrop -->
<div id="notificationBackdrop" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-[9998] hidden transition-opacity" onclick="closeNotificationPanel()"></div>

<!-- Notification Slide-out Drawer Panel -->
<div id="notificationPanel" class="fixed top-0 right-0 h-full w-[440px] max-w-[95vw] bg-white shadow-2xl z-[9999] transform translate-x-full transition-transform duration-300 ease-in-out flex flex-col font-sans">
    
    <!-- 1. Drawer Header -->
    <div class="px-5 py-4 bg-slate-50/90 border-b border-slate-200 shrink-0">
        <div class="flex items-center justify-between gap-2">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-extrabold shadow-2xs">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                </div>
                <div>
                    <h2 class="text-base font-extrabold text-slate-900 leading-tight">Notifications &amp; Alerts</h2>
                    <p class="text-[11px] font-semibold text-slate-500">
                        <span id="drawerUnreadPill" class="text-emerald-700 font-extrabold"><?php echo $unreadCount; ?> unread</span> &bull; Activity Logs
                    </p>
                </div>
            </div>

            <!-- Header Quick Actions -->
            <div class="flex items-center gap-1.5">
                <?php if ($isAdminUser): ?>
                    <button onclick="openBroadcastModal()" class="p-2 rounded-xl text-emerald-700 hover:text-emerald-900 hover:bg-emerald-50 border border-emerald-200 transition cursor-pointer" title="Broadcast System Alert">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 11 18-5v12L3 13v-2z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>
                    </button>
                <?php endif; ?>
                <button onclick="markAllAsRead()" class="p-2 rounded-xl text-slate-500 hover:text-slate-900 hover:bg-slate-200 transition cursor-pointer" title="Mark all as read">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                </button>
                <button onclick="clearReadNotifications()" class="p-2 rounded-xl text-slate-400 hover:text-red-600 hover:bg-red-50 transition cursor-pointer" title="Clear read notifications">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                </button>
                <button onclick="closeNotificationPanel()" class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-200 transition cursor-pointer" title="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>
        </div>

        <!-- 2. Search & Category Filters -->
        <div class="mt-3 space-y-2">
            <!-- Search Bar -->
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                </div>
                <input type="text" id="notifSearchInput" oninput="filterNotifications()" placeholder="Search notifications or logs..." 
                       class="w-full pl-9 pr-3 py-1.5 bg-white border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/10 outline-none transition">
            </div>

            <!-- Tab Pills -->
            <div class="flex items-center gap-1 overflow-x-auto pb-0.5 scrollbar-none text-[11px] font-extrabold">
                <button onclick="setNotifTab('all')" class="notif-tab active px-2.5 py-1 rounded-lg bg-[#0B2E22] text-white transition cursor-pointer" data-tab="all">All</button>
                <button onclick="setNotifTab('unread')" class="notif-tab px-2.5 py-1 rounded-lg bg-slate-200/70 text-slate-700 hover:bg-slate-200 transition cursor-pointer" data-tab="unread">Unread</button>
                <button onclick="setNotifTab('report_update')" class="notif-tab px-2.5 py-1 rounded-lg bg-slate-200/70 text-slate-700 hover:bg-slate-200 transition cursor-pointer" data-tab="report_update">Reports</button>
                <button onclick="setNotifTab('announcement')" class="notif-tab px-2.5 py-1 rounded-lg bg-slate-200/70 text-slate-700 hover:bg-slate-200 transition cursor-pointer" data-tab="announcement">Advisories</button>
                <button onclick="setNotifTab('system')" class="notif-tab px-2.5 py-1 rounded-lg bg-slate-200/70 text-slate-700 hover:bg-slate-200 transition cursor-pointer" data-tab="system">Logs &amp; System</button>
            </div>
        </div>
    </div>

    <!-- 3. Scrollable Notification Feed List -->
    <div id="notificationList" class="flex-1 overflow-y-auto px-4 py-3 space-y-2.5">
        <!-- Notification cards dynamically rendered here -->
    </div>

    <!-- 4. Drawer Footer Actions -->
    <div class="px-5 py-3 border-t border-slate-200 bg-slate-50 flex items-center justify-between gap-3 shrink-0">
        <div class="text-[11px] font-bold text-slate-500">
            Total: <strong id="drawerTotalCount" class="text-slate-800"><?php echo count($notifications); ?></strong> items
        </div>
        <button onclick="exportNotificationsCSV()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white hover:bg-slate-100 border border-slate-200 text-slate-700 text-xs font-extrabold transition shadow-2xs cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
            <span>Export CSV</span>
        </button>
    </div>
</div>

<!-- ============================================================ -->
<!-- QUICK BROADCAST ALERT MODAL (Inline Topbar Drawer Trigger)   -->
<!-- ============================================================ -->
<div id="drawerBroadcastModal" class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs hidden z-[10000] flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full border border-slate-200 overflow-hidden animate-fadeIn">
        <div class="px-5 py-4 bg-emerald-900 text-white flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 11 18-5v12L3 14v-3z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>
                <h3 class="text-sm font-extrabold">Broadcast Instant Alert</h3>
            </div>
            <button onclick="closeBroadcastModal()" class="text-emerald-200 hover:text-white transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form onsubmit="submitBroadcastAlert(event)" class="p-5 space-y-4">
            <div>
                <label class="block text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-1.5">Alert Type</label>
                <select id="broadcastType" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs font-bold text-slate-800 bg-slate-50 focus:bg-white outline-none">
                    <option value="system">System Advisory / Emergency</option>
                    <option value="announcement">Public Announcement</option>
                    <option value="report_update">Waste Collection Notice</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-1.5">Alert Title</label>
                <input type="text" id="broadcastTitle" required placeholder="e.g. Typhoon Schedule Delay or Advisory" 
                       class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs font-bold text-slate-800 bg-slate-50 focus:bg-white outline-none">
            </div>
            <div>
                <label class="block text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-1.5">Alert Message Content</label>
                <textarea id="broadcastContent" required rows="3" placeholder="Provide complete advisory details for all residents and collectors..."
                          class="w-full rounded-xl border border-slate-200 p-3 text-xs font-semibold text-slate-800 bg-slate-50 focus:bg-white outline-none"></textarea>
            </div>
            <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100">
                <button type="button" onclick="closeBroadcastModal()" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-extrabold text-xs hover:bg-slate-200 transition">Cancel</button>
                <button type="submit" id="broadcastSubmitBtn" class="px-4 py-2 rounded-xl bg-[#0B2E22] hover:bg-[#084232] text-white font-extrabold text-xs transition shadow-xs">
                    Send to All Residents
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // In-memory data store
    let rawNotifications = <?php echo json_encode($notifications ?: []); ?>;
    let currentTab = 'all';
    let searchQuery = '';

    // Synchronize Topbar Bell Red Dot & Badge
    function syncUnreadBadge(count) {
        const dot = document.getElementById('topbarNotificationDot');
        if (dot) {
            if (count > 0) {
                dot.classList.remove('hidden');
            } else {
                dot.classList.add('hidden');
            }
        }
        const pill = document.getElementById('drawerUnreadPill');
        if (pill) {
            pill.textContent = `${count} unread`;
        }
        const total = document.getElementById('drawerTotalCount');
        if (total) {
            total.textContent = rawNotifications.length;
        }
    }

    // Drawer Open / Close Animation
    function openNotificationPanel() {
        document.getElementById('notificationBackdrop').classList.remove('hidden');
        document.getElementById('notificationPanel').classList.remove('translate-x-full');
        document.body.style.overflow = 'hidden';
        renderNotifications();
    }

    function closeNotificationPanel() {
        document.getElementById('notificationPanel').classList.add('translate-x-full');
        setTimeout(() => {
            document.getElementById('notificationBackdrop').classList.add('hidden');
        }, 300);
        document.body.style.overflow = '';
    }

    // Filter by Tab
    function setNotifTab(tab) {
        currentTab = tab;
        document.querySelectorAll('.notif-tab').forEach(btn => {
            if (btn.getAttribute('data-tab') === tab) {
                btn.className = 'notif-tab active px-2.5 py-1 rounded-lg bg-[#0B2E22] text-white transition cursor-pointer';
            } else {
                btn.className = 'notif-tab px-2.5 py-1 rounded-lg bg-slate-200/70 text-slate-700 hover:bg-slate-200 transition cursor-pointer';
            }
        });
        renderNotifications();
    }

    // Live search filter
    function filterNotifications() {
        searchQuery = (document.getElementById('notifSearchInput').value || '').toLowerCase().trim();
        renderNotifications();
    }

    function getRelativeTime(dateStr) {
        try {
            const d = new Date(dateStr);
            const now = new Date();
            const diffMs = now - d;
            const diffSec = Math.floor(diffMs / 1000);
            const diffMin = Math.floor(diffSec / 60);
            const diffHour = Math.floor(diffMin / 60);
            const diffDay = Math.floor(diffHour / 24);

            if (diffSec < 60) return 'Just now';
            if (diffMin < 60) return `${diffMin}m ago`;
            if (diffHour < 24) return `${diffHour}h ago`;
            if (diffDay < 7) return `${diffDay}d ago`;
            return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        } catch (e) {
            return dateStr;
        }
    }

    function getTypeMeta(type) {
        switch (type) {
            case 'report_update':
                return {
                    svg: '<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>',
                    badge: 'Report',
                    badgeClass: 'bg-emerald-100 text-emerald-900 border-emerald-300',
                    bg: 'hover:border-emerald-300'
                };
            case 'announcement':
                return {
                    svg: '<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 11 18-5v12L3 14v-3z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>',
                    badge: 'Advisory',
                    badgeClass: 'bg-amber-100 text-amber-900 border-amber-300',
                    bg: 'hover:border-amber-300'
                };
            case 'account':
                return {
                    svg: '<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-sky-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
                    badge: 'Account',
                    badgeClass: 'bg-sky-100 text-sky-900 border-sky-300',
                    bg: 'hover:border-sky-300'
                };
            default:
                return {
                    svg: '<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-purple-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>',
                    badge: 'System Log',
                    badgeClass: 'bg-purple-100 text-purple-900 border-purple-300',
                    bg: 'hover:border-purple-300'
                };
        }
    }

    function renderNotifications() {
        const container = document.getElementById('notificationList');
        if (!container) return;

        let filtered = rawNotifications.filter(n => {
            const isRead = n.is_read == 1 || n.is_read === true;
            if (currentTab === 'unread' && isRead) return false;
            if (currentTab === 'report_update' && n.type !== 'report_update') return false;
            if (currentTab === 'announcement' && n.type !== 'announcement') return false;
            if (currentTab === 'system' && (n.type === 'report_update' || n.type === 'announcement')) return false;

            if (searchQuery) {
                const hay = `${n.title || ''} ${n.content || ''} ${n.type || ''}`.toLowerCase();
                if (!hay.includes(searchQuery)) return false;
            }
            return true;
        });

        if (filtered.length === 0) {
            container.innerHTML = `
                <div class="py-12 px-4 text-center space-y-2">
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                    </div>
                    <h4 class="text-xs font-extrabold text-slate-700">No notifications found</h4>
                    <p class="text-[11px] font-semibold text-slate-400">There are no alerts matching your current filter.</p>
                </div>
            `;
            return;
        }

        container.innerHTML = filtered.map(n => {
            const isRead = n.is_read == 1 || n.is_read === true;
            const meta = getTypeMeta(n.type);
            const timeAgo = getRelativeTime(n.created_at);
            const reportId = n.report_id || null;

            return `
                <div class="relative p-3.5 rounded-2xl border transition-all ${isRead ? 'bg-white border-slate-200' : 'bg-emerald-50/40 border-emerald-200 shadow-2xs'} ${meta.bg} group">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-xl bg-slate-100 flex items-center justify-center shrink-0 shadow-2xs">
                            ${meta.svg}
                        </div>
                        <div class="flex-1 min-w-0 space-y-1">
                            <div class="flex items-center justify-between gap-1">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold border ${meta.badgeClass}">
                                    ${meta.badge}
                                </span>
                                <div class="flex items-center gap-1 text-[10px] font-bold text-slate-400">
                                    <span>${timeAgo}</span>
                                    ${!isRead ? '<span class="w-2 h-2 rounded-full bg-emerald-600 inline-block"></span>' : ''}
                                </div>
                            </div>
                            <h4 class="text-xs font-extrabold text-slate-900 leading-snug break-words">
                                ${escapeHtml(n.title)}
                            </h4>
                            <p class="text-[11px] font-semibold text-slate-600 leading-relaxed break-words">
                                ${escapeHtml(n.content)}
                            </p>

                            <!-- Actions Row -->
                            <div class="pt-1.5 flex items-center justify-between gap-2">
                                <div>
                                    ${reportId ? `
                                        <a href="<?php echo app_url('admin/viewReport/${reportId}'); ?>" class="inline-flex items-center gap-1 text-[11px] font-extrabold text-emerald-800 hover:text-emerald-950 underline underline-offset-2">
                                            <span>Inspect Report #${reportId}</span>
                                            <span>&rarr;</span>
                                        </a>
                                    ` : ''}
                                </div>
                                <div class="flex items-center gap-1 opacity-80 group-hover:opacity-100 transition">
                                    ${!isRead ? `
                                        <button onclick="markAsRead(${n.id})" class="px-2 py-1 rounded-lg text-[10px] font-extrabold bg-white border border-slate-200 hover:bg-slate-100 text-slate-700 transition cursor-pointer" title="Mark as Read">
                                            Mark Read
                                        </button>
                                    ` : ''}
                                    <button onclick="deleteNotification(${n.id})" class="p-1 rounded-lg text-slate-400 hover:text-red-600 hover:bg-red-50 transition cursor-pointer" title="Delete notification">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }

    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Mark single notification as read
    function markAsRead(id) {
        fetch('<?php echo app_url('api/notifications.php'); ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ notification_id: id })
        }).then(r => r.json()).then(res => {
            if (res.success) {
                const item = rawNotifications.find(n => n.id == id);
                if (item) item.is_read = 1;
                syncUnreadBadge(res.unread_count ?? rawNotifications.filter(n => n.is_read == 0).length);
                renderNotifications();
            }
        }).catch(console.error);
    }

    // Mark all notifications as read
    function markAllAsRead() {
        fetch('<?php echo app_url('api/notifications.php'); ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ mark_all: true })
        }).then(r => r.json()).then(res => {
            if (res.success) {
                rawNotifications.forEach(n => n.is_read = 1);
                syncUnreadBadge(0);
                renderNotifications();
            }
        }).catch(console.error);
    }

    // Delete single notification
    function deleteNotification(id) {
        fetch('<?php echo app_url('api/notifications.php'); ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ delete_id: id })
        }).then(r => r.json()).then(res => {
            if (res.success) {
                rawNotifications = rawNotifications.filter(n => n.id != id);
                syncUnreadBadge(res.unread_count ?? rawNotifications.filter(n => n.is_read == 0).length);
                renderNotifications();
            }
        }).catch(console.error);
    }

    // Clear all read notifications
    function clearReadNotifications() {
        if (!confirm('Clear all read notifications?')) return;
        fetch('<?php echo app_url('api/notifications.php'); ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ clear_read: true })
        }).then(r => r.json()).then(res => {
            if (res.success) {
                rawNotifications = rawNotifications.filter(n => n.is_read == 0);
                syncUnreadBadge(res.unread_count ?? rawNotifications.length);
                renderNotifications();
            }
        }).catch(console.error);
    }

    // Export notifications logs as CSV
    function exportNotificationsCSV() {
        if (rawNotifications.length === 0) {
            alert('No notification logs available to export.');
            return;
        }
        let csv = 'ID,Type,Title,Content,Read_Status,Timestamp\n';
        rawNotifications.forEach(n => {
            const isRead = (n.is_read == 1 || n.is_read === true) ? 'Read' : 'Unread';
            const cleanTitle = `"${(n.title || '').replace(/"/g, '""')}"`;
            const cleanContent = `"${(n.content || '').replace(/"/g, '""')}"`;
            csv += `${n.id},${n.type},${cleanTitle},${cleanContent},${isRead},${n.created_at}\n`;
        });
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `Notification_Logs_${new Date().toISOString().slice(0, 10)}.csv`;
        a.click();
        URL.revokeObjectURL(url);
    }

    // Broadcast Alert Modal Controls
    function openBroadcastModal() {
        document.getElementById('drawerBroadcastModal').classList.remove('hidden');
    }
    function closeBroadcastModal() {
        document.getElementById('drawerBroadcastModal').classList.add('hidden');
    }

    function submitBroadcastAlert(e) {
        e.preventDefault();
        const type = document.getElementById('broadcastType').value;
        const title = document.getElementById('broadcastTitle').value.trim();
        const content = document.getElementById('broadcastContent').value.trim();
        const btn = document.getElementById('broadcastSubmitBtn');

        if (!title || !content) return;
        btn.disabled = true;
        btn.textContent = 'Broadcasting...';

        fetch('<?php echo app_url('api/notifications.php'); ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ broadcast: true, type, title, content })
        }).then(r => r.json()).then(res => {
            btn.disabled = false;
            btn.textContent = 'Send to All Residents';
            if (res.success) {
                closeBroadcastModal();
                alert('Broadcast alert dispatched successfully!');
                // Reload list
                fetch('<?php echo app_url('api/notifications.php'); ?>', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ get_list: true })
                }).then(r => r.json()).then(listRes => {
                    if (listRes.success) {
                        rawNotifications = listRes.notifications || [];
                        syncUnreadBadge(listRes.unread_count || 0);
                        renderNotifications();
                    }
                });
            } else {
                alert(res.message || 'Failed to dispatch broadcast');
            }
        }).catch(err => {
            btn.disabled = false;
            btn.textContent = 'Send to All Residents';
            console.error(err);
        });
    }

    // Initial load
    document.addEventListener('DOMContentLoaded', function() {
        syncUnreadBadge(<?php echo $unreadCount; ?>);
        renderNotifications();
    });
</script>
