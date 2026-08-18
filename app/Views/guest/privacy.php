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
    <title>Privacy Notice · <?php echo htmlspecialchars($shortName); ?></title>
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
            <a href="<?php echo app_url(''); ?>" class="inline-flex items-center gap-3 group transition">
                <!-- Circular Logo -->
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

        <!-- Main Card -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-7 shadow-xs space-y-5">
            
            <!-- Shield Icon -->
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-700 border border-emerald-100 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>

            <!-- Title & Subtitle -->
            <div class="space-y-1">
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">Privacy Notice</h1>
                <p class="text-xs text-slate-500 leading-relaxed">
                    Before proceeding, please understand how Barangay <?php echo htmlspecialchars($brgyName); ?> will use your information.
                </p>
            </div>

            <!-- Checklist Items -->
            <div class="space-y-3 pt-1">
                <div class="flex items-center gap-3">
                    <div class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                    <span class="text-xs text-slate-700">Verify your identity as the reporter</span>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                    <span class="text-xs text-slate-700">Process and investigate your waste report</span>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                    <span class="text-xs text-slate-700">Prevent spam and fraudulent submissions</span>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                    <span class="text-xs text-slate-700">Check the plausibility of the reported location</span>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-4 h-4 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                    </div>
                    <span class="text-xs text-slate-700">Send report-status updates through SMS</span>
                </div>
            </div>

            <!-- Optional Name Callout Box -->
            <div class="p-3.5 rounded-xl bg-emerald-50/60 border border-emerald-100 text-slate-600 text-xs leading-relaxed">
                <p>
                    <span class="font-semibold text-slate-800">Your name is optional.</span> Only a mobile number is required for verification and SMS updates. No account is created.
                </p>
            </div>
        </div>

        <!-- Info Card -->
        <div class="p-3.5 rounded-xl bg-blue-50/60 border border-blue-100 text-blue-900 text-xs flex items-start gap-2.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 text-blue-600 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="16" x2="12" y2="12"/>
                <line x1="12" y1="8" x2="12.01" y2="8"/>
            </svg>
            <p class="text-blue-800 leading-relaxed">
                Guest reports are tracked using your mobile number and a unique tracking number &mdash; no login required.
            </p>
        </div>

        <!-- CTA Button -->
        <div>
            <a href="<?php echo app_url('index.php?url=guest/phone'); ?>" 
               class="w-full py-3 bg-[#0B2E22] hover:bg-[#07281E] text-white font-semibold rounded-xl shadow-xs hover:shadow transition-all flex items-center justify-center gap-2 text-xs sm:text-sm active:scale-[0.99]">
                <span>I Understand &amp; Agree</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="m9 18 6-6-6-6"/>
                </svg>
            </a>
        </div>

        <!-- Return to Homepage Link -->
        <div class="text-center">
            <a href="<?php echo app_url(''); ?>" 
               class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-500 hover:text-slate-800 transition py-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M19 12H5"/><path d="m12 19-7-7 7-7"/>
                </svg>
                <span>Return to Homepage</span>
            </a>
        </div>

    </div>

</body>
</html>
