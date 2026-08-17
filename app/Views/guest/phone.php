<?php
$barangay   = $data['barangay'] ?? [];
$systemName = $barangay['system_name'] ?? 'Barangay Waste Management System';
$shortName  = $barangay['system_short_name'] ?? 'LINARAYA';
$brgyName   = $barangay['barangay_name'] ?? 'Dulong Bayan';
$sysLogo    = $barangay['system_logo'] ?? '';
$brgyLogo   = $barangay['barangay_logo'] ?? '';
$activeLogo = !empty($sysLogo) ? $sysLogo : (!empty($brgyLogo) ? $brgyLogo : '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter Mobile Number · <?php echo htmlspecialchars($shortName); ?></title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Miranda+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <style>
        body, * { font-family: 'Miranda Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col justify-center items-center px-4 py-8 sm:py-12 selection:bg-emerald-500 selection:text-white">

    <div class="w-full max-w-[460px] space-y-5">

        <!-- Top Branding -->
        <div class="flex flex-col items-center text-center space-y-2">
            <a href="/brgy-waste-app-v3/public/" class="inline-flex items-center gap-3 group transition">
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

        <!-- Main Form Card -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-7 shadow-xs space-y-4">
            
            <!-- Title & Subtitle -->
            <div class="space-y-1">
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Enter your mobile number</h1>
                <p class="text-xs text-slate-500 leading-relaxed">
                    We'll send a one-time verification code to this number.
                </p>
            </div>

            <!-- Form -->
            <form id="phoneForm" action="/brgy-waste-app-v3/public/index.php?url=guest/sendOtp" method="POST" class="space-y-4" onsubmit="return validatePhoneForm()">
                
                <!-- FULL NAME -->
                <div>
                    <label class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-1">
                        Full Name <span class="text-slate-400 font-normal lowercase">(Optional)</span>
                    </label>
                    <input type="text" id="guest_name" name="guest_name" 
                        value="<?php echo htmlspecialchars($data['guest_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                        placeholder="e.g. Juan dela Cruz"
                        class="w-full h-11 px-3.5 rounded-xl border border-slate-200 bg-white text-slate-900 text-xs sm:text-sm placeholder:text-slate-400 outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10"
                        oninput="this.value = this.value.replace(/[^a-zA-Z\s\-.]/g, '')">
                </div>

                <!-- MOBILE NUMBER -->
                <div>
                    <label class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-1">
                        Mobile Number <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-4.69-4.69 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2.18h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        </div>
                        <input type="tel" id="phone" name="phone" required maxlength="11"
                            value="<?php echo htmlspecialchars($data['phone'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                            placeholder="09XXXXXXXXX"
                            class="w-full h-11 pl-9 pr-3.5 rounded-xl border border-slate-200 bg-white text-slate-900 text-xs sm:text-sm font-medium tracking-wide placeholder:text-slate-400 placeholder:font-normal outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10"
                            oninput="this.value = this.value.replace(/[^0-9]/g, ''); validatePhoneInput(this)">
                    </div>
                    <p id="phone-error" class="text-red-500 text-xs mt-1 hidden">Please enter a valid 11-digit mobile number (e.g. 09951281511).</p>
                </div>

                <!-- Callout Notice Box -->
                <div class="p-3 rounded-xl bg-emerald-50/60 border border-emerald-100 text-slate-600 text-xs flex items-start gap-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 text-emerald-600 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                        <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                    </svg>
                    <p class="leading-relaxed">
                        Standard SMS rates may apply. Your number will only be used for this report and status updates.
                    </p>
                </div>

                <!-- CTA Button -->
                <div class="pt-1">
                    <button type="submit" id="submitBtn"
                        class="w-full py-3 bg-[#0B2E22] hover:bg-[#07281E] text-white font-semibold rounded-xl shadow-xs hover:shadow transition-all flex items-center justify-center gap-2 text-xs sm:text-sm active:scale-[0.99] cursor-pointer">
                        <span>Send Verification Code</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="m9 18 6-6-6-6"/>
                        </svg>
                    </button>
                </div>
            </form>
        </div>

        <!-- Back Button -->
        <div class="text-center">
            <a href="/brgy-waste-app-v3/public/index.php?url=guest" 
               class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-500 hover:text-slate-800 transition py-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5"/><path d="m12 19-7-7 7-7"/>
                </svg>
                <span>Back</span>
            </a>
        </div>

    </div>

    <script>
        function validatePhoneInput(el) {
            const err = document.getElementById('phone-error');
            const val = el.value.trim();
            if (val.length === 11) {
                if (/^09\d{9}$/.test(val)) {
                    err.classList.add('hidden');
                    el.classList.remove('border-red-500');
                } else {
                    err.classList.remove('hidden');
                    el.classList.add('border-red-500');
                }
            } else {
                err.classList.add('hidden');
                el.classList.remove('border-red-500');
            }
        }

        function validatePhoneForm() {
            const phone = document.getElementById('phone');
            const val = phone.value.trim();
            if (!/^09\d{9}$/.test(val)) {
                phone.classList.add('border-red-500');
                document.getElementById('phone-error').classList.remove('hidden');
                phone.focus();
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
