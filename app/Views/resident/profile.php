<?php include __DIR__ . '/../layouts/header.php'; ?>

<?php
$user          = $data['user'] ?? [];
$firstName     = isset($user['name']) ? explode(' ', trim($user['name']))[0] : 'User';
$fullName      = $user['name'] ?? 'Juan Dela Cruz';
$email         = $user['email'] ?? '';
$phone         = $user['phone_number'] ?? '';
$address       = $user['address'] ?? '';
$position      = $user['position_name'] ?? 'Resident';
$role          = $user['role_name'] ?? 'Resident';
$purok         = $user['purok_name'] ?? 'Purok 1';
$status        = $user['status'] ?? 'active';
$createdAt     = $user['created_at'] ?? 'now';
$formattedDate = date('M d, Y', strtotime($createdAt));
$memberSince   = date('F Y', strtotime($createdAt));

$rawPic = $user['profile_pic'] ?? '';
$profilePic = !empty($rawPic) ? format_asset_url($rawPic) : '';

function getResidentBadgeStyle($status) {
    switch (strtolower($status)) {
        case 'active':
            return 'bg-emerald-50 text-emerald-800 border-emerald-200';
        case 'pending':
            return 'bg-amber-50 text-amber-800 border-amber-200';
        case 'suspended':
            return 'bg-red-50 text-red-800 border-red-200';
        default:
            return 'bg-slate-100 text-slate-700 border-slate-200';
    }
}
$statusBadge = getResidentBadgeStyle($status);
$initial = strtoupper(substr($firstName, 0, 1));
?>

<style>
    body, * { font-family: 'Miranda Sans', sans-serif !important; font-optical-sizing: auto; }
    
    .input-field {
        width: 100%;
        padding: 0.625rem 0.875rem;
        border-radius: 0.5rem;
        border: 1px solid #cbd5e1;
        background-color: #ffffff;
        font-size: 0.875rem;
        color: #1e293b;
        outline: none;
        transition: all 0.15s ease-in-out;
    }
    .input-field:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.12);
    }
    .input-field:disabled, .input-field.readonly {
        background-color: #f8fafc;
        color: #64748b;
        border-color: #e2e8f0;
        cursor: not-allowed;
    }
    .field-label {
        display: block;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #475569;
        margin-bottom: 0.375rem;
    }
</style>

