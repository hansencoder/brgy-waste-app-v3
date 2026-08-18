<?php include __DIR__ . '/../layouts/header.php'; ?>

<style>
    body, * { font-family: 'Miranda Sans', sans-serif !important; font-optical-sizing: auto; }
    .mobile-sidebar-open #mobileSidebar { transform: translateX(0) !important; }
    #mobileSidebarOverlay { transition: opacity 0.25s ease, visibility 0.25s ease; opacity: 0; visibility: hidden; }
    .mobile-sidebar-open #mobileSidebarOverlay { opacity: 1; visibility: visible; }
    .modal-backdrop { transition: opacity 0.2s ease, visibility 0.2s ease; }
    .modal-box      { transition: transform 0.3s cubic-bezier(0.34,1.3,0.64,1), opacity 0.2s ease; }
    .toggle-track { transition: background 0.25s; }
    .toggle-thumb { transition: transform 0.25s cubic-bezier(0.34,1.3,0.64,1); }
    input[type="datetime-local"] { color-scheme: light; }
</style>

<?php
$status      = $data['status']  ?? [];
$history     = $data['history'] ?? [];
$isActive    = (bool)($status['maintenance_mode'] ?? 0);
$isEmergency = $isActive && ($status['maintenance_type'] ?? '') === 'emergency';

