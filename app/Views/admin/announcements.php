<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php
$announcements = $data['announcements'] ?? [];
$visibilities = $data['visibilities'] ?? [];
$totalAnnouncements = $data['total_announcements'] ?? count($announcements);
$activeAnnouncements = $data['active_announcements'] ?? 0;
$urgentAnnouncements = $data['urgent_announcements'] ?? 0;
$expiringAnnouncements = $data['expiring_announcements'] ?? 0;

$searchQuery = $_GET['search'] ?? '';
$filterVisibility = $_GET['visibility'] ?? 'all';

function getVisibilityBadge($visName) {
    switch (strtolower($visName ?? '')) {
        case 'public':
            return ['bg' => 'bg-emerald-50 text-emerald-900 border-emerald-300', 'label' => 'Public (Everyone)'];
        case 'registered':
        case 'residents':
            return ['bg' => 'bg-sky-50 text-sky-900 border-sky-300', 'label' => 'Residents Only'];
        case 'internal':
        case 'staff':
            return ['bg' => 'bg-purple-50 text-purple-900 border-purple-300', 'label' => 'Staff / Internal'];
        default:
            return ['bg' => 'bg-slate-100 text-slate-800 border-slate-300', 'label' => $visName ?: 'General'];
    }
}
?>

<style>
    body, * { font-family: 'Miranda Sans', sans-serif !important; font-optical-sizing: auto; }
    .mobile-sidebar-open #mobileSidebar { transform: translateX(0) !important; }
    #mobileSidebarOverlay { transition: opacity 0.25s ease, visibility 0.25s ease; opacity: 0; visibility: hidden; }
    .mobile-sidebar-open #mobileSidebarOverlay { opacity: 1; visibility: visible; }
</style>

