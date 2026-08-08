<?php include __DIR__ . '/../layouts/header.php'; ?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap');
  /* Apply Nunito Sans to everything EXCEPT material-icons */
    *:not(.material-icons) {
        font-family: 'Nunito Sans', 'Roboto', sans-serif !important;
    }
    /* Ensure Material Icons render correctly */
    .material-icons {
        font-family: 'Material Icons' !important;
        font-weight: normal;
        font-style: normal;
    }
</style>


<?php
// Retrieve user info from session if available
$fullName = $_SESSION['user_name'] ?? 'Juan Dela Cruz';
$firstName = isset($_SESSION['user_name']) ? explode(' ', trim($_SESSION['user_name']))[0] : 'Juan';

$categoryOptions = [];
if (!empty($data['reports'])) {
    foreach ($data['reports'] as $report) {
        if (!empty($report['waste_category'])) {
            $categoryOptions[] = $report['waste_category'];
        }
    }
    $categoryOptions = array_unique($categoryOptions);
}
?>

<div class="min-h-screen bg-[#F8FAFC] text-slate-800">
    <div class="lg:flex">
        <?php include __DIR__ . '/../layouts/resident_sidebar.php'; ?>

        <div class="flex-1">
            <header class="border-b border-slate-200 bg-white/90 px-4 py-4 backdrop-blur sm:px-6 lg:px-8 lg:py-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.35em] text-[#0D9488]">Resident Portal</p>
                        <h1 class="mt-1 text-2xl font-black tracking-tight text-slate-900 sm:text-3xl">My Reports</h1>
                        <p class="mt-1 text-sm text-slate-500">View and track every waste report you have submitted.</p>
                    </div>
                    <a href="/brgy-waste-app-v3/public/resident/submit" class="inline-flex items-center gap-2 rounded-full bg-[#07281E] px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-slate-900/10 transition hover:bg-slate-900">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                        New Report
                    </a>
                </div>
            </header>

            <main class="mx-auto max-w-7xl px-4 py-5 pb-24 sm:px-6 lg:px-8 lg:py-8">
                <?php if (isset($_SESSION['success'])): ?>
                <div id="flashSuccess" class="mb-5 flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-800">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <span><?php echo htmlspecialchars($_SESSION['success']); ?></span>
                    <button onclick="document.getElementById('flashSuccess').remove()" class="ml-auto text-emerald-500 hover:text-emerald-700">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>
                <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                <div id="flashError" class="mb-5 flex items-center gap-3 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm font-semibold text-red-800">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                    <span><?php echo htmlspecialchars($_SESSION['error']); ?></span>
                    <button onclick="document.getElementById('flashError').remove()" class="ml-auto text-red-500 hover:text-red-700">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    </button>
                </div>
                <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <section class="rounded-[28px] border border-slate-200 bg-white p-4 shadow-[0_18px_45px_-30px_rgba(15,23,42,0.18)] sm:p-6">
                    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                        <label class="flex flex-1 items-center gap-2 rounded-2xl border border-slate-200 bg-slate-50 px-3 py-3 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                            <input id="reportSearch" type="text" placeholder="Search by Report ID or category..." class="w-full border-0 bg-transparent text-sm text-slate-700 outline-none placeholder:text-slate-400" />
                        </label>

                        <div class="flex flex-col gap-3 sm:flex-row">
                            <label class="flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-3 py-3 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                                <select id="statusFilter" class="border-0 bg-transparent text-sm font-semibold text-slate-700 outline-none">
                                    <option value="all">All Statuses</option>
                                    <option value="pending">Pending Verification</option>
                                    <option value="verified">In Progress</option>
                                    <option value="resolved">Resolved</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </label>

                            <label class="flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-3 py-3 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>
                                <select id="categoryFilter" class="border-0 bg-transparent text-sm font-semibold text-slate-700 outline-none">
                                    <option value="all">All Categories</option>
                                    <?php foreach ($categoryOptions as $category): ?>
                                        <option value="<?php echo strtolower(str_replace(' ', '-', htmlspecialchars($category))); ?>"><?php echo htmlspecialchars($category); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                        </div>
                    </div>

                    <div class="mt-5 hidden overflow-hidden rounded-[24px] border border-slate-200 md:block">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-[0.25em] text-slate-500">Report ID</th>
                                    <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-[0.25em] text-slate-500">Category</th>
                                    <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-[0.25em] text-slate-500">Est. Quantity</th>
                                    <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-[0.25em] text-slate-500">Status</th>
                                    <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-[0.25em] text-slate-500">Date Submitted</th>
                                    <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-[0.25em] text-slate-500">Last Updated</th>
                                    <th class="px-4 py-3 text-left text-[11px] font-bold uppercase tracking-[0.25em] text-slate-500">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 bg-white">
                                <?php if (!empty($data['reports'])): ?>
                                    <?php foreach ($data['reports'] as $report): ?>
                                        <?php
                                            $status = strtolower($report['status'] ?? 'pending');
                                            $statusBadge = [
                                                'pending'  => ['label' => 'Pending Verification', 'classes' => 'bg-amber-50 text-amber-700 ring-1 ring-amber-100'],
                                                'verified' => ['label' => 'In Progress',           'classes' => 'bg-cyan-50 text-cyan-700 ring-1 ring-cyan-100'],
                                                'resolved' => ['label' => 'Resolved',               'classes' => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100'],
                                                'rejected' => ['label' => 'Rejected',               'classes' => 'bg-red-50 text-red-700 ring-1 ring-red-100'],
                                            ];
                                            $badge = $statusBadge[$status] ?? ['label' => ucfirst($status), 'classes' => 'bg-slate-100 text-slate-600 ring-1 ring-slate-200'];
                                            $quantity = $report['estimated_quantity'] ?? 'N/A';
                                            $categorySlug = strtolower(str_replace(' ', '-', $report['waste_category'] ?? ''));
                                            $reportId = 'WR-' . str_pad($report['id'], 4, '0', STR_PAD_LEFT);
                                            $createdAt = date('M j, Y', strtotime($report['submission_date']));
                                            $updatedAt = !empty($report['updated_at']) ? date('M j, Y', strtotime($report['updated_at'])) : $createdAt;
                                        ?>
                                        <tr class="report-row transition hover:bg-slate-50" data-status="<?php echo htmlspecialchars($status); ?>" data-category="<?php echo htmlspecialchars($categorySlug); ?>" data-id="<?php echo strtolower($reportId); ?>" data-description="<?php echo strtolower(htmlspecialchars($report['description'] ?? '')); ?>">
                                            <td class="px-4 py-3 font-semibold text-[#0D9488]"><a href="/brgy-waste-app-v3/public/resident/view_report/<?php echo $report['id']; ?>" class="hover:underline"><?php echo htmlspecialchars($reportId); ?></a></td>
                                            <td class="px-4 py-3 font-semibold text-slate-700"><?php echo htmlspecialchars($report['waste_category'] ?? 'Reported Issue'); ?></td>
                                            <td class="px-4 py-3 text-slate-600"><?php echo htmlspecialchars($quantity); ?></td>
                                            <td class="px-4 py-3"><span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold <?php echo $badge['classes']; ?>"><?php echo htmlspecialchars($badge['label']); ?></span></td>
                                            <td class="px-4 py-3 text-slate-500"><?php echo htmlspecialchars($createdAt); ?></td>
                                            <td class="px-4 py-3 text-slate-500"><?php echo htmlspecialchars($updatedAt); ?></td>
                                            <td class="px-4 py-3"><a href="/brgy-waste-app-v3/public/resident/view_report/<?php echo $report['id']; ?>" class="font-semibold text-[#0D9488] transition hover:text-emerald-600">View Details <span aria-hidden="true">→</span></a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="px-4 py-8 text-center text-slate-500">You have not submitted any reports yet.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 space-y-3 md:hidden">
                        <?php if (!empty($data['reports'])): ?>
                            <?php foreach ($data['reports'] as $report): ?>
                                <?php
                                    $status = strtolower($report['status'] ?? 'pending');
                                    $statusBadge = [
                                        'pending'  => ['label' => 'Pending Verification', 'classes' => 'bg-amber-50 text-amber-700'],
                                        'verified' => ['label' => 'In Progress',           'classes' => 'bg-cyan-50 text-cyan-700'],
                                        'resolved' => ['label' => 'Resolved',               'classes' => 'bg-emerald-50 text-emerald-700'],
                                        'rejected' => ['label' => 'Rejected',               'classes' => 'bg-red-50 text-red-700'],
                                    ];
                                    $badge = $statusBadge[$status] ?? ['label' => ucfirst($status), 'classes' => 'bg-slate-100 text-slate-600'];
                                    $quantity = $report['estimated_quantity'] ?? 'N/A';
                                    $categorySlug = strtolower(str_replace(' ', '-', $report['waste_category'] ?? ''));
                                    $reportId = 'WR-' . str_pad($report['id'], 4, '0', STR_PAD_LEFT);
                                    $createdAt = date('M j, Y', strtotime($report['submission_date']));
                                    $updatedAt = !empty($report['updated_at']) ? date('M j, Y', strtotime($report['updated_at'])) : $createdAt;
                                ?>
                                <a href="/brgy-waste-app-v3/public/resident/view_report/<?php echo $report['id']; ?>" class="report-card block rounded-[22px] border border-slate-200 bg-white p-4 shadow-sm" data-status="<?php echo htmlspecialchars($status); ?>" data-category="<?php echo htmlspecialchars($categorySlug); ?>" data-id="<?php echo strtolower($reportId); ?>" data-description="<?php echo strtolower(htmlspecialchars($report['description'] ?? '')); ?>">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="text-[11px] font-bold uppercase tracking-[0.3em] text-[#0D9488]"><?php echo htmlspecialchars($reportId); ?></p>
                                            <h3 class="mt-1 text-sm font-black text-slate-900"><?php echo htmlspecialchars($report['waste_category'] ?? 'Reported Issue'); ?></h3>
                                            <p class="mt-1 text-sm text-slate-500"><?php echo htmlspecialchars($report['description'] ?? ''); ?></p>
                                        </div>
                                        <span class="inline-flex rounded-full px-3 py-1 text-[11px] font-semibold <?php echo $badge['classes']; ?>"><?php echo htmlspecialchars($badge['label']); ?></span>
                                    </div>
                                    <div class="mt-3 flex items-center justify-between border-t border-slate-100 pt-3 text-sm text-slate-500">
                                        <span><?php echo htmlspecialchars($quantity); ?></span>
                                        <span><?php echo htmlspecialchars($updatedAt); ?></span>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="rounded-[22px] border border-slate-200 bg-slate-50 p-6 text-center text-sm text-slate-500">You have not submitted any reports yet.</div>
                        <?php endif; ?>
                    </div>

                    <div id="emptyState" class="mt-4 hidden rounded-[22px] border border-dashed border-slate-300 bg-slate-50 p-8 text-center text-sm text-slate-500">
                        No reports match the selected filters.
                    </div>
                </section>
            </main>
        </div>
    </div>

    <nav class="fixed bottom-0 left-0 right-0 z-40 border-t border-slate-200 bg-white/95 px-2 py-3 backdrop-blur md:hidden">
        <div class="mx-auto flex max-w-md items-center justify-between gap-1">
            <a href="/brgy-waste-app-v3/public/resident/dashboard" class="flex-1 rounded-2xl bg-white px-2 py-2 text-center text-[10px] font-semibold text-slate-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-1"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
                Home
            </a>
            <a href="/brgy-waste-app-v3/public/resident/my_report" class="flex-1 rounded-2xl bg-[#E6F4EA] px-2 py-2 text-center text-[10px] font-semibold text-slate-900">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-1"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
                Reports
            </a>
            <a href="/brgy-waste-app-v3/public/resident/submit" class="flex-1 rounded-full bg-[#10B981] px-3 py-2.5 text-center text-[10px] font-black text-white shadow-lg shadow-emerald-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-1"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                Report
            </a>
            <a href="/brgy-waste-app-v3/public/resident/announcements" class="flex-1 rounded-2xl bg-white px-2 py-2 text-center text-[10px] font-semibold text-slate-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-1"><path d="M4 4h16v12H7l-3 3z"/></svg>
                News
            </a>
            <a href="/brgy-waste-app-v3/public/resident/profile" class="flex-1 rounded-2xl bg-white px-2 py-2 text-center text-[10px] font-semibold text-slate-600">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-1"><path d="M20 21a8 8 0 1 0-16 0"/><circle cx="12" cy="8" r="4"/></svg>
                Profile
            </a>
        </div>
    </nav>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('reportSearch');
    const statusSelect = document.getElementById('statusFilter');
    const categorySelect = document.getElementById('categoryFilter');
    const rows = Array.from(document.querySelectorAll('.report-row'));
    const cards = Array.from(document.querySelectorAll('.report-card'));
    const emptyState = document.getElementById('emptyState');

    function applyFilters() {
        const query = (searchInput?.value || '').trim().toLowerCase();
        const status = (statusSelect?.value || 'all');
        const category = (categorySelect?.value || 'all');

        let visibleCount = 0;

        const items = [...rows, ...cards];
        items.forEach((item) => {
            const itemStatus = item.getAttribute('data-status') || '';
            const itemCategory = item.getAttribute('data-category') || '';
            const itemId = item.getAttribute('data-id') || '';
            const itemDescription = item.getAttribute('data-description') || '';
            const matchesStatus = status === 'all' || itemStatus === status;
            const matchesCategory = category === 'all' || itemCategory === category;
            const matchesQuery = !query || itemId.includes(query) || itemCategory.includes(query) || itemDescription.includes(query) || (item.textContent || '').toLowerCase().includes(query);
            const show = matchesStatus && matchesCategory && matchesQuery;

            item.classList.toggle('hidden', !show);
            if (show) {
                visibleCount += 1;
            }
        });

        if (emptyState) {
            emptyState.classList.toggle('hidden', visibleCount > 0);
        }
    }

    [searchInput, statusSelect, categorySelect].forEach((element) => {
        element?.addEventListener('input', applyFilters);
        element?.addEventListener('change', applyFilters);
    });

    applyFilters();
});
</script>