// Status indicator
if ($isEmergency) {
    $statusColor  = 'text-red-700';
    $statusBg     = 'bg-red-50 border-red-200';
    $statusDot    = 'bg-red-500';
    $statusLabel  = 'Emergency Lockdown';
} elseif ($isActive) {
    $statusColor  = 'text-amber-700';
    $statusBg     = 'bg-amber-50 border-amber-200';
    $statusDot    = 'bg-amber-500';
    $statusLabel  = 'Maintenance Active';
} else {
    $statusColor  = 'text-emerald-700';
    $statusBg     = 'bg-emerald-50 border-emerald-200';
    $statusDot    = 'bg-emerald-500';
    $statusLabel  = 'Operational';
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

<div class="min-h-screen bg-white text-slate-900 w-full flex font-sans antialiased">
    <div id="mobileSidebarOverlay" class="fixed inset-0 bg-slate-950/40 z-40 lg:hidden"></div>

    <div class="lg:flex lg:min-h-screen w-full">
        <?php include __DIR__ . '/../layouts/admin_sidebar.php'; ?>

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <?php include __DIR__ . '/../layouts/admin_topbar.php'; ?>

            <main class="flex-1 overflow-y-auto">
                <div class="max-w-9xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

                    <!-- Page Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 sm:p-8 rounded-2xl border border-slate-200 shadow-xs">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <a href="<?php echo app_url('settings'); ?>" class="text-sm font-extrabold text-slate-500 hover:text-emerald-700 transition">Settings Hub</a>
                                <span class="text-sm text-slate-300">/</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-extrabold bg-red-100 text-red-900 border border-red-300">System Availability</span>
                            </div>
                            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">System Availability</h1>
                            <p class="text-base sm:text-lg text-slate-600 font-semibold mt-1">
                                Control maintenance mode, schedule downtime, and manage emergency lockdowns.
                            </p>
                        </div>
                        <!-- Live status badge -->
                        <div class="shrink-0 flex items-center gap-3 px-5 py-3.5 rounded-2xl border-2 <?php echo $statusBg; ?>">
                            <span class="w-2.5 h-2.5 rounded-full <?php echo $statusDot; ?> animate-pulse shrink-0"></span>
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500">Current Status</p>
                                <p class="text-base font-extrabold <?php echo $statusColor; ?>"><?php echo $statusLabel; ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Toast notification container -->
                    <div id="toastContainer" class="fixed top-5 right-5 z-[200] space-y-2 pointer-events-none"></div>

                    <!-- Content Layout -->
                    <div class="flex flex-col lg:flex-row gap-6">
                        <?php $activeTab = 'system_availability'; include __DIR__ . '/../layouts/settings_sidebar.php'; ?>

                        <div class="flex-1 min-w-0 space-y-5">

                            <!-- ═══════════════════════════════════════════════════
                                 STATUS OVERVIEW CARD
                            ════════════════════════════════════════════════════ -->
                            <div class="bg-white rounded-2xl border-2 <?php echo $isEmergency ? 'border-red-300' : ($isActive ? 'border-amber-300' : 'border-slate-200'); ?> shadow-xs overflow-hidden">
                                <div class="p-5 sm:p-6 <?php echo $isEmergency ? 'bg-red-50' : ($isActive ? 'bg-amber-50' : 'bg-emerald-50'); ?>">
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0
                                                <?php echo $isEmergency ? 'bg-red-100' : ($isActive ? 'bg-amber-100' : 'bg-emerald-100'); ?>">
                                                <?php if ($isEmergency): ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-red-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                                <?php elseif ($isActive): ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-amber-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                                                <?php else: ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-emerald-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <p class="text-xs font-bold uppercase tracking-widest <?php echo $isEmergency ? 'text-red-600' : ($isActive ? 'text-amber-600' : 'text-emerald-600'); ?>">
                                                    <?php echo $isEmergency ? 'Emergency Mode' : ($isActive ? 'Maintenance Active' : 'All Systems'); ?>
                                                </p>
                                                <h3 class="text-xl font-extrabold text-slate-900">
                                                    <?php echo $isEmergency ? 'Emergency Lockdown Active' : ($isActive ? 'System Under Maintenance' : 'System is Operational'); ?>
                                                </h3>
                                                <?php if ($isActive && !empty($status['updated_by_name'])): ?>
                                                <p class="text-xs text-slate-500 mt-0.5 font-medium">
                                                    Activated by <strong><?php echo htmlspecialchars($status['updated_by_name']); ?></strong>
                                                    <?php if (!empty($status['updated_at'])): ?>
                                                    · <?php echo date('M j, Y g:i A', strtotime($status['updated_at'])); ?>
                                                    <?php endif; ?>
                                                </p>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <!-- Quick action button -->
                                        <?php if ($isEmergency): ?>
                                        <button onclick="confirmAction('deactivate_emergency')"
                                                class="shrink-0 inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-red-700 hover:bg-red-800 text-white font-extrabold text-sm transition shadow-xs">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="8" y1="8" x2="16" y2="16"/><line x1="16" y1="8" x2="8" y2="16"/></svg>
                                            Lift Emergency Lockdown
                                        </button>
                                        <?php elseif ($isActive): ?>
                                        <button onclick="confirmAction('deactivate')"
                                                class="shrink-0 inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-extrabold text-sm transition shadow-xs">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                                            Restore to Operational
                                        </button>
                                        <?php endif; ?>
                                    </div>

                                    <?php if ($isActive && !empty($status['end_at'])): ?>
                                    <div class="mt-4 pt-4 border-t <?php echo $isEmergency ? 'border-red-200' : 'border-amber-200'; ?> flex items-center gap-2 text-sm <?php echo $isEmergency ? 'text-red-700' : 'text-amber-700'; ?> font-semibold">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                        <span>Expected end: <strong><?php echo date('F j, Y \a\t g:i A', strtotime($status['end_at'])); ?></strong></span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- ═══════════════════════════════════════════════════
                                 MAINTENANCE SETTINGS FORM
                            ════════════════════════════════════════════════════ -->
                            <div class="bg-white rounded-2xl border-2 border-slate-200 shadow-xs overflow-hidden">
                                <div class="p-5 sm:p-6 border-b border-slate-100">
                                    <h2 class="text-lg font-extrabold text-slate-900">Maintenance Configuration</h2>
                                    <p class="text-sm text-slate-500 font-medium mt-0.5">Configure settings before activating maintenance mode.</p>
                                </div>

                                <form id="maintenanceForm" class="p-5 sm:p-6 space-y-5">

                                    <!-- Maintenance Type -->
                                    <div>
                                        <label class="block text-sm font-extrabold text-slate-800 mb-2">Maintenance Type</label>
                                        <div class="grid grid-cols-2 gap-3">
                                            <label id="typeScheduledCard" class="flex items-center gap-3 p-4 rounded-xl border-2 cursor-pointer transition-all <?php echo $savedType === 'scheduled' ? 'border-emerald-500 bg-emerald-50' : 'border-slate-200 hover:border-slate-300'; ?>">
                                                <input type="radio" name="maintenance_type" value="scheduled" class="sr-only" <?php echo $savedType === 'scheduled' ? 'checked' : ''; ?>>
                                                <div class="w-9 h-9 rounded-xl bg-amber-100 flex items-center justify-center shrink-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-extrabold text-slate-800">Scheduled Maintenance</p>
                                                    <p class="text-[11px] text-slate-500 font-medium">Planned downtime with schedule</p>
                                                </div>
                                            </label>
                                            <label id="typeEmergencyCard" class="flex items-center gap-3 p-4 rounded-xl border-2 cursor-pointer transition-all <?php echo $savedType === 'emergency' ? 'border-red-500 bg-red-50' : 'border-slate-200 hover:border-slate-300'; ?>">
                                                <input type="radio" name="maintenance_type" value="emergency" class="sr-only" <?php echo $savedType === 'emergency' ? 'checked' : ''; ?>>
                                                <div class="w-9 h-9 rounded-xl bg-red-100 flex items-center justify-center shrink-0">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-extrabold text-slate-800">Emergency Maintenance</p>
                                                    <p class="text-[11px] text-slate-500 font-medium">Immediate unplanned lockdown</p>
                                                </div>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Maintenance Message -->
                                    <div>
                                        <label for="maintenance_message" class="block text-sm font-extrabold text-slate-800 mb-1.5">
                                            Maintenance Message <span class="text-red-500">*</span>
                                        </label>
                                        <textarea id="maintenance_message" name="maintenance_message" rows="3" required
                                            class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm text-slate-800 font-medium focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 resize-none"
                                            placeholder="Message shown to non-admin users during maintenance..."><?php echo $savedMessage; ?></textarea>
                                        <p class="text-[11px] text-slate-400 font-medium mt-1">This message is displayed on the maintenance page. Do not include sensitive technical details.</p>
                                    </div>

                                    <!-- Reason (internal) -->
                                    <div>
                                        <label for="reason" class="block text-sm font-extrabold text-slate-800 mb-1.5">
                                            Internal Reason <span class="text-slate-400 font-medium">(admin-only, not shown to users)</span>
                                        </label>
                                        <input type="text" id="reason" name="reason" value="<?php echo $savedReason; ?>"
                                            class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm text-slate-800 font-medium focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100"
                                            placeholder="e.g., Database migration, Server upgrade, Security patch...">
                                    </div>

                                    <!-- Schedule row -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label for="start_at" class="block text-sm font-extrabold text-slate-800 mb-1.5">Start Date &amp; Time</label>
                                            <input type="datetime-local" id="start_at" name="start_at" value="<?php echo $savedStart; ?>"
                                                class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm text-slate-800 font-medium focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                                            <p class="text-[11px] text-slate-400 font-medium mt-1">Leave blank to start immediately on activation.</p>
                                        </div>
                                        <div>
                                            <label for="end_at" class="block text-sm font-extrabold text-slate-800 mb-1.5">Expected End Date &amp; Time</label>
                                            <input type="datetime-local" id="end_at" name="end_at" value="<?php echo $savedEnd; ?>"
                                                class="w-full rounded-xl border-2 border-slate-200 px-4 py-3 text-sm text-slate-800 font-medium focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100">
                                            <p class="text-[11px] text-slate-400 font-medium mt-1">Auto-deactivates when this time is reached.</p>
                                        </div>
                                    </div>

                                    <!-- Admin access notice -->
                                    <div class="flex items-start gap-3 p-4 bg-emerald-50 border-2 border-emerald-200 rounded-xl">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-700 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                                        <div>
                                            <p class="text-sm font-extrabold text-emerald-900">Administrator Access Always Permitted</p>
                                            <p class="text-xs text-emerald-700 font-medium mt-0.5">Administrators, Secretaries, and Captains retain full access regardless of maintenance status. This cannot be disabled.</p>
                                        </div>
                                    </div>

                                    <!-- Action buttons -->
                                    <div class="flex flex-col sm:flex-row gap-3 pt-2">
                                        <button type="button" onclick="submitAction('save_settings')"
                                            class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl border-2 border-slate-200 bg-white hover:bg-slate-50 text-slate-800 font-extrabold text-sm transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                            Save Settings
                                        </button>
                                        <?php if (!$isActive): ?>
                                        <button type="button" onclick="confirmAction('activate')"
                                            class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-extrabold text-sm transition shadow-xs">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg>
                                            Activate Maintenance Mode
                                        </button>
                                        <?php else: ?>
                                        <button type="button" onclick="confirmAction('deactivate')"
                                            class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white font-extrabold text-sm transition shadow-xs">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                                            Deactivate Maintenance Mode
                                        </button>
                                        <?php endif; ?>
                                    </div>

                                </form>
                            </div>

                            <!-- ═══════════════════════════════════════════════════
                                 EMERGENCY LOCKDOWN SECTION
                            ════════════════════════════════════════════════════ -->
                            <?php if (!$isEmergency): ?>
                            <div class="bg-white rounded-2xl border-2 border-red-200 shadow-xs overflow-hidden">
                                <div class="p-5 sm:p-6 border-b border-red-100 bg-red-50/50">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                        </div>
                                        <div>
                                            <h2 class="text-lg font-extrabold text-red-900">Emergency Lockdown</h2>
                                            <p class="text-sm text-red-700 font-medium">Immediately blocks all non-admin access. Use only for critical situations.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-5 sm:p-6 space-y-4">
                                    <div>
                                        <label for="emergency_reason" class="block text-sm font-extrabold text-red-900 mb-1.5">
                                            Emergency Reason <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" id="emergency_reason" name="emergency_reason"
                                            class="w-full rounded-xl border-2 border-red-200 px-4 py-3 text-sm text-slate-800 font-medium focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100"
                                            placeholder="State the reason for emergency lockdown...">
                                    </div>
                                    <div>
                                        <label for="emergency_message" class="block text-sm font-extrabold text-red-900 mb-1.5">
                                            Emergency Message <span class="text-red-500">*</span>
                                        </label>
                                        <textarea id="emergency_message" rows="2"
                                            class="w-full rounded-xl border-2 border-red-200 px-4 py-3 text-sm text-slate-800 font-medium focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100 resize-none"
                                            placeholder="Message to show to blocked users...">The system is temporarily unavailable due to an emergency situation. Please check back later or contact the barangay hall for urgent concerns.</textarea>
                                    </div>
                                    <button onclick="triggerEmergencyModal()"
                                        class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-red-700 hover:bg-red-800 text-white font-extrabold text-sm transition shadow-xs">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                                        Activate Emergency Lockdown
                                    </button>
                                </div>
                            </div>
                            <?php endif; ?>

                            <!-- ═══════════════════════════════════════════════════
                                 MAINTENANCE HISTORY TABLE
                            ════════════════════════════════════════════════════ -->
                            <div class="bg-white rounded-2xl border-2 border-slate-200 shadow-xs overflow-hidden">
                                <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between gap-3">
                                    <div>
                                        <h2 class="text-lg font-extrabold text-slate-900">Maintenance History</h2>
                                        <p class="text-sm text-slate-500 font-medium mt-0.5">Log of all maintenance events and actions.</p>
                                    </div>
                                    <span class="text-xs font-bold text-slate-400"><?php echo count($history); ?> events</span>
                                </div>

                                <?php if (empty($history)): ?>
                                <div class="py-14 text-center text-slate-400 space-y-2">
                                    <div class="w-12 h-12 mx-auto rounded-2xl bg-slate-100 flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                    </div>
                                    <p class="text-sm font-bold text-slate-400">No maintenance history yet.</p>
                                </div>
                                <?php else: ?>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm">
                                        <thead>
                                            <tr class="bg-slate-50 border-b border-slate-100">
                                                <th class="px-5 py-3.5 text-left text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Action</th>
                                                <th class="px-5 py-3.5 text-left text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Type</th>
                                                <th class="px-5 py-3.5 text-left text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Performed By</th>
                                                <th class="px-5 py-3.5 text-left text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Reason</th>
                                                <th class="px-5 py-3.5 text-left text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Timestamp</th>
                                                <th class="px-5 py-3.5 text-left text-[11px] font-extrabold text-slate-500 uppercase tracking-wider">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-50">
                                            <?php foreach ($history as $h): ?>
                                            <tr class="hover:bg-slate-50/60 transition">
                                                <td class="px-5 py-3.5 font-semibold text-slate-800">
                                                    <span class="inline-flex px-2 py-1 rounded-lg text-[11px] font-bold border <?php echo maintenanceActionBadge($h['action']); ?>">
                                                        <?php echo htmlspecialchars(maintenanceActionLabel($h['action'])); ?>
                                                    </span>
                                                </td>
                                                <td class="px-5 py-3.5 text-slate-600">
                                                    <?php echo ucfirst(htmlspecialchars($h['maintenance_type'] ?? '—')); ?>
                                                </td>
                                                <td class="px-5 py-3.5 text-slate-800 font-semibold">
                                                    <?php echo htmlspecialchars($h['performed_by_name'] ?? 'System'); ?>
                                                </td>
                                                <td class="px-5 py-3.5 text-slate-500 max-w-xs truncate">
                                                    <?php echo htmlspecialchars($h['reason'] ?? '—'); ?>
                                                </td>
                                                <td class="px-5 py-3.5 text-slate-500 font-mono text-[11px] whitespace-nowrap">
                                                    <?php echo date('M j, Y g:i A', strtotime($h['created_at'])); ?>
                                                </td>
                                                <td class="px-5 py-3.5">
                                                    <?php
                                                    $prev = (int)$h['previous_status'];
                                                    $next = (int)$h['new_status'];
                                                    ?>
                                                    <div class="flex items-center gap-1.5 text-[11px] font-bold">
                                                        <span class="px-1.5 py-0.5 rounded <?php echo $prev ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800'; ?>">
                                                            <?php echo $prev ? 'Active' : 'Off'; ?>
                                                        </span>
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
                                                        <span class="px-1.5 py-0.5 rounded <?php echo $next ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800'; ?>">
                                                            <?php echo $next ? 'Active' : 'Off'; ?>
                                                        </span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
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
<div id="confirmModal" class="modal-backdrop fixed inset-0 z-[100] bg-slate-950/60 flex items-center justify-center p-4 opacity-0 invisible">
    <div class="modal-box bg-white rounded-2xl shadow-2xl w-full max-w-md scale-95 opacity-0 p-7 space-y-5">
        <div id="confirmModalIcon" class="w-14 h-14 mx-auto rounded-2xl flex items-center justify-center bg-amber-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-amber-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        </div>
        <div class="text-center">
            <h3 id="confirmModalTitle" class="text-xl font-extrabold text-slate-900">Confirm Action</h3>
            <p id="confirmModalMsg" class="text-sm text-slate-600 font-medium mt-2 leading-relaxed"></p>
        </div>
        <div class="flex gap-3">
            <button onclick="closeConfirmModal()" class="flex-1 px-4 py-3 rounded-xl border-2 border-slate-200 text-slate-700 font-extrabold text-sm hover:bg-slate-50 transition">Cancel</button>
            <button id="confirmModalBtn" onclick="" class="flex-1 px-4 py-3 rounded-xl bg-amber-600 hover:bg-amber-700 text-white font-extrabold text-sm transition">Confirm</button>
        </div>
    </div>
</div>

<!-- ═══════════════════════════════════════════════════
     EMERGENCY LOCKDOWN DOUBLE-CONFIRM MODAL
════════════════════════════════════════════════════ -->
<div id="emergencyModal" class="modal-backdrop fixed inset-0 z-[110] bg-red-950/70 flex items-center justify-center p-4 opacity-0 invisible">
    <div class="modal-box bg-white rounded-2xl shadow-2xl w-full max-w-md scale-95 opacity-0 p-7 space-y-5">
        <div class="w-14 h-14 mx-auto rounded-2xl flex items-center justify-center bg-red-100">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-red-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        </div>
        <div class="text-center space-y-1">
            <h3 class="text-xl font-extrabold text-red-900">Emergency Lockdown</h3>
            <p class="text-sm text-red-700 font-semibold">This will IMMEDIATELY block all non-admin access to the system.</p>
        </div>
        <div class="bg-red-50 border-2 border-red-200 rounded-xl p-4 text-sm text-red-800 font-medium space-y-1.5">
            <p class="font-extrabold text-red-900">This action will:</p>
            <ul class="list-disc list-inside space-y-1 text-xs">
                <li>Immediately redirect all residents to the maintenance page</li>
                <li>Block all guest report submissions</li>
                <li>Block all supervisor access</li>
                <li>Record this action and your identity in the audit log</li>
            </ul>
        </div>
        <div>
            <label class="block text-sm font-extrabold text-slate-800 mb-1.5">Type <span class="font-mono bg-slate-100 px-1 py-0.5 rounded text-red-700">CONFIRM</span> to proceed</label>
            <input type="text" id="emergencyConfirmInput" class="w-full rounded-xl border-2 border-red-200 px-4 py-3 text-sm font-bold text-slate-800 focus:outline-none focus:border-red-500 focus:ring-2 focus:ring-red-100 uppercase tracking-widest" placeholder="Type CONFIRM">
        </div>
        <div class="flex gap-3">
            <button onclick="closeEmergencyModal()" class="flex-1 px-4 py-3 rounded-xl border-2 border-slate-200 text-slate-700 font-extrabold text-sm hover:bg-slate-50 transition">Cancel</button>
            <button onclick="submitEmergencyLockdown()" class="flex-1 px-4 py-3 rounded-xl bg-red-700 hover:bg-red-800 text-white font-extrabold text-sm transition">
                Activate Emergency Lockdown
            </button>
        </div>
    </div>
</div>

<script>
const FORM_URL = '<?php echo app_url('index.php?url=settings/system_availability'); ?>';

// ── Type card toggle ─────────────────────────────────────────
document.querySelectorAll('input[name="maintenance_type"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.getElementById('typeScheduledCard').className = 'flex items-center gap-3 p-4 rounded-xl border-2 cursor-pointer transition-all ' +
            (this.value === 'scheduled' ? 'border-emerald-500 bg-emerald-50' : 'border-slate-200 hover:border-slate-300');
        document.getElementById('typeEmergencyCard').className = 'flex items-center gap-3 p-4 rounded-xl border-2 cursor-pointer transition-all ' +
            (this.value === 'emergency' ? 'border-red-500 bg-red-50' : 'border-slate-200 hover:border-slate-300');
    });
});

