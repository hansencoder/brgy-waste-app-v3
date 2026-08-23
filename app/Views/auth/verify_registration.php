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
$barangayName = $authBranding['barangay_name'] ?? 'Dulong Bayan';
$sysShortName = $authBranding['system_short_name'] ?? 'WasteWatch';
$sysLogo      = !empty($authBranding['system_logo']) ? format_asset_url($authBranding['system_logo']) : null;

$isPhone = ($_SESSION['reg_type'] ?? '') === 'phone';
$contact = $_SESSION['reg_email'] ?? '';
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
            <h1 class="text-lg font-bold text-slate-900 tracking-tight">Verify Your Account</h1>
            <p class="text-xs text-slate-500 mt-0.5 max-w-xs">
                We sent a 6-digit verification code to your <?php echo $isPhone ? 'phone number' : 'email address'; ?>:
            </p>
            <?php if (!empty($contact)): ?>
                <span class="mt-1.5 inline-block text-xs font-mono font-bold text-emerald-800 bg-emerald-50 border border-emerald-200/80 px-2.5 py-0.5 rounded-full">
                    <?php echo htmlspecialchars($contact, ENT_QUOTES, 'UTF-8'); ?>
                </span>
            <?php endif; ?>
        </div>

        <!-- Error/Success/Info Alerts -->
        <?php if (!empty($data['error'])): ?>
            <div class="mb-5 p-3.5 rounded-xl bg-red-50 border border-red-200/80 text-red-700 text-xs flex items-start gap-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 text-red-500 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <div class="flex-1 font-medium"><?php echo htmlspecialchars($data['error'], ENT_QUOTES, 'UTF-8'); ?></div>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($data['info'])): ?>
            <div class="mb-5 p-3.5 rounded-xl bg-blue-50 border border-blue-200/80 text-blue-800 text-xs flex items-start gap-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 text-blue-600 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                <div class="flex-1 font-medium"><?php echo htmlspecialchars($data['info'], ENT_QUOTES, 'UTF-8'); ?></div>
            </div>
        <?php endif; ?>

        <?php if (!empty($data['success'])): ?>
            <div class="mb-5 p-3.5 rounded-xl bg-emerald-50 border border-emerald-200/80 text-emerald-800 text-xs flex items-start gap-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 text-emerald-600 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                <div class="flex-1 font-medium"><?php echo htmlspecialchars($data['success'], ENT_QUOTES, 'UTF-8'); ?></div>
            </div>
        <?php endif; ?>

        <form action="<?php echo app_url('index.php?url=auth/verifyRegistration'); ?>" method="POST" id="verifyRegForm" class="space-y-5">
            <input type="hidden" name="otp" id="otp">
            <input type="hidden" id="cooldownEnd" value="<?php echo isset($data['retry_after_seconds']) ? (time() + $data['retry_after_seconds']) : 0; ?>">

            <div>
                <label class="block text-xs font-semibold text-slate-700 text-center mb-2">Enter 6-Digit OTP</label>
                <div class="flex gap-2 justify-center">
                    <?php for($i = 0; $i < 6; $i++): ?>
                    <input type="text" maxlength="1" id="box-<?php echo $i; ?>" data-index="<?php echo $i; ?>" 
                        class="otp-input w-11 h-12 text-center text-lg font-mono font-bold rounded-xl border border-slate-200 outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10 transition-all bg-white placeholder:text-slate-300" 
                        placeholder="•" autocomplete="off" inputmode="numeric" <?php echo $i === 0 ? 'autofocus' : ''; ?>>
                    <?php endfor; ?>
                </div>
            </div>
            
            <button type="submit" id="submitBtn" class="w-full h-10 bg-[#0B2E22] hover:bg-[#07281E] text-white text-xs sm:text-sm font-semibold rounded-xl shadow-xs transition flex items-center justify-center gap-2 cursor-pointer">
                <span>Activate Account</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </button>
        </form>

        <div class="mt-5 text-center space-y-2">
            <p class="text-xs text-slate-500 font-normal">
                Didn't receive the code? 
                <a id="resendLink" href="<?php echo app_url('index.php?url=auth/verifyRegistration&action=resend'); ?>" class="font-semibold text-emerald-700 hover:underline">Resend code</a>
                <span id="resendCountdown" class="ml-1 text-xs text-slate-400 font-mono font-medium"></span>
            </p>
            <div>
                <a href="<?php echo app_url('index.php?url=auth/register'); ?>" class="inline-flex items-center gap-1 text-xs font-semibold text-slate-500 hover:text-slate-900 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="m12 5-7 7 7 7"/></svg>
                    <span>Back to Registration</span>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.otp-input');
    const hiddenOtp = document.getElementById('otp');
    const form = document.getElementById('verifyRegForm');
    const submitBtn = document.getElementById('submitBtn');

    function syncOtp() {
        let otpVal = '';
        inputs.forEach(inp => otpVal += inp.value);
        hiddenOtp.value = otpVal;
        return otpVal;
    }

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
            const otpVal = syncOtp();
            if (otpVal.length === 6) {
                form.submit();
            }
        });

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && !this.value && index > 0) {
                inputs[index - 1].focus();
                inputs[index - 1].value = '';
                syncOtp();
            }
        });

        input.addEventListener('paste', function(e) {
            e.preventDefault();
            const paste = (e.clipboardData || window.clipboardData).getData('text');
            const clean = paste.replace(/\D/g, '').slice(0, 6);
            if (clean.length > 0) {
                for (let i = 0; i < 6; i++) {
                    inputs[i].value = clean[i] || '';
                }
                const otpVal = syncOtp();
                if (clean.length >= 6) {
                    inputs[5].focus();
                    form.submit();
                } else {
                    inputs[clean.length].focus();
                }
            }
        });
    });

    form.addEventListener('submit', function(e) {
        const otpVal = syncOtp();
        if (otpVal.length < 6) {
            e.preventDefault();
            if (typeof showModalAlert === 'function') {
                showModalAlert('Please enter the full 6-digit verification code.', 'Verification Code Required', 'warning');
            } else {
                alert('Please enter the full 6-digit verification code.');
            }
            return false;
        }

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-80', 'cursor-not-allowed');
            submitBtn.innerHTML = `
                <svg class="animate-spin h-4 w-4 text-white shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Activating...</span>
            `;
        }
    });

    // 60-Second Resend Cooldown Timer
    const resendLink = document.getElementById('resendLink');
    const resendCountdown = document.getElementById('resendCountdown');
    const STORAGE_KEY = 'waste_reg_resend_expiry';

    let serverCooldown = <?php echo (int)($data['retry_after_seconds'] ?? 0); ?>;
    let stored = parseInt(sessionStorage.getItem(STORAGE_KEY) || '0', 10);
    let endTime;

    if (serverCooldown > 0) {
        endTime = Date.now() + (serverCooldown * 1000);
        sessionStorage.setItem(STORAGE_KEY, endTime.toString());
    } else if (stored > Date.now()) {
        endTime = stored;
    } else {
        endTime = Date.now() + 60000;
        sessionStorage.setItem(STORAGE_KEY, endTime.toString());
    }

    function formatTime(s){
        const m = Math.floor(s / 60);
        const sec = s % 60;
        if (m > 0) return sec > 0 ? m + ':' + String(sec).padStart(2, '0') : m + 'm';
        return sec + 's';
    }

    function updateTimer(){
        const now = Date.now();
        const diff = Math.max(0, Math.floor((endTime - now) / 1000));
        if (diff <= 0) {
            resendCountdown.textContent = '';
            resendLink.style.pointerEvents = '';
            resendLink.style.opacity = '';
            sessionStorage.removeItem(STORAGE_KEY);
            return;
        }
        resendCountdown.textContent = 'in (' + formatTime(diff) + ')';
        resendLink.style.pointerEvents = 'none';
        resendLink.style.opacity = '0.5';
        setTimeout(updateTimer, 1000);
    }

    resendLink.addEventListener('click', function(e) {
        const diff = Math.max(0, Math.floor((endTime - Date.now()) / 1000));
        if (diff > 0) {
            e.preventDefault();
        } else {
            sessionStorage.setItem(STORAGE_KEY, (Date.now() + 60000).toString());
        }
    });

    updateTimer();
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>