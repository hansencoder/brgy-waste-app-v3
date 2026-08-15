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
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    
    <!-- Google Fonts Miranda Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Miranda+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    
    <style>
        body, * { font-family: 'Miranda Sans', sans-serif !important; font-optical-sizing: auto; }
        
        .tracking-card {
            background: #ffffff;
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.07), 0 0 0 1px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased min-h-screen flex flex-col justify-center items-center px-4 py-8 sm:py-12 selection:bg-emerald-500 selection:text-white">

    <div class="w-full max-w-[460px] space-y-6">

        <!-- Top Branding & Navigation -->
        <div class="flex flex-col items-center text-center space-y-3">
            <a href="/brgy-waste-app-v3/public/" class="inline-flex items-center gap-3 group transition">
                <!-- Circular Logo Container -->
                <div class="w-12 h-12 rounded-full bg-emerald-700 p-0.5 shadow-md flex items-center justify-center overflow-hidden border-2 border-white ring-2 ring-emerald-600/20 group-hover:scale-105 transition">
                    <?php if (!empty($activeLogo)): ?>
                        <img src="<?php echo htmlspecialchars($activeLogo); ?>" alt="Logo" class="w-full h-full rounded-full object-cover">
                    <?php else: ?>
                        <div class="w-full h-full rounded-full bg-[#0B2E22] flex items-center justify-center text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="text-left">
                    <span class="text-base sm:text-lg font-extrabold text-slate-900 tracking-tight block leading-tight group-hover:text-emerald-800 transition">
                        <?php echo htmlspecialchars($shortName); ?>
                    </span>
                    <span class="text-xs font-semibold text-slate-500 block">
                        Barangay <?php echo htmlspecialchars($brgyName); ?>
                    </span>
                </div>
            </a>
        </div>

        <!-- Main Card -->
        <div class="tracking-card rounded-3xl p-6 sm:p-8 space-y-6 border border-slate-200/80">
            
            <!-- Header Info -->
            <div class="text-center space-y-2">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-200/70 flex items-center justify-center mx-auto text-emerald-700 shadow-2xs">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                </div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                    Track Incident Report
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 font-semibold max-w-xs mx-auto">
                    Check real-time resolution status and inspection updates for your submitted report.
                </p>
            </div>

            <!-- Error Notification Alert -->
            <?php if (!empty($data['error'])): ?>
                <div class="p-4 rounded-2xl bg-red-50 border border-red-200 text-red-900 text-xs font-bold flex items-start gap-3 shadow-2xs animate-shake">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0 text-red-600 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <div class="flex-1 leading-relaxed">
                        <?php echo htmlspecialchars($data['error'], ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <form action="/brgy-waste-app-v3/public/index.php?url=guest/trackStatus" method="POST" class="space-y-4">
                
                <!-- Tracking Number Input -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider">
                        Tracking Number <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" name="tracking_number" required
                               value="<?php echo htmlspecialchars($data['tracking_number'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                               placeholder="WRS-2026-XXXXX"
                               class="w-full h-12 pl-4 pr-10 rounded-xl border border-slate-200 bg-slate-50 text-slate-900 text-sm font-mono font-bold placeholder:text-slate-400 outline-none transition-all focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10 uppercase"
                               oninput="this.value = this.value.toUpperCase()">
                        <div class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M7 7h10"/><path d="M7 12h10"/><path d="M7 17h10"/></svg>
                        </div>
                    </div>
                    <span class="text-[11px] font-semibold text-slate-400 block">Issued upon report submission or received via SMS.</span>
                </div>

                <!-- Registered Phone Input -->
                <div class="space-y-1.5">
                    <label class="block text-xs font-extrabold text-slate-700 uppercase tracking-wider">
                        Registered Mobile Number <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 font-bold text-xs flex items-center gap-1">
                            <span>🇵🇭</span>
                            <span>+63</span>
                        </span>
                        <input type="tel" name="phone" required placeholder="09XX XXX XXXX" maxlength="11"
                               class="w-full h-12 pl-16 pr-4 rounded-xl border border-slate-200 bg-slate-50 text-slate-900 text-sm font-bold placeholder:text-slate-400 outline-none transition-all focus:bg-white focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>
                    <span class="text-[11px] font-semibold text-slate-400 block">The 11-digit mobile number used when filing.</span>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full h-12 mt-2 bg-[#0B2E22] hover:bg-[#084232] text-white text-sm font-extrabold rounded-xl shadow-xs transition-all flex items-center justify-center gap-2 border border-emerald-900 cursor-pointer active:scale-[0.99]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    Check Status Now
                </button>
            </form>

            <!-- Divider -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-xs font-bold text-slate-500">
                <span>Need to file a new report?</span>
                <a href="/brgy-waste-app-v3/public/index.php?url=guest" class="text-emerald-700 hover:text-emerald-900 font-extrabold hover:underline">
                    Report Waste &rarr;
                </a>
            </div>

        </div>

        <!-- Footer Navigation Links -->
        <div class="text-center space-y-2">
            <p class="text-xs font-bold text-slate-500">
                <a href="/brgy-waste-app-v3/public/" class="hover:text-emerald-700 transition inline-flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="m15 18-6-6 6-6"/></svg>
                    Return to Homepage
                </a>
            </p>
            <p class="text-[11px] font-semibold text-slate-400">
                Official Waste Management Portal · Barangay <?php echo htmlspecialchars($brgyName); ?>
            </p>
        </div>

    </div>

</body>
</html>
