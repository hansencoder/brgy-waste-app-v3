<?php
// If $data is not defined, initialize it
if (!isset($data) || !is_array($data)) {
    $data = [
        'error' => '',
        'success' => '',
        'positions' => [],
        'roles' => [],
        'puroks' => []
    ];
}
?>

<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="flex h-screen bg-gray-50 overflow-hidden w-full">
    <?php include __DIR__ . '/../layouts/admin_sidebar.php'; ?>
    <div class="flex flex-col flex-1 w-0 overflow-hidden">
        <?php include __DIR__ . '/../layouts/admin_topbar.php'; ?>
        <main class="flex-1 relative overflow-y-auto focus:outline-none">
            <div class="max-w-3xl mx-auto px-4 py-8">
                <h1 class="text-3xl font-bold text-foreground mb-6">Create Staff Account</h1>

                <?php if (!empty($data['error'])): ?>
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg"><?php echo htmlspecialchars($data['error']); ?></div>
                <?php endif; ?>
                <?php if (!empty($data['success'])): ?>
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg"><?php echo htmlspecialchars($data['success']); ?></div>
                <?php endif; ?>

                <div class="bg-white rounded-lg shadow p-6">
                    <form action="/brgy-waste-app-v3/public/admin/createStaff" method="POST">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Full Name</label>
                                <input type="text" name="name" required class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-emerald-500 outline-none" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Username</label>
                                <input type="text" name="username" required class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-emerald-500 outline-none" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" required class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-emerald-500 outline-none" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Phone Number</label>
                                <input type="text" name="phone" required class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-emerald-500 outline-none" placeholder="09XXXXXXXXX" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Official Position</label>
                                <select name="position_id" required class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-emerald-500 outline-none">
                                    <option value="">Select Position</option>
                                    <?php foreach ($data['positions'] as $pos): ?>
                                        <option value="<?php echo $pos['position_id']; ?>" <?php echo (isset($_POST['position_id']) && $_POST['position_id'] == $pos['position_id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($pos['position_name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">System Role</label>
                                <select name="role_id" required class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-emerald-500 outline-none">
                                    <option value="">Select Role</option>
                                    <?php foreach ($data['roles'] as $role): ?>
                                        <option value="<?php echo $role['role_id']; ?>" <?php echo (isset($_POST['role_id']) && $_POST['role_id'] == $role['role_id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($role['role_name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Purok</label>
                                <select name="purok_id" required class="w-full px-3 py-2 border rounded-md focus:ring-2 focus:ring-emerald-500 outline-none">
                                    <?php foreach ($data['puroks'] as $p): ?>
                                        <option value="<?php echo $p['purok_id']; ?>" <?php echo (isset($_POST['purok_id']) && $_POST['purok_id'] == $p['purok_id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($p['purok_name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="mt-6 flex gap-4">
                            <button type="submit" class="px-6 py-2 bg-emerald-600 text-white font-bold rounded-md hover:bg-emerald-700 transition">Create Account</button>
                            <a href="/brgy-waste-app-v3/public/admin/accounts" class="px-6 py-2 bg-gray-300 text-gray-700 font-bold rounded-md hover:bg-gray-400 transition">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>