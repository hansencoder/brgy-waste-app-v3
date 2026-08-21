<?php include __DIR__ . '/../layouts/header.php'; ?>

<?php
$reports = $data['reports'] ?? [];
$reports_by_purok = $data['reports_by_purok'] ?? [];
$puroks = $data['puroks'] ?? [];
$status_counts = $data['status_counts'] ?? [
    'Total' => 0,
    'Pending' => 0,
    'Verified' => 0,
    'Rejected' => 0,
    'In Progress' => 0,
    'Resolved' => 0
];

$activeStatus = $data['active_status'] ?? ($_GET['status'] ?? '');
$searchQuery = $_GET['search'] ?? '';
$dateFrom = $data['date_from'] ?? ($_GET['date_from'] ?? '');
$dateTo = $data['date_to'] ?? ($_GET['date_to'] ?? '');
$selectedPurok = $data['selected_purok'] ?? ($_GET['purok'] ?? '');
$selectedReporterType = $data['selected_reporter_type'] ?? ($_GET['reporter_type'] ?? '');

$repSettings = $data['report_settings'] ?? [];
$barangay = $data['barangay'] ?? [];

$logoLeft = format_asset_url(!empty($repSettings['header_logo_left']) ? $repSettings['header_logo_left'] : ($barangay['barangay_logo'] ?? ''));
$logoRight = format_asset_url(!empty($repSettings['header_logo_right']) ? $repSettings['header_logo_right'] : ($barangay['system_logo'] ?? ''));

// Helper for status badge styling
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

// Helper to preserve existing GET params while changing one
function buildReportFilterUrl($params = []) {
    $current = $_GET;
    foreach ($params as $k => $v) {
        if ($v === '' || $v === null) {
            unset($current[$k]);
        } else {
            $current[$k] = $v;
        }
    }
    return app_url('admin/reports' . (!empty($current) ? '?' . http_build_query($current) : ''));
}

// Metric cards config
$metrics = [
    'Total'       => ['value' => $status_counts['Total'], 'color' => 'text-slate-900', 'bg' => 'bg-white', 'border' => 'border-slate-200', 'filter' => ''],
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
        header, aside, .no-print, #bulkActionBar, nav, .sidebar-link, #mobileSidebarOverlay, .mobile-menu-btn, .view-mode-tab { 
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
            padding: 8px 12px !important;
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
        .purok-print-card {
            page-break-inside: avoid !important;
            margin-bottom: 16px !important;
        }
    }
</style>

