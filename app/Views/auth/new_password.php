<?php include __DIR__ . '/../layouts/header.php'; ?>

<?php
// Branding details
try {
    $authDb = new Database();
    $authDb->query("SELECT system_name, system_short_name, barangay_name, system_logo FROM barangays LIMIT 1");
    $authBranding = $authDb->single();
} catch (Exception $e) {
    $authBranding = null;
}
$barangayName    = $authBranding['barangay_name'] ?? 'Dulong Bayan';
$sysShortName    = $authBranding['system_short_name'] ?? 'WasteWatch';
$sysLogo         = !empty($authBranding['system_logo']) ? format_asset_url($authBranding['system_logo']) : null;
?>

<div class="w-full min-h-[calc(100vh-2rem)] flex-1 flex flex-col justify-center items-center py-10 px-4 sm:px-6">

    <!-- Centered Card -->
    <div class="w-full max-w-[440px] bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs">
        
        <!-- Brand Header -->
        <div class="flex flex-col items-center text-center mb-5">
            <a href="<?php echo app_url(''); ?>" class="inline-block transition hover:opacity-90 mb-3" title="Home">
                <?php if (!empty($sysLogo)): ?>
                    <div class="w-14 h-14 rounded-full flex items-center justify-center overflow-hidden">
                        <img src="<?php echo htmlspecialchars($sysLogo); ?>" class="w-full h-full rounded-full object-cover" alt="Logo">
                    </div>
                <?php else: ?>
                    <div class="w-14 h-14 rounded-full flex items-center justify-center text-emerald-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                <?php endif; ?>
            </a>
            <h1 class="text-lg font-bold text-slate-900 tracking-tight">Create New Password</h1>
            <p class="text-xs text-slate-500 mt-0.5 max-w-xs">Please choose a strong, unique password for your account</p>
        </div>

        <!-- Error/Success Alerts -->
        <?php if (!empty($data['error'])): ?>
            <div class="mb-5 p-3.5 rounded-xl bg-red-50 border border-red-200/80 text-red-700 text-xs flex items-start gap-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 text-red-500 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <div class="flex-1 font-medium"><?php echo htmlspecialchars($data['error'], ENT_QUOTES, 'UTF-8'); ?></div>
            </div>
        <?php endif; ?>

        <form action="<?php echo app_url('index.php?url=auth/updatePassword'); ?>" method="POST" class="space-y-4" onsubmit="return validateForm()">
            <input type="hidden" name="csrf_token" value="<?php echo isset($_SESSION['csrf_token']) ? htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') : bin2hex(random_bytes(32)); ?>">

            <div>
                <label for="password" class="block text-xs font-semibold text-slate-700 mb-1">New Password</label>
                <div class="relative">
                    <input type="password" id="password" name="password" required minlength="8"
                        class="w-full h-10 px-3.5 pr-10 rounded-xl border border-slate-200 bg-white text-slate-900 text-xs sm:text-sm placeholder:text-slate-400 outline-none transition-all focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10"
                        placeholder="••••••••" oninput="checkStrength(this.value); validateMatch();">
                    <button type="button" onclick="togglePasswordVisibility('password', this)" aria-label="Show password" title="Show password" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1 focus:outline-none">
                        <span class="eye-open w-4 h-4">
                            <svg class="w-4 h-4 text-slate-500 stroke-current" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                        </span>
                        <span class="eye-closed hidden w-4 h-4">
                            <svg class="w-4 h-4 text-slate-500 stroke-current" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                        </span>
                    </button>
                </div>
            </div>

            <div>
                <label for="confirm_password" class="block text-xs font-semibold text-slate-700 mb-1">Confirm New Password</label>
                <div class="relative">
                    <input type="password" id="confirm_password" name="confirm_password" required minlength="8"
                        class="w-full h-10 px-3.5 pr-10 rounded-xl border border-slate-200 bg-white text-slate-900 text-xs sm:text-sm placeholder:text-slate-400 outline-none transition-all focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10"
                        placeholder="••••••••" oninput="validateMatch()">
                    <button type="button" onclick="togglePasswordVisibility('confirm_password', this)" aria-label="Show password" title="Show password" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1 focus:outline-none">
                        <span class="eye-open w-4 h-4">
                            <svg class="w-4 h-4 text-slate-500 stroke-current" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                        </span>
                        <span class="eye-closed hidden w-4 h-4">
                            <svg class="w-4 h-4 text-slate-500 stroke-current" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>
                        </span>
                    </button>
                </div>
            </div>

            <!-- Password Rules Indicator -->
            <div id="password-rules-container" class="hidden p-3 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                <ul class="text-[11px] font-medium space-y-0.5">
                    <li id="rule-length" class="password-rule flex items-center gap-1.5 text-slate-400">
                        <svg class="w-3.5 h-3.5 shrink-0 stroke-current" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/></svg>
                        At least 8 characters
                    </li>
                    <li id="rule-upper" class="password-rule flex items-center gap-1.5 text-slate-400">
                        <svg class="w-3.5 h-3.5 shrink-0 stroke-current" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/></svg>
                        One uppercase letter (A-Z)
                    </li>
                    <li id="rule-lower" class="password-rule flex items-center gap-1.5 text-slate-400">
                        <svg class="w-3.5 h-3.5 shrink-0 stroke-current" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/></svg>
                        One lowercase letter (a-z)
                    </li>
                    <li id="rule-number" class="password-rule flex items-center gap-1.5 text-slate-400">
                        <svg class="w-3.5 h-3.5 shrink-0 stroke-current" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/></svg>
                        One number (0-9)
                    </li>
                </ul>
            </div>

            <p id="matchError" class="text-red-500 text-xs font-medium hidden">Passwords do not match.</p>

            <button type="submit" class="w-full h-10 bg-[#0B2E22] hover:bg-[#07281E] text-white text-xs sm:text-sm font-semibold rounded-xl shadow-xs transition flex items-center justify-center gap-2 cursor-pointer">
                <span>Save New Password</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </button>
        </form>

        <div class="mt-5 text-center">
            <a href="<?php echo app_url('index.php?url=auth'); ?>" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-900 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="m12 5-7 7 7 7"/></svg>
                <span>Back to sign in</span>
            </a>
        </div>
    </div>
