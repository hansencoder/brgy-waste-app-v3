<?php include '../app/Views/layouts/header.php'; ?>
<?php
// Retrieve user info from session if available
$fullName = $_SESSION['user_name'] ?? 'Juan Dela Cruz';
$firstName = isset($_SESSION['user_name']) ? explode(' ', trim($_SESSION['user_name']))[0] : 'Juan';
?>

<div class="min-h-screen bg-[#f9fafb] w-full font-sans antialiased text-slate-800 flex flex-col">

    <!-- Top Navbar -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm shrink-0">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-[68px]">
                <!-- Left: Logo -->
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-[#118B50] flex items-center justify-center text-white shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                    </div>
                    <span class="font-extrabold text-[#111827] text-lg tracking-tight">CivicLens</span>
                </div>

                <!-- Center: Nav Links -->
                <div class="hidden md:flex items-center justify-center gap-1.5 flex-1">
                    <a href="/brgy-waste-app-v3/public/resident/dashboard" class="flex items-center gap-2 text-slate-500 hover:text-slate-800 hover:bg-slate-50 px-4 py-2.5 rounded-[12px] font-semibold text-[13.5px] transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="7" x="3" y="3" rx="1.5"/><rect width="7" height="7" x="14" y="3" rx="1.5"/><rect width="7" height="7" x="14" y="14" rx="1.5"/><rect width="7" height="7" x="3" y="14" rx="1.5"/></svg>
                        Home
                    </a>
                    <a href="/brgy-waste-app-v3/public/resident/my_report" class="flex items-center gap-2 bg-[#118B50] text-white px-4 py-2.5 rounded-[12px] font-semibold text-[13.5px] shadow-sm shadow-[#118B50]/20 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg>
                        Reports
                    </a>
                    <a href="/brgy-waste-app-v3/public/resident/submit" class="flex items-center gap-2 text-slate-500 hover:text-slate-800 hover:bg-slate-50 px-4 py-2.5 rounded-[12px] font-semibold text-[13.5px] transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                        Report
                    </a>
                    <a href="/brgy-waste-app-v3/public/resident/announcements" class="flex items-center gap-2 text-slate-500 hover:text-slate-800 hover:bg-slate-50 px-4 py-2.5 rounded-[12px] font-semibold text-[13.5px] transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 11 18-5v12L3 13v-2z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>
                        News
                    </a>
                </div>

                <!-- Right: Profile -->
                <div class="flex items-center gap-3 md:gap-5">
                    <button class="relative p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-50 rounded-full transition-colors hidden md:block">
                        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                        <span class="absolute top-[6px] right-[7px] w-[9px] h-[9px] rounded-full bg-red-500 border-2 border-white"></span>
                    </button>
                    
                    <div class="h-6 w-px bg-gray-200 hidden md:block"></div>

                    <div class="relative group cursor-pointer">
                        <div class="flex items-center gap-2.5 pr-1 py-1 rounded-full hover:bg-slate-50 transition-colors">
                            <div class="w-[34px] h-[34px] rounded-full border border-gray-200 flex items-center justify-center bg-gray-50 text-slate-500 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </div>
                            <span class="text-[13px] font-bold text-slate-700 hidden sm:block"><?php echo htmlspecialchars($firstName); ?></span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400 hidden sm:block"><path d="m6 9 6 6 6-6"/></svg>
                        </div>
                        <!-- Dropdown Menu -->
                        <div class="absolute right-0 top-[100%] mt-1 w-48 bg-white border border-gray-100 rounded-[12px] shadow-[0_10px_40px_rgba(0,0,0,0.08)] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all text-sm overflow-hidden z-50">
                            <a href="#" class="block px-4 py-3 text-slate-600 font-medium hover:bg-slate-50 hover:text-slate-900 transition-colors">Settings</a>
                            <div class="h-px bg-gray-100"></div>
                            <a href="/brgy-waste-app-v3/public/auth/logout" class="block px-4 py-3 text-red-600 font-medium hover:bg-red-50 transition-colors">Logout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-10 flex-1 w-full flex flex-col mb-16 md:mb-0">

        <!-- Flash Messages -->
        <?php if (isset($_SESSION['success'])): ?>
        <div id="flashSuccess" class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-center gap-3 animate-fade-in">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-600 shrink-0"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            <span class="text-emerald-800 font-semibold text-sm"><?php echo htmlspecialchars($_SESSION['success']); ?></span>
            <button onclick="document.getElementById('flashSuccess').remove()" class="ml-auto text-emerald-400 hover:text-emerald-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
        <div id="flashError" class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl flex items-center gap-3 animate-fade-in">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-red-600 shrink-0"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            <span class="text-red-800 font-semibold text-sm"><?php echo htmlspecialchars($_SESSION['error']); ?></span>
            <button onclick="document.getElementById('flashError').remove()" class="ml-auto text-red-400 hover:text-red-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- Header & Action Controls -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-6 md:mb-8">
            <h1 class="text-[32px] font-extrabold text-[#111827] tracking-tight leading-tight">My Reports</h1>
            
            <div class="flex items-center gap-3">
                <button class="flex items-center gap-2 bg-white border border-gray-200 text-slate-700 px-4 py-[9px] rounded-xl text-[13.5px] font-bold hover:bg-gray-50 transition-colors shadow-sm relative group cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#118B50]/20">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                    All Status
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-1 text-slate-400"><path d="m6 9 6 6 6-6"/></svg>
                </button>

                <button class="flex items-center gap-2 bg-white border border-gray-200 text-slate-700 px-4 py-[9px] rounded-xl text-[13.5px] font-bold hover:bg-gray-50 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-[#118B50]/20">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400"><path d="m3 16 4 4 4-4"/><path d="M7 20V4"/><path d="m21 8-4-4-4 4"/><path d="M17 4v16"/></svg>
                    Newest
                </button>
            </div>
        </div>

        <!-- Reports List -->
        <div class="space-y-4 mb-10">
            <?php if(!empty($data['reports'])): ?>
                <?php foreach($data['reports'] as $report): ?>
                <?php 
                    $statusColors = [
                        'pending' => ['bg' => 'amber-50', 'text' => 'amber-600', 'dot' => 'amber-500'],
                        'verified' => ['bg' => 'blue-50', 'text' => 'blue-600', 'dot' => 'blue-500'],
                        'resolved' => ['bg' => 'emerald-50', 'text' => 'emerald-600', 'dot' => 'emerald-500']
                    ];
                    $color = $statusColors[$report['status']] ?? $statusColors['pending'];
                    $imgPath = !empty($report['photo_path']) ? '/brgy-waste-app-v3/public/uploads/' . $report['photo_path'] : 'https://placehold.co/150x150?text=No+Image';
                ?>
                <!-- Card Row -->
                <a href="/brgy-waste-app-v3/public/resident/view_report/<?php echo $report['id']; ?>" class="block">
                    <div class="flex flex-col sm:flex-row gap-4 sm:gap-5 bg-white border border-gray-200/80 rounded-[18px] p-4 md:p-5 shadow-sm hover:shadow-md transition-shadow relative items-start group">
                        <!-- Thumbnail -->
                        <div class="w-16 h-16 sm:w-20 sm:h-20 md:w-[90px] md:h-[90px] rounded-[14px] overflow-hidden shrink-0 border border-gray-100 bg-gray-50 flex items-center justify-center self-start sm:self-center">
                            <img src="<?php echo htmlspecialchars($imgPath); ?>" alt="Report Thumbnail" class="w-full h-full object-cover">
                        </div>
                        
                        <!-- Center Content -->
                        <div class="flex-1 min-w-0 flex flex-col justify-center sm:self-center h-full">
                            <span class="text-[12.5px] font-mono text-slate-400 tracking-tight font-medium mb-1">RPT-<?php echo str_pad($report['id'], 5, '0', STR_PAD_LEFT); ?></span>
                            <h3 class="text-[15px] font-semibold text-slate-800 leading-snug truncate sm:max-w-[70%] mb-1.5 group-hover:text-[#118B50] transition-colors"><?php echo htmlspecialchars($report['description']); ?></h3>
                            <div class="text-[12px] text-slate-400 font-medium">Submitted <?php echo date('M j, Y', strtotime($report['created_at'])); ?></div>
                        </div>

                        <!-- Right Side: Badge & Updates -->
                        <div class="flex flex-col items-start sm:items-end sm:justify-center gap-1.5 absolute top-5 right-5 sm:static sm:top-auto sm:right-auto shrink-0 sm:self-center">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-<?php echo $color['bg']; ?> text-<?php echo $color['text']; ?> rounded-full text-[11.5px] font-bold">
                                <span class="w-1.5 h-1.5 rounded-full bg-<?php echo $color['dot']; ?> shadow-sm"></span> <?php echo ucfirst($report['status']); ?>
                            </span>
                            <div class="text-[11.5px] text-slate-400 font-medium hidden sm:block">Updated <?php echo date('M j, Y', strtotime($report['updated_at'])); ?></div>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="bg-white rounded-[12px] border border-gray-200/80 border-dashed p-10 flex flex-col items-center justify-center text-center">
                    <p class="text-[15px] font-bold text-slate-700">No reports found</p>
                    <p class="text-[14px] text-slate-500 mt-1">You haven't submitted any waste reports yet.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <div class="mt-auto flex justify-center items-center gap-2 border-t border-gray-200/60 pt-6 px-2">
            <button class="px-4 py-2 text-[14px] font-bold text-slate-500 hover:text-slate-800 hover:bg-slate-50 rounded-xl transition-colors focus:ring-2 focus:ring-slate-200 outline-none disabled:opacity-50" disabled>Previous</button>
            <div class="flex items-center gap-1">
                <button class="w-9 h-9 flex items-center justify-center rounded-[10px] bg-[#118B50] text-white text-[13.5px] font-bold shadow-sm shadow-[#118B50]/20">1</button>
                <button class="w-9 h-9 flex items-center justify-center rounded-[10px] text-slate-600 hover:bg-slate-50 text-[13.5px] font-bold transition-colors">2</button>
                <button class="w-9 h-9 flex items-center justify-center rounded-[10px] text-slate-600 hover:bg-slate-50 text-[13.5px] font-bold transition-colors">3</button>
            </div>
            <button class="px-4 py-2 text-[14px] font-bold text-slate-600 hover:text-[#118B50] hover:bg-emerald-50 rounded-xl transition-colors focus:ring-2 focus:ring-emerald-200 outline-none">Next</button>
        </div>

    </main>
</div>

<!-- Mobile Bottom Navigation (only visible < md screens) -->
<nav class="md:hidden fixed bottom-0 w-full bg-white/95 backdrop-blur-md border-t border-gray-200/60 pt-2.5 pb-6 px-1 z-50 flex justify-between items-end shadow-[0_-10px_20px_rgba(0,0,0,0.03)]">
    <a href="/brgy-waste-app-v3/public/resident/dashboard" class="flex flex-col items-center flex-1 pb-1 transform active:scale-95 transition-transform group">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="mb-1 group-active:stroke-[#334155]"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
        <span class="text-[10.5px] font-bold tracking-wide text-slate-500">Home</span>
    </a>
    <a href="/brgy-waste-app-v3/public/resident/my_report" class="flex flex-col items-center flex-1 pb-1 transform active:scale-95 transition-transform group">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#118B50" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="mb-1"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg>
        <span class="text-[10.5px] font-extrabold tracking-wide text-[#118B50]">Reports</span>
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
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="mb-1 group-active:stroke-[#334155]"><path d="m3 11 18-5v12L3 13v-2z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>
        <span class="text-[10.5px] font-bold tracking-wide text-slate-500">News</span>
    </a>
    <a href="#" class="flex flex-col items-center flex-1 pb-1 transform active:scale-95 transition-transform group">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="mb-1 group-active:stroke-[#334155]"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        <span class="text-[10.5px] font-bold tracking-wide text-slate-500">Profile</span>
    </a>
</nav>

<?php include '../app/Views/layouts/footer.php'; ?>
