<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php
$logs = $data['logs'] ?? [];
$isArchiveView = !empty($data['is_archive_view']);
$activeCount = (int)($data['active_count'] ?? count($logs));
$archivedCount = (int)($data['archived_count'] ?? 0);
$flashSuccess = $data['flash_success'] ?? null;
$flashError = $data['flash_error'] ?? null;

$stats = $data['stats'] ?? [
    'total' => count($logs),
    'today' => 0,
    'success' => 0,
    'failed' => 0,
    'unique_users_count' => 0
];
$uniqueUsers = $data['unique_users'] ?? [];
$uniqueActions = $data['unique_actions'] ?? [];
$barangay = $data['barangay'] ?? [];

$brgyName = $barangay['barangay_name'] ?? 'Barangay Dulong Bayan';
$brgyCity = $barangay['municipality'] ?? 'Quezon City';
$brgyProv = $barangay['province'] ?? 'Metro Manila';
$sysLogo = !empty($barangay['system_logo']) ? format_asset_url($barangay['system_logo']) : format_asset_url('images/logo.png');

// Helper to determine action styling & badges
function getActionMeta($action) {
    $act = strtolower($action ?? '');
    if (strpos($act, 'verified') !== false || strpos($act, 'resolved') !== false) {
        return ['badge' => 'bg-emerald-50 text-emerald-900 border-emerald-200'];
    } elseif (strpos($act, 'rejected') !== false || strpos($act, 'delete') !== false || strpos($act, 'remove') !== false || strpos($act, 'suspend') !== false) {
        return ['badge' => 'bg-red-50 text-red-900 border-red-200'];
    } elseif (strpos($act, 'post') !== false || strpos($act, 'create') !== false || strpos($act, 'add') !== false || strpos($act, 'edit') !== false) {
        return ['badge' => 'bg-blue-50 text-blue-900 border-blue-200'];
    } elseif (strpos($act, 'gis') !== false || strpos($act, 'boundary') !== false) {
        return ['badge' => 'bg-purple-50 text-purple-900 border-purple-200'];
    } elseif (strpos($act, 'schedule') !== false) {
        return ['badge' => 'bg-amber-50 text-amber-900 border-amber-200'];
    } elseif (strpos($act, 'export') !== false || strpos($act, 'archive') !== false || strpos($act, 'restore') !== false) {
        return ['badge' => 'bg-teal-50 text-teal-900 border-teal-200'];
    } else {
        return ['badge' => 'bg-slate-100 text-slate-800 border-slate-200'];
    }
}
?>

<style>
    body, * { font-family: 'Miranda Sans', sans-serif !important; font-optical-sizing: auto; }
    .mobile-sidebar-open #mobileSidebar { transform: translateX(0) !important; }
    #mobileSidebarOverlay { transition: opacity 0.25s ease, visibility 0.25s ease; opacity: 0; visibility: hidden; }
    .mobile-sidebar-open #mobileSidebarOverlay { opacity: 1; visibility: visible; }

    @media print {
        @page {
            size: landscape;
            margin: 8mm;
        }
        html, body {
            background: #ffffff !important;
            color: #0f172a !important;
            height: auto !important;
            min-height: auto !important;
            overflow: visible !important;
        }
        body * {
            visibility: hidden !important;
        }
        #printArea, #printArea * {
            visibility: visible !important;
        }
        #printArea {
            display: block !important;
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            background: #ffffff !important;
            z-index: 9999999 !important;
        }
        .no-print {
            display: none !important;
        }
    }
</style>

