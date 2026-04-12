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
            <div class="max-w-5xl mx-auto px-4 py-8 flex-grow">
    <h1 class="text-3xl font-bold text-foreground mb-6">Barangay Announcements</h1>

    <?php if ($_SESSION['user_role'] == 'secretary'): ?>
    <div class="bg-card rounded-lg shadow-md p-6 mb-8 border-t-4 border-secondary">
        <h2 class="text-xl font-bold mb-4 text-foreground">Post New Announcement</h2>
        <form action="/brgy-waste-app-v3/public/admin/announcements" method="POST">
            <div class="mb-4">
                <label class="block text-sm font-medium text-foreground mb-1">Title</label>
                <input type="text" name="title" required class="w-full px-4 py-2 border border-border rounded-md focus:ring-2 focus:ring-primary outline-none bg-background">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-foreground mb-1">Content</label>
                <textarea name="content" required rows="4" class="w-full px-4 py-2 border border-border rounded-md focus:ring-2 focus:ring-primary outline-none bg-background"></textarea>
            </div>
            <div class="text-right">
                <button type="submit" class="bg-primary hover:bg-primary/90 text-primary-foreground px-6 py-2 rounded-md shadow-sm font-semibold">Post Announcement</button>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <div class="space-y-4">
        <?php if(!empty($data['announcements'])): foreach($data['announcements'] as $item): ?>
            <div class="bg-card rounded-lg shadow-sm border border-border p-6 flex items-start">
                <div class="bg-secondary/15 p-3 rounded-full mr-4 text-secondary text-xl font-bold">📢</div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900"><?php echo htmlspecialchars($item['title']); ?></h3>
                    <p class="text-xs text-gray-500 mb-2"><?php echo date('F d, Y h:i A', strtotime($item['created_at'])); ?></p>
                    <p class="text-gray-700"><?php echo nl2br(htmlspecialchars($item['content'])); ?></p>
                </div>
            </div>
        <?php endforeach; else: ?>
            <p class="text-center text-gray-500 py-4 bg-white rounded shadow-sm">No announcements posted yet.</p>
        <?php endif; ?>
    </div>
</div>
        </main>
    </div>
</div>
<?php include '../app/Views/layouts/footer.php'; ?>