<div class="min-h-screen bg-slate-50 text-slate-900 w-full flex font-sans antialiased">
    
    <!-- Mobile Sidebar Overlay -->
    <div id="mobileSidebarOverlay" class="fixed inset-0 bg-slate-950/40 z-40 lg:hidden"></div>

    <!-- Layout Wrapper -->
    <div class="lg:flex lg:min-h-screen w-full">
        
        <!-- Sidebar Layout Component -->
        <?php include __DIR__ . '/../layouts/admin_sidebar.php'; ?>

        <!-- Main Workspace -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <!-- Top App Bar Component -->
            <?php include __DIR__ . '/../layouts/admin_topbar.php'; ?>

            <!-- Main Scrollable Canvas -->
            <main class="flex-1 overflow-y-auto">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6">

                    <!-- Flash Messages -->
                    <?php if (isset($_SESSION['flash_success'])): ?>
                        <div class="p-4 sm:p-5 bg-emerald-50 border-2 border-emerald-200 text-emerald-950 rounded-2xl text-sm sm:text-base font-bold flex items-center justify-between shadow-xs">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                                </div>
                                <span><?php echo htmlspecialchars($_SESSION['flash_success']); ?></span>
                            </div>
                            <button onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 p-1.5 rounded-lg hover:bg-emerald-100 transition cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            </button>
                        </div>
                        <?php unset($_SESSION['flash_success']); ?>
                    <?php endif; ?>

                    <?php if (!empty($data['error'])): ?>
                        <div class="p-4 sm:p-5 bg-red-50 border-2 border-red-200 text-red-950 rounded-2xl text-sm sm:text-base font-bold flex items-center justify-between shadow-xs">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-xl bg-red-100 text-red-700 flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                </div>
                                <span><?php echo htmlspecialchars($data['error']); ?></span>
                            </div>
                            <button onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-900 p-1.5 rounded-lg hover:bg-red-100 transition cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            </button>
                        </div>
                    <?php endif; ?>

                    <!-- Page Action Header -->
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs sm:text-sm font-extrabold bg-emerald-100 text-emerald-900 border border-emerald-300">
                                    Public Broadcasts &amp; Advisories
                                </span>
                                <span class="text-sm text-slate-300 font-bold">•</span>
                                <span class="text-xs sm:text-sm font-bold text-slate-500"><?php echo $activeAnnouncements; ?> Active Broadcasts</span>
                            </div>
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                                Barangay Announcements Management
                            </h1>
                            <p class="text-sm sm:text-base text-slate-600 font-semibold mt-1">
                                Publish waste collection advisories, clean-up drives, schedule changes, and emergency notifications.
                            </p>
                        </div>

                        <!-- Main Action Buttons -->
                        <div class="flex flex-wrap items-center gap-2.5">
                            <button onclick="toggleCreatePanel()" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#0B2E22] hover:bg-[#084232] text-white text-xs sm:text-sm font-extrabold transition shadow-xs border border-emerald-900 cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                <span>Create Announcement</span>
                            </button>
                        </div>
                    </div>

                    <!-- KPI Statistics Row -->
                    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex flex-col justify-between">
                            <span class="text-xs font-black text-slate-500 uppercase tracking-wider">Total Broadcasts</span>
                            <div class="flex items-baseline justify-between my-1">
                                <p class="text-3xl font-extrabold text-slate-900 tracking-tight"><?php echo $totalAnnouncements; ?></p>
                                <span class="text-xs font-bold text-slate-600 bg-slate-100 px-2 py-0.5 rounded-md border border-slate-200">All Time</span>
                            </div>
                            <span class="text-[11px] font-bold text-slate-400">Total announcements created</span>
                        </div>

                        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex flex-col justify-between">
                            <span class="text-xs font-black text-emerald-700 uppercase tracking-wider">Active &amp; Live</span>
                            <div class="flex items-baseline justify-between my-1">
                                <p class="text-3xl font-extrabold text-emerald-700 tracking-tight"><?php echo $activeAnnouncements; ?></p>
                                <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200">Publicly Visible</span>
                            </div>
                            <span class="text-[11px] font-bold text-slate-400">Currently active broadcasts</span>
                        </div>

                        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex flex-col justify-between">
                            <span class="text-xs font-black text-amber-700 uppercase tracking-wider">Urgent Alerts</span>
                            <div class="flex items-baseline justify-between my-1">
                                <p class="text-3xl font-extrabold text-amber-600 tracking-tight"><?php echo $urgentAnnouncements; ?></p>
                                <span class="text-xs font-bold text-amber-700 bg-amber-50 px-2 py-0.5 rounded-md border border-amber-200">High Priority</span>
                            </div>
                            <span class="text-[11px] font-bold text-slate-400">Emergency &amp; delay notices</span>
                        </div>

                        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs flex flex-col justify-between">
                            <span class="text-xs font-black text-sky-700 uppercase tracking-wider">Expiring Soon</span>
                            <div class="flex items-baseline justify-between my-1">
                                <p class="text-3xl font-extrabold text-sky-700 tracking-tight"><?php echo $expiringAnnouncements; ?></p>
                                <span class="text-xs font-bold text-sky-700 bg-sky-50 px-2 py-0.5 rounded-md border border-sky-200">Next 7 Days</span>
                            </div>
                            <span class="text-[11px] font-bold text-slate-400">Expiring scheduled posts</span>
                        </div>
                    </div>

                    <!-- Create Announcement Collapsible Form Panel -->
                    <div id="createAnnouncementPanel" class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 sm:p-7 space-y-6 hidden">
                        <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-extrabold text-lg">
                                    ✍️
                                </div>
                                <div>
                                    <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight">Post New Community Announcement</h2>
                                    <p class="text-xs font-semibold text-slate-500">Draft and publish broadcasts with media attachments and scheduled expiration</p>
                                </div>
                            </div>
                            <button onclick="toggleCreatePanel()" class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                            </button>
                        </div>

                        <form action="/brgy-waste-app-v3/public/admin/announcements" method="POST" enctype="multipart/form-data" class="space-y-5">
                            <!-- Headline -->
                            <div>
                                <label class="block text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-2">Announcement Title / Headline <span class="text-red-500">*</span></label>
                                <input type="text" name="title" required placeholder="e.g. Special Hazardous Waste & E-Waste Collection Drive" 
                                       class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-bold text-slate-900 bg-slate-50 focus:bg-white focus:border-emerald-500 outline-none transition">
                            </div>

                            <!-- Content -->
                            <div>
                                <label class="block text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-2">Announcement Content &amp; Details <span class="text-red-500">*</span></label>
                                <textarea name="content" rows="5" required placeholder="Describe the announcement schedule, instructions for residents, designated drop-off points..." 
                                          class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-semibold text-slate-900 bg-slate-50 focus:bg-white focus:border-emerald-500 outline-none transition"></textarea>
                            </div>

                            <!-- Grid Configuration -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <!-- Target Audience -->
                                <div>
                                    <label class="block text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-2">Target Audience / Visibility</label>
                                    <select name="visibility_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-bold text-slate-800 bg-slate-50 focus:bg-white focus:border-emerald-500 outline-none cursor-pointer">
                                        <?php foreach ($visibilities as $vis): ?>
                                            <option value="<?php echo (int)$vis['visibility_id']; ?>"><?php echo htmlspecialchars($vis['visibility_name']); ?></option>
                                        <?php endforeach; ?>
                                        <?php if (empty($visibilities)): ?>
                                            <option value="1">Public</option>
                                        <?php endif; ?>
                                    </select>
                                </div>

                                <!-- Publish Date -->
                                <div>
                                    <label class="block text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-2">Publish Date &amp; Time</label>
                                    <input type="datetime-local" name="publish_date" 
                                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-bold text-slate-800 bg-slate-50 focus:bg-white focus:border-emerald-500 outline-none">
                                    <span class="text-[11px] font-semibold text-slate-400 mt-1 block">Leave empty to publish immediately</span>
                                </div>

                                <!-- Expiration Date -->
                                <div>
                                    <label class="block text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-2">Expiration Date (Optional)</label>
                                    <input type="datetime-local" name="expiration_date" 
                                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-bold text-slate-800 bg-slate-50 focus:bg-white focus:border-emerald-500 outline-none">
                                    <span class="text-[11px] font-semibold text-slate-400 mt-1 block">Auto-archive after this date</span>
                                </div>
                            </div>

                            <!-- Cover Image Attachment -->
                            <div>
                                <label class="block text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-2">Cover Banner / Photo (Optional)</label>
                                <input type="file" name="cover_image" accept="image/jpeg,image/png,image/webp" 
                                       class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 bg-slate-50 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-extrabold file:bg-emerald-100 file:text-emerald-900 hover:file:bg-emerald-200 cursor-pointer">
                                <span class="text-[11px] font-semibold text-slate-400 mt-1 block">Supported: JPG, PNG, WEBP (Max 5MB)</span>
                            </div>

                            <!-- Publish Checkbox & Submit -->
                            <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <label class="flex items-center gap-2.5 cursor-pointer">
                                    <input type="checkbox" name="is_published" checked class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500">
                                    <span class="text-xs sm:text-sm font-extrabold text-slate-800">Publish to live feed immediately</span>
                                </label>

                                <div class="flex items-center gap-2.5 justify-end">
                                    <button type="button" onclick="toggleCreatePanel()" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs sm:text-sm font-extrabold transition cursor-pointer">
                                        Cancel
                                    </button>
                                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#0B2E22] hover:bg-[#084232] text-white text-xs sm:text-sm font-extrabold transition shadow-xs border border-emerald-900 cursor-pointer flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m3 11 18-5v12L3 13v-2z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>
                                        Post &amp; Broadcast
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Search & Filter Bar -->
                    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-xs space-y-4">
                        <form method="GET" action="/brgy-waste-app-v3/public/admin/announcements" class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                            <!-- Search Field -->
                            <div class="relative flex-1 min-w-0">
                                <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3.5 top-1/2 -translate-y-1/2 h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                                <input type="text" name="search" value="<?php echo htmlspecialchars($searchQuery); ?>" placeholder="Search announcements by title or content keywords..." 
                                       class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-semibold text-slate-900 placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none bg-slate-50 focus:bg-white transition">
                            </div>

                            <!-- Dropdown & Actions -->
                            <div class="flex items-center gap-2.5 flex-wrap sm:flex-nowrap">
                                <select name="visibility" onchange="this.form.submit()" class="px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-bold text-slate-700 bg-slate-50 focus:bg-white focus:border-emerald-500 outline-none cursor-pointer">
                                    <option value="all" <?php echo $filterVisibility === 'all' ? 'selected' : ''; ?>>All Audiences</option>
                                    <?php foreach ($visibilities as $vis): ?>
                                        <option value="<?php echo htmlspecialchars($vis['visibility_name']); ?>" <?php echo $filterVisibility === $vis['visibility_name'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($vis['visibility_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                                <button type="submit" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-xl font-extrabold text-xs sm:text-sm transition border border-slate-200 cursor-pointer">
                                    Filter
                                </button>
                                <?php if (!empty($searchQuery) || $filterVisibility !== 'all'): ?>
                                    <a href="/brgy-waste-app-v3/public/admin/announcements" class="px-3 py-2.5 text-xs font-bold text-red-600 hover:underline">Reset</a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>

                    <!-- Announcements Feed Grid -->
                    <div class="space-y-4">
                        <?php if (!empty($announcements)): ?>
                            <?php foreach ($announcements as $item): 
                                $visProps = getVisibilityBadge($item['visibility_name'] ?? '');
                                $createdDate = date('M d, Y · h:i A', strtotime($item['created_at']));
                                $isUrgent = (stripos($item['title'], 'urgent') !== false || stripos($item['title'], 'alert') !== false || stripos($item['title'], 'emergency') !== false);
                            ?>
                                <div class="bg-white rounded-2xl border border-slate-200 hover:border-emerald-500/40 p-5 sm:p-6 shadow-xs hover:shadow-md transition-all duration-200 flex flex-col md:flex-row items-start justify-between gap-5 group">
                                    
                                    <!-- Left Details -->
                                    <div class="flex items-start gap-4 flex-1 min-w-0">
                                        <!-- Cover Thumbnail or Type Icon -->
                                        <?php if (!empty($item['cover_image'])): ?>
                                            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-slate-100 overflow-hidden border border-slate-200 shrink-0 shadow-2xs">
                                                <img src="<?php echo htmlspecialchars($item['cover_image']); ?>" alt="Banner" class="w-full h-full object-cover">
                                            </div>
                                        <?php else: ?>
                                            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-2xl shrink-0 shadow-2xs">
                                                📢
                                            </div>
                                        <?php endif; ?>

                                        <div class="space-y-2 flex-1 min-w-0">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold border <?php echo $visProps['bg']; ?>">
                                                    <?php echo $visProps['label']; ?>
                                                </span>
                                                <?php if ($isUrgent): ?>
                                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-red-100 text-red-900 border border-red-300">
                                                        URGENT ADVISORY
                                                    </span>
                                                <?php endif; ?>
                                                <?php if (empty($item['is_published'])): ?>
                                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-amber-100 text-amber-900 border border-amber-300">
                                                        DRAFT / UNPUBLISHED
                                                    </span>
                                                <?php endif; ?>
                                                <span class="text-xs font-semibold text-slate-400 font-mono"><?php echo $createdDate; ?></span>
                                            </div>

                                            <h3 class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight group-hover:text-emerald-950 transition">
                                                <?php echo htmlspecialchars($item['title']); ?>
                                            </h3>

                                            <p class="text-xs sm:text-sm font-semibold text-slate-600 leading-relaxed line-clamp-3">
                                                <?php echo htmlspecialchars($item['content']); ?>
                                            </p>

                                            <div class="pt-1 flex items-center gap-3 text-xs text-slate-400 font-semibold">
                                                <span>Posted by: <strong class="text-slate-700"><?php echo htmlspecialchars($item['author_name'] ?? 'Barangay Staff'); ?></strong></span>
                                                <?php if (!empty($item['expiration_date'])): ?>
                                                    <span>•</span>
                                                    <span>Expires: <strong class="text-slate-700"><?php echo date('M d, Y', strtotime($item['expiration_date'])); ?></strong></span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right Action Buttons -->
                                    <div class="flex items-center gap-2 shrink-0 self-end md:self-start pt-2 md:pt-0">
                                        <!-- Preview Button -->
                                        <button onclick="previewAnnouncement(<?php echo htmlspecialchars(json_encode($item)); ?>)" 
                                                class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-extrabold transition border border-slate-200 flex items-center gap-1.5 cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                            Preview
                                        </button>

                                        <?php if ($_SESSION['user_role'] == 'secretary' || $_SESSION['user_role'] == 'administrator'): ?>
                                            <a href="/brgy-waste-app-v3/public/admin/edit_announcement/<?php echo $item['id']; ?>" 
                                               class="px-3.5 py-2 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-900 text-xs font-extrabold transition border border-emerald-200 flex items-center gap-1.5">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/></svg>
                                                Edit
                                            </a>

                                            <button onclick="showDeleteConfirm(<?php echo $item['id']; ?>, '<?php echo addslashes(htmlspecialchars($item['title'])); ?>')" 
                                                    class="p-2 rounded-xl text-slate-400 hover:text-red-600 hover:bg-red-50 transition border border-transparent hover:border-red-200 cursor-pointer" title="Delete Announcement">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center space-y-3 shadow-xs">
                                <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center mx-auto text-2xl border border-emerald-200">
                                    📢
                                </div>
                                <h3 class="text-base font-extrabold text-slate-800">No announcements posted yet</h3>
                                <p class="text-xs font-semibold text-slate-400 max-w-sm mx-auto">Create and broadcast announcements to inform residents about schedule updates, cleaning activities, and barangay news.</p>
                                <button onclick="toggleCreatePanel()" class="mt-2 inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#0B2E22] hover:bg-[#084232] text-white text-xs font-extrabold transition shadow-xs">
                                    Post First Announcement
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </main>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div id="previewAnnouncementModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs hidden z-50 flex items-center justify-center p-4 sm:p-6 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full border border-slate-200 my-8 overflow-hidden">
        <div class="px-6 py-5 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-900 border border-emerald-300">
                    Live Resident Preview
                </span>
                <span id="prevVisibility" class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-slate-200 text-slate-800">Public</span>
            </div>
            <button onclick="closePreviewModal()" class="p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-200 transition cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <div class="p-6 sm:p-7 space-y-4 max-h-[75vh] overflow-y-auto">
            <div id="prevCoverContainer" class="w-full h-52 sm:h-64 rounded-xl bg-slate-100 overflow-hidden border border-slate-200 hidden">
                <img id="prevCoverImg" src="" alt="Cover Banner" class="w-full h-full object-cover">
            </div>

            <div>
                <h2 id="prevTitle" class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight mb-2"></h2>
                <div class="flex items-center gap-3 text-xs text-slate-400 font-semibold pb-3 border-b border-slate-100">
                    <span id="prevDate"></span>
                    <span>•</span>
                    <span id="prevAuthor"></span>
                </div>
            </div>

            <p id="prevContent" class="text-sm font-semibold text-slate-700 leading-relaxed whitespace-pre-line"></p>
        </div>

        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end">
            <button onclick="closePreviewModal()" class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-extrabold transition cursor-pointer">
                Close Preview
            </button>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteAnnouncementModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs hidden z-50 flex items-center justify-center p-4 sm:p-6 overflow-y-auto">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 border border-slate-200 relative">
        <button onclick="hideDeleteConfirm()" class="absolute top-4 right-4 p-2 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>

        <div class="text-center mb-5">
            <div class="mx-auto w-14 h-14 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            </div>
            <h3 class="text-lg font-extrabold text-slate-900 mb-1">Delete Announcement?</h3>
            <p class="text-xs font-semibold text-slate-500">Are you sure you want to permanently delete <strong id="announcementTitle" class="text-slate-800"></strong>?</p>
        </div>

        <div class="flex gap-3">
            <button onclick="hideDeleteConfirm()" class="flex-1 px-4 py-2.5 border border-slate-200 text-slate-700 rounded-xl font-extrabold text-xs hover:bg-slate-50 transition cursor-pointer">
                Cancel
            </button>
            <form id="deleteAnnouncementForm" action="/brgy-waste-app-v3/public/admin/delete_announcement" method="POST" class="flex-1">
                <input type="hidden" id="announcementId" name="announcement_id" value="">
                <button type="submit" class="w-full px-4 py-2.5 bg-red-600 text-white rounded-xl font-extrabold text-xs hover:bg-red-700 transition shadow-xs cursor-pointer">
                    Delete Announcement
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function toggleCreatePanel() {
    const panel = document.getElementById('createAnnouncementPanel');
    panel.classList.toggle('hidden');
    if (!panel.classList.contains('hidden')) {
        panel.scrollIntoView({ behavior: 'smooth' });
    }
}

function previewAnnouncement(item) {
    document.getElementById('prevTitle').textContent = item.title || '';
    document.getElementById('prevContent').textContent = item.content || '';
    document.getElementById('prevVisibility').textContent = item.visibility_name || 'Public';
    document.getElementById('prevDate').textContent = item.created_at ? new Date(item.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : '';
    document.getElementById('prevAuthor').textContent = item.author_name ? `Posted by ${item.author_name}` : 'Barangay Office';

    const coverContainer = document.getElementById('prevCoverContainer');
    const coverImg = document.getElementById('prevCoverImg');
    if (item.cover_image) {
        coverImg.src = item.cover_image;
        coverContainer.classList.remove('hidden');
    } else {
        coverContainer.classList.add('hidden');
    }

    document.getElementById('previewAnnouncementModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closePreviewModal() {
    document.getElementById('previewAnnouncementModal').classList.add('hidden');
    document.body.style.overflow = '';
}

function showDeleteConfirm(id, title) {
    document.getElementById('announcementId').value = id;
    document.getElementById('announcementTitle').textContent = title;
    document.getElementById('deleteAnnouncementModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function hideDeleteConfirm() {
    document.getElementById('deleteAnnouncementModal').classList.add('hidden');
    document.body.style.overflow = '';
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>