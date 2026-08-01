<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php
$reports = $data['reports'] ?? [];
$statuses = $data['statuses'] ?? [];
$categories = $data['categories'] ?? [];
$puroks = $data['puroks'] ?? [];

// Helper for status badge
function getStatusBadge($status) {
    $map = [
        'Pending'     => ['bg' => '#FEF3C7', 'text' => '#92400E', 'label' => 'Pending'],
        'Verified'    => ['bg' => '#DCFCE7', 'text' => '#15803D', 'label' => 'Verified'],
        'Resolved'    => ['bg' => '#E0F2FE', 'text' => '#0369A1', 'label' => 'Resolved'],
        'Rejected'    => ['bg' => '#FEE2E2', 'text' => '#B91C1C', 'label' => 'Rejected'],
        'In Progress' => ['bg' => '#FFEDD5', 'text' => '#C2410C', 'label' => 'In Progress'],
    ];
    return $map[$status] ?? ['bg' => '#F3F4F6', 'text' => '#4B5563', 'label' => $status];
}

// Get filter values from GET
$search = isset($_GET['search']) ? $_GET['search'] : '';
$statusFilter = isset($_GET['status']) ? $_GET['status'] : '';
$categoryFilter = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$purokFilter = isset($_GET['purok']) ? (int)$_GET['purok'] : 0;
$dateFrom = isset($_GET['date_from']) ? $_GET['date_from'] : '';
$dateTo = isset($_GET['date_to']) ? $_GET['date_to'] : '';
?>

