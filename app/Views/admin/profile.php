<?php include __DIR__ . '/../layouts/header.php'; ?>
<?php
/** @var array $data */
$data = $data ?? [];
$user = $data['user'] ?? $user ?? [];
$firstName = isset($user['name']) ? explode(' ', trim($user['name']))[0] : 'Admin';
$fullName = $user['name'] ?? 'Barangay Secretary';
$email = $user['email'] ?? '';
$phone = $user['phone_number'] ?? '';
$address = $user['address'] ?? '';
$role = !empty($user['role_name']) ? ucfirst($user['role_name']) : (!empty($user['role']) ? ucfirst($user['role']) : 'Secretary');
$position = $user['position_name'] ?? $role;
$purok = $user['purok_name'] ?? 'Barangay Hall';
$status = $user['status'] ?? 'active';
$createdAt = $user['created_at'] ?? 'now';
$formattedDate = date('M d, Y', strtotime($createdAt));
$memberSince = date('F Y', strtotime($createdAt));
$rawPic = $user['profile_pic'] ?? '';
$profilePic = !empty($rawPic) ? format_asset_url($rawPic) : '';

$notifPrefs = [
    'emergency_alerts'    => true,
    'audit_notifications' => true,
    'daily_digests'       => false,
    'report_updates'      => true,
    'announcement_drafts' => false,
];

function getAdminStatusStyle($status) {
    $map = [
        'active'    => ['dot' => '#10B981', 'bg' => 'bg-emerald-500/20', 'text' => 'text-emerald-300', 'border' => 'border-emerald-500/20', 'badge' => 'bg-emerald-100 text-emerald-900 border-emerald-300'],
        'pending'   => ['dot' => '#F59E0B', 'bg' => 'bg-amber-500/20',   'text' => 'text-amber-300',   'border' => 'border-amber-500/20',   'badge' => 'bg-amber-100 text-amber-900 border-amber-300'],
        'suspended' => ['dot' => '#EF4444', 'bg' => 'bg-red-500/20',     'text' => 'text-red-300',     'border' => 'border-red-500/20',     'badge' => 'bg-red-100 text-red-900 border-red-300'],
    ];
    return $map[strtolower($status)] ?? $map['active'];
}
$statusStyle = getAdminStatusStyle($status);
$initial = strtoupper(substr($firstName, 0, 1));

// Role icon
$roleIconSvg = '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>';
?>

