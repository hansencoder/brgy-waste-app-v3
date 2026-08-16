<?php include __DIR__ . '/../layouts/header.php'; ?>

<?php
$users = $data['users'] ?? [];
$tab = $data['tab'] ?? 'resident';
$search = $data['search'] ?? '';
$residentCount = $data['resident_count'] ?? 0;
$staffCount = $data['staff_count'] ?? 0;
$suspendedCount = $data['suspended_count'] ?? 0;
$totalCount = $residentCount + $staffCount + $suspendedCount;

// Helper for status badge styling (Black & White Monochrome Theme)
function getAccountBadgeProps($status) {
    $map = [
        'active'      => ['bg' => 'bg-slate-100 text-slate-900 border-slate-300', 'dot' => 'bg-slate-900', 'label' => 'Active'],
        'pending'     => ['bg' => 'bg-slate-100 text-slate-700 border-slate-200', 'dot' => 'bg-slate-400', 'label' => 'Pending'],
        'suspended'   => ['bg' => 'bg-slate-200 text-slate-900 border-slate-400', 'dot' => 'bg-slate-900', 'label' => 'Suspended'],
        'deactivated' => ['bg' => 'bg-slate-50 text-slate-600 border-slate-200', 'dot' => 'bg-slate-400', 'label' => 'Deactivated'],
    ];
    return $map[$status] ?? ['bg' => 'bg-slate-50 text-slate-700 border-slate-200', 'dot' => 'bg-slate-400', 'label' => ucfirst($status)];
}
?>

<style>
    body, * { font-family: 'Miranda Sans', sans-serif !important; font-optical-sizing: auto; }
    .mobile-sidebar-open #mobileSidebar { transform: translateX(0) !important; }
    #mobileSidebarOverlay { transition: opacity 0.25s ease, visibility 0.25s ease; opacity: 0; visibility: hidden; }
    .mobile-sidebar-open #mobileSidebarOverlay { opacity: 1; visibility: visible; }
</style>

