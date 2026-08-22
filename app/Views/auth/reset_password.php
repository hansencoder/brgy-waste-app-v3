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
            <h1 class="text-lg font-bold text-slate-900 tracking-tight">Verify Your Email</h1>
            <p class="text-xs text-slate-500 mt-0.5 max-w-xs">We sent a 6-digit verification code to your email address.</p>
        </div>

        <!-- Error/Success Alerts -->
        <?php if (!empty($data['error'])): ?>
            <div class="mb-5 p-3.5 rounded-xl bg-red-50 border border-red-200/80 text-red-700 text-xs flex items-start gap-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 text-red-500 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <div class="flex-1 font-medium"><?php echo htmlspecialchars($data['error'], ENT_QUOTES, 'UTF-8'); ?></div>
            </div>
        <?php endif; ?>
        <?php if (!empty($data['success'])): ?>
            <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200/80 text-emerald-800 text-xs flex items-start gap-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 text-emerald-600 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                <div class="flex-1 font-medium"><?php echo htmlspecialchars($data['success'], ENT_QUOTES, 'UTF-8'); ?></div>
            </div>
        <?php endif; ?>

        <form action="<?php echo app_url('index.php?url=auth/verifyResetOtp'); ?>" method="POST" class="space-y-5">
            <input type="hidden" name="csrf_token" value="<?php echo isset($_SESSION['csrf_token']) ? htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8') : bin2hex(random_bytes(32)); ?>">
            <input type="hidden" name="otp" id="otp">

            <div>
                <label class="block text-xs font-semibold text-slate-700 text-center mb-2">Enter 6-Digit OTP</label>
                <div class="flex gap-2 justify-center">
                    <?php for($i = 0; $i < 6; $i++): ?>
                    <input type="text" maxlength="1" id="box-<?php echo $i; ?>" data-index="<?php echo $i; ?>" 
                        class="otp-input w-11 h-12 text-center text-lg font-mono font-bold rounded-xl border border-slate-200 outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10 transition-all bg-white placeholder:text-slate-300" 
                        placeholder="•" autocomplete="off">
                    <?php endfor; ?>
                </div>
            </div>
            
            <button type="submit" class="w-full h-10 bg-[#0B2E22] hover:bg-[#07281E] text-white text-xs sm:text-sm font-semibold rounded-xl shadow-xs transition flex items-center justify-center gap-2 cursor-pointer">
                <span>Verify Code</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
            </button>
        </form>

        <div class="mt-5 text-center space-y-2">
            <p class="text-xs text-slate-500 font-normal">
                Didn't receive the code? 
                <a id="resendLink" href="<?php echo app_url('index.php?url=auth/resendResetOtp'); ?>" class="font-semibold text-emerald-700 hover:underline">Resend code</a>
                <span id="resendCountdown" class="ml-1 text-xs text-slate-400 font-mono font-medium"></span>
            </p>
            <div>
                <a href="<?php echo app_url('index.php?url=auth'); ?>" class="inline-flex items-center gap-1 text-xs font-semibold text-slate-500 hover:text-slate-900 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="m12 5-7 7 7 7"/></svg>
                    <span>Back to Sign In</span>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.otp-input');
    const hiddenOtp = document.getElementById('otp');

    inputs.forEach((input, index) => {
        input.addEventListener('keyup', function(e) {
            const val = this.value;
            if (val && !/^\d$/.test(val)) {
                this.value = '';
                return;
            }
            if (val && index < 5) {
                inputs[index + 1].focus();
            }
            let otpVal = '';
            inputs.forEach(inp => otpVal += inp.value);
            hiddenOtp.value = otpVal;
        });

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && !this.value && index > 0) {
                inputs[index - 1].focus();
                inputs[index - 1].value = '';
                let otpVal = '';
                inputs.forEach(inp => otpVal += inp.value);
                hiddenOtp.value = otpVal;
            }
        });

        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const paste = (e.clipboardData || window.clipboardData).getData('text');
            if (paste && /^\d{6}$/.test(paste)) {
                let i = 0;
                inputs.forEach(inp => {
                    inp.value = paste[i];
                    i++;
                });
                let otpVal = '';
                inputs.forEach(inp => otpVal += inp.value);
                hiddenOtp.value = otpVal;
                inputs[5].focus();
            }
        });
    });

    document.querySelector('form')?.addEventListener('submit', function(e) {
        let otpVal = '';
        inputs.forEach(inp => otpVal += inp.value);
        hiddenOtp.value = otpVal;
        if (otpVal.length < 6) {
            e.preventDefault();
            showModalAlert('Please enter the full 6-digit verification code.', 'Verification Code Required', 'warning');
        }
    });

    // 60-Second Resend Cooldown Timer
    const resendLink = document.getElementById('resendLink');
    const resendCountdown = document.getElementById('resendCountdown');
    const STORAGE_KEY = 'waste_reset_otp_expiry';

    function startCooldown(durationSecs) {
        let expiry = Date.now() + durationSecs * 1000;
        sessionStorage.setItem(STORAGE_KEY, expiry.toString());
        tick();
    }

    function tick() {
        let expiry = parseInt(sessionStorage.getItem(STORAGE_KEY) || '0', 10);
        let remaining = Math.ceil((expiry - Date.now()) / 1000);

        if (remaining > 0) {
            resendLink.style.pointerEvents = 'none';
            resendLink.classList.add('opacity-50', 'cursor-not-allowed', 'text-slate-400');
            resendLink.classList.remove('text-emerald-700', 'hover:underline');
            resendCountdown.textContent = `in (${remaining}s)`;
            setTimeout(tick, 1000);
        } else {
            sessionStorage.removeItem(STORAGE_KEY);
            resendLink.style.pointerEvents = '';
            resendLink.classList.remove('opacity-50', 'cursor-not-allowed', 'text-slate-400');
            resendLink.classList.add('text-emerald-700', 'hover:underline');
            resendCountdown.textContent = '';
        }
    }

    // Check if there is an active timer or start initial 60s cooldown if just loaded from send
    let existingExpiry = parseInt(sessionStorage.getItem(STORAGE_KEY) || '0', 10);
    if (existingExpiry > Date.now()) {
        tick();
    } else {
        startCooldown(60);
    }

    resendLink.addEventListener('click', function(e) {
        let expiry = parseInt(sessionStorage.getItem(STORAGE_KEY) || '0', 10);
        if (expiry > Date.now()) {
            e.preventDefault();
        } else {
            // Set cooldown for next load
            sessionStorage.setItem(STORAGE_KEY, (Date.now() + 60000).toString());
        }
    });
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>