<?php include __DIR__ . '/../layouts/header.php'; ?>

<?php
$reports = $data['reports'] ?? [];
$status_counts = $data['status_counts'] ?? [
    'Total' => 0,
    'Pending' => 0,
    'Verified' => 0,
    'Rejected' => 0,
    'In Progress' => 0,
    'Resolved' => 0
];

$activeStatus = $_GET['status'] ?? '';
$searchQuery = $_GET['search'] ?? '';

$repSettings = $data['report_settings'] ?? [];
$barangay = $data['barangay'] ?? [];

$logoLeft = format_asset_url(!empty($repSettings['header_logo_left']) ? $repSettings['header_logo_left'] : ($barangay['barangay_logo'] ?? ''));
$logoRight = format_asset_url(!empty($repSettings['header_logo_right']) ? $repSettings['header_logo_right'] : ($barangay['system_logo'] ?? ''));

// Helper for status badge styling without decorative dot spans
function getReportBadgeProps($status) {
    $map = [
        'Pending'     => ['bg' => 'bg-amber-50 text-amber-900 border-amber-200', 'label' => 'Pending'],
        'Verified'    => ['bg' => 'bg-blue-50 text-blue-900 border-blue-200', 'label' => 'Verified'],
        'In Progress' => ['bg' => 'bg-purple-50 text-purple-900 border-purple-200', 'label' => 'In Progress'],
        'Resolved'    => ['bg' => 'bg-emerald-50 text-emerald-900 border-emerald-200', 'label' => 'Resolved'],
        'Rejected'    => ['bg' => 'bg-red-50 text-red-900 border-red-200', 'label' => 'Rejected'],
    ];
    return $map[$status] ?? ['bg' => 'bg-slate-50 text-slate-700 border-slate-200', 'label' => $status];
}

// Metric cards config
$metrics = [
    'Total'       => ['value' => $status_counts['Total'], 'color' => 'text-slate-900', 'bg' => 'bg-slate-50', 'border' => 'border-slate-200', 'filter' => ''],
    'Pending'     => ['value' => $status_counts['Pending'], 'color' => 'text-amber-700', 'bg' => 'bg-amber-50/60', 'border' => 'border-amber-200', 'filter' => 'Pending'],
    'Verified'    => ['value' => $status_counts['Verified'], 'color' => 'text-blue-700', 'bg' => 'bg-blue-50/60', 'border' => 'border-blue-200', 'filter' => 'Verified'],
    'In Progress' => ['value' => $status_counts['In Progress'], 'color' => 'text-purple-700', 'bg' => 'bg-purple-50/60', 'border' => 'border-purple-200', 'filter' => 'In Progress'],
    'Resolved'    => ['value' => $status_counts['Resolved'], 'color' => 'text-emerald-700', 'bg' => 'bg-emerald-50/60', 'border' => 'border-emerald-200', 'filter' => 'Resolved'],
    'Rejected'    => ['value' => $status_counts['Rejected'], 'color' => 'text-red-700', 'bg' => 'bg-red-50/60', 'border' => 'border-red-200', 'filter' => 'Rejected'],
];
?>

