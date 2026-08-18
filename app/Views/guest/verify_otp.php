<?php
$barangay   = $data['barangay'] ?? [];
$systemName = $barangay['system_name'] ?? 'Barangay Waste Management System';
$shortName  = $barangay['system_short_name'] ?? 'LINARAYA';
$brgyName   = $barangay['barangay_name'] ?? 'Dulong Bayan';
$sysLogo    = $barangay['system_logo'] ?? '';
$brgyLogo   = $barangay['barangay_logo'] ?? '';
$activeLogo = !empty($sysLogo) ? format_asset_url($sysLogo) : (!empty($brgyLogo) ? format_asset_url($brgyLogo) : '');

$contact        = $data['contact'] ?? ($data['phone'] ?? '');
$channel        = $data['channel'] ?? 'phone';
$resendCooldown = (int)($data['resend_cooldown_seconds'] ?? 0);
$expiresIn      = (int)($data['expires_in_seconds'] ?? 300);

$destinationType = ($channel === 'email') ? 'inbox' : 'phone';
$destinationLabel = ($channel === 'email') ? 'email address' : 'mobile number';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Code · <?php echo htmlspecialchars($shortName); ?></title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body, * { font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important; }
        .otp-box::-webkit-outer-spin-button,
        .otp-box::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        .otp-box {
            -moz-appearance: textfield;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col justify-center items-center px-4 py-8 sm:py-12 selection:bg-emerald-500 selection:text-white">

    <div class="w-full max-w-[460px] space-y-5">

        <!-- Top Branding -->
        <div class="flex flex-col items-center text-center space-y-2">
            <a href="<?php echo app_url(''); ?>" class="inline-flex items-center gap-2.5 group transition" title="Home">
                <?php if (!empty($activeLogo)): ?>
                    <img src="<?php echo htmlspecialchars($activeLogo); ?>" alt="Logo" class="w-10 h-10 object-contain group-hover:scale-105 transition">
                <?php else: ?>
                    <div class="w-10 h-10 flex items-center justify-center text-emerald-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                <?php endif; ?>
                <div class="text-left">
                    <span class="text-sm font-bold text-slate-900 tracking-tight block leading-tight group-hover:text-emerald-800 transition">
                        <?php echo htmlspecialchars($shortName); ?>
                    </span>
                    <span class="text-[11px] text-slate-500 block font-medium">
                        Barangay <?php echo htmlspecialchars($brgyName); ?>
                    </span>
                </div>
            </a>
        </div>

        <!-- Error Alert -->
        <?php if (!empty($data['error'])): ?>
        <div class="p-3.5 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs font-medium flex items-start gap-2.5 shadow-2xs">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 text-red-500 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            <span><?php echo htmlspecialchars($data['error'], ENT_QUOTES, 'UTF-8'); ?></span>
        </div>
        <?php endif; ?>

        <!-- Success Alert -->
        <?php if (!empty($data['success'])): ?>
        <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-medium flex items-start gap-2.5 shadow-2xs">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 text-emerald-600 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
            <span><?php echo htmlspecialchars($data['success'], ENT_QUOTES, 'UTF-8'); ?></span>
        </div>
        <?php endif; ?>

        <!-- Main Card -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-7 shadow-xs space-y-4">
            
            <!-- Title & Subtitle -->
            <div class="space-y-1">
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Check your <?php echo $destinationType; ?></h1>
                <p class="text-xs text-slate-500 leading-relaxed">
                    We sent a 6-digit verification code to <span class="font-bold text-slate-800 font-mono"><?php echo htmlspecialchars($contact); ?></span>.
                </p>
            </div>

            <!-- OTP Form -->
            <form id="otpForm" action="<?php echo app_url('index.php?url=guest/verifyOtp'); ?>" method="POST" class="space-y-4" onsubmit="return submitOtpForm()">
                
                <!-- Master Hidden OTP input -->
                <input type="hidden" id="otp" name="otp" value="">

                <!-- 6-Digit Individual PIN Inputs -->
                <div class="grid grid-cols-6 gap-2 sm:gap-2.5">
                    <?php for ($i = 1; $i <= 6; $i++): ?>
                    <input type="tel" 
                           id="otp_<?php echo $i; ?>" 
                           maxlength="1" 
                           pattern="[0-9]" 
                           inputmode="numeric" 
                           autocomplete="one-time-code"
                           class="otp-box w-full aspect-square sm:h-13 rounded-xl border border-slate-200 bg-white text-slate-900 text-lg sm:text-xl font-bold text-center outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10"
                           data-index="<?php echo $i; ?>">
                    <?php endfor; ?>
                </div>
                <p id="otp-error" class="text-red-500 text-xs text-center hidden">Please enter all 6 digits of the code.</p>

                <!-- Resend Pill / Timer Box -->
                <div id="resendContainer" class="p-3 rounded-xl bg-emerald-50/60 border border-emerald-100 text-slate-600 text-xs flex items-center justify-center text-center">
                    <?php if ($resendCooldown > 0): ?>
                        <span id="cooldownLabel" class="text-slate-600">
                            Resend code in <strong id="cooldownTimer" class="font-semibold text-slate-900 font-mono"><?php echo $resendCooldown; ?>s</strong>
                        </span>
                    <?php else: ?>
                        <span class="text-slate-600">
                            Didn't get the code? 
                            <a href="<?php echo app_url('index.php?url=guest/resendOtp'); ?>" class="font-semibold text-emerald-800 hover:text-emerald-950 hover:underline ml-1">Resend code</a>
                        </span>
                    <?php endif; ?>
                </div>

                <!-- CTA Button -->
                <div class="pt-1">
                    <button type="submit" id="verifyBtn"
                        class="w-full py-3 bg-[#0B2E22] hover:bg-[#07281E] text-white font-semibold rounded-xl shadow-xs hover:shadow transition-all flex items-center justify-center gap-2 text-xs sm:text-sm active:scale-[0.99] cursor-pointer">
                        <span>Verify &amp; Continue</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m9 18 6-6-6-6"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <!-- Change Contact Link -->
        <div class="text-center">
            <a href="<?php echo app_url('index.php?url=guest/phone'); ?>" 
               class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-500 hover:text-slate-800 transition py-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5"/><path d="m12 19-7-7 7-7"/>
                </svg>
                <span>Change mobile number or email</span>
            </a>
        </div>

    </div>

    <script>
        const boxes = Array.from({length: 6}, (_, i) => document.getElementById(`otp_${i + 1}`));
        const hiddenOtp = document.getElementById('otp');
        const otpError = document.getElementById('otp-error');

        boxes[0]?.focus();

        boxes.forEach((box, idx) => {
            box.addEventListener('input', (e) => {
                const val = e.target.value.replace(/[^0-9]/g, '');
                e.target.value = val ? val.slice(-1) : '';

                if (val && idx < 5) {
                    boxes[idx + 1].focus();
                }
                syncOtp();
            });

            box.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !box.value && idx > 0) {
                    boxes[idx - 1].focus();
                }
            });

            box.addEventListener('paste', (e) => {
                e.preventDefault();
                const paste = (e.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '');
                if (paste.length >= 6) {
                    for (let i = 0; i < 6; i++) {
                        if (boxes[i]) boxes[i].value = paste[i];
                    }
                    boxes[5]?.focus();
                    syncOtp();
                }
            });
        });

        function syncOtp() {
            const combined = boxes.map(b => b.value).join('');
            hiddenOtp.value = combined;
            if (combined.length === 6) {
                otpError.classList.add('hidden');
                boxes.forEach(b => b.classList.remove('border-red-500'));
            }
        }

        function submitOtpForm() {
            syncOtp();
            if (hiddenOtp.value.length !== 6) {
                otpError.classList.remove('hidden');
                boxes.forEach(b => { if (!b.value) b.classList.add('border-red-500'); });
                return false;
            }
            return true;
        }

        // Countdown timer for Resend
        let cooldownSecs = <?php echo $resendCooldown; ?>;
        if (cooldownSecs > 0) {
            const timerEl = document.getElementById('cooldownTimer');
            const interval = setInterval(() => {
                cooldownSecs--;
                if (timerEl) timerEl.textContent = `${cooldownSecs}s`;
                if (cooldownSecs <= 0) {
                    clearInterval(interval);
                    const container = document.getElementById('resendContainer');
                    if (container) {
                        container.innerHTML = `
                            <span class="text-slate-600">
                                Didn't get the code? 
                                <a href="<?php echo app_url('index.php?url=guest/resendOtp'); ?>" class="font-semibold text-emerald-800 hover:text-emerald-950 hover:underline ml-1">Resend code</a>
                            </span>
                        `;
                    }
                }
            }, 1000);
        }
    </script>
</body>
</html>
