<?php include __DIR__ . '/../layouts/header.php'; ?>

<style>
    body, * { font-family: 'Miranda Sans', sans-serif !important; font-optical-sizing: auto; }
    .mobile-sidebar-open #mobileSidebar { transform: translateX(0) !important; }
    #mobileSidebarOverlay { transition: opacity 0.25s ease, visibility 0.25s ease; opacity: 0; visibility: hidden; }
    .mobile-sidebar-open #mobileSidebarOverlay { opacity: 1; visibility: visible; }
    .modal-backdrop { transition: opacity 0.2s ease, visibility 0.2s ease; }
    .modal-box { transition: transform 0.25s cubic-bezier(0.34,1.3,0.64,1), opacity 0.2s ease; }
</style>

<?php
$systemRoles      = $data['systemRoles'] ?? ['administrator','supervisor','resident'];
$permissionGroups = $data['permissionGroups'] ?? [];
?>

<div class="min-h-screen bg-white text-slate-900 w-full flex font-sans antialiased">
    <div id="mobileSidebarOverlay" class="fixed inset-0 bg-slate-950/40 z-40 lg:hidden"></div>

    <div class="lg:flex lg:min-h-screen w-full">
        <?php include __DIR__ . '/../layouts/admin_sidebar.php'; ?>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <?php include __DIR__ . '/../layouts/admin_topbar.php'; ?>

            <main class="flex-1 overflow-y-auto">
                <div class="max-w-9xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

                    <!-- Page Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <a href="/brgy-waste-app-v3/public/settings" class="text-sm font-extrabold text-slate-500 hover:text-emerald-700 transition">Settings Hub</a>
                                <span class="text-sm text-slate-300">/</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-extrabold bg-blue-100 text-blue-900 border border-blue-300">Role Management</span>
                            </div>
                            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Role Management</h1>
                            <p class="text-base sm:text-lg text-slate-600 font-semibold mt-1">
                                Create custom roles with fine-grained permission access. System roles are protected and cannot be modified.
                            </p>
                        </div>
                        <button onclick="openAddRoleModal()" class="inline-flex items-center gap-2 px-5 py-3 bg-[#0B2E22] hover:bg-[#093024] text-white font-extrabold text-sm rounded-xl shadow-xs transition shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                            Add New Role
                        </button>
                    </div>

                    <!-- Alerts -->
                    <?php if (!empty($data['error'])): ?>
                        <div class="p-5 bg-red-50 border-2 border-red-200 text-red-950 rounded-2xl text-base font-extrabold flex items-center gap-3 shadow-xs">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <span><?php echo htmlspecialchars($data['error']); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($data['success'])): ?>
                        <div class="p-5 bg-emerald-50 border-2 border-emerald-200 text-emerald-950 rounded-2xl text-base font-extrabold flex items-center gap-3 shadow-xs">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                            <span><?php echo htmlspecialchars($data['success']); ?></span>
                        </div>
                    <?php endif; ?>

                    <!-- Content Layout -->
                    <div class="flex flex-col lg:flex-row gap-6">
                        <?php $activeTab = 'role_management'; include __DIR__ . '/../layouts/settings_sidebar.php'; ?>

                        <div class="flex-1 min-w-0 space-y-6">

                            <!-- Info Banner -->
                            <div class="p-4 bg-blue-50 border-2 border-blue-200 rounded-2xl flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                <div>
                                    <p class="text-sm font-extrabold text-blue-900">Permission Storage</p>
                                    <p class="text-xs text-blue-800 font-medium mt-0.5">Permissions are stored as a JSON configuration per role. System roles (Administrator, Supervisor, Resident) are locked and cannot be edited or deleted.</p>
                                </div>
                            </div>

                            <!-- Roles Table -->
                            <div class="bg-white rounded-2xl border-2 border-slate-200 shadow-xs overflow-hidden">
                                <div class="p-6 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                    <div>
                                        <h2 class="text-xl font-extrabold text-slate-900">All Roles</h2>
                                        <p class="text-sm text-slate-600 font-semibold mt-0.5">Manage user roles and their access permissions.</p>
                                    </div>
                                    <span class="px-4 py-1.5 rounded-full bg-slate-100 text-slate-900 font-mono font-extrabold text-sm border border-slate-200 self-start sm:self-auto shrink-0">
                                        <?php echo count($data['roles']); ?> Roles
                                    </span>
                                </div>

                                <div class="overflow-x-auto">
                                    <table class="w-full text-left border-collapse text-sm">
                                        <thead class="bg-slate-50 text-slate-500 font-extrabold uppercase tracking-wider text-xs border-b-2 border-slate-200">
                                            <tr>
                                                <th class="px-5 py-4">Role Name</th>
                                                <th class="px-5 py-4 hidden md:table-cell">Description</th>
                                                <th class="px-5 py-4 hidden lg:table-cell">Permissions</th>
                                                <th class="px-5 py-4 text-center">Type</th>
                                                <th class="px-5 py-4 text-right">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 bg-white">
                                            <?php foreach ($data['roles'] as $role):
                                                $isSystem = in_array(strtolower($role['role_name']), $systemRoles);
                                                $perms = [];
                                                if (!empty($role['permissions'])) {
                                                    $decoded = json_decode($role['permissions'], true);
                                                    if (is_array($decoded)) $perms = $decoded;
                                                }
                                            ?>
                                            <tr class="hover:bg-slate-50/70 transition">
                                                <td class="px-5 py-4">
                                                    <div class="flex items-center gap-2">
                                                        <?php if ($isSystem): ?>
                                                            <span class="w-5 h-5 text-amber-500 shrink-0">
                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                                            </span>
                                                        <?php endif; ?>
                                                        <span class="font-extrabold text-slate-900 text-sm capitalize"><?php echo htmlspecialchars($role['role_name']); ?></span>
                                                    </div>
                                                </td>
                                                <td class="px-5 py-4 hidden md:table-cell text-xs text-slate-500 font-medium max-w-xs">
                                                    <?php echo htmlspecialchars($role['description'] ?: ($isSystem ? 'Built-in system role' : 'No description')); ?>
                                                </td>
                                                <td class="px-5 py-4 hidden lg:table-cell">
                                                    <?php if ($isSystem): ?>
                                                        <span class="text-xs text-slate-400 font-semibold italic">Full access (system)</span>
                                                    <?php elseif (empty($perms)): ?>
                                                        <span class="text-xs text-slate-400 font-semibold italic">No permissions set</span>
                                                    <?php else: ?>
                                                        <div class="flex flex-wrap gap-1 max-w-xs">
                                                            <?php foreach (array_slice($perms, 0, 4) as $p): ?>
                                                                <span class="px-1.5 py-0.5 bg-blue-50 text-blue-800 border border-blue-100 text-[10px] font-bold rounded"><?php echo htmlspecialchars(str_replace('_',' ',$p)); ?></span>
                                                            <?php endforeach; ?>
                                                            <?php if (count($perms) > 4): ?>
                                                                <span class="text-[10px] text-slate-400 font-semibold">+<?php echo count($perms)-4; ?> more</span>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="px-5 py-4 text-center">
                                                    <?php if ($isSystem): ?>
                                                        <span class="px-2.5 py-1 rounded-full bg-amber-100 text-amber-900 text-xs font-extrabold border border-amber-200">System</span>
                                                    <?php else: ?>
                                                        <span class="px-2.5 py-1 rounded-full bg-blue-100 text-blue-900 text-xs font-extrabold border border-blue-200">Custom</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="px-5 py-4 text-right">
                                                    <?php if (!$isSystem): ?>
                                                        <div class="flex items-center justify-end gap-2">
                                                            <button type="button"
                                                                onclick='openEditRoleModal(<?php echo json_encode($role); ?>)'
                                                                class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-extrabold transition border border-slate-200">
                                                                Edit
                                                            </button>
                                                            <form method="POST" onsubmit="return confirm('Delete this role? Users assigned to it may lose access.');" class="inline-block">
                                                                <input type="hidden" name="delete_role" value="1">
                                                                <input type="hidden" name="role_id" value="<?php echo $role['role_id']; ?>">
                                                                <button type="submit" class="px-3 py-2 bg-red-50 hover:bg-red-100 text-red-700 rounded-xl text-xs font-extrabold transition border border-red-200">Delete</button>
                                                            </form>
                                                        </div>
                                                    <?php else: ?>
                                                        <span class="text-xs text-slate-400 font-semibold">Protected</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>