<div class="min-h-screen bg-white text-slate-800 w-full flex font-sans antialiased">
    
    <!-- Mobile Sidebar Overlay -->
    <div id="mobileSidebarOverlay" class="fixed inset-0 bg-slate-950/40 z-40 lg:hidden"></div>

    <!-- Layout Wrapper -->
    <div class="lg:flex lg:min-h-screen w-full">
        
        <!-- Sidebar Layout Component -->
        <?php include __DIR__ . '/../layouts/admin_sidebar.php'; ?>

        <!-- Main Workspace -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <!-- Top App Bar Component -->
            <?php include __DIR__ . '/../layouts/admin_topbar.php'; ?>

            <!-- Main Scrollable Canvas -->
            <main class="flex-1 overflow-y-auto">
                <div class="max-w-9xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

                    <!-- ============================================================ -->
                    <!-- 1. PAGE HEADER & QUICK ACTIONS                               -->
                    <!-- ============================================================ -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-250 shadow-xs">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-medium text-slate-400"> <?php echo number_format($totalCount); ?> Registered Accounts</span>
                            </div>
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                                User &amp; Staff Management
                            </h1>
                            <p class="text-sm text-slate-500 font-medium mt-0.5">
                                Manage registered resident profiles, administrative staff access, and account status controls.
                            </p>
                        </div>

                        <!-- Create Staff Button -->
                        <a href="/brgy-waste-app-v3/public/admin/createStaff" 
                           class="inline-flex items-center gap-2 rounded-xl bg-slate-900 hover:bg-black px-4 py-2.5 text-xs font-bold text-white shadow-sm transition active:scale-[0.98] self-start sm:self-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Create Staff Account
                        </a>
                    </div>

                    <!-- ============================================================ -->
                    <!-- 2. KPI METRICS SUMMARY CARDS ROW (BLACK & WHITE)             -->
                    <!-- ============================================================ -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        
                        <!-- Card 1: Resident Accounts -->
                        <a href="?tab=resident" class="bg-white rounded-2xl p-5 border shadow-xs hover:border-slate-400 transition flex items-center justify-between <?php echo $tab === 'resident' ? 'border-slate-900' : 'border-slate-200'; ?>">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Resident Accounts</p>
                                <p class="text-3xl font-extrabold text-slate-900 font-mono mt-1"><?php echo number_format($residentCount); ?></p>
                                <p class="text-[11px] text-slate-500 font-bold mt-1">Verified Community Users</p>
                            </div>
                            <span class="p-3 rounded-2xl bg-slate-100 text-slate-800 border border-slate-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                            </span>
                        </a>

                        <!-- Card 2: Staff & Personnel -->
                        <a href="?tab=staff" class="bg-white rounded-2xl p-5 border shadow-xs hover:border-slate-400 transition flex items-center justify-between <?php echo $tab === 'staff' ? 'border-slate-900' : 'border-slate-200'; ?>">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Staff &amp; Personnel</p>
                                <p class="text-3xl font-extrabold text-slate-900 font-mono mt-1"><?php echo number_format($staffCount); ?></p>
                                <p class="text-[11px] text-slate-500 font-bold mt-1">Admins, Inspectors &amp; Drivers</p>
                            </div>
                            <span class="p-3 rounded-2xl bg-slate-100 text-slate-800 border border-slate-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect width="20" height="14" x="2" y="7" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                            </span>
                        </a>

                        <!-- Card 3: Suspended Accounts -->
                        <a href="?tab=suspended" class="bg-white rounded-2xl p-5 border shadow-xs hover:border-slate-400 transition flex items-center justify-between <?php echo $tab === 'suspended' ? 'border-slate-900' : 'border-slate-200'; ?>">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Suspended / Restricted</p>
                                <p class="text-3xl font-extrabold text-slate-900 font-mono mt-1"><?php echo number_format($suspendedCount); ?></p>
                                <p class="text-[11px] text-slate-500 font-bold mt-1">Requires Admin Action</p>
                            </div>
                            <span class="p-3 rounded-2xl bg-slate-100 text-slate-800 border border-slate-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                            </span>
                        </a>

                    </div>

                    <!-- ============================================================ -->
                    <!-- 3. TAB NAVIGATION & LIVE SEARCH (MONOCHROME)                  -->
                    <!-- ============================================================ -->
                    <div class="bg-white rounded-2xl border border-slate-250 p-4 shadow-xs space-y-4">
                        
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-3">
                            <!-- Segmented Tabs -->
                            <div class="flex items-center gap-1.5 overflow-x-auto pb-1 text-xs font-bold scrollbar-none">
                                <a href="?tab=resident<?php echo $search ? '&search='.urlencode($search) : ''; ?>" 
                                   class="px-4 py-2 rounded-xl transition shrink-0 flex items-center gap-2 <?php echo $tab === 'resident' ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'; ?>">
                                    Resident Accounts
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-mono <?php echo $tab === 'resident' ? 'bg-slate-700 text-slate-100' : 'bg-slate-200 text-slate-700'; ?>"><?php echo $residentCount; ?></span>
                                </a>

                                <a href="?tab=staff<?php echo $search ? '&search='.urlencode($search) : ''; ?>" 
                                   class="px-4 py-2 rounded-xl transition shrink-0 flex items-center gap-2 <?php echo $tab === 'staff' ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'; ?>">
                                    Staff Accounts
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-mono <?php echo $tab === 'staff' ? 'bg-slate-700 text-slate-100' : 'bg-slate-200 text-slate-700'; ?>"><?php echo $staffCount; ?></span>
                                </a>

                                <a href="?tab=suspended<?php echo $search ? '&search='.urlencode($search) : ''; ?>" 
                                   class="px-4 py-2 rounded-xl transition shrink-0 flex items-center gap-2 <?php echo $tab === 'suspended' ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100'; ?>">
                                    Suspended Accounts
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-mono <?php echo $tab === 'suspended' ? 'bg-slate-700 text-slate-100' : 'bg-slate-200 text-slate-700'; ?>"><?php echo $suspendedCount; ?></span>
                                </a>
                            </div>
                        </div>

                        <!-- Real-time Live Search Input -->
                        <form method="GET" action="" class="flex flex-col sm:flex-row gap-3 items-center justify-between">
                            <input type="hidden" name="tab" value="<?php echo $tab; ?>">
                            <div class="relative w-full sm:w-96">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                                </div>
                                <input type="text" name="search" id="liveSearchInput" onkeyup="filterAccountTableLive()" placeholder="Search name, email, phone, purok..." value="<?php echo htmlspecialchars($search); ?>" 
                                       class="w-full rounded-xl border border-slate-200 bg-slate-50/70 py-2.5 pl-10 pr-4 text-xs font-semibold text-slate-800 placeholder:text-slate-400 focus:bg-white focus:border-slate-900 focus:ring-4 focus:ring-slate-900/10 outline-none transition">
                            </div>

                            <span id="accountCountBadge" class="text-xs font-bold font-mono text-slate-500 bg-slate-100 px-3 py-2 rounded-xl self-end sm:self-auto">
                                Showing <?php echo count($users); ?> accounts
                            </span>
                        </form>

                    </div>

                    <!-- ============================================================ -->
                    <!-- 4. ACCOUNTS DATA TABLE                                       -->
                    <!-- ============================================================ -->
                    <div class="bg-white rounded-2xl border border-slate-250 shadow-xs overflow-hidden">
                        <div class="overflow-x-auto">
                            <table id="accountsDataTable" class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50/80 border-b border-slate-200 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                        <th class="py-3.5 px-6">User / Full Name</th>
                                        <th class="py-3.5 px-6">Email Address</th>
                                        <th class="py-3.5 px-6">Contact Number</th>
                                        <th class="py-3.5 px-6">Assigned Purok</th>
                                        <th class="py-3.5 px-6">Registered Date</th>
                                        <?php if ($tab === 'resident'): ?>
                                            <th class="py-3.5 px-6 text-center">Reports Submitted</th>
                                        <?php endif; ?>
                                        <th class="py-3.5 px-6">Status</th>
                                        <th class="py-3.5 px-6 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-xs">
                                    <?php if (!empty($users)): ?>
                                        <?php foreach ($users as $user): 
                                            $badge = getAccountBadgeProps($user['status']);
                                            $initials = strtoupper(substr($user['name'] ?? 'U', 0, 1));
                                            $reportCount = ($user['role_id'] == 3) ? ($user['report_count'] ?? 0) : '-';
                                            $roleTitle = !empty($user['role_name']) ? ucfirst(htmlspecialchars($user['role_name'])) : '';
                                        ?>
                                        <tr class="account-row hover:bg-slate-50/70 transition">
                                            
                                            <!-- Name & Initials Avatar -->
                                            <td class="py-4 px-6">
                                                <div class="flex items-center gap-3">
                                                    <div class="h-9 w-9 rounded-full bg-slate-900 text-white flex items-center justify-center text-xs font-bold border border-slate-800 shrink-0">
                                                        <?php echo htmlspecialchars($initials); ?>
                                                    </div>
                                                    <div>
                                                        <span class="font-bold text-slate-900 block"><?php echo htmlspecialchars($user['name']); ?></span>
                                                        <?php if (!empty($roleTitle)): ?>
                                                            <span class="inline-block mt-0.5 text-[10px] font-semibold text-slate-400 uppercase tracking-wider"><?php echo $roleTitle; ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- Email -->
                                            <td class="py-4 px-6 text-slate-600 font-medium">
                                                <?php echo htmlspecialchars($user['email']); ?>
                                            </td>

                                            <!-- Phone -->
                                            <td class="py-4 px-6 text-slate-600 font-mono">
                                                <?php echo htmlspecialchars($user['phone_number']); ?>
                                            </td>

                                            <!-- Purok -->
                                            <td class="py-4 px-6 text-slate-700 font-medium">
                                                <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-800 font-semibold text-[11px] border border-slate-200">
                                                    <?php echo htmlspecialchars($user['purok_name'] ?? 'N/A'); ?>
                                                </span>
                                            </td>

                                            <!-- Registered Date -->
                                            <td class="py-4 px-6 text-slate-500 font-mono">
                                                <?php echo date('M d, Y', strtotime($user['created_at'])); ?>
                                            </td>

                                            <!-- Reports Submitted (Resident Tab) -->
                                            <?php if ($tab === 'resident'): ?>
                                                <td class="py-4 px-6 text-center">
                                                    <span class="font-bold font-mono text-slate-900 bg-slate-100 px-2.5 py-1 rounded-full border border-slate-200">
                                                        <?php echo $reportCount; ?>
                                                    </span>
                                                </td>
                                            <?php endif; ?>

                                            <!-- Status Badge -->
                                            <td class="py-4 px-6">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold border <?php echo $badge['bg']; ?>">
                                                    <span class="w-1.5 h-1.5 rounded-full <?php echo $badge['dot']; ?>"></span>
                                                    <?php echo $badge['label']; ?>
                                                </span>
                                            </td>

                                            <!-- Actions -->
                                            <td class="py-4 px-6 text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <?php if ($user['status'] === 'suspended'): ?>
                                                        <button onclick="openActionModal('reactivate', <?php echo $user['id']; ?>, '<?php echo htmlspecialchars(addslashes($user['name'])); ?>')" 
                                                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-900 font-bold text-xs border border-slate-300 transition">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                                            Reactivate
                                                        </button>
                                                    <?php else: ?>
                                                        <button onclick="openActionModal('suspend', <?php echo $user['id']; ?>, '<?php echo htmlspecialchars(addslashes($user['name'])); ?>')" 
                                                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-900 font-bold text-xs border border-slate-300 transition">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                                                            Suspend
                                                        </button>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="<?php echo $tab === 'resident' ? 8 : 7; ?>" class="py-12 text-center text-slate-400 font-medium">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto text-slate-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/></svg>
                                                No accounts found in this section.
                                            </td>
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

