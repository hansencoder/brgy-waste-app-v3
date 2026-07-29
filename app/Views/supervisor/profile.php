<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php
$user = $data['user'] ?? [];
$firstName = isset($user['name']) ? explode(' ', trim($user['name']))[0] : 'Supervisor';
$fullName = $user['name'] ?? 'Supervisor User';
$email = $user['email'] ?? '';
$phone = $user['phone_number'] ?? '';
$address = $user['address'] ?? '';
$position = $user['position_name'] ?? 'Supervisor';
$role = $user['role_name'] ?? 'Supervisor';
$purok = $user['purok_name'] ?? 'N/A';
$status = $user['status'] ?? 'active';
$createdAt = $user['created_at'] ?? 'now';
$formattedDate = date('M d, Y', strtotime($createdAt));
?>

<div class="min-h-screen bg-[#F8FAFC] w-full font-sans antialiased text-slate-800 flex">
    
    <!-- SIDEBAR -->
    <?php include __DIR__ . '/../layouts/supervisor_sidebar.php'; ?>

    <!-- MAIN CONTENT -->
    <div class="flex-1 min-w-0">

        <!-- HERO BANNER -->
        <div class="relative bg-gradient-to-br from-[#07281E] via-[#0B3024] to-[#10B981]/80 pt-8 pb-20 px-6 md:px-10 lg:px-14 overflow-hidden">
            <div class="absolute inset-0 opacity-5 pointer-events-none">
                <div class="absolute top-10 left-10 w-72 h-72 rounded-full bg-white/5 blur-2xl"></div>
                <div class="absolute bottom-10 right-10 w-96 h-96 rounded-full bg-emerald-400/10 blur-3xl"></div>
            </div>
            <div class="relative z-10 max-w-6xl mx-auto">
                <div class="flex items-center gap-3 mb-4">
                    <span class="text-[11px] font-bold uppercase tracking-[0.35em] text-emerald-300">Profile</span>
                    <span class="h-px flex-1 max-w-20 bg-emerald-500/30"></span>
                </div>
                <div class="flex flex-col md:flex-row md:items-center gap-6 md:gap-8">
                    <div class="relative flex-shrink-0">
                        <div class="w-24 h-24 md:w-28 md:h-28 rounded-2xl bg-[#0D9488] flex items-center justify-center text-white text-4xl font-bold shadow-lg shadow-emerald-900/30">
                            <?php echo strtoupper(substr($firstName, 0, 1)); ?>
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-emerald-400 border-2 border-[#0B3024] shadow-lg shadow-emerald-500/30"></div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h1 class="text-3xl md:text-4xl font-extrabold text-white tracking-tight"><?php echo htmlspecialchars($fullName); ?></h1>
                        <p class="text-emerald-200/80 text-sm md:text-base mt-1 font-medium">
                            <?php echo htmlspecialchars($position); ?> · <?php echo htmlspecialchars($purok); ?>
                        </p>
                        <div class="flex flex-wrap items-center gap-3 mt-3">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-500/20 text-emerald-100 border border-emerald-500/20">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                <?php echo ucfirst($status); ?>
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-white/10 text-emerald-100/70 border border-white/5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4"/><path d="M8 2v4"/><path d="M3 10h18"/></svg>
                                Registered: <?php echo $formattedDate; ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FORM CONTENT -->
        <div class="max-w-6xl mx-auto px-4 md:px-8 lg:px-10 -mt-6 relative z-20 pb-32">

            <!-- Personal Information -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-[0_18px_45px_-30px_rgba(15,23,42,0.2)] p-6 md:p-8 mb-6">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M19 8v8"/><path d="M22 12h-6"/></svg>
                            Personal Information
                        </h2>
                        <p class="text-sm text-slate-500 font-medium mt-0.5">Full Name · Position · Contact · Account Status</p>
                    </div>
                </div>

                <form id="profileForm" action="/brgy-waste-app-v3/public/index.php?url=supervisor/profile" method="POST" class="space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-[0.2em] text-slate-500 mb-1.5">Full Name</label>
                            <input type="text" name="name" id="profileName" value="<?php echo htmlspecialchars($fullName); ?>" required
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-[15px] text-slate-700 outline-none transition-all focus:border-[#10B981] focus:bg-white focus:ring-4 focus:ring-emerald-100">
                            <p id="nameError" class="mt-1 hidden text-[12px] font-bold text-red-500">Full name is required.</p>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-[0.2em] text-slate-500 mb-1.5">Position</label>
                            <div class="w-full rounded-xl border border-slate-200 bg-slate-100/70 px-4 py-3 text-[15px] text-slate-600 flex items-center gap-2 cursor-not-allowed">
                                <span class="w-2 h-2 rounded-full bg-[#10B981]"></span>
                                <?php echo htmlspecialchars($position); ?>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-[0.2em] text-slate-500 mb-1.5">Contact Information</label>
                        <input type="text" name="phone_number" id="profilePhone" value="<?php echo htmlspecialchars($phone); ?>" required maxlength="11"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-[15px] text-slate-700 outline-none transition-all focus:border-[#10B981] focus:bg-white focus:ring-4 focus:ring-emerald-100"
                            placeholder="09XXXXXXXXX">
                        <p id="phoneError" class="mt-1 hidden text-[12px] font-bold text-red-500">Invalid PH phone number (11 digits starting with 09).</p>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-[0.2em] text-slate-500 mb-1.5">Email Address</label>
                        <input type="email" value="<?php echo htmlspecialchars($email); ?>" disabled
                            class="w-full rounded-xl border border-slate-200 bg-slate-100/70 px-4 py-3 text-[15px] text-slate-600 cursor-not-allowed">
                        <p class="text-[11px] text-slate-400 mt-1">Email cannot be changed for security reasons.</p>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-[0.2em] text-slate-500 mb-1.5">Address</label>
                        <input type="text" name="address" id="profileAddress" value="<?php echo htmlspecialchars($address); ?>" required
                            class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-[15px] text-slate-700 outline-none transition-all focus:border-[#10B981] focus:bg-white focus:ring-4 focus:ring-emerald-100"
                            placeholder="Barangay Dulong Bayan">
                        <p id="addressError" class="mt-1 hidden text-[12px] font-bold text-red-500">Address is required.</p>
                    </div>
                    <div class="pt-2">
                        <button type="submit" class="w-full bg-[#10B981] hover:bg-emerald-600 text-white font-bold py-3 rounded-xl shadow-lg shadow-emerald-500/20 transition flex items-center justify-center gap-2 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Change Password -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-[0_18px_45px_-30px_rgba(15,23,42,0.2)] p-6 md:p-8 mb-6">
                <h2 class="text-lg font-extrabold text-slate-900 flex items-center gap-2 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#10B981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    Change Password
                </h2>

                <form id="passwordForm" action="/brgy-waste-app-v3/public/index.php?url=supervisor/change_password" method="POST" class="space-y-5">
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-[0.2em] text-slate-500 mb-1.5">Current Password</label>
                        <div class="relative">
                            <input type="password" name="current_password" id="currentPassword" required
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 pr-12 text-[15px] text-slate-700 outline-none transition-all focus:border-[#10B981] focus:bg-white focus:ring-4 focus:ring-emerald-100"
                                placeholder="••••••••">
                            <button type="button" onclick="togglePassword('currentPassword', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors p-1">
                                <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg class="w-5 h-5 eye-off-icon hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-[0.2em] text-slate-500 mb-1.5">New Password</label>
                        <div class="relative">
                            <input type="password" name="new_password" id="newPassword" required minlength="8"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 pr-12 text-[15px] text-slate-700 outline-none transition-all focus:border-[#10B981] focus:bg-white focus:ring-4 focus:ring-emerald-100"
                                placeholder="••••••••"
                                oninput="checkPasswordStrength(this.value)">
                            <button type="button" onclick="togglePassword('newPassword', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors p-1">
                                <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg class="w-5 h-5 eye-off-icon hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
                        </div>
                        <div id="passwordStrength" class="mt-2 hidden">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 h-1.5 bg-slate-200 rounded-full overflow-hidden">
                                    <div id="strengthBar" class="h-full w-0 bg-red-400 transition-all duration-300 rounded-full"></div>
                                </div>
                                <span id="strengthText" class="text-xs font-bold text-slate-400 min-w-[40px] text-right">Weak</span>
                            </div>
                            <ul class="mt-2 space-y-1 text-xs font-medium">
                                <li id="rule-length" class="flex items-center gap-1.5 text-slate-400"><svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg> At least 8 characters</li>
                                <li id="rule-upper" class="flex items-center gap-1.5 text-slate-400"><svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg> One uppercase letter</li>
                                <li id="rule-number" class="flex items-center gap-1.5 text-slate-400"><svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg> One number & one special character</li>
                            </ul>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold uppercase tracking-[0.2em] text-slate-500 mb-1.5">Confirm Password</label>
                        <div class="relative">
                            <input type="password" name="confirm_password" id="confirmPassword" required
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 pr-12 text-[15px] text-slate-700 outline-none transition-all focus:border-[#10B981] focus:bg-white focus:ring-4 focus:ring-emerald-100"
                                placeholder="••••••••"
                                oninput="validateMatch()">
                            <button type="button" onclick="togglePassword('confirmPassword', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors p-1">
                                <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg class="w-5 h-5 eye-off-icon hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                            </button>
                        </div>
                        <p id="matchError" class="text-red-500 text-xs font-bold mt-1.5 hidden">Passwords do not match.</p>
                    </div>
                    <div class="pt-2">
                        <button type="submit" class="w-full bg-[#10B981] hover:bg-emerald-600 text-white font-bold py-3 rounded-xl shadow-lg shadow-emerald-500/20 transition flex items-center justify-center gap-2 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            Change Password
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
// Password Visibility Toggle
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

// Password Strength
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
    const score = [hasLength, hasUpper, hasNumber && hasSpecial].filter(Boolean).length;
    const bar = document.getElementById('strengthBar');
    const text = document.getElementById('strengthText');
    const rules = {
        'rule-length': hasLength,
        'rule-upper': hasUpper,
        'rule-number': hasNumber && hasSpecial
    };
    Object.keys(rules).forEach(id => {
        const el = document.getElementById(id);
        const icon = el.querySelector('svg');
        if (rules[id]) {
            el.className = 'flex items-center gap-1.5 text-emerald-600 font-semibold text-xs';
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>';
            icon.className = 'w-3 h-3 shrink-0';
        } else {
            el.className = 'flex items-center gap-1.5 text-slate-400 text-xs';
            icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>';
            icon.className = 'w-3 h-3 shrink-0';
        }
    });
    if (score === 0) {
        bar.style.width = '0%';
        bar.className = 'h-full w-0 bg-red-400 transition-all duration-300 rounded-full';
        text.textContent = 'Weak';
        text.className = 'text-xs font-bold text-red-500 min-w-[40px] text-right';
    } else if (score <= 1) {
        bar.style.width = '33%';
        bar.className = 'h-full w-[33%] bg-red-400 transition-all duration-300 rounded-full';
        text.textContent = 'Weak';
        text.className = 'text-xs font-bold text-red-500 min-w-[40px] text-right';
    } else if (score === 2) {
        bar.style.width = '66%';
        bar.className = 'h-full w-[66%] bg-orange-400 transition-all duration-300 rounded-full';
        text.textContent = 'Fair';
        text.className = 'text-xs font-bold text-orange-500 min-w-[40px] text-right';
    } else {
        bar.style.width = '100%';
        bar.className = 'h-full w-full bg-emerald-500 transition-all duration-300 rounded-full';
        text.textContent = 'Strong';
        text.className = 'text-xs font-bold text-emerald-500 min-w-[40px] text-right';
    }
    validateMatch();
}

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
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>