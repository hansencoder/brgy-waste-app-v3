<?php include '../app/Views/layouts/header.php'; ?>
<div class="flex h-screen bg-gray-50 overflow-hidden w-full">
    <!-- Sidebar -->
    <?php include '../app/Views/layouts/admin_sidebar.php'; ?>

    <!-- Main Content Wrapper -->
    <div class="flex flex-col flex-1 w-0 overflow-hidden">
        
        <!-- Top Nav -->
        <?php include '../app/Views/layouts/admin_topbar.php'; ?>
        
        <!-- Scrollable Content -->
        <main class="flex-1 relative overflow-y-auto focus:outline-none">
            <div class="max-w-7xl mx-auto px-4 py-8 flex-grow">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-foreground">Manage Waste Reports</h1>
        <?php if ($_SESSION['user_role'] == 'secretary'): ?>
        <?php endif; ?>
    </div>

    <!-- Search and Filter Bar -->
    <div class="mb-6 bg-[#eefff2] rounded-lg shadow p-4">
        <form action="/brgy-waste-app-v3/public/admin/reports" method="GET" class="flex gap-4 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-2">Search Reports</label>
                <input type="text" name="search" placeholder="Search by description, reporter name, or email..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
            </div>
            <div class="w-48">
                <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All Statuses</option>
                    <option value="pending" <?php echo ($_GET['status'] ?? '') == 'pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="verified" <?php echo ($_GET['status'] ?? '') == 'verified' ? 'selected' : ''; ?>>Verified</option>
                    <option value="resolved" <?php echo ($_GET['status'] ?? '') == 'resolved' ? 'selected' : ''; ?>>Resolved</option>
                    <option value="rejected" <?php echo ($_GET['status'] ?? '') == 'rejected' ? 'selected' : ''; ?>>Rejected</option>
                </select>
            </div>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded font-semibold text-sm shadow">Search</button>
        </form>
    </div>
    
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Report Info</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reporter</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <?php if ($_SESSION['user_role'] == 'secretary'): ?>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($data['reports'] as $report): ?>
                    <tr>
                        <td class="px-6 py-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 h-16 w-16">
                                    <a href="/brgy-waste-app-v3/public/uploads/<?php echo $report['photo_path']; ?>" target="_blank">
                                        <img class="h-16 w-16 rounded object-cover cursor-pointer hover:opacity-75" src="/brgy-waste-app-v3/public/uploads/<?php echo $report['photo_path']; ?>" alt="">
                                    </a>
                                </div>
                                <div class="ml-4 w-48">
                                    <div class="text-sm font-medium text-gray-900 border border-gray-100 p-1 rounded bg-gray-50 overflow-hidden text-ellipsis h-12">
                                        <?php echo htmlspecialchars($report['description']); ?>
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1"><?php echo date('M d, Y', strtotime($report['created_at'])); ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($report['name']); ?></div>
                            <div class="text-xs text-gray-500"><?php echo htmlspecialchars($report['email']); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($report['location_name'] ?? 'Unknown location'); ?></div>
                            <div class="text-xs text-gray-500 mt-1"><?php echo $report['latitude']; ?>, <?php echo $report['longitude']; ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <?php 
                                $statusClass = "bg-yellow-100 text-yellow-800";
                                if($report['status'] == 'verified') $statusClass = "bg-blue-100 text-blue-800";
                                if($report['status'] == 'resolved') $statusClass = "bg-green-100 text-green-800";
                                if($report['status'] == 'rejected') $statusClass = "bg-red-100 text-red-800";
                            ?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $statusClass; ?>">
                                <?php echo strtoupper($report['status']); ?>
                            </span>
                        </td>
                        <?php if ($_SESSION['user_role'] == 'secretary'): ?>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <form action="/brgy-waste-app-v3/public/admin/reports" method="POST" class="inline">
                                <input type="hidden" name="report_id" value="<?php echo $report['id']; ?>">
                                <?php if ($report['status'] == 'pending'): ?>
                                    <button type="button" onclick="promptAction(<?php echo $report['id']; ?>, 'verify')" class="text-blue-600 hover:text-blue-900 mr-2">Verify</button>
                                    <button type="button" onclick="promptAction(<?php echo $report['id']; ?>, 'reject')" class="text-red-600 hover:text-red-900">Reject</button>
                                <?php elseif ($report['status'] == 'verified'): ?>
                                    <button type="button" onclick="promptAction(<?php echo $report['id']; ?>, 'resolve')" class="text-green-600 hover:text-green-900 mr-2">Resolve</button>
                                    <button type="button" onclick="promptAction(<?php echo $report['id']; ?>, 'reject')" class="text-red-600 hover:text-red-900">Reject</button>
                                <?php elseif ($report['status'] == 'rejected'): ?>
                                    <button type="button" onclick="viewFlagReason(<?php echo $report['id']; ?>, '<?php echo htmlspecialchars(addslashes($report['flag_reason'] ?? '')); ?>')" class="text-indigo-600 hover:text-indigo-900">View Reason</button>
                                <?php endif; ?>
                            </form>
                        </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Flag Reason Modal -->
