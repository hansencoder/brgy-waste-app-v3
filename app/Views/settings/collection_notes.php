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
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-extrabold bg-emerald-100 text-emerald-900 border border-emerald-300">Collection Notes</span>
                            </div>
                            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">Important Notes on Garbage Collection</h1>
                            <p class="text-base sm:text-lg text-slate-600 font-semibold mt-1">
                                Manage guidelines displayed on the public portal's collection schedule section.
                            </p>
                        </div>
                        <button onclick="openAddNoteModal()" class="inline-flex items-center gap-2 px-5 py-3 bg-[#0B2E22] hover:bg-[#093024] text-white font-extrabold text-sm rounded-xl shadow-xs transition shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                            Add New Note
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
                        <?php $activeTab = 'collection_notes'; include __DIR__ . '/../layouts/settings_sidebar.php'; ?>

                        <div class="flex-1 min-w-0 space-y-6">

                            <!-- Info Banner -->
                            <div class="p-4 bg-emerald-50 border-2 border-emerald-200 rounded-2xl flex items-start gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                <p class="text-sm text-emerald-900 font-semibold">
                                    These notes appear on the public home page in the dark green panel under the collection schedule. Only <strong>active</strong> notes will be shown. Lower sort order = displayed first.
                                </p>
                            </div>

                            <!-- Notes Management Card -->
                            <div class="bg-white rounded-2xl border-2 border-slate-200 shadow-xs overflow-hidden">
                                <div class="p-6 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                    <div>
                                        <h2 class="text-xl font-extrabold text-slate-900">Collection Notes</h2>
                                        <p class="text-sm text-slate-600 font-semibold mt-0.5">Drag rows to reorder, or use the Order field when editing.</p>
                                    </div>
                                    <span class="px-4 py-1.5 rounded-full bg-slate-100 text-slate-900 font-mono font-extrabold text-sm border border-slate-200 self-start sm:self-auto shrink-0">
                                        <?php echo count($data['notes']); ?> Notes
                                    </span>
                                </div>

                                <?php if (empty($data['notes'])): ?>
                                    <div class="p-12 text-center">
                                        <div class="w-16 h-16 rounded-2xl bg-emerald-50 border-2 border-emerald-100 flex items-center justify-center mx-auto mb-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.85 2.83 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5Z"/><path d="m15 5 4 4"/></svg>
                                        </div>
                                        <p class="text-base font-extrabold text-slate-700">No notes added yet</p>
                                        <p class="text-sm text-slate-500 mt-1">Click <strong>Add New Note</strong> to publish guidelines to the public portal.</p>
                                    </div>
                                <?php else: ?>
                                    <div class="overflow-x-auto">
                                        <table class="w-full text-left border-collapse text-sm">
                                            <thead class="bg-slate-50 text-slate-500 font-extrabold uppercase tracking-wider text-xs border-b-2 border-slate-200">
                                                <tr>
                                                    <th class="px-5 py-4 w-16 text-center">Order</th>
                                                    <th class="px-5 py-4">Title &amp; Content</th>
                                                    <th class="px-5 py-4 text-center">Status</th>
                                                    <th class="px-5 py-4 text-right">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-100 bg-white">
                                                <?php foreach ($data['notes'] as $i => $note): ?>
                                                <tr class="hover:bg-slate-50/70 transition">
                                                    <td class="px-5 py-4 text-center">
                                                        <span class="font-mono text-sm font-extrabold text-slate-500"><?php echo $i + 1; ?></span>
                                                    </td>
                                                    <td class="px-5 py-4">
                                                        <p class="font-extrabold text-slate-900 text-sm"><?php echo htmlspecialchars($note['title']); ?></p>
                                                        <p class="text-xs text-slate-500 mt-0.5 font-medium leading-snug max-w-lg line-clamp-2"><?php echo htmlspecialchars($note['content']); ?></p>
                                                    </td>
                                                    <td class="px-5 py-4 text-center">
                                                        <?php if ($note['is_active']): ?>
                                                            <span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-900 text-xs font-extrabold border border-emerald-200">Active</span>
                                                        <?php else: ?>
                                                            <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-500 text-xs font-extrabold">Hidden</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="px-5 py-4 text-right">
                                                        <div class="flex items-center justify-end gap-2">
                                                            <button type="button"
                                                                onclick='openEditNoteModal(<?php echo json_encode($note); ?>)'
                                                                class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-extrabold transition border border-slate-200">
                                                                Edit
                                                            </button>
                                                            <form method="POST" onsubmit="return confirm('Delete this note?');" class="inline-block">
                                                                <input type="hidden" name="delete_note" value="1">
                                                                <input type="hidden" name="note_id" value="<?php echo $note['note_id']; ?>">
                                                                <button type="submit" class="px-3 py-2 bg-red-50 hover:bg-red-100 text-red-700 rounded-xl text-xs font-extrabold transition border border-red-200">Delete</button>
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

                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</div>

