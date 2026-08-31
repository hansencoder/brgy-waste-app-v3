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

                            <select id="perPageFilter" onchange="changePerPage(this.value)" class="px-3 py-2 rounded-xl border border-slate-200 bg-slate-50 text-xs font-bold text-slate-700 outline-none cursor-pointer focus:border-emerald-500">
                                <option value="10" selected>10 per page</option>
                                <option value="25">25 per page</option>
                                <option value="50">50 per page</option>
                                <option value="all">All reports</option>
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
                                            <a href="<?php echo app_url('resident/view_report/' . ($r['id']) . '?from=my_report'); ?>" class="hover:text-emerald-700">
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
                                            <a href="<?php echo app_url('resident/view_report/' . ($r['id']) . '?from=my_report'); ?>"
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-bold text-xs border border-emerald-200 transition">
                                                <span>Track</span>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <tr id="noFilterResultsRow" class="hidden">
                                        <td colspan="6" class="py-12 text-center text-slate-400 font-medium">
                                            No waste reports match your search criteria.
                                        </td>
                                    </tr>
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
                                    <a href="<?php echo app_url('resident/view_report/' . ($r['id']) . '?from=my_report'); ?>" class="font-bold text-emerald-700">
                                        Track Status →
                                    </a>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            <div id="noFilterResultsCard" class="p-8 text-center text-slate-400 font-medium hidden">
                                No waste reports match your search criteria.
                            </div>
                        <?php else: ?>
                            <div class="p-8 text-center text-slate-400 font-medium">No reports found.</div>
                        <?php endif; ?>
                    </div>

                    <!-- Pagination Footer Bar -->
                    <div id="paginationBar" class="p-4 bg-slate-50/90 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
                        <div class="text-slate-600 font-semibold" id="paginationInfo">
                            Showing <span id="pageStart" class="font-bold text-slate-900">0</span> to <span id="pageEnd" class="font-bold text-slate-900">0</span> of <span id="pageTotal" class="font-bold text-slate-900">0</span> reports
                        </div>
                        
                        <div class="flex items-center gap-1" id="paginationControls">
                            <!-- Dynamic pagination buttons -->
                        </div>
                    </div>

                </div>

            </div>
        </main>
    </div>
</div>