<style>
    body, * { font-family: 'Miranda Sans', sans-serif !important; font-optical-sizing: auto; }
    .mobile-sidebar-open #mobileSidebar { transform: translateX(0) !important; }
    #mobileSidebarOverlay { transition: opacity 0.25s ease, visibility 0.25s ease; opacity: 0; visibility: hidden; }
    .mobile-sidebar-open #mobileSidebarOverlay { opacity: 1; visibility: visible; }
    
    .print-only-block { display: none; }

    @media print {
        header, aside, .no-print, #bulkActionBar, nav, .sidebar-link, #mobileSidebarOverlay, .mobile-menu-btn { 
            display: none !important; 
        }
        body { 
            background: white !important; 
            padding: 0 !important; 
            margin: 0 !important;
            -webkit-print-color-adjust: exact !important; 
            print-color-adjust: exact !important; 
        }
        .main-container { 
            padding: 0 !important; 
            max-width: 100% !important; 
            margin: 0 !important;
        }
        .print-only-block { 
            display: block !important; 
        }
        body.print-selected-mode tr.unselected-for-print { 
            display: none !important; 
        }
        .print-letterhead { 
            display: flex !important; 
            align-items: center !important; 
            justify-content: space-between !important; 
            border-bottom: 2.5px solid #0f172a !important; 
            padding-bottom: 12px !important; 
            margin-bottom: 12px !important; 
            gap: 16px !important; 
        }
        .print-logo-box { 
            width: 72px !important; 
            height: 72px !important; 
            border-radius: 50% !important; 
            overflow: hidden !important; 
            display: flex !important; 
            align-items: center !important; 
            justify-content: center !important; 
            flex-shrink: 0 !important; 
        }
        .print-logo-box img { 
            width: 100% !important; 
            height: 100% !important; 
            object-fit: contain !important; 
        }
        .print-head-center { 
            text-align: center !important; 
            flex: 1 !important; 
        }
        .print-head-center .rep { 
            font-size: 10px !important; 
            text-transform: uppercase !important; 
            letter-spacing: 0.08em !important; 
            color: #475569 !important; 
            font-weight: 700 !important; 
        }
        .print-head-center .sub { 
            font-size: 11px !important; 
            font-weight: 800 !important; 
            color: #1e293b !important; 
        }
        .print-head-center h1 { 
            font-size: 16px !important; 
            font-weight: 900 !important; 
            color: #0f172a !important; 
            text-transform: uppercase !important; 
            margin: 2px 0 !important; 
        }
        .print-head-center .office { 
            font-size: 10.5px !important; 
            font-weight: 800 !important; 
            color: #065f46 !important; 
            text-transform: uppercase !important; 
        }
        .print-doc-meta {
            display: grid !important;
            grid-template-columns: repeat(4, 1fr) !important;
            gap: 8px !important;
            padding: 6px 10px !important;
            background: #f8fafc !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 6px !important;
            font-size: 10px !important;
            color: #334155 !important;
            margin-bottom: 14px !important;
        }
        table { 
            border-collapse: collapse !important; 
            width: 100% !important; 
            font-size: 10.5px !important; 
            border: 1px solid #cbd5e1 !important;
        }
        th { 
            background: #0f172a !important; 
            color: white !important; 
            padding: 7px 8px !important; 
            font-size: 10px !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            border: 1px solid #0f172a !important;
            -webkit-print-color-adjust: exact !important; 
            print-color-adjust: exact !important; 
        }
        td { 
            border: 1px solid #e2e8f0 !important; 
            padding: 6px 8px !important; 
        }
        tr:nth-child(even) td {
            background-color: #f8fafc !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .print-sig-grid { 
            display: grid !important; 
            grid-template-columns: 1fr 1fr !important; 
            gap: 60px !important; 
            margin-top: 28px !important; 
            padding-top: 10px !important; 
            page-break-inside: avoid !important; 
        }
        .print-sig-line { 
            border-bottom: 1.5px solid #0f172a !important; 
            width: 220px !important; 
            margin-top: 40px !important; 
            margin-bottom: 4px !important; 
        }
    }
</style>

