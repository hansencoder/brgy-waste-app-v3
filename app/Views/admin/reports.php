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

// Helper for status badge styling
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

// Metric card definition
$metrics = [
    'Total'       => ['value' => $status_counts['Total'], 'color' => 'text-slate-900', 'border' => 'border-emerald-500'],
    'Pending'     => ['value' => $status_counts['Pending'], 'color' => 'text-amber-600', 'border' => 'border-amber-500'],
    'Verified'    => ['value' => $status_counts['Verified'], 'color' => 'text-emerald-600', 'border' => 'border-emerald-500'],
    'Rejected'    => ['value' => $status_counts['Rejected'], 'color' => 'text-red-600', 'border' => 'border-red-500'],
    'In Progress' => ['value' => $status_counts['In Progress'], 'color' => 'text-orange-600', 'border' => 'border-orange-500'],
    'Resolved'    => ['value' => $status_counts['Resolved'], 'color' => 'text-cyan-600', 'border' => 'border-cyan-500'],
];
?>

<div class="min-h-screen bg-[#F8FAFC]">
    <div class="lg:flex lg:min-h-screen">
        <?php include __DIR__ . '/../layouts/admin_sidebar.php'; ?>

        <div class="flex-1 flex flex-col overflow-hidden">
            <?php include __DIR__ . '/../layouts/admin_topbar.php'; ?>

            <main class="flex-1 overflow-y-auto">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">

                    <!-- Page Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                        <div>
                            <h1 class="text-2xl font-extrabold text-slate-900">Waste Report Management</h1>
                            <p class="text-sm text-slate-500"><?php echo $status_counts['Total']; ?> total reports</p>
                        </div>
                        <a href="/brgy-waste-app-v3/public/admin/export?format=csv" class="inline-flex items-center gap-2 rounded-2xl bg-[#10B981] px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-600 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                            Export
                        </a>
                    </div>

                    <!-- Metrics Cards -->
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
                        <?php foreach ($metrics as $label => $metric): ?>
                            <div class="bg-white rounded-2xl border-2 p-4 text-center shadow-sm transition hover:shadow-md <?php echo $metric['border']; ?>">
                                <p class="text-2xl font-black <?php echo $metric['color']; ?>"><?php echo $metric['value']; ?></p>
                                <p class="text-xs font-semibold uppercase tracking-[0.15em] text-slate-500 mt-1"><?php echo $label; ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Search & Filter Bar -->
                    <div class="flex flex-col sm:flex-row gap-3 items-center justify-between mb-6">
                        <div class="relative w-full sm:w-80 lg:w-96">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            </div>
                            <input type="text" name="search" id="searchInput" placeholder="Search by ID, resident, category..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" class="w-full rounded-2xl border border-slate-200 bg-white py-2.5 pl-10 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition">
                        </div>
                        <div class="w-full sm:w-auto">
                            <select name="status" id="statusFilter" class="w-full sm:w-48 rounded-2xl border border-slate-200 bg-white py-2.5 px-4 text-sm text-slate-700 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition">
                                <option value="">All Status</option>
                                <option value="Pending" <?php echo ($_GET['status'] ?? '') === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="Verified" <?php echo ($_GET['status'] ?? '') === 'Verified' ? 'selected' : ''; ?>>Verified</option>
                                <option value="In Progress" <?php echo ($_GET['status'] ?? '') === 'In Progress' ? 'selected' : ''; ?>>In Progress</option>
                                <option value="Resolved" <?php echo ($_GET['status'] ?? '') === 'Resolved' ? 'selected' : ''; ?>>Resolved</option>
                                <option value="Rejected" <?php echo ($_GET['status'] ?? '') === 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
                            </select>
                        </div>
                    </div>

                    <!-- Reports Table -->
                    <div class="bg-white rounded-[32px] border border-slate-200 shadow-sm overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200 text-sm">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-6 py-4 text-left font-semibold uppercase tracking-[0.18em] text-slate-500 text-xs">Report ID</th>
                                        <th class="px-6 py-4 text-left font-semibold uppercase tracking-[0.18em] text-slate-500 text-xs">Date</th>
                                        <th class="px-6 py-4 text-left font-semibold uppercase tracking-[0.18em] text-slate-500 text-xs">Resident</th>
                                        <th class="px-6 py-4 text-left font-semibold uppercase tracking-[0.18em] text-slate-500 text-xs">Category</th>
                                        <th class="px-6 py-4 text-left font-semibold uppercase tracking-[0.18em] text-slate-500 text-xs">Qty</th>
                                        <th class="px-6 py-4 text-left font-semibold uppercase tracking-[0.18em] text-slate-500 text-xs">Purok</th>
                                        <th class="px-6 py-4 text-left font-semibold uppercase tracking-[0.18em] text-slate-500 text-xs">Status</th>
                                        <th class="px-6 py-4 text-left font-semibold uppercase tracking-[0.18em] text-slate-500 text-xs">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
                                    <?php if (!empty($reports)): ?>
                                        <?php foreach ($reports as $report):
                                            $badge = getStatusBadge($report['status']);
                                            $reportId = 'WR-' . str_pad($report['id'], 7, '0', STR_PAD_LEFT);
                                        ?>
                                        <tr class="hover:bg-slate-50 transition">
                                            <td class="px-6 py-4 font-mono text-slate-900"><?php echo htmlspecialchars($reportId); ?></td>
                                            <td class="px-6 py-4 text-slate-500"><?php echo date('M d, Y', strtotime($report['submission_date'])); ?></td>
                                            <td class="px-6 py-4 text-slate-700"><?php echo htmlspecialchars($report['name'] ?? 'N/A'); ?></td>
                                            <td class="px-6 py-4 text-slate-600"><?php echo htmlspecialchars($report['waste_category'] ?? 'N/A'); ?></td>
                                            <td class="px-6 py-4 text-slate-600"><?php echo htmlspecialchars($report['estimated_quantity'] ?? 'N/A'); ?></td>
                                            <td class="px-6 py-4 text-slate-600"><?php echo htmlspecialchars($report['purok'] ?? 'N/A'); ?></td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold" style="background: <?php echo $badge['bg']; ?>; color: <?php echo $badge['text']; ?>;"><?php echo $badge['label']; ?></span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <a href="/brgy-waste-app-v3/public/admin/reports?view=<?php echo $report['id']; ?>" class="text-emerald-600 hover:text-emerald-700 inline-flex items-center gap-1.5 text-sm font-semibold">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                                    Review
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="px-6 py-8 text-center text-slate-500">No reports found.</td>
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

<script>
    // Auto-submit filter on change
    document.getElementById('statusFilter')?.addEventListener('change', function() {
        const url = new URL(window.location.href);
        url.searchParams.set('status', this.value);
        window.location.href = url.toString();
    });

    // Search with enter key or after typing delay
    const searchInput = document.getElementById('searchInput');
    let timeout = null;
    searchInput?.addEventListener('input', function() {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            const url = new URL(window.location.href);
            if (this.value) {
                url.searchParams.set('search', this.value);
            } else {
                url.searchParams.delete('search');
            }
            window.location.href = url.toString();
        }, 400);
    });
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>