<script>
    let currentPage = 1;
    let perPage = 10;

    function changePerPage(val) {
        perPage = (val === 'all') ? 999999 : parseInt(val, 10);
        currentPage = 1;
        applyPagination();
    }

    function goToPage(p) {
        currentPage = p;
        applyPagination();
        document.querySelector('#reportTableBody')?.closest('.bg-white')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function applyPagination() {
        const query = (document.getElementById('reportSearch')?.value || '').toLowerCase().trim();
        const statusVal = (document.getElementById('statusFilter')?.value || 'all').toLowerCase();
        const catVal = (document.getElementById('categoryFilter')?.value || 'all').toLowerCase();

        const desktopRows = Array.from(document.querySelectorAll('.report-row'));
        const mobileCards = Array.from(document.querySelectorAll('.report-card'));

        const matchedDesktop = [];
        const matchedMobile = [];

        desktopRows.forEach((row, idx) => {
            const id = (row.getAttribute('data-id') || '').toLowerCase();
            const st = (row.getAttribute('data-status') || '').toLowerCase();
            const cat = (row.getAttribute('data-category') || '').toLowerCase();
            const desc = (row.getAttribute('data-desc') || '').toLowerCase();

            const matchQuery = !query || id.includes(query) || cat.includes(query) || desc.includes(query);
            const matchStatus = (statusVal === 'all') || (st === statusVal);
            const matchCategory = (catVal === 'all') || (cat === catVal);

            if (matchQuery && matchStatus && matchCategory) {
                matchedDesktop.push(row);
                if (mobileCards[idx]) matchedMobile.push(mobileCards[idx]);
            } else {
                row.style.display = 'none';
                if (mobileCards[idx]) mobileCards[idx].style.display = 'none';
            }
        });

        const total = matchedDesktop.length;
        const totalPages = Math.max(1, Math.ceil(total / perPage));

        if (currentPage > totalPages) currentPage = totalPages;
        if (currentPage < 1) currentPage = 1;

        const startIdx = (currentPage - 1) * perPage;
        const endIdx = Math.min(startIdx + perPage, total);

        matchedDesktop.forEach((row, idx) => {
            row.style.display = (idx >= startIdx && idx < endIdx) ? '' : 'none';
        });
        matchedMobile.forEach((card, idx) => {
            card.style.display = (idx >= startIdx && idx < endIdx) ? '' : 'none';
        });

        const pStart = document.getElementById('pageStart');
        const pEnd = document.getElementById('pageEnd');
        const pTotal = document.getElementById('pageTotal');

        if (pStart) pStart.textContent = total > 0 ? (startIdx + 1) : 0;
        if (pEnd) pEnd.textContent = endIdx;
        if (pTotal) pTotal.textContent = total;

        const noResultsRow = document.getElementById('noFilterResultsRow');
        const noResultsCard = document.getElementById('noFilterResultsCard');
        if (total === 0 && desktopRows.length > 0) {
            if (noResultsRow) noResultsRow.classList.remove('hidden');
            if (noResultsCard) noResultsCard.classList.remove('hidden');
        } else {
            if (noResultsRow) noResultsRow.classList.add('hidden');
            if (noResultsCard) noResultsCard.classList.add('hidden');
        }

        renderPaginationControls(totalPages, total);
    }

    function renderPaginationControls(totalPages, total) {
        const container = document.getElementById('paginationControls');
        if (!container) return;

        if (total === 0 || (totalPages <= 1 && perPage >= 999999)) {
            container.innerHTML = '';
            return;
        }

        let html = '';

        const prevDisabled = (currentPage <= 1);
        html += `
            <button type="button" onclick="goToPage(${currentPage - 1})" ${prevDisabled ? 'disabled' : ''} 
                    class="px-2.5 py-1.5 rounded-lg border text-xs font-bold transition flex items-center gap-1 ${prevDisabled ? 'border-slate-200 text-slate-300 cursor-not-allowed bg-slate-50' : 'border-slate-300 text-slate-700 bg-white hover:bg-slate-100 cursor-pointer active:scale-95'}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                <span class="hidden sm:inline">Prev</span>
            </button>
        `;

        let startPage = Math.max(1, currentPage - 2);
        let endPage = Math.min(totalPages, startPage + 4);
        if (endPage - startPage < 4) {
            startPage = Math.max(1, endPage - 4);
        }

        if (startPage > 1) {
            html += `<button type="button" onclick="goToPage(1)" class="w-8 h-8 rounded-lg border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold transition cursor-pointer">1</button>`;
            if (startPage > 2) html += `<span class="px-1 text-slate-400 font-bold">...</span>`;
        }

        for (let p = startPage; p <= endPage; p++) {
            const isActive = (p === currentPage);
            html += `
                <button type="button" onclick="goToPage(${p})" 
                        class="w-8 h-8 rounded-lg text-xs font-bold transition cursor-pointer ${isActive ? 'bg-[#0B2E22] text-white shadow-xs' : 'border border-slate-200 bg-white hover:bg-slate-100 text-slate-700'}">
                    ${p}
                </button>
            `;
        }

        if (endPage < totalPages) {
            if (endPage < totalPages - 1) html += `<span class="px-1 text-slate-400 font-bold">...</span>`;
            html += `<button type="button" onclick="goToPage(${totalPages})" class="w-8 h-8 rounded-lg border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold transition cursor-pointer">${totalPages}</button>`;
        }

        const nextDisabled = (currentPage >= totalPages);
        html += `
            <button type="button" onclick="goToPage(${currentPage + 1})" ${nextDisabled ? 'disabled' : ''} 
                    class="px-2.5 py-1.5 rounded-lg border text-xs font-bold transition flex items-center gap-1 ${nextDisabled ? 'border-slate-200 text-slate-300 cursor-not-allowed bg-slate-50' : 'border-slate-300 text-slate-700 bg-white hover:bg-slate-100 cursor-pointer active:scale-95'}">
                <span class="hidden sm:inline">Next</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </button>
        `;

        container.innerHTML = html;
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('reportSearch')?.addEventListener('input', () => { currentPage = 1; applyPagination(); });
        document.getElementById('statusFilter')?.addEventListener('change', () => { currentPage = 1; applyPagination(); });
        document.getElementById('categoryFilter')?.addEventListener('change', () => { currentPage = 1; applyPagination(); });
        applyPagination();
    });
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