<div class="flex h-screen bg-slate-50 overflow-hidden w-full">
    <!-- Desktop & Mobile Sidebar Navigation -->
    <?php include __DIR__ . '/../layouts/admin_sidebar.php'; ?>

    <!-- Main Content Wrapper -->
    <div class="flex flex-col flex-1 min-w-0 overflow-hidden">
        
        <!-- Top App Bar Navigation -->
        <?php include __DIR__ . '/../layouts/admin_topbar.php'; ?>
        
        <!-- Scrollable Main Container -->
        <main class="flex-1 overflow-y-auto bg-slate-50 focus:outline-none">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6">

                <!-- Flash Alert Messages -->
                <?php if (!empty($flashSuccess)): ?>
                    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between shadow-xs">
                        <div class="flex items-center gap-2.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            <span><?php echo htmlspecialchars($flashSuccess); ?></span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-950 font-bold text-xs cursor-pointer">✕</button>
                    </div>
                <?php endif; ?>

                <?php if (!empty($flashError)): ?>
                    <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-xs font-semibold flex items-center justify-between shadow-xs">
                        <div class="flex items-center gap-2.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <span><?php echo htmlspecialchars($flashError); ?></span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-950 font-bold text-xs cursor-pointer">✕</button>
                    </div>
                <?php endif; ?>

                <!-- 1. Header & Quick Actions Toolbar -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <!-- Breadcrumb -->
                        <nav class="flex items-center gap-1.5 text-xs font-bold text-slate-500 mb-1">
                            <span>Admin</span>
                            <span>/</span>
                            <span>Security &amp; Compliance</span>
                            <span>/</span>
                            <span class="text-slate-900"><?php echo $isArchiveView ? 'Archive Vault' : 'Audit Trail'; ?></span>
                        </nav>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2.5">
                            <span><?php echo $isArchiveView ? 'Archived Audit Vault' : 'System Audit &amp; Activity Trail'; ?></span>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-extrabold <?php echo $isArchiveView ? 'bg-amber-100 text-amber-900 border border-amber-300' : 'bg-emerald-100 text-emerald-900 border border-emerald-300'; ?>">
                                <?php echo $isArchiveView ? 'Archived Storage' : 'Live Forensic Logs'; ?>
                            </span>
                        </h1>
                        <p class="text-xs sm:text-sm text-slate-500 font-semibold mt-1">
                            <?php echo $isArchiveView 
                                ? 'Historical archived audit logs preserved off the active table to maintain fast system speed.' 
                                : 'Immutable audit trail of administrative modifications, report reviews, staff actions, and access events.'; ?>
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2.5 shrink-0 flex-wrap sm:flex-nowrap">
                        <?php if (!$isArchiveView): ?>
                            <!-- Archive Old Logs Modal Trigger -->
                            <button onclick="openArchiveModal()" class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-900 font-extrabold text-xs sm:text-sm border border-amber-200 shadow-2xs transition active:scale-[0.98] cursor-pointer" title="Archive Old Logs">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="5" x="2" y="3" rx="1"/><path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8"/><path d="M10 12h4"/></svg>
                                <span>Archive Old Logs</span>
                            </button>
                        <?php else: ?>
                            <!-- Restore All Trigger -->
                            <form action="<?php echo app_url('index.php?url=admin/restore_audit_logs'); ?>" method="POST" onsubmit="return confirm('Restore all archived logs back to the active audit trail?');">
                                <input type="hidden" name="restore_scope" value="all">
                                <button type="submit" class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-900 font-extrabold text-xs sm:text-sm border border-emerald-200 shadow-2xs transition active:scale-[0.98] cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg>
                                    <span>Restore All to Active</span>
                                </button>
                            </form>
                        <?php endif; ?>

                        <!-- Export CSV Dropdown / Action -->
                        <a href="<?php echo app_url('index.php?url=admin/export_audit_logs' . ($isArchiveView ? '&view=archive' : '')); ?>" class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-white hover:bg-slate-100 text-slate-700 font-extrabold text-xs sm:text-sm border border-slate-200 shadow-2xs transition active:scale-[0.98] cursor-pointer" title="Export CSV">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            <span>Export CSV</span>
                        </a>

                        <!-- Print Action with Options Modal -->
                        <button onclick="openPrintModal()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-[#0B2E22] hover:bg-[#07281E] text-white font-extrabold text-xs sm:text-sm shadow-2xs transition active:scale-[0.98] cursor-pointer" title="Print Audit Report">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                            <span>Print Report</span>
                        </button>
                    </div>
                </div>

                <!-- 1.5 Active vs Archive View Navigation Tabs -->
                <div class="flex items-center gap-2 border-b border-slate-200 pb-2">
                    <a href="<?php echo app_url('index.php?url=admin/audit_logs'); ?>" 
                       class="px-4 py-2 rounded-xl text-xs font-black transition flex items-center gap-2 <?php echo !$isArchiveView ? 'bg-[#0B2E22] text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'; ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 <?php echo !$isArchiveView ? 'text-emerald-400' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        <span>Active Audit Trail</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold <?php echo !$isArchiveView ? 'bg-emerald-800 text-emerald-100' : 'bg-slate-100 text-slate-600'; ?>"><?php echo number_format($activeCount); ?></span>
                    </a>

                    <a href="<?php echo app_url('index.php?url=admin/audit_logs&view=archive'); ?>" 
                       class="px-4 py-2 rounded-xl text-xs font-black transition flex items-center gap-2 <?php echo $isArchiveView ? 'bg-[#0B2E22] text-white shadow-sm' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'; ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 <?php echo $isArchiveView ? 'text-amber-400' : 'text-slate-400'; ?>" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="5" x="2" y="3" rx="1"/><path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8"/><path d="M10 12h4"/></svg>
                        <span>Archive Vault</span>
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold <?php echo $isArchiveView ? 'bg-emerald-800 text-emerald-100' : 'bg-slate-100 text-slate-600'; ?>"><?php echo number_format($archivedCount); ?></span>
                    </a>
                </div>

                <!-- 2. KPI Metric Summary Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Total Logs -->
                    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex items-center justify-between">
                        <div class="space-y-1">
                            <p class="text-xs font-extrabold uppercase tracking-wider text-slate-400"><?php echo $isArchiveView ? 'Archived Records' : 'Active Recorded Logs'; ?></p>
                            <p class="text-2xl font-black text-slate-900"><?php echo number_format($stats['total']); ?></p>
                            <p class="text-[11px] font-semibold text-slate-500"><?php echo $isArchiveView ? 'Preserved in archive vault' : 'Live active table records'; ?></p>
                        </div>
                    </div>

                    <!-- Today's Activity -->
                    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex items-center justify-between">
                        <div class="space-y-1">
                            <p class="text-xs font-extrabold uppercase tracking-wider text-slate-400">Today's Activity</p>
                            <p class="text-2xl font-black text-slate-900"><?php echo number_format($stats['today']); ?></p>
                            <p class="text-[11px] font-semibold text-slate-700">Events dated <?php echo date('M d, Y'); ?></p>
                        </div>
                    </div>

                    <!-- Successful Executions -->
                    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex items-center justify-between">
                        <div class="space-y-1">
                            <p class="text-xs font-extrabold uppercase tracking-wider text-slate-400">Successful Executions</p>
                            <p class="text-2xl font-black text-slate-900"><?php echo number_format($stats['success']); ?></p>
                            <p class="text-[11px] font-semibold text-slate-500">Verified &amp; confirmed actions</p>
                        </div>
                    </div>

                    <!-- Active Admin Staff Actors -->
                    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex items-center justify-between">
                        <div class="space-y-1">
                            <p class="text-xs font-extrabold uppercase tracking-wider text-slate-400">Unique Actors</p>
                            <p class="text-2xl font-black text-slate-900"><?php echo number_format($stats['unique_users_count']); ?></p>
                            <p class="text-[11px] font-semibold text-slate-700">Staff &amp; admin accounts logged</p>
                        </div>
                    </div>
                </div>

                <!-- 3. Advanced Filtering & Search Toolstrip -->
                <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 shadow-xs space-y-4">
                    
                    <!-- Row 1: Search and Main Selects -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        <!-- Search Box -->
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            </div>
                            <input type="text" id="searchInput" oninput="applyFilters()" placeholder="Search action, user, entity, IP..." 
                                   class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10 outline-none transition">
                        </div>

                        <!-- Module / Action Type Filter -->
                        <div>
                            <select id="actionFilter" onchange="applyFilters()" 
                                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:bg-white focus:border-emerald-500 outline-none transition cursor-pointer">
                                <option value="all">-- All Modules &amp; Actions --</option>
                                <option value="Report">Report Operations (Verify/Resolve/Reject)</option>
                                <option value="User">User &amp; Account Management</option>
                                <option value="Announcement">Announcements &amp; Advisories</option>
                                <option value="Schedule">Collection Schedules</option>
                                <option value="GIS">GIS &amp; Territorial Boundaries</option>
                                <option value="Export">Export &amp; Analytics Logs</option>
                                <option value="Login">Login &amp; Session Access</option>
                                <option value="Archive">Archival &amp; Storage</option>
                            </select>
                        </div>

                        <!-- Specific User Filter -->
                        <div>
                            <select id="userFilter" onchange="applyFilters()" 
                                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:bg-white focus:border-emerald-500 outline-none transition cursor-pointer">
                                <option value="all">-- Filter by Actor / User --</option>
                                <?php foreach ($uniqueUsers as $uname): ?>
                                    <option value="<?php echo htmlspecialchars($uname); ?>"><?php echo htmlspecialchars($uname); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Result Status Filter -->
                        <div>
                            <select id="resultFilter" onchange="applyFilters()" 
                                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:bg-white focus:border-emerald-500 outline-none transition cursor-pointer">
                                <option value="all">-- All Results --</option>
                                <option value="success">Success Only</option>
                                <option value="failed">Failed / Error Only</option>
                            </select>
                        </div>
                    </div>

                    <!-- Row 2: Date Range & Quick Preset Chips -->
                    <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-slate-100">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-xs font-bold text-slate-500">Date Range:</span>
                            <input type="date" id="dateFrom" onchange="applyFilters()" class="px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 outline-none focus:bg-white focus:border-emerald-500">
                            <span class="text-xs text-slate-400 font-bold">to</span>
                            <input type="date" id="dateTo" onchange="applyFilters()" class="px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 outline-none focus:bg-white focus:border-emerald-500">

                            <!-- Quick Preset Buttons -->
                            <div class="flex items-center gap-1 ml-1">
                                <button type="button" onclick="setDatePreset('all')" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 font-extrabold text-[11px] transition cursor-pointer">All Time</button>
                                <button type="button" onclick="setDatePreset('today')" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 font-extrabold text-[11px] transition cursor-pointer">Today</button>
                                <button type="button" onclick="setDatePreset('7days')" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 font-extrabold text-[11px] transition cursor-pointer">Last 7 Days</button>
                                <button type="button" onclick="setDatePreset('month')" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-600 font-extrabold text-[11px] transition cursor-pointer">This Month</button>
                            </div>
                        </div>

                        <button onclick="resetFilters()" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-extrabold text-xs transition cursor-pointer shrink-0" title="Reset All Filters">
                            Reset All Filters
                        </button>
                    </div>

                    <!-- Row 3: Counter & Page Size Selection -->
                    <div class="flex items-center justify-between text-xs font-bold text-slate-500 pt-2 border-t border-slate-100">
                        <div>
                            Showing <strong id="visibleLogCount" class="text-slate-900"><?php echo count($logs); ?></strong> of <?php echo count($logs); ?> <?php echo $isArchiveView ? 'archived' : 'recorded'; ?> events
                        </div>
                        <div class="flex items-center gap-2">
                            <span>Per page:</span>
                            <select id="pageSizeSelect" onchange="changePageSize()" class="bg-slate-100 border border-slate-200 text-xs font-bold text-slate-800 rounded-lg px-2 py-1 outline-none">
                                <option value="15">15</option>
                                <option value="25" selected>25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- 4. Main Audit Trail Data Table -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 text-left" id="auditTable">
                            <thead class="bg-slate-50 text-[11px] font-black text-slate-500 uppercase tracking-wider">
                                <tr>
                                    <!-- Checkbox Column -->
                                    <th class="px-4 py-3.5 w-10 text-center">
                                        <input type="checkbox" id="selectAllCheckbox" onchange="toggleSelectAll(this.checked)" 
                                               class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500/20 cursor-pointer" title="Select all on this page">
                                    </th>
                                    <th class="px-5 py-3.5">Timestamp</th>
                                    <th class="px-5 py-3.5">Actor / User</th>
                                    <th class="px-5 py-3.5">Action &amp; Module</th>
                                    <th class="px-5 py-3.5">Target Entity</th>
                                    <th class="px-5 py-3.5">Details / Remarks</th>
                                    <th class="px-5 py-3.5">Result</th>
                                    <th class="px-5 py-3.5 text-right">Inspect</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-xs font-semibold text-slate-700" id="auditTableBody">
                                <?php if (!empty($logs)): ?>
                                    <?php foreach ($logs as $index => $log): 
                                        $meta = getActionMeta($log['action']);
                                        $isSuccess = strtolower($log['result'] ?? '') === 'success';
                                        $userDisplay = !empty($log['user_name']) ? $log['user_name'] : 'System / Auto';
                                        $roleDisplay = !empty($log['role_name']) ? ucfirst($log['role_name']) : 'Automated';
                                        $initial = strtoupper(substr($userDisplay, 0, 1));
                                        $logDateOnly = substr($log['created_at'] ?? '', 0, 10);
                                    ?>
                                        <tr class="log-row hover:bg-slate-50/80 transition group" 
                                            data-id="<?php echo htmlspecialchars($log['id']); ?>"
                                            data-date="<?php echo htmlspecialchars($logDateOnly); ?>"
                                            data-user="<?php echo strtolower(htmlspecialchars($userDisplay)); ?>"
                                            data-action="<?php echo strtolower(htmlspecialchars($log['action'])); ?>"
                                            data-details="<?php echo strtolower(htmlspecialchars($log['details'] ?? '')); ?>"
                                            data-record="<?php echo strtolower(htmlspecialchars($log['affected_record'] ?? '')); ?>"
                                            data-ip="<?php echo strtolower(htmlspecialchars($log['ip_address'] ?? '')); ?>"
                                            data-result="<?php echo strtolower(htmlspecialchars($log['result'] ?? '')); ?>">
                                            
                                            <!-- Row Checkbox -->
                                            <td class="px-4 py-3.5 text-center" onclick="event.stopPropagation()">
                                                <input type="checkbox" value="<?php echo htmlspecialchars($log['id']); ?>" 
                                                       class="log-row-checkbox w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500/20 cursor-pointer"
                                                       onchange="updateSelectedCount()">
                                            </td>

                                            <!-- Timestamp -->
                                            <td class="px-5 py-3.5 whitespace-nowrap cursor-pointer" onclick="inspectLog(<?php echo htmlspecialchars(json_encode($log)); ?>)">
                                                <div class="font-extrabold text-slate-900"><?php echo date('M d, Y', strtotime($log['created_at'])); ?></div>
                                                <div class="text-[10px] text-slate-400 font-mono"><?php echo date('h:i:s A', strtotime($log['created_at'])); ?></div>
                                            </td>

                                            <!-- Actor -->
                                            <td class="px-5 py-3.5 whitespace-nowrap cursor-pointer" onclick="inspectLog(<?php echo htmlspecialchars(json_encode($log)); ?>)">
                                                <div class="flex items-center gap-2.5">
                                                    <div class="w-7 h-7 rounded-full bg-[#0B2E22] text-white flex items-center justify-center text-xs font-extrabold shrink-0">
                                                        <?php echo $initial; ?>
                                                    </div>
                                                    <div>
                                                        <div class="font-extrabold text-slate-900"><?php echo htmlspecialchars($userDisplay); ?></div>
                                                        <div class="text-[10px] font-bold text-slate-500"><?php echo htmlspecialchars($roleDisplay); ?></div>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Action & Module -->
                                            <td class="px-5 py-3.5 whitespace-nowrap cursor-pointer" onclick="inspectLog(<?php echo htmlspecialchars(json_encode($log)); ?>)">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-extrabold border <?php echo $meta['badge']; ?>">
                                                    <?php echo htmlspecialchars($log['action']); ?>
                                                </span>
                                            </td>

                                            <!-- Affected Record -->
                                            <td class="px-5 py-3.5 whitespace-nowrap cursor-pointer" onclick="inspectLog(<?php echo htmlspecialchars(json_encode($log)); ?>)">
                                                <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-800 font-mono text-[11px] font-bold border border-slate-200">
                                                    <?php echo htmlspecialchars($log['affected_record'] ?? 'N/A'); ?>
                                                </span>
                                            </td>

                                            <!-- Details -->
                                            <td class="px-5 py-3.5 max-w-xs sm:max-w-sm truncate text-slate-600 font-medium cursor-pointer" onclick="inspectLog(<?php echo htmlspecialchars(json_encode($log)); ?>)" title="<?php echo htmlspecialchars($log['details'] ?? ''); ?>">
                                                <?php echo htmlspecialchars($log['details'] ?? 'No extra remarks'); ?>
                                            </td>

                                            <!-- Result -->
                                            <td class="px-5 py-3.5 whitespace-nowrap cursor-pointer" onclick="inspectLog(<?php echo htmlspecialchars(json_encode($log)); ?>)">
                                                <?php if ($isSuccess): ?>
                                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-black bg-emerald-50 text-emerald-900 border border-emerald-300">
                                                        SUCCESS
                                                    </span>
                                                <?php else: ?>
                                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-black bg-red-50 text-red-900 border border-red-300">
                                                        FAILED
                                                    </span>
                                                <?php endif; ?>
                                            </td>

                                            <!-- Inspect Action -->
                                            <td class="px-5 py-3.5 whitespace-nowrap text-right">
                                                <button type="button" onclick="inspectLog(<?php echo htmlspecialchars(json_encode($log)); ?>)" class="p-1.5 rounded-lg text-slate-400 group-hover:text-emerald-700 group-hover:bg-emerald-50 transition cursor-pointer" title="Inspect Log Entry">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr id="emptyRow">
                                        <td colspan="8" class="px-6 py-12 text-center text-slate-400 space-y-2">
                                            <div class="w-12 h-12 mx-auto rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                                            </div>
                                            <div class="text-sm font-extrabold text-slate-700"><?php echo $isArchiveView ? 'No archived logs found in vault' : 'No active audit logs found'; ?></div>
                                            <div class="text-xs"><?php echo $isArchiveView ? 'Archived log records will appear here after running the archive operation.' : 'Any future actions performed by administrators or system scripts will be logged here.'; ?></div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Table Pagination Strip -->
                    <div class="px-5 py-3.5 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs font-extrabold text-slate-600">
                        <div id="paginationInfo">
                            Page 1 of 1
                        </div>
                        <div class="flex items-center gap-1" id="paginationControls">
                            <!-- Page buttons rendered by JavaScript -->
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>

