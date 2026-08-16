<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php
$logs = $data['logs'] ?? [];
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
$sysLogo = !empty($barangay['system_logo']) ? $barangay['system_logo'] : '/brgy-waste-app-v3/public/images/logo.png';

// Helper to determine action styling & badges
function getActionMeta($action) {
    $act = strtolower($action ?? '');
    if (strpos($act, 'verified') !== false || strpos($act, 'resolved') !== false) {
        return ['badge' => 'bg-emerald-50 text-emerald-900 border-emerald-200', 'icon' => '✅'];
    } elseif (strpos($act, 'rejected') !== false || strpos($act, 'delete') !== false || strpos($act, 'remove') !== false || strpos($act, 'suspend') !== false) {
        return ['badge' => 'bg-red-50 text-red-900 border-red-200', 'icon' => '🛑'];
    } elseif (strpos($act, 'post') !== false || strpos($act, 'create') !== false || strpos($act, 'add') !== false || strpos($act, 'edit') !== false) {
        return ['badge' => 'bg-blue-50 text-blue-900 border-blue-200', 'icon' => '📝'];
    } elseif (strpos($act, 'gis') !== false || strpos($act, 'boundary') !== false) {
        return ['badge' => 'bg-purple-50 text-purple-900 border-purple-200', 'icon' => '🗺️'];
    } elseif (strpos($act, 'schedule') !== false) {
        return ['badge' => 'bg-amber-50 text-amber-900 border-amber-200', 'icon' => '📅'];
    } elseif (strpos($act, 'export') !== false) {
        return ['badge' => 'bg-teal-50 text-teal-900 border-teal-200', 'icon' => '📊'];
    } else {
        return ['badge' => 'bg-slate-100 text-slate-800 border-slate-200', 'icon' => '⚙️'];
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
            margin: 10mm;
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

                <!-- 1. Header & Quick Actions Toolbar -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <!-- Breadcrumb -->
                        <nav class="flex items-center gap-1.5 text-xs font-bold text-slate-500 mb-1">
                            <span>Admin</span>
                            <span>/</span>
                            <span>Security &amp; Compliance</span>
                            <span>/</span>
                            <span class="text-slate-900">Audit Trail</span>
                        </nav>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2.5">
                            <span>System Audit &amp; Activity Trail</span>
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-900 border border-emerald-300">
                                Live Forensic Logs
                            </span>
                        </h1>
                        <p class="text-xs sm:text-sm text-slate-500 font-semibold mt-1">
                            Immutable audit trail of administrative modifications, report reviews, staff actions, and access events.
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2.5 shrink-0">
                        <button onclick="printAuditReport()" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-white hover:bg-slate-100 text-slate-700 font-extrabold text-xs sm:text-sm border border-slate-200 shadow-2xs transition active:scale-[0.98] cursor-pointer" title="Print Audit Report">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                            <span>Print Report</span>
                        </button>
                    </div>
                </div>

                <!-- 2. KPI Metric Summary Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Total Logs -->
                    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex items-center justify-between">
                        <div class="space-y-1">
                            <p class="text-xs font-extrabold uppercase tracking-wider text-slate-400">Total Recorded Logs</p>
                            <p class="text-2xl font-black text-slate-900"><?php echo number_format($stats['total']); ?></p>
                            <p class="text-[11px] font-semibold text-slate-500">Full audit history retention</p>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-700 flex items-center justify-center font-extrabold border border-slate-200">
                            📁
                        </div>
                    </div>

                    <!-- Today's Activity -->
                    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex items-center justify-between">
                        <div class="space-y-1">
                            <p class="text-xs font-extrabold uppercase tracking-wider text-slate-400">Today's Activity</p>
                            <p class="text-2xl font-black text-emerald-800"><?php echo number_format($stats['today']); ?></p>
                            <p class="text-[11px] font-semibold text-emerald-700">Events logged <?php echo date('M d, Y'); ?></p>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-800 flex items-center justify-center font-extrabold border border-emerald-200">
                            ⚡
                        </div>
                    </div>

                    <!-- Successful Executions -->
                    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex items-center justify-between">
                        <div class="space-y-1">
                            <p class="text-xs font-extrabold uppercase tracking-wider text-slate-400">Successful Executions</p>
                            <p class="text-2xl font-black text-blue-900"><?php echo number_format($stats['success']); ?></p>
                            <p class="text-[11px] font-semibold text-slate-500">Verified &amp; confirmed actions</p>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-800 flex items-center justify-center font-extrabold border border-blue-200">
                            🛡️
                        </div>
                    </div>

                    <!-- Active Admin Staff Actors -->
                    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs flex items-center justify-between">
                        <div class="space-y-1">
                            <p class="text-xs font-extrabold uppercase tracking-wider text-slate-400">Active Actors</p>
                            <p class="text-2xl font-black text-purple-900"><?php echo number_format($stats['unique_users_count']); ?></p>
                            <p class="text-[11px] font-semibold text-purple-700">Staff &amp; admin accounts logged</p>
                        </div>
                        <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-800 flex items-center justify-center font-extrabold border border-purple-200">
                            👥
                        </div>
                    </div>
                </div>

                <!-- 3. Advanced Filtering & Search Toolstrip -->
                <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 shadow-xs space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        
                        <!-- Search Box -->
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            </div>
                            <input type="text" id="searchInput" oninput="applyFilters()" placeholder="Search action, user, target ID, details..." 
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
                                <option value="Dashboard">Dashboard &amp; System Access</option>
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
                        <div class="flex items-center gap-2">
                            <select id="resultFilter" onchange="applyFilters()" 
                                    class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:bg-white focus:border-emerald-500 outline-none transition cursor-pointer">
                                <option value="all">-- All Results --</option>
                                <option value="success">Success</option>
                                <option value="failed">Failed / Error</option>
                            </select>

                            <button onclick="resetFilters()" class="px-3 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-extrabold text-xs transition cursor-pointer shrink-0" title="Reset Filters">
                                Reset
                            </button>
                        </div>
                    </div>

                    <!-- Filter Counter -->
                    <div class="flex items-center justify-between text-xs font-bold text-slate-500 pt-2 border-t border-slate-100">
                        <div>
                            Showing <strong id="visibleLogCount" class="text-slate-900"><?php echo count($logs); ?></strong> of <?php echo count($logs); ?> recorded events
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
                                    ?>
                                        <tr class="log-row hover:bg-slate-50/80 transition group cursor-pointer" 
                                            onclick="inspectLog(<?php echo htmlspecialchars(json_encode($log)); ?>)"
                                            data-user="<?php echo strtolower(htmlspecialchars($userDisplay)); ?>"
                                            data-action="<?php echo strtolower(htmlspecialchars($log['action'])); ?>"
                                            data-details="<?php echo strtolower(htmlspecialchars($log['details'] ?? '')); ?>"
                                            data-record="<?php echo strtolower(htmlspecialchars($log['affected_record'] ?? '')); ?>"
                                            data-result="<?php echo strtolower(htmlspecialchars($log['result'] ?? '')); ?>">
                                            
                                            <!-- Timestamp -->
                                            <td class="px-5 py-3.5 whitespace-nowrap">
                                                <div class="font-extrabold text-slate-900"><?php echo date('M d, Y', strtotime($log['created_at'])); ?></div>
                                                <div class="text-[10px] text-slate-400 font-mono"><?php echo date('h:i:s A', strtotime($log['created_at'])); ?></div>
                                            </td>

                                            <!-- Actor -->
                                            <td class="px-5 py-3.5 whitespace-nowrap">
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
                                            <td class="px-5 py-3.5 whitespace-nowrap">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-extrabold border <?php echo $meta['badge']; ?>">
                                                    <span><?php echo $meta['icon']; ?></span>
                                                    <span><?php echo htmlspecialchars($log['action']); ?></span>
                                                </span>
                                            </td>

                                            <!-- Affected Record -->
                                            <td class="px-5 py-3.5 whitespace-nowrap">
                                                <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-800 font-mono text-[11px] font-bold border border-slate-200">
                                                    <?php echo htmlspecialchars($log['affected_record'] ?? 'N/A'); ?>
                                                </span>
                                            </td>

                                            <!-- Details -->
                                            <td class="px-5 py-3.5 max-w-xs sm:max-w-sm truncate text-slate-600 font-medium" title="<?php echo htmlspecialchars($log['details'] ?? ''); ?>">
                                                <?php echo htmlspecialchars($log['details'] ?? 'No extra remarks'); ?>
                                            </td>

                                            <!-- Result -->
                                            <td class="px-5 py-3.5 whitespace-nowrap">
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
                                                <button type="button" class="p-1.5 rounded-lg text-slate-400 group-hover:text-emerald-700 group-hover:bg-emerald-50 transition" title="Inspect Log Entry">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr id="emptyRow">
                                        <td colspan="7" class="px-6 py-12 text-center text-slate-400 space-y-2">
                                            <div class="text-3xl">📁</div>
                                            <div class="text-sm font-extrabold text-slate-700">No system audit logs found</div>
                                            <div class="text-xs">Any future actions performed by administrators or system scripts will be logged here.</div>
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
<!-- LOG INSPECTOR MODAL                                          -->
<!-- ============================================================ -->
<div id="logDetailModal" class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-xl w-full border border-slate-200 overflow-hidden animate-fadeIn">
        <!-- Modal Header -->
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-black">
                    🔍
                </div>
                <div>
                    <h3 class="text-base font-extrabold text-slate-900">Audit Log Forensic Record</h3>
                    <p class="text-[11px] font-semibold text-slate-500" id="modalLogId">Log Entry Details</p>
                </div>
            </div>
            <button onclick="closeInspectModal()" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-200 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
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

        <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-200 flex items-center justify-end">
            <button onclick="closeInspectModal()" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-extrabold text-xs transition">
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
        <div class="flex items-center justify-between border-b-2 border-slate-800 pb-4">
            <div class="w-16 h-16">
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
            <span id="printFilterMetadata">All System Records</span>
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
                    <th class="border border-slate-300 p-2">Result</th>
                </tr>
            </thead>
            <tbody id="printableAuditTableBody">
                <!-- Populated dynamically before print -->
            </tbody>
        </table>

        <!-- Signatures strip -->
        <div class="pt-8 flex justify-between items-end text-xs text-slate-700">
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
    // Client side filter and pagination logic
    let currentPage = 1;
    let pageSize = 25;
    let filteredRows = [];

    function applyFilters() {
        const query = (document.getElementById('searchInput').value || '').toLowerCase().trim();
        const action = (document.getElementById('actionFilter').value || 'all').toLowerCase();
        const user = (document.getElementById('userFilter').value || 'all').toLowerCase();
        const result = (document.getElementById('resultFilter').value || 'all').toLowerCase();

        const allRows = Array.from(document.querySelectorAll('tbody tr.log-row'));
        filteredRows = [];

        allRows.forEach(row => {
            const rowUser = row.getAttribute('data-user') || '';
            const rowAction = row.getAttribute('data-action') || '';
            const rowDetails = row.getAttribute('data-details') || '';
            const rowRecord = row.getAttribute('data-record') || '';
            const rowResult = row.getAttribute('data-result') || '';

            const matchesQuery = !query || rowUser.includes(query) || rowAction.includes(query) || rowDetails.includes(query) || rowRecord.includes(query);
            const matchesAction = (action === 'all') || rowAction.includes(action);
            const matchesUser = (user === 'all') || (rowUser === user);
            const matchesResult = (result === 'all') || (rowResult === result);

            if (matchesQuery && matchesAction && matchesUser && matchesResult) {
                filteredRows.push(row);
            }
        });

        document.getElementById('visibleLogCount').textContent = filteredRows.length;
        currentPage = 1;
        renderPaginatedRows();
    }

    function resetFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('actionFilter').value = 'all';
        document.getElementById('userFilter').value = 'all';
        document.getElementById('resultFilter').value = 'all';
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

            // Pages
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
    }

    // Modal Inspection
    function inspectLog(log) {
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

    // Print Report Generation
    function printAuditReport() {
        const printTableBody = document.getElementById('printableAuditTableBody');
        if (!printTableBody) return;

        printTableBody.innerHTML = '';
        const visibleRows = (filteredRows && filteredRows.length > 0) ? filteredRows : Array.from(document.querySelectorAll('tbody tr.log-row'));

        if (visibleRows.length === 0) {
            printTableBody.innerHTML = '<tr><td colspan="6" class="p-4 text-center text-slate-400">No audit log records to display.</td></tr>';
        } else {
            visibleRows.forEach(row => {
                const timeCol = row.children[0]?.innerText.replace(/\n/g, ' ').trim() || '';
                const actorCol = row.children[1]?.innerText.replace(/\n/g, ' - ').trim() || '';
                const actionCol = row.children[2]?.innerText.trim() || '';
                const targetCol = row.children[3]?.innerText.trim() || '';
                const detailsCol = row.children[4]?.innerText.trim() || '';
                const resultCol = row.children[5]?.innerText.trim() || '';

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="border border-slate-300 p-2 font-mono whitespace-nowrap text-[11px]">${timeCol}</td>
                    <td class="border border-slate-300 p-2 font-bold text-[11px]">${actorCol}</td>
                    <td class="border border-slate-300 p-2 text-[11px]">${actionCol}</td>
                    <td class="border border-slate-300 p-2 font-mono text-[11px]">${targetCol}</td>
                    <td class="border border-slate-300 p-2 text-[11px]">${detailsCol}</td>
                    <td class="border border-slate-300 p-2 font-bold uppercase text-[11px]">${resultCol}</td>
                `;
                printTableBody.appendChild(tr);
            });
        }

        const filterDesc = [];
        const q = document.getElementById('searchInput')?.value.trim();
        const act = document.getElementById('actionFilter')?.value;
        const usr = document.getElementById('userFilter')?.value;
        const res = document.getElementById('resultFilter')?.value;
        if (q) filterDesc.push(`Query: "${q}"`);
        if (act && act !== 'all') filterDesc.push(`Module: ${act}`);
        if (usr && usr !== 'all') filterDesc.push(`User: ${usr}`);
        if (res && res !== 'all') filterDesc.push(`Result: ${res.toUpperCase()}`);

        const metaLabel = document.getElementById('printFilterMetadata');
        if (metaLabel) {
            metaLabel.textContent = filterDesc.length > 0 ? `Filtered (${visibleRows.length} logs): ${filterDesc.join(' | ')}` : `All Records (${visibleRows.length} total logs)`;
        }

        window.print();
    }

    // Initial setup
    document.addEventListener('DOMContentLoaded', () => {
        applyFilters();
    });
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
