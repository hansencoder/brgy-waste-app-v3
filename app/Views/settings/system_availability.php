<?php include __DIR__ . '/../layouts/header.php'; ?>

<style>
    body, * { font-family: 'Miranda Sans', sans-serif !important; font-optical-sizing: auto; }
    .mobile-sidebar-open #mobileSidebar { transform: translateX(0) !important; }
    #mobileSidebarOverlay { transition: opacity 0.25s ease, visibility 0.25s ease; opacity: 0; visibility: hidden; }
    .mobile-sidebar-open #mobileSidebarOverlay { opacity: 1; visibility: visible; }
    .modal-backdrop { transition: opacity 0.2s ease, visibility 0.2s ease; }
    .modal-box      { transition: transform 0.3s cubic-bezier(0.34,1.3,0.64,1), opacity 0.2s ease; }
    input[type="datetime-local"] { color-scheme: light; }
</style>

<?php
$status      = $data['status']  ?? [];
$history     = $data['history'] ?? [];
$isActive    = (bool)($status['maintenance_mode'] ?? 0);
$isEmergency = $isActive && ($status['maintenance_type'] ?? '') === 'emergency';

// Status styling configuration
if ($isEmergency) {
    $statusColor  = 'text-red-700';
    $statusBg     = 'bg-red-50 border-red-200';
    $statusDot    = 'bg-red-500';
    $statusLabel  = 'Emergency Lockdown';
    $heroBorder   = 'border-red-300';
    $heroBg       = 'bg-gradient-to-r from-red-50 via-rose-50/70 to-white';
} elseif ($isActive) {
    $statusColor  = 'text-amber-700';
    $statusBg     = 'bg-amber-50 border-amber-200';
    $statusDot    = 'bg-amber-500';
    $statusLabel  = 'Maintenance Active';
    $heroBorder   = 'border-amber-300';
    $heroBg       = 'bg-gradient-to-r from-amber-50 via-yellow-50/70 to-white';
} else {
    $statusColor  = 'text-emerald-700';
    $statusBg     = 'bg-emerald-50 border-emerald-200';
    $statusDot    = 'bg-emerald-500';
    $statusLabel  = 'Operational';
    $heroBorder   = 'border-slate-200';
    $heroBg       = 'bg-gradient-to-r from-emerald-50/80 via-teal-50/50 to-white';
}

$savedMessage = htmlspecialchars($status['maintenance_message'] ?? 'The system is currently undergoing scheduled maintenance. We apologize for any inconvenience and will be back shortly.');
$savedType    = $status['maintenance_type'] ?? 'scheduled';
$savedReason  = htmlspecialchars($status['reason'] ?? '');
$savedStart   = !empty($status['start_at']) ? date('Y-m-d\TH:i', strtotime($status['start_at'])) : '';
$savedEnd     = !empty($status['end_at'])   ? date('Y-m-d\TH:i', strtotime($status['end_at']))   : '';

function maintenanceActionLabel($action) {
    $labels = [
        'ENABLE_MAINTENANCE_MODE'    => 'Enabled Maintenance',
        'DISABLE_MAINTENANCE_MODE'   => 'Disabled Maintenance',
        'ENABLE_EMERGENCY_LOCKDOWN'  => 'Emergency Lockdown Activated',
        'DISABLE_EMERGENCY_LOCKDOWN' => 'Emergency Lockdown Lifted',
        'UPDATE_MAINTENANCE_SETTINGS'=> 'Settings Updated',
        'AUTO_DEACTIVATE_MAINTENANCE'=> 'Auto-Deactivated',
    ];
    return $labels[$action] ?? $action;
}
function maintenanceActionBadge($action) {
    if (str_contains($action, 'EMERGENCY')) return 'bg-red-100 text-red-800 border-red-200';
    if (str_contains($action, 'ENABLE'))    return 'bg-amber-100 text-amber-800 border-amber-200';
    if (str_contains($action, 'DISABLE') || str_contains($action, 'AUTO')) return 'bg-emerald-100 text-emerald-800 border-emerald-200';
    return 'bg-slate-100 text-slate-700 border-slate-200';
}
?>

