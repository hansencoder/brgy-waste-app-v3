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
$sysLogo         = format_asset_url($authBranding['system_logo'] ?? '');
?>

<div class="w-full min-h-[calc(100vh-2rem)] flex-1 flex flex-col justify-center items-center py-10 px-4 sm:px-6">

    <!-- Top Back Link -->
    <div class="w-full max-w-[440px] mb-4 flex items-center justify-between">
        <a href="<?php echo app_url(''); ?>" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-900 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 12H5" />
                <path d="m12 5-7 7 7 7" />
            </svg>
            <span>Back to home</span>
        </a>
    </div>

    <!-- Centered Card -->
    <div class="w-full max-w-[440px] bg-white rounded-3xl border border-slate-200 p-6 sm:p-8 shadow-xs">
        
        <!-- Brand Header -->
        <div class="flex flex-col items-center text-center mb-5">
            <a href="<?php echo app_url(''); ?>" class="inline-block transition hover:opacity-90" title="Home">
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
        </div>

        <!-- Navigation Switcher (Tabs) -->
        <div class="mb-5 p-1 bg-slate-100 rounded-xl grid grid-cols-2 gap-1 text-xs font-semibold">
            <a href="<?php echo app_url('index.php?url=auth'); ?>" class="py-2 text-center rounded-lg bg-white text-slate-900 shadow-2xs transition-all">
                Sign In
            </a>
            <a href="<?php echo app_url('index.php?url=auth/register'); ?>" class="py-2 text-center rounded-lg text-slate-600 hover:text-slate-900 transition-all">
                Create Account
            </a>
        </div>

        <!-- Server Error Alert -->
        <?php if (!empty($data['error'])): ?>
            <div class="mb-5 p-3.5 rounded-xl bg-red-50 border border-red-200/80 text-red-700 text-xs flex items-start gap-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 text-red-500 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <div class="flex-1 font-medium">
                    <?php echo htmlspecialchars($data['error'], ENT_QUOTES, 'UTF-8'); ?>
                    <?php if (isset($data['lockout_seconds']) && $data['lockout_seconds'] > 0): ?>
                        <span id="loginCountdown" class="font-bold ml-1"></span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Server Success Alert -->
        <?php if (!empty($data['success'])): ?>
            <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200/80 text-emerald-800 text-xs flex items-start gap-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 text-emerald-600 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                <div class="flex-1 font-medium">
                    <?php echo htmlspecialchars($data['success'], ENT_QUOTES, 'UTF-8'); ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Server Warning Alert (e.g. Suspended Account or Maintenance Mode) -->
        <?php if (!empty($data['warning'])): ?>
            <?php
                $isMaintNotice = (stripos($data['warning'], 'maintenance') !== false);
                $noticeTitle = $isMaintNotice ? 'System Under Maintenance' : 'Account Notice';
            ?>
            <div class="mb-5 p-4 rounded-2xl <?php echo $isMaintNotice ? 'bg-orange-50 border-2 border-orange-300 text-orange-950' : 'bg-amber-50 border-2 border-amber-300 text-amber-950'; ?> text-xs flex items-start gap-3 shadow-xs">
                <div class="w-8 h-8 rounded-xl <?php echo $isMaintNotice ? 'bg-orange-200/80 text-orange-900' : 'bg-amber-200/80 text-amber-900'; ?> flex items-center justify-center shrink-0 mt-0.5">
                    <?php if ($isMaintNotice): ?>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                    <?php else: ?>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                    <?php endif; ?>
                </div>
                <div class="flex-1 space-y-1">
                    <p class="font-extrabold <?php echo $isMaintNotice ? 'text-orange-950' : 'text-amber-950'; ?> text-xs sm:text-sm"><?php echo $noticeTitle; ?></p>
                    <p class="font-medium <?php echo $isMaintNotice ? 'text-orange-900' : 'text-amber-900'; ?> text-xs leading-relaxed"><?php echo htmlspecialchars($data['warning'], ENT_QUOTES, 'UTF-8'); ?></p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Form -->
        <form action="<?php echo app_url('index.php?url=auth/login'); ?>" method="POST" class="space-y-4" onsubmit="return validateLoginForm()">
            <input type="hidden" name="csrf_token" value="<?php echo isset($_SESSION['csrf_token']) ? htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') : bin2hex(random_bytes(32)); ?>">

            <!-- Email / Phone Input -->
            <div>
                <label for="email" class="block text-xs font-semibold text-slate-700 mb-1">Email or Phone number</label>
                <input 
                    type="text" 
                    id="email" 
                    name="email" 
                    required 
                    autocomplete="username"
                    placeholder="Email or 09XXXXXXXXX"
                    value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8') : ''; ?>"
                    class="w-full h-10 px-3.5 rounded-xl border border-slate-200 bg-white text-slate-900 text-xs sm:text-sm placeholder:text-slate-400 outline-none transition-all focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10"
                >
            </div>

            <!-- Password Input -->
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label for="password" class="block text-xs font-semibold text-slate-700">Password</label>
                    <a href="<?php echo app_url('index.php?url=auth/forgotPassword'); ?>" class="text-[11px] font-semibold text-emerald-600 hover:text-emerald-700 hover:underline">
                        Forgot password?
                    </a>
                </div>
                <div class="relative">
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required 
                        minlength="8"
                        autocomplete="current-password"
                        placeholder="••••••••"
                        class="w-full h-10 px-3.5 pr-10 rounded-xl border border-slate-200 bg-white text-slate-900 text-xs sm:text-sm placeholder:text-slate-400 outline-none transition-all focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10"
                    >
                    <button 
                        type="button" 
                        onclick="togglePasswordVisibility()" 
                        aria-label="Toggle password visibility"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 focus:outline-none p-1 transition-colors"
                    >
                        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                </div>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between pt-0.5">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-600/20 cursor-pointer">
                    <span class="text-xs text-slate-600 font-normal">Remember this device</span>
                </label>
            </div>

            <!-- Submit Button -->
            <button 
                type="submit" 
                id="loginSubmitBtn"
                class="w-full h-10 bg-[#0B2E22] hover:bg-[#07281E] text-white text-xs sm:text-sm font-semibold rounded-xl shadow-xs transition flex items-center justify-center gap-2 cursor-pointer"
            >
                <span>Sign in to account</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </button>

            <!-- Footer Sign-up Prompt -->
            <p class="text-center text-xs text-slate-500 pt-2 font-normal">
                Don't have an account yet? 
                <a href="<?php echo app_url('index.php?url=auth/register'); ?>" class="font-semibold text-emerald-700 hover:text-emerald-800 hover:underline">
                    Create an account
                </a>
            </p>

            <!-- Guest Reporting Option -->
            <div class="pt-4 border-t border-slate-100 text-center space-y-2">
                <p class="text-xs text-slate-500 font-normal">Quick public reporting without an account:</p>
                <div class="flex items-center justify-center">
                    <a href="<?php echo app_url('index.php?url=guest'); ?>" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-emerald-50 text-emerald-800 hover:bg-emerald-100 text-xs font-semibold transition border border-emerald-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        <span>Report as Guest</span>
                    </a>
                </div>
            </div>

        </form>
    </div>
