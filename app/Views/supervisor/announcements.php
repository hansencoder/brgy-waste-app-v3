<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php
$announcements = $data['announcements'] ?? [];
?>

<div class="min-h-screen bg-[#F8FAFC]">
    <div class="lg:flex lg:min-h-screen">
        <?php include __DIR__ . '/../layouts/supervisor_sidebar.php'; ?>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="sticky top-0 z-40 bg-white/90 backdrop-blur border-b border-slate-200/80 px-4 sm:px-6 py-3 flex flex-wrap items-center justify-between gap-2">
                <div class="min-w-0">
                    <h1 class="text-base sm:text-lg md:text-xl font-bold text-slate-900 tracking-tight truncate">Announcements</h1>
                    <p class="text-xs text-slate-500 font-medium truncate">Official barangay announcements</p>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <span class="text-xs text-slate-400 font-medium"><?php echo count($announcements); ?> announcements</span>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">

                    <?php if (!empty($announcements)): ?>
                        <div class="space-y-4">
                            <?php foreach ($announcements as $item): ?>
                                <?php
                                    $visibility = $item['visibility_name'] ?? 'Public';
                                    $badgeColor = $visibility === 'Public' ? 'bg-emerald-50 text-emerald-700' : ($visibility === 'Registered' ? 'bg-blue-50 text-blue-700' : 'bg-purple-50 text-purple-700');
                                ?>
                                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 hover:shadow-md transition">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-3 flex-wrap mb-2">
                                                <h3 class="text-lg font-bold text-slate-900"><?php echo htmlspecialchars($item['title']); ?></h3>
                                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-[10px] font-semibold <?php echo $badgeColor; ?>">
                                                    <?php echo htmlspecialchars($visibility); ?>
                                                </span>
                                            </div>
                                            <p class="text-sm text-slate-600 whitespace-pre-line"><?php echo nl2br(htmlspecialchars($item['content'])); ?></p>
                                            <div class="flex items-center gap-4 mt-3 text-xs text-slate-400">
                                                <span>Posted by <?php echo htmlspecialchars($item['author'] ?? 'Barangay Secretary'); ?></span>
                                                <span>•</span>
                                                <span><?php echo date('M d, Y h:i A', strtotime($item['created_at'])); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-12 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-slate-300 mx-auto mb-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="m3 11 18-5v12L3 13v-2z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>
                            <p class="text-slate-500 font-medium">No announcements available.</p>
                            <p class="text-sm text-slate-400 mt-1">Check back later for updates.</p>
                        </div>
                    <?php endif; ?>

                </div>
            </main>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>