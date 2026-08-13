<?php include __DIR__ . '/../layouts/header.php'; ?>

<!-- Load Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    body, * { font-family: 'Space Grotesk', sans-serif !important; }
    /* Custom Toggle Switch Styling */
    .toggle-checkbox:checked { right: 0; border-color: #10B981; }
    .toggle-checkbox:checked + .toggle-label { background-color: #10B981; }
</style>

<?php
$user = $data['user'] ?? [];
$firstName = isset($user['name']) ? explode(' ', trim($user['name']))[0] : 'User';
$fullName = $user['name'] ?? 'Juan Dela Cruz';
$email = $user['email'] ?? '';
$phone = $user['phone_number'] ?? '';
$address = $user['address'] ?? '';
$position = $user['position_name'] ?? 'Resident';
$role = $user['role_name'] ?? 'Resident';
$purok = $user['purok_name'] ?? 'N/A';
$status = $user['status'] ?? 'pending';
$createdAt = $user['created_at'] ?? 'now';
$formattedDate = date('M d, Y', strtotime($createdAt));
$rawPic = $user['profile_pic'] ?? '';
$profilePic = '';
if (!empty($rawPic)) {
    if (strpos($rawPic, 'http') === 0 || strpos($rawPic, 'data:') === 0 || strpos($rawPic, '/brgy-waste-app-v3') === 0) {
        $profilePic = $rawPic;
    } else {
        $profilePic = '/brgy-waste-app-v3' . (strpos($rawPic, '/') === 0 ? '' : '/') . $rawPic;
    }
}

// Default notification preferences
$notifPrefs = [
    'collection' => true,
    'announcements' => true,
    'reports' => false
];

function getResidentStatusStyle($status) {
    $map = [
        'active'    => ['dot' => '#10B981', 'bg' => 'bg-emerald-500/20', 'text' => 'text-emerald-300', 'border' => 'border-emerald-500/20'],
        'pending'   => ['dot' => '#F59E0B', 'bg' => 'bg-amber-500/20', 'text' => 'text-amber-300', 'border' => 'border-amber-500/20'],
        'suspended' => ['dot' => '#EF4444', 'bg' => 'bg-red-500/20', 'text' => 'text-red-300', 'border' => 'border-red-500/20'],
    ];
    return $map[strtolower($status)] ?? $map['pending'];
}
$statusStyle = getResidentStatusStyle($status);
?>

<div class="min-h-screen bg-[#F8FAFC] w-full font-sans antialiased text-slate-800 flex">
    
    <!-- ============================================================ -->
    <!-- SIDEBAR                                                        -->
    <!-- ============================================================ -->
    <?php include __DIR__ . '/../layouts/resident_sidebar.php'; ?>

    <!-- ============================================================ -->
    <!-- MAIN CONTENT AREA                                             -->
    <!-- ============================================================ -->
    <div class="flex-1 min-w-0">
        
        <!-- ============================================================ -->
        <!-- HERO BANNER – Matches Dashboard Dark Green Theme              -->
        <!-- ============================================================ -->
        <div class="bg-[#0B2E22] pt-8 pb-16 px-6 md:px-10 lg:px-14">
            <div class="max-w-6xl mx-auto">
                <!-- Profile Header Content -->
                <div class="flex flex-col md:flex-row md:items-center gap-6 md:gap-8 mt-2">
                    
                    <!-- Avatar -->
                    <div class="relative flex-shrink-0 self-start md:self-center">
                        <div id="avatarContainer" class="w-24 h-24 md:w-28 md:h-28 rounded-full bg-emerald-500/20 ring-2 ring-white/20 flex items-center justify-center text-white text-5xl font-bold overflow-hidden">
                            <?php if (!empty($profilePic)): ?>
                                <img src="<?php echo htmlspecialchars($profilePic, ENT_QUOTES, 'UTF-8'); ?>" alt="Profile" class="w-full h-full object-cover">
                            <?php else: ?>
                                <?php echo strtoupper(substr($firstName, 0, 1)); ?>
                            <?php endif; ?>
                        </div>
                        <!-- Upload button — label targets the single file input inside the form below -->
                        <label for="profilePicInput"
                               class="absolute -bottom-1 -right-1 flex h-9 w-9 cursor-pointer items-center justify-center rounded-full border-2 border-[#0B2E22] bg-[#10B981] text-white shadow-lg hover:bg-emerald-500 transition-transform hover:scale-105"
                               title="Upload profile picture">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <path d="M17 8 12 3 7 8"/>
                                <path d="M12 3v12"/>
                            </svg>
                        </label>
                    </div>

                    <!-- User Info -->
                    <div class="flex-1 min-w-0">
                        <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight"><?php echo htmlspecialchars($fullName); ?></h1>
                        <p class="text-emerald-200/80 text-sm font-medium mt-1">
                            <?php echo htmlspecialchars($position); ?> · <?php echo htmlspecialchars($purok); ?>
                        </p>
                        <div class="flex flex-wrap items-center gap-3 mt-3">
                            <!-- Status Badge -->
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold border <?php echo $statusStyle['bg']; ?> <?php echo $statusStyle['text']; ?> <?php echo $statusStyle['border']; ?>">
                                <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background: <?php echo $statusStyle['dot']; ?>;"></span>
                                <?php echo ucfirst($status); ?>
                            </span>
                            <!-- Registered Date Badge -->
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-white/10 text-emerald-100/70 border border-white/5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="inline-block"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>
                                <span class="ml-1">Registered: <?php echo $formattedDate; ?></span>
                            </span>
                        </div>
                        <p id="photoPendingNotice" class="hidden mt-2 text-xs font-semibold text-amber-300 leading-tight">New photo selected — click "Save Changes" to apply it.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- MAIN FORM CONTENT – Cards Stack                              -->
        <!-- ============================================================ -->
        <div class="max-w-6xl mx-auto px-4 md:px-8 lg:px-10 -mt-8 relative z-20 pb-32">

            <?php if (!empty($data['error'])): ?>
                <div class="mb-6 rounded-xl bg-red-50 border border-red-200 p-4 text-sm font-semibold text-red-700 flex items-center gap-2 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span><?php echo htmlspecialchars($data['error']); ?></span>
                </div>
            <?php endif; ?>

            <?php if (!empty($data['success'])): ?>
                <div class="mb-6 rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-sm font-semibold text-emerald-800 flex items-center gap-2 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    <span><?php echo htmlspecialchars($data['success']); ?></span>
                </div>
            <?php endif; ?>

            <!-- ============================================================ -->
            <!-- CARD 1: PERSONAL INFORMATION                                 -->
            <!-- ============================================================ -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 md:p-8 mb-6">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M19 8v8"/><path d="M22 12h-6"/></svg>
                            Personal Information
                        </h2>
                        <p class="text-sm text-slate-500 font-medium mt-0.5">Update your profile details here.</p>
                    </div>
                </div>

                <form id="profileForm" action="/brgy-waste-app-v3/public/resident/profile" method="POST" enctype="multipart/form-data" class="space-y-5">
                <!-- File upload input MUST be here for enctype to work -->
                <input id="profilePicInput" type="file" name="profile_pic" accept="image/*" class="hidden" onchange="previewProfilePic(event)">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Full Name -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Full Name</label>
                            <input type="text" name="name" id="profileName" value="<?php echo htmlspecialchars($fullName); ?>" required
                                class="w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-700 outline-none transition focus:border-[#10B981] focus:bg-white focus:ring-4 focus:ring-emerald-500/10">
                            <p id="nameError" class="mt-1 hidden text-xs font-bold text-red-500">Full name is required.</p>
                        </div>
                        <!-- Position -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Position</label>
                            <div class="w-full rounded-lg border border-slate-200 bg-slate-100/70 px-4 py-2.5 text-sm text-slate-600 flex items-center gap-2 cursor-not-allowed">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#10B981]"></span>
                                <?php echo htmlspecialchars($position); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Address -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Address</label>
                        <input type="text" name="address" id="profileAddress" value="<?php echo htmlspecialchars($address); ?>" required
                            class="w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-700 outline-none transition focus:border-[#10B981] focus:bg-white focus:ring-4 focus:ring-emerald-500/10"
                            placeholder="Current Address">
                        <p id="addressError" class="mt-1 hidden text-xs font-bold text-red-500">Address is required.</p>
                    </div>

                    <!-- Contact Information (Email & Contact Number) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Email Address</label>
                            <div class="relative">
                                <input type="email" id="profileEmail" value="<?php echo htmlspecialchars($email); ?>" disabled
                                    class="w-full rounded-lg border border-slate-200 bg-slate-100/70 px-4 py-2.5 text-sm text-slate-600 cursor-not-allowed">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Contact Number</label>
                            <div class="relative">
                                <input type="tel" name="phone_number" id="profilePhone" value="<?php echo htmlspecialchars($phone); ?>" disabled
                                    class="w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm text-slate-700 outline-none cursor-not-allowed transition focus:border-[#10B981] focus:bg-white focus:ring-4 focus:ring-emerald-500/10"
                                    placeholder="09123456789" maxlength="11">
                            </div>
                            <p id="phoneError" class="mt-1 hidden text-xs font-bold text-red-500">Valid Philippine phone number required (11 digits starting with 09).</p>
                        </div>
                    </div>
                    <p class="text-[11px] text-amber-600 font-medium flex items-start gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 mt-0.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        Changing sensitive personal details (name / phone number) requires OTP verification.
                    </p>

                    <!-- Row 3: Metadata Columns -->
                    <div class="col-span-full grid grid-cols-1 sm:grid-cols-3 gap-4 pt-4 border-t border-slate-100">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Account Status</p>
                            <p class="text-sm font-bold text-slate-800 mt-1 capitalize flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full" style="background: <?php echo $statusStyle['dot']; ?>;"></span>
                                <?php echo htmlspecialchars($status); ?>
                            </p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Date Registered</p>
                            <p class="text-sm font-bold text-slate-800 mt-1 font-mono"><?php echo $formattedDate; ?></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Zone / Purok</p>
                            <p class="text-sm font-bold text-slate-800 mt-1"><?php echo htmlspecialchars($purok); ?></p>
                        </div>
                    </div>
                </form>
            </div>

            <!-- ============================================================ -->
            <!-- CARD 2: CHANGE PASSWORD                                     -->
            <!-- ============================================================ -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 md:p-8 mb-6">
                <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    Change Password
                </h2>

                <form id="passwordForm" action="/brgy-waste-app-v3/public/resident/change_password" method="POST" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Current Password -->
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Current Password</label>
                            <div class="relative">
                                <input type="password" name="current_password" id="currentPassword" required
                                    class="w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-2.5 pr-10 text-sm text-slate-700 outline-none transition focus:border-[#10B981] focus:bg-white focus:ring-4 focus:ring-emerald-500/10"
                                    placeholder="••••••••">
                                <button type="button" onclick="togglePassword('currentPassword', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1">
                                    <span class="eye-open block">
                                        <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </span>
                                    <span class="eye-closed hidden">
                                        <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M13.875 18.825A10.05 10.05 0 0 1 12 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 0 1 1.563-3.029m5.858.908a3 3 0 1 1 4.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0 1 12 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 0 1-4.132 5.411m0 0L21 21"/></svg>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- New Password -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">New Password</label>
                        <div class="relative">
                            <input type="password" name="new_password" id="newPassword" required minlength="8"
                                class="w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-2.5 pr-10 text-sm text-slate-700 outline-none transition focus:border-[#10B981] focus:bg-white focus:ring-4 focus:ring-emerald-500/10"
                                placeholder="••••••••"
                                oninput="checkPasswordStrength(this.value)">
                            <button type="button" onclick="togglePassword('newPassword', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1">
                                <span class="eye-open block">
                                    <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </span>
                                <span class="eye-closed hidden">
                                    <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M13.875 18.825A10.05 10.05 0 0 1 12 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 0 1 1.563-3.029m5.858.908a3 3 0 1 1 4.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0 1 12 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 0 1-4.132 5.411m0 0L21 21"/></svg>
                                </span>
                            </button>
                        </div>
                        
                        <!-- Password Strength Indicator -->
                        <div id="passwordStrength" class="mt-2 hidden">
                            <ul class="space-y-0.5 text-xs font-medium">
                                <li id="rule-length" class="flex items-center gap-1.5 text-slate-400"><svg class="w-3.5 h-3.5 shrink-0 stroke-current" fill="none" stroke-width="2.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/></svg> At least 8 characters</li>
                                <li id="rule-upper" class="flex items-center gap-1.5 text-slate-400"><svg class="w-3.5 h-3.5 shrink-0 stroke-current" fill="none" stroke-width="2.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/></svg> One uppercase letter</li>
                                <li id="rule-number" class="flex items-center gap-1.5 text-slate-400"><svg class="w-3.5 h-3.5 shrink-0 stroke-current" fill="none" stroke-width="2.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/></svg> One number &amp; one special character</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Confirm Password</label>
                        <div class="relative">
                            <input type="password" name="confirm_password" id="confirmPassword" required
                                class="w-full rounded-lg border border-slate-200 bg-slate-50 px-4 py-2.5 pr-10 text-sm text-slate-700 outline-none transition focus:border-[#10B981] focus:bg-white focus:ring-4 focus:ring-emerald-500/10"
                                placeholder="••••••••"
                                oninput="validateMatch()">
                            <button type="button" onclick="togglePassword('confirmPassword', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1">
                                <span class="eye-open block">
                                    <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </span>
                                <span class="eye-closed hidden">
                                    <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4"><path d="M13.875 18.825A10.05 10.05 0 0 1 12 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 0 1 1.563-3.029m5.858.908a3 3 0 1 1 4.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0 1 12 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 0 1-4.132 5.411m0 0L21 21"/></svg>
                                </span>
                            </button>
                        </div>
                        <p id="matchError" class="text-red-500 text-xs font-bold mt-1.5 hidden">Passwords do not match.</p>
                    </div>

                    <p class="text-xs text-slate-500 font-medium flex items-start gap-2 pt-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 mt-0.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        A confirmation notification will be sent after the update.
                    </p>
                </form>
            </div>

            <!-- ============================================================ -->
            <!-- CARD 3: NOTIFICATION PREFERENCES                             -->
            <!-- ============================================================ -->
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 md:p-8 mb-6">
                <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                    Notification Preferences
                </h2>

                <div class="space-y-4">
                    <!-- Toggle 1 -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-4 rounded-lg bg-slate-50/80 border border-slate-100">
                        <div>
                            <p class="font-bold text-slate-800 text-sm">Collection day reminders</p>
                            <p class="text-sm text-slate-500">Notified the day before your scheduled pick-up</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                            <input type="checkbox" class="sr-only peer" <?php echo $notifPrefs['collection'] ? 'checked' : ''; ?>>
                            <div class="w-10 h-5 bg-slate-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-emerald-500/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[0.5px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                        </label>
                    </div>

                    <!-- Toggle 2 -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-4 rounded-lg bg-slate-50/80 border border-slate-100">
                        <div>
                            <p class="font-bold text-slate-800 text-sm">Barangay announcements</p>
                            <p class="text-sm text-slate-500">Receive official notices and urgent alerts</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                            <input type="checkbox" class="sr-only peer" <?php echo $notifPrefs['announcements'] ? 'checked' : ''; ?>>
                            <div class="w-10 h-5 bg-slate-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-emerald-500/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[0.5px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                        </label>
                    </div>

                    <!-- Toggle 3 -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-4 rounded-lg bg-slate-50/80 border border-slate-100">
                        <div>
                            <p class="font-bold text-slate-800 text-sm">Report status updates</p>
                            <p class="text-sm text-slate-500">Track when your reports are reviewed/resolved</p>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                            <input type="checkbox" class="sr-only peer" <?php echo $notifPrefs['reports'] ? 'checked' : ''; ?>>
                            <div class="w-10 h-5 bg-slate-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-emerald-500/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[0.5px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- ============================================================ -->
            <!-- BOTTOM ACTION BAR                                             -->
            <!-- ============================================================ -->
            <div class="fixed bottom-0 left-0 right-0 lg:static lg:mt-6 bg-white border-t lg:border lg:rounded-xl lg:border-slate-200 lg:shadow-sm p-4 z-30">
                <div class="max-w-6xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4">
                    
                    <!-- Left: Form validation status (optional) -->
                    <div class="hidden lg:flex text-sm text-slate-400 font-medium items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v4M12 22v-4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M22 12h-4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
                        Ready to save
                    </div>

                    <!-- Right: Buttons -->
                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <!-- Logout Button -->
                        <a href="/brgy-waste-app-v3/public/auth/logout" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-lg border border-slate-200 bg-white text-slate-700 font-bold text-sm hover:bg-slate-50 transition shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                            Logout
                        </a>

                        <!-- Save Changes Button -->
                        <button onclick="saveAllChanges()" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-8 py-2.5 rounded-lg bg-[#0B2E22] hover:bg-[#093024] text-white font-bold text-sm shadow-md transition active:scale-[0.98]">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- MOBILE BOTTOM NAVIGATION                                      -->
<!-- ============================================================ -->
<nav class="lg:hidden fixed bottom-0 left-0 right-0 z-20 bg-white/95 backdrop-blur-sm border-t border-slate-200 px-2 py-2 shadow-[0_-4px_20px_rgba(0,0,0,0.05)] pb-[env(safe-area-inset-bottom)]">
    <div class="mx-auto flex max-w-md items-center justify-between gap-1">
        <a href="/brgy-waste-app-v3/public/resident" class="flex-1 rounded-lg bg-white px-2 py-2 text-center text-[9px] font-semibold text-slate-500">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-1"><rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/></svg>
            Home
        </a>
        <a href="/brgy-waste-app-v3/public/resident/my_report" class="flex-1 rounded-lg bg-white px-2 py-2 text-center text-[9px] font-semibold text-slate-500">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-1"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/></svg>
            Reports
        </a>
        <a href="/brgy-waste-app-v3/public/resident/submit" class="flex-1 rounded-full bg-[#10B981] px-3 py-2.5 text-center text-[9px] font-black text-white shadow-lg shadow-emerald-500/30">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-1"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
            Report
        </a>
        <a href="/brgy-waste-app-v3/public/resident/announcements" class="flex-1 rounded-lg bg-white px-2 py-2 text-center text-[9px] font-semibold text-slate-500">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#475569" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-1"><path d="m3 11 18-5v12L3 13v-2z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>
            News
        </a>
        <a href="/brgy-waste-app-v3/public/resident/profile" class="flex-1 rounded-lg bg-emerald-50 px-2 py-2 text-center text-[9px] font-semibold text-emerald-700">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="mx-auto mb-1"><path d="M20 21a8 8 0 1 0-16 0"/><circle cx="12" cy="8" r="4"/></svg>
            Profile
        </a>
    </div>
</nav>

<!-- ============================================================ -->
<!-- OTP VERIFICATION MODAL                                        -->
<!-- ============================================================ -->
<div id="otpModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6 relative">
        <button onclick="closeOTPModal()" class="absolute top-4 right-4 p-1 rounded-full text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
        <div class="text-center mb-4">
            <div class="mx-auto w-14 h-14 rounded-full bg-emerald-50 flex items-center justify-center mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 8l7.89 5.26a2 2 0 0 0 2.22 0L21 8M5 19h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2z"/></svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900 mb-1">Verify Your Identity</h3>
            <p class="text-sm text-slate-500">We sent a 6-digit code to your registered email.</p>
            <p id="otpEmailDisplay" class="text-xs font-semibold text-slate-700 mt-1"></p>
        </div>
        <div class="mb-4">
            <input type="text" id="otpInput" maxlength="6" placeholder="Enter OTP" class="font-mono w-full px-4 py-3 text-center tracking-widest text-2xl border border-slate-200 rounded-lg focus:border-[#10B981] focus:ring-2 focus:ring-[#10B981]/20 outline-none transition">
        </div>
        <div id="otpError" class="text-red-500 text-sm font-semibold text-center hidden mb-3"></div>
        <div id="otpSuccess" class="text-emerald-500 text-sm font-semibold text-center hidden mb-3"></div>
        <div class="flex gap-3">
            <button onclick="closeOTPModal()" class="flex-1 px-4 py-2.5 border border-slate-200 text-slate-700 rounded-lg font-semibold text-sm hover:bg-slate-50 transition">Cancel</button>
            <button onclick="verifyOTP()" id="verifyOTPBtn" class="flex-1 px-4 py-2.5 bg-[#10B981] text-white rounded-lg font-semibold text-sm hover:bg-emerald-600 transition flex items-center justify-center gap-2">
                <span id="verifyOTPText">Verify</span>
                <svg id="verifyOTPSpinner" class="animate-spin h-4 w-4 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            </button>
        </div>
        <div class="mt-3 text-center">
            <button onclick="resendOTP()" id="resendOTPBtn" class="text-sm text-[#10B981] hover:text-emerald-700 font-semibold">Resend OTP</button>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- JAVASCRIPT                                                   -->
<!-- ============================================================ -->
<script>
    // --- Password Visibility Toggle ---
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const eyeOpen = btn.querySelector('.eye-open');
        const eyeClosed = btn.querySelector('.eye-closed');

        if (input.type === 'password') {
            input.type = 'text';
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        } else {
            input.type = 'password';
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        }
    }

    // --- Password Strength Check ---
    function checkPasswordStrength(val) {
        const container = document.getElementById('passwordStrength');
        if (val.length > 0) {
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
            return;
        }

        const hasUpper = /[A-Z]/.test(val);
        const hasNumber = /[0-9]/.test(val);
        const hasSpecial = /[!@#$%^&*]/.test(val);
        const hasLength = val.length >= 8;

        const rules = {
            'rule-length': hasLength,
            'rule-upper': hasUpper,
            'rule-number': hasNumber && hasSpecial
        };

        Object.keys(rules).forEach(id => {
            const el = document.getElementById(id);
            const svg = el.querySelector('svg');
            const text = el.childNodes[2]; // Gets the text node after the SVG
            
            if (rules[id]) {
                el.className = 'flex items-center gap-1.5 text-emerald-600 font-semibold text-xs';
                svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>';
                svg.classList.add('stroke-emerald-600');
                svg.classList.remove('stroke-slate-400');
            } else {
                el.className = 'flex items-center gap-1.5 text-slate-400 text-xs';
                svg.innerHTML = '<circle cx="12" cy="12" r="10"/>';
                svg.classList.remove('stroke-emerald-600');
                svg.classList.add('stroke-slate-400');
            }
        });

        validateMatch();
    }

    // --- Password Match Validation ---
    function validateMatch() {
        const pass = document.getElementById('newPassword').value;
        const confirm = document.getElementById('confirmPassword').value;
        const error = document.getElementById('matchError');
        const confirmInput = document.getElementById('confirmPassword');

        if (confirm.length > 0 && pass !== confirm) {
            error.classList.remove('hidden');
            confirmInput.classList.add('border-red-400', 'ring-red-100');
            return false;
        } else {
            error.classList.add('hidden');
            confirmInput.classList.remove('border-red-400', 'ring-red-100');
            return pass === confirm && confirm.length > 0;
        }
    }

    // --- Save All Changes ---
    function saveAllChanges() {
        const profileForm = document.getElementById('profileForm');
        const passwordForm = document.getElementById('passwordForm');
        const name = document.getElementById('profileName');
        const address = document.getElementById('profileAddress');
        const phone = document.getElementById('profilePhone');

        // Validate required fields
        let valid = true;
        
        if (!name.value.trim()) {
            document.getElementById('nameError')?.classList.remove('hidden');
            name.classList.add('border-red-400');
            valid = false;
        } else {
            document.getElementById('nameError')?.classList.add('hidden');
            name.classList.remove('border-red-400');
        }
        if (!address.value.trim()) {
            document.getElementById('addressError')?.classList.remove('hidden');
            address.classList.add('border-red-400');
            valid = false;
        } else {
            document.getElementById('addressError')?.classList.add('hidden');
            address.classList.remove('border-red-400');
        }
        if (phone) {
            const phoneVal = phone.value.trim();
            if (!phoneVal || !/^09\d{9}$/.test(phoneVal)) {
                document.getElementById('phoneError')?.classList.remove('hidden');
                phone.classList.add('border-red-400');
                valid = false;
            } else {
                document.getElementById('phoneError')?.classList.add('hidden');
                phone.classList.remove('border-red-400');
            }
        }
        
        if (!valid) return;

        // Validate password if any password field has content
        const currentPass = document.getElementById('currentPassword').value;
        const newPass = document.getElementById('newPassword').value;
        const confirmPass = document.getElementById('confirmPassword').value;

        if (currentPass || newPass || confirmPass) {
            if (!currentPass) {
                alert('Please enter your current password.');
                document.getElementById('currentPassword').focus();
                return;
            }
            if (newPass.length < 8 || !/[A-Z]/.test(newPass) || !/[0-9]/.test(newPass) || !/[!@#$%^&*]/.test(newPass)) {
                alert('New password does not meet the requirements.');
                document.getElementById('newPassword').focus();
                return;
            }
            if (newPass !== confirmPass) {
                alert('Passwords do not match.');
                document.getElementById('confirmPassword').focus();
                return;
            }
            passwordForm.submit();
            return;
        }

        // Sensitive field changes (Name / Phone) go through OTP verification
        const originalName = '<?php echo htmlspecialchars($fullName); ?>';
        const originalPhone = '<?php echo htmlspecialchars($phone); ?>';
        const phoneChanged = phone && phone.value.trim() !== originalPhone;
        const nameChanged = name.value.trim() !== originalName;

        if (nameChanged || phoneChanged) {
            requestOTP();
        } else {
            profileForm.submit();
        }
    }

    // --- Profile picture preview ---
    function previewProfilePic(event) {
        const file = event.target.files[0];
        const notice = document.getElementById('photoPendingNotice');
        if (!file) {
            if (notice) notice.classList.add('hidden');
            return;
        }

        const allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
        const maxSizeBytes = 5 * 1024 * 1024;

        if (!allowedTypes.includes(file.type)) {
            alert('Please choose a JPG, PNG, WEBP, or GIF image.');
            event.target.value = '';
            return;
        }
        if (file.size > maxSizeBytes) {
            alert('Image is too large. Please choose a file under 5MB.');
            event.target.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            const container = document.getElementById('avatarContainer');
            container.innerHTML = `<img src="${e.target.result}" alt="Profile" class="w-full h-full object-cover">`;
            if (notice) notice.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }

    // ============================================================
    // OTP Verification Functions (Profile Changes)
    // ============================================================
    function requestOTP() {
        const name = document.getElementById('profileName')?.value;
        if (!name) {
            alert('Please enter your name.');
            return;
        }

        fetch('/brgy-waste-app-v3/public/resident/requestProfileOTP', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('otpEmailDisplay').textContent = 'OTP sent to your registered email';
                document.getElementById('otpError').classList.add('hidden');
                document.getElementById('otpSuccess').classList.remove('hidden');
                document.getElementById('otpSuccess').textContent = 'OTP sent! Check your email.';
                document.getElementById('otpModal').classList.remove('hidden');
                document.getElementById('otpInput').value = '';
                document.getElementById('verifyOTPText').textContent = 'Verify';
                document.getElementById('verifyOTPSpinner').classList.add('hidden');
            } else {
                alert('Failed to send OTP: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error sending OTP. Please try again.');
        });
    }

    function verifyOTP() {
        const otp = document.getElementById('otpInput').value.trim();
        if (otp.length !== 6) {
            document.getElementById('otpError').textContent = 'Please enter a 6-digit OTP.';
            document.getElementById('otpError').classList.remove('hidden');
            return;
        }

        const btn = document.getElementById('verifyOTPBtn');
        btn.disabled = true;
        document.getElementById('verifyOTPText').textContent = 'Verifying...';
        document.getElementById('verifyOTPSpinner').classList.remove('hidden');
        document.getElementById('otpError').classList.add('hidden');

        fetch('/brgy-waste-app-v3/public/resident/verifyProfileOTP', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'otp=' + encodeURIComponent(otp)
        })
        .then(response => response.json())
        .then(data => {
            btn.disabled = false;
            document.getElementById('verifyOTPText').textContent = 'Verify';
            document.getElementById('verifyOTPSpinner').classList.add('hidden');

            if (data.success) {
                document.getElementById('otpSuccess').textContent = '✓ Verified! Saving changes...';
                document.getElementById('otpSuccess').classList.remove('hidden');
                setTimeout(() => {
                    closeOTPModal();
                    document.getElementById('profileForm').submit();
                }, 1000);
            } else {
                document.getElementById('otpError').textContent = data.message;
                document.getElementById('otpError').classList.remove('hidden');
            }
        })
        .catch(error => {
            btn.disabled = false;
            document.getElementById('verifyOTPText').textContent = 'Verify';
            document.getElementById('verifyOTPSpinner').classList.add('hidden');
            document.getElementById('otpError').textContent = 'Error verifying OTP. Please try again.';
            document.getElementById('otpError').classList.remove('hidden');
        });
    }

    function closeOTPModal() {
        document.getElementById('otpModal').classList.add('hidden');
        document.getElementById('otpError').classList.add('hidden');
        document.getElementById('otpSuccess').classList.add('hidden');
    }

    function resendOTP() {
        document.getElementById('resendOTPBtn').disabled = true;
        document.getElementById('resendOTPBtn').textContent = 'Sending...';
        
        fetch('/brgy-waste-app-v3/public/resident/requestProfileOTP', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('resendOTPBtn').disabled = false;
            document.getElementById('resendOTPBtn').textContent = 'Resend OTP';
            if (data.success) {
                document.getElementById('otpSuccess').textContent = 'New OTP sent! Check your email.';
                document.getElementById('otpSuccess').classList.remove('hidden');
                document.getElementById('otpError').classList.add('hidden');
                document.getElementById('otpInput').value = '';
            } else {
                document.getElementById('otpError').textContent = data.message;
                document.getElementById('otpError').classList.remove('hidden');
            }
        })
        .catch(error => {
            document.getElementById('resendOTPBtn').disabled = false;
            document.getElementById('resendOTPBtn').textContent = 'Resend OTP';
            document.getElementById('otpError').textContent = 'Error resending OTP. Please try again.';
            document.getElementById('otpError').classList.remove('hidden');
        });
    }

    // Intercept profile form submission to trigger OTP if sensitive fields changed
    document.addEventListener('DOMContentLoaded', function() {
        const profileForm = document.getElementById('profileForm');
        if (profileForm) {
            profileForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const originalName = '<?php echo htmlspecialchars($fullName); ?>';
                const currentName = document.getElementById('profileName').value;

                if (currentName !== originalName) {
                    requestOTP();
                } else {
                    this.submit();
                }
            });
        }
    });
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>