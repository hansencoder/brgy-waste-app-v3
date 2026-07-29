<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php
$users = $data['users'] ?? [];
$tab = $data['tab'] ?? 'resident';
$search = $data['search'] ?? '';
$residentCount = $data['resident_count'] ?? 0;
$staffCount = $data['staff_count'] ?? 0;

function getStatusBadge($status) {
    $map = [
        'active'      => ['bg' => '#DCFCE7', 'text' => '#15803D', 'label' => 'Active'],
        'pending'     => ['bg' => '#FEF3C7', 'text' => '#92400E', 'label' => 'Pending'],
        'suspended'   => ['bg' => '#FEF3C7', 'text' => '#92400E', 'label' => 'Suspended'],
        'deactivated' => ['bg' => '#FEE2E2', 'text' => '#B91C1C', 'label' => 'Deactivated'],
    ];
    return $map[$status] ?? ['bg' => '#F3F4F6', 'text' => '#4B5563', 'label' => $status];
}
?>

<div class="min-h-screen bg-[#F8FAFC]">
    <div class="lg:flex lg:min-h-screen">
        <?php include __DIR__ . '/../layouts/admin_sidebar.php'; ?>

        <div class="flex-1 flex flex-col overflow-hidden">
            <?php include __DIR__ . '/../layouts/admin_topbar.php'; ?>

            <main class="flex-1 overflow-y-auto">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-8">

                    <!-- Page Header -->
                    <div class="mb-6">
                        <h1 class="text-2xl font-extrabold text-slate-900">User Management</h1>
                        <p class="text-sm text-slate-500">Manage resident and staff accounts</p>
                    </div>

                    <!-- Segmented Control (Tabs) -->
                    <div class="flex justify-center mb-6">
                        <div class="inline-flex rounded-full bg-slate-100 p-1 shadow-sm">
                            <a href="?tab=resident<?php echo $search ? '&search='.urlencode($search) : ''; ?>" 
                               class="rounded-full px-6 py-2 text-sm font-semibold transition <?php echo $tab === 'resident' ? 'bg-[#10B981] text-white shadow-sm' : 'text-slate-700 hover:bg-slate-200'; ?>">
                                Resident Accounts <span class="inline-flex items-center justify-center rounded-full bg-white/20 px-2 py-0.5 text-xs ml-1"><?php echo $residentCount; ?></span>
                            </a>
                            <a href="?tab=staff<?php echo $search ? '&search='.urlencode($search) : ''; ?>" 
                               class="rounded-full px-6 py-2 text-sm font-semibold transition <?php echo $tab === 'staff' ? 'bg-[#10B981] text-white shadow-sm' : 'text-slate-700 hover:bg-slate-200'; ?>">
                                Staff Accounts <span class="inline-flex items-center justify-center rounded-full bg-white/20 px-2 py-0.5 text-xs ml-1"><?php echo $staffCount; ?></span>
                            </a>
                        </div>
                    </div>

                    <!-- Search Bar -->
                    <form method="GET" action="" class="max-w-xl mx-auto mb-6">
                        <input type="hidden" name="tab" value="<?php echo $tab; ?>">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                            </div>
                            <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search residents..." class="w-full rounded-full border border-slate-200 bg-white py-2.5 pl-10 pr-4 text-sm text-slate-700 placeholder:text-slate-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 outline-none transition">
                        </div>
                    </form>

                    <!-- Users Table -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200 text-sm">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-6 py-4 text-left font-semibold uppercase tracking-[0.18em] text-slate-500 text-xs">Name</th>
                                        <th class="px-6 py-4 text-left font-semibold uppercase tracking-[0.18em] text-slate-500 text-xs">Email</th>
                                        <th class="px-6 py-4 text-left font-semibold uppercase tracking-[0.18em] text-slate-500 text-xs">Contact</th>
                                        <th class="px-6 py-4 text-left font-semibold uppercase tracking-[0.18em] text-slate-500 text-xs">Purok</th>
                                        <th class="px-6 py-4 text-left font-semibold uppercase tracking-[0.18em] text-slate-500 text-xs">Registered</th>
                                        <th class="px-6 py-4 text-left font-semibold uppercase tracking-[0.18em] text-slate-500 text-xs">Reports</th>
                                        <th class="px-6 py-4 text-left font-semibold uppercase tracking-[0.18em] text-slate-500 text-xs">Status</th>
                                        <th class="px-6 py-4 text-left font-semibold uppercase tracking-[0.18em] text-slate-500 text-xs">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
                                    <?php if (!empty($users)): ?>
                                        <?php foreach ($users as $user): 
                                            $badge = getStatusBadge($user['status']);
                                            $initials = $user['initials'] ?? '?';
                                            $reportCount = ($user['role_id'] == 3) ? ($user['report_count'] ?? 0) : '-';
                                        ?>
                                        <tr class="hover:bg-slate-50 transition">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-xs">
                                                        <?php echo htmlspecialchars($initials); ?>
                                                    </div>
                                                    <span class="font-semibold text-slate-900"><?php echo htmlspecialchars($user['name']); ?></span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-slate-600"><?php echo htmlspecialchars($user['email']); ?></td>
                                            <td class="px-6 py-4 text-slate-600"><?php echo htmlspecialchars($user['phone_number']); ?></td>
                                            <td class="px-6 py-4 text-slate-600"><?php echo htmlspecialchars($user['purok_name'] ?? 'N/A'); ?></td>
                                            <td class="px-6 py-4 text-slate-500"><?php echo date('M d, Y', strtotime($user['created_at'])); ?></td>
                                            <td class="px-6 py-4 text-center font-bold text-slate-800"><?php echo $reportCount; ?></td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold" style="background: <?php echo $badge['bg']; ?>; color: <?php echo $badge['text']; ?>;"><?php echo $badge['label']; ?></span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <a href="#" class="text-emerald-600 hover:text-emerald-700 font-semibold text-sm">View</a>
                                                    <?php if ($user['status'] === 'active'): ?>
                                                        <button onclick="openActionModal('suspend', <?php echo $user['id']; ?>, '<?php echo htmlspecialchars(addslashes($user['name'])); ?>')" class="text-amber-600 hover:text-amber-700 font-semibold text-sm">Suspend</button>
                                                    <?php elseif ($user['status'] === 'suspended'): ?>
                                                        <button onclick="openActionModal('reactivate', <?php echo $user['id']; ?>, '<?php echo htmlspecialchars(addslashes($user['name'])); ?>')" class="text-teal-600 hover:text-teal-700 font-semibold text-sm">Reactivate</button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="8" class="px-6 py-8 text-center text-slate-500">No accounts found.</td>
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

