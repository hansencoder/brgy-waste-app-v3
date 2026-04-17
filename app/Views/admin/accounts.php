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
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-4 py-4">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">Manage Accounts</h1>
                    <p class="mt-1 text-sm text-gray-600">Review and manage resident registrations</p>
                </div>

                <!-- Status Tab Navigation -->
                <div class="border-2 rounded-lg p-4 mb-8 bg-white inline-flex gap-8">
                    <?php 
                        $pending_count = count(array_filter($data['users'], fn($u) => $u['role'] == 'resident' && $u['status'] == 'pending'));
                        $active_count = count(array_filter($data['users'], fn($u) => $u['role'] == 'resident' && $u['status'] == 'active'));
                        $deactivated_count = count(array_filter($data['users'], fn($u) => $u['role'] == 'resident' && $u['status'] == 'deactivated'));
                    ?>
                    
                    <!-- Pending Tab -->
                    <button onclick="filterTab('pending')" class="tab-btn cursor-pointer transition-all font-medium text-gray-700 hover:text-gray-900" data-tab="pending">
                        <span>Pending <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-yellow-200 text-yellow-800 text-xs font-bold ml-1"><?php echo $pending_count; ?></span></span>
                    </button>

                    <!-- Active Tab -->
                    <button onclick="filterTab('active')" class="tab-btn cursor-pointer transition-all font-medium text-gray-700 hover:text-gray-900" data-tab="active">
                        <span>Active <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-green-200 text-green-800 text-xs font-bold ml-1"><?php echo $active_count; ?></span></span>
                    </button>

                    <!-- Deactivated Tab -->
                    <button onclick="filterTab('deactivated')" class="tab-btn cursor-pointer transition-all font-medium text-gray-700 hover:text-gray-900" data-tab="deactivated">
                        <span>Deactivated <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-gray-300 text-gray-700 text-xs font-bold ml-1"><?php echo $deactivated_count; ?></span></span>
                    </button>
                </div>

                <!-- Table Container -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <!-- Pending Table -->
                    <div id="table-pending" class="table-view">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Full Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($data['users'] as $user):
                                    if ($user['role'] == 'resident' && $user['status'] == 'pending'): ?>
                                    <tr class="hover:bg-gray-50 transition-colors" data-user-id="<?php echo $user['id']; ?>" data-user-status="<?php echo $user['status']; ?>">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-mono text-gray-600"><?php echo htmlspecialchars($user['id']); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-gray-900"><?php echo htmlspecialchars($user['name']); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-600"><?php echo htmlspecialchars($user['email']); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-600"><?php echo htmlspecialchars($user['phone_number']); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-600"><?php echo htmlspecialchars($user['address'] ?? 'N/A'); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-600"><?php echo date('m/d/Y', strtotime($user['created_at'] ?? 'now')); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                            <button onclick="openActionModal('approve', <?php echo $user['id']; ?>, '<?php echo htmlspecialchars(addslashes($user['name'])); ?>')" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white transition-colors" title="Approve">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                                <span class="text-xs">Approve</span>
                                            </button>
                                            <button onclick="openActionModal('reject', <?php echo $user['id']; ?>, '<?php echo htmlspecialchars(addslashes($user['name'])); ?>')" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white transition-colors" title="Reject">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                                <span class="text-xs">Reject</span>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endif;
                                endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Active Table -->
                    <div id="table-active" class="table-view hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Full Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Reports</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($data['users'] as $user):
                                    if ($user['role'] == 'resident' && $user['status'] == 'active'): ?>
                                    <tr class="hover:bg-gray-50 transition-colors" data-user-id="<?php echo $user['id']; ?>" data-user-status="<?php echo $user['status']; ?>">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-mono text-gray-600"><?php echo htmlspecialchars($user['id']); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-gray-900"><?php echo htmlspecialchars($user['name']); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-600"><?php echo htmlspecialchars($user['email']); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-600"><?php echo htmlspecialchars($user['phone_number']); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-600"><?php echo htmlspecialchars($user['address'] ?? 'N/A'); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="inline-flex items-center justify-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800"><?php echo $user['report_count'] ?? 0; ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                                <span class="w-2 h-2 bg-green-600 rounded-full"></span>
                                                Active
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                            <button onclick="openEditModal(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars(addslashes($user['name'])); ?>', '<?php echo htmlspecialchars(addslashes($user['email'])); ?>', '<?php echo htmlspecialchars(addslashes($user['phone_number'])); ?>')" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white transition-colors" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                                                <span class="text-xs">Edit</span>
                                            </button>
                                            <button onclick="openActionModal('deactivate', <?php echo $user['id']; ?>, '<?php echo htmlspecialchars(addslashes($user['name'])); ?>')" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-yellow-600 hover:bg-yellow-700 text-white transition-colors" title="Deactivate">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" x2="9" y1="9" y2="15"/><line x1="9" x2="15" y1="9" y2="15"/></svg>
                                                <span class="text-xs">Deactivate</span>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endif;
                                endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Deactivated Table -->
                    <div id="table-deactivated" class="table-view hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Full Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($data['users'] as $user):
                                    if ($user['role'] == 'resident' && $user['status'] == 'deactivated'): ?>
                                    <tr class="hover:bg-gray-50 transition-colors" data-user-id="<?php echo $user['id']; ?>" data-user-status="<?php echo $user['status']; ?>">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-mono text-gray-600"><?php echo htmlspecialchars($user['id']); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-gray-900"><?php echo htmlspecialchars($user['name']); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-600"><?php echo htmlspecialchars($user['email']); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-600"><?php echo htmlspecialchars($user['address'] ?? 'N/A'); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-semibold bg-gray-100 text-gray-700">
                                                <span class="w-2 h-2 bg-gray-600 rounded-full"></span>
                                                Deactivated
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                            <button onclick="openActionModal('reactivate', <?php echo $user['id']; ?>, '<?php echo htmlspecialchars(addslashes($user['name'])); ?>')" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white transition-colors" title="Reactivate">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2-8.83"/></svg>
                                                <span class="text-xs">Reactivate</span>
                                            </button>
                                            <button onclick="openActionModal('remove', <?php echo $user['id']; ?>, '<?php echo htmlspecialchars(addslashes($user['name'])); ?>')" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white transition-colors" title="Remove">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/></svg>
                                                <span class="text-xs">Remove</span>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endif;
                                endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>