<!-- ═════════════════ ADD ROLE MODAL ═════════════════ -->
<div id="addRoleModal" class="modal-backdrop fixed inset-0 z-[999] flex items-center justify-center bg-slate-950/60 backdrop-blur-sm opacity-0 invisible p-4">
    <div class="modal-box w-full max-w-2xl bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden scale-95 opacity-0 max-h-[90vh] flex flex-col">
        <div class="p-6 border-b border-slate-200 flex items-center justify-between shrink-0">
            <h3 class="text-xl font-extrabold text-slate-900">Add New Role</h3>
            <button onclick="closeAddRoleModal()" class="p-2 hover:bg-slate-100 rounded-xl transition text-slate-400 hover:text-slate-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form method="POST" class="overflow-y-auto flex-1 p-6 space-y-5">
            <input type="hidden" name="add_role" value="1">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-extrabold text-slate-900 mb-1.5">Role Name <span class="text-red-500">*</span></label>
                    <input type="text" name="role_name" required placeholder="e.g. Field Officer"
                           class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm font-bold text-slate-900 outline-none focus:border-emerald-500 focus:bg-white transition">
                </div>
                <div>
                    <label class="block text-sm font-extrabold text-slate-900 mb-1.5">Description</label>
                    <input type="text" name="description" placeholder="Brief description of this role"
                           class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm font-bold text-slate-900 outline-none focus:border-emerald-500 focus:bg-white transition">
                </div>
            </div>

            <!-- Permission Groups -->
            <div>
                <p class="text-sm font-extrabold text-slate-900 mb-3 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    Permissions
                </p>
                <div class="space-y-4">
                    <?php foreach ($permissionGroups as $groupName => $perms): ?>
                    <div class="bg-slate-50 rounded-2xl border border-slate-200 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-xs font-extrabold uppercase tracking-wider text-slate-700"><?php echo htmlspecialchars($groupName); ?></p>
                            <button type="button" onclick="toggleGroup(this, '<?php echo md5($groupName); ?>')"
                                class="text-[10px] font-extrabold text-emerald-700 hover:text-emerald-900 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-lg transition">
                                Select All
                            </button>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2" data-group="<?php echo md5($groupName); ?>">
                            <?php foreach ($perms as $key => $label): ?>
                            <label class="flex items-center gap-2.5 p-2 rounded-xl hover:bg-white cursor-pointer transition group">
                                <input type="checkbox" name="permissions[]" value="<?php echo htmlspecialchars($key); ?>"
                                       class="w-4 h-4 accent-emerald-600 rounded">
                                <span class="text-sm font-semibold text-slate-700 group-hover:text-slate-900"><?php echo htmlspecialchars($label); ?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-200 flex justify-end gap-3 shrink-0">
                <button type="button" onclick="closeAddRoleModal()" class="px-5 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-extrabold rounded-xl transition">Cancel</button>
                <button type="submit" class="px-6 py-3 bg-[#0B2E22] hover:bg-[#093024] text-white text-sm font-extrabold rounded-xl shadow-xs transition">Create Role</button>
            </div>
        </form>
    </div>