</div>

<script>
function togglePasswordVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    const eyeOpen = btn.querySelector('.eye-open');
    const eyeClosed = btn.querySelector('.eye-closed');
    if (input.type === 'password') {
        input.type = 'text';
        if (eyeOpen) eyeOpen.classList.add('hidden');
        if (eyeClosed) eyeClosed.classList.remove('hidden');
    } else {
        input.type = 'password';
        if (eyeOpen) eyeOpen.classList.remove('hidden');
        if (eyeClosed) eyeClosed.classList.add('hidden');
    }
}

function checkStrength(val) {
    const container = document.getElementById('password-rules-container');
    if (val.length > 0) {
        container.classList.remove('hidden');
    } else {
        container.classList.add('hidden');
        return;
    }

    const hasUpper = /[A-Z]/.test(val);
    const hasLower = /[a-z]/.test(val);
    const hasNumber = /[0-9]/.test(val);
    const hasLength = val.length >= 8;

    updateRule('rule-upper', hasUpper);
    updateRule('rule-lower', hasLower);
    updateRule('rule-number', hasNumber);
    updateRule('rule-length', hasLength);
}

function updateRule(id, isValid) {
    const el = document.getElementById(id);
    if (!el) return;
    const svg = el.querySelector('svg');
    if (isValid) {
        el.className = 'password-rule flex items-center gap-1.5 text-emerald-600 text-[11px] font-medium';
        if (svg) {
            svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>';
            svg.classList.add('stroke-emerald-600');
            svg.classList.remove('stroke-slate-400');
        }
    } else {
        el.className = 'password-rule flex items-center gap-1.5 text-slate-400 text-[11px] font-medium';
        if (svg) {
            svg.innerHTML = '<circle cx="12" cy="12" r="10"/>';
            svg.classList.remove('stroke-emerald-600');
            svg.classList.add('stroke-slate-400');
        }
    }
}

function validateMatch() {
    const p1 = document.getElementById('password').value;
    const p2 = document.getElementById('confirm_password').value;
    const err = document.getElementById('matchError');
    if (p2 && p1 !== p2) {
        err.classList.remove('hidden');
        return false;
    } else {
        err.classList.add('hidden');
        return true;
    }
}

function validateForm() {
    const p1 = document.getElementById('password').value;
    if (p1.length < 8) {
        alert('Password must be at least 8 characters long.');
        return false;
    }
    return validateMatch();
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>