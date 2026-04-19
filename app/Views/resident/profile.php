<?php include '../app/Views/layouts/header.php'; ?>
<?php
$user = $data['user'];
$firstName = isset($user['name']) ? explode(' ', trim($user['name']))[0] : 'User';
$fullName = $user['name'] ?? 'Juan Dela Cruz';
?>

<div class="min-h-screen bg-[#f9fafb] w-full font-sans antialiased text-slate-800 flex flex-col">
    <!-- Top Navbar -->
    <nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-[68px]">
                <!-- Left: Logo -->
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-[#2A523D] flex items-center justify-center text-white shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    <span class="font-extrabold text-black text-lg tracking-tight">WasteWatch</span>
                </div>

                <!-- Center: Nav Links -->
                <div class="hidden md:flex items-center justify-center gap-1.5 flex-1">
                    <a href="/brgy-waste-app-v3/public/resident/dashboard" class="flex items-center gap-2 text-slate-500 hover:text-white hover:bg-[#2A523D] px-4 py-2.5 rounded-[12px] font-semibold text-[13.5px] transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="7" x="3" y="3" rx="1.5"/><rect width="7" height="7" x="14" y="3" rx="1.5"/><rect width="7" height="7" x="14" y="14" rx="1.5"/><rect width="7" height="7" x="3" y="14" rx="1.5"/></svg>
                        Home
                    </a>
                    <a href="/brgy-waste-app-v3/public/resident/my_report" class="flex items-center gap-2 text-slate-500 hover:text-white hover:bg-[#2A523D] px-4 py-2.5 rounded-[12px] font-semibold text-[13.5px] transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg>
                        Reports
                    </a>
                    <a href="/brgy-waste-app-v3/public/resident/submit" class="flex items-center gap-2 text-slate-500 hover:text-white hover:bg-[#2A523D] px-4 py-2.5 rounded-[12px] font-semibold text-[13.5px] transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                        Submit Report
                    </a>
                    <a href="/brgy-waste-app-v3/public/resident/announcements" class="flex items-center gap-2 text-slate-500 hover:text-white hover:bg-[#2A523D] px-4 py-2.5 rounded-[12px] font-semibold text-[13.5px] transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 11 18-5v12L3 13v-2z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>
                        News
                    </a>
                </div>

                <!-- Right: Profile -->
                <div class="flex items-center gap-3 md:gap-5">
                    <button onclick="openNotificationPanel()" class="relative p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-50 rounded-full transition-colors hidden md:block">
                        <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                    </button>

                    <div class="h-6 w-px bg-gray-200 hidden md:block"></div>

                    <a href="/brgy-waste-app-v3/public/auth/logout" class="flex items-center gap-2.5 px-3 py-1 rounded-full hover:bg-red-50 transition-colors group">
                        <div class="w-[34px] h-[34px] rounded-full border border-gray-200 flex items-center justify-center bg-gray-50 text-slate-500 shadow-sm group-hover:border-red-200 group-hover:bg-red-50 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                        </div>
                        <span class="text-[13px] font-bold text-slate-700 hidden sm:block group-hover:text-red-600 transition-colors"><?php echo htmlspecialchars($firstName); ?></span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-5xl mx-auto px-4 sm:px-6 py-8 md:py-10 flex-1 w-full pb-20">

        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-[28px] font-extrabold text-[#111827] tracking-tight leading-tight mb-1">My Profile</h1>
            <p class="text-[15px] text-slate-500 font-medium">Manage your account information and security settings.</p>
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
                <div class="bg-white border border-gray-200/80 rounded-[20px] p-6 shadow-sm flex flex-col items-center text-center sticky top-24">
                    <!-- Avatar -->
                    <div class="w-[80px] h-[80px] rounded-full bg-[#118B50]/10 flex items-center justify-center text-[#118B50] mb-4 border-2 border-[#118B50]/20">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>

                    <!-- Full Name -->
                    <h2 class="text-[18px] font-extrabold text-slate-800 mb-1"><?php echo htmlspecialchars($fullName); ?></h2>

                    <!-- Email -->
                    <p class="text-[14px] text-slate-500 mb-3"><?php echo htmlspecialchars($user['email'] ?? ''); ?></p>

                    <!-- Badge -->
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-50 text-green-700 rounded-full text-[12px] font-bold border border-green-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                        Resident
                    </span>

                    <!-- Account Status -->
                    <div class="mt-4 pt-4 border-t border-gray-100 w-full">
                        <div class="text-[12px] text-slate-400 font-medium">Account Status</div>
                        <div class="text-[14px] font-bold text-slate-700 mt-1 capitalize"><?php echo htmlspecialchars($user['status'] ?? 'pending'); ?></div>
                    </div>

                    <!-- Member Since -->
                    <div class="mt-3 pt-3 border-t border-gray-100 w-full">
                        <div class="text-[12px] text-slate-400 font-medium">Member Since</div>
                        <div class="text-[14px] font-bold text-slate-700 mt-1"><?php echo date('M d, Y', strtotime($user['created_at'] ?? 'now')); ?></div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN - Forms -->
            <div class="lg:col-span-2 flex flex-col gap-6">

                <!-- A. Personal Information Form -->
                <div class="bg-white border border-gray-200/80 rounded-[20px] p-6 shadow-sm">
                    <h3 class="text-[16px] font-extrabold text-slate-800 mb-1 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-[#118B50]"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/></svg>
                        Personal Information
                    </h3>
                    <p class="text-[13px] text-slate-400 font-medium mb-5">Update your personal details below.</p>

                    <form id="profileForm" action="/brgy-waste-app-v3/public/resident/profile" method="POST" class="space-y-4">

                        <!-- Full Name -->
                        <div>
                            <label class="block text-[13px] font-bold text-slate-700 mb-1.5">Full Name</label>
                            <input type="text" name="name" id="profileName" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-[12px] text-[14px] text-slate-700 outline-none focus:bg-white focus:border-[#118B50] focus:ring-4 focus:ring-[#118B50]/10 transition-all">
                            <p id="nameError" class="text-red-500 text-[12px] font-bold mt-1 hidden">Full name is required.</p>
                        </div>

                        <!-- Address -->
                        <div>
                            <label class="block text-[13px] font-bold text-slate-700 mb-1.5">Address</label>
                            <input type="text" name="address" id="profileAddress" value="<?php echo htmlspecialchars($user['address'] ?? ''); ?>" required
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-[12px] text-[14px] text-slate-700 outline-none focus:bg-white focus:border-[#118B50] focus:ring-4 focus:ring-[#118B50]/10 transition-all">
                            <p id="addressError" class="text-red-500 text-[12px] font-bold mt-1 hidden">Address is required.</p>
                        </div>

                        <!-- Contact Number -->
                        <div>
                            <label class="block text-[13px] font-bold text-slate-700 mb-1.5">Contact Number</label>
                            <input type="tel" name="phone_number" id="profilePhone" value="<?php echo htmlspecialchars($user['phone_number'] ?? ''); ?>" required maxlength="11"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-[12px] text-[14px] text-slate-700 outline-none focus:bg-white focus:border-[#118B50] focus:ring-4 focus:ring-[#118B50]/10 transition-all"
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
                            <button type="submit" id="saveProfileBtn" class="w-full bg-[#2A523D] hover:bg-[#1e3c2c] active:scale-[0.99] text-white font-bold py-3 rounded-[12px] shadow-[0_4px_14px_rgba(42,82,61,0.3)] transition-all flex justify-center items-center gap-2 text-[14px]">
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
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-[#118B50]"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        Change Password
                    </h3>
                    <p class="text-[13px] text-slate-400 font-medium mb-5">Update your password to keep your account secure.</p>

                    <form id="passwordForm" action="/brgy-waste-app-v3/public/resident/change_password" method="POST" class="space-y-4">

                        <!-- Current Password -->
                        <div>
                            <label class="block text-[13px] font-bold text-slate-700 mb-1.5">Current Password</label>
                            <div class="relative">
                                <input type="password" name="current_password" id="currentPassword" required
                                    class="w-full px-4 py-3 pr-12 bg-gray-50 border border-gray-200 rounded-[12px] text-[14px] text-slate-700 outline-none focus:bg-white focus:border-[#118B50] focus:ring-4 focus:ring-[#118B50]/10 transition-all"
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
                                    class="w-full px-4 py-3 pr-12 bg-gray-50 border border-gray-200 rounded-[12px] text-[14px] text-slate-700 outline-none focus:bg-white focus:border-[#118B50] focus:ring-4 focus:ring-[#118B50]/10 transition-all"
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
                                    class="w-full px-4 py-3 pr-12 bg-gray-50 border border-gray-200 rounded-[12px] text-[14px] text-slate-700 outline-none focus:bg-white focus:border-[#118B50] focus:ring-4 focus:ring-[#118B50]/10 transition-all"
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
                            <button type="submit" id="changePasswordBtn" class="w-full bg-[#2A523D] hover:bg-[#1e3c2c] active:scale-[0.99] text-white font-bold py-3 rounded-[12px] shadow-[0_4px_14px_rgba(42,82,61,0.3)] transition-all flex justify-center items-center gap-2 text-[14px]">
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
    </main>
</div>

<!-- Mobile Bottom Navigation -->
<nav class="md:hidden fixed bottom-0 w-full bg-white/95 backdrop-blur-md border-t border-gray-200/60 pt-2.5 pb-6 px-1 z-50 flex justify-between items-end shadow-[0_-10px_20px_rgba(0,0,0,0.03)]">
    <a href="/brgy-waste-app-v3/public/resident/dashboard" class="flex flex-col items-center flex-1 pb-1 transform active:scale-95 transition-transform group">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="mb-1 group-active:stroke-[#334155]"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
        <span class="text-[10.5px] font-bold tracking-wide text-slate-500">Home</span>
    </a>
    <a href="/brgy-waste-app-v3/public/resident/my_report" class="flex flex-col items-center flex-1 pb-1 transform active:scale-95 transition-transform group">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="mb-1 group-active:stroke-[#334155]"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg>
        <span class="text-[10.5px] font-bold tracking-wide text-slate-500">Reports</span>
    </a>
    <div class="flex-1 flex justify-center sticky z-50">
        <a href="/brgy-waste-app-v3/public/resident/submit" class="flex flex-col items-center relative -top-[22px] group transform active:scale-95 transition-all">
            <div class="w-[58px] h-[58px] rounded-full bg-[#2A523D] flex items-center justify-center border-[5px] border-[#f9fafb] shadow-md text-white mb-1 group-hover:bg-[#1e3c2c]">
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
            </div>
            <span class="text-[10.5px] font-extrabold tracking-wide text-[#2A523D]">Report</span>
        </a>
    </div>
    <a href="/brgy-waste-app-v3/public/resident/announcements" class="flex flex-col items-center flex-1 pb-1 transform active:scale-95 transition-transform group">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#64748b" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="mb-1 group-active:stroke-[#334155]"><path d="m3 11 18-5v12L3 13v-2z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>
        <span class="text-[10.5px] font-bold tracking-wide text-slate-500">News</span>
    </a>
    <a href="/brgy-waste-app-v3/public/resident/profile" class="flex flex-col items-center flex-1 pb-1 transform active:scale-95 transition-transform group">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#2A523D" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="mb-1"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        <span class="text-[10.5px] font-extrabold tracking-wide text-[#2A523D]">Profile</span>
    </a>
</nav>

<?php include '../app/Views/layouts/notification-panel.php'; ?>
<?php include '../app/Views/layouts/footer.php'; ?>

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

        // Name validation
        if (!name.value.trim()) {
            document.getElementById('nameError').classList.remove('hidden');
            name.classList.add('border-red-400');
            valid = false;
        } else {
            document.getElementById('nameError').classList.add('hidden');
            name.classList.remove('border-red-400');
        }

        // Address validation
        if (!address.value.trim()) {
            document.getElementById('addressError').classList.remove('hidden');
            address.classList.add('border-red-400');
            valid = false;
        } else {
            document.getElementById('addressError').classList.add('hidden');
            address.classList.remove('border-red-400');
        }

        // Phone validation (PH format: 11 digits starting with 09)
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

        // Disable button during submit
        const btn = document.getElementById('saveProfileBtn');
        btn.disabled = true;
        btn.classList.add('opacity-80', 'cursor-not-allowed');
        document.getElementById('saveBtnText').textContent = 'Saving...';
        document.getElementById('saveSpinner').classList.remove('hidden');
    });

    // --- Password Form Validation ---
    document.getElementById('passwordForm').addEventListener('submit', function(e) {
        const currentPassword = document.getElementById('currentPassword').value;
        const newPasswordVal = document.getElementById('newPassword').value;
        const confirmPasswordVal = document.getElementById('confirmPassword').value;

        let valid = true;

        // Check password match
        if (newPasswordVal !== confirmPasswordVal) {
            document.getElementById('confirmError').classList.remove('hidden');
            confirmPassword.classList.add('border-red-400');
            valid = false;
        }

        // Check password requirements
        if (newPasswordVal.length < 8 || !/[A-Z]/.test(newPasswordVal) || !/[0-9]/.test(newPasswordVal) || !/[!@#$%^&*]/.test(newPasswordVal)) {
            alert('Password does not meet the requirements.');
            valid = false;
        }

        if (!valid) {
            e.preventDefault();
            return;
        }

        // Disable button during submit
        const btn = document.getElementById('changePasswordBtn');
        btn.disabled = true;
        btn.classList.add('opacity-80', 'cursor-not-allowed');
        document.getElementById('passwordBtnText').textContent = 'Changing Password...';
        document.getElementById('passwordSpinner').classList.remove('hidden');
    });

    // Auto-hide success/error messages after 5 seconds
    setTimeout(() => {
        const successMsg = document.getElementById('successMsg');
        const errorMsg = document.getElementById('errorMsg');
        if (successMsg) successMsg.style.display = 'none';
        if (errorMsg) errorMsg.style.display = 'none';
    }, 5000);
</script>
