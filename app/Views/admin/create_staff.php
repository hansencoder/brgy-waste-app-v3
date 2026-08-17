<?php
// Initialize data defaults if not passed
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
                <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

                    <!-- ============================================================ -->
                    <!-- 1. PAGE HEADER & BREADCRUMB                                  -->
                    <!-- ============================================================ -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-250 shadow-xs">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <a href="/brgy-waste-app-v3/public/admin/accounts" class="text-xs font-bold text-slate-400 hover:text-emerald-700 transition">User Management</a>
                                <span class="text-xs text-slate-300">/</span>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                    Create Staff Account
                                </span>
                            </div>
                            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                                Register Staff Member
                            </h1>
                            <p class="text-sm text-slate-500 font-medium mt-0.5">
                                Onboard administrative personnel, field supervisors, or eco-inspectors into the system.
                            </p>
                        </div>

                        <!-- Back Button -->
                        <a href="/brgy-waste-app-v3/public/admin/accounts" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition self-start sm:self-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                            Back to Accounts
                        </a>
                    </div>

                    <!-- ============================================================ -->
                    <!-- 2. NOTIFICATIONS & CREDENTIALS BANNER                        -->
                    <!-- ============================================================ -->
                    <?php if (!empty($data['error'])): ?>
                        <div class="p-4 bg-red-50 border border-red-200 text-red-900 rounded-2xl text-xs font-bold flex items-start gap-3 shadow-xs">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <div class="flex-1">
                                <span><?php echo htmlspecialchars($data['error']); ?></span>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($data['success'])): ?>
                        <div class="p-5 bg-emerald-50 border border-emerald-200 text-emerald-950 rounded-2xl text-xs font-medium space-y-3 shadow-xs">
                            <div class="flex items-center gap-3">
                                <span class="p-2 rounded-xl bg-emerald-100 text-emerald-700 shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                                </span>
                                <span class="font-bold text-sm text-emerald-900"><?php echo htmlspecialchars($data['success']); ?></span>
                            </div>

                            <?php if (!empty($data['generated_password'])): ?>
                            <!-- Temporary Credential Display Box -->
                            <div class="p-4 rounded-xl bg-[#0B2E22] text-white border border-emerald-500/30 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-400">Account Credentials Created</p>
                                    <p class="text-xs text-slate-200 mt-0.5">Password: <strong class="font-mono text-emerald-300 text-sm px-2 py-0.5 bg-emerald-950/80 rounded border border-emerald-500/30"><?php echo htmlspecialchars($data['generated_password']); ?></strong></p>
                                </div>
                                <button type="button" onclick="navigator.clipboard.writeText('<?php echo htmlspecialchars($data['generated_password']); ?>'); alert('Password copied to clipboard!');" 
                                        class="px-3 py-1.5 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-slate-950 font-bold text-xs shadow-xs transition">
                                    Copy Password
                                </button>
                            </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <!-- ============================================================ -->
                    <!-- 3. MAIN FORM CONTAINER                                       -->
                    <!-- ============================================================ -->
                    <form action="/brgy-waste-app-v3/public/admin/createStaff" method="POST" class="space-y-6">
                        
                        <!-- SECTION 1: PERSONAL & CONTACT IDENTITY -->
                        <div class="bg-white rounded-2xl border border-slate-250 p-6 shadow-xs space-y-5">
                            <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                </span>
                                <div>
                                    <h2 class="text-base font-bold text-slate-900">Personal &amp; Contact Identity</h2>
                                    <p class="text-xs text-slate-500">Provide official details for staff identification</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                
                                <!-- Full Name -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-800 tracking-tight mb-2">Full Name <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                        </div>
                                        <input type="text" name="name" required placeholder="e.g. Maria Santos" 
                                               value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" 
                                               class="w-full pl-10 pr-4 py-3 bg-slate-50/70 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#10B981] focus:ring-4 focus:ring-emerald-500/10 outline-none transition-all">
                                    </div>
                                </div>

                                <!-- Username -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-800 tracking-tight mb-2">Username <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 font-mono font-bold text-xs">
                                            @
                                        </div>
                                        <input type="text" name="username" required placeholder="msantos" 
                                               value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" 
                                               class="w-full pl-10 pr-4 py-3 bg-slate-50/70 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#10B981] focus:ring-4 focus:ring-emerald-500/10 outline-none transition-all">
                                    </div>
                                </div>

                                <!-- Email Address -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-800 tracking-tight mb-2">Email Address <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                                        </div>
                                        <input type="email" name="email" required placeholder="maria.santos@dulongbayan.gov.ph" 
                                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" 
                                               class="w-full pl-10 pr-4 py-3 bg-slate-50/70 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#10B981] focus:ring-4 focus:ring-emerald-500/10 outline-none transition-all">
                                    </div>
                                </div>

                                <!-- Phone Number -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-800 tracking-tight mb-2">Mobile Phone Number <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="20" x="5" y="2" rx="2" ry="2"/><path d="M12 18h.01"/></svg>
                                        </div>
                                        <input type="text" name="phone" id="phoneInput" required placeholder="09171234567" maxlength="11"
                                               value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>" 
                                               class="w-full pl-10 pr-4 py-3 bg-slate-50/70 border border-slate-200 rounded-xl text-xs font-semibold font-mono text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#10B981] focus:ring-4 focus:ring-emerald-500/10 outline-none transition-all">
                                    </div>
                                    <p class="text-[10px] text-slate-400 font-medium mt-1">Must be 11 digits starting with 09 (e.g. 09171234567)</p>
                                </div>

                            </div>
                        </div>

                        <!-- SECTION 2: OFFICIAL POSITION & ACCESS ASSIGNMENT -->
                        <div class="bg-white rounded-2xl border border-slate-250 p-6 shadow-xs space-y-5">
                            <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-purple-50 text-purple-600 border border-purple-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="7" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                                </span>
                                <div>
                                    <h2 class="text-base font-bold text-slate-900">Position &amp; Role Access</h2>
                                    <p class="text-xs text-slate-500">Configure staff system permissions and assigned zone</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                
                                <!-- Official Position -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-800 tracking-tight mb-2">Official Position <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <select name="position_id" required 
                                                class="w-full px-4 py-3 bg-slate-50/70 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:bg-white focus:border-[#10B981] focus:ring-4 focus:ring-emerald-500/10 outline-none transition-all appearance-none cursor-pointer pr-10">
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
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-xs font-bold text-slate-800 tracking-tight">System Access Role <span class="text-red-500">*</span></label>
                                        <a href="/brgy-waste-app-v3/public/settings/role_management" target="_blank" class="text-[10px] font-bold text-emerald-600 hover:text-emerald-700 hover:underline flex items-center gap-1">
                                            <span>Manage Roles</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                                        </a>
                                    </div>
                                    <div class="relative">
                                        <select name="role_id" required 
                                                class="w-full px-4 py-3 bg-slate-50/70 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 focus:bg-white focus:border-[#10B981] focus:ring-4 focus:ring-emerald-500/10 outline-none transition-all appearance-none cursor-pointer pr-10">
                                            <option value="">Select Role</option>
                                            <?php foreach ($data['roles'] as $role): ?>
                                                <option value="<?php echo $role['role_id']; ?>" <?php echo (isset($_POST['role_id']) && $_POST['role_id'] == $role['role_id']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($role['role_name']); ?><?php echo (!empty($role['is_custom'])) ? ' (Custom Role)' : ''; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                                        </div>
                                    </div>
                                    <p class="text-[10px] text-slate-400 font-medium mt-1">Defines dashboard access and permissions for this staff member</p>
                                </div>

                            </div>
                        </div>

                        <!-- SECTION 3: CREDENTIALS & PASSWORD OPTIONS -->
                        <div class="bg-white rounded-2xl border border-slate-250 p-6 shadow-xs space-y-5">
                            <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-amber-50 text-amber-600 border border-amber-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                </span>
                                <div>
                                    <h2 class="text-base font-bold text-slate-900">Security &amp; Password Assignment</h2>
                                    <p class="text-xs text-slate-500">Choose password generation strategy</p>
                                </div>
                            </div>

                            <!-- Mode Selector -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <label class="p-4 rounded-xl border border-slate-200 bg-slate-50/70 hover:bg-slate-50 cursor-pointer flex items-start gap-3 transition">
                                    <input type="radio" name="password_type" value="auto" checked onclick="togglePasswordMode('auto')" class="mt-1 text-emerald-600 focus:ring-emerald-500">
                                    <div>
                                        <p class="text-xs font-bold text-slate-900">Auto-generate Password (Recommended)</p>
                                        <p class="text-[11px] text-slate-500 mt-0.5">Creates a secure 12-char password and emails it to the staff member.</p>
                                    </div>
                                </label>

                                <label class="p-4 rounded-xl border border-slate-200 bg-slate-50/70 hover:bg-slate-50 cursor-pointer flex items-start gap-3 transition">
                                    <input type="radio" name="password_type" value="manual" onclick="togglePasswordMode('manual')" class="mt-1 text-emerald-600 focus:ring-emerald-500">
                                    <div>
                                        <p class="text-xs font-bold text-slate-900">Set Manual Password</p>
                                        <p class="text-[11px] text-slate-500 mt-0.5">Specify a custom password or click the password generator button.</p>
                                    </div>
                                </label>
                            </div>

                            <!-- Manual Password Inputs (Hidden by Default) -->
                            <div id="manualPasswordContainer" class="hidden pt-3 border-t border-slate-100 space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-800">Custom Password</span>
                                    <button type="button" onclick="generateRandomPassword()" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.5 2v6h-6"/><path d="M21.34 15.57a10 10 0 1 1-.57-8.38l5.67-5.67"/></svg>
                                        Generate Secure Password
                                    </button>
                                </div>

                                <div class="relative">
                                    <input type="password" id="manualPasswordInput" name="manual_password" placeholder="Enter custom password (min 6 chars)" 
                                           onkeyup="checkPasswordStrength(this.value)"
                                           class="w-full px-4 py-3 bg-slate-50/70 border border-slate-200 rounded-xl text-xs font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:border-[#10B981] focus:ring-4 focus:ring-emerald-500/10 outline-none transition-all pr-10">
                                    <button type="button" onclick="togglePasswordVisibility('manualPasswordInput')" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </button>
                                </div>

                                <!-- Live Strength Meter -->
                                <div class="space-y-1.5">
                                    <div class="flex justify-between items-center text-[10px] font-bold text-slate-400">
                                        <span>Password Strength</span>
                                        <span id="strengthLabel">Weak</span>
                                    </div>
                                    <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                                        <div id="strengthBar" class="h-full w-1/4 bg-red-500 transition-all duration-300"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ============================================================ -->
                        <!-- 4. ACTION BUTTONS FOOTER                                     -->
                        <!-- ============================================================ -->
                        <div class="flex items-center gap-3 pt-2">
                            <button type="submit" class="px-6 py-3 bg-[#0B2E22] hover:bg-[#093024] text-white text-xs font-bold rounded-xl shadow-xs transition-all active:scale-[0.98] flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#10B981]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
                                Create Staff Account
                            </button>
                            <a href="/brgy-waste-app-v3/public/admin/accounts" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold rounded-xl transition">
                                Cancel
                            </a>
                        </div>

                    </form>

                </div>
            </main>
        </div>
    </div>
</div>

<script>
    function togglePasswordMode(mode) {
        const container = document.getElementById('manualPasswordContainer');
        const input = document.getElementById('manualPasswordInput');
        if (mode === 'manual') {
            container.classList.remove('hidden');
            input.setAttribute('required', 'required');
        } else {
            container.classList.add('hidden');
            input.removeAttribute('required');
        }
    }

    function togglePasswordVisibility(id) {
        const el = document.getElementById(id);
        if (el) {
            el.type = el.type === 'password' ? 'text' : 'password';
        }
    }

    function generateRandomPassword() {
        const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789!@#$';
        let pass = '';
        for (let i = 0; i < 12; i++) {
            pass += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        const input = document.getElementById('manualPasswordInput');
        if (input) {
            input.type = 'text';
            input.value = pass;
            checkPasswordStrength(pass);
        }
    }

    function checkPasswordStrength(val) {
        const bar = document.getElementById('strengthBar');
        const label = document.getElementById('strengthLabel');
        if (!bar || !label) return;

        if (val.length === 0) {
            bar.className = 'h-full w-0 bg-slate-200 transition-all duration-300';
            label.textContent = 'None';
        } else if (val.length < 6) {
            bar.className = 'h-full w-1/4 bg-red-500 transition-all duration-300';
            label.textContent = 'Weak';
        } else if (val.length < 10) {
            bar.className = 'h-full w-2/4 bg-amber-500 transition-all duration-300';
            label.textContent = 'Medium';
        } else {
            bar.className = 'h-full w-full bg-emerald-500 transition-all duration-300';
            label.textContent = 'Strong';
        }
    }
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>