<!-- Action Modal (same as before) -->
<div id="actionModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h2 id="modalTitle" class="text-lg font-bold text-gray-900">Confirm Action</h2>
            <button onclick="closeActionModal()" class="text-gray-400 hover:text-gray-600 transition-colors p-1 hover:bg-gray-100 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="px-6 py-6">
            <p id="modalMessage" class="text-gray-600 text-sm leading-relaxed">Are you sure?</p>
        </div>
        <div class="px-6 py-4 bg-gray-50 rounded-b-xl flex gap-3">
            <button onclick="closeActionModal()" class="flex-1 px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg font-semibold text-sm hover:bg-gray-100 transition-colors">
                Cancel
            </button>
            <form id="actionForm" action="/brgy-waste-app-v3/public/admin/accounts" method="POST" class="flex-1">
                <input type="hidden" id="form_user_id" name="user_id">
                <input type="hidden" id="form_action" name="action">
                <input type="hidden" id="form_reason" name="reason" value="">
                <button type="submit" class="w-full px-4 py-2.5 bg-blue-600 text-white rounded-lg font-semibold text-sm hover:bg-blue-700 transition-colors">
                    Confirm
                </button>
            </form>
        </div>
    </div>
</div>

<script>
let currentAction = null;
let currentUserId = null;

function openActionModal(action, userId, userName) {
    currentAction = action;
    currentUserId = userId;

    const titles = {
        'suspend': 'Suspend Account',
        'reactivate': 'Reactivate Account',
        'deactivate': 'Deactivate Account',
        'remove': 'Remove Account'
    };

    const messages = {
        'suspend': `Are you sure you want to suspend <strong>${userName}</strong>?`,
        'reactivate': `Are you sure you want to reactivate <strong>${userName}</strong>?`,
        'deactivate': `Are you sure you want to deactivate <strong>${userName}</strong>?`,
        'remove': `Are you sure you want to permanently remove <strong>${userName}</strong>? This cannot be undone.`
    };

    document.getElementById('modalTitle').textContent = titles[action] || 'Confirm Action';
    document.getElementById('modalMessage').innerHTML = messages[action] || 'Are you sure?';
    document.getElementById('actionModal').classList.remove('hidden');
}

function closeActionModal() {
    document.getElementById('actionModal').classList.add('hidden');
    currentAction = null;
    currentUserId = null;
}

// When form is submitted, set the hidden fields
document.getElementById('actionForm')?.addEventListener('submit', function(e) {
    if (!currentUserId || !currentAction) {
        e.preventDefault();
        return;
    }
    document.getElementById('form_user_id').value = currentUserId;
    document.getElementById('form_action').value = currentAction;
    // You can add reason input if needed
});

// Close modal on overlay click
document.getElementById('actionModal')?.addEventListener('click', function(e) {
    if (e.target === this) closeActionModal();
});

// Close on Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeActionModal();
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>