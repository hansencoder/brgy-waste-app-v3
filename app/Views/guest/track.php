<?php
$barangay = $data['barangay'] ?? [];
$systemName = $barangay['system_name'] ?? 'Barangay Waste Management System';
$shortName  = $barangay['system_short_name'] ?? 'WasteWatch';
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
    <title>Track Waste Report · <?php echo htmlspecialchars($shortName); ?></title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts Miranda Sans -->
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

        <!-- Main Card -->
        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-7 shadow-xs space-y-5">
            
            <!-- Header Info -->
            <div class="text-center space-y-1.5">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center mx-auto text-emerald-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                </div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">
                    Track Incident Report
                </h1>
                <p class="text-xs text-slate-500 leading-relaxed max-w-xs mx-auto">
                    Check real-time resolution status and inspection updates for your submitted report.
                </p>
            </div>

            <!-- Error Notification Alert -->
            <?php if (!empty($data['error'])): ?>
                <div class="p-3.5 rounded-xl bg-red-50 border border-red-200 text-red-800 text-xs font-medium flex items-start gap-2.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 text-red-600 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <div class="flex-1 leading-relaxed">
                        <?php echo htmlspecialchars($data['error'], ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <form action="/brgy-waste-app-v3/public/index.php?url=guest/trackStatus" method="POST" class="space-y-4">
                
                <!-- Tracking Number Input -->
                <div class="space-y-1">
                    <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider">
                        Tracking Number <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" name="tracking_number" required
                               value="<?php echo htmlspecialchars($data['tracking_number'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                               placeholder="WRS-2026-XXXXX"
                               class="w-full h-11 pl-3.5 pr-9 rounded-xl border border-slate-200 bg-slate-50 text-slate-900 text-xs sm:text-sm font-mono font-medium placeholder:text-slate-400 outline-none transition focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10 uppercase"
                               autocomplete="off">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                        </div>
                    </div>
                    <p class="text-[11px] text-slate-400">Enter the 14-character tracking reference sent to your SMS.</p>
                </div>

                <!-- Submit Button -->
                <div class="pt-1">
                    <button type="submit" 
                            class="w-full py-3 bg-[#0B2E22] hover:bg-[#07281E] text-white font-semibold rounded-xl shadow-xs hover:shadow transition-all flex items-center justify-center gap-2 text-xs sm:text-sm active:scale-[0.99] cursor-pointer">
                        <span>Check Status</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="9 18 15 12 9 6"/>
                        </svg>
                    </button>
                </div>
            </form>

            <div class="p-3 rounded-xl bg-emerald-50/60 border border-emerald-100 text-xs text-slate-600 flex items-start gap-2.5">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 text-emerald-600 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                <p class="leading-relaxed">
                    Lost your tracking code? You can review your SMS logs or call the barangay waste helpdesk for assistance.
                </p>
            </div>
        </div>

        <!-- Links -->
        <div class="flex items-center justify-center gap-4 text-xs font-medium text-slate-500">
            <a href="/brgy-waste-app-v3/public/index.php?url=guest" class="hover:text-slate-800 transition">Report an Incident</a>
            <span>•</span>
            <a href="/brgy-waste-app-v3/public/" class="hover:text-slate-800 transition">Return to Home</a>
        </div>

    </div>

</body>
</html>
