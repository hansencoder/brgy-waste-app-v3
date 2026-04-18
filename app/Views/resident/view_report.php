<?php include '../app/Views/layouts/header.php'; ?>
<?php
$fullName = $_SESSION['user_name'] ?? 'Juan Dela Cruz';
$firstName = isset($_SESSION['user_name']) ? explode(' ', trim($_SESSION['user_name']))[0] : 'Juan';

$report = $data['report'];
$timeline = $data['timeline'];

$statusColors = [
    'pending' => ['bg' => 'amber-50', 'text' => 'amber-600', 'dot' => 'amber-500'],
    'verified' => ['bg' => 'blue-50', 'text' => 'blue-600', 'dot' => 'blue-500'],
    'resolved' => ['bg' => 'emerald-50', 'text' => 'emerald-600', 'dot' => 'emerald-500']
];
$color = $statusColors[$report['status']] ?? $statusColors['pending'];
$imgPath = !empty($report['photo_path']) ? '/brgy-waste-app-v3/public/uploads/' . $report['photo_path'] : 'https://placehold.co/800x400?text=No+Image';

$events = [];
// 1. Always show Pending
$events[] = [
    'status' => 'pending',
    'title' => 'Report submitted',
    'date' => date('M j, Y, h:i A', strtotime($report['submission_date'])),
    'color' => $statusColors['pending']
];

if (!empty($timeline)) {
    // 2. Use real history if available
    foreach ($timeline as $t) {
        if ($t['new_status'] == 'verified') {
            $title = "Report verified by " . ($t['changed_by_name'] ?? 'secretary');
        } else if ($t['new_status'] == 'resolved') {
            $title = "Cleanup completed";
        } else {
            $title = "Status updated to " . ucfirst($t['new_status']);
        }

        $tColor = $statusColors[$t['new_status']] ?? $statusColors['pending'];
        
        $events[] = [
            'status' => $t['new_status'],
            'title' => $title,
            'date' => date('M j, Y, h:i A', strtotime($t['changed_at'])),
            'color' => $tColor
        ];
    }
} else {
    // 3. Fallback: Synthesize timeline from current status for testing purposes (when manually updated via DB)
    if ($report['status'] === 'verified' || $report['status'] === 'resolved') {
        $events[] = [
            'status' => 'verified',
            'title' => 'Report verified by secretary',
            // approximate dates if no history
            'date' => date('M j, Y, h:i A', strtotime($report['updated_at'])),
            'color' => $statusColors['verified']
        ];
    }
    
    if ($report['status'] === 'resolved') {
        $events[] = [
            'status' => 'resolved',
            'title' => 'Cleanup completed',
            'date' => date('M j, Y, h:i A', strtotime($report['updated_at'])),
            'color' => $statusColors['resolved']
        ];
    }
}
?>

