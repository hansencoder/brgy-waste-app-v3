<?php include __DIR__ . '/../layouts/header.php'; ?>

<!-- Use Tailwind + Inter; keep Material Icons -->
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Miranda+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<style>
    body, * { font-family: 'Miranda Sans', sans-serif !important; font-optical-sizing: auto; }
    .material-icons { font-family: 'Material Icons' !important; font-size: 20px; vertical-align: middle; }
</style>

<div class="min-h-screen w-full grid grid-cols-1 lg:grid-cols-12">
    <!-- ============================================================ -->
    <!-- LEFT PANEL: Brand Identity                                   -->
    <!-- ============================================================ -->
    <div class="hidden lg:flex lg:col-span-6 xl:col-span-6 bg-[#081C15] text-white p-12 flex-col justify-between relative overflow-hidden">
        <div class="relative z-10">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-emerald-500 flex items-center justify-center text-slate-950 font-bold shadow-md shadow-emerald-500/20">
                    <span class="material-icons text-white">shield</span>
                </div>
                <div>
                    <span class="text-base font-bold tracking-tight text-white block leading-none">WasteWatch</span>
                    <span class="text-[11px] font-medium text-emerald-400/80">Municipal Operations</span>
                </div>
            </div>
        </div>

        <div class="relative z-10 my-auto py-12">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-medium mb-6">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Secure Portal</span>
            </div>
            <h2 class="text-3xl xl:text-4xl font-bold tracking-tight text-white leading-tight">
                Secure account<br>recovery.
            </h2>
            <p class="text-slate-400 text-sm mt-4 leading-relaxed max-w-sm">
                Protecting municipal data with advanced encryption protocols. Please verify your new credentials to regain access to the operational dashboard.
            </p>
            
            <div class="grid grid-cols-2 gap-6 mt-10 pt-8 border-t border-slate-800/80">
                <div>
                    <div class="text-xl font-bold text-white tracking-tight">AES-256</div>
                    <div class="text-xs text-slate-400 font-medium mt-0.5">Encryption Standard</div>
                </div>
                <div>
                    <div class="text-xl font-bold text-white tracking-tight">2FA</div>
                    <div class="text-xs text-slate-400 font-medium mt-0.5">Verification Ready</div>
                </div>
            </div>
        </div>

        <div class="relative z-10 border-t border-slate-800/80 pt-6 flex items-center justify-between text-xs text-slate-400">
            <span>(02) 8-123-4567</span>
            <span class="text-slate-600">•</span>
            <span>support@wastewatch.gov</span>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- RIGHT PANEL: Form                                            -->
    <!-- ============================================================ -->
    <div class="lg:col-span-6 xl:col-span-6 flex flex-col justify-center items-center p-6 sm:p-12 lg:p-16 bg-white min-h-screen lg:min-h-0">
        <div class="w-full max-w-md">
            <div class="mb-8">
                <h1 class="text-3xl font-bold tracking-tight text-slate-900">Reset your password</h1>
                <p class="text-sm text-slate-600 mt-2">Choose a strong password to protect your account.</p>
            </div>

            <!-- Error handling from Controller -->
            <?php if (!empty($data['error'])): ?>
                <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200/80 text-red-700 text-xs flex items-start gap-3">
                    <span class="material-icons text-red-500 text-[18px]">error_outline</span>
                    <div class="flex-1"><span class="font-medium"><?php echo htmlspecialchars($data['error'], ENT_QUOTES, 'UTF-8'); ?></span></div>
                </div>
            <?php endif; ?>

            <!-- Kept original action and input names -->
            <form action="/brgy-waste-app-v3/public/index.php?url=auth/processResetPassword" method="POST" class="space-y-6" onsubmit="return validatePasswordForm()">
                <input type="hidden" name="csrf_token" value="<?php echo isset($_SESSION['csrf_token']) ? htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') : bin2hex(random_bytes(32)); ?>">

                <!-- New Password Field -->
                <div>
                    <label for="newPassword" class="block text-xs font-semibold text-slate-700 mb-1.5">New password</label>
                    <div class="relative">
                        <input type="password" id="newPassword" name="new_password" required minlength="8" placeholder="********"
                            class="w-full h-11 px-3.5 pr-10 rounded-lg border border-slate-200 bg-white text-slate-900 text-sm placeholder:text-slate-400 outline-none transition-all focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10"
                            oninput="checkResetPasswordStrength(this.value)">
                        <button type="button" onclick="togglePassword('newPassword', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none transition-colors p-1">
                            <span class="material-icons text-[20px]">visibility</span>
                        </button>
                    </div>
                </div>

                <!-- Confirm Password Field -->
                <div>
                    <label for="confirmPassword" class="block text-xs font-semibold text-slate-700 mb-1.5">Confirm new password</label>
                    <div class="relative">
                        <input type="password" id="confirmPassword" name="confirm_password" required placeholder="********"
                            class="w-full h-11 px-3.5 pr-10 rounded-lg border border-slate-200 bg-white text-slate-900 text-sm placeholder:text-slate-400 outline-none transition-all focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10"
                            oninput="validateMatch()">
                        <button type="button" onclick="togglePassword('confirmPassword', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none transition-colors p-1">
                            <span class="material-icons text-[20px]">visibility</span>
                        </button>
                    </div>
                    <p id="matchError" class="text-red-500 text-xs font-bold mt-1.5 hidden">Passwords do not match.</p>
                </div>

                <!-- Requirements Box -->
                <div id="passwordStrength" class="hidden bg-slate-50 p-4 rounded-lg border border-slate-200">
                    <p class="text-xs font-semibold text-slate-700 mb-2">Password must contain:</p>
                    <ul class="space-y-0.5 text-xs font-medium">
                        <li id="rule-length" class="flex items-center gap-1.5 text-slate-400"><span class="material-icons text-[16px]">check_circle_outline</span> At least 8 characters</li>
                        <li id="rule-upper" class="flex items-center gap-1.5 text-slate-400"><span class="material-icons text-[16px]">check_circle_outline</span> One uppercase letter</li>
                        <li id="rule-number" class="flex items-center gap-1.5 text-slate-400"><span class="material-icons text-[16px]">check_circle_outline</span> One number &amp; one special character</li>
                    </ul>
                </div>

                <button type="submit" class="w-full h-11 bg-slate-900 hover:bg-slate-800 text-white text-sm font-semibold rounded-lg shadow-sm transition-all flex items-center justify-center gap-2 focus:ring-2 focus:ring-slate-900/20 active:scale-[0.99]">
                    <span>Update password</span>
                    <span class="material-icons text-white text-[20px]">arrow_forward</span>
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="/brgy-waste-app-v3/public/index.php?url=auth" class="inline-flex items-center gap-1 text-xs font-semibold text-slate-600 hover:text-slate-900 transition-colors">
                    <span class="material-icons text-[16px]">arrow_back</span>
                    Back to sign in
                </a>
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
        const icon = btn.querySelector('.material-icons');
        if (input.type === 'password') {
            input.type = 'text';
            icon.textContent = 'visibility_off';
        } else {
            input.type = 'password';
            icon.textContent = 'visibility';
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
        
        const rules = { 'rule-length': hasLength, 'rule-upper': hasUpper, 'rule-number': hasNumber && hasSpecial };
        Object.keys(rules).forEach(id => {
            const el = document.getElementById(id);
            const icon = el.querySelector('.material-icons');
            if (rules[id]) {
                el.className = 'flex items-center gap-1.5 text-emerald-600 font-semibold text-xs';
                icon.textContent = 'check_circle';
                icon.style.color = '#10B981';
            } else {
                el.className = 'flex items-center gap-1.5 text-slate-400 text-xs';
                icon.textContent = 'radio_button_unchecked';
                icon.style.color = '';
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