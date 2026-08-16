<?php
$status    = $data['status']    ?? [];
$barangay  = $data['barangay']  ?? [];

$isEmergency = ($status['maintenance_type'] ?? 'scheduled') === 'emergency';
$message     = htmlspecialchars($status['maintenance_message'] ?? 'The system is currently undergoing scheduled maintenance.');
$endAt       = $status['end_at'] ?? null;
$systemName  = htmlspecialchars($barangay['system_name'] ?? 'Barangay Waste Management System');
$systemShort = htmlspecialchars($barangay['system_short_name'] ?? 'WasteWatch');
$brgyName    = htmlspecialchars($barangay['barangay_name'] ?? 'Barangay');

$accentColor   = $isEmergency ? '#dc2626' : '#d97706';
$accentLight   = $isEmergency ? '#fef2f2' : '#fffbeb';
$accentBorder  = $isEmergency ? '#fecaca' : '#fde68a';
$badgeText     = $isEmergency ? 'Emergency Lockdown' : 'Scheduled Maintenance';
$badgeDot      = $isEmergency ? 'bg-red-500'         : 'bg-amber-500';
$iconColor     = $isEmergency ? 'text-red-600'        : 'text-amber-600';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Maintenance — <?php echo $systemName; ?></title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * { font-family: 'Inter', sans-serif; }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50%       { transform: translateY(-12px); }
        }
        @keyframes pulse-ring {
            0%   { transform: scale(0.8); opacity: 1; }
            100% { transform: scale(2.2); opacity: 0; }
        }
        @keyframes spin-slow { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        .float-anim  { animation: float 4s ease-in-out infinite; }
        .spin-slow   { animation: spin-slow 12s linear infinite; }
        .pulse-ring  { animation: pulse-ring 2s ease-out infinite; }
        .glass-card  { backdrop-filter: blur(16px); background: rgba(255,255,255,0.7); border: 1px solid rgba(255,255,255,0.6); }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 flex items-center justify-center p-4">

    <!-- Decorative background orbs -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none" aria-hidden="true">
        <div class="absolute top-[-10%] right-[-5%] w-96 h-96 rounded-full opacity-10"
             style="background: radial-gradient(circle, <?php echo $accentColor; ?>, transparent)"></div>
        <div class="absolute bottom-[-10%] left-[-5%] w-80 h-80 rounded-full opacity-8"
             style="background: radial-gradient(circle, #059669, transparent)"></div>
        <div class="absolute top-1/2 left-1/4 w-64 h-64 rounded-full opacity-5"
             style="background: radial-gradient(circle, #3b82f6, transparent)"></div>
    </div>

    <div class="relative w-full max-w-2xl mx-auto">

        <!-- System Brand Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/20 text-white/70 text-xs font-semibold tracking-widest uppercase mb-4">
                <span class="w-1.5 h-1.5 rounded-full <?php echo $badgeDot; ?> animate-pulse"></span>
                <?php echo $badgeText; ?>
            </div>
            <p class="text-emerald-400/80 text-sm font-semibold tracking-wide"><?php echo $systemShort; ?> · <?php echo $brgyName; ?></p>
        </div>

        <!-- Main Card -->
        <div class="glass-card rounded-3xl p-8 sm:p-10 shadow-2xl text-center space-y-8">

            <!-- Animated Illustration -->
            <div class="relative flex items-center justify-center">
                <!-- Outer pulse ring -->
                <div class="absolute w-40 h-40 rounded-full pulse-ring"
                     style="border: 2px solid <?php echo $accentColor; ?>40;"></div>
                <!-- Middle ring -->
                <div class="absolute w-32 h-32 rounded-full border-2 opacity-20 spin-slow"
                     style="border-color: <?php echo $accentColor; ?>; border-style: dashed;"></div>
                <!-- Icon container -->
                <div class="float-anim relative z-10 w-24 h-24 rounded-2xl flex items-center justify-center shadow-xl"
                     style="background: linear-gradient(135deg, <?php echo $accentLight; ?>, white); border: 1px solid <?php echo $accentBorder; ?>;">
                    <?php if ($isEmergency): ?>
                    <!-- Emergency icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" viewBox="0 0 24 24" fill="none"
                         stroke="<?php echo $accentColor; ?>" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                        <line x1="12" y1="9" x2="12" y2="13"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                    <?php else: ?>
                    <!-- Maintenance/wrench icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" viewBox="0 0 24 24" fill="none"
                         stroke="<?php echo $accentColor; ?>" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>
                    </svg>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Status heading -->
            <div class="space-y-3">
                <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight leading-tight">
                    <?php echo $isEmergency ? 'Emergency System Lockdown' : 'System Temporarily Unavailable'; ?>
                </h1>
                <p class="text-slate-600 text-sm sm:text-base leading-relaxed max-w-lg mx-auto">
                    <?php echo $message; ?>
                </p>
            </div>

            <!-- Info cards row -->
            <div class="grid grid-cols-1 sm:grid-cols-<?php echo $endAt ? '2' : '1'; ?> gap-3">
                <!-- Status card -->
                <div class="rounded-2xl p-4 text-left" style="background:<?php echo $accentLight; ?>; border: 1px solid <?php echo $accentBorder; ?>;">
                    <p class="text-[10px] font-bold uppercase tracking-widest mb-1" style="color:<?php echo $accentColor; ?>;">Current Status</p>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full <?php echo $badgeDot; ?> animate-pulse"></span>
                        <span class="text-sm font-bold text-slate-800"><?php echo $badgeText; ?></span>
                    </div>
                </div>

                <?php if ($endAt): ?>
                <!-- Expected return card -->
                <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-4 text-left">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-emerald-700 mb-1">Expected Return</p>
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        <span class="text-sm font-bold text-slate-800"><?php echo date('F j, Y · g:i A', strtotime($endAt)); ?></span>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Divider -->
            <div class="border-t border-slate-100"></div>

            <!-- Footer message -->
            <div class="space-y-3">
                <p class="text-slate-500 text-xs leading-relaxed">
                    All your existing data and reports are safe. Access will be restored once maintenance is complete.
                    For urgent concerns, please contact the barangay hall directly.
                </p>
                <div class="flex items-center justify-center gap-2 text-slate-400 text-[11px] font-medium">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    <span><?php echo $systemName; ?> — Secured &amp; Maintained by the Barangay Administration</span>
                </div>
            </div>

        </div>

        <!-- Back to login link (for authenticated users who might still have session) -->
        <div class="text-center mt-6">
            <a href="/brgy-waste-app-v3/public/index.php?url=auth"
               class="text-sm text-white/50 hover:text-white/80 transition font-medium">
                Administrator? <span class="underline underline-offset-2">Sign in here</span>
            </a>
        </div>

    </div>

    <!-- Auto-refresh every 60 seconds to detect when maintenance ends -->
    <script>
        setTimeout(function() {
            fetch(window.location.href, { method: 'HEAD' })
                .then(function(res) {
                    // If server redirects away from maintenance page, refresh
                    if (res.url && !res.url.includes('maintenance')) {
                        window.location.reload();
                    }
                })
                .catch(function() {});
        }, 60000);
    </script>

</body>
</html>
