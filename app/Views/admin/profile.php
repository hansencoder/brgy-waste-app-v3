<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php
/** @var array $data */
$data = $data ?? [];
$user = $data['user'] ?? $user ?? [];
$firstName = isset($user['name']) ? explode(' ', trim($user['name']))[0] : 'Admin';
$fullName = $user['name'] ?? 'Barangay Secretary';
?>

<div class="flex h-screen bg-gray-50 overflow-hidden w-full">
    <!-- Sidebar -->
    <?php include __DIR__ . '/../layouts/admin_sidebar.php'; ?>

    <!-- Main Content Wrapper -->
    <div class="flex flex-col flex-1 w-0 overflow-hidden">

        <!-- Top Nav -->
        <?php include __DIR__ . '/../layouts/admin_topbar.php'; ?>

        <!-- Scrollable Content -->
        <main class="flex-1 relative overflow-y-auto focus:outline-none">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

                <!-- Page Header -->
                <div class="mb-8">
                    <h1 class="text-[28px] font-extrabold text-[#111827] tracking-tight leading-tight mb-1"><?php echo ucfirst($user['role'] ?? 'Admin'); ?> Profile</h1>
                    <p class="text-[15px] text-slate-500 font-medium">Manage your administrative account information and security settings.</p>
                </div>

                <?php if (!empty($data['success'])): ?>
                    <div id="successMsg" class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl shadow-sm mb-6 flex gap-3 text-[14px] font-medium items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        <?php echo htmlspecialchars($data['success']); ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($data['error'])): ?>
                    <div id="errorMsg" class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl shadow-sm mb-6 flex gap-3 text-[14px] font-medium items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        <?php echo htmlspecialchars($data['error']); ?>
                    </div>
                <?php endif; ?>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">

                    <!-- LEFT COLUMN - User Info Card -->
                    <div class="lg:col-span-1">
                        <div class="bg-white border border-gray-200/80 rounded-[20px] p-6 shadow-sm flex flex-col items-center text-center sticky top-8">
                            <!-- Avatar -->
                            <div class="w-[80px] h-[80px] rounded-full bg-[#2A523D]/10 flex items-center justify-center text-[#2A523D] mb-4 border-2 border-[#2A523D]/20">
                                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </div>

                            <!-- Full Name -->
                            <h2 class="text-[18px] font-extrabold text-slate-800 mb-1"><?php echo htmlspecialchars($fullName); ?></h2>

                            <!-- Email -->
                            <p class="text-[14px] text-slate-500 mb-3"><?php echo htmlspecialchars($user['email'] ?? ''); ?></p>

                            <!-- Badge -->
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-50 text-green-700 rounded-full text-[12px] font-bold border border-green-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                <?php echo ucfirst(htmlspecialchars($user['role'] ?? 'Secretary')); ?>
                            </span>

                            <!-- Member Since -->
                            <div class="mt-4 pt-4 border-t border-gray-100 w-full">
                                <div class="text-[12px] text-slate-400 font-medium">System Access Since</div>
                                <div class="text-[14px] font-bold text-slate-700 mt-1"><?php echo date('M d, Y', strtotime($user['created_at'] ?? 'now')); ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN - Forms -->
                    <div class="lg:col-span-2 flex flex-col gap-6">

                        <!-- A. Personal Information Form -->
                        <div class="bg-white border border-gray-200/80 rounded-[20px] p-6 shadow-sm">
                            <h3 class="text-[16px] font-extrabold text-slate-800 mb-1 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-[#2A523D]"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
                                Personal Information
                            </h3>
                            <p class="text-[13px] text-slate-400 font-medium mb-5">Update your administrative details below.</p>

                            <form id="profileForm" action="/brgy-waste-app-v3/public/admin/profile" method="POST" class="space-y-4">

                                <!-- Full Name -->
                                <div>
                                    <label class="block text-[13px] font-bold text-slate-700 mb-1.5">Full Name</label>
                                    <input type="text" name="name" id="profileName" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-[12px] text-[14px] text-slate-700 outline-none focus:bg-white focus:border-[#2A523D] focus:ring-4 focus:ring-[#2A523D]/10 transition-all">
                                    <p id="nameError" class="text-red-500 text-[12px] font-bold mt-1 hidden">Full name is required.</p>
                                </div>

                                <!-- Address -->
                                <div>
                                    <label class="block text-[13px] font-bold text-slate-700 mb-1.5">Address</label>
                                    <input type="text" name="address" id="profileAddress" value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>" required
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-[12px] text-[14px] text-slate-700 outline-none focus:bg-white focus:border-[#2A523D] focus:ring-4 focus:ring-[#2A523D]/10 transition-all">
                                    <p id="addressError" class="text-red-500 text-[12px] font-bold mt-1 hidden">Address is required.</p>
                                </div>

                                <!-- Contact Number -->
                                <div>
                                    <label class="block text-[13px] font-bold text-slate-700 mb-1.5">Contact Number</label>
                                    <input type="tel" name="phone_number" id="profilePhone" value="<?php echo htmlspecialchars($user['phone_number'] ?? ''); ?>" required maxlength="11"
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-[12px] text-[14px] text-slate-700 outline-none focus:bg-white focus:border-[#2A523D] focus:ring-4 focus:ring-[#2A523D]/10 transition-all"
                                        placeholder="09XXXXXXXXX">
                                    <p id="phoneError" class="text-red-500 text-[12px] font-bold mt-1 hidden">Invalid PH phone number (11 digits starting with 09).</p>
                                </div>

                                <!-- Email (Disabled) -->
                                <div>
                                    <label class="block text-[13px] font-bold text-slate-700 mb-1.5">Email Address</label>
                                    <input type="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" disabled
                                        class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-[12px] text-[14px] text-slate-400 cursor-not-allowed">
                                    <p class="text-[11px] text-slate-400 mt-1">Email cannot be changed for security reasons.</p>
                                </div>

                                <!-- Save Button -->
                                <div class="pt-2">
                                    <button type="submit" id="saveProfileBtn" class="w-full bg-[#118B50] hover:bg-[#0e7442] active:scale-[0.99] text-white font-bold py-3 rounded-[12px] shadow-[0_4px_14px_rgba(17,139,80,0.3)] transition-all flex justify-center items-center gap-2 text-[14px]">
                                        <span id="saveBtnText">Save Changes</span>
                                        <svg id="saveSpinner" class="animate-spin h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- B. Change Password Section -->
                        <div class="bg-white border border-gray-200/80 rounded-[20px] p-6 shadow-sm">
                            <h3 class="text-[16px] font-extrabold text-slate-800 mb-1 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-[#2A523D]"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                Change Password
                            </h3>
                            <p class="text-[13px] text-slate-400 font-medium mb-5">Update your administrative password to keep the portal secure.</p>

                            <form id="passwordForm" action="/brgy-waste-app-v3/public/admin/change_password" method="POST" class="space-y-4">

                                <!-- Current Password -->
                                <div>
                                    <label class="block text-[13px] font-bold text-slate-700 mb-1.5">Current Password</label>
                                    <div class="relative">
                                        <input type="password" name="current_password" id="currentPassword" required
                                            class="w-full px-4 py-3 pr-12 bg-gray-50 border border-gray-200 rounded-[12px] text-[14px] text-slate-700 outline-none focus:bg-white focus:border-[#2A523D] focus:ring-4 focus:ring-[#2A523D]/10 transition-all"
                                            placeholder="Enter current password">
                                        <button type="button" onclick="togglePassword('currentPassword', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors p-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="eye-icon"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="eye-off-icon hidden"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- New Password -->
                                <div>
                                    <label class="block text-[13px] font-bold text-slate-700 mb-1.5">New Password</label>
                                    <div class="relative">
                                        <input type="password" name="new_password" id="newPassword" required minlength="8"
                                            class="w-full px-4 py-3 pr-12 bg-gray-50 border border-gray-200 rounded-[12px] text-[14px] text-slate-700 outline-none focus:bg-white focus:border-[#2A523D] focus:ring-4 focus:ring-[#2A523D]/10 transition-all"
                                            placeholder="Enter new password">
                                        <button type="button" onclick="togglePassword('newPassword', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors p-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="eye-icon"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="eye-off-icon hidden"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                                        </button>
                                    </div>

                                    <!-- Password Requirements -->
                                    <div id="passwordRequirements" class="mt-2 space-y-1">
                                        <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Password must contain:</p>
                                        <div id="reqLength" class="flex items-center gap-2 text-[12px] text-slate-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="req-icon"><circle cx="12" cy="12" r="10"/></svg>
                                            At least 8 characters
                                        </div>
                                        <div id="reqUpper" class="flex items-center gap-2 text-[12px] text-slate-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="req-icon"><circle cx="12" cy="12" r="10"/></svg>
                                            One uppercase letter
                                        </div>
                                        <div id="reqNumber" class="flex items-center gap-2 text-[12px] text-slate-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="req-icon"><circle cx="12" cy="12" r="10"/></svg>
                                            One number
                                        </div>
                                        <div id="reqSpecial" class="flex items-center gap-2 text-[12px] text-slate-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="req-icon"><circle cx="12" cy="12" r="10"/></svg>
                                            One special character (!@#$%^&*)
                                        </div>
                                    </div>
                                </div>

                                <!-- Confirm Password -->
                                <div>
                                    <label class="block text-[13px] font-bold text-slate-700 mb-1.5">Confirm Password</label>
                                    <div class="relative">
                                        <input type="password" name="confirm_password" id="confirmPassword" required
                                            class="w-full px-4 py-3 pr-12 bg-gray-50 border border-gray-200 rounded-[12px] text-[14px] text-slate-700 outline-none focus:bg-white focus:border-[#2A523D] focus:ring-4 focus:ring-[#2A523D]/10 transition-all"
                                            placeholder="Re-enter new password">
                                        <button type="button" onclick="togglePassword('confirmPassword', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors p-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="eye-icon"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="eye-off-icon hidden"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                                        </button>
                                    </div>
                                    <p id="confirmError" class="text-red-500 text-[12px] font-bold mt-1 hidden">Passwords do not match.</p>
                                </div>

                                <!-- Change Password Button -->
                                <div class="pt-2">
                                    <button type="submit" id="changePasswordBtn" class="w-full bg-[#118B50] hover:bg-[#0e7442] active:scale-[0.99] text-white font-bold py-3 rounded-[12px] shadow-[0_4px_14px_rgba(17,139,80,0.3)] transition-all flex justify-center items-center gap-2 text-[14px]">
                                        <span id="passwordBtnText">Change Password</span>
                                        <svg id="passwordSpinner" class="animate-spin h-5 w-5 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<script>
    // --- Password Visibility Toggle ---
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const eyeIcon = btn.querySelector('.eye-icon');
        const eyeOffIcon = btn.querySelector('.eye-off-icon');

        if (input.type === 'password') {
            input.type = 'text';
            eyeIcon.classList.add('hidden');
            eyeOffIcon.classList.remove('hidden');
        } else {
            input.type = 'password';
            eyeIcon.classList.remove('hidden');
            eyeOffIcon.classList.add('hidden');
        }
    }

    // --- Password Requirements Validation ---
    const newPassword = document.getElementById('newPassword');
    const confirmPassword = document.getElementById('confirmPassword');

    if (newPassword) {
        newPassword.addEventListener('input', function() {
            const val = this.value;
            // Length check
            updateReqStatus('reqLength', val.length >= 8);
            // Uppercase check
            updateReqStatus('reqUpper', /[A-Z]/.test(val));
            // Number check
            updateReqStatus('reqNumber', /[0-9]/.test(val));
            // Special character check
            updateReqStatus('reqSpecial', /[!@#$%^&*]/.test(val));
        });
    }

    function updateReqStatus(id, isValid) {
        const el = document.getElementById(id);
        const icon = el.querySelector('.req-icon');
        if (isValid) {
            el.classList.remove('text-slate-400');
            el.classList.add('text-green-600');
            icon.innerHTML = '<polyline points="20 6 9 17 4 12"/>';
        } else {
            el.classList.add('text-slate-400');
            el.classList.remove('text-green-600');
            icon.innerHTML = '<circle cx="12" cy="12" r="10"/>';
        }
    }

    // --- Confirm Password Match Check ---
    if (confirmPassword) {
        confirmPassword.addEventListener('input', function() {
            const confirmError = document.getElementById('confirmError');
            if (this.value && this.value !== newPassword.value) {
                confirmError.classList.remove('hidden');
                this.classList.add('border-red-400');
            } else {
                confirmError.classList.add('hidden');
                this.classList.remove('border-red-400');
            }
        });
    }

    // --- Profile Form Validation ---
    document.getElementById('profileForm').addEventListener('submit', function(e) {
        let valid = true;
        const name = document.getElementById('profileName');
        const address = document.getElementById('profileAddress');
        const phone = document.getElementById('profilePhone');

        if (!name.value.trim()) {
            document.getElementById('nameError').classList.remove('hidden');
            name.classList.add('border-red-400');
            valid = false;
        } else {
            document.getElementById('nameError').classList.add('hidden');
            name.classList.remove('border-red-400');
        }

        if (!address.value.trim()) {
            document.getElementById('addressError').classList.remove('hidden');
            address.classList.add('border-red-400');
            valid = false;
        } else {
            document.getElementById('addressError').classList.add('hidden');
            address.classList.remove('border-red-400');
        }

        const phoneRegex = /^09\d{9}$/;
        if (!phoneRegex.test(phone.value)) {
            document.getElementById('phoneError').classList.remove('hidden');
            phone.classList.add('border-red-400');
            valid = false;
        } else {
            document.getElementById('phoneError').classList.add('hidden');
            phone.classList.remove('border-red-400');
        }

        if (!valid) {
            e.preventDefault();
            return;
        }

        const btn = document.getElementById('saveProfileBtn');
        btn.disabled = true;
        btn.classList.add('opacity-80', 'cursor-not-allowed');
        document.getElementById('saveBtnText').textContent = 'Saving...';
        document.getElementById('saveSpinner').classList.remove('hidden');
    });

    // --- Password Form Validation ---
    document.getElementById('passwordForm').addEventListener('submit', function(e) {
        const newPasswordVal = document.getElementById('newPassword').value;
        const confirmPasswordVal = document.getElementById('confirmPassword').value;

        let valid = true;

        if (newPasswordVal !== confirmPasswordVal) {
            document.getElementById('confirmError').classList.remove('hidden');
            document.getElementById('confirmPassword').classList.add('border-red-400');
            valid = false;
        }

        if (newPasswordVal.length < 8 || !/[A-Z]/.test(newPasswordVal) || !/[0-9]/.test(newPasswordVal) || !/[!@#$%^&*]/.test(newPasswordVal)) {
            alert('Password does not meet the requirements.');
            valid = false;
        }

        if (!valid) {
            e.preventDefault();
            return;
        }

        const btn = document.getElementById('changePasswordBtn');
        btn.disabled = true;
        btn.classList.add('opacity-80', 'cursor-not-allowed');
        document.getElementById('passwordBtnText').textContent = 'Changing Password...';
        document.getElementById('passwordSpinner').classList.remove('hidden');
    });

    setTimeout(() => {
        const successMsg = document.getElementById('successMsg');
        const errorMsg = document.getElementById('errorMsg');
        if (successMsg) successMsg.style.display = 'none';
        if (errorMsg) errorMsg.style.display = 'none';
    }, 5000);
</script>
<?php include __DIR__ . '/../layouts/footer.php'; ?>
