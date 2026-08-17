<?php include __DIR__ . '/../layouts/header.php'; ?>

$sysLogoUrl = format_asset_url($data['barangay']['system_logo'] ?? '');
$brgyLogoUrl = format_asset_url($data['barangay']['barangay_logo'] ?? '');
?>

<style>
    body, * { font-family: 'Miranda Sans', sans-serif !important; font-optical-sizing: auto; }
    .mobile-sidebar-open #mobileSidebar { transform: translateX(0) !important; }
    #mobileSidebarOverlay { transition: opacity 0.25s ease, visibility 0.25s ease; opacity: 0; visibility: hidden; }
    .mobile-sidebar-open #mobileSidebarOverlay { opacity: 1; visibility: visible; }
</style>

<div class="min-h-screen bg-white text-slate-900 w-full flex font-sans antialiased">
    
    <!-- Mobile Sidebar Overlay -->
    <div id="mobileSidebarOverlay" class="fixed inset-0 bg-slate-950/40 z-40 lg:hidden"></div>

    <!-- Layout Wrapper -->
    <div class="lg:flex lg:min-h-screen w-full">
        
        <!-- Sidebar Layout Component -->
        <?php include __DIR__ . '/../layouts/admin_sidebar.php'; ?>

        <!-- Main Workspace -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            
            <!-- Top App Bar Component -->
            <?php include __DIR__ . '/../layouts/admin_topbar.php'; ?>

            <!-- Main Scrollable Canvas -->
            <main class="flex-1 overflow-y-auto">
                <div class="max-w-9xl mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

                    <!-- Page Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 sm:p-8 rounded-2xl border border-slate-250 shadow-xs">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <a href="/brgy-waste-app-v3/public/settings" class="text-sm font-extrabold text-slate-500 hover:text-emerald-700 transition">Settings Hub</a>
                                <span class="text-sm text-slate-300">/</span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-extrabold bg-emerald-100 text-emerald-900 border border-emerald-300">
                                    Branding &amp; Barangay Profile
                                </span>
                            </div>
                            <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                                System Branding &amp; Barangay Profile
                            </h1>
                            <p class="text-base sm:text-lg text-slate-600 font-semibold mt-1">
                                Customize the system application logo, brand name, tagline, official barangay seal, and LGU contact details.
                            </p>
                        </div>

                    </div>

                    <!-- Alerts -->
                    <?php if (!empty($data['error'])): ?>
                        <div class="p-5 bg-red-50 border-2 border-red-200 text-red-950 rounded-2xl text-base font-extrabold flex items-center gap-3 shadow-xs">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <span><?php echo htmlspecialchars($data['error']); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($data['success'])): ?>
                        <div class="p-5 bg-emerald-50 border-2 border-emerald-200 text-emerald-950 rounded-2xl text-base font-extrabold flex items-center gap-3 shadow-xs">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-600 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                            <span><?php echo htmlspecialchars($data['success']); ?></span>
                        </div>
                    <?php endif; ?>

                    <!-- Content Layout -->
                    <div class="flex flex-col lg:flex-row gap-6">
                        <?php 
                        $activeTab = 'barangay'; 
                        include __DIR__ . '/../layouts/settings_sidebar.php'; 
                        ?>

                        <!-- Main Form Card -->
                        <div class="flex-1 min-w-0 space-y-6">
                            
                            <form action="/brgy-waste-app-v3/public/settings/barangay" method="POST" enctype="multipart/form-data" class="space-y-6">
                                
                                <!-- SECTION 1: SYSTEM BRANDING & LOGO CUSTOMIZATION -->
                                <div class="bg-white rounded-2xl border-2 border-slate-250 p-6 sm:p-8 shadow-xs space-y-6">
                                    <div class="border-b border-slate-200 pb-5 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                        <div>
                                            <h2 class="text-xl font-extrabold text-slate-900 flex items-center gap-2.5">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"/><path d="M2 12h20"/></svg>
                                                System Customization &amp; Branding
                                            </h2>
                                            <p class="text-sm text-slate-600 font-semibold mt-1">Configure the application title, brand logo, and system tagline shown across the platform.</p>
                                        </div>
                                        <span class="px-3.5 py-1.5 rounded-full bg-emerald-100 text-emerald-950 font-extrabold text-xs sm:text-sm border border-emerald-300 self-start sm:self-auto">
                                            Application Identity
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        
                                        <!-- System Full Name -->
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-extrabold text-slate-900 mb-2">System Full Name <span class="text-red-600">*</span></label>
                                            <input type="text" name="system_name" id="inputSysName" value="<?php echo htmlspecialchars($data['barangay']['system_name'] ?? 'Barangay Waste Management System'); ?>" required
                                                   class="w-full px-4 py-3.5 bg-slate-50 border-2 border-slate-250 rounded-xl text-base font-bold text-slate-900 focus:bg-white focus:border-[#10B981] focus:ring-4 focus:ring-emerald-500/10 outline-none transition"
                                                   oninput="updateBrandingPreview()">
                                            <p class="text-xs text-slate-500 font-semibold mt-1.5">Primary system title shown on login pages, document headers, and public portals.</p>
                                        </div>

                                        <!-- System Short Name / Brand -->
                                        <div>
                                            <label class="block text-sm font-extrabold text-slate-900 mb-2">System Short Brand Name <span class="text-red-600">*</span></label>
                                            <input type="text" name="system_short_name" id="inputShortName" value="<?php echo htmlspecialchars($data['barangay']['system_short_name'] ?? 'WasteWatch'); ?>" required
                                                   class="w-full px-4 py-3.5 bg-slate-50 border-2 border-slate-250 rounded-xl text-base font-bold text-slate-900 focus:bg-white focus:border-[#10B981] focus:ring-4 focus:ring-emerald-500/10 outline-none transition"
                                                   oninput="updateBrandingPreview()">
                                            <p class="text-xs text-slate-500 font-semibold mt-1.5">Short name shown on top navigation bars and mobile headers.</p>
                                        </div>

                                        <!-- System Dynamic Location Subtitle / Tagline Info -->
                                        <div class="p-4 bg-emerald-50/60 border-2 border-emerald-200/80 rounded-xl text-emerald-950">
                                            <div class="flex items-center gap-2">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-700 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                                <span class="text-xs font-extrabold uppercase tracking-wider text-emerald-800">System Location Subtitle</span>
                                            </div>
                                            <p class="text-xs text-emerald-900 font-medium mt-1">
                                                The system tagline is automatically formulated from your official location: <strong id="previewLocationSubtitleInline" class="font-bold">Barangay <?php echo htmlspecialchars($data['barangay']['barangay_name'] ?? 'Dulong Bayan'); ?>, <?php echo htmlspecialchars($data['barangay']['municipality'] ?? 'Talavera'); ?>, <?php echo htmlspecialchars($data['barangay']['province'] ?? 'Nueva Ecija'); ?></strong>.
                                            </p>
                                        </div>

                                        <!-- LOGO UPLOADS -->
                                        <!-- 1. System Logo -->
                                        <div class="p-5 bg-slate-50 rounded-2xl border-2 border-slate-250 space-y-4">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <label class="block text-sm font-extrabold text-slate-900">System Application Logo</label>
                                                    <p class="text-xs text-slate-500 font-semibold mt-0.5">PNG, JPG, WEBP or SVG (Max 5MB)</p>
                                                </div>
                                                <span class="text-xs font-bold text-slate-500 bg-slate-200 px-2.5 py-1 rounded-md">Nav &amp; Topbar</span>
                                            </div>

                                            <div class="flex items-center gap-4">
                                                <div class="h-16 w-16 rounded-full bg-white border-2 border-emerald-500/40 flex items-center justify-center overflow-hidden shrink-0 shadow-sm">
                                                    <?php if (!empty($sysLogoUrl)): ?>
                                                        <img id="prevSysLogo" src="<?php echo htmlspecialchars($sysLogoUrl); ?>" class="h-full w-full object-cover">
                                                    <?php else: ?>
                                                        <div id="prevSysLogoPlaceholder" class="flex h-full w-full items-center justify-center rounded-full bg-emerald-100 text-emerald-800">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-emerald-800" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                                                        </div>
                                                        <img id="prevSysLogo" src="" class="h-full w-full object-cover hidden">
                                                    <?php endif; ?>
                                                </div>
                                                <div class="flex-1">
                                                    <input type="file" name="system_logo" id="systemLogoInput" accept="image/*" class="hidden" onchange="previewImage(this, 'prevSysLogo', 'prevSysLogoPlaceholder', 'previewSysLogoImg', 'previewSysLogoIcon')">
                                                    <label for="systemLogoInput" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-extrabold cursor-pointer transition shadow-xs">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                                        Upload New Logo
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- 2. Barangay Seal Logo -->
                                        <div class="p-5 bg-slate-50 rounded-2xl border-2 border-slate-250 space-y-4">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <label class="block text-sm font-extrabold text-slate-900">Barangay Official Seal</label>
                                                    <p class="text-xs text-slate-500 font-semibold mt-0.5">PNG, JPG, WEBP or SVG (Max 5MB)</p>
                                                </div>
                                                <span class="text-xs font-bold text-slate-500 bg-slate-200 px-2.5 py-1 rounded-md">Official Documents</span>
                                            </div>

                                            <div class="flex items-center gap-4">
                                                <div class="h-16 w-16 rounded-full bg-white border-2 border-amber-500/40 flex items-center justify-center overflow-hidden shrink-0 shadow-sm">
                                                    <?php if (!empty($brgyLogoUrl)): ?>
                                                        <img id="prevBrgySeal" src="<?php echo htmlspecialchars($brgyLogoUrl); ?>" class="h-full w-full object-cover">
                                                    <?php else: ?>
                                                        <div id="prevBrgySealPlaceholder" class="flex h-full w-full items-center justify-center rounded-full bg-amber-100 text-amber-800">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-amber-800" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18M3 10h18M5 10v11M9 10v11M15 10v11M19 10v11M12 2L2 7h20L12 2z"/></svg>
                                                        </div>
                                                        <img id="prevBrgySeal" src="" class="h-full w-full object-cover hidden">
                                                    <?php endif; ?>
                                                </div>
                                                <div class="flex-1">
                                                    <input type="file" name="barangay_logo" id="barangayLogoInput" accept="image/*" class="hidden" onchange="previewImage(this, 'prevBrgySeal', 'prevBrgySealPlaceholder')">
                                                    <label for="barangayLogoInput" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-extrabold cursor-pointer transition shadow-xs">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                                                        Upload Barangay Seal
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <!-- LIVE BRANDING PREVIEW CARD -->
                                <div class="bg-slate-900 rounded-2xl p-6 text-white space-y-4 shadow-sm border border-slate-800">
                                    <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                                        <span class="text-xs font-extrabold text-emerald-400 uppercase tracking-wider">
                                            Live Header &amp; Topbar Branding Preview
                                        </span>
                                        <span class="text-xs font-mono text-slate-400">Real-time Preview</span>
                                    </div>
                                    <div class="flex items-center justify-between bg-slate-950 p-4 rounded-xl border border-slate-800">
                                        <div class="flex items-center gap-3">
                                            <div class="h-11 w-11 rounded-full bg-[#083528] text-white flex items-center justify-center font-extrabold text-lg overflow-hidden border-2 border-emerald-500/40 shrink-0">
                                                <?php if (!empty($sysLogoUrl)): ?>
                                                    <img id="previewSysLogoImg" src="<?php echo htmlspecialchars($sysLogoUrl); ?>" class="h-full w-full object-cover">
                                                <?php else: ?>
                                                    <span id="previewSysLogoIcon" class="flex items-center justify-center"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18M3 10h18M5 10v11M9 10v11M15 10v11M19 10v11M12 2L2 7h20L12 2z"/></svg></span>
                                                    <img id="previewSysLogoImg" src="" class="h-full w-full object-cover hidden">
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <p id="previewShortName" class="text-base font-extrabold text-white leading-tight">
                                                    <?php echo htmlspecialchars($data['barangay']['system_short_name'] ?? 'WasteWatch'); ?>
                                                </p>
                                                <p id="previewMotto" class="text-xs font-semibold text-emerald-400/90 leading-tight mt-0.5">
                                                    Barangay <?php echo htmlspecialchars($data['barangay']['barangay_name'] ?? 'Dulong Bayan'); ?>, <?php echo htmlspecialchars($data['barangay']['municipality'] ?? 'Talavera'); ?>, <?php echo htmlspecialchars($data['barangay']['province'] ?? 'Nueva Ecija'); ?>
                                                </p>
                                            </div>
                                        </div>
                                        <span class="px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-extrabold border border-emerald-500/30">
                                            Active System
                                        </span>
                                    </div>
                                </div>

                                <!-- SECTION 2: OFFICIAL BARANGAY DETAILS -->
                                <div class="bg-white rounded-2xl border-2 border-slate-250 p-6 sm:p-8 shadow-xs space-y-6">
                                    <div class="border-b border-slate-200 pb-5 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                        <div>
                                            <h2 class="text-xl font-extrabold text-slate-900 flex items-center gap-2.5">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"/><path d="M3 7v14"/><path d="M21 7v14"/><path d="M6 18h12"/><path d="M6 14h12"/><path d="M6 10h12"/><path d="M12 3L2 7h20L12 3z"/></svg>
                                                Official Barangay Details
                                            </h2>
                                            <p class="text-sm text-slate-600 font-semibold mt-1">This information will be displayed on official reports, notices, and public exports.</p>
                                        </div>
                                        <span class="px-4 py-1.5 rounded-full bg-slate-100 text-slate-800 font-extrabold text-xs sm:text-sm border border-slate-300 self-start sm:self-auto">
                                            LGU Active Profile
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        
                                        <!-- Barangay Name -->
                                        <div>
                                            <label class="block text-sm font-extrabold text-slate-900 mb-2">Barangay Name <span class="text-red-600">*</span></label>
                                            <input type="text" name="barangay_name" id="inputBrgyName" value="<?php echo htmlspecialchars($data['barangay']['barangay_name'] ?? ''); ?>" required
                                                   class="w-full px-4 py-3.5 bg-slate-50 border-2 border-slate-250 rounded-xl text-base font-bold text-slate-900 focus:bg-white focus:border-[#10B981] focus:ring-4 focus:ring-emerald-500/10 outline-none transition"
                                                   oninput="updateBrandingPreview()">
                                        </div>

                                        <!-- Municipality / City -->
                                        <div>
                                            <label class="block text-sm font-extrabold text-slate-900 mb-2">Municipality / City <span class="text-red-600">*</span></label>
                                            <input type="text" name="municipality" id="inputMuni" value="<?php echo htmlspecialchars($data['barangay']['municipality'] ?? ''); ?>" required
                                                   class="w-full px-4 py-3.5 bg-slate-50 border-2 border-slate-250 rounded-xl text-base font-bold text-slate-900 focus:bg-white focus:border-[#10B981] focus:ring-4 focus:ring-emerald-500/10 outline-none transition"
                                                   oninput="updateBrandingPreview()">
                                        </div>

                                        <!-- Province -->
                                        <div>
                                            <label class="block text-sm font-extrabold text-slate-900 mb-2">Province <span class="text-red-600">*</span></label>
                                            <input type="text" name="province" id="inputProv" value="<?php echo htmlspecialchars($data['barangay']['province'] ?? ''); ?>" required
                                                   class="w-full px-4 py-3.5 bg-slate-50 border-2 border-slate-250 rounded-xl text-base font-bold text-slate-900 focus:bg-white focus:border-[#10B981] focus:ring-4 focus:ring-emerald-500/10 outline-none transition"
                                                   oninput="updateBrandingPreview()">
                                        </div>

                                        <!-- Region -->
                                        <div>
                                            <label class="block text-sm font-extrabold text-slate-900 mb-2">Region <span class="text-red-600">*</span></label>
                                            <input type="text" name="region" value="<?php echo htmlspecialchars($data['barangay']['region'] ?? ''); ?>" required
                                                   class="w-full px-4 py-3.5 bg-slate-50 border-2 border-slate-250 rounded-xl text-base font-bold text-slate-900 focus:bg-white focus:border-[#10B981] focus:ring-4 focus:ring-emerald-500/10 outline-none transition">
                                        </div>

                                        <!-- Address -->
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-extrabold text-slate-900 mb-2">Official Barangay Hall Address <span class="text-red-600">*</span></label>
                                            <input type="text" name="official_address" value="<?php echo htmlspecialchars($data['barangay']['official_address'] ?? ''); ?>" required
                                                   class="w-full px-4 py-3.5 bg-slate-50 border-2 border-slate-250 rounded-xl text-base font-bold text-slate-900 focus:bg-white focus:border-[#10B981] focus:ring-4 focus:ring-emerald-500/10 outline-none transition">
                                        </div>

                                        <!-- Contact Number -->
                                        <div>
                                            <label class="block text-sm font-extrabold text-slate-900 mb-2">Contact Telephone / Mobile</label>
                                            <input type="text" name="contact_number" value="<?php echo htmlspecialchars($data['barangay']['contact_number'] ?? ''); ?>"
                                                   class="w-full px-4 py-3.5 bg-slate-50 border-2 border-slate-250 rounded-xl text-base font-bold font-mono text-slate-900 focus:bg-white focus:border-[#10B981] focus:ring-4 focus:ring-emerald-500/10 outline-none transition">
                                        </div>

                                        <!-- Email Address -->
                                        <div>
                                            <label class="block text-sm font-extrabold text-slate-900 mb-2">Official Email Address</label>
                                            <input type="email" name="official_email" value="<?php echo htmlspecialchars($data['barangay']['official_email'] ?? ''); ?>"
                                                   class="w-full px-4 py-3.5 bg-slate-50 border-2 border-slate-250 rounded-xl text-base font-bold text-slate-900 focus:bg-white focus:border-[#10B981] focus:ring-4 focus:ring-emerald-500/10 outline-none transition">
                                        </div>
                                    </div>

                                    <!-- Footer Action -->
                                    <div class="pt-6 border-t border-slate-200 flex items-center gap-4">
                                        <button type="submit" class="px-8 py-4 rounded-xl bg-[#083528] hover:bg-[#06291f] text-white font-extrabold text-base shadow-sm hover:shadow-md transition flex items-center gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                            Save Barangay Information
                                        </button>
                                        <a href="/brgy-waste-app-v3/public/settings" class="px-7 py-4 bg-slate-100 hover:bg-slate-200 text-slate-800 text-base font-extrabold rounded-xl transition border border-slate-250">
                                            Cancel
                                        </a>
                                    </div>
                                </div>

                            </form>
                        </div>

                    </div>

                </div>
            </main>
        </div>
    </div>