<div class="min-h-screen bg-white text-slate-800 w-full flex font-sans antialiased">
    
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

            <!-- Main Content Area -->
            <main class="flex-1 overflow-y-auto main-container">
                <div class="max-w-9xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">

                    <!-- ============================================================ -->
                    <!-- PRINT LETTERHEAD (Displayed only during official printing)   -->
                    <!-- ============================================================ -->
                    <div class="print-only-block">
                        <div class="print-letterhead">
                            <div class="print-logo-box">
                                <?php if (!empty($logoLeft)): ?>
                                    <img src="<?php echo htmlspecialchars($logoLeft); ?>" alt="Barangay Seal">
                                <?php else: ?>
                                    <div style="background:#f1f5f9;width:100%;height:100%;display:flex;align-items:center;justify-content:center;"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" stroke="#64748b" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18M3 10h18M5 10v11M9 10v11M15 10v11M19 10v11M12 2L2 7h20L12 2z"/></svg></div>
                                <?php endif; ?>
                            </div>

                            <div class="print-head-center">
                                <div class="rep"><?php echo htmlspecialchars($repSettings['republic_header'] ?? 'Republic of the Philippines'); ?></div>
                                <div class="sub"><?php echo htmlspecialchars($repSettings['sub_header'] ?? 'Province of Nueva Ecija · Municipality of Talavera'); ?></div>
                                <h1><?php echo htmlspecialchars($repSettings['report_header'] ?? 'Barangay Dulong Bayan Solid Waste Management Incident Report'); ?></h1>
                                <div class="office"><?php echo htmlspecialchars($repSettings['office_name'] ?? 'Office of the Barangay Solid Waste Management Committee'); ?></div>
                            </div>

                            <div class="print-logo-box">
                                <?php if (!empty($logoRight)): ?>
                                    <img src="<?php echo htmlspecialchars($logoRight); ?>" alt="System Logo">
                                <?php else: ?>
                                    <div style="background:#f1f5f9;width:100%;height:100%;display:flex;align-items:center;justify-content:center;"><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" stroke="#64748b" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21"/><line x1="9" y1="3" x2="9" y2="18"/><line x1="15" y1="6" x2="15" y2="21"/></svg></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Official Document Metadata Strip -->
                        <div class="print-doc-meta">
                            <div><span>Scope: </span><strong id="printScopeLabel">All Filtered Records</strong></div>
                            <div><span>Printed On: </span><strong><?php echo date('F j, Y - h:i A'); ?></strong></div>
                            <div><span>Printed By: </span><strong><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Administrator'); ?></strong></div>
                            <div><span>Status Filter: </span><strong><?php echo !empty($activeStatus) ? htmlspecialchars($activeStatus) : 'All Statuses'; ?></strong></div>
                        </div>
                    </div>

                    <!-- ============================================================ -->
                    <!-- 1. PAGE HEADER & ACTION BUTTONS                              -->
                    <!-- ============================================================ -->
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-xs no-print">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-900 border border-emerald-300">
                                    Report Operations
                                </span>
                                <span class="text-xs font-bold text-slate-400">· Real-time Database Records</span>
                            </div>
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                                Waste Report Management
                            </h1>
                            <p class="text-sm text-slate-500 font-semibold mt-0.5">
                                Review, verify, export, and print official resident and guest report submissions.
                            </p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-2.5 self-start md:self-auto flex-wrap">
                            <!-- Single Clean CSV Export Button -->
                            <button onclick="exportTableToCSV()" class="inline-flex items-center gap-2 rounded-xl bg-[#0B2E22] hover:bg-[#084232] px-4 py-2.5 text-xs font-extrabold text-white shadow-xs transition border border-emerald-900 cursor-pointer active:scale-[0.98]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                Export CSV
                            </button>

                            <!-- Print Button with Official Format -->
                            <button onclick="printOfficialReports(false)" class="inline-flex items-center gap-2 rounded-xl bg-slate-100 hover:bg-slate-200 px-4 py-2.5 text-xs font-extrabold text-slate-800 border border-slate-200 transition cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                                Print Official Report
                            </button>
                        </div>
                    </div>

                    <!-- ============================================================ -->
                    <!-- 2. KPI METRICS SUMMARY CARDS ROW                            -->
                    <!-- ============================================================ -->
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 no-print">
                        <?php foreach ($metrics as $label => $m): 
                            $isSelected = ($activeStatus === $m['filter']) || ($label === 'Total' && empty($activeStatus));
                        ?>
                            <a href="<?php echo app_url('admin/reports<?php echo !empty($m['filter']) ? '?status=' . urlencode($m['filter']) : ''; ?>'); ?>" 
                               class="rounded-lg p-4 text-center border transition-all shadow-xs hover:scale-[1.02] <?php echo $m['bg']; ?> <?php echo $m['border']; ?> <?php echo $isSelected ? 'ring-2 ring-emerald-500 shadow-sm' : ''; ?>">
                                <p class="text-2xl font-extrabold font-mono <?php echo $m['color']; ?>"><?php echo number_format($m['value']); ?></p>
                                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mt-1"><?php echo $label; ?></p>
                            </a>
                        <?php endforeach; ?>
                    </div>

                    <!-- ============================================================ -->
                    <!-- 3. SEARCH, STATUS TABS & FILTER CONTROLS                      -->
                    <!-- ============================================================ -->
                    <div class="bg-white rounded-lg border border-slate-250 p-4 shadow-xs space-y-4 no-print">
                        
                        <!-- Status Filter Tabs -->
                        <div class="flex items-center gap-1.5 overflow-x-auto pb-1 border-b border-slate-100 text-xs font-bold scrollbar-none">
                            <a href="<?php echo app_url('admin/reports'); ?>" 
                               class="px-4 py-2 rounded-xl transition shrink-0 <?php echo empty($activeStatus) ? 'bg-[#0B2E22] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'; ?>">
                                All Reports (<?php echo $status_counts['Total']; ?>)
                            </a>
                            <a href="<?php echo app_url('admin/reports?status=Pending'); ?>" 
                               class="px-4 py-2 rounded-xl transition shrink-0 flex items-center gap-1.5 <?php echo $activeStatus === 'Pending' ? 'bg-amber-600 text-white shadow-xs' : 'text-amber-700 hover:bg-amber-50'; ?>">
                                <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                                Pending (<?php echo $status_counts['Pending']; ?>)
                            </a>
                            <a href="<?php echo app_url('admin/reports?status=Verified'); ?>" 
                               class="px-4 py-2 rounded-xl transition shrink-0 flex items-center gap-1.5 <?php echo $activeStatus === 'Verified' ? 'bg-blue-600 text-white shadow-xs' : 'text-blue-700 hover:bg-blue-50'; ?>">
                                <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                                Verified (<?php echo $status_counts['Verified']; ?>)
                            </a>
                            <a href="<?php echo app_url('admin/reports?status=In Progress'); ?>" 
                               class="px-4 py-2 rounded-xl transition shrink-0 flex items-center gap-1.5 <?php echo $activeStatus === 'In Progress' ? 'bg-emerald-600 text-white shadow-xs' : 'text-emerald-700 hover:bg-emerald-50'; ?>">
                                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                                In Progress (<?php echo $status_counts['In Progress']; ?>)
                            </a>
                            <a href="<?php echo app_url('admin/reports?status=Resolved'); ?>" 
                               class="px-4 py-2 rounded-xl transition shrink-0 flex items-center gap-1.5 <?php echo $activeStatus === 'Resolved' ? 'bg-teal-600 text-white shadow-xs' : 'text-teal-700 hover:bg-teal-50'; ?>">
                                <span class="w-2 h-2 rounded-full bg-teal-400"></span>
                                Resolved (<?php echo $status_counts['Resolved']; ?>)
                            </a>
                            <a href="<?php echo app_url('admin/reports?status=Rejected'); ?>" 
                               class="px-4 py-2 rounded-xl transition shrink-0 flex items-center gap-1.5 <?php echo $activeStatus === 'Rejected' ? 'bg-red-600 text-white shadow-xs' : 'text-red-700 hover:bg-red-50'; ?>">
                                <span class="w-2 h-2 rounded-full bg-red-400"></span>
                                Rejected (<?php echo $status_counts['Rejected']; ?>)
                            </a>
                        </div>

                        <!-- Real-time Live Search & Quick Filter Controls -->
                        <div class="flex flex-col lg:flex-row gap-3 items-center justify-between">
                            <div class="relative w-full lg:w-96">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                </div>
                                <input type="text" id="liveSearchInput" onkeyup="onSearchOrFilterChange()" placeholder="Live search by ID, name, category, purok..." value="<?php echo htmlspecialchars($searchQuery); ?>" 
                                       class="w-full rounded-xl border border-slate-200 bg-slate-50/70 py-2.5 pl-10 pr-4 text-xs font-semibold text-slate-800 placeholder:text-slate-400 focus:bg-white focus:border-[#10B981] focus:ring-4 focus:ring-emerald-500/10 outline-none transition">
                            </div>

                            <div class="flex flex-wrap items-center gap-2.5 w-full lg:w-auto justify-end">
                                <!-- Reporter Type Filter -->
                                <select id="reporterTypeFilter" onchange="onSearchOrFilterChange()" class="rounded-xl border border-slate-200 bg-slate-50/70 py-2.5 px-3 text-xs font-semibold text-slate-700 outline-none focus:bg-white focus:border-[#10B981] transition cursor-pointer">
                                    <option value="">All Reporter Types</option>
                                    <option value="resident">Residents Only</option>
                                    <option value="guest">Guests Only</option>
                                </select>

                                <!-- Per Page Selector -->
                                <div class="flex items-center gap-1.5 bg-slate-50/70 border border-slate-200 rounded-xl px-2.5 py-1.5">
                                    <span class="text-[11px] font-bold text-slate-500">Show:</span>
                                    <select id="perPageSelect" onchange="changePerPage(this.value)" class="bg-transparent text-xs font-bold text-slate-800 outline-none cursor-pointer">
                                        <option value="5">5</option>
                                        <option value="10" selected>10</option>
                                        <option value="20">20</option>
                                        <option value="50">50</option>
                                        <option value="all">All</option>
                                    </select>
                                    <span class="text-[11px] font-bold text-slate-500">/ page</span>
                                </div>

                                <!-- Table Rows Counter -->
                                <span id="recordCountBadge" class="text-xs font-bold font-mono text-slate-600 bg-slate-100 px-3 py-2 rounded-xl">
                                    Total: <?php echo count($reports); ?>
                                </span>
                            </div>
                        </div>

                    </div>

                    <!-- ============================================================ -->
                    <!-- 4. FLOATING BULK ACTION TOOLBAR                              -->
                    <!-- ============================================================ -->
                    <div id="bulkActionBar" class="hidden fixed bottom-6 left-1/2 -translate-x-1/2 z-50 bg-[#0B2E22] text-white px-5 py-3 rounded-2xl shadow-2xl border border-emerald-500/30 flex items-center gap-3 transition-all flex-wrap">
                        <span class="text-xs font-black font-mono text-emerald-300" id="selectedCount">0 selected</span>
                        <div class="h-4 w-px bg-emerald-700 hidden sm:block"></div>
                        <button onclick="printOfficialReports(true)" class="text-xs font-extrabold bg-emerald-600 hover:bg-emerald-500 text-white px-3.5 py-1.5 rounded-xl flex items-center gap-1.5 transition cursor-pointer shadow-xs border border-emerald-400/30 active:scale-[0.98]" title="Print official report of selected rows">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                            <span>Print Selected</span>
                        </button>
                        <button onclick="exportSelectedRows()" class="text-xs font-extrabold bg-white/10 hover:bg-white/20 text-white px-3.5 py-1.5 rounded-xl flex items-center gap-1.5 transition cursor-pointer border border-white/10 active:scale-[0.98]" title="Export selected rows as CSV">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                            <span>Export Selected</span>
                        </button>
                        <button onclick="unselectAllRows()" class="text-xs font-extrabold text-slate-300 hover:text-white px-2 py-1 transition cursor-pointer ml-1">
                            Cancel
                        </button>
                    </div>

                    <!-- ============================================================ -->
                    <!-- 5. MAIN REPORTS DATA TABLE                                   -->
                    <!-- ============================================================ -->
                    <div class="bg-white rounded-lg border border-slate-250 shadow-xs overflow-hidden">
                        
                        <!-- Desktop Table -->
                        <div class="overflow-x-auto">
                            <table id="reportsDataTable" class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50/80 border-b border-slate-200 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                        <th class="py-3.5 px-4 w-10 text-center no-print">
                                            <input type="checkbox" id="selectAllCheckbox" onclick="toggleSelectAllRows(this)" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                                        </th>
                                        <th class="py-3.5 px-4">Report ID</th>
                                        <th class="py-3.5 px-4">Submission Date</th>
                                        <th class="py-3.5 px-4">Reporter Info</th>
                                        <th class="py-3.5 px-4">Waste Category</th>
                                        <th class="py-3.5 px-4">Est. Quantity</th>
                                        <th class="py-3.5 px-4">Purok / Zone</th>
                                        <th class="py-3.5 px-4">Status</th>
                                        <th class="py-3.5 px-4 text-right no-print">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-xs">
                                    <?php if (!empty($reports)): ?>
                                        <?php foreach ($reports as $report):
                                            $badge = getReportBadgeProps($report['status']);
                                            $reportId = 'WR-' . str_pad($report['id'], 6, '0', STR_PAD_LEFT);
                                            $isGuest = isset($report['reporter_type']) && $report['reporter_type'] === 'guest';
                                            $reporterName = htmlspecialchars($report['name'] ?? 'Guest');
                                            $purokName = htmlspecialchars($report['purok'] ?? 'N/A');
                                            $catName = htmlspecialchars($report['waste_category'] ?? 'General Waste');
                                            $qtyName = htmlspecialchars($report['estimated_quantity'] ?? 'N/A');
                                        ?>
                                        <tr class="report-row hover:bg-slate-50/70 transition" 
                                            data-[#0b2e22]="<?php echo strtolower($reportId . ' ' . $reporterName . ' ' . $catName . ' ' . $purokName . ' ' . $report['status']); ?>"
                                            data-reporter-type="<?php echo $isGuest ? 'guest' : 'resident'; ?>">
                                            
                                            <!-- Checkbox -->
                                            <td class="py-4 px-4 text-center no-print">
                                                <input type="checkbox" class="row-checkbox rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer" 
                                                       value="<?php echo $report['id']; ?>"
                                                       data-id="<?php echo htmlspecialchars($reportId); ?>"
                                                       data-name="<?php echo $reporterName; ?>"
                                                       data-status="<?php echo $report['status']; ?>"
                                                       onchange="updateBulkActionState()">
                                            </td>

                                            <!-- Tracking ID -->
                                            <td class="py-4 px-4 font-mono font-bold text-slate-900">
                                                <a href="<?php echo app_url('admin/viewReport/<?php echo $report['id']; ?>'); ?>" class="hover:text-emerald-600 transition">
                                                    <?php echo htmlspecialchars($reportId); ?>
                                                </a>
                                            </td>

                                            <!-- Submission Date -->
                                            <td class="py-4 px-4 text-slate-600 font-mono">
                                                <div><?php echo date('M d, Y', strtotime($report['submission_date'])); ?></div>
                                                <div class="text-[10px] text-slate-400 font-sans"><?php echo date('g:i A', strtotime($report['submission_date'])); ?></div>
                                            </td>

                                            <!-- Reporter Info -->
                                            <td class="py-4 px-4">
                                                <div class="font-bold text-slate-800 flex items-center gap-1.5">
                                                    <span><?php echo $reporterName; ?></span>
                                                    <?php if ($isGuest): ?>
                                                        <span class="px-1.5 py-0.2 rounded-md bg-amber-100 text-amber-800 text-[9px] font-black uppercase tracking-wider border border-amber-200">Guest</span>
                                                    <?php else: ?>
                                                        <span class="px-1.5 py-0.2 rounded-md bg-emerald-50 text-emerald-700 text-[9px] font-extrabold uppercase tracking-wider border border-emerald-200">Resident</span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>

                                            <!-- Waste Category -->
                                            <td class="py-4 px-4 text-slate-700 font-semibold">
                                                <?php echo $catName; ?>
                                            </td>

                                            <!-- Quantity -->
                                            <td class="py-4 px-4 text-slate-600 font-medium">
                                                <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[11px] font-mono">
                                                    <?php echo $qtyName; ?>
                                                </span>
                                            </td>

                                            <!-- Purok / Zone -->
                                            <td class="py-4 px-4 text-slate-700 font-medium">
                                                <?php echo $purokName; ?>
                                            </td>

                                             <!-- Status Badge -->
                                            <td class="py-4 px-4">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold border <?php echo $badge['bg']; ?>">
                                                    <?php echo $badge['label']; ?>
                                                </span>
                                            </td>

                                            <!-- Action Button -->
                                            <td class="py-4 px-4 text-right no-print">
                                                <a href="<?php echo app_url('admin/viewReport/<?php echo $report['id']; ?>'); ?>" 
                                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold text-xs border border-emerald-200 transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                                    Review
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr id="noRecordsRow">
                                            <td colspan="9" class="py-12 text-center text-slate-400 font-medium">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto text-slate-300 mb-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                                No waste report records found matching your filters.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination Footer Bar (No-Print) -->
                        <div id="paginationBar" class="p-4 bg-slate-50/90 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs no-print">
                            <div class="text-slate-600 font-semibold" id="paginationInfo">
                                Showing <span id="pageStart" class="font-bold text-slate-900">0</span> to <span id="pageEnd" class="font-bold text-slate-900">0</span> of <span id="pageTotal" class="font-bold text-slate-900">0</span> entries
                            </div>
                            
                            <div class="flex items-center gap-1" id="paginationControls">
                                <!-- Dynamic pagination buttons -->
                            </div>
                        </div>

                        <!-- Print Only: Official Signatures & Footer Notice -->
                        <div class="print-only-block" style="margin-top: 24px;">
                            <div class="print-sig-grid">
                                <div>
                                    <div style="font-size: 10px; font-weight: 700; color: #64748b; text-transform: uppercase;">Prepared &amp; Certified By:</div>
                                    <div class="print-sig-line"></div>
                                    <div style="font-size: 12px; font-weight: 800; color: #0f172a;"><?php echo htmlspecialchars($repSettings['signatory_name'] ?? ($_SESSION['user_name'] ?? 'Administrator')); ?></div>
                                    <div style="font-size: 10px; font-weight: 700; color: #64748b;"><?php echo htmlspecialchars($repSettings['signatory_position'] ?? 'Barangay Secretary'); ?></div>
                                </div>

                                <div style="text-align: right;">
                                    <div style="font-size: 10px; font-weight: 700; color: #64748b; text-transform: uppercase;">Approved &amp; Noted By:</div>
                                    <div class="print-sig-line" style="margin-left: auto;"></div>
                                    <div style="font-size: 12px; font-weight: 800; color: #0f172a;"><?php echo htmlspecialchars($repSettings['signatory_approved_name'] ?? 'Hon. Punong Barangay'); ?></div>
                                    <div style="font-size: 10px; font-weight: 700; color: #64748b;"><?php echo htmlspecialchars($repSettings['signatory_approved_position'] ?? 'Punong Barangay'); ?></div>
                                </div>
                            </div>

                            <div style="margin-top: 20px; padding-top: 10px; border-top: 1px solid #e2e8f0; text-align: center; font-size: 10px; color: #64748b;">
                                <div><?php echo htmlspecialchars($repSettings['report_footer'] ?? 'This report is for official use only.'); ?></div>
                                <?php if (!empty($repSettings['disclaimer'])): ?>
                                    <div style="font-size: 9px; color: #94a3b8; margin-top: 3px; font-style: italic;"><?php echo htmlspecialchars($repSettings['disclaimer']); ?></div>
                                <?php endif; ?>
                                <div style="font-size: 9px; color: #cbd5e1; margin-top: 3px;">System Generated on <?php echo date('M d, Y h:i A'); ?></div>
                            </div>
                        </div>

                    </div>

                </div>
            </main>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- JAVASCRIPT FUNCTIONS FOR PAGINATION, FILTERING, EXPORT & BULK -->
<!-- ============================================================ -->
<script>
    // Global Pagination State
    let currentPage = 1;
    let perPage = 10;

    function changePerPage(val) {
        perPage = (val === 'all') ? 999999 : parseInt(val, 10);
        currentPage = 1;
        applyPagination();
    }

    function onSearchOrFilterChange() {
        currentPage = 1;
        applyPagination();
    }

    function goToPage(page) {
        currentPage = page;
        applyPagination();
    }

    function applyPagination() {
        const input = (document.getElementById('liveSearchInput')?.value || '').toLowerCase().trim();
        const typeFilter = document.getElementById('reporterTypeFilter')?.value || '';
        const allRows = Array.from(document.querySelectorAll('.report-row'));
        const noRecordsRow = document.getElementById('noRecordsRow');
        const paginationBar = document.getElementById('paginationBar');

        // 1. Filter rows that match search and reporter type
        const matchingRows = allRows.filter(row => {
            const text = (row.getAttribute('data-[#0b2e22]') || row.textContent).toLowerCase();
            const repType = row.getAttribute('data-reporter-type') || '';
            const matchesSearch = !input || text.includes(input);
            const matchesType = !typeFilter || repType === typeFilter;
            return matchesSearch && matchesType;
        });

        const totalMatching = matchingRows.length;
        const totalPages = Math.max(1, Math.ceil(totalMatching / perPage));

        if (currentPage > totalPages) currentPage = totalPages;
        if (currentPage < 1) currentPage = 1;

        const startIdx = (currentPage - 1) * perPage;
        const endIdx = Math.min(startIdx + perPage, totalMatching);

        // 2. Hide all rows first, then show only the active page's matching rows
        allRows.forEach(row => row.style.display = 'none');

        matchingRows.slice(startIdx, endIdx).forEach(row => {
            row.style.display = '';
        });

        // 3. Handle Empty State
        if (noRecordsRow) {
            noRecordsRow.style.display = (totalMatching === 0) ? '' : 'none';
        }

        // 4. Update Header Badge & Footer Text
        const recordCountBadge = document.getElementById('recordCountBadge');
        if (recordCountBadge) {
            recordCountBadge.textContent = `Total: ${totalMatching}`;
        }

        const pageStartEl = document.getElementById('pageStart');
        const pageEndEl = document.getElementById('pageEnd');
        const pageTotalEl = document.getElementById('pageTotal');

        if (pageStartEl) pageStartEl.textContent = totalMatching > 0 ? (startIdx + 1) : 0;
        if (pageEndEl) pageEndEl.textContent = endIdx;
        if (pageTotalEl) pageTotalEl.textContent = totalMatching;

        // 5. Render Pagination Controls
        renderPaginationControls(totalPages);
        updateBulkActionState();
    }

    function renderPaginationControls(totalPages) {
        const container = document.getElementById('paginationControls');
        if (!container) return;

        if (totalPages <= 1 && perPage >= 999999) {
            container.innerHTML = '';
            return;
        }

        let html = '';

        // Previous Button
        const prevDisabled = (currentPage <= 1);
        html += `
            <button onclick="goToPage(${currentPage - 1})" ${prevDisabled ? 'disabled' : ''} 
                    class="px-2.5 py-1.5 rounded-lg border text-xs font-bold transition flex items-center gap-1 ${prevDisabled ? 'border-slate-200 text-slate-300 cursor-not-allowed bg-slate-50' : 'border-slate-300 text-slate-700 bg-white hover:bg-slate-100 cursor-pointer active:scale-95'}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                <span>Prev</span>
            </button>
        `;

        // Page Number Buttons (Show max 5 numbered buttons with window around currentPage)
        let startPage = Math.max(1, currentPage - 2);
        let endPage = Math.min(totalPages, startPage + 4);
        if (endPage - startPage < 4) {
            startPage = Math.max(1, endPage - 4);
        }

        if (startPage > 1) {
            html += `<button onclick="goToPage(1)" class="w-8 h-8 rounded-lg border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold transition cursor-pointer">1</button>`;
            if (startPage > 2) {
                html += `<span class="px-1 text-slate-400 font-bold">...</span>`;
            }
        }

        for (let p = startPage; p <= endPage; p++) {
            const isActive = (p === currentPage);
            html += `
                <button onclick="goToPage(${p})" 
                        class="w-8 h-8 rounded-lg text-xs font-bold transition cursor-pointer ${isActive ? 'bg-[#0B2E22] text-white shadow-xs' : 'border border-slate-200 bg-white hover:bg-slate-100 text-slate-700'}">
                    ${p}
                </button>
            `;
        }

        if (endPage < totalPages) {
            if (endPage < totalPages - 1) {
                html += `<span class="px-1 text-slate-400 font-bold">...</span>`;
            }
            html += `<button onclick="goToPage(${totalPages})" class="w-8 h-8 rounded-lg border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold transition cursor-pointer">${totalPages}</button>`;
        }

        // Next Button
        const nextDisabled = (currentPage >= totalPages);
        html += `
            <button onclick="goToPage(${currentPage + 1})" ${nextDisabled ? 'disabled' : ''} 
                    class="px-2.5 py-1.5 rounded-lg border text-xs font-bold transition flex items-center gap-1 ${nextDisabled ? 'border-slate-200 text-slate-300 cursor-not-allowed bg-slate-50' : 'border-slate-300 text-slate-700 bg-white hover:bg-slate-100 cursor-pointer active:scale-95'}">
                <span>Next</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </button>
        `;

        container.innerHTML = html;
    }

    // Select All Checkbox Handler
    function toggleSelectAllRows(mainCheckbox) {
        const checkboxes = document.querySelectorAll('.row-checkbox');
        checkboxes.forEach(cb => {
            const row = cb.closest('tr');
            if (row && row.style.display !== 'none') {
                cb.checked = mainCheckbox.checked;
            }
        });
        updateBulkActionState();
    }

    function updateBulkActionState() {
        const selected = document.querySelectorAll('.row-checkbox:checked');
        const bulkBar = document.getElementById('bulkActionBar');
        const countText = document.getElementById('selectedCount');

        if (selected.length > 0) {
            bulkBar?.classList.remove('hidden');
            if (countText) countText.textContent = `${selected.length} item(s) selected`;
        } else {
            bulkBar?.classList.add('hidden');
        }
    }

    function unselectAllRows() {
        document.querySelectorAll('.row-checkbox').forEach(cb => cb.checked = false);
        const main = document.getElementById('selectAllCheckbox');
        if (main) main.checked = false;
        updateBulkActionState();
    }

    // Official Print Handler (Prints All Filtered or Selected Reports)
    function printOfficialReports(onlySelected = false) {
        const selectedBoxes = Array.from(document.querySelectorAll('.row-checkbox:checked'));
        const allRows = Array.from(document.querySelectorAll('.report-row'));
        const scopeLabel = document.getElementById('printScopeLabel');

        if (onlySelected && selectedBoxes.length === 0) {
            alert('Please select at least one report using the checkboxes to print selected reports.');
            return;
        }

        const isSelectedMode = onlySelected || selectedBoxes.length > 0;

        if (isSelectedMode) {
            if (scopeLabel) {
                scopeLabel.textContent = `Selected Reports (${selectedBoxes.length} item${selectedBoxes.length > 1 ? 's' : ''})`;
            }
            document.body.classList.add('print-selected-mode');
            
            allRows.forEach(row => {
                const cb = row.querySelector('.row-checkbox');
                if (cb && cb.checked) {
                    row.style.display = '';
                    row.classList.remove('unselected-for-print');
                } else {
                    row.style.display = 'none';
                    row.classList.add('unselected-for-print');
                }
            });
        } else {
            // Print all filtered records regardless of current page
            const input = (document.getElementById('liveSearchInput')?.value || '').toLowerCase().trim();
            const typeFilter = document.getElementById('reporterTypeFilter')?.value || '';
            
            let count = 0;
            allRows.forEach(row => {
                const text = (row.getAttribute('data-[#0b2e22]') || row.textContent).toLowerCase();
                const repType = row.getAttribute('data-reporter-type') || '';
                const matches = (!input || text.includes(input)) && (!typeFilter || repType === typeFilter);
                if (matches) {
                    row.style.display = '';
                    row.classList.remove('unselected-for-print');
                    count++;
                } else {
                    row.style.display = 'none';
                    row.classList.add('unselected-for-print');
                }
            });

            if (scopeLabel) {
                scopeLabel.textContent = `All Filtered Records (${count} item${count > 1 ? 's' : ''})`;
            }
            document.body.classList.remove('print-selected-mode');
        }

        // Trigger browser print
        window.print();

        // Restore pagination view after print
        setTimeout(() => {
            document.body.classList.remove('print-selected-mode');
            allRows.forEach(row => row.classList.remove('unselected-for-print'));
            applyPagination();
        }, 800);
    }

    // CSV Exporter for All Filtered Records
    function exportTableToCSV() {
        const input = (document.getElementById('liveSearchInput')?.value || '').toLowerCase().trim();
        const typeFilter = document.getElementById('reporterTypeFilter')?.value || '';
        const allRows = Array.from(document.querySelectorAll('.report-row'));
        
        let csv = [['Report ID', 'Submission Date', 'Reporter Name', 'Reporter Type', 'Category', 'Quantity', 'Purok', 'Status']];
        
        allRows.forEach(row => {
            const text = (row.getAttribute('data-[#0b2e22]') || row.textContent).toLowerCase();
            const repType = row.getAttribute('data-reporter-type') || '';
            const matches = (!input || text.includes(input)) && (!typeFilter || repType === typeFilter);
            if (!matches) return;

            const cells = row.querySelectorAll('td');
            if (cells.length >= 8) {
                csv.push([
                    `"${cells[1].innerText.trim()}"`,
                    `"${cells[2].innerText.replace(/\n/g, ' ').trim()}"`,
                    `"${cells[3].innerText.replace(/\n/g, ' ').trim()}"`,
                    `"${row.getAttribute('data-reporter-type') || 'resident'}"`,
                    `"${cells[4].innerText.trim()}"`,
                    `"${cells[5].innerText.trim()}"`,
                    `"${cells[6].innerText.trim()}"`,
                    `"${cells[7].innerText.trim()}"`
                ]);
            }
        });

        const csvString = csv.map(e => e.join(',')).join('\n');
        const blob = new Blob([csvString], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `Waste_Reports_Export_${new Date().toISOString().slice(0,10)}.csv`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }

    // Export Selected Rows to CSV
    function exportSelectedRows() {
        const selected = document.querySelectorAll('.row-checkbox:checked');
        if (selected.length === 0) return;

        let csv = [['Report ID', 'Submission Date', 'Reporter Name', 'Reporter Type', 'Category', 'Quantity', 'Purok', 'Status']];
        selected.forEach(cb => {
            const row = cb.closest('tr');
            if (!row) return;
            const cells = row.querySelectorAll('td');
            if (cells.length >= 8) {
                csv.push([
                    `"${cells[1].innerText.trim()}"`,
                    `"${cells[2].innerText.replace(/\n/g, ' ').trim()}"`,
                    `"${cells[3].innerText.replace(/\n/g, ' ').trim()}"`,
                    `"${row.getAttribute('data-reporter-type') || 'resident'}"`,
                    `"${cells[4].innerText.trim()}"`,
                    `"${cells[5].innerText.trim()}"`,
                    `"${cells[6].innerText.trim()}"`,
                    `"${cells[7].innerText.trim()}"`
                ]);
            }
        });

        const csvString = csv.map(e => e.join(',')).join('\n');
        const blob = new Blob([csvString], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `Selected_Reports_Export_${new Date().toISOString().slice(0,10)}.csv`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }

    // Initialize pagination on DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        applyPagination();
    });
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>