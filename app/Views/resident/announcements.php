<?php include '../app/Views/layouts/header.php'; ?>
<?php
// Retrieve user info from session if available
$fullName = $_SESSION['user_name'] ?? 'Juan Dela Cruz';
$firstName = isset($_SESSION['user_name']) ? explode(' ', trim($_SESSION['user_name']))[0] : 'Juan';

$announcements = $data['announcements'] ?? [];
?>

<div class="min-h-screen bg-[#f9fafb] w-full font-sans antialiased text-slate-800 flex flex-col">

    <!-- Top Navbar -->
    <nav class="bg-[#15281f] border-b border-gray-200 sticky top-0 z-50 shadow-sm shrink-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-[68px]">
                <!-- Left: Logo -->
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-[#F4A825] flex items-center justify-center text-white shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    <span class="font-extrabold text-white text-lg tracking-tight">WasteWatch</span>
                </div>

                <!-- Center: Nav Links -->
                <div class="hidden md:flex items-center justify-center gap-1.5 flex-1">
                    <a href="/brgy-waste-app-v3/public/resident/dashboard" class="flex items-center gap-2 text-slate-500 hover:text-white hover:bg-[#234232] px-4 py-2.5 rounded-[12px] font-semibold text-[13.5px] transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="7" x="3" y="3" rx="1.5"/><rect width="7" height="7" x="14" y="3" rx="1.5"/><rect width="7" height="7" x="14" y="14" rx="1.5"/><rect width="7" height="7" x="3" y="14" rx="1.5"/></svg>
                        Home
                    </a>
                    <a href="/brgy-waste-app-v3/public/resident/my_report" class="flex items-center gap-2 text-slate-500 hover:text-white hover:bg-[#234232] px-4 py-2.5 rounded-[12px] font-semibold text-[13.5px] transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg>
                        Reports
                    </a>
                    <a href="/brgy-waste-app-v3/public/resident/submit" class="flex items-center gap-2 text-slate-500 hover:text-white hover:bg-[#234232] px-4 py-2.5 rounded-[12px] font-semibold text-[13.5px] transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                        Submit Report
                    </a>
                    <a href="/brgy-waste-app-v3/public/resident/announcements" class="flex items-center gap-2 bg-[#234232] text-white px-4 py-2.5 rounded-[12px] font-semibold text-[13.5px] shadow-sm shadow-[#118B50]/20 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 11 18-5v12L3 13v-2z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>
                        News
                    </a>
                </div>

                <!-- Right: Profile -->
                <div class="flex items-center gap-3 md:gap-5">
                    <button onclick="openNotificationPanel()" class="relative p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-50 rounded-full transition-colors hidden md:block">
                        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                    </button>

                    <div class="h-6 w-px bg-gray-200 hidden md:block"></div>


                    <a href="/brgy-waste-app-v3/public/resident/profile" class="text-[13px] font-bold text-white hidden sm:block hover:text-[#118B50] transition-colors">Resident <?php echo htmlspecialchars($firstName); ?></a>


                    <a href="/brgy-waste-app-v3/public/auth/logout" class="flex items-center gap-2.5 px-3 py-1 rounded-full hover:bg-red-50 transition-colors ">


                        <div class="w-[34px] h-[34px] rounded-full border border-red-200 flex items-center justify-center bg-gray-50 text-slate-500 shadow-sm group-hover:border-red-200 group-hover:bg-red-50 ">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                            
                        </div>
                        
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-10 flex-1 w-full flex flex-col mb-16 md:mb-0">
        
        <!-- Header -->
        <div class="flex items-center gap-3 mb-8">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#118B50" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m3 11 18-5v12L3 13v-2z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>
            <h1 class="text-[26px] font-extrabold text-[#111827] tracking-tight leading-tight">Announcements</h1>
        </div>

        <!-- Announcements Card List -->
        <div class="space-y-5 mb-10 w-full max-w-[100%]">
            <?php foreach($announcements as $item): ?>
            <div class="bg-white rounded-[12px] shadow-sm border border-gray-200/80 p-5 flex flex-col transition-shadow hover:shadow-md">
                
                <h3 class="text-[15px] font-bold text-slate-800 mb-1.5"><?php echo htmlspecialchars($item['title']); ?></h3>

                <p class="text-[14px] text-slate-500 mb-5 leading-relaxed"><?php echo nl2br(htmlspecialchars($item['content'])); ?></p>
                
                <div class="text-[11.5px] text-slate-400 font-medium space-y-0.5">
                    <div><?php echo date('M j, Y', strtotime($item['created_at'])); ?></div>
                    <div>Posted by <?php echo isset($item['author']) ? htmlspecialchars($item['author']) : 'Brgy. Secretary'; ?></div>
                </div>

            </div>
            <?php endforeach; ?>
            
            <?php if(empty($announcements)): ?>
            <div class="bg-white rounded-[12px] border border-gray-200/80 border-dashed p-10 flex flex-col items-center justify-center text-center">
                <p class="text-[15px] font-bold text-slate-700">No announcements yet</p>
                <p class="text-[14px] text-slate-500 mt-1">Check back later for important updates from the barangay.</p>
            </div>
            <?php endif; ?>
        </div>

    </main>
</div>

<!-- Mobile Bottom Navigation (only visible < md screens) -->
<nav class="md:hidden fixed bottom-0 w-full bg-white/95 backdrop-blur-md border-t border-gray-200/60 pt-2.5 pb-6 px-1 z-50 flex justify-between items-end shadow-[0_-10px_20px_rgba(0,0,0,0.03)]">
    <a href="/brgy-waste-app-v3/public/resident/dashboard" class="flex flex-col items-center flex-1 pb-1 transform active:scale-95 transition-transform group">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="mb-1 group-active:stroke-[#334155]"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
        <span class="text-[10.5px] font-bold tracking-wide text-slate-500">Home</span>
    </a>
    <a href="/brgy-waste-app-v3/public/resident" class="flex flex-col items-center flex-1 pb-1 transform active:scale-95 transition-transform group">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="mb-1 group-active:stroke-[#334155]"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg>
        <span class="text-[10.5px] font-bold tracking-wide text-slate-500">Reports</span>
    </a>
    <div class="flex-1 flex justify-center sticky z-50">
        <a href="/brgy-waste-app-v3/public/resident/submit" class="flex flex-col items-center relative -top-[22px] group transform active:scale-95 transition-all">
            <div class="w-[58px] h-[58px] rounded-full bg-[#118B50] flex items-center justify-center border-[5px] border-[#f9fafb] shadow-md text-white mb-1 group-hover:bg-[#0e7442]">
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
            </div>
            <span class="text-[10.5px] font-extrabold tracking-wide text-[#118B50]">Report</span>
        </a>
    </div>
    <a href="/brgy-waste-app-v3/public/resident/announcements" class="flex flex-col items-center flex-1 pb-1 transform active:scale-95 transition-transform group">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#118B50" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="mb-1"><path d="m3 11 18-5v12L3 13v-2z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>
        <span class="text-[10.5px] font-extrabold tracking-wide text-[#118B50]">News</span>
    </a>
    <a href="/brgy-waste-app-v3/public/resident/profile" class="flex flex-col items-center flex-1 pb-1 transform active:scale-95 transition-transform group">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="mb-1 group-active:stroke-[#334155]"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        <span class="text-[10.5px] font-bold tracking-wide text-slate-500">Profile</span>
    </a>
</nav>

<?php include '../app/Views/layouts/notification-panel.php'; ?>
<?php include '../app/Views/layouts/footer.php'; ?>
