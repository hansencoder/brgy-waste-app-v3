<?php
$barangay   = $data['barangay'] ?? [];
$systemName = $barangay['system_name'] ?? 'Barangay Waste Management System';
$shortName  = $barangay['system_short_name'] ?? 'LINARAYA';
$brgyName   = $barangay['barangay_name'] ?? 'Dulong Bayan';
$sysLogo    = $barangay['system_logo'] ?? '';
$brgyLogo   = $barangay['barangay_logo'] ?? '';
$activeLogo = !empty($sysLogo) ? $sysLogo : (!empty($brgyLogo) ? $brgyLogo : '');
$phone      = $data['phone'] ?? '';
$resendCooldown = (int)($data['resend_cooldown_seconds'] ?? 0);
$expiresIn      = (int)($data['expires_in_seconds'] ?? 300);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP · <?php echo htmlspecialchars($shortName); ?></title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Miranda+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <style>
        body, * { font-family: 'Miranda Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important; }
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
            <a href="<?php echo app_url(''); ?>" class="inline-flex items-center gap-3 group transition">
                <div class="w-11 h-11 rounded-full bg-[#07281E] p-0.5 shadow-sm flex items-center justify-center overflow-hidden border border-slate-200 group-hover:scale-105 transition">
                    <?php if (!empty($activeLogo)): ?>
                        <img src="<?php echo htmlspecialchars($activeLogo); ?>" alt="Logo" class="w-full h-full rounded-full object-cover">
                    <?php else: ?>
                        <div class="w-full h-full rounded-full bg-[#07281E] flex items-center justify-center text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="text-left">
                    <span class="text-base font-bold text-slate-900 tracking-tight block leading-tight group-hover:text-emerald-800 transition">
                        <?php echo htmlspecialchars($shortName); ?>
                    </span>
                    <span class="text-xs text-slate-500 block">
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
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Check your phone</h1>
                <p class="text-xs text-slate-500 leading-relaxed">
                    We sent a 6-digit verification code to <span class="font-medium text-slate-800 font-mono"><?php echo htmlspecialchars($phone); ?></span>.
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
                        <span>Verify Code</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m9 18 6-6-6-6"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <!-- Change Mobile Number Link -->
        <div class="text-center">
            <a href="<?php echo app_url('index.php?url=guest/phone'); ?>" 
               class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-500 hover:text-slate-800 transition py-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5"/><path d="m12 19-7-7 7-7"/>
                </svg>
                <span>Change mobile number</span>
            </a>
        </div>

    </div>

    <script>
        const otpBoxes = Array.from(document.querySelectorAll('.otp-box'));
        const hiddenOtpInput = document.getElementById('otp');
        const form = document.getElementById('otpForm');

        // Focus first box on load
        window.addEventListener('DOMContentLoaded', () => {
            if (otpBoxes.length > 0) {
                otpBoxes[0].focus();
            }
        });

        // Handle box input navigation
        otpBoxes.forEach((box, idx) => {
            box.addEventListener('input', (e) => {
                const val = e.target.value.replace(/[^0-9]/g, '');
                e.target.value = val ? val.slice(-1) : '';

                if (val && idx < otpBoxes.length - 1) {
                    otpBoxes[idx + 1].focus();
                }

                syncAndCheckAutoSubmit();
            });

            box.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace') {
                    if (!box.value && idx > 0) {
                        otpBoxes[idx - 1].focus();
                        otpBoxes[idx - 1].value = '';
                    }
                } else if (e.key === 'ArrowLeft' && idx > 0) {
                    otpBoxes[idx - 1].focus();
                } else if (e.key === 'ArrowRight' && idx < otpBoxes.length - 1) {
                    otpBoxes[idx + 1].focus();
                }
            });

            box.addEventListener('paste', (e) => {
                e.preventDefault();
                const pasteData = (e.clipboardData || window.clipboardData).getData('text').trim().replace(/[^0-9]/g, '');
                if (pasteData) {
                    const digits = pasteData.slice(0, 6).split('');
                    digits.forEach((d, i) => {
                        if (otpBoxes[i]) otpBoxes[i].value = d;
                    });
                    const focusIdx = Math.min(digits.length, 5);
                    otpBoxes[focusIdx].focus();
                    syncAndCheckAutoSubmit();
                }
            });
        });

        function syncAndCheckAutoSubmit() {
            const code = otpBoxes.map(b => b.value).join('');
            hiddenOtpInput.value = code;

            if (code.length === 6) {
                document.getElementById('otp-error').classList.add('hidden');
                form.submit();
            }
        }

        function submitOtpForm() {
            const code = otpBoxes.map(b => b.value).join('');
            hiddenOtpInput.value = code;
            if (code.length < 6) {
                document.getElementById('otp-error').classList.remove('hidden');
                return false;
            }
            return true;
        }

        // Timer for resend
        let cooldown = <?php echo $resendCooldown; ?>;
        if (cooldown > 0) {
            const timerEl = document.getElementById('cooldownTimer');
            const interval = setInterval(() => {
                cooldown--;
                if (cooldown <= 0) {
                    clearInterval(interval);
                    document.getElementById('resendContainer').innerHTML = `
                        <span class="text-slate-600">
                            Didn't get the code? 
                            <a href="<?php echo app_url('index.php?url=guest/resendOtp'); ?>" class="font-semibold text-emerald-800 hover:text-emerald-950 hover:underline ml-1">Resend code</a>
                        </span>
                    `;
                } else if (timerEl) {
                    timerEl.textContent = cooldown + 's';
                }
            }, 1000);
        }
    </script>
</body>
</html>
