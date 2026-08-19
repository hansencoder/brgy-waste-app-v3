<?php
$barangay   = $data['barangay'] ?? [];
$systemName = $barangay['system_name'] ?? 'Barangay Waste Management System';
$shortName  = $barangay['system_short_name'] ?? 'LINARAYA';
$brgyName   = $barangay['barangay_name'] ?? 'Dulong Bayan';
$sysLogo    = $barangay['system_logo'] ?? '';
$brgyLogo   = $barangay['barangay_logo'] ?? '';
$activeLogo = !empty($sysLogo) ? format_asset_url($sysLogo) : (!empty($brgyLogo) ? format_asset_url($brgyLogo) : '');

$currentChannel = $data['channel'] ?? 'phone';
$isRegisteredResident = !empty($data['is_registered_resident']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest Verification · <?php echo htmlspecialchars($shortName); ?></title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body, * { font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col justify-center items-center px-4 py-8 sm:py-12 selection:bg-emerald-500 selection:text-white">

    <div class="w-full max-w-[460px] space-y-5">

        <!-- Top Branding -->
        <div class="flex flex-col items-center text-center space-y-2">
            <a href="<?php echo app_url(''); ?>" class="inline-flex items-center gap-2.5 group transition" title="Home">
                <?php if (!empty($activeLogo)): ?>
                    <div class="w-10 h-10 rounded-full flex items-center justify-center overflow-hidden group-hover:scale-105 transition">
                        <img src="<?php echo htmlspecialchars($activeLogo); ?>" alt="Logo" class="w-full h-full rounded-full object-cover">
                    </div>
                <?php else: ?>
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-emerald-600">
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

        <!-- Error Alert / Resident Warning -->
        <?php if (!empty($data['error'])): ?>
            <?php if ($isRegisteredResident): ?>
                <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs shadow-sm space-y-2.5">
                    <div class="flex items-start gap-2.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0 text-amber-600 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 9v4"/><path d="M12 17h.01"/><path d="M3.6 20.4a2 2 0 0 0 1.7 1h13.4a2 2 0 0 0 1.7-1l-8.4-14.5a2 2 0 0 0-3.4 0z"/>
                        </svg>
                        <div class="space-y-1">
                            <p class="font-bold text-amber-950">Official Resident Account Detected</p>
                            <p class="text-amber-800 leading-relaxed"><?php echo htmlspecialchars($data['error'], ENT_QUOTES, 'UTF-8'); ?></p>
                        </div>
                    </div>
                    <div class="pt-1">
                        <a href="<?php echo app_url('index.php?url=auth'); ?>" 
                           class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition active:scale-[0.99]">
                            <span>Sign In to Resident Portal</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="p-3.5 rounded-xl bg-red-50 border border-red-200 text-red-700 text-xs font-medium flex items-start gap-2.5 shadow-2xs">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 text-red-500 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    <span><?php echo htmlspecialchars($data['error'], ENT_QUOTES, 'UTF-8'); ?></span>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Main Form Card -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-7 shadow-xs space-y-4">
            
            <!-- Title & Subtitle -->
            <div class="space-y-1">
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Guest Waste Reporting</h1>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Select how you want to receive your one-time verification code.
                </p>
            </div>

            <!-- Channel Selection Tabs -->
            <div class="grid grid-cols-2 gap-1.5 p-1 bg-slate-100/80 rounded-xl border border-slate-200/80 text-xs font-bold">
                <button type="button" id="tabPhone" onclick="switchChannel('phone')"
                    class="py-2 rounded-lg transition-all flex items-center justify-center gap-1.5 <?php echo $currentChannel === 'phone' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-500 hover:text-slate-800'; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-4.69-4.69 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2.18h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    <span>Mobile SMS</span>
                </button>
                <button type="button" id="tabEmail" onclick="switchChannel('email')"
                    class="py-2 rounded-lg transition-all flex items-center justify-center gap-1.5 <?php echo $currentChannel === 'email' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-500 hover:text-slate-800'; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                    <span>Email Address</span>
                </button>
            </div>

            <!-- Form -->
            <form id="phoneForm" action="<?php echo app_url('index.php?url=guest/sendOtp'); ?>" method="POST" class="space-y-4" onsubmit="return validateContactForm()">
                <input type="hidden" id="channelInput" name="channel" value="<?php echo htmlspecialchars($currentChannel); ?>">
                
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

                <!-- PHONE FIELD -->
                <div id="phoneFieldBlock" class="<?php echo $currentChannel === 'email' ? 'hidden' : ''; ?>">
                    <label class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-1">
                        Mobile Number <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-4.69-4.69 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2.18h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                        </div>
                        <input type="tel" id="phone" name="phone" maxlength="11"
                            value="<?php echo htmlspecialchars($currentChannel === 'phone' ? ($data['contact'] ?? ($data['phone'] ?? '')) : '', ENT_QUOTES, 'UTF-8'); ?>"
                            placeholder="09XXXXXXXXX"
                            class="w-full h-11 pl-9 pr-3.5 rounded-xl border <?php echo ($currentChannel === 'phone' && !empty($data['error'])) ? 'border-rose-500 ring-2 ring-rose-500/10' : 'border-slate-200'; ?> bg-white text-slate-900 text-xs sm:text-sm font-medium tracking-wide placeholder:text-slate-400 placeholder:font-normal outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10"
                            oninput="this.value = this.value.replace(/[^0-9]/g, ''); validatePhoneInput(this)">
                    </div>
                    <?php if ($currentChannel === 'phone' && !empty($data['field_error_message'])): ?>
                        <p class="text-rose-600 text-xs font-semibold mt-1 flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 shrink-0 text-rose-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <span><?php echo htmlspecialchars($data['field_error_message'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </p>
                    <?php endif; ?>
                    <p id="phone-error" class="text-red-500 text-xs mt-1 hidden">Please enter a valid 11-digit mobile number (e.g. 09951281511).</p>
                </div>

                <!-- EMAIL FIELD -->
                <div id="emailFieldBlock" class="<?php echo $currentChannel === 'phone' ? 'hidden' : ''; ?>">
                    <label class="block text-[11px] font-semibold uppercase tracking-wider text-slate-500 mb-1">
                        Email Address <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                        </div>
                        <input type="email" id="email" name="email"
                            value="<?php echo htmlspecialchars($currentChannel === 'email' ? ($data['contact'] ?? '') : '', ENT_QUOTES, 'UTF-8'); ?>"
                            placeholder="name@example.com"
                            class="w-full h-11 pl-9 pr-3.5 rounded-xl border <?php echo ($currentChannel === 'email' && !empty($data['error'])) ? 'border-rose-500 ring-2 ring-rose-500/10' : 'border-slate-200'; ?> bg-white text-slate-900 text-xs sm:text-sm font-medium tracking-wide placeholder:text-slate-400 placeholder:font-normal outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10">
                    </div>
                    <?php if ($currentChannel === 'email' && !empty($data['field_error_message'])): ?>
                        <p class="text-rose-600 text-xs font-semibold mt-1 flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 shrink-0 text-rose-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <span><?php echo htmlspecialchars($data['field_error_message'], ENT_QUOTES, 'UTF-8'); ?></span>
                        </p>
                    <?php endif; ?>
                    <p id="email-error" class="text-red-500 text-xs mt-1 hidden">Please enter a valid email address.</p>
                </div>

                <!-- Callout Notice Box -->
                <div class="p-3 rounded-xl bg-emerald-50/60 border border-emerald-100 text-slate-600 text-xs flex items-start gap-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 text-emerald-600 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/>
                        <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/>
                    </svg>
                    <p class="leading-relaxed" id="noticeText">
                        <?php if ($currentChannel === 'email'): ?>
                            We will send a 6-digit verification code to your email inbox. Check your spam folder if it doesn't arrive within 1 minute.
                        <?php else: ?>
                            Standard SMS rates may apply. Your number will only be used for this report verification and status notifications.
                        <?php endif; ?>
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
            <a href="<?php echo app_url('index.php?url=guest'); ?>" 
               class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-500 hover:text-slate-800 transition py-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5"/><path d="m12 19-7-7 7-7"/>
                </svg>
                <span>Back</span>
            </a>
        </div>

    </div>

    <script>
        let currentMode = '<?php echo $currentChannel; ?>';

        function switchChannel(mode) {
            currentMode = mode;
            document.getElementById('channelInput').value = mode;

            const tabPhone = document.getElementById('tabPhone');
            const tabEmail = document.getElementById('tabEmail');
            const phoneBlock = document.getElementById('phoneFieldBlock');
            const emailBlock = document.getElementById('emailFieldBlock');
            const noticeText = document.getElementById('noticeText');

            if (mode === 'phone') {
                tabPhone.className = 'py-2 rounded-lg transition-all flex items-center justify-center gap-1.5 bg-white text-slate-900 shadow-2xs';
                tabEmail.className = 'py-2 rounded-lg transition-all flex items-center justify-center gap-1.5 text-slate-500 hover:text-slate-800';
                phoneBlock.classList.remove('hidden');
                emailBlock.classList.add('hidden');
                noticeText.textContent = 'Standard SMS rates may apply. Your number will only be used for this report verification and status notifications.';
                document.getElementById('phone').focus();
            } else {
                tabEmail.className = 'py-2 rounded-lg transition-all flex items-center justify-center gap-1.5 bg-white text-slate-900 shadow-2xs';
                tabPhone.className = 'py-2 rounded-lg transition-all flex items-center justify-center gap-1.5 text-slate-500 hover:text-slate-800';
                emailBlock.classList.remove('hidden');
                phoneBlock.classList.add('hidden');
                noticeText.textContent = 'We will send a 6-digit verification code to your email inbox. Check your spam folder if it does not arrive within 1 minute.';
                document.getElementById('email').focus();
            }
        }

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

        function validateContactForm() {
            if (currentMode === 'phone') {
                const phone = document.getElementById('phone');
                const val = phone.value.trim();
                if (!/^09\d{9}$/.test(val)) {
                    phone.classList.add('border-red-500');
                    document.getElementById('phone-error').classList.remove('hidden');
                    phone.focus();
                    return false;
                }
            } else {
                const email = document.getElementById('email');
                const val = email.value.trim();
                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!re.test(val)) {
                    email.classList.add('border-red-500');
                    document.getElementById('email-error').classList.remove('hidden');
                    email.focus();
                    return false;
                }
            }
            return true;
        }
    </script>
</body>
</html>
