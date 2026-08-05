<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="flex-grow flex items-center justify-center p-6">
    <div class="glassmorphism rounded-2xl p-8 max-w-sm w-full shadow-2xl fade-in text-center relative overflow-hidden">
        <div class="relative z-10">
            <div class="mx-auto w-16 h-16 bg-[#15281f]/10 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-[#15281f]" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
            </div>

            <h2 class="text-2xl font-bold text-foreground mb-2">Create New Password</h2>
            <p class="text-muted-foreground text-sm mb-6">Enter a strong, secure password for your account.</p>

            <?php if (!empty($data['error'])): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-3 mb-6 rounded-md text-sm text-left">
                    <p><?php echo htmlspecialchars($data['error']); ?></p>
                </div>
            <?php endif; ?>

            <form action="/brgy-waste-app-v3/public/index.php?url=auth/processResetPassword" method="POST" class="space-y-4" onsubmit="return validatePasswordForm()">
                <input type="hidden" name="csrf_token" value="<?php echo isset($_SESSION['csrf_token']) ? htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') : bin2hex(random_bytes(32)); ?>">
                <input type="hidden" name="otp" value="<?php echo htmlspecialchars($_SESSION['reset_otp'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">

                <!-- New Password Field -->
                <div>
                    <label class="block text-sm font-medium text-foreground text-left mb-1.5">New Password</label>
                    <div class="relative">
                        <input type="password" id="newPassword" name="new_password" required minlength="8"
                            class="w-full px-4 py-3 pr-12 rounded-lg border border-border focus:ring-2 focus:ring-primary outline-none bg-background"
                            placeholder="••••••••"
                            oninput="checkResetPasswordStrength(this.value)">
                        
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
                            <span id="strengthText" class="text-xs font-bold text-slate-400 min-w-[32px] text-right">Weak</span>
                        </div>
                        <ul class="mt-2 space-y-0.5 text-xs font-medium text-left">
                            <li id="rule-length" class="flex items-center gap-1.5 text-slate-400"><svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg> At least 8 characters</li>
                            <li id="rule-upper" class="flex items-center gap-1.5 text-slate-400"><svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg> One uppercase letter</li>
                            <li id="rule-number" class="flex items-center gap-1.5 text-slate-400"><svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg> One number &amp; one special character</li>
                        </ul>
                    </div>
                </div>

                <!-- Confirm Password Field -->
                <div>
                    <label class="block text-sm font-medium text-foreground text-left mb-1.5">Confirm Password</label>
                    <div class="relative">
                        <input type="password" id="confirmPassword" name="confirm_password" required
                            class="w-full px-4 py-3 pr-12 rounded-lg border border-border focus:ring-2 focus:ring-primary outline-none bg-background"
                            placeholder="••••••••"
                            oninput="validateMatch()">
                        
                        <button type="button" onclick="togglePassword('confirmPassword', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors p-1">
                            <svg class="w-5 h-5 eye-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg class="w-5 h-5 eye-off-icon hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                    <p id="matchError" class="text-red-500 text-xs font-bold mt-1.5 hidden">Passwords do not match.</p>
                </div>

                <button type="submit" class="w-full bg-[#15281f] hover:bg-[#0f1a17] text-white font-semibold py-3 px-4 rounded-lg shadow-md transition mt-2">
                    Reset Password
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-slate-500">
                    <a href="/brgy-waste-app-v3/public/index.php?url=auth" class="text-[#15281f] font-semibold hover:underline">
                        &larr; Back to Login
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- JAVASCRIPT                                                    -->
<!-- ============================================================ -->
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

    // --- Password Strength Check ---
    function checkResetPasswordStrength(val) {
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

        const rules = { 'rule-length': hasLength, 'rule-upper': hasUpper, 'rule-number': hasNumber && hasSpecial };
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
            text.textContent = 'Weak'; text.className = 'text-xs font-bold text-red-500 min-w-[32px] text-right';
        } else if (score <= 1) {
            bar.style.width = '33%';
            bar.className = 'h-full w-[33%] bg-red-400 transition-all duration-300 rounded-full';
            text.textContent = 'Weak'; text.className = 'text-xs font-bold text-red-500 min-w-[32px] text-right';
        } else if (score === 2) {
            bar.style.width = '66%';
            bar.className = 'h-full w-[66%] bg-orange-400 transition-all duration-300 rounded-full';
            text.textContent = 'Fair'; text.className = 'text-xs font-bold text-orange-500 min-w-[32px] text-right';
        } else {
            bar.style.width = '100%';
            bar.className = 'h-full w-full bg-emerald-500 transition-all duration-300 rounded-full';
            text.textContent = 'Strong'; text.className = 'text-xs font-bold text-emerald-500 min-w-[32px] text-right';
        }
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

    // --- Final Form Validation ---
    function validatePasswordForm() {
        const pass = document.getElementById('newPassword').value;
        if (pass.length < 8 || !/[A-Z]/.test(pass) || !/[0-9]/.test(pass) || !/[!@#$%^&*]/.test(pass)) {
            alert('New password does not meet the security requirements.');
            document.getElementById('newPassword').focus();
            return false;
        }
        return validateMatch();
    }
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>