<!-- ============================================================ -->
<!-- STICKY BATCH ACTIONS BAR (Appears when rows are selected)    -->
<!-- ============================================================ -->
<div id="batchActionBar" class="fixed bottom-6 left-1/2 -translate-x-1/2 bg-slate-900/95 text-white px-5 py-3 rounded-2xl shadow-2xl backdrop-blur-md flex items-center gap-4 z-40 border border-slate-800 transition-all duration-300 transform translate-y-24 opacity-0 pointer-events-none">
    <div class="flex items-center gap-2">
        <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
        <span id="selectedCountLabel" class="text-xs font-extrabold">0 logs selected</span>
    </div>

    <div class="h-4 w-px bg-slate-700"></div>

    <div class="flex items-center gap-2">
        <?php if (!$isArchiveView): ?>
            <!-- Archive Selected Button -->
            <button onclick="archiveSelectedLogs()" class="px-3 py-1.5 rounded-xl bg-amber-600 hover:bg-amber-500 text-white font-extrabold text-xs transition flex items-center gap-1.5 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="5" x="2" y="3" rx="1"/><path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8"/><path d="M10 12h4"/></svg>
                <span>Archive Selected</span>
            </button>
        <?php else: ?>
            <!-- Restore Selected Button -->
            <button onclick="restoreSelectedLogs()" class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-extrabold text-xs transition flex items-center gap-1.5 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg>
                <span>Restore Selected</span>
            </button>
        <?php endif; ?>

        <button onclick="printScope('selected')" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-extrabold text-xs transition flex items-center gap-1.5 cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
            <span>Print Selected</span>
        </button>

        <button onclick="exportSelectedLogsCSV()" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 font-extrabold text-xs transition flex items-center gap-1.5 cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            <span>Export Selected</span>
        </button>

        <button onclick="deselectAll()" class="px-2.5 py-1.5 rounded-xl bg-transparent hover:bg-slate-800 text-slate-400 hover:text-white font-extrabold text-xs transition cursor-pointer">
            Deselect All
        </button>
    </div>