<div class="min-h-screen bg-[#f9fafb] w-full font-sans antialiased text-slate-800 flex flex-col">
    <!-- Top Navbar -->
    <nav class="bg-[#118B50] border-b border-gray-200 sticky top-0 z-50 shadow-sm shrink-0">
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
                    <a href="/brgy-waste-app-v3/public/resident/dashboard" class="flex items-center gap-2 text-slate-300 hover:text-white hover:bg-[#10a95e] px-4 py-2.5 rounded-[12px] font-semibold text-[13.5px] transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="7" x="3" y="3" rx="1.5"/><rect width="7" height="7" x="14" y="3" rx="1.5"/><rect width="7" height="7" x="14" y="14" rx="1.5"/><rect width="7" height="7" x="3" y="14" rx="1.5"/></svg>
                        Home
                    </a>
                    <a href="/brgy-waste-app-v3/public/resident/my_report" class="flex items-center gap-2 bg-[#10a95e] text-white px-4 py-2.5 rounded-[12px] font-semibold text-[13.5px] shadow-sm shadow-[#118B50]/20 transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg>
                        Reports
                    </a>
                    <a href="/brgy-waste-app-v3/public/resident/submit" class="flex items-center gap-2 text-slate-300 hover:text-white hover:bg-[#10a95e] px-4 py-2.5 rounded-[12px] font-semibold text-[13.5px] transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                        Submit Report
                    </a>
                    <a href="/brgy-waste-app-v3/public/resident/announcements" class="flex items-center gap-2 text-slate-300 hover:text-white hover:bg-[#10a95e] px-4 py-2.5 rounded-[12px] font-semibold text-[13.5px] transition-all">
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
                    <a href="/brgy-waste-app-v3/public/auth/logout" class="flex items-center gap-2.5 px-3 py-1 rounded-full hover:bg-red-50 transition-colors group">
                        <div class="w-[34px] h-[34px] rounded-full border border-gray-200 flex items-center justify-center bg-gray-50 text-slate-500 shadow-sm group-hover:border-red-200 group-hover:bg-red-50 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-2xl mx-auto px-4 sm:px-6 py-8 md:py-10 flex-1 w-full pb-20">
        
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <a href="/brgy-waste-app-v3/public/resident/my_report" class="text-slate-400 hover:text-slate-600 transition-colors p-2 -ml-2 rounded-full hover:bg-slate-100 focus:ring-2 focus:ring-slate-200 outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                </a>
                <div>
                    <h1 class="text-[18px] font-extrabold text-slate-900 tracking-tight leading-none mb-1.5 flex items-center gap-3">
                        RPT-<?php echo str_pad($report['id'], 5, '0', STR_PAD_LEFT); ?>
                    </h1>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-[3px] bg-<?php echo $color['bg']; ?> text-<?php echo $color['text']; ?> rounded-full text-[10.5px] font-bold">
                        <span class="w-1.5 h-1.5 rounded-full bg-<?php echo $color['dot']; ?> shadow-sm"></span> <?php echo ucfirst($report['status']); ?>
                    </span>
                </div>
            </div>

            <?php if ($report['status'] === 'pending'): ?>
            
            <?php endif; ?>

            <button onclick="showDeleteConfirm()" class="flex items-center gap-2 bg-white border border-red-200 text-red-600 px-4 py-[9px] rounded-[10px] text-[13px] font-bold hover:bg-red-50 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-red-200">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                Delete
            </button>
        </div>

        <!-- Delete Confirmation Modal -->
        <div id="deleteModal" class="fixed inset-0 z-[90] hidden">
            <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="hideDeleteConfirm()"></div>
            <div class="absolute inset-0 flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 relative">
                    <button onclick="hideDeleteConfirm()" class="absolute top-4 right-4 p-1 rounded-full text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                    
                    <div class="text-center mb-4">
                        <div class="mx-auto w-14 h-14 rounded-full bg-red-100 flex items-center justify-center mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-600"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-1">Delete Report?</h3>
                        <p class="text-sm text-slate-500">This action cannot be undone. The report <strong class="text-slate-700">RPT-<?php echo str_pad($report['id'], 5, '0', STR_PAD_LEFT); ?></strong> will be permanently removed.</p>
                    </div>
                    
                    <div class="flex gap-3">
                        <button onclick="hideDeleteConfirm()" class="flex-1 px-4 py-2.5 border border-gray-200 text-slate-700 rounded-xl font-semibold text-sm hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <form action="/brgy-waste-app-v3/public/resident/delete_report/<?php echo $report['id']; ?>" method="POST" class="flex-1">
                            <button type="submit" class="w-full px-4 py-2.5 bg-red-600 text-white rounded-xl font-semibold text-sm hover:bg-red-700 transition-colors">
                                Delete Report
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- 1. Report Image -->
        <div class="mb-5 bg-white rounded-2xl overflow-hidden border border-gray-200/80 shadow-sm relative group cursor-pointer" onclick="document.getElementById('imageModal').classList.remove('hidden')">
            <div class="aspect-[16/9] w-full overflow-hidden bg-gray-50 relative">
                <img src="<?php echo htmlspecialchars($imgPath); ?>" alt="Waste Report" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors flex items-center justify-center">
                    <div class="w-10 h-10 rounded-full bg-white/90 shadow-lg flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-600"><polyline points="15 3 21 3 21 9"/><polyline points="9 21 3 21 3 15"/><line x1="21" y1="3" x2="14" y2="10"/><line x1="3" y1="21" x2="10" y2="14"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Image Fullscreen Modal -->
        <div id="imageModal" class="fixed inset-0 z-[100] hidden">
            <div class="absolute inset-0 bg-slate-900/95 backdrop-blur-sm cursor-zoom-out" onclick="this.parentElement.classList.add('hidden')"></div>
            <button onclick="document.getElementById('imageModal').classList.add('hidden')" class="absolute top-6 right-6 p-2 rounded-full bg-white/10 hover:bg-white/20 text-white transition-colors border border-white/20">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
            <div class="absolute inset-0 flex items-center justify-center pointer-events-none p-4 w-full h-full overflow-hidden">
                <img src="<?php echo htmlspecialchars($imgPath); ?>" class="w-auto h-auto max-w-[90vw] max-h-[90vh] object-contain pointer-events-auto rounded-lg shadow-2xl">
            </div>
        </div>

        <!-- 2. Description Card -->
        <div class="bg-white rounded-[18px] p-5 md:p-6 shadow-sm border border-gray-200/80 mb-5">
            <h3 class="text-[12px] font-bold uppercase tracking-wider text-slate-400 mb-2">Description</h3>
            <p class="text-[15px] text-slate-700 leading-relaxed font-medium"><?php echo nl2br(htmlspecialchars($report['description'])); ?></p>
        </div>

        <!-- 3. Map Section -->
        <div class="bg-white rounded-[18px] overflow-hidden shadow-sm border border-gray-200/80 mb-5 flex flex-col">
            <div id="reportMap" class="w-full h-[220px] bg-gray-100 border-b border-gray-100 z-10"></div>
            <div class="p-4 space-y-3">
                <!-- Location Name -->
                <div class="flex items-start gap-2 text-slate-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-[#118B50] flex-shrink-0 mt-0.5"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                    <div class="flex-1">
                        <p class="text-[12px] text-slate-400 font-medium uppercase tracking-wide mb-0.5">Location</p>
                        <p class="text-[13.5px] font-semibold text-slate-800" id="locationName"><?php echo htmlspecialchars($report['location_name'] ?? 'Unknown location'); ?></p>
                    </div>
                </div>
                
                <!-- Coordinates -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 text-slate-500">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-slate-400"><circle cx="12" cy="12" r="1"/><circle cx="12" cy="5" r="1"/><circle cx="12" cy="19" r="1"/></svg>
                        <span class="text-[12px] text-slate-400 font-medium">Coordinates</span>
                    </div>
                    <span class="text-[12.5px] font-mono font-medium tracking-tight text-slate-600" id="coordsText"><?php echo $report['latitude'] . ', ' . $report['longitude']; ?></span>
                </div>
                
                <!-- Copy Coordinates Button -->
                <button onclick="copyCoords()" class="w-full text-[12.5px] font-bold text-[#118B50] hover:text-[#0e7442] hover:bg-[#118B50]/10 px-3 py-2 rounded-lg transition-colors flex items-center justify-center gap-1.5" id="copyBtn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
                    Copy Coordinates
                </button>
            </div>
        </div>

        <!-- 4. Status Timeline -->
        <div class="bg-white rounded-[18px] p-6 shadow-sm border border-gray-200/80 mb-6 relative">
            <h3 class="text-[12px] font-bold uppercase tracking-wider text-slate-400 mb-6">Status Timeline</h3>
            
            <div class="relative pl-[14px] space-y-7 z-20">
                <!-- Vertical connecting line -->
                <div class="absolute left-[19px] top-2 bottom-6 w-[2px] bg-gray-100 rounded-full -z-10"></div>

                <?php foreach ($events as $index => $event): ?>
                <div class="relative flex items-start gap-4 group">
                    <!-- Dot Outline Background (to create gap in vertical line) -->
                    <div class="absolute left-[-5px] top-1 w-[14px] h-[14px] bg-white rounded-full flex items-center justify-center">
                        <div class="w-[10px] h-[10px] rounded-full bg-<?php echo $event['color']['dot']; ?> shadow-sm shadow-<?php echo $event['color']['dot']; ?>/40 z-20"></div>
                    </div>
                    
                    <div class="ml-4 flex-1">
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-[3px] bg-<?php echo $event['color']['bg']; ?> text-<?php echo $event['color']['text']; ?> rounded-full text-[10px] font-extrabold tracking-wide uppercase mb-1.5">
                            <?php echo $event['status']; ?>
                        </div>
                        <h4 class="text-[14px] font-bold text-slate-800 leading-snug mb-1"><?php echo $event['title']; ?></h4>
                        <div class="text-[12px] text-slate-400 font-medium"><?php echo $event['date']; ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- 5. Footer Metadata -->
        <div class="flex items-center justify-between px-2 text-[11.5px] text-slate-400 font-medium">
            <div class="flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                Submitted <?php echo date('n/j/Y', strtotime($report['submission_date'])); ?>
            </div>
            <div>
                Last updated: <?php echo date('n/j/Y', isset($report['updated_at']) ? strtotime($report['updated_at']) : strtotime($report['submission_date'])); ?>
            </div>
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
    <a href="/brgy-waste-app-v3/public/resident/profile" class="flex flex-col items-center flex-1 pb-1 transform active:scale-95 transition-transform group">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="mb-1 group-active:stroke-[#334155]"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        <span class="text-[10.5px] font-bold tracking-wide text-slate-500">Profile</span>
    </a>
