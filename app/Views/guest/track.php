<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Report · WasteWatch</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased min-h-screen flex flex-col justify-center items-center px-4 py-12">

<div class="w-full max-w-[440px]">

    <!-- Logo -->
    <div class="flex items-center justify-center gap-2 mb-8">
        <div class="w-9 h-9 rounded-lg bg-emerald-600 flex items-center justify-center text-white shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </div>
        <span class="text-base font-bold text-slate-900">WasteWatch</span>
    </div>

    <!-- Header -->
    <div class="text-center mb-8">
        <div class="w-14 h-14 rounded-2xl bg-blue-50 border border-blue-200/70 flex items-center justify-center mx-auto mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        </div>
        <h1 class="text-2xl font-bold text-slate-900">Track Your Report</h1>
        <p class="text-sm text-slate-500 mt-1">Enter your tracking number and registered mobile number.</p>
    </div>

    <!-- Error Alert -->
    <?php if (!empty($data['error'])): ?>
    <div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200/80 text-red-700 text-xs flex items-start gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 text-red-500 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <span class="font-medium"><?php echo htmlspecialchars($data['error'], ENT_QUOTES, 'UTF-8'); ?></span>
    </div>
    <?php endif; ?>

    <!-- Track Form -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <form action="/brgy-waste-app-v3/public/guest/trackStatus" method="POST" class="space-y-4">

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Tracking Number <span class="text-red-500">*</span></label>
                <input type="text" name="tracking_number" required
                    value="<?php echo htmlspecialchars($data['tracking_number'] ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                    placeholder="WRS-2026-XXXXX"
                    class="w-full h-11 px-3.5 rounded-xl border border-slate-200 bg-slate-50 text-slate-900 text-sm font-mono placeholder:text-slate-400 outline-none transition-all focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10 uppercase"
                    oninput="this.value = this.value.toUpperCase()">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Registered Mobile Number <span class="text-red-500">*</span></label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm">🇵🇭</span>
                    <input type="tel" name="phone" required placeholder="09XX XXX XXXX" maxlength="11"
                        class="w-full h-11 pl-10 pr-3.5 rounded-xl border border-slate-200 bg-slate-50 text-slate-900 text-sm placeholder:text-slate-400 outline-none transition-all focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/10"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                </div>
            </div>

            <button type="submit" class="w-full h-11 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all flex items-center justify-center gap-2 active:scale-[0.99]">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                Find My Report
            </button>
        </form>
    </div>

    <!-- Back / Report New -->
    <div class="mt-6 text-center space-y-2">
        <p class="text-xs text-slate-500">
            Don't have a tracking number?
            <a href="/brgy-waste-app-v3/public/index.php?url=guest" class="font-semibold text-emerald-600 hover:underline ml-1">Submit a report</a>
        </p>
        <p class="text-xs text-slate-400">
            <a href="/brgy-waste-app-v3/public/" class="hover:underline">← Back to home</a>
        </p>
    </div>
</div>

</body>
</html>