</div>

<!-- ============================================================ -->
<!-- ARCHIVE LOGS CONFIGURATION MODAL                             -->
<!-- ============================================================ -->
<div id="archiveConfigModal" class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full border border-slate-200 overflow-hidden animate-fadeIn">
        <form action="<?php echo app_url('index.php?url=admin/archive_audit_logs'); ?>" method="POST">
            <!-- Header -->
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-2xl bg-amber-100 text-amber-900 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-800" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="5" x="2" y="3" rx="1"/><path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8"/><path d="M10 12h4"/></svg>
                    </div>
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900">Archive Audit Logs</h3>
                        <p class="text-xs font-semibold text-slate-500">Speed up active table by archiving older entries</p>
                    </div>
                </div>
                <button type="button" onclick="closeArchiveModal()" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-200 transition cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                </button>
            </div>

            <!-- Body -->
            <div class="p-6 space-y-4 text-xs">
                <input type="hidden" name="archive_scope" id="archiveScopeInput" value="days">
                <input type="hidden" name="selected_ids" id="archiveSelectedIdsInput" value="">

                <div class="p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-[11px] leading-relaxed">
                    <strong>💡 Performance Tip:</strong> Archiving moves old logs to the <code>audit_logs_archive</code> table. This significantly speeds up database queries, report filtering, and overall portal responsiveness. You can review or restore archived records at any time from the <strong>Archive Vault</strong> tab.
                </div>

                <div id="archiveDaysContainer">
                    <label class="block text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-2">Select Archival Age</label>
                    <select name="days" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:bg-white focus:border-amber-500 outline-none transition cursor-pointer">
                        <option value="30">Logs older than 30 Days (1 Month)</option>
                        <option value="60" selected>Logs older than 60 Days (2 Months) - Recommended</option>
                        <option value="90">Logs older than 90 Days (3 Months)</option>
                        <option value="180">Logs older than 180 Days (6 Months)</option>
                        <option value="365">Logs older than 365 Days (1 Year)</option>
                    </select>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeArchiveModal()" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-extrabold text-xs transition cursor-pointer">
                    Cancel
                </button>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-amber-700 hover:bg-amber-800 text-white font-extrabold text-xs transition flex items-center gap-2 shadow-sm cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="5" x="2" y="3" rx="1"/><path d="M4 8v11a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8"/><path d="M10 12h4"/></svg>
                    <span>Proceed to Archive</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================ -->