<!-- ============================================================ -->
<!-- ACTION CONFIRMATION MODAL                                    -->
<!-- ============================================================ -->
<div id="actionModal" class="fixed inset-0 bg-slate-950/60 backdrop-blur-xs hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full border border-slate-200 overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/70">
            <h2 id="modalTitle" class="text-base font-bold text-slate-900">Confirm Action</h2>
            <button onclick="closeActionModal()" class="text-slate-400 hover:text-slate-600 transition p-1 rounded-lg hover:bg-slate-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="p-6">
            <p id="modalMessage" class="text-slate-600 text-xs font-medium leading-relaxed">Are you sure?</p>
        </div>
        <div class="p-4 bg-slate-50 border-t border-slate-100 flex gap-3">
            <button onclick="closeActionModal()" class="flex-1 px-4 py-2.5 border border-slate-300 text-slate-700 rounded-xl font-bold text-xs hover:bg-slate-100 transition">
                Cancel
            </button>
            <form id="actionForm" action="/brgy-waste-app-v3/public/admin/accounts" method="POST" class="flex-1">
                <input type="hidden" id="form_user_id" name="user_id">
                <input type="hidden" id="form_action" name="action">
                <input type="hidden" id="form_reason" name="reason" value="">
                <button type="submit" id="modalSubmitBtn" class="w-full px-4 py-2.5 bg-slate-900 text-white rounded-xl font-bold text-xs hover:bg-black transition">
                    Confirm
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    let currentAction = null;
    let currentUserId = null;

    // Live filter search for accounts table
    function filterAccountTableLive() {
        const input = document.getElementById('liveSearchInput').value.toLowerCase().trim();
        const rows = document.querySelectorAll('.account-row');
        let visible = 0;

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            if (!input || text.includes(input)) {
                row.style.display = '';
                visible++;
            } else {
                row.style.display = 'none';
            }
        });

        const badge = document.getElementById('accountCountBadge');
        if (badge) badge.textContent = `Showing ${visible} accounts`;
    }

    function openActionModal(action, userId, userName) {
        currentAction = action;
        currentUserId = userId;

        const titles = {
            'suspend': 'Suspend Account',
            'reactivate': 'Reactivate Account',
            'deactivate': 'Deactivate Account'
        };

        const messages = {
            'suspend': `Are you sure you want to suspend <strong class="text-slate-900">${userName}</strong>? This user will temporarily lose login access.`,
            'reactivate': `Are you sure you want to reactivate <strong class="text-slate-900">${userName}</strong>? This will restore full portal access.`,
            'deactivate': `Are you sure you want to deactivate <strong class="text-slate-900">${userName}</strong>?`
        };

        document.getElementById('modalTitle').textContent = titles[action] || 'Confirm Action';
        document.getElementById('modalMessage').innerHTML = messages[action] || 'Are you sure?';
        
        const btn = document.getElementById('modalSubmitBtn');
        btn.className = 'w-full px-4 py-2.5 bg-slate-900 text-white rounded-xl font-bold text-xs hover:bg-black transition';

        document.getElementById('actionModal').classList.remove('hidden');
    }

    function closeActionModal() {
        document.getElementById('actionModal').classList.add('hidden');
        currentAction = null;
        currentUserId = null;
    }

    document.getElementById('actionForm')?.addEventListener('submit', function(e) {
        if (!currentUserId || !currentAction) {
            e.preventDefault();
            return;
        }
        document.getElementById('form_user_id').value = currentUserId;
        document.getElementById('form_action').value = currentAction;
    });

    document.getElementById('actionModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeActionModal();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeActionModal();
    });
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>