</div>

<!-- ═════════════════ EDIT ROLE MODAL ═════════════════ -->
<div id="editRoleModal" class="modal-backdrop fixed inset-0 z-[999] flex items-center justify-center bg-slate-950/60 backdrop-blur-sm opacity-0 invisible p-4">
    <div class="modal-box w-full max-w-2xl bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden scale-95 opacity-0 max-h-[90vh] flex flex-col">
        <div class="p-6 border-b border-slate-200 flex items-center justify-between shrink-0">
            <h3 class="text-xl font-extrabold text-slate-900">Edit Role: <span id="editRoleName" class="text-emerald-700"></span></h3>
            <button onclick="closeEditRoleModal()" class="p-2 hover:bg-slate-100 rounded-xl transition text-slate-400 hover:text-slate-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form method="POST" class="overflow-y-auto flex-1 p-6 space-y-5">
            <input type="hidden" name="edit_role" value="1">
            <input type="hidden" name="role_id" id="editRoleId">
            <div>
                <label class="block text-sm font-extrabold text-slate-900 mb-1.5">Description</label>
                <input type="text" name="description" id="editRoleDescription" placeholder="Brief description"
                       class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm font-bold text-slate-900 outline-none focus:border-emerald-500 focus:bg-white transition">
            </div>

            <!-- Permission Groups (Edit) -->
            <div>
                <p class="text-sm font-extrabold text-slate-900 mb-3">Permissions</p>
                <div class="space-y-4">
                    <?php foreach ($permissionGroups as $groupName => $perms): ?>
                    <div class="bg-slate-50 rounded-2xl border border-slate-200 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-xs font-extrabold uppercase tracking-wider text-slate-700"><?php echo htmlspecialchars($groupName); ?></p>
                            <button type="button" onclick="toggleGroup(this, 'edit-<?php echo md5($groupName); ?>')"
                                class="text-[10px] font-extrabold text-emerald-700 hover:text-emerald-900 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-lg transition">
                                Select All
                            </button>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2" data-group="edit-<?php echo md5($groupName); ?>">
                            <?php foreach ($perms as $key => $label): ?>
                            <label class="flex items-center gap-2.5 p-2 rounded-xl hover:bg-white cursor-pointer transition group">
                                <input type="checkbox" name="permissions[]" value="<?php echo htmlspecialchars($key); ?>"
                                       class="edit-perm-checkbox w-4 h-4 accent-emerald-600 rounded" data-perm="<?php echo htmlspecialchars($key); ?>">
                                <span class="text-sm font-semibold text-slate-700 group-hover:text-slate-900"><?php echo htmlspecialchars($label); ?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-200 flex justify-end gap-3">
                <button type="button" onclick="closeEditRoleModal()" class="px-5 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-extrabold rounded-xl transition">Cancel</button>
                <button type="submit" class="px-6 py-3 bg-[#0B2E22] hover:bg-[#093024] text-white text-sm font-extrabold rounded-xl shadow-xs transition">Update Role</button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddRoleModal() {
    const m = document.getElementById('addRoleModal');
    m.classList.remove('opacity-0','invisible');
    setTimeout(() => m.querySelector('.modal-box').classList.remove('scale-95','opacity-0'), 10);
}
function closeAddRoleModal() {
    const m = document.getElementById('addRoleModal');
    m.querySelector('.modal-box').classList.add('scale-95','opacity-0');
    setTimeout(() => m.classList.add('opacity-0','invisible'), 200);
}
function openEditRoleModal(role) {
    document.getElementById('editRoleId').value          = role.role_id;
    document.getElementById('editRoleName').textContent  = role.role_name;
    document.getElementById('editRoleDescription').value = role.description || '';
    // Reset all checkboxes
    document.querySelectorAll('.edit-perm-checkbox').forEach(cb => cb.checked = false);
    // Tick existing permissions
    let perms = [];
    try { perms = JSON.parse(role.permissions || '[]'); } catch(e){}
    if (Array.isArray(perms)) {
        perms.forEach(p => {
            const cb = document.querySelector(`.edit-perm-checkbox[data-perm="${p}"]`);
            if (cb) cb.checked = true;
        });
    }
    const m = document.getElementById('editRoleModal');
    m.classList.remove('opacity-0','invisible');
    setTimeout(() => m.querySelector('.modal-box').classList.remove('scale-95','opacity-0'), 10);
}
function closeEditRoleModal() {
    const m = document.getElementById('editRoleModal');
    m.querySelector('.modal-box').classList.add('scale-95','opacity-0');
    setTimeout(() => m.classList.add('opacity-0','invisible'), 200);
}
function toggleGroup(btn, groupId) {
    const group = document.querySelector(`[data-group="${groupId}"]`);
    if (!group) return;
    const boxes = group.querySelectorAll('input[type="checkbox"]');
    const allChecked = [...boxes].every(cb => cb.checked);
    boxes.forEach(cb => cb.checked = !allChecked);
    btn.textContent = allChecked ? 'Select All' : 'Deselect All';
}
['addRoleModal','editRoleModal'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) { id === 'addRoleModal' ? closeAddRoleModal() : closeEditRoleModal(); }
    });
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