<div class="min-h-screen bg-[#F8FAFC] text-slate-800 w-full flex font-sans antialiased">
    
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
                <div class="max-w-9xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-5">

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
                            <div><span>Date Period: </span><strong><?php echo (!empty($dateFrom) || !empty($dateTo)) ? (htmlspecialchars($dateFrom ?: 'Beginning') . ' to ' . htmlspecialchars($dateTo ?: 'Today')) : 'All Time'; ?></strong></div>
                            <div><span>Purok / Zone: </span><strong><?php echo !empty($selectedPurok) ? htmlspecialchars($selectedPurok) : 'All Puroks'; ?></strong></div>
                            <div><span>Status: </span><strong><?php echo !empty($activeStatus) ? htmlspecialchars($activeStatus) : 'All Statuses'; ?></strong></div>
                        </div>
                    </div>

                    <!-- ============================================================ -->
                    <!-- 1. PAGE HEADER & VIEW MODE CONTROLS                          -->
                    <!-- ============================================================ -->
                    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-xs no-print">
                        <div>
                            <div class="flex items-center gap-2 mb-1.5">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold bg-emerald-100 text-emerald-900 border border-emerald-300">
                                    Report Operations
                                </span>
                                <span class="text-xs font-bold text-slate-400">· Real-time Database Records</span>
                            </div>
                            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                                Waste Report Management
                            </h1>
                            <p class="text-xs sm:text-sm text-slate-500 font-semibold mt-0.5">
                                Filter by date, group by purok/zone, review submissions, export CSV, and generate official print reports.
                            </p>
                        </div>

                        <!-- Right Control Actions -->
                        <div class="flex flex-wrap items-center gap-2.5 self-start lg:self-auto">
                            
                            <!-- View Mode Switcher -->
                            <div class="inline-flex p-1 bg-slate-100 rounded-xl border border-slate-200 text-xs font-extrabold">
                                <button type="button" onclick="switchViewMode('list')" id="btnViewList"
                                        class="view-mode-tab inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white text-slate-900 shadow-2xs transition-all cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-700" viewBox="0 0 24 24" fill="currentColor"><path d="M4 6h16v2H4zm0 5h16v2H4zm0 5h16v2H4z"/></svg>
                                    <span>List View</span>
                                </button>
                                <button type="button" onclick="switchViewMode('purok')" id="btnViewPurok"
                                        class="view-mode-tab inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 transition-all cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-700" viewBox="0 0 24 24" fill="currentColor"><path d="M3 3h8v8H3V3zm10 0h8v8h-8V3zM3 13h8v8H3v-8zm10 0h8v8h-8v-8z"/></svg>
                                    <span>Group by Purok</span>
                                </button>
                            </div>

                            <!-- CSV Export Button -->
                            <button onclick="exportTableToCSV()" class="inline-flex items-center gap-2 rounded-xl bg-[#0B2E22] hover:bg-[#084232] px-4 py-2.5 text-xs font-extrabold text-white shadow-xs transition border border-emerald-900 cursor-pointer active:scale-[0.98]" title="Export filtered records to CSV">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-400" viewBox="0 0 24 24" fill="currentColor"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg>
                                <span>Export CSV</span>
                            </button>

                            <!-- Print Button -->
                            <button onclick="printOfficialReports(false)" class="inline-flex items-center gap-2 rounded-xl bg-white hover:bg-slate-50 px-4 py-2.5 text-xs font-extrabold text-slate-800 border border-slate-200 shadow-2xs transition cursor-pointer active:scale-[0.98]" title="Print official report">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-700" viewBox="0 0 24 24" fill="currentColor"><path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/></svg>
                                <span>Print Official</span>
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
                            <a href="<?php echo buildReportFilterUrl(['status' => $m['filter']]); ?>" 
                               class="rounded-xl p-4 text-center border transition-all shadow-xs hover:scale-[1.02] <?php echo $m['bg']; ?> <?php echo $m['border']; ?> <?php echo $isSelected ? 'ring-2 ring-emerald-500 shadow-sm' : ''; ?>">
                                <p class="text-2xl font-black font-mono <?php echo $m['color']; ?>"><?php echo number_format($m['value']); ?></p>
                                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mt-1"><?php echo $label; ?></p>
                            </a>
                        <?php endforeach; ?>
                    </div>

                    <!-- ============================================================ -->
                    <!-- 3. ADVANCED FILTER & DATE CONTROLS TOOLBAR                    -->
                    <!-- ============================================================ -->
                    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs space-y-4 no-print">
                        
                        <!-- Top Filter Row: Date Range Filter & Quick Presets -->
                        <form method="GET" action="<?php echo app_url('admin/reports'); ?>" id="reportFilterForm" class="space-y-3">
                            <input type="hidden" name="url" value="admin/reports">
                            <?php if (!empty($activeStatus)): ?>
                                <input type="hidden" name="status" value="<?php echo htmlspecialchars($activeStatus); ?>">
                            <?php endif; ?>

                            <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-3 pb-3 border-b border-slate-100">
                                
                                <!-- Date Inputs -->
                                <div class="flex flex-wrap items-center gap-2.5 w-full lg:w-auto">
                                    <span class="text-xs font-bold text-slate-700 flex items-center gap-1.5 shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-600" viewBox="0 0 24 24" fill="currentColor"><path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-1.99.9-1.99 2L3 20c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 16H5V10h14v10zm0-12H5V6h14v2z"/></svg>
                                        Date Range:
                                    </span>
                                    <div class="flex items-center gap-1.5">
                                        <input type="date" id="filterDateFrom" name="date_from" value="<?php echo htmlspecialchars($dateFrom); ?>" 
                                               class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-800 focus:bg-white focus:border-emerald-500 outline-none transition cursor-pointer">
                                        <span class="text-xs font-bold text-slate-400">to</span>
                                        <input type="date" id="filterDateTo" name="date_to" value="<?php echo htmlspecialchars($dateTo); ?>" 
                                               class="rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-xs font-bold text-slate-800 focus:bg-white focus:border-emerald-500 outline-none transition cursor-pointer">
                                    </div>
                                    <button type="submit" class="px-3.5 py-2 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-extrabold text-xs transition shadow-2xs cursor-pointer">
                                        Apply Filter
                                    </button>
                                    <?php if (!empty($dateFrom) || !empty($dateTo) || !empty($selectedPurok) || !empty($activeStatus) || !empty($selectedReporterType) || !empty($searchQuery)): ?>
                                        <a href="<?php echo app_url('admin/reports'); ?>" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition cursor-pointer">
                                            Reset All
                                        </a>
                                    <?php endif; ?>
                                </div>

                                <!-- Quick Date Presets -->
                                <div class="flex items-center gap-1.5 overflow-x-auto text-[11px] font-bold text-slate-600 scrollbar-none w-full lg:w-auto justify-start lg:justify-end">
                                    <span class="text-slate-400 font-semibold shrink-0">Presets:</span>
                                    <button type="button" onclick="setDatePreset('today')" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 transition cursor-pointer">Today</button>
                                    <button type="button" onclick="setDatePreset('7days')" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 transition cursor-pointer">Last 7 Days</button>
                                    <button type="button" onclick="setDatePreset('30days')" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 transition cursor-pointer">Last 30 Days</button>
                                    <button type="button" onclick="setDatePreset('this_month')" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 transition cursor-pointer">This Month</button>
                                    <button type="button" onclick="setDatePreset('all')" class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 transition cursor-pointer">All Time</button>
                                </div>
                            </div>

                            <!-- Bottom Filter Controls Row: Purok Dropdown, Reporter Type, Live Search, Per Page -->
                            <div class="flex flex-col sm:flex-row flex-wrap items-center justify-between gap-3 pt-1">
                                <div class="flex flex-wrap items-center gap-2.5 w-full sm:w-auto">
                                    <!-- Purok Filter Dropdown -->
                                    <select name="purok" onchange="document.getElementById('reportFilterForm').submit()" 
                                            class="rounded-xl border border-slate-200 bg-slate-50/80 py-2 px-3 text-xs font-bold text-slate-700 outline-none focus:bg-white focus:border-emerald-500 transition cursor-pointer">
                                        <option value="">All Puroks / Zones</option>
                                        <?php foreach ($puroks as $pk): ?>
                                            <option value="<?php echo htmlspecialchars($pk['purok_name']); ?>" <?php echo ($selectedPurok === $pk['purok_name']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($pk['purok_name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>

                                    <!-- Reporter Type Filter Dropdown -->
                                    <select name="reporter_type" onchange="document.getElementById('reportFilterForm').submit()" 
                                            class="rounded-xl border border-slate-200 bg-slate-50/80 py-2 px-3 text-xs font-bold text-slate-700 outline-none focus:bg-white focus:border-emerald-500 transition cursor-pointer">
                                        <option value="">All Reporter Types</option>
                                        <option value="resident" <?php echo ($selectedReporterType === 'resident') ? 'selected' : ''; ?>>Residents Only</option>
                                        <option value="guest" <?php echo ($selectedReporterType === 'guest') ? 'selected' : ''; ?>>Guests Only</option>
                                    </select>
                                </div>

                                <div class="flex flex-wrap items-center gap-2.5 w-full sm:w-auto justify-start sm:justify-end">
                                    <!-- Live Instant Search Input -->
                                    <div class="relative w-full sm:w-64">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                        </div>
                                        <input type="text" id="liveSearchInput" onkeyup="onSearchOrFilterChange()" placeholder="Live search reports..." value="<?php echo htmlspecialchars($searchQuery); ?>" 
                                               class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2 pl-9 pr-3 text-xs font-semibold text-slate-800 placeholder:text-slate-400 focus:bg-white focus:border-emerald-500 outline-none transition">
                                    </div>

                                    <!-- Per Page Selector (For List View) -->
                                    <div class="flex items-center gap-1 bg-slate-50 border border-slate-200 rounded-xl px-2.5 py-1.5">
                                        <span class="text-[11px] font-bold text-slate-500">Show:</span>
                                        <select id="perPageSelect" onchange="changePerPage(this.value)" class="bg-transparent text-xs font-bold text-slate-800 outline-none cursor-pointer">
                                            <option value="5">5</option>
                                            <option value="10" selected>10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="all">All</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- ============================================================ -->
                    <!-- 4. FLOATING BULK ACTION TOOLBAR                              -->
                    <!-- ============================================================ -->
                    <div id="bulkActionBar" class="hidden fixed bottom-6 left-1/2 -translate-x-1/2 z-50 bg-[#0B2E22] text-white px-5 py-3 rounded-2xl shadow-2xl border border-emerald-500/30 flex items-center gap-3 transition-all flex-wrap">
                        <span class="text-xs font-black font-mono text-emerald-300" id="selectedCount">0 selected</span>
                        <div class="h-4 w-px bg-emerald-700 hidden sm:block"></div>
                        <button onclick="printOfficialReports(true)" class="text-xs font-extrabold bg-emerald-600 hover:bg-emerald-500 text-white px-3.5 py-1.5 rounded-xl flex items-center gap-1.5 transition cursor-pointer shadow-xs border border-emerald-400/30 active:scale-[0.98]" title="Print official report of selected rows">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/></svg>
                            <span>Print Selected</span>
                        </button>
                        <button onclick="exportSelectedRows()" class="text-xs font-extrabold bg-white/10 hover:bg-white/20 text-white px-3.5 py-1.5 rounded-xl flex items-center gap-1.5 transition cursor-pointer border border-white/10 active:scale-[0.98]" title="Export selected rows as CSV">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg>
                            <span>Export Selected</span>
                        </button>
                        <button onclick="unselectAllRows()" class="text-xs font-extrabold text-slate-300 hover:text-white px-2 py-1 transition cursor-pointer ml-1">
                            Cancel
                        </button>
                    </div>

                    <!-- ============================================================ -->
                    <!-- 5. MAIN CONTENT: DUAL VIEW (TABULAR LIST VS GROUPED BY PUROK)-->
                    <!-- ============================================================ -->
                    
                    <!-- VIEW 1: TABULAR LIST VIEW CONTAINER -->
                    <div id="tableViewContainer" class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                        <div class="overflow-x-auto">
                            <table id="reportsDataTable" class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50/90 border-b border-slate-200 text-[11px] font-extrabold uppercase tracking-wider text-slate-400">
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
                                            $purokName = htmlspecialchars($report['purok'] ?? 'Unassigned');
                                            $catName = htmlspecialchars($report['waste_category'] ?? 'General Waste');
                                            $qtyName = htmlspecialchars($report['estimated_quantity'] ?? 'N/A');
                                            $photoCount = (int)($report['photo_count'] ?? 1);
                                        ?>
                                        <tr class="report-row hover:bg-slate-50/80 transition" 
                                            data-search-text="<?php echo strtolower($reportId . ' ' . $reporterName . ' ' . $catName . ' ' . $purokName . ' ' . $report['status']); ?>"
                                            data-reporter-type="<?php echo $isGuest ? 'guest' : 'resident'; ?>"
                                            data-purok="<?php echo htmlspecialchars($purokName); ?>">
                                            
                                            <!-- Checkbox -->
                                            <td class="py-3.5 px-4 text-center no-print">
                                                <input type="checkbox" class="row-checkbox rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 cursor-pointer" 
                                                       value="<?php echo $report['id']; ?>"
                                                       data-id="<?php echo htmlspecialchars($reportId); ?>"
                                                       data-name="<?php echo $reporterName; ?>"
                                                       data-status="<?php echo $report['status']; ?>"
                                                       onchange="updateBulkActionState()">
                                            </td>

                                            <!-- Tracking ID -->
                                            <td class="py-3.5 px-4 font-mono font-black text-slate-900">
                                                <a href="<?php echo app_url('admin/viewReport/' . ($report['id'])); ?>" class="hover:text-emerald-600 transition">
                                                    <?php echo htmlspecialchars($reportId); ?>
                                                </a>
                                            </td>



                                            <!-- Submission Date -->
                                            <td class="py-3.5 px-4 text-slate-600 font-mono">
                                                <div class="font-bold text-slate-800"><?php echo date('M d, Y', strtotime($report['submission_date'])); ?></div>
                                                <div class="text-[10px] text-slate-400 font-sans font-semibold"><?php echo date('g:i A', strtotime($report['submission_date'])); ?></div>
                                            </td>

                                            <!-- Reporter Info -->
                                            <td class="py-3.5 px-4">
                                                <div class="font-bold text-slate-800 inline-flex items-center gap-1.5">
                                                    <span><?php echo $reporterName; ?></span>
                                                    <?php if ($isGuest): ?>
                                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-md bg-amber-100 text-amber-800 text-[9px] font-black uppercase tracking-wider border border-amber-200 leading-none">Guest</span>
                                                    <?php else: ?>
                                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-md bg-emerald-50 text-emerald-700 text-[9px] font-extrabold uppercase tracking-wider border border-emerald-200 leading-none">Resident</span>
                                                    <?php endif; ?>
                                                </div>
                                            </td>

                                            <!-- Waste Category -->
                                            <td class="py-3.5 px-4 text-slate-700 font-bold">
                                                <?php echo $catName; ?>
                                            </td>

                                            <!-- Quantity -->
                                            <td class="py-3.5 px-4 text-slate-600 font-medium">
                                                <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[11px] font-mono font-bold">
                                                    <?php echo $qtyName; ?>
                                                </span>
                                            </td>

                                            <!-- Purok / Zone -->
                                            <td class="py-3.5 px-4 text-slate-700 font-semibold">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-slate-100 text-slate-800 text-xs font-bold">
                                                    <?php echo $purokName; ?>
                                                </span>
                                            </td>

                                             <!-- Status Badge -->
                                            <td class="py-3.5 px-4">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-black border <?php echo $badge['bg']; ?>">
                                                    <?php echo $badge['label']; ?>
                                                </span>
                                            </td>

                                            <!-- Action Button -->
                                            <td class="py-3.5 px-4 text-right no-print">
                                                <a href="<?php echo app_url('admin/viewReport/' . ($report['id'])); ?>" 
                                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-extrabold text-xs border border-emerald-200 transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-700" viewBox="0 0 24 24" fill="currentColor"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                                                    Review
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr id="noRecordsRow">
                                            <td colspan="10" class="py-12 text-center text-slate-400 font-medium">
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
                    </div>

                    <!-- VIEW 2: GROUPED BY PUROK / ZONE CONTAINER -->
                    <div id="purokGroupedContainer" class="hidden space-y-4">
                        
                        <!-- Header Banner for Grouped View -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-4 rounded-2xl border border-slate-200 shadow-xs no-print">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center font-bold">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor"><path d="M3 3h8v8H3V3zm10 0h8v8h-8V3zM3 13h8v8H3v-8zm10 0h8v8h-8v-8z"/></svg>
                                </div>
                                <div>
                                    <h2 class="text-sm sm:text-base font-black text-slate-900">Reports Grouped by Barangay Purok / Zone</h2>
                                    <p class="text-xs text-slate-500 font-medium">Categorized cluster summary for localized waste management and sweep operations.</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button onclick="toggleAllPurokAccordions(true)" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition cursor-pointer">
                                    Expand All
                                </button>
                                <button onclick="toggleAllPurokAccordions(false)" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition cursor-pointer">
                                    Collapse All
                                </button>
                            </div>
                        </div>

                        <?php if (!empty($reports_by_purok)): ?>
                            <?php foreach ($reports_by_purok as $purokName => $pData): 
                                $pCount = $pData['total'];
                                $pReports = $pData['reports'];
                                $purokCardId = 'purok_card_' . preg_replace('/[^a-zA-Z0-9]/', '_', $purokName);
                            ?>
                                <div class="purok-print-card bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden transition" id="<?php echo $purokCardId; ?>">
                                    
                                    <!-- Purok Header Accordion Bar -->
                                    <div class="p-4 sm:p-5 flex flex-col md:flex-row md:items-center justify-between gap-3 bg-slate-50/70 border-b border-slate-200 cursor-pointer select-none hover:bg-slate-50"
                                         onclick="togglePurokAccordion('<?php echo $purokCardId; ?>')">
                                        
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-black text-xs shrink-0 shadow-2xs">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <h3 class="text-base font-black text-slate-900"><?php echo htmlspecialchars($purokName); ?></h3>
                                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-emerald-100 text-emerald-900 border border-emerald-300">
                                                        <?php echo $pCount; ?> Report<?php echo $pCount !== 1 ? 's' : ''; ?>
                                                    </span>
                                                </div>
                                                <div class="flex flex-wrap items-center gap-2 mt-1 text-[11px] font-bold">
                                                    <span class="text-amber-700">Pending: <strong><?php echo $pData['pending']; ?></strong></span>
                                                    <span class="text-slate-300">·</span>
                                                    <span class="text-blue-700">Verified: <strong><?php echo $pData['verified']; ?></strong></span>
                                                    <span class="text-slate-300">·</span>
                                                    <span class="text-purple-700">In Progress: <strong><?php echo $pData['in_progress']; ?></strong></span>
                                                    <span class="text-slate-300">·</span>
                                                    <span class="text-emerald-700">Resolved: <strong><?php echo $pData['resolved']; ?></strong></span>
                                                    <span class="text-slate-300">·</span>
                                                    <span class="text-red-700">Rejected: <strong><?php echo $pData['rejected']; ?></strong></span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Purok Action Buttons (No Print) -->
                                        <div class="flex items-center gap-2 no-print self-end md:self-auto" onclick="event.stopPropagation()">
                                            <button onclick="exportSinglePurokCSV('<?php echo htmlspecialchars(addslashes($purokName)); ?>')" 
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 text-xs font-bold transition cursor-pointer shadow-2xs"
                                                    title="Export only <?php echo htmlspecialchars($purokName); ?> reports">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-600" viewBox="0 0 24 24" fill="currentColor"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg>
                                                <span>CSV</span>
                                            </button>
                                            <button onclick="printSinglePurok('<?php echo htmlspecialchars(addslashes($purokName)); ?>')" 
                                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-200 text-xs font-extrabold transition cursor-pointer"
                                                    title="Print <?php echo htmlspecialchars($purokName); ?> reports">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-700" viewBox="0 0 24 24" fill="currentColor"><path d="M19 8H5c-1.66 0-3 1.34-3 3v6h4v4h12v-4h4v-6c0-1.66-1.34-3-3-3zm-3 11H8v-5h8v5zm3-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm-1-9H6v4h12V3z"/></svg>
                                                <span>Print</span>
                                            </button>
                                            <div class="w-6 h-6 rounded-lg bg-slate-200 flex items-center justify-center text-slate-600 transition transform accordion-arrow">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Purok Reports Table Body -->
                                    <div class="accordion-content overflow-x-auto">
                                        <?php if (!empty($pReports)): ?>
                                            <table class="w-full text-left border-collapse text-xs">
                                                <thead>
                                                    <tr class="bg-slate-50/60 border-b border-slate-200 text-[10px] font-extrabold uppercase tracking-wider text-slate-400">
                                                        <th class="py-2.5 px-4">Report ID</th>
                                                        <th class="py-2.5 px-3">Evidence</th>
                                                        <th class="py-2.5 px-4">Submission Date</th>
                                                        <th class="py-2.5 px-4">Reporter</th>
                                                        <th class="py-2.5 px-4">Category</th>
                                                        <th class="py-2.5 px-4">Est. Quantity</th>
                                                        <th class="py-2.5 px-4">Status</th>
                                                        <th class="py-2.5 px-4 text-right no-print">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-slate-100">
                                                    <?php foreach ($pReports as $prep): 
                                                        $pBadge = getReportBadgeProps($prep['status']);
                                                        $prepId = 'WR-' . str_pad($prep['id'], 6, '0', STR_PAD_LEFT);
                                                        $pIsGuest = isset($prep['reporter_type']) && $prep['reporter_type'] === 'guest';
                                                        $pReporter = htmlspecialchars($prep['name'] ?? 'Guest');
                                                    ?>
                                                        <tr class="hover:bg-slate-50/80 transition">
                                                            <td class="py-3 px-4 font-mono font-bold text-slate-900">
                                                                <a href="<?php echo app_url('admin/viewReport/' . $prep['id']); ?>" class="hover:text-emerald-600">
                                                                    <?php echo htmlspecialchars($prepId); ?>
                                                                </a>
                                                            </td>
                                                            <td class="py-3 px-3">
                                                                <?php if (!empty($prep['photo_path'])): ?>
                                                                    <a href="<?php echo app_url('admin/viewReport/' . $prep['id']); ?>" class="block w-9 h-9 rounded-lg overflow-hidden border border-slate-200 bg-slate-100">
                                                                        <img src="<?php echo htmlspecialchars(format_asset_url($prep['photo_path'])); ?>" alt="Photo" class="w-full h-full object-cover">
                                                                    </a>
                                                                <?php else: ?>
                                                                    <div class="w-9 h-9 rounded-lg border border-dashed border-slate-200 bg-slate-50 flex items-center justify-center text-slate-300">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td class="py-3 px-4 text-slate-600 font-mono">
                                                                <div class="font-bold text-slate-800"><?php echo date('M d, Y', strtotime($prep['submission_date'])); ?></div>
                                                                <div class="text-[10px] text-slate-400"><?php echo date('g:i A', strtotime($prep['submission_date'])); ?></div>
                                                            </td>
                                                            <td class="py-3 px-4 font-bold text-slate-800">
                                                                <div class="flex items-center gap-1.5">
                                                                    <span><?php echo $pReporter; ?></span>
                                                                    <?php if ($pIsGuest): ?>
                                                                        <span class="px-1.5 py-0.2 rounded-md bg-amber-100 text-amber-800 text-[9px] font-black uppercase">Guest</span>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </td>
                                                            <td class="py-3 px-4 text-slate-700 font-bold">
                                                                <?php echo htmlspecialchars($prep['waste_category'] ?? 'General Waste'); ?>
                                                            </td>
                                                            <td class="py-3 px-4 text-slate-600 font-mono font-bold">
                                                                <?php echo htmlspecialchars($prep['estimated_quantity'] ?? 'N/A'); ?>
                                                            </td>
                                                            <td class="py-3 px-4">
                                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-black border <?php echo $pBadge['bg']; ?>">
                                                                    <?php echo $pBadge['label']; ?>
                                                                </span>
                                                            </td>
                                                            <td class="py-3 px-4 text-right no-print">
                                                                <a href="<?php echo app_url('admin/viewReport/' . $prep['id']); ?>" 
                                                                   class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-bold text-xs border border-emerald-200 transition">
                                                                    Review
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        <?php else: ?>
                                            <div class="p-6 text-center text-slate-400 font-medium text-xs">
                                                No active reports recorded in <?php echo htmlspecialchars($purokName); ?> for the selected filters.
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center text-slate-400 font-medium">
                                No purok grouped data available for the current filter criteria.
                            </div>
                        <?php endif; ?>

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
            </main>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- JAVASCRIPT FUNCTIONS FOR FILTERING, PUROK GROUPING & EXPORTS -->
<!-- ============================================================ -->
<script>
    // Global Pagination and View State
    let currentViewMode = 'list'; // 'list' or 'purok'
    let currentPage = 1;
    let perPage = 10;

    // View Switcher
    function switchViewMode(mode) {
        currentViewMode = mode;
        const listContainer = document.getElementById('tableViewContainer');
        const purokContainer = document.getElementById('purokGroupedContainer');
        const btnList = document.getElementById('btnViewList');
        const btnPurok = document.getElementById('btnViewPurok');

        if (mode === 'purok') {
            listContainer.classList.add('hidden');
            purokContainer.classList.remove('hidden');
            btnPurok.className = 'view-mode-tab inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white text-slate-900 shadow-2xs transition-all cursor-pointer';
            btnList.className = 'view-mode-tab inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 transition-all cursor-pointer';
        } else {
            purokContainer.classList.add('hidden');
            listContainer.classList.remove('hidden');
            btnList.className = 'view-mode-tab inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white text-slate-900 shadow-2xs transition-all cursor-pointer';
            btnPurok.className = 'view-mode-tab inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-slate-600 hover:text-slate-900 transition-all cursor-pointer';
            applyPagination();
        }
    }

    // Date Presets Helper
    function setDatePreset(preset) {
        const fromInput = document.getElementById('filterDateFrom');
        const toInput = document.getElementById('filterDateTo');
        const form = document.getElementById('reportFilterForm');
        
        const today = new Date();
        const formatDate = d => d.toISOString().slice(0, 10);

        if (preset === 'today') {
            fromInput.value = formatDate(today);
            toInput.value = formatDate(today);
        } else if (preset === '7days') {
            const past7 = new Date();
            past7.setDate(today.getDate() - 7);
            fromInput.value = formatDate(past7);
            toInput.value = formatDate(today);
        } else if (preset === '30days') {
            const past30 = new Date();
            past30.setDate(today.getDate() - 30);
            fromInput.value = formatDate(past30);
            toInput.value = formatDate(today);
        } else if (preset === 'this_month') {
            const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
            fromInput.value = formatDate(firstDay);
            toInput.value = formatDate(today);
        } else if (preset === 'all') {
            fromInput.value = '';
            toInput.value = '';
        }

        form.submit();
    }

    // Accordion Toggle for Purok Cards
    function togglePurokAccordion(cardId) {
        const card = document.getElementById(cardId);
        if (!card) return;
        const content = card.querySelector('.accordion-content');
        const arrow = card.querySelector('.accordion-arrow');
        
        if (content.style.display === 'none') {
            content.style.display = '';
            if (arrow) arrow.style.transform = 'rotate(0deg)';
        } else {
            content.style.display = 'none';
            if (arrow) arrow.style.transform = 'rotate(-90deg)';
        }
    }

    function toggleAllPurokAccordions(expand) {
        document.querySelectorAll('.purok-print-card').forEach(card => {
            const content = card.querySelector('.accordion-content');
            const arrow = card.querySelector('.accordion-arrow');
            if (content) content.style.display = expand ? '' : 'none';
            if (arrow) arrow.style.transform = expand ? 'rotate(0deg)' : 'rotate(-90deg)';
        });
    }

    // Pagination & Search Logic for List View
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
        const allRows = Array.from(document.querySelectorAll('.report-row'));
        const noRecordsRow = document.getElementById('noRecordsRow');

        const matchingRows = allRows.filter(row => {
            const text = (row.getAttribute('data-search-text') || row.textContent).toLowerCase();
            return !input || text.includes(input);
        });

        const totalMatching = matchingRows.length;
        const totalPages = Math.max(1, Math.ceil(totalMatching / perPage));

        if (currentPage > totalPages) currentPage = totalPages;
        if (currentPage < 1) currentPage = 1;

        const startIdx = (currentPage - 1) * perPage;
        const endIdx = Math.min(startIdx + perPage, totalMatching);

        allRows.forEach(row => row.style.display = 'none');
        matchingRows.slice(startIdx, endIdx).forEach(row => { row.style.display = ''; });

        if (noRecordsRow) {
            noRecordsRow.style.display = (totalMatching === 0) ? '' : 'none';
        }

        const pageStartEl = document.getElementById('pageStart');
        const pageEndEl = document.getElementById('pageEnd');
        const pageTotalEl = document.getElementById('pageTotal');

        if (pageStartEl) pageStartEl.textContent = totalMatching > 0 ? (startIdx + 1) : 0;
        if (pageEndEl) pageEndEl.textContent = endIdx;
        if (pageTotalEl) pageTotalEl.textContent = totalMatching;

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
        const prevDisabled = (currentPage <= 1);
        html += `
            <button onclick="goToPage(${currentPage - 1})" ${prevDisabled ? 'disabled' : ''} 
                    class="px-2.5 py-1.5 rounded-lg border text-xs font-bold transition flex items-center gap-1 ${prevDisabled ? 'border-slate-200 text-slate-300 cursor-not-allowed bg-slate-50' : 'border-slate-300 text-slate-700 bg-white hover:bg-slate-100 cursor-pointer active:scale-95'}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
                <span>Prev</span>
            </button>
        `;

        let startPage = Math.max(1, currentPage - 2);
        let endPage = Math.min(totalPages, startPage + 4);
        if (endPage - startPage < 4) {
            startPage = Math.max(1, endPage - 4);
        }

        if (startPage > 1) {
            html += `<button onclick="goToPage(1)" class="w-8 h-8 rounded-lg border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold transition cursor-pointer">1</button>`;
            if (startPage > 2) html += `<span class="px-1 text-slate-400 font-bold">...</span>`;
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
            if (endPage < totalPages - 1) html += `<span class="px-1 text-slate-400 font-bold">...</span>`;
            html += `<button onclick="goToPage(${totalPages})" class="w-8 h-8 rounded-lg border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold transition cursor-pointer">${totalPages}</button>`;
        }

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

    // Checkbox Bulk Selection
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

    // Official Printing
    function printOfficialReports(onlySelected = false) {
        const selectedBoxes = Array.from(document.querySelectorAll('.row-checkbox:checked'));
        const allRows = Array.from(document.querySelectorAll('.report-row'));
        const scopeLabel = document.getElementById('printScopeLabel');

        if (onlySelected && selectedBoxes.length === 0) {
            alert('Please select at least one report using the checkboxes to print selected reports.');
            return;
        }

        const isSelectedMode = onlySelected || (selectedBoxes.length > 0 && currentViewMode === 'list');

        if (isSelectedMode) {
            if (scopeLabel) scopeLabel.textContent = `Selected Reports (${selectedBoxes.length} item${selectedBoxes.length > 1 ? 's' : ''})`;
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
            const input = (document.getElementById('liveSearchInput')?.value || '').toLowerCase().trim();
            let count = 0;
            allRows.forEach(row => {
                const text = (row.getAttribute('data-search-text') || row.textContent).toLowerCase();
                const matches = (!input || text.includes(input));
                if (matches) {
                    row.style.display = '';
                    row.classList.remove('unselected-for-print');
                    count++;
                } else {
                    row.style.display = 'none';
                    row.classList.add('unselected-for-print');
                }
            });

            if (scopeLabel) scopeLabel.textContent = `All Filtered Records (${count} item${count > 1 ? 's' : ''})`;
            document.body.classList.remove('print-selected-mode');
        }

        // Trigger print
        window.print();

        // Restore pagination view after print
        setTimeout(() => {
            document.body.classList.remove('print-selected-mode');
            allRows.forEach(row => row.classList.remove('unselected-for-print'));
            if (currentViewMode === 'list') {
                applyPagination();
            }
        }, 800);
    }

    // Print Single Purok
    function printSinglePurok(purokName) {
        const allRows = Array.from(document.querySelectorAll('.report-row'));
        const scopeLabel = document.getElementById('printScopeLabel');

        let count = 0;
        allRows.forEach(row => {
            const rowPurok = (row.getAttribute('data-purok') || '').trim();
            if (rowPurok.toLowerCase() === purokName.toLowerCase()) {
                row.style.display = '';
                row.classList.remove('unselected-for-print');
                count++;
            } else {
                row.style.display = 'none';
                row.classList.add('unselected-for-print');
            }
        });

        if (scopeLabel) scopeLabel.textContent = `${purokName} Specific Cluster (${count} item${count > 1 ? 's' : ''})`;
        document.body.classList.add('print-selected-mode');

        window.print();

        setTimeout(() => {
            document.body.classList.remove('print-selected-mode');
            allRows.forEach(row => row.classList.remove('unselected-for-print'));
            if (currentViewMode === 'list') {
                applyPagination();
            }
        }, 800);
    }

    // CSV Exports
    function exportTableToCSV() {
        const input = (document.getElementById('liveSearchInput')?.value || '').toLowerCase().trim();
        const allRows = Array.from(document.querySelectorAll('.report-row'));
        
        let csv = [['Report ID', 'Submission Date', 'Submission Time', 'Reporter Name', 'Reporter Type', 'Category', 'Quantity', 'Purok / Zone', 'Status']];
        
        allRows.forEach(row => {
            const text = (row.getAttribute('data-search-text') || row.textContent).toLowerCase();
            if (input && !text.includes(input)) return;

            const cells = row.querySelectorAll('td');
            if (cells.length >= 9) {
                const dateParts = cells[3].innerText.trim().split('\n');
                csv.push([
                    `"${cells[1].innerText.trim()}"`,
                    `"${dateParts[0] || ''}"`,
                    `"${dateParts[1] || ''}"`,
                    `"${cells[4].innerText.replace(/\n/g, ' ').trim()}"`,
                    `"${row.getAttribute('data-reporter-type') || 'resident'}"`,
                    `"${cells[5].innerText.trim()}"`,
                    `"${cells[6].innerText.trim()}"`,
                    `"${cells[7].innerText.trim()}"`,
                    `"${cells[8].innerText.trim()}"`
                ]);
            }
        });

        downloadCSV(csv, `Waste_Reports_Export_${new Date().toISOString().slice(0,10)}.csv`);
    }

    function exportSelectedRows() {
        const selected = document.querySelectorAll('.row-checkbox:checked');
        if (selected.length === 0) return;

        let csv = [['Report ID', 'Submission Date', 'Submission Time', 'Reporter Name', 'Reporter Type', 'Category', 'Quantity', 'Purok / Zone', 'Status']];
        selected.forEach(cb => {
            const row = cb.closest('tr');
            if (!row) return;
            const cells = row.querySelectorAll('td');
            if (cells.length >= 9) {
                const dateParts = cells[3].innerText.trim().split('\n');
                csv.push([
                    `"${cells[1].innerText.trim()}"`,
                    `"${dateParts[0] || ''}"`,
                    `"${dateParts[1] || ''}"`,
                    `"${cells[4].innerText.replace(/\n/g, ' ').trim()}"`,
                    `"${row.getAttribute('data-reporter-type') || 'resident'}"`,
                    `"${cells[5].innerText.trim()}"`,
                    `"${cells[6].innerText.trim()}"`,
                    `"${cells[7].innerText.trim()}"`,
                    `"${cells[8].innerText.trim()}"`
                ]);
            }
        });

        downloadCSV(csv, `Selected_Reports_Export_${new Date().toISOString().slice(0,10)}.csv`);
    }

    function exportSinglePurokCSV(purokName) {
        const allRows = Array.from(document.querySelectorAll('.report-row'));
        let csv = [['Report ID', 'Submission Date', 'Submission Time', 'Reporter Name', 'Reporter Type', 'Category', 'Quantity', 'Purok / Zone', 'Status']];

        allRows.forEach(row => {
            const rowPurok = (row.getAttribute('data-purok') || '').trim();
            if (rowPurok.toLowerCase() === purokName.toLowerCase()) {
                const cells = row.querySelectorAll('td');
                if (cells.length >= 9) {
                    const dateParts = cells[3].innerText.trim().split('\n');
                    csv.push([
                        `"${cells[1].innerText.trim()}"`,
                        `"${dateParts[0] || ''}"`,
                        `"${dateParts[1] || ''}"`,
                        `"${cells[4].innerText.replace(/\n/g, ' ').trim()}"`,
                        `"${row.getAttribute('data-reporter-type') || 'resident'}"`,
                        `"${cells[5].innerText.trim()}"`,
                        `"${cells[6].innerText.trim()}"`,
                        `"${cells[7].innerText.trim()}"`,
                        `"${cells[8].innerText.trim()}"`
                    ]);
                }
            }
        });

        const safePurok = purokName.replace(/[^a-zA-Z0-9]/g, '_');
        downloadCSV(csv, `${safePurok}_Reports_Export_${new Date().toISOString().slice(0,10)}.csv`);
    }

    function downloadCSV(dataArray, filename) {
        const csvString = '\uFEFF' + dataArray.map(e => e.join(',')).join('\n');
        const blob = new Blob([csvString], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }

    // Initialize list view pagination on DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        applyPagination();
    });
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>