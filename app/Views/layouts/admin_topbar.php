<header class="h-16 flex items-center justify-between px-6 bg-white z-10 sticky top-0">
    <div class="flex-1 flex items-center pr-4">
        <!-- Empty space on the left as requested by design -->
    </div>
    
    <div class="flex items-center space-x-6">
        <button onclick="openNotificationPanel()" class="relative p-2 text-gray-500 hover:text-gray-700 rounded-full transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
            </svg>
        </button>
        
        <a href="/brgy-waste-app-v3/public/admin/profile" class="flex items-center text-white text-sm space-x-2 pl-2 bg-[#2A523D] hover:bg-[#1a3828] transition-colors rounded-[6px] px-4 py-2">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span class="font-medium"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin User'); ?></span>
            </div>
        </a>

        <div class="flex items-center text-white text-sm  pl-6  bg-[#c21313] rounded-[6px] px-4 py-2">
            <a href="/brgy-waste-app-v3/public/auth/logout" class="text-white hover:text-red-400 font-medium transition-colors">
                Log Out
            </a>
        </div>
    </div>
</header>

<!-- Include Notification Panel -->
<?php include '../app/Views/layouts/notification-panel.php'; ?>
