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
?>
<div class="flex h-screen bg-gray-50 overflow-hidden w-full">
    <?php include __DIR__ . '/../layouts/admin_sidebar.php'; ?>
    <div class="flex flex-col flex-1 w-0 overflow-hidden">
        <?php include __DIR__ . '/../layouts/admin_topbar.php'; ?>
        <main class="flex-1 relative overflow-y-auto focus:outline-none">
            <div class="max-w-3xl mx-auto px-4 py-8">
                <h1 class="text-3xl font-bold text-foreground mb-6">Edit Announcement</h1>
                <a href="/brgy-waste-app-v3/public/admin/announcements" class="text-sm text-emerald-600 hover:underline mb-4 inline-block">← Back to Announcements</a>

                <div class="bg-white rounded-lg shadow p-6">
                    <form action="/brgy-waste-app-v3/public/admin/edit_announcement/<?php echo $data['announcement']['id']; ?>" method="POST" enctype="multipart/form-data">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-foreground mb-1">Title</label>
                            <input type="text" name="title" value="<?php echo htmlspecialchars($announcementTitle); ?>" required class="w-full px-4 py-2 border border-border rounded-md focus:ring-2 focus:ring-primary outline-none bg-background">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-foreground mb-1">Content</label>
                            <textarea name="content" required rows="4" class="w-full px-4 py-2 border border-border rounded-md focus:ring-2 focus:ring-primary outline-none bg-background"><?php echo htmlspecialchars($announcementContent); ?></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-foreground mb-1">Visibility</label>
                            <select name="visibility_id" class="w-full px-4 py-2 border border-border rounded-md focus:ring-2 focus:ring-primary outline-none bg-background">
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
                        <div class="mb-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_published" <?php echo $isPublished ? 'checked' : ''; ?> class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                <span class="text-sm font-medium text-foreground">Published</span>
                            </label>
                        </div>

                        <div class="mb-4">
                          <label class="block text-sm font-medium text-foreground mb-1">Cover Image (Optional)</label>
                          <input type="file" name="cover_image" accept="image/jpeg,image/png,image/webp" class="w-full px-4 py-2 border border-border rounded-md focus:ring-2 focus:ring-primary outline-none bg-background">
                          <?php if (!empty($data['announcement']['cover_image'])): ?>
                              <div class="mt-2">
                                  <p class="text-xs text-gray-500 mb-1">Current cover image:</p>
                                  <img src="<?php echo htmlspecialchars($data['announcement']['cover_image']); ?>" alt="Current cover" class="h-32 object-cover rounded border border-gray-200">
                              </div>
                          <?php endif; ?>
                          <p class="text-xs text-gray-500 mt-1">Max 2MB. JPG, PNG, WEBP accepted.</p>
                      </div>
                        
                        <div class="flex gap-4">
                            <button type="submit" class="px-6 py-2 bg-emerald-600 text-white font-bold rounded-md hover:bg-emerald-700 transition">Update Announcement</button>
                            <a href="/brgy-waste-app-v3/public/admin/announcements" class="px-6 py-2 bg-gray-300 text-gray-700 font-bold rounded-md hover:bg-gray-400 transition">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>