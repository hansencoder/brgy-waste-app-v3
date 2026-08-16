<?php include __DIR__ . '/../layouts/header.php'; ?>

<?php
$fullName = $_SESSION['user_name'] ?? 'Juan Dela Cruz';
$announcements = $data['announcements'] ?? [];
$hasAnnouncements = !empty($announcements);

$total = count($announcements);
$urgent = 0;
$events = 0;
foreach ($announcements as $item) {
    $title = $item['title'] ?? '';
    $content = $item['content'] ?? '';
    if (stripos($title, 'collection') !== false || stripos($content, 'collection') !== false || stripos($title, 'urgent') !== false) {
        $urgent++;
    } elseif (stripos($title, 'clean') !== false || stripos($title, 'drive') !== false || stripos($title, 'event') !== false) {
        $events++;
    }
}
?>

<style>
    body, * { font-family: 'Miranda Sans', sans-serif !important; font-optical-sizing: auto; }
</style>

<div class="flex h-screen bg-slate-50 overflow-hidden w-full">
    <!-- Resident Sidebar -->
    <?php include __DIR__ . '/../layouts/resident_sidebar.php'; ?>

    <!-- Main Content Wrapper -->
    <div class="flex flex-col flex-1 min-w-0 overflow-hidden">
        <!-- Resident Topbar -->
        <?php include __DIR__ . '/../layouts/resident_topbar.php'; ?>

        <!-- Scrollable Main Content -->
        <main class="flex-1 overflow-y-auto bg-slate-50 focus:outline-none">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6">

                <!-- Header Banner -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2 border-b border-slate-200">
                    <div>
                        <div class="flex items-center gap-2 text-xs font-bold text-emerald-700 uppercase tracking-wider mb-1">
                            <span>Resident Portal</span>
                            <span>•</span>
                            <span>Barangay Bulletin</span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Barangay Announcements</h1>
                        <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1">Official bulletins, holiday schedule adjustments, and waste management advisories.</p>
                    </div>
                </div>

                <!-- KPI Metric Summary -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-slate-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 11 18-5v12L3 14v-3z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-slate-900 font-mono"><?php echo $total; ?></p>
                            <p class="text-xs font-semibold text-slate-500">Total Bulletins</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-red-50 text-red-600 flex items-center justify-center shrink-0 border border-red-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-red-600 font-mono"><?php echo $urgent; ?></p>
                            <p class="text-xs font-semibold text-slate-500">Urgent Advisories</p>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0 border border-emerald-100">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-emerald-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 12 12"/></svg>
                        </div>
                        <div>
                            <p class="text-2xl font-black text-emerald-800 font-mono"><?php echo $events; ?></p>
                            <p class="text-xs font-semibold text-slate-500">Community Drives</p>
                        </div>
                    </div>
                </div>

                <!-- Filter & Search Strip -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-4 sm:p-5 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                    <div class="relative flex-1 max-w-md">
                        <input type="text" id="announceSearch" placeholder="Search announcements..."
                               class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    </div>

                    <div class="flex items-center gap-2">
                        <button type="button" onclick="filterType('all')" class="type-filter active px-3 py-1.5 rounded-lg text-xs font-bold bg-[#0B2E22] text-white transition cursor-pointer">
                            All
                        </button>
                        <button type="button" onclick="filterType('urgent')" class="type-filter px-3 py-1.5 rounded-lg text-xs font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 transition cursor-pointer">
                            Urgent
                        </button>
                        <button type="button" onclick="filterType('event')" class="type-filter px-3 py-1.5 rounded-lg text-xs font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 transition cursor-pointer">
                            Events
                        </button>
                    </div>
                </div>

                <!-- Announcements List -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5" id="announcementGrid">
                    <?php if ($hasAnnouncements): ?>
                        <?php foreach ($announcements as $item):
                            $title = $item['title'] ?? '';
                            $content = $item['content'] ?? '';
                            $type = 'Notice';
                            $typeSlug = 'notice';
                            $badgeClass = 'bg-slate-100 text-slate-700 border-slate-200';

                            if (stripos($title, 'collection') !== false || stripos($content, 'collection') !== false || stripos($title, 'urgent') !== false) {
                                $type = 'Urgent Notice';
                                $typeSlug = 'urgent';
                                $badgeClass = 'bg-red-50 text-red-800 border-red-200';
                            } elseif (stripos($title, 'clean') !== false || stripos($title, 'drive') !== false || stripos($title, 'event') !== false) {
                                $type = 'Community Event';
                                $typeSlug = 'event';
                                $badgeClass = 'bg-emerald-50 text-emerald-800 border-emerald-200';
                            }

                            $author = !empty($item['author']) ? $item['author'] : 'Barangay Council';
                            $date = !empty($item['created_at']) ? date('M d, Y', strtotime($item['created_at'])) : date('M d, Y');
                        ?>
                        <article class="announce-card bg-white rounded-2xl border border-slate-200 shadow-xs p-6 hover:shadow-md transition flex flex-col justify-between space-y-4"
                                 data-type="<?php echo $typeSlug; ?>"
                                 data-title="<?php echo htmlspecialchars(strtolower($title)); ?>"
                                 data-content="<?php echo htmlspecialchars(strtolower($content)); ?>">
                            <div>
                                <div class="flex items-center justify-between gap-2 mb-3">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold border <?php echo $badgeClass; ?>">
                                        <?php echo $type; ?>
                                    </span>
                                    <span class="text-[11px] font-mono text-slate-400"><?php echo $date; ?></span>
                                </div>
                                <h2 class="text-base font-extrabold text-slate-900 leading-snug"><?php echo htmlspecialchars($title); ?></h2>
                                <p class="text-xs text-slate-600 font-medium leading-relaxed mt-2 line-clamp-4">
                                    <?php echo nl2br(htmlspecialchars($content)); ?>
                                </p>
                            </div>

                            <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
                                <span class="text-slate-400 text-[11px] font-medium">By <?php echo htmlspecialchars($author); ?></span>
                                <button type="button" onclick="readAnnouncement(<?php echo htmlspecialchars(json_encode([
                                    'title' => $title,
                                    'type' => $type,
                                    'content' => $content,
                                    'author' => $author,
                                    'date' => $date
                                ]), ENT_QUOTES, 'UTF-8'); ?>)" class="text-xs font-bold text-emerald-700 hover:text-emerald-900 cursor-pointer">
                                    Read Full →
                                </button>
                            </div>
                        </article>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-span-full bg-white rounded-2xl border border-slate-200 p-12 text-center text-slate-400 text-xs">
                            No announcements published at this time.
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </main>
    </div>
