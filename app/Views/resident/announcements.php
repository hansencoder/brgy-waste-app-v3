<?php include __DIR__ . '/../layouts/header.php'; ?>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap');
  /* Apply Nunito Sans to everything EXCEPT material-icons */
  *:not(.material-icons) {
    font-family: 'Lato', sans-serif !important;
    }
  /* Ensure Material Icons render correctly */
    .material-icons {
    font-family: 'Material Icons' !important;
    font-weight: normal;
    font-style: normal;
    font-size: 24px;  
    display: inline-block;
    line-height: 1;
    text-transform: none;
    letter-spacing: normal;
    word-wrap: normal;
    white-space: nowrap;
    direction: ltr;
    vertical-align: middle;
    }
</style>
<?php
$fullName = $_SESSION['user_name'] ?? 'Juan Dela Cruz';
$firstName = isset($_SESSION['user_name']) ? explode(' ', trim($_SESSION['user_name']))[0] : 'Juan';
$announcements = $data['announcements'] ?? [];
$hasAnnouncements = !empty($announcements);

// Calculate stats
$total = count($announcements);
$urgent = 0;
$events = 0;
foreach ($announcements as $item) {
    $title = $item['title'] ?? '';
    $content = $item['content'] ?? '';
    if (stripos($title, 'collection') !== false || stripos($content, 'collection') !== false) {
        $urgent++;
    } elseif (stripos($title, 'clean') !== false || stripos($title, 'drive') !== false) {
        $events++;
    }
}
?>

