<?php
// Fetch notifications from database
require_once __DIR__ . '/../../init.php';
require_once __DIR__ . '/../../Models/Notification.php';

$userId = $_SESSION['user_id'] ?? null;
$notifications = [];
$unreadCount = 0;

if ($userId) {
    $notificationModel = new Notification();
    $notifications = $notificationModel->getUserNotifications($userId, 20);
    $unreadCount = $notificationModel->getUnreadCount($userId);
}
?>

<!-- Notification Panel Backdrop -->
<div id="notificationBackdrop" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[9998] hidden transition-opacity" onclick="closeNotificationPanel()"></div>

<!-- Notification Panel -->
<div id="notificationPanel" class="fixed top-0 right-0 h-full w-96 bg-white shadow-2xl z-[9999] transform translate-x-full transition-transform duration-300 ease-in-out">
    <!-- Header -->
    <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100">
        <h2 class="text-[18px] font-bold text-slate-800">Notifications</h2>
        <div class="flex items-center gap-3">
            <button onclick="markAllAsRead()" class="flex items-center gap-2 text-[13px] font-semibold text-slate-500 hover:text-[#118B50] transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
                Mark all read
            </button>
            <button onclick="closeNotificationPanel()" class="p-1.5 text-slate-400 hover:text-slate-600 hover:bg-gray-100 rounded-lg transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Notification List -->
    <div id="notificationList" class="flex-1 overflow-y-auto px-6 py-4 space-y-3">
        <!-- Notifications will be rendered here -->
    </div>
</div>

<script>
    // Unread count from PHP
    const initialUnreadCount = <?php echo $unreadCount; ?>;

    // Update bell icon badge
    function updateUnreadBadge(count) {
        const badges = document.querySelectorAll('.notification-badge');
        badges.forEach(badge => {
            if (count > 0) {
                badge.textContent = count > 99 ? '99+' : count;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        });
    }

    // --- Notification Panel Functions ---
    function openNotificationPanel() {
        document.getElementById('notificationBackdrop').classList.remove('hidden');
        document.getElementById('notificationPanel').classList.remove('translate-x-full');
        document.body.style.overflow = 'hidden';
    }

    function closeNotificationPanel() {
        document.getElementById('notificationPanel').classList.add('translate-x-full');
        setTimeout(() => {
            document.getElementById('notificationBackdrop').classList.add('hidden');
        }, 300);
        document.body.style.overflow = '';
    }

    // Notifications data from PHP backend
    const notifications = <?php echo json_encode($notifications ?: []); ?>;

    function getIcon(type) {
        const icons = {
            report_update: `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>`,
            announcement: `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 11 18-5v12L3 13v-2z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>`,
            account: `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>`,
            system: `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>`
        };
        return icons[type] || icons.system;
    }

    function getNotificationStyle(type, read) {
        if (read) return 'bg-white border-gray-100';
        
        const styles = {
            report_update: 'bg-green-50 border-green-100',
            announcement: 'bg-green-50 border-green-100',
            account: 'bg-blue-50 border-blue-100',
            system: 'bg-gray-50 border-gray-100'
        };
        return styles[type] || 'bg-white border-gray-100';
    }

    function getIconColor(type, read) {
        if (read) return 'text-slate-400 bg-gray-100';
        
        const colors = {
            report_update: 'text-green-600 bg-green-100',
            announcement: 'text-green-600 bg-green-100',
            account: 'text-blue-600 bg-blue-100',
            system: 'text-slate-500 bg-gray-100'
        };
        return colors[type] || 'text-slate-400 bg-gray-100';
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        const options = { month: 'short', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit' };
        return date.toLocaleDateString('en-US', options);
    }

    function renderNotifications() {
        const list = document.getElementById('notificationList');
        list.innerHTML = '';

        if (notifications.length === 0) {
            list.innerHTML = `
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                    </div>
                    <p class="text-[14px] font-semibold text-slate-600 mb-1">No notifications yet</p>
                    <p class="text-[12px] text-slate-400">We'll notify you when something happens</p>
                </div>
            `;
            return;
        }

        notifications.forEach(notification => {
            const item = document.createElement('div');
            const isRead = notification.is_read == 1 || notification.is_read === true;
            item.className = `p-4 rounded-[14px] border ${getNotificationStyle(notification.type, isRead)} transition-all cursor-pointer hover:shadow-sm`;
            item.onclick = () => markAsRead(notification.id);

            item.innerHTML = `
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 rounded-full ${getIconColor(notification.type, isRead)} flex items-center justify-center shrink-0">
                        ${getIcon(notification.type)}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2 mb-1">
                            <h3 class="text-[14px] font-bold text-slate-800 leading-tight">${escapeHtml(notification.title)}</h3>
                            ${!isRead ? '<div class="w-2 h-2 rounded-full bg-green-500 mt-1.5 shrink-0"></div>' : ''}
                        </div>
                        <p class="text-[13px] text-slate-500 leading-relaxed mb-1.5">${escapeHtml(notification.content)}</p>
                        <span class="text-[12px] text-slate-400 font-medium">${formatDate(notification.created_at)}</span>
                    </div>
                </div>
            `;

            list.appendChild(item);
        });
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    function markAsRead(id) {
        fetch('/brgy-waste-app-v3/public/api/notifications.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ notification_id: id })
        }).then(response => response.json())
        .then(data => {
            if (data.success) {
                const notification = notifications.find(n => n.id == id);
                if (notification && notification.is_read == 0) {
                    notification.is_read = 1;
                    renderNotifications();
                    // Update badge
                    const currentCount = notifications.filter(n => n.is_read == 0).length;
                    updateUnreadBadge(currentCount);
                }
            }
        }).catch(error => {
            console.error('Error marking notification as read:', error);
        });
    }

    function markAllAsRead() {
        fetch('/brgy-waste-app-v3/public/api/notifications.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ mark_all: true })
        }).then(response => response.json())
        .then(data => {
            if (data.success) {
                notifications.forEach(n => n.is_read = 1);
                renderNotifications();
                updateUnreadBadge(0);
            }
        }).catch(error => {
            console.error('Error marking all notifications as read:', error);
        });
    }

    // Initialize notifications
    document.addEventListener('DOMContentLoaded', function() {
        renderNotifications();
        updateUnreadBadge(initialUnreadCount);
        
        // Inject badge into bell icon
        const bellButtons = document.querySelectorAll('button[onclick="openNotificationPanel()"]');
        bellButtons.forEach(button => {
            if (!button.querySelector('.notification-badge')) {
                const badge = document.createElement('span');
                badge.className = 'notification-badge absolute -top-1 -right-1 min-w-[18px] h-[18px] rounded-full bg-red-500 text-white text-[10px] font-bold flex items-center justify-center px-1 border-2 border-white';
                if (initialUnreadCount === 0) {
                    badge.classList.add('hidden');
                }
                badge.textContent = initialUnreadCount > 99 ? '99+' : initialUnreadCount;
                button.appendChild(badge);
            }
        });
    });
</script>