<!-- ═════════════════ ADD NOTE MODAL ═════════════════ -->
<div id="addNoteModal" class="modal-backdrop fixed inset-0 z-[999] flex items-center justify-center bg-slate-950/60 backdrop-blur-sm opacity-0 invisible p-4">
    <div class="modal-box w-full max-w-xl bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden scale-95 opacity-0">
        <div class="p-6 border-b border-slate-200 flex items-center justify-between">
            <h3 class="text-xl font-extrabold text-slate-900">Add New Collection Note</h3>
            <button onclick="closeAddNoteModal()" class="p-2 hover:bg-slate-100 rounded-xl transition text-slate-400 hover:text-slate-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form method="POST" class="p-6 space-y-4">
            <input type="hidden" name="add_note" value="1">
            <div>
                <label class="block text-sm font-extrabold text-slate-900 mb-1.5">Note Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" required placeholder='e.g. "No Segregation, No Collection"'
                       class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm font-bold text-slate-900 outline-none focus:border-emerald-500 focus:bg-white transition">
            </div>
            <div>
                <label class="block text-sm font-extrabold text-slate-900 mb-1.5">Content <span class="text-red-500">*</span></label>
                <textarea name="content" rows="4" required placeholder="Describe this guideline in detail..."
                          class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm font-medium text-slate-900 outline-none focus:border-emerald-500 focus:bg-white transition resize-none"></textarea>
            </div>
            <div class="pt-4 border-t border-slate-200 flex justify-end gap-3">
                <button type="button" onclick="closeAddNoteModal()" class="px-5 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-extrabold rounded-xl transition">Cancel</button>
                <button type="submit" class="px-6 py-3 bg-[#0B2E22] hover:bg-[#093024] text-white text-sm font-extrabold rounded-xl shadow-xs transition">Save Note</button>
            </div>
        </form>
    </div>
</div>

<!-- ═════════════════ EDIT NOTE MODAL ═════════════════ -->
<div id="editNoteModal" class="modal-backdrop fixed inset-0 z-[999] flex items-center justify-center bg-slate-950/60 backdrop-blur-sm opacity-0 invisible p-4">
    <div class="modal-box w-full max-w-xl bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden scale-95 opacity-0">
        <div class="p-6 border-b border-slate-200 flex items-center justify-between">
            <h3 class="text-xl font-extrabold text-slate-900">Edit Note</h3>
            <button onclick="closeEditNoteModal()" class="p-2 hover:bg-slate-100 rounded-xl transition text-slate-400 hover:text-slate-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <form method="POST" class="p-6 space-y-4">
            <input type="hidden" name="edit_note" value="1">
            <input type="hidden" name="note_id" id="editNoteId">
            <div>
                <label class="block text-sm font-extrabold text-slate-900 mb-1.5">Note Title <span class="text-red-500">*</span></label>
                <input type="text" name="title" id="editNoteTitle" required
                       class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm font-bold text-slate-900 outline-none focus:border-emerald-500 focus:bg-white transition">
            </div>
            <div>
                <label class="block text-sm font-extrabold text-slate-900 mb-1.5">Content <span class="text-red-500">*</span></label>
                <textarea name="content" id="editNoteContent" rows="4" required
                          class="w-full px-4 py-3 bg-slate-50 border-2 border-slate-200 rounded-xl text-sm font-medium text-slate-900 outline-none focus:border-emerald-500 focus:bg-white transition resize-none"></textarea>
            </div>
            <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-200">
                <input type="checkbox" name="is_active" id="editNoteActive" value="1" class="w-4 h-4 accent-emerald-600">
                <label for="editNoteActive" class="text-sm font-extrabold text-slate-900 cursor-pointer">Active (visible on public portal)</label>
            </div>
            <div class="pt-4 border-t border-slate-200 flex justify-end gap-3">
                <button type="button" onclick="closeEditNoteModal()" class="px-5 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-extrabold rounded-xl transition">Cancel</button>
                <button type="submit" class="px-6 py-3 bg-[#0B2E22] hover:bg-[#093024] text-white text-sm font-extrabold rounded-xl shadow-xs transition">Update Note</button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddNoteModal() {
    const m = document.getElementById('addNoteModal');
    m.classList.remove('opacity-0','invisible');
    setTimeout(() => m.querySelector('.modal-box').classList.remove('scale-95','opacity-0'), 10);
}
function closeAddNoteModal() {
    const m = document.getElementById('addNoteModal');
    m.querySelector('.modal-box').classList.add('scale-95','opacity-0');
    setTimeout(() => m.classList.add('opacity-0','invisible'), 200);
}
function openEditNoteModal(note) {
    document.getElementById('editNoteId').value      = note.note_id;
    document.getElementById('editNoteTitle').value   = note.title;
    document.getElementById('editNoteContent').value = note.content;
    document.getElementById('editNoteActive').checked= note.is_active == 1;
    const m = document.getElementById('editNoteModal');
    m.classList.remove('opacity-0','invisible');
    setTimeout(() => m.querySelector('.modal-box').classList.remove('scale-95','opacity-0'), 10);
}
function closeEditNoteModal() {
    const m = document.getElementById('editNoteModal');
    m.querySelector('.modal-box').classList.add('scale-95','opacity-0');
    setTimeout(() => m.classList.add('opacity-0','invisible'), 200);
}
['addNoteModal','editNoteModal'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) { id === 'addNoteModal' ? closeAddNoteModal() : closeEditNoteModal(); }
    });
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