<div class="min-h-screen bg-[#F8FAFC] text-slate-800 lg:flex">
    
    <!-- Reusable Sidebar -->
    <?php include __DIR__ . '/../layouts/resident_sidebar.php'; ?>

    <!-- Main Content -->
    <div class="flex-1">
        <header class="border-b border-slate-200 bg-white/90 px-4 py-4 backdrop-blur sm:px-6 lg:px-8 lg:py-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-[0.35em] text-[#0D9488]">Resident Portal</p>
                    <h1 class="mt-1 text-2xl font-black tracking-tight text-slate-900 sm:text-3xl">Announcements</h1>
                    <p class="mt-1 text-sm text-slate-500">Official notices and updates for registered residents of Barangay Dulong Bayan.</p>
                </div>
                <div class="inline-flex items-center gap-2 rounded-full border border-rose-200 bg-rose-50 px-3 py-2 text-sm font-semibold text-rose-600 shadow-sm">
                    <span class="h-2.5 w-2.5 rounded-full bg-[#EF4444]"></span>
                    <?php 
                        $unreadCount = isset($data['unread_count']) ? (int)$data['unread_count'] : 0;
                        echo $unreadCount > 0 ? $unreadCount . ' unread' : 'All read';
                    ?>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-4 py-5 sm:px-6 lg:px-8 lg:py-8">

            <!-- ====== STATS CARDS (MOVED TO TOP) ====== -->
            <section class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="rounded-[22px] border border-slate-200 bg-white p-5 shadow-[0_18px_45px_-30px_rgba(15,23,42,0.2)] flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-700">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16v12H7l-3 3z"/><path d="M8 8h8"/><path d="M8 12h6"/></svg>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-slate-900"><?php echo $total; ?></p>
                        <p class="text-sm font-semibold text-slate-500">Total Notices</p>
                    </div>
                </div>
                <div class="rounded-[22px] border border-slate-200 bg-white p-5 shadow-[0_18px_45px_-30px_rgba(15,23,42,0.2)] flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-[#FEE2E2] text-[#DC2626]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v4"/><path d="M12 17h.01"/><path d="M13 2 3 14h7l-1 8 10-12h-7l1-8Z"/></svg>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-[#DC2626]"><?php echo $urgent; ?></p>
                        <p class="text-sm font-semibold text-slate-500">Urgent</p>
                    </div>
                </div>
                <div class="rounded-[22px] border border-slate-200 bg-white p-5 shadow-[0_18px_45px_-30px_rgba(15,23,42,0.2)] flex items-center gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-50 text-emerald-700">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v18"/><path d="M3 12h18"/></svg>
                    </div>
                    <div>
                        <p class="text-3xl font-black text-emerald-700"><?php echo $events; ?></p>
                        <p class="text-sm font-semibold text-slate-500">Upcoming Events</p>
                    </div>
                </div>
            </section>

            <!-- ====== ANNOUNCEMENTS GRID ====== -->
            <div class="mt-6 grid gap-5 lg:grid-cols-[1.2fr_0.8fr]">
                <section class="space-y-3">
                    <?php if ($hasAnnouncements): ?>
                        <?php foreach ($announcements as $item): ?>
                            <?php
                                // Auto-detect type based on title/content
                                $title = $item['title'] ?? '';
                                $content = $item['content'] ?? '';
                                $type = 'Notice';
                                if (stripos($title, 'collection') !== false || stripos($content, 'collection') !== false) {
                                    $type = 'Urgent';
                                } elseif (stripos($title, 'clean') !== false || stripos($title, 'drive') !== false) {
                                    $type = 'Event';
                                }
                                
                                $badgeClass = 'bg-slate-100 text-slate-700';
                                $dotClass = 'bg-slate-500';
                                if ($type === 'Urgent') {
                                    $badgeClass = 'bg-[#FEE2E2] text-[#DC2626]';
                                    $dotClass = 'bg-[#EF4444]';
                                } elseif ($type === 'Event') {
                                    $badgeClass = 'bg-emerald-50 text-emerald-700';
                                    $dotClass = 'bg-emerald-600';
                                }
                                
                                $author = isset($item['author']) && $item['author'] !== '' 
                                    ? $item['author'] 
                                    : 'Brgy. Secretary';
                                $date = !empty($item['created_at']) 
                                    ? date('M j, Y', strtotime($item['created_at'])) 
                                    : 'Recently posted';
                            ?>
                            <article class="rounded-[22px] border border-slate-200 bg-white p-4 shadow-[0_18px_45px_-30px_rgba(15,23,42,0.2)] transition hover:-translate-y-0.5 hover:shadow-[0_20px_45px_-25px_rgba(15,23,42,0.25)]">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-[11px] font-semibold <?php echo htmlspecialchars($badgeClass); ?>">
                                        <span class="h-2 w-2 rounded-full <?php echo htmlspecialchars($dotClass); ?>"></span>
                                        <?php echo htmlspecialchars($type); ?>
                                    </span>
                                    <span class="text-sm text-slate-400"><?php echo htmlspecialchars($date); ?></span>
                                </div>
                                <h2 class="mt-3 text-lg font-black text-slate-900"><?php echo htmlspecialchars($title); ?></h2>
                                <p class="mt-2 text-sm leading-7 text-slate-600"><?php echo nl2br(htmlspecialchars($content)); ?></p>
                                <p class="mt-4 text-sm font-semibold text-slate-500">Posted by <?php echo htmlspecialchars($author); ?></p>
                            </article>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="rounded-[22px] border border-slate-200 bg-white p-12 text-center shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-4"><path d="M4 4h16v12H7l-3 3z"/><path d="M8 8h8"/><path d="M8 12h6"/></svg>
                            <p class="text-slate-500 font-medium">No announcements available</p>
                            <p class="text-sm text-slate-400 mt-1">Check back later for updates from the barangay.</p>
                        </div>
                    <?php endif; ?>
                </section>

                <!-- Featured Announcement Sidebar (unchanged) -->
                <aside class="rounded-[28px] bg-[#0B3024] p-4 text-white shadow-[0_20px_50px_-25px_rgba(2,26,16,0.55)] sm:p-5 lg:p-6">
                    <?php 
                        if ($hasAnnouncements && isset($announcements[0])) {
                            $featured = $announcements[0];
                            $featuredTitle = $featured['title'] ?? 'Barangay Update';
                            $featuredContent = $featured['content'] ?? '';
                            $featuredAuthor = isset($featured['author']) && $featured['author'] !== '' 
                                ? $featured['author'] 
                                : 'Brgy. Secretary';
                            $featuredDate = !empty($featured['created_at']) 
                                ? date('M j, Y', strtotime($featured['created_at'])) 
                                : 'Recently posted';
                        } else {
                            $featuredTitle = 'Special collection day — July 26, 2026';
                            $featuredContent = 'Due to the national holiday, waste collection in Zones A, B, and C is rescheduled to Saturday, July 26. Please place bins at the curb no later than 6:00 AM.';
                            $featuredAuthor = 'Brgy. Captain';
                            $featuredDate = 'July 22, 2026';
                        }
                    ?>
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-red-500/20 text-red-300">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2 3 14h7l-1 8 10-12h-7l1-8Z"/></svg>
                            </div>
                            <span class="rounded-full bg-[#FEE2E2] px-3 py-1 text-[11px] font-semibold text-[#DC2626]">Urgent</span>
                        </div>
                        <span class="text-sm text-emerald-100/80"><?php echo htmlspecialchars($featuredDate); ?></span>
                    </div>

                    <h2 class="mt-5 text-2xl font-black leading-tight sm:text-[28px]"><?php echo htmlspecialchars($featuredTitle); ?></h2>
                    <p class="mt-3 text-sm leading-7 text-emerald-50/80"><?php echo nl2br(htmlspecialchars($featuredContent)); ?></p>
                    <p class="mt-5 text-sm font-semibold text-emerald-200">Posted by <?php echo htmlspecialchars($featuredAuthor); ?></p>

                    <div class="mt-6 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2">
                            <span class="h-2.5 w-10 rounded-full bg-[#10B981]"></span>
                            <span class="h-2.5 w-2.5 rounded-full bg-white/30"></span>
                            <span class="h-2.5 w-2.5 rounded-full bg-white/30"></span>
                        </div>
                        <div class="flex items-center gap-2">
                            <button class="flex h-10 w-10 items-center justify-center rounded-full border border-white/20 bg-white/10 text-white transition hover:bg-white/20">&lt;</button>
                            <button class="flex h-10 w-10 items-center justify-center rounded-full border border-white/20 bg-white/10 text-white transition hover:bg-white/20">&gt;</button>
                        </div>
                    </div>
                </aside>
            </div>
        </main>
    </div>
</div>

<!-- Mobile Bottom Navigation (unchanged) -->
<nav class="fixed bottom-0 left-0 right-0 z-40 border-t border-slate-200 bg-white/95 px-2 py-3 backdrop-blur md:hidden">
    <!-- ... (same as before) ... -->
</nav>