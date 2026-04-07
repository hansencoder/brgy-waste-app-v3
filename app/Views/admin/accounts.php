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
            <div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-foreground mb-6">Manage Accounts</h1>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact Info</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($data['users'] as $user): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($user['full_name']); ?></div>
                            <div class="text-sm text-gray-500 truncate w-48" title="<?php echo htmlspecialchars($user['address']); ?>"><?php echo htmlspecialchars($user['address']); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900"><?php echo htmlspecialchars($user['email']); ?></div>
                            <div class="text-sm text-gray-500"><?php echo htmlspecialchars($user['contact_number']); ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                <?php echo ucfirst($user['role']); ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php 
                                $statusClass = "bg-yellow-100 text-yellow-800";
                                if($user['status'] == 'active') $statusClass = "bg-green-100 text-green-800";
                                if($user['status'] == 'deactivated') $statusClass = "bg-red-100 text-red-800";
                            ?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $statusClass; ?>">
                                <?php echo ucfirst($user['status']); ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <?php if ($user['role'] == 'resident'): ?>
                                <form action="/brgy-waste-app-v3/public/admin/accounts" method="POST" class="inline">
                                    <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                    <?php if ($user['status'] == 'pending'): ?>
                                        <button type="submit" name="action" value="approve" class="text-green-600 hover:text-green-900 mr-2">Approve</button>
                                        <button type="button" onclick="promptReason(<?php echo $user['id']; ?>, 'reject')" class="text-red-600 hover:text-red-900 mr-2">Reject</button>
                                    <?php elseif ($user['status'] == 'active'): ?>
                                        <button type="button" onclick="promptReason(<?php echo $user['id']; ?>, 'deactivate')" class="text-yellow-600 hover:text-yellow-900 mr-2">Deactivate</button>
                                    <?php endif; ?>
                                    <button type="button" onclick="confirmDelete(<?php echo $user['id']; ?>)" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            <?php else: ?>
                                <span class="text-gray-400">System Admin</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Hidden forms for JS prompt submissions -->
<form id="actionForm" action="/brgy-waste-app-v3/public/admin/accounts" method="POST" class="hidden">
    <input type="hidden" id="form_user_id" name="user_id">
    <input type="hidden" id="form_action" name="action">
    <input type="hidden" id="form_reason" name="reason" value="No reason provided">
</form>

<script>
function promptReason(userId, actionType) {
    let msg = actionType === 'reject' ? "Reason for rejection:" : "Reason for deactivation:";
    let reason = prompt(msg);
    if (reason !== null) {
        document.getElementById('form_user_id').value = userId;
        document.getElementById('form_action').value = actionType;
        if(reason.trim() !== '') document.getElementById('form_reason').value = reason;
        document.getElementById('actionForm').submit();
    }
}
function confirmDelete(userId) {
    if (confirm("Are you sure you want to permanently delete this account? This cannot be undone.")) {
        document.getElementById('form_user_id').value = userId;
        document.getElementById('form_action').value = 'delete';
        document.getElementById('actionForm').submit();
    }
}
</script>
            </div>
        </main>
    </div>
</div>
<?php include '../app/Views/layouts/footer.php'; ?>
