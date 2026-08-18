<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Submitted · WasteWatch Guest</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Miranda+Sans:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
    <style>
        body, * { font-family: 'Miranda Sans', sans-serif !important; font-optical-sizing: auto; }
        @keyframes scaleIn { from { transform: scale(0.3); opacity: 0; } to { transform: scale(1); opacity: 1; } }
        @keyframes fadeUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .scale-in { animation: scaleIn 0.5s cubic-bezier(0.34,1.56,0.64,1) both; }
        .fade-up { animation: fadeUp 0.5s ease both; }
        .fade-up-1 { animation-delay: 0.2s; }
        .fade-up-2 { animation-delay: 0.35s; }
        .fade-up-3 { animation-delay: 0.5s; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased min-h-screen flex flex-col justify-center items-center px-4 py-16">

    <div class="w-full max-w-md text-center">

        <!-- Success Icon -->
        <div class="flex justify-center mb-6 scale-in">
            <div class="w-20 h-20 rounded-full bg-emerald-100 border-4 border-emerald-200 flex items-center justify-center shadow-lg shadow-emerald-100/60">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
        </div>

        <!-- Progress Steps -->
        <div class="flex items-center gap-2 mb-8 fade-up fade-up-1">
            <?php $labels = ['Verify', 'Details', 'Review', 'Done']; ?>
            <?php foreach ($labels as $i => $label): ?>
                <div class="flex items-center <?php echo $i < count($labels)-1 ? 'flex-1' : ''; ?>">
                    <div class="flex items-center gap-1.5">
                        <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold bg-emerald-600 text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <span class="text-xs font-medium text-slate-400 hidden sm:block"><?php echo $label; ?></span>
                    </div>
                    <?php if ($i < count($labels)-1): ?>
                    <div class="flex-1 h-px bg-emerald-300 mx-2"></div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Headline -->
        <div class="fade-up fade-up-1">
            <h1 class="text-2xl font-bold text-slate-900">Report Submitted!</h1>
            <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                Thank you for helping keep our barangay clean. Your report has been received and is being reviewed.
            </p>
        </div>

        <!-- Tracking Number Card -->
        <div class="mt-7 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm fade-up fade-up-2">
            <div class="text-xs text-slate-500 font-medium uppercase tracking-wider mb-2">Your Tracking Number</div>
            <div class="text-3xl font-bold tracking-widest text-emerald-700 bg-emerald-50 rounded-xl px-4 py-3 select-all border border-emerald-200/80 mb-4">
                <?php echo htmlspecialchars($data['tracking_number'], ENT_QUOTES, 'UTF-8'); ?>
            </div>
            <button onclick="copyTracking()" id="copyBtn"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition">
                <svg id="copyIcon" xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>
                Copy Tracking Number
            </button>
        </div>

        <!-- What Happens Next -->
        <div class="mt-5 bg-white border border-slate-200 rounded-2xl p-5 shadow-sm fade-up fade-up-2 text-left">
            <h2 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">What happens next</h2>
            <div class="space-y-3">
                <?php
                $steps = [
                    ['svg' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>', 'text' => 'Our team will review and verify your report within 24 hours.'],
                    ['svg' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="20" x="5" y="2" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>', 'text' => "You'll receive SMS updates at {$data['phone']} as the status changes."],
                    ['svg' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 17h4V5H2v12h3"/><path d="M20 17h2v-3.34a4 4 0 0 0-1.17-2.83L19 9h-5v8h1"/><circle cx="7.5" cy="17.5" r="2.5"/><circle cx="17.5" cy="17.5" r="2.5"/></svg>', 'text' => 'An assigned team will handle the waste collection or cleanup.'],
                    ['svg' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>', 'text' => 'You\'ll be notified when the issue is resolved.'],
                ];
                foreach ($steps as $step): ?>
                <div class="flex items-start gap-3">
                    <span class="w-6 h-6 rounded-lg bg-emerald-50 border border-emerald-100 flex items-center justify-center shrink-0 mt-0.5"><?php echo $step['svg']; ?></span>
                    <p class="text-xs text-slate-600 leading-relaxed pt-0.5"><?php echo $step['text']; ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- CTA Buttons -->
        <div class="mt-6 flex flex-col gap-3 fade-up fade-up-3">
            <a href="<?php echo app_url('index.php?url=guest/track&tn=' . (urlencode($data['tracking_number']))); ?>"
                class="w-full h-11 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl shadow-sm transition flex items-center justify-center gap-2 text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                Track My Report
            </a>
            <a href="<?php echo app_url('index.php?url=guest'); ?>"
                class="w-full h-11 bg-white border border-slate-200 text-slate-700 font-semibold rounded-xl hover:bg-slate-50 transition flex items-center justify-center gap-2 text-sm shadow-sm">
                Submit Another Report
            </a>
            <a href="<?php echo app_url(''); ?>"
                class="text-xs text-slate-400 hover:text-slate-600 font-medium transition">
                Return to Home
            </a>
        </div>
    </div>

    <script>
        function copyTracking() {
            const tn = <?php echo json_encode($data['tracking_number']); ?>;
            navigator.clipboard.writeText(tn).then(() => {
                const btn = document.getElementById('copyBtn');
                btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline mr-1.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>Copied!';
                btn.classList.add('bg-emerald-100','text-emerald-700');
                setTimeout(() => {
                    btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline mr-1.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="14" x="8" y="8" rx="2" ry="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/></svg>Copy Tracking Number';
                    btn.classList.remove('bg-emerald-100','text-emerald-700');
                }, 2000);
            });
        }
    </script>
</body>
</html>
