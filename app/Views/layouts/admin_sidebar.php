<?php
$current_uri = $_SERVER['REQUEST_URI'];
$isActive = function($path) use ($current_uri) {
    if ($path === '/admin') {
        return (strpos($current_uri, '/admin') !== false && strpos($current_uri, '/admin/') === false) || strpos($current_uri, '/admin/dashboard') !== false;
    }
    return strpos($current_uri, $path) !== false;
};
?>

<aside class="w-64 flex-shrink-0 bg-white flex flex-col h-full z-10">
    <div class="h-16 flex items-center px-6">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 rounded bg-[#2a523d] flex items-center justify-center text-white font-bold shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>
            <div>
                <h1 class="text-black font-bold text-lg leading-tight">WasteWatch</h1>
                <p class="text-xs text-gray-500 font-medium">Secretary Portal</p>
            </div>
        </div>
    </div>

    <div class="p-4 flex-1 overflow-y-auto">
        <div class="text-xs font-semibold text-gray-400 tracking-wider uppercase mb-3 px-2">Navigation</div>
        
        <nav class="space-y-1">
            <a href="/brgy-waste-app-v3/public/admin" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg <?php echo $isActive('/admin') ? 'bg-[#2A523D] text-white' : 'text-gray-400 hover:text-black hover:bg-[#dfece2]'; ?>">
                <svg class="mr-3 h-5 w-5 <?php echo $isActive('/admin') ? 'text-white' : ' group-hover:text-gray-500'; ?>" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                </svg>
                Dashboard
            </a>

            <a href="/brgy-waste-app-v3/public/admin/reports" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg <?php echo $isActive('/admin/reports') ? 'bg-[#234232] text-white' : 'text-gray-500 hover:text-black hover:bg-[#dfece2]'; ?>">
                <svg class="mr-3 h-5 w-5 <?php echo $isActive('/admin/reports') ? 'text-white' : ' group-hover:text-gray-500'; ?>" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Manage Reports
            </a>

            <?php if ($_SESSION['user_role'] == 'secretary'): ?>
            <a href="/brgy-waste-app-v3/public/admin/accounts" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg <?php echo $isActive('/admin/accounts') ? 'bg-[#234232] text-white' : 'text-gray-500 hover:text-black hover:bg-[#dfece2]'; ?>">
                <svg class="mr-3 h-5 w-5 <?php echo $isActive('/admin/accounts') ? 'text-white' : ' group-hover:text-gray-500'; ?>" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Manage Accounts
            </a>
            <?php endif; ?>

            <a href="/brgy-waste-app-v3/public/admin/announcements" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg <?php echo $isActive('/admin/announcements') ? 'bg-[#234232] text-white' : 'text-gray-500 hover:text-black hover:bg-[#dfece2]'; ?>">
                <svg class="mr-3 h-5 w-5 <?php echo $isActive('/admin/announcements') ? 'text-white' : ' group-hover:text-gray-500'; ?>" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                </svg>
                Announcements
            </a>

            <a href="/brgy-waste-app-v3/public/admin/report_summaries" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg <?php echo $isActive('/admin/report_summaries') ? 'bg-[#234232] text-white' : 'text-gray-500 hover:text-black hover:bg-[#dfece2]'; ?>">
                <svg class="mr-3 h-5 w-5 <?php echo $isActive('/admin/report_summaries') ? 'text-white' : ' group-hover:text-gray-500'; ?>" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Report Summaries
            </a>

            <a href="/brgy-waste-app-v3/public/admin/auditLogs" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg <?php echo $isActive('/admin/auditLogs') ? 'bg-[#234232] text-white' : 'text-gray-500 hover:text-black hover:bg-[#dfece2]'; ?>">
                <svg class="mr-3 h-5 w-5 <?php echo $isActive('/admin/auditLogs') ? 'text-white' : ' group-hover:text-gray-500'; ?>" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                Audit Logs
            </a>

            <div class="pt-4 mt-4 border-t border-gray-100"></div>

            <a href="/brgy-waste-app-v3/public/admin/profile" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-lg <?php echo $isActive('/admin/profile') ? 'bg-[#234232] text-white' : 'text-gray-500 hover:text-black hover:bg-[#dfece2]'; ?>">
                <svg class="mr-3 h-5 w-5 <?php echo $isActive('/admin/profile') ? 'text-white' : ' group-hover:text-gray-500'; ?>" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Settings & Profile
            </a>
        </nav>
    </div>
</aside>
