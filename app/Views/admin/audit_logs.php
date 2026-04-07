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
            <div class="max-w-7xl mx-auto px-4 py-8 flex flex-col h-full">
    <h1 class="text-3xl font-bold text-foreground mb-6">System Audit Logs</h1>
    
    <div class="bg-white rounded-lg shadow overflow-hidden flex-grow flex flex-col max-h-[70vh]">
        <div class="overflow-y-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 sticky top-0 shadow-sm">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Result</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 font-mono text-xs">
                    <?php if(!empty($data['logs'])): foreach ($data['logs'] as $log): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 whitespace-nowrap text-gray-500">
                                <?php echo date('m/d/Y h:i A', strtotime($log['created_at'])); ?>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-gray-900 font-medium">
                                <?php echo htmlspecialchars($log['user_name'] ?? 'System / Anonymous'); ?>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-gray-700">
                                <?php echo htmlspecialchars($log['action_type']); ?>
                            </td>
                            <td class="px-6 py-3 text-gray-500 max-w-sm truncate" title="<?php echo htmlspecialchars($log['action_details']); ?>">
                                <?php echo htmlspecialchars($log['action_details']); ?>
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
                        <tr><td colspan="5" class="text-center py-4">No logs found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
        </main>
    </div>
</div>
<?php include '../app/Views/layouts/footer.php'; ?>
