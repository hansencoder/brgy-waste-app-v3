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
        <div>
            <a href="/brgy-waste-app-v3/public/admin/export?format=csv" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded font-semibold text-sm shadow">Export to CSV (Excel)</a>
            <a href="/brgy-waste-app-v3/public/admin/export?format=print" target="_blank" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded font-semibold text-sm shadow ml-2">Export to PDF</a>
        </div>
        <?php endif; ?>
    </div>
    
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Report Info</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reporter</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
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
                            <div class="text-xs text-gray-500">Lat: <?php echo $report['latitude']; ?></div>
                            <div class="text-xs text-gray-500">Lng: <?php echo $report['longitude']; ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <?php 
                                $statusClass = "bg-yellow-100 text-yellow-800";
                                if($report['status'] == 'verified') $statusClass = "bg-blue-100 text-blue-800";
                                if($report['status'] == 'resolved') $statusClass = "bg-green-100 text-green-800";
                            ?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $statusClass; ?>">
                                <?php echo strtoupper($report['status']); ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <form action="/brgy-waste-app-v3/public/admin/reports" method="POST" class="inline">
                                <input type="hidden" name="report_id" value="<?php echo $report['id']; ?>">
                                <?php if ($report['status'] == 'pending'): ?>
                                    <button type="button" onclick="promptAction(<?php echo $report['id']; ?>, 'verify')" class="text-blue-600 hover:text-blue-900 mr-2">Verify</button>
                                <?php elseif ($report['status'] == 'verified'): ?>
                                    <button type="button" onclick="promptAction(<?php echo $report['id']; ?>, 'resolve')" class="text-green-600 hover:text-green-900 mr-2">Resolve</button>
                                <?php endif; ?>
                                
                                <button type="button" onclick="promptAction(<?php echo $report['id']; ?>, 'delete')" class="text-red-600 hover:text-red-900">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<form id="reportForm" action="/brgy-waste-app-v3/public/admin/reports" method="POST" class="hidden">
    <input type="hidden" id="r_id" name="report_id">
    <input type="hidden" id="r_action" name="action">
    <input type="hidden" id="r_remark" name="remark">
</form>

<script>
function promptAction(id, action) {
    let required = action === 'delete';
    let msg = action === 'delete' ? "Reason for removing this report (Required):" : "Remarks (Optional):";
    let remark = prompt(msg);
    if (remark !== null) {
        if (required && remark.trim() === '') {
            alert("A reason is required to delete a report.");
            return;
        }
        if (action === 'delete') {
            if (!confirm("Are you sure you want to permanently delete this report?")) return;
        }
        document.getElementById('r_id').value = id;
        document.getElementById('r_action').value = action;
        document.getElementById('r_remark').value = remark;
        document.getElementById('reportForm').submit();
    }
}
</script>
            </div>
        </main>
    </div>
</div>
<?php include '../app/Views/layouts/footer.php'; ?>