</nav>

<!-- Leaflet Library Injection (If not loaded by header) -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<!-- Scripts -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Initialize Map
    if (typeof L !== 'undefined') {
        const lat = <?php echo htmlspecialchars($report['latitude']); ?>;
        const lng = <?php echo htmlspecialchars($report['longitude']); ?>;
        
        const map = L.map('reportMap', {
            center: [lat, lng],
            zoom: 16,
            zoomControl: false,
            dragging: !L.Browser.mobile,
            touchZoom: !L.Browser.mobile,
            scrollWheelZoom: true
        });

        // Add tiles with grayscale styled class
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OSM',
            className: 'map-tiles'
        }).addTo(map);

        // Add CSS filter directly to map pane
        document.head.insertAdjacentHTML('beforeend', '<style>.map-tiles { filter: grayscale(80%) opacity(0.8); }</style>');

        // Custom pinpoint mimicking the CivicLens theme
        const status = '<?php echo $report['status']; ?>';
        let pinColor = '#f59e0b'; // default amber
        if(status === 'verified') pinColor = '#3b82f6';
        if(status === 'resolved') pinColor = '#10b981';
        
        const customIcon = L.divIcon({
            html: `<div style="background-color: ${pinColor}; width: 16px; height: 16px; border-radius: 50%; border: 3px solid white; box-shadow: 0 3px 8px rgba(0,0,0,0.4);"></div>`,
            className: '', iconSize: [16, 16], iconAnchor: [8, 8]
        });

        const marker = L.marker([lat, lng], {icon: customIcon}).addTo(map);
    }
});

// 2. Copy Coordinates Function
function copyCoords() {
    const coords = document.getElementById('coordsText').innerText;
    navigator.clipboard.writeText(coords).then(() => {
        const btn = document.getElementById('copyBtn');
        const ogHTML = btn.innerHTML;
        btn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg> Copied`;
        btn.classList.add('bg-[#118B50]/10');

        setTimeout(() => {
            btn.innerHTML = ogHTML;
            btn.classList.remove('bg-[#118B50]/10');
        }, 2000);
    });
}

// 3. Delete Confirmation Modal Functions
function showDeleteConfirm() {
    document.getElementById('deleteModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function hideDeleteConfirm() {
    document.getElementById('deleteModal').classList.add('hidden');
    document.body.style.overflow = '';
}
</script>

<?php include '../app/Views/layouts/footer.php'; ?>
