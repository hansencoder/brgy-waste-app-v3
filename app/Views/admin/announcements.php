<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="flex h-screen bg-gray-50 overflow-hidden w-full">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../layouts/admin_sidebar.php'; ?>

    <!-- Main Content Wrapper -->
    <div class="flex flex-col flex-1 w-0 overflow-hidden">
        
        <!-- Top Nav -->
        <?php include __DIR__ . '/../layouts/admin_topbar.php'; ?>
        
        <!-- Scrollable Content -->
        <main class="flex-1 relative overflow-y-auto focus:outline-none">
            <div class="max-w-5xl mx-auto px-4 py-8 flex-grow">
                <h1 class="text-3xl font-bold text-foreground mb-6">Barangay Announcements</h1>

                <?php if ($_SESSION['user_role'] == 'secretary' || $_SESSION['user_role'] == 'administrator'): ?>
                <div class="bg-card rounded-lg shadow-md p-6 mb-8 border-t-4 border-secondary">
                    <h2 class="text-xl font-bold mb-4 text-foreground">Post New Announcement</h2>
                    <form action="/brgy-waste-app-v3/public/admin/announcements" method="POST" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-foreground mb-1">Title</label>
                            <input type="text" name="title" required class="w-full px-4 py-2 border border-border rounded-md focus:ring-2 focus:ring-primary outline-none bg-background">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-foreground mb-1">Content</label>
                            <textarea name="content" required rows="4" class="w-full px-4 py-2 border border-border rounded-md focus:ring-2 focus:ring-primary outline-none bg-background"></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-foreground mb-1">Visibility</label>
                            <select name="visibility_id" class="w-full px-4 py-2 border border-border rounded-md focus:ring-2 focus:ring-primary outline-none bg-background">
                                <?php foreach (($data['visibilities'] ?? []) as $vis): ?>
                                    <option value="<?php echo (int)($vis['visibility_id'] ?? 1); ?>"><?php echo htmlspecialchars((string)($vis['visibility_name'] ?? 'Public')); ?></option>
                                <?php endforeach; ?>
                                <?php if (empty($data['visibilities'])): ?>
                                    <option value="1">Public</option>
                                <?php endif; ?>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Public = visible to all; Registered = residents, staff; Internal = staff only.</p>
                        </div>

                        <!-- Cover Image Upload -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-foreground mb-1">Cover Image (Optional)</label>
                            <input type="file" name="cover_image" accept="image/jpeg,image/png,image/webp" class="w-full px-4 py-2 border border-border rounded-md focus:ring-2 focus:ring-primary outline-none bg-background">
                            <p class="text-xs text-gray-500 mt-1">Recommended size: 1200x600px. Max 2MB.</p>
                        </div>

                        <!-- Publish Date -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-foreground mb-1">Publish Date</label>
                            <input type="datetime-local" name="publish_date" class="w-full px-4 py-2 border border-border rounded-md focus:ring-2 focus:ring-primary outline-none bg-background">
                            <p class="text-xs text-gray-500 mt-1">Leave empty to publish immediately.</p>
                        </div>

                        <!-- Expiration Date -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-foreground mb-1">Expiration Date (Optional)</label>
                            <input type="datetime-local" name="expiration_date" class="w-full px-4 py-2 border border-border rounded-md focus:ring-2 focus:ring-primary outline-none bg-background">
                            <p class="text-xs text-gray-500 mt-1">Announcement will be hidden after this date.</p>
                        </div>

                        <!-- Published Status -->
                        <div class="mb-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_published" checked class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                <span class="text-sm font-medium text-foreground">Publish immediately</span>
                            </label>
                        </div>


                        <div class="text-right">
                            <button type="submit" class="bg-[#15281F] hover:bg-[#15281F]/90 text-primary-foreground px-6 py-2 rounded-md shadow-sm font-semibold">Post Announcement</button>
                        </div>
                    </form>
                </div>
                <?php endif; ?>

                <div class="space-y-4">
                    <?php if(!empty($data['announcements'])): foreach($data['announcements'] as $item): ?>
                        <div class="bg-card rounded-lg shadow-sm border border-border p-6 flex items-start relative group">
                            <div class="flex-1">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-bold text-gray-900"><?php echo htmlspecialchars($item['title']); ?></h3>
                                        <p class="text-xs text-gray-500 mb-2"><?php echo date('F d, Y h:i A', strtotime($item['created_at'])); ?></p>
                                        <span class="inline-block px-2 py-0.5 text-xs font-semibold rounded-full bg-gray-100 text-gray-700"><?php echo htmlspecialchars($item['visibility_name'] ?? 'Public'); ?></span>
                                    </div>
                                    <?php if ($_SESSION['user_role'] == 'secretary' || $_SESSION['user_role'] == 'administrator'): ?>
                                        <a href="/brgy-waste-app-v3/public/admin/edit_announcement/<?php echo $item['id']; ?>" class="text-blue-600 hover:text-blue-800 text-sm font-medium mr-2">Edit</a>
                                    <button onclick="showDeleteConfirm(<?php echo $item['id']; ?>, '<?php echo addslashes(htmlspecialchars($item['title'])); ?>')" class="shrink-0 p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete Announcement">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                    </button>
                                    <?php endif; ?>
                                </div>
                                <p class="text-gray-700 mt-2"><?php echo nl2br(htmlspecialchars($item['content'])); ?></p>
                            </div>
                        </div>
                    <?php endforeach; else: ?>
                        <p class="text-center text-gray-500 py-4 bg-white rounded shadow-sm">No announcements posted yet.</p>
                    <?php endif; ?>
                </div>
            </div>

<!-- Delete Confirmation Modal -->
<div id="deleteAnnouncementModal" class="fixed inset-0 z-[9999] hidden">
    <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" onclick="hideDeleteConfirm()"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 relative">
            <button onclick="hideDeleteConfirm()" class="absolute top-4 right-4 p-1 rounded-full text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>

            <div class="text-center mb-4">
                <div class="mx-auto w-14 h-14 rounded-full bg-red-100 flex items-center justify-center mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-red-600"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-1">Delete Announcement?</h3>
                <p class="text-sm text-slate-500">Are you sure you want to delete <strong id="announcementTitle" class="text-slate-700"></strong>? This action cannot be undone.</p>
            </div>

            <div class="flex gap-3">
                <button onclick="hideDeleteConfirm()" class="flex-1 px-4 py-2.5 border border-gray-200 text-slate-700 rounded-xl font-semibold text-sm hover:bg-gray-50 transition-colors">
                    Cancel
                </button>
                <form id="deleteAnnouncementForm" action="/brgy-waste-app-v3/public/admin/delete_announcement" method="POST" class="flex-1">
                    <input type="hidden" id="announcementId" name="announcement_id" value="">
                    <button type="submit" class="w-full px-4 py-2.5 bg-red-600 text-white rounded-xl font-semibold text-sm hover:bg-red-700 transition-colors shadow-lg shadow-red-600/20">
                        Delete Announcement
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function showDeleteConfirm(id, title) {
    document.getElementById('announcementId').value = id;
    document.getElementById('announcementTitle').textContent = title;
    document.getElementById('deleteAnnouncementModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function hideDeleteConfirm() {
    document.getElementById('deleteAnnouncementModal').classList.add('hidden');
    document.body.style.overflow = '';
}
</script>
        </main>
    </div>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>