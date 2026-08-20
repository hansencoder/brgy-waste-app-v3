<?php include __DIR__ . '/../layouts/header.php'; ?>

<style>
    body, * { font-family: 'Miranda Sans', sans-serif !important; font-optical-sizing: auto; }
    .mobile-sidebar-open #mobileSidebar { transform: translateX(0) !important; }
    #mobileSidebarOverlay { transition: opacity 0.25s ease, visibility 0.25s ease; opacity: 0; visibility: hidden; }
    .mobile-sidebar-open #mobileSidebarOverlay { opacity: 1; visibility: visible; }
    .modal-backdrop { transition: opacity 0.2s ease, visibility 0.2s ease; }
    .modal-box { transition: transform 0.25s cubic-bezier(0.34,1.3,0.64,1), opacity 0.2s ease; }
</style>

<div class="min-h-screen bg-[#F8FAFC] text-slate-900 w-full flex font-sans antialiased">

    <!-- Mobile Sidebar Overlay -->
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
                                <a href="<?php echo app_url('settings'); ?>" class="text-sm font-extrabold text-slate-500 hover:text-emerald-700 transition">Settings Hub</a>
                                <span class="text-sm text-slate-300">/</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-extrabold bg-red-100 text-red-900 border border-red-300">
                                    Rules &amp; Penalties
                                </span>
                            </div>
                            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Barangay Rules &amp; Penalties</h1>
                            <p class="text-base sm:text-lg text-slate-600 font-semibold mt-1">
                                Manage prohibited actions and penalty tiers displayed on the public portal.
                            </p>
                        </div>
                        <button onclick="openAddModal()" class="inline-flex items-center gap-2 px-5 py-3 bg-[#0B2E22] hover:bg-[#093024] text-white font-extrabold text-sm rounded-xl shadow-xs transition shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                            Add New Rule
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
                    <div class="flex flex-col lg:flex-row gap-6 items-start">
                        <?php $activeTab = 'penalty_rules'; include __DIR__ . '/../layouts/settings_sidebar.php'; ?>

                        <div class="flex-1 min-w-0 space-y-6">

                            <!-- Rules Table Card -->
                            <div class="bg-white rounded-2xl border-2 border-slate-200 shadow-xs overflow-hidden">
                                <div class="p-6 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                    <div>
                                        <h2 class="text-xl font-extrabold text-slate-900">Prohibited Actions &amp; Penalty Tiers</h2>
                                        <p class="text-sm text-slate-600 font-semibold mt-0.5">
                                            These rules are displayed publicly on the Barangay portal under <em>Penalties &amp; Laws</em>.
                                        </p>
                                    </div>
                                    <span class="px-4 py-1.5 rounded-full bg-slate-100 text-slate-900 font-mono font-extrabold text-sm border border-slate-200 self-start sm:self-auto shrink-0">
                                        <?php echo count($data['rules']); ?> Rules
                                    </span>
                                </div>

                                <?php if (empty($data['rules'])): ?>
                                    <div class="p-12 text-center">
                                        <div class="w-16 h-16 rounded-2xl bg-red-50 border-2 border-red-100 flex items-center justify-center mx-auto mb-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-red-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                                        </div>
                                        <p class="text-base font-extrabold text-slate-700">No rules added yet</p>
                                        <p class="text-sm text-slate-500 mt-1">Click <strong>Add New Rule</strong> above to get started.</p>
                                    </div>
                                <?php else: ?>
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-left border-collapse text-sm">
                                            <thead class="bg-slate-50 text-slate-500 font-extrabold uppercase tracking-wider text-xs border-b-2 border-slate-200">
                                                <tr>
                                                    <th class="px-5 py-4 w-16">#</th>
                                                    <th class="px-5 py-4">Offense Title</th>
                                                    <th class="px-5 py-4 hidden md:table-cell">Legal Reference</th>
                                                    <th class="px-5 py-4 hidden lg:table-cell">Fine / Penalty</th>
                                                    <th class="px-5 py-4 text-center">Status</th>
                                                    <th class="px-5 py-4 text-right">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-100 bg-white">
                                                <?php foreach ($data['rules'] as $rule): ?>
                                                <tr class="hover:bg-slate-50/70 transition group">
                                                    <td class="px-5 py-4">
                                                        <span class="font-mono text-xs font-extrabold text-red-600 bg-red-50 border border-red-100 px-2 py-1 rounded-lg">
                                                            <?php echo str_pad((int)$rule['offense_no'], 2, '0', STR_PAD_LEFT); ?>
                                                        </span>
                                                    </td>
                                                    <td class="px-5 py-4">
                                                        <p class="font-extrabold text-slate-900 text-sm"><?php echo htmlspecialchars($rule['title']); ?></p>
                                                        <?php if (!empty($rule['description'])): ?>
                                                            <p class="text-xs text-slate-500 mt-0.5 font-medium leading-snug max-w-sm truncate"><?php echo htmlspecialchars($rule['description']); ?></p>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="px-5 py-4 hidden md:table-cell">
                                                        <span class="text-xs font-semibold text-slate-600 bg-slate-100 px-2 py-1 rounded-md">
                                                            <?php echo htmlspecialchars($rule['legal_ref'] ?: '—'); ?>
                                                        </span>
                                                    </td>
                                                    <td class="px-5 py-4 hidden lg:table-cell">
                                                        <span class="text-xs font-extrabold text-red-700"><?php echo htmlspecialchars($rule['fine_range'] ?: '—'); ?></span>
                                                        <?php if (!empty($rule['alt_penalty'])): ?>
                                                            <p class="text-xs text-slate-500 mt-0.5"><?php echo htmlspecialchars($rule['alt_penalty']); ?></p>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="px-5 py-4 text-center">
                                                        <?php if ($rule['is_active']): ?>
                                                            <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-900 text-xs font-extrabold border border-emerald-200">Active</span>
                                                        <?php else: ?>
                                                            <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-500 text-xs font-extrabold">Inactive</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="px-5 py-4 text-right">
                                                        <div class="flex items-center justify-end gap-2">
                                                            <button type="button"
                                                                onclick='openEditModal(<?php echo json_encode($rule); ?>)'
                                                                class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-extrabold transition border border-slate-200">
                                                                Edit
                                                            </button>
                                                            <form method="POST" onsubmit="return confirm('Delete this rule?');" class="inline-block">
                                                                <input type="hidden" name="delete_rule" value="1">
                                                                <input type="hidden" name="rule_id" value="<?php echo $rule['rule_id']; ?>">
                                                                <button type="submit" class="px-3 py-2 bg-red-50 hover:bg-red-100 text-red-700 rounded-xl text-xs font-extrabold transition border border-red-200">
                                                                    Delete
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                            </div>

                        </div><!-- /flex-1 -->
                    </div><!-- /content layout -->
                </div>
            </main>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════ ADD MODAL ═══════════════════════════════════════ -->
