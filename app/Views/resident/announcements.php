<?php include '../app/Views/layouts/header.php'; ?>
<nav class="bg-sidebar text-sidebar-foreground shadow-md border-b border-sidebar-border">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between h-16">
            <div class="flex items-center space-x-4">
                <span class="font-bold text-xl">Dulong Bayan Reporter</span>
                <a href="/brgy-waste-app-v3/public/resident" class="px-3 py-2 rounded-md hover:bg-sidebar-accent/90">My Reports</a>
                <a href="/brgy-waste-app-v3/public/resident/submit" class="px-3 py-2 rounded-md hover:bg-sidebar-accent/90">Submit Report</a>
                <a href="/brgy-waste-app-v3/public/resident/announcements" class="px-3 py-2 rounded-md bg-sidebar-accent">Announcements</a>
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-sm">Hi, <?php echo $_SESSION['user_name']; ?></span>
                <a href="/brgy-waste-app-v3/public/auth/logout" class="px-3 py-2 bg-destructive text-destructive-foreground hover:opacity-90 rounded-md text-sm font-semibold">Logout</a>
            </div>
        </div>
    </div>
</nav>

<div class="max-w-5xl mx-auto px-4 py-8 flex-grow">
    <h1 class="text-3xl font-bold text-foreground mb-6">Barangay Announcements</h1>

    <div class="space-y-4">
        <?php if(!empty($data['announcements'])): foreach($data['announcements'] as $item): ?>
            <div class="bg-card rounded-lg shadow-sm border border-border p-6 flex items-start hover:shadow-md transition">
                <div class="bg-secondary/15 p-3 rounded-full mr-4 text-secondary text-xl font-bold">📢</div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900"><?php echo htmlspecialchars($item['title']); ?></h3>
                    <p class="text-xs text-gray-500 mb-2">Posted on: <?php echo date('F d, Y h:i A', strtotime($item['created_at'])); ?></p>
                    <p class="text-gray-700 leading-relaxed"><?php echo nl2br(htmlspecialchars($item['message'])); ?></p>
                </div>
            </div>
        <?php endforeach; else: ?>
            <p class="text-center text-gray-500 py-8 bg-white rounded shadow-sm border border-dashed border-gray-300">There are currently no announcements. Check back later!</p>
        <?php endif; ?>
    </div>
</div>
<?php include '../app/Views/layouts/footer.php'; ?>
