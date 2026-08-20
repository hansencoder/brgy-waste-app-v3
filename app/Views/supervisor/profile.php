<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php
$user = $data['user'] ?? [];
$fullName = $user['name'] ?? ($_SESSION['user_name'] ?? 'Supervisor User');
$firstName = explode(' ', trim($fullName))[0] ?? 'Supervisor';
$email = $user['email'] ?? '';
$phone = $user['phone_number'] ?? '';
$address = $user['address'] ?? '';
$position = $user['position_name'] ?? 'Barangay Supervisor';
$purok = $user['purok_name'] ?? 'Barangay Wide';
$status = $user['status'] ?? 'active';
$createdAt = $user['created_at'] ?? 'now';
$formattedDate = date('M d, Y', strtotime($createdAt));
$memberSince = date('F Y', strtotime($createdAt));

$rawPic = $user['profile_pic'] ?? '';
$profilePic = !empty($rawPic) ? format_asset_url($rawPic) : '';
$initial = strtoupper(substr($firstName, 0, 1));

$error = $data['error'] ?? '';
$success = $data['success'] ?? '';
?>

<style>
    body, * { font-family: 'Miranda Sans', sans-serif !important; font-optical-sizing: auto; }
</style>

<div class="min-h-screen bg-[#F8FAFC] flex">
    
    <!-- Sidebar -->
    <?php include __DIR__ . '/../layouts/supervisor_sidebar.php'; ?>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
        
        <!-- Topbar -->
        <?php include __DIR__ . '/../layouts/supervisor_topbar.php'; ?>

        <!-- Page Body -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8 space-y-6 max-w-4xl mx-auto w-full">

            <!-- Header -->
            <div>
                <nav class="flex items-center gap-1.5 text-xs font-bold text-slate-500 mb-1">
                    <span>Supervisor</span>
                    <span>/</span>
                    <span class="text-slate-900">Account Settings</span>
                </nav>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Supervisor Profile &amp; Security</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Manage your official credentials, contact numbers, and security credentials</p>
            </div>

            <!-- Flash Alerts -->
            <?php if (!empty($success)): ?>
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    <span><?php echo htmlspecialchars($success); ?></span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-950 font-bold text-xs cursor-pointer">✕</button>
            </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
            <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-xs font-semibold flex items-center justify-between shadow-xs">
                <div class="flex items-center gap-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-red-700 hover:text-red-950 font-bold text-xs cursor-pointer">✕</button>
            </div>
            <?php endif; ?>

            <!-- Profile Hero Identity Card -->
            <div class="bg-gradient-to-br from-[#07281E] to-[#0B3024] rounded-3xl p-6 sm:p-8 text-white shadow-lg flex flex-col sm:flex-row items-center sm:items-start gap-6 relative overflow-hidden">
                <!-- Decorative background elements -->
                <div class="absolute -right-8 -bottom-8 w-44 h-44 rounded-full bg-emerald-500/10 blur-2xl"></div>

                <!-- Avatar Container (Rounded Full) -->
                <div class="relative shrink-0">
                    <div id="avatarContainer" class="w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-emerald-700 ring-4 ring-white/90 shadow-xl flex items-center justify-center text-3xl sm:text-4xl font-extrabold text-white overflow-hidden shrink-0">
                        <?php if (!empty($profilePic)): ?>
                            <img id="avatarImage" src="<?php echo htmlspecialchars($profilePic, ENT_QUOTES, 'UTF-8'); ?>" alt="Profile Picture" class="w-full h-full object-cover">
                        <?php else: ?>
                            <span id="avatarInitial"><?php echo $initial; ?></span>
                        <?php endif; ?>
                    </div>
                    <label for="profilePicInput" class="absolute -bottom-1 -right-1 w-8 h-8 rounded-full bg-emerald-500 hover:bg-emerald-400 text-white flex items-center justify-center shadow-md border-2 border-white cursor-pointer transition transform hover:scale-110" title="Upload profile picture">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                    </label>
                </div>

                <div class="text-center sm:text-left space-y-2 flex-1 min-w-0 relative z-10">
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2.5">
                        <h2 class="text-xl sm:text-2xl font-black tracking-tight truncate"><?php echo htmlspecialchars($fullName); ?></h2>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            <?php echo ucfirst($status); ?>
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-white/10 text-emerald-200 border border-white/10">
                            Supervisor
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-emerald-200/90 font-semibold"><?php echo htmlspecialchars($position); ?> &middot; <?php echo htmlspecialchars($purok); ?></p>
                    
                    <p id="photoPendingNotice" class="hidden text-xs font-bold text-amber-300 bg-amber-950/40 p-2 rounded-xl border border-amber-500/30 flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                        <span>New profile photo chosen. Remember to click "Save Changes" below to apply it.</span>
                    </p>

                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-4 pt-1 text-[11px] text-emerald-100/80 font-mono">
                        <span class="inline-flex items-center gap-1.5"><svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg> <span><?php echo htmlspecialchars($email ?: '—'); ?></span></span>
                        <span class="inline-flex items-center gap-1.5"><svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> <span>Registered <?php echo $formattedDate; ?></span></span>
                    </div>
                </div>
            </div>

            <!-- Personal Information Form Card -->
            <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <h3 class="text-base font-extrabold text-slate-900">Personal Information</h3>
                    <p class="text-xs text-slate-500 font-semibold mt-0.5">Update your display name, contact phone number, and physical assignment</p>
                </div>

                <form action="<?php echo app_url('supervisor/profile'); ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
                    <!-- Hidden file input triggered by avatar camera button -->
                    <input type="file" id="profilePicInput" name="profile_pic" accept="image/*" class="hidden" onchange="previewSupervisorPic(event)">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">Full Name</label>
                            <input type="text" name="name" value="<?php echo htmlspecialchars($fullName); ?>" required class="w-full h-11 px-3.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-semibold text-slate-900 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-600/10 outline-none transition">
                        </div>

                        <div>
                            <label class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">Official Position</label>
                            <input type="text" value="<?php echo htmlspecialchars($position); ?>" disabled class="w-full h-11 px-3.5 rounded-xl border border-slate-200 bg-slate-50 text-xs sm:text-sm font-semibold text-slate-500 cursor-not-allowed">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">Phone Number (PH)</label>
                            <input type="text" name="phone_number" value="<?php echo htmlspecialchars($phone); ?>" maxlength="11" required placeholder="09XXXXXXXXX" class="w-full h-11 px-3.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-semibold text-slate-900 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-600/10 outline-none transition">
                        </div>

                        <div>
                            <label class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">Email Address</label>
                            <input type="email" value="<?php echo htmlspecialchars($email); ?>" disabled class="w-full h-11 px-3.5 rounded-xl border border-slate-200 bg-slate-50 text-xs sm:text-sm font-semibold text-slate-500 cursor-not-allowed">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">Office / Residential Address</label>
                        <input type="text" name="address" value="<?php echo htmlspecialchars($address); ?>" required placeholder="Barangay Dulong Bayan" class="w-full h-11 px-3.5 rounded-xl border border-slate-200 text-xs sm:text-sm font-semibold text-slate-900 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-600/10 outline-none transition">
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold text-xs shadow-xs transition active:scale-[0.98] cursor-pointer">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Password Security Form Card -->
            <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <h3 class="text-base font-extrabold text-slate-900">Account Security &amp; Password</h3>
                    <p class="text-xs text-slate-500 font-semibold mt-0.5">Ensure your supervisor account is protected with a strong password</p>
                </div>

                <form action="<?php echo app_url('supervisor/change_password'); ?>" method="POST" class="space-y-4">
                    <div>
                        <label class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">Current Password</label>
                        <div class="relative">
                            <input type="password" name="current_password" id="current_password" required placeholder="••••••••" class="w-full h-11 pl-3.5 pr-10 rounded-xl border border-slate-200 text-xs sm:text-sm font-semibold text-slate-900 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-600/10 outline-none transition">
                            <button type="button" onclick="togglePasswordVisibility('current_password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">New Password</label>
                            <div class="relative">
                                <input type="password" name="new_password" id="new_password" required minlength="8" placeholder="••••••••" oninput="checkStrength(this.value)" class="w-full h-11 pl-3.5 pr-10 rounded-xl border border-slate-200 text-xs sm:text-sm font-semibold text-slate-900 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-600/10 outline-none transition">
                                <button type="button" onclick="togglePasswordVisibility('new_password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">Confirm New Password</label>
                            <div class="relative">
                                <input type="password" name="confirm_password" id="confirm_password" required placeholder="••••••••" oninput="checkMatch()" class="w-full h-11 pl-3.5 pr-10 rounded-xl border border-slate-200 text-xs sm:text-sm font-semibold text-slate-900 focus:border-emerald-600 focus:ring-4 focus:ring-emerald-600/10 outline-none transition">
                                <button type="button" onclick="togglePasswordVisibility('confirm_password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Password Strength Meter -->
                    <div id="meterBox" class="hidden space-y-1.5 p-3.5 rounded-xl bg-slate-50 border border-slate-200">
                        <div class="flex items-center justify-between text-[11px]">
                            <span class="text-slate-500 font-bold">Password Strength:</span>
                            <span id="strengthLabel" class="font-extrabold text-red-500">Weak</span>
                        </div>
                        <div class="h-1.5 w-full bg-slate-200 rounded-full overflow-hidden">
                            <div id="strengthBar" class="h-full w-0 bg-red-500 transition-all duration-300"></div>
                        </div>
                    </div>

                    <p id="matchMsg" class="hidden text-xs text-red-600 font-bold">Passwords do not match.</p>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#0B2E22] hover:bg-[#07281E] text-white font-extrabold text-xs shadow-xs transition active:scale-[0.98] cursor-pointer">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>

        </main>

    </div>
