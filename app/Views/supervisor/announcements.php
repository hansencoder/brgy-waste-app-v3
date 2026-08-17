<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php
$announcements = $data['announcements'] ?? [];
?>

<div class="min-h-screen bg-[#F8FAFC] flex">
    
    <!-- Sidebar -->
    <?php include __DIR__ . '/../layouts/supervisor_sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
        
        <!-- Topbar -->
        <?php include __DIR__ . '/../layouts/supervisor_topbar.php'; ?>

        <!-- Page Body -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8 space-y-6 max-w-7xl mx-auto w-full">

            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Barangay Bulletins &amp; Advisories</h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Official community announcements, schedule changes, and cleanup advisories</p>
                </div>

                <div class="flex items-center gap-2">
                    <span class="px-3 py-1.5 rounded-xl bg-slate-100 text-slate-700 text-xs font-semibold font-mono">
                        <?php echo count($announcements); ?> Published
                    </span>
                </div>
            </div>

            <!-- Bulletins Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <?php if (!empty($announcements)): ?>
                    <?php foreach ($announcements as $item):
                        $vis = $item['visibility_name'] ?? 'Public';
                        $visColor = $vis === 'Public' ? 'bg-emerald-50 text-emerald-800 border-emerald-200' : ($vis === 'Registered' ? 'bg-blue-50 text-blue-800 border-blue-200' : 'bg-purple-50 text-purple-800 border-purple-200');
                    ?>
                    <div class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 shadow-2xs space-y-4 flex flex-col justify-between hover:border-emerald-300 transition">
                        <div>
                            <div class="flex items-center justify-between gap-2 mb-3">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border <?php echo $visColor; ?>">
                                    <?php echo htmlspecialchars($vis); ?>
                                </span>
                                <span class="text-[11px] text-slate-400 font-mono">
                                    <?php echo date('M d, Y', strtotime($item['created_at'])); ?>
                                </span>
                            </div>

                            <h3 class="text-base font-bold text-slate-900 leading-snug">
                                <?php echo htmlspecialchars($item['title']); ?>
                            </h3>

                            <p class="text-xs text-slate-600 mt-2 leading-relaxed whitespace-pre-line line-clamp-4">
                                <?php echo htmlspecialchars($item['content']); ?>
                            </p>
                        </div>

                        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-400">
                            <span class="truncate">By <?php echo htmlspecialchars($item['author'] ?? 'Barangay Office'); ?></span>
                            <button onclick="readAnnouncement(<?php echo htmlspecialchars(json_encode($item)); ?>)" class="text-emerald-700 hover:text-emerald-900 font-semibold text-xs shrink-0 cursor-pointer">
                                Read Full →
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-full py-12 text-center text-slate-400 bg-white rounded-2xl border border-slate-200 text-xs">
                        No active announcements logged.
                    </div>
                <?php endif; ?>
            </div>

        </main>

    </div>
</div>

<!-- Modal Reader Dialog -->
<div id="readModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-50 hidden flex items-center justify-center p-4" onclick="closeReadModal()">
    <div class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl space-y-4" onclick="event.stopPropagation()">
        <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-3">
            <div>
                <span id="modalVis" class="px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-emerald-50 text-emerald-800"></span>
                <h3 id="modalTitle" class="text-lg font-bold text-slate-900 mt-2 leading-tight"></h3>
                <p id="modalMeta" class="text-xs text-slate-400 mt-1"></p>
            </div>
            <button onclick="closeReadModal()" class="text-slate-400 hover:text-slate-700 p-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>
        <div id="modalContent" class="text-xs sm:text-sm text-slate-700 leading-relaxed whitespace-pre-line max-h-96 overflow-y-auto pr-2"></div>
    </div>
</div>

<script>
function readAnnouncement(item) {
    document.getElementById('modalTitle').textContent = item.title;
    document.getElementById('modalVis').textContent = item.visibility_name || 'Public';
    document.getElementById('modalMeta').textContent = 'Posted by ' + (item.author || 'Barangay Office') + ' · ' + item.created_at;
    document.getElementById('modalContent').textContent = item.content;
    document.getElementById('readModal').classList.remove('hidden');
}
function closeReadModal() {
    document.getElementById('readModal').classList.add('hidden');
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>