<div id="addModal" class="modal-backdrop fixed inset-0 z-[999] flex items-center justify-center bg-slate-950/60 backdrop-blur-sm opacity-0 invisible p-4">
    <div class="modal-box w-full max-w-xl bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden scale-95 opacity-0">
        <div class="p-6 border-b border-slate-200 flex items-center justify-between">
            <h3 class="text-xl font-extrabold text-slate-900">Add New Rule</h3>
            <button onclick="closeAddModal()" class="p-2 hover:bg-slate-100 rounded-xl transition text-slate-400 hover:text-slate-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form method="POST" class="p-6 space-y-4">
            <input type="hidden" name="add_rule" value="1">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-extrabold text-slate-900 mb-1.5">Offense # <span class="text-red-500">*</span></label>
                    <input type="number" name="offense_no" min="1" max="999" placeholder="01"
                           class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm font-bold font-mono text-slate-900 outline-none focus:border-emerald-500 focus:bg-white transition">
                </div>
                <div>
                    <label class="block text-sm font-extrabold text-slate-900 mb-1.5">Legal Reference</label>
                    <input type="text" name="legal_ref" placeholder="e.g. RA 9003 Sec. 48"
                           class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm font-bold text-slate-900 outline-none focus:border-emerald-500 focus:bg-white transition">
                </div>
            </div>
            <div>
                <label class="block text-sm font-extrabold text-slate-900 mb-1.5">Offense Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" required placeholder="e.g. Littering & Illegal Dumping"
                       class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm font-bold text-slate-900 outline-none focus:border-emerald-500 focus:bg-white transition">
            </div>
            <div>
                <label class="block text-sm font-extrabold text-slate-900 mb-1.5">Description</label>
                <textarea name="description" rows="3" placeholder="Describe the prohibited action..."
                          class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm font-medium text-slate-900 outline-none focus:border-emerald-500 focus:bg-white transition resize-none"></textarea>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-extrabold text-slate-900 mb-1.5">Fine Range</label>
                    <input type="text" name="fine_range" placeholder="e.g. ₱300 – ₱1,000"
                           class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm font-bold text-slate-900 outline-none focus:border-emerald-500 focus:bg-white transition">
                </div>
                <div>
                    <label class="block text-sm font-extrabold text-slate-900 mb-1.5">Alternative Penalty</label>
                    <input type="text" name="alt_penalty" placeholder="e.g. 1–15 days Community Service"
                           class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm font-bold text-slate-900 outline-none focus:border-emerald-500 focus:bg-white transition">
                </div>
            </div>
            <div class="pt-4 border-t border-slate-200 flex justify-end gap-3">
                <button type="button" onclick="closeAddModal()" class="px-5 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-extrabold rounded-xl transition">Cancel</button>
                <button type="submit" class="px-6 py-3 bg-[#0B2E22] hover:bg-[#093024] text-white text-sm font-extrabold rounded-xl shadow-xs transition">Save Rule</button>
            </div>
        </form>
    </div>
