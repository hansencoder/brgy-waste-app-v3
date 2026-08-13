<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Waste as Guest · WasteWatch</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .step-dot { transition: all 0.3s ease; }
    </style>
</head>
<body class="h-full text-slate-900 antialiased selection:bg-emerald-500 selection:text-white">

<div class="min-h-screen w-full grid grid-cols-1 lg:grid-cols-12">

    <!-- LEFT PANEL -->
    <div class="hidden lg:flex lg:col-span-6 bg-[#081C15] text-white p-12 flex-col justify-between relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-emerald-900/30 via-transparent to-transparent pointer-events-none"></div>

        <!-- Logo -->
        <div class="relative z-10 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-emerald-500 flex items-center justify-center text-slate-950 font-bold shadow-md shadow-emerald-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>
            <div>
                <span class="text-base font-bold tracking-tight text-white block leading-none">WasteWatch</span>
                <span class="text-[11px] font-medium text-emerald-400/80">Guest Reporting</span>
            </div>
        </div>

        <!-- Center -->
        <div class="relative z-10 my-auto py-12">
            <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-medium mb-6">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>No Account Required</span>
            </div>
            <h2 class="text-3xl xl:text-4xl font-bold tracking-tight text-white leading-tight">
                Help keep our barangay clean.
            </h2>
            <p class="text-slate-400 text-sm mt-4 leading-relaxed max-w-sm">
                Report waste issues in your area without needing to create an account. Your verified mobile number keeps you informed of your report status.
            </p>

            <!-- Steps -->
            <div class="mt-10 pt-8 border-t border-slate-800/80 space-y-4">
                <?php
                $steps = [
                    ['icon' => '📱', 'title' => 'Verify your mobile number',  'desc' => 'Quick SMS OTP — 30 seconds'],
                    ['icon' => '📍', 'title' => 'Pin the waste location',      'desc' => 'Tap on map or use GPS'],
                    ['icon' => '📤', 'title' => 'Submit & get a tracking code','desc' => 'Track status anytime'],
                ];
                foreach ($steps as $i => $step): ?>
                <div class="flex items-start gap-3">
                    <div class="w-8 h-8 rounded-full bg-emerald-500/15 border border-emerald-500/20 flex items-center justify-center text-sm shrink-0 mt-0.5"><?php echo $step['icon']; ?></div>
                    <div>
                        <div class="text-sm font-semibold text-white"><?php echo $step['title']; ?></div>
                        <div class="text-xs text-slate-400 mt-0.5"><?php echo $step['desc']; ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Bottom -->
        <div class="relative z-10 border-t border-slate-800/80 pt-6 text-xs text-slate-500">
            Already have an account?
            <a href="/brgy-waste-app-v3/public/index.php?url=auth" class="text-emerald-400 hover:underline font-semibold ml-1">Sign in instead</a>
        </div>
    </div>

    <!-- RIGHT PANEL -->
    <div class="lg:col-span-6 flex flex-col justify-center items-center p-6 sm:p-12 bg-slate-50 min-h-screen lg:min-h-0">
        <div class="w-full max-w-[420px]">

            <!-- Back -->
            <a href="/brgy-waste-app-v3/public/" class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-900 text-sm font-semibold mb-6 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="m12 5-7 7 7 7"/></svg>
                Back to home
            </a>

            <!-- Progress Steps -->
            <div class="flex items-center gap-2 mb-8">
                <?php $labels = ['Verify', 'Details', 'Review', 'Done']; ?>
                <?php foreach ($labels as $i => $label): ?>
                    <div class="flex items-center <?php echo $i < count($labels)-1 ? 'flex-1' : ''; ?>">
                        <div class="flex items-center gap-1.5">
                            <div class="step-dot w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold <?php echo $i === 0 ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-500'; ?>">
                                <?php echo $i + 1; ?>
                            </div>
                            <span class="text-xs font-medium <?php echo $i === 0 ? 'text-slate-900' : 'text-slate-400'; ?> hidden sm:block"><?php echo $label; ?></span>
                        </div>
                        <?php if ($i < count($labels)-1): ?>
                        <div class="flex-1 h-px bg-slate-200 mx-2"></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Header -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Verify your mobile number</h1>
                <p class="text-sm text-slate-500 mt-1">We'll send a 6-digit code to confirm your number.</p>
            </div>

            <!-- Error Alert -->
            <?php if (!empty($data['error'])): ?>
            <div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200/80 text-red-700 text-xs flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 text-red-500 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span class="font-medium"><?php echo htmlspecialchars($data['error'], ENT_QUOTES, 'UTF-8'); ?></span>
            </div>
            <?php endif; ?>

            <!-- Form -->
            <form action="/brgy-waste-app-v3/public/guest/sendOtp" method="POST" class="space-y-5" onsubmit="return validatePrivacyForm()">



                <!-- Optional Name -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Your name <span class="text-slate-400 font-normal">(optional)</span></label>
                    <input type="text" id="guest_name" name="guest_name" placeholder="Juan Dela Cruz"
                        class="w-full h-11 px-3.5 rounded-xl border border-slate-200 bg-white text-slate-900 text-sm placeholder:text-slate-400 outline-none transition-all focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10"
                        oninput="this.value = this.value.replace(/[^a-zA-Z\s\-.]/g, '')">
                </div>

                <!-- Phone Number -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Mobile number <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-medium">🇵🇭</span>
                        <input type="tel" id="phone" name="phone" required placeholder="09XX XXX XXXX" maxlength="11"
                            class="w-full h-11 pl-10 pr-3.5 rounded-xl border border-slate-200 bg-white text-slate-900 text-sm placeholder:text-slate-400 outline-none transition-all focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10"
                            oninput="this.value = this.value.replace(/[^0-9]/g, ''); validatePhone(this)">
                    </div>
                    <p id="phone-error" class="text-red-500 text-xs font-medium mt-1.5 hidden">Please enter a valid Philippine mobile number (09XXXXXXXXX).</p>
                </div>

                <!-- Privacy Notice Card -->
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200/80 text-emerald-800 text-xs space-y-1.5">
                    <div class="font-semibold text-xs text-emerald-700 flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        Privacy Notice
                    </div>
                    <p class="text-emerald-700/80 leading-relaxed">Your mobile number is used only to verify your identity and send report status updates. It will not be shared publicly or used for marketing.</p>
                </div>

                <!-- Anti-Abuse Warning Notice -->
                <div class="p-3.5 rounded-xl bg-amber-50 border border-amber-200/80 text-amber-800 text-xs space-y-1">
                    <div class="font-semibold text-amber-700 flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        Anti-Spam & Abuse Protection
                    </div>
                    <p class="text-amber-700/80 leading-relaxed">Verification codes are limited to 3 per hour per number/IP. Submitting false reports or spamming will result in your mobile number being blocked.</p>
                </div>

                <!-- Agreement -->
                <div class="flex items-start gap-3 pt-1">
                    <input type="checkbox" id="agree" name="agree" required class="w-4 h-4 mt-0.5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-600/20 cursor-pointer">
                    <label for="agree" class="text-xs text-slate-600 font-medium cursor-pointer leading-relaxed">
                        I agree to the <a href="#" class="text-emerald-600 hover:underline font-semibold">Privacy Policy</a> and consent to my mobile number being used for report verification and status updates.
                    </label>
                </div>

                <!-- CTA -->
                <button type="submit" id="sendOtpBtn" class="w-full h-11 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all flex items-center justify-center gap-2 focus:ring-2 focus:ring-emerald-600/20 active:scale-[0.99]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13a19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2.18h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    Send Verification Code
                </button>

                <!-- Already have tracking number -->
                <p class="text-center text-xs text-slate-500 pt-1">
                    Already submitted a report?
                    <a href="/brgy-waste-app-v3/public/index.php?url=guest/track" class="font-semibold text-emerald-600 hover:text-emerald-700 hover:underline">Track your report</a>
                </p>
            </form>
        </div>
    </div>
</div>

<script>
    function validatePhone(el) {
        const err = document.getElementById('phone-error');
        const val = el.value;
        const valid = /^09\d{9}$/.test(val);
        if (val.length >= 11) {
            if (!valid) {
                err.classList.remove('hidden');
                el.classList.add('border-red-500', 'ring-2', 'ring-red-500/10');
            } else {
                err.classList.add('hidden');
                el.classList.remove('border-red-500', 'ring-2', 'ring-red-500/10');
            }
        } else {
            err.classList.add('hidden');
        }
    }

    function validatePrivacyForm() {
        const phone = document.getElementById('phone');
        const valid = /^09\d{9}$/.test(phone.value);
        if (!valid) {
            phone.classList.add('border-red-500', 'ring-2', 'ring-red-500/10');
            document.getElementById('phone-error').classList.remove('hidden');
            return false;
        }
        return true;
    }
</script>

</body>
</html>