</div>

<script>
function updateBrandingPreview() {
    const shortName = document.getElementById('inputShortName') ? document.getElementById('inputShortName').value : '';
    const brgyName = document.getElementById('inputBrgyName') ? document.getElementById('inputBrgyName').value : 'Dulong Bayan';
    const muni = document.getElementById('inputMuni') ? document.getElementById('inputMuni').value : 'Talavera';
    const prov = document.getElementById('inputProv') ? document.getElementById('inputProv').value : 'Nueva Ecija';

    const locationText = `Barangay ${brgyName || 'Dulong Bayan'}, ${muni || 'Talavera'}, ${prov || 'Nueva Ecija'}`;

    if (shortName && document.getElementById('previewShortName')) {
        document.getElementById('previewShortName').textContent = shortName;
    }
    if (document.getElementById('previewMotto')) {
        document.getElementById('previewMotto').textContent = locationText;
    }
    if (document.getElementById('previewLocationSubtitleInline')) {
        document.getElementById('previewLocationSubtitleInline').textContent = locationText;
    }
}

function previewImage(input, imgId, placeholderId, livePreviewImgId, livePreviewIconId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.getElementById(imgId);
            const placeholder = document.getElementById(placeholderId);
            if (img) {
                img.src = e.target.result;
                img.classList.remove('hidden');
            }
            if (placeholder) {
                placeholder.classList.add('hidden');
            }
            if (livePreviewImgId) {
                const liveImg = document.getElementById(livePreviewImgId);
                const liveIcon = document.getElementById(livePreviewIconId);
                if (liveImg) {
                    liveImg.src = e.target.result;
                    liveImg.classList.remove('hidden');
                }
                if (liveIcon) {
                    liveIcon.classList.add('hidden');
                }
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?>