<!-- PRINT CONFIGURATION MODAL (Supports Selected Page to Print)  -->
<!-- ============================================================ -->
<div id="printConfigModal" class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full border border-slate-200 overflow-hidden animate-fadeIn">
        <!-- Header -->
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-emerald-100 text-emerald-900 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-800" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Print Audit Report</h3>
                    <p class="text-xs font-semibold text-slate-500">Configure page scope and print options</p>
                </div>
            </div>
            <button onclick="closePrintModal()" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-200 transition cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <!-- Body -->
        <div class="p-6 space-y-5 text-xs">
            <!-- Print Scope Selection -->
            <div>
                <label class="block text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-2">Print Scope</label>
                <div class="space-y-2">
                    <!-- Option 1: Current Page Only -->
                    <label class="flex items-start gap-3 p-3 rounded-xl border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50/30 transition cursor-pointer">
                        <input type="radio" name="printScopeRadio" value="current_page" checked onchange="toggleCustomPageInput(false)" class="mt-0.5 w-4 h-4 text-emerald-600 focus:ring-emerald-500/20">
                        <div class="flex-1">
                            <span class="block font-extrabold text-slate-900 text-xs">Current Page Only (<span id="modalCurrentPageLabel">Page 1</span>)</span>
                            <span class="block text-slate-500 text-[11px]">Prints only the entries currently visible on this pagination page.</span>
                        </div>
                    </label>

                    <!-- Option 2: All Filtered Logs -->
                    <label class="flex items-start gap-3 p-3 rounded-xl border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50/30 transition cursor-pointer">
                        <input type="radio" name="printScopeRadio" value="all_filtered" onchange="toggleCustomPageInput(false)" class="mt-0.5 w-4 h-4 text-emerald-600 focus:ring-emerald-500/20">
                        <div class="flex-1">
                            <span class="block font-extrabold text-slate-900 text-xs">All Filtered Records (<span id="modalFilteredCountLabel">0</span> logs)</span>
                            <span class="block text-slate-500 text-[11px]">Prints all matching logs across all pages according to current filters.</span>
                        </div>
                    </label>

                    <!-- Option 3: Selected Checked Logs -->
                    <label class="flex items-start gap-3 p-3 rounded-xl border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50/30 transition cursor-pointer">
                        <input type="radio" name="printScopeRadio" value="selected" onchange="toggleCustomPageInput(false)" class="mt-0.5 w-4 h-4 text-emerald-600 focus:ring-emerald-500/20">
                        <div class="flex-1">
                            <span class="block font-extrabold text-slate-900 text-xs">Selected / Checked Rows (<span id="modalSelectedCountLabel">0</span> logs)</span>
                            <span class="block text-slate-500 text-[11px]">Prints only rows that you checked with checkboxes.</span>
                        </div>
                    </label>

                    <!-- Option 4: Custom Page Range -->
                    <label class="flex items-start gap-3 p-3 rounded-xl border border-slate-200 hover:border-emerald-500 hover:bg-emerald-50/30 transition cursor-pointer">
                        <input type="radio" name="printScopeRadio" value="page_range" onchange="toggleCustomPageInput(true)" class="mt-0.5 w-4 h-4 text-emerald-600 focus:ring-emerald-500/20">
                        <div class="flex-1">
                            <span class="block font-extrabold text-slate-900 text-xs">Specific Page Range</span>
                            <span class="block text-slate-500 text-[11px]">Select a range of pages to print.</span>
                            
                            <!-- Custom Page Inputs -->
                            <div id="customPageRangeContainer" class="hidden mt-2 pt-2 border-t border-slate-100 flex items-center gap-2">
                                <span class="font-bold text-slate-600">From Page:</span>
                                <input type="number" id="rangeFromPage" min="1" value="1" class="w-16 px-2 py-1 bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold text-slate-800 outline-none">
                                <span class="font-bold text-slate-600">To Page:</span>
                                <input type="number" id="rangeToPage" min="1" value="1" class="w-16 px-2 py-1 bg-slate-50 border border-slate-200 rounded-lg text-xs font-bold text-slate-800 outline-none">
                            </div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Print Formatting Options -->
            <div class="pt-3 border-t border-slate-100 space-y-2">
                <label class="block text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-1">Layout Options</label>
                
                <label class="flex items-center gap-2.5 font-semibold text-slate-700 cursor-pointer">
                    <input type="checkbox" id="optIncludeHeader" checked class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500/20">
                    <span>Include Official Barangay Seal and Header</span>
                </label>

                <label class="flex items-center gap-2.5 font-semibold text-slate-700 cursor-pointer">
                    <input type="checkbox" id="optIncludeSignatures" checked class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500/20">
                    <span>Include Prepared By &amp; Approved By Signature Block</span>
                </label>

                <label class="flex items-center gap-2.5 font-semibold text-slate-700 cursor-pointer">
                    <input type="checkbox" id="optIncludeIp" checked class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500/20">
                    <span>Include Client IP Address Column</span>
                </label>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-2.5">
            <button onclick="closePrintModal()" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-extrabold text-xs transition cursor-pointer">
                Cancel
            </button>
            <button onclick="executePrintFromModal()" class="px-5 py-2.5 rounded-xl bg-[#0B2E22] hover:bg-[#07281E] text-white font-extrabold text-xs transition flex items-center gap-2 shadow-sm cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                <span>Proceed to Print</span>
            </button>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- LOG INSPECTOR MODAL                                          -->
<!-- ============================================================ -->
<div id="logDetailModal" class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-xl w-full border border-slate-200 overflow-hidden animate-fadeIn">
        <!-- Modal Header -->
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-800" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Audit Log Forensic Record</h3>
                    <p class="text-[11px] font-semibold text-slate-500" id="modalLogId">Log Entry Details</p>
                </div>
            </div>
            <button onclick="closeInspectModal()" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-200 transition cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 space-y-4 text-xs font-semibold text-slate-700 max-h-[70vh] overflow-y-auto">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Timestamp</span>
                    <span class="text-xs font-extrabold text-slate-900" id="modalTime"></span>
                </div>
                <div>
                    <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Execution Result</span>
                    <span id="modalResult" class="inline-block mt-0.5"></span>
                </div>
                <div>
                    <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Actor / User</span>
                    <span class="text-xs font-extrabold text-slate-900" id="modalUser"></span>
                </div>
                <div>
                    <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">System Role</span>
                    <span class="text-xs font-extrabold text-slate-700" id="modalRole"></span>
                </div>
                <div>
                    <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Action Name</span>
                    <span class="text-xs font-extrabold text-emerald-950 font-mono" id="modalAction"></span>
                </div>
                <div>
                    <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Target Entity</span>
                    <span class="text-xs font-extrabold text-slate-800 font-mono" id="modalRecord"></span>
                </div>
                <div>
                    <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Client IP Address</span>
                    <span class="text-xs font-bold text-slate-600 font-mono" id="modalIp"></span>
                </div>
                <div>
                    <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400">User Agent / Client</span>
                    <span class="text-[11px] font-mono text-slate-500 truncate block" id="modalAgent"></span>
                </div>
            </div>

            <div class="pt-3 border-t border-slate-100">
                <span class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1.5">Action Remarks &amp; Details</span>
                <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 text-xs font-mono text-slate-800 whitespace-pre-wrap leading-relaxed" id="modalDetails"></div>
            </div>
        </div>

        <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-200 flex items-center justify-between">
            <button onclick="printSingleIncident()" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-extrabold text-xs transition cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                <span>Print Single Log Record</span>
            </button>
            <button onclick="closeInspectModal()" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-extrabold text-xs transition cursor-pointer">
                Close Inspector
            </button>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- PRINT AREA FORMATTING                                        -->
