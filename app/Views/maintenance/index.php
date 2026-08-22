<?php
$status      = $data['status']    ?? [];
$barangay    = $data['barangay']  ?? [];

$isEmergency = ($status['maintenance_type'] ?? 'scheduled') === 'emergency';
$message     = htmlspecialchars($status['maintenance_message'] ?? ($isEmergency 
    ? 'The system is temporarily unavailable due to an emergency situation. Please check back later or contact the Barangay Hall for urgent concerns.' 
    : 'The system is currently undergoing scheduled maintenance. We apologize for any inconvenience and will be back shortly.'));
$endAt       = $status['end_at'] ?? null;
$systemName  = htmlspecialchars($barangay['system_name'] ?? 'Barangay Waste Management System');
$systemShort = htmlspecialchars($barangay['system_short_name'] ?? 'WasteWatch');
$brgyName    = htmlspecialchars($barangay['barangay_name'] ?? 'Barangay Dulong Bayan');

// Theme tokens
$theme = $isEmergency ? [
    'accent'       => '#DC2626',
    'accentHover'  => '#B91C1C',
    'badgeText'    => 'Emergency Lockdown',
    'badgeSub'     => 'Immediate Security & Incident Intervention',
    'title'        => 'Emergency System Lockdown',
    'badgeBg'      => 'bg-red-500/10 text-red-400 border-red-500/30',
    'cardHeaderBg' => 'from-red-950/40 via-red-900/20 to-transparent',
    'ringColor'    => 'border-red-500/30',
    'iconBg'       => 'from-red-600 to-rose-700',
    'dotColor'     => 'bg-red-500',
    'boxBg'        => 'bg-red-950/30 border-red-500/20 text-red-200',
    'boxTitle'     => 'Emergency Incident Advisory',
] : [
    'accent'       => '#D97706',
    'accentHover'  => '#B45309',
    'badgeText'    => 'Scheduled Maintenance',
    'badgeSub'     => 'Routine System Upgrades & Infrastructure Maintenance',
    'title'        => 'System Under Scheduled Maintenance',
    'badgeBg'      => 'bg-amber-500/10 text-amber-400 border-amber-500/30',
    'cardHeaderBg' => 'from-amber-950/40 via-amber-900/20 to-transparent',
    'ringColor'    => 'border-amber-500/30',
    'iconBg'       => 'from-amber-500 to-orange-600',
    'dotColor'     => 'bg-amber-500',
    'boxBg'        => 'bg-amber-950/30 border-amber-500/20 text-amber-200',
    'boxTitle'     => 'Maintenance Notice',
];
?>
<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $theme['title']; ?> — <?php echo $systemShort; ?></title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body, * { font-family: 'Miranda Sans', 'Plus Jakarta Sans', sans-serif !important; font-optical-sizing: auto; }
        
        @keyframes pulse-radar {
            0%   { transform: scale(0.85); opacity: 0.9; }
            50%  { transform: scale(1.6);  opacity: 0.3; }
            100% { transform: scale(2.2);  opacity: 0; }
        }
        @keyframes float-gentle {
            0%, 100% { transform: translateY(0px); }
            50%      { transform: translateY(-8px); }
        }
        @keyframes sweep {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }
        @keyframes progress-shimmer {
            0%   { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }

        .radar-ring  { animation: pulse-radar 2.8s cubic-bezier(0.215, 0.61, 0.355, 1) infinite; }
        .radar-ring-2{ animation: pulse-radar 2.8s cubic-bezier(0.215, 0.61, 0.355, 1) infinite 1.4s; }
        .float-icon  { animation: float-gentle 4s ease-in-out infinite; }
        .radar-sweep { animation: sweep 8s linear infinite; }
        
        .hero-glass {
            background: linear-gradient(145deg, rgba(15, 23, 42, 0.92) 0%, rgba(10, 15, 29, 0.96) 100%);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 30px 90px -20px rgba(0, 0, 0, 0.85), 0 0 50px -10px <?php echo $isEmergency ? 'rgba(220, 38, 38, 0.18)' : 'rgba(217, 119, 6, 0.15)'; ?>;
        }

        .shimmer-line {
            background: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.15) 50%, rgba(255,255,255,0) 100%);
            background-size: 200% 100%;
            animation: progress-shimmer 3.5s infinite;
        }
    </style>