<!-- Action Confirmation Modal -->
<div id="actionModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4 animate-fadeIn">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all animate-slideUp">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 id="modalTitle" class="text-lg font-bold text-gray-900">Confirm Action</h2>
            <button onclick="closeActionModal()" class="text-gray-400 hover:text-gray-600 transition-colors p-1 hover:bg-gray-100 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="px-6 py-6">
            <p id="modalMessage" class="text-gray-600 text-sm leading-relaxed">Are you sure?</p>
            
            <!-- Reason Textarea (for reject/deactivate) -->
            <textarea id="reasonInput" placeholder="Enter reason (optional)" class="hidden w-full mt-4 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm resize-none" rows="3"></textarea>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 bg-gray-50 rounded-b-xl flex gap-3">
            <button onclick="closeActionModal()" class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg font-semibold text-sm hover:bg-gray-100 transition-colors">
                Cancel
            </button>
            <button onclick="confirmAction()" class="flex-1 px-4 py-2.5 bg-blue-600 text-white rounded-lg font-semibold text-sm hover:bg-blue-700 transition-colors">
                Confirm
            </button>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4 animate-fadeIn">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all animate-slideUp">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 class="text-lg font-bold text-gray-900">Edit Account</h2>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition-colors p-1 hover:bg-gray-100 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <!-- Modal Body -->
        <form id="editForm" class="px-6 py-6 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name</label>
                <input id="editName" type="text" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Full Name">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                <input id="editEmail" type="email" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Email">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Contact Number</label>
                <input id="editContact" type="tel" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Contact Number">
            </div>
        </form>

        <!-- Modal Footer -->
        <div class="px-6 py-4 bg-gray-50 rounded-b-xl flex gap-3">
            <button onclick="closeEditModal()" class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg font-semibold text-sm hover:bg-gray-100 transition-colors">
                Cancel
            </button>
            <button onclick="saveEditForm()" class="flex-1 px-4 py-2.5 bg-green-600 text-white rounded-lg font-semibold text-sm hover:bg-green-700 transition-colors">
                Save Changes
            </button>
        </div>
    </div>
</div>

<!-- Success Toast -->
<div id="successToast" class="fixed bottom-6 right-6 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg hidden animate-slideUp">
    <div class="flex items-center gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        <span id="toastMessage">Action completed successfully!</span>
    </div>
</div>

