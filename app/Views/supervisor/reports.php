<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php
$reports = $data['reports'] ?? [];
$statuses = $data['statuses'] ?? [];
$categories = $data['categories'] ?? [];
$puroks = $data['puroks'] ?? [];

// Helper for status badge
function getSupervisorReportBadge($status) {
    $map = [
        'Pending'     => ['bg' => 'bg-amber-50 text-amber-800 border-amber-200/60', 'dot' => 'bg-amber-500', 'label' => 'Pending'],
        'Verified'    => ['bg' => 'bg-blue-50 text-blue-800 border-blue-200/60', 'dot' => 'bg-blue-500', 'label' => 'Verified'],
        'In Progress' => ['bg' => 'bg-purple-50 text-purple-800 border-purple-200/60', 'dot' => 'bg-purple-500', 'label' => 'In Progress'],
        'Resolved'    => ['bg' => 'bg-emerald-50 text-emerald-800 border-emerald-200/60', 'dot' => 'bg-emerald-500', 'label' => 'Resolved'],
        'Rejected'    => ['bg' => 'bg-rose-50 text-rose-800 border-rose-200/60', 'dot' => 'bg-rose-500', 'label' => 'Rejected'],
    ];
    return $map[$status] ?? ['bg' => 'bg-slate-100 text-slate-700 border-slate-200', 'dot' => 'bg-slate-500', 'label' => $status];
}

