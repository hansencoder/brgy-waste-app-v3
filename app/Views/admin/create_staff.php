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
<div class="min-h-screen bg-[#F8FAFC]">
    <div class="lg:flex lg:min-h-screen">
        <?php include __DIR__ . '/../layouts/admin_sidebar.php'; ?>
        <div class="flex-1 flex flex-col min-w-0">
            <?php include __DIR__ . '/../layouts/admin_topbar.php'; ?>
            
            <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto">
                <div class="max-w-3xl mx-auto py-2 sm:py-4">
                    
                    <!-- Centered Header Section -->
                    <div class="text-center max-w-xl mx-auto mb-6 sm:mb-8">
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">Create Staff Account</h1>
                        <p class="text-xs sm:text-sm font-medium text-slate-500 mt-1.5 leading-relaxed">Register a new administrative or field staff member into the system.</p>
                    </div>

                    <!-- Main Form Card (Matching Screenshot) -->
                    <div class="bg-white rounded-2xl sm:rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-200/80 border-l-4 border-l-[#056839]">
                        
                        <!-- Notifications -->
                        <?php if (!empty($data['error'])): ?>
                            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-2xl text-xs sm:text-sm font-semibold flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                <span><?php echo htmlspecialchars($data['error']); ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($data['success'])): ?>
                            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl text-xs sm:text-sm font-semibold flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                <span><?php echo htmlspecialchars($data['success']); ?></span>
                            </div>
                        <?php endif; ?>

                        <form action="/brgy-waste-app-v3/public/admin/createStaff" method="POST" class="space-y-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6">
                                
                                <!-- Full Name -->
                                <div>
                                    <label class="block text-xs sm:text-sm font-bold text-slate-800 tracking-tight mb-2">Full Name</label>
                                    <input type="text" name="name" required placeholder="Enter full name" 
                                           value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" 
                                           class="w-full px-4 py-3 bg-[#F8FAFC] border border-slate-200 rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 outline-none transition-all">
                                </div>

                                <!-- Username -->
                                <div>
                                    <label class="block text-xs sm:text-sm font-bold text-slate-800 tracking-tight mb-2">Username</label>
                                    <input type="text" name="username" required placeholder="Choose a username" 
                                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" 
                                           class="w-full px-4 py-3 bg-[#F8FAFC] border border-slate-200 rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 outline-none transition-all">
                                </div>

                                <!-- Email Address -->
                                <div>
                                    <label class="block text-xs sm:text-sm font-bold text-slate-800 tracking-tight mb-2">Email Address</label>
                                    <input type="email" name="email" required placeholder="name@domain.com" 
                                           value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                                           class="w-full px-4 py-3 bg-[#F8FAFC] border border-slate-200 rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 outline-none transition-all">
                                </div>

                                <!-- Phone Number -->
                                <div>
                                    <label class="block text-xs sm:text-sm font-bold text-slate-800 tracking-tight mb-2">Phone Number</label>
                                    <input type="text" name="phone" required placeholder="09XXXXXXXXX" 
                                           value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>" 
                                           class="w-full px-4 py-3 bg-[#F8FAFC] border border-slate-200 rounded-xl text-sm font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 outline-none transition-all">
                                </div>

                                <!-- Official Position -->
                                <div>
                                    <label class="block text-xs sm:text-sm font-bold text-slate-800 tracking-tight mb-2">Official Position</label>
                                    <div class="relative">
                                        <select name="position_id" required 
                                                class="w-full px-4 py-3 bg-[#F8FAFC] border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 outline-none transition-all appearance-none cursor-pointer pr-10">
                                            <option value="">Select Position</option>
                                            <?php foreach ($data['positions'] as $pos): ?>
                                                <option value="<?php echo $pos['position_id']; ?>" <?php echo (isset($_POST['position_id']) && $_POST['position_id'] == $pos['position_id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($pos['position_name']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- System Role -->
                                <div>
                                    <label class="block text-xs sm:text-sm font-bold text-slate-800 tracking-tight mb-2">System Role</label>
                                    <div class="relative">
                                        <select name="role_id" required 
                                                class="w-full px-4 py-3 bg-[#F8FAFC] border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 outline-none transition-all appearance-none cursor-pointer pr-10">
                                            <option value="">Select Role</option>
                                            <?php foreach ($data['roles'] as $role): ?>
                                                <option value="<?php echo $role['role_id']; ?>" <?php echo (isset($_POST['role_id']) && $_POST['role_id'] == $role['role_id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($role['role_name']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Assigned Purok -->
                                <div class="md:col-span-2">
                                    <label class="block text-xs sm:text-sm font-bold text-slate-800 tracking-tight mb-2">Assigned Purok</label>
                                    <div class="relative">
                                        <select name="purok_id" required 
                                                class="w-full px-4 py-3 bg-[#F8FAFC] border border-slate-200 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 outline-none transition-all appearance-none cursor-pointer pr-10">
                                            <?php foreach ($data['puroks'] as $p): ?>
                                                <option value="<?php echo $p['purok_id']; ?>" <?php echo (isset($_POST['purok_id']) && $_POST['purok_id'] == $p['purok_id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($p['purok_name']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- Divider and Action Buttons -->
                            <div class="pt-6 mt-6 border-t border-slate-100 flex items-center gap-3">
                                <button type="submit" class="px-6 py-2.5 bg-[#056839] hover:bg-[#04522d] text-white text-sm font-bold rounded-xl shadow-sm transition-all duration-200 cursor-pointer">
                                    Create Account
                                </button>
                                <a href="/brgy-waste-app-v3/public/admin/accounts" class="px-6 py-2.5 bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 text-sm font-bold rounded-xl transition-all duration-200 inline-flex items-center justify-center">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>

                </div>
            </main>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?>