// ── Toast notification ────────────────────────────────────────
function showToast(message, type = 'success') {
    const colors = {
        success: 'bg-emerald-900 border-emerald-700 text-white',
        error:   'bg-red-900 border-red-700 text-white',
        info:    'bg-slate-800 border-slate-600 text-white',
    };
    const toast = document.createElement('div');
    toast.className = `pointer-events-auto max-w-sm w-full px-5 py-4 rounded-xl border-2 shadow-2xl text-sm font-semibold flex items-start gap-3 ${colors[type] || colors.info}`;
    toast.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg><span>${message}</span>`;
    document.getElementById('toastContainer').appendChild(toast);
    setTimeout(() => toast.remove(), 5000);
}

// ── Gather form data ─────────────────────────────────────────
function getFormData() {
    return {
        maintenance_type:    document.querySelector('input[name="maintenance_type"]:checked')?.value || 'scheduled',
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
            setTimeout(() => window.location.reload(), 1200);
        }
    } catch (e) {
        showToast('Network error. Please try again.', 'error');
    }
}

// ── Confirm modal ─────────────────────────────────────────────
const confirmMessages = {
    activate:            'Are you sure you want to activate Maintenance Mode? All non-administrative users will be temporarily blocked from accessing the system.',
    deactivate:          'Are you sure you want to deactivate Maintenance Mode? The system will be restored to normal operational status.',
    deactivate_emergency:'Are you sure you want to lift the Emergency Lockdown? Normal access will be immediately restored for all users.',
};
const confirmColors = {
    activate:            'bg-amber-600 hover:bg-amber-700',
    deactivate:          'bg-emerald-700 hover:bg-emerald-800',
    deactivate_emergency:'bg-red-700 hover:bg-red-800',
};
const confirmBtnLabels = {
    activate:            'Yes, Activate Maintenance',
    deactivate:          'Yes, Restore to Operational',
    deactivate_emergency:'Yes, Lift Emergency Lockdown',
};

let _pendingAction = null;
function confirmAction(action) {
    _pendingAction = action;
    document.getElementById('confirmModalMsg').textContent = confirmMessages[action] || 'Confirm this action?';
    const btn = document.getElementById('confirmModalBtn');
    btn.className = `flex-1 px-4 py-3 rounded-xl text-white font-extrabold text-sm transition ${confirmColors[action] || 'bg-amber-600 hover:bg-amber-700'}`;
    btn.textContent = confirmBtnLabels[action] || 'Confirm';
    btn.onclick = () => { closeConfirmModal(); submitAction(action); };
    openModal('confirmModal');
}

// ── Emergency modal ───────────────────────────────────────────
function triggerEmergencyModal() {
    const reason  = document.getElementById('emergency_reason').value.trim();
    const message = document.getElementById('emergency_message').value.trim();
    if (!reason)  { showToast('Please enter an emergency reason.', 'error');  return; }
    if (!message) { showToast('Please enter an emergency message.', 'error'); return; }
    document.getElementById('emergencyConfirmInput').value = '';
    openModal('emergencyModal');
}

async function submitEmergencyLockdown() {
    const confirmVal = document.getElementById('emergencyConfirmInput').value.trim().toUpperCase();
    if (confirmVal !== 'CONFIRM') {
        showToast('Please type CONFIRM to proceed with emergency lockdown.', 'error');
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
        if (json.success) setTimeout(() => window.location.reload(), 1200);
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
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