<!-- ============================================================ -->
<div id="printArea" class="hidden">
    <div class="p-8 space-y-6">
        <!-- Print Header with Official Barangay Seals -->
        <div id="printHeaderBlock" class="flex items-center justify-between border-b-2 border-slate-800 pb-4">
            <div class="w-16 h-16 flex items-center justify-center">
                <img src="<?php echo htmlspecialchars($sysLogo); ?>" class="h-16 w-16 object-contain" alt="Seal">
            </div>
            <div class="text-center space-y-0.5">
                <p class="text-xs uppercase tracking-widest text-slate-600 font-bold">Republic of the Philippines</p>
                <h2 class="text-xl font-black text-slate-900"><?php echo htmlspecialchars($brgyName); ?></h2>
                <p class="text-xs text-slate-600"><?php echo htmlspecialchars($brgyCity); ?>, <?php echo htmlspecialchars($brgyProv); ?></p>
                <h3 class="text-sm font-extrabold text-emerald-950 mt-1 uppercase tracking-wider">Official System Audit &amp; Forensic Log Report</h3>
            </div>
            <div class="w-16 h-16 text-right">
                <span class="text-xs font-mono font-bold text-slate-500">CONFIDENTIAL</span>
            </div>
        </div>

        <div class="flex items-center justify-between text-xs text-slate-600 border-b pb-2">
            <span>Generated on: <strong><?php echo date('F j, Y - h:i A'); ?></strong></span>
            <span id="printFilterMetadata" class="font-bold text-slate-800">All System Records</span>
            <span>Generated by: <strong><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?></strong></span>
        </div>

        <!-- Printable Table -->
        <table class="w-full text-xs text-left border-collapse border border-slate-300">
            <thead>
                <tr class="bg-slate-100 text-[11px] font-bold text-slate-800">
                    <th class="border border-slate-300 p-2">Timestamp</th>
                    <th class="border border-slate-300 p-2">Actor / User</th>
                    <th class="border border-slate-300 p-2">Action</th>
                    <th class="border border-slate-300 p-2">Target Entity</th>
                    <th class="border border-slate-300 p-2">Details / Remarks</th>
                    <th class="border border-slate-300 p-2 print-ip-col">Client IP</th>
                    <th class="border border-slate-300 p-2">Result</th>
                </tr>
            </thead>
            <tbody id="printableAuditTableBody">
                <!-- Populated dynamically before print -->
            </tbody>
        </table>

        <!-- Signatures strip -->
        <div id="printSignaturesBlock" class="pt-8 flex justify-between items-end text-xs text-slate-700">
            <div>
                <p class="text-[10px] text-slate-400 uppercase font-bold">Prepared By:</p>
                <div class="mt-6 border-t border-slate-400 pt-1 font-bold">
                    <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Administrator'); ?>
                </div>
            </div>
            <div class="text-right">
                <p class="text-[10px] text-slate-400 uppercase font-bold">Approved By:</p>
                <div class="mt-6 border-t border-slate-400 pt-1 font-bold">
                    Punong Barangay / Office Administrator
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // State management
    let currentPage = 1;
    let pageSize = 25;
    let filteredRows = [];
    let currentInspectedLog = null;

    function applyFilters() {
        const query = (document.getElementById('searchInput').value || '').toLowerCase().trim();
        const action = (document.getElementById('actionFilter').value || 'all').toLowerCase();
        const user = (document.getElementById('userFilter').value || 'all').toLowerCase();
        const result = (document.getElementById('resultFilter').value || 'all').toLowerCase();
        const dateFrom = document.getElementById('dateFrom').value || '';
        const dateTo = document.getElementById('dateTo').value || '';

        const allRows = Array.from(document.querySelectorAll('tbody tr.log-row'));
        filteredRows = [];

        allRows.forEach(row => {
            const rowUser = row.getAttribute('data-user') || '';
            const rowAction = row.getAttribute('data-action') || '';
            const rowDetails = row.getAttribute('data-details') || '';
            const rowRecord = row.getAttribute('data-record') || '';
            const rowResult = row.getAttribute('data-result') || '';
            const rowIp = row.getAttribute('data-ip') || '';
            const rowDate = row.getAttribute('data-date') || '';

            const matchesQuery = !query || rowUser.includes(query) || rowAction.includes(query) || rowDetails.includes(query) || rowRecord.includes(query) || rowIp.includes(query);
            const matchesAction = (action === 'all') || rowAction.includes(action);
            const matchesUser = (user === 'all') || (rowUser === user);
            const matchesResult = (result === 'all') || (rowResult === result);
            
            let matchesDate = true;
            if (dateFrom && rowDate < dateFrom) matchesDate = false;
            if (dateTo && rowDate > dateTo) matchesDate = false;

            if (matchesQuery && matchesAction && matchesUser && matchesResult && matchesDate) {
                filteredRows.push(row);
            }
        });

        document.getElementById('visibleLogCount').textContent = filteredRows.length;
        currentPage = 1;
        renderPaginatedRows();
        updateSelectedCount();
    }

    function setDatePreset(preset) {
        const now = new Date();
        const dFrom = document.getElementById('dateFrom');
        const dTo = document.getElementById('dateTo');

        const formatDate = (d) => d.toISOString().split('T')[0];

        if (preset === 'all') {
            dFrom.value = '';
            dTo.value = '';
        } else if (preset === 'today') {
            const todayStr = formatDate(now);
            dFrom.value = todayStr;
            dTo.value = todayStr;
        } else if (preset === '7days') {
            const past7 = new Date();
            past7.setDate(now.getDate() - 7);
            dFrom.value = formatDate(past7);
            dTo.value = formatDate(now);
        } else if (preset === 'month') {
            const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
            dFrom.value = formatDate(firstDay);
            dTo.value = formatDate(now);
        }
        applyFilters();
    }

    function resetFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('actionFilter').value = 'all';
        document.getElementById('userFilter').value = 'all';
        document.getElementById('resultFilter').value = 'all';
        document.getElementById('dateFrom').value = '';
        document.getElementById('dateTo').value = '';
        applyFilters();
    }

    function changePageSize() {
        pageSize = parseInt(document.getElementById('pageSizeSelect').value, 10) || 25;
        currentPage = 1;
        renderPaginatedRows();
    }

    function renderPaginatedRows() {
        const allRows = Array.from(document.querySelectorAll('tbody tr.log-row'));
        allRows.forEach(r => r.style.display = 'none');

        const total = filteredRows.length;
        const totalPages = Math.ceil(total / pageSize) || 1;
        if (currentPage > totalPages) currentPage = totalPages;

        const start = (currentPage - 1) * pageSize;
        const end = Math.min(start + pageSize, total);

        for (let i = start; i < end; i++) {
            if (filteredRows[i]) filteredRows[i].style.display = '';
        }

        // Update pagination counter
        document.getElementById('paginationInfo').textContent = `Showing ${total === 0 ? 0 : start + 1} to ${end} of ${total} entries (Page ${currentPage} of ${totalPages})`;

        // Render Page Buttons
        const controls = document.getElementById('paginationControls');
        controls.innerHTML = '';

        if (totalPages > 1) {
            // Prev
            const prevBtn = document.createElement('button');
            prevBtn.textContent = '‹ Prev';
            prevBtn.disabled = (currentPage === 1);
            prevBtn.className = `px-2.5 py-1 rounded-lg border text-xs font-extrabold ${currentPage === 1 ? 'opacity-40 cursor-not-allowed bg-slate-100 text-slate-400' : 'bg-white text-slate-700 hover:bg-slate-100 cursor-pointer'}`;
            prevBtn.onclick = () => { if (currentPage > 1) { currentPage--; renderPaginatedRows(); } };
            controls.appendChild(prevBtn);

            // Page numbers with smart ellipsis
            for (let p = 1; p <= totalPages; p++) {
                if (totalPages > 7 && Math.abs(p - currentPage) > 2 && p !== 1 && p !== totalPages) {
                    continue;
                }
                const pBtn = document.createElement('button');
                pBtn.textContent = p;
                pBtn.className = `px-2.5 py-1 rounded-lg border text-xs font-extrabold ${p === currentPage ? 'bg-[#0B2E22] text-white border-emerald-950' : 'bg-white text-slate-700 hover:bg-slate-100 border-slate-200 cursor-pointer'}`;
                pBtn.onclick = () => { currentPage = p; renderPaginatedRows(); };
                controls.appendChild(pBtn);
            }

            // Next
            const nextBtn = document.createElement('button');
            nextBtn.textContent = 'Next ›';
            nextBtn.disabled = (currentPage === totalPages);
            nextBtn.className = `px-2.5 py-1 rounded-lg border text-xs font-extrabold ${currentPage === totalPages ? 'opacity-40 cursor-not-allowed bg-slate-100 text-slate-400' : 'bg-white text-slate-700 hover:bg-slate-100 cursor-pointer'}`;
            nextBtn.onclick = () => { if (currentPage < totalPages) { currentPage++; renderPaginatedRows(); } };
            controls.appendChild(nextBtn);
        }

        // Sync header select all checkbox for visible page
        const selectAllCb = document.getElementById('selectAllCheckbox');
        if (selectAllCb) {
            const visibleCheckboxes = Array.from(document.querySelectorAll('tbody tr.log-row:not([style*="display: none"]) .log-row-checkbox'));
            selectAllCb.checked = visibleCheckboxes.length > 0 && visibleCheckboxes.every(cb => cb.checked);
        }
    }

    // Checkbox and Batch Actions
    function toggleSelectAll(checked) {
        const visibleCheckboxes = Array.from(document.querySelectorAll('tbody tr.log-row:not([style*="display: none"]) .log-row-checkbox'));
        visibleCheckboxes.forEach(cb => cb.checked = checked);
        updateSelectedCount();
    }

    function deselectAll() {
        document.querySelectorAll('.log-row-checkbox').forEach(cb => cb.checked = false);
        const masterCb = document.getElementById('selectAllCheckbox');
        if (masterCb) masterCb.checked = false;
        updateSelectedCount();
    }

    function getSelectedRows() {
        const selectedCbs = Array.from(document.querySelectorAll('.log-row-checkbox:checked'));
        return selectedCbs.map(cb => cb.closest('tr.log-row')).filter(Boolean);
    }

    function updateSelectedCount() {
        const selectedCount = document.querySelectorAll('.log-row-checkbox:checked').length;
        const batchBar = document.getElementById('batchActionBar');
        const countLabel = document.getElementById('selectedCountLabel');
        
        if (countLabel) countLabel.textContent = `${selectedCount} log${selectedCount === 1 ? '' : 's'} selected`;

        if (selectedCount > 0) {
            batchBar.classList.remove('translate-y-24', 'opacity-0', 'pointer-events-none');
            batchBar.classList.add('translate-y-0', 'opacity-100');
        } else {
            batchBar.classList.add('translate-y-24', 'opacity-0', 'pointer-events-none');
            batchBar.classList.remove('translate-y-0', 'opacity-100');
        }
    }

    // Archival Modal Controls
    function openArchiveModal() {
        document.getElementById('archiveConfigModal').classList.remove('hidden');
    }

    function closeArchiveModal() {
        document.getElementById('archiveConfigModal').classList.add('hidden');
    }

    function archiveSelectedLogs() {
        const selectedCbs = Array.from(document.querySelectorAll('.log-row-checkbox:checked'));
        if (selectedCbs.length === 0) {
            alert('Please select at least one log row to archive.');
            return;
        }

        if (!confirm(`Are you sure you want to move ${selectedCbs.length} selected log(s) to the Archive Vault?`)) {
            return;
        }

        const ids = selectedCbs.map(cb => cb.value).join(',');
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?php echo app_url('index.php?url=admin/archive_audit_logs'); ?>';

        const inputScope = document.createElement('input');
        inputScope.type = 'hidden';
        inputScope.name = 'archive_scope';
        inputScope.value = 'selected';
        form.appendChild(inputScope);

        const inputIds = document.createElement('input');
        inputIds.type = 'hidden';
        inputIds.name = 'selected_ids';
        inputIds.value = ids;
        form.appendChild(inputIds);

        document.body.appendChild(form);
        form.submit();
    }

    function restoreSelectedLogs() {
        const selectedCbs = Array.from(document.querySelectorAll('.log-row-checkbox:checked'));
        if (selectedCbs.length === 0) {
            alert('Please select at least one log row to restore.');
            return;
        }

        if (!confirm(`Restore ${selectedCbs.length} selected log(s) back to the Active Audit Trail?`)) {
            return;
        }

        const ids = selectedCbs.map(cb => cb.value).join(',');
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?php echo app_url('index.php?url=admin/restore_audit_logs'); ?>';

        const inputScope = document.createElement('input');
        inputScope.type = 'hidden';
        inputScope.name = 'restore_scope';
        inputScope.value = 'selected';
        form.appendChild(inputScope);

        const inputIds = document.createElement('input');
        inputIds.type = 'hidden';
        inputIds.name = 'selected_ids';
        inputIds.value = ids;
        form.appendChild(inputIds);

        document.body.appendChild(form);
        form.submit();
    }

    // Modal Print Configuration
    function openPrintModal() {
        const total = filteredRows.length;
        const totalPages = Math.ceil(total / pageSize) || 1;
        const selectedCount = document.querySelectorAll('.log-row-checkbox:checked').length;

        document.getElementById('modalCurrentPageLabel').textContent = `Page ${currentPage} of ${totalPages}`;
        document.getElementById('modalFilteredCountLabel').textContent = total;
        document.getElementById('modalSelectedCountLabel').textContent = selectedCount;

        const rangeTo = document.getElementById('rangeToPage');
        if (rangeTo) {
            rangeTo.max = totalPages;
            rangeTo.value = totalPages;
        }

        document.getElementById('printConfigModal').classList.remove('hidden');
    }

    function closePrintModal() {
        document.getElementById('printConfigModal').classList.add('hidden');
    }

    function toggleCustomPageInput(show) {
        const container = document.getElementById('customPageRangeContainer');
        if (show) {
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
        }
    }

    function executePrintFromModal() {
        const selectedScope = document.querySelector('input[name="printScopeRadio"]:checked')?.value || 'current_page';
        closePrintModal();
        printScope(selectedScope);
    }

    // Core Print Engine Supporting Selected Page, All Filtered, Checked Rows, and Custom Range
    function printScope(scope) {
        const printTableBody = document.getElementById('printableAuditTableBody');
        if (!printTableBody) return;
        printTableBody.innerHTML = '';

        let targetRows = [];
        let scopeTitle = '';

        const includeHeader = document.getElementById('optIncludeHeader')?.checked !== false;
        const includeSignatures = document.getElementById('optIncludeSignatures')?.checked !== false;
        const includeIp = document.getElementById('optIncludeIp')?.checked !== false;

        document.getElementById('printHeaderBlock').style.display = includeHeader ? 'flex' : 'none';
        document.getElementById('printSignaturesBlock').style.display = includeSignatures ? 'flex' : 'none';

        // Toggle IP column header & cells
        document.querySelectorAll('.print-ip-col').forEach(el => {
            el.style.display = includeIp ? '' : 'none';
        });

        const total = filteredRows.length;
        const totalPages = Math.ceil(total / pageSize) || 1;

        if (scope === 'current_page') {
            const start = (currentPage - 1) * pageSize;
            const end = Math.min(start + pageSize, total);
            for (let i = start; i < end; i++) {
                if (filteredRows[i]) targetRows.push(filteredRows[i]);
            }
            scopeTitle = `Current Page Only (Page ${currentPage} of ${totalPages} • ${targetRows.length} entries)`;
        } else if (scope === 'selected') {
            targetRows = getSelectedRows();
            if (targetRows.length === 0) {
                alert('No log rows selected. Please check at least one row or choose "Current Page".');
                return;
            }
            scopeTitle = `Manually Selected Records (${targetRows.length} checked logs)`;
        } else if (scope === 'page_range') {
            const fromP = Math.max(1, parseInt(document.getElementById('rangeFromPage')?.value || 1, 10));
            const toP = Math.min(totalPages, parseInt(document.getElementById('rangeToPage')?.value || totalPages, 10));
            
            const start = (fromP - 1) * pageSize;
            const end = Math.min(toP * pageSize, total);
            for (let i = start; i < end; i++) {
                if (filteredRows[i]) targetRows.push(filteredRows[i]);
            }
            scopeTitle = `Custom Page Range (Pages ${fromP} to ${toP} • ${targetRows.length} entries)`;
        } else {
            // all_filtered
            targetRows = filteredRows;
            scopeTitle = `All Filtered Records (${targetRows.length} total logs across ${totalPages} pages)`;
        }

        if (targetRows.length === 0) {
            printTableBody.innerHTML = '<tr><td colspan="7" class="p-4 text-center text-slate-400">No audit log records to display.</td></tr>';
        } else {
            targetRows.forEach(row => {
                const timeCol = row.children[1]?.innerText.replace(/\n/g, ' ').trim() || '';
                const actorCol = row.children[2]?.innerText.replace(/\n/g, ' - ').trim() || '';
                const actionCol = row.children[3]?.innerText.trim() || '';
                const targetCol = row.children[4]?.innerText.trim() || '';
                const detailsCol = row.children[5]?.innerText.trim() || '';
                const resultCol = row.children[6]?.innerText.trim() || '';
                const ipVal = row.getAttribute('data-ip') || '127.0.0.1';

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="border border-slate-300 p-2 font-mono whitespace-nowrap text-[11px]">${timeCol}</td>
                    <td class="border border-slate-300 p-2 font-bold text-[11px]">${actorCol}</td>
                    <td class="border border-slate-300 p-2 text-[11px]">${actionCol}</td>
                    <td class="border border-slate-300 p-2 font-mono text-[11px]">${targetCol}</td>
                    <td class="border border-slate-300 p-2 text-[11px]">${detailsCol}</td>
                    <td class="border border-slate-300 p-2 font-mono text-[10px] print-ip-col" style="${includeIp ? '' : 'display:none;'}">${ipVal}</td>
                    <td class="border border-slate-300 p-2 font-bold uppercase text-[11px]">${resultCol}</td>
                `;
                printTableBody.appendChild(tr);
            });
        }

        const metaLabel = document.getElementById('printFilterMetadata');
        if (metaLabel) {
            metaLabel.textContent = scopeTitle;
        }

        window.print();
    }

    // Modal Inspection
    function inspectLog(log) {
        currentInspectedLog = log;
        document.getElementById('modalLogId').textContent = `Log Record #${log.id} • ${log.created_at}`;
        document.getElementById('modalTime').textContent = log.created_at;
        document.getElementById('modalUser').textContent = log.user_name || 'System / Anonymous';
        document.getElementById('modalRole').textContent = log.role_name ? log.role_name.toUpperCase() : 'AUTOMATED SYSTEM';
        document.getElementById('modalAction').textContent = log.action;
        document.getElementById('modalRecord').textContent = log.affected_record || 'N/A';
        document.getElementById('modalIp').textContent = log.ip_address || '127.0.0.1';
        document.getElementById('modalAgent').textContent = log.user_agent || 'Standard Web Client';
        document.getElementById('modalDetails').textContent = log.details || 'No additional parameters provided.';

        const isSuccess = (log.result || '').toLowerCase() === 'success';
        document.getElementById('modalResult').innerHTML = isSuccess 
            ? '<span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-900 border border-emerald-300">SUCCESS</span>'
            : '<span class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-red-50 text-red-900 border border-red-300">FAILED</span>';

        document.getElementById('logDetailModal').classList.remove('hidden');
    }

    function closeInspectModal() {
        document.getElementById('logDetailModal').classList.add('hidden');
    }

    function printSingleIncident() {
        if (!currentInspectedLog) return;
        closeInspectModal();

        const printTableBody = document.getElementById('printableAuditTableBody');
        if (!printTableBody) return;
        printTableBody.innerHTML = `
            <tr>
                <td class="border border-slate-300 p-2 font-mono whitespace-nowrap text-[11px]">${currentInspectedLog.created_at}</td>
                <td class="border border-slate-300 p-2 font-bold text-[11px]">${currentInspectedLog.user_name || 'System'} (${currentInspectedLog.role_name || 'Automated'})</td>
                <td class="border border-slate-300 p-2 text-[11px]">${currentInspectedLog.action}</td>
                <td class="border border-slate-300 p-2 font-mono text-[11px]">${currentInspectedLog.affected_record || 'N/A'}</td>
                <td class="border border-slate-300 p-2 text-[11px]">${currentInspectedLog.details || 'N/A'}</td>
                <td class="border border-slate-300 p-2 font-mono text-[10px] print-ip-col">${currentInspectedLog.ip_address || '127.0.0.1'}</td>
                <td class="border border-slate-300 p-2 font-bold uppercase text-[11px]">${(currentInspectedLog.result || 'SUCCESS').toUpperCase()}</td>
            </tr>
        `;

        document.getElementById('printFilterMetadata').textContent = `Single Log Forensic Report (#${currentInspectedLog.id})`;
        window.print();
    }

    // CSV Export Helpers
    function exportFilteredLogsCSV() {
        const rowsToExport = (filteredRows && filteredRows.length > 0) ? filteredRows : Array.from(document.querySelectorAll('tbody tr.log-row'));
        downloadCSVFromRows(rowsToExport, '<?php echo $isArchiveView ? "Archived_Audit_Logs" : "Active_Audit_Logs"; ?>');
    }

    function exportSelectedLogsCSV() {
        const selected = getSelectedRows();
        if (selected.length === 0) {
            alert('Please select at least one log row to export.');
            return;
        }
        downloadCSVFromRows(selected, '<?php echo $isArchiveView ? "Selected_Archived_Logs" : "Selected_Audit_Logs"; ?>');
    }

    function downloadCSVFromRows(rows, filenamePrefix) {
        const headers = ['Log ID', 'Timestamp', 'Actor / User', 'Action', 'Target Entity', 'Details / Remarks', 'IP Address', 'Result'];
        const csvContent = [];
        csvContent.push(headers.map(h => `"${h}"`).join(','));

        rows.forEach(row => {
            const logId = row.getAttribute('data-id') || '';
            const timeCol = row.children[1]?.innerText.replace(/\n/g, ' ').trim() || '';
            const actorCol = row.children[2]?.innerText.replace(/\n/g, ' - ').trim() || '';
            const actionCol = row.children[3]?.innerText.trim() || '';
            const targetCol = row.children[4]?.innerText.trim() || '';
            const detailsCol = (row.children[5]?.innerText.trim() || '').replace(/"/g, '""');
            const ipVal = row.getAttribute('data-ip') || '127.0.0.1';
            const resultCol = row.children[6]?.innerText.trim() || '';

            const line = [logId, timeCol, actorCol, actionCol, targetCol, detailsCol, ipVal, resultCol];
            csvContent.push(line.map(val => `"${val}"`).join(','));
        });

        const blob = new Blob(["\uFEFF" + csvContent.join('\r\n')], { type: 'text/csv;charset=utf-8;' });
        const link = document.createElement('a');
        const url = URL.createObjectURL(blob);
        link.setAttribute('href', url);
        link.setAttribute('download', `${filenamePrefix}_${new Date().toISOString().split('T')[0]}.csv`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    // Initial setup
    document.addEventListener('DOMContentLoaded', () => {
        applyFilters();
    });
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
