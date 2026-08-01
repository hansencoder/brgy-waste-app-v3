<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="flex h-screen bg-gray-50 overflow-hidden w-full">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../layouts/admin_sidebar.php'; ?>

    <!-- Main Content Wrapper -->
    <div class="flex flex-col flex-1 w-0 overflow-hidden">
        
        <!-- Top Nav -->
        <?php include __DIR__ . '/../layouts/admin_topbar.php'; ?>
        
        <!-- Scrollable Content -->
        <main class="flex-1 relative overflow-y-auto focus:outline-none">
            <div class="max-w-7xl mx-auto px-4 py-8 flex flex-col h-full">
    <h1 class="text-3xl font-bold text-foreground mb-6">System Audit Logs</h1>
    
    <!-- Search Bar -->

    <div class="mb-6 flex flex-wrap items-center gap-3">
        <div class="relative flex-1 min-w-[200px]">
            <input type="text" id="searchInput" placeholder="Search by Date & Time, User, or Details..." class="w-full px-4 py-2.5 pl-10 pr-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
            <svg class="absolute left-3 top-3 w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
        </div>
        <select id="moduleFilter" class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            <option value="all">All Modules</option>
            <option value="User">User</option>
            <option value="Dashboard">Dashboard</option>
            <option value="Reports">Reports</option>
            <option value="Settings">Settings</option>
            <option value="GIS">GIS</option>
            <option value="Schedule">Schedule</option>
            <option value="Announcements">Announcements</option>
        </select>
        <a href="/brgy-waste-app-v3/public/admin/exportAuditLogs" class="px-4 py-2.5 bg-emerald-600 text-white rounded-lg font-semibold text-sm hover:bg-emerald-700 transition">Export CSV</a>
    </div>
    
    <div class="bg-white rounded-lg shadow overflow-hidden flex-grow flex flex-col max-h-[70vh]">
        <div class="overflow-y-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 sticky top-0 shadow-sm">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Affected Record</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Result</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 font-mono text-xs">
                    <?php if(!empty($data['logs'])): foreach ($data['logs'] as $log): ?>
                        <tr class="hover:bg-gray-50 log-row">
                            <td class="px-6 py-3 whitespace-nowrap text-gray-500">
                                <?php echo date('m/d/Y h:i A', strtotime($log['created_at'])); ?>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-gray-900 font-medium">
                                <?php echo htmlspecialchars($log['user_name'] ?? 'System / Anonymous'); ?>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-gray-700">
                                <?php echo htmlspecialchars($log['action']); ?>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-gray-600 max-w-xs truncate" title="<?php echo htmlspecialchars($log['affected_record'] ?? ''); ?>">
                                <?php echo htmlspecialchars($log['affected_record'] ?? 'N/A'); ?>
                            </td>
                            <td class="px-6 py-3 text-gray-500 max-w-sm truncate" title="<?php echo htmlspecialchars($log['details'] ?? ''); ?>">
                                <?php echo htmlspecialchars($log['details'] ?? 'N/A'); ?>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-center">
                                <?php if($log['result'] == 'success'): ?>
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded">Success</span>
                                <?php else: ?>
                                    <span class="px-2 py-1 bg-red-100 text-red-800 rounded">Failed</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="6" class="text-center py-4">No logs found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
        </main>
    </div>
</div>

<script>
// Search functionality
document.getElementById('searchInput').addEventListener('keyup', filterLogs);
document.getElementById('moduleFilter').addEventListener('change', filterLogs);

function filterLogs() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const moduleFilter = document.getElementById('moduleFilter').value.toLowerCase();
    const rows = document.querySelectorAll('tbody tr.log-row');

    rows.forEach(row => {
        const cells = row.querySelectorAll('td');
        if (cells.length < 6) return;
        
        const dateTime = cells[0].textContent.toLowerCase();
        const user = cells[1].textContent.toLowerCase();
        const details = cells[4].textContent.toLowerCase();
        const action = cells[2].textContent.toLowerCase();

        const matchesSearch = dateTime.includes(searchTerm) || user.includes(searchTerm) || details.includes(searchTerm) || action.includes(searchTerm);
        const matchesModule = moduleFilter === 'all' || action.includes(moduleFilter) || user.includes(moduleFilter);

        row.style.display = (matchesSearch && matchesModule) ? '' : 'none';
    });
}
</script>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
