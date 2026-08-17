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
            <div class="max-w-7xl mx-auto px-4 py-8 flex-grow">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">Flagged Reports</h1>
                    <p class="mt-1 text-sm text-gray-600">Review reports that have been flagged for quality or validity issues</p>
                </div>

                <!-- Stats Card -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <div class="flex items-center">
                        <div class="flex-1">
                            <p class="text-sm text-gray-600 font-medium">Total Flagged Reports</p>
                            <p class="text-4xl font-bold text-red-600 mt-1"><?php echo count($data['flagged_reports']); ?></p>
                        </div>
                        <div class="flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" class="text-red-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3.05h16.94a2 2 0 0 0 1.71-3.05L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                                <line x1="12" y1="9" x2="12" y2="13"></line>
                                <line x1="12" y1="17" x2="12.01" y2="17"></line>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Reports Table -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <?php if (empty($data['flagged_reports'])): ?>
                        <div class="p-12 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" class="text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                            <p class="text-gray-500 text-lg font-medium">No flagged reports yet</p>
                            <p class="text-gray-400 text-sm mt-1">All reports are in good standing</p>
                        </div>
                    <?php else: ?>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Report Details</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reporter</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Flag Reason</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Flagged By</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Flagged</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($data['flagged_reports'] as $flag): ?>
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">Report ID: <?php echo $flag['report_id']; ?></p>
                                                <p class="text-xs text-gray-600 mt-1 line-clamp-2"><?php echo htmlspecialchars($flag['description']); ?></p>
                                                <p class="text-xs text-gray-500 mt-1">
                                                    Submitted: <?php echo date('M d, Y', strtotime($flag['submission_date'])); ?>
                                                </p>
                                                <span class="inline-block mt-2 px-2 py-1 text-xs font-semibold rounded-full
                                                    <?php 
                                                        if ($flag['report_status'] == 'pending') {
                                                            echo 'bg-yellow-100 text-yellow-800';
                                                        } elseif ($flag['report_status'] == 'verified') {
                                                            echo 'bg-blue-100 text-blue-800';
                                                        } elseif ($flag['report_status'] == 'resolved') {
                                                            echo 'bg-green-100 text-green-800';
                                                        } else {
                                                            echo 'bg-gray-100 text-gray-800';
                                                        }
                                                    ?>">
                                                    <?php echo strtoupper($flag['report_status']); ?>
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($flag['reporter_name']); ?></div>
                                            <div class="text-xs text-gray-500"><?php echo htmlspecialchars($flag['reporter_email']); ?></div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-700 bg-red-50 p-3 rounded border-l-4 border-red-400">
                                                <?php echo htmlspecialchars($flag['flag_reason']); ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($flag['flagged_by_name'] ?? 'System'); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-600"><?php echo date('M d, Y', strtotime($flag['flagged_at'])); ?></div>
                                            <div class="text-xs text-gray-500"><?php echo date('H:i', strtotime($flag['flagged_at'])); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <button onclick="viewDetails(<?php echo $flag['report_id']; ?>, '<?php echo htmlspecialchars(addslashes($flag['description'])); ?>')" class="text-blue-600 hover:text-blue-900 font-medium text-sm">
                                                View
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Detail Modal -->
<div id="detailModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all animate-slideUp">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-900">Report Details</h2>
            <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600 transition-colors p-1 hover:bg-gray-100 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="px-6 py-6">
            <p id="detailContent" class="text-gray-700 text-sm leading-relaxed">Loading...</p>
        </div>
        <div class="px-6 py-4 bg-gray-50 rounded-b-xl flex gap-3">
            <button onclick="closeDetailModal()" class="flex-1 px-4 py-2.5 bg-blue-600 text-white rounded-lg font-semibold text-sm hover:bg-blue-700 transition-colors">
                Close
            </button>
        </div>
    </div>
</div>

<style>
@keyframes slideUp {
    from { 
        opacity: 0;
        transform: translateY(20px);
    }
    to { 
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-slideUp {
    animation: slideUp 0.3s ease-out;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<script>
function viewDetails(reportId, description) {
    document.getElementById('detailContent').textContent = description;
    document.getElementById('detailModal').classList.remove('hidden');
}

function closeDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

// Close modal with Escape key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeDetailModal();
    }
});

// Close modal when clicking outside
document.getElementById('detailModal')?.addEventListener('click', (e) => {
    if (e.target.id === 'detailModal') closeDetailModal();
});
</script>
            </div>
        </main>
    </div>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