<div class="flex h-screen bg-slate-50 overflow-hidden w-full">
    <!-- Resident Sidebar -->
    <?php include __DIR__ . '/../layouts/resident_sidebar.php'; ?>

    <!-- Main Content Wrapper -->
    <div class="flex flex-col flex-1 min-w-0 overflow-hidden">
        <!-- Resident Topbar -->
        <?php include __DIR__ . '/../layouts/resident_topbar.php'; ?>

        <!-- Scrollable Main View -->
        <main class="flex-1 overflow-y-auto bg-slate-50 focus:outline-none">
            
            <!-- Cover Header -->
            <div class="bg-[#0B2E22] border-b border-emerald-900 px-4 sm:px-8 pt-8 pb-20">
                <div class="max-w-6xl mx-auto flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 text-emerald-300 text-xs font-bold uppercase tracking-wider mb-1">
                            <span>Resident Portal</span>
                            <span>•</span>
                            <span>Account Settings</span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Resident Profile</h1>
                        <p class="text-xs sm:text-sm text-emerald-200/80 mt-1 font-medium">Manage your contact details, security credentials, and barangay notifications.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="<?php echo app_url('resident/my_report'); ?>" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs border border-white/10 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                            <span>My Reports</span>
                        </a>
                        <a href="<?php echo app_url('auth/logout'); ?>" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-red-600 hover:bg-red-700 text-white font-bold text-xs transition shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                            <span>Sign Out</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Profile Workspace Container (overlapping banner) -->
            <div class="max-w-6xl mx-auto px-4 sm:px-8 -mt-12 pb-16 space-y-6">

                <!-- Alert Messages -->
                <?php if (!empty($data['error'])): ?>
                    <div class="rounded-xl bg-red-50 border border-red-200 p-4 text-xs sm:text-sm font-bold text-red-700 flex items-center gap-3 shadow-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <span><?php echo htmlspecialchars($data['error']); ?></span>
                    </div>
                <?php endif; ?>
                <?php if (!empty($data['success'])): ?>
                    <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-xs sm:text-sm font-bold text-emerald-800 flex items-center gap-3 shadow-xs">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                        <span><?php echo htmlspecialchars($data['success']); ?></span>
                    </div>
                <?php endif; ?>

                <!-- Profile Identity Overview Card -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-7">
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">
                        
                        <!-- Avatar with upload -->
                        <div class="relative shrink-0">
                            <div id="avatarContainer" class="w-24 h-24 sm:w-28 sm:h-28 rounded-full bg-[#0B2E22] ring-4 ring-white shadow-xl flex items-center justify-center text-white text-4xl font-extrabold overflow-hidden">
                                <?php if (!empty($profilePic)): ?>
                                    <img src="<?php echo htmlspecialchars($profilePic, ENT_QUOTES, 'UTF-8'); ?>" alt="Profile Picture" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <span><?php echo $initial; ?></span>
                                <?php endif; ?>
                            </div>
                            <label for="profilePicInput" class="absolute -bottom-1 -right-1 w-9 h-9 rounded-full bg-emerald-600 hover:bg-emerald-500 text-white flex items-center justify-center shadow-md border-2 border-white cursor-pointer transition transform hover:scale-105" title="Change Profile Picture">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                            </label>
                        </div>

                        <!-- Info details -->
                        <div class="flex-1 text-center sm:text-left min-w-0">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div>
                                    <h2 class="text-xl sm:text-2xl font-bold text-slate-900"><?php echo htmlspecialchars($fullName); ?></h2>
                                    <p class="text-sm font-medium text-slate-500 mt-0.5">
                                        Resident · <?php echo htmlspecialchars($purok); ?>
                                    </p>
                                </div>
                                <div class="flex items-center justify-center sm:justify-end gap-2 flex-wrap">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border <?php echo $statusBadge; ?>">
                                        <?php echo ucfirst($status); ?>
                                    </span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                                        Resident
                                    </span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-slate-50 text-slate-600 border border-slate-200">
                                        Registered <?php echo $memberSince; ?>
                                    </span>
                                </div>
                            </div>

                            <p id="photoPendingNotice" class="hidden mt-3 text-xs font-bold text-amber-700 bg-amber-50 p-2.5 rounded-lg border border-amber-200 flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                                <span>New profile photo chosen. Remember to click "Save Profile" below to apply it.</span>
                            </p>

                            <!-- Profile Details Strip -->
                            <div class="mt-5 pt-5 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                                <div>
                                    <span class="text-slate-400 font-bold uppercase tracking-wider block">Registered Email</span>
                                    <span class="text-slate-800 font-semibold text-sm truncate block mt-0.5"><?php echo htmlspecialchars($email ?: '—'); ?></span>
                                </div>
                                <div>
                                    <span class="text-slate-400 font-bold uppercase tracking-wider block">Contact Number</span>
                                    <span class="text-slate-800 font-semibold text-sm block mt-0.5"><?php echo htmlspecialchars($phone ?: 'Not set'); ?></span>
                                </div>
                                <div>
                                    <span class="text-slate-400 font-bold uppercase tracking-wider block">Account Created</span>
                                    <span class="text-slate-800 font-semibold text-sm block mt-0.5"><?php echo $formattedDate; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2-Column Workspace Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <!-- Left Column (2 cols): Personal Info & Change Password -->
                    <div class="lg:col-span-2 space-y-6">

                        <!-- SECTION 1: Personal Details Form -->
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-7">
                            <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-100">
                                <div>
                                    <h3 class="text-base font-bold text-slate-900">Personal Information</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">Update your full name, contact phone number, and address.</p>
                                </div>
                                <span class="text-xs font-semibold text-slate-400">Step 1</span>
                            </div>

                            <form id="profileForm" action="<?php echo app_url('resident/profile'); ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
                                <input id="profilePicInput" type="file" name="profile_pic" accept="image/*" class="hidden" onchange="previewProfilePic(event)">

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <!-- Full Name -->
                                    <div>
                                        <label class="field-label">Full Name</label>
                                        <input type="text" name="name" id="profileName" value="<?php echo htmlspecialchars($fullName); ?>" required class="input-field" placeholder="Enter your full name">
                                        <p id="nameError" class="mt-1 hidden text-xs font-bold text-red-500">Full name is required.</p>
                                    </div>

                                    <!-- Purok / Zone -->
                                    <div>
                                        <label class="field-label">Assigned Purok</label>
                                        <input type="text" value="<?php echo htmlspecialchars($purok); ?>" disabled class="input-field readonly">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <!-- Email -->
                                    <div>
                                        <label class="field-label">Email Address (Read-only)</label>
                                        <input type="email" id="profileEmail" value="<?php echo htmlspecialchars($email); ?>" disabled class="input-field readonly">
                                        <span class="text-[11px] text-slate-400 mt-1 block">Account login email is permanent.</span>
                                    </div>

                                    <!-- Contact Number -->
                                    <div>
                                        <label class="field-label">Contact Number</label>
                                        <input type="tel" name="phone_number" id="profilePhone" value="<?php echo htmlspecialchars($phone); ?>" maxlength="11" class="input-field" placeholder="09XXXXXXXXX">
                                        <p id="phoneError" class="mt-1 hidden text-xs font-bold text-red-500">Must be a valid 11-digit Philippine number.</p>
                                    </div>
                                </div>

                                <!-- Address -->
                                <div>
                                    <label class="field-label">Home Address / Street Location</label>
                                    <input type="text" name="address" id="profileAddress" value="<?php echo htmlspecialchars($address); ?>" class="input-field" placeholder="House #, Street name, Purok">
                                    <p id="addressError" class="mt-1 hidden text-xs font-bold text-red-500">Address is required.</p>
                                </div>

                                <!-- Security Notice -->
                                <div class="bg-amber-50/80 rounded-xl p-3.5 border border-amber-200 flex items-start gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-600 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                    <p class="text-xs text-amber-800 leading-relaxed font-medium">
                                        Modifying your full name or phone number triggers an OTP confirmation code to your registered email for account security.
                                    </p>
                                </div>

                                <div class="pt-2 flex justify-end">
                                    <button type="button" onclick="saveAllChanges()" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-[#0B2E22] hover:bg-[#083528] text-white font-bold text-sm shadow-xs transition active:scale-[0.98] cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                        <span>Save Profile</span>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- SECTION 2: Security & Password Management -->
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sm:p-7">
                            <div class="flex items-center justify-between pb-4 mb-5 border-b border-slate-100">
                                <div>
                                    <h3 class="text-base font-bold text-slate-900">Change Password</h3>
                                    <p class="text-xs text-slate-500 mt-0.5">Ensure your resident account uses a strong passphrase.</p>
                                </div>
                                <span class="text-xs font-semibold text-slate-400">Security</span>
                            </div>

                            <form id="passwordForm" action="<?php echo app_url('resident/change_password'); ?>" method="POST" class="space-y-4">
                                <!-- Current Password -->
                                <div>
                                    <label class="field-label">Current Password</label>
                                    <div class="relative">
                                        <input type="password" name="current_password" id="currentPassword" required class="input-field pr-10" placeholder="••••••••">
                                        <button type="button" onclick="togglePassword('currentPassword', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1 cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <!-- New Password -->
                                    <div>
                                        <label class="field-label">New Password</label>
                                        <div class="relative">
                                            <input type="password" name="new_password" id="newPassword" required minlength="8" class="input-field pr-10" placeholder="••••••••" oninput="checkPasswordStrength(this.value)">
                                            <button type="button" onclick="togglePassword('newPassword', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1 cursor-pointer">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Confirm Password -->
                                    <div>
                                        <label class="field-label">Confirm New Password</label>
                                        <div class="relative">
                                            <input type="password" name="confirm_password" id="confirmPassword" required class="input-field pr-10" placeholder="••••••••" oninput="validateMatch()">
                                            <button type="button" onclick="togglePassword('confirmPassword', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1 cursor-pointer">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                            </button>
                                        </div>
                                        <p id="matchError" class="text-red-500 text-xs font-bold mt-1 hidden">Passwords do not match.</p>
                                    </div>
                                </div>

                                <!-- Password Checklist -->
                                <div class="bg-slate-50 rounded-xl p-3.5 border border-slate-200 space-y-1.5 text-xs text-slate-600">
                                    <p class="font-bold text-slate-700 mb-1">Password Requirements:</p>
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                                        <span id="rule-length" class="flex items-center gap-1.5 text-slate-500"><span>•</span> 8+ characters</span>
                                        <span id="rule-upper" class="flex items-center gap-1.5 text-slate-500"><span>•</span> 1 uppercase letter</span>
                                        <span id="rule-number" class="flex items-center gap-1.5 text-slate-500"><span>•</span> Number &amp; symbol</span>
                                    </div>
                                </div>

                                <div class="pt-2 flex justify-end">
                                    <button type="button" onclick="submitPasswordChange()" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-bold text-sm shadow-xs transition active:scale-[0.98] cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                        <span>Update Password</span>
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>

                    <!-- Right Column (1 col): Preferences & Session Overview -->
                    <div class="space-y-6">

                        <!-- SECTION 3: Notification Alerts -->
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                            <div class="flex items-center justify-between pb-3 mb-4 border-b border-slate-100">
                                <h3 class="text-sm font-bold text-slate-900">Resident Notifications</h3>
                                <span class="text-xs text-emerald-600 font-bold">Preferences</span>
                            </div>

                            <div class="space-y-3 text-xs">
                                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                                    <div>
                                        <p class="font-bold text-slate-800">Collection Reminders</p>
                                        <p class="text-[11px] text-slate-500">Weekly waste schedule alerts</p>
                                    </div>
                                    <input type="checkbox" checked class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                </div>

                                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                                    <div>
                                        <p class="font-bold text-slate-800">Report Status Updates</p>
                                        <p class="text-[11px] text-slate-500">When your reports are verified</p>
                                    </div>
                                    <input type="checkbox" checked class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                </div>

                                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100">
                                    <div>
                                        <p class="font-bold text-slate-800">Barangay Bulletins</p>
                                        <p class="text-[11px] text-slate-500">Urgent waste notices &amp; drives</p>
                                    </div>
                                    <input type="checkbox" checked class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 4: Account & Session Info -->
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-3.5">
                            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                                <h3 class="text-sm font-bold text-slate-900">Account Status</h3>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Verified Resident</span>
                            </div>

                            <div class="space-y-3 text-xs">
                                <div class="flex justify-between items-center">
                                    <span class="text-slate-500">Account Type</span>
                                    <span class="font-bold text-slate-800">Resident</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-slate-500">Home Barangay</span>
                                    <span class="font-bold text-slate-800">Barangay Dulong Bayan</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-slate-500">Registered Purok</span>
                                    <span class="font-bold text-slate-800"><?php echo htmlspecialchars($purok); ?></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-slate-500">Live Clock</span>
                                    <span id="sessionTimeDisplay" class="font-mono font-semibold text-slate-700"><?php echo date('h:i A'); ?></span>
                                </div>
                            </div>

                            <div class="pt-3 border-t border-slate-100">
                                <a href="<?php echo app_url('resident/my_report'); ?>" class="block text-center py-2 px-3 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 text-xs font-bold text-slate-700 transition">
                                    View Submitted Reports →
                                </a>
                            </div>
                        </div>

                    </div>

                </div>

            </div>
        </main>
    </div>
</div>

<!-- OTP Verification Modal -->
<div id="otpModal" class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full overflow-hidden border border-slate-200">
        <div class="bg-[#0B2E22] px-6 py-4 flex items-center justify-between text-white">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-300 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-300" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-white">Security Verification</h3>
                    <p class="text-[11px] text-emerald-300">Enter OTP sent to email</p>
                </div>
            </div>
            <button onclick="closeOTPModal()" class="text-emerald-300 hover:text-white transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <p class="text-xs text-slate-600 text-center">We sent a 6-digit confirmation code to:</p>
            <p id="otpEmailDisplay" class="text-xs font-bold text-slate-900 text-center bg-slate-50 py-2 rounded-lg border border-slate-200"><?php echo htmlspecialchars($email); ?></p>
            <input type="text" id="otpInput" maxlength="6" placeholder="000000" class="font-mono w-full px-4 py-3 text-center tracking-[0.4em] text-2xl font-bold border border-slate-300 rounded-xl focus:border-[#10B981] focus:ring-2 focus:ring-emerald-500/20 outline-none transition">
            <div id="otpError" class="text-red-500 text-xs font-bold text-center hidden"></div>
            <div id="otpSuccess" class="text-emerald-600 text-xs font-bold text-center hidden"></div>
            <div class="grid grid-cols-2 gap-3">
                <button onclick="closeOTPModal()" class="px-4 py-2 border border-slate-200 text-slate-700 rounded-xl font-bold text-xs hover:bg-slate-50 transition cursor-pointer">Cancel</button>
                <button onclick="verifyOTP()" id="verifyOTPBtn" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl font-bold text-xs transition cursor-pointer">Verify &amp; Apply</button>
            </div>
            <div class="text-center">
                <button onclick="resendOTP()" id="resendOTPBtn" class="text-xs text-emerald-600 hover:text-emerald-700 font-bold underline cursor-pointer">Resend Code</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Toggle Password Visibility
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        if (input.type === 'password') {
            input.type = 'text';
            btn.classList.add('text-emerald-600');
        } else {
            input.type = 'password';
            btn.classList.remove('text-emerald-600');
        }
    }

    // Profile Picture Preview
    function previewProfilePic(event) {
        const file = event.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(e) {
            const container = document.getElementById('avatarContainer');
            container.innerHTML = `<img src="${e.target.result}" alt="Profile Preview" class="w-full h-full object-cover">`;
            document.getElementById('photoPendingNotice')?.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }

    // Profile Picture Live Preview
    function previewProfilePic(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const container = document.getElementById('avatarContainer');
                if (container) {
                    container.innerHTML = `<img src="${e.target.result}" alt="Profile Preview" class="w-full h-full object-cover">`;
                }
            };
            reader.readAsDataURL(file);
        }
    }

    // Password strength check
    function checkPasswordStrength(val) {
        const hasLength = val.length >= 8;
        const hasUpper = /[A-Z]/.test(val);
        const hasNumber = /[0-9]/.test(val) && /[!@#$%^&*]/.test(val);

        const updateRule = (id, valid) => {
            const el = document.getElementById(id);
            if (!el) return;
            if (valid) {
                el.className = 'flex items-center gap-1.5 text-emerald-600 font-bold';
            } else {
                el.className = 'flex items-center gap-1.5 text-slate-400';
            }
        };

        updateRule('rule-length', hasLength);
        updateRule('rule-upper', hasUpper);
        updateRule('rule-number', hasNumber);

        validateMatch();
    }

    // Password match validation
    function validateMatch() {
        const pass = document.getElementById('newPassword').value;
        const confirm = document.getElementById('confirmPassword').value;
        const error = document.getElementById('matchError');

        if (confirm.length > 0 && pass !== confirm) {
            error.classList.remove('hidden');
            return false;
        } else {
            error.classList.add('hidden');
            return pass === confirm && confirm.length > 0;
        }
    }

    // Submit Password Change
    function submitPasswordChange() {
        const currentPass = document.getElementById('currentPassword').value;
        const newPass = document.getElementById('newPassword').value;
        const confirmPass = document.getElementById('confirmPassword').value;

        if (!currentPass) { showModalAlert('Please enter your current password.', 'Password Required', 'warning'); return; }
        if (newPass.length < 8) { showModalAlert('New password must be at least 8 characters long.', 'Password Too Short', 'warning'); return; }
        if (newPass !== confirmPass) { showModalAlert('New passwords do not match. Please re-enter them.', 'Password Mismatch', 'error'); return; }

        document.getElementById('passwordForm').submit();
    }

    // Save All Changes (Personal Info)
    function saveAllChanges() {
        const name = document.getElementById('profileName');
        const phone = document.getElementById('profilePhone');
        let valid = true;

        if (!name.value.trim()) {
            document.getElementById('nameError').classList.remove('hidden');
            name.classList.add('border-red-400');
            valid = false;
        } else {
            document.getElementById('nameError').classList.add('hidden');
            name.classList.remove('border-red-400');
        }

        if (phone && phone.value.trim()) {
            if (!/^09\d{9}$/.test(phone.value.trim())) {
                document.getElementById('phoneError').classList.remove('hidden');
                phone.classList.add('border-red-400');
                valid = false;
            } else {
                document.getElementById('phoneError').classList.add('hidden');
                phone.classList.remove('border-red-400');
            }
        }

        if (!valid) return;

        const originalName = '<?php echo htmlspecialchars($fullName, ENT_QUOTES); ?>';
        const originalPhone = '<?php echo htmlspecialchars($phone, ENT_QUOTES); ?>';
        const nameChanged = name.value.trim() !== originalName;
        const phoneChanged = phone && phone.value.trim() !== originalPhone;

        if (nameChanged || phoneChanged) {
            requestOTP();
            return;
        }

        document.getElementById('profileForm').submit();
    }

    // OTP Logic
    function requestOTP() {
        fetch('<?php echo app_url('resident/requestProfileOTP'); ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById('otpModal').classList.remove('hidden');
                document.getElementById('otpInput').focus();
            } else {
                showModalAlert(data.message || 'Failed to send OTP.', 'OTP Error', 'error');
            }
        })
        .catch(() => showModalAlert('An error occurred. Please try again.', 'Connection Error', 'error'));
    }

    function closeOTPModal() {
        document.getElementById('otpModal').classList.add('hidden');
        document.getElementById('otpInput').value = '';
        document.getElementById('otpError').classList.add('hidden');
        document.getElementById('otpSuccess').classList.add('hidden');
    }

    function verifyOTP() {
        const otp = document.getElementById('otpInput').value.trim();
        const btn = document.getElementById('verifyOTPBtn');

        if (otp.length !== 6) {
            document.getElementById('otpError').textContent = 'Please enter the 6-digit code.';
            document.getElementById('otpError').classList.remove('hidden');
            return;
        }

        btn.disabled = true;
        btn.textContent = 'Verifying...';

        fetch('<?php echo app_url('resident/verifyProfileOTP'); ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'otp=' + encodeURIComponent(otp)
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.textContent = 'Verify & Apply';
            if (data.success) {
                document.getElementById('otpSuccess').textContent = 'Verified! Saving profile...';
                document.getElementById('otpSuccess').classList.remove('hidden');
                document.getElementById('otpError').classList.add('hidden');
                setTimeout(() => {
                    closeOTPModal();
                    document.getElementById('profileForm').submit();
                }, 1000);
            } else {
                document.getElementById('otpError').textContent = data.message || 'Invalid or expired code.';
                document.getElementById('otpError').classList.remove('hidden');
            }
        })
        .catch(() => {
            btn.disabled = false;
            btn.textContent = 'Verify & Apply';
            showModalAlert('An error occurred. Please try again.', 'Connection Error', 'error');
        });
    }

    let residentOtpCooldownTimer = null;
    function resendOTP() {
        const btn = document.getElementById('resendOTPBtn');
        btn.disabled = true;
        btn.textContent = 'Sending...';
        requestOTP();
        
        let remaining = 60;
        if (residentOtpCooldownTimer) clearInterval(residentOtpCooldownTimer);

        residentOtpCooldownTimer = setInterval(() => {
            remaining--;
            if (remaining > 0) {
                btn.disabled = true;
                btn.textContent = `Resend Code in (${remaining}s)`;
            } else {
                clearInterval(residentOtpCooldownTimer);
                btn.disabled = false;
                btn.textContent = 'Resend Code';
            }
        }, 1000);
    }

    // Live Clock
    function updateClock() {
        const now = new Date();
        const h = now.getHours() % 12 || 12;
        const m = String(now.getMinutes()).padStart(2, '0');
        const ampm = now.getHours() >= 12 ? 'PM' : 'AM';
        const el = document.getElementById('sessionTimeDisplay');
        if (el) el.textContent = `${h}:${m} ${ampm}`;
    }
    setInterval(updateClock, 1000);
    updateClock();
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>