<div id="reasonModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Rejection Reason</h3>
        </div>
        <div class="p-6">
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <p id="reasonContent" class="text-red-700 text-sm leading-relaxed"></p>
            </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
            <button onclick="closeReasonModal()" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md font-medium text-sm">
                Close
            </button>
        </div>
    </div>
</div>
<div id="flagModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Flag Report as Invalid</h3>
            <p class="text-sm text-gray-600 mt-1">Please select a reason for flagging this report</p>
        </div>
        <form id="flagForm" action="/brgy-waste-app-v3/public/admin/reports" method="POST" class="p-6 space-y-4">
            <input type="hidden" id="flag_report_id" name="report_id">
            <input type="hidden" name="action" value="reject">
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Flag Reason</label>
                <select id="flagReason" name="remark" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500" required>
                    <option value="">-- Select a reason --</option>
                    <option value="Vague or Unclear report">Vague or Unclear report</option>
                    <option value="Duplicated report">Duplicated report</option>
                    <option value="Inappropriate or Misleading evidence">Inappropriate or Misleading evidence</option>
                    <option value="Not Waste Related">Not Waste Related</option>
                    <option value="Spam or Malicious Report">Spam or Malicious Report</option>
                    <option value="Suspicious User Activity">Suspicious User Activity</option>
                    <option value="Policy Violation">Policy Violation</option>
                    <option value="Other">Other (Please specify below)</option>
                </select>
            </div>
            
            <div id="otherReasonDiv" class="hidden">
                <label class="block text-sm font-medium text-gray-700 mb-2">Please specify</label>
                <textarea id="otherReason" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500" rows="3" placeholder="Enter your custom reason..."></textarea>
            </div>
            
            <div class="bg-red-50 border border-red-200 rounded p-3">
                <p class="text-sm text-red-800">⚠️ This report will be marked as rejected and will show with a red status badge in the reports list.</p>
            </div>
            
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="closeFlagModal()" class="flex-1 px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md shadow-sm text-sm font-medium">
                    Flag Report
                </button>
            </div>
        </form>
    </div>
</div>

<form id="reportForm" action="/brgy-waste-app-v3/public/admin/reports" method="POST" class="hidden">
    <input type="hidden" id="r_id" name="report_id">
    <input type="hidden" id="r_action" name="action">
    <input type="hidden" id="r_remark" name="remark">
</form>

<script>
function viewFlagReason(reportId, reason) {
    document.getElementById('reasonContent').textContent = reason || 'No reason provided';
    document.getElementById('reasonModal').classList.remove('hidden');
}

function closeReasonModal() {
    document.getElementById('reasonModal').classList.add('hidden');
}

// Close reason modal when escape key is pressed
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeReasonModal();
    }
});

// Close reason modal when clicking outside
document.getElementById('reasonModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeReasonModal();
    }
});

function promptAction(id, action) {
    if (action === 'reject') {
        // Open flag modal
        document.getElementById('flag_report_id').value = id;
        document.getElementById('flagReason').value = '';
        document.getElementById('otherReasonDiv').classList.add('hidden');
        document.getElementById('otherReason').value = '';
        document.getElementById('flagModal').classList.remove('hidden');
    } else {
        // For verify and resolve actions, use simple form submission
        document.getElementById('r_id').value = id;
        document.getElementById('r_action').value = action;
        document.getElementById('r_remark').value = '';
        document.getElementById('reportForm').submit();
    }
}

function closeFlagModal() {
    document.getElementById('flagModal').classList.add('hidden');
}

// Handle flag reason selection
document.getElementById('flagReason')?.addEventListener('change', function() {
    const otherDiv = document.getElementById('otherReasonDiv');
    const otherReason = document.getElementById('otherReason');
    if (this.value === 'Other') {
        otherDiv.classList.remove('hidden');
        otherReason.required = true;
    } else {
        otherDiv.classList.add('hidden');
        otherReason.required = false;
    }
});

// Close modal when escape key is pressed
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeFlagModal();
    }
});

// Close modal when clicking outside
document.getElementById('flagModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeFlagModal();
    }
});

// Handle form submission for flag modal
document.getElementById('flagForm')?.addEventListener('submit', function(e) {
    const reason = document.getElementById('flagReason').value;
    const otherReason = document.getElementById('otherReason').value;
    
    if (!reason) {
        e.preventDefault();
        alert('Please select a flag reason');
        return;
    }
    
    if (reason === 'Other' && !otherReason.trim()) {
        e.preventDefault();
        alert('Please specify your custom reason');
        return;
    }
    
    // If "Other" is selected, use the custom reason
    if (reason === 'Other') {
        document.querySelector('input[name="remark"]').value = otherReason;
    }
});
</script>
            </div>
        </main>
    </div>
</div>
<?php include '../app/Views/layouts/footer.php'; ?>
