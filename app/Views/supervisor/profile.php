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

$error = $data['error'] ?? '';
$success = $data['success'] ?? '';
?>

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
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Supervisor Profile &amp; Security</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Manage your official credentials, contact numbers, and security credentials</p>
            </div>

            <!-- Flash Alerts -->
            <?php if (!empty($success)): ?>
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold flex items-center gap-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                <span><?php echo htmlspecialchars($success); ?></span>
            </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
            <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-800 text-xs font-semibold flex items-center gap-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-red-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>
            <?php endif; ?>

            <!-- Profile Hero Identity Card -->
            <div class="bg-gradient-to-br from-[#07281E] to-[#0B3024] rounded-2xl p-6 sm:p-8 text-white shadow-md flex flex-col sm:flex-row items-center sm:items-start gap-5">
                <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-emerald-600 flex items-center justify-center text-3xl sm:text-4xl font-bold shadow-lg shadow-black/20 shrink-0">
                    <?php echo strtoupper(substr($firstName, 0, 1)); ?>
                </div>
                <div class="text-center sm:text-left space-y-1.5 flex-1 min-w-0">
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                        <h2 class="text-xl sm:text-2xl font-bold tracking-tight truncate"><?php echo htmlspecialchars($fullName); ?></h2>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            <?php echo ucfirst($status); ?>
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-emerald-200/90 font-medium"><?php echo htmlspecialchars($position); ?> · <?php echo htmlspecialchars($purok); ?></p>
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-4 pt-2 text-[11px] text-emerald-100/80 font-mono">
                        <span class="inline-flex items-center gap-1.5"><svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg> <span><?php echo htmlspecialchars($email); ?></span></span>
                        <span class="inline-flex items-center gap-1.5"><svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg> <span>Registered <?php echo $formattedDate; ?></span></span>
                    </div>
                </div>
            </div>

            <!-- Personal Information Form Card -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-2xs space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <h3 class="text-base font-bold text-slate-900">Personal Information</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Update your display name, contact phone number, and physical assignment</p>
                </div>

                <form action="/brgy-waste-app-v3/public/supervisor/profile" method="POST" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Full Name</label>
                            <input type="text" name="name" value="<?php echo htmlspecialchars($fullName); ?>" required class="w-full h-10 px-3.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-900 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10 outline-none transition">
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Official Position</label>
                            <input type="text" value="<?php echo htmlspecialchars($position); ?>" disabled class="w-full h-10 px-3.5 rounded-xl border border-slate-200 bg-slate-50 text-xs sm:text-sm text-slate-500 cursor-not-allowed">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Phone Number (PH)</label>
                            <input type="text" name="phone_number" value="<?php echo htmlspecialchars($phone); ?>" maxlength="11" required placeholder="09XXXXXXXXX" class="w-full h-10 px-3.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-900 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10 outline-none transition">
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Email Address</label>
                            <input type="email" value="<?php echo htmlspecialchars($email); ?>" disabled class="w-full h-10 px-3.5 rounded-xl border border-slate-200 bg-slate-50 text-xs sm:text-sm text-slate-500 cursor-not-allowed">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Office / Residential Address</label>
                        <input type="text" name="address" value="<?php echo htmlspecialchars($address); ?>" required placeholder="Barangay Dulong Bayan" class="w-full h-10 px-3.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-900 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10 outline-none transition">
                    </div>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-xs transition">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Password Security Form Card -->
            <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-2xs space-y-6">
                <div class="border-b border-slate-100 pb-4">
                    <h3 class="text-base font-bold text-slate-900">Account Security &amp; Password</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Ensure your supervisor account is protected with a strong password</p>
                </div>

                <form action="/brgy-waste-app-v3/public/supervisor/change_password" method="POST" class="space-y-4">
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Current Password</label>
                        <input type="password" name="current_password" required placeholder="••••••••" class="w-full h-10 px-3.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-900 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10 outline-none transition">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">New Password</label>
                            <input type="password" name="new_password" id="new_password" required minlength="8" placeholder="••••••••" oninput="checkStrength(this.value)" class="w-full h-10 px-3.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-900 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10 outline-none transition">
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Confirm New Password</label>
                            <input type="password" name="confirm_password" id="confirm_password" required placeholder="••••••••" oninput="checkMatch()" class="w-full h-10 px-3.5 rounded-xl border border-slate-200 text-xs sm:text-sm text-slate-900 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10 outline-none transition">
                        </div>
                    </div>

                    <!-- Password Strength Meter -->
                    <div id="meterBox" class="hidden space-y-1.5 p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <div class="flex items-center justify-between text-[11px]">
                            <span class="text-slate-500">Password Strength:</span>
                            <span id="strengthLabel" class="font-bold text-red-500">Weak</span>
                        </div>
                        <div class="h-1.5 w-full bg-slate-200 rounded-full overflow-hidden">
                            <div id="strengthBar" class="h-full w-0 bg-red-500 transition-all duration-300"></div>
                        </div>
                    </div>

                    <p id="matchMsg" class="hidden text-xs text-red-600 font-semibold">Passwords do not match.</p>

                    <div class="pt-2 flex justify-end">
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#0B2E22] hover:bg-[#07281E] text-white font-semibold text-xs shadow-xs transition">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>

        </main>

    </div>
</div>

<script>
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