</div>

<script>
function previewSupervisorPic(event) {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function(e) {
        const container = document.getElementById('avatarContainer');
        container.innerHTML = `<img id="avatarImage" src="${e.target.result}" class="w-full h-full object-cover" alt="Profile Picture">`;
        const notice = document.getElementById('photoPendingNotice');
        if (notice) notice.classList.remove('hidden');
    };
    reader.readAsDataURL(file);
}

function togglePasswordVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    if (input.type === 'password') {
        input.type = 'text';
        btn.classList.add('text-emerald-600');
    } else {
        input.type = 'password';
        btn.classList.remove('text-emerald-600');
    }
}

function checkStrength(val) {
    const box = document.getElementById('meterBox');
    const label = document.getElementById('strengthLabel');
    const bar = document.getElementById('strengthBar');
    if (!val) {
        box.classList.add('hidden');
        return;
    }
    box.classList.remove('hidden');

    let score = 0;
    if (val.length >= 8) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[!@#$%^&*]/.test(val)) score++;

    if (score <= 1) {
        label.textContent = 'Weak';
        label.className = 'font-bold text-red-500';
        bar.className = 'h-full w-1/4 bg-red-500 transition-all';
    } else if (score === 2 || score === 3) {
        label.textContent = 'Moderate';
        label.className = 'font-bold text-amber-500';
        bar.className = 'h-full w-3/4 bg-amber-500 transition-all';
    } else {
        label.textContent = 'Strong';
        label.className = 'font-bold text-emerald-500';
        bar.className = 'h-full w-full bg-emerald-500 transition-all';
    }
    checkMatch();
}

function checkMatch() {
    const p1 = document.getElementById('new_password').value;
    const p2 = document.getElementById('confirm_password').value;
    const msg = document.getElementById('matchMsg');
    if (p2 && p1 !== p2) {
        msg.classList.remove('hidden');
    } else {
        msg.classList.add('hidden');
    }
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>