// Get filter values from GET
$search = $_GET['search'] ?? '';
$statusFilter = $_GET['status'] ?? '';
$categoryFilter = (int)($_GET['category'] ?? 0);
$purokFilter = (int)($_GET['purok'] ?? 0);
$dateFrom = $_GET['date_from'] ?? '';
$dateTo = $_GET['date_to'] ?? '';
$quantityFilter = $_GET['quantity'] ?? '';
$conditionFilter = $_GET['condition'] ?? '';
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

            <!-- Header Title -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Incident Reports Monitoring</h1>
                    <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Filter, inspect, and monitor community waste reports submitted across all puroks</p>
                </div>

                <div class="flex items-center gap-2">
                    <span class="px-3 py-1.5 rounded-xl bg-slate-100 text-slate-700 text-xs font-semibold font-mono">
                        <?php echo count($reports); ?> Reports
                    </span>
                    <a href="<?php echo app_url('supervisor/reports?export=csv'); ?>" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#0B2E22] hover:bg-[#07281E] text-white text-xs font-semibold rounded-xl shadow-xs transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                        <span>Export CSV</span>
                    </a>
                </div>
            </div>

            <!-- Filter Panel -->
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-2xs space-y-4">
                <form method="GET" action="" class="space-y-3.5">
                    <input type="hidden" name="url" value="supervisor/reports">
                    
                    <!-- Row 1: Search, Status, Category, Purok -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Search Keyword</label>
                            <div class="relative">
                                <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="ID, resident, category..." class="w-full h-10 pl-9 pr-3 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-900 placeholder:text-slate-400 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10 outline-none transition">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Status</label>
                            <select name="status" class="w-full h-10 px-3 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-900 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10 outline-none transition bg-white">
                                <option value="">All Statuses</option>
                                <?php foreach ($statuses as $s): ?>
                                    <option value="<?php echo $s['status_name']; ?>" <?php echo $statusFilter === $s['status_name'] ? 'selected' : ''; ?>><?php echo $s['status_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Waste Category</label>
                            <select name="category" class="w-full h-10 px-3 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-900 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10 outline-none transition bg-white">
                                <option value="0">All Categories</option>
                                <?php foreach ($categories as $c): ?>
                                    <option value="<?php echo $c['category_id']; ?>" <?php echo $categoryFilter === (int)$c['category_id'] ? 'selected' : ''; ?>><?php echo $c['category_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Purok Area</label>
                            <select name="purok" class="w-full h-10 px-3 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-900 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10 outline-none transition bg-white">
                                <option value="0">All Puroks</option>
                                <?php foreach ($puroks as $p): ?>
                                    <option value="<?php echo $p['purok_id']; ?>" <?php echo $purokFilter === (int)$p['purok_id'] ? 'selected' : ''; ?>><?php echo $p['purok_name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Row 2: Date Range, Quantity, Condition, Action Buttons -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3 items-end">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Date From</label>
                            <input type="date" name="date_from" value="<?php echo htmlspecialchars($dateFrom); ?>" class="w-full h-10 px-3 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-900 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10 outline-none transition bg-white">
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Date To</label>
                            <input type="date" name="date_to" value="<?php echo htmlspecialchars($dateTo); ?>" class="w-full h-10 px-3 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-900 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10 outline-none transition bg-white">
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Est. Quantity</label>
                            <select name="quantity" class="w-full h-10 px-3 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-900 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10 outline-none transition bg-white">
                                <option value="">All Quantities</option>
                                <?php
                                $db = new Database();
                                $db->query("SELECT * FROM estimated_quantities WHERE is_active = 1 ORDER BY sort_order");
                                $quantities = $db->resultSet();
                                foreach ($quantities as $qty):
                                ?>
                                    <option value="<?php echo $qty['quantity_id']; ?>" <?php echo ($quantityFilter == $qty['quantity_id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($qty['quantity_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Waste Condition</label>
                            <select name="condition" class="w-full h-10 px-3 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-900 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10 outline-none transition bg-white">
                                <option value="">All Conditions</option>
                                <?php
                                $db->query("SELECT * FROM waste_conditions WHERE is_active = 1 ORDER BY condition_name");
                                $conditions = $db->resultSet();
                                foreach ($conditions as $cond):
                                ?>
                                    <option value="<?php echo $cond['condition_id']; ?>" <?php echo ($conditionFilter == $cond['condition_id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cond['condition_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="lg:col-span-2 flex items-center gap-2">
                            <button type="submit" class="flex-1 h-10 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs sm:text-sm shadow-xs transition">
                                Apply Filter
                            </button>
                            <a href="<?php echo app_url('supervisor/reports'); ?>" class="h-10 px-3.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 text-xs font-semibold transition flex items-center justify-center">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Filter bar footer with Per Page -->
            <div class="flex items-center justify-between gap-3 text-xs font-semibold text-slate-600">
                <span class="text-slate-500">Total Filtered: <strong class="text-slate-800" id="supTotalBadge"><?php echo count($reports); ?></strong> reports</span>
                <div class="flex items-center gap-1.5 bg-white border border-slate-200 rounded-xl px-3 py-1.5 shadow-2xs">
                    <span class="text-[11px] text-slate-500 font-medium">Show:</span>
                    <select id="supPerPageSelect" onchange="changeSupPerPage(this.value)" class="bg-transparent text-xs font-bold text-slate-800 outline-none cursor-pointer">
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="all">All</option>
                    </select>
                    <span class="text-[11px] text-slate-500 font-medium">/ page</span>
                </div>
            </div>

            <!-- Reports Data Table -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50/70 text-slate-500 uppercase text-[10px] font-semibold tracking-wider">
                                <th class="py-3 px-4">Report ID</th>
                                <th class="py-3 px-4">Submitted</th>
                                <th class="py-3 px-4">Reporter</th>
                                <th class="py-3 px-4">Category</th>
                                <th class="py-3 px-4">Volume</th>
                                <th class="py-3 px-4">Purok Area</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4 text-center">Supports</th>
                                <th class="py-3 px-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100" id="supReportsTbody">
                            <?php if (!empty($reports)): ?>
                                <?php foreach ($reports as $report):
                                    $badge = getSupervisorReportBadge($report['status'] ?? 'Pending');
                                    $reportId = 'WR-' . str_pad($report['id'], 5, '0', STR_PAD_LEFT);
                                ?>
                                <tr class="sup-report-row hover:bg-slate-50/80 transition">
                                    <td class="py-3.5 px-4 font-mono font-bold text-slate-900 text-xs">
                                        <a href="<?php echo app_url('supervisor/view_report/' . ($report['id'])); ?>" class="text-emerald-700 hover:underline">
                                            <?php echo htmlspecialchars($reportId); ?>
                                        </a>
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-500 whitespace-nowrap">
                                        <?php echo date('M d, Y', strtotime($report['submission_date'])); ?>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <div class="font-medium text-slate-800"><?php echo htmlspecialchars($report['reporter_name'] ?? 'Guest User'); ?></div>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="inline-flex px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-medium text-[11px]">
                                            <?php echo htmlspecialchars($report['waste_category'] ?? 'General Waste'); ?>
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-600">
                                        <?php echo htmlspecialchars($report['estimated_quantity'] ?? '—'); ?>
                                    </td>
                                    <td class="py-3.5 px-4 text-slate-700 font-medium whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                            <span><?php echo htmlspecialchars($report['purok'] ?? 'Barangay Wide'); ?></span>
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold border <?php echo $badge['bg']; ?>">
                                            <span class="w-1.5 h-1.5 rounded-full <?php echo $badge['dot']; ?>"></span>
                                            <?php echo $badge['label']; ?>
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-slate-100 text-slate-700 font-mono text-[11px] font-bold">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 10v12"/><path d="M15 5.88 14 10h5.83a2 2 0 0 1 1.92 2.56l-2.33 8A2 2 0 0 1 17.5 22H4a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h3"/><path d="M7 10 12 2a2 2 0 0 1 3 3.88Z"/></svg>
                                            <span><?php echo (int)($report['support_count'] ?? 0); ?></span>
                                        </span>
                                    </td>
                                    <td class="py-3.5 px-4 text-right whitespace-nowrap">
                                        <a href="<?php echo app_url('supervisor/view_report/' . ($report['id'])); ?>" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-emerald-50 hover:text-emerald-800 text-slate-700 text-xs font-semibold transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                            <span>Inspect</span>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr id="supNoRecords">
                                    <td colspan="9" class="py-12 text-center text-slate-400 text-xs">
                                        No incident reports match your current filter criteria.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer Bar -->
                <div id="supPaginationBar" class="p-4 bg-slate-50/90 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
                    <div class="text-slate-600 font-semibold" id="supPaginationInfo">
                        Showing <span id="supPageStart" class="font-bold text-slate-900">0</span> to <span id="supPageEnd" class="font-bold text-slate-900">0</span> of <span id="supPageTotal" class="font-bold text-slate-900">0</span> entries
                    </div>
                    
                    <div class="flex items-center gap-1" id="supPaginationControls">
                        <!-- Dynamic pagination buttons -->
                    </div>
                </div>
            </div>

        </main>

    </div>
</div>

<script>
    let supCurrentPage = 1;
    let supPerPage = 10;

    function changeSupPerPage(val) {
        supPerPage = (val === 'all') ? 999999 : parseInt(val, 10);
        supCurrentPage = 1;
        applySupPagination();
    }

    function goToSupPage(p) {
        supCurrentPage = p;
        applySupPagination();
    }

    function applySupPagination() {
        const rows = Array.from(document.querySelectorAll('.sup-report-row'));
        const total = rows.length;
        const totalPages = Math.max(1, Math.ceil(total / supPerPage));

        if (supCurrentPage > totalPages) supCurrentPage = totalPages;
        if (supCurrentPage < 1) supCurrentPage = 1;

        const startIdx = (supCurrentPage - 1) * supPerPage;
        const endIdx = Math.min(startIdx + supPerPage, total);

        rows.forEach((r, idx) => {
            r.style.display = (idx >= startIdx && idx < endIdx) ? '' : 'none';
        });

        const pStart = document.getElementById('supPageStart');
        const pEnd = document.getElementById('supPageEnd');
        const pTotal = document.getElementById('supPageTotal');

        if (pStart) pStart.textContent = total > 0 ? (startIdx + 1) : 0;
        if (pEnd) pEnd.textContent = endIdx;
        if (pTotal) pTotal.textContent = total;

        renderSupPaginationControls(totalPages);
    }

    function renderSupPaginationControls(totalPages) {
        const container = document.getElementById('supPaginationControls');
        if (!container) return;

        if (totalPages <= 1 && supPerPage >= 999999) {
            container.innerHTML = '';
            return;
        }

        let html = '';

        const prevDisabled = (supCurrentPage <= 1);
        html += `
            <button onclick="goToSupPage(${supCurrentPage - 1})" ${prevDisabled ? 'disabled' : ''} 
                    class="px-2.5 py-1.5 rounded-lg border text-xs font-bold transition flex items-center gap-1 ${prevDisabled ? 'border-slate-200 text-slate-300 cursor-not-allowed bg-slate-50' : 'border-slate-300 text-slate-700 bg-white hover:bg-slate-100 cursor-pointer active:scale-95'}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                <span>Prev</span>
            </button>
        `;

        let startPage = Math.max(1, supCurrentPage - 2);
        let endPage = Math.min(totalPages, startPage + 4);
        if (endPage - startPage < 4) {
            startPage = Math.max(1, endPage - 4);
        }

        if (startPage > 1) {
            html += `<button onclick="goToSupPage(1)" class="w-8 h-8 rounded-lg border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold transition cursor-pointer">1</button>`;
            if (startPage > 2) html += `<span class="px-1 text-slate-400 font-bold">...</span>`;
        }

        for (let p = startPage; p <= endPage; p++) {
            const isActive = (p === supCurrentPage);
            html += `
                <button onclick="goToSupPage(${p})" 
                        class="w-8 h-8 rounded-lg text-xs font-bold transition cursor-pointer ${isActive ? 'bg-[#0B2E22] text-white shadow-xs' : 'border border-slate-200 bg-white hover:bg-slate-100 text-slate-700'}">
                    ${p}
                </button>
            `;
        }

        if (endPage < totalPages) {
            if (endPage < totalPages - 1) html += `<span class="px-1 text-slate-400 font-bold">...</span>`;
            html += `<button onclick="goToSupPage(${totalPages})" class="w-8 h-8 rounded-lg border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold transition cursor-pointer">${totalPages}</button>`;
        }

        const nextDisabled = (supCurrentPage >= totalPages);
        html += `
            <button onclick="goToSupPage(${supCurrentPage + 1})" ${nextDisabled ? 'disabled' : ''} 
                    class="px-2.5 py-1.5 rounded-lg border text-xs font-bold transition flex items-center gap-1 ${nextDisabled ? 'border-slate-200 text-slate-300 cursor-not-allowed bg-slate-50' : 'border-slate-300 text-slate-700 bg-white hover:bg-slate-100 cursor-pointer active:scale-95'}">
                <span>Next</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </button>
        `;

        container.innerHTML = html;
    }

    document.addEventListener('DOMContentLoaded', function() {
        applySupPagination();
    });
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>