<div class="min-h-screen bg-[#F8FAFC] text-slate-900 w-full flex font-sans antialiased">
    <div id="mobileSidebarOverlay" class="fixed inset-0 bg-slate-950/40 z-40 lg:hidden"></div>

    <div class="lg:flex lg:min-h-screen w-full">
        <?php include __DIR__ . '/../layouts/admin_sidebar.php'; ?>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <?php include __DIR__ . '/../layouts/admin_topbar.php'; ?>

            <main class="flex-1 overflow-y-auto">
                <div class="max-w-9xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

                    <!-- ═══════════════════════════════════════════════════
                         PAGE HEADER
                    ════════════════════════════════════════════════════ -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 sm:p-6 rounded-2xl border border-slate-200 shadow-2xs">
                        <div>
                            <div class="flex items-center gap-2 mb-1.5">
                                <a href="<?php echo app_url('settings'); ?>" class="text-xs font-bold text-slate-500 hover:text-emerald-700 transition">Settings Hub</a>
                                <span class="text-xs text-slate-300">/</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-700 border border-slate-200">System Availability</span>
                            </div>
                            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">System Availability &amp; Maintenance</h1>
                            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">
                                Manage planned system downtime windows, public advisory notices, and emergency system lockdowns.
                            </p>
                        </div>
                        <!-- Live status badge -->
                        <div class="shrink-0 flex items-center gap-3 px-4 py-2.5 rounded-xl border <?php echo $statusBg; ?> shadow-2xs">
                            <span class="relative flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full <?php echo $statusDot; ?> opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 <?php echo $statusDot; ?>"></span>
                            </span>
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Current Status</p>
                                <p class="text-xs font-black <?php echo $statusColor; ?>"><?php echo $statusLabel; ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Toast notification container -->
                    <div id="toastContainer" class="fixed top-5 right-5 z-[200] space-y-2 pointer-events-none"></div>

                    <!-- Content Layout -->
                    <div class="flex flex-col lg:flex-row gap-6 items-start">
                        <?php $activeTab = 'system_availability'; include __DIR__ . '/../layouts/settings_sidebar.php'; ?>

                        <div class="flex-1 min-w-0 space-y-6">

                            <!-- ═══════════════════════════════════════════════════
                                 1. LIVE STATUS HERO CARD
                            ════════════════════════════════════════════════════ -->
                            <div class="bg-white rounded-2xl border <?php echo $heroBorder; ?> shadow-2xs overflow-hidden">
                                <div class="p-5 sm:p-6 <?php echo $heroBg; ?>">
                                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-5">
                                        <div class="flex items-start sm:items-center gap-4">
                                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 shadow-2xs
                                                <?php echo $isEmergency ? 'bg-red-600 text-white' : ($isActive ? 'bg-amber-500 text-white' : 'bg-emerald-600 text-white'); ?>">
                                                <?php if ($isEmergency): ?>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                                <?php elseif ($isActive): ?>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                                                <?php else: ?>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <span class="text-[11px] font-bold uppercase tracking-wider <?php echo $statusColor; ?>">
                                                        <?php echo $isEmergency ? 'Emergency Mode' : ($isActive ? 'System In Maintenance' : 'Public Portals Online'); ?>
                                                    </span>
                                                    <span class="w-1.5 h-1.5 rounded-full <?php echo $statusDot; ?>"></span>
                                                </div>
                                                <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 mt-0.5">
                                                    <?php echo $isEmergency ? 'Emergency Lockdown Active' : ($isActive ? 'Scheduled Maintenance Mode Active' : 'System is Fully Operational'); ?>
                                                </h2>
                                                
                                                <?php if ($isActive): ?>
                                                    <p class="text-xs text-slate-600 mt-1 font-medium flex flex-wrap items-center gap-x-2 gap-y-1">
                                                        <?php if (!empty($status['updated_by_name'])): ?>
                                                            <span>Activated by <strong class="text-slate-800"><?php echo htmlspecialchars($status['updated_by_name']); ?></strong></span>
                                                        <?php endif; ?>
                                                        <?php if (!empty($status['updated_at'])): ?>
                                                            <span>• <?php echo date('M j, Y \a\t g:i A', strtotime($status['updated_at'])); ?></span>
                                                        <?php endif; ?>
                                                    </p>
                                                <?php else: ?>
                                                    <p class="text-xs text-slate-500 mt-1 font-medium">
                                                        Residents, guests, and field officers have uninterrupted access to report submission and dashboard portals.
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <!-- Quick Primary State Action Button -->
                                        <div class="shrink-0 flex items-center gap-3">
                                            <?php if ($isEmergency): ?>
                                                <button onclick="confirmAction('deactivate_emergency')"
                                                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-red-700 hover:bg-red-800 text-white font-bold text-xs shadow-xs hover:shadow-sm transition active:scale-98">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 9.9-1"/></svg>
                                                    Lift Emergency Lockdown
                                                </button>
                                            <?php elseif ($isActive): ?>
                                                <button onclick="confirmAction('deactivate')"
                                                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs shadow-xs hover:shadow-sm transition active:scale-98">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                                    Restore to Operational
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <?php if ($isActive && !empty($status['end_at'])): ?>
                                    <div class="mt-4 pt-3.5 border-t <?php echo $isEmergency ? 'border-red-200/80' : 'border-amber-200/80'; ?> flex flex-wrap items-center justify-between gap-2 text-xs font-semibold <?php echo $isEmergency ? 'text-red-800' : 'text-amber-900'; ?>">
                                        <div class="flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                            <span>Automatic Deactivation Scheduled: <strong><?php echo date('F j, Y \a\t g:i A', strtotime($status['end_at'])); ?></strong></span>
                                        </div>
                                        <span class="text-[11px] px-2.5 py-0.5 rounded-md bg-white/70 border border-amber-300/80">Auto-Timer Active</span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- ═══════════════════════════════════════════════════
                                 2. SCHEDULED MAINTENANCE CONFIGURATION FORM
                            ════════════════════════════════════════════════════ -->
                            <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">
                                <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center text-slate-700 shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                                        </div>
                                        <div>
                                            <h2 class="text-base sm:text-lg font-bold text-slate-900">Scheduled Maintenance Configuration</h2>
                                            <p class="text-xs text-slate-500 font-medium mt-0.5">Customize public notices, administrative logs, and downtime schedule.</p>
                                        </div>
                                    </div>
                                    <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 border border-slate-200 hidden sm:inline-block">Planned Window</span>
                                </div>

                                <form id="maintenanceForm" class="p-5 sm:p-6 space-y-5">
                                    <input type="hidden" name="maintenance_type" value="scheduled">

                                    <!-- Public Maintenance Message -->
                                    <div>
                                        <div class="flex items-center justify-between mb-1.5">
                                            <label for="maintenance_message" class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                                                Public Advisory Notice <span class="text-red-500">*</span>
                                            </label>
                                            <span class="text-[11px] text-slate-400 font-medium">Displayed to residents on the maintenance screen</span>
                                        </div>
                                        <textarea id="maintenance_message" name="maintenance_message" rows="3" required
                                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-xs sm:text-sm text-slate-800 font-medium focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 resize-none transition"
                                            placeholder="e.g., The system is currently undergoing scheduled system upgrades and will be back online shortly..."><?php echo $savedMessage; ?></textarea>
                                    </div>

                                    <!-- Internal Reason & Technical Notes -->
                                    <div>
                                        <div class="flex items-center justify-between mb-1.5">
                                            <label for="reason" class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                                                Internal Log / Reason <span class="text-slate-400 font-normal lowercase">(admin audit only)</span>
                                            </label>
                                            <span class="text-[11px] text-slate-400 font-medium">Not visible to public or residents</span>
                                        </div>
                                        <input type="text" id="reason" name="reason" value="<?php echo $savedReason; ?>"
                                            class="w-full rounded-xl border border-slate-200 px-4 py-2.5 text-xs sm:text-sm text-slate-800 font-medium focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition"
                                            placeholder="e.g., Database migration, OS patches, or GIS boundary synchronization...">
                                    </div>

                                    <!-- Schedule Date & Time Row -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                                        <div class="p-4 rounded-xl bg-slate-50/80 border border-slate-200 space-y-1.5">
                                            <label for="start_at" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                                Start Date &amp; Time
                                            </label>
                                            <input type="datetime-local" id="start_at" name="start_at" value="<?php echo $savedStart; ?>"
                                                class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs sm:text-sm text-slate-800 font-medium focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                                            <p class="text-[11px] text-slate-400 font-medium">Leave blank to activate immediately when turned on.</p>
                                        </div>

                                        <div class="p-4 rounded-xl bg-slate-50/80 border border-slate-200 space-y-1.5">
                                            <label for="end_at" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                                                Expected End Date &amp; Time
                                            </label>
                                            <input type="datetime-local" id="end_at" name="end_at" value="<?php echo $savedEnd; ?>"
                                                class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs sm:text-sm text-slate-800 font-medium focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                                            <p class="text-[11px] text-slate-400 font-medium">System automatically restores when this time is reached.</p>
                                        </div>
                                    </div>

                                    <!-- Official Access Callout Banner -->
                                    <div class="flex items-start gap-3 p-4 bg-emerald-50/80 border border-emerald-200 rounded-xl text-xs">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-700 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/></svg>
                                        <div>
                                            <p class="font-bold text-emerald-900">Administrator &amp; Staff Access Always Retained</p>
                                            <p class="text-emerald-700/90 mt-0.5 leading-relaxed">
                                                Barangay Captains, Secretaries, Environmental Officers, and Haulers retain complete access to all backend portals during maintenance.
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Form Action Button Bar -->
                                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-3 border-t border-slate-100">
                                        <div class="text-xs text-slate-400 font-medium order-2 sm:order-1">
                                            <?php if ($isActive && !$isEmergency): ?>
                                                <span class="text-amber-700 font-bold flex items-center gap-1.5">
                                                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                                                    Maintenance is currently running
                                                </span>
                                            <?php else: ?>
                                                <span>Save changes to template or trigger activation</span>
                                            <?php endif; ?>
                                        </div>

                                        <div class="flex items-center gap-2.5 w-full sm:w-auto order-1 sm:order-2">
                                            <button type="button" onclick="submitAction('save_settings')"
                                                class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs transition shadow-2xs active:scale-98">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                                Save Settings
                                            </button>

                                            <?php if (!$isActive): ?>
                                                <button type="button" onclick="confirmAction('activate')"
                                                    class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs shadow-xs hover:shadow-sm transition active:scale-98">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18.36 6.64a9 9 0 1 1-12.73 0"/><line x1="12" y1="2" x2="12" y2="12"/></svg>
                                                    Activate Maintenance Mode
                                                </button>
                                            <?php else: ?>
                                                <button type="button" onclick="confirmAction('activate')"
                                                    class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-[#0B2E22] hover:bg-[#06241a] text-white font-bold text-xs shadow-xs hover:shadow-sm transition active:scale-98">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 0 0-9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 9 9 9.75 9.75 0 0 0 6.74-2.74L21 16"/><path d="M16 21h5v-5"/></svg>
                                                    Update Active Maintenance
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                </form>
                            </div>

                            <!-- ═══════════════════════════════════════════════════
                                 3. EMERGENCY LOCKDOWN DANGER ZONE
                            ════════════════════════════════════════════════════ -->
                            <?php if (!$isEmergency): ?>
                            <div class="bg-gradient-to-b from-red-50/40 to-white rounded-2xl border border-red-200 shadow-2xs overflow-hidden">
                                <div class="p-5 sm:p-6 border-b border-red-100/80 flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-red-100 text-red-700 flex items-center justify-center shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                        </div>
                                        <div>
                                            <h2 class="text-base sm:text-lg font-bold text-red-900">Emergency System Lockdown</h2>
                                            <p class="text-xs text-red-700/80 font-medium mt-0.5">Immediate security or critical incident intervention.</p>
                                        </div>
                                    </div>
                                    <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full bg-red-100 text-red-800 border border-red-200 uppercase tracking-wider">Danger Zone</span>
                                </div>

                                <div class="p-5 sm:p-6 space-y-4">
                                    <p class="text-xs text-red-900/80 leading-relaxed font-medium">
                                        Activating Emergency Lockdown <strong>instantly cuts off public access</strong>, terminates active resident reporting sessions, and displays an emergency advisory. Use only during severe emergencies, data anomalies, or security breaches.
                                    </p>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label for="emergency_reason" class="block text-xs font-bold text-red-900 uppercase tracking-wider mb-1">
                                                Emergency Reason <span class="text-red-600">*</span>
                                            </label>
                                            <input type="text" id="emergency_reason" name="emergency_reason"
                                                class="w-full rounded-xl border border-red-200 bg-white px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 font-medium focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20"
                                                placeholder="e.g., Severe storm advisory, data corruption audit, unauthorized breach...">
                                        </div>
                                        <div>
                                            <label for="emergency_message" class="block text-xs font-bold text-red-900 uppercase tracking-wider mb-1">
                                                Emergency Public Advisory <span class="text-red-600">*</span>
                                            </label>
                                            <input type="text" id="emergency_message" name="emergency_message"
                                                class="w-full rounded-xl border border-red-200 bg-white px-3.5 py-2.5 text-xs sm:text-sm text-slate-800 font-medium focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20"
                                                value="The system is temporarily unavailable due to an emergency situation. Please check back later or contact the Barangay Hall.">
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-end pt-2">
                                        <button onclick="triggerEmergencyModal()"
                                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-red-700 hover:bg-red-800 text-white font-bold text-xs shadow-xs hover:shadow-sm transition active:scale-98">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                            Trigger Emergency Lockdown
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- ═══════════════════════════════════════════════════
                                 4. MAINTENANCE HISTORY TABLE
                            ════════════════════════════════════════════════════ -->
                            <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">
                                <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center text-slate-700 shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                        </div>
                                        <div>
                                            <h2 class="text-base sm:text-lg font-bold text-slate-900">Maintenance &amp; Availability History</h2>
                                            <p class="text-xs text-slate-500 font-medium mt-0.5">Comprehensive audit trail of all maintenance activations and setting changes.</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <?php if (!empty($history)): ?>
                                        <div class="flex items-center gap-1.5 text-xs text-slate-500 font-bold">
                                            <span>Show</span>
                                            <select onchange="changeHistoryPerPage(this.value)" class="rounded-lg border border-slate-200 bg-white px-2 py-1 text-xs font-bold text-slate-700 focus:outline-none focus:border-emerald-500">
                                                <option value="5">5</option>
                                                <option value="10" selected>10</option>
                                                <option value="25">25</option>
                                                <option value="all">All</option>
                                            </select>
                                            <span>entries</span>
                                        </div>
                                        <?php endif; ?>
                                        <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 border border-slate-200"><?php echo count($history); ?> events</span>
                                    </div>
                                </div>

                                <?php if (empty($history)): ?>
                                <div class="py-14 text-center text-slate-400 space-y-2">
                                    <div class="w-12 h-12 mx-auto rounded-2xl bg-slate-100 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                    </div>
                                    <p class="text-sm font-bold text-slate-400">No maintenance history recorded yet.</p>
                                </div>
                                <?php else: ?>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-xs sm:text-sm">
                                        <thead>
                                            <tr class="bg-slate-50/80 border-b border-slate-100">
                                                <th class="px-5 py-3.5 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Action</th>
                                                <th class="px-5 py-3.5 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Type</th>
                                                <th class="px-5 py-3.5 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Performed By</th>
                                                <th class="px-5 py-3.5 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Reason / Notes</th>
                                                <th class="px-5 py-3.5 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Timestamp</th>
                                                <th class="px-5 py-3.5 text-left text-[11px] font-bold text-slate-500 uppercase tracking-wider">Status Change</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100">
                                            <?php foreach ($history as $h): ?>
                                            <tr class="maintenance-history-row hover:bg-slate-50/70 transition">
                                                <td class="px-5 py-3.5 font-semibold text-slate-800 whitespace-nowrap">
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[11px] font-bold border <?php echo maintenanceActionBadge($h['action']); ?>">
                                                        <span class="w-1.5 h-1.5 rounded-full <?php echo str_contains($h['action'], 'EMERGENCY') ? 'bg-red-500' : (str_contains($h['action'], 'ENABLE') ? 'bg-amber-500' : 'bg-emerald-500'); ?>"></span>
                                                        <?php echo htmlspecialchars(maintenanceActionLabel($h['action'])); ?>
                                                    </span>
                                                </td>
                                                <td class="px-5 py-3.5 text-slate-600 whitespace-nowrap">
                                                    <span class="px-2 py-0.5 rounded text-[11px] font-semibold <?php echo ($h['maintenance_type'] === 'emergency') ? 'bg-red-50 text-red-700 border border-red-200' : 'bg-slate-100 text-slate-600'; ?>">
                                                        <?php echo ucfirst(htmlspecialchars($h['maintenance_type'] ?? 'scheduled')); ?>
                                                    </span>
                                                </td>
                                                <td class="px-5 py-3.5 text-slate-800 font-bold whitespace-nowrap">
                                                    <?php echo htmlspecialchars($h['performed_by_name'] ?? 'Authorized Officer'); ?>
                                                </td>
                                                <td class="px-5 py-3.5 text-slate-600 max-w-xs truncate">
                                                    <?php echo htmlspecialchars($h['reason'] ?? '—'); ?>
                                                </td>
                                                <td class="px-5 py-3.5 text-slate-500 font-mono text-[11px] whitespace-nowrap">
                                                    <?php echo date('M j, Y \a\t g:i A', strtotime($h['created_at'])); ?>
                                                </td>
                                                <td class="px-5 py-3.5 whitespace-nowrap">
                                                    <?php
                                                     $prev = (int)$h['previous_status'];
                                                     $next = (int)$h['new_status'];
                                                     ?>
                                                    <div class="flex items-center gap-1.5 text-[11px] font-bold">
                                                        <span class="px-2 py-0.5 rounded-md <?php echo $prev ? 'bg-amber-100 text-amber-900 border border-amber-200' : 'bg-emerald-100 text-emerald-900 border border-emerald-200'; ?>">
                                                            <?php echo $prev ? 'Active' : 'Off'; ?>
                                                        </span>
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                                                        <span class="px-2 py-0.5 rounded-md <?php echo $next ? 'bg-amber-100 text-amber-900 border border-amber-200' : 'bg-emerald-100 text-emerald-900 border border-emerald-200'; ?>">
                                                            <?php echo $next ? 'Active' : 'Off'; ?>
                                                        </span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination Footer Bar -->
                                <div id="historyPaginationBar" class="p-4 bg-slate-50/90 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
                                    <div class="text-slate-600 font-semibold" id="historyPaginationInfo">
                                        Showing <span id="historyPageStart" class="font-bold text-slate-900">0</span> to <span id="historyPageEnd" class="font-bold text-slate-900">0</span> of <span id="historyPageTotal" class="font-bold text-slate-900">0</span> entries
                                    </div>
                                    
                                    <div class="flex items-center gap-1" id="historyPaginationControls">
                                        <!-- Dynamic pagination buttons -->
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>

                        </div><!-- /flex-1 -->
                    </div><!-- /content layout -->
                </div>
            </main>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════
     CONFIRMATION MODAL (Standard)
════════════════════════════════════════════════════ -->
<div id="confirmModal" class="modal-backdrop fixed inset-0 z-[100] bg-slate-950/60 flex items-center justify-center p-4 opacity-0 invisible backdrop-blur-2xs">
    <div class="modal-box bg-white rounded-2xl shadow-2xl w-full max-w-md scale-95 opacity-0 p-6 sm:p-7 space-y-5 border border-slate-100">
        <div id="confirmModalIcon" class="w-13 h-13 mx-auto rounded-2xl flex items-center justify-center bg-amber-100 text-amber-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        </div>
        <div class="text-center">
            <h3 id="confirmModalTitle" class="text-lg font-black text-slate-900">Confirm System Action</h3>
            <p id="confirmModalMsg" class="text-xs sm:text-sm text-slate-600 font-medium mt-2 leading-relaxed"></p>
        </div>
        <div class="flex gap-2.5">
            <button onclick="closeConfirmModal()" class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-50 transition">Cancel</button>
            <button id="confirmModalBtn" onclick="" class="flex-1 px-4 py-2.5 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs transition shadow-xs">Confirm</button>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════
     EMERGENCY LOCKDOWN DOUBLE-CONFIRM MODAL
════════════════════════════════════════════════════ -->
<div id="emergencyModal" class="modal-backdrop fixed inset-0 z-[110] bg-red-950/70 flex items-center justify-center p-4 opacity-0 invisible backdrop-blur-2xs">
    <div class="modal-box bg-white rounded-2xl shadow-2xl w-full max-w-md scale-95 opacity-0 p-6 sm:p-7 space-y-5 border border-red-200">
        <div class="w-13 h-13 mx-auto rounded-2xl flex items-center justify-center bg-red-100 text-red-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        </div>
        <div class="text-center space-y-1">
            <h3 class="text-lg font-black text-red-900">Authorize Emergency Lockdown</h3>
            <p class="text-xs text-red-700 font-semibold">This immediately halts public access and resident submissions.</p>
        </div>
        <div class="bg-red-50 border border-red-200 rounded-xl p-3.5 text-xs text-red-800 font-medium space-y-1.5">
            <p class="font-bold text-red-900">System impact during lockdown:</p>
            <ul class="list-disc list-inside space-y-1 text-[11px]">
                <li>Residents are instantly redirected to emergency notice page</li>
                <li>Public guest report submission is halted</li>
                <li>Audit trail records your admin ID and timestamp</li>
            </ul>
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-800 mb-1.5">Type <span class="font-mono bg-red-100 text-red-800 px-1.5 py-0.5 rounded font-bold">CONFIRM</span> to authenticate</label>
            <input type="text" id="emergencyConfirmInput" class="w-full rounded-xl border-2 border-red-200 px-4 py-2.5 text-xs sm:text-sm font-black text-slate-800 focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-500/20 uppercase tracking-widest text-center" placeholder="CONFIRM">
        </div>
        <div class="flex gap-2.5">
            <button onclick="closeEmergencyModal()" class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-700 font-bold text-xs hover:bg-slate-50 transition">Cancel</button>
            <button onclick="submitEmergencyLockdown()" class="flex-1 px-4 py-2.5 rounded-xl bg-red-700 hover:bg-red-800 text-white font-bold text-xs transition shadow-xs">
                Activate Lockdown
            </button>
        </div>
    </div>
</div>

<script>
const FORM_URL = '<?php echo app_url('index.php?url=settings/system_availability'); ?>';

// ── Toast notification ────────────────────────────────────────
function showToast(message, type = 'success') {
    const colors = {
        success: 'bg-[#0B2E22] border-emerald-600 text-white',
        error:   'bg-red-900 border-red-700 text-white',
        info:    'bg-slate-800 border-slate-600 text-white',
    };
    const toast = document.createElement('div');
    toast.className = `pointer-events-auto max-w-sm w-full px-4 py-3 rounded-xl border shadow-xl text-xs font-bold flex items-start gap-2.5 ${colors[type] || colors.info}`;
    toast.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg><span>${message}</span>`;
    document.getElementById('toastContainer').appendChild(toast);
    setTimeout(() => toast.remove(), 4500);
}

// ── Gather form data ─────────────────────────────────────────
function getFormData() {
    return {
        maintenance_type:    'scheduled',
        maintenance_message: document.getElementById('maintenance_message').value.trim(),
        reason:              document.getElementById('reason').value.trim(),
        start_at:            document.getElementById('start_at').value,
        end_at:              document.getElementById('end_at').value,
    };
}

// ── Submit action (AJAX) ──────────────────────────────────────
async function submitAction(action, extraData = {}) {
    const formData = getFormData();
    const body = new URLSearchParams({ action, ...formData, ...extraData });

    // Validate message for activate actions
    if (['activate', 'save_settings'].includes(action) && !formData.maintenance_message) {
        showToast('Maintenance message cannot be empty.', 'error');
        return;
    }
    // Validate date order
    if (formData.start_at && formData.end_at) {
        if (new Date(formData.end_at) <= new Date(formData.start_at)) {
            showToast('End date/time must be after start date/time.', 'error');
            return;
        }
    }

    try {
        const res  = await fetch(FORM_URL, { method: 'POST', body });
        const json = await res.json();
        showToast(json.message, json.success ? 'success' : 'error');
        if (json.success) {
            setTimeout(() => window.location.reload(), 1000);
        }
    } catch (e) {
        showToast('Network error. Please try again.', 'error');
    }
}

// ── Confirm modal ─────────────────────────────────────────────
const confirmMessages = {
    activate:            'Are you sure you want to activate Scheduled Maintenance? Public users and residents will see the maintenance advisory notice upon visiting the app.',
    deactivate:          'Are you sure you want to deactivate Maintenance Mode? Public and resident portals will immediately return to normal operation.',
    deactivate_emergency:'Are you sure you want to lift the Emergency Lockdown? Full system access will be immediately restored.',
};
const confirmColors = {
    activate:            'bg-amber-600 hover:bg-amber-700',
    deactivate:          'bg-emerald-700 hover:bg-emerald-800',
    deactivate_emergency:'bg-red-700 hover:bg-red-800',
};
const confirmBtnLabels = {
    activate:            'Yes, Activate Maintenance',
    deactivate:          'Yes, Restore to Operational',
    deactivate_emergency:'Yes, Lift Lockdown',
};

let _pendingAction = null;
function confirmAction(action) {
    _pendingAction = action;
    document.getElementById('confirmModalMsg').textContent = confirmMessages[action] || 'Confirm this action?';
    const btn = document.getElementById('confirmModalBtn');
    btn.className = `flex-1 px-4 py-2.5 rounded-xl text-white font-bold text-xs transition shadow-xs ${confirmColors[action] || 'bg-amber-600 hover:bg-amber-700'}`;
    btn.textContent = confirmBtnLabels[action] || 'Confirm';
    btn.onclick = () => { closeConfirmModal(); submitAction(action); };
    openModal('confirmModal');
}

// ── Emergency modal ───────────────────────────────────────────
function triggerEmergencyModal() {
    const reason  = document.getElementById('emergency_reason').value.trim();
    const message = document.getElementById('emergency_message').value.trim();
    if (!reason)  { showToast('Please provide an emergency reason.', 'error');  return; }
    if (!message) { showToast('Please enter an emergency public advisory.', 'error'); return; }
    document.getElementById('emergencyConfirmInput').value = '';
    openModal('emergencyModal');
}

async function submitEmergencyLockdown() {
    const confirmVal = document.getElementById('emergencyConfirmInput').value.trim().toUpperCase();
    if (confirmVal !== 'CONFIRM') {
        showToast('Please type CONFIRM to authorize emergency lockdown.', 'error');
        return;
    }
    const reason  = document.getElementById('emergency_reason').value.trim();
    const message = document.getElementById('emergency_message').value.trim();
    closeEmergencyModal();

    const body = new URLSearchParams({
        action:               'emergency_lockdown',
        confirm_emergency:    '1',
        maintenance_type:     'emergency',
        maintenance_message:  message,
        reason:               reason,
        start_at:             '',
        end_at:               '',
    });
    try {
        const res  = await fetch(FORM_URL, { method: 'POST', body });
        const json = await res.json();
        showToast(json.message, json.success ? 'success' : 'error');
        if (json.success) setTimeout(() => window.location.reload(), 1000);
    } catch (e) {
        showToast('Network error. Please try again.', 'error');
    }
}

function closeEmergencyModal() { closeModal('emergencyModal'); }
function closeConfirmModal()   { closeModal('confirmModal'); }

// ── Generic modal helpers ─────────────────────────────────────
function openModal(id) {
    const m  = document.getElementById(id);
    const bx = m.querySelector('.modal-box');
    m.classList.remove('opacity-0', 'invisible');
    bx.classList.remove('scale-95', 'opacity-0');
    bx.classList.add('scale-100', 'opacity-100');
}
function closeModal(id) {
    const m  = document.getElementById(id);
    const bx = m.querySelector('.modal-box');
    bx.classList.add('scale-95', 'opacity-0');
    bx.classList.remove('scale-100', 'opacity-100');
    setTimeout(() => m.classList.add('opacity-0', 'invisible'), 200);
}
// Close modals on overlay click
document.querySelectorAll('.modal-backdrop').forEach(m => {
    m.addEventListener('click', function(e) { if (e.target === this) closeModal(this.id); });
});

// ── Maintenance History Pagination ─────────────────────────────
let historyCurrentPage = 1;
let historyPerPage = 10;

function changeHistoryPerPage(val) {
    historyPerPage = (val === 'all') ? 999999 : parseInt(val, 10);
    historyCurrentPage = 1;
    applyHistoryPagination();
}

function goToHistoryPage(p) {
    historyCurrentPage = p;
    applyHistoryPagination();
}

function applyHistoryPagination() {
    const rows = Array.from(document.querySelectorAll('.maintenance-history-row'));
    const total = rows.length;
    if (total === 0) return;

    const totalPages = Math.max(1, Math.ceil(total / historyPerPage));

    if (historyCurrentPage > totalPages) historyCurrentPage = totalPages;
    if (historyCurrentPage < 1) historyCurrentPage = 1;

    const startIdx = (historyCurrentPage - 1) * historyPerPage;
    const endIdx = Math.min(startIdx + historyPerPage, total);

    rows.forEach((r, idx) => {
        r.style.display = (idx >= startIdx && idx < endIdx) ? '' : 'none';
    });

    const pStart = document.getElementById('historyPageStart');
    const pEnd = document.getElementById('historyPageEnd');
    const pTotal = document.getElementById('historyPageTotal');

    if (pStart) pStart.textContent = total > 0 ? (startIdx + 1) : 0;
    if (pEnd) pEnd.textContent = endIdx;
    if (pTotal) pTotal.textContent = total;

    renderHistoryPaginationControls(totalPages);
}

function renderHistoryPaginationControls(totalPages) {
    const container = document.getElementById('historyPaginationControls');
    if (!container) return;

    if (totalPages <= 1 && historyPerPage >= 999999) {
        container.innerHTML = '';
        return;
    }

    let html = '';

    const prevDisabled = (historyCurrentPage <= 1);
    html += `
        <button onclick="goToHistoryPage(${historyCurrentPage - 1})" ${prevDisabled ? 'disabled' : ''} 
                class="px-2.5 py-1.5 rounded-lg border text-xs font-bold transition flex items-center gap-1 ${prevDisabled ? 'border-slate-200 text-slate-300 cursor-not-allowed bg-slate-50' : 'border-slate-300 text-slate-700 bg-white hover:bg-slate-100 cursor-pointer active:scale-95'}">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            <span>Prev</span>
        </button>
    `;

    let startPage = Math.max(1, historyCurrentPage - 2);
    let endPage = Math.min(totalPages, startPage + 4);
    if (endPage - startPage < 4) {
        startPage = Math.max(1, endPage - 4);
    }

    if (startPage > 1) {
        html += `<button onclick="goToHistoryPage(1)" class="w-8 h-8 rounded-lg border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold transition cursor-pointer">1</button>`;
        if (startPage > 2) html += `<span class="px-1 text-slate-400 font-bold">...</span>`;
    }

    for (let p = startPage; p <= endPage; p++) {
        const isActive = (p === historyCurrentPage);
        html += `
            <button onclick="goToHistoryPage(${p})" 
                    class="w-8 h-8 rounded-lg text-xs font-bold transition cursor-pointer ${isActive ? 'bg-[#0B2E22] text-white shadow-xs' : 'border border-slate-200 bg-white hover:bg-slate-100 text-slate-700'}">
                ${p}
            </button>
        `;
    }

    if (endPage < totalPages) {
        if (endPage < totalPages - 1) html += `<span class="px-1 text-slate-400 font-bold">...</span>`;
        html += `<button onclick="goToHistoryPage(${totalPages})" class="w-8 h-8 rounded-lg border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 text-xs font-bold transition cursor-pointer">${totalPages}</button>`;
    }

    const nextDisabled = (historyCurrentPage >= totalPages);
    html += `
        <button onclick="goToHistoryPage(${historyCurrentPage + 1})" ${nextDisabled ? 'disabled' : ''} 
                class="px-2.5 py-1.5 rounded-lg border text-xs font-bold transition flex items-center gap-1 ${nextDisabled ? 'border-slate-200 text-slate-300 cursor-not-allowed bg-slate-50' : 'border-slate-300 text-slate-700 bg-white hover:bg-slate-100 cursor-pointer active:scale-95'}">
            <span>Next</span>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
        </button>
    `;

    container.innerHTML = html;
}

document.addEventListener('DOMContentLoaded', () => {
    applyHistoryPagination();
});
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
