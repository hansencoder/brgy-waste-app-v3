<?php include __DIR__ . '/../layouts/header.php'; ?>

<?php
$fullName = $_SESSION['user_name'] ?? 'Juan Dela Cruz';
$reports = $data['reports'] ?? [];

$categoryOptions = [];
$counts = ['total' => count($reports), 'pending' => 0, 'verified' => 0, 'in_progress' => 0, 'resolved' => 0, 'rejected' => 0];

foreach ($reports as $r) {
    if (!empty($r['waste_category'])) {
        $categoryOptions[] = $r['waste_category'];
    }
    $st = strtolower($r['status'] ?? 'pending');
    if (isset($counts[$st])) {
        $counts[$st]++;
    }
}
$categoryOptions = array_unique($categoryOptions);

function getReportBadge($status) {
    switch (strtolower($status)) {
        case 'pending':
            return ['class' => 'bg-amber-50 text-amber-900 border-amber-200', 'label' => 'Pending'];
        case 'verified':
            return ['class' => 'bg-blue-50 text-blue-900 border-blue-200', 'label' => 'Verified'];
        case 'in_progress':
            return ['class' => 'bg-purple-50 text-purple-900 border-purple-200', 'label' => 'In Progress'];
        case 'resolved':
            return ['class' => 'bg-emerald-50 text-emerald-900 border-emerald-200', 'label' => 'Resolved'];
        case 'rejected':
            return ['class' => 'bg-red-50 text-red-900 border-red-200', 'label' => 'Rejected'];
        default:
            return ['class' => 'bg-slate-100 text-slate-700 border-slate-200', 'label' => ucfirst($status)];
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
                            <span>Incident Tracker</span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">My Waste Reports</h1>
                        <p class="text-xs sm:text-sm text-slate-500 font-medium mt-1">Track the real-time status and collection history of your submitted waste issues.</p>
                    </div>
                    <a href="<?php echo app_url('resident/submit'); ?>" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[#0B2E22] hover:bg-[#083528] text-white font-bold text-xs sm:text-sm shadow-sm transition active:scale-[0.98] self-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        <span>Submit New Report</span>
                    </a>
                </div>

                <!-- KPI Status Counts -->
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
                    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">All Reports</p>
                        <p class="text-xl sm:text-2xl font-black text-slate-900 mt-1 font-mono"><?php echo $counts['total']; ?></p>
                    </div>
                    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-amber-700">Pending</p>
                        <p class="text-xl sm:text-2xl font-black text-amber-600 mt-1 font-mono"><?php echo $counts['pending']; ?></p>
                    </div>
                    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-blue-700">Verified</p>
                        <p class="text-xl sm:text-2xl font-black text-blue-600 mt-1 font-mono"><?php echo $counts['verified']; ?></p>
                    </div>
                    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-purple-700">In Progress</p>
                        <p class="text-xl sm:text-2xl font-black text-purple-600 mt-1 font-mono"><?php echo $counts['in_progress']; ?></p>
                    </div>
                    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-800">Resolved</p>
                        <p class="text-xl sm:text-2xl font-black text-emerald-800 mt-1 font-mono"><?php echo $counts['resolved']; ?></p>
                    </div>
                    <div class="bg-white rounded-2xl border border-slate-200 p-4 shadow-xs">
                        <p class="text-[10px] font-bold uppercase tracking-wider text-red-700">Rejected</p>
                        <p class="text-xl sm:text-2xl font-black text-red-600 mt-1 font-mono"><?php echo $counts['rejected']; ?></p>
                    </div>
                </div>

                <!-- Main Data Card -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                    
                    <!-- Search & Filter Controls -->
                    <div class="p-4 sm:p-5 border-b border-slate-100 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3">
                        <div class="relative flex-1 max-w-md">
                            <input type="text" id="reportSearch" placeholder="Search by Report ID, category, or notes..."
                                   class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                        </div>

                        <div class="flex items-center gap-2.5 flex-wrap">
                            <select id="statusFilter" class="px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs font-bold text-slate-700 outline-none cursor-pointer focus:border-emerald-500">
                                <option value="all">All Statuses</option>
                                <option value="pending">Pending</option>
                                <option value="verified">Verified</option>
                                <option value="in_progress">In Progress</option>
                                <option value="resolved">Resolved</option>
                                <option value="rejected">Rejected</option>
                            </select>

                            <select id="categoryFilter" class="px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs font-bold text-slate-700 outline-none cursor-pointer focus:border-emerald-500">
                                <option value="all">All Categories</option>
                                <?php foreach ($categoryOptions as $cat): ?>
                                    <option value="<?php echo htmlspecialchars(strtolower($cat)); ?>"><?php echo htmlspecialchars($cat); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Table View (Desktop) -->
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-slate-50/80 border-b border-slate-100 text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                    <th class="py-3 px-6">Report ID</th>
                                    <th class="py-3 px-6">Category</th>
                                    <th class="py-3 px-6">Volume</th>
                                    <th class="py-3 px-6">Date Logged</th>
                                    <th class="py-3 px-6">Status</th>
                                    <th class="py-3 px-6 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody id="reportTableBody" class="divide-y divide-slate-100">
                                <?php if (!empty($reports)): ?>
                                    <?php foreach ($reports as $r):
                                        $badge = getReportBadge($r['status'] ?? 'pending');
                                        $reportId = 'WR-' . str_pad($r['id'], 6, '0', STR_PAD_LEFT);
                                        $stSlug = strtolower($r['status'] ?? 'pending');
                                        $catSlug = strtolower($r['waste_category'] ?? '');
                                        $desc = strtolower($r['description'] ?? '');
                                    ?>
                                    <tr class="report-row hover:bg-slate-50/60 transition"
                                        data-id="<?php echo strtolower($reportId); ?>"
                                        data-status="<?php echo $stSlug; ?>"
                                        data-category="<?php echo $catSlug; ?>"
                                        data-desc="<?php echo htmlspecialchars($desc); ?>">
                                        <td class="py-4 px-6 font-mono font-bold text-slate-900">
                                            <a href="<?php echo app_url('resident/view_report/<?php echo $r['id']; ?>'); ?>" class="hover:text-emerald-700">
                                                <?php echo $reportId; ?>
                                            </a>
                                        </td>
                                        <td class="py-4 px-6 font-semibold text-slate-800">
                                            <?php echo htmlspecialchars($r['waste_category'] ?? 'General Waste'); ?>
                                        </td>
                                        <td class="py-4 px-6 text-slate-500">
                                            <?php echo htmlspecialchars($r['estimated_quantity'] ?? '—'); ?>
                                        </td>
                                        <td class="py-4 px-6 text-slate-500 font-mono">
                                            <?php echo date('M d, Y', strtotime($r['submission_date'])); ?>
                                        </td>
                                        <td class="py-4 px-6">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold border <?php echo $badge['class']; ?>">
                                                <?php echo $badge['label']; ?>
                                            </span>
                                        </td>
                                        <td class="py-4 px-6 text-right">
                                            <a href="<?php echo app_url('resident/view_report/<?php echo $r['id']; ?>'); ?>"
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-bold text-xs border border-emerald-200 transition">
                                                <span>Track</span>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr id="emptyTableRow">
                                        <td colspan="6" class="py-12 text-center text-slate-400 font-medium">
                                            You haven't submitted any waste reports yet. Click "Submit New Report" to get started.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Card List -->
                    <div class="md:hidden divide-y divide-slate-100 text-xs" id="mobileReportList">
                        <?php if (!empty($reports)): ?>
                            <?php foreach ($reports as $r):
                                $badge = getReportBadge($r['status'] ?? 'pending');
                                $reportId = 'WR-' . str_pad($r['id'], 6, '0', STR_PAD_LEFT);
                                $stSlug = strtolower($r['status'] ?? 'pending');
                                $catSlug = strtolower($r['waste_category'] ?? '');
                                $desc = strtolower($r['description'] ?? '');
                            ?>
                            <div class="report-card p-4 space-y-2"
                                 data-id="<?php echo strtolower($reportId); ?>"
                                 data-status="<?php echo $stSlug; ?>"
                                 data-category="<?php echo $catSlug; ?>"
                                 data-desc="<?php echo htmlspecialchars($desc); ?>">
                                <div class="flex items-center justify-between">
                                    <span class="font-mono font-bold text-slate-900"><?php echo $reportId; ?></span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold border <?php echo $badge['class']; ?>">
                                        <?php echo $badge['label']; ?>
                                    </span>
                                </div>
                                <p class="font-semibold text-slate-800"><?php echo htmlspecialchars($r['waste_category'] ?? 'General Waste'); ?></p>
                                <?php if (!empty($r['description'])): ?>
                                    <p class="text-xs text-slate-500 font-medium line-clamp-2 leading-relaxed"><?php echo htmlspecialchars($r['description']); ?></p>
                                <?php endif; ?>
                                <div class="flex items-center justify-between text-[11px] text-slate-400 font-mono pt-1">
                                    <span><?php echo date('M d, Y', strtotime($r['submission_date'])); ?></span>
                                    <a href="<?php echo app_url('resident/view_report/<?php echo $r['id']; ?>'); ?>" class="font-bold text-emerald-700">
                                        Track Status →
                                    </a>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="p-8 text-center text-slate-400 font-medium">No reports found.</div>
                        <?php endif; ?>
                    </div>

                </div>

            </div>
        </main>
    </div>
</div>

<script>
    const searchInput = document.getElementById('reportSearch');
    const statusFilter = document.getElementById('statusFilter');
    const categoryFilter = document.getElementById('categoryFilter');

    function filterReports() {
        const query = searchInput.value.toLowerCase().trim();
        const statusVal = statusFilter.value.toLowerCase();
        const catVal = categoryFilter.value.toLowerCase();

        const rows = document.querySelectorAll('.report-row, .report-card');
        let visibleCount = 0;

        rows.forEach(el => {
            const id = el.getAttribute('data-id') || '';
            const st = el.getAttribute('data-status') || '';
            const cat = el.getAttribute('data-category') || '';
            const desc = el.getAttribute('data-desc') || '';

            const matchQuery = !query || id.includes(query) || cat.includes(query) || desc.includes(query);
            const matchStatus = statusVal === 'all' || st === statusVal;
            const matchCategory = catVal === 'all' || cat === catVal;

            if (matchQuery && matchStatus && matchCategory) {
                el.style.display = '';
                visibleCount++;
            } else {
                el.style.display = 'none';
            }
        });
    }

    searchInput?.addEventListener('input', filterReports);
    statusFilter?.addEventListener('change', filterReports);
    categoryFilter?.addEventListener('change', filterReports);
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