<style>
    body, * { font-family: 'Miranda Sans', sans-serif !important; font-optical-sizing: auto; }
    .mobile-sidebar-open #mobileSidebar { transform: translateX(0) !important; }
    #mobileSidebarOverlay { transition: opacity 0.25s ease, visibility 0.25s ease; opacity: 0; visibility: hidden; }
    .mobile-sidebar-open #mobileSidebarOverlay { opacity: 1; visibility: visible; }

    .tab-btn { transition: all 0.2s ease; }
    .tab-btn.active {
        color: #0B2E22;
        border-bottom: 2.5px solid #10B981;
        font-weight: 900;
        background: transparent;
    }
    .tab-panel { display: none; }
    .tab-panel.active { display: block; }

    .form-input {
        width: 100%;
        padding: 0.625rem 1rem;
        border-radius: 0.625rem;
        border: 1.5px solid #e2e8f0;
        background: #f8fafc;
        font-size: 0.875rem;
        color: #1e293b;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    }
    .form-input:focus {
        border-color: #10B981;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(16,185,129,0.08);
    }
    .form-input:disabled, .form-input.readonly {
        background: #f1f5f9;
        color: #94a3b8;
        cursor: not-allowed;
    }
    .form-label {
        display: block;
        font-size: 0.65rem;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #64748b;
        margin-bottom: 0.375rem;
    }
    .strength-bar {
        height: 4px;
        border-radius: 9999px;
        transition: width 0.4s, background-color 0.4s;
    }
    .profile-cover {
        background: linear-gradient(135deg, #0B2E22 0%, #083528 40%, #0a3d2e 70%, #0f4a38 100%);
    }
</style>

<div class="flex h-screen bg-slate-50 overflow-hidden w-full">
    <?php include __DIR__ . '/../layouts/admin_sidebar.php'; ?>

    <div class="flex flex-col flex-1 min-w-0 overflow-hidden">
        <?php include __DIR__ . '/../layouts/admin_topbar.php'; ?>

        <main class="flex-1 overflow-y-auto bg-slate-50 focus:outline-none">

            <!-- ============================================================ -->
            <!-- PROFILE COVER BANNER                                          -->
            <!-- ============================================================ -->
            <div class="profile-cover relative w-full h-36 sm:h-44 overflow-hidden">
                <!-- Decorative Pattern -->
                <div class="absolute inset-0 opacity-10">
                    <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg" fill="currentColor"><defs><pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse"><path d="M 40 0 L 0 0 0 40"/></pattern></defs><rect width="100%" height="100%" fill="url(#grid)"/></svg>
                </div>
                <!-- Glowing orbs -->
                <div class="absolute -top-10 -left-10 w-48 h-48 rounded-full bg-emerald-500/10 blur-3xl"></div>
                <div class="absolute top-0 right-24 w-72 h-72 rounded-full bg-teal-400/5 blur-3xl"></div>
                <div class="absolute bottom-0 right-0 w-40 h-40 rounded-full bg-emerald-800/30 blur-2xl"></div>
            </div>

            <!-- ============================================================ -->
            <!-- PROFILE HEADER CARD (overlapping banner)                      -->
            <!-- ============================================================ -->
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-14 sm:-mt-16 relative z-10">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-lg px-6 sm:px-8 pt-5 pb-6">
                    <div class="flex flex-col sm:flex-row sm:items-end gap-5">

                        <!-- Avatar -->
                        <div class="relative flex-shrink-0">
                            <div id="avatarContainer"
                                 class="w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-[#0B2E22] ring-4 ring-white shadow-xl flex items-center justify-center text-white text-4xl font-extrabold overflow-hidden">
                                <?php if (!empty($profilePic)): ?>
                                    <img src="<?php echo htmlspecialchars($profilePic, ENT_QUOTES, 'UTF-8'); ?>" alt="Profile" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <?php echo $initial; ?>
                                <?php endif; ?>
                            </div>
                            <!-- Upload trigger -->
                            <label for="profilePicInput"
                                   class="absolute -bottom-1 -right-1 flex h-8 w-8 cursor-pointer items-center justify-center rounded-full bg-[#10B981] text-white shadow-md hover:bg-emerald-500 transition-transform hover:scale-110 border-2 border-white"
                                   title="Upload profile picture">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                            </label>
                            <input id="profilePicInput" type="file" name="profile_pic" accept="image/*" class="hidden" onchange="previewProfilePic(event)">
                        </div>

                        <!-- Identity Info -->
                        <div class="flex-1 min-w-0 pb-1">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div>
                                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight truncate">
                                        <?php echo htmlspecialchars($fullName); ?>
                                    </h1>
                                    <p class="text-sm font-semibold text-slate-500 mt-0.5">
                                        <?php echo htmlspecialchars($position); ?> &middot; <?php echo htmlspecialchars($purok); ?>
                                    </p>
                                    <p id="photoPendingNotice" class="hidden mt-1 text-xs font-bold text-amber-600 flex items-center gap-1.5"><svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg> New photo selected — click "Save Changes" to apply.</p>
                                </div>

                                <!-- Right badges -->
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-extrabold border <?php echo $statusStyle['badge']; ?>">
                                        <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background:<?php echo $statusStyle['dot']; ?>"></span>
                                        <?php echo ucfirst($status); ?>
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-extrabold bg-slate-100 text-slate-700 border border-slate-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                        <?php echo htmlspecialchars($role); ?>
                                    </span>
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-extrabold bg-slate-100 text-slate-700 border border-slate-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                        Since <?php echo $memberSince; ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stats Row -->
                    <div class="mt-5 pt-5 border-t border-slate-100 grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div class="text-center">
                            <p class="text-lg sm:text-2xl font-black text-slate-900"><?php echo htmlspecialchars($email ? substr($email, 0, 20) . (strlen($email) > 20 ? '…' : '') : '—'); ?></p>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-0.5">Email Address</p>
                        </div>
                        <div class="text-center">
                            <p class="text-lg sm:text-2xl font-black text-slate-900"><?php echo htmlspecialchars($phone ?: '—'); ?></p>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-0.5">Contact Number</p>
                        </div>
                        <div class="text-center">
                            <p class="text-lg sm:text-2xl font-black text-emerald-900"><?php echo htmlspecialchars($position); ?></p>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-0.5">Official Position</p>
                        </div>
                        <div class="text-center">
                            <p class="text-lg sm:text-2xl font-black text-purple-900"><?php echo $formattedDate; ?></p>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mt-0.5">Account Created</p>
                        </div>
                    </div>
                </div>

                <!-- ============================================================ -->
                <!-- FLASH MESSAGES                                                -->
                <!-- ============================================================ -->
                <?php if (!empty($data['error'])): ?>
                    <div class="mt-4 rounded-xl bg-red-50 border border-red-200 p-4 text-sm font-semibold text-red-700 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <?php echo htmlspecialchars($data['error']); ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($data['success'])): ?>
                    <div class="mt-4 rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-sm font-semibold text-emerald-800 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                        <?php echo htmlspecialchars($data['success']); ?>
                    </div>
                <?php endif; ?>

                <!-- ============================================================ -->
                <!-- TWO-COLUMN LAYOUT: Profile Sidebar Card + Tabbed Content     -->
                <!-- ============================================================ -->
                <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6 pb-32 lg:pb-8">

                    <!-- LEFT: Profile Summary Sidebar Card -->
                    <div class="lg:col-span-1 space-y-5">

                        <!-- Identity Card -->
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                            <div class="bg-gradient-to-r from-[#0B2E22] to-[#083528] px-5 py-4">
                                <p class="text-xs font-extrabold uppercase tracking-wider text-emerald-300">Official Identity</p>
                                <p class="text-base font-extrabold text-white mt-0.5"><?php echo htmlspecialchars($fullName); ?></p>
                            </div>
                            <div class="p-5 space-y-4">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center shrink-0 border border-emerald-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">System Role</p>
                                        <p class="text-sm font-extrabold text-slate-900"><?php echo htmlspecialchars($role); ?></p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center shrink-0 border border-blue-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 8l7.89 5.26a2 2 0 0 0 2.22 0L21 8M5 19h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2z"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Email Address</p>
                                        <p class="text-sm font-extrabold text-slate-900 truncate"><?php echo htmlspecialchars($email); ?></p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center shrink-0 border border-purple-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-purple-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-4.69-4.69 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 3.6 2.18h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Contact Number</p>
                                        <p class="text-sm font-extrabold text-slate-900"><?php echo htmlspecialchars($phone ?: 'Not set'); ?></p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center shrink-0 border border-amber-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black uppercase tracking-wider text-slate-400">Address / Zone</p>
                                        <p class="text-sm font-extrabold text-slate-900"><?php echo htmlspecialchars($address ?: 'Not set'); ?></p>
                                    </div>
                                </div>

                                <!-- Account Status -->
                                <div class="pt-4 border-t border-slate-100">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-extrabold text-slate-500 uppercase tracking-wider">Account Status</span>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-black border <?php echo $statusStyle['badge']; ?>">
                                            <span class="w-1.5 h-1.5 rounded-full animate-pulse" style="background:<?php echo $statusStyle['dot']; ?>"></span>
                                            <?php echo ucfirst($status); ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions Card -->
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5 space-y-2.5">
                            <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-400 mb-3">Quick Actions</h3>
                            <a href="<?php echo app_url('admin/auditLogs'); ?>" class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-100 hover:border-slate-200 transition group">
                                <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-700 flex items-center justify-center border border-purple-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-extrabold text-slate-800 group-hover:text-slate-900">View Audit Logs</p>
                                    <p class="text-[10px] text-slate-400">System forensic trail</p>
                                </div>
                            </a>
                            <a href="<?php echo app_url('admin/reports'); ?>" class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-100 hover:border-slate-200 transition group">
                                <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center border border-emerald-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-extrabold text-slate-800 group-hover:text-slate-900">Manage Reports</p>
                                    <p class="text-[10px] text-slate-400">Review &amp; verify reports</p>
                                </div>
                            </a>
                            <a href="<?php echo app_url('admin/schedule'); ?>" class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-100 hover:border-slate-200 transition group">
                                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center border border-amber-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-extrabold text-slate-800 group-hover:text-slate-900">Collection Schedule</p>
                                    <p class="text-[10px] text-slate-400">Manage waste routes</p>
                                </div>
                            </a>
                            <div class="pt-2 border-t border-slate-100">
                                <a href="<?php echo app_url('auth/logout'); ?>"
                                   class="flex items-center gap-3 p-3 rounded-xl bg-red-50 hover:bg-red-100 border border-red-100 hover:border-red-200 transition group">
                                    <div class="w-8 h-8 rounded-lg bg-red-100 text-red-700 flex items-center justify-center border border-red-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-xs font-extrabold text-red-700 group-hover:text-red-900">Sign Out</p>
                                        <p class="text-[10px] text-red-400">End current session</p>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <!-- Session Info Card -->
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs p-5">
                            <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-400 mb-4">Session & Access</h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-500">Current Session</span>
                                    <span class="text-xs font-extrabold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">Active</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-500">Access Level</span>
                                    <span class="text-xs font-extrabold text-slate-800"><?php echo htmlspecialchars($role); ?></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-500">Member Since</span>
                                    <span class="text-xs font-extrabold text-slate-800"><?php echo $memberSince; ?></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-500">IP Address</span>
                                    <span class="text-xs font-mono font-bold text-slate-600"><?php echo htmlspecialchars($_SERVER['REMOTE_ADDR'] ?? '127.0.0.1'); ?></span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-500">Current Time</span>
                                    <span class="text-xs font-mono font-bold text-slate-600" id="currentTimeDisplay"><?php echo date('h:i A'); ?></span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- RIGHT: Tabbed Settings Panels -->
                    <div class="lg:col-span-2 space-y-5">

                        <!-- Tab Navigation -->
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                            <div class="flex border-b border-slate-100 overflow-x-auto">
                                <button onclick="switchTab('personal')" id="tab-personal"
                                        class="tab-btn active flex items-center gap-2 px-5 py-3.5 text-xs font-bold text-slate-500 whitespace-nowrap">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    Personal Info
                                </button>
                                <button onclick="switchTab('security')" id="tab-security"
                                        class="tab-btn flex items-center gap-2 px-5 py-3.5 text-xs font-bold text-slate-500 whitespace-nowrap">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                    Security
                                </button>
                                <button onclick="switchTab('preferences')" id="tab-preferences"
                                        class="tab-btn flex items-center gap-2 px-5 py-3.5 text-xs font-bold text-slate-500 whitespace-nowrap">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9"/><path d="M10.3 21a1.94 1.94 0 0 0 3.4 0"/></svg>
                                    Notifications
                                </button>
                                <button onclick="switchTab('activity')" id="tab-activity"
                                        class="tab-btn flex items-center gap-2 px-5 py-3.5 text-xs font-bold text-slate-500 whitespace-nowrap">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                                    Activity
                                </button>
                            </div>

                            <!-- =========================================== -->
                            <!-- TAB 1: PERSONAL INFORMATION                 -->
                            <!-- =========================================== -->
                            <div id="panel-personal" class="tab-panel active p-6 sm:p-7">
                                <div class="mb-5">
                                    <h2 class="text-base font-extrabold text-slate-900">Personal Information</h2>
                                    <p class="text-xs text-slate-500 font-semibold mt-0.5">Update your administrative account contact details.</p>
                                </div>

                                <form id="profileForm" action="<?php echo app_url('admin/profile'); ?>" method="POST" enctype="multipart/form-data" class="space-y-5">
                                    <input id="profilePicInput2" type="file" name="profile_pic" accept="image/*" class="hidden" onchange="previewProfilePic(event)">

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <!-- Full Name -->
                                        <div>
                                            <label class="form-label">Full Name</label>
                                            <input type="text" name="name" id="profileName"
                                                   value="<?php echo htmlspecialchars($fullName); ?>" required
                                                   class="form-input"
                                                   placeholder="Enter your full name">
                                            <p id="nameError" class="mt-1 hidden text-xs font-bold text-red-500">Full name is required.</p>
                                        </div>

                                        <!-- System Role (readonly) -->
                                        <div>
                                            <label class="form-label">System Access Role</label>
                                            <div class="form-input flex items-center gap-2 readonly">
                                                <span class="w-1.5 h-1.5 rounded-full bg-[#10B981]"></span>
                                                <span><?php echo htmlspecialchars($role); ?></span>
                                                <span class="ml-auto text-[10px] text-slate-400 font-bold">READ ONLY</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Address -->
                                    <div>
                                        <label class="form-label">Home / Office Address</label>
                                        <input type="text" name="address" id="profileAddress"
                                               value="<?php echo htmlspecialchars($address); ?>"
                                               class="form-input"
                                               placeholder="Enter your address or zone/purok">
                                        <p id="addressError" class="mt-1 hidden text-xs font-bold text-red-500">Address is required.</p>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <!-- Email (disabled) -->
                                        <div>
                                            <label class="form-label">Email Address</label>
                                            <input type="email" id="profileEmail"
                                                   value="<?php echo htmlspecialchars($email); ?>" disabled
                                                   class="form-input readonly">
                                            <p class="text-[11px] text-slate-400 mt-1 font-semibold flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 inline-block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg> Cannot be changed for security.</p>
                                        </div>

                                        <!-- Contact Number -->
                                        <div>
                                            <label class="form-label">Contact Number</label>
                                            <div class="relative">
                                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400 font-mono">+63</span>
                                                <input type="tel" name="phone_number" id="profilePhone"
                                                       value="<?php echo htmlspecialchars($phone); ?>"
                                                       class="form-input pl-9"
                                                       placeholder="09XXXXXXXXX" maxlength="11">
                                            </div>
                                            <p id="phoneError" class="mt-1 hidden text-xs font-bold text-red-500">Valid 11-digit Philippine number required.</p>
                                        </div>
                                    </div>

                                    <!-- OTP Notice -->
                                    <div class="flex items-start gap-2 p-3.5 bg-amber-50 rounded-xl border border-amber-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-600 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                                        <p class="text-xs text-amber-700 font-semibold leading-relaxed">
                                            Changes to your <strong>full name</strong> or <strong>contact number</strong> require OTP email verification for security compliance.
                                        </p>
                                    </div>

                                    <!-- Metadata Strip -->
                                    <div class="pt-5 border-t border-slate-100 grid grid-cols-3 gap-4">
                                        <div>
                                            <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Account Status</p>
                                            <p class="text-sm font-extrabold text-slate-800 mt-1 flex items-center gap-1.5">
                                                <span class="w-1.5 h-1.5 rounded-full" style="background:<?php echo $statusStyle['dot']; ?>"></span>
                                                <?php echo ucfirst($status); ?>
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Access Since</p>
                                            <p class="text-sm font-extrabold text-slate-800 mt-1 font-mono"><?php echo $formattedDate; ?></p>
                                        </div>
                                        <div>
                                            <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Position</p>
                                            <p class="text-sm font-extrabold text-slate-800 mt-1"><?php echo htmlspecialchars($position); ?></p>
                                        </div>
                                    </div>

                                    <!-- Save -->
                                    <div class="flex justify-end pt-2">
                                        <button type="button" onclick="saveAllChanges()"
                                                class="inline-flex items-center gap-2 px-7 py-2.5 rounded-xl bg-[#0B2E22] hover:bg-[#093024] text-white font-extrabold text-sm shadow transition active:scale-[0.98] cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#10B981]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                            Save Changes
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- =========================================== -->
                            <!-- TAB 2: SECURITY / PASSWORD                  -->
                            <!-- =========================================== -->
                            <div id="panel-security" class="tab-panel p-6 sm:p-7">
                                <div class="mb-5">
                                    <h2 class="text-base font-extrabold text-slate-900">Security Settings</h2>
                                    <p class="text-xs text-slate-500 font-semibold mt-0.5">Manage your password and account security preferences.</p>
                                </div>

                                <!-- Password Requirements Banner -->
                                <div class="mb-5 p-4 bg-[#F0FDF4] rounded-xl border border-emerald-200">
                                    <p class="text-xs font-extrabold text-emerald-900 mb-2">Password Requirements</p>
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                                        <div class="flex items-center gap-1.5 text-[11px] font-bold text-slate-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                            Minimum 8 characters
                                        </div>
                                        <div class="flex items-center gap-1.5 text-[11px] font-bold text-slate-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                            One uppercase letter
                                        </div>
                                        <div class="flex items-center gap-1.5 text-[11px] font-bold text-slate-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                            Number &amp; special character
                                        </div>
                                    </div>
                                </div>

                                <form id="passwordForm" action="<?php echo app_url('admin/change_password'); ?>" method="POST" class="space-y-4">
                                    <!-- Current Password -->
                                    <div>
                                        <label class="form-label">Current Password</label>
                                        <div class="relative">
                                            <input type="password" name="current_password" id="currentPassword" required
                                                   class="form-input pr-11" placeholder="••••••••">
                                            <button type="button" onclick="togglePassword('currentPassword', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1 cursor-pointer">
                                                <span class="eye-open block"><svg viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg></span>
                                                <span class="eye-closed hidden"><svg viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg></span>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <!-- New Password -->
                                        <div>
                                            <label class="form-label">New Password</label>
                                            <div class="relative">
                                                <input type="password" name="new_password" id="newPassword" required minlength="8"
                                                       class="form-input pr-11" placeholder="••••••••"
                                                       oninput="checkPasswordStrength(this.value)">
                                                <button type="button" onclick="togglePassword('newPassword', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1 cursor-pointer">
                                                    <span class="eye-open block"><svg viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg></span>
                                                    <span class="eye-closed hidden"><svg viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg></span>
                                                </button>
                                            </div>

                                            <!-- Strength Bar -->
                                            <div id="strengthBarWrap" class="mt-2 hidden space-y-1.5">
                                                <div class="flex gap-1">
                                                    <div id="bar1" class="strength-bar flex-1 bg-slate-200"></div>
                                                    <div id="bar2" class="strength-bar flex-1 bg-slate-200"></div>
                                                    <div id="bar3" class="strength-bar flex-1 bg-slate-200"></div>
                                                    <div id="bar4" class="strength-bar flex-1 bg-slate-200"></div>
                                                </div>
                                                <p id="strengthLabel" class="text-[11px] font-bold text-slate-400">Password strength</p>
                                                <ul class="space-y-0.5 text-[11px]" id="passwordStrength">
                                                    <li id="rule-length" class="flex items-center gap-1.5 text-slate-400"><svg class="w-3 h-3 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/></svg> At least 8 characters</li>
                                                    <li id="rule-upper" class="flex items-center gap-1.5 text-slate-400"><svg class="w-3 h-3 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/></svg> One uppercase letter</li>
                                                    <li id="rule-number" class="flex items-center gap-1.5 text-slate-400"><svg class="w-3 h-3 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/></svg> Number &amp; special character</li>
                                                </ul>
                                            </div>
                                        </div>

                                        <!-- Confirm Password -->
                                        <div>
                                            <label class="form-label">Confirm New Password</label>
                                            <div class="relative">
                                                <input type="password" name="confirm_password" id="confirmPassword" required
                                                       class="form-input pr-11" placeholder="••••••••"
                                                       oninput="validateMatch()">
                                                <button type="button" onclick="togglePassword('confirmPassword', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1 cursor-pointer">
                                                    <span class="eye-open block"><svg viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg></span>
                                                    <span class="eye-closed hidden"><svg viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg></span>
                                                </button>
                                            </div>
                                            <p id="matchError" class="text-red-500 text-xs font-bold mt-1.5 hidden">Passwords do not match.</p>
                                            <p id="matchOk" class="text-emerald-600 text-xs font-bold mt-1.5 hidden flex items-center gap-1"><svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 inline-block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg> Passwords match!</p>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-2 p-3.5 bg-slate-50 rounded-xl border border-slate-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-500 shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                        <p class="text-xs text-slate-500 font-semibold">An audit log entry will be created upon updating your password for security compliance.</p>
                                    </div>

                                    <div class="flex justify-end pt-2">
                                        <button type="button" onclick="submitPasswordChange()"
                                                class="inline-flex items-center gap-2 px-7 py-2.5 rounded-xl bg-[#0B2E22] hover:bg-[#093024] text-white font-extrabold text-sm shadow transition active:scale-[0.98] cursor-pointer">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#10B981]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="11" x="3" y="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                            Update Password
                                        </button>
                                    </div>
                                </form>
                            </div>

                            <!-- =========================================== -->
                            <!-- TAB 3: NOTIFICATION PREFERENCES             -->
                            <!-- =========================================== -->
                            <div id="panel-preferences" class="tab-panel p-6 sm:p-7">
                                <div class="mb-5">
                                    <h2 class="text-base font-extrabold text-slate-900">Notification Preferences</h2>
                                    <p class="text-xs text-slate-500 font-semibold mt-0.5">Configure which alerts and notifications you receive.</p>
                                </div>

                                <div class="space-y-3">
                                    <?php
                                    $toggles = [
                                        ['key' => 'emergency_alerts',    'title' => 'Emergency Waste Alerts',       'desc' => 'Instant notifications for high-priority or hazardous waste incidents.',   'svg' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>'],
                                        ['key' => 'report_updates',      'title' => 'Citizen Report Updates',       'desc' => 'Alerts when new reports are submitted, verified, or resolved.',           'svg' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>'],
                                        ['key' => 'audit_notifications', 'title' => 'System Audit Log Alerts',     'desc' => 'Alerts on critical administrative security events and logins.',            'svg' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-purple-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>'],
                                        ['key' => 'announcement_drafts', 'title' => 'Announcement Draft Reminders','desc' => 'Notify when pending announcements are awaiting publishing approval.',       'svg' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 11 18-5v12L3 14v-3z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/></svg>'],
                                        ['key' => 'daily_digests',       'title' => 'Daily Report Digest',         'desc' => 'Receive daily summary digest of submitted and resolved waste reports.',    'svg' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>'],
                                    ];
                                    foreach ($toggles as $t): ?>
                                    <div class="flex items-start sm:items-center justify-between gap-4 p-4 rounded-xl bg-slate-50 border border-slate-100 hover:border-slate-200 hover:bg-white transition">
                                        <div class="flex items-start gap-3">
                                            <span class="mt-0.5"><?php echo $t['svg']; ?></span>
                                            <div>
                                                <p class="text-sm font-extrabold text-slate-800"><?php echo $t['title']; ?></p>
                                                <p class="text-xs text-slate-500 mt-0.5 font-medium"><?php echo $t['desc']; ?></p>
                                            </div>
                                        </div>
                                        <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                                            <input type="checkbox" class="sr-only peer" <?php echo $notifPrefs[$t['key']] ? 'checked' : ''; ?>>
                                            <div class="w-10 h-5 bg-slate-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-emerald-500/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                                        </label>
                                    </div>
                                    <?php endforeach; ?>
                                </div>

                                <div class="mt-5 flex justify-end">
                                    <button onclick="alert('Notification preferences saved!')" class="inline-flex items-center gap-2 px-7 py-2.5 rounded-xl bg-[#0B2E22] hover:bg-[#093024] text-white font-extrabold text-sm shadow transition cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#10B981]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                        Save Preferences
                                    </button>
                                </div>
                            </div>

                            <!-- =========================================== -->
                            <!-- TAB 4: ACTIVITY SUMMARY                     -->
                            <!-- =========================================== -->
                            <div id="panel-activity" class="tab-panel p-6 sm:p-7">
                                <div class="mb-5">
                                    <h2 class="text-base font-extrabold text-slate-900">Account Activity Summary</h2>
                                    <p class="text-xs text-slate-500 font-semibold mt-0.5">Overview of your administrative actions and system interactions.</p>
                                </div>

                                <!-- Activity Stats Grid -->
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
                                    <div class="p-4 bg-emerald-50 rounded-xl border border-emerald-200 text-center">
                                        <p class="text-2xl font-black text-emerald-900">—</p>
                                        <p class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-700 mt-1">Reports Reviewed</p>
                                    </div>
                                    <div class="p-4 bg-blue-50 rounded-xl border border-blue-200 text-center">
                                        <p class="text-2xl font-black text-blue-900">—</p>
                                        <p class="text-[10px] font-extrabold uppercase tracking-wider text-blue-700 mt-1">Announcements</p>
                                    </div>
                                    <div class="p-4 bg-purple-50 rounded-xl border border-purple-200 text-center">
                                        <p class="text-2xl font-black text-purple-900">—</p>
                                        <p class="text-[10px] font-extrabold uppercase tracking-wider text-purple-700 mt-1">Audit Events</p>
                                    </div>
                                    <div class="p-4 bg-amber-50 rounded-xl border border-amber-200 text-center">
                                        <p class="text-2xl font-black text-amber-900"><?php echo date('D'); ?></p>
                                        <p class="text-[10px] font-extrabold uppercase tracking-wider text-amber-700 mt-1">Active Today</p>
                                    </div>
                                </div>

                                <!-- Timeline Placeholder -->
                                <div class="space-y-2">
                                    <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-400 mb-3">Recent Admin Actions</h3>
                                    <?php
                                    $sampleActions = [
                                        ['svg' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="7.5" cy="15.5" r="5.5"/><path d="m21 2-9.6 9.6"/><path d="m15.5 7.5 3 3L22 7l-3-3"/></svg>', 'action' => 'Accessed Admin Profile', 'time' => 'Just now'],
                                        ['svg' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>', 'action' => 'Viewed System Audit Trail', 'time' => 'Earlier today'],
                                        ['svg' => '<svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-purple-700" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>', 'action' => 'System Access Verified',  'time' => $memberSince],
                                    ];
                                    foreach ($sampleActions as $act): ?>
                                    <div class="flex items-center gap-3 p-3.5 bg-slate-50 rounded-xl border border-slate-100">
                                        <span class="p-2 rounded-xl bg-slate-100 flex items-center justify-center shrink-0"><?php echo $act['svg']; ?></span>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-extrabold text-slate-800"><?php echo $act['action']; ?></p>
                                        </div>
                                        <span class="text-[10px] font-bold text-slate-400 whitespace-nowrap"><?php echo $act['time']; ?></span>
                                    </div>
                                    <?php endforeach; ?>

                                    <div class="text-center pt-3">
                                        <a href="<?php echo app_url('admin/auditLogs'); ?>" class="text-xs font-extrabold text-emerald-700 hover:text-emerald-900 underline underline-offset-2">
                                            View full audit trail →
                                        </a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- ============================================================ -->
<!-- OTP VERIFICATION MODAL                                        -->
<!-- ============================================================ -->
<div id="otpModal" class="fixed inset-0 bg-slate-950/70 backdrop-blur-xs hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full overflow-hidden border border-slate-200">
        <div class="bg-gradient-to-r from-[#0B2E22] to-[#083528] px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-emerald-500/20 text-white flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#10B981]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 8l7.89 5.26a2 2 0 0 0 2.22 0L21 8M5 19h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2z"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-extrabold text-white">Identity Verification</h3>
                    <p class="text-[11px] font-semibold text-emerald-300">OTP sent to your email</p>
                </div>
            </div>
            <button onclick="closeOTPModal()" class="text-emerald-300 hover:text-white transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <p class="text-xs text-slate-500 font-semibold text-center">Enter the 6-digit verification code sent to:</p>
            <p id="otpEmailDisplay" class="text-sm font-extrabold text-slate-900 text-center bg-slate-50 rounded-lg py-2 border border-slate-200"></p>
            <input type="text" id="otpInput" maxlength="6" placeholder="• • • • • •"
                   class="font-mono w-full px-4 py-3.5 text-center tracking-[0.5em] text-2xl font-extrabold border-2 border-slate-200 rounded-xl focus:border-[#10B981] focus:ring-4 focus:ring-emerald-500/10 outline-none transition">
            <div id="otpError" class="text-red-500 text-xs font-bold text-center hidden"></div>
            <div id="otpSuccess" class="text-emerald-600 text-xs font-bold text-center hidden"></div>
            <div class="grid grid-cols-2 gap-3">
                <button onclick="closeOTPModal()" class="px-4 py-2.5 border border-slate-200 text-slate-700 rounded-xl font-extrabold text-xs hover:bg-slate-50 transition cursor-pointer">Cancel</button>
                <button onclick="verifyOTP()" id="verifyOTPBtn" class="px-4 py-2.5 bg-[#10B981] text-white rounded-xl font-extrabold text-xs hover:bg-emerald-600 transition flex items-center justify-center gap-2 cursor-pointer">
                    <span id="verifyOTPText">Verify Identity</span>
                    <svg id="verifyOTPSpinner" class="animate-spin h-3.5 w-3.5 text-white hidden" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><circle class="opacity-25" cx="12" cy="12" r="10"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </button>
            </div>
            <div class="text-center">
                <button onclick="resendOTP()" id="resendOTPBtn" class="text-xs text-[#10B981] hover:text-emerald-700 font-extrabold underline underline-offset-2 cursor-pointer">Resend OTP Code</button>
            </div>
        </div>
    </div>
</div>

<!-- ============================================================ -->
<!-- JAVASCRIPT                                                   -->
<!-- ============================================================ -->
<script>
    // ---- Tab Switching ----
    function switchTab(name) {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
        document.getElementById('tab-' + name).classList.add('active');
        document.getElementById('panel-' + name).classList.add('active');
    }

    // ---- Password Visibility Toggle ----
    function togglePassword(inputId, btn) {
        const input = document.getElementById(inputId);
        const eyeOpen = btn.querySelector('.eye-open');
        const eyeClosed = btn.querySelector('.eye-closed');
        if (input.type === 'password') {
            input.type = 'text';
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        } else {
            input.type = 'password';
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        }
    }

    // ---- Profile Picture Preview ----
    function previewProfilePic(event) {
        const file = event.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(e) {
            const container = document.getElementById('avatarContainer');
            container.innerHTML = `<img src="${e.target.result}" alt="Profile Preview" class="w-full h-full object-cover">`;
            document.getElementById('photoPendingNotice')?.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }

    // ---- Password Strength ----
    function checkPasswordStrength(val) {
        const wrap = document.getElementById('strengthBarWrap');
        if (val.length > 0) {
            wrap.classList.remove('hidden');
        } else {
            wrap.classList.add('hidden');
            return;
        }

        const hasUpper = /[A-Z]/.test(val);
        const hasNumber = /[0-9]/.test(val);
        const hasSpecial = /[!@#$%^&*]/.test(val);
        const hasLength = val.length >= 8;

        let score = [hasLength, hasUpper, hasNumber, hasSpecial].filter(Boolean).length;

        const bars = ['bar1','bar2','bar3','bar4'];
        const colors = ['bg-red-400','bg-amber-400','bg-yellow-400','bg-emerald-500'];
        const labels = ['Very Weak','Weak','Moderate','Strong'];
        const textColors = ['text-red-500','text-amber-500','text-yellow-600','text-emerald-600'];

        bars.forEach((id, i) => {
            const el = document.getElementById(id);
            el.className = 'strength-bar flex-1 ' + (i < score ? colors[score - 1] : 'bg-slate-200');
        });

        const label = document.getElementById('strengthLabel');
        label.textContent = score > 0 ? labels[score - 1] : 'Password strength';
        label.className = 'text-[11px] font-bold ' + (score > 0 ? textColors[score - 1] : 'text-slate-400');

        // Rule checkers
        const rules = { 'rule-length': hasLength, 'rule-upper': hasUpper, 'rule-number': hasNumber && hasSpecial };
        Object.keys(rules).forEach(id => {
            const el = document.getElementById(id);
            const svg = el.querySelector('svg');
            if (rules[id]) {
                el.className = 'flex items-center gap-1.5 text-emerald-600 font-bold text-[11px]';
                svg.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>';
            } else {
                el.className = 'flex items-center gap-1.5 text-slate-400 text-[11px]';
                svg.innerHTML = '<circle cx="12" cy="12" r="10"/>';
            }
        });

        validateMatch();
    }

    // ---- Password Match ----
    function validateMatch() {
        const pass = document.getElementById('newPassword').value;
        const confirm = document.getElementById('confirmPassword').value;
        const error = document.getElementById('matchError');
        const ok = document.getElementById('matchOk');

        if (confirm.length > 0 && pass !== confirm) {
            error.classList.remove('hidden');
            ok.classList.add('hidden');
            return false;
        } else if (confirm.length > 0 && pass === confirm) {
            error.classList.add('hidden');
            ok.classList.remove('hidden');
            return true;
        } else {
            error.classList.add('hidden');
            ok.classList.add('hidden');
        }
    }

    // ---- Submit Password Change ----
    function submitPasswordChange() {
        const currentPass = document.getElementById('currentPassword').value;
        const newPass = document.getElementById('newPassword').value;
        const confirmPass = document.getElementById('confirmPassword').value;
        if (!currentPass) { alert('Please enter your current password.'); return; }
        if (newPass.length < 8 || !/[A-Z]/.test(newPass) || !/[0-9]/.test(newPass) || !/[!@#$%^&*]/.test(newPass)) {
            alert('New password does not meet the security requirements.'); return;
        }
        if (newPass !== confirmPass) { alert('Passwords do not match.'); return; }
        document.getElementById('passwordForm').submit();
    }

    // ---- Save All Changes (Personal Info) ----
    function saveAllChanges() {
        const name = document.getElementById('profileName');
        const address = document.getElementById('profileAddress');
        const phone = document.getElementById('profilePhone');
        const photoInput = document.getElementById('profilePicInput');
        let valid = true;

        if (!name.value.trim()) {
            document.getElementById('nameError').classList.remove('hidden');
            name.classList.add('border-red-400');
            valid = false;
        } else {
            document.getElementById('nameError').classList.add('hidden');
            name.classList.remove('border-red-400');
        }

        if (phone) {
            const phoneVal = phone.value.trim();
            if (phoneVal && !/^09\d{9}$/.test(phoneVal)) {
                document.getElementById('phoneError').classList.remove('hidden');
                phone.classList.add('border-red-400');
                valid = false;
            } else {
                document.getElementById('phoneError').classList.add('hidden');
                phone.classList.remove('border-red-400');
            }
        }

        if (!valid) return;

        const originalName = '<?php echo htmlspecialchars($fullName, ENT_QUOTES); ?>';
        const originalPhone = '<?php echo htmlspecialchars($phone, ENT_QUOTES); ?>';
        const nameChanged = name.value.trim() !== originalName;
        const phoneChanged = phone && phone.value.trim() !== originalPhone;

        if (nameChanged || phoneChanged) {
            requestOTP();
            return;
        }

        document.getElementById('profileForm').submit();
    }

    // ---- OTP ----
    function requestOTP() {
        fetch('<?php echo app_url('admin/requestProfileOTP'); ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById('otpEmailDisplay').textContent = '<?php echo htmlspecialchars($email); ?>';
                document.getElementById('otpModal').classList.remove('hidden');
                document.getElementById('otpInput').focus();
            } else {
                alert(data.message || 'Failed to send OTP.');
            }
        })
        .catch(() => alert('An error occurred. Please try again.'));
    }

    function closeOTPModal() {
        document.getElementById('otpModal').classList.add('hidden');
        document.getElementById('otpInput').value = '';
        document.getElementById('otpError').classList.add('hidden');
        document.getElementById('otpSuccess').classList.add('hidden');
    }

    function verifyOTP() {
        const otp = document.getElementById('otpInput').value.trim();
        const btn = document.getElementById('verifyOTPBtn');
        const text = document.getElementById('verifyOTPText');
        const spinner = document.getElementById('verifyOTPSpinner');

        if (otp.length !== 6) {
            document.getElementById('otpError').textContent = 'Please enter the 6-digit code.';
            document.getElementById('otpError').classList.remove('hidden');
            return;
        }

        text.textContent = 'Verifying...';
        spinner.classList.remove('hidden');
        btn.disabled = true;

        fetch('<?php echo app_url('admin/verifyProfileOTP'); ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'otp=' + encodeURIComponent(otp)
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            spinner.classList.add('hidden');
            text.textContent = 'Verify Identity';
            if (data.success) {
                document.getElementById('otpSuccess').textContent = 'Verified! Saving changes...';
                document.getElementById('otpSuccess').classList.remove('hidden');
                document.getElementById('otpError').classList.add('hidden');
                setTimeout(() => {
                    closeOTPModal();
                    document.getElementById('profileForm').submit();
                }, 1200);
            } else {
                document.getElementById('otpError').textContent = data.message || 'Invalid or expired code.';
                document.getElementById('otpError').classList.remove('hidden');
            }
        })
        .catch(() => {
            btn.disabled = false;
            spinner.classList.add('hidden');
            text.textContent = 'Verify Identity';
            alert('An error occurred. Please try again.');
        });
    }

    let adminOtpCooldownTimer = null;
    function resendOTP() {
        const btn = document.getElementById('resendOTPBtn');
        btn.disabled = true;
        btn.textContent = 'Sending...';
        requestOTP();
        
        let remaining = 60;
        if (adminOtpCooldownTimer) clearInterval(adminOtpCooldownTimer);

        adminOtpCooldownTimer = setInterval(() => {
            remaining--;
            if (remaining > 0) {
                btn.disabled = true;
                btn.textContent = `Resend Code in (${remaining}s)`;
            } else {
                clearInterval(adminOtpCooldownTimer);
                btn.disabled = false;
                btn.textContent = 'Resend OTP Code';
            }
        }, 1000);
    }

    // ---- Live Clock ----
    function updateClock() {
        const now = new Date();
        const h = now.getHours() % 12 || 12;
        const m = String(now.getMinutes()).padStart(2, '0');
        const s = String(now.getSeconds()).padStart(2, '0');
        const ampm = now.getHours() >= 12 ? 'PM' : 'AM';
        const el = document.getElementById('currentTimeDisplay');
        if (el) el.textContent = `${h}:${m}:${s} ${ampm}`;
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Auto-dismiss flash messages
    setTimeout(() => {
        document.querySelectorAll('[id="errorMsg"], [id="successMsg"]').forEach(el => {
            el.style.transition = 'opacity 0.5s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        });
    }, 5000);
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
