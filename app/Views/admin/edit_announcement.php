<?php
// Safety: Ensure $data is defined
if (!isset($data) || !is_array($data)) {
    $data = [
        'announcement' => [],
        'visibilities' => []
    ];
}
?>
<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php
$announcement = $data['announcement'] ?? [];
$visibilities = $data['visibilities'] ?? [];
$announcementId = (int)($announcement['id'] ?? 0);
$announcementTitle = $announcement['title'] ?? '';
$announcementContent = $announcement['content'] ?? '';
$announcementVisibility = (int)($announcement['visibility_id'] ?? 1);
$isPublished = !empty($announcement['is_published']);
$publishDate = !empty($announcement['publish_date']) ? substr($announcement['publish_date'], 0, 16) : '';
$expirationDate = !empty($announcement['expiration_date']) ? substr($announcement['expiration_date'], 0, 16) : '';
?>

<style>
    body, * { font-family: 'Miranda Sans', sans-serif !important; font-optical-sizing: auto; }
    .mobile-sidebar-open #mobileSidebar { transform: translateX(0) !important; }
    #mobileSidebarOverlay { transition: opacity 0.25s ease, visibility 0.25s ease; opacity: 0; visibility: hidden; }
    .mobile-sidebar-open #mobileSidebarOverlay { opacity: 1; visibility: visible; }
</style>

<div class="min-h-screen bg-[#F8FAFC] text-slate-900 w-full flex font-sans antialiased">
    
    <!-- Mobile Sidebar Overlay -->
    <div id="mobileSidebarOverlay" class="fixed inset-0 bg-slate-950/40 z-40 lg:hidden"></div>

    <div class="lg:flex lg:min-h-screen w-full">
        <?php include __DIR__ . '/../layouts/admin_sidebar.php'; ?>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <?php include __DIR__ . '/../layouts/admin_topbar.php'; ?>

            <main class="flex-1 overflow-y-auto">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6">

                    <!-- Breadcrumbs & Header -->
                    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <a href="<?php echo app_url('admin/announcements'); ?>" class="text-sm font-extrabold text-slate-500 hover:text-emerald-700 transition">Announcements Hub</a>
                                <span class="text-sm text-slate-300">/</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-extrabold bg-amber-100 text-amber-900 border border-amber-300">
                                    Edit Broadcast
                                </span>
                            </div>
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Edit Announcement</h1>
                            <p class="text-sm text-slate-500 font-semibold mt-1">Modify announcement content, visibility target, or schedule dates.</p>
                        </div>
                        <a href="<?php echo app_url('admin/announcements'); ?>" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs sm:text-sm font-extrabold rounded-xl transition border border-slate-200 shrink-0 self-start sm:self-auto">
                            ← Back to Feed
                        </a>
                    </div>

                    <!-- Edit Form Card -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-6 sm:p-8">
                        <form action="<?php echo app_url('admin/edit_announcement/' . $announcementId); ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
                            <input type="hidden" name="announcement_id" value="<?php echo $announcementId; ?>">

                            <!-- Title -->
                            <div>
                                <label class="block text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-2">Title / Headline <span class="text-red-500">*</span></label>
                                <input type="text" name="title" value="<?php echo htmlspecialchars($announcementTitle); ?>" required
                                       class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-bold text-slate-900 bg-slate-50 focus:bg-white focus:border-emerald-500 outline-none transition">
                            </div>

                            <!-- Content -->
                            <div>
                                <label class="block text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-2">Announcement Content <span class="text-red-500">*</span></label>
                                <textarea name="content" required rows="6"
                                          class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm font-semibold text-slate-900 bg-slate-50 focus:bg-white focus:border-emerald-500 outline-none transition"><?php echo htmlspecialchars($announcementContent); ?></textarea>
                            </div>

                            <!-- Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-2">Visibility</label>
                                    <select name="visibility_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-bold text-slate-800 bg-slate-50 focus:bg-white focus:border-emerald-500 outline-none cursor-pointer">
                                        <?php foreach ($visibilities as $vis): ?>
                                            <?php $visId = (int)($vis['visibility_id'] ?? 1); ?>
                                            <?php $visName = (string)($vis['visibility_name'] ?? 'Public'); ?>
                                            <option value="<?php echo $visId; ?>" <?php echo $announcementVisibility == $visId ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($visName); ?>
                                            </option>
                                        <?php endforeach; ?>
                                        <?php if (empty($visibilities)): ?>
                                            <option value="1" <?php echo $announcementVisibility == 1 ? 'selected' : ''; ?>>Public</option>
                                        <?php endif; ?>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-2">Publish Date</label>
                                    <input type="datetime-local" name="publish_date" value="<?php echo htmlspecialchars($publishDate); ?>"
                                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-bold text-slate-800 bg-slate-50 focus:bg-white focus:border-emerald-500 outline-none">
                                </div>

                                <div>
                                    <label class="block text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-2">Expiration Date (Optional)</label>
                                    <input type="datetime-local" name="expiration_date" value="<?php echo htmlspecialchars($expirationDate); ?>"
                                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-bold text-slate-800 bg-slate-50 focus:bg-white focus:border-emerald-500 outline-none">
                                </div>
                            </div>

                            <!-- Cover Image -->
                            <div>
                                <label class="block text-xs font-extrabold text-slate-800 uppercase tracking-wider mb-2">Cover Banner / Photo (Optional)</label>
                                <input type="file" name="cover_image" accept="image/jpeg,image/png,image/webp"
                                       class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-700 bg-slate-50 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-extrabold file:bg-emerald-100 file:text-emerald-900 hover:file:bg-emerald-200 cursor-pointer">
                                <?php if (!empty($announcement['cover_image'])): ?>
                                    <div class="mt-3 flex items-center gap-3 p-3 bg-slate-50 rounded-xl border border-slate-200">
                                        <img src="<?php echo htmlspecialchars($announcement['cover_image']); ?>" alt="Current cover" class="w-20 h-14 object-cover rounded-lg border border-slate-200">
                                        <span class="text-xs text-slate-500 font-semibold">Current cover image attached</span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Published Toggle -->
                            <div class="flex items-center gap-2.5 p-3 bg-slate-50 rounded-xl border border-slate-200">
                                <input type="checkbox" name="is_published" id="isPub" <?php echo $isPublished ? 'checked' : ''; ?> class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500">
                                <label for="isPub" class="text-xs sm:text-sm font-extrabold text-slate-800 cursor-pointer">Published (Visible on resident app / portal)</label>
                            </div>

                            <!-- Actions -->
                            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                                <a href="<?php echo app_url('admin/announcements'); ?>" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs sm:text-sm font-extrabold rounded-xl transition">
                                    Cancel
                                </a>
                                <button type="submit" class="px-6 py-2.5 bg-[#0B2E22] hover:bg-[#084232] text-white text-xs sm:text-sm font-extrabold rounded-xl transition shadow-xs">
                                    Save Changes
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </main>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>