</head>
<body class="min-h-screen bg-[#070B14] text-slate-100 flex flex-col justify-between p-4 sm:p-6 lg:p-8 relative overflow-x-hidden selection:bg-red-500 selection:text-white">

    <!-- ═══════════════════════════════════════════════════
         AMBIENT GLOWS & BACKGROUND GRID
    ════════════════════════════════════════════════════ -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden" aria-hidden="true">
        <!-- Grid Pattern -->
        <div class="absolute inset-0 opacity-[0.03]"
             style="background-image: linear-gradient(to right, #ffffff 1px, transparent 1px), linear-gradient(to bottom, #ffffff 1px, transparent 1px); background-size: 40px 40px;"></div>

        <!-- Top center accent glow -->
        <div class="absolute top-[-15%] left-1/2 -translate-x-1/2 w-[650px] h-[450px] rounded-full blur-[140px] opacity-25"
             style="background: radial-gradient(circle, <?php echo $theme['accent']; ?> 0%, transparent 70%);"></div>

        <!-- Secondary teal/emerald ambiance -->
        <div class="absolute bottom-[-10%] right-[-5%] w-[500px] h-[500px] rounded-full blur-[160px] opacity-15"
             style="background: radial-gradient(circle, #059669 0%, transparent 70%);"></div>

        <!-- Tertiary blue ambient corner -->
        <div class="absolute top-[20%] left-[-10%] w-[450px] h-[450px] rounded-full blur-[150px] opacity-10"
             style="background: radial-gradient(circle, #3b82f6 0%, transparent 70%);"></div>
    </div>

    <!-- Top Header -->
    <header class="relative z-10 w-full max-w-2xl mx-auto flex items-center justify-between gap-4 py-2">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-xl bg-slate-900 border border-slate-700/60 flex items-center justify-center shadow-xs">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>
            <div>
                <p class="text-xs font-black tracking-tight text-white"><?php echo $systemShort; ?></p>
                <p class="text-[10px] font-bold text-slate-400 leading-none"><?php echo $brgyName; ?></p>
            </div>
        </div>

        <!-- Status Badge -->
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full border text-[11px] font-extrabold tracking-wider uppercase shadow-2xs <?php echo $theme['badgeBg']; ?>">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full <?php echo $theme['dotColor']; ?> opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 <?php echo $theme['dotColor']; ?>"></span>
            </span>
            <span><?php echo $theme['badgeText']; ?></span>
        </div>
    </header>

    <!-- Main Container -->
    <main class="relative z-10 w-full max-w-2xl mx-auto my-auto py-6">

        <div class="hero-glass rounded-3xl overflow-hidden border border-white/10 relative">

            <!-- Decorative top highlight bar -->
            <div class="h-1 w-full" style="background: linear-gradient(90deg, <?php echo $theme['accent']; ?>, <?php echo $isEmergency ? '#f43f5e' : '#fbbf24'; ?>, <?php echo $theme['accent']; ?>);"></div>

            <div class="p-6 sm:p-10 space-y-8 text-center">

                <!-- ═══════════════════════════════════════════════════
                     BEACON RADAR & ANIMATED ICON
                ════════════════════════════════════════════════════ -->
                <div class="relative flex items-center justify-center py-2">
                    <!-- Radar concentric pulse waves -->
                    <div class="absolute w-36 h-36 rounded-full radar-ring pointer-events-none" style="border: 2px solid <?php echo $theme['accent']; ?>;"></div>
                    <div class="absolute w-36 h-36 rounded-full radar-ring-2 pointer-events-none" style="border: 2px solid <?php echo $theme['accent']; ?>;"></div>

                    <!-- Outer dashed decorative ring with rotation -->
                    <div class="absolute w-28 h-28 rounded-full border border-dashed <?php echo $theme['ringColor']; ?> radar-sweep pointer-events-none"></div>

                    <!-- Floating Icon Badge -->
                    <div class="float-icon relative z-10 w-20 h-20 sm:w-22 sm:h-22 rounded-3xl flex items-center justify-center shadow-2xl bg-gradient-to-br <?php echo $theme['iconBg']; ?> p-0.5 border border-white/20">
                        <div class="w-full h-full rounded-[22px] bg-slate-950/30 flex items-center justify-center backdrop-blur-xs">
                            <?php if ($isEmergency): ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-white drop-shadow-md" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/>
                                    <line x1="12" y1="9" x2="12" y2="13"/>
                                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                                </svg>
                            <?php else: ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-white drop-shadow-md" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                                </svg>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- ═══════════════════════════════════════════════════
                     HEADINGS & ADVISORY NOTICE
                ════════════════════════════════════════════════════ -->
                <div class="space-y-3">
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-white tracking-tight leading-tight">
                        <?php echo $theme['title']; ?>
                    </h1>
                    <p class="text-xs sm:text-sm text-slate-400 font-medium max-w-lg mx-auto leading-relaxed">
                        Public portals and non-administrative report submissions are temporarily paused.
                    </p>
                </div>

                <!-- Advisory Message Box -->
                <div class="rounded-2xl p-5 sm:p-6 text-left border relative overflow-hidden <?php echo $theme['boxBg']; ?> backdrop-blur-md">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="w-2 h-2 rounded-full <?php echo $theme['dotColor']; ?>"></span>
                        <h3 class="text-xs font-black uppercase tracking-wider text-white">
                            <?php echo $theme['boxTitle']; ?>
                        </h3>
                    </div>
                    <p class="text-xs sm:text-sm font-medium leading-relaxed text-slate-200">
                        <?php echo $message; ?>
                    </p>
                </div>

                <!-- ═══════════════════════════════════════════════════
                     STATUS METADATA GRID
                ════════════════════════════════════════════════════ -->
                <div class="grid grid-cols-1 sm:grid-cols-<?php echo $endAt ? '2' : '1'; ?> gap-3 text-left">
                    <!-- Current Status Metric -->
                    <div class="p-4 rounded-2xl bg-slate-900/70 border border-slate-800 flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Current Status</p>
                            <p class="text-xs sm:text-sm font-extrabold text-white mt-0.5 flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full <?php echo $theme['dotColor']; ?> animate-pulse"></span>
                                <?php echo $theme['badgeText']; ?>
                            </p>
                        </div>
                        <span class="text-[10px] font-bold px-2 py-1 rounded-lg bg-slate-800 text-slate-300 border border-slate-700">
                            Active
                        </span>
                    </div>

                    <?php if ($endAt): ?>
                    <!-- Scheduled Return Metric -->
                    <div class="p-4 rounded-2xl bg-slate-900/70 border border-slate-800 flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-400">Expected Restoration</p>
                            <p class="text-xs sm:text-sm font-extrabold text-white mt-0.5 flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-emerald-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                <?php echo date('M j, Y · g:i A', strtotime($endAt)); ?>
                            </p>
                        </div>
                        <span class="text-[10px] font-bold px-2 py-1 rounded-lg bg-emerald-950/60 text-emerald-300 border border-emerald-800/60">
                            Auto-Timer
                        </span>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- ═══════════════════════════════════════════════════
                     DATA INTEGRITY & REASSURANCE NOTICE
                ════════════════════════════════════════════════════ -->
                <div class="pt-4 border-t border-slate-800/80 space-y-4">
                    <div class="flex items-center justify-center gap-2 text-xs font-bold text-slate-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-400 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
                        <span>All existing waste reports, accounts, and records remain safe &amp; intact.</span>
                    </div>

                    <!-- Live Auto-Reconnection Monitor -->
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-slate-900/90 border border-slate-800 text-[11px] text-slate-400 font-medium">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                        <span id="reconnectStatus">Live status monitoring active · Auto-refreshing</span>
                    </div>
                </div>

            </div>

        </div>

    </main>

    <!-- ═══════════════════════════════════════════════════
         FOOTER & ADMIN PORTAL ACCESS
    ════════════════════════════════════════════════════ -->
    <footer class="relative z-10 w-full max-w-2xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left py-2">
        <p class="text-xs text-slate-500 font-medium">
            For urgent community concerns, please contact the Barangay Hall desk directly.
        </p>

        <a href="<?php echo app_url('index.php?url=auth'); ?>"
           class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-slate-900/80 hover:bg-slate-800 text-slate-300 hover:text-white border border-slate-800 hover:border-slate-700 text-xs font-bold transition shadow-xs">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            <span>Authorized Official Login</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
        </a>
    </footer>

    <!-- ═══════════════════════════════════════════════════
         LIVE RECONNECT HEARTBEAT SCRIPT
    ════════════════════════════════════════════════════ -->
    <script>
        // Automatic periodic polling to restore instantly when maintenance ends
        let pollCount = 0;
        const pollInterval = setInterval(() => {
            pollCount++;
            fetch(window.location.href, { method: 'HEAD', cache: 'no-cache' })
                .then(res => {
                    // If redirected away from maintenance page, reload to go to active site
                    if (res.url && !res.url.includes('maintenance')) {
                        const statusEl = document.getElementById('reconnectStatus');
                        if (statusEl) statusEl.textContent = 'Service restored! Reloading...';
                        window.location.reload();
                    }
                })
                .catch(() => {});
        }, 15000);
    </script>

</body>
</html>