</div>

<!-- ═══════════════════════════════════════ EDIT MODAL ═══════════════════════════════════════ -->
<div id="editModal" class="modal-backdrop fixed inset-0 z-[999] flex items-center justify-center bg-slate-950/60 backdrop-blur-sm opacity-0 invisible p-4">
    <div class="modal-box w-full max-w-xl bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden scale-95 opacity-0">
        <div class="p-6 border-b border-slate-200 flex items-center justify-between">
            <h3 class="text-xl font-extrabold text-slate-900">Edit Rule</h3>
            <button onclick="closeEditModal()" class="p-2 hover:bg-slate-100 rounded-xl transition text-slate-400 hover:text-slate-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form method="POST" class="p-6 space-y-4">
            <input type="hidden" name="edit_rule" value="1">
            <input type="hidden" name="rule_id" id="editRuleId">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-extrabold text-slate-900 mb-1.5">Offense #</label>
                    <input type="number" name="offense_no" id="editOffenseNo" min="1" max="999"
                           class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm font-bold font-mono text-slate-900 outline-none focus:border-emerald-500 focus:bg-white transition">
                </div>
                <div>
                    <label class="block text-sm font-extrabold text-slate-900 mb-1.5">Legal Reference</label>
                    <input type="text" name="legal_ref" id="editLegalRef"
                           class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm font-bold text-slate-900 outline-none focus:border-emerald-500 focus:bg-white transition">
                </div>
            </div>
            <div>
                <label class="block text-sm font-extrabold text-slate-900 mb-1.5">Offense Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="editTitle" required
                       class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm font-bold text-slate-900 outline-none focus:border-emerald-500 focus:bg-white transition">
            </div>
            <div>
                <label class="block text-sm font-extrabold text-slate-900 mb-1.5">Description</label>
                <textarea name="description" id="editDescription" rows="3"
                          class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm font-medium text-slate-900 outline-none focus:border-emerald-500 focus:bg-white transition resize-none"></textarea>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-extrabold text-slate-900 mb-1.5">Fine Range</label>
                    <input type="text" name="fine_range" id="editFineRange"
                           class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm font-bold text-slate-900 outline-none focus:border-emerald-500 focus:bg-white transition">
                </div>
                <div>
                    <label class="block text-sm font-extrabold text-slate-900 mb-1.5">Alternative Penalty</label>
                    <input type="text" name="alt_penalty" id="editAltPenalty"
                           class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm font-bold text-slate-900 outline-none focus:border-emerald-500 focus:bg-white transition">
                </div>
            </div>
            <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-200">
                <input type="checkbox" name="is_active" id="editIsActive" value="1" class="w-4 h-4 accent-emerald-600">
                <label for="editIsActive" class="text-sm font-extrabold text-slate-900 cursor-pointer">Active (visible on public portal)</label>
            </div>
            <div class="pt-4 border-t border-slate-200 flex justify-end gap-3">
                <button type="button" onclick="closeEditModal()" class="px-5 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-extrabold rounded-xl transition">Cancel</button>
                <button type="submit" class="px-6 py-3 bg-[#0B2E22] hover:bg-[#093024] text-white text-sm font-extrabold rounded-xl shadow-xs transition">Update Rule</button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddModal() {
    const m = document.getElementById('addModal');
    m.classList.remove('opacity-0','invisible');
    setTimeout(() => { m.querySelector('.modal-box').classList.remove('scale-95','opacity-0'); }, 10);
}
function closeAddModal() {
    const m = document.getElementById('addModal');
    m.querySelector('.modal-box').classList.add('scale-95','opacity-0');
    setTimeout(() => m.classList.add('opacity-0','invisible'), 200);
}
function openEditModal(rule) {
    document.getElementById('editRuleId').value    = rule.rule_id;
    document.getElementById('editOffenseNo').value = rule.offense_no;
    document.getElementById('editTitle').value     = rule.title;
    document.getElementById('editDescription').value = rule.description || '';
    document.getElementById('editLegalRef').value  = rule.legal_ref  || '';
    document.getElementById('editFineRange').value = rule.fine_range || '';
    document.getElementById('editAltPenalty').value= rule.alt_penalty|| '';
    document.getElementById('editIsActive').checked= rule.is_active == 1;
    const m = document.getElementById('editModal');
    m.classList.remove('opacity-0','invisible');
    setTimeout(() => { m.querySelector('.modal-box').classList.remove('scale-95','opacity-0'); }, 10);
}
function closeEditModal() {
    const m = document.getElementById('editModal');
    m.querySelector('.modal-box').classList.add('scale-95','opacity-0');
    setTimeout(() => m.classList.add('opacity-0','invisible'), 200);
}
// Close on backdrop click
['addModal','editModal'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) { id === 'addModal' ? closeAddModal() : closeEditModal(); }
    });
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