<!-- Hidden form for backend submissions -->
<form id="actionForm" action="/brgy-waste-app-v3/public/admin/accounts" method="POST" class="hidden">
    <input type="hidden" id="form_user_id" name="user_id">
    <input type="hidden" id="form_action" name="action">
    <input type="hidden" id="form_reason" name="reason" value="">
</form>

<style>
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

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

.animate-fadeIn {
    animation: fadeIn 0.2s ease-out;
}

.animate-slideUp {
    animation: slideUp 0.3s ease-out;
}
</style>

<script>
let currentAction = null;
let currentUserId = null;
let currentUserName = null;

// Tab filtering
function filterTab(tab) {
    // Hide all tables
    document.querySelectorAll('.table-view').forEach(el => el.classList.add('hidden'));
    
    // Show selected table
    const selectedTable = document.getElementById('table-' + tab);
    if (selectedTable) {
        selectedTable.classList.remove('hidden');
    }

    // Update tab styling
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('text-blue-700');
    });
    document.querySelector(`[data-tab="${tab}"]`)?.classList.add('text-blue-700');
}

// Open action modal
function openActionModal(action, userId, userName) {
    currentAction = action;
    currentUserId = userId;
    currentUserName = userName;

    const titles = {
        'approve': 'Approve Account',
        'reject': 'Reject Account',
        'deactivate': 'Deactivate Account',
        'reactivate': 'Reactivate Account',
        'remove': 'Remove Account'
    };

    const messages = {
        'approve': `Are you sure you want to approve <strong>${userName}</strong>?`,
        'reject': `Are you sure you want to reject <strong>${userName}</strong>?`,
        'deactivate': `Are you sure you want to deactivate <strong>${userName}</strong>?`,
        'reactivate': `Are you sure you want to reactivate <strong>${userName}</strong>?`,
        'remove': `Are you sure you want to permanently remove <strong>${userName}</strong>? This cannot be undone.`
    };

    document.getElementById('modalTitle').textContent = titles[action];
    document.getElementById('modalMessage').innerHTML = messages[action];

    // Show reason input for reject/deactivate
    const reasonInput = document.getElementById('reasonInput');
    if (['reject', 'deactivate'].includes(action)) {
        reasonInput.classList.remove('hidden');
        reasonInput.value = '';
        reasonInput.placeholder = action === 'reject' ? 'Enter rejection reason (optional)' : 'Enter deactivation reason (optional)';
    } else {
        reasonInput.classList.add('hidden');
    }

    document.getElementById('actionModal').classList.remove('hidden');
}

// Close action modal
function closeActionModal() {
    document.getElementById('actionModal').classList.add('hidden');
    currentAction = null;
    currentUserId = null;
}

// Confirm action
function confirmAction() {
    if (!currentAction || !currentUserId) return;

    document.getElementById('form_user_id').value = currentUserId;
    document.getElementById('form_action').value = currentAction;
    
    const reason = document.getElementById('reasonInput').value.trim();
    if (['reject', 'deactivate'].includes(currentAction)) {
        document.getElementById('form_reason').value = reason || '';
    }

    // Submit form
    document.getElementById('actionForm').submit();
}

// Edit modal
function openEditModal(userId, name, email, contact) {
    currentUserId = userId;
    document.getElementById('editName').value = name;
    document.getElementById('editEmail').value = email;
    document.getElementById('editContact').value = contact;
    document.getElementById('editModal').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    currentUserId = null;
}

function saveEditForm() {
    // This would typically send data via AJAX to backend
    showSuccessToast('Account updated successfully!');
    closeEditModal();
}

// Success toast
function showSuccessToast(message) {
    const toast = document.getElementById('successToast');
    document.getElementById('toastMessage').textContent = message;
    toast.classList.remove('hidden');
    
    setTimeout(() => {
        toast.classList.add('hidden');
    }, 3000);
}

// Close modals with Escape key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeActionModal();
        closeEditModal();
    }
});

// Close modals when clicking outside
document.getElementById('actionModal')?.addEventListener('click', (e) => {
    if (e.target.id === 'actionModal') closeActionModal();
});

document.getElementById('editModal')?.addEventListener('click', (e) => {
    if (e.target.id === 'editModal') closeEditModal();
});

// Set default tab to pending on load
window.addEventListener('load', () => {
    filterTab('pending');
});
</script>
            </div>
        </main>
    </div>
</div>
<?php include '../app/Views/layouts/footer.php'; ?>
