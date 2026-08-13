<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Code · WasteWatch Guest</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .otp-input { letter-spacing: 0.5em; }
    </style>
</head>
<body class="h-full text-slate-900 antialiased selection:bg-emerald-500 selection:text-white">

<div class="min-h-screen flex flex-col justify-center items-center bg-slate-50 px-4 py-12">
    <div class="w-full max-w-[420px]">

        <!-- Logo -->
        <div class="flex items-center justify-center gap-2 mb-8">
            <div class="w-9 h-9 rounded-lg bg-emerald-600 flex items-center justify-center text-white shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>
            <span class="text-base font-bold text-slate-900">WasteWatch</span>
        </div>

        <!-- Progress Steps -->
        <div class="flex items-center gap-2 mb-8">
            <?php $labels = ['Verify', 'Details', 'Review', 'Done']; ?>
            <?php foreach ($labels as $i => $label): ?>
                <div class="flex items-center <?php echo $i < count($labels)-1 ? 'flex-1' : ''; ?>">
                    <div class="flex items-center gap-1.5">
                        <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold
                            <?php echo $i === 0 ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-400'; ?>">
                            <?php if ($i === 0): ?>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                            <?php else: echo $i + 1; endif; ?>
                        </div>
                        <span class="text-xs font-medium <?php echo $i === 0 ? 'text-slate-900' : 'text-slate-400'; ?> hidden sm:block"><?php echo $label; ?></span>
                    </div>
                    <?php if ($i < count($labels)-1): ?>
                    <div class="flex-1 h-px bg-slate-200 mx-2"></div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Phone Icon -->
        <div class="w-16 h-16 rounded-2xl bg-emerald-50 border border-emerald-200/70 flex items-center justify-center mx-auto mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2.18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
        </div>

        <!-- Header -->
        <div class="text-center mb-7">
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Check your phone</h1>
            <p class="text-sm text-slate-500 mt-2">
                We sent a 6-digit verification code to
                <br><span class="font-semibold text-slate-800"><?php echo htmlspecialchars($data['phone'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
            </p>
        </div>

        <!-- Error Alert -->
        <?php 
        $errorMsg = !empty($data['error']) ? $data['error'] : ($_GET['resend_error'] ?? '');
        if (!empty($errorMsg)): 
        ?>
        <div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200/80 text-red-700 text-xs flex items-start gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 text-red-500 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <span class="font-medium"><?php echo htmlspecialchars($errorMsg, ENT_QUOTES, 'UTF-8'); ?></span>
        </div>
        <?php endif; ?>

        <!-- Resent Success -->
        <?php if (isset($_GET['resent'])): ?>
        <div class="mb-5 p-4 rounded-xl bg-green-50 border border-green-200/80 text-green-700 text-xs flex items-start gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 text-green-500 mt-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
            <span class="font-medium">A new verification code has been sent to your phone.</span>
        </div>
        <?php endif; ?>

        <!-- OTP Form -->
        <form action="/brgy-waste-app-v3/public/guest/verifyOtp" method="POST" class="space-y-5" onsubmit="return validateOtpForm()">

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-2 text-center">Enter 6-digit code</label>
                <input type="text" id="otp" name="otp" required maxlength="6" inputmode="numeric" pattern="[0-9]{6}"
                    placeholder="······"
                    class="otp-input w-full h-14 px-4 rounded-xl border border-slate-200 bg-white text-slate-900 text-2xl font-bold text-center placeholder:text-slate-300 outline-none transition-all focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10 tracking-widest"
                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,6); autoSubmitIfComplete(this)">
                <p id="otp-error" class="text-red-500 text-xs font-medium mt-1.5 text-center hidden">Please enter the 6-digit code.</p>
            </div>

            <!-- Timer -->
            <div class="text-center text-xs text-slate-500">
                <?php
                $initialSeconds = (int)($data['expires_in_seconds'] ?? 300);
                $initialM = floor($initialSeconds / 60);
                $initialS = $initialSeconds % 60;
                $initialFormatted = sprintf('%d:%02d', $initialM, $initialS);
                ?>
                Code expires in <span id="otpTimer" class="font-semibold text-slate-700"><?php echo $initialFormatted; ?></span>
            </div>

            <button type="submit" id="verifyBtn" class="w-full h-11 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all flex items-center justify-center gap-2 focus:ring-2 focus:ring-emerald-600/20 active:scale-[0.99]">
                Verify Code
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </button>
        </form>

        <!-- Resend -->
        <div class="text-center mt-5 text-xs text-slate-500">
            Didn't receive a code?
            <a href="/brgy-waste-app-v3/public/index.php?url=guest/resendOtp" id="resendLink" class="font-semibold text-emerald-600 hover:underline ml-1">Resend code</a>
        </div>

        <!-- Wrong number -->
        <div class="text-center mt-3 text-xs text-slate-400">
            Wrong number?
            <a href="/brgy-waste-app-v3/public/index.php?url=guest" class="text-slate-600 font-semibold hover:underline ml-1">Go back</a>
        </div>
    </div>
</div>

<script>
    // Exact Token Expiration Countdown
    let seconds = <?php echo (int)($data['expires_in_seconds'] ?? 300); ?>;
    const timerEl = document.getElementById('otpTimer');

    function renderExpiryTime() {
        if (seconds <= 0) {
            timerEl.textContent = 'Expired';
            timerEl.classList.add('text-red-500');
            const btn = document.getElementById('verifyBtn');
            if (btn) {
                btn.disabled = true;
                btn.classList.add('opacity-50', 'cursor-not-allowed');
            }
            return false;
        }
        const m = Math.floor(seconds / 60);
        const s = seconds % 60;
        timerEl.textContent = `${m}:${s.toString().padStart(2,'0')}`;
        return true;
    }

    renderExpiryTime();
    const expiryInterval = setInterval(() => {
        seconds--;
        if (!renderExpiryTime()) {
            clearInterval(expiryInterval);
        }
    }, 1000);

    // Resend Cooldown Countdown
    let resendCooldown = <?php echo (int)($data['resend_cooldown_seconds'] ?? 0); ?>;
    const resendLink = document.getElementById('resendLink');

    function updateResendState() {
        if (!resendLink) return;
        if (resendCooldown > 0) {
            resendLink.classList.add('opacity-50', 'pointer-events-none', 'text-slate-400');
            resendLink.classList.remove('text-emerald-600', 'hover:underline');
            resendLink.textContent = `Resend code (wait ${resendCooldown}s)`;
        } else {
            resendLink.classList.remove('opacity-50', 'pointer-events-none', 'text-slate-400');
            resendLink.classList.add('text-emerald-600', 'hover:underline');
            resendLink.textContent = 'Resend code';
        }
    }

    updateResendState();
    if (resendCooldown > 0) {
        const resendInterval = setInterval(() => {
            resendCooldown--;
            updateResendState();
            if (resendCooldown <= 0) {
                clearInterval(resendInterval);
            }
        }, 1000);
    }

    function autoSubmitIfComplete(el) {
        if (el.value.length === 6) {
            el.closest('form').submit();
        }
    }

    function validateOtpForm() {
        const otp = document.getElementById('otp');
        const err = document.getElementById('otp-error');
        if (!/^\d{6}$/.test(otp.value)) {
            otp.classList.add('border-red-500', 'ring-2', 'ring-red-500/10');
            err.classList.remove('hidden');
            return false;
        }
        return true;
    }
</script>

</body>
</html>