</div>

<!-- Modal Reader -->
<div id="announceModal" class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden border border-slate-200">
        <div class="bg-[#0B2E22] px-6 py-4 flex items-center justify-between text-white">
            <span id="modalTypeBadge" class="text-xs font-bold uppercase tracking-wider text-emerald-300">Barangay Notice</span>
            <button onclick="closeAnnounceModal()" class="text-emerald-300 hover:text-white cursor-pointer transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div>
                <h3 id="modalTitle" class="text-lg font-extrabold text-slate-900 leading-snug"></h3>
                <p id="modalMeta" class="text-xs text-slate-400 font-mono mt-1"></p>
            </div>
            <div id="modalContent" class="text-xs sm:text-sm text-slate-700 leading-relaxed max-h-96 overflow-y-auto whitespace-pre-line border-t border-b border-slate-100 py-3"></div>
            <div class="flex justify-end">
                <button type="button" onclick="closeAnnounceModal()" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs cursor-pointer">
                    Close Notice
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function filterType(type) {
        document.querySelectorAll('.type-filter').forEach(btn => {
            btn.className = 'type-filter px-3 py-1.5 rounded-lg text-xs font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 transition cursor-pointer';
        });
        event.target.className = 'type-filter active px-3 py-1.5 rounded-lg text-xs font-bold bg-[#0B2E22] text-white transition cursor-pointer';

        const cards = document.querySelectorAll('.announce-card');
        cards.forEach(c => {
            const cardType = c.getAttribute('data-type');
            if (type === 'all' || cardType === type) {
                c.style.display = '';
            } else {
                c.style.display = 'none';
            }
        });
    }

    const searchInput = document.getElementById('announceSearch');
    searchInput?.addEventListener('input', function() {
        const q = this.value.toLowerCase().trim();
        const cards = document.querySelectorAll('.announce-card');
        cards.forEach(c => {
            const t = c.getAttribute('data-title') || '';
            const cnt = c.getAttribute('data-content') || '';
            if (!q || t.includes(q) || cnt.includes(q)) {
                c.style.display = '';
            } else {
                c.style.display = 'none';
            }
        });
    });

    function readAnnouncement(data) {
        document.getElementById('modalTypeBadge').textContent = data.type;
        document.getElementById('modalTitle').textContent = data.title;
        document.getElementById('modalMeta').textContent = `Posted on ${data.date} • By ${data.author}`;
        document.getElementById('modalContent').textContent = data.content;
        document.getElementById('announceModal').classList.remove('hidden');
    }

    function closeAnnounceModal() {
        document.getElementById('announceModal').classList.add('hidden');
    }
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>