<div class="min-h-screen bg-[#F8FAFC]">
    <div class="lg:flex lg:min-h-screen">
        <?php include __DIR__ . '/../layouts/supervisor_sidebar.php'; ?>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="sticky top-0 z-40 bg-white/90 backdrop-blur border-b border-slate-200/80 px-4 sm:px-6 py-3 flex flex-wrap items-center justify-between gap-2">
                <div class="min-w-0">
                    <h1 class="text-base sm:text-lg md:text-xl font-bold text-slate-900 tracking-tight truncate">Reports Monitoring</h1>
                    <p class="text-xs text-slate-500 font-medium truncate">View and monitor all waste reports</p>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <span class="text-xs text-slate-500 font-medium"><?php echo count($reports); ?> reports</span>
                    <a href="/brgy-waste-app-v3/public/index.php?url=supervisor/reports&export=csv" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 text-emerald-700 text-xs font-semibold rounded-lg hover:bg-emerald-100 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        Export
                    </a>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">

                    <!-- Filter Bar with Date Range -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 mb-6">
                        <form method="GET" action="">
                            <!-- First Row: Search, Status, Category, Purok -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 mb-1">Search</label>
                                    <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="ID, resident, category..." class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 mb-1">Status</label>
                                    <select name="status" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition">
                                        <option value="">All Status</option>
                                        <?php foreach ($statuses as $s): ?>
                                            <option value="<?php echo $s['status_name']; ?>" <?php echo $statusFilter == $s['status_name'] ? 'selected' : ''; ?>><?php echo $s['status_name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 mb-1">Category</label>
                                    <select name="category" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition">
                                        <option value="0">All Categories</option>
                                        <?php foreach ($categories as $c): ?>
                                            <option value="<?php echo $c['category_id']; ?>" <?php echo $categoryFilter == $c['category_id'] ? 'selected' : ''; ?>><?php echo $c['category_name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 mb-1">Purok</label>
                                    <select name="purok" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition">
                                        <option value="0">All Puroks</option>
                                        <?php foreach ($puroks as $p): ?>
                                            <option value="<?php echo $p['purok_id']; ?>" <?php echo $purokFilter == $p['purok_id'] ? 'selected' : ''; ?>><?php echo $p['purok_name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Second Row: Date Range, Quantity, Condition, and Buttons -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 mb-1">Date From</label>
                                    <input type="date" name="date_from" value="<?php echo htmlspecialchars($dateFrom); ?>" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 mb-1">Date To</label>
                                    <input type="date" name="date_to" value="<?php echo htmlspecialchars($dateTo); ?>" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 mb-1">Est. Quantity</label>
                                    <select name="quantity" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition">
                                        <option value="">All Quantities</option>
                                        <?php
                                        $db = new Database();
                                        $db->query("SELECT * FROM estimated_quantities WHERE is_active = 1 ORDER BY sort_order");
                                        $quantities = $db->resultSet();
                                        foreach ($quantities as $qty):
                                        ?>
                                            <option value="<?php echo $qty['quantity_id']; ?>" <?php echo (isset($_GET['quantity']) && $_GET['quantity'] == $qty['quantity_id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($qty['quantity_name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-slate-500 mb-1">Waste Condition</label>
                                    <select name="condition" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition">
                                        <option value="">All Conditions</option>
                                        <?php
                                        $db->query("SELECT * FROM waste_conditions WHERE is_active = 1 ORDER BY condition_name");
                                        $conditions = $db->resultSet();
                                        foreach ($conditions as $cond):
                                        ?>
                                            <option value="<?php echo $cond['condition_id']; ?>" <?php echo (isset($_GET['condition']) && $_GET['condition'] == $cond['condition_id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($cond['condition_name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!-- Apply/Clear buttons – span 2 columns on large screens -->
                                <div class="flex items-end gap-2 lg:col-span-2">
                                    <button type="submit" class="flex-1 rounded-xl bg-[#10B981] hover:bg-emerald-600 text-white font-semibold px-4 py-2 text-sm transition">Apply Filters</button>
                                    <a href="/brgy-waste-app-v3/public/index.php?url=supervisor/reports" class="flex-1 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold px-4 py-2 text-sm text-center transition">Clear All</a>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Reports Table -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200 text-sm">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left font-semibold uppercase tracking-[0.18em] text-slate-500 text-[10px]">
                                            <a href="?<?php echo http_build_query(array_merge($_GET, ['sort' => 'id', 'order' => ($_GET['sort'] ?? '') == 'id' && ($_GET['order'] ?? 'DESC') == 'DESC' ? 'ASC' : 'DESC'])); ?>" class="flex items-center gap-1 hover:text-slate-700">
                                                Report ID
                                                <?php if (($_GET['sort'] ?? '') == 'id'): ?>
                                                    <span class="text-emerald-600"><?php echo ($_GET['order'] ?? 'DESC') == 'DESC' ? '↓' : '↑'; ?></span>
                                                <?php endif; ?>
                                            </a>
                                        </th>
                                        <th class="px-4 py-3 text-left font-semibold uppercase tracking-[0.18em] text-slate-500 text-[10px]">
                                            <a href="?<?php echo http_build_query(array_merge($_GET, ['sort' => 'date', 'order' => ($_GET['sort'] ?? '') == 'date' && ($_GET['order'] ?? 'DESC') == 'DESC' ? 'ASC' : 'DESC'])); ?>" class="flex items-center gap-1 hover:text-slate-700">
                                                Date
                                                <?php if (($_GET['sort'] ?? '') == 'date'): ?>
                                                    <span class="text-emerald-600"><?php echo ($_GET['order'] ?? 'DESC') == 'DESC' ? '↓' : '↑'; ?></span>
                                                <?php endif; ?>
                                            </a>
                                        </th>
                                        <th class="px-4 py-3 text-left font-semibold uppercase tracking-[0.18em] text-slate-500 text-[10px]">Resident</th>
                                        <th class="px-4 py-3 text-left font-semibold uppercase tracking-[0.18em] text-slate-500 text-[10px]">
                                            <a href="?<?php echo http_build_query(array_merge($_GET, ['sort' => 'category', 'order' => ($_GET['sort'] ?? '') == 'category' && ($_GET['order'] ?? 'DESC') == 'DESC' ? 'ASC' : 'DESC'])); ?>" class="flex items-center gap-1 hover:text-slate-700">
                                                Category
                                                <?php if (($_GET['sort'] ?? '') == 'category'): ?>
                                                    <span class="text-emerald-600"><?php echo ($_GET['order'] ?? 'DESC') == 'DESC' ? '↓' : '↑'; ?></span>
                                                <?php endif; ?>
                                            </a>
                                        </th>
                                        <th class="px-4 py-3 text-left font-semibold uppercase tracking-[0.18em] text-slate-500 text-[10px]">Qty</th>
                                        <th class="px-4 py-3 text-left font-semibold uppercase tracking-[0.18em] text-slate-500 text-[10px]">Purok</th>
                                        <th class="px-4 py-3 text-left font-semibold uppercase tracking-[0.18em] text-slate-500 text-[10px]">
                                            <a href="?<?php echo http_build_query(array_merge($_GET, ['sort' => 'status', 'order' => ($_GET['sort'] ?? '') == 'status' && ($_GET['order'] ?? 'DESC') == 'DESC' ? 'ASC' : 'DESC'])); ?>" class="flex items-center gap-1 hover:text-slate-700">
                                                Status
                                                <?php if (($_GET['sort'] ?? '') == 'status'): ?>
                                                    <span class="text-emerald-600"><?php echo ($_GET['order'] ?? 'DESC') == 'DESC' ? '↓' : '↑'; ?></span>
                                                <?php endif; ?>
                                            </a>
                                        </th>
                                        <th class="px-4 py-3 text-left font-semibold uppercase tracking-[0.18em] text-slate-500 text-[10px]">
                                            <a href="?<?php echo http_build_query(array_merge($_GET, ['sort' => 'support', 'order' => ($_GET['sort'] ?? '') == 'support' && ($_GET['order'] ?? 'DESC') == 'DESC' ? 'ASC' : 'DESC'])); ?>" class="flex items-center gap-1 hover:text-slate-700">
                                                Supports
                                                <?php if (($_GET['sort'] ?? '') == 'support'): ?>
                                                    <span class="text-emerald-600"><?php echo ($_GET['order'] ?? 'DESC') == 'DESC' ? '↓' : '↑'; ?></span>
                                                <?php endif; ?>
                                            </a>
                                        </th>
                                        <th class="px-4 py-3 text-left font-semibold uppercase tracking-[0.18em] text-slate-500 text-[10px]">Action</th>
                                    </tr>
                                </thead>
                                
                                <tbody class="divide-y divide-slate-100 bg-white">
                                    <?php if (!empty($reports)): ?>
                                        <?php foreach ($reports as $report):
                                            $badge = getStatusBadge($report['status']);
                                            $reportId = 'WR-' . str_pad($report['id'], 7, '0', STR_PAD_LEFT);
                                        ?>
                                        <tr class="hover:bg-slate-50 transition">
                                            <td class="px-4 py-3 font-mono text-slate-900 text-xs"><?php echo htmlspecialchars($reportId); ?></td>
                                            <td class="px-4 py-3 text-slate-500 text-xs"><?php echo date('M d, Y', strtotime($report['submission_date'])); ?></td>
                                            <td class="px-4 py-3 text-slate-700 font-medium text-xs"><?php echo htmlspecialchars($report['reporter_name'] ?? 'N/A'); ?></td>
                                            <td class="px-4 py-3 text-slate-600 text-xs"><?php echo htmlspecialchars($report['waste_category'] ?? 'N/A'); ?></td>
                                            <td class="px-4 py-3 text-slate-600 text-xs"><?php echo htmlspecialchars($report['estimated_quantity'] ?? 'N/A'); ?></td>
                                            <td class="px-4 py-3 text-slate-600 text-xs"><?php echo htmlspecialchars($report['purok'] ?? 'N/A'); ?></td>
                                            <td class="px-4 py-3">
                                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-[10px] font-semibold" style="background: <?php echo $badge['bg']; ?>; color: <?php echo $badge['text']; ?>;"><?php echo $badge['label']; ?></span>
                                            </td>
                                            <td class="px-4 py-3">
                                                <a href="/brgy-waste-app-v3/public/index.php?url=supervisor/view_report/<?php echo $report['id']; ?>" class="text-emerald-600 hover:text-emerald-700 font-semibold text-xs inline-flex items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="px-4 py-8 text-center text-slate-500 text-sm">No reports found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>