</div>

<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.innerHTML = '<path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/>';
        } else {
            passwordInput.type = 'password';
            eyeIcon.innerHTML = '<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/>';
        }
    }

    let isSubmitting = false;
    function validateLoginForm() {
        if (isSubmitting) return false;

        const email = document.getElementById('email');
        const password = document.getElementById('password');
        let isValid = true;

        if (!email.value.trim()) {
            email.classList.add('border-red-500', 'ring-2', 'ring-red-500/10');
            isValid = false;
        } else {
            email.classList.remove('border-red-500', 'ring-2', 'ring-red-500/10');
        }

        if (!password.value || password.value.length < 8) {
            password.classList.add('border-red-500', 'ring-2', 'ring-red-500/10');
            isValid = false;
        } else {
            password.classList.remove('border-red-500', 'ring-2', 'ring-red-500/10');
        }

        if (isValid) {
            isSubmitting = true;
            const btn = document.getElementById('loginSubmitBtn');
            if (btn) {
                btn.disabled = true;
                btn.classList.add('opacity-80', 'cursor-not-allowed');
                btn.innerHTML = `
                    <svg class="animate-spin h-4 w-4 text-white shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Signing in...</span>
                `;
            }
        }

        return isValid;
    }

    <?php if (isset($data['lockout_seconds']) && $data['lockout_seconds'] > 0): ?>
    (function() {
        let seconds = <?php echo (int)$data['lockout_seconds']; ?>;
        const countdownEl = document.getElementById('loginCountdown');
        if (!countdownEl) return;

        function updateCountdown() {
            if (seconds <= 0) {
                countdownEl.textContent = '';
                window.location.reload();
                return;
            }
            const mins = Math.floor(seconds / 60);
            const secs = seconds % 60;
            countdownEl.textContent = `(${mins > 0 ? mins + 'm ' : ''}${secs}s remaining)`;
            seconds--;
            setTimeout(updateCountdown, 1000);
        }
        updateCountdown();
    })();